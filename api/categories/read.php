<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/Category.php';

// Database Connection
$database = new Database();
$db = $database->connect();

// Initialize Category Model
$category = new Category($db);
$result = $category->read();

if ($result->rowCount() > 0) {
    $categories_arr = ["data" => []];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $categories_arr["data"][] = [
            "id" => $id,
            "name" => $name
        ];
    }
    echo json_encode($categories_arr, JSON_PRETTY_PRINT);
} else {
    echo json_encode(["message" => "No categories found."]);
}
?>
