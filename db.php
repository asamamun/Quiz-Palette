<?php
// Database configuration
$host = '127.0.0.1'; // or 'localhost'
$username = 'root'; // Replace with your MySQL username
$password = ''; // Replace with your MySQL password
$database = 'quizpallete'; // Database name

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

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