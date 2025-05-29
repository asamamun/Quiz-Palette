<?php $currentPage = basename($_SERVER['SCRIPT_NAME']); ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Palette - Premium Quiz Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
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
                <li class="nav-item"><a class="nav-link" href="quiz4.php">Quizzes</a></li>
                <li class="nav-item"><a class="nav-link" href="leaderboard.php">Leaderboard</a></li>
                <li class="nav-item"><a class="nav-link" href="categories.php">Catagories</a></li>
                <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.html">Profile</a></li>
            </ul>
            <form class="d-flex" role="search">
                <input id="searchInput" class="form-control me-2" type="search"
                    placeholder="Search Quizzes..." aria-label="Search">
                <button class="btn btn-outline-light" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <ul class="navbar-nav ms-3">
                <li class="nav-item"><a class="nav-link btn btn-outline-primary text-white" href="login.html">Login</a></li>
                <li class="nav-item"><a class="nav-link btn btn-outline-primary text-white" href="login.html">Registration</a></li>
            </ul>
        </div>
    </div>
</nav>


<?php if ($currentPage === 'index.php'): ?>
<!-- Carousel Section -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" style="margin-top: 80px;">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="assets/images/Quiz1.png" class="d-block w-100" alt="Banner 1" style = "width:200px">
      <div class="carousel-caption d-none d-md-block">
        <h2 class="text-white">Welcome to Quiz Palette</h2>
        <p>Your premium quiz platform for Bangladeshi students.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="assets/images/banner2.jpg" class="d-block w-100" alt="Banner 2">
      <div class="carousel-caption d-none d-md-block">
        <h2 class="text-white">Master Your Skills</h2>
        <p>Practice, compete, and earn badges!</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="assets/images/banner3.jpg" class="d-block w-100" alt="Banner 3">
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
                        <!-- a minr change has been done inthe name  -->
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
                        <span class="fw-semibold" style="color: #096B6B;">Categories</span>
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