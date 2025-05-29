<?php
header('Content-Type: application/json');
// require_once 'includes/db_connect.php';

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$result = $conn->query("SELECT id, name FROM classes WHERE category_id = $category_id");
$classes = [];
while ($row = $result->fetch_assoc()) {
    $classes[] = $row;
}
$result->free();
echo json_encode($classes);

$conn->close();
?>