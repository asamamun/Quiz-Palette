<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quizpallete";

$conn = new mysqli($host, $user, $pass, $dbname);
function sanitize($conn, $str) {
    return $conn->real_escape_string(trim($str));
}

// Add this at the top of your processing file to enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Add debugging to see if form is being submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST request received<br>";
    var_dump($_POST); // Remove this after debugging
}

if (isset($_POST['add_quiz'])) {
    echo "add_quiz form detected<br>"; // Debug line - remove after testing
    
    $question = sanitize($conn, $_POST['question']);
    $option_a = sanitize($conn, $_POST['option_a']);
    $option_b = sanitize($conn, $_POST['option_b']);
    $option_c = sanitize($conn, $_POST['option_c']);
    $option_d = sanitize($conn, $_POST['option_d']);
    $correct_option = sanitize($conn, $_POST['correct_option']);
    $status = sanitize($conn, $_POST['status']);
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $class_id = !empty($_POST['class_id']) ? (int)$_POST['class_id'] : null;
    $subject_id = !empty($_POST['subject_id']) ? (int)$_POST['subject_id'] : null;
    $event_id = !empty($_POST['event_id']) ? (int)$_POST['event_id'] : null;

    // Debug: Check sanitized values
    echo "Sanitized question: " . $question . "<br>";
    echo "Category ID: " . $category_id . "<br>";

    // Validate foreign keys
    if ($category_id !== null) {
        $check = $conn->prepare("SELECT id FROM categories WHERE id = ? AND status = 'active'");
        if (!$check) {
            die("Prepare failed for category check: " . $conn->error);
        }
        $check->bind_param("i", $category_id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) {
            $category_id = null;
            echo "Category not found or inactive<br>";
        }
        $check->close();
    }
    
    if ($class_id !== null) {
        $check = $conn->prepare("SELECT id FROM classes WHERE id = ? AND status = 'active'");
        if (!$check) {
            die("Prepare failed for class check: " . $conn->error);
        }
        $check->bind_param("i", $class_id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) {
            $class_id = null;
        }
        $check->close();
    }
    
    if ($subject_id !== null) {
        $check = $conn->prepare("SELECT id FROM subjects WHERE id = ? AND status = 'active'");
        if (!$check) {
            die("Prepare failed for subject check: " . $conn->error);
        }
        $check->bind_param("i", $subject_id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) {
            $subject_id = null;
        }
        $check->close();
    }
    
    if ($event_id !== null) {
        $check = $conn->prepare("SELECT id FROM events WHERE id = ? AND status = 'active'");
        if (!$check) {
            die("Prepare failed for event check: " . $conn->error);
        }
        $check->bind_param("i", $event_id);
        $check->execute();
        if ($check->get_result()->num_rows === 0) {
            $event_id = null;
        }
        $check->close();
    }
    
    // Validate required fields
    if (empty($question) || empty($option_a) || empty($option_b) || empty($option_c) || empty($option_d)) {
        $error_message = "All question and option fields are required.";
        echo "Validation failed: " . $error_message . "<br>";
    } else if ($category_id === null) {
        $error_message = "Please select a valid category.";
        echo "Validation failed: " . $error_message . "<br>";
    } else {
        echo "Starting transaction...<br>";
        
        // Start transaction
        $conn->autocommit(false);
        
        try {
            // Generate quiz title and slug from question (first 50 chars)
            $quiz_title = substr($question, 0, 50) . (strlen($question) > 50 ? '...' : '');
            $quiz_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $quiz_title)));
            
            // Ensure unique slug
            $slug_counter = 1;
            $original_slug = $quiz_slug;
            while (true) {
                $slug_check = $conn->prepare("SELECT id FROM quizzes WHERE title = ?");
                if (!$slug_check) {
                    throw new Exception("Prepare failed for slug check: " . $conn->error);
                }
                $slug_check->bind_param("s", $quiz_title);
                $slug_check->execute();
                if ($slug_check->get_result()->num_rows === 0) {
                    $slug_check->close();
                    break;
                }
                $slug_check->close();
                $quiz_title = substr($question, 0, 47) . '-' . $slug_counter . '...';
                $slug_counter++;
            }
            
            echo "Quiz title: " . $quiz_title . "<br>";
            
            // **FIXED: Insert into quizzes table with correct column structure**
            $quiz_sql = "INSERT INTO quizzes (
                title, description, status, category_id, class_id, subject_id, event_id, created_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $quiz_stmt = $conn->prepare($quiz_sql);
            if (!$quiz_stmt) {
                throw new Exception("Prepare failed for quiz insert: " . $conn->error);
            }
            
            // Check if user_id exists in session
            $created_by = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
            
            $quiz_stmt->bind_param(
                "sssiiiii", 
                $quiz_title, $quiz_title, $status, $category_id, $class_id, $subject_id, $event_id, $created_by
            );
            
            if (!$quiz_stmt->execute()) {
                throw new Exception("Failed to insert quiz: " . $quiz_stmt->error);
            }
            
            $quiz_id = $conn->insert_id;
            $quiz_stmt->close();
            
            echo "Quiz inserted with ID: " . $quiz_id . "<br>";
            
            // 2. Insert into questions table
            $question_sql = "INSERT INTO questions (
                quiz_id, question_text, question_type, marks, status, order_index
            ) VALUES (?, ?, ?, ?, ?, ?)";
            
            $question_type = 'multiple_choice';
            $marks = 1;
            $order_index = 1;
            
            $question_stmt = $conn->prepare($question_sql);
            if (!$question_stmt) {
                throw new Exception("Prepare failed for question insert: " . $conn->error);
            }
            
            $question_stmt->bind_param(
                "issisi", 
                $quiz_id, $question, $question_type, $marks, $status, $order_index
            );
            
            if (!$question_stmt->execute()) {
                throw new Exception("Failed to insert question: " . $question_stmt->error);
            }
            
            $question_id = $conn->insert_id;
            $question_stmt->close();
            
            echo "Question inserted with ID: " . $question_id . "<br>";
            
            // 3. Insert into question_options table
            $options = [
                ['text' => $option_a, 'is_correct' => ($correct_option === 'a'), 'order' => 1],
                ['text' => $option_b, 'is_correct' => ($correct_option === 'b'), 'order' => 2],
                ['text' => $option_c, 'is_correct' => ($correct_option === 'c'), 'order' => 3],
                ['text' => $option_d, 'is_correct' => ($correct_option === 'd'), 'order' => 4]
            ];
            
            $option_sql = "INSERT INTO question_options (
                question_id, option_text, is_correct, order_index
            ) VALUES (?, ?, ?, ?)";
            
            $option_stmt = $conn->prepare($option_sql);
            if (!$option_stmt) {
                throw new Exception("Prepare failed for option insert: " . $conn->error);
            }
            
            foreach ($options as $option) {
                $is_correct = $option['is_correct'] ? 1 : 0;
                $option_stmt->bind_param(
                    "isii", 
                    $question_id, $option['text'], $is_correct, $option['order']
                );
                
                if (!$option_stmt->execute()) {
                    throw new Exception("Failed to insert option: " . $option_stmt->error);
                }
            }
            $option_stmt->close();
            
            echo "All options inserted successfully<br>";
            
            // If we reach here, all queries succeeded
            $conn->commit();
            $success_message = "Quiz created successfully!";
            echo "Transaction committed: " . $success_message . "<br>";
            
        } catch (Exception $e) {
            // An error occurred, rollback the transaction
            $conn->rollback();
            $error_message = "Error creating quiz: " . $e->getMessage();
            echo "Transaction rolled back: " . $error_message . "<br>";
        } finally {
            // Reset autocommit to true
            $conn->autocommit(true);
        }
    }
} else {
    echo "Form not submitted or add_quiz not set<br>";
}

// Display messages
if (isset($success_message)) {
    echo "<div class='alert alert-success'>$success_message</div>";
}
if (isset($error_message)) {
    echo "<div class='alert alert-danger'>$error_message</div>";
}
?>

<!-- Also add this debugging form to test basic submission -->
<div class="mt-4">
    <h5>Debug Form Test</h5>
    <form method="POST" action="">
        <input type="hidden" name="add_quiz" value="1">
        <input type="text" name="question" value="Test Question?" required>
        <input type="text" name="option_a" value="Option A" required>
        <input type="text" name="option_b" value="Option B" required>
        <input type="text" name="option_c" value="Option C" required>
        <input type="text" name="option_d" value="Option D" required>
        <select name="correct_option" required>
            <option value="a">A</option>
        </select>
        <select name="status">
            <option value="active">Active</option>
        </select>
        <select name="category_id" required>
            <option value="1">Test Category (ID: 1)</option>
        </select>
        <button type="submit">Test Submit</button>
    </form>
</div>