<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quizpallete";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['add_quiz'])) {
    $question = $_POST['question'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO quizzes (question, option_a, option_b, option_c, option_d, correct_option, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $question, $option_a, $option_b, $option_c, $option_d, $correct_option, $status);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>✅ Quiz added successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ Failed to add quiz.</div>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container-fluid">
  <div class="row min-vh-100">
    <!-- Sidebar for large screens -->
    <div class="col-md-2 d-none d-md-block p-0" style="background-color: #129990;">
      <nav class="navbar border-bottom border-white" style="background-color: #129990;">
        <div class="container-fluid">
          <span class="navbar-brand text-white">Admin</span>
        </div>
      </nav>
      <nav class="nav flex-column" style="background-color: #129990;">
        <a class="nav-link text-white" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        
        <!-- Manage Quizzes toggle -->
        <a class="nav-link text-white" data-bs-toggle="collapse" href="#quizSubMenu" role="button" aria-expanded="false">
          <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
        </a>
        <div class="collapse ms-3" id="quizSubMenu">
          <a class="nav-link text-white" href="create_quiz.php"><i class="bi bi-plus-circle me-2"></i>Create Quiz</a>
          <a class="nav-link text-white" href="add_quiz.php"><i class="bi bi-plus-circle me-2"></i>Add Quiz</a>

          <a class="nav-link text-white" href="quiz_list.php"><i class="bi bi-list-ul me-2"></i>Quiz List</a>
        </div>

        <a class="nav-link text-white" href="manage_users.php"><i class="bi bi-people-fill me-2"></i>Manage Users</a>
        <a class="nav-link text-white" href="track_results.php"><i class="bi bi-graph-up-arrow me-2"></i>Track Results</a>
        <a class="nav-link text-white" href="leaderboards.php"><i class="bi bi-trophy-fill me-2"></i>Leaderboards</a>
        <a class="nav-link text-white" href="requests.php"><i class="bi bi-inbox-fill me-2"></i>Requests</a>
        <a class="nav-link text-white" href="certificate.php"><i class="bi bi-award-fill me-2"></i>Certificates</a>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 col-12 p-0">
      <!-- Top navbar with hamburger -->
      <nav class="navbar navbar-expand-lg" style="background-color: #129990;">
        <div class="container-fluid">
          <!-- Hamburger button for small screens -->
          <button class="btn text-white d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
            <i class="bi bi-list"></i>
          </button>
          <span class="navbar-brand text-white d-md-none">Admin</span>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link text-white" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Offcanvas sidebar for mobile -->
      <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMobile" style="background-color: #129990;">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title text-white">Menu</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
          <nav class="nav flex-column">
            <a class="nav-link text-white" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            
            <!-- Mobile toggle -->
            <a class="nav-link text-white" data-bs-toggle="collapse" href="#quizSubMenuMobile" role="button" aria-expanded="false">
              <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
            </a>
            <div class="collapse ms-3" id="quizSubMenuMobile">
              <a class="nav-link text-white" href="create_quiz.php"><i class="bi bi-plus-circle me-2"></i>Create Quiz</a>
              <a class="nav-link text-white" href="quiz_list.php"><i class="bi bi-list-ul me-2"></i>Quiz List</a>
            </div>

            <a class="nav-link text-white" href="manage_users.php"><i class="bi bi-people-fill me-2"></i>Manage Users</a>
            <a class="nav-link text-white" href="track_results.php"><i class="bi bi-graph-up-arrow me-2"></i>Track Results</a>
            <a class="nav-link text-white" href="leaderboards.php"><i class="bi bi-trophy-fill me-2"></i>Leaderboards</a>
            <a class="nav-link text-white" href="requests.php"><i class="bi bi-inbox-fill me-2"></i>Requests</a>
            <a class="nav-link text-white" href="certificate.php"><i class="bi bi-award-fill me-2"></i>Certificates</a>
          </nav>
        </div>
      </div>

      <!-- Responsive Quiz Form -->
<div class="container my-4">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header theme-bg fw-semibold fs-5">
            <i class="bi bi-plus-circle me-2"></i>Add New Quiz
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Question</label>
                    <textarea name="question" class="form-control" rows="3" required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option A</label>
                        <input type="text" name="option_a" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option B</label>
                        <input type="text" name="option_b" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option C</label>
                        <input type="text" name="option_c" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option D</label>
                        <input type="text" name="option_d" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Correct Option</label>
                        <select name="correct_option" class="form-select" required>
                            <option value="a">Option A</option>
                            <option value="b">Option B</option>
                            <option value="c">Option C</option>
                            <option value="d">Option D</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <button type="submit" name="add_quiz" class="btn btn-theme w-100 mt-2">
                    <i class="bi bi-check-circle me-1"></i>Submit Quiz
                </button>
            </form>
        </div>
    </div>
</div>

      </div>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


















<!-- Custom Styles -->
<!-- <style>
    .theme-bg {
        background-color: #129990 !important;
        color: white;
    }
    .btn-theme {
        background-color: #129990;
        color: white;
        border: none;
    }
    .btn-theme:hover {
        background-color: #0f837e;
    }
</style> -->

