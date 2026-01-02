<?php
/**
 * Migration Script: Convert flat chatbot Q&A to Intent-Based Structure
 * 
 * This script migrates existing data from the `chatbot` table to the new
 * intent-based architecture with `intents`, `training_phrases`, and `responses` tables.
 * 
 * Usage: 
 *   php migrate_to_intents.php
 *   OR access via browser (not recommended for large datasets)
 * 
 * IMPORTANT: Backup your database before running this script!
 */

require_once __DIR__ . '/config.php';

// Set content type for CLI vs browser
$isCli = php_sapi_name() === 'cli';
if (!$isCli) {
    header('Content-Type: text/plain; charset=utf-8');
}

function output($msg) {
    global $isCli;
    echo $msg . ($isCli ? "\n" : "<br>\n");
    if (!$isCli) flush();
}

function normalizeText($text) {
    if ($text === null || $text === '') {
        return '';
    }
    $text = function_exists('mb_strtolower') ? mb_strtolower($text, 'UTF-8') : strtolower($text);
    $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
    $text = preg_replace('/\s+/', ' ', $text);
    return trim($text);
}

output("=== Chatbot Migration to Intent-Based Structure ===");
output("");

// Check if new tables exist
$tablesCheck = mysqli_query($conn, "SHOW TABLES LIKE 'intents'");
if (!$tablesCheck || mysqli_num_rows($tablesCheck) === 0) {
    output("ERROR: 'intents' table does not exist.");
    output("Please run botv4.sql first to create the required tables.");
    exit(1);
}

// Check if chatbot table exists
$chatbotTableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'chatbot'");
if (!$chatbotTableCheck || mysqli_num_rows($chatbotTableCheck) === 0) {
    output("'chatbot' table does not exist. Nothing to migrate.");
    output("You can start fresh with the intent-based system.");
    exit(0);
}

// Check if chatbot table has data
$chatbotCount = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM chatbot");
if (!$chatbotCount) {
    output("Could not query chatbot table: " . mysqli_error($conn));
    exit(1);
}
$chatbotRow = mysqli_fetch_assoc($chatbotCount);
$totalEntries = (int)$chatbotRow['cnt'];

if ($totalEntries === 0) {
    output("No entries found in chatbot table. Nothing to migrate.");
    exit(0);
}

output("Found {$totalEntries} entries in chatbot table to migrate.");
output("");

// Begin migration
$migrated = 0;
$errors = [];

// Fetch all chatbot entries
$result = mysqli_query($conn, "SELECT id, queries, replies FROM chatbot ORDER BY id ASC");

while ($row = mysqli_fetch_assoc($result)) {
    $query = trim($row['queries']);
    $replyData = $row['replies'];
    
    if ($query === '') {
        $errors[] = "Entry #{$row['id']}: Empty query, skipped";
        continue;
    }
    
    // Generate intent name from query (first 50 chars, sanitized)
    $intentName = preg_replace('/[^a-zA-Z0-9\s]/', '', $query);
    $intentName = preg_replace('/\s+/', '_', trim($intentName));
    $intentName = substr($intentName, 0, 50);
    if ($intentName === '') {
        $intentName = 'intent_' . $row['id'];
    }
    
    // Check if intent already exists (by name)
    $checkStmt = mysqli_prepare($conn, "SELECT id FROM intents WHERE name = ?");
    mysqli_stmt_bind_param($checkStmt, 's', $intentName);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);
    $existingIntent = mysqli_fetch_assoc($checkResult);
    mysqli_stmt_close($checkStmt);
    
    $intentId = null;
    
    if ($existingIntent) {
        // Intent exists, use it
        $intentId = (int)$existingIntent['id'];
    } else {
        // Create new intent
        $description = "Auto-migrated from chatbot entry #{$row['id']}";
        $insertIntent = mysqli_prepare($conn, "INSERT INTO intents (name, description) VALUES (?, ?)");
        mysqli_stmt_bind_param($insertIntent, 'ss', $intentName, $description);
        
        if (!mysqli_stmt_execute($insertIntent)) {
            // Try with unique suffix if name collision
            $intentName = $intentName . '_' . $row['id'];
            mysqli_stmt_bind_param($insertIntent, 'ss', $intentName, $description);
            if (!mysqli_stmt_execute($insertIntent)) {
                $errors[] = "Entry #{$row['id']}: Failed to create intent - " . mysqli_stmt_error($insertIntent);
                mysqli_stmt_close($insertIntent);
                continue;
            }
        }
        $intentId = mysqli_insert_id($conn);
        mysqli_stmt_close($insertIntent);
    }
    
    // Insert training phrase
    $normalizedPhrase = normalizeText($query);
    $insertPhrase = mysqli_prepare($conn, "INSERT INTO training_phrases (intent_id, phrase, phrase_normalized) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($insertPhrase, 'iss', $intentId, $query, $normalizedPhrase);
    
    if (!mysqli_stmt_execute($insertPhrase)) {
        $errors[] = "Entry #{$row['id']}: Failed to create training phrase - " . mysqli_stmt_error($insertPhrase);
        mysqli_stmt_close($insertPhrase);
        continue;
    }
    mysqli_stmt_close($insertPhrase);
    
    // Handle replies (could be JSON array or single string)
    $replies = json_decode($replyData, true);
    if (!is_array($replies)) {
        $replies = [$replyData];
    }
    
    // Insert responses
    $insertResponse = mysqli_prepare($conn, "INSERT INTO intent_responses (intent_id, response, confidence) VALUES (?, ?, 1.0)");
    foreach ($replies as $reply) {
        $reply = trim($reply);
        if ($reply === '' || $reply === '?') continue;
        
        mysqli_stmt_bind_param($insertResponse, 'is', $intentId, $reply);
        if (!mysqli_stmt_execute($insertResponse)) {
            $errors[] = "Entry #{$row['id']}: Failed to create response - " . mysqli_stmt_error($insertResponse);
        }
    }
    mysqli_stmt_close($insertResponse);
    
    $migrated++;
    
    if ($migrated % 100 === 0) {
        output("Progress: {$migrated} / {$totalEntries} entries processed...");
    }
}

output("");
output("=== Migration Complete ===");
output("Successfully migrated: {$migrated} entries");
output("Errors: " . count($errors));

if (!empty($errors)) {
    output("");
    output("Error details:");
    foreach ($errors as $error) {
        output("  - " . $error);
    }
}

// Show summary stats
$intentsCount = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM intents");
$phrasesCount = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM training_phrases");
$responsesCount = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM intent_responses");

output("");
output("=== New Table Stats ===");
output("Intents: " . mysqli_fetch_assoc($intentsCount)['cnt']);
output("Training Phrases: " . mysqli_fetch_assoc($phrasesCount)['cnt']);
output("Responses: " . mysqli_fetch_assoc($responsesCount)['cnt']);
output("");
output("NOTE: The original 'chatbot' table has been preserved for rollback if needed.");
output("You can safely drop it after verifying the migration: DROP TABLE chatbot;");
?>

