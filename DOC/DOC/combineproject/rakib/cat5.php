<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Education Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
            min-height: 100vh;
            background-color: white;
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
            margin: 20px;
        }
        
        /* Sidebar Styles - Updated */
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
            top: -63px;
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
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-header h2 {
            font-weight: 600;
            margin: 0;
            font-size: 1.5rem;
        }
        
        .sidebar-header .icon {
            margin-right: 12px;
            font-size: 1.5rem;
            color: white;
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
            color: white;
        }
        
        /* Updated Menu List Styles */
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
            position: relative;
        }
        
        .menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: white;
            transform: scaleY(0);
            transform-origin: bottom;
            transition: transform 0.3s ease;
        }
        
        .menu-item:hover, 
        .menu-item.active {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .menu-item.active::before {
            transform: scaleY(1);
        }
        
        .menu-link {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            text-decoration: none;
            color: white;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }
        
        .menu-link .icon {
            margin-right: 15px;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
            transition: var(--transition);
        }
        
        .menu-item:hover .icon,
        .menu-item.active .icon {
            transform: scale(1.1);
            
        }
        
        /* Content Area Styles (unchanged from your original) */
        .content-area {
            flex: 1;
            padding: 30px;
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
            border-left: 3px solid var(--secondary-color);
        }
        
        .icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
            min-width: 80px;
            background-color: var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .icon-card:hover .icon-container {
            background-color: var(--primary-color);
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
            color: var(--primary-color);
            margin-bottom: 5px;
            font-size: 1rem;
            transition: color 0.3s ease;
        }
        
        .icon-card:hover .card-title {

            color: var(--primary-color);
        }
        
        .card-text {
            color: #666;
            font-size: 0.85rem;
            line-height: 1.4;
            margin-bottom: 0;
        }
        
        /* Card link wrapper */
        .card-link-wrapper {
            display: block;
            text-decoration: none;
            color: inherit;
            height: 100%;
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
    <div class="app-container">
        <!-- Left Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <span class="icon bi bi-journal-bookmark-fill"></span>
                <h2>Menu</h2>
            </div>
            
            <input type="text" id="searchInput" class="form-control search-input" placeholder="Search..." aria-label="Search">
            
            <ul class="menu-list" id="menuList">
                <li class="menu-item active">
                    <a href="#" class="menu-link" data-target="class1-8">
                        <span class="icon bi bi-1-circle-fill"></span>
                        Class 1-8
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link" data-target="class9-10">
                        <span class="icon bi bi-2-circle-fill"></span>
                        Class 9-10
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <span class="icon bi bi-shield-fill"></span>
                        Cadet Coaching
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <span class="icon bi bi-door-open-fill"></span>
                        Admission
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <span class="icon bi bi-briefcase-fill"></span>
                        Job Solution
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <span class="icon bi bi-mortarboard-fill"></span>
                        BCS Preparation
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <span class="icon bi bi-translate"></span>
                        IELTS MCQ
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <span class="icon bi bi-globe2"></span>
                        GRE & SAT
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Right Content Area -->
         <!-- class 1-8 -->
            <div class="col-lg-9 col-md-8 content-area" id="class1-8">
                <h2 class="page-title mb-4">Dashboard</h2>
                
                <!-- Card Grid -->
                <div class="row g-4">
                    <!-- Card 1 -->
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <a href="quiz4.php" class="card-link-wrapper">
                            <div class="icon-card h-100">
                                <div class="icon-container">
                                    <img src="category_image/math.png-444b34-128c.png" alt="Class 1-8" class="icon-img">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Bangla Crash Quiz</h5>
                                    <p class="card-text">Primary education materials</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Card 2 -->
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <a href="quiz4.php" class="card-link-wrapper">
                            <div class="icon-card h-100">
                                <div class="icon-container">
                                    <img src="category_image/math.png-444b34-128c.png" alt="Class 9-10" class="icon-img">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Religion</h5>
                                    <p class="card-text">Secondary exam preparation</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Card 3 -->
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <a href="quiz4.php" class="card-link-wrapper">
                            <div class="icon-card h-100">
                                <div class="icon-container">
                                    <img src="category_image/math.png-444b34-128c.png" alt="Cadet Coaching" class="icon-img">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">General Knowledge</h5>
                                    <p class="card-text">Military school training</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Card 4 -->
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <a href="quiz4.php" class="card-link-wrapper">
                            <div class="icon-card h-100">
                                <div class="icon-container">
                                    <img src="category_image/math.png-444b34-128c.png" alt="Admission" class="icon-img">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Social Science</h5>
                                    <p class="card-text">University admission guidance</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card 5 -->
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <a href="quiz4.php" class="card-link-wrapper">
                            <div class="icon-card h-100">
                                <div class="icon-container">
                                    <img src="category_image/math.png-444b34-128c.png" alt="Admission" class="icon-img">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Science</h5>
                                    <p class="card-text">University admission guidance</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card 6 -->
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <a href="quiz4.php" class="card-link-wrapper">
                            <div class="icon-card h-100">
                                <div class="icon-container">
                                    <img src="category_image/math.png-444b34-128c.png" alt="Admission" class="icon-img">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Math</h5>
                                    <p class="card-text">University admission guidance</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card 7 -->
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <a href="quiz4.php" class="card-link-wrapper">
                            <div class="icon-card h-100">
                                <div class="icon-container">
                                    <img src="category_image/math.png-444b34-128c.png" alt="Admission" class="icon-img">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">English</h5>
                                    <p class="card-text">University admission guidance</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- class 9-10 -->
            <div class="col-lg-9 col-md-8 content-area" id="class9-10" style="display: none;">
                <h2 class="page-title mb-4">Dashboard</h2>
                
                <!-- Card Grid -->
                <div class="row g-4">
                    <!-- Card 1 -->
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <a href="quiz4.php" class="card-link-wrapper">
                            <div class="icon-card h-100">
                                <div class="icon-container">
                                    <img src="category_image/math.png-444b34-128c.png" alt="Class 1-8" class="icon-img">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Bangla 1st</h5>
                                    <p class="card-text">Primary education materials</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Card 2 -->
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <a href="quiz4.php" class="card-link-wrapper">
                            <div class="icon-card h-100">
                                <div class="icon-container">
                                    <img src="category_image/math.png-444b34-128c.png" alt="Class 9-10" class="icon-img">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Bangla 2nd</h5>
                                    <p class="card-text">Secondary exam preparation</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Card 3 -->
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <a href="quiz4.php" class="card-link-wrapper">
                            <div class="icon-card h-100">
                                <div class="icon-container">
                                    <img src="category_image/math.png-444b34-128c.png" alt="Cadet Coaching" class="icon-img">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">English Grammer</h5>
                                    <p class="card-text">Military school training</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Card 4 -->
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <a href="quiz4.php" class="card-link-wrapper">
                            <div class="icon-card h-100">
                                <div class="icon-container">
                                    <img src="category_image/math.png-444b34-128c.png" alt="Admission" class="icon-img">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Higher Math</h5>
                                    <p class="card-text">University admission guidance</p>
                                </div>
                            </div>
                        </a>
                    </div>
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
            
            // Hide all content areas except the default one (Class 1-8)
            document.querySelectorAll('.content-area').forEach(div => {
                if (div.id !== 'class1-8') {
                    div.style.display = 'none';
                }
            });

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
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    
                
                    // Hide all content areas
                    document.querySelectorAll('.content-area').forEach(div => div.style.display = 'none');
                    
                    // Show the selected target content
                    const targetId = this.querySelector('.menu-link').getAttribute('data-target');
                    if (targetId) {
                        document.getElementById(targetId).style.display = 'block';
                    }
                });
            });
        });
    </script>
</body>
</html>