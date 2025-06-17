<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quizpallete";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Edit user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, first_name=?, last_name=?, phone=?, role=?, status=? WHERE id=?");
        $phone = $_POST['phone'] ?: null; // Handle empty phone as NULL
        $stmt->bind_param("sssssssi", 
            $_POST['username'], 
            $_POST['email'], 
            $_POST['first_name'], 
            $_POST['last_name'], 
            $phone, 
            $_POST['role'], 
            $_POST['status'], 
            $_POST['user_id']
        );
        $stmt->execute();
        $conn->commit();
        header("Location: user_list.php?message=User updated successfully");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: user_list.php?error=Failed to update user: " . urlencode($e->getMessage()));
        exit;
    }
}

// Handle Delete user
if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $conn->commit();
        header("Location: user_list.php?message=User deleted successfully");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: user_list.php?error=Failed to delete user: " . urlencode($e->getMessage()));
        exit;
    }
}

// Fetch users
$sql = "SELECT id, username, email, first_name, last_name, phone, role, status, 
        email_verified_at, created_at 
        FROM users 
        ORDER BY id DESC";
$users = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>User List</title>
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
                    <a class="nav-link text-white" href="set_exams.php"><i class="bi bi-book me-2"></i>Manage Exams</a>
                    <a class="nav-link text-white" href="quiz_list.php"><i class="bi bi-list-ul me-2"></i>Quiz List</a>
                </div>
                <a class="nav-link text-white active" href="user_list.php"><i class="bi bi-people-fill me-2"></i>Manage Users</a>
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
                            <a class="nav-link text-white" href="set_exams.php"><i class="bi bi-book me-2"></i>Manage Exams</a>
                            <a class="nav-link text-white" href="quiz_list.php"><i class="bi bi-list-ul me-2"></i>Quiz List</a>
                        </div>
                        <a class="nav-link text-white active" href="user_list.php"><i class="bi bi-people-fill me-2"></i>Manage Users</a>
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
                    <h2 class="text-primary-emphasis">User List</h2>
                </div>

                <table id="userTable" class="table table-bordered table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Email Verified</th>
                            <th>Created At</th>
                            <th style="width:120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($u = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?= $u['id'] ?></td>
                                <td><?= htmlspecialchars($u['username']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['first_name']) ?></td>
                                <td><?= htmlspecialchars($u['last_name']) ?></td>
                                <td><?= htmlspecialchars($u['phone'] ?? '') ?></td>
                                <td><?= ucfirst($u['role']) ?></td>
                                <td><?= ucfirst($u['status']) ?></td>
                                <td><?= $u['email_verified_at'] ? 'Yes' : 'No' ?></td>
                                <td><?= date('Y-m-d H:i:s', strtotime($u['created_at'])) ?></td>
                                <td>
                                    <button
                                        class="btn btn-sm btn-warning editBtn"
                                        data-user_id="<?= $u['id'] ?>"
                                        data-username="<?= htmlspecialchars($u['username'], ENT_QUOTES) ?>"
                                        data-email="<?= htmlspecialchars($u['email'], ENT_QUOTES) ?>"
                                        data-first_name="<?= htmlspecialchars($u['first_name'], ENT_QUOTES) ?>"
                                        data-last_name="<?= htmlspecialchars($u['last_name'], ENT_QUOTES) ?>"
                                        data-phone="<?= htmlspecialchars($u['phone'] ?? '', ENT_QUOTES) ?>"
                                        data-role="<?= $u['role'] ?>"
                                        data-status="<?= $u['status'] ?>"
                                    >Edit</button>
                                    <a href="?delete=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this user?')">Delete</a>
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
                        <input type="hidden" name="user_id" id="edit_user_id" />
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="edit_username" class="form-label">Username</label>
                                    <input type="text" name="username" id="edit_username" class="form-control" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_email" class="form-label">Email</label>
                                    <input type="email" name="email" id="edit_email" class="form-control" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_first_name" class="form-label">First Name</label>
                                    <input type="text" name="first_name" id="edit_first_name" class="form-control" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_last_name" class="form-label">Last Name</label>
                                    <input type="text" name="last_name" id="edit_last_name" class="form-control" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_phone" class="form-label">Phone</label>
                                    <input type="text" name="phone" id="edit_phone" class="form-control" />
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_role" class="form-label">Role</label>
                                    <select name="role" id="edit_role" class="form-select" required>
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_status" class="form-label">Status</label>
                                    <select name="status" id="edit_status" class="form-select" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="banned">Banned</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="edit_user" class="btn btn-primary">Update User</button>
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
    const table = $('#userTable').DataTable({
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
            { orderable: false, targets: 10 } // Disable sorting on Actions column
        ],
        responsive: true
    });

    // Edit button click handler
    $('#userTable').on('click', '.editBtn', function () {
        const btn = $(this);
        $('#edit_user_id').val(btn.data('user_id'));
        $('#edit_username').val(btn.data('username'));
        $('#edit_email').val(btn.data('email'));
        $('#edit_first_name').val(btn.data('first_name'));
        $('#edit_last_name').val(btn.data('last_name'));
        $('#edit_phone').val(btn.data('phone'));
        $('#edit_role').val(btn.data('role'));
        $('#edit_status').val(btn.data('status'));

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