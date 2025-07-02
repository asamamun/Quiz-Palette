<?php
session_start();
require __DIR__ . "/vendor/autoload.php";

$db = new MysqliDb();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if (empty($errors)) {
        $user = $db->where('email', $email)->getOne('users', ['id', 'username', 'password', 'role']);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            session_write_close();
            if ($user['role'] === 'admin') {
                $redirect = 'admin/dashboard.php';
            } elseif ($user['role'] === 'teacher') {
                $redirect = 'teacher/teacher_dashboard.php';
            } else {
                $redirect = 'index.php';
            }
            echo json_encode(['success' => true, 'message' => '', 'redirect' => $redirect]);
        } else {
            $errors[] = "Incorrect email or password.";
            echo json_encode(['success' => false, 'message' => implode('<br>', $errors)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => implode('<br>', $errors)]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Quiz Palette</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" onerror="this.href='assets/css/bootstrap-icons.min.css'">
    <style>
        :root {
            --main-color: #129990;
            --form-background: #ffffff;
            --text-color: #212529;
            --error-color: #dc3545;
        }
        body {
            background: var(--main-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
            position: relative;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        .background-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }
        .quiz-element {
            position: absolute;
            color: #ffffff;
            text-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
            opacity: 0.25;
            font-size: 1.5rem;
            animation: float 10s infinite ease-in-out;
            will-change: transform, opacity;
        }
        .quiz-element.bold {
            font-weight: bold;
        }
        .quiz-element:nth-child(1) { top: 5%; left: 5%; animation-delay: 0s; }
        .quiz-element:nth-child(2) { top: 10%; left: 90%; animation-delay: 0.5s; }
        .quiz-element:nth-child(3) { top: 15%; left: 15%; animation-delay: 1s; }
        .quiz-element:nth-child(4) { top: 20%; left: 80%; animation-delay: 1.5s; }
        .quiz-element:nth-child(5) { top: 25%; left: 10%; animation-delay: 2s; }
        .quiz-element:nth-child(6) { top: 30%; left: 85%; animation-delay: 2.5s; }
        .quiz-element:nth-child(7) { top: 35%; left: 20%; animation-delay: 3s; }
        .quiz-element:nth-child(8) { top: 40%; left: 75%; animation-delay: 3.5s; }
        .quiz-element:nth-child(9) { top: 45%; left: 5%; animation-delay: 4s; }
        .quiz-element:nth-child(10) { top: 50%; left: 95%; animation-delay: 4.5s; }
        .quiz-element:nth-child(11) { top: 55%; left: 15%; animation-delay: 5s; }
        .quiz-element:nth-child(12) { top: 60%; left: 80%; animation-delay: 5.5s; }
        .quiz-element:nth-child(13) { top: 65%; left: 10%; animation-delay: 6s; }
        .quiz-element:nth-child(14) { top: 70%; left: 90%; animation-delay: 6.5s; }
        .quiz-element:nth-child(15) { top: 75%; left: 25%; animation-delay: 7s; }
        .quiz-element:nth-child(16) { top: 80%; left: 70%; animation-delay: 7.5s; }
        .quiz-element:nth-child(17) { top: 85%; left: 20%; animation-delay: 8s; }
        .quiz-element:nth-child(18) { top: 90%; left: 85%; animation-delay: 8.5s; }
        .quiz-element:nth-child(19) { top: 95%; left: 15%; animation-delay: 9s; }
        .quiz-element:nth-child(20) { top: 100%; left: 75%; animation-delay: 9.5s; }
        .quiz-element:nth-child(21) { top: 2%; left: 40%; animation-delay: 10s; }
        .quiz-element:nth-child(22) { top: 4%; left: 45%; animation-delay: 10.2s; }
        .quiz-element:nth-child(23) { top: 8%; left: 55%; animation-delay: 10.4s; }
        .quiz-element:nth-child(24) { top: 12%; left: 60%; animation-delay: 10.6s; }
        .quiz-element:nth-child(25) { top: 88%; left: 40%; animation-delay: 10.8s; }
        .quiz-element:nth-child(26) { top: 90%; left: 45%; animation-delay: 11s; }
        .quiz-element:nth-child(27) { top: 94%; left: 55%; animation-delay: 11.2s; }
        .quiz-element:nth-child(28) { top: 98%; left: 60%; animation-delay: 11.4s; }
        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); opacity: 0.25; }
            50% { transform: translateY(-10px) rotate(6deg); opacity: 0.80; }
            100% { transform: translateY(0) rotate(0deg); opacity: 0.25; }
        }
        @media (prefers-reduced-motion: reduce) {
            .quiz-element {
                animation: none;
            }
        }
        .container {
            max-width: 450px;
            background: var(--form-background);
            padding: 35px 25px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            z-index: 2;
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: var(--main-color);
            border-color: var(--main-color);
            font-weight: 600;
            padding: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #0f7c7c;
            border-color: #0f7c7c;
            transform: scale(1.02);
        }
        .btn-primary:disabled {
            background-color: #6c757d;
            border-color: #6c757d;
            cursor: not-allowed;
        }
        .form-control {
            height: 38px;
            font-size: 0.95rem;
            border-radius: 6px;
            padding-right: 35px;
            transition: border-color 0.2s ease;
        }
        .form-control:focus {
            border-color: var(--main-color);
            box-shadow: 0 0 0 0.2rem rgba(18, 153, 144, 0.25);
        }
        .form-control.is-invalid {
            border-color: var(--error-color);
        }
        .alert-dismissible .btn-close {
            background: none;
            cursor: pointer;
        }
        .logo {
            text-align: center;
            margin-bottom: 15px;
        }
        .logo h1 {
            color: #129990;
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
        }
        .logo a {
            color: inherit;
            text-decoration: none;
        }
        .logo a:hover {
            text-decoration: none;
        }
        .link {
            text-align: center;
            margin-top: 12px;
        }
        .link a {
            color: var(--main-color);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .link a:hover {
            text-decoration: underline;
        }
        .form-group {
            margin-bottom: 12px;
            position: relative;
        }
        .form-label {
            margin-bottom: 4px;
            font-size: 0.9rem;
            color: var(--text-color);
            font-weight: 500;
        }
        .form-text {
            font-size: 0.75rem;
            color: #6c757d;
        }
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 8px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            font-size: 1.1rem;
            z-index: 3;
            pointer-events: auto;
        }
        .password-toggle:focus {
            outline: 2px solid var(--main-color);
            outline-offset: 2px;
        }
        .password-toggle.fallback::before {
            content: 'Show';
            font-size: 0.85rem;
            color: #6c757d;
        }
        .password-toggle.fallback.bi-eye::before {
            content: 'Hide';
        }
        .alert {
            margin-bottom: 12px;
            padding: 10px;
            font-size: 0.85rem;
            opacity: 0;
            animation: fadeIn 0.3s ease forwards;
        }
        @keyframes fadeIn {
            to { opacity: 1; }
        }
        @media (max-width: 576px) {
            .container {
                max-width: 95%;
                padding: 25px 15px;
                margin-top: 10px;
                margin-bottom: 10px;
            }
            .logo h1 {
                font-size: 1.4rem;
            }
            .form-control {
                font-size: 0.9rem;
                height: 36px;
            }
            .quiz-element {
                font-size: 1.3rem;
            }
            .password-toggle {
                right: 6px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
   <div class="background-animation">
        <div class="quiz-element bold">E=mc²</div>
        <div class="quiz-element">✔</div>
        <div class="quiz-element bold">❓</div>
        <div class="quiz-element">x²+y²</div>
        <div class="quiz-element bold">π</div>
        <div class="quiz-element">✔</div>
        <div class="quiz-element bold">√2</div>
        <div class="quiz-element">E=mc²</div>
        <div class="quiz-element bold">x²+y²</div>
        <div class="quiz-element">✔</div>
        <div class="quiz-element bold">?</div>
        <div class="quiz-element">✘</div>
        <div class="quiz-element bold">∑</div>
        <div class="quiz-element">❓</div>
        <div class="quiz-element bold">∫</div>
        <div class="quiz-element">✘</div>
        <div class="quiz-element bold">α</div>
        <div class="quiz-element">✔</div>
        <div class="quiz-element bold">∞</div>
        <div class="quiz-element">❓</div>
        <div class="quiz-element bold">E=mc²</div>
        <div class="quiz-element bold">?</div>
        <div class="quiz-element bold">π</div>
        <div class="quiz-element bold">✔</div>
        <div class="quiz-element bold">∑</div>
        <div class="quiz-element bold">∫</div>
        <div class="quiz-element bold">α</div>
        <div class="quiz-element bold">∞</div>
    </div>
    <div class="container">
        <div class="logo">
            <h1><a href="index.php">Quiz Palette</a></h1>
        </div>
        <div id="alertBox" role="alert"></div>
        <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" aria-label="Login Form" novalidate>
            <fieldset>
                <legend class="visually-hidden">Login</legend>
                <div class="row g-2">
                    <div class="col-12 form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email" aria-required="true" aria-describedby="emailHelp">
                        
                    </div>
                    <div class="col-12 form-group">
                        <label for="password" class="form-label">Password</label>
                        <div style="position: relative;">
                            <input type="password" class="form-control" id="password" name="password" required minlength="6" placeholder="Enter your password" aria-required="true" aria-describedby="passwordHelp">
                            <span class="password-toggle bi bi-eye-slash" id="togglePassword" role="button" aria-label="Toggle password visibility" tabindex="0"></span>
                        </div>
                        
                    </div>
                    <div class="col-12 form-group">
                        <button type="submit" class="btn btn-primary w-100" id="submitButton">Log In</button>
                    </div>
                </div>
            </fieldset>
        </form>
        <div class="link">
            <a href="register.php">Don't have an account? Sign Up</a>
        </div>
    </div>

    <script defer src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous" onerror="this.src='assets/js/jquery-3.7.1.min.js'"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
        const form = document.getElementById('loginForm');
        const submitButton = document.getElementById('submitButton');
        const alertBox = document.getElementById('alertBox');

        if (!togglePassword.classList.contains('bi-eye-slash')) {
            togglePassword.classList.add('fallback');
            console.warn('Bootstrap Icons not loaded, using text fallback');
        }

        function toggleVisibility(e) {
            try {
                e.preventDefault();
                const type = passwordField.type === 'password' ? 'text' : 'password';
                passwordField.type = type;
                togglePassword.classList.toggle('bi-eye');
                togglePassword.classList.toggle('bi-eye-slash');
                togglePassword.setAttribute('aria-label', type === 'password' ? 'Show password' : 'Hide password');
                console.log('Password toggle: Type set to', type);
            } catch (error) {
                console.error('Toggle password error:', error);
            }
        }

        togglePassword.addEventListener('click', toggleVisibility);
        togglePassword.addEventListener('touchend', toggleVisibility);
        togglePassword.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleVisibility(e);
            }
        });

        if (togglePassword) {
            console.log('Password toggle initialized (vanilla JS)');
        } else {
            console.error('Password toggle element not found');
        }

        function validateInput(input) {
            if (!input.checkValidity()) {
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        }

        form.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', () => validateInput(input));
            input.addEventListener('blur', () => validateInput(input));
        });

        if (typeof jQuery !== 'undefined') {
            $(form).on('submit', function (e) {
                e.preventDefault();
                if (!form.checkValidity()) {
                    form.querySelectorAll('input').forEach(input => validateInput(input));
                    return;
                }
                const formData = $(this).serialize();
                $(submitButton).prop('disabled', true).text('Logging in...');
                $.ajax({
                    type: 'POST',
                    url: '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>',
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    success: function (response) {
                        $(submitButton).prop('disabled', false).text('Log In');
                        if (response.success) {
                            window.location.href = response.redirect;
                        } else {
                            alertBox.innerHTML = `
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    ${response.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `;
                        }
                    },
                    error: function () {
                        $(submitButton).prop('disabled', false).text('Log In');
                        alertBox.innerHTML = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                An error occurred. Please try again.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `;
                    }
                });
            });
        } else {
            console.error('jQuery not loaded, form submission disabled');
        }
    });
    </script>
</body>
</html>