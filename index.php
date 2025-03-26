<?php
// index.php

// Include the necessary files
include 'authors.php';
include 'categories.php';
include 'quotes.php';

// Set up routing (example)
if ($_SERVER['REQUEST_URI'] == '/api/authors') {
    include 'authors.php';
} elseif ($_SERVER['REQUEST_URI'] == '/api/categories') {
    include 'categories.php';
} elseif ($_SERVER['REQUEST_URI'] == '/api/quotes') {
    include 'quotes.php';
} else {
    echo "Invalid endpoint";
}
?>
