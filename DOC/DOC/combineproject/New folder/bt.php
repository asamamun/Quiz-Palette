<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/ico/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/ico/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/ico/favicon-16x16.png">
    <link rel="manifest" href="assets/ico/site.webmanifest">
    <link rel="mask-icon" href="assets/ico/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="assets/ico/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/libs/flickity/dist/flickity.min.css">
    <link rel="stylesheet" href="assets/libs/flickity-fade/flickity-fade.css">
    <link rel="stylesheet" href="assets/libs/@fortawesome/fontawesome-free/css/all.min.css">

    <!-- Bootstrap 5.3.6 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">

    <title>Quiz Palette | Welcome</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-xl navbar-dark fixed-top shadow">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <svg class="navbar-brand-svg" viewBox="0 0 245 80" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0 L 20 10 L 0 20 Z" class="navbar-brand-svg-i" fill="currentColor"></path>
                    <path d="M0 30 L 20 40 L 0 50 Z M20 45 L 0 55 L 20 65 Z M0 60 L 20 70 L 0 80 Z" fill="currentColor"></path>
                    <text x="40" y="70" font-family="Arial, sans-serif" font-size="60" font-weight="bold" letter-spacing="-.025em" fill="currentColor">QuizPallete.</text>
                </svg>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-xl-0">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarQuizzes" data-bs-toggle="dropdown" aria-expanded="false">Quizzes</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarQuizzes">
                            <li><a class="dropdown-item" href="math.html">Math</a></li>
                            <li><a class="dropdown-item" href="science.html">Science</a></li>
                            <li><a class="dropdown-item" href="history.html">History</a></li>
                            <li><a class="dropdown-item" href="literature.html">Literature</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#leaderboard">Leaderboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="#why-us">Why Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                </ul>
                <form class="d-flex me-3" role="search">
                    <input id="searchInput" class="form-control me-2" type="search" placeholder="Search Quizzes..." aria-label="Search">
                    <button class="btn btn-outline-light" type="submit"><i class="fas fa-search"></i></button>
                </form>
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="https://github.com" class="nav-link"><i class="fab fa-github"></i></a></li>
                    <li class="nav-item"><a href="https://twitter.com" class="nav-link"><i class="fab fa-twitter"></i></a></li>
                    <li class="nav-item"><a href="https://instagram.com" class="nav-link"><i class="fab fa-instagram"></i></a></li>
                    <li class="nav-item"><a class="nav-link btn btn-outline-primary text-white ms-2" href="sign-in.html">Sign In</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="section section-top section-full">
        <div class="bg-cover" style="background-image: url(assets/img/quiz.jpg);"></div>
        <div class="bg-overlay"></div>
        <div class="bg-triangle bg-triangle-light bg-triangle-bottom bg-triangle-left"></div>
        <div class="bg-triangle bg-triangle-light bg-triangle-bottom bg-triangle-right"></div>
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-8 col-lg-7 text-center">
                    <p class="font-weight-medium text-xs text-uppercase text-white text-muted">by QuizPallete</p>
                    <h1 class="text-white mb-4">Quizzes for Bangladeshi Students</h1>
                    <p class="lead text-white text-muted mb-5">Test your knowledge, earn badges, and prepare for exams with our tailored quizzes.</p>
                    <a href="#quizzes" class="btn btn-primary btn-lg">Start Quizzing Now</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Breadcrumb Navigation -->
    <div class="b-example-divider"></div>
    <div class="container my-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-3 bg-body-tertiary rounded-3">
                <li class="breadcrumb-item">
                    <a class="link-body-emphasis" href="#home">
                        <svg class="bi" width="16" height="16" aria-hidden="true">
                            <use xlink:href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css#house-door-fill"></use>
                        </svg>
                        <span class="visually-hidden">Home</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a class="link-body-emphasis fw-semibold text-decoration-none" href="#quizzes">Quizzes</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Categories</li>
            </ol>
        </nav>
    </div>

    <!-- Categories Section -->
    <section id="quizzes" class="section py-5">
        <div class="container">
            <h2 class="text-center mb-5">Explore Quiz Categories</h2>
            <div id="quizList" class="row">
                <div class="col-md-4 mb-4">
                    <a href="math.html" class="card border-0 shadow-sm quiz-card">
                        <img src="assets/img/math.jpg" alt="Math Quiz" class="card-img-top">
                        <div class="card-body">
                            <h4 class="card-title">Math Quiz</h4>
                            <p class="card-text text-muted">Challenge your math skills with engaging quizzes.</p>
                            <button class="btn btn-primary take-quiz-btn" data-category="math">Take Quiz</button>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-4">
                    <a href="science.html" class="card border-0 shadow-sm quiz-card">
                        <img src="assets/img/science.jpg" alt="Science Quiz" class="card-img-top">
                        <div class="card-body">
                            <h4 class="card-title">Science Quiz</h4>
                            <p class="card-text text-muted">Test your science knowledge with our quizzes.</p>
                            <button class="btn btn-primary take-quiz-btn" data-category="science">Take Quiz</button>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-4">
                    <a href="history.html" class="card border-0 shadow-sm quiz-card">
                        <img src="assets/img/history.jpg" alt="History Quiz" class="card-img-top">
                        <div class="card-body">
                            <h4 class="card-title">History Quiz</h4>
                            <p class="card-text text-muted">Explore historical events with our quizzes.</p>
                            <button class="btn btn-primary take-quiz-btn" data-category="history">Take Quiz</button>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section id="why-us" class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose Quiz Palette</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="card-body">
                            <i class="fas fa-shield-alt fa-3x mb-3 text-primary"></i>
                            <h4 class="card-title">Trusted Platform</h4>
                            <p class="card-text text-muted">Join thousands of Bangladeshi students preparing for success.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="card-body">
                            <i class="fas fa-book-open fa-3x mb-3 text-primary"></i>
                            <h4 class="card-title">Engaging Learning</h4>
                            <p class="card-text text-muted">Interactive quizzes to boost your knowledge.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="card-body">
                            <i class="fas fa-chalkboard-teacher fa-3x mb-3 text-primary"></i>
                            <h4 class="card-title">Expert Content</h4>
                            <p class="card-text text-muted">Quizzes crafted by top educators in Bangladesh.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leaderboard Section -->
    <section id="leaderboard" class="section py-5">
        <div class="container">
            <h2 class="text-center mb-5">Leaderboard</h2>
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Rank</th>
                                <th scope="col">Username</th>
                                <th scope="col">Score</th>
                                <th scope="col">Badge</th>
                            </tr>
                        </thead>
                        <tbody id="leaderboard-body">
                            <!-- Populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Pricing Plans</h2>
            <div class="row justify-content-center">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="card-body">
                            <h4 class="card-title">Basic</h4>
                            <h3 class="card-title text-primary">৳2000<span class="fs-6">/month</span></h3>
                            <ul class="list-unstyled">
                                <li>Access to basic quizzes</li>
                                <li>Bronze badge eligibility</li>
                                <li>Standard support</li>
                            </ul>
                            <a href="#" class="btn btn-outline-primary subscribe-btn" data-plan="basic">Buy Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-primary text-center">
                        <div class="card-body">
                            <h4 class="card-title">Standard</h4>
                            <h3 class="card-title text-primary">৳5000<span class="fs-6">/month</span></h3>
                            <ul class="list-unstyled">
                                <li>All quizzes included</li>
                                <li>Silver badge eligibility</li>
                                <li>Priority support</li>
                            </ul>
                            <a href="#" class="btn btn-primary subscribe-btn" data-plan="standard">Buy Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="card-body">
                            <h4 class="card-title">Premium</h4>
                            <h3 class="card-title text-primary">৳10000<span class="fs-6">/month</span></h3>
                            <ul class="list-unstyled">
                                <li>Unlimited quiz access</li>
                                <li>Gold badge eligibility</li>
                                <li>Exclusive content</li>
                            </ul>
                            <a href="#" class="btn btn-outline-primary subscribe-btn" data-plan="premium">Buy Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section py-5">
        <div class="container">
            <h2 class="text-center mb-5">Get in Touch</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h4>Contact Us</h4>
                    <p>Email: support@quizpalette.com</p>
                    <p>Phone: +880 1234-567890</p>
                    <p>Address: Dhaka, Bangladesh</p>
                </div>
                <div class="col-md-6 mb-4">
                    <h4>Quick Links</h4>
                    <ul class="list-unstyled">
                        <li><a href="#faq" class="text-decoration-none">FAQ</a></li>
                        <li><a href="privacy.html" class="text-decoration-none">Privacy Policy</a></li>
                        <li><a href="terms.html" class="text-decoration-none">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Frequently Asked Questions</h2>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq1">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                            How do I earn badges?
                        </button>
                    </h2>
                    <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Earn Bronze, Silver, and Gold badges by achieving high scores in quizzes!
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                            What payment methods are accepted?
                        </button>
                    </h2>
                    <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We accept bKash, Nagad, and other popular payment methods in Bangladesh.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="section bg-dark py-5">
        <div class="container">
            <div class="row align-self-center">
                <div class="col-md-auto">
                    <p>
                        <a href="#home" class="footer-brand text-white">
                            <svg class="footer-brand-svg" viewBox="0 0 235 80" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 0 L 20 10 L 0 20 Z" class="text-primary" fill="currentColor"></path>
                                <path d="M0 30 L 20 40 L 0 50 Z M20 45 L 0 55 L 20 65 Z M0 60 L 20 70 L 0 80 Z" fill="currentColor"></path>
                                <text x="40" y="70" font-family="Arial, sans-serif" font-size="60" font-weight="bold" letter-spacing="-.025em" fill="currentColor">QuizPallete.</text>
                            </svg>
                        </a>
                    </p>
                </div>
                <div class="col-md">
                    <ul class="list-unstyled list-inline text-md-end">
                        <li class="list-inline-item me-2"><a href="terms.html" class="text-white">Terms and Conditions</a></li>
                        <li class="list-inline-item me-2"><a href="privacy.html" class="text-white">Privacy Policy</a></li>
                        <li class="list-inline-item"><a href="#contact" class="text-white">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md">
                    <p class="text-white text-muted">
                        <small>© Copyright <span class="current-year">2025</span> QuizPallete. All rights reserved.</small>
                    </p>
                </div>
                <div class="col-md">
                    <ul class="list-inline list-unstyled text-md-end">
                        <li class="list-inline-item"><a href="https://github.com" class="text-white"><i class="fab fa-github"></i></a></li>
                        <li class="list-inline-item ms-3"><a href="https://twitter.com" class="text-white"><i class="fab fa-twitter"></i></a></li>
                        <li class="list-inline-item ms-3"><a href="https://instagram.com" class="text-white"><i class="fab fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login Required</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Please log in to access quizzes or subscribe to a plan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="sign-in.html" class="btn btn-primary">Login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <script src="assets/libs/flickity/dist/flickity.pkgd.min.js"></script>
    <script src="assets/libs/flickity-fade/flickity-fade.js"></script>
    <script src="scripts.js"></script>
</body>
</html>