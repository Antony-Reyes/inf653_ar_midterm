<?php
// Set Headers for JSON Response
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

// Define API Information
$api_info = [
    "message" => "Welcome to the Quotes API",
    "endpoints" => [
        "GET /api/quotes/" => "Retrieve all quotes",
        "GET /api/quotes/?id={id}" => "Retrieve a single quote by ID",
        "POST /api/quotes/" => "Create a new quote (Requires JSON body: quote, author_id, category_id)",
        "PUT /api/quotes/" => "Update an existing quote (Requires JSON body: id, quote, author_id, category_id)",
        "DELETE /api/quotes/" => "Delete a quote (Requires JSON body: id)"
    ],
    "status" => "API is running"
];

// Return JSON response
echo json_encode($api_info, JSON_PRETTY_PRINT);
?>
