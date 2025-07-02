<?php

session_start();
require __DIR__."/../vendor/autoload.php";
require __DIR__."/admincheck.php";
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quizpallete";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Connection failed: " . htmlspecialchars($conn->connect_error));

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Enable debug output (set to false after testing)
define('DEBUG', true);

// Helper function to sanitize input
function sanitize($conn, $str)
{
    return $conn->real_escape_string(trim($str));
}

// Handle POST requests: add/edit/delete exams
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_exam') {
    $title = sanitize($conn, $_POST['title']);
    $slug = sanitize($conn, $_POST['slug']);
    $description = sanitize($conn, $_POST['description']);
    $duration = !empty($_POST['duration']) ? (int)$_POST['duration'] : null;
    $status = sanitize($conn, $_POST['status']);
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $class_id = !empty($_POST['class_id']) ? (int)$_POST['class_id'] : null;
    $subject_id = !empty($_POST['subject_id']) ? (int)$_POST['subject_id'] : null;
    $event_id = !empty($_POST['event_id']) ? (int)$_POST['event_id'] : null;
    $selected_quizzes = isset($_POST['quiz_ids']) ? array_map('intval', $_POST['quiz_ids']) : [];

    // Validate foreign keys
    if ($category_id !== null) {
        $check = $conn->prepare("SELECT id FROM categories WHERE id = ? AND status = 'active'");
        $check->bind_param("i", $category_id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) $category_id = null;
    }
    if ($class_id !== null) {
        $check = $conn->prepare("SELECT id FROM classes WHERE id = ? AND status = 'active'");
        $check->bind_param("i", $class_id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) $class_id = null;
    }
    if ($subject_id !== null) {
        $check = $conn->prepare("SELECT id FROM subjects WHERE id = ? AND status = 'active'");
        $check->bind_param("i", $subject_id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) $subject_id = null;
    }
    if ($event_id !== null) {
        $check = $conn->prepare("SELECT id FROM events WHERE id = ? AND status = 'active'");
        $check->bind_param("i", $event_id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) $event_id = null;
    }

    // Insert exam
    $stmt = $conn->prepare("INSERT INTO exams (title, slug, description, duration, status, category_id, class_id, subject_id, event_id, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())");
    $stmt->bind_param("sssisisis", $title, $slug, $description, $duration, $status, $category_id, $class_id, $subject_id, $event_id);
    if ($stmt->execute()) {
        $exam_id = $conn->insert_id;

        // Validate and insert selected quizzes
        $quiz_count = 0;
        if (!empty($selected_quizzes)) {
            $quiz_ids_str = implode(',', $selected_quizzes);
            $res = $conn->query("SELECT id FROM quizzes WHERE id IN ($quiz_ids_str) AND status = 'active'");
            $valid_quiz_ids = [];
            while ($row = $res->fetch_assoc()) {
                $valid_quiz_ids[] = $row['id'];
            }

            $stmt = $conn->prepare("INSERT INTO exam_quizzes (exam_id, quiz_id) VALUES (?, ?)");
            foreach ($valid_quiz_ids as $quiz_id) {
                $stmt->bind_param("ii", $exam_id, $quiz_id);
                if ($stmt->execute()) {
                    $quiz_count++;
                }
            }
        }
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Exam added with $quiz_count quizzes.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Failed to add exam.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle edit and delete requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_exam'])) {
    $id = (int)$_POST['id'];
    $title = sanitize($conn, $_POST['title']);
    $slug = sanitize($conn, $_POST['slug']);
    $description = sanitize($conn, $_POST['description']);
    $duration = !empty($_POST['duration']) ? (int)$_POST['duration'] : null;
    $status = sanitize($conn, $_POST['status']);
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $class_id = !empty($_POST['class_id']) ? (int)$_POST['class_id'] : null;
    $subject_id = !empty($_POST['subject_id']) ? (int)$_POST['subject_id'] : null;
    $event_id = !empty($_POST['event_id']) ? (int)$_POST['event_id'] : null;

    // Validate foreign keys
    if ($category_id !== null) {
        $check = $conn->prepare("SELECT id FROM categories WHERE id = ? AND status = 'active'");
        $check->bind_param("i", $category_id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) $category_id = null;
    }
    if ($class_id !== null) {
        $check = $conn->prepare("SELECT id FROM classes WHERE id = ? AND status = 'active'");
        $check->bind_param("i", $class_id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) $class_id = null;
    }
    if ($subject_id !== null) {
        $check = $conn->prepare("SELECT id FROM subjects WHERE id = ? AND status = 'active'");
        $check->bind_param("i", $subject_id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) $subject_id = null;
    }
    if ($event_id !== null) {
        $check = $conn->prepare("SELECT id FROM events WHERE id = ? AND status = 'active'");
        $check->bind_param("i", $event_id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) $event_id = null;
    }

    // Update exam
    $stmt = $conn->prepare("UPDATE exams SET title=?, slug=?, description=?, duration=?, status=?, category_id=?, class_id=?, subject_id=?, event_id=? WHERE id=?");
    $stmt->bind_param("sssisisiii", $title, $slug, $description, $duration, $status, $category_id, $class_id, $subject_id, $event_id, $id);
    $stmt->execute();

    // Clear existing quiz associations
    $stmt = $conn->prepare("DELETE FROM exam_quizzes WHERE exam_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Fetch matching quizzes
    $conditions = [];
    $params = [];
    $types = "";
    if ($category_id !== null) {
        $conditions[] = "category_id = ?";
        $params[] = $category_id;
        $types .= "i";
    }
    if ($class_id !== null) {
        $conditions[] = "class_id = ?";
        $params[] = $class_id;
        $types .= "i";
    }
    if ($subject_id !== null) {
        $conditions[] = "subject_id = ?";
        $params[] = $subject_id;
        $types .= "i";
    }
    if ($event_id !== null) {
        $conditions[] = "event_id = ?";
        $params[] = $event_id;
        $types .= "i";
    }
    $query = "SELECT id FROM quizzes WHERE status = 'active'";
    if (!empty($conditions)) {
        $query .= " AND (" . implode(" OR ", $conditions) . ")";
    }
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    // Insert new quiz associations
    $stmt = $conn->prepare("INSERT INTO exam_quizzes (exam_id, quiz_id) VALUES (?, ?)");
    while ($quiz = $result->fetch_assoc()) {
        $stmt->bind_param("ii", $id, $quiz['id']);
        $stmt->execute();
    }

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_exam'])) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM exams WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    // exam_quizzes are deleted automatically via ON DELETE CASCADE
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch data for display
$allCategories = $conn->query("SELECT id, name FROM categories WHERE status='active'");
$allClasses = $conn->query("SELECT id, name FROM classes WHERE status='active'");
$allSubjects = $conn->query("SELECT id, name FROM subjects WHERE status='active'");
$allEvents = $conn->query("SELECT id, name FROM events WHERE status='active'");
$exams = $conn->query("SELECT e.*, cat.name AS category_name, cl.name AS class_name, s.name AS subject_name, ev.name AS event_name,
                        (SELECT COUNT(*) FROM exam_quizzes eq WHERE eq.exam_id = e.id) AS quiz_count
                        FROM exams e 
                        LEFT JOIN categories cat ON e.category_id = cat.id 
                        LEFT JOIN classes cl ON e.class_id = cl.id 
                        LEFT JOIN subjects s ON e.subject_id = s.id 
                        LEFT JOIN events ev ON e.event_id = ev.id 
                        ORDER BY e.id DESC");

// Fetch quizzes for the add exam form based on filters
$category_id = isset($_POST['category_id']) && !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
$class_id = isset($_POST['class_id']) && !empty($_POST['class_id']) ? (int)$_POST['class_id'] : null;
$subject_id = isset($_POST['subject_id']) && !empty($_POST['subject_id']) ? (int)$_POST['subject_id'] : null;
$conditions = [];
$params = [];
$types = "";

// Require category to show quizzes
if ($category_id !== null) {
    $conditions[] = "q.category_id = ?";
    $params[] = $category_id;
    $types .= "i";
    if ($class_id !== null) {
        $conditions[] = "q.class_id = ?";
        $params[] = $class_id;
        $types .= "i";
    }
    if ($subject_id !== null) {
        $conditions[] = "q.subject_id = ?";
        $params[] = $subject_id;
        $types .= "i";
    }
}

// Default to no results if no category is selected
$where_sql = empty($conditions) ? "1=0" : implode(" AND ", $conditions);
$quizzes_sql = "
    SELECT q.id, q.title, COUNT(ques.id) as question_count
    FROM quizzes q
    LEFT JOIN questions ques ON ques.quiz_id = q.id
    WHERE $where_sql AND q.status = 'active'
    GROUP BY q.id
";

// Debug query (remove after testing)
// if (defined('DEBUG') && DEBUG) {
//     echo "Debug SQL: " . htmlspecialchars($quizzes_sql) . "<br>";
//     echo "Debug Params: " . htmlspecialchars(print_r($params, true)) . "<br>";
// }

$stmt = $conn->prepare($quizzes_sql);
if ($stmt === false) {
    echo "<div class='alert alert-danger'>Query preparation failed: " . htmlspecialchars($conn->error) . "</div>";
    exit;
}
if (!empty($params)) {
    if (!$stmt->bind_param($types, ...$params)) {
        echo "<div class='alert alert-danger'>Parameter binding failed: " . htmlspecialchars($stmt->error) . "</div>";
        exit;
    }
}
if (!$stmt->execute()) {
    echo "<div class='alert alert-danger'>Query execution failed: " . htmlspecialchars($stmt->error) . "</div>";
    exit;
}
$quizzes = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Set Exams</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-color: #129990;
        }
        h2, h4 {
            color: var(--primary-color);
        }
        .btn-primary, .btn-success {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover, .btn-primary:focus, .btn-success:hover, .btn-success:focus {
            background-color: #0e7a75;
            border-color: #0e7a75;
        }
        .btn-danger {
            background-color: #d9534f;
            border-color: #d9534f;
        }
        table.table-bordered {
            border-color: var(--primary-color);
        }
        table.table-bordered th, table.table-bordered td {
            border-color: var(--primary-color);
        }
        .form-inline input, .form-inline select, .form-inline textarea {
            display: inline-block;
            width: auto;
            vertical-align: middle;
        }
        .action-btn {
            margin-left: 5px;
        }
        @media (max-width: 576px) {
            form.row.g-3 > div {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }
            .action-btn {
                margin-top: 0.25rem;
                margin-left: 0;
            }
            table input.form-control, table select.form-select, table textarea.form-control {
                min-width: 100px;
                font-size: 0.85rem;
                padding: 0.25rem 0.5rem;
            }
            table td {
                white-space: nowrap;
            }
        }
        .table-responsive {
            overflow-x: auto;
        }
        .card-header.theme-bg {
            background-color: var(--primary-color);
            color: white;
        }
        .btn-theme {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-theme:hover {
            background-color: #0e7a75;
            border-color: #0e7a75;
        }
        .nav-link.text-white:hover {
            background-color: #0e7a75;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Sidebar for large screens -->
            <div class="col-md-2 d-none d-md-block p-0" style="background-color: var(--primary-color);">
                <nav class="navbar border-bottom border-white">
                    <div class="container-fluid">
                        <span class="navbar-brand text-white">Admin</span>
                    </div>
                </nav>
                <nav class="nav flex-column">
                    <a class="nav-link text-white" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#quizSubMenu" role="button" aria-expanded="true">
                        <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
                    </a>
                    <div class="collapse show ms-3" id="quizSubMenu">
                        <a class="nav-link text-white" href="create_quiz.php"><i class="bi bi-plus-circle me-2"></i>Manage Courses</a>
                        <a class="nav-link text-white active" href="set_exams.php"><i class="bi bi-book me-2"></i>Manage Exams</a>
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
                <nav class="navbar navbar-expand-lg" style="background-color: var(--primary-color);">
                    <div class="container-fluid">
                        <button class="btn text-white d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
                            <i class="bi bi-list"></i>
                        </button>
                        <span class="navbar-brand text-white d-md-none">Admin</span>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- Offcanvas sidebar for mobile -->
                <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMobile" style="background-color: var(--primary-color);">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title text-white">Menu</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                    </div>
                    <div class="offcanvas-body">
                        <nav class="nav flex-column">
                            <a class="nav-link text-white" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                            <a class="nav-link text-white" data-bs-toggle="collapse" href="#quizSubMenuMobile" role="button" aria-expanded="true">
                                <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
                            </a>
                            <div class="collapse show ms-3" id="quizSubMenuMobile">
                                <a class="nav-link text-white" href="create_quiz.php"><i class="bi bi-plus-circle me-2"></i>Manage Courses</a>
                                <a class="nav-link text-white active" href="set_exams.php"><i class="bi bi-book me-2"></i>Manage Exams</a>
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

                <!-- Main Content Area -->
                <div class="py-4 px-3 px-md-4">
                    <h2 class="mb-4">Set Exams</h2>

                    <div class="card shadow border-0 rounded-4 mb-4">
                        <div class="card-header theme-bg fw-semibold fs-5">
                            <i class="bi bi-plus-circle me-2"></i> Add New Exam
                        </div>
                        <div class="card-body">
                            <form method="POST" class="row g-3" id="addExamForm">
                                <input type="hidden" name="action" value="filter">
                                <div class="col-md-3">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select" onchange="updateFormAction('filter')">
                                        <option value="">Select Category</option>
                                        <?php while ($cat = $allCategories->fetch_assoc()): ?>
                                            <option value="<?= $cat['id'] ?>" <?= $category_id == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                        <?php endwhile; $allCategories->data_seek(0); ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Class</label>
                                    <select name="class_id" class="form-select" onchange="updateFormAction('filter')">
                                        <option value="">Select Class</option>
                                        <?php while ($cl = $allClasses->fetch_assoc()): ?>
                                            <option value="<?= $cl['id'] ?>" <?= $class_id == $cl['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cl['name']) ?></option>
                                        <?php endwhile; $allClasses->data_seek(0); ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Subject</label>
                                    <select name="subject_id" class="form-select" onchange="updateFormAction('filter')">
                                        <option value="">Select Subject</option>
                                        <?php while ($sub = $allSubjects->fetch_assoc()): ?>
                                            <option value="<?= $sub['id'] ?>" <?= $subject_id == $sub['id'] ? 'selected' : '' ?>><?= htmlspecialchars($sub['name']) ?></option>
                                        <?php endwhile; $allSubjects->data_seek(0); ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Event</label>
                                    <select name="event_id" class="form-select">
                                        <option value="">Select Event (Optional)</option>
                                        <?php while ($evt = $allEvents->fetch_assoc()): ?>
                                            <option value="<?= $evt['id'] ?>" <?= isset($_POST['event_id']) && $_POST['event_id'] == $evt['id'] ? 'selected' : '' ?>><?= htmlspecialchars($evt['name']) ?></option>
                                        <?php endwhile; $allEvents->data_seek(0); ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" class="form-control" value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>" placeholder="Exam Title" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Slug</label>
                                    <input type="text" name="slug" class="form-control" value="<?= isset($_POST['slug']) ? htmlspecialchars($_POST['slug']) : '' ?>" placeholder="exam-slug" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Duration (minutes)</label>
                                    <input type="number" name="duration" class="form-control" value="<?= isset($_POST['duration']) ? htmlspecialchars($_POST['duration']) : '' ?>" placeholder="e.g., 60">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Exam description"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active" <?= isset($_POST['status']) && $_POST['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= isset($_POST['status']) && $_POST['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Select Quizzes</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Select</th>
                                                    <th>Quiz Title</th>
                                                    <th>Questions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($quizzes->num_rows === 0): ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center">Please select a category to view quizzes.</td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php while ($quiz = $quizzes->fetch_assoc()): ?>
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" name="quiz_ids[]" value="<?= $quiz['id'] ?>" <?= isset($_POST['quiz_ids']) && in_array($quiz['id'], $_POST['quiz_ids']) ? 'checked' : '' ?>>
                                                            </td>
                                                            <td><?= htmlspecialchars($quiz['title']) ?></td>
                                                            <td><?= $quiz['question_count'] ?></td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-theme w-100 mt-2" onclick="updateFormAction('add_exam')">
                                        <i class="bi bi-check-circle me-1"></i> Add Exam
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <h4>Existing Exams</h4>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Category</th>
                                    <th>Class</th>
                                    <th>Subject</th>
                                    <th>Event</th>
                                    <th>Duration</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Quizzes</th>
                                    <th style="min-width:120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $exams->fetch_assoc()): ?>
                                    <tr>
                                        <form method="POST" class="form-inline d-flex flex-wrap gap-2 align-items-center">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <td><input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" class="form-control form-control-sm"></td>
                                            <td><input type="text" name="slug" value="<?= htmlspecialchars($row['slug']) ?>" class="form-control form-control-sm"></td>
                                            <td>
                                                <select name="category_id" class="form-select form-select-sm">
                                                    <option value="">None</option>
                                                    <?php while ($cat = $allCategories->fetch_assoc()): ?>
                                                        <option value="<?= $cat['id'] ?>" <?= $row['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                                    <?php endwhile; $allCategories->data_seek(0); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="class_id" class="form-select form-select-sm">
                                                    <option value="">None</option>
                                                    <?php while ($cl = $allClasses->fetch_assoc()): ?>
                                                        <option value="<?= $cl['id'] ?>" <?= $row['class_id'] == $cl['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cl['name']) ?></option>
                                                    <?php endwhile; $allClasses->data_seek(0); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="subject_id" class="form-select form-select-sm">
                                                    <option value="">None</option>
                                                    <?php while ($sub = $allSubjects->fetch_assoc()): ?>
                                                        <option value="<?= $sub['id'] ?>" <?= $row['subject_id'] == $sub['id'] ? 'selected' : '' ?>><?= htmlspecialchars($sub['name']) ?></option>
                                                    <?php endwhile; $allSubjects->data_seek(0); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="event_id" class="form-select form-select-sm">
                                                    <option value="">None</option>
                                                    <?php while ($evt = $allEvents->fetch_assoc()): ?>
                                                        <option value="<?= $evt['id'] ?>" <?= $row['event_id'] == $evt['id'] ? 'selected' : '' ?>><?= htmlspecialchars($evt['name']) ?></option>
                                                    <?php endwhile; $allEvents->data_seek(0); ?>
                                                </select>
                                            </td>
                                            <td><input type="number" name="duration" value="<?= htmlspecialchars($row['duration'] ?? '') ?>" class="form-control form-control-sm"></td>
                                            <td><textarea name="description" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($row['description'] ?? '') ?></textarea></td>
                                            <td>
                                                <select name="status" class="form-select form-select-sm">
                                                    <option value="active" <?= $row['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                                    <option value="inactive" <?= $row['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                                </select>
                                            </td>
                                            <td>
                                                <a href="exam_quiz_manager.php?exam_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm"><?= $row['quiz_count'] ?></a>
                                            </td>
                                            <td class="d-flex gap-2">
                                                <button type="submit" name="edit_exam" class="btn btn-sm btn-success action-btn">Save</button>
                                        </form>
                                        <form method="POST" onsubmit="return confirm('Delete this exam?')" class="m-0">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <button type="submit" name="delete_exam" class="btn btn-sm btn-danger action-btn">Delete</button>
                                        </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateFormAction(action) {
            document.getElementById('addExamForm').querySelector('input[name="action"]').value = action;
            if (action === 'filter') {
                document.getElementById('addExamForm').submit();
            }
        }
    </script>
    <?php $conn->close(); ?>
</body>
</html>