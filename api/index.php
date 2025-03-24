<?php
header("Content-Type: application/json");
require 'database.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $stmt = $pdo->prepare("SELECT quotes.id, quotes.quote, authors.author, categories.category 
                               FROM quotes 
                               JOIN authors ON quotes.author_id = authors.id 
                               JOIN categories ON quotes.category_id = categories.id 
                               WHERE quotes.id = ?");
        $stmt->execute([$id]);
        $quote = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($quote) {
            echo json_encode($quote);
        } else {
            echo json_encode(["message" => "No Quotes Found"]);
        }
    } elseif (isset($_GET['author_id']) && isset($_GET['category_id'])) {
        $author_id = intval($_GET['author_id']);
        $category_id = intval($_GET['category_id']);
        $stmt = $pdo->prepare("SELECT quotes.id, quotes.quote, authors.author, categories.category 
                               FROM quotes 
                               JOIN authors ON quotes.author_id = authors.id 
                               JOIN categories ON quotes.category_id = categories.id 
                               WHERE authors.id = ? AND categories.id = ?");
        $stmt->execute([$author_id, $category_id]);
        $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($quotes ?: ["message" => "No Quotes Found"]);
    } elseif (isset($_GET['author_id'])) {
        $author_id = intval($_GET['author_id']);
        $stmt = $pdo->prepare("SELECT quotes.id, quotes.quote, authors.author, categories.category 
                               FROM quotes 
                               JOIN authors ON quotes.author_id = authors.id 
                               JOIN categories ON quotes.category_id = categories.id 
                               WHERE authors.id = ?");
        $stmt->execute([$author_id]);
        $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($quotes ?: ["message" => "No Quotes Found"]);
    } elseif (isset($_GET['category_id'])) {
        $category_id = intval($_GET['category_id']);
        $stmt = $pdo->prepare("SELECT quotes.id, quotes.quote, authors.author, categories.category 
                               FROM quotes 
                               JOIN authors ON quotes.author_id = authors.id 
                               JOIN categories ON quotes.category_id = categories.id 
                               WHERE categories.id = ?");
        $stmt->execute([$category_id]);
        $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($quotes ?: ["message" => "No Quotes Found"]);
    } else {
        $stmt = $pdo->query("SELECT quotes.id, quotes.quote, authors.author, categories.category 
                             FROM quotes 
                             JOIN authors ON quotes.author_id = authors.id 
                             JOIN categories ON quotes.category_id = categories.id");
        $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($quotes ?: ["message" => "No Quotes Found"]);
    }
} elseif ($method == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['quote'], $data['author_id'], $data['category_id'])) {
        $stmt = $pdo->prepare("INSERT INTO quotes (quote, author_id, category_id) VALUES (?, ?, ?)");
        if ($stmt->execute([$data['quote'], $data['author_id'], $data['category_id']])) {
            echo json_encode(["message" => "Quote added successfully"]);
        } else {
            echo json_encode(["message" => "Failed to add quote"]);
        }
    } else {
        echo json_encode(["message" => "Invalid input"]);
    }
} elseif ($method == 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id'], $data['quote'], $data['author_id'], $data['category_id'])) {
        $stmt = $pdo->prepare("UPDATE quotes SET quote = ?, author_id = ?, category_id = ? WHERE id = ?");
        if ($stmt->execute([$data['quote'], $data['author_id'], $data['category_id'], $data['id']])) {
            echo json_encode(["message" => "Quote updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update quote"]);
        }
    } else {
        echo json_encode(["message" => "Invalid input"]);
    }
} elseif ($method == 'DELETE') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $stmt = $pdo->prepare("DELETE FROM quotes WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(["message" => "Quote deleted successfully"]);
        } else {
            echo json_encode(["message" => "Failed to delete quote"]);
        }
    } else {
        echo json_encode(["message" => "Missing quote ID"]);
    }
} else {
    echo json_encode(["message" => "Invalid request method"]);
}
?>
