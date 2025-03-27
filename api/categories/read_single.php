<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/Category.php';

// Database Connection
$database = new Database();
$db = $database->connect();

// Initialize Category Model
$category = new Category($db);

// Get category ID from URL
$category->id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(["message" => "Missing category ID."]));

$category->read_single();

if ($category->name) {
    echo json_encode([
        "id" => $category->id,
        "name" => $category->name
    ], JSON_PRETTY_PRINT);
} else {
    echo json_encode(["message" => "Category not found."]);
}
?>
