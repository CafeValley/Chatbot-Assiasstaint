<?php
/**
 * Admin API - Backend for Intent Management
 * Handles all CRUD operations for intents, training phrases, responses, and queue management
 */

session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

// Check admin authentication (session or token-based)
function isAdminAuth() {
    // Check session
    if (!empty($_SESSION['is_admin'])) {
        return true;
    }
    
    // Check POST auth token
    if (isset($_POST['auth'])) {
        $decoded = base64_decode($_POST['auth']);
        if ($decoded === 'admin:admin') {
            return true;
        }
    }
    
    return false;
}

if (!isAdminAuth()) {
    echo json_encode(['ok' => false, 'error' => 'Unauthorized']);
    exit;
}

/**
 * Normalize text for matching
 */
function normalizeText($text) {
    if ($text === null || $text === '') return '';
    $text = function_exists('mb_strtolower') ? mb_strtolower($text, 'UTF-8') : strtolower($text);
    $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
    $text = preg_replace('/\s+/', ' ', $text);
    return trim($text);
}

$action = isset($_POST['action']) ? trim($_POST['action']) : '';

// ============ INTENTS ============

if ($action === 'list_intents') {
    $search = isset($_POST['search']) ? trim($_POST['search']) : '';
    
    $sql = "SELECT i.*, 
            (SELECT COUNT(*) FROM training_phrases WHERE intent_id = i.id) as phrase_count,
            (SELECT COUNT(*) FROM intent_responses WHERE intent_id = i.id) as response_count
            FROM intents i";
    
    if ($search !== '') {
        $sql .= " WHERE i.name LIKE ? OR i.description LIKE ?";
        $stmt = mysqli_prepare($conn, $sql . " ORDER BY i.name ASC");
        $like = '%' . $search . '%';
        mysqli_stmt_bind_param($stmt, 'ss', $like, $like);
    } else {
        $stmt = mysqli_prepare($conn, $sql . " ORDER BY i.name ASC");
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $intents = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $intents[] = $row;
    }
    mysqli_stmt_close($stmt);
    
    echo json_encode(['ok' => true, 'intents' => $intents]);
    exit;
}

if ($action === 'get_intent') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $stmt = mysqli_prepare($conn, "SELECT * FROM intents WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $intent = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    if (!$intent) {
        echo json_encode(['ok' => false, 'error' => 'Intent not found']);
        exit;
    }
    
    echo json_encode(['ok' => true, 'intent' => $intent]);
    exit;
}

if ($action === 'create_intent') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    
    if ($name === '') {
        echo json_encode(['ok' => false, 'error' => 'Name is required']);
        exit;
    }
    
    // Sanitize name for uniqueness
    $name = preg_replace('/[^a-zA-Z0-9_\s-]/', '', $name);
    $name = preg_replace('/\s+/', '_', $name);
    
    $stmt = mysqli_prepare($conn, "INSERT INTO intents (name, description) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, 'ss', $name, $description);
    
    if (mysqli_stmt_execute($stmt)) {
        $newId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        echo json_encode(['ok' => true, 'id' => $newId]);
    } else {
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        echo json_encode(['ok' => false, 'error' => 'Failed to create intent: ' . $error]);
    }
    exit;
}

if ($action === 'update_intent') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $isActive = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;
    
    if ($id <= 0 || $name === '') {
        echo json_encode(['ok' => false, 'error' => 'ID and name required']);
        exit;
    }
    
    $stmt = mysqli_prepare($conn, "UPDATE intents SET name = ?, description = ?, is_active = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'ssii', $name, $description, $isActive, $id);
    mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    
    echo json_encode(['ok' => true, 'updated' => $affected]);
    exit;
}

if ($action === 'delete_intent') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id <= 0) {
        echo json_encode(['ok' => false, 'error' => 'Invalid ID']);
        exit;
    }
    
    // Cascade delete handled by foreign key, but let's be explicit
    mysqli_query($conn, "DELETE FROM training_phrases WHERE intent_id = " . intval($id));
    mysqli_query($conn, "DELETE FROM intent_responses WHERE intent_id = " . intval($id));
    
    $stmt = mysqli_prepare($conn, "DELETE FROM intents WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    
    echo json_encode(['ok' => true, 'deleted' => $affected]);
    exit;
}

// ============ TRAINING PHRASES ============

