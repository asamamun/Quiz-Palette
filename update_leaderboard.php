<?php
require_once 'db.php';

function updateLeaderboard($user_id, $exam_id, $category_id, $class_id, $subject_id, $score) {
    global $conn;

    // Ensure inputs are valid
    $user_id = intval($user_id);
    $exam_id = $exam_id ? intval($exam_id) : null;
    $category_id = $category_id ? intval($category_id) : null;
    $class_id = $class_id ? intval($class_id) : null;
    $subject_id = $subject_id ? intval($subject_id) : null;
    $score = floatval($score);

    try {
        // Check if entry exists
        $query = "
            SELECT * FROM leaderboards 
            WHERE user_id = ? 
            AND (exam_id <=> ?) 
            AND (category_id <=> ?) 
            AND (class_id <=> ?) 
            AND (subject_id <=> ?)
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iiiii', $user_id, $exam_id, $category_id, $class_id, $subject_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing entry
            $row = $result->fetch_assoc();
            $total_score = $row['total_score'] + $score;
            $total_attempts = $row['total_attempts'] + 1;
            $average_score = $total_score / $total_attempts;

            $update_query = "
                UPDATE leaderboards 
                SET total_score = ?, total_attempts = ?, average_score = ?, last_updated = CURRENT_TIMESTAMP 
                WHERE user_id = ? AND (exam_id <=> ?) AND (category_id <=> ?) AND (class_id <=> ?) AND (subject_id <=> ?)
            ";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param('iidi', $total_score, $total_attempts, $average_score, $user_id, $exam_id, $category_id, $class_id, $subject_id);
            $update_stmt->execute();
        } else {
            // Insert new entry
            $insert_query = "
                INSERT INTO leaderboards (user_id, exam_id, category_id, class_id, subject_id, total_score, total_attempts, average_score) 
                VALUES (?, ?, ?, ?, ?, ?, 1, ?)
            ";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param('iiiiiiid', $user_id, $exam_id, $category_id, $class_id, $subject_id, $score, $score);
            $insert_stmt->execute();
        }

        // Update rank_position
        $rank_query = "
            UPDATE leaderboards l
            JOIN (
                SELECT user_id, exam_id, category_id, class_id, subject_id,
                       RANK() OVER (
                           PARTITION BY 
                               COALESCE(exam_id, 0), 
                               COALESCE(category_id, 0), 
                               COALESCE(class_id, 0), 
                               COALESCE(subject_id, 0) 
                           ORDER BY total_score DESC
                       ) as rank_position
                FROM leaderboards
            ) r ON l.user_id = r.user_id 
                AND (l.exam_id <=> r.exam_id) 
                AND (l.category_id <=> r.category_id) 
                AND (l.class_id <=> r.class_id) 
                AND (l.subject_id <=> r.subject_id)
            SET l.rank_position = r.rank_position
        ";
        $conn->query($rank_query);

        return true;
    } catch (Exception $e) {
        error_log("Error in updateLeaderboard: " . $e->getMessage());
        return false;
    }
}

// Sample data to populate leaderboards
try {
    $attempts = [
        // Category: SSC (id=2), Class: Class 9-10 Science (id=2), Subject: Math (id=5)
        ['user_id' => 1, 'exam_id' => 3, 'category_id' => 2, 'class_id' => 2, 'subject_id' => 5, 'score' => 80],
        ['user_id' => 2, 'exam_id' => 3, 'category_id' => 2, 'class_id' => 2, 'subject_id' => 5, 'score' => 90],
        ['user_id' => 3, 'exam_id' => 3, 'category_id' => 2, 'class_id' => 2, 'subject_id' => 5, 'score' => 70],
        // Category: HSC (id=3), Class: Class 11-12 Science (id=3), Subject: Physics (id=7)
        ['user_id' => 1, 'exam_id' => 4, 'category_id' => 3, 'class_id' => 3, 'subject_id' => 7, 'score' => 85],
        ['user_id' => 2, 'exam_id' => 4, 'category_id' => 3, 'class_id' => 3, 'subject_id' => 7, 'score' => 95],
        // Category: SSC (id=2), Class: Class 9-10 Science (id=2), Subject: Chemistry (id=6)
        ['user_id' => 1, 'exam_id' => 5, 'category_id' => 2, 'class_id' => 2, 'subject_id' => 6, 'score' => 75],
        ['user_id' => 3, 'exam_id' => 5, 'category_id' => 2, 'class_id' => 2, 'subject_id' => 6, 'score' => 88],
    ];

    foreach ($attempts as $attempt) {
        if (updateLeaderboard(
            $attempt['user_id'],
            $attempt['exam_id'],
            $attempt['category_id'],
            $attempt['class_id'],
            $attempt['subject_id'],
            $attempt['score']
        )) {
            echo "Leaderboard updated for user ID: {$attempt['user_id']}\n";
        } else {
            echo "Failed to update leaderboard for user ID: {$attempt['user_id']}\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>