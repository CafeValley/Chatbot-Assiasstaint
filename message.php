<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

/**
 * Check if the current request is from an authenticated admin
 * Admins can authenticate via:
 * 1. Session (logged in via admin panel)
 * 2. Auth header (for API access)
 * @return bool
 */
function isAdminAuthenticated() {
    // Check session-based admin auth
    if (isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true) {
        return true;
    }
    
    // Check header-based auth (for API/AJAX from admin panel)
    if (isset($_POST['admin_auth'])) {
        $auth = $_POST['admin_auth'];
        $decoded = base64_decode($auth);
        if ($decoded === 'admin:admin') {
            return true;
        }
    }
    
    // Check Authorization header
    $headers = function_exists('getallheaders') ? getallheaders() : [];
    if (isset($headers['Authorization'])) {
        $auth = str_replace('Basic ', '', $headers['Authorization']);
        $decoded = base64_decode($auth);
        if ($decoded === 'admin:admin') {
            return true;
        }
    }
    
    return false;
}

/**
 * Return error for admin-only commands
 */
function adminOnlyError() {
    echo json_encode([
        'ok' => false, 
        'error' => 'This command requires admin authentication. Please use the admin panel.',
        'admin_required' => true
    ]);
    exit;
}

/**
 * Normalize text for consistent matching
 * - Converts to lowercase
 * - Removes punctuation and special characters
 * - Trims whitespace
 * - Handles Unicode properly
 * 
 * @param string $text Input text
 * @return string Normalized text
 */
function normalizeText($text) {
    if ($text === null || $text === '') {
        return '';
    }
    // Convert to lowercase (Unicode-aware)
    $text = function_exists('mb_strtolower') ? mb_strtolower($text, 'UTF-8') : strtolower($text);
    // Remove punctuation and special characters, keep letters, numbers, spaces
    $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
    // Collapse multiple spaces into one
    $text = preg_replace('/\s+/', ' ', $text);
    // Trim whitespace
    return trim($text);
}

/**
 * Check if intent-based tables exist
 * @return bool
 */
function hasIntentTables($conn) {
    $result = mysqli_query($conn, "SHOW TABLES LIKE 'intents'");
    return $result && mysqli_num_rows($result) > 0;
}

// Initialize conversation history in session if not exists
if (!isset($_SESSION['conversation_history'])) {
    $_SESSION['conversation_history'] = [];
}

// Extract raw text early for command handling
$getMesgRaw = isset($_POST['text']) ? trim((string)$_POST['text']) : '';

// Get conversation context (last N messages, excluding commands)
$getContext = isset($_POST['context']) ? (int)$_POST['context'] : 3; // Default: last 3 messages
$contextMessages = [];
if (isset($_SESSION['conversation_history']) && is_array($_SESSION['conversation_history'])) {
    $history = $_SESSION['conversation_history'];
    $contextMessages = array_slice($history, -$getContext, $getContext);
}

