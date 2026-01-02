<?php
/**
 * Seed Helper Functions
 * 
 * Shared functions used by all seed files.
 */

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

/**
 * Insert seed data into the database
 * 
 * @param mysqli $conn Database connection
 * @param array $faqData Array of FAQ data
 * @param string $category Category name for display
 */
function insertSeedData($conn, $faqData, $category) {
    $totalIntents = 0;
    $totalPhrases = 0;
    $totalResponses = 0;
    
    foreach ($faqData as $intent) {
        // Check if intent already exists
        $checkStmt = mysqli_prepare($conn, "SELECT id FROM intents WHERE name = ?");
        mysqli_stmt_bind_param($checkStmt, 's', $intent['name']);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);
        $existing = mysqli_fetch_assoc($checkResult);
        mysqli_stmt_close($checkStmt);
        
        if ($existing) {
            echo "  ⏭ Skipped (exists): {$intent['name']}\n";
            continue;
        }
        
        // Insert intent
        $stmt = mysqli_prepare($conn, "INSERT INTO intents (name, description, is_active) VALUES (?, ?, 1)");
        mysqli_stmt_bind_param($stmt, 'ss', $intent['name'], $intent['description']);
        
        if (!mysqli_stmt_execute($stmt)) {
            echo "  ✗ Failed: {$intent['name']} - " . mysqli_error($conn) . "\n";
            mysqli_stmt_close($stmt);
            continue;
        }
        
        $intentId = mysqli_insert_id($conn);
        $totalIntents++;
        mysqli_stmt_close($stmt);
        
        // Insert training phrases
        $phraseStmt = mysqli_prepare($conn, "INSERT INTO training_phrases (intent_id, phrase, phrase_normalized) VALUES (?, ?, ?)");
        foreach ($intent['phrases'] as $phrase) {
            $normalized = normalizeText($phrase);
            mysqli_stmt_bind_param($phraseStmt, 'iss', $intentId, $phrase, $normalized);
            if (mysqli_stmt_execute($phraseStmt)) {
                $totalPhrases++;
            }
        }
        mysqli_stmt_close($phraseStmt);
        
        // Insert responses
        $respStmt = mysqli_prepare($conn, "INSERT INTO intent_responses (intent_id, response, confidence, is_active) VALUES (?, ?, 1.0, 1)");
        foreach ($intent['responses'] as $response) {
            mysqli_stmt_bind_param($respStmt, 'is', $intentId, $response);
            if (mysqli_stmt_execute($respStmt)) {
                $totalResponses++;
            }
        }
        mysqli_stmt_close($respStmt);
        
        echo "  ✓ {$intent['name']}: " . count($intent['phrases']) . " phrases, " . count($intent['responses']) . " responses\n";
    }
    
    echo "\n";
    echo "-------------------------------------------\n";
    echo "  $category Complete!\n";
    echo "  - Intents added: $totalIntents\n";
    echo "  - Phrases added: $totalPhrases\n";
    echo "  - Responses added: $totalResponses\n";
    echo "-------------------------------------------\n\n";
    
    return ['intents' => $totalIntents, 'phrases' => $totalPhrases, 'responses' => $totalResponses];
}

/**
 * Clear all intent data from the database
 */
function clearAllIntentData($conn) {
    echo "Clearing existing intent data...\n";
    
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");
    
    $tables = ['intent_responses', 'training_phrases', 'intents'];
    foreach ($tables as $table) {
        $result = mysqli_query($conn, "TRUNCATE TABLE `$table`");
        if ($result) {
            echo "  ✓ Cleared: $table\n";
        } else {
            echo "  ✗ Failed: $table - " . mysqli_error($conn) . "\n";
        }
    }
    
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
    echo "\n";
}

