<?php
// only ajax calls allowed
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    exit();
}
require __DIR__."/../../vendor/autoload.php";
header('Content-Type: application/json');
$db = new MysqliDb();
$db->where('category_id', $_POST['category_id']);
$db->where('class_id', $_POST['class_id']);
$exams = $db->get('exams');
if(count($exams)){
echo json_encode(['success'=>true,'data' => $exams]);
}
else{
echo json_encode(['success'=>false,'data' => null, 'message'=>'no exam available']);
/*     <div class="card text-bg-primary mb-3" style="max-width: 18rem;">
  <div class="card-header">Header</div>
  <div class="card-body">
    <h5 class="card-title">Primary card title</h5>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
  </div>
</div> */
}

?>