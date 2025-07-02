<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . "/../vendor/autoload.php";
$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>

<!-- total card section  -->
<?php
$conn = new mysqli(settings()['hostname'], settings()['user'], settings()['password'], settings()['database']);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$totalQuizzes = $conn->query("SELECT COUNT(*) AS total FROM quizzes")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Palette - Premium Quiz Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/total_card.css">
    
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow" style="background-color: #096B6B; z-index: 1040;">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="index.php">
                <img src="assets/images/web_icon2.png" alt="Quiz Palette Logo" style="width: 50px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="quizread.php">Quiz Read</a></li>
                    <li class="nav-item"><a class="nav-link" href="exam.php">Exam</a></li>
                    <li class="nav-item"><a class="nav-link" href="subcription.php">Subscription</a></li>
                    <li class="nav-item"><a class="nav-link" href="leaderboard.php">Leaderboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="faq.php">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                </ul>

                
                
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-light" id="searchToggleBtn" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                    <form class="d-none ms-2" id="searchForm" role="search">
                        <input id="searchInput" class="form-control" type="search" placeholder="Search Quizzes..." aria-label="Search">
                    </form>
                </div>

                <ul class="navbar-nav ms-3">
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) { ?>
                        <li class="nav-item"><a class="nav-link btn btn-outline-primary text-white" href="profile.php"><i class="fas fa-user me-1"></i><?= $_SESSION['username'] ?> Profile</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-primary text-white" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-primary text-white" href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
                        </li>
                        <li class="nav-item" style="margin-left:10px;">
                            <a class="nav-link btn btn-outline-primary text-white" href="register.php"><i class="fas fa-user-plus me-1"></i>Registration</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php if ($currentPage === 'index.php'): ?>
    <!-- Hero Carousel Section - Full Width -->
    <div class="container" style="margin-top: 70px;">
        <div class="row g-0">
            <!-- Carousel Column -->
            <div class="col-12 col-lg-9 mb-4 mb-md-0">
                <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000" style="max-height: 400px; overflow: hidden;">
                    
                    <!-- Carousel Indicators -->
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>

                    <!-- Carousel Items -->
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="assets/images/car1.png" class="d-block w-100 img-fluid" alt="Banner 1" style="object-fit: cover; height: 400px;" loading="lazy">
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                                <h4 class="text-white">Welcome to Quiz Palette</h4>
                                <p class="small">Your premium quiz platform for Bangladeshi students.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="assets/images/car2.png" class="d-block w-100 img-fluid" alt="Banner 2" style="object-fit: cover; height: 400px;" loading="lazy">
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                                <h4 class="text-white">Master Your Skills</h4>
                                <p class="small">Practice, compete, and earn badges!</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="assets/images/Quiz1.png" class="d-block w-100 img-fluid" alt="Banner 3" style="object-fit: cover; height: 400px;" loading="lazy">
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                                <h4 class="text-white">Shine on the Leaderboard</h4>
                                <p class="small">Track your performance and stand out.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>

            <!-- Side Column - Top Scorers -->
            <div class="col-12 col-lg-3">
                <div class="card w-100 h-100 border-0 shadow-sm" style="min-height: 400px;">
                    <!-- Card Header -->
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="card-title mb-2 text-center text-primary fw-semibold">
                            <i class="fas fa-trophy me-2"></i>Top Scorers
                        </h5>
                        <p class="text-muted small text-center mb-0">Challenge Yourself To Be On The Top!</p>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="card-body p-3 pt-0">
                        <ul class="list-group list-group-flush border-top">
                            <?php
                            $top_scorers_query = "
                                SELECT l.user_id, u.username, l.total_score, l.rank_position
                                FROM leaderboards l
                                JOIN users u ON l.user_id = u.id
                                ORDER BY l.total_score DESC
                                LIMIT 5
                            ";
                            $top_scorers_result = $conn->query($top_scorers_query);

                            if ($top_scorers_result && $top_scorers_result->num_rows > 0) {
                                $position = 1;
                                while ($row = $top_scorers_result->fetch_assoc()) {
                                    $badge_class = match ($position) {
                                        1 => 'bg-warning text-dark',
                                        2 => 'bg-secondary',
                                        3 => 'bg-danger',
                                        4 => 'bg-primary',
                                        5 => 'bg-success',
                                        default => 'bg-info'
                                    };
                                    $badge_text_class = match ($position) {
                                        1 => 'text-warning',
                                        2 => 'text-secondary',
                                        3 => 'text-danger',
                                        4 => 'text-primary',
                                        5 => 'text-success',
                                        default => 'text-info'
                                    };
                            ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <div class="d-flex align-items-center">
                                            <span class="badge <?php echo $badge_class; ?> me-2"><?php echo $position; ?></span>
                                            <span><?php echo htmlspecialchars($row['username']); ?></span>
                                        </div>
                                        <span class="badge <?php echo $badge_class; ?> bg-opacity-10 <?php echo $badge_text_class; ?> fw-medium"><?php echo $row['total_score']; ?> pts</span>
                                    </li>
                            <?php
                                    $position++;
                                }
                            } else {
                            ?>
                                    <li class="list-group-item text-center text-muted py-2">
                                        No scorers available
                                    </li>
                            <?php } ?>
                        </ul>
                    </div>
                    
                    <!-- Card Footer -->
                    <div class="card-footer bg-white border-top-0 text-center pt-0">
                        <a href="leaderboard.php" class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-list-ol me-2"></i>View Full Leaderboard
                        </a>
                        <p class="small text-muted mt-2 mb-0">Leaderboard will be updated automatically</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Sticky Breadcrumb under Navbar - Full Width -->
    <div class="container-fluid px-0" style="position: sticky; top: 70px; z-index: 1030;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-3 bg-body-tertiary rounded-0 mb-0" style="color: #096B6B;">
                <!-- Always show Home -->
                <li class="breadcrumb-item">
                    <a href="index.php" class="fw-semibold text-decoration-none" style="color: #096B6B;">Home</a>
                </li>

                <?php if ($currentPage === 'index.php'): ?>
                    <!-- Home page needs no further items -->
                <?php elseif ($currentPage === 'quiz4.php'): ?>
                    <li class="breadcrumb-item">
                        <a href="quiz4.php" class="fw-semibold text-decoration-none" style="color: #096B6B;">Quizzes</a>
                    </li>
                <?php elseif ($currentPage === 'categories.php'): ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-semibold" style="color: #096B6B;">Exam</span>
                    </li>
                <?php elseif ($currentPage === 'leaderboard.php'): ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-semibold" style="color: #096B6B;">Leaderboard</span>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-semibold" style="color: #096B6B;">
                            <?php echo htmlspecialchars(ucfirst(str_replace('.php', '', $currentPage))); ?>
                        </span>
                    </li>
                <?php endif; ?>
            </ol>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchToggleBtn').on('click', function() {
                $('#searchForm').toggleClass('d-none');
                if (!$('#searchForm').hasClass('d-none')) {
                    $('#searchInput').focus();
                }
            });
        });
    </script>

    