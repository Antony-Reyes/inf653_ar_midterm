<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/Quote.php';

// Initialize DB connection
$database = new Database();
$db = $database->connect();

$quote = new Quote($db);
$result = $quote->read();

if ($result->rowCount() > 0) {
    $quotes_arr = array("data" => array());

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $quotes_arr["data"][] = array(
            "id" => $id,
            "quote" => $quote,
            "author" => $author_name,
            "category" => $category_name
        );
    }

    echo json_encode($quotes_arr);
} else {
    echo json_encode(array("message" => "No quotes found."));
}
?>
