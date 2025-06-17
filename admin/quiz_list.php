<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quizpallete";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Edit question
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_quiz'])) {
    $conn->begin_transaction();
    try {
        // Update question in the questions table
        $stmt = $conn->prepare("UPDATE questions SET question_text=?, status=? WHERE id=?");
        $stmt->bind_param("ssi", $_POST['question'], $_POST['status'], $_POST['question_id']);
        $stmt->execute();

        // Update options in聗

        // Update options in the question_options table
        $options = [
            ['id' => $_POST['option_a_id'], 'text' => $_POST['option_a'], 'is_correct' => $_POST['correct_option'] == 'a' ? 1 : 0, 'order_index' => 1],
            ['id' => $_POST['option_b_id'], 'text' => $_POST['option_b'], 'is_correct' => $_POST['correct_option'] == 'b' ? 1 : 0, 'order_index' => 2],
            ['id' => $_POST['option_c_id'], 'text' => $_POST['option_c'], 'is_correct' => $_POST['correct_option'] == 'c' ? 1 : 0, 'order_index' => 3],
            ['id' => $_POST['option_d_id'], 'text' => $_POST['option_d'], 'is_correct' => $_POST['correct_option'] == 'd' ? 1 : 0, 'order_index' => 4],
        ];

        $stmt = $conn->prepare("UPDATE question_options SET option_text=?, is_correct=? WHERE id=?");
        foreach ($options as $option) {
            $stmt->bind_param("sii", $option['text'], $option['is_correct'], $option['id']);
            $stmt->execute();
        }

        // Update quiz metadata
        $stmt = $conn->prepare("UPDATE quizzes SET category_id=?, subject_id=? WHERE id=?");
        $stmt->bind_param("iii", $_POST['category_id'], $_POST['subject_id'], $_POST['quiz_id']);
        $stmt->execute();

        $conn->commit();
        // Redirect with success message
        header("Location: quiz_list.php?message=Question updated successfully");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        // Redirect with error message
        header("Location: quiz_list.php?error=Failed to update question: " . urlencode($e->getMessage()));
        exit;
    }
}

// Handle Delete question
if (isset($_GET['delete'])) {
    $question_id = intval($_GET['delete']);
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("DELETE FROM question_options WHERE question_id=?");
        $stmt->bind_param("i", $question_id);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM questions WHERE id=?");
        $stmt->bind_param("i", $question_id);
        $stmt->execute();

        $conn->commit();
        header("Location: quiz_list.php?message=Question deleted successfully");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: quiz_list.php?error=Failed to delete question: " . urlencode($e->getMessage()));
        exit;
    }
}

// Fetch categories and subjects for dropdowns
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
$subjects = $conn->query("SELECT id, name FROM subjects ORDER BY name");

// Fetch questions with options, quiz, category, and subject names
$sql = "SELECT q.id AS quiz_id, q.title, q.status, q.category_id, q.subject_id, 
               c.name AS category_name, s.name AS subject_name,
               ques.id AS question_id, ques.question_text,
               opt1.option_text AS option_a, opt1.id AS option_a_id, opt1.is_correct AS is_correct_a,
               opt2.option_text AS option_b, opt2.id AS option_b_id, opt2.is_correct AS is_correct_b,
               opt3.option_text AS option_c, opt3.id AS option_c_id, opt3.is_correct AS is_correct_c,
               opt4.option_text AS option_d, opt4.id AS option_d_id, opt4.is_correct AS is_correct_d
        FROM quizzes q
        LEFT JOIN categories c ON q.category_id = c.id
        LEFT JOIN subjects s ON q.subject_id = s.id
        LEFT JOIN questions ques ON q.id = ques.quiz_id
        LEFT JOIN question_options opt1 ON ques.id = opt1.question_id AND opt1.order_index = 1
        LEFT JOIN question_options opt2 ON ques.id = opt2.question_id AND opt2.order_index = 2
        LEFT JOIN question_options opt3 ON ques.id = opt3.question_id AND opt3.order_index = 3
        LEFT JOIN question_options opt4 ON ques.id = opt4.question_id AND opt4.order_index = 4
        WHERE ques.id IS NOT NULL
        ORDER BY ques.id DESC";
