<?php
header("Content-Type: application/json");
include_once 'database.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT * FROM authors");
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data->name)) {
        $stmt = $conn->prepare("INSERT INTO authors (name) VALUES (:name)");
        $stmt->bindParam(":name", $data->name);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Author added successfully"]);
        } else {
            echo json_encode(["message" => "Failed to add author"]);
        }
    }
}
?>