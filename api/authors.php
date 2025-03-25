<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/database.php";

$database = new Database();
$conn = $database->connect();

$method = $_SERVER["REQUEST_METHOD"];

if ($method == "GET") {
    if (isset($_GET["id"])) {
        $stmt = $conn->prepare("SELECT * FROM authors WHERE id = ?");
        $stmt->execute([$_GET["id"]]);
        $author = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($author ?: ["message" => "author_id Not Found"]);
    } else {
        $stmt = $conn->query("SELECT * FROM authors");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} elseif ($method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Define the authors to insert
    $authors = [
        'Dr. Seuss',
        'Barack Obama',
        'Taylor Swift',
        'Donald Trump',
        'Hillary Clinton',
        'Lady Gaga'
    ];
    
    // Insert each author into the authors table
    foreach ($authors as $author) {
        $stmt = $conn->prepare("INSERT INTO authors (author) VALUES (?)");
        $stmt->execute([$author]);
    }
    
    echo json_encode(["message" => "Authors inserted successfully"]);
} elseif ($method == "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data["id"], $data["author"])) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit;
    }
    $stmt = $conn->prepare("UPDATE authors SET author = ? WHERE id = ?");
    $stmt->execute([$data["author"], $data["id"]]);
    echo json_encode(["id" => $data["id"], "author" => $data["author"]]);
} elseif ($method == "DELETE") {
    if (!isset($_GET["id"])) {
        echo json_encode(["message" => "Missing Required Parameters"]);
        exit;
    }
    $stmt = $conn->prepare("DELETE FROM authors WHERE id = ?");
    $stmt->execute([$_GET["id"]]);
    echo json_encode(["id" => $_GET["id"]]);
}
?>
