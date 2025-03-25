<?php
// Load environment variables (for Render.com)
$host = getenv("DB_HOST") ?: "localhost"; // Default to localhost for local testing
$username = getenv("DB_USER") ?: "root"; // Default MySQL username for XAMPP
$password = getenv("DB_PASSWORD") ?: ""; // Default password (empty in XAMPP)
$dbname = getenv("INF653_AR_Midterm") ?: "INF653_AR_Midterm"; // Database name

try {
    // Create a PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable error reporting
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch as associative array
        PDO::ATTR_EMULATE_PREPARES => false, // Use real prepared statements
    ]);
} catch (PDOException $e) {
    die(json_encode(["error" => "Database connection failed: " . $e->getMessage()]));
}
?>

