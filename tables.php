<?php
require_once "databaseConnection.php";

// Create a database connection
$db = new Database();
$conn = $db->getConnection();

// Fetch all authors
$query = "SELECT * FROM authors";
$stmt = $conn->prepare($query);
$stmt->execute();
$authors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all categories
$query = "SELECT * FROM categories";
$stmt = $conn->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all quotes
$query = "SELECT quotes.id, quotes.quote_text, authors.name AS author, categories.category_name 
          FROM quotes
          JOIN authors ON quotes.author_id = authors.id
          JOIN categories ON quotes.category_id = categories.id";
$stmt = $conn->prepare($query);
$stmt->execute();
$quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Display Data
echo "<h2>Authors</h2><pre>" . print_r($authors, true) . "</pre>";
echo "<h2>Categories</h2><pre>" . print_r($categories, true) . "</pre>";
echo "<h2>Quotes</h2><pre>" . print_r($quotes, true) . "</pre>";
?>
