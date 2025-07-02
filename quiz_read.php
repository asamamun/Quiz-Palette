<?php
include "includes/header.php";
require_once "db.php";

// Get subject ID from URL
$subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : 0;

// Fetch subject details with hierarchy
$subject_query = "SELECT s.*, c.name AS class_name, cat.name AS category_name 
                 FROM subjects s
                 JOIN classes c ON s.class_id = c.id
                 JOIN categories cat ON c.category_id = cat.id
                 WHERE s.id = $subject_id AND s.status = 'active'";
$subject_result = mysqli_query($conn, $subject_query);
$subject = mysqli_fetch_assoc($subject_result);

// Fetch quizzes for this subject
$quiz_query = "SELECT * FROM quizzes WHERE subject_id = $subject_id AND status = 'active'";
$quiz_result = mysqli_query($conn, $quiz_query);

// Fetch questions for each quiz
$quizzes = [];
while ($quiz = mysqli_fetch_assoc($quiz_result)) {
    $quiz_id = $quiz['id'];
    $question_query = "SELECT q.* FROM questions q 
                      WHERE q.quiz_id = $quiz_id AND q.status = 'active'";
    $question_result = mysqli_query($conn, $question_query);

    $questions = [];
    while ($question = mysqli_fetch_assoc($question_result)) {
        // Get options for each question
        $option_query = "SELECT * FROM question_options 
                        WHERE question_id = {$question['id']} 
                        ORDER BY order_index";
        $option_result = mysqli_query($conn, $option_query);
        $options = mysqli_fetch_all($option_result, MYSQLI_ASSOC);

        $question['options'] = $options;
        $questions[] = $question;
    }

    $quiz['questions'] = $questions;
    $quizzes[] = $quiz;
}
?>

<style>
    .quiz-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        font-size: 0.95rem;
    }

    .question-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 20px;
        margin-bottom: 20px;
        border-left: 4px solid #90D1CA;
    }

    .question-text {
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 1rem;
    }

    .options-container {
        display: grid;
        gap: 8px;
    }

    .option-item {
        padding: 10px 12px;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        background: #f8f9fa;
        font-size: 0.9rem;
        display: flex;
        align-items: flex-start;
    }

    .option-label {
        display: inline-block;
        width: 30px;
        font-weight: bold;
    }

    .correct-option {
        background: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .subject-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .quiz-breadcrumb {
        background: linear-gradient(135deg, rgba(18, 153, 144, 0.15), rgba(144, 209, 202, 0.15));
        border-radius: 8px;
        padding: 12px 20px;
        margin-bottom: 25px;
        border-left: 4px solid #129990;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    }

    .quiz-breadcrumb ol {
        display: flex;
        align-items: center;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .quiz-breadcrumb-item {
        font-size: 0.9rem;
        color: #4a606a;
        position: relative;
        padding: 6px 10px;
        border-radius: 4px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .quiz-breadcrumb-item:hover:not(.active) {
        color: #129990;
        background: rgba(18, 153, 144, 0.1);
        transform: translateY(-1px);
    }

    .quiz-breadcrumb-item.active {
        color: #129990;
        font-weight: 600;
        cursor: default;
    }

    .quiz-breadcrumb-item[data-tooltip]::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: white;
        font-size: 0.8rem;
        padding: 5px 10px;
        border-radius: 4px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease;
        z-index: 10;
        margin-bottom: 8px;
    }

    .quiz-breadcrumb-item[data-tooltip]:hover::after {
        opacity: 1;
        visibility: visible;
    }

    .quiz-breadcrumb-item:focus:not(.active) {
        outline: 2px solid #90D1CA;
        outline-offset: 2px;
    }

    @media (max-width: 576px) {
        .quiz-breadcrumb-item {
            font-size: 0.85rem;
            padding: 4px 8px;
        }
    }

    .question-number {
        display: inline-block;
        background: #129990;
        color: white;
        width: 25px;
        height: 25px;
        text-align: center;
        line-height: 25px;
        border-radius: 50%;
        margin-right: 10px;
        font-size: 0.8rem;
    }
</style>

<br>

<div class="container pt-5">
    <br>
    <nav aria-label="breadcrumb" class="quiz-breadcrumb">
        <ol class="quiz-breadcrumb">
            <li class="quiz-breadcrumb-item" onclick="history.back()" data-tooltip="Go Back">
                <?php echo htmlspecialchars($subject['category_name'] ?? 'Category'); ?>
            </li>
            <li class="quiz-breadcrumb-item" onclick="history.back()" data-tooltip="Go Back">
                <?php echo htmlspecialchars($subject['class_name'] ?? 'Class'); ?>
            </li>
            <li class="quiz-breadcrumb-item active" aria-current="page">
                <?php echo htmlspecialchars($subject['name'] ?? 'Subject'); ?>
            </li>
        </ol>
    </nav>

    <div class="subject-header">
        <h3><?php echo htmlspecialchars($subject['name'] ?? 'Subject Not Found'); ?></h3>
        <p class="text-muted">Read and learn the correct answers</p>
    </div>

    <?php if (empty($quizzes)): ?>
        <div class="alert alert-info">No quizzes available for this subject yet.</div>
    <?php else: ?>
        <?php
        $question_counter = 1;
        $option_labels = ['a', 'b', 'c', 'd'];
        foreach ($quizzes as $quiz): ?>
            <div class="quiz-container">
                <?php foreach ($quiz['questions'] as $question): ?>
                    <div class="question-card">
                        <div class="question-text">
                            <span class="question-number"><?php echo $question_counter++; ?></span>
                            <?php echo htmlspecialchars($question['question_text']); ?>
                        </div>

                        <div class="options-container">
                            <?php foreach ($question['options'] as $index => $option): ?>
                                <div class="option-item <?php echo $option['is_correct'] ? 'correct-option' : ''; ?>">
                                    <span class="option-label"><?php echo $option_labels[$index] . '.'; ?></span>
                                    <span><?php echo htmlspecialchars($option['option_text']); ?></span>
                                    <?php if ($option['is_correct']): ?>
                                        <span class="float-end"><i class="bi bi-check-circle-fill text-success"></i></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (!empty($question['explanation'])): ?>
                            <div class="explanation mt-3 p-3 bg-light rounded">
                                <strong>Explanation:</strong> <?php echo htmlspecialchars($question['explanation']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php $conn->close();?>
<?php include "includes/footer.php"?>