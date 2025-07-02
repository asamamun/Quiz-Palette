<?php
header('Content-Type: application/json');

// Start session to access user_id
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include DB connection
require_once '../../vendor/autoload.php';
$db = new MysqliDb();

// Ensure POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Ensure user is logged in
if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized: user not logged in']);
    exit;
}

// Collect and sanitize inputs
$category_id = trim($_POST['category_id'] ?? '');
$class_id = trim($_POST['class_id'] ?? '');
$subject_id = trim($_POST['subject_id'] ?? '');
$question = trim($_POST['question'] ?? '');
$option_a = trim($_POST['option_a'] ?? '');
$option_b = trim($_POST['option_b'] ?? '');
$option_c = trim($_POST['option_c'] ?? '');
$option_d = trim($_POST['option_d'] ?? '');
$correct_option = trim($_POST['correct_option'] ?? '');

// Validate required fields
if (
    empty($category_id) || empty($class_id) || empty($subject_id) ||
    empty($question) || empty($option_a) || empty($option_b) ||
    empty($option_c) || empty($option_d) || empty($correct_option)
) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields']);
    exit;
}

// Prepare data for insertion
$data = [
    'category_id' => $category_id,
    'class_id' => $class_id,
    'subject_id' => $subject_id,
    'question' => $question,
    'option_a' => $option_a,
    'option_b' => $option_b,
    'option_c' => $option_c,
    'option_d' => $option_d,
    'correct_option' => $correct_option,
    'status' => 'pending',
    'created_by' => (int)$_SESSION['user_id'],
    'created_at' => date('Y-m-d H:i:s')
];

// Insert into DB
$id = $db->insert('pending_questions', $data);

if ($id) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database insert failed: ' . $db->getLastError()]);
}
