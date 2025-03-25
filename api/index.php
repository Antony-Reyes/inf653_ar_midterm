<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit();
}

require __DIR__ . '/database.php'; // Ensure correct path

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Allow API access from any origin
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$database = new Database();
$pdo = $database->connect();

$method = $_SERVER['REQUEST_METHOD'];

// Handle OPTIONS request for CORS preflight
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    switch ($method) {
        case 'GET':
            // Fetch all quotes with author and category names
            $stmt = $pdo->query("
                SELECT q.id, q.quote, a.author, c.category 
                FROM quotes q
                JOIN authors a ON q.author_id = a.id
                JOIN categories c ON q.category_id = c.id
            ");
            $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($quotes);
            break;

        case 'POST':
            // Insert a new quote
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['quote'], $data['author_id'], $data['category_id'])) {
                echo json_encode(["error" => "Missing required fields"]);
                http_response_code(400);
                exit();
            }

            $stmt = $pdo->prepare("INSERT INTO quotes (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)");
            $stmt->execute([
                ':quote' => $data['quote'],
                ':author_id' => $data['author_id'],
                ':category_id' => $data['category_id']
            ]);
            echo json_encode(["message" => "Quote added successfully", "id" => $pdo->lastInsertId()]);
            break;

        case 'PUT':
            // Update an existing quote
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['id'], $data['quote'])) {
                echo json_encode(["error" => "Missing required fields"]);
                http_response_code(400);
                exit();
            }

            $stmt = $pdo->prepare("UPDATE quotes SET quote = :quote WHERE id = :id");
            $stmt->execute([
                ':quote' => $data['quote'],
                ':id' => $data['id']
            ]);
            echo json_encode(["message" => "Quote updated successfully"]);
            break;

        case 'DELETE':
            // Delete a quote
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['id'])) {
                echo json_encode(["error" => "Missing quote ID"]);
                http_response_code(400);
                exit();
            }

            $stmt = $pdo->prepare("DELETE FROM quotes WHERE id = :id");
            $stmt->execute([':id' => $data['id']]);
            echo json_encode(["message" => "Quote deleted successfully"]);
            break;

        default:
            echo json_encode(["error" => "Unsupported request method"]);
            http_response_code(405);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    http_response_code(500);
}