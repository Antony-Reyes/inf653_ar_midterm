<?php
require_once __DIR__ . "/config.php";
require __DIR__ . '/database.php';

$database = new Database();
$conn = $database->connect();

$method = $_SERVER["REQUEST_METHOD"];

if ($method == "GET") {
    if (isset($_GET["id"])) {
        $stmt = $conn->prepare("SELECT * FROM quotes WHERE id = ?");
        $stmt->execute([$_GET["id"]]);
        $quote = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($quote ?: ["message" => "Quote Not Found"]);
    } else {
        $stmt = $conn->query("SELECT * FROM quotes");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} elseif ($method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Define quotes to insert
    $quotes = [
        ["quote" => "Today you are you! That is truer than true! There is no one alive who is you-er than you!", "author_id" => 1, "category_id" => 1],
        ["quote" => "The more that you read, the more things you will know. The more that you learn, the more places you'll go.", "author_id" => 1, "category_id" => 1],
        ["quote" => "Only you can control your future.", "author_id" => 1, "category_id" => 1],
        ["quote" => "You're in pretty good shape for the shape you are in.", "author_id" => 1, "category_id" => 2],
        ["quote" => "Money is not the only answer, but it makes a difference.", "author_id" => 2, "category_id" => 5],
        ["quote" => "We don't need to share the same opinions as others, but we need to be respectful.", "author_id" => 3, "category_id" => 4],
        ["quote" => "What separates the winners from the losers is how a person reacts to each new twist of fate.", "author_id" => 4, "category_id" => 5],
        ["quote" => "Women are the largest untapped reservoir of talent in the world.", "author_id" => 5, "category_id" => 5],
        ["quote" => "Showing up is not all of life - but it counts for a lot.", "author_id" => 5, "category_id" => 4],
        ["quote" => "The Internet is a toilet. It is.", "author_id" => 6, "category_id" => 2],
        ["quote" => "I am my own sanctuary and I can be reborn as many times as I choose throughout my life.", "author_id" => 6, "category_id" => 3]
    ];
    
    // Insert each quote into the quotes table
    foreach ($quotes as $quote) {
        $stmt = $conn->prepare("INSERT INTO quotes (quote, author_id, category_id) VALUES (?, ?, ?)");
        $stmt->execute([$quote["quote"], $quote["author_id"], $quote["category_id"]]);
    }
    
    echo json_encode(["message" => "Quotes inserted successfully"]);
} elseif ($method == "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data["id"], $data["quote"], $data["author_id"], $data["category_id"])) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit;
    }
    $stmt = $conn->prepare("UPDATE quotes SET quote = ?, author_id = ?, category_id = ? WHERE id = ?");
    $stmt->execute([$data["quote"], $data["author_id"], $data["category_id"], $data["id"]]);
    echo json_encode(["id" => $data["id"], "quote" => $data["quote"], "author_id" => $data["author_id"], "category_id" => $data["category_id"]]);
} elseif ($method == "DELETE") {
    if (!isset($_GET["id"])) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit;
    }
    $stmt = $conn->prepare("DELETE FROM quotes WHERE id = ?");
    $stmt->execute([$_GET["id"]]);
    echo json_encode(["id" => $_GET["id"]]);
}
?>