// Command: replay_with: <reply> (ADMIN ONLY)
if ($getMesgRaw !== '' && preg_match('/^\s*replay_with\s*:(.*)$/i', $getMesgRaw, $m)) {
    if (!isAdminAuthenticated()) { adminOnlyError(); }
    $reply = trim($m[1]);
    if ($reply === '') {
        echo json_encode([ 'ok' => false, 'error' => 'Empty reply in replay_with' ]);
        exit;
    }
    // Find the most recent learning sample
    $stmt = mysqli_prepare($conn, 'SELECT id, queries FROM learning ORDER BY id DESC LIMIT 1');
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $learning = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if (!$learning) {
        echo json_encode([ 'ok' => false, 'error' => 'No pending learning sample found' ]);
        exit;
    }

    // Check if using intent-based system
    if (hasIntentTables($conn)) {
        // Create a quick intent from the query
        $query = $learning['queries'];
        $intentName = preg_replace('/[^a-zA-Z0-9\s]/', '', $query);
        $intentName = preg_replace('/\s+/', '_', trim($intentName));
        $intentName = substr($intentName, 0, 50);
        if ($intentName === '') {
            $intentName = 'quick_' . $learning['id'];
        }
        
        // Check if intent exists
        $checkStmt = mysqli_prepare($conn, "SELECT id FROM intents WHERE name = ?");
        mysqli_stmt_bind_param($checkStmt, 's', $intentName);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);
        $existingIntent = mysqli_fetch_assoc($checkResult);
        mysqli_stmt_close($checkStmt);
        
        $intentId = null;
        if ($existingIntent) {
            $intentId = (int)$existingIntent['id'];
        } else {
            // Create intent
            $desc = 'Quick-trained via replay_with command';
            $insertIntent = mysqli_prepare($conn, "INSERT INTO intents (name, description) VALUES (?, ?)");
            mysqli_stmt_bind_param($insertIntent, 'ss', $intentName, $desc);
            if (mysqli_stmt_execute($insertIntent)) {
                $intentId = mysqli_insert_id($conn);
            }
            mysqli_stmt_close($insertIntent);
        }
        
        if ($intentId) {
            // Add training phrase
            $normalized = normalizeText($query);
            $insertPhrase = mysqli_prepare($conn, "INSERT INTO training_phrases (intent_id, phrase, phrase_normalized) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($insertPhrase, 'iss', $intentId, $query, $normalized);
            mysqli_stmt_execute($insertPhrase);
            mysqli_stmt_close($insertPhrase);
            
            // Add response
            $insertResp = mysqli_prepare($conn, "INSERT INTO intent_responses (intent_id, response, confidence) VALUES (?, ?, 1.0)");
            mysqli_stmt_bind_param($insertResp, 'is', $intentId, $reply);
            mysqli_stmt_execute($insertResp);
            mysqli_stmt_close($insertResp);
        }
    } else {
        // Legacy: Insert into chatbot
        $stmt = mysqli_prepare($conn, 'INSERT INTO chatbot (queries, replies) VALUES (?, ?)');
        mysqli_stmt_bind_param($stmt, 'ss', $learning['queries'], $reply);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Cleanup learning sample
    $stmt = mysqli_prepare($conn, 'DELETE FROM learning WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $learning['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo json_encode([ 'ok' => true, 'trained' => true, 'via' => 'replay_with', 'query' => $learning['queries'] ]);
    exit;
}

// Command: remove_replay: <query>  OR remove_replay:id:<id> (ADMIN ONLY)
if ($getMesgRaw !== '' && preg_match('/^\s*remove_replay\s*:(.*)$/i', $getMesgRaw, $m)) {
    if (!isAdminAuthenticated()) { adminOnlyError(); }
    $arg = trim($m[1]);
    $deleted = 0;
    if (preg_match('/^id\s*:(\d+)$/i', $arg, $mm)) {
        $id = intval($mm[1]);
        $stmt = mysqli_prepare($conn, 'DELETE FROM chatbot WHERE id = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $deleted = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
    } else if ($arg !== '') {
        $like = '%' . $arg . '%';
        // Delete one closest match: find one id first, then delete by id
        $stmt = mysqli_prepare($conn, 'SELECT id FROM chatbot WHERE queries LIKE ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 's', $like);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);
        if ($row) {
            $id = intval($row['id']);
            $stmt = mysqli_prepare($conn, 'DELETE FROM chatbot WHERE id = ? LIMIT 1');
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $deleted = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    echo json_encode([ 'ok' => true, 'removed' => (int)$deleted ]);
    exit;
}

// Command: list_replies: <term> [limit:10] (ADMIN ONLY)
if ($getMesgRaw !== '' && preg_match('/^\s*list_replies\s*:(.*)$/i', $getMesgRaw, $m)) {
    if (!isAdminAuthenticated()) { adminOnlyError(); }
    $arg = trim($m[1]);
    $limit = 10;
    if (preg_match('/limit\s*:\s*(\d+)/i', $arg, $mm)) {
        $limit = max(1, min(50, intval($mm[1])));
        $arg = trim(preg_replace('/limit\s*:\s*\d+/i', '', $arg));
    }
    $like = '%' . $arg . '%';
    $stmt = mysqli_prepare($conn, 'SELECT id, queries, replies FROM chatbot WHERE queries LIKE ? ORDER BY id DESC LIMIT ?');
    mysqli_stmt_bind_param($stmt, 'si', $like, $limit);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $rows = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $repliesRaw = $row['replies'];
        $replies = json_decode($repliesRaw, true);
        $isArray = is_array($replies) && count($replies) > 0;
        $rows[] = [
            'id' => (int)$row['id'],
            'query' => $row['queries'],
            'reply' => $isArray ? $replies : $repliesRaw,
            'multiple' => $isArray,
            'count' => $isArray ? count($replies) : 1,
        ];
    }
    mysqli_stmt_close($stmt);
    echo json_encode([ 'ok' => true, 'command' => true, 'type' => 'list_replies', 'count' => count($rows), 'items' => $rows ]);
    exit;
}

// Command: add_reply:id:<id>: <new reply>  OR add_reply:<query>: <new reply> (ADMIN ONLY)
// Adds another reply variant to an existing query (creates array if single reply exists)
if ($getMesgRaw !== '' && preg_match('/^\s*add_reply\s*:(.*)$/i', $getMesgRaw, $m)) {
    if (!isAdminAuthenticated()) { adminOnlyError(); }
    $arg = trim($m[1]);
    $id = null;
    $newReply = '';
    if (preg_match('/^id\s*:(\d+)\s*:(.*)$/i', $arg, $mm)) {
        $id = intval($mm[1]);
        $newReply = trim($mm[2]);
    } else if (preg_match('/^(.*?)\s*:(.*)$/', $arg, $mm)) {
        $term = trim($mm[1]);
        $newReply = trim($mm[2]);
        if ($term !== '') {
            $like = '%' . $term . '%';
            $stmt = mysqli_prepare($conn, 'SELECT id FROM chatbot WHERE queries LIKE ? ORDER BY id DESC LIMIT 1');
            mysqli_stmt_bind_param($stmt, 's', $like);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($res);
            mysqli_stmt_close($stmt);
            if ($row) { $id = (int)$row['id']; }
        }
    }
    if (!$id || $newReply === '') {
        echo json_encode([ 'ok' => false, 'error' => 'Provide id or query and a new reply' ]);
        exit;
    }
    // Fetch current reply
    $stmt = mysqli_prepare($conn, 'SELECT replies FROM chatbot WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    if (!$row) {
        echo json_encode([ 'ok' => false, 'error' => 'Entry not found' ]);
        exit;
    }
    // Handle existing replies - convert to array if needed
    $currentReplies = $row['replies'];
    $replies = json_decode($currentReplies, true);
    if (!is_array($replies)) {
        // Convert single reply to array
        $replies = [$currentReplies];
    }
    // Add new reply
    $replies[] = $newReply;
    $updatedReplies = json_encode($replies);
    // Update
    $stmt = mysqli_prepare($conn, 'UPDATE chatbot SET replies = ? WHERE id = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'si', $updatedReplies, $id);
    mysqli_stmt_execute($stmt);
    $updated = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    echo json_encode([ 'ok' => true, 'command' => true, 'type' => 'add_reply', 'updated' => (int)$updated, 'id' => (int)$id, 'total_replies' => count($replies) ]);
    exit;
}

// Command: edit_replay:id:<id>: <new reply>  OR edit_replay:<query>: <new reply> (ADMIN ONLY)
if ($getMesgRaw !== '' && preg_match('/^\s*edit_replay\s*:(.*)$/i', $getMesgRaw, $m)) {
    if (!isAdminAuthenticated()) { adminOnlyError(); }
    $arg = trim($m[1]);
    $id = null;
    $newReply = '';
    if (preg_match('/^id\s*:(\d+)\s*:(.*)$/i', $arg, $mm)) {
        $id = intval($mm[1]);
        $newReply = trim($mm[2]);
    } else if (preg_match('/^(.*?)\s*:(.*)$/', $arg, $mm)) {
        $term = trim($mm[1]);
        $newReply = trim($mm[2]);
        if ($term !== '') {
            $like = '%' . $term . '%';
            $stmt = mysqli_prepare($conn, 'SELECT id FROM chatbot WHERE queries LIKE ? ORDER BY id DESC LIMIT 1');
            mysqli_stmt_bind_param($stmt, 's', $like);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($res);
            mysqli_stmt_close($stmt);
            if ($row) { $id = (int)$row['id']; }
        }
    }
    if (!$id || $newReply === '') {
        echo json_encode([ 'ok' => false, 'error' => 'Provide id or query and a new reply' ]);
        exit;
    }
    $stmt = mysqli_prepare($conn, 'UPDATE chatbot SET replies = ? WHERE id = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'si', $newReply, $id);
    mysqli_stmt_execute($stmt);
    $updated = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    echo json_encode([ 'ok' => true, 'command' => true, 'type' => 'edit_replay', 'updated' => (int)$updated, 'id' => (int)$id ]);
    exit;
}

// Command: clear_context - Clear conversation history
if ($getMesgRaw !== '' && preg_match('/^\s*clear_context\s*$/i', $getMesgRaw)) {
    $_SESSION['conversation_history'] = [];
    echo json_encode([ 'ok' => true, 'command' => true, 'type' => 'clear_context', 'message' => 'Conversation context cleared' ]);
    exit;
}

// Command: show_context - Show recent conversation history
if ($getMesgRaw !== '' && preg_match('/^\s*show_context\s*$/i', $getMesgRaw)) {
    $history = isset($_SESSION['conversation_history']) ? $_SESSION['conversation_history'] : [];
    $recent = array_slice($history, -5, 5); // Last 5 messages
    echo json_encode([ 'ok' => true, 'command' => true, 'type' => 'show_context', 'history' => $recent, 'total' => count($history) ]);
    exit;
}

// Command: show_stats (ADMIN ONLY)
if ($getMesgRaw !== '' && preg_match('/^\s*show_stats\s*$/i', $getMesgRaw)) {
    if (!isAdminAuthenticated()) { adminOnlyError(); }
    $stats = [];
    $q = function($sql) use ($conn) {
        $res = mysqli_query($conn, $sql);
        if (!$res) return 0;
        $row = mysqli_fetch_row($res);
        return $row ? (int)$row[0] : 0;
    };
    $stats['chatbot'] = $q('SELECT COUNT(*) FROM chatbot');
    $stats['learning'] = $q('SELECT COUNT(*) FROM learning');
    $stats['history_total'] = $q('SELECT COUNT(*) FROM history_chat');
    $stats['history_matched'] = $q("SELECT COUNT(*) FROM history_chat WHERE replay = '1'");
    $stats['history_unmatched'] = $q("SELECT COUNT(*) FROM history_chat WHERE replay = '0'");
    $stats['confidence_threshold'] = $CONFIDENCE_THRESHOLD;
    echo json_encode([ 'ok' => true, 'command' => true, 'type' => 'show_stats', 'stats' => $stats ]);
    exit;
}

// Command: export_data [what:all|chatbot|learning] (ADMIN ONLY)
if ($getMesgRaw !== '' && preg_match('/^\s*export_data(.*)$/i', $getMesgRaw, $m)) {
    if (!isAdminAuthenticated()) { adminOnlyError(); }
    $arg = trim($m[1] ?? '');
    $what = 'all';
    if (preg_match('/what\s*:\s*(chatbot|learning|all)/i', $arg, $mm)) {
        $what = strtolower($mm[1]);
    }
    $payload = [];
    if ($what === 'all' || $what === 'chatbot') {
        $res = mysqli_query($conn, 'SELECT id, queries, replies FROM chatbot ORDER BY id ASC');
        $arr = [];
        while ($row = $res && mysqli_fetch_assoc($res)) {
            $arr[] = [ 'id' => (int)$row['id'], 'query' => $row['queries'], 'reply' => $row['replies'] ];
        }
        $payload['chatbot'] = $arr;
    }
    if ($what === 'all' || $what === 'learning') {
        $res = mysqli_query($conn, 'SELECT id, queries, replies FROM learning ORDER BY id ASC');
        $arr = [];
        while ($row = $res && mysqli_fetch_assoc($res)) {
            $arr[] = [ 'id' => (int)$row['id'], 'query' => $row['queries'], 'reply' => $row['replies'] ];
        }
        $payload['learning'] = $arr;
    }
    echo json_encode([ 'ok' => true, 'command' => true, 'type' => 'export_data', 'what' => $what, 'data' => $payload ]);
    exit;
}

// Command: bulk_train: <json> (ADMIN ONLY)
// Accepts JSON with pairs: [{"q": "query", "a": "reply"}, ...] or [{"query": "...", "reply": "..."}, ...]
if ($getMesgRaw !== '' && preg_match('/^\s*bulk_train\s*:(.*)$/is', $getMesgRaw, $m)) {
    if (!isAdminAuthenticated()) { adminOnlyError(); }
    $jsonPart = trim($m[1]);
    if ($jsonPart === '') {
        echo json_encode([ 'ok' => false, 'error' => 'Provide JSON payload after bulk_train:' ]);
        exit;
    }
    $data = json_decode($jsonPart, true);
    if (!is_array($data)) {
        echo json_encode([ 'ok' => false, 'error' => 'Invalid JSON for bulk_train - expected array' ]);
        exit;
    }
    $inserted = 0;
    $errors = [];
    $stmt = mysqli_prepare($conn, 'INSERT INTO chatbot (queries, replies) VALUES (?, ?)');
    foreach ($data as $idx => $pair) {
        // Support both {"q": "...", "a": "..."} and {"query": "...", "reply": "..."} formats
        $q = '';
        $r = '';
        if (isset($pair['q']) && isset($pair['a'])) {
            $q = trim((string)$pair['q']);
            $r = trim((string)$pair['a']);
        } else if (isset($pair['query']) && isset($pair['reply'])) {
            $q = trim((string)$pair['query']);
            $r = trim((string)$pair['reply']);
        } else if (isset($pair[0]) && isset($pair[1])) {
            // Support array format: ["query", "reply"]
            $q = trim((string)$pair[0]);
            $r = trim((string)$pair[1]);
        }
        if ($q === '' || $r === '') {
            $errors[] = 'Row ' . ($idx + 1) . ': missing query or reply';
            continue;
        }
        mysqli_stmt_bind_param($stmt, 'ss', $q, $r);
        if (mysqli_stmt_execute($stmt)) {
            $inserted += (mysqli_stmt_affected_rows($stmt) > 0) ? 1 : 0;
        } else {
            $errors[] = 'Row ' . ($idx + 1) . ': ' . mysqli_stmt_error($stmt);
        }
    }
    mysqli_stmt_close($stmt);
    echo json_encode([
        'ok' => true,
        'command' => true,
        'type' => 'bulk_train',
        'inserted' => $inserted,
        'total' => count($data),
        'errors' => $errors
    ]);
    exit;
}

// Command: import_data: <json> (ADMIN ONLY)
// Accepts a JSON object with optional keys: chatbot: [{query, reply}], learning: [{query, reply}]
if ($getMesgRaw !== '' && preg_match('/^\s*import_data\s*:(.*)$/is', $getMesgRaw, $m)) {
    if (!isAdminAuthenticated()) { adminOnlyError(); }
    $jsonPart = trim($m[1]);
    if ($jsonPart === '') {
        echo json_encode([ 'ok' => false, 'error' => 'Provide JSON payload after import_data:' ]);
        exit;
    }
    $data = json_decode($jsonPart, true);
    if (!is_array($data)) {
        echo json_encode([ 'ok' => false, 'error' => 'Invalid JSON for import_data' ]);
        exit;
    }
    $inserted = [ 'chatbot' => 0, 'learning' => 0 ];
    if (isset($data['chatbot']) && is_array($data['chatbot'])) {
        $stmt = mysqli_prepare($conn, 'INSERT INTO chatbot (queries, replies) VALUES (?, ?)');
        foreach ($data['chatbot'] as $row) {
            $q = isset($row['query']) ? trim((string)$row['query']) : '';
            $r = isset($row['reply']) ? trim((string)$row['reply']) : '';
            if ($q === '' || $r === '') continue;
            mysqli_stmt_bind_param($stmt, 'ss', $q, $r);
            mysqli_stmt_execute($stmt);
            $inserted['chatbot'] += (mysqli_stmt_affected_rows($stmt) > 0) ? 1 : 0;
        }
        mysqli_stmt_close($stmt);
    }
    if (isset($data['learning']) && is_array($data['learning'])) {
        $stmt = mysqli_prepare($conn, 'INSERT INTO learning (queries, replies) VALUES (?, ?)');
        foreach ($data['learning'] as $row) {
            $q = isset($row['query']) ? trim((string)$row['query']) : '';
            $r = isset($row['reply']) ? trim((string)$row['reply']) : '?';
            if ($q === '') continue;
            mysqli_stmt_bind_param($stmt, 'ss', $q, $r);
            mysqli_stmt_execute($stmt);
            $inserted['learning'] += (mysqli_stmt_affected_rows($stmt) > 0) ? 1 : 0;
        }
        mysqli_stmt_close($stmt);
    }
    echo json_encode([ 'ok' => true, 'command' => true, 'type' => 'import_data', 'inserted' => $inserted ]);
    exit;
}

// Command: list_learning [limit:50] (ADMIN ONLY)
if ($getMesgRaw !== '' && preg_match('/^\s*list_learning(.*)$/i', $getMesgRaw, $m)) {
    if (!isAdminAuthenticated()) { adminOnlyError(); }
    $arg = trim($m[1] ?? '');
    $limit = 50;
    if (preg_match('/limit\s*:\s*(\d+)/i', $arg, $mm)) {
        $limit = max(1, min(200, intval($mm[1])));
    }
    $res = mysqli_query($conn, 'SELECT id, queries, replies FROM learning ORDER BY id DESC LIMIT ' . intval($limit));
    $rows = [];
    while ($row = $res && mysqli_fetch_assoc($res)) {
        $rows[] = [ 'id' => (int)$row['id'], 'query' => $row['queries'], 'reply' => $row['replies'] ];
    }
    echo json_encode([ 'ok' => true, 'command' => true, 'type' => 'list_learning', 'count' => count($rows), 'items' => $rows ]);
    exit;
}

// Handle training write: expects POST teach_for (learning id) and reply (ADMIN ONLY)
if (isset($_POST['teach_for']) && isset($_POST['reply'])) {
    if (!isAdminAuthenticated()) { adminOnlyError(); }
    $teachId = intval($_POST['teach_for']);
    $reply = trim($_POST['reply']);
    if ($teachId <= 0 || $reply === '') {
        echo json_encode([ 'ok' => false, 'error' => 'Invalid training payload' ]);
        exit;
    }

    // Fetch the learning row to get the original query
    $stmt = mysqli_prepare($conn, 'SELECT id, queries FROM learning WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $teachId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $learning = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$learning) {
        echo json_encode([ 'ok' => false, 'error' => 'Learning sample not found' ]);
        exit;
    }

    // Check if using intent-based system
    if (hasIntentTables($conn)) {
        // Create a quick intent from the query
        $query = $learning['queries'];
        $intentName = preg_replace('/[^a-zA-Z0-9\s]/', '', $query);
        $intentName = preg_replace('/\s+/', '_', trim($intentName));
        $intentName = substr($intentName, 0, 50);
        if ($intentName === '') {
            $intentName = 'taught_' . $teachId;
        }
        
        // Check if intent exists
        $checkStmt = mysqli_prepare($conn, "SELECT id FROM intents WHERE name = ?");
        mysqli_stmt_bind_param($checkStmt, 's', $intentName);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);
        $existingIntent = mysqli_fetch_assoc($checkResult);
        mysqli_stmt_close($checkStmt);
        
        $intentId = null;
        if ($existingIntent) {
            $intentId = (int)$existingIntent['id'];
        } else {
            // Create intent
            $desc = 'Taught via chat interface';
            $insertIntent = mysqli_prepare($conn, "INSERT INTO intents (name, description) VALUES (?, ?)");
            mysqli_stmt_bind_param($insertIntent, 'ss', $intentName, $desc);
            if (mysqli_stmt_execute($insertIntent)) {
                $intentId = mysqli_insert_id($conn);
            }
            mysqli_stmt_close($insertIntent);
        }
        
        if ($intentId) {
            // Add training phrase
            $normalized = normalizeText($query);
            $insertPhrase = mysqli_prepare($conn, "INSERT INTO training_phrases (intent_id, phrase, phrase_normalized) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($insertPhrase, 'iss', $intentId, $query, $normalized);
            mysqli_stmt_execute($insertPhrase);
            mysqli_stmt_close($insertPhrase);
            
            // Add response
            $insertResp = mysqli_prepare($conn, "INSERT INTO intent_responses (intent_id, response, confidence) VALUES (?, ?, 1.0)");
            mysqli_stmt_bind_param($insertResp, 'is', $intentId, $reply);
            mysqli_stmt_execute($insertResp);
            mysqli_stmt_close($insertResp);
        }
    } else {
        // Legacy: Insert into chatbot
        $stmt = mysqli_prepare($conn, 'INSERT INTO chatbot (queries, replies) VALUES (?, ?)');
        mysqli_stmt_bind_param($stmt, 'ss', $learning['queries'], $reply);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Delete learning sample
    $stmt = mysqli_prepare($conn, 'DELETE FROM learning WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $teachId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo json_encode([ 'ok' => true, 'trained' => true ]);
    exit;
}

// Handle feedback/reaction: expects POST feedback (thumbs_up or thumbs_down), message_id, query, reply
if (isset($_POST['feedback']) && isset($_POST['message_id'])) {
    $feedback = trim($_POST['feedback']);
    $messageId = trim($_POST['message_id']);
    $query = isset($_POST['query']) ? trim($_POST['query']) : '';
    $reply = isset($_POST['reply']) ? trim($_POST['reply']) : '';
    
    if (!in_array($feedback, ['thumbs_up', 'thumbs_down'])) {
        echo json_encode([ 'ok' => false, 'error' => 'Invalid feedback type' ]);
        exit;
    }
    
    // Create feedback table if it doesn't exist (for simplicity, we'll try to insert)
    // Using a simple approach: store in history_chat with a feedback column, or create new table
    // For now, let's use a simple INSERT with IF NOT EXISTS pattern
    $feedbackValue = $feedback === 'thumbs_up' ? '1' : '0';
    
    // Check if feedback table exists, if not create it
    $createTable = "CREATE TABLE IF NOT EXISTS feedback (
        id INT AUTO_INCREMENT PRIMARY KEY,
        message_id VARCHAR(100) NOT NULL,
        query TEXT,
        reply TEXT,
        feedback_type ENUM('thumbs_up', 'thumbs_down') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_message_id (message_id),
        INDEX idx_feedback_type (feedback_type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    mysqli_query($conn, $createTable);
    
    // Insert feedback
    $stmt = mysqli_prepare($conn, 'INSERT INTO feedback (message_id, query, reply, feedback_type) VALUES (?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssss', $messageId, $query, $reply, $feedback);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    echo json_encode([ 'ok' => true, 'feedback' => true, 'type' => $feedback ]);
    exit;
}

// Handle message query
if (!isset($_POST['text'])) {
    echo json_encode([ 'ok' => false, 'error' => 'No text provided' ]);
    exit;
}

if ($getMesgRaw === '') {
    echo json_encode([ 'ok' => false, 'error' => 'Empty message' ]);
    exit;
}

// Check if this is a command (don't store commands in history)
$isCommand = false;
$commandPatterns = [
    '/^\s*(replay_with|remove_replay|edit_replay|add_reply|list_replies|list_learning|show_stats|show_context|clear_context|export_data|import_data|bulk_train)\s*:/i',
];
foreach ($commandPatterns as $pattern) {
    if (preg_match($pattern, $getMesgRaw)) {
        $isCommand = true;
        break;
    }
}

// Build context-enhanced query for better matching (only for non-commands)
// Combine current message with recent context (last 2-3 messages)
$enhancedQuery = $getMesgRaw;
if (!empty($contextMessages) && !$isCommand) {
    $contextText = '';
    foreach ($contextMessages as $msg) {
        if (isset($msg['user']) && trim($msg['user']) !== '') {
            $contextText .= ' ' . trim($msg['user']);
        }
    }
    // Prepend context to help with follow-up questions
    $enhancedQuery = trim($contextText . ' ' . $getMesgRaw);
}

// Store current user message in conversation history (only non-commands)
if (!$isCommand) {
    $_SESSION['conversation_history'][] = [
        'user' => $getMesgRaw,
        'bot' => null,
        'timestamp' => time()
    ];
}

// Determine if we're using intent-based or legacy matching
$useIntentMatching = hasIntentTables($conn);

// Normalize the user query for matching
$normalizedQuery = normalizeText($getMesgRaw);
$normalizedEnhanced = normalizeText($enhancedQuery);

// ========== INTENT-BASED MATCHING ==========
if ($useIntentMatching) {
    // Level 1: Exact match on normalized training phrases
    $exactStmt = mysqli_prepare($conn, '
        SELECT tp.id, tp.intent_id, tp.phrase, tp.phrase_normalized, i.name as intent_name
        FROM training_phrases tp
        JOIN intents i ON tp.intent_id = i.id
        WHERE i.is_active = 1 AND tp.phrase_normalized = ?
        LIMIT 1
    ');
    mysqli_stmt_bind_param($exactStmt, 's', $normalizedQuery);
    mysqli_stmt_execute($exactStmt);
    $exactResult = mysqli_stmt_get_result($exactStmt);
    $exactMatch = mysqli_fetch_assoc($exactResult);
    mysqli_stmt_close($exactStmt);
    
    if ($exactMatch) {
        // Get a response for this intent
        $respStmt = mysqli_prepare($conn, '
            SELECT id, response, confidence
            FROM intent_responses
            WHERE intent_id = ? AND is_active = 1
            ORDER BY confidence DESC
        ');
        mysqli_stmt_bind_param($respStmt, 'i', $exactMatch['intent_id']);
        mysqli_stmt_execute($respStmt);
        $respResult = mysqli_stmt_get_result($respStmt);
        $responses = [];
        while ($r = mysqli_fetch_assoc($respResult)) {
            $responses[] = $r;
        }
        mysqli_stmt_close($respStmt);
        
        if (!empty($responses)) {
            // Weighted random selection based on confidence
            $totalWeight = array_sum(array_column($responses, 'confidence'));
            $rand = mt_rand() / mt_getrandmax() * $totalWeight;
            $cumulative = 0;
            $selectedResponse = $responses[0];
            foreach ($responses as $resp) {
                $cumulative += $resp['confidence'];
                if ($rand <= $cumulative) {
                    $selectedResponse = $resp;
                    break;
                }
            }
            
            // Log history as matched
            $stmt = mysqli_prepare($conn, 'INSERT INTO history_chat (text, replay) VALUES (?, ?)');
            $matched = '1';
            mysqli_stmt_bind_param($stmt, 'ss', $getMesgRaw, $matched);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            
            // Update conversation history
            if (!$isCommand && !empty($_SESSION['conversation_history'])) {
                $lastIndex = count($_SESSION['conversation_history']) - 1;
                if (isset($_SESSION['conversation_history'][$lastIndex])) {
                    $_SESSION['conversation_history'][$lastIndex]['bot'] = $selectedResponse['response'];
                }
            }
            
            echo json_encode([
                'ok' => true,
                'found' => true,
                'reply' => $selectedResponse['response'],
                'confidence' => 100,
                'intent' => $exactMatch['intent_name'],
                'match_type' => 'exact'
            ]);
            exit;
        }
    }
    
    // Level 2: Fuzzy match on training phrases
    $like1 = '%' . $getMesgRaw . '%';
    $like2 = '%' . $normalizedQuery . '%';
    $fuzzyStmt = mysqli_prepare($conn, '
        SELECT tp.id, tp.intent_id, tp.phrase, tp.phrase_normalized, i.name as intent_name
        FROM training_phrases tp
        JOIN intents i ON tp.intent_id = i.id
        WHERE i.is_active = 1 AND (tp.phrase LIKE ? OR tp.phrase_normalized LIKE ?)
        LIMIT 50
    ');
    mysqli_stmt_bind_param($fuzzyStmt, 'ss', $like1, $like2);
    mysqli_stmt_execute($fuzzyStmt);
    $fuzzyResult = mysqli_stmt_get_result($fuzzyStmt);
    $candidates = [];
    while ($row = mysqli_fetch_assoc($fuzzyResult)) {
        $candidates[] = $row;
    }
    mysqli_stmt_close($fuzzyStmt);
} else {
    // ========== LEGACY MATCHING (chatbot table) ==========
    $like1 = '%' . $getMesgRaw . '%';
    $like2 = '%' . $enhancedQuery . '%';
    $stmt = mysqli_prepare($conn, 'SELECT id, queries, replies FROM chatbot WHERE queries LIKE ? OR queries LIKE ? LIMIT 30');
    mysqli_stmt_bind_param($stmt, 'ss', $like1, $like2);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $candidates = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $candidates[] = $row;
    }
    mysqli_stmt_close($stmt);
}

// Jaro-Winkler similarity function (0-1 scale, where 1 = identical)
function jaroWinkler($s1, $s2) {
    $len1 = mb_strlen($s1);
    $len2 = mb_strlen($s2);
    
    if ($len1 == 0 && $len2 == 0) return 1.0;
    if ($len1 == 0 || $len2 == 0) return 0.0;
    
    $matchWindow = (int)(max($len1, $len2) / 2) - 1;
    if ($matchWindow < 0) $matchWindow = 0;
    
    $s1Matches = array_fill(0, $len1, false);
    $s2Matches = array_fill(0, $len2, false);
    
    $matches = 0;
    $transpositions = 0;
    
    // Find matches
    for ($i = 0; $i < $len1; $i++) {
        $start = max(0, $i - $matchWindow);
        $end = min($i + $matchWindow + 1, $len2);
        for ($j = $start; $j < $end; $j++) {
            if ($s2Matches[$j] || mb_substr($s1, $i, 1) !== mb_substr($s2, $j, 1)) {
                continue;
            }
            $s1Matches[$i] = true;
            $s2Matches[$j] = true;
            $matches++;
            break;
        }
    }
    
    if ($matches == 0) return 0.0;
    
    // Find transpositions
    $k = 0;
    for ($i = 0; $i < $len1; $i++) {
        if (!$s1Matches[$i]) continue;
        while (!$s2Matches[$k]) $k++;
        if (mb_substr($s1, $i, 1) !== mb_substr($s2, $k, 1)) {
            $transpositions++;
        }
        $k++;
    }
    
    // Jaro similarity
    $jaro = ($matches / $len1 + $matches / $len2 + ($matches - $transpositions / 2) / $matches) / 3.0;
    
    // Winkler modification (common prefix bonus)
    $prefixLen = 0;
    $maxPrefix = min(4, min($len1, $len2));
    for ($i = 0; $i < $maxPrefix; $i++) {
        if (mb_substr($s1, $i, 1) === mb_substr($s2, $i, 1)) {
            $prefixLen++;
        } else {
            break;
        }
    }
    
    $jaroWinkler = $jaro + (0.1 * $prefixLen * (1 - $jaro));
    return min(1.0, $jaroWinkler);
}

// Enhanced fuzzy matching using multiple algorithms (Levenshtein + Jaro-Winkler)
$best = null;
$bestScore = PHP_INT_MAX;
$bestConfidence = 0;

$q1Lower = function_exists('mb_strtolower') ? mb_strtolower($getMesgRaw) : strtolower($getMesgRaw);
$q1Len = mb_strlen($getMesgRaw);

// Try matching with both original query and enhanced query (with context)
$queriesToTry = [$getMesgRaw, $normalizedQuery];
if ($enhancedQuery !== $getMesgRaw && strlen($enhancedQuery) > strlen($getMesgRaw)) {
    $queriesToTry[] = $enhancedQuery;
    $queriesToTry[] = $normalizedEnhanced;
}

foreach ($candidates as $row) {
    // Handle both intent-based (phrase/phrase_normalized) and legacy (queries) structures
    $candidatePhrase = isset($row['phrase']) ? $row['phrase'] : (isset($row['queries']) ? $row['queries'] : '');
    $candidateNormalized = isset($row['phrase_normalized']) ? $row['phrase_normalized'] : normalizeText($candidatePhrase);
    
    $q2Lower = function_exists('mb_strtolower') ? mb_strtolower($candidatePhrase) : strtolower($candidatePhrase);
    $q2Len = mb_strlen($candidatePhrase);
    
    $bestMatchForThis = null;
    $bestConfidenceForThis = 0;
    $bestDistanceForThis = PHP_INT_MAX;
    
    // Try matching with original query and enhanced query, pick best
    foreach ($queriesToTry as $tryQuery) {
        $tryLower = function_exists('mb_strtolower') ? mb_strtolower($tryQuery) : strtolower($tryQuery);
        $tryLen = mb_strlen($tryQuery);
        
        // Also try matching against the normalized version
        $targetToMatch = $candidateNormalized !== '' ? $candidateNormalized : $q2Lower;
        
        // Calculate Levenshtein distance
        $levDistance = levenshtein($tryLower, $targetToMatch);
        $maxLen = max($tryLen, mb_strlen($targetToMatch));
        
        // Levenshtein-based confidence (0-100)
        $levConfidence = 0;
        if ($maxLen == 0) {
            $levConfidence = 100;
        } else {
            $levConfidence = (1 - ($levDistance / $maxLen)) * 100;
        }
        
        // Calculate Jaro-Winkler similarity (0-1 scale)
        $jwSimilarity = jaroWinkler($tryLower, $targetToMatch);
        $jwConfidence = $jwSimilarity * 100; // Convert to 0-100 scale
        
        // Combined confidence: weighted average (60% Jaro-Winkler, 40% Levenshtein)
        $confidence = ($jwConfidence * 0.6) + ($levConfidence * 0.4);
        
        // Track best match for this candidate
        if ($confidence > $bestConfidenceForThis) {
            $bestConfidenceForThis = $confidence;
            $bestDistanceForThis = $levDistance;
        }
    }
    
    // Use combined confidence for threshold check, but Levenshtein distance for ranking
    if ($bestConfidenceForThis >= $CONFIDENCE_THRESHOLD) {
        if ($bestDistanceForThis < $bestScore) {
            $bestScore = $bestDistanceForThis;
            $bestConfidence = $bestConfidenceForThis;
            $best = $row;
        }
    }
}

if ($best) {
    // Log history as matched
    $stmt = mysqli_prepare($conn, 'INSERT INTO history_chat (text, replay) VALUES (?, ?)');
    $matched = '1';
    mysqli_stmt_bind_param($stmt, 'ss', $getMesgRaw, $matched);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $reply = '';
    $intentName = null;
    $matchType = 'fuzzy';

    if ($useIntentMatching && isset($best['intent_id'])) {
        // Intent-based: fetch response from responses table
        $intentName = isset($best['intent_name']) ? $best['intent_name'] : null;
        
        $respStmt = mysqli_prepare($conn, '
            SELECT id, response, confidence
            FROM intent_responses
            WHERE intent_id = ? AND is_active = 1
            ORDER BY confidence DESC
        ');
        mysqli_stmt_bind_param($respStmt, 'i', $best['intent_id']);
        mysqli_stmt_execute($respStmt);
        $respResult = mysqli_stmt_get_result($respStmt);
        $responses = [];
        while ($r = mysqli_fetch_assoc($respResult)) {
            $responses[] = $r;
        }
        mysqli_stmt_close($respStmt);
        
        if (!empty($responses)) {
            // Weighted random selection based on confidence
            $totalWeight = array_sum(array_column($responses, 'confidence'));
            if ($totalWeight > 0) {
                $rand = mt_rand() / mt_getrandmax() * $totalWeight;
                $cumulative = 0;
                foreach ($responses as $resp) {
                    $cumulative += $resp['confidence'];
                    if ($rand <= $cumulative) {
                        $reply = $resp['response'];
                        break;
                    }
                }
            }
            if ($reply === '') {
                $reply = $responses[0]['response'];
            }
        }
    } else {
        // Legacy: Handle multiple replies - support JSON array or single string
        $reply = isset($best['replies']) ? $best['replies'] : '';
        $replies = json_decode($reply, true);
        if (is_array($replies) && count($replies) > 0) {
            // Multiple replies: pick one randomly
            $reply = $replies[array_rand($replies)];
        }
    }

    // Update conversation history with bot's reply (only for non-commands)
    if (!$isCommand && !empty($_SESSION['conversation_history'])) {
        $lastIndex = count($_SESSION['conversation_history']) - 1;
        if (isset($_SESSION['conversation_history'][$lastIndex])) {
            $_SESSION['conversation_history'][$lastIndex]['bot'] = $reply;
        }
    }

    $response = [ 
        'ok' => true, 
        'found' => true, 
        'reply' => $reply,
        'confidence' => round($bestConfidence, 1),
        'match_type' => $matchType
    ];
    if ($intentName) {
        $response['intent'] = $intentName;
    }
    
    echo json_encode($response);
    exit;
}

// Not found: insert into learning and return pending id
$stmt = mysqli_prepare($conn, 'INSERT INTO learning (queries, replies) VALUES (?, ?)');
$placeholder = '?';
mysqli_stmt_bind_param($stmt, 'ss', $getMesgRaw, $placeholder);
mysqli_stmt_execute($stmt);
$learnId = mysqli_insert_id($conn);
mysqli_stmt_close($stmt);

// Log history as unmatched
$stmt = mysqli_prepare($conn, 'INSERT INTO history_chat (text, replay) VALUES (?, ?)');
$matched = '0';
mysqli_stmt_bind_param($stmt, 'ss', $getMesgRaw, $matched);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// Update conversation history with "unknown" response (only for non-commands)
if (!$isCommand && !empty($_SESSION['conversation_history'])) {
    $lastIndex = count($_SESSION['conversation_history']) - 1;
    if (isset($_SESSION['conversation_history'][$lastIndex])) {
        $_SESSION['conversation_history'][$lastIndex]['bot'] = "I don't have an answer for that yet. Your question has been saved for review.";
    }
}

echo json_encode([
    'ok' => true,
    'found' => false,
    'learn_id' => $learnId,
    'message' => "I don't have an answer for that yet. Your question has been saved for review."
]);
exit;
?>