if ($action === 'list_phrases') {
    $search = isset($_POST['search']) ? trim($_POST['search']) : '';
    $intentId = isset($_POST['intent_id']) ? (int)$_POST['intent_id'] : 0;
    
    $sql = "SELECT tp.*, i.name as intent_name FROM training_phrases tp 
            JOIN intents i ON tp.intent_id = i.id WHERE 1=1";
    $params = [];
    $types = '';
    
    if ($search !== '') {
        $sql .= " AND (tp.phrase LIKE ? OR tp.phrase_normalized LIKE ?)";
        $like = '%' . $search . '%';
        $params[] = $like;
        $params[] = $like;
        $types .= 'ss';
    }
    
    if ($intentId > 0) {
        $sql .= " AND tp.intent_id = ?";
        $params[] = $intentId;
        $types .= 'i';
    }
    
    $sql .= " ORDER BY tp.id DESC LIMIT 200";
    
    $stmt = mysqli_prepare($conn, $sql);
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $phrases = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $phrases[] = $row;
    }
    mysqli_stmt_close($stmt);
    
    echo json_encode(['ok' => true, 'phrases' => $phrases]);
    exit;
}

if ($action === 'add_phrase') {
    $intentId = isset($_POST['intent_id']) ? (int)$_POST['intent_id'] : 0;
    $phrase = isset($_POST['phrase']) ? trim($_POST['phrase']) : '';
    
    if ($intentId <= 0 || $phrase === '') {
        echo json_encode(['ok' => false, 'error' => 'Intent ID and phrase required']);
        exit;
    }
    
    $normalized = normalizeText($phrase);
    
    $stmt = mysqli_prepare($conn, "INSERT INTO training_phrases (intent_id, phrase, phrase_normalized) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'iss', $intentId, $phrase, $normalized);
    
    if (mysqli_stmt_execute($stmt)) {
        $newId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        echo json_encode(['ok' => true, 'id' => $newId]);
    } else {
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        echo json_encode(['ok' => false, 'error' => 'Failed to add phrase: ' . $error]);
    }
    exit;
}

if ($action === 'delete_phrase') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id <= 0) {
        echo json_encode(['ok' => false, 'error' => 'Invalid ID']);
        exit;
    }
    
    $stmt = mysqli_prepare($conn, "DELETE FROM training_phrases WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    
    echo json_encode(['ok' => true, 'deleted' => $affected]);
    exit;
}

// ============ RESPONSES ============

if ($action === 'list_responses') {
    $search = isset($_POST['search']) ? trim($_POST['search']) : '';
    $intentId = isset($_POST['intent_id']) ? (int)$_POST['intent_id'] : 0;
    
    $sql = "SELECT r.*, i.name as intent_name FROM intent_responses r 
            JOIN intents i ON r.intent_id = i.id WHERE 1=1";
    $params = [];
    $types = '';
    
    if ($search !== '') {
        $sql .= " AND r.response LIKE ?";
        $like = '%' . $search . '%';
        $params[] = $like;
        $types .= 's';
    }
    
    if ($intentId > 0) {
        $sql .= " AND r.intent_id = ?";
        $params[] = $intentId;
        $types .= 'i';
    }
    
    $sql .= " ORDER BY r.id DESC LIMIT 200";
    
    $stmt = mysqli_prepare($conn, $sql);
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $responses = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $responses[] = $row;
    }
    mysqli_stmt_close($stmt);
    
    echo json_encode(['ok' => true, 'responses' => $responses]);
    exit;
}

if ($action === 'get_response') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $stmt = mysqli_prepare($conn, "SELECT * FROM intent_responses WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $response = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    if (!$response) {
        echo json_encode(['ok' => false, 'error' => 'Response not found']);
        exit;
    }
    
    echo json_encode(['ok' => true, 'response' => $response]);
    exit;
}

