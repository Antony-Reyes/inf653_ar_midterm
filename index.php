<?php
// index.php - API Documentation Page

// Function to generate API documentation page
function displayApiDocumentation() {
    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>INF653 AR Midterm API</title>";
    echo "<style>";
    echo "body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; margin: 0; padding: 20px; text-align: center; }";
    echo "h1 { color: #4CAF50; }";
    echo "h2 { color: #333; }";
    echo "p { max-width: 600px; margin: 0 auto 20px; }";
    echo "ul { list-style-type: none; padding: 0; display: inline-block; text-align: left; }";
    echo "li { margin: 10px 0; }";
    echo "a { display: inline-block; background-color: #4CAF50; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; }";
    echo "a:hover { background-color: #45a049; }";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    echo "<h1>Welcome to the INF653 AR Midterm API</h1>";
    echo "<p>This API provides various endpoints to retrieve data. Click on any of the endpoints below to access the data.</p>";
    
    echo "<h2>Available Endpoints</h2>";
    echo "<ul>";
    echo "<li><a href='/api/authors' target='_blank'>View All Authors</a></li>";
    echo "<li><a href='/api/categories' target='_blank'>View All Categories</a></li>";
    echo "<li><a href='/api/quotes' target='_blank'>View All Quotes</a></li>";
    echo "</ul>";

    echo "<p><strong>Note:</strong> Ensure your database is properly connected before accessing these endpoints.</p>";
    
    // Display a footer with links to other documentation or support if needed
    echo "<footer>";
    echo "<p><a href='/help' target='_blank'>API Help</a> | <a href='/contact' target='_blank'>Contact Support</a></p>";
    echo "</footer>";
    
    echo "</body>";
    echo "</html>";
}

// Display API Documentation (No Routing)
displayApiDocumentation();
?>