$questions = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Quiz List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        :root {
            --main-color: #129990;
        }
        .table-primary {
            background-color: var(--main-color) !important;
            color: white;
        }
        .btn-primary, .btn-warning, .btn-danger {
            border: none;
        }
        .btn-primary {
            background-color: var(--main-color);
        }
        .btn-primary:hover {
            background-color: #0f7c7c;
        }
        .modal-header {
            background-color: var(--main-color);
            color: white;
        }
        .btn-close {
            background-color: white;
        }
        .alert-dismissible .btn-close {
            background: none;
        }
    </style>
</head>
<body class="bg-light">

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
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#quizSubMenu" role="button" aria-expanded="false">
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
            <nav class="navbar navbar-expand-lg" style="background-color: #129990;">
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
            <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMobile" style="background-color: #129990;">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title text-white">Menu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <nav class="nav flex-column">
                        <a class="nav-link text-white" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                        <a class="nav-link text-white" data-bs-toggle="collapse" href="#quizSubMenuMobile" role="button" aria-expanded="false">
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

            <div class="container py-4">
                <?php if (isset($_GET['message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_GET['message']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_GET['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="text-primary-emphasis">Quiz Questions List</h2>
                </div>

                <table id="quizTable" class="table table-bordered table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Question</th>
                            <th>Options</th>
                            <th>Correct Option</th>
                            <th>Category</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th style="width:120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($q = $questions->fetch_assoc()): ?>
                            <tr>
                                <td><?= $q['question_id'] ?></td>
                                <td><?= htmlspecialchars($q['question_text']) ?></td>
                                <td>
                                    A: <?= htmlspecialchars($q['option_a'] ?? '') ?><br />
                                    B: <?= htmlspecialchars($q['option_b'] ?? '') ?><br />
                                    C: <?= htmlspecialchars($q['option_c'] ?? '') ?><br />
                                    D: <?= htmlspecialchars($q['option_d'] ?? '') ?>
                                </td>
                                <td>
                                    <?= $q['is_correct_a'] ? 'A' : ($q['is_correct_b'] ? 'B' : ($q['is_correct_c'] ? 'C' : ($q['is_correct_d'] ? 'D' : ''))) ?>
                                </td>
                                <td><?= htmlspecialchars($q['category_name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($q['subject_name'] ?? '') ?></td>
                                <td><?= ucfirst($q['status']) ?></td>
                                <td>
                                    <button
                                        class="btn btn-sm btn-warning editBtn"
                                        data-question_id="<?= $q['question_id'] ?>"
                                        data-quiz_id="<?= $q['quiz_id'] ?>"
                                        data-question="<?= htmlspecialchars($q['question_text'] ?? '', ENT_QUOTES) ?>"
                                        data-option_a="<?= htmlspecialchars($q['option_a'] ?? '', ENT_QUOTES) ?>"
                                        data-option_b="<?= htmlspecialchars($q['option_b'] ?? '', ENT_QUOTES) ?>"
                                        data-option_c="<?= htmlspecialchars($q['option_c'] ?? '', ENT_QUOTES) ?>"
                                        data-option_d="<?= htmlspecialchars($q['option_d'] ?? '', ENT_QUOTES) ?>"
                                        data-option_a_id="<?= $q['option_a_id'] ?? '' ?>"
                                        data-option_b_id="<?= $q['option_b_id'] ?? '' ?>"
                                        data-option_c_id="<?= $q['option_c_id'] ?? '' ?>"
                                        data-option_d_id="<?= $q['option_d_id'] ?? '' ?>"
                                        data-correct_option="<?= $q['is_correct_a'] ? 'a' : ($q['is_correct_b'] ? 'b' : ($q['is_correct_c'] ? 'c' : ($q['is_correct_d'] ? 'd' : ''))) ?>"
                                        data-status="<?= $q['status'] ?>"
                                        data-category_id="<?= $q['category_id'] ?? '' ?>"
                                        data-subject_id="<?= $q['subject_id'] ?? '' ?>"
                                    >Edit</button>
                                    <a href="?delete=<?= $q['question_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this question?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" class="modal-content">
                        <input type="hidden" name="quiz_id" id="edit_quiz_id" />
                        <input type="hidden" name="question_id" id="edit_question_id" />
                        <input type="hidden" name="option_a_id" id="edit_option_a_id" />
                        <input type="hidden" name="option_b_id" id="edit_option_b_id" />
                        <input type="hidden" name="option_c_id" id="edit_option_c_id" />
                        <input type="hidden" name="option_d_id" id="edit_option_d_id" />
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Quiz Question</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_question" class="form-label">Question</label>
                                <textarea name="question" id="edit_question" class="form-control" required></textarea>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="edit_option_a" class="form-label">Option A</label>
                                    <input type="text" name="option_a" id="edit_option_a" class="form-control" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_option_b" class="form-label">Option B</label>
                                    <input type="text" name="option_b" id="edit_option_b" class="form-control" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_option_c" class="form-label">Option C</label>
                                    <input type="text" name="option_c" id="edit_option_c" class="form-control" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_option_d" class="form-label">Option D</label>
                                    <input type="text" name="option_d" id="edit_option_d" class="form-control" required />
                                </div>
                            </div>
                            <div class="row g-3 mt-3">
                                <div class="col-md-4">
                                    <label for="edit_correct_option" class="form-label">Correct Option</label>
                                    <select name="correct_option" id="edit_correct_option" class="form-select" required>
                                        <option value="a">A</option>
                                        <option value="b">B</option>
                                        <option value="c">C</option>
                                        <option value="d">D</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_status" class="form-label">Status</label>
                                    <select name="status" id="edit_status" class="form-select" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_category_id" class="form-label">Category</label>
                                    <select name="category_id" id="edit_category_id" class="form-select" required>
                                        <option value="">-- Select Category --</option>
                                        <?php
                                        $categories->data_seek(0);
                                        while ($cat = $categories->fetch_assoc()) {
                                            echo "<option value='{$cat['id']}'>" . htmlspecialchars($cat['name']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label for="edit_subject_id" class="form-label">Subject</label>
                                    <select name="subject_id" id="edit_subject_id" class="form-select" required>
                                        <option value="">-- Select Subject --</option>
                                        <?php
                                        $subjects->data_seek(0);
                                        while ($sub = $subjects->fetch_assoc()) {
                                            echo "<option value='{$sub['id']}'>" . htmlspecialchars($sub['name']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="edit_quiz" class="btn btn-primary">Update Question</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function () {
    const table = $('#quizTable').DataTable({
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'csvHtml5',
                text: '<i class="bi bi-filetype-csv me-2"></i>Export CSV',
                className: 'btn btn-primary',
                titleAttr: 'Export table to CSV',
                exportOptions: {
                    columns: ':visible'
                },
                init: function (api, node, config) {
                    $(node).css({
                        'background-color': '#129990',
                        'border': 'none',
                        'color': 'white',
                        'padding': '8px 16px',
                        'border-radius': '5px',
                        'font-weight': '500',
                        'transition': 'background-color 0.2s ease'
                    }).hover(
                        function () { $(this).css('background-color', '#0f7c7c'); },
                        function () { $(this).css('background-color', '#129990'); }
                    );
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="bi bi-filetype-pdf me-2"></i>Export PDF',
                className: 'btn btn-primary',
                titleAttr: 'Export table to PDF',
                exportOptions: {
                    columns: ':visible'
                },
                init: function (api, node, config) {
                    $(node).css({
                        'background-color': '#129990',
                        'border': 'none',
                        'color': 'white',
                        'padding': '8px 16px',
                        'border-radius': '5px',
                        'font-weight': '500',
                        'transition': 'background-color 0.2s ease'
                    }).hover(
                        function () { $(this).css('background-color', '#0f7c7c'); },
                        function () { $(this).css('background-color', '#129990'); }
                    );
                }
            }
        ],
        columnDefs: [
            { orderable: false, targets: 7 } // Disable sorting on Actions column
        ],
        responsive: true
    });

    // Edit button click handler
    $('#quizTable').on('click', '.editBtn', function () {
        const btn = $(this);
        $('#edit_quiz_id').val(btn.data('quiz_id'));
        $('#edit_question_id').val(btn.data('question_id'));
        $('#edit_question').val(btn.data('question'));
        $('#edit_option_a').val(btn.data('option_a'));
        $('#edit_option_b').val(btn.data('option_b'));
        $('#edit_option_c').val(btn.data('option_c'));
        $('#edit_option_d').val(btn.data('option_d'));
        $('#edit_option_a_id').val(btn.data('option_a_id'));
        $('#edit_option_b_id').val(btn.data('option_b_id'));
        $('#edit_option_c_id').val(btn.data('option_c_id'));
        $('#edit_option_d_id').val(btn.data('option_d_id'));
        $('#edit_correct_option').val(btn.data('correct_option'));
        $('#edit_status').val(btn.data('status'));
        $('#edit_category_id').val(btn.data('category_id'));
        $('#edit_subject_id').val(btn.data('subject_id'));

        // Show modal
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    });
});
</script>

</body>
</html>

<?php
$conn->close();
?>