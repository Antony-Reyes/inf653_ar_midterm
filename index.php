<?php
// Include the database connection class
include 'databaseConnection.php';

// Create a new instance of the DatabaseConnection class
$dbConnection = new DatabaseConnection();

// Attempt to connect to the database
$conn = $dbConnection->connect();

// Check if the connection was successful
if ($conn) {
    echo "<h1>Welcome to Your Application!</h1>";
    echo "<p>Database connection successful!</p>";
} else {
    echo "<h1>Welcome to Your Application!</h1>";
    echo "<p>There was a problem connecting to the database.</p>";
}
?>
