<?php
require __DIR__ . "/../vendor/autoload.php";
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quizpallete";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

// Get POST data
$category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
$class_id = isset($_POST['class_id']) ? (int)$_POST['class_id'] : 0;

if (!$category_id) {
    die(json_encode(["error" => "Invalid category ID"]));
}

// Fetch classes for the category
$classes = [];
$query = "SELECT id, name FROM classes WHERE category_id = ? AND status = 'active'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $classes[] = $row;
}

// Fetch subjects (filter by class_id if provided)
$subjects = [];
$query = "SELECT id, name FROM subjects WHERE category_id = ? AND status = 'active'";
if ($class_id) {
    $query .= " AND class_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $category_id, $class_id);
} else {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $category_id);
}
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}

echo json_encode([
    "data" => [
        "classes" => $classes,
        "subjects" => $subjects
    ]
]);

$conn->close();
?>