<?php
$host = "localhost";
$db = "quizpallete";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$totalQuizzes = $conn->query("SELECT COUNT(*) AS total FROM quizzes")->fetch_assoc()['total'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard Stats</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- FontAwesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />

  <style>
    :root {
      --primary-color: #129990;
      --accent-color: #90D1CA;
    }

    body {
      background-color: #f1f5f9;
    }

    .ds-stat-card {
      background-color: var(--primary-color);
      border-radius: 1rem;
      color: white;
      padding: 1.5rem 1rem;
      text-align: center;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
      position: relative;
      overflow: hidden;
    }

    .ds-stat-card::before {
      content: '';
      position: absolute;
      top: -30px;
      right: -30px;
      width: 100px;
      height: 100px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
    }

    .ds-stat-icon {
      width: 55px;
      height: 55px;
      background-color: var(--accent-color);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 0.7rem;
      animation: pulse 2s infinite ease-in-out;
    }

    .ds-stat-icon i {
      font-size: 1.7rem;
      color: white;
      animation: bounce 2s infinite ease-in-out;
    }

    .ds-stat-label {
      font-weight: 700;
      font-size: 1.4rem;
      margin-bottom: 0.4rem;
    }

    .ds-stat-count {
      font-size: 2.8rem;
      font-weight: 800;
      margin: 0;
    }

    @keyframes pulse {
      0%, 100% {
        box-shadow: 0 0 8px var(--accent-color);
        transform: scale(1);
      }
      50% {
        box-shadow: 0 0 20px var(--accent-color);
        transform: scale(1.1);
      }
    }

    @keyframes bounce {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-5px);
      }
    }
  </style>
</head>
<body>
  <div class="container my-5">
    <div class="row g-4 justify-content-center">
      <div class="col-md-4 col-sm-6">
        <div class="ds-stat-card">
          <div class="ds-stat-icon">
            <i class="fas fa-users"></i>
          </div>
          <div class="ds-stat-label">Total Users</div>
          <div class="ds-stat-count" id="userCount">0</div>
        </div>
      </div>

      <div class="col-md-4 col-sm-6">
        <div class="ds-stat-card">
          <div class="ds-stat-icon">
            <i class="fas fa-question-circle"></i>
          </div>
          <div class="ds-stat-label">Total Quizzes</div>
          <div class="ds-stat-count" id="quizCount">0</div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function animateCount(id, target) {
      const el = document.getElementById(id);
      let count = 0;
      const step = Math.max(1, Math.ceil(target / 50));
      const interval = setInterval(() => {
        count += step;
        if (count >= target) {
          count = target;
          clearInterval(interval);
        }
        el.textContent = count;
      }, 20);
    }

    animateCount("userCount", <?php echo $totalUsers; ?>);
    animateCount("quizCount", <?php echo $totalQuizzes; ?>);
  </script>
</body>
</html>
