<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quizpallete";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$quizCount = $userCount = $resultCount = $examCount = 0;

$q1 = $conn->query("SELECT COUNT(*) AS total FROM quizzes");
if ($q1) $quizCount = $q1->fetch_assoc()['total'];

$q2 = $conn->query("SELECT COUNT(*) AS total FROM users");
if ($q2) $userCount = $q2->fetch_assoc()['total'];

$q3 = $conn->query("SELECT COUNT(*) AS total FROM quiz_reports");
if ($q3) $resultCount = $q3->fetch_assoc()['total'];

$q4 = $conn->query("SELECT COUNT(*) AS total FROM exams");
if ($q4) $examCount = $q4->fetch_assoc()['total'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            /* min-height: 100vh; */
            /* padding-top: 1rem; */
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

        @media (max-width: 767.98px) {
            .card {
                margin-bottom: 1rem;
            }

            .btn-primary {
                width: 100%;
                padding: 8px;
                font-size: 14px;
            }

            .card-title {
                font-size: 1.1rem;
            }

            .card-text {
                font-size: 1.5rem;
            }
        }

        @media print {
            .sidebar, .navbar, .btn-primary {
                display: none;
            }

            .card {
                border: 1px solid #000;
                color: #000;
                background-color: #fff;
            }

            .card-body {
                background-color: #fff;
                color: #000;
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
                    <a class="nav-link text-white active" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#quizSubMenu" role="button" aria-expanded="false">
                        <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
                    </a>
                    <div class="collapse ms-3" id="quizSubMenu">
                        <a class="nav-link text-white" href="create_quiz.php"><i class="bi bi-plus-circle me-2"></i>Manage Courses</a>
                        <a class="nav-link text-white" href="set_exams.php"><i class="bi bi-book me-2"></i>Manage Exams</a>
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
                            <a class="nav-link text-white active" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                            <a class="nav-link text-white" data-bs-toggle="collapse" href="#quizSubMenuMobile" role="button" aria-expanded="false">
                                <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
                            </a>
                            <div class="collapse ms-3" id="quizSubMenuMobile">
                                <a class="nav-link text-white" href="create_quiz.php"><i class="bi bi-plus-circle me-2"></i>Manage Courses</a>
                                <a class="nav-link text-white" href="set_exams.php"><i class="bi bi-book me-2"></i>Manage Exams</a>
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

                <!-- Dashboard content -->
                <div class="p-4">
                    <h2 class="text-primary-emphasis"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h2>
                    <div class="row mt-4">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card text-white">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-ui-checks-grid me-2"></i>Total Quizzes</h5>
                                    <p class="card-text fs-4"><?php echo $quizCount; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card text-white">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-book me-2"></i>Total Exams</h5>
                                    <p class="card-text fs-4"><?php echo $examCount; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card text-white">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-people-fill me-2"></i>Total Users</h5>
                                    <p class="card-text fs-4"><?php echo $userCount; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card text-white">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-bar-chart-line-fill me-2"></i>Total Results</h5>
                                    <p class="card-text fs-4"><?php echo $resultCount; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary mt-3" onclick="window.print();">
                        <i class="bi bi-printer me-2"></i>Print Dashboard
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>