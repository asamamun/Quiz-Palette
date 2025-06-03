<?php
require __DIR__."/../vendor/autoload.php";
require __DIR__."/admincheck.php";
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quizpallete";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Connection failed: " . htmlspecialchars($conn->connect_error));

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Add debugging to see if form is being submitted
/* if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST request received<br>";
    var_dump($_POST); // Remove this after debugging
} */

// Helper function to sanitize input
function sanitize($conn, $str) {
    return $conn->real_escape_string(trim($str));
}

// Handle POST requests: add/edit/delete for all entities
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // === CATEGORY ===
    if (isset($_POST['add_category'])) {
        $name = sanitize($conn, $_POST['name']);
        $slug = sanitize($conn, $_POST['slug']);
        $description = sanitize($conn, $_POST['description']);
        $icon = sanitize($conn, $_POST['icon']);
        $status = sanitize($conn, $_POST['status']);
        $stmt = $conn->prepare("INSERT INTO categories (name, slug, description, icon, status, created_by, created_at) VALUES (?, ?, ?, ?, ?, 1, NOW())");
        $stmt->bind_param("sssss", $name, $slug, $description, $icon, $status);
        $stmt->execute();
    }
    if (isset($_POST['edit_category'])) {
        $id = (int)$_POST['id'];
        $name = sanitize($conn, $_POST['name']);
        $slug = sanitize($conn, $_POST['slug']);
        $description = sanitize($conn, $_POST['description']);
        $icon = sanitize($conn, $_POST['icon']);
        $status = sanitize($conn, $_POST['status']);
        $stmt = $conn->prepare("UPDATE categories SET name=?, slug=?, description=?, icon=?, status=? WHERE id=?");
        $stmt->bind_param("sssssi", $name, $slug, $description, $icon, $status, $id);
        $stmt->execute();
    }
    if (isset($_POST['delete_category'])) {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("DELETE FROM categories WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // === CLASS ===
    if (isset($_POST['add_class'])) {
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $name = sanitize($conn, $_POST['name']);
        $slug = sanitize($conn, $_POST['slug']);
        $description = sanitize($conn, $_POST['description']);
        $status = sanitize($conn, $_POST['status']);
        if ($category_id !== null) {
            $check = $conn->prepare("SELECT id FROM categories WHERE id = ? AND status = 'active'");
            $check->bind_param("i", $category_id);
            $check->execute();
            if ($check->get_result()->num_rows === 0) {
                $category_id = null;
            }
        }
        $stmt = $conn->prepare("INSERT INTO classes (category_id, name, slug, description, status, created_by, created_at) VALUES (?, ?, ?, ?, ?, 1, NOW())");
        $stmt->bind_param("issss", $category_id, $name, $slug, $description, $status);
        $stmt->execute();
    }
    if (isset($_POST['edit_class'])) {
        $id = (int)$_POST['id'];
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $name = sanitize($conn, $_POST['name']);
        $slug = sanitize($conn, $_POST['slug']);
        $description = sanitize($conn, $_POST['description']);
        $status = sanitize($conn, $_POST['status']);
        if ($category_id !== null) {
            $check = $conn->prepare("SELECT id FROM categories WHERE id = ? AND status = 'active'");
            $check->bind_param("i", $category_id);
            $check->execute();
            if ($check->get_result()->num_rows === 0) {
                $category_id = null;
            }
        }
        $stmt = $conn->prepare("UPDATE classes SET category_id=?, name=?, slug=?, description=?, status=? WHERE id=?");
        $stmt->bind_param("issssi", $category_id, $name, $slug, $description, $status, $id);
        $stmt->execute();
    }
    if (isset($_POST['delete_class'])) {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("DELETE FROM classes WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // === SUBJECT ===
    if (isset($_POST['add_subject'])) {
        $name = sanitize($conn, $_POST['name']);
        $slug = sanitize($conn, $_POST['slug']);
        $description = sanitize($conn, $_POST['description']);
        $status = sanitize($conn, $_POST['status']);
        $class_id = !empty($_POST['class_id']) ? (int)$_POST['class_id'] : null;
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        if ($class_id !== null) {
            $check = $conn->prepare("SELECT id FROM classes WHERE id = ? AND status = 'active'");
            $check->bind_param("i", $class_id);
            $check->execute();
            if ($check->get_result()->num_rows === 0) {
                $class_id = null;
            }
        }
        if ($category_id !== null) {
            $check = $conn->prepare("SELECT id FROM categories WHERE id = ? AND status = 'active'");
            $check->bind_param("i", $category_id);
            $check->execute();
            if ($check->get_result()->num_rows === 0) {
                $category_id = null;
            }
        }
        $stmt = $conn->prepare("INSERT INTO subjects (class_id, name, slug, description, status, created_by, created_at, category_id) VALUES (?, ?, ?, ?, ?, 1, NOW(), ?)");
        $stmt->bind_param("issssi", $class_id, $name, $slug, $description, $status, $category_id);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Subject added successfully.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Failed to add subject.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        }
    }
    if (isset($_POST['edit_subject'])) {
        $id = (int)$_POST['id'];
        $name = sanitize($conn, $_POST['name']);
        $slug = sanitize($conn, $_POST['slug']);
        $description = sanitize($conn, $_POST['description']);
        $status = sanitize($conn, $_POST['status']);
        $class_id = !empty($_POST['class_id']) ? (int)$_POST['class_id'] : null;
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        if ($class_id !== null) {
            $check = $conn->prepare("SELECT id FROM classes WHERE id = ? AND status = 'active'");
            $check->bind_param("i", $class_id);
            $check->execute();
            if ($check->get_result()->num_rows === 0) {
                $class_id = null;
            }
        }
        if ($category_id !== null) {
            $check = $conn->prepare("SELECT id FROM categories WHERE id = ? AND status = 'active'");
            $check->bind_param("i", $category_id);
            $check->execute();
            if ($check->get_result()->num_rows === 0) {
                $category_id = null;
            }
        }
        $stmt = $conn->prepare("UPDATE subjects SET class_id=?, name=?, slug=?, description=?, status=?, category_id=? WHERE id=?");
        $stmt->bind_param("issssii", $class_id, $name, $slug, $description, $status, $category_id, $id);
        $stmt->execute();
    }
    if (isset($_POST['delete_subject'])) {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("DELETE FROM subjects WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // === EVENT ===
    if (isset($_POST['add_event'])) {
        $name = sanitize($conn, $_POST['name']);
        $slug = sanitize($conn, $_POST['slug']);
        $description = sanitize($conn, $_POST['description']);
        $status = sanitize($conn, $_POST['status']);
        $stmt = $conn->prepare("INSERT INTO events (name, slug, description, status, created_by, created_at) VALUES (?, ?, ?, ?, 1, NOW())");
        $stmt->bind_param("ssss", $name, $slug, $description, $status);
        $stmt->execute();
    }
    if (isset($_POST['edit_event'])) {
        $id = (int)$_POST['id'];
        $name = sanitize($conn, $_POST['name']);
        $slug = sanitize($conn, $_POST['slug']);
        $description = sanitize($conn, $_POST['description']);
        $status = sanitize($conn, $_POST['status']);
        $stmt = $conn->prepare("UPDATE events SET name=?, slug=?, description=?, status=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $slug, $description, $status, $id);
        $stmt->execute();
    }
    if (isset($_POST['delete_event'])) {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("DELETE FROM events WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // === QUIZ ===
    // === QUIZ ===
if (isset($_POST['add_quiz'])) {
    //echo "add_quiz form detected<br>"; // Debug line - remove after testing
    
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
    //echo "Sanitized question: " . $question . "<br>";
    //echo "Category ID: " . $category_id . "<br>";

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
            //echo "Category not found or inactive<br>";
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
        //echo "Starting transaction...<br>";
        
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
            
            //echo "Quiz title: " . $quiz_title . "<br>";
            
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
            
           // echo "Quiz inserted with ID: " . $quiz_id . "<br>";
            
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
            
            //echo "All options inserted successfully<br>";
            
            // If we reach here, all queries succeeded
            $conn->commit();
            $success_message = "Quiz created successfully!";
           // echo "Transaction committed: " . $success_message . "<br>";
            
        } catch (Exception $e) {
            // An error occurred, rollback the transaction
            $conn->rollback();
            $error_message = "Error creating quiz: " . $e->getMessage();
            //echo "Transaction rolled back: " . $error_message . "<br>";
        } finally {
            // Reset autocommit to true
            $conn->autocommit(true);
        }
    }
} else {
    //echo "Form not submitted or add_quiz not set<br>";
}

    if (isset($_POST['edit_quiz'])) {
        $id = (int)$_POST['id'];
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

        // Validate foreign keys
        if ($category_id !== null) {
            $check = $conn->prepare("SELECT id FROM categories WHERE id = ? AND status = 'active'");
            $check->bind_param("i", $category_id);
            $check->execute();
            if ($check->get_result()->num_rows === 0) {
                $category_id = null;
            }
        }
        if ($class_id !== null) {
            $check = $conn->prepare("SELECT id FROM classes WHERE id = ? AND status = 'active'");
            $check->bind_param("i", $class_id);
            $check->execute();
            if ($check->get_result()->num_rows === 0) {
                $class_id = null;
            }
        }
        if ($subject_id !== null) {
            $check = $conn->prepare("SELECT id FROM subjects WHERE id = ? AND status = 'active'");
            $check->bind_param("i", $subject_id);
            $check->execute();
            if ($check->get_result()->num_rows === 0) {
                $subject_id = null;
            }
        }
        if ($event_id !== null) {
            $check = $conn->prepare("SELECT id FROM events WHERE id = ? AND status = 'active'");
            $check->bind_param("i", $event_id);
            $check->execute();
            if ($check->get_result()->num_rows === 0) {
                $event_id = null;
            }
        }

        $stmt = $conn->prepare("UPDATE quizzes SET question=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_option=?, status=?, category_id=?, class_id=?, subject_id=?, event_id=? WHERE id=?");
        $stmt->bind_param("sssssssiiiii", $question, $option_a, $option_b, $option_c, $option_d, $correct_option, $status, $category_id, $class_id, $subject_id, $event_id, $id);
        $stmt->execute();
    }
    if (isset($_POST['delete_quiz'])) {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("DELETE FROM quizzes WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // === PENDING QUESTIONS ===
    if (isset($_POST['approve_question'])) {
        $id = (int)$_POST['id'];
        // Fetch the pending question
        $stmt = $conn->prepare("SELECT * FROM pending_questions WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $question = $row['question'];
            $option_a = $row['option_a'];
            $option_b = $row['option_b'];
            $option_c = $row['option_c'];
            $option_d = $row['option_d'];
            $correct_option = $row['correct_option'];
            $status = 'active';
            $category_id = !empty($row['category_id']) ? (int)$row['category_id'] : null;
            $class_id = !empty($row['class_id']) ? (int)$row['class_id'] : null;
            $subject_id = !empty($row['subject_id']) ? (int)$row['subject_id'] : null;
            $event_id = !empty($row['event_id']) ? (int)$row['event_id'] : null;

            // Validate foreign keys
            if ($category_id !== null) {
                $check = $conn->prepare("SELECT id FROM categories WHERE id = ? AND status = 'active'");
                $check->bind_param("i", $category_id);
                $check->execute();
                if ($check->get_result()->num_rows === 0) {
                    $category_id = null;
                }
            }
            if ($class_id !== null) {
                $check = $conn->prepare("SELECT id FROM classes WHERE id = ? AND status = 'active'");
                $check->bind_param("i", $class_id);
                $check->execute();
                if ($check->get_result()->num_rows === 0) {
                    $class_id = null;
                }
            }
            if ($subject_id !== null) {
                $check = $conn->prepare("SELECT id FROM subjects WHERE id = ? AND status = 'active'");
                $check->bind_param("i", $subject_id);
                $check->execute();
                if ($check->get_result()->num_rows === 0) {
                    $subject_id = null;
                }
            }
            if ($event_id !== null) {
                $check = $conn->prepare("SELECT id FROM events WHERE id = ? AND status = 'active'");
                $check->bind_param("i", $event_id);
                $check->execute();
                if ($check->get_result()->num_rows === 0) {
                    $event_id = null;
                }
            }

            // Insert into quizzes
            $stmt = $conn->prepare("INSERT INTO quizzes (question, option_a, option_b, option_c, option_d, correct_option, status, category_id, class_id, subject_id, event_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssssssiiii", $question, $option_a, $option_b, $option_c, $option_d, $correct_option, $status, $category_id, $class_id, $subject_id, $event_id);
            if ($stmt->execute()) {
                // Update pending question status
                $stmt = $conn->prepare("UPDATE pending_questions SET status = 'approved' WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Question approved and added to quizzes.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
            } else {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Failed to approve question.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
            }
        }
    }
    if (isset($_POST['reject_question'])) {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("UPDATE pending_questions SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Question rejected.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
    if (isset($_POST['delete_pending_question'])) {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("DELETE FROM pending_questions WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Pending question deleted.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch data for display
$categories = $conn->query("SELECT * FROM categories ORDER BY created_at DESC");
$classes = $conn->query("SELECT c.*, cat.name AS category_name FROM classes c LEFT JOIN categories cat ON c.category_id = cat.id ORDER BY c.id DESC");
$subjects = $conn->query("SELECT s.*, c.name AS class_name, cat.name AS category_name FROM subjects s LEFT JOIN classes c ON s.class_id = c.id LEFT JOIN categories cat ON s.category_id = cat.id ORDER BY s.id DESC");
$events = $conn->query("SELECT * FROM events ORDER BY id DESC");
$quizzes = $conn->query("SELECT q.*, cat.name AS category_name, cl.name AS class_name, s.name AS subject_name, e.name AS event_name 
                         FROM quizzes q 
                         LEFT JOIN categories cat ON q.category_id = cat.id 
                         LEFT JOIN classes cl ON q.class_id = cl.id 
                         LEFT JOIN subjects s ON q.subject_id = s.id 
                         LEFT JOIN events e ON q.event_id = e.id 
                         ORDER BY q.id DESC");
$pending_questions = $conn->query("SELECT pq.*, cat.name AS category_name, cl.name AS class_name, s.name AS subject_name, e.name AS event_name, COALESCE(u.first_name, u.username) AS display_name 
                                   FROM pending_questions pq 
                                   LEFT JOIN categories cat ON pq.category_id = cat.id 
                                   LEFT JOIN classes cl ON pq.class_id = cl.id 
                                   LEFT JOIN subjects s ON pq.subject_id = s.id 
                                   LEFT JOIN events e ON pq.event_id = e.id 
                                   LEFT JOIN users u ON pq.created_by = u.id 
                                   ORDER BY pq.created_at DESC");
$allCategories = $conn->query("SELECT id, name FROM categories WHERE status='active'");
$allClasses = $conn->query("SELECT id, name FROM classes WHERE status='active'");
$allSubjects = $conn->query("SELECT id, name FROM subjects WHERE status='active'");
$allEvents = $conn->query("SELECT id, name FROM events WHERE status='active'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Quiz System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-color: #129990;
        }
        h2, h4 {
            color: var(--primary-color);
        }
        .btn-primary, .btn-success {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover, .btn-primary:focus,
        .btn-success:hover, .btn-success:focus {
            background-color: #0e7a75;
            border-color: #0e7a75;
        }
        .btn-danger {
            background-color: #d9534f;
            border-color: #d9534f;
        }
        table.table-bordered {
            border-color: var(--primary-color);
        }
        table.table-bordered th,
        table.table-bordered td {
            border-color: var(--primary-color);
        }
        .form-inline input, .form-inline select, .form-inline textarea {
            display: inline-block;
            width: auto;
            vertical-align: middle;
        }
        .action-btn {
            margin-left: 5px;
        }
        @media (max-width: 576px) {
            form.row.g-3 > div {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }
            .action-btn {
                margin-top: 0.25rem;
                margin-left: 0;
            }
            table input.form-control,
            table select.form-select,
            table textarea.form-control {
                min-width: 60px;
                font-size: 0.85rem;
                padding: 0.25rem 0.5rem;
            }
            table td {
                white-space: nowrap;
            }
        }
        .table-responsive {
            overflow-x: auto;
        }
        .card-header.theme-bg {
            background-color: var(--primary-color);
            color: white;
        }
        .btn-theme {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-theme:hover {
            background-color: #0e7a75;
            border-color: #0e7a75;
        }
        .nav-link.text-white:hover {
            background-color: #0e7a75;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row min-vh-100">
        <!-- Sidebar for large screens -->
        <div class="col-md-2 d-none d-md-block p-0" style="background-color: var(--primary-color);">
            <nav class="navbar border-bottom border-white">
                <div class="container-fluid">
                    <span class="navbar-brand text-white">Admin</span>
                </div>
            </nav>
            <nav class="nav flex-column">
                <a class="nav-link text-white" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#quizSubMenu" role="button" aria-expanded="true">
                    <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
                </a>
                <div class="collapse show ms-3" id="quizSubMenu">
                    <a class="nav-link text-white" href="create_quiz.php"><i class="bi bi-plus-circle me-2"></i>Manage Courses</a>
                    <a class="nav-link text-white active" href="set_exams.php"><i class="bi bi-book me-2"></i>Manage Exams</a>
                    <a class="nav-link text-white" href="quiz_list.php"><i class="bi bi-list-ul me-2"></i>Quiz List</a>
                </div>
                <a class="nav-link text-white" href="manage_users.php"><i class="bi bi-people-fill me-2"></i>Manage Users</a>
                <a class="nav-link text-white" href="track_results.php"><i class="bi bi-graph-up-arrow me-2"></i>Track Results</a>
                <a class="nav-link text-white" href="leaderboards.php"><i class="bi bi-trophy-fill me-2"></i>Leaderboards</a>
                <a class="nav-link text-white" href="requests.php"><i class="bi bi-inbox-fill me-2"></i>Requests</a>
                <a class="nav-link text-white" href="certificate.php"><i class="bi bi-award-fill me-2"></i>Certificates</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 col-12 p-0">
            <!-- Top navbar with hamburger -->
            <nav class="navbar navbar-expand-lg" style="background-color: var(--primary-color);">
                <div class="container-fluid">
                    <button class="btn text-white d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="navbar-brand text-white d-md-none">Admin</span>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Offcanvas sidebar for mobile -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMobile" style="background-color: var(--primary-color);">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title text-white">Menu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <nav class="nav flex-column">
                        <a class="nav-link text-white" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                        <a class="nav-link text-white" data-bs-toggle="collapse" href="#quizSubMenuMobile" role="button" aria-expanded="true">
                            <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
                        </a>
                        <div class="collapse show ms-3" id="quizSubMenuMobile">
                            <a class="nav-link text-white" href="create_quiz.php"><i class="bi bi-plus-circle me-2"></i>Manage Courses</a>
                            <a class="nav-link text-white active" href="set_exams.php"><i class="bi bi-book me-2"></i>Manage Exams</a>
                            <a class="nav-link text-white" href="quiz_list.php"><i class="bi bi-list-ul me-2"></i>Quiz List</a>
                        </div>
                        <a class="nav-link text-white" href="manage_users.php"><i class="bi bi-people-fill me-2"></i>Manage Users</a>
                        <a class="nav-link text-white" href="track_results.php"><i class="bi bi-graph-up-arrow me-2"></i>Track Results</a>
                        <a class="nav-link text-white" href="leaderboards.php"><i class="bi bi-trophy-fill me-2"></i>Leaderboards</a>
                        <a class="nav-link text-white" href="requests.php"><i class="bi bi-inbox-fill me-2"></i>Requests</a>
                        <a class="nav-link text-white" href="certificate.php"><i class="bi bi-award-fill me-2"></i>Certificates</a>
                    </nav>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="py-4 px-3 px-md-4">
                <h2 class="mb-4">Manage Quiz System (Categories, Classes, Subjects, Events, Quizzes, Pending Questions)</h2>

                <ul class="nav nav-tabs mb-3" id="manageTabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#categories" role="tab" aria-selected="true">Categories</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#classes" role="tab" aria-selected="false">Classes</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#subjects" role="tab" aria-selected="false">Subjects</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#events" role="tab" aria-selected="false">Events</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#quizzes" role="tab" aria-selected="false">Quizzes</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pending_questions" role="tab" aria-selected="false">Pending Questions</a></li>
                </ul>

                <div class="tab-content">
                    <!-- CATEGORIES -->
                    <div class="tab-pane fade show active" id="categories" role="tabpanel">
                        <h4>Add Category</h4>
                        <form method="POST" class="row g-3 mb-4">
                            <input type="hidden" name="add_category">
                            <div class="col-12 col-md-3">
                                <input type="text" name="name" class="form-control" placeholder="Name" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <input type="text" name="slug" class="form-control" placeholder="Slug" required>
                            </div>
                            <div class="col-12 col-md-2">
                                <input type="text" name="icon" class="form-control" placeholder="Icon">
                            </div>
                            <div class="col-12 col-md-4">
                                <input type="text" name="description" class="form-control" placeholder="Description">
                            </div>
                            <div class="col-6 col-md-2">
                                <select name="status" class="form-select">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-1">
                                <button type="submit" class="btn btn-primary w-100">Add</button>
                            </div>
                        </form>

                        <h4>Existing Categories</h4>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Icon</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th style="min-width:120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $categories->fetch_assoc()): ?>
                                        <tr>
                                            <form method="POST" class="form-inline d-flex flex-wrap gap-2 align-items-center">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control form-control-sm"></td>
                                                <td><input type="text" name="slug" value="<?= htmlspecialchars($row['slug']) ?>" class="form-control form-control-sm"></td>
                                                <td><input type="text" name="icon" value="<?= htmlspecialchars($row['icon']) ?>" class="form-control form-control-sm"></td>
                                                <td><input type="text" name="description" value="<?= htmlspecialchars($row['description']) ?>" class="form-control form-control-sm"></td>
                                                <td>
                                                    <select name="status" class="form-select form-select-sm">
                                                        <option value="active" <?= $row['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                                        <option value="inactive" <?= $row['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                                    </select>
                                                </td>
                                                <td class="d-flex gap-2">
                                                    <button type="submit" name="edit_category" class="btn btn-sm btn-success action-btn">Save</button>
                                            </form>
                                                    <form method="POST" onsubmit="return confirm('Delete this category?')" class="m-0">
                                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                        <button type="submit" name="delete_category" class="btn btn-sm btn-danger action-btn">Delete</button>
                                                    </form>
                                                </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- CLASSES -->
                    <div class="tab-pane fade" id="classes" role="tabpanel">
                        <h4>Add Class</h4>
                        <?php if ($allCategories->num_rows === 0): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                No active categories available. Please add a category first.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php else: ?>
                            <form method="POST" class="row g-3 mb-4">
                                <input type="hidden" name="add_class">
                                <div class="col-12 col-md-3">
                                    <select name="category_id" class="form-select" required>
                                        <option value="">Select Category</option>
                                        <?php while ($cat = $allCategories->fetch_assoc()): ?>
                                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                        <?php endwhile; ?>
                                        <?php $allCategories->data_seek(0); ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <input type="text" name="name" class="form-control" placeholder="Class Name" required>
                                </div>
                                <div class="col-12 col-md-2">
                                    <input type="text" name="slug" class="form-control" placeholder="Slug" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <input type="text" name="description" class="form-control" placeholder="Description">
                                </div>
                                <div class="col-6 col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-6 col-md-1">
                                    <button type="submit" class="btn btn-primary w-100">Add</button>
                                </div>
                            </form>
                        <?php endif; ?>

                        <h4>Existing Classes</h4>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Category</th>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th style="min-width:120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $classes->fetch_assoc()): ?>
                                        <tr>
                                            <form method="POST" class="form-inline d-flex flex-wrap gap-2 align-items-center">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <td>
                                                    <select name="category_id" class="form-select form-select-sm">
                                                        <option value="">None</option>
                                                        <?php while ($cat = $allCategories->fetch_assoc()): ?>
                                                            <option value="<?= $cat['id'] ?>" <?= $row['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                                        <?php endwhile; ?>
                                                        <?php $allCategories->data_seek(0); ?>
                                                    </select>
                                                </td>
                                                <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control form-control-sm"></td>
                                                <td><input type="text" name="slug" value="<?= htmlspecialchars($row['slug']) ?>" class="form-control form-control-sm"></td>
                                                <td><input type="text" name="description" value="<?= htmlspecialchars($row['description']) ?>" class="form-control form-control-sm"></td>
                                                <td>
                                                    <select name="status" class="form-select form-select-sm">
                                                        <option value="active" <?= $row['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                                        <option value="inactive" <?= $row['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                                    </select>
                                                </td>
                                                <td class="d-flex gap-2">
                                                    <button type="submit" name="edit_class" class="btn btn-sm btn-success action-btn">Save</button>
                                            </form>
                                                    <form method="POST" onsubmit="return confirm('Delete this class?')" class="m-0">
                                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                        <button type="submit" name="delete_class" class="btn btn-sm btn-danger action-btn">Delete</button>
                                                    </form>
                                                </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- SUBJECTS -->
                    <div class="tab-pane fade" id="subjects" role="tabpanel">
                        <h4>Add Subject</h4>
                        <?php if ($allCategories->num_rows === 0 || $allClasses->num_rows === 0): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                No active categories or classes available. Please add them first.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php else: ?>
                            <form method="POST" class="row g-3 mb-4">
                                <input type="hidden" name="add_subject">
                                <div class="col-12 col-md-3">
                                    <select name="category_id" class="form-select">
                                        <option value="">Select Category (Optional)</option>
                                        <?php while ($cat = $allCategories->fetch_assoc()): ?>
                                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                        <?php endwhile; ?>
                                        <?php $allCategories->data_seek(0); ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <select name="class_id" class="form-select">
                                        <option value="">Select Class (Optional)</option>
                                        <?php while ($class = $allClasses->fetch_assoc()): ?>
                                            <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name']) ?></option>
                                        <?php endwhile; ?>
                                        <?php $allClasses->data_seek(0); ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <input type="text" name="name" class="form-control" placeholder="Subject Name" required>
                                </div>
                                <div class="col-12 col-md-3">
                                    <input type="text" name="slug" class="form-control" placeholder="Slug" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <input type="text" name="description" class="form-control" placeholder="Description">
                                </div>
                                <div class="col-6 col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-6 col-md-1">
                                    <button type="submit" class="btn btn-primary w-100">Add</button>
                                </div>
                            </form>
                        <?php endif; ?>

                        <h4>Existing Subjects</h4>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Category</th>
                                        <th>Class</th>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th style="min-width:120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $subjects->fetch_assoc()): ?>
                                        <tr>
                                            <form method="POST" class="form-inline d-flex flex-wrap gap-2 align-items-center">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <td>
                                                    <select name="category_id" class="form-select form-select-sm">
                                                        <option value="">None</option>
                                                        <?php while ($cat = $allCategories->fetch_assoc()): ?>
                                                            <option value="<?= $cat['id'] ?>" <?= $row['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                                        <?php endwhile; ?>
                                                        <?php $allCategories->data_seek(0); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="class_id" class="form-select form-select-sm">
                                                        <option value="">None</option>
                                                        <?php while ($class = $allClasses->fetch_assoc()): ?>
                                                            <option value="<?= $class['id'] ?>" <?= $row['class_id'] == $class['id'] ? 'selected' : '' ?>><?= htmlspecialchars($class['name']) ?></option>
                                                        <?php endwhile; ?>
                                                        <?php $allClasses->data_seek(0); ?>
                                                    </select>
                                                </td>
                                                <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control form-control-sm"></td>
                                                <td><input type="text" name="slug" value="<?= htmlspecialchars($row['slug']) ?>" class="form-control form-control-sm"></td>
                                                <td><input type="text" name="description" value="<?= htmlspecialchars($row['description']) ?>" class="form-control form-control-sm"></td>
                                                <td>
                                                    <select name="status" class="form-select form-select-sm">
                                                        <option value="active" <?= $row['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                                        <option value="inactive" <?= $row['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                                    </select>
                                                </td>
                                                <td class="d-flex gap-2">
                                                    <button type="submit" name="edit_subject" class="btn btn-sm btn-success action-btn">Save</button>
                                            </form>
                                                    <form method="POST" onsubmit="return confirm('Delete this subject?')" class="m-0">
                                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                        <button type="submit" name="delete_subject" class="btn btn-sm btn-danger action-btn">Delete</button>
                                                    </form>
                                                </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- EVENTS -->
                    <div class="tab-pane fade" id="events" role="tabpanel">
                        <h4>Add Event</h4>
                        <form method="POST" class="row g-3 mb-4">
                            <input type="hidden" name="add_event">
                            <div class="col-12 col-md-4">
                                <input type="text" name="name" class="form-control" placeholder="Event Name" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <input type="text" name="slug" class="form-control" placeholder="Slug" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <input type="text" name="description" class="form-control" placeholder="Description">
                            </div>
                            <div class="col-6 col-md-2">
                                <select name="status" class="form-select">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-1">
                                <button type="submit" class="btn btn-primary w-100">Add</button>
                            </div>
                        </form>

                        <h4>Existing Events</h4>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th style="min-width:120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $events->fetch_assoc()): ?>
                                        <tr>
                                            <form method="POST" class="form-inline d-flex flex-wrap gap-2 align-items-center">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control form-control-sm"></td>
                                                <td><input type="text" name="slug" value="<?= htmlspecialchars($row['slug']) ?>" class="form-control form-control-sm"></td>
                                                <td><input type="text" name="description" value="<?= htmlspecialchars($row['description']) ?>" class="form-control form-control-sm"></td>
                                                <td>
                                                    <select name="status" class="form-select form-select-sm">
                                                        <option value="active" <?= $row['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                                        <option value="inactive" <?= $row['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                                    </select>
                                                </td>
                                                <td class="d-flex gap-2">
                                                    <button type="submit" name="edit_event" class="btn btn-sm btn-success action-btn">Save</button>
                                            </form>
                                                    <form method="POST" onsubmit="return confirm('Delete this event?')" class="m-0">
                                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                        <button type="submit" name="delete_event" class="btn btn-sm btn-danger action-btn">Delete</button>
                                                    </form>
                                                </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- QUIZZES -->
                    <div class="tab-pane fade" id="quizzes" role="tabpanel">
                        <h4>Add Quiz</h4>
                        <div class="card shadow border-0 rounded-4 mb-4">
                            <div class="card-header theme-bg fw-semibold fs-5">
                                <i class="bi bi-plus-circle me-2"></i> Add New Quiz
                            </div>
                            <div class="card-body">
                                <?php if ($allCategories->num_rows === 0 || $allClasses->num_rows === 0 || $allSubjects->num_rows === 0): ?>
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        No active categories, classes, subjects, or events available. Please add them first.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php else: ?>
                                    <form method="POST">
                                        <input type="hidden" name="add_quiz">
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label class="form-label">Category</label>
                                                <select name="category_id" class="form-select">
                                                    <option value="">Select Category</option>
                                                    <?php while ($cat = $allCategories->fetch_assoc()): ?>
                                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                                    <?php endwhile; ?>
                                                    <?php $allCategories->data_seek(0); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Class</label>
                                                <select name="class_id" class="form-select">
                                                    <option value="">Select Class</option>
                                                    <?php while ($cl = $allClasses->fetch_assoc()): ?>
                                                        <option value="<?= $cl['id'] ?>"><?= htmlspecialchars($cl['name']) ?></option>
                                                    <?php endwhile; ?>
                                                    <?php $allClasses->data_seek(0); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Subject</label>
                                                <select name="subject_id" class="form-select">
                                                    <option value="">Select Subject</option>
                                                    <?php while ($sub = $allSubjects->fetch_assoc()): ?>
                                                        <option value="<?= $sub['id'] ?>"><?= htmlspecialchars($sub['name']) ?></option>
                                                    <?php endwhile; ?>
                                                    <?php $allSubjects->data_seek(0); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Event</label>
                                                <select name="event_id" class="form-select">
                                                    <option value="">Select Event</option>
                                                    <?php while ($evt = $allEvents->fetch_assoc()): ?>
                                                        <option value="<?= $evt['id'] ?>"><?= htmlspecialchars($evt['name']) ?></option>
                                                    <?php endwhile; ?>
                                                    <?php $allEvents->data_seek(0); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Question</label>
                                            <textarea name="question" class="form-control" rows="3" required></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Option A</label>
                                                <input type="text" name="option_a" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Option B</label>
                                                <input type="text" name="option_b" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Option C</label>
                                                <input type="text" name="option_c" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Option D</label>
                                                <input type="text" name="option_d" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Correct Option</label>
                                                <select name="correct_option" class="form-select" required>
                                                    <option value="a">Option A</option>
                                                    <option value="b">Option B</option>
                                                    <option value="c">Option C</option>
                                                    <option value="d">Option D</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="active" selected>Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-theme w-100 mt-2">
                                            <i class="bi bi-check-circle me-1"></i> Submit Quiz
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>

                        <h4>Existing Quizzes</h4>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Category</th>
                                        <th>Class</th>
                                        <th>Subject</th>
                                        <th>Event</th>
                                        <th>Question</th>
                                        <th>Option A</th>
                                        <th>Option B</th>
                                        <th>Option C</th>
                                        <th>Option D</th>
                                        <th>Correct Option</th>
                                        <th>Status</th>
                                        <th style="min-width:120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $quizzes->fetch_assoc()): ?>
                                        <tr>
                                            <form method="POST" class="form-inline d-flex flex-wrap gap-2 align-items-center">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <td>
                                                    <select name="category_id" class="form-select form-select-sm">
                                                        <option value="">None</option>
                                                        <?php while ($cat = $allCategories->fetch_assoc()): ?>
                                                            <option value="<?= $cat['id'] ?>" <?= $row['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                                        <?php endwhile; ?>
                                                        <?php $allCategories->data_seek(0); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="class_id" class="form-select form-select-sm">
                                                        <option value="">None</option>
                                                        <?php while ($cl = $allClasses->fetch_assoc()): ?>
                                                            <option value="<?= $cl['id'] ?>" <?= $row['class_id'] == $cl['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cl['name']) ?></option>
                                                        <?php endwhile; ?>
                                                        <?php $allClasses->data_seek(0); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="subject_id" class="form-select form-select-sm">
                                                        <option value="">None</option>
                                                        <?php while ($sub = $allSubjects->fetch_assoc()): ?>
                                                            <option value="<?= $sub['id'] ?>" <?= $row['subject_id'] == $sub['id'] ? 'selected' : '' ?>><?= htmlspecialchars($sub['name']) ?></option>
                                                        <?php endwhile; ?>
                                                        <?php $allSubjects->data_seek(0); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="event_id" class="form-select form-select-sm">
                                                        <option value="">None</option>
                                                        <?php while ($evt = $allEvents->fetch_assoc()): ?>
                                                            <option value="<?= $evt['id'] ?>" <?= $row['event_id'] == $evt['id'] ? 'selected' : '' ?>><?= htmlspecialchars($evt['name']) ?></option>
                                                        <?php endwhile; ?>
                                                        <?php $allEvents->data_seek(0); ?>
                                                    </select>
                                                </td>
                                                <td><textarea name="question" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($row['question']) ?></textarea></td>
                                                <td><input type="text" name="option_a" value="<?= htmlspecialchars($row['option_a']) ?>" class="form-control form-control-sm"></td>
                                                <td><input type="text" name="option_b" value="<?= htmlspecialchars($row['option_b']) ?>" class="form-control form-control-sm"></td>
                                                <td><input type="text" name="option_c" value="<?= htmlspecialchars($row['option_c']) ?>" class="form-control form-control-sm"></td>
                                                <td><input type="text" name="option_d" value="<?= htmlspecialchars($row['option_d']) ?>" class="form-control form-control-sm"></td>
                                                <td>
                                                    <select name="correct_option" class="form-select form-select-sm">
                                                        <option value="a" <?= $row['correct_option'] == 'a' ? 'selected' : '' ?>>Option A</option>
                                                        <option value="b" <?= $row['correct_option'] == 'b' ? 'selected' : '' ?>>Option B</option>
                                                        <option value="c" <?= $row['correct_option'] == 'c' ? 'selected' : '' ?>>Option C</option>
                                                        <option value="d" <?= $row['correct_option'] == 'd' ? 'selected' : '' ?>>Option D</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="status" class="form-select form-select-sm">
                                                        <option value="active" <?= $row['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                                        <option value="inactive" <?= $row['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                                    </select>
                                                </td>
                                                <td class="d-flex gap-2">
                                                    <button type="submit" name="edit_quiz" class="btn btn-sm btn-success action-btn">Save</button>
                                            </form>
                                                    <form method="POST" onsubmit="return confirm('Delete this quiz?')" class="m-0">
                                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                        <button type="submit" name="delete_quiz" class="btn btn-sm btn-danger action-btn">Delete</button>
                                                    </form>
                                                </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PENDING QUESTIONS -->
                    <!-- PENDING QUESTIONS -->
<div class="tab-pane fade" id="pending_questions" role="tabpanel">
    <h4>Pending Questions</h4>
    <div class="table-responsive mb-4">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Submitted By</th>
                    <th>Category</th>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Event</th>
                    <th>Question</th>
                    <th>Option A</th>
                    <th>Option B</th>
                    <th>Option C</th>
                    <th>Option D</th>
                    <th>Correct Option</th>
                    <th>Status</th>
                    <th style="min-width:150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $pending_questions->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['display_name'] ?: 'Unknown') ?></td>
                        <td><?= htmlspecialchars($row['category_name'] ?: 'None') ?></td>
                        <td><?= htmlspecialchars($row['class_name'] ?: 'None') ?></td>
                        <td><?= htmlspecialchars($row['subject_name'] ?: 'None') ?></td>
                        <td><?= htmlspecialchars($row['event_name'] ?: 'None') ?></td>
                        <td><textarea class="form-control form-control-sm" rows="2" readonly><?= htmlspecialchars($row['question']) ?></textarea></td>
                        <td><input type="text" value="<?= htmlspecialchars($row['option_a']) ?>" class="form-control form-control-sm" readonly></td>
                        <td><input type="text" value="<?= htmlspecialchars($row['option_b']) ?>" class="form-control form-control-sm" readonly></td>
                        <td><input type="text" value="<?= htmlspecialchars($row['option_c']) ?>" class="form-control form-control-sm" readonly></td>
                        <td><input type="text" value="<?= htmlspecialchars($row['option_d']) ?>" class="form-control form-control-sm" readonly></td>
                        <td>
                            <select class="form-select form-select-sm" disabled>
                                <option value="a" <?= $row['correct_option'] == 'a' ? 'selected' : '' ?>>Option A</option>
                                <option value="b" <?= $row['correct_option'] == 'b' ? 'selected' : '' ?>>Option B</option>
                                <option value="c" <?= $row['correct_option'] == 'c' ? 'selected' : '' ?>>Option C</option>
                                <option value="d" <?= $row['correct_option'] == 'd' ? 'selected' : '' ?>>Option D</option>
                            </select>
                        </td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td class="d-flex gap-2">
                            <?php if ($row['status'] == 'pending'): ?>
                                <form method="POST" class="m-0">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="approve_question" class="btn btn-sm btn-success action-btn">Approve</button>
                                </form>
                                <form method="POST" class="m-0">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="reject_question" class="btn btn-sm btn-warning action-btn">Reject</button>
                                </form>
                            <?php endif; ?>
                            <form method="POST" onsubmit="return confirm('Delete this pending question?')" class="m-0">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" name="delete_pending_question" class="btn btn-sm btn-danger action-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<?php $conn->close(); ?>
</body>
</html>