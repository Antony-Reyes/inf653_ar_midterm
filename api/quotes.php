<?php
header("Content-Type: application/json");
include_once 'database.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT q.id, q.quote, a.name as author, c.name as category 
                            FROM quotes q
                            JOIN authors a ON q.author_id = a.id
                            JOIN categories c ON q.category_id = c.id");
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->quote) && !empty($data->author) && !empty($data->category)) {
        // Get or create author
        $stmt = $conn->prepare("SELECT id FROM authors WHERE name = :name");
        $stmt->bindParam(":name", $data->author);
        $stmt->execute();
        $author = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$author) {
            $stmt = $conn->prepare("INSERT INTO authors (name) VALUES (:name)");
            $stmt->bindParam(":name", $data->author);
            $stmt->execute();
            $author_id = $conn->lastInsertId();
        } else {
            $author_id = $author['id'];
        }

        // Get or create category
        $stmt = $conn->prepare("SELECT id FROM categories WHERE name = :name");
        $stmt->bindParam(":name", $data->category);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$category) {
            $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (:name)");
            $stmt->bindParam(":name", $data->category);
            $stmt->execute();
            $category_id = $conn->lastInsertId();
        } else {
            $category_id = $category['id'];
        }

        // Insert quote
        $stmt = $conn->prepare("INSERT INTO quotes (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)");
        $stmt->bindParam(":quote", $data->quote);
        $stmt->bindParam(":author_id", $author_id);
        $stmt->bindParam(":category_id", $category_id);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Quote added successfully"]);
        } else {
            echo json_encode(["message" => "Failed to add quote"]);
        }
    }
}
?>
