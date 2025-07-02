<?php
require_once __DIR__ . '/vendor/autoload.php';
// Database configuration
$conn = new mysqli(
    settings()['hostname'], 
    settings()['user'], 
    settings()['password'], 
    settings()['database']
);

// Check connection
if (!$conn) {
    // Log error to a file or display a user-friendly message
    error_log("Connection failed: " . mysqli_connect_error(), 3, "logs/db_errors.log");
    die("Database connection failed. Please try again later.");
}

// Set charset to UTF-8 to match database encoding
if (!mysqli_set_charset($conn, 'utf8mb4')) {
    error_log("Error setting charset: " . mysqli_error($conn), 3, "logs/db_errors.log");
    die("Database error. Please try again later.");
}
?>