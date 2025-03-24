<?php
require 'database.php';

header("Content-Type: application/json");

$database = new Database();
$pdo = $database->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Example: Fetch all quotes (modify as needed)
        $stmt = $pdo->query("SELECT * FROM quotes");
        $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($quotes);
        break;

    case 'POST':
        // Example: Insert a new quote (modify as needed)
        $data = json_decode(file_get_contents("php://input"), true);
        if (!empty($data['quote']) && !empty($data['author_id']) && !empty($data['category_id'])) {
            $stmt = $pdo->prepare("INSERT INTO quotes (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)");
            $stmt->execute([
                ':quote' => $data['quote'],
                ':author_id' => $data['author_id'],
                ':category_id' => $data['category_id']
            ]);
            echo json_encode(["message" => "Quote added successfully"]);
        } else {
            echo json_encode(["error" => "Invalid input"]);
        }
        break;

    case 'PUT':
        // Example: Update a quote (modify as needed)
        $data = json_decode(file_get_contents("php://input"), true);
        if (!empty($data['id']) && !empty($data['quote'])) {
            $stmt = $pdo->prepare("UPDATE quotes SET quote = :quote WHERE id = :id");
            $stmt->execute([
                ':quote' => $data['quote'],
                ':id' => $data['id']
            ]);
            echo json_encode(["message" => "Quote updated successfully"]);
        } else {
            echo json_encode(["error" => "Invalid input"]);
        }
        break;

    case 'DELETE':
        // Example: Delete a quote (modify as needed)
        $data = json_decode(file_get_contents("php://input"), true);
        if (!empty($data['id'])) {
            $stmt = $pdo->prepare("DELETE FROM quotes WHERE id = :id");
            $stmt->execute([':id' => $data['id']]);
            echo json_encode(["message" => "Quote deleted successfully"]);
        } else {
            echo json_encode(["error" => "Invalid input"]);
        }
        break;

    default:
        echo json_encode(["error" => "Unsupported request method"]);
        break;
}
