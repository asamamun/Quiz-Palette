<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $plan = $_GET['plan'] ?? 'basic';

    // Validate inputs
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        $error = "Please fill in all fields";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";
    } elseif (!preg_match('/^01[3-9]\d{8}$/', $phone)) {
        $error = "Please enter a valid Bangladesh phone number";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        try {
            // Check if email already exists
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Email already registered";
            } else {
                // Create user
                $names = explode(' ', $name, 2);
                $first_name = $names[0];
                $last_name = isset($names[1]) ? $names[1] : '';
                $username = strtolower(str_replace(' ', '', $name)) . rand(100, 999);
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $db->prepare("
                    INSERT INTO users (username, email, password, first_name, last_name, phone, role, status) 
                    VALUES (?, ?, ?, ?, ?, ?, 'user', 'active')
                ");
                
                $stmt->execute([
                    $username, $email, $hashed_password, $first_name, $last_name, $phone
                ]);
                
                $user_id = $db->lastInsertId();

                // Create basic subscription
                $stmt = $db->prepare("
                    INSERT INTO subscriptions (
                        user_id, plan_name, start_date, status, amount
                    ) VALUES (?, 'basic', NOW(), 'active', 0)
                ");
                $stmt->execute([$user_id]);

                // Redirect to login or dashboard
                header("Location: login.php?registered=1");
                exit;
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Quiz Palette</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #096B68 0%, #0a4f4c 100%);
            min-height: 100vh;
            color: white;
        }
        .card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }
        .btn-primary {
            background-color: #00d4aa;
            border-color: #00d4aa;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <h2 class="text-center mb-4">Create Your Free Account</h2>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="login.php" class="text-info">Sign In</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>