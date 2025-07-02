<?php
require __DIR__."/../vendor/autoload.php";
require __DIR__."/teachercheck.php";

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quizpallete";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Connection failed: " . htmlspecialchars($conn->connect_error));

// Get current teacher's ID
$teacher_id = $_SESSION['user_id'];

// Helper function to sanitize input
function sanitize($conn, $str) {
    return $conn->real_escape_string(trim($str));
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new exam
    if (isset($_POST['action']) && $_POST['action'] === 'add_exam') {
        $title = sanitize($conn, $_POST['title']);
        $slug = sanitize($conn, $_POST['slug']);
        $description = sanitize($conn, $_POST['description']);
        $duration = !empty($_POST['duration']) ? (int)$_POST['duration'] : null;
        $status = sanitize($conn, $_POST['status']);
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $class_id = !empty($_POST['class_id']) ? (int)$_POST['class_id'] : null;
        $subject_id = !empty($_POST['subject_id']) ? (int)$_POST['subject_id'] : null;
        $selected_quizzes = isset($_POST['quiz_ids']) ? array_map('intval', $_POST['quiz_ids']) : [];

        // Insert exam
        $stmt = $conn->prepare("INSERT INTO exams (title, slug, description, duration, status, category_id, class_id, subject_id, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssisiiii", $title, $slug, $description, $duration, $status, $category_id, $class_id, $subject_id, $teacher_id);
        
        if ($stmt->execute()) {
            $exam_id = $conn->insert_id;
            $quiz_count = 0;
            
            if (!empty($selected_quizzes)) {
                $quiz_ids_str = implode(',', $selected_quizzes);
                $res = $conn->query("SELECT id FROM quizzes WHERE id IN ($quiz_ids_str) AND status = 'active' AND created_by = $teacher_id");
                
                $stmt = $conn->prepare("INSERT INTO exam_quizzes (exam_id, quiz_id) VALUES (?, ?)");
                while ($row = $res->fetch_assoc()) {
                    $stmt->bind_param("ii", $exam_id, $row['id']);
                    if ($stmt->execute()) {
                        $quiz_count++;
                    }
                }
            }
            
            $_SESSION['success'] = "Exam added with $quiz_count quizzes";
        } else {
            $_SESSION['error'] = "Failed to add exam";
        }
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Edit exam
    if (isset($_POST['edit_exam'])) {
        $id = (int)$_POST['id'];
        $title = sanitize($conn, $_POST['title']);
        $slug = sanitize($conn, $_POST['slug']);
        $description = sanitize($conn, $_POST['description']);
        $duration = !empty($_POST['duration']) ? (int)$_POST['duration'] : null;
        $status = sanitize($conn, $_POST['status']);
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $class_id = !empty($_POST['class_id']) ? (int)$_POST['class_id'] : null;
        $subject_id = !empty($_POST['subject_id']) ? (int)$_POST['subject_id'] : null;

        // Verify teacher owns this exam
        $check = $conn->prepare("SELECT id FROM exams WHERE id = ? AND created_by = ?");
        $check->bind_param("ii", $id, $teacher_id);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            // Update exam
            $stmt = $conn->prepare("UPDATE exams SET title=?, slug=?, description=?, duration=?, status=?, category_id=?, class_id=?, subject_id=? WHERE id=?");
            $stmt->bind_param("sssisiiii", $title, $slug, $description, $duration, $status, $category_id, $class_id, $subject_id, $id);
            $stmt->execute();

            $_SESSION['success'] = "Exam updated successfully";
        } else {
            $_SESSION['error'] = "You don't have permission to edit this exam";
        }
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Delete exam
    if (isset($_POST['delete_exam'])) {
        $id = (int)$_POST['id'];
        
        // Verify teacher owns this exam
        $check = $conn->prepare("SELECT id FROM exams WHERE id = ? AND created_by = ?");
        $check->bind_param("ii", $id, $teacher_id);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            $conn->query("DELETE FROM exams WHERE id = $id");
            $_SESSION['success'] = "Exam deleted successfully";
        } else {
            $_SESSION['error'] = "You don't have permission to delete this exam";
        }
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch data for display
$allCategories = $conn->query("SELECT id, name FROM categories WHERE status='active'");
$teacherExams = $conn->query("
    SELECT e.*, 
           cat.name AS category_name,
           cl.name AS class_name, 
           s.name AS subject_name,
           (SELECT COUNT(*) FROM exam_quizzes eq WHERE eq.exam_id = e.id) AS quiz_count
    FROM exams e
    LEFT JOIN categories cat ON e.category_id = cat.id
    LEFT JOIN classes cl ON e.class_id = cl.id
    LEFT JOIN subjects s ON e.subject_id = s.id
    WHERE e.created_by = $teacher_id
    ORDER BY e.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teacher - Set Exams</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        :root { --primary-color: #129990; }
        h2, h4 { color: var(--primary-color); }
        .btn-primary, .btn-success { background-color: var(--primary-color); border-color: var(--primary-color); }
        .btn-primary:hover, .btn-primary:focus, .btn-success:hover, .btn-success:focus { background-color: #0e7a75; border-color: #0e7a75; }
        .btn-danger { background-color: #d9534f; border-color: #d9534f; }
        table.table-bordered, table.table-bordered th, table.table-bordered td { border-color: var(--primary-color); }
        .form-inline input, .form-inline select, .form-inline textarea { display: inline-block; width: auto; vertical-align: middle; }
        .action-btn { margin-left: 5px; }
        @media (max-width: 576px) {
            form.row.g-3 > div { flex: 0 0 100% !important; max-width: 100% !important; }
            .action-btn { margin-top: 0.25rem; margin-left: 0; }
            table input.form-control, table select.form-select, table textarea.form-control { min-width: 100px; font-size: 0.85rem; padding: 0.25rem 0.5rem; }
            table td { white-space: nowrap; }
        }
        .table-responsive { overflow-x: auto; }
        .card-header.theme-bg { background-color: var(--primary-color); color: white; }
        .btn-theme { background-color: var(--primary-color); border-color: var(--primary-color); }
        .btn-theme:hover { background-color: #0e7a75; border-color: #0e7a75; }
        .nav-link.text-white:hover { background-color: #0e7a75; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Sidebar -->
            <div class="col-md-2 d-none d-md-block p-0" style="background-color: var(--primary-color);">
                <nav class="navbar border-bottom border-white">
                    <div class="container-fluid">
                        <span class="navbar-brand text-white">Teacher</span>
                    </div>
                </nav>
                <nav class="nav flex-column">
                    <a class="nav-link text-white" href="teacher_dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#quizSubMenu" role="button" aria-expanded="true">
                        <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
                    </a>
                    <div class="collapse show ms-3" id="quizSubMenu">
                        <a class="nav-link text-white" href="create_quiz_teacher.php"><i class="bi bi-plus-circle me-2"></i>Create Quiz</a>
                        <a class="nav-link text-white active" href="set_exams_teacher.php"><i class="bi bi-book me-2"></i>Set Exams</a>
                        <a class="nav-link text-white" href="manage_quiz_teacher.php"><i class="bi bi-list-ul me-2"></i>Quiz list</a>
                    </div>
                    <a class="nav-link text-white" href="track_results_teacher.php"><i class="bi bi-graph-up-arrow me-2"></i>View Results</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 col-12 p-0">
                <!-- Top navbar -->
                <nav class="navbar navbar-expand-lg" style="background-color: var(--primary-color);">
                    <div class="container-fluid">
                        <button class="btn text-white d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
                            <i class="bi bi-list"></i>
                        </button>
                        <span class="navbar-brand text-white d-md-none">Teacher</span>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- Mobile Sidebar -->
                <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMobile" style="background-color: var(--primary-color);">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title text-white">Menu</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                    </div>
                    <div class="offcanvas-body">
                        <nav class="nav flex-column">
                            <a class="nav-link text-white" href="teacher_dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                            <a class="nav-link text-white" data-bs-toggle="collapse" href="#quizSubMenuMobile" role="button" aria-expanded="true">
                                <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
                            </a>
                            <div class="collapse show ms-3" id="quizSubMenuMobile">
                                <a class="nav-link text-white" href="create_quiz_teacher.php"><i class="bi bi-plus-circle me-2"></i>Create Quiz</a>
                                <a class="nav-link text-white active" href="set_exams_teacher.php"><i class="bi bi-book me-2"></i>Set Exams</a>
                                <a class="nav-link text-white" href="manage_quiz_teacher.php"><i class="bi bi-list-ul me-2"></i>Quiz List</a>
                            </div>
                            <a class="nav-link text-white" href="track_results_teacher.php"><i class="bi bi-graph-up-arrow me-2"></i>View Results</a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="py-4 px-3 px-md-4">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $_SESSION['success'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <h2 class="mb-4">Manage Exams</h2>

                    <div class="card shadow border-0 rounded-4 mb-4">
                        <div class="card-header theme-bg fw-semibold fs-5">
                            <i class="bi bi-plus-circle me-2"></i> Create New Exam
                        </div>
                        <div class="card-body">
                            <form method="POST" class="row g-3" id="addExamForm">
                                <input type="hidden" name="action" value="add_exam">
                                
                                <!-- Category Selection -->
                                <div class="col-md-3">
                                    <label class="form-label">Category</label>
                                    <select id="category_id" name="category_id" class="form-select" required>
                                        <option value="">Select Category</option>
                                        <?php while ($cat = $allCategories->fetch_assoc()): ?>
                                            <option value="<?= $cat['id'] ?>" <?= isset($_POST['category_id']) && $_POST['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['name']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                
                                <!-- Class Selection -->
                                <div class="col-md-3">
                                    <label class="form-label">Class</label>
                                    <select id="class_id" name="class_id" class="form-select">
                                        <option value="">Select Class</option>
                                        <?php if (isset($_POST['class_id']) && $_POST['class_id']): ?>
                                            <?php 
                                            $selectedClass = $conn->query("SELECT id, name FROM classes WHERE id = ".(int)$_POST['class_id']);
                                            if ($selectedClass && $selectedClass->num_rows > 0):
                                                $class = $selectedClass->fetch_assoc();
                                            ?>
                                                <option value="<?= $class['id'] ?>" selected><?= htmlspecialchars($class['name']) ?></option>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                
                                <!-- Subject Selection -->
                                <div class="col-md-3">
                                    <label class="form-label">Subject</label>
                                    <select id="subject_id" name="subject_id" class="form-select">
                                        <option value="">Select Subject</option>
                                        <?php if (isset($_POST['subject_id']) && $_POST['subject_id']): ?>
                                            <?php 
                                            $selectedSubject = $conn->query("SELECT id, name FROM subjects WHERE id = ".(int)$_POST['subject_id']);
                                            if ($selectedSubject && $selectedSubject->num_rows > 0):
                                                $subject = $selectedSubject->fetch_assoc();
                                            ?>
                                                <option value="<?= $subject['id'] ?>" selected><?= htmlspecialchars($subject['name']) ?></option>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                
                                <!-- Exam Details -->
                                <div class="col-md-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" class="form-control" value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>" required>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">Slug</label>
                                    <input type="text" name="slug" class="form-control" value="<?= isset($_POST['slug']) ? htmlspecialchars($_POST['slug']) : '' ?>" required>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">Duration (minutes)</label>
                                    <input type="number" name="duration" class="form-control" value="<?= isset($_POST['duration']) ? htmlspecialchars($_POST['duration']) : '' ?>">
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active" <?= isset($_POST['status']) && $_POST['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= isset($_POST['status']) && $_POST['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                                </div>
                                
                                <!-- Quizzes Table -->
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
                                            <tbody id="quizzes-container">
                                                <tr>
                                                    <td colspan="3" class="text-center">Please select a category to view quizzes.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-theme w-100 mt-2">
                                        <i class="bi bi-check-circle me-1"></i> Create Exam
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <h4>My Exams</h4>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Category</th>
                                    <th>Class</th>
                                    <th>Subject</th>
                                    <th>Duration</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Quizzes</th>
                                    <th style="min-width:120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $teacherExams->fetch_assoc()): ?>
                                    <tr>
                                        <form method="POST">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <td><input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" class="form-control form-control-sm"></td>
                                            <td><input type="text" name="slug" value="<?= htmlspecialchars($row['slug']) ?>" class="form-control form-control-sm"></td>
                                            <td>
                                                <?= htmlspecialchars($row['category_name'] ?? 'None') ?>
                                                <input type="hidden" name="category_id" value="<?= $row['category_id'] ?>">
                                            </td>
                                            <td>
                                                <select name="class_id" class="form-select form-select-sm">
                                                    <option value="">None</option>
                                                    <?php 
                                                    $classes = $conn->query("SELECT id, name FROM classes WHERE status='active'");
                                                    while ($cl = $classes->fetch_assoc()): ?>
                                                        <option value="<?= $cl['id'] ?>" <?= $row['class_id'] == $cl['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cl['name']) ?></option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="subject_id" class="form-select form-select-sm">
                                                    <option value="">None</option>
                                                    <?php 
                                                    $subjects = $conn->query("SELECT id, name FROM subjects WHERE status='active'");
                                                    while ($sub = $subjects->fetch_assoc()): ?>
                                                        <option value="<?= $sub['id'] ?>" <?= $row['subject_id'] == $sub['id'] ? 'selected' : '' ?>><?= htmlspecialchars($sub['name']) ?></option>
                                                    <?php endwhile; ?>
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
                                                <a href="exam_quiz_manager_teacher.php?exam_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm"><?= $row['quiz_count'] ?></a>
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
    // Function to update classes and subjects based on selected category
    function updateFilters(categoryId, classId = null) {
        if (!categoryId) {
            document.getElementById('class_id').innerHTML = '<option value="">Select Class</option>';
            document.getElementById('subject_id').innerHTML = '<option value="">Select Subject</option>';
            updateQuizzes();
            return;
        }

        const formData = new FormData();
        formData.append('category_id', categoryId);
        if (classId) formData.append('class_id', classId);

        fetch('your_ajax_endpoint.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Update classes dropdown
            const classSelect = document.getElementById('class_id');
            classSelect.innerHTML = '<option value="">Select Class</option>';
            data.data.classes.forEach(cls => {
                classSelect.innerHTML += `<option value="${cls.id}">${cls.name}</option>`;
            });

            // Update subjects dropdown
            const subjectSelect = document.getElementById('subject_id');
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
            data.data.subjects.forEach(sub => {
                subjectSelect.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
            });

            // Preserve selected class if available
            if (classId) {
                classSelect.value = classId;
            }

            updateQuizzes();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Function to update quizzes based on filters
    function updateQuizzes() {
        const categoryId = document.getElementById('category_id').value;
        const classId = document.getElementById('class_id').value;
        const subjectId = document.getElementById('subject_id').value;
        
        if (!categoryId) {
            document.getElementById('quizzes-container').innerHTML = `
                <tr>
                    <td colspan="3" class="text-center">Please select a category to view quizzes.</td>
                </tr>`;
            return;
        }

        const formData = new FormData();
        formData.append('category_id', categoryId);
        if (classId) formData.append('class_id', classId);
        if (subjectId) formData.append('subject_id', subjectId);
        formData.append('teacher_id', <?= $teacher_id ?>);

        fetch('get_quizzes.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('quizzes-container').innerHTML = html;
        });
    }

    // Initialize event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Category change handler
        document.getElementById('category_id').addEventListener('change', function() {
            updateFilters(this.value);
        });
        
        // Class change handler
        document.getElementById('class_id').addEventListener('change', function() {
            const categoryId = document.getElementById('category_id').value;
            updateFilters(categoryId, this.value);
        });
        
        // Subject change handler
        document.getElementById('subject_id').addEventListener('change', function() {
            updateQuizzes();
        });
        
        // Initial load if category is already selected
        const initialCategory = document.getElementById('category_id').value;
        if (initialCategory) {
            updateFilters(initialCategory);
        }
    });
    </script>
</body>
</html>
<?php $conn->close(); ?>