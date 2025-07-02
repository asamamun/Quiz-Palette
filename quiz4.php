<?php
include "includes/header.php";
require_once "db.php"; // Database connection file

// Check if subject_id is provided
if (!isset($_GET['subject_id']) || !is_numeric($_GET['subject_id'])) {
    header("Location: menu.php");
    exit();
}

$subject_id = (int)$_GET['subject_id'];

// Fetch subject details
$subject_query = "SELECT name, slug, class_id, category_id FROM subjects WHERE id = ? AND status = 'active'";
$stmt = mysqli_prepare($conn, $subject_query);
mysqli_stmt_bind_param($stmt, "i", $subject_id);
mysqli_stmt_execute($stmt);
$subject_result = mysqli_stmt_get_result($stmt);
$subject = mysqli_fetch_assoc($subject_result);
mysqli_stmt_close($stmt);

if (!$subject) {
    header("Location: menu.php");
    exit();
}

// Fetch active categories
$category_query = "SELECT id, name, slug FROM categories WHERE status = 'active'";
$category_result = mysqli_query($conn, $category_query);
if (!$category_result) {
    error_log("Category query failed: " . mysqli_error($conn), 3, "logs/db_errors.log");
    die("Error fetching categories.");
}

// Fetch classes grouped by category_id
$class_query = "SELECT id, category_id, name, slug FROM classes WHERE status = 'active'";
$class_result = mysqli_query($conn, $class_query);
if (!$class_result) {
    error_log("Class query failed: " . mysqli_error($conn), 3, "logs/db_errors.log");
    die("Error fetching classes.");
}
$classes_by_category = [];
while ($class = mysqli_fetch_assoc($class_result)) {
    $classes_by_category[$class['category_id']][] = $class;
}

// Fetch subjects grouped by class_id
$subject_query = "SELECT id, class_id, name, slug FROM subjects WHERE status = 'active'";
$subject_result = mysqli_query($conn, $subject_query);
if (!$subject_result) {
    error_log("Subject query failed: " . mysqli_error($conn), 3, "logs/db_errors.log");
    die("Error fetching subjects.");
}
$subjects_by_class = [];
while ($sub = mysqli_fetch_assoc($subject_result)) {
    $subjects_by_class[$sub['class_id']][] = $sub;
}

