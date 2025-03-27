<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/Category.php';

// Database Connection
$database = new Database();
$db = $database->connect();

// Initialize Category Model
$category = new Category($db);

// Get raw DELETE data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $category->id = $data->id;

    if ($category->delete()) {
        echo json_encode(["message" => "Category deleted successfully."]);
    } else {
        echo json_encode(["message" => "Failed to delete category."]);
    }
} else {
    echo json_encode(["message" => "Category ID is required."]);
}
?>
