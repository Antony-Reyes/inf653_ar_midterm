<?php
// Headers to allow API access and set response type as JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include database and quote model
include_once '../../config/database.php';
include_once '../../models/Quote.php';

// Instantiate Database
$database = new Database();
$db = $database->connect();

// Instantiate Quote object
$quote = new Quote($db);

// Check if an ID is provided in the request
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(["message" => "Missing quote ID"]);
    exit;
}

// Get the ID from the request
$quote->id = intval($_GET['id']);

// Fetch the single quote
$result = $quote->read_single();

if ($result) {
    // Create an array with the quote data
    $quote_arr = [
        "id" => $quote->id,
        "quote" => $quote->quote,
        "author" => $quote->author_name,
        "category" => $quote->category_name
    ];

    // Convert to JSON and output response
    echo json_encode($quote_arr, JSON_PRETTY_PRINT);
} else {
    echo json_encode(["message" => "Quote not found"]);
}
?>