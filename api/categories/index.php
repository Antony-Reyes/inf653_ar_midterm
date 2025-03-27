<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Route requests based on HTTP method
switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            require 'read_single.php'; // Get a single category
        } else {
            require 'read.php'; // Get all categories
        }
        break;
    case 'POST':
        require 'create.php'; // Create a new category
        break;
    case 'PUT':
        require 'update.php'; // Update an existing category
        break;
    case 'DELETE':
        require 'delete.php'; // Delete a category
        break;
    case 'OPTIONS':
        // Respond to preflight request
        http_response_code(200);
        exit;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
        break;
}
?>
