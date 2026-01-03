<?php
/**
 * MASTER SEED FILE - Run All Industry Seeds
 * 
 * This script clears the database and runs all seed files.
 * 
 * Usage: 
 *   php seeds/seed_all.php          - Clear and run all seeds
 *   php seeds/seed_all.php --keep   - Keep existing data, add new seeds
 * 
 * Industries included:
 *   - Medical / Healthcare
 *   - Sales / Business
 *   - Management / HR
 *   - Stone / Construction
 *   - E-commerce / Online Store
 *   - Restaurant / Food Service
 *   - Banking / Finance
 *   - Real Estate
 *   - Technical Support
 *   - General Customer Service
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/seed_helper.php';

echo "\n";
echo "╔═══════════════════════════════════════════════════════╗\n";
echo "║     DATABASE SEEDER - ALL INDUSTRIES                  ║\n";
echo "╚═══════════════════════════════════════════════════════╝\n\n";

// Check for --keep flag
$keepExisting = in_array('--keep', $argv ?? []);

if (!$keepExisting) {
    clearAllIntentData($conn);
} else {
    echo "Mode: APPEND (keeping existing data)\n\n";
}

// Track totals
$totals = ['intents' => 0, 'phrases' => 0, 'responses' => 0];

// List of seed files to run
$seedFiles = [
    'seed_general.php'     => 'General Customer Service',
    'seed_medical.php'     => 'Medical / Healthcare',
    'seed_sales.php'       => 'Sales / Business',
    'seed_management.php'  => 'Management / HR',
    'seed_stone.php'       => 'Stone / Construction',
    'seed_ecommerce.php'   => 'E-commerce / Online Store',
    'seed_restaurant.php'  => 'Restaurant / Food Service',
    'seed_banking.php'     => 'Banking / Finance',
    'seed_realestate.php'  => 'Real Estate',
    'seed_techsupport.php' => 'Technical Support',
];

echo "Running " . count($seedFiles) . " seed files...\n\n";

foreach ($seedFiles as $file => $name) {
    $path = __DIR__ . '/' . $file;
    
    if (file_exists($path)) {
        // Load the file content to extract $faqData
        $content = file_get_contents($path);
        
        // We need to include and run the seed
        echo "-------------------------------------------\n";
        echo "  Loading: $name\n";
        echo "-------------------------------------------\n";
        
        // Include the seed file (it will output its own results)
        include $path;
        
    } else {
        echo "  ⚠ File not found: $file\n";
    }
}

// Get final counts from database
$intentCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM intents"))['count'];
$phraseCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM training_phrases"))['count'];
$responseCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM intent_responses"))['count'];

echo "\n";
echo "╔═══════════════════════════════════════════════════════╗\n";
echo "║     SEEDING COMPLETE!                                 ║\n";
echo "╠═══════════════════════════════════════════════════════╣\n";
printf("║  Total Intents:          %-5s                       ║\n", $intentCount);
printf("║  Total Training Phrases: %-5s                       ║\n", $phraseCount);
printf("║  Total Responses:        %-5s                       ║\n", $responseCount);
echo "╠═══════════════════════════════════════════════════════╣\n";
echo "║  Test your chatbot at: http://localhost:8000/bot.php ║\n";
echo "║  Admin panel at:       http://localhost:8000/admin.php║\n";
echo "╚═══════════════════════════════════════════════════════╝\n\n";

mysqli_close($conn);


