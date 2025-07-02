<?php
require_once '../db.php';

header('Content-Type: application/json');

// Enable error logging for debugging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_log("Leaderboard API called: " . json_encode($_GET));

// Get request type
$action = isset($_GET['action']) ? $_GET['action'] : '';

global $conn;

switch ($action) {
    case 'get_categories':
        // Fetch active categories
        $query = "SELECT id, name FROM categories WHERE status = 'active' ORDER BY name";
        $result = $conn->query($query);
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        echo json_encode($categories);
        break;

    case 'get_classes':
        // Fetch classes for a given category
        $category_id = isset($_GET['category_id']) && $_GET['category_id'] !== '' ? intval($_GET['category_id']) : 0;
        $query = "SELECT id, name FROM classes WHERE category_id = ? AND status = 'active' ORDER BY name";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $classes = [];
        while ($row = $result->fetch_assoc()) {
            $classes[] = $row;
        }
        echo json_encode($classes);
        break;

    case 'get_subjects':
        // Fetch subjects for a given class
        $class_id = isset($_GET['class_id']) && $_GET['class_id'] !== '' ? intval($_GET['class_id']) : 0;
        $query = "SELECT id, name FROM subjects WHERE class_id = ? AND status = 'active' ORDER BY name";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $class_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $subjects = [];
        while ($row = $result->fetch_assoc()) {
            $subjects[] = $row;
        }
        echo json_encode($subjects);
        break;

    case 'get_events':
        // Fetch active events
        $query = "SELECT id, name, event_date FROM events WHERE status = 'active' ORDER BY event_date DESC";
        $result = $conn->query($query);
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        echo json_encode($events);
        break;

    case 'get_leaderboard':
        // Fetch leaderboard data based on type and filters
        $type = isset($_GET['type']) ? $_GET['type'] : 'global';
        $category_id = isset($_GET['category_id']) && $_GET['category_id'] !== '' ? intval($_GET['category_id']) : null;
        $class_id = isset($_GET['class_id']) && $_GET['class_id'] !== '' ? intval($_GET['class_id']) : null;
        $subject_id = isset($_GET['subject_id']) && $_GET['subject_id'] !== '' ? intval($_GET['subject_id']) : null;
        $event_id = isset($_GET['event_id']) && $_GET['event_id'] !== '' ? intval($_GET['event_id']) : null;

        // Base query
        $query = "
            SELECT 
                l.user_id, 
                u.username, 
                SUM(l.total_score) as total_score,
                SUM(l.total_attempts) as total_attempts,
                AVG(l.average_score) as average_score,
                MIN(l.rank_position) as rank_position,
                (SELECT COUNT(*) FROM user_badges ub WHERE ub.user_id = l.user_id) as badge_count,
                (SELECT GROUP_CONCAT(b.name) FROM user_badges ub JOIN badges b ON ub.badge_id = b.id WHERE ub.user_id = l.user_id) as badge_names
            FROM leaderboards l
            JOIN users u ON l.user_id = u.id
            WHERE 1=1
        ";

        $params = [];
        $types = '';

        // Apply filters
        if ($type === 'category' && $category_id) {
            $query .= " AND l.category_id = ?";
            $params[] = $category_id;
            $types .= 'i';
        }
        if ($type === 'class' && $class_id) {
            $query .= " AND l.class_id = ?";
            $params[] = $class_id;
            $types .= 'i';
        }
        if ($type === 'subject' && $subject_id) {
            $query .= " AND l.subject_id = ?";
            $params[] = $subject_id;
            $types .= 'i';
        }
        if ($type === 'event' && $event_id) {
            $query .= " AND l.exam_id IN (SELECT id FROM exams WHERE event_id = ?)";
            $params[] = $event_id;
            $types .= 'i';
        }

        // Group by user to aggregate scores for Global tab
        $query .= " GROUP BY l.user_id, u.username";

        // Order by rank or total score
        $query .= " ORDER BY rank_position ASC, total_score DESC LIMIT 50";

        error_log("Leaderboard query: $query, Params: " . json_encode($params));

        $stmt = $conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $leaderboard = [];
        while ($row = $result->fetch_assoc()) {
            $leaderboard[] = $row;
        }
        echo json_encode($leaderboard);
        break;

    default:
        error_log("Invalid action: $action");
        echo json_encode(['error' => 'Invalid action']);
        break;
}

$conn->close();
?>