if ($action === 'add_response') {
    $intentId = isset($_POST['intent_id']) ? (int)$_POST['intent_id'] : 0;
    $response = isset($_POST['response']) ? trim($_POST['response']) : '';
    $confidence = isset($_POST['confidence']) ? (float)$_POST['confidence'] : 1.0;
    
    if ($intentId <= 0 || $response === '') {
        echo json_encode(['ok' => false, 'error' => 'Intent ID and response required']);
        exit;
    }
    
    $confidence = max(0, min(1, $confidence));
    
    $stmt = mysqli_prepare($conn, "INSERT INTO intent_responses (intent_id, response, confidence) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'isd', $intentId, $response, $confidence);
    
    if (mysqli_stmt_execute($stmt)) {
        $newId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        echo json_encode(['ok' => true, 'id' => $newId]);
    } else {
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        echo json_encode(['ok' => false, 'error' => 'Failed to add response: ' . $error]);
    }
    exit;
}

if ($action === 'update_response') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $response = isset($_POST['response']) ? trim($_POST['response']) : '';
    $confidence = isset($_POST['confidence']) ? (float)$_POST['confidence'] : 1.0;
    $isActive = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;
    
    if ($id <= 0 || $response === '') {
        echo json_encode(['ok' => false, 'error' => 'ID and response required']);
        exit;
    }
    
    $confidence = max(0, min(1, $confidence));
    
    $stmt = mysqli_prepare($conn, "UPDATE intent_responses SET response = ?, confidence = ?, is_active = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'sdii', $response, $confidence, $isActive, $id);
    mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    
    echo json_encode(['ok' => true, 'updated' => $affected]);
    exit;
}

if ($action === 'delete_response') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id <= 0) {
        echo json_encode(['ok' => false, 'error' => 'Invalid ID']);
        exit;
    }
    
    $stmt = mysqli_prepare($conn, "DELETE FROM intent_responses WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    
    echo json_encode(['ok' => true, 'deleted' => $affected]);
    exit;
}

// ============ LEARNING QUEUE ============

if ($action === 'list_queue') {
    $sql = "SELECT * FROM learning ORDER BY id DESC LIMIT 100";
    $result = mysqli_query($conn, $sql);
    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
    
    echo json_encode(['ok' => true, 'items' => $items]);
    exit;
}

if ($action === 'assign_to_intent') {
    $learnId = isset($_POST['learn_id']) ? (int)$_POST['learn_id'] : 0;
    $intentId = isset($_POST['intent_id']) ? (int)$_POST['intent_id'] : 0;
    $query = isset($_POST['query']) ? trim($_POST['query']) : '';
    $response = isset($_POST['response']) ? trim($_POST['response']) : '';
    
    if ($learnId <= 0 || $intentId <= 0) {
        echo json_encode(['ok' => false, 'error' => 'Learn ID and Intent ID required']);
        exit;
    }
    
    // Add as training phrase
    if ($query !== '') {
        $normalized = normalizeText($query);
        $stmt = mysqli_prepare($conn, "INSERT INTO training_phrases (intent_id, phrase, phrase_normalized) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'iss', $intentId, $query, $normalized);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    // Add response if provided
    if ($response !== '') {
        $stmt = mysqli_prepare($conn, "INSERT INTO intent_responses (intent_id, response, confidence) VALUES (?, ?, 1.0)");
        mysqli_stmt_bind_param($stmt, 'is', $intentId, $response);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    // Delete from learning queue
    $stmt = mysqli_prepare($conn, "DELETE FROM learning WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $learnId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    echo json_encode(['ok' => true]);
    exit;
}

if ($action === 'create_intent_from_queue') {
    $learnId = isset($_POST['learn_id']) ? (int)$_POST['learn_id'] : 0;
    $intentName = isset($_POST['intent_name']) ? trim($_POST['intent_name']) : '';
    $intentDesc = isset($_POST['intent_desc']) ? trim($_POST['intent_desc']) : '';
    $query = isset($_POST['query']) ? trim($_POST['query']) : '';
    $response = isset($_POST['response']) ? trim($_POST['response']) : '';
    
    if ($learnId <= 0 || $intentName === '' || $response === '') {
        echo json_encode(['ok' => false, 'error' => 'Learn ID, intent name, and response required']);
        exit;
    }
    
    // Sanitize intent name
    $intentName = preg_replace('/[^a-zA-Z0-9_\s-]/', '', $intentName);
    $intentName = preg_replace('/\s+/', '_', $intentName);
    
    // Create intent
    $stmt = mysqli_prepare($conn, "INSERT INTO intents (name, description) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, 'ss', $intentName, $intentDesc);
    if (!mysqli_stmt_execute($stmt)) {
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        echo json_encode(['ok' => false, 'error' => 'Failed to create intent: ' . $error]);
        exit;
    }
    $intentId = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);
    
    // Add training phrase
    if ($query !== '') {
        $normalized = normalizeText($query);
        $stmt = mysqli_prepare($conn, "INSERT INTO training_phrases (intent_id, phrase, phrase_normalized) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'iss', $intentId, $query, $normalized);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    // Add response
    $stmt = mysqli_prepare($conn, "INSERT INTO intent_responses (intent_id, response, confidence) VALUES (?, ?, 1.0)");
    mysqli_stmt_bind_param($stmt, 'is', $intentId, $response);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    // Delete from learning queue
    $stmt = mysqli_prepare($conn, "DELETE FROM learning WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $learnId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    echo json_encode(['ok' => true, 'intent_id' => $intentId]);
    exit;
}

if ($action === 'dismiss_queue') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id <= 0) {
        echo json_encode(['ok' => false, 'error' => 'Invalid ID']);
        exit;
    }
    
    // Delete from queue
    $stmt = mysqli_prepare($conn, "DELETE FROM learning WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    
    echo json_encode(['ok' => true, 'deleted' => $affected]);
    exit;
}

// ============ LEGACY DATA ============

if ($action === 'list_legacy') {
    $search = isset($_POST['search']) ? trim($_POST['search']) : '';
    
    // Check if chatbot table exists
    $tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'chatbot'");
    if (!$tableCheck || mysqli_num_rows($tableCheck) === 0) {
        echo json_encode(['ok' => true, 'items' => []]);
        exit;
    }
    
    if ($search !== '') {
        $stmt = mysqli_prepare($conn, "SELECT * FROM chatbot WHERE queries LIKE ? OR replies LIKE ? ORDER BY id DESC LIMIT 100");
        $like = '%' . $search . '%';
        mysqli_stmt_bind_param($stmt, 'ss', $like, $like);
    } else {
        $stmt = mysqli_prepare($conn, "SELECT * FROM chatbot ORDER BY id DESC LIMIT 100");
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
    mysqli_stmt_close($stmt);
    
    echo json_encode(['ok' => true, 'items' => $items]);
    exit;
}

if ($action === 'delete_legacy') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id <= 0) {
        echo json_encode(['ok' => false, 'error' => 'Invalid ID']);
        exit;
    }
    
    $stmt = mysqli_prepare($conn, "DELETE FROM chatbot WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    
    echo json_encode(['ok' => true, 'deleted' => $affected]);
    exit;
}

// ============ STATISTICS ============

if ($action === 'get_stats') {
    $stats = [];
    
    // Helper to count
    $count = function($sql) use ($conn) {
        $result = mysqli_query($conn, $sql);
        if (!$result) return 0;
        $row = mysqli_fetch_row($result);
        return $row ? (int)$row[0] : 0;
    };
    
    // Check if tables exist before counting
    $hasIntents = mysqli_query($conn, "SHOW TABLES LIKE 'intents'");
    if ($hasIntents && mysqli_num_rows($hasIntents) > 0) {
        $stats['intents'] = $count("SELECT COUNT(*) FROM intents");
        $stats['phrases'] = $count("SELECT COUNT(*) FROM training_phrases");
        $stats['responses'] = $count("SELECT COUNT(*) FROM intent_responses");
    } else {
        $stats['intents'] = 0;
        $stats['phrases'] = 0;
        $stats['responses'] = 0;
    }
    
    $hasChatbot = mysqli_query($conn, "SHOW TABLES LIKE 'chatbot'");
    $stats['chatbot'] = ($hasChatbot && mysqli_num_rows($hasChatbot) > 0) ? $count("SELECT COUNT(*) FROM chatbot") : 0;
    
    $stats['learning'] = $count("SELECT COUNT(*) FROM learning");
    $stats['history_total'] = $count("SELECT COUNT(*) FROM history_chat");
    $stats['history_matched'] = $count("SELECT COUNT(*) FROM history_chat WHERE replay = '1'");
    $stats['history_unmatched'] = $count("SELECT COUNT(*) FROM history_chat WHERE replay = '0'");
    
    // Feedback stats
    $feedback = [];
    $hasFeedback = mysqli_query($conn, "SHOW TABLES LIKE 'feedback'");
    if ($hasFeedback && mysqli_num_rows($hasFeedback) > 0) {
        $feedback['thumbs_up'] = $count("SELECT COUNT(*) FROM feedback WHERE feedback_type = 'thumbs_up'");
        $feedback['thumbs_down'] = $count("SELECT COUNT(*) FROM feedback WHERE feedback_type = 'thumbs_down'");
    } else {
        $feedback['thumbs_up'] = 0;
        $feedback['thumbs_down'] = 0;
    }
    
    echo json_encode(['ok' => true, 'stats' => $stats, 'feedback' => $feedback]);
    exit;
}

// Unknown action
echo json_encode(['ok' => false, 'error' => 'Unknown action: ' . $action]);
exit;
?>

