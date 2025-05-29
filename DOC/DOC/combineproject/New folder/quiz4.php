

<?php include "includes/header.php"?>
<!-- for icons  -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">  
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
            --border-radius: 10px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-color);
            line-height: 1.6;
        }
        
        .app-container {
            display: flex;
            min-height: 60vh;
            background-color: white;
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
            margin: 20px;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px 20px;
            position: relative;
            z-index: 1;
        }
        
        .sidebar::before {

            
            content: '';
            position: absolute;
            top: -60px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: -1;
        }
        
        .sidebar-header {
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }
        
        .sidebar-header h2 {
            font-weight: 600;
            margin: 0;
            font-size: 1.5rem;
        }
        
        .sidebar-header .icon {
            margin-right: 12px;
            font-size: 1.5rem;
        }
        
        .search-input {
            border-radius: var(--border-radius);
            padding: 12px 15px;
            border: none;
            margin-bottom: 25px;
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            width: 100%;
            transition: var(--transition);
        }
        
        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .search-input:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.15);
        }
        
        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .menu-item {
            margin-bottom: 8px;
            border-radius: var(--border-radius);
            transition: var(--transition);
            overflow: hidden;
        }
        
        .menu-item:hover, .menu-item.active {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .menu-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            text-decoration: none;
            color: white;
            font-weight: 500;
        }
        
        .menu-link .icon {
            margin-right: 10px;
            font-size: 1.1rem;
            opacity: 0.8;
        }
        
        /* Main Content Styles */
        .content-area {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }
        
        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .quiz-title {
            color: var(--primary-color);
            font-weight: 600;
            margin: 0;
        }
        
        .quiz-badge {
            background-color: var(--accent-color);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .progress-container {
            margin-bottom: 30px;
            background-color: #e9ecef;
            border-radius: 10px;
            height: 10px;
        }
        
        .progress-bar {
            background: linear-gradient(90deg, var(--accent-color), var(--primary-color));
            border-radius: 10px;
            height: 100%;
            transition: width 0.5s ease;
        }
        
        /* Quiz Card Styles */
        .quiz-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .question-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 25px;
            margin-bottom: 25px;
            border-left: 4px solid var(--accent-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .question-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .question-text {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 20px;
            font-size: 1.1rem;
        }
        
        .options {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .option-btn {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            text-align: left;
            background: white;
            border: 2px solid #e9ecef;
            border-radius: var(--border-radius);
            transition: var(--transition);
            cursor: pointer;
            font-weight: 500;
        }
        
        .option-btn:hover {
            border-color: var(--accent-color);
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .option-btn.selected {
            background-color: rgba(67, 97, 238, 0.1);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .option-btn .option-marker {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #e9ecef;
            border-radius: 50%;
            margin-right: 15px;
            flex-shrink: 0;
            transition: var(--transition);
        }
        
        .option-btn.selected .option-marker {
            border-color: var(--primary-color);
            background-color: var(--primary-color);
            position: relative;
        }
        
        .option-btn.selected .option-marker::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            background-color: white;
            border-radius: 50%;
        }
        
        /* Navigation Styles */
        .quiz-nav {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        
        .quiz-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: var(--border-radius);
            transition: var(--transition);
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .quiz-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .quiz-btn:disabled {
            background: #e9ecef;
            color: #adb5bd;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .quiz-btn .icon {
            margin: 0 5px;
        }
        
        /* Pagination Styles */
        .pagination {
            justify-content: center;
            margin-top: 30px;
        }
        
        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .page-link {
            color: var(--primary-color);
            border: none;
            margin: 0 5px;
            border-radius: 50% !important;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
        }
        
        .page-link:hover {
            color: var(--secondary-color);
            background-color: rgba(67, 97, 238, 0.1);
        }
        
        /* Results Styles */
        .result-container {
            display: none;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            margin-top: 30px;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .result-summary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 25px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .result-summary::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        
        
        .result-summary h3 {
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
        }
        
        .result-summary .score-display {
            font-size: 3rem;
            font-weight: 700;
            margin: 20px 0;
            position: relative;
        }
        
        .result-stats {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            position: relative;
        }
        
        .stat-item {
            background: rgba(255, 255, 255, 0.15);
            padding: 10px 20px;
            border-radius: var(--border-radius);
            min-width: 100px;
        }
        
        .stat-item .stat-value {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .stat-item .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .correct-stat .stat-value {
            color: var(--success-color);
        }
        
        .wrong-stat .stat-value {
            color: var(--error-color);
        }
        
        .unanswered-stat .stat-value {
            color: var(--warning-color);
        }
        
        .result-item {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .result-item:last-child {
            border-bottom: none;
        }
        
        .result-item h5 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .result-item p {
            margin-bottom: 8px;
        }
        
        .correct-answer {
            color: var(--success-color);
            font-weight: 500;
        }
        
        .wrong-answer {
            color: var(--error-color);
            font-weight: 500;
        }
        
        .unanswered {
            color: var(--warning-color);
            font-weight: 500;
        }
        
        .submit-btn-container {
            text-align: center;
            margin-top: 40px;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .app-container {
                flex-direction: column;
                margin: 0;
                border-radius: 0;
            }
            
            .sidebar {
                width: 100%;
                padding: 20px;
            }
            
            .content-area {
                padding: 20px;
            }
        }
        
        @media (max-width: 576px) {
            .quiz-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .quiz-badge {
                margin-top: 10px;
            }
            
            .result-stats {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
            
            .stat-item {
                width: 100%;
                max-width: 200px;
            }
        }
    </style>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<div class="container d-flex flex-column justify-content-center" style="min-height: 10vh;">

</div>
    <div class="app-container">
        <!-- Left Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <span class="icon bi bi-journal-bookmark-fill"></span>
                <h2>Quiz Portal</h2>
            </div>
            
            <input type="text" id="searchInput" class="form-control search-input" placeholder="Search quizzes..." aria-label="Search">
            
            <ul class="menu-list" id="menuList">
                <li class="menu-item active">
                    <a href="#" class="menu-link">
                        <span class="icon bi bi-calculator"></span>
                        Mathematics
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <span class="icon bi bi-bezier2"></span>
                        Science
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <span class="icon bi bi-translate"></span>
                        English
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <span class="icon bi bi-building"></span>
                        History
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <span class="icon bi bi-mortarboard"></span>
                        BCS Practice
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <span class="icon bi bi-globe2"></span>
                        IELTS Prep
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Right Content Area - Quiz Section -->
        <div class="content-area">
            <div class="quiz-header">
                <h2 class="quiz-title">Mathematics Quiz</h2>
                <span class="quiz-badge">8 Questions</span>
            </div>
            
            <div class="progress-container">
                <div class="progress-bar" role="progressbar" id="globalProgress" style="width: 12.5%;" aria-valuenow="12.5" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            
            <div class="quiz-container">
                <!-- Quiz Pages -->
                <div class="quiz-page active" id="quizPage1">
                    <!-- Question 1 -->
                    <div class="question-card" data-question="1" data-correct="3.14">
                        <div class="question-text">
                            1. What is the value of π (pi) rounded to two decimal places?
                        </div>
                        <div class="options">
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                3.14
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                3.16
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                3.18
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                3.12
                            </button>
                        </div>
                    </div>
                    
                    <!-- Question 2 -->
                    <div class="question-card" data-question="2" data-correct="8">
                        <div class="question-text">
                            2. What is the square root of 64?
                        </div>
                        <div class="options">
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                6
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                8
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                7
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                9
                            </button>
                        </div>
                    </div>
                    
                    <!-- Question 3 -->
                    <div class="question-card" data-question="3" data-correct="5">
                        <div class="question-text">
                            3. Solve for x: 2x + 5 = 15
                        </div>
                        <div class="options">
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                10
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                7
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                5
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                3
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="quiz-page" id="quizPage2">
                    <!-- Question 4 -->
                    <div class="question-card" data-question="4" data-correct="25π">
                        <div class="question-text">
                            4. What is the area of a circle with radius 5?
                        </div>
                        <div class="options">
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                25π
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                10π
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                50π
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                5π
                            </button>
                        </div>
                    </div>
                    
                    <!-- Question 5 -->
                    <div class="question-card" data-question="5" data-correct="120">
                        <div class="question-text">
                            5. What is 5 factorial (5!)?
                        </div>
                        <div class="options">
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                25
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                60
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                120
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                720
                            </button>
                        </div>
                    </div>
                    
                    <!-- Question 6 -->
                    <div class="question-card" data-question="6" data-correct="32">
                        <div class="question-text">
                            6. What is the next number in the sequence: 2, 4, 8, 16, __?
                        </div>
                        <div class="options">
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                20
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                24
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                32
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                64
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="quiz-page" id="quizPage3">
                    <!-- Question 7 -->
                    <div class="question-card" data-question="7" data-correct="2x">
                        <div class="question-text">
                            7. What is the derivative of x²?
                        </div>
                        <div class="options">
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                x
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                2x
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                x³/3
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                2
                            </button>
                        </div>
                    </div>
                    
                    <!-- Question 8 -->
                    <div class="question-card" data-question="8" data-correct="180°">
                        <div class="question-text">
                            8. What is the sum of angles in a triangle?
                        </div>
                        <div class="options">
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                90°
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                180°
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                270°
                            </button>
                            <button type="button" class="option-btn">
                                <span class="option-marker"></span>
                                360°
                            </button>
                        </div>
                    </div>
                    
                    <!-- Submit Button (only visible on last page) -->
                    <div class="submit-btn-container">
                        <button type="button" class="quiz-btn" id="submitBtn">
                            <span class="icon bi bi-send-fill"></span>
                            Submit Quiz
                        </button>
                    </div>
                </div>
                
                <div class="quiz-nav">
                    <button type="button" class="quiz-btn" id="prevBtn" disabled>
                        <span class="icon bi bi-arrow-left"></span>
                        Previous
                    </button>
                    <button type="button" class="quiz-btn" id="nextBtn">
                        Next
                        <span class="icon bi bi-arrow-right"></span>
                    </button>
                </div>
                
                <!-- Pagination -->
                <nav aria-label="Quiz navigation">
                    <ul class="pagination" id="pagination">
                        <li class="page-item active"><a class="page-link" href="#" data-page="1">1</a></li>
                        <li class="page-item"><a class="page-link" href="#" data-page="2">2</a></li>
                        <li class="page-item"><a class="page-link" href="#" data-page="3">3</a></li>
                    </ul>
                </nav>
                
                <!-- Results Container -->
                <div class="result-container" id="resultContainer">
                    <div class="result-summary">
                        <h3>Quiz Results</h3>
                        <div class="score-display" id="scoreDisplay">0%</div>
                        <div class="result-stats">
                            <div class="stat-item correct-stat">
                                <div class="stat-value" id="correctCount">0</div>
                                <div class="stat-label">Correct</div>
                            </div>
                            <div class="stat-item wrong-stat">
                                <div class="stat-value" id="wrongCount">0</div>
                                <div class="stat-label">Wrong</div>
                            </div>
                            <div class="stat-item unanswered-stat">
                                <div class="stat-value" id="unansweredCount">0</div>
                                <div class="stat-label">Unanswered</div>
                            </div>
                        </div>
                    </div>
                    <div id="detailedResults"></div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const menuItems = document.querySelectorAll('.menu-item');
            
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toUpperCase();
                
                menuItems.forEach(item => {
                    const text = item.textContent || item.innerText;
                    item.style.display = text.toUpperCase().includes(filter) ? "" : "none";
                });
            });
            
            // Active menu item styling
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });
            
            // Quiz option selection
            const optionBtns = document.querySelectorAll('.option-btn');
            optionBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove selected class from siblings
                    this.parentNode.querySelectorAll('.option-btn').forEach(option => {
                        option.classList.remove('selected');
                    });
                    
                    // Add selected class to clicked option
                    this.classList.add('selected');
                    
                    // Store the selected answer
                    const questionCard = this.closest('.question-card');
                    questionCard.dataset.userAnswer = this.textContent.trim();
                });
            });
            
            // Pagination functionality
            const quizPages = document.querySelectorAll('.quiz-page');
            const pageLinks = document.querySelectorAll('.page-link');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            const globalProgress = document.getElementById('globalProgress');
            const resultContainer = document.getElementById('resultContainer');
            const scoreDisplay = document.getElementById('scoreDisplay');
            const correctCountEl = document.getElementById('correctCount');
            const wrongCountEl = document.getElementById('wrongCount');
            const unansweredCountEl = document.getElementById('unansweredCount');
            const detailedResults = document.getElementById('detailedResults');
            
            let currentPage = 1;
            const totalPages = quizPages.length;
            
            // Update progress bar
            function updateProgress() {
                const percentage = (currentPage / totalPages) * 100;
                globalProgress.style.width = `${percentage}%`;
                globalProgress.setAttribute('aria-valuenow', percentage);
                
                // Update button states
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages;
                
                // Update pagination
                pageLinks.forEach(link => {
                    const pageItem = link.parentElement;
                    pageItem.classList.toggle('active', parseInt(link.dataset.page) === currentPage);
                });
                
                // Show/hide submit button
                submitBtn.style.display = currentPage === totalPages ? 'block' : 'none';
            }
            
            // Show specific page
            function showPage(pageNumber) {
                quizPages.forEach(page => {
                    page.classList.remove('active');
                    page.style.display = 'none';
                });
                const activePage = document.getElementById(`quizPage${pageNumber}`);
                activePage.classList.add('active');
                activePage.style.display = 'block';
                currentPage = pageNumber;
                updateProgress();
            }
            
            // Pagination click handler
            pageLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const pageNumber = parseInt(this.dataset.page);
                    showPage(pageNumber);
                });
            });
            
            // Previous button handler
            prevBtn.addEventListener('click', function() {
                if (currentPage > 1) {
                    showPage(currentPage - 1);
                }
            });
            
            // Next button handler
            nextBtn.addEventListener('click', function() {
                if (currentPage < totalPages) {
                    showPage(currentPage + 1);
                }
            });
            
            // Submit button handler
            submitBtn.addEventListener('click', function() {
                calculateResults();
            });
            
            // Calculate and display results
            function calculateResults() {
                const questionCards = document.querySelectorAll('.question-card');
                let correctCount = 0;
                let wrongCount = 0;
                let unansweredCount = 0;
                
                // Generate detailed results
                let resultsHTML = '';
                
                questionCards.forEach(card => {
                    const questionNumber = card.dataset.question;
                    const correctAnswer = card.dataset.correct;
                    const userAnswer = card.dataset.userAnswer;
                    
                    let resultClass = '';
                    let resultText = '';
                    
                    if (userAnswer) {
                        if (userAnswer === correctAnswer) {
                            correctCount++;
                            resultClass = 'correct-answer';
                            resultText = 'Correct';
                        } else {
                            wrongCount++;
                            resultClass = 'wrong-answer';
                            resultText = 'Wrong';
                        }
                    } else {
                        unansweredCount++;
                        resultClass = 'unanswered';
                        resultText = 'Not answered';
                    }
                    
                    resultsHTML += `
                        <div class="result-item">
                            <h5>Question ${questionNumber}</h5>
                            <p>Your answer: <span class="${resultClass}">${userAnswer || 'Not answered'}</span></p>
                            <p>Correct answer: <span class="correct-answer">${correctAnswer}</span></p>
                            <p class="${resultClass}">${resultText}</p>
                        </div>
                    `;
                });
                
                // Calculate score (percentage)
                const totalQuestions = questionCards.length;
                const scorePercentage = Math.round((correctCount / totalQuestions) * 100);
                
                // Update results display
                scoreDisplay.textContent = `${scorePercentage}%`;
                correctCountEl.textContent = correctCount;
                wrongCountEl.textContent = wrongCount;
                unansweredCountEl.textContent = unansweredCount;
                detailedResults.innerHTML = resultsHTML;
                
                // Hide all quiz elements
                quizPages.forEach(page => page.style.display = 'none');
                document.querySelector('.quiz-nav').style.display = 'none';
                document.querySelector('.pagination').style.display = 'none';
                document.querySelector('.progress-container').style.display = 'none';
                submitBtn.style.display = 'none';
                document.querySelector('.quiz-header').style.display = 'none';
                
                // Show results container
                resultContainer.style.display = 'block';
            }
            
            // Initialize
            showPage(1);
        });
    </script>
</body>
</html>