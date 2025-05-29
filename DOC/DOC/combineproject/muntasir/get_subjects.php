<?php
header('Content-Type: application/json');
// require_once 'includes/db_connect.php';

$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;
$result = $conn->query("SELECT id, name FROM subjects WHERE class_id = $class_id");
$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}
$result->free();
echo json_encode($subjects);

$conn->close();
?>