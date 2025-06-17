<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$currentPage = basename($_SERVER['SCRIPT_NAME']); 
require __DIR__."/../vendor/autoload.php";
?>

<!-- total card section  -->
<?php
$conn = new mysqli(
    settings()['hostname'], 
    settings()['user'], 
    settings()['password'], 
    settings()['database']);

$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$totalQuizzes = $conn->query("SELECT COUNT(*) AS total FROM quizzes")->fetch_assoc()['total'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Palette - Premium Quiz Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/profile.css">
    <!-- animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- for total card section -->
       <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

<!-- FontAwesome -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
/>
<link rel="stylesheet" href="assets/css/total_card.css">

</head>
<body>
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow" style="background-color: #096B6B; z-index: 1040;">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
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
                <li class="nav-item"><a class="nav-link" href="#">Quiz Read</a></li>
                
                <li class="nav-item"><a class="nav-link" href="categories.php">Exam</a></li>
                <li class="nav-item"><a class="nav-link" href="#pricing">Subscription</a></li>
                <li class="nav-item"><a class="nav-link" href="leaderboard.php">Leaderboard</a></li>
                
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="faq.php">FAQ</a></li>
                <li class="nav-item"><a class="nav-link" href="stat.php">Profile</a></li>
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
                <?php
                if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true){
                    ?>
                    <li class="nav-item"><a class="nav-link btn btn-outline-primary text-white" href="profile.php"><i class="fas fa-user me-1"></i><?= $_SESSION['username'] ?> Profile</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-outline-primary text-white" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
                    <?php                   
                }
                else{
                ?>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-primary text-white" href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
                </li>
                <li class="nav-item" style = "margin-left:10px;">                    
                    <a class="nav-link btn btn-outline-primary text-white" href="register.php"><i class="fas fa-user-plus me-1"></i>Registration</a>
                </li>
                <?php } ?>
            </ul>

            <!-- <form class="d-flex" role="search">
                <input id="searchInput" class="form-control me-2" type="search"
                       placeholder="Search Quizzes..." aria-label="Search">
                <button class="btn btn-outline-light" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <ul class="navbar-nav ms-3">
                <li class="nav-item">
                    <button class="nav-link btn btn-outline-primary text-white d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="fas fa-sign-in-alt me-1"></i> Login
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link btn btn-outline-primary text-white d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#registerModal">
                        <i class="fas fa-user-plus me-1"></i> Registration
                    </button>
                </li>
            </ul> -->



        </div>
    </div>
</nav>


<?php if ($currentPage === 'index.php'): ?>
<!-- Carousel Section -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" style="margin-top: 80px;">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="assets/images/car1.png" class="d-block w-100" alt="Banner 1" style = "width:200px">
      <div class="carousel-caption d-none d-md-block">
        <h2 class="text-white">Welcome to Quiz Palette</h2>
        <p>Your premium quiz platform for Bangladeshi students.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="assets/images/car2.png" class="d-block w-100" alt="Banner 2">
      <div class="carousel-caption d-none d-md-block">
        <h2 class="text-white">Master Your Skills</h2>
        <p>Practice, compete, and earn badges!</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="assets/images/car1.png" class="d-block w-100" alt="Banner 3">
      <div class="carousel-caption d-none d-md-block">
        <h2 class="text-white">Shine on the Leaderboard</h2>
        <p>Track your performance and stand out.</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
<?php endif; ?>


<!-- Sticky Breadcrumb under Navbar -->

<div class="container-fluid" style="position: sticky; top: 70px; z-index: 1030;">
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
                        <!-- name has been changed -->
                        <a href="quiz4.php" class="fw-semibold text-decoration-none" style="color: #096B6B;">Quizzes</a>
                    </li>
                    <!-- <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-semibold" style="color: #096B6B;">Quiz</span>
                    </li> -->
                <?php elseif ($currentPage === 'categories.php'): ?>
                    <!-- <li class="breadcrumb-item">
                        <a href="quizzes.php" class="fw-semibold text-decoration-none" style="color: #096B6B;">Quizzes</a>
                    </li> -->
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-semibold" style="color: #096B6B;">Exam</span>
                    </li>
                <?php elseif ($currentPage === 'leaderboard.php'): ?>
                    <!-- <li class="breadcrumb-item">
                        <a href="quizzes.php" class="fw-semibold text-decoration-none" style="color: #096B6B;">Quizzes</a>
                    </li> -->
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
    // Toggle search input visibility
    $('#searchToggleBtn').on('click', function() {
        $('#searchForm').toggleClass('d-none');
        if (!$('#searchForm').hasClass('d-none')) {
            $('#searchInput').focus(); // Focus the input when shown
        }
    });


});
</script>


