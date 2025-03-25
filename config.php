<?php
// Load environment variables
$host = getenv("DB_HOST") ?: "44.226.145.213"; // Use Render DB_HOST or default to localhost
$username = getenv("DB_USER") ?: ""; // Use Render DB_USER (ensure it's set!)
$password = getenv("DB_PASSWORD") ?: ""; // Use Render DB_PASSWORD
$dbname = getenv("DB_NAME") ?: "DB_NAME"; // Use Render DB_NAME
$port = getenv("DB_PORT") ?: "3306"; // Use Render DB_PORT (default 3306 for MySQL)

try {
    // Create a PDO connection
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable error reporting
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch as associative array
        PDO::ATTR_EMULATE_PREPARES => false, // Use real prepared statements
    ]);
} catch (PDOException $e) {
    error_log("❌ Database connection failed: " . $e->getMessage()); // Log error
    http_response_code(500); // Return HTTP 500 error
    die(json_encode(["error" => "Database connection failed. Check server logs."]));
}
?>
