<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
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

// Validate required fields
if (!isset($data->name)) {
    echo json_encode(["message" => "Missing required fields"]);
    exit;
}

// Assign data to Author object
$author->name = htmlspecialchars(strip_tags($data->name));

// Attempt to create the author
if ($author->create()) {
    echo json_encode(["message" => "Author created successfully"]);
} else {
    echo json_encode(["message" => "Failed to create author"]);
}
?>
