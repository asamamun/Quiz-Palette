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
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $name = htmlentities(trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS)), ENT_QUOTES);
    // $name = trim(filter_var(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
    // $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $email = filter_var(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

/*     $subject = trim(filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING));
    $message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING)); */
    $subject = htmlspecialchars($_POST['subject'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
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