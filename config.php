<?php
// Basic configuration for database connection. Do not commit real secrets.

$DB_HOST = getenv('CHATBOT_DB_HOST') ?: 'localhost';
$DB_USER = getenv('CHATBOT_DB_USER') ?: 'root';
$DB_PASS = getenv('CHATBOT_DB_PASS') ?: 'oracleoracle';
$DB_NAME = getenv('CHATBOT_DB_NAME') ?: 'bot';
$DB_PORT = getenv('CHATBOT_DB_PORT') ?: 3306;
$DB_SOCKET = getenv('CHATBOT_DB_SOCKET') ?: null; // e.g., /tmp/mysql.sock

// Prefer TCP on macOS to avoid socket "No such file or directory" when using localhost
$hostForTcp = ($DB_HOST === 'localhost') ? '127.0.0.1' : $DB_HOST;

mysqli_report(MYSQLI_REPORT_OFF);

// Try TCP first
$conn = @mysqli_init();
if ($conn) {
    @mysqli_real_connect($conn, $hostForTcp, $DB_USER, $DB_PASS, $DB_NAME, (int)$DB_PORT, null, 0);
}

// If failed and a socket is provided, try socket explicitly
if (!$conn || mysqli_connect_errno()) {
    $conn = @mysqli_init();
    if ($conn) {
        @mysqli_real_connect($conn, null, $DB_USER, $DB_PASS, $DB_NAME, null, $DB_SOCKET, 0);
    }
}

if (!$conn || mysqli_connect_errno()) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'ok' => false,
        'error' => 'Database Error',
        'details' => mysqli_connect_error(),
        'host' => $hostForTcp,
        'port' => (int)$DB_PORT,
        'socket' => $DB_SOCKET,
    ]);
    exit;
}

mysqli_set_charset($conn, 'utf8mb4');

// Matching configuration
// Confidence threshold: 0-100. Only match if similarity score is above this percentage
// Lower = more lenient (matches more), Higher = stricter (only close matches)
$CONFIDENCE_THRESHOLD = getenv('CHATBOT_CONFIDENCE_THRESHOLD') !== false
    ? max(0, min(100, (int)getenv('CHATBOT_CONFIDENCE_THRESHOLD')))
    : 60; // Default: 60% similarity required

?>

