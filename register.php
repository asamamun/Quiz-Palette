<?php
require __DIR__."/vendor/autoload.php";
$db = new MysqliDb();
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $data = array(
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'phone' => $_POST['phone'],
        'avatar' => $_POST['avatar']
    );
    $db->insert('users', $data);
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Quiz Palette</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>Social Talk : <?php echo settings()['companyname']; ?></h1>
            <h2><?php //echo testfunc(); ?></h2>
            <h2><?php //echo config('companyinfo.address') ?></h2>
            <h2><?php //echo config('db.database') ?></h2>
        </div>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <!-- Username -->
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" class="form-control" id="username" name="username" required maxlength="50">
    </div>

    <!-- Email -->
    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" class="form-control" id="email" name="email" required maxlength="100">
    </div>

    <!-- Password -->
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <!-- First Name -->
    <div class="mb-3">
      <label for="first_name" class="form-label">First Name</label>
      <input type="text" class="form-control" id="first_name" name="first_name" required maxlength="50">
    </div>

    <!-- Last Name -->
    <div class="mb-3">
      <label for="last_name" class="form-label">Last Name</label>
      <input type="text" class="form-control" id="last_name" name="last_name" required maxlength="50">
    </div>

    <!-- Phone -->
    <div class="mb-3">
      <label for="phone" class="form-label">Phone</label>
      <input type="tel" class="form-control" id="phone" name="phone" maxlength="20">
    </div>

    <!-- Avatar -->
    <div class="mb-3">
      <label for="avatar" class="form-label">Avatar</label>
      <input type="url" class="form-control" id="avatar" name="avatar">
    </div>


    <!-- Status -->



    <!-- Submit -->
    <button type="submit" class="btn btn-primary">Register</button>
  </form>
        <div class="link">
            <a href="login.php">Already have an account? Log In</a>
        </div>
    </div>

<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>