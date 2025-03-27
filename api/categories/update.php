<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/Category.php';

// Database Connection
$database = new Database();
$db = $database->connect();

// Initialize Category Model
$category = new Category($db);

// Get raw PUT data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id) && !empty($data->name)) {
    $category->id = $data->id;
    $category->name = $data->name;

    if ($category->update()) {
        echo json_encode(["message" => "Category updated successfully."]);
    } else {
        echo json_encode(["message" => "Failed to update category."]);
    }
} else {
    echo json_encode(["message" => "Category ID and name are required."]);
}
?>
