<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database and author model
include_once '../../config/database.php';
include_once '../../models/Author.php';

// Instantiate Database
$database = new Database();
$db = $database->connect();

// Instantiate Author object
$author = new Author($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Validate required field
if (!isset($data->id)) {
    echo json_encode(["message" => "Missing required fields"]);
    exit;
}

// Assign ID to Author object
$author->id = intval($data->id);

// Attempt to delete the author
if ($author->delete()) {
    echo json_encode(["message" => "Author deleted successfully"]);
} else {
    echo json_encode(["message" => "Failed to delete author"]);
}
?>
