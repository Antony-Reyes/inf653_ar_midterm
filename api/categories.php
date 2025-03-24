<?php
include '../config.php';
require_once "database.php";

$database = new Database();
$conn = $database->connect();

$method = $_SERVER["REQUEST_METHOD"];

if ($method == "GET") {
    if (isset($_GET["id"])) {
        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$_GET["id"]]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($category ?: ["message" => "Category Not Found"]);
    } else {
        $stmt = $conn->query("SELECT * FROM categories");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} elseif ($method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Define categories to insert
    $categories = [
        'Motivational',
        'Humor',
        'Inspiration',
        'Philosophy',
        'Politics'
    ];
    
    // Insert each category into the categories table
    foreach ($categories as $category) {
        $stmt = $conn->prepare("INSERT INTO categories (category) VALUES (?)");
        $stmt->execute([$category]);
    }
    
    echo json_encode(["message" => "Categories inserted successfully"]);
} elseif ($method == "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data["id"], $data["category"])) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit;
    }
    $stmt = $conn->prepare("UPDATE categories SET category = ? WHERE id = ?");
    $stmt->execute([$data["category"], $data["id"]]);
    echo json_encode(["id" => $data["id"], "category" => $data["category"]]);
} elseif ($method == "DELETE") {
    if (!isset($_GET["id"])) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit;
    }
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$_GET["id"]]);
    echo json_encode(["id" => $_GET["id"]]);
}
?>
