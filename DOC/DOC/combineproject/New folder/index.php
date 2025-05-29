<?php include "includes/header.php"?>
    <!-- Categories Section -->
    <section id="quizzes" class="categories py-5">
        <div class="container">
            <h2 class="text-center mb-5">Explore Quiz Categories</h2>
            <div id="quizList" class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm quiz-card">
                        <div class="card-body">
                            <h5 class="card-title">Math</h5>
                            <p class="card-text">Challenge your math skills with engaging quizzes.</p>
                            <a href="#" class="btn btn-primary take-quiz-btn" data-category="math">Take Quiz</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm quiz-card">
                        <div class="card-body">
                            <h5 class="card-title">Admission</h5>
                            <p class="card-text">Prepare for university and college admission tests.</p>
                            <a href="#" class="btn btn-primary take-quiz-btn" data-category="admission">Take Quiz</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm quiz-card">
                        <div class="card-body">
                            <h5 class="card-title">Job Preparation</h5>
                            <p class="card-text">Ace your job interviews with practice quizzes.</p>
                            <a href="#" class="btn btn-primary take-quiz-btn" data-category="job">Take Quiz</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section id="why-us" class="why-us py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose Quiz Palette</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-shield-alt fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">Trusted Platform</h5>
                            <p class="card-text">Join thousands of students across Bangladesh.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-book-open fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">Engaging Learning</h5>
                            <p class="card-text">Interactive quizzes to boost your knowledge.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-chalkboard-teacher fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">Expert Content</h5>
                            <p class="card-text">Quizzes crafted by top educators in Bangladesh.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="pricing py-5">
        <div class="container">
            <h2 class="text-center mb-5">Pricing Plans</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title">Basic</h5>
                            <p class="card-text display-6">৳2000<span class="fs-6">/month</span></p>
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
                    <div class="card h-100 shadow-sm border-primary">
                        <div class="card-body text-center">
                            <h5 class="card-title">Standard</h5>
                            <p class="card-text display-6">৳5000<span class="fs-6">/month</span></p>
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
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title">Premium</h5>
                            <p class="card-text display-6">৳10000<span class="fs-6">/month</span></p>
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

    <!-- Leaderboard Section -->
    <section id="leaderboard" class="leaderboard py-5 bg-light">
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

    <!-- Contact Section -->
    <section id="contact" class="contact py-5">
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
                        <li><a href="#privacy" class="text-decoration-none">Privacy Policy</a></li>
                        <li><a href="#terms" class="text-decoration-none">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="faq py-5 bg-light">
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
                    <a href="login.html" class="btn btn-primary">Login</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <script src="assets/js/scripts.js"></script>
</body>
</html>