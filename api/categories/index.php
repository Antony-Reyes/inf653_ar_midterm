<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD']; // Fix: Use $_SERVER instead of $server

if ($method == 'OPTIONS') { // Fix: Use == instead of =
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

// Include Database Connection
include_once '../../databaseConnection.php';
include_once '../../models/quotes.php'; // Adjust based on the folder structure

// Initialize DB connection
$database = new Database();
$db = $database->connect();

$categories = new Categories($db);
$result = $categories->read(); // Assuming a read() function exists in Authors.php

if ($result->rowCount() > 0) {
    $categories_arr = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $categories_arr[] = ["id" => $id, "name" => $name];
    }
    echo json_encode($categories_arr);
} else {
    echo json_encode(["message" => "No authors found"]);
}
?>

