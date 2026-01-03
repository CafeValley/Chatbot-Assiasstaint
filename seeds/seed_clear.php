<?php
/**
 * CLEAR DATABASE - Remove All Intent Data
 * 
 * This script clears all intents, training phrases, and responses.
 * Use this to reset the database before running individual seeds.
 * 
 * Usage: php seeds/seed_clear.php
 * 
 * WARNING: This will delete ALL intent data!
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/seed_helper.php';

echo "\n";
echo "╔═══════════════════════════════════════════════════════╗\n";
echo "║     CLEAR DATABASE - REMOVE ALL INTENT DATA           ║\n";
echo "╚═══════════════════════════════════════════════════════╝\n\n";

// Get counts before clearing
$intentCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM intents"))['count'];
$phraseCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM training_phrases"))['count'];
$responseCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM intent_responses"))['count'];

echo "Current data:\n";
echo "  - Intents: $intentCount\n";
echo "  - Training Phrases: $phraseCount\n";
echo "  - Responses: $responseCount\n\n";

clearAllIntentData($conn);

echo "Database cleared successfully!\n\n";
echo "Run individual seeds or 'php seeds/seed_all.php' to populate.\n\n";

mysqli_close($conn);


