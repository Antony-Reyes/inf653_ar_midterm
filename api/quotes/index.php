<?php
// Headers to allow API access and set response type as JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database and quote model
include_once '../../config/database.php';
include_once '../../models/Quote.php';

// Instantiate Database
$database = new Database();
$db = $database->connect();

// Instantiate Quote object
$quote = new Quote($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Check if ID is provided
if (!isset($data->id)) {
    echo json_encode(["message" => "Missing required ID"]);
    exit;
}

// Assign ID to the Quote object
$quote->id = intval($data->id);

// Attempt to delete the quote
if ($quote->delete()) {
    echo json_encode(["message" => "Quote deleted successfully"]);
} else {
    echo json_encode(["message" => "Failed to delete quote"]);
}
?>
