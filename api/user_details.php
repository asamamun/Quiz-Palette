<?php
require_once '../db.php';

header('Content-Type: application/json');

// Enable error logging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_log("User Details API called: " . json_encode($_GET));

// Get user ID
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id <= 0) {
    echo json_encode(['error' => 'Invalid user ID']);
    exit;
}

global $conn;

try {
    // Fetch user profile
    $user_query = "SELECT username FROM users WHERE id = ? AND status = 'active'";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    if ($user_result->num_rows === 0) {
        echo json_encode(['error' => 'User not found']);
        exit;
    }
    $user = $user_result->fetch_assoc();

    // Fetch overall performance
    $perf_query = "
        SELECT 
            SUM(total_score) as total_score,
            SUM(total_attempts) as total_attempts,
            AVG(average_score) as average_score
        FROM leaderboards 
        WHERE user_id = ?
    ";
    $stmt = $conn->prepare($perf_query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $perf_result = $stmt->get_result();
    $performance = $perf_result->fetch_assoc();

    // Fetch badges
    $badges_query = "
        SELECT b.name, b.description
        FROM user_badges ub
        JOIN badges b ON ub.badge_id = b.id
        WHERE ub.user_id = ?
    ";
    $stmt = $conn->prepare($badges_query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $badges_result = $stmt->get_result();
    $badges = [];
    while ($row = $badges_result->fetch_assoc()) {
        $badges[] = $row;
    }

    // Fetch performance by category
    $category_perf_query = "
        SELECT 
            c.name as category_name,
            SUM(l.total_score) as total_score,
            AVG(l.average_score) as average_score
        FROM leaderboards l
        JOIN categories c ON l.category_id = c.id
        WHERE l.user_id = ?
        GROUP BY c.id, c.name
    ";
    $stmt = $conn->prepare($category_perf_query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $category_perf_result = $stmt->get_result();
    $performance_by_category = [];
    while ($row = $category_perf_result->fetch_assoc()) {
        $performance_by_category[] = $row;
    }

    // Compile response
    $response = [
        'username' => $user['username'],
        'total_score' => $performance['total_score'] ?? 0,
        'total_attempts' => $performance['total_attempts'] ?? 0,
        'average_score' => $performance['average_score'] ?? 0,
        'badges' => $badges,
        'performance_by_category' => $performance_by_category
    ];

    echo json_encode($response);
} catch (Exception $e) {
    error_log("Error in user_details.php: " . $e->getMessage());
    echo json_encode(['error' => 'Server error']);
}

$conn->close();
?>