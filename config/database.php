<?php
// database.php - Database Connection for the API

// Load environment variables (if you are using .env for local development, use php dotenv)
$host = getenv('HOST');     // PostgreSQL host
$dbname = getenv('DBNAME'); // Database name
$user = getenv('USERNAME'); // Database username
$password = getenv('PASSWORD'); // Database password

try {
    // Create a new PDO connection to PostgreSQL using environment variables
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    
    // Set the PDO error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Set the character set to UTF-8 for better compatibility
    $pdo->exec("SET NAMES 'utf8'");
    
    // Optionally, you can log the successful connection for debugging
    // error_log("Database connection established successfully.", 0);

} catch (PDOException $e) {
    // In production, you might want to log the error instead of displaying it
    // For local development, it's okay to display the error message
    error_log("Connection failed: " . $e->getMessage(), 0);
    
    // User-friendly error message
    echo "Database connection failed. Please try again later.";
    exit;
}

// Return the PDO instance so it can be used elsewhere in the application
return $pdo;
?>
