<?php
// Headers to allow API access and set response type as JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
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

// Validate required fields
if (!isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
    echo json_encode(["message" => "Missing required fields"]);
    exit;
}

// Assign data to Quote object
$quote->quote = htmlspecialchars(strip_tags($data->quote));
$quote->author_id = intval($data->author_id);
$quote->category_id = intval($data->category_id);

// Attempt to create the quote
if ($quote->create()) {
    echo json_encode(["message" => "Quote created successfully"]);
} else {
    echo json_encode(["message" => "Failed to create quote"]);
}
?>
