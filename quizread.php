<?php
include "includes/header.php";
require_once "db.php"; // Database connection file

// Fetch active categories
$category_query = "SELECT id, name, slug FROM categories WHERE status = 'active'";
$category_result = mysqli_query($conn, $category_query);
if (!$category_result) {
    error_log("Category query failed: " . mysqli_error($conn), 3, "logs/db_errors.log");
    die("Error fetching categories. Please try again later.");
}

// Fetch all classes grouped by category_id
$class_query = "SELECT id, category_id, name, slug FROM classes WHERE status = 'active'";
$class_result = mysqli_query($conn, $class_query);
if (!$class_result) {
    error_log("Class query failed: " . mysqli_error($conn), 3, "logs/db_errors.log");
    die("Error fetching classes. Please try again later.");
}
$classes_by_category = [];
while ($class = mysqli_fetch_assoc($class_result)) {
    $classes_by_category[$class['category_id']][] = $class;
}

// Fetch all subjects grouped by class_id
$subject_query = "SELECT id, class_id, name, slug FROM subjects WHERE status = 'active'";
$subject_result = mysqli_query($conn, $subject_query);
if (!$subject_result) {
    error_log("Subject query failed: " . mysqli_error($conn), 3, "logs/db_errors.log");
    die("Error fetching subjects. Please try again later.");
}
$subjects_by_class = [];
while ($subject = mysqli_fetch_assoc($subject_result)) {
    $subjects_by_class[$subject['class_id']][] = $subject;
}
?>

<link rel="stylesheet" href="assets/css/exam.css">
<style>
    /* Root Variables from exam.css */
    :root {
        --primary-color: #129990;
        --secondary-color: #096B68;
        --accent-color: #90D1CA;
        --light-color: #f8f9fa;
        --dark-color: #1a1e22;
        /* Darkened for better contrast */
        --success-color: #4cc9f0;
        --error-color: #FE4F2D;
        --warning-color: #ff9e00;
        --border-radius: 10px;
        --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --active-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
        /* Enhanced shadow for active states */
        --transition: all 0.3s ease;
    }

    /* Body Styles */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f7fa;
        color: var(--dark-color);
        line-height: 1.6;
    }

    /* Container */
    .container {
        margin-top: 80px;
    }

    /* App Container */
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
        color: #ffffff;
        /* Brighter white for better visibility */
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

    .menu-item:hover,
    .menu-item.active {
        background-color: rgba(255, 255, 255, 0.15);
        box-shadow: var(--active-shadow);
        /* Enhanced shadow on active */
    }

    .menu-link {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        text-decoration: none;
        color: #ffffff;
        /* Brighter white for better visibility */
        font-weight: 500;
    }

    .menu-link .icon {
        margin-right: 10px;
        font-size: 1.1rem;
        opacity: 0.8;
    }

    /* Sub-menu Styles for Classes */
    .sub-menu {
        display: none;
        list-style: none;
        padding: 0 0 0 20px;
        margin: 0;
        background-color: rgba(255, 255, 255, 0.05);
    }

    .sub-menu-item {
        margin-bottom: 6px;
    }

    .sub-menu-link {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        text-decoration: none;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 400;
        font-size: 0.85rem;
        transition: var(--transition);
    }

    .sub-menu-link:hover {
        color: var(--accent-color);
    }

    .sub-menu-link .icon {
        margin-right: 8px;
        font-size: 0.9rem;
        opacity: 0.7;
    }

    .sub-menu-item.active .sub-menu-link {
        color: var(--accent-color);
        font-weight: 500;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: var(--border-radius);
        box-shadow: var(--active-shadow);
        /* Enhanced shadow on active */
    }

    /* Main Content Styles */
    .content-area {
        flex: 1;
        padding: 20px;
        /* Reduced padding for better balance */
        overflow-y: auto;
        max-width: calc(100% - 280px);
        /* Ensure content area respects sidebar width */
    }

    .subject-list {
        margin-top: 20px;
        display: none;
    }

    .subject-list.active {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
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
        box-shadow: var(--active-shadow);
        /* Consistent shadow on hover */
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
        color: var(--dark-color);
        /* Improved text visibility */
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
        box-shadow: var(--active-shadow);
        /* Enhanced shadow on hover */
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
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
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
            max-width: 100%;
            /* Full width on smaller screens */
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

        .sub-menu-link {
            font-size: 0.8rem;
        }
    }
</style>

<div class="container d-flex flex-column justify-content-center">
</div>

