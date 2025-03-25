<?php
header("Content-Type: application/json");

// Include database connection
include('database.php');

// Fetch quotes with parameters (author_id, category_id)
if (isset($_GET['author_id']) && isset($_GET['category_id'])) {
    $author_id = $_GET['author_id'];
    $category_id = $_GET['category_id'];
    $query = "SELECT * FROM quotes WHERE author_id = $author_id AND category_id = $category_id";
} else if (isset($_GET['author_id'])) {
    $author_id = $_GET['author_id'];
    $query = "SELECT * FROM quotes WHERE author_id = $author_id";
} else if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $query = "SELECT * FROM quotes WHERE category_id = $category_id";
} else if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM quotes WHERE id = $id";
} else {
    // Default to fetching all quotes
    $query = "SELECT * FROM quotes LIMIT 25";
}

$result = mysqli_query($conn, $query);
$quotes = [];

while ($row = mysqli_fetch_assoc($result)) {
    $quotes[] = $row;
}

// Return the results as JSON
echo json_encode($quotes);
?>
