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

// Get ID from URL
$author->id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch author
$author->read_single();

if ($author->name) {
    echo json_encode(["id" => $author->id, "name" => $author->name]);
} else {
    echo json_encode(["message" => "Author not found"]);
}
?>
