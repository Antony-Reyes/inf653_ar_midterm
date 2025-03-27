<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Check if API endpoints are being accessed
$request_uri = $_SERVER['REQUEST_URI'];

if (strpos($request_uri, "/api/quotes/") !== false) {
    include_once __DIR__ . "/api/quotes/index.php";
} elseif (strpos($request_uri, "/api/authors/") !== false) {
    include_once __DIR__ . "/api/authors/index.php";
} elseif (strpos($request_uri, "/api/categories/") !== false) {
    include_once __DIR__ . "/api/categories/index.php";
} else {
    // Default response
    echo json_encode(["message" => "Welcome to the Quotes API"]);
}
?>

