<?php include "includes/header.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - QuizPalette</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS CSS -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #129990;
            --secondary-color: #096B68;
            --accent-color: #90D1CA;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --error-color: #FE4F2D;
            --warning-color: #ff9e00;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-color);
            color: var(--dark-color);
        }

        .bg-primary-custom {
            background-color: var(--primary-color) !important;
        }

        .bg-secondary-custom {
            background-color: #129990 !important;
        }

        .bg-accent-custom {
            background-color: var(--accent-color) !important;
        }

        .text-primary-custom {
            color: var(--primary-color) !important;
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary-custom:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 5rem 0;
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .team-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .team-card:hover {
            transform: translateY(-10px);
        }

        .team-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .team-head {
            border: 3px solid var(--primary-color);
        }
    </style>
</head>

<body>
    
    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">About QuizPalette</h1>
            <p class="lead mb-5">A team of passionate developers and educators creating the ultimate quiz platform</p>
            <a href="#our-team" class="btn btn-light btn-lg px-4 me-2">Meet Our Team</a>
            <a href="#our-mission" class="btn btn-outline-light btn-lg px-4">Our Mission</a>
        </div>
    </section>

    <!-- Team Section -->
    <section id="our-team" class="py-5" data-aos="fade-up" data-aos-duration="1500">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary-custom">Our Dedicated Team</h2>
                <p class="lead">5 passionate individuals working together to revolutionize learning</p>
            </div>
            <div class="row g-4 justify-content-center">
                <!-- Team Head -->
                <div class="col-md-4" data-aos="fade-up" data-aos-duration="1700">
                    <div class="card team-card text-center p-4">
                        <img src="assets/images/sir2.jpg" alt="Team Head"
                            class="rounded-circle team-img team-head mx-auto mb-3">
                        <h4>ASA Al Mamun</h4>
                        <p class="text-muted">Team Head & Mentor</p>
                        <p>Computer Science Professor with 20+ years of experience in educational technology.</p>
                        <div class="d-flex justify-content-center">
                            <a href="https://github.com/asamamun" class="btn btn-sm btn-outline-primary-custom me-2">
                                <i class="bi bi-github"></i>
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 justify-content-center mt-3">
                <!-- Team Members -->
                <div class="col-md-3" data-aos="fade-up" data-aos-duration="1650">
                    <div class="card team-card text-center p-4">
                        <img src="assets/images/rajon.jpg" alt="Team member"
                            class="rounded-circle team-img mx-auto mb-3">
                        <h4>Rajon Ahmed</h4>
                        <p class="text-muted">Team Member</p>
                        
                        <div class="d-flex justify-content-center">
                            <a href="#" class="btn btn-sm btn-outline-primary-custom me-2">
                                <i class="bi bi-github"></i>
                            </a>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-duration="1750">
                    <div class="card team-card text-center p-4">
                        <img src="assets/images/muntasir.jpeg" alt="Team member"
                            class="rounded-circle team-img mx-auto mb-3">
                        <h4>Muntasir Mahmud</h4>
                        <p class="text-muted">Team Member</p>
                        
                        <div class="d-flex justify-content-center">
                            <a href="https://github.com/muntasir0177" class="btn btn-sm btn-outline-primary-custom me-2">
                                <i class="bi bi-github"></i>
                            </a>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-duration="1850">
                    <div class="card team-card text-center p-4">
                        <img src="assets/images/SHAHINUR ISLAM.jpg" alt="Team member"
                            class="rounded-circle team-img mx-auto mb-3">
                        <h4>Sahinur Islam</h4>
                        <p class="text-muted">Team Member</p>
                        
                        <div class="d-flex justify-content-center">
                            <a href="#" class="btn btn-sm btn-outline-primary-custom me-2">
                                <i class="bi bi-github"></i>
                            </a>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-duration="1950">
                    <div class="card team-card text-center p-4">
                        <img src="assets/images/rakib.jpg" alt="Team member"
                            class="rounded-circle team-img mx-auto mb-3">
                        <h4>Rakib-Ul-Alam</h4>
                        <p class="text-muted">Team Member</p>
                        
                        <div class="d-flex justify-content-center">
                            <a href="https://github.com/Rakib-Ul-Alam" class="btn btn-sm btn-outline-primary-custom me-2">
                                <i class="bi bi-github"></i>
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Mission Section -->
    <section id="our-mission" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-bold text-primary-custom mb-4">Our Mission</h2>
                    <p class="lead">To create an engaging, accessible quiz platform that helps students master their
                        subjects through practice.</p>
                    <p>As a team of developers and educators, we combine technical expertise with pedagogical knowledge
                        to build a system that adapts to each learner's needs. Our platform provides immediate feedback,
                        tracks progress, and makes learning an enjoyable experience.</p>
                    <p>Under the guidance of our team head Al Mamun Sir, we're committed to educational innovation that
                        makes a real difference in students' lives.</p>
                </div>
                <div class="col-lg-6">
                    <img src="assets/images/car1.png"
                        alt="Team working together" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-secondary-custom text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h3 class="display-4 fw-bold">5</h3>
                    <p class="mb-0">Dedicated Team Members</p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h3 class="display-4 fw-bold">100+</h3>
                    <p class="mb-0">Hours of Development</p>
                </div>
                <div class="col-md-4">
                    <h3 class="display-4 fw-bold">100%</h3>
                    <p class="mb-0">Commitment to Quality</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-accent-custom">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Experience Our Quiz Platform</h2>
            <p class="lead mb-5">Join the educational revolution we're building together</p>
            <a href="index.php" class="btn btn-primary-custom btn-lg px-4 me-2">Try Now</a>
            <a href="contact.php" class="btn btn-outline-dark btn-lg px-4">Contact Team</a>
        </div>
    </section>


    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
</script>
</body>

</html>
<?php include "includes/footer.php" ?>