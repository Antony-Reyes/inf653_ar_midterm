<?php
header("Content-Type: application/json");
include_once 'database.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data->name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->bindParam(":name", $data->name);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Category added successfully"]);
        } else {
            echo json_encode(["message" => "Failed to add category"]);
        }
    }
}
?>