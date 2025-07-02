<?php
require __DIR__."/../vendor/autoload.php";
require __DIR__."/admincheck.php";
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quizpallete";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch leaderboard data with joins to get user, category, class, and subject names
$query = "
    SELECT 
        l.id, l.user_id, l.exam_id, l.category_id, l.class_id, l.subject_id, l.level, 
        l.total_score, l.total_attempts, l.average_score, l.rank_position, l.last_updated,
        u.username, c.name AS category_name, cl.name AS class_name, s.name AS subject_name
    FROM leaderboards l
    LEFT JOIN users u ON l.user_id = u.id
    LEFT JOIN categories c ON l.category_id = c.id
    LEFT JOIN classes cl ON l.class_id = cl.id
    LEFT JOIN subjects s ON l.subject_id = s.id
    ORDER BY l.rank_position ASC, l.total_score DESC
";
$result = $conn->query($query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Results</title>
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

        .card {
            border: none;
            border-radius: 10px;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-body {
            background-color: var(--main-color);
            color: white;
            border-radius: 10px;
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

        .table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        @media (max-width: 767.98px) {
            .card, .table {
                margin-bottom: 1rem;
            }

            .btn-primary {
                width: 100%;
                padding: 8px;
                font-size: 14px;
            }
        }

        @media print {
            .sidebar, .navbar, .btn-primary {
                display: none;
            }

            .table {
                border: 1px solid #000;
                color: #000;
                background-color: #fff;
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
                        <span class="navbar-brand text-white">Admin</span>
                    </div>
                </nav>
                <nav class="nav flex-column">
                    <a class="nav-link text-white" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#quizSubMenu" role="button" aria-expanded="false">
                        <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
                    </a>
                    <div class="collapse ms-3" id="quizSubMenu">
                        <a class="nav-link text-white" href="create_quiz.php"><i class="bi bi-plus-circle me-2"></i>Manage Courses</a>
                        <a class="nav-link text-white" href="set_exams.php"><i class="bi bi-book me-2"></i>Manage Exams</a>
                        <a class="nav-link text-white" href="quiz_list.php"><i class="bi bi-list-ul me-2"></i>Quiz List</a>
                    </div>
                    <a class="nav-link text-white" href="manage_users.php"><i class="bi bi-people-fill me-2"></i>Manage Users</a>
                    <a class="nav-link text-white active" href="track_results.php"><i class="bi bi-graph-up-arrow me-2"></i>Track Results</a>
                    <a class="nav-link text-white" href="leaderboards.php"><i class="bi bi-trophy-fill me-2"></i>Leaderboards</a>
                    <a class="nav-link text-white" href="requests.php"><i class="bi bi-inbox-fill me-2"></i>Requests</a>
                    <a class="nav-link text-white" href="certificate.php"><i class="bi bi-award-fill me-2"></i>Certificates</a>
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
                        <span class="navbar-brand text-white d-md-none">Admin</span>
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
                            <a class="nav-link text-white" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                            <a class="nav-link text-white" data-bs-toggle="collapse" href="#quizSubMenuMobile" role="button" aria-expanded="false">
                                <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
                            </a>
                            <div class="collapse ms-3" id="quizSubMenuMobile">
                                <a class="nav-link text-white" href="create_quiz.php"><i class="bi bi-plus-circle me-2"></i>Manage Courses</a>
                                <a class="nav-link text-white" href="set_exams.php"><i class="bi bi-book me-2"></i>Manage Exams</a>
                                <a class="nav-link text-white" href="quiz_list.php"><i class="bi bi-list-ul me-2"></i>Quiz List</a>
                            </div>
                            <a class="nav-link text-white" href="manage_users.php"><i class="bi bi-people-fill me-2"></i>Manage Users</a>
                            <a class="nav-link text-white active" href="track_results.php"><i class="bi bi-graph-up-arrow me-2"></i>Track Results</a>
                            <a class="nav-link text-white" href="leaderboards.php"><i class="bi bi-trophy-fill me-2"></i>Leaderboards</a>
                            <a class="nav-link text-white" href="requests.php"><i class="bi bi-inbox-fill me-2"></i>Requests</a>
                            <a class="nav-link text-white" href="certificate.php"><i class="bi bi-award-fill me-2"></i>Certificates</a>
                        </nav>
                    </div>
                </div>

                <!-- Track Results content -->
                <div class="p-4">
                    <h2 class="text-primary-emphasis"><i class="bi bi-graph-up-arrow me-2"></i>Track Results</h2>
                    <div class="mt-4">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Username</th>
                                            <th>Exam ID</th>
                                            <th>Category</th>
                                            <th>Class</th>
                                            <th>Subject</th>
                                            <th>Level</th>
                                            <th>Total Score</th>
                                            <th>Total Attempts</th>
                                            <th>Average Score</th>
                                            <th>Rank</th>
                                            <th>Last Updated</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                                <td><?php echo htmlspecialchars($row['username'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($row['exam_id'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($row['category_name'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($row['class_name'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($row['subject_name'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($row['level'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($row['total_score']); ?></td>
                                                <td><?php echo htmlspecialchars($row['total_attempts']); ?></td>
                                                <td><?php echo htmlspecialchars($row['average_score']); ?></td>
                                                <td><?php echo htmlspecialchars($row['rank_position']); ?></td>
                                                <td><?php echo htmlspecialchars($row['last_updated']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info" role="alert">
                                No results found in the leaderboards.
                            </div>
                        <?php endif; ?>
                    </div>
                    <button class="btn btn-primary mt-3" onclick="window.print();">
                        <i class="bi bi-printer me-2"></i>Print Results
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>