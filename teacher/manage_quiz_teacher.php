<?php
require __DIR__ . "/teachercheck.php"; // Ensure only teachers can access
require __DIR__ . "/../vendor/autoload.php";

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quizpallete";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$teacher_id = $_SESSION['user_id'];

// Fetch quizzes created by the teacher
$quizzes = $conn->query("SELECT q.*, c.name as category, cl.name as class, s.name as subject 
    FROM quizzes q 
    LEFT JOIN categories c ON q.category_id = c.id 
    LEFT JOIN classes cl ON q.class_id = cl.id 
    LEFT JOIN subjects s ON q.subject_id = s.id 
    WHERE q.created_by = $teacher_id 
    ORDER BY q.created_at DESC");

// Handle subject creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_subject'])) {
    $category_id = (int)$_POST['category_id'];
    $class_id = (int)$_POST['class_id'];
    $subject_name = $conn->real_escape_string($_POST['subject_name']);
    $subject_slug = $conn->real_escape_string(strtolower(str_replace(' ', '-', $_POST['subject_name'])));
    $description = $conn->real_escape_string($_POST['description']);
    
    // Check if slug already exists
    $check_slug = $conn->query("SELECT id FROM subjects WHERE slug = '$subject_slug'");
    if ($check_slug->num_rows > 0) {
        $subject_slug .= '-' . time(); // Append timestamp to make slug unique
    }

    $query = "INSERT INTO subjects (class_id, category_id, name, slug, description, status, created_by, created_at, updated_at) 
              VALUES ($class_id, $category_id, '$subject_name', '$subject_slug', '$description', 'active', $teacher_id, NOW(), NOW())";
    
    if ($conn->query($query)) {
        $success_message = "Subject created successfully!";
    } else {
        $error_message = "Failed to create subject: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Quizzes - Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        :root {
            --main-color: #129990;
            --main-hover: #0f7c7c;
            --secondary-color: #f8f9fa;
        }

        body {
            background-color: var(--secondary-color);
            font-size: 16px;
        }

        .sidebar {
            background-color: var(--main-color);
        }

        .nav-link {
            transition: background-color 0.2s ease;
        }

        .nav-link:hover {
            background-color: var(--main-hover);
            border-radius: 5px;
        }

        .nav-link.active {
            background-color: var(--main-hover);
            font-weight: bold;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .btn-primary {
            background-color: var(--main-color);
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }

        .btn-primary:hover {
            background-color: var(--main-hover);
        }

        @media (max-width: 767.98px) {
            .btn-primary {
                width: 100%;
                padding: 8px;
                font-size: 14px;
            }

            .table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">
            <!-- Sidebar for large screens -->
            <div class="col-md-2 d-none d-md-block sidebar">
                <nav class="navbar border-bottom border-white">
                    <div class="container-fluid">
                        <span class="navbar-brand text-white">Teacher</span>
                    </div>
                </nav>
                <nav class="nav flex-column">
                    <a class="nav-link text-white" href="teacher_dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                    <a class="nav-link text-white active" data-bs-toggle="collapse" href="#quizSubMenu" role="button" aria-expanded="true">
                        <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
                    </a>
                    <div class="collapse show ms-3" id="quizSubMenu">
                        <a class="nav-link text-white" href="create_quiz_teacher.php"><i class="bi bi-plus-circle me-2"></i>Create Quiz</a>
                        <a class="nav-link text-white" href="set_exams_teacher.php"><i class="bi bi-book me-2"></i>Set Exams</a>
                        <a class="nav-link text-white active" href="manage_quiz_teacher.php"><i class="bi bi-list-ul me-2"></i>Quiz List</a>
                    </div>
                    <a class="nav-link text-white" href="track_results.php"><i class="bi bi-graph-up-arrow me-2"></i>Track Results</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 col-12">
                <!-- Top navbar with hamburger -->
                <nav class="navbar navbar-expand-lg" style="background-color: var(--main-color);">
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

                <!-- Offcanvas sidebar for mobile -->
                <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMobile" style="background-color: var(--main-color);">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title text-white">Menu</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                    </div>
                    <div class="offcanvas-body">
                        <nav class="nav flex-column">
                            <a class="nav-link text-white" href="teacher_dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                            <a class="nav-link text-white active" data-bs-toggle="collapse" href="#quizSubMenuMobile" role="button" aria-expanded="true">
                                <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
                            </a>
                            <div class="collapse show ms-3" id="quizSubMenuMobile">
                                <a class="nav-link text-white" href="create_quiz_teacher.php"><i class="bi bi-plus-circle me-2"></i>Create Quiz</a>
                                <a class="nav-link text-white" href="set_exams_teacher.php.php"><i class="bi bi-book me-2"></i>Set Exams</a>
                                <a class="nav-link text-white active" href="manage_quiz_teacher.php"><i class="bi bi-list-ul me-2"></i>Quiz List</a>
                            </div>
                            <a class="nav-link text-white" href="track_results.php"><i class="bi bi-graph-up-arrow me-2"></i>Track Results</a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-primary-emphasis"><i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes</h4>
                        <div>
                            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                                <i class="bi bi-plus-circle me-2"></i>Create Subject
                            </button>
                            <a href="create_quiz_teacher.php" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Create Quiz</a>
                        </div>
                    </div>

                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php elseif (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>

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
                            <?php $i = 1; while ($row = $quizzes->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['category'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['class'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['subject'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $row['status'] == 'approved' ? 'success' : ($row['status'] == 'pending' ? 'warning' : 'secondary'); ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <a href="edit_quiz.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a>
                                        <a href="manage_questions.php?quiz_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-success"><i class="bi bi-question-circle"></i></a>
                                        <a href="delete_quiz.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Create Subject Modal -->
                <div class="modal fade" id="createSubjectModal" tabindex="-1" aria-labelledby="createSubjectModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createSubjectModalLabel">Create New Subject</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="createSubjectForm" method="POST" action="">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value="">Select Category</option>
                                            <?php
                                            $categories = $conn->query("SELECT id, name FROM categories WHERE status = 'active' ORDER BY name");
                                            while ($cat = $categories->fetch_assoc()) {
                                                echo "<option value='{$cat['id']}'>" . htmlspecialchars($cat['name']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="class_id" class="form-label">Class</label>
                                        <select class="form-select" id="class_id" name="class_id" required>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="subject_name" class="form-label">Subject Name</label>
                                        <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                                    </div>
                                    <input type="hidden" name="create_subject" value="1">
                                    <button type="submit" class="btn btn-primary">Create Subject</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('category_id').addEventListener('change', function() {
            const categoryId = this.value;
            const classSelect = document.getElementById('class_id');
            classSelect.innerHTML = '<option value="">Select Class</option>';

            if (categoryId) {
                fetch('get_classes.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'cat_id=' + encodeURIComponent(categoryId)
                })
                .then(response => response.json())
                .then(data => {
                    data.data.forEach(cls => {
                        if (cls.id !== -1) { // Skip "Select Class" option from API
                            const option = document.createElement('option');
                            option.value = cls.id;
                            option.textContent = cls.name;
                            classSelect.appendChild(option);
                        }
                    });
                })
                .catch(error => console.error('Error fetching classes:', error));
            }
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>