<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Education Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #096B6B;    /* Main dark teal */
            --primary-medium: #129990;  /* Vibrant teal for interactions */
            --primary-light: #90D1CA;   /* Light teal for backgrounds */
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
        
        /* Main Layout */
        .search-container {
            height: 100vh;
            overflow: hidden;
        }
        
        /* Sidebar Styles */
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
        
        /* Content Area */
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
        
        /* Professional Card Styles */
        .icon-card {
            border: none;
            border-radius: var(--border-radius);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 12px 18px rgba(0, 0, 0, 0.12);
            height: 100%;
            margin-bottom: 20px;
            background: white;
            overflow: hidden;
            display: flex;
            align-items: stretch;
        }
        
        .icon-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border-left: 3px solid var(--primary-medium);
        }
        
        .icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            min-width: 80px;
            background-color: var(--primary-medium);
            transition: all 0.3s ease;
        }
        
        .icon-card:hover .icon-container {
            background-color: var(--primary-medium);
        }
        
        .icon-img {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }
        
        .card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .card-title {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 5px;
            font-size: 1rem;
            transition: color 0.3s ease;
        }
        
        .icon-card:hover .card-title {
            color: var(--primary-medium);
        }
        
        .card-text {
            color: #666;
            font-size: 0.85rem;
            line-height: 1.4;
            margin-bottom: 0;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .search-container {
                height: auto;
                overflow: visible;
            }
            
            .sidebar {
                border-right: none;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }
            
            .icon-container {
                padding: 15px;
                min-width: 70px;
            }
            
            .card-body {
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
                <h2 class="page-title">Menu</h2>
                <input type="text" id="searchInput" class="form-control search-input" placeholder="Search..." aria-label="Search">
                
                <ul class="menu-list" id="menuList">
                    <li class="menu-item active">
                        <a href="#" class="menu-link" data-target="class1-8">Class 1-8</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link" data-target="class9-10">Class 9-10</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">Cadet Coaching</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">Admission</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">Job Solution</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">BCS Preparation</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">IELTS MCQ</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">GRE & SAT</a>
                    </li>
                </ul>
            </div>
            
            <!-- Right Content Area -->


            <!-- class 1-8 -->
            <div class="col-lg-9 col-md-8 content-area" id="class1-8" style="display: none;">
                <h2 class="page-title mb-4">Dashboard</h2>
                
                <!-- Card Grid -->
                <div class="row g-4">
                    <!-- Card 1 -->
                    
                    <div class="col-xl-4 col-lg-4 col-md-6" id="bangla_crash_quiz">
                    <a href="quiz.php" style="text-decoration: none; color: inherit;">
                        <div class="icon-card h-100">
                            <div class="icon-container">
                                <img src="category_image/math.png-444b34-128c.png" alt="Class 1-8" class="icon-img">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Bangla Crash Quiz</h5>
                                <p class="card-text">Primary education materials</p>
                            </div>
                        </div>
                    </div>
                    </a>
                    
                    <!-- Card 2 -->
                    
                    <div class="col-xl-4 col-lg-4 col-md-6">
                    <a href="quiz.php" style="text-decoration: none; color: inherit;">
                        <div class="icon-card h-100">
                            <div class="icon-container">
                                <img src="category_image/math.png-444b34-128c.png" alt="Class 9-10" class="icon-img">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Religion</h5>
                                <p class="card-text">Secondary exam preparation</p>
                            </div>
                        </div>
                    </div>
                    </a>
                    
                    <!-- Card 3 -->
                    <a href="quiz.php" style="text-decoration: none; color: inherit;">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="icon-card h-100">
                            <div class="icon-container">
                                <img src="category_image/math.png-444b34-128c.png" alt="Cadet Coaching" class="icon-img">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">General Knowledge</h5>
                                <p class="card-text">Military school training</p>
                            </div>
                        </div>
                    </div>
                    </a>
                    
                    <!-- Card 4 -->
                    <a href="quiz.php" style="text-decoration: none; color: inherit;">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="icon-card h-100">
                            <div class="icon-container">
                                <img src="category_image/math.png-444b34-128c.png" alt="Admission" class="icon-img">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Social Science</h5>
                                <p class="card-text">University admission guidance</p>
                            </div>
                        </div>
                    </div>
                    </a>

                    <!-- Card 5 -->
                    <a href="quiz.php" style="text-decoration: none; color: inherit;">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="icon-card h-100">
                            <div class="icon-container">
                                <img src="category_image/math.png-444b34-128c.png" alt="Admission" class="icon-img">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Science</h5>
                                <p class="card-text">University admission guidance</p>
                            </div>
                        </div>
                    </div>
                    </a>

                    <!-- Card 6 -->
                    <a href="quiz.php" style="text-decoration: none; color: inherit;">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="icon-card h-100">
                            <div class="icon-container">
                                <img src="category_image/math.png-444b34-128c.png" alt="Admission" class="icon-img">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Math</h5>
                                <p class="card-text">University admission guidance</p>
                            </div>
                        </div>
                    </div>
                    </a>

                    <!-- Card 7 -->
                    <a href="quiz.php" style="text-decoration: none; color: inherit;">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="icon-card h-100">
                            <div class="icon-container">
                                <img src="category_image/math.png-444b34-128c.png" alt="Admission" class="icon-img">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">English</h5>
                                <p class="card-text">University admission guidance</p>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
            </div>

            <!-- class 9-10 -->
            <div class="col-lg-9 col-md-8 content-area" id="class9-10" style="display: none;">
                <h2 class="page-title mb-4">Dashboard</h2>
                
                <!-- Card Grid -->
                <div class="row g-4">
                    <!-- Card 1 -->
                    <a href="quiz.php" style="text-decoration: none; color: inherit;">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="icon-card h-100">
                            <div class="icon-container">
                                <img src="category_image/math.png-444b34-128c.png" alt="Class 1-8" class="icon-img">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Bangla 1st</h5>
                                <p class="card-text">Primary education materials</p>
                            </div>
                        </div>
                    </div>
                    </a>
                    
                    <!-- Card 2 -->
                    <a href="quiz.php" style="text-decoration: none; color: inherit;">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="icon-card h-100">
                            <div class="icon-container">
                                <img src="category_image/math.png-444b34-128c.png" alt="Class 9-10" class="icon-img">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Bangla 2nd</h5>
                                <p class="card-text">Secondary exam preparation</p>
                            </div>
                        </div>
                    </div>
                    </a>
                    
                    <!-- Card 3 -->
                    <a href="quiz.php" style="text-decoration: none; color: inherit;">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="icon-card h-100">
                            <div class="icon-container">
                                <img src="category_image/math.png-444b34-128c.png" alt="Cadet Coaching" class="icon-img">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">English Grammer</h5>
                                <p class="card-text">Military school training</p>
                            </div>
                        </div>
                    </div>
                    </a>
                    
                    <!-- Card 4 -->
                    <a href="quiz.php" style="text-decoration: none; color: inherit;">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="icon-card h-100">
                            <div class="icon-container">
                                <img src="category_image/math.png-444b34-128c.png" alt="Admission" class="icon-img">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Higher Math</h5>
                                <p class="card-text">University admission guidance</p>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const menuItems = document.querySelectorAll('.menu-item');
            
            // Search functionality
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
        });



// for side bar click to show on right 
        document.querySelectorAll('.menu-link').forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault(); // Stop default link behavior

      // Hide all content areas
      document.querySelectorAll('.content-area').forEach(div => div.style.display = 'none');

      // Show the selected target content
      const targetId = this.getAttribute('data-target');
      const target = document.getElementById(targetId);
      if (target) {
        target.style.display = 'block';
      }
    });
  });
    </script>
</body>
</html>