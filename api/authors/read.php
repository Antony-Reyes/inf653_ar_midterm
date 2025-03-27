<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include database and author model
include_once '../../config/database.php';
include_once '../../models/Author.php';

// Instantiate Database
$database = new Database();
$db = $database->connect();

// Instantiate Author object
$author = new Author($db);

// Get authors
$result = $author->read();
$num = $result->rowCount();

if ($num > 0) {
    $authors_arr = ["data" => []];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $authors_arr["data"][] = ["id" => $id, "name" => $name];
    }

    echo json_encode($authors_arr);
} else {
    echo json_encode(["message" => "No authors found"]);
}
?>
