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

// Helper function to sanitize input
function sanitize($conn, $str) {
    return $conn->real_escape_string(trim($str));
}

// Handle subject creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_subject'])) {
    $category_id = (int)$_POST['category_id'];
    $class_id = (int)$_POST['class_id'];
    $subject_name = sanitize($conn, $_POST['subject_name']);
    $subject_slug = sanitize($conn, strtolower(str_replace(' ', '-', $_POST['subject_name'])));
    $description = sanitize($conn, $_POST['description']);
    
    // Check if slug already exists
    $check_slug = $conn->query("SELECT id FROM subjects WHERE slug = '$subject_slug'");
    if ($check_slug->num_rows > 0) {
        $subject_slug .= '-' . time(); // Append timestamp to make slug unique
    }

    $query = "INSERT INTO subjects (class_id, category_id, name, slug, description, status, created_by, created_at, updated_at) 
              VALUES ($class_id, $category_id, '$subject_name', '$subject_slug', '$description', 'active', $teacher_id, NOW(), NOW())";
    
    if ($conn->query($query)) {
        $_SESSION['success_message'] = "Subject created successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to create subject: " . $conn->error;
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle quiz creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_quiz'])) {
    // Sanitize inputs
    $title = sanitize($conn, $_POST['title']);
    $question = sanitize($conn, $_POST['question']);
    $option_a = sanitize($conn, $_POST['option_a']);
    $option_b = sanitize($conn, $_POST['option_b']);
    $option_c = sanitize($conn, $_POST['option_c']);
    $option_d = sanitize($conn, $_POST['option_d']);
    $correct_option = sanitize($conn, $_POST['correct_option']);
    $status = sanitize($conn, $_POST['status']);
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $class_id = !empty($_POST['class_id']) ? (int)$_POST['class_id'] : null;
    $subject_id = !empty($_POST['subject_id']) ? (int)$_POST['subject_id'] : null;
    $event_id = !empty($_POST['event_id']) ? (int)$_POST['event_id'] : null;

    // Validate required fields
    $required = ['title', 'question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $error_message = "All required fields must be filled.";
            break;
        }
    }

    if (!isset($error_message)) {
        $conn->autocommit(false);
        try {
            // Generate slug
            $quiz_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            
            // Check for duplicate slug
            $counter = 1;
            $original_slug = $quiz_slug;
            $slug_check = $conn->prepare("SELECT id FROM quizzes WHERE slug = ?");
            while (true) {
                $slug_check->bind_param("s", $quiz_slug);
                $slug_check->execute();
                if ($slug_check->get_result()->num_rows === 0) break;
                $quiz_slug = $original_slug . '-' . $counter++;
            }
            $slug_check->close();

            // Insert quiz
            $quiz_stmt = $conn->prepare("INSERT INTO quizzes 
                (title, slug, description, status, category_id, class_id, subject_id, event_id, created_by, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $description = $title; // Using title as description
            $quiz_stmt->bind_param("ssssiiiii", 
                $title, $quiz_slug, $description, $status, 
                $category_id, $class_id, $subject_id, $event_id, $teacher_id);
            
            if (!$quiz_stmt->execute()) {
                throw new Exception("Quiz insert failed: " . $quiz_stmt->error);
            }
            
            $quiz_id = $conn->insert_id;
            $quiz_stmt->close();

            // Insert question
            $question_stmt = $conn->prepare("INSERT INTO questions 
                (quiz_id, question_text, question_type, marks, explanation, status, created_at, updated_at) 
                VALUES (?, ?, 'multiple_choice', 1, NULL, ?, NOW(), NOW())");
            $question_stmt->bind_param("iss", $quiz_id, $question, $status);
            
            if (!$question_stmt->execute()) {
                throw new Exception("Question insert failed: " . $question_stmt->error);
            }
            
            $question_id = $conn->insert_id;
            $question_stmt->close();

            // Insert options
            $options = [
                ['a', $option_a, ($correct_option === 'a')],
                ['b', $option_b, ($correct_option === 'b')],
                ['c', $option_c, ($correct_option === 'c')],
                ['d', $option_d, ($correct_option === 'd')]
            ];
            
            $option_stmt = $conn->prepare("INSERT INTO question_options 
                (question_id, option_text, is_correct, order_index) 
                VALUES (?, ?, ?, ?)");
            
            foreach ($options as $index => $option) {
                $order = $index + 1;
                $is_correct = $option[2] ? 1 : 0;
                $option_stmt->bind_param("isii", $question_id, $option[1], $is_correct, $order);
                
                if (!$option_stmt->execute()) {
                    throw new Exception("Option insert failed: " . $option_stmt->error);
                }
            }
            
            $option_stmt->close();
            $conn->commit();
            
            $_SESSION['success_message'] = "Quiz created successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
            
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } finally {
            $conn->autocommit(true);
        }
    } else {
        $_SESSION['error_message'] = $error_message;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Display success/error messages from session
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Fetch data for dropdowns
$categories = $conn->query("SELECT id, name FROM categories WHERE status = 'active' ORDER BY name");
$events = $conn->query("SELECT id, name FROM events WHERE status = 'active' ORDER BY name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz - Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

        .card {
            border: none;
            border-radius: 10px;
        }

        .card-header {
            background-color: var(--main-color);
            color: white;
            border-radius: 10px 10px 0 0;
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

            .card {
                margin-bottom: 1rem;
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
                        <a class="nav-link text-white active" href="create_quiz_teacher.php"><i class="bi bi-plus-circle me-2"></i>Create Quiz</a>
                        <a class="nav-link text-white" href="set_exams_teacher.php"><i class="bi bi-book me-2"></i>Set Exams</a>
                        <a class="nav-link text-white" href="manage_quiz_teacher.php"><i class="bi bi-list-ul me-2"></i>Quiz List</a>
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
                                <a class="nav-link text-white active" href="create_quiz_teacher.php"><i class="bi bi-plus-circle me-2"></i>Create Quiz</a>
                                <a class="nav-link text-white" href="set_exams_teacher.php"><i class="bi bi-book me-2"></i>Set Exams</a>
                                <a class="nav-link text-white" href="manage_quiz_teacher.php"><i class="bi bi-list-ul me-2"></i>Quiz List</a>
                            </div>
                            <a class="nav-link text-white" href="track_results.php"><i class="bi bi-graph-up-arrow me-2"></i>Track Results</a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="p-4">
                    <h2 class="text-primary-emphasis"><i class="bi bi-plus-circle me-2"></i>Create Quiz</h2>

                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $success_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php elseif (isset($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow border-0 rounded-4 mb-4">
                        <div class="card-header fw-semibold fs-5">
                            <i class="bi bi-plus-circle me-2"></i>Add New Quiz
                        </div>
                        <div class="card-body">
                            <?php if ($categories->num_rows === 0): ?>
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    No active categories available. Please contact an admin to add them.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php else: ?>
                                <form method="POST" id="quizForm">
                                    <input type="hidden" name="add_quiz">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Quiz Title</label>
                                        <input type="text" name="title" id="title" class="form-control" required>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="category_id" class="form-label">Category</label>
                                            <select name="category_id" id="category_id" class="form-select" required>
                                                <option value="">Select Category</option>
                                                <?php while ($cat = $categories->fetch_assoc()): ?>
                                                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="class_id" class="form-label">Class</label>
                                            <select name="class_id" id="class_id" class="form-select">
                                                <option value="">Select Class</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="subject_id" class="form-label">Subject</label>
                                            <div class="input-group">
                                                <select name="subject_id" id="subject_id" class="form-select">
                                                    <option value="">Select Subject</option>
                                                </select>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                                                    <i class="bi bi-plus-circle"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="event_id" class="form-label">Event (Optional)</label>
                                            <select name="event_id" id="event_id" class="form-select">
                                                <option value="">Select Event</option>
                                                <?php while ($event = $events->fetch_assoc()): ?>
                                                    <option value="<?php echo $event['id']; ?>"><?php echo htmlspecialchars($event['name']); ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="question" class="form-label fw-semibold">Question</label>
                                        <textarea name="question" id="question" class="form-control" rows="3" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="option_a" class="form-label">Option A</label>
                                            <input type="text" name="option_a" id="option_a" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="option_b" class="form-label">Option B</label>
                                            <input type="text" name="option_b" id="option_b" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="option_c" class="form-label">Option C</label>
                                            <input type="text" name="option_c" id="option_c" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="option_d" class="form-label">Option D</label>
                                            <input type="text" name="option_d" id="option_d" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="correct_option" class="form-label">Correct Option</label>
                                            <select name="correct_option" id="correct_option" class="form-select" required>
                                                <option value="a">Option A</option>
                                                <option value="b">Option B</option>
                                                <option value="c">Option C</option>
                                                <option value="d">Option D</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" id="status" class="form-select">
                                                <option value="active" selected>Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 mt-2">
                                        <i class="bi bi-check-circle me-1"></i>Submit Quiz
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
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
                                            <label for="modal_category_id" class="form-label">Category</label>
                                            <select class="form-select" id="modal_category_id" name="category_id" required>
                                                <option value="">Select Category</option>
                                                <?php
                                                $categories->data_seek(0); // Reset pointer
                                                while ($cat = $categories->fetch_assoc()) {
                                                    echo "<option value='{$cat['id']}'>" . htmlspecialchars($cat['name']) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="modal_class_id" class="form-label">Class</label>
                                            <select class="form-select" id="modal_class_id" name="class_id" required>
                                                <option value="">Select Class</option>
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
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Helper function to display alerts
    function showAlert(message, type = 'danger') {
        const alertContainer = document.createElement('div');
        alertContainer.className = `alert alert-${type} alert-dismissible fade show`;
        alertContainer.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.p-4').prepend(alertContainer);
    }

    // Populate classes and subjects based on category selection
    async function fetchClassSubject(categoryId, classId = null) {
        const classSelect = document.getElementById('class_id');
        const subjectSelect = document.getElementById('subject_id');
        
        // Clear existing options but keep first empty option
        classSelect.innerHTML = '<option value="">Select Class</option>';
        subjectSelect.innerHTML = '<option value="">Select Subject</option>';

        if (!categoryId) {
            return;
        }

        try {
            const formData = new FormData();
            formData.append('category_id', categoryId);
            if (classId) formData.append('class_id', classId);

            const response = await fetch('get_class_subject.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.error) {
                showAlert(data.error, 'danger');
                return;
            }

            // Populate Classes
            if (data.data?.classes?.length) {
                data.data.classes.forEach(cls => {
                    const option = new Option(cls.name, cls.id);
                    classSelect.add(option);
                });
            }

            // Populate Subjects if classId was provided
            if (classId && data.data?.subjects?.length) {
                data.data.subjects.forEach(subject => {
                    const option = new Option(subject.name, subject.id);
                    subjectSelect.add(option);
                });
            }

        } catch (error) {
            console.error('Fetch error:', error);
            showAlert('Failed to load data. Please try again.', 'danger');
        }
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Main form: Category change
        document.getElementById('category_id').addEventListener('change', function() {
            fetchClassSubject(this.value);
        });

        // Main form: Class change (update subjects)
        document.getElementById('class_id').addEventListener('change', function() {
            const categoryId = document.getElementById('category_id').value;
            fetchClassSubject(categoryId, this.value);
        });

        // Modal form: Category change
        document.getElementById('modal_category_id').addEventListener('change', function() {
            const categoryId = this.value;
            const classSelect = document.getElementById('modal_class_id');
            classSelect.innerHTML = '<option value="">Select Class</option>';

            if (!categoryId) return;

            fetch('get_class_subject.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `category_id=${encodeURIComponent(categoryId)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    showAlert(data.error, 'danger');
                } else if (data.data.classes) {
                    data.data.classes.forEach(cls => {
                        const option = new Option(cls.name, cls.id);
                        classSelect.add(option);
                    });
                }
            })
            .catch(error => {
                console.error('Modal fetch error:', error);
                showAlert('Failed to load classes.', 'danger');
            });
        });

        // Subject creation form submission
        document.getElementById('createSubjectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('', { // Submit to same page
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.redirected) {
                    window.location.reload(); // Reload if redirected
                } else {
                    return response.text();
                }
            })
            .then(() => {
                // Refresh subjects dropdown after creation
                const categoryId = document.getElementById('category_id').value;
                const classId = document.getElementById('class_id').value;
                if (categoryId) {
                    fetchClassSubject(categoryId, classId);
                }
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('createSubjectModal'));
                modal.hide();
            })
            .catch(error => {
                console.error('Subject creation error:', error);
                showAlert('Failed to create subject.', 'danger');
            });
        });
    });
</script>
</body>
</html>

<?php $conn->close(); ?>