<?php
// database.php - Database Connection for the API

// Get the environment variables
$host = getenv('HOST'); // PostgreSQL host
$dbname = getenv('DBNAME'); // Database name
$user = getenv('USERNAME'); // Database username
$password = getenv('PASSWORD'); // Database password

try {
    // Create a new PDO connection to PostgreSQL using environment variables
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Set the character set to UTF-8
    $pdo->exec("SET NAMES 'utf8'");
    
    // Success message (Optional)
    // echo "Database connection established successfully.";

} catch (PDOException $e) {
    // If connection fails, display the error message
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
