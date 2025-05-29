<?php
session_start();
require __DIR__."/vendor/autoload.php";
$db = new MysqliDb();
if($_SERVER["REQUEST_METHOD"] == "POST"){
$email = $_POST['email'];
$password = $_POST['password'];
$user = $db->where('email', $email)->getOne('users');
// var_dump($user);
// exit;
if($user && password_verify($password, $user['password'])){
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['logged_in'] = true;
    if($user['role'] == 'admin'){
        header("Location: admin/dashboard.php");
        exit;
    }
    else{
    header("Location: index.php");
    exit;
}
}else{
    $message = "Invalid email or password.";
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= settings()['companyname']; ?></title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f0f2f5;
        }

        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo h1 {
            color: #1877f2;
            font-size: 2rem;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            outline: none;
        }

        .form-group input:focus {
            border-color: #1877f2;
            box-shadow: 0 0 5px rgba(24, 119, 242, 0.3);
        }

        .btn {
            width: 100%;
            padding: 0.8rem;
            background: #1877f2;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #165eab;
        }

        .link {
            text-align: center;
            margin-top: 1rem;
        }

        .link a {
            color: #1877f2;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .link a:hover {
            text-decoration: underline;
        }

        .error {
            color: #e63946;
            font-size: 0.8rem;
            margin-top: 0.5rem;
            display: none;
        }

        @media (max-width: 480px) {
            .container {
                padding: 1.5rem;
                margin: 1rem;
            }

            .logo h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1><?= settings()['companyname']; ?></h1>
        </div>
<?php if (!empty($message)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

        <form id="loginForm" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <div id="emailError" class="error">Please enter a valid email address.</div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <div id="passwordError" class="error">Password must be at least 6 characters.</div>
            </div>
            <button type="submit" class="btn">Log In</button>
        </form>
        <div class="link">
            <a href="register.php">Don't have an account? Sign Up</a>
        </div>
    </div>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        const form = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');

        form.addEventListener('submit', (e) => {
            let valid = true;

            // Email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailInput.value)) {
                emailError.style.display = 'block';
                valid = false;
            } else {
                emailError.style.display = 'none';
            }

            // Password validation
            if (passwordInput.value.length < 5) {
                passwordError.style.display = 'block';
                valid = false;
            } else {
                passwordError.style.display = 'none';
            }

            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>