<?php
// Include the database connection
include_once 'databaseConnection.php';  // Use the correct relative path to the databaseConnection.php file

// Create a DatabaseConnection instance and connect to the database
$dbConnection = new DatabaseConnection();
$conn = $dbConnection->connect();

// Check if the connection was successful before proceeding
if ($conn) {
    // Query to get authors data from the 'authors' table
    $sql = 'SELECT * FROM authors';  // Replace 'authors' with your actual table name
    $stmt = $conn->query($sql);  // Use the connection to query the database

    // Fetch the data and return it as JSON
    $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($authors);
} else {
    // If connection fails, return an error message
    echo "Failed to connect to the database.";
}
?>