// Fetch quizzes for the subject
$quiz_query = "SELECT id, title, description FROM quizzes WHERE subject_id = ? AND status = 'active'";
$stmt = mysqli_prepare($conn, $quiz_query);
mysqli_stmt_bind_param($stmt, "i", $subject_id);
mysqli_stmt_execute($stmt);
$quiz_result = mysqli_stmt_get_result($stmt);
$quizzes = mysqli_fetch_all($quiz_result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Fetch questions for all quizzes
$questions = [];
$quiz_ids = array_column($quizzes, 'id');
if (!empty($quiz_ids)) {
    $quiz_ids_placeholder = implode(',', array_fill(0, count($quiz_ids), '?'));
    $question_query = "SELECT q.id, q.quiz_id, q.question_text, q.marks, qo.id AS option_id, qo.option_text, qo.is_correct
                      FROM questions q
                      LEFT JOIN question_options qo ON q.id = qo.question_id
                      WHERE q.quiz_id IN ($quiz_ids_placeholder) AND q.status = 'active'
                      ORDER BY q.quiz_id, q.order_index, qo.order_index";
    $stmt = mysqli_prepare($conn, $question_query);
    mysqli_stmt_bind_param($stmt, str_repeat('i', count($quiz_ids)), ...$quiz_ids);
    mysqli_stmt_execute($stmt);
    $question_result = mysqli_stmt_get_result($stmt);

    $current_question_id = null;
    while ($row = mysqli_fetch_assoc($question_result)) {
        if ($row['id'] != $current_question_id) {
            $questions[$row['quiz_id']][$row['id']] = [
                'question_text' => $row['question_text'],
                'marks' => $row['marks'],
                'options' => []
            ];
            $current_question_id = $row['id'];
        }
        if ($row['option_id']) {
            $questions[$row['quiz_id']][$row['id']]['options'][] = [
                'id' => $row['option_id'],
                'text' => $row['option_text'],
                'is_correct' => $row['is_correct']
            ];
        }
    }
    mysqli_stmt_close($stmt);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_quiz'])) {
    $user_answers = $_POST['answers'] ?? [];
    $total_marks = 0;
    $correct_answers = 0;
    $wrong_answers = 0;
    $unanswered = 0;
    $results = [];

    foreach ($questions as $quiz_id => $quiz_questions) {
        foreach ($quiz_questions as $question_id => $question) {
            $selected_option = $user_answers[$question_id] ?? null;
            $is_correct = false;
            $marks_obtained = 0;
            $correct_option_text = '';
            $selected_option_text = $selected_option ? $question['options'][array_search($selected_option, array_column($question['options'], 'id'))]['text'] : 'Unanswered';

            foreach ($question['options'] as $option) {
                if ($option['is_correct']) {
                    $correct_option_text = $option['text'];
                }
                if ($selected_option == $option['id']) {
                    $is_correct = $option['is_correct'];
                    $marks_obtained = $is_correct ? $question['marks'] : 0;
                }
            }

            if ($selected_option) {
                if ($is_correct) {
                    $correct_answers++;
                } else {
                    $wrong_answers++;
                }
            } else {
                $unanswered++;
            }

            $total_marks += $marks_obtained;
            $results[$question_id] = [
                'question_text' => $question['question_text'],
                'selected_option' => $selected_option_text,
                'correct_option' => $correct_option_text,
                'is_correct' => $is_correct,
                'marks_obtained' => $marks_obtained
            ];
        }
    }

    // Store quiz attempt in database
    $user_id = $_SESSION['user_id'] ?? 0; // Adjust based on your authentication system
    $total_questions = $correct_answers + $wrong_answers + $unanswered;
    $percentage = $total_questions > 0 ? ($total_marks / array_sum(array_column(array_merge(...array_values($questions)), 'marks'))) * 100 : 0;
    $passed = $percentage >= 50; // Example passing criteria

    $attempt_query = "INSERT INTO quiz_attempts (user_id, quiz_id, total_questions, correct_answers, wrong_answers, unanswered, score, percentage, passed, status)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'completed')";
    $stmt = mysqli_prepare($conn, $attempt_query);
    $quiz_id = $quiz_ids[0] ?? 0; // Use first quiz_id or 0 if none
    mysqli_stmt_bind_param($stmt, "iiiiiiidd", $user_id, $quiz_id, $total_questions, $correct_answers, $wrong_answers, $unanswered, $total_marks, $percentage, $passed);
    mysqli_stmt_execute($stmt);
    $attempt_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // Store user answers
    foreach ($results as $question_id => $result) {
        $selected_option_id = array_search($result['selected_option'], array_column($questions[$quiz_id][$question_id]['options'], 'text'));
        $selected_option_id = $selected_option_id !== false ? $questions[$quiz_id][$question_id]['options'][$selected_option_id]['id'] : null;
        $answer_query = "INSERT INTO user_answers (attempt_id, question_id, selected_option_id, is_correct, marks_obtained)
                        VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $answer_query);
        mysqli_stmt_bind_param($stmt, "iiiid", $attempt_id, $question_id, $selected_option_id, $result['is_correct'], $result['marks_obtained']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
?>

<link rel="stylesheet" href="assets/css/exam.css">
<style>
/* Inherit styles from menu.php */
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

.container {
    margin-top: 80px;
}

.app-container {
    display: flex;
    min-height: 60vh;
    background-color: white;
    box-shadow: var(--box-shadow);
    border-radius: var(--border-radius);
    margin: 20px;
    overflow: hidden;
}

.sidebar {
    width: 280px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 20px;
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

.back-button {
    display: flex;
    align-items: center;
    color: white;
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 20px;
}

.back-button .icon {
    margin-right: 8px;
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

.sub-menu {
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

.sub-menu-item.active .sub-menu-link {
    color: var(--accent-color);
    font-weight: 500;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
}

.content-area {
    flex: 1;
    padding: 30px;
    overflow-y: auto;
}

.quiz-container {
    max-width: 1200px; /* Full-width for landscape view */
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

.result-container {
    display: <?php echo isset($results) ? 'block' : 'none'; ?>;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 30px;
    margin-top: 30px;
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
}

.result-summary .score-display {
    font-size: 3rem;
    font-weight: 700;
    margin: 20px 0;
}

.result-stats {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
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

<div class="container">
    <div class="app-container">
        <!-- Left Sidebar -->
        <div class="sidebar">
            <a href="menu.php" class="back-button">
                <span class="icon bi bi-arrow-left"></span> Back to Menu
            </a>
            <div class="sidebar-header">
                <span class="icon bi bi-journal-bookmark-fill"></span>
                <h2>Categories</h2>
            </div>
            <input type="text" id="searchInput" class="form-control search-input" placeholder="Search..." aria-label="Search">
            <ul class="menu-list" id="menuList">
                <?php while ($category = mysqli_fetch_assoc($category_result)) : ?>
                    <li class="menu-item <?php echo $category['id'] == $subject['category_id'] ? 'active' : ''; ?>" data-category-id="<?php echo $category['id']; ?>">
                        <a href="#" class="menu-link" data-target="category-<?php echo $category['id']; ?>">
                            <span class="icon bi bi-folder-fill"></span>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </a>
                        <?php if (!empty($classes_by_category[$category['id']])) : ?>
                            <ul class="sub-menu" id="classes-<?php echo $category['id']; ?>" style="display: <?php echo $category['id'] == $subject['category_id'] ? 'block' : 'none'; ?>;">
                                <?php foreach ($classes_by_category[$category['id']] as $class) : ?>
                                    <li class="sub-menu-item <?php echo $class['id'] == $subject['class_id'] ? 'active' : ''; ?>" data-class-id="<?php echo $class['id']; ?>">
                                        <a href="#" class="sub-menu-link" data-target="class-<?php echo $class['id']; ?>">
                                            <span class="icon bi bi-bookmark-fill"></span>
                                            <?php echo htmlspecialchars($class['name']); ?>
                                        </a>
                                        <?php if (!empty($subjects_by_class[$class['id']])) : ?>
                                            <ul class="sub-menu">
                                                <?php foreach ($subjects_by_class[$class['id']] as $sub) : ?>
                                                    <li class="sub-menu-item <?php echo $sub['id'] == $subject_id ? 'active' : ''; ?>">
                                                        <a href="quiz4.php?subject_id=<?php echo $sub['id']; ?>" class="sub-menu-link">
                                                            <span class="icon bi bi-book-fill"></span>
                                                            <?php echo htmlspecialchars($sub['name']); ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>

        <!-- Right Content Area -->
        <div class="content-area">
            <div class="quiz-container">
                <div class="quiz-header">
                    <h2 class="quiz-title"><?php echo htmlspecialchars($subject['name']); ?> Quiz</h2>
                    <span class="quiz-badge"><?php echo count($questions) ?> Quizzes</span>
                </div>

                <?php if (empty($quizzes)): ?>
                    <p>No quizzes available for this subject.</p>
                <?php else: ?>
                    <form id="quizForm" method="POST">
                        <?php foreach ($questions as $quiz_id => $quiz_questions): ?>
                            <?php foreach ($quiz_questions as $question_id => $question): ?>
                                <div class="question-card">
                                    <div class="question-text"><?php echo htmlspecialchars($question['question_text']); ?></div>
                                    <div class="options">
                                        <?php foreach ($question['options'] as $option): ?>
                                            <label class="option-btn">
                                                <input type="radio" name="answers[<?php echo $question_id; ?>]" value="<?php echo $option['id']; ?>" class="option-radio">
                                                <span class="option-marker"></span>
                                                <span class="option-text"><?php echo htmlspecialchars($option['text']); ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        <div class="submit-btn-container">
                            <button type="submit" name="submit_quiz" class="quiz-btn">Submit Quiz</button>
                        </div>
                    </form>
                <?php endif; ?>

                <?php if (isset($results)): ?>
                    <div class="result-container">
                        <div class="result-summary">
                            <h3>Quiz Results</h3>
                            <div class="score-display"><?php echo number_format($percentage, 2); ?>%</div>
                            <div class="result-stats">
                                <div class="stat-item correct-stat">
                                    <div class="stat-value"><?php echo $correct_answers; ?></div>
                                    <div class="stat-label">Correct</div>
                                </div>
                                <div class="stat-item wrong-stat">
                                    <div class="stat-value"><?php echo $wrong_answers; ?></div>
                                    <div class="stat-label">Wrong</div>
                                </div>
                                <div class="stat-item unanswered-stat">
                                    <div class="stat-value"><?php echo $unanswered; ?></div>
                                    <div class="stat-label">Unanswered</div>
                                </div>
                            </div>
                        </div>
                        <?php foreach ($results as $question_id => $result): ?>
                            <div class="result-item">
                                <h5><?php echo htmlspecialchars($result['question_text']); ?></h5>
                                <p>Your Answer: <span class="<?php echo $result['is_correct'] ? 'correct-answer' : ($result['selected_option'] === 'Unanswered' ? 'unanswered' : 'wrong-answer'); ?>">
                                    <?php echo htmlspecialchars($result['selected_option']); ?>
                                </span></p>
                                <p>Correct Answer: <span class="correct-answer"><?php echo htmlspecialchars($result['correct_option']); ?></span></p>
                                <p>Marks Obtained: <?php echo $result['marks_obtained']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const optionButtons = document.querySelectorAll('.option-btn');
    const searchInput = document.getElementById('searchInput');
    const menuItems = document.querySelectorAll('.menu-item');

    // Option selection
    optionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const radio = this.querySelector('.option-radio');
            radio.checked = true;
            const siblings = this.parentElement.querySelectorAll('.option-btn');
            siblings.forEach(sib => sib.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    // Search functionality
    searchInput.addEventListener('keyup', function() {
        const filter = this.value.toUpperCase();
        menuItems.forEach(item => {
            const text = item.textContent || item.innerText;
            item.style.display = text.toUpperCase().includes(filter) ? '' : 'none';
        });
    });

    // Category menu item click
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const categoryId = this.getAttribute('data-category-id');
            const subMenu = document.getElementById('classes-' + categoryId);

            if (subMenu) {
                const isVisible = subMenu.style.display === 'block';
                document.querySelectorAll('.sub-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
                subMenu.style.display = isVisible ? 'none' : 'block';
            }

            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>
</body>
</html>
<?php $conn->close(); ?>