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

// Query to get quotes
$result = $quote->read();
$num = $result->rowCount();

// Check if there are any quotes
if ($num > 0) {
    $quotes_arr = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $quotes_arr[] = array(
            "id" => $id,
            "quote" => $quote,
            "author" => $author_name,
            "category" => $category_name
        );
    }

    // Convert to JSON and output response
    echo json_encode(["data" => $quotes_arr], JSON_PRETTY_PRINT);
} else {
    echo json_encode(["message" => "No quotes found"]);
}
?>