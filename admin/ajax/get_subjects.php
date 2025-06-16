<?php
// only ajax calls allowed
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    exit();
}
require __DIR__."/../../vendor/autoload.php";
header('Content-Type: application/json');
$db = new MysqliDb();
$db->where('class_id', $_POST['class_id']);
$subjects = $db->get('subjects', null, 'id, name');
array_unshift($subjects, ['id' => -1, 'name' => 'Select Subjects']); 
echo json_encode(['data' => $subjects]);
?>