<?php
// Include your DatabaseConnection class
include 'DatabaseConnection.php';

// Create an instance of the DatabaseConnection class and try to connect
$dbConnection = new DatabaseConnection();
$conn = $dbConnection->connect();

// Check if the connection is successful
if ($conn) {
    echo "Connected to the database successfully!";
} else {
    echo "Failed to connect to the database!";
}
?>
