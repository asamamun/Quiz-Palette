<?php
header('Content-Type: application/json');
require_once 'includes/db_connect.php';

$type = isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : 'global';
$query = "SELECT l.*, u.name AS user_name FROM leaderboards l JOIN users u ON l.user_id = u.id";
$details = '';

if ($type === 'category' && isset($_GET['category_id'])) {
    $category_id = (int)$_GET['category_id'];
    $query .= " WHERE l.category_id = $category_id";
    $result = $conn->query("SELECT name FROM categories WHERE id = $category_id");
    $row = $result->fetch_assoc();
    $details = $row['name'] ?? '';
    $result->free();
} elseif ($type === 'class' && isset($_GET['class_id'])) {
    $class_id = (int)$_GET['class_id'];
    $query .= " WHERE l.class_id = $class_id";
    $result = $conn->query("SELECT name FROM classes WHERE id = $class_id");
    $row = $result->fetch_assoc();
    $details = $row['name'] ?? '';
    $result->free();
} elseif ($type === 'subject' && isset($_GET['subject_id'])) {
    $subject_id = (int)$_GET['subject_id'];
    $query .= " WHERE l.subject_id = $subject_id";
    $result = $conn->query("SELECT name FROM subjects WHERE id = $subject_id");
    $row = $result->fetch_assoc();
    $details = $row['name'] ?? '';
    $result->free();
} elseif ($type === 'event' && isset($_GET['quiz_id'])) {
    $quiz_id = (int)$_GET['quiz_id'];
    $query .= " WHERE l.quiz_id = $quiz_id";
    $result = $conn->query("SELECT title FROM quizzes WHERE id = $quiz_id");
    $row = $result->fetch_assoc();
    $details = $row['title'] ?? '';
    $result->free();
}

$query .= " ORDER BY l.total_score DESC";
$result = $conn->query($query);
$leaderboard = [];
while ($row = $result->fetch_assoc()) {
    $leaderboard[] = $row;
}
$result->free();

// Fetch badges for each user
foreach ($leaderboard as &$entry) {
    $user_id = (int)$entry['user_id'];
    $result = $conn->query("SELECT b.name FROM badges b JOIN user_badges ub ON b.id = ub.badge_id WHERE ub.user_id = $user_id");
    $badges = [];
    while ($row = $result->fetch_assoc()) {
        $badges[] = $row['name'];
    }
    $result->free();
    $entry['user_name'] = htmlspecialchars($entry['user_name']);
    $entry['badges'] = $badges;
    $entry['details'] = htmlspecialchars($details);
}

echo json_encode($leaderboard);

$conn->close();
?>