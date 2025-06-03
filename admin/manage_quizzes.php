<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quizpallete";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$quizzes = $conn->query("SELECT q.*, c.name as category, cl.name as class, s.name as subject FROM quizzes q 
LEFT JOIN categories c ON q.category_id = c.id 
LEFT JOIN classes cl ON q.class_id = cl.id 
LEFT JOIN subjects s ON q.subject_id = s.id ORDER BY q.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Quizzes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <div class="col-2">
<?php include"includes/sidebar.php;"?>
    </div>
    <div class="col-10 p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes</h4>
        <a href="create_quiz.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Create Quiz</a>
      </div>
      <table class="table table-bordered table-hover">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Category</th>
            <th>Class</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php $i = 1; while($row = $quizzes->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= htmlspecialchars($row['class']) ?></td>
            <td><?= htmlspecialchars($row['subject']) ?></td>
            <td><span class="badge bg-<?= $row['status'] == 'approved' ? 'success' : ($row['status'] == 'pending' ? 'warning' : 'secondary') ?>"><?= ucfirst($row['status']) ?></span></td>
            <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
            <td>
              <a href="edit_quiz.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a>
              <a href="manage_questions.php?quiz_id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-success"><i class="bi bi-question-circle"></i></a>
              <a href="delete_quiz.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
