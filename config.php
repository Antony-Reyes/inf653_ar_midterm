<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Default username for MySQL in XAMPP
$password = ""; // Default password is empty in XAMPP unless you set one
$dbname = "quotesdb"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
