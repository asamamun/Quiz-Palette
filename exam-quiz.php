<?php

session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "includes/header.php";


// Get exam ID from URL
$exam_id = isset($_GET['examid']) ? intval($_GET['examid']) : 0;

// Check if exam exists
$exam_query = "SELECT * FROM exams WHERE id = ?";
$stmt = mysqli_prepare($conn, $exam_query);
mysqli_stmt_bind_param($stmt, 'i', $exam_id);
mysqli_stmt_execute($stmt);
$exam_result = mysqli_stmt_get_result($stmt);
$exam = mysqli_fetch_assoc($exam_result);
mysqli_stmt_close($stmt);

if (!$exam) {
    echo "<div class='alert alert-danger'>Exam not found!</div>";
    
    exit();
}

// Check if user is admin or regular user
$is_admin = ($_SESSION['role'] ?? '') === 'admin';

// Get all quizzes for this exam
$quiz_query = "SELECT q.id, q.title, q.description FROM quizzes q INNER JOIN exam_quizzes eq ON q.id = eq.quiz_id WHERE eq.exam_id = ?";
$stmt = mysqli_prepare($conn, $quiz_query);
mysqli_stmt_bind_param($stmt, 'i', $exam_id);
mysqli_stmt_execute($stmt);
$quiz_result = mysqli_stmt_get_result($stmt);
$quizzes = [];
while ($row = mysqli_fetch_assoc($quiz_result)) {
    $quizzes[] = $row;
}
mysqli_stmt_close($stmt);

if (empty($quizzes)) {
    echo "<div class='alert alert-warning'>No quizzes found for this exam!</div>";
    
    exit();
}

// Get quiz IDs
$quiz_ids = array_column($quizzes, 'id');

// Get all questions for these quizzes
$quiz_ids_placeholder = implode(',', array_fill(0, count($quiz_ids), '?'));
$question_query = "SELECT id, quiz_id, question_text FROM questions WHERE quiz_id IN ($quiz_ids_placeholder)";
$stmt = mysqli_prepare($conn, $question_query);
mysqli_stmt_bind_param($stmt, str_repeat('i', count($quiz_ids)), ...$quiz_ids);
mysqli_stmt_execute($stmt);
$question_result = mysqli_stmt_get_result($stmt);
$questions = [];
while ($row = mysqli_fetch_assoc($question_result)) {
    $questions[] = $row;
}
mysqli_stmt_close($stmt);

if (empty($questions)) {
    echo "<div class='alert alert-warning'>No questions found for this exam!</div>";
    include "includes/footer.php";
    exit();
}

// Get options for all questions
$question_ids = array_column($questions, 'id');
$question_ids_placeholder = implode(',', array_fill(0, count($question_ids), '?'));
$option_query = "SELECT id, question_id, option_text, is_correct FROM question_options WHERE question_id IN ($question_ids_placeholder)";
$stmt = mysqli_prepare($conn, $option_query);
mysqli_stmt_bind_param($stmt, str_repeat('i', count($question_ids)), ...$question_ids);
mysqli_stmt_execute($stmt);
$option_result = mysqli_stmt_get_result($stmt);
$options_by_question = [];
while ($row = mysqli_fetch_assoc($option_result)) {
    $options_by_question[$row['question_id']][] = $row;
}
mysqli_stmt_close($stmt);
?>

<style>
    /* Standardized colors using CSS variables */
    :root {
        --primary-color: #096B68;
        --primary-hover: #107a73;
        --primary-light: #e0f2f1;
        --border-color:rgba(18, 153, 144, 0.41);
        --light-bg: #f8f9fa;
        --text-color: #333;
        --muted-text: #6c757d;
    }

    /* Option item styling */
    .option-item {
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        background: var(--light-bg);
        color: var(--text-color);
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 8px; /* Add some spacing between options */
    }

    .option-item:hover {
        background: #e9f9f8;
    }

    /* Hide default radio */
    .option-item input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        width: 18px;
        height: 18px;
        border: 2px solid var(--border-color);
        border-radius: 50%;
        background: white;
        position: relative;
        cursor: pointer;
        flex-shrink: 0;
    }

    /* Radio checked dot */
    .option-item input[type="radio"]:checked::before {
        content: "";
        width: 10px;
        height: 10px;
        background: var(--primary-color);
        border-radius: 50%;
        position: absolute;
        top: 2px;
        left: 2px;
    }

    /* This is the magic - styles the entire label when radio is checked */
    .option-item input[type="radio"]:checked {
        border-color: var(--primary-color);
    }

    .option-item input[type="radio"]:checked ~ span {
        color: var(--text-color);
    }

    /* Highlight entire option box when selected */
    .option-item:has(input[type="radio"]:checked) {
        background-color: var(--primary-light);
        border-color: var(--primary-color);
        
    }

    /* Submit button */
    .btn-primary {
        background-color: var(--primary-color);
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
        transition: background-color 0.2s;
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container {
            margin-top: 60px;
            padding: 0 15px;
        }
        
        .card-body {
            padding: 1rem;
        }
    }
</style>


<div class="container" style="margin-top: 80px;">
    <h2><?php echo htmlspecialchars($exam['title']); ?></h2>
    <p><?php echo htmlspecialchars($exam['description']); ?></p>
    <p class="text-muted">Duration: <?php echo $exam['duration']; ?> minutes</p>
    <hr>

    <form id="quizForm" method="post" action="quiz_result.php">
        <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
        <input type="hidden" name="submit_quiz" value="1">

        <?php foreach ($quizzes as $quiz): ?>
            <?php 
            // Get questions for this quiz
            $quiz_questions = array_filter($questions, function($q) use ($quiz) {
                return $q['quiz_id'] == $quiz['id'];
            });
            
            if (!empty($quiz_questions)): ?>
                <div class="quiz-section mb-4">
                    
                    
                    
                    <?php foreach ($quiz_questions as $index => $question): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Question <?php echo $index + 1; ?> of <?php echo count($questions); ?></h5>
                                <hr>
                                <p class="card-text"><?php echo htmlspecialchars($question['question_text']); ?></p>
                                
                                <?php if (!empty($options_by_question[$question['id']])): ?>
                                    <div class="options">
                                       <?php foreach ($options_by_question[$question['id']] as $option): ?>
                                            <label class="option-item">
                                                <input type="radio"
                                                                 name="answers[<?php echo $question['id']; ?>]"
                                                                 value="<?php echo $option['id']; ?>">
                                                    <span><?php echo htmlspecialchars($option['option_text']); ?></span>
                                             </label>
                                        <?php endforeach; ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-md text-white" style="background-color:#096B68">Submit Answers</button>
        </div>
    </form>
</div>



<?php
// Close database connection
mysqli_close($conn);

?>
<?php include "includes/footer.php" ?>