<div class="app-container">
    <!-- Left Sidebar for Categories -->
    <div class="sidebar">
        <div class="sidebar-header">
            <span class="icon bi bi-journal-bookmark-fill"></span>
            <h2>Categories</h2>
        </div>
        <input type="text" id="searchInput" class="form-control search-input" placeholder="Search..." aria-label="Search">
        <ul class="menu-list" id="menuList">
            <?php while ($category = mysqli_fetch_assoc($category_result)) : ?>
                <li class="menu-item" data-category-id="<?php echo $category['id']; ?>">
                    <a href="#" class="menu-link" data-target="category-<?php echo $category['id']; ?>">
                        <span class="icon bi bi-folder-fill"></span>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </a>
                    <?php if (!empty($classes_by_category[$category['id']])) : ?>
                        <ul class="sub-menu" id="classes-<?php echo $category['id']; ?>">
                            <?php foreach ($classes_by_category[$category['id']] as $class) : ?>
                                <li class="sub-menu-item" data-class-id="<?php echo $class['id']; ?>">
                                    <a href="#" class="sub-menu-link" data-target="class-<?php echo $class['id']; ?>">
                                        <span class="icon bi bi-bookmark-fill"></span>
                                        <?php echo htmlspecialchars($class['name']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <!-- Right Content Area for Subjects -->
    <!-- Previous code remains the same until the subject cards section -->
    <div class="content-area" id="contentArea">
        <h2 class="page-title mb-4">Dashboard</h2>
        <?php foreach ($subjects_by_class as $class_id => $subjects) : ?>
            <div class="subject-list" id="class-<?php echo $class_id; ?>" style="display: none;">
                <h3>Subjects</h3>
                <div class="row g-4">
                    <?php foreach ($subjects as $subject) : ?>
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <!-- Changed link to quiz_read.php and added subject_id parameter -->
                            <a href="quiz_read.php?subject_id=<?php echo $subject['id']; ?>" class="card-link-wrapper">
                                <div class="icon-card h-100">
                                    <div class="icon-container">
                                        <img src="assets/images/math.png-444b34-128c.png" alt="<?php echo htmlspecialchars($subject['name']); ?>" class="icon-img">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($subject['name']); ?></h5>
                                        <p class="card-text">Explore quizzes for this subject</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <!-- Improved default content section -->
        <div id="default-content" class="text-center py-5">
            <div class="empty-state">
                <i class="bi bi-journal-bookmark fs-1 text-muted mb-3"></i>
                <h4 class="text-muted">No Class Selected</h4>
                <p class="text-muted">Please select a category and class to view subjects.</p>
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
        const subMenuItems = document.querySelectorAll('.sub-menu-item');
        const contentAreas = document.querySelectorAll('.subject-list');
        const defaultContent = document.getElementById('default-content');

        // Search functionality for categories
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toUpperCase();
            menuItems.forEach(item => {
                const text = item.textContent || item.innerText;
                item.style.display = text.toUpperCase().includes(filter) ? '' : 'none';
            });
        });

        // Category menu item click to toggle sub-menu
        menuItems.forEach(item => {
            const menuLink = item.querySelector('.menu-link');
            menuLink.addEventListener('click', function(e) {
                e.preventDefault();
                const categoryId = item.getAttribute('data-category-id');
                const subMenu = document.getElementById('classes-' + categoryId);

                // Toggle sub-menu visibility
                if (subMenu) {
                    const isVisible = subMenu.style.display === 'block';
                    document.querySelectorAll('.sub-menu').forEach(menu => {
                        menu.style.display = 'none';
                    });
                    subMenu.style.display = isVisible ? 'none' : 'block';
                }

                // Update active state
                menuItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');

                // Hide all content areas and show default content
                contentAreas.forEach(area => area.style.display = 'none');
                defaultContent.style.display = 'block';
            });
        });

        // Class sub-menu item click to show subjects
        subMenuItems.forEach(item => {
            const subMenuLink = item.querySelector('.sub-menu-link');
            subMenuLink.addEventListener('click', function(e) {
                e.preventDefault();

                // Update active states
                subMenuItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');

                // Hide all content areas and default content
                contentAreas.forEach(area => {
                    area.style.display = 'none';
                    area.classList.remove('active');
                });
                defaultContent.style.display = 'none';

                // Show the selected class's subjects
                const targetId = subMenuLink.getAttribute('data-target');
                const targetContent = document.getElementById(targetId);

                if (targetContent) {
                    targetContent.style.display = 'block';
                    targetContent.classList.add('active');
                } else {
                    // If no content found, show default message
                    defaultContent.style.display = 'block';
                }
            });
        });
    });
</script>
</body>

</html>
<?php $conn->close(); ?>
<?php include "includes/footer.php"?>