<?php
// Database configuration
$dbHost     = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName     = 'quizmaster_db';

// Create database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $subject = trim(filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING));
    $message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING));
    $subscribe = isset($_POST['subscribe']) ? 1 : 0;
    
    // Validate inputs
    $valid = true;
    
    if (empty($name)) {
        $valid = false;
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $valid = false;
    }
    
    if (empty($subject)) {
        $valid = false;
    }
    
    if (empty($message)) {
        $valid = false;
    }
    
    // If all inputs are valid, proceed with database insertion
    if ($valid) {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, subscribe, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssi", $name, $email, $subject, $message, $subscribe);
        
        if ($stmt->execute()) {
            // Return simple "Submitted" response
            echo "Submitted";
            exit();
        }
        
        $stmt->close();
    }
}

// Close database connection
$conn->close();

// If we get here, something went wrong
echo "Error";
?>