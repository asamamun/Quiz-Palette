<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if no result is available and no form submission
if (!isset($_SESSION['quiz_result']) && !isset($_POST['submit_quiz'])) {
    header("Location: index.php");
    exit();
}

// Include database connection
require_once 'db.php'; // Adjust path if needed

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_quiz'])) {
    $user_id = intval($_SESSION['user_id']);
    $exam_id = intval($_POST['exam_id']);
    $answers = $_POST['answers'] ?? [];

    // Get exam details
    $exam_query = "SELECT * FROM exams WHERE id = ?";
    $stmt = mysqli_prepare($conn, $exam_query);
    mysqli_stmt_bind_param($stmt, 'i', $exam_id);
    mysqli_stmt_execute($stmt);
    $exam_result = mysqli_stmt_get_result($stmt);
    $exam = mysqli_fetch_assoc($exam_result);
    mysqli_stmt_close($stmt);

    if (!$exam) {
        $_SESSION['error'] = 'Exam not found!';
        header("Location: index.php");
        exit();
    }

    // Get user details
    $user_query = "SELECT first_name, last_name FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $user_query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $user_result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($user_result);
    mysqli_stmt_close($stmt);

    if (!$user) {
        $_SESSION['error'] = 'User not found!';
        header("Location: index.php");
        exit();
    }

    // Get all quizzes for this exam
    $quiz_query = "SELECT q.id FROM quizzes q INNER JOIN exam_quizzes eq ON q.id = eq.quiz_id WHERE eq.exam_id = ?";
    $stmt = mysqli_prepare($conn, $quiz_query);
    mysqli_stmt_bind_param($stmt, 'i', $exam_id);
    mysqli_stmt_execute($stmt);
    $quiz_result = mysqli_stmt_get_result($stmt);
    $quiz_ids = [];
    while ($row = mysqli_fetch_assoc($quiz_result)) {
        $quiz_ids[] = $row['id'];
    }
    mysqli_stmt_close($stmt);

    if (empty($quiz_ids)) {
        $_SESSION['error'] = 'No quizzes found for this exam!';
        header("Location: index.php");
        exit();
    }

    // Get all questions for these quizzes
    $quiz_ids_placeholder = implode(',', array_fill(0, count($quiz_ids), '?'));
    $question_query = "SELECT id FROM questions WHERE quiz_id IN ($quiz_ids_placeholder)";
    $stmt = mysqli_prepare($conn, $question_query);
    mysqli_stmt_bind_param($stmt, str_repeat('i', count($quiz_ids)), ...$quiz_ids);
    mysqli_stmt_execute($stmt);
    $question_result = mysqli_stmt_get_result($stmt);
    $questions = [];
    while ($row = mysqli_fetch_assoc($question_result)) {
        $questions[] = $row['id'];
    }
    mysqli_stmt_close($stmt);

    if (empty($questions)) {
        $_SESSION['error'] = 'No questions found for this exam!';
        header("Location: index.php");
        exit();
    }

    // Calculate score
    $correct_answers = 0;
    $total_questions = count($questions);

    foreach ($questions as $question_id) {
        if (isset($answers[$question_id])) {
            $option_id = intval($answers[$question_id]);
            $option_query = "SELECT is_correct FROM question_options WHERE id = ? AND question_id = ?";
            $stmt = mysqli_prepare($conn, $option_query);
            mysqli_stmt_bind_param($stmt, 'ii', $option_id, $question_id);
            mysqli_stmt_execute($stmt);
            $option_result = mysqli_stmt_get_result($stmt);
            $option = mysqli_fetch_assoc($option_result);
            mysqli_stmt_close($stmt);

            if ($option && $option['is_correct']) {
                $correct_answers++;
            }
        }
    }

    $incorrect_answers = $total_questions - $correct_answers;
    $score = ($total_questions > 0) ? ($correct_answers / $total_questions) * 100 : 0;

    // Insert into leaderboard
$leaderboard_query = "INSERT INTO leaderboards (user_id, exam_id, category_id, class_id, subject_id, total_score, total_attempts, average_score, rank_position) VALUES (?, ?, ?, ?, ?, ?, 1, ?, 0)";
$stmt = mysqli_prepare($conn, $leaderboard_query);
mysqli_stmt_bind_param($stmt, 'iiiiidi', $user_id, $exam['id'], $exam['category_id'], $exam['class_id'], $exam['subject_id'], $score, $score);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// Store results in session
$_SESSION['quiz_result'] = [
    'user_name' => $user['first_name'] . ' ' . $user['last_name'],
    'exam_id' => $exam_id['exam_id'],
    'exam_title' => $exam['title'],
    'score' => $score,
    'correct_answers' => $correct_answers,
    'incorrect_answers' => $incorrect_answers,
    'total_questions' => $total_questions
];

// Redirect to self to display results
header("Location: quiz_result.php");
exit();
}

// Include header after redirect checks
include "includes/header.php";

// Fetch and clear results
$result = $_SESSION['quiz_result'];
unset($_SESSION['quiz_result']);
?>

<div class="container" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white" style="background-color:#096B68">
                    <h4 class="mb-0">Quiz Results</h4>
                </div>
                <div class="card-body">
                    <!-- Display error if set -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($_SESSION['error']); ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mb-4">
                        <h3><?php echo htmlspecialchars($result['exam_title']); ?></h3>
                        <p class="text-muted">Completed by: <?php echo htmlspecialchars($result['user_name']); ?></p>
                    </div>

                    <div class="progress mb-4" style="height: 30px;">
                        <div class="progress-bar <?php echo ($result['score'] >= 50) ? 'bg-success' : 'bg-danger'; ?>" 
                             role="progressbar" 
                             style="width: <?php echo $result['score']; ?>%" 
                             aria-valuenow="<?php echo $result['score']; ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            <?php echo round($result['score'], 2); ?>%
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Correct Answers</h5>
                                    <p class="card-text display-4 text-success"><?php echo $result['correct_answers']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Incorrect Answers</h5>
                                    <p class="card-text display-4 text-danger"><?php echo $result['incorrect_answers']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Questions</h5>
                                    <p class="card-text display-4"><?php echo $result['total_questions']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <?php if ($result['score'] >= 50): ?>
                            <div class="alert alert-success">
                                <h4 class="alert-heading">Congratulations!</h4>
                                <p>You have passed this quiz with a score of <?php echo round($result['score'], 2); ?>%.</p>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger">
                                <h4 class="alert-heading">Try Again!</h4>
                                <p>You scored <?php echo round($result['score'], 2); ?>%. Keep practicing to improve your score.</p>
                            </div>
                        <?php endif; ?>

                        <a href="index.php" class="btn btn-primary text-white" style="background-color:#096B68">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Close database connection
mysqli_close($conn);

?>
<?php include "includes/footer.php" ?>