<?php
// Load environment variables
$host = getenv("DB_HOST") ?: "localhost"; // Use Render DB_HOST or default to localhost
$username = getenv("DB_USER") ?: "root"; // Use Render DB_USER or default to root
$password = getenv("DB_PASSWORD") ?: ""; // Use Render DB_PASSWORD or default to empty
$dbname = getenv("DB_NAME") ?: "DB_NAME"; // Use Render DB_NAME or default
$port = getenv("DB_PORT") ?: "3306"; // Use Render DB_PORT or default to MySQL 3306

try {
    // Create a PDO connection
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable error reporting
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch as associative array
        PDO::ATTR_EMULATE_PREPARES => false, // Use real prepared statements
    ]);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage()); // Log error
    die(json_encode(["error" => "Database connection failed. Please check logs."]));
}
?>
