<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once '../../config/database.php';
include_once '../../models/Author.php';
include_once '../../models/Category.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();

$quote = new Quote($db);
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id) && !empty($data->quote)) {
    $quote->id = $data->id;
    $quote->quote = $data->quote;

    if ($quote->update()) {
        echo json_encode(["message" => "Quote updated successfully."]);
    } else {
        echo json_encode(["message" => "Quote update failed."]);
    }
} else {
    echo json_encode(["message" => "Missing required fields."]);
}
?>
