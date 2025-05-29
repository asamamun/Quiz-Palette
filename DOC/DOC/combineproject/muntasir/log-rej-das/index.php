<?php
session_start();

// Database configuration — update as needed
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'test_db';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$register_err = $login_err = "";
$show_tab = "login";

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Registration handler
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $show_tab = "register";

    $username = sanitize_input($_POST["username"]);
    $email = sanitize_input($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if(empty($username) || empty($email) || empty($password) || empty($confirm_password)){
        $register_err = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_err = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $register_err = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $register_err = "Password must be at least 6 characters.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $register_err = "Email is already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            if ($stmt->execute()) {
                $register_err = "Registration successful! Please login.";
                $show_tab = "login";
            } else {
                $register_err = "Something went wrong. Please try again.";
            }
        }
        $stmt->close();
    }
}

// Login handler
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $show_tab = "login";

    $email = sanitize_input($_POST["email"]);
    $password = $_POST["password"];

    if(empty($email) || empty($password)){
        $login_err = "Please enter email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username, $hashed_password, $role);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["role"] = $role;

                // Redirect based on role
                if ($role === 'admin') {
                    header("Location: admin_dashboard.php");
                    exit;
                } elseif ($role === 'moderator') {
                    header("Location: moderator_dashboard.php"); // Placeholder for mod dashboard
                    exit;
                } else {
                    header("Location: user_dashboard.php");
                    exit;
                }

            } else {
                $login_err = "Invalid password.";
            }
        } else {
            $login_err = "No account found with that email.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Login & Registration with Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 15px;
        }
        .auth-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            max-width: 420px;
            width: 100%;
            padding: 30px;
        }
        .nav-tabs .nav-link {
            font-weight: 600;
            color: #764ba2;
        }
        .nav-tabs .nav-link.active {
            color: white;
            background-color: #764ba2;
            border-color: #764ba2 #764ba2 white;
            border-radius: 20px 20px 0 0;
        }
        .form-control:focus {
            border-color: #764ba2;
            box-shadow: 0 0 10px #764ba2a1;
        }
        .btn-primary {
            background-color: #764ba2;
            border-color: #764ba2;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #5a357a;
            border-color: #5a357a;
        }
        .error-message {
            color: #d9534f;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        .success-message {
            color: #3c763d;
            font-size: 0.9rem;
            margin-top: 5px;
            font-weight: 600;
        }
        @media(max-width: 400px){
            .auth-container {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container" role="main">
        <ul class="nav nav-tabs mb-4" id="authTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo ($show_tab == 'login') ? 'active' : ''; ?>" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="true">Login</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo ($show_tab == 'register') ? 'active' : ''; ?>" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab" aria-controls="register" aria-selected="false">Register</button>
            </li>
        </ul>
        <div class="tab-content" id="authTabContent">
            <div class="tab-pane fade <?php echo ($show_tab == 'login') ? 'show active' : ''; ?>" id="login" role="tabpanel" aria-labelledby="login-tab">
                <form id="loginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">Email address</label>
                        <input type="email" 
                               class="form-control" id="loginEmail" name="email" required placeholder="Enter email" />
                        <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Password</label>
                        <input type="password" 
                               class="form-control" id="loginPassword" name="password" required minlength="6" placeholder="Password" />
                        <div class="invalid-feedback">Password must be at least 6 characters.</div>
                    </div>
                    <?php if(!empty($login_err)) { 
                        if(strpos($login_err, 'successful') !== false) {
                            echo '<div class="success-message mb-3">'.$login_err.'</div>';
                        } else {
                            echo '<div class="error-message mb-3">'.$login_err.'</div>';
                        }
                    }?>
                    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
            <div class="tab-pane fade <?php echo ($show_tab == 'register') ? 'show active' : ''; ?>" id="register" role="tabpanel" aria-labelledby="register-tab">
                <form id="registerForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
                    <div class="mb-3">
                        <label for="regUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="regUsername" name="username" required placeholder="Enter username" />
                        <div class="invalid-feedback">Please enter a username.</div>
                    </div>
                    <div class="mb-3">
                        <label for="regEmail" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="regEmail" name="email" required placeholder="Enter email" />
                        <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>
                    <div class="mb-3">
                        <label for="regPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="regPassword" name="password" required minlength="6" placeholder="Password" />
                        <div class="invalid-feedback">Password must be at least 6 characters.</div>
                    </div>
                    <div class="mb-3">
                        <label for="regConfirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="regConfirmPassword" name="confirm_password" required minlength="6" placeholder="Confirm Password" />
                        <div class="invalid-feedback">Passwords must match.</div>
                    </div>
                    <?php if(!empty($register_err)) { 
                        if(strpos($register_err, 'successful') !== false) {
                            echo '<div class="success-message mb-3">'.$register_err.'</div>';
                        } else {
                            echo '<div class="error-message mb-3">'.$register_err.'</div>';
                        }
                    }?>
                    <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            'use strict';
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', event => {
                    if(form.id === 'registerForm') {
                        const password = form.querySelector("#regPassword").value;
                        const confirmPassword = form.querySelector("#regConfirmPassword").value;
                        if(password !== confirmPassword){
                            event.preventDefault();
                            event.stopPropagation();
                            form.querySelector("#regConfirmPassword").classList.add('is-invalid');
                            form.querySelector("#regConfirmPassword").nextElementSibling.textContent = "Passwords do not match.";
                            return;
                        } else {
                            form.querySelector("#regConfirmPassword").classList.remove('is-invalid');
                        }
                    }
                    if(!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });

            // Show the correct tab based on PHP variable
            const showTab = '<?php echo $show_tab; ?>';
            if(showTab === 'register'){
                const tabTrigger = document.querySelector('#register-tab');
                const tab = new bootstrap.Tab(tabTrigger);
                tab.show();
            } else {
                const tabTrigger = document.querySelector('#login-tab');
                const tab = new bootstrap.Tab(tabTrigger);
                tab.show();
            }
        })();
    </script>
</body>
</html>
