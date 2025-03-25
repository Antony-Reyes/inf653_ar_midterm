<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/database.php";

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$database = new Database();
$conn = $database->connect();

$method = $_SERVER["REQUEST_METHOD"];

// Handle OPTIONS request for CORS preflight
if ($method === "OPTIONS") {
    http_response_code(200);
    exit();
}

try {
    if ($method == "GET") {
        if (isset($_GET["id"])) {
            $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
            $stmt->execute([$_GET["id"]]);
            $category = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($category) {
                echo json_encode($category);
            } else {
                echo json_encode(["error" => "Category Not Found"]);
                http_response_code(404);
            }
        } else {
            $stmt = $conn->query("SELECT * FROM categories");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    } elseif ($method == "POST") {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["category"]) || empty($data["category"])) {
            echo json_encode(["error" => "Missing category name"]);
            http_response_code(400);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO categories (category) VALUES (?)");
        $stmt->execute([$data["category"]]);

        echo json_encode(["message" => "Category inserted successfully", "id" => $conn->lastInsertId()]);
        http_response_code(201);
    } elseif ($method == "PUT") {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["id"], $data["category"]) || empty($data["id"]) || empty($data["category"])) {
            echo json_encode(["error" => "Missing required parameters"]);
            http_response_code(400);
            exit();
        }

        $stmt = $conn->prepare("UPDATE categories SET category = ? WHERE id = ?");
        $stmt->execute([$data["category"], $data["id"]]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["message" => "Category updated successfully", "id" => $data["id"], "category" => $data["category"]]);
        } else {
            echo json_encode(["error" => "Category not found or no changes made"]);
            http_response_code(404);
        }
    } elseif ($method == "DELETE") {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["id"]) || empty($data["id"])) {
            echo json_encode(["error" => "Missing category ID"]);
            http_response_code(400);
            exit();
        }

        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$data["id"]]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["message" => "Category deleted successfully", "id" => $data["id"]]);
        } else {
            echo json_encode(["error" => "Category not found"]);
            http_response_code(404);
        }
    } else {
        echo json_encode(["error" => "Unsupported request method"]);
        http_response_code(405);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    http_response_code(500);
}
?>
