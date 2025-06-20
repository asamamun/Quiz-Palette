<?php
require __DIR__."/vendor/autoload.php";
$db = new MysqliDb();
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $data = array(
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'password_hash' => password_hash($_POST['password'], PASSWORD_DEFAULT)
    );

    $db->insert('users', $data);
    header("Location: login.php");
}
?>
<script type="text/javascript">
        var gk_isXlsx = false;
        var gk_xlsxFileLookup = {};
        var gk_fileData = {};
        function filledCell(cell) {
          return cell !== '' && cell != null;
        }
        function loadFileData(filename) {
        if (gk_isXlsx && gk_xlsxFileLookup[filename]) {
            try {
                var workbook = XLSX.read(gk_fileData[filename], { type: 'base64' });
                var firstSheetName = workbook.SheetNames[0];
                var worksheet = workbook.Sheets[firstSheetName];

                // Convert sheet to JSON to filter blank rows
                var jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1, blankrows: false, defval: '' });
                // Filter out blank rows (rows where all cells are empty, null, or undefined)
                var filteredData = jsonData.filter(row => row.some(filledCell));

                // Heuristic to find the header row by ignoring rows with fewer filled cells than the next row
                var headerRowIndex = filteredData.findIndex((row, index) =>
                  row.filter(filledCell).length >= filteredData[index + 1]?.filter(filledCell).length
                );
                // Fallback
                if (headerRowIndex === -1 || headerRowIndex > 25) {
                  headerRowIndex = 0;
                }

                // Convert filtered JSON back to CSV
                var csv = XLSX.utils.aoa_to_sheet(filteredData.slice(headerRowIndex)); // Create a new sheet from filtered array of arrays
                csv = XLSX.utils.sheet_to_csv(csv, { header: 1 });
                return csv;
            } catch (e) {
                console.error(e);
                return "";
            }
        }
        return gk_fileData[filename] || "";
        }
        </script><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Social Talk</title>
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
            <h1>Social Talk : <?php echo settings()['companyname']; ?></h1>
            <h2><?= testfunc(); ?></h2>
            <h2><?php echo config('companyinfo.address') ?></h2>
            <h2><?php echo config('db.database') ?></h2>
        </div>
        <form id="registerForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                <div id="usernameError" class="error">Username must be at least 3 characters.</div>
            </div>
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
            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
                <div id="confirmPasswordError" class="error">Passwords do not match.</div>
            </div>
            <button type="submit" class="btn" name="register" value="Sign Up">Sign Up</button>
        </form>
        <div class="link">
            <a href="login.php">Already have an account? Log In</a>
        </div>
    </div>

    <script>
        const form = document.getElementById('registerForm');
        const usernameInput = document.getElementById('username');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const usernameError = document.getElementById('usernameError');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');
        const confirmPasswordError = document.getElementById('confirmPasswordError');

        form.addEventListener('submit', (e) => {
            let valid = true;

            // Username validation
            if (usernameInput.value.length < 3) {
                usernameError.style.display = 'block';
                valid = false;
            } else {
                usernameError.style.display = 'none';
            }

            // Email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailInput.value)) {
                emailError.style.display = 'block';
                valid = false;
            } else {
                emailError.style.display = 'none';
            }

            // Password validation
            if (passwordInput.value.length < 6) {
                passwordError.style.display = 'block';
                valid = false;
            } else {
                passwordError.style.display = 'none';
            }

            // Confirm password validation
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordError.style.display = 'block';
                valid = false;
            } else {
                confirmPasswordError.style.display = 'none';
            }

            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>