<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Education Quiz Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #096B6B;
            --primary-medium: #129990;
            --primary-light: #90D1CA;
            --sidebar-bg: #f8f9fa;
            --content-bg: #ffffff;
            --text-on-dark: white;
            --text-on-light: #333333;
            --border-radius: 8px;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f5f5f5;
            color: var(--text-on-light);
        }
        
        .search-container {
            height: 100vh;
            overflow: hidden;
        }
        
        .sidebar {
            background-color: var(--sidebar-bg);
            padding: 20px;
            height: 100%;
            border-right: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .search-input {
            border-radius: var(--border-radius);
            padding: 10px 15px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            box-shadow: none;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: var(--primary-medium);
            box-shadow: 0 0 0 0.2rem rgba(18, 153, 144, 0.15);
        }
        
        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .menu-item {
            margin-bottom: 4px;
            border-radius: var(--border-radius);
            transition: all 0.2s ease;
        }
        
        .menu-item:hover, .menu-item.active {
            background-color: var(--primary-medium);
        }
        
        .menu-link {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: var(--text-on-light);
            font-weight: 500;
        }
        
        .menu-item:hover .menu-link, 
        .menu-item.active .menu-link {
            color: var(--text-on-dark);
        }
        
        .content-area {
            background-color: var(--content-bg);
            padding: 25px;
            height: 100%;
            overflow-y: auto;
        }
        
        .page-title {
            color: var(--primary-dark);
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        /* Quiz Specific Styles */
        .quiz-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .question-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 30px;
            border-left: 3px solid var(--primary-light);
        }
        
        .question-text {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 20px;
            font-size: 1.1rem;
        }
        
        .option-btn {
            display: block;
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 10px;
            text-align: left;
            background: var(--sidebar-bg);
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .option-btn:hover {
            background: var(--primary-light);
            color: var(--text-on-dark);
            border-color: var(--primary-medium);
        }
        
        .option-btn.selected {
            background: var(--primary-medium);
            color: white;
            border-color: var(--primary-dark);
        }
        
        .quiz-nav {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .quiz-btn {
            background: var(--primary-dark);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            transition: all 0.2s ease;
        }
        
        .quiz-btn:hover {
            background: var(--primary-medium);
            transform: translateY(-2px);
        }
        
        .quiz-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }
        
        .progress-container {
            margin-bottom: 30px;
        }
        
        .progress-bar {
            background-color: var(--primary-light);
        }
        
        @media (max-width: 768px) {
            .search-container {
                height: auto;
                overflow: visible;
            }
            
            .sidebar {
                border-right: none;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }
            
            .question-card {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid search-container">
        <div class="row h-100">
            <!-- Left Sidebar -->
            <div class="col-lg-3 col-md-4 sidebar">
                <h2 class="page-title">Quiz Menu</h2>
                <input type="text" id="searchInput" class="form-control search-input" placeholder="Search quizzes..." aria-label="Search">
                
                <ul class="menu-list" id="menuList">
                    <li class="menu-item active">
                        <a href="#" class="menu-link">Mathematics</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">Science</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">English</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">History</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">BCS Practice</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">IELTS Prep</a>
                    </li>
                </ul>
            </div>
            
            <!-- Right Content Area - Quiz Section -->
            <div class="col-lg-9 col-md-8 content-area">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="page-title mb-0">Mathematics Quiz</h2>
                    <span class="badge bg-primary">10 Questions</span>
                </div>
                
                <div class="progress-container">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 30%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">3/10</div>
                    </div>
                </div>
                
                <div class="quiz-container">
                    <!-- Question 1 -->
                    <div class="question-card">
                        <div class="question-text">
                            1. What is the value of π (pi) rounded to two decimal places?
                        </div>
                        <div class="options">
                            <button type="button" class="option-btn">3.14</button>
                            <button type="button" class="option-btn">3.16</button>
                            <button type="button" class="option-btn">3.18</button>
                            <button type="button" class="option-btn">3.12</button>
                        </div>
                    </div>
                    
                    <!-- Question 2 -->
                    <div class="question-card">
                        <div class="question-text">
                            2. What is the square root of 64?
                        </div>
                        <div class="options">
                            <button type="button" class="option-btn">6</button>
                            <button type="button" class="option-btn">8</button>
                            <button type="button" class="option-btn">7</button>
                            <button type="button" class="option-btn">9</button>
                        </div>
                    </div>
                    
                    <!-- Question 3 -->
                    <div class="question-card">
                        <div class="question-text">
                            3. Solve for x: 2x + 5 = 15
                        </div>
                        <div class="options">
                            <button type="button" class="option-btn">10</button>
                            <button type="button" class="option-btn">7</button>
                            <button type="button" class="option-btn">5</button>
                            <button type="button" class="option-btn">3</button>
                        </div>
                    </div>
                    
                    <div class="quiz-nav">
                        <button type="button" class="quiz-btn" disabled>
                            <i class="bi bi-arrow-left"></i> Previous
                        </button>
                        <button type="button" class="quiz-btn">
                            Next <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
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
                });
            });
            
            // Simulate progress update (in real app this would come from backend)
            function updateProgress(current, total) {
                const progressBar = document.querySelector('.progress-bar');
                const percentage = (current / total) * 100;
                progressBar.style.width = `${percentage}%`;
                progressBar.textContent = `${current}/${total}`;
                progressBar.setAttribute('aria-valuenow', percentage);
            }
            
            // Initialize progress
            updateProgress(3, 10);
        });
    </script>
</body>
</html>