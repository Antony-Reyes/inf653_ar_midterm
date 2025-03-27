<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/Category.php';

// Database Connection
$database = new Database();
$db = $database->connect();

// Initialize Category Model
$category = new Category($db);

// Get raw POST data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->name)) {
    $category->name = $data->name;

    if ($category->create()) {
        echo json_encode(["message" => "Category created successfully."]);
    } else {
        echo json_encode(["message" => "Failed to create category."]);
    }
} else {
    echo json_encode(["message" => "Category name is required."]);
}
?>
