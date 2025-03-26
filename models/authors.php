<?php
// authors.php

// Include the database connection
include 'database.php';

// Query to get authors data
$sql = 'SELECT * FROM authors'; // Replace 'authors' with your actual table name
$stmt = $pdo->query($sql);

// Fetch the data
$authors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($authors);
?>
