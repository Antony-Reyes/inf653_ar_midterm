<?php
// index.php

// Function to generate API documentation page
function displayApiDocumentation() {
    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>INF653 AR Midterm API</title>";
    echo "<style>";
    echo "body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; margin: 0; padding: 20px; }";
    echo "h1 { color: #4CAF50; }";
    echo "h2 { color: #333; }";
    echo "ul { list-style-type: none; padding: 0; }";
    echo "li { margin: 8px 0; }";
    echo "a { color: #4CAF50; text-decoration: none; }";
    echo "a:hover { text-decoration: underline; }";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    echo "<h1>Welcome to the INF653 AR Midterm API</h1>";
    echo "<p>This is the main index page for your API. Below are the available endpoints you can access:</p>";
    echo "<h2>Available Endpoints</h2>";
    echo "<ul>";
    echo "<li><strong><a href='/api/authors'>/api/authors</a></strong>: Get all authors.</li>";
    echo "<li><strong><a href='/api/categories'>/api/categories</a></strong>: Get all categories.</li>";
    echo "<li><strong><a href='/api/quotes'>/api/quotes</a></strong>: Get all quotes.</li>";
    echo "</ul>";
    echo "<p>For detailed information, access each endpoint to retrieve the data.</p>";
    echo "</body>";
    echo "</html>";
}

// Set up routing (example)
if ($_SERVER['REQUEST_URI'] == '/api/authors') {
    // Include the correct path to the authors.php file in the modules folder
    include 'modules/authors.php';
} elseif ($_SERVER['REQUEST_URI'] == '/api/categories') {
    // Include the correct path to the categories.php file in the modules folder
    include 'modules/categories.php';
} elseif ($_SERVER['REQUEST_URI'] == '/api/quotes') {
    // Include the correct path to the quotes.php file in the modules folder
    include 'modules/quotes.php';
} elseif ($_SERVER['REQUEST_URI'] == '/api') {
    // Display the API documentation if the base /api endpoint is hit
    displayApiDocumentation();
} else {
    // Return an error message for invalid endpoints
    header("HTTP/1.1 404 Not Found");
    echo "<h1>404 - Not Found</h1>";
    echo "<p>The endpoint you are trying to access does not exist.</p>";
}
?>
