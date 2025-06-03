<?php
require 'includes/header.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profile Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #129990;
      --secondary-color: #096B68;
      --accent-color: #90D1CA;
      --light-color: #f8f9fa;
      --dark-color: #212529;
      --success-color: #4cc9f0;
      --error-color: #FE4F2D;
      --warning-color: #ff9e00;
      --border-radius: 10px;
      --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      --transition: all 0.3s ease;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f7fa;
      color: var(--dark-color);
      line-height: 1.6;
    }

    .app-container {
      display: flex;
      min-height: 100vh;
      background-color: white;
      box-shadow: var(--box-shadow);
      border-radius: var(--border-radius);
      overflow: hidden;
      margin: 20px;
    }

    /* Header Styles */
    .header {
      background-color: var(--light-color);
      height: 200px;
      position: relative;
    }

    #coverImage {
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0.9;
    }

    .cover-upload {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 2;
      background: var(--primary-color);
      color: white;
      border-radius: var(--border-radius);
      padding: 8px 15px;
      font-weight: 500;
      transition: var(--transition);
    }

    .cover-upload:hover {
      background: var(--secondary-color);
      transform: translateY(-2px);
    }

    .profile-pic {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      position: absolute;
      bottom: -50px;
      left: 20px;
      border: 3px solid white;
      background-color: #ddd;
      z-index: 2;
      box-shadow: var(--box-shadow);
    }

    .edit-icon-profile {
      position: absolute;
      bottom: -5px;
      left: 100px;
      background: white;
      border-radius: 50%;
      padding: 8px;
      cursor: pointer;
      z-index: 3;
      box-shadow: var(--box-shadow);
      transition: var(--transition);
    }

    .edit-icon-profile:hover {
      background: var(--accent-color);
      color: white;
    }

    .edit-icon-bio {
      cursor: pointer;
      color: var(--primary-color);
      margin-left: 10px;
      transition: var(--transition);
    }

    .edit-icon-bio:hover {
      color: var(--secondary-color);
      transform: scale(1.2);
    }

    /* Sidebar Styles */
    .sidebar {
      width: 280px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 30px 20px;
      position: relative;
      z-index: 1;
    }

    .sidebar::before {
      content: '';
      position: absolute;
      top: -63px;
      right: -50px;
      width: 150px;
      height: 150px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      z-index: -1;
    }

    .sidebar-header {
      margin-bottom: 30px;
      display: flex;
      align-items: center;
      padding-bottom: 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .sidebar-header h2 {
      font-weight: 600;
      margin: 0;
      font-size: 1.5rem;
    }

    .sidebar-header .icon {
      margin-right: 12px;
      font-size: 1.5rem;
      color: white;
    }

    /* Menu List Styles */
    .menu-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .menu-item {
      margin-bottom: 8px;
      border-radius: var(--border-radius);
      transition: var(--transition);
      overflow: hidden;
      position: relative;
    }

    .menu-item::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 4px;
      background-color: white;
      transform: scaleY(0);
      transform-origin: bottom;
      transition: transform 0.3s ease;
    }

    .menu-item:hover,
    .menu-item.active {
      background-color: rgba(255, 255, 255, 0.15);
    }

    .menu-item.active::before {
      transform: scaleY(1);
    }

    .menu-link {
      display: flex;
      align-items: center;
      padding: 14px 20px;
      text-decoration: none;
      color: white;
      font-weight: 500;
      position: relative;
      z-index: 1;
    }

    .menu-link .icon {
      margin-right: 15px;
      font-size: 1.2rem;
      width: 24px;
      text-align: center;
      transition: var(--transition);
    }

    .menu-item:hover .icon,
    .menu-item.active .icon {
      transform: scale(1.1);
    }

    /* Content Area Styles */
    .content-area {
      flex: 1;
      padding: 30px;
      background-color: var(--light-color);
      height: 100%;
      overflow-y: auto;
    }

    /* Quiz Result Card Styles (Adapted) */
    .quiz-result-card {
      background: white;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      padding: 30px;
      margin-bottom: 25px;
      animation: fadeIn 0.5s ease;
      position: relative;
    }

    .quiz-result-card .icon-container {
      position: absolute;
      top: 15px;
      right: 15px;
      font-size: 1.5rem;
      color: var(--accent-color);
      opacity: 0.7;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .quiz-result-card .result-summary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      padding: 25px;
      border-radius: var(--border-radius);
      margin-bottom: 20px;
      text-align: center;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .quiz-result-card .result-summary::before {
      content: '';
      position: absolute;
      top: -50px;
      right: -50px;
      width: 150px;
      height: 150px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
    }

    .quiz-result-card .result-summary h5 {
      font-weight: 600;
      margin-bottom: 15px;
      position: relative;
    }

    .quiz-result-card .result-summary .score-display {
      font-size: 2.5rem;
      font-weight: 700;
      margin: 15px 0;
      position: relative;
    }

    .quiz-result-card .result-stats {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 20px;
      position: relative;
    }

    .quiz-result-card .stat-item {
      background: rgba(255, 255, 255, 0.15);
      padding: 10px 20px;
      border-radius: var(--border-radius);
      min-width: 100px;
    }

    .quiz-result-card .stat-item .stat-value {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 5px;
    }

    .quiz-result-card .stat-item .stat-label {
      font-size: 0.9rem;
      opacity: 0.9;
    }

    .quiz-result-card .correct-stat .stat-value {
      color: var(--success-color);
    }

    .quiz-result-card .wrong-stat .stat-value {
      color: var(--error-color);
    }

    .quiz-result-card .unanswered-stat .stat-value {
      color: var(--warning-color);
    }

    .quiz-result-card .timestamp {
      font-size: 0.9rem;
      color: #adb5bd;
      margin-top: 10px;
      text-align: center;
    }

    /* Tab Styles */
    .nav-tabs {
      border-bottom: 2px solid var(--accent-color);
    }

    .nav-tabs .nav-link {
      color: var(--dark-color);
      border: none;
      border-radius: var(--border-radius) var(--border-radius) 0 0;
      padding: 10px 20px;
      transition: var(--transition);
    }

    .nav-tabs .nav-link:hover {
      background-color: rgba(18, 153, 144, 0.1);
      color: var(--primary-color);
    }

    .nav-tabs .nav-link.active {
      background-color: var(--primary-color);
      color: white;
      border: none;
    }

    /* Card Styles for Other Tabs */
    .icon-card {
      border: none;
      border-radius: var(--border-radius);
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
      box-shadow: 0 12px 18px rgba(0, 0, 0, 0.12);
      height: 100%;
      margin-bottom: 20px;
      background: white;
      overflow: hidden;
      display: flex;
      align-items: stretch;
    }

    .icon-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
      border-left: 3px solid var(--secondary-color);
    }

    .icon-container {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 15px;
      min-width: 80px;
      background-color: var(--primary-color);
      transition: all 0.3s ease;
    }

    .icon-card:hover .icon-container {
      background-color: var(--secondary-color);
    }

    .icon-img {
      font-size: 1.5rem;
      color: white;
    }

    .card-body {
      padding: 20px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .card-title {
      font-weight: 600;
      color: var(--primary-color);
      margin-bottom: 5px;
      font-size: 1rem;
      transition: color 0.3s ease;
    }

    .icon-card:hover .card-title {
      color: var(--secondary-color);
    }

    .card-text {
      color: #666;
      font-size: 0.85rem;
      line-height: 1.4;
      margin-bottom: 0;
    }

    /* Modal Styles */
    .modal-content {
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
    }

    .modal-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    .modal-body {
      padding: 20px;
    }

    .modal-footer .btn-primary {
      background: var(--primary-color);
      border: none;
      transition: var(--transition);
    }

    .modal-footer .btn-primary:hover {
      background: var(--secondary-color);
      transform: translateY(-2px);
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
      .app-container {
        flex-direction: column;
        margin: 0;
        border-radius: 0;
      }

      .sidebar {
        width: 100%;
        padding: 20px;
        border-right: none;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      }

      .content-area {
        padding: 20px;
      }

      .profile-pic {
        width: 80px;
        height: 80px;
        bottom: -40px;
        left: 15px;
      }

      .edit-icon-profile {
        left: 80px;
        padding: 6px;
      }

      .quiz-result-card .icon-container {
        font-size: 1.2rem;
        top: 10px;
        right: 10px;
      }
    }

    @media (max-width: 576px) {
      .quiz-result-card .result-stats {
        flex-direction: column;
        align-items: center;
        gap: 10px;
      }

      .quiz-result-card .stat-item {
        width: 100%;
        max-width: 200px;
      }

      .quiz-result-card .icon-container {
        font-size: 1rem;
      }

      .cover-upload {
        padding: 6px 10px;
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>
  <div class="container d-flex flex-column justify-content-center fixed" style="margin-top: 80px;"></div>
  <div class="app-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="sidebar-header">
        <span class="icon fa-solid fa-user"></span>
        <h2 id="userName">Your Name</h2>
      </div>
      <div>
        <h5>
          Bio
          <i class="fas fa-pen edit-icon-bio" data-bs-toggle="modal" data-bs-target="#editModal"></i>
        </h5>
        <p id="bioText">This is a short bio about the user...</p>
        <h6>
          Contact
          <i class="fas fa-pen edit-icon-bio" data-bs-toggle="modal" data-bs-target="#editModal"></i>
        </h6>
        <p><i class="fas fa-envelope"></i> <span id="emailText">abc@gmail.com</span></p>
        <p><i class="fas fa-phone"></i> <span id="phoneText">+0134567890</span></p>
      </div>
    </div>

    <!-- Content Area -->
    <div class="content-area">
      <div class="header">
        <input type="file" id="coverInput" accept="image/*" hidden>
        <label for="coverInput" class="cover-upload btn btn-sm">
          <i class="fas fa-pen"></i> Cover
        </label>
        <img id="coverImage" src="" alt="Cover" />

        <input type="file" id="profileInput" accept="image/*" hidden>
        <img id="profilePic" src="https://via.placeholder.com/100?text=User" class="profile-pic" alt="Profile" />
        <label for="profileInput" class="edit-icon-profile">
          <i class="fas fa-pen"></i>
        </label>
      </div>

      <ul class="nav nav-tabs mt-5" id="myTab" role="tablist">
        <li class="nav-item menu-item active" role="presentation">
          <button class="nav-link menu-link active" id="tab-a" data-bs-toggle="tab" data-bs-target="#content-a" type="button" role="tab">
            <span class="icon fa-solid fa-owl"></span> Overview
          </button>
        </li>
        <li class="nav-item menu-item" role="presentation">
          <button class="nav-link menu-link" id="tab-b" data-bs-toggle="tab" data-bs-target="#content-b" type="button" role="tab">
            <span class="icon fa-solid fa-fox"></span> Subscription
          </button>
        </li>
        <li class="nav-item menu-item" role="presentation">
          <button class="nav-link menu-link" id="tab-c" data-bs-toggle="tab" data-bs-target="#content-c" type="button" role="tab">
            <span class="icon fa-solid fa-cat"></span> Report
          </button>
        </li>
      </ul>

      <div class="tab-content pt-3">
        <div class="tab-pane fade show active" id="content-a" role="tabpanel">
          <div class="card-content row" id="quizResultsContainer">
            <!-- Quiz results will be dynamically inserted here -->
            <div class="col-md-6 mb-3">
              <div class="icon-card">
                <div class="icon-container">
                  <i class="icon-img fa-solid fa-turtle"></i>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Sample Card</h5>
                  <p class="card-text">This is a sample card for the Overview tab.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="content-b" role="tabpanel">
          <div class="card-content row">
            <div class="col-md-6 mb-3">
              <div class="icon-card">
                <div class="icon-container">
                  <i class="icon-img fa-solid fa-dog"></i>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Subscription Plan</h5>
                  <p class="card-text">Details about your current subscription plan.</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="icon-card">
                <div class="icon-container">
                  <i class="icon-img fa-solid fa-fish"></i>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Billing Info</h5>
                  <p class="card-text">Manage your billing and payment details.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="content-c" role="tabpanel">
          <div class="card-content row">
            <div class="col-md-6 mb-3">
              <div class="icon-card">
                <div class="icon-container">
                  <i class="icon-img fa-solid fa-horse"></i>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Performance Report</h5>
                  <p class="card-text">View your quiz performance metrics.</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="icon-card">
                <div class="icon-container">
                  <i class="icon-img fa-solid fa-bird"></i>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Feedback</h5>
                  <p class="card-text">Submit feedback or view past reports.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Modal -->
  <div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Info</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="bioInput" class="form-label">Bio</label>
            <textarea id="bioInput" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label for="emailInput" class="form-label">Email</label>
            <input type="email" id="emailInput" class="form-control">
          </div>
          <div class="mb-3">
            <label for="phoneInput" class="form-label">Phone</label>
            <input type="text" id="phoneInput" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="saveInfo()">Save</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Load data from localStorage
      const profile = localStorage.getItem("profilePic");
      const cover = localStorage.getItem("coverImage");
      const name = localStorage.getItem("userName");
      const bio = localStorage.getItem("bioText");
      const email = localStorage.getItem("emailText");
      const phone = localStorage.getItem("phoneText");
      const quizResults = JSON.parse(localStorage.getItem("quizResults")) || [];

      if (profile) document.getElementById("profilePic").src = profile;
      if (cover) document.getElementById("coverImage").src = cover;
      if (name) document.getElementById("userName").textContent = name;
      if (bio) document.getElementById("bioText").textContent = bio;
      if (email) document.getElementById("emailText").textContent = email;
      if (phone) document.getElementById("phoneText").textContent = phone;

      // Load quiz results into cards
      const quizResultsContainer = document.getElementById("quizResultsContainer");
      if (quizResults.length > 0) {
        quizResults.forEach((result, index) => {
          const quizCard = document.createElement("div");
          quizCard.className = "col-md-6 mb-3";
          quizCard.innerHTML = `
            <div class="quiz-result-card">
              <div class="icon-container">
                <i class="fa-solid fa-owl"></i>
              </div>
              <div class="result-summary">
                <h5>${result.examTitle}</h5>
                <div class="score-display">${result.scorePercentage}%</div>
                <div class="result-stats">
                  <div class="stat-item correct-stat">
                    <div class="stat-value">${result.correctCount}</div>
                    <div class="stat-label">Correct</div>
                  </div>
                  <div class="stat-item wrong-stat">
                    <div class="stat-value">${result.wrongCount}</div>
                    <div class="stat-label">Wrong</div>
                  </div>
                  <div class="stat-item unanswered-stat">
                    <div class="stat-value">${result.unansweredCount}</div>
                    <div class="stat-label">Unanswered</div>
                  </div>
                </div>
              </div>
              <div class="timestamp">Completed: ${result.timestamp}</div>
            </div>
          `;
          quizResultsContainer.insertBefore(quizCard, quizResultsContainer.firstChild);
        });
      } else {
        const noResultsCard = document.createElement("div");
        noResultsCard.className = "col-md-6 mb-3";
        noResultsCard.innerHTML = `
          <div class="quiz-result-card">
            <div class="icon-container">
              <i class="fa-solid fa-paw"></i>
            </div>
            <div class="result-summary">
              <h5>No Quiz Results</h5>
              <p>Complete a quiz to see your results here.</p>
            </div>
          </div>
        `;
        quizResultsContainer.insertBefore(noResultsCard, quizResultsContainer.firstChild);
      }

      // Profile picture upload
      document.getElementById("profileInput").addEventListener("change", function (e) {
        const reader = new FileReader();
        reader.onload = function (ev) {
          document.getElementById("profilePic").src = ev.target.result;
          localStorage.setItem("profilePic", ev.target.result);
        };
        if (e.target.files[0]) reader.readAsDataURL(e.target.files[0]);
      });

      // Cover image upload
      document.getElementById("coverInput").addEventListener("change", function (e) {
        const reader = new FileReader();
        reader.onload = function (ev) {
          document.getElementById("coverImage").src = ev.target.result;
          localStorage.setItem("coverImage", ev.target.result);
        };
        if (e.target.files[0]) reader.readAsDataURL(e.target.files[0]);
      });
    });

    function saveInfo() {
      const bio = document.getElementById("bioInput").value;
      const email = document.getElementById("emailInput").value;
      const phone = document.getElementById("phoneInput").value;

      document.getElementById("bioText").textContent = bio;
      document.getElementById("emailText").textContent = email;
      document.getElementById("phoneText").textContent = phone;

      localStorage.setItem("bioText", bio);
      localStorage.setItem("emailText", email);
      localStorage.setItem("phoneText", phone);

      const modal = bootstrap.Modal.getInstance(document.getElementById("editModal"));
      modal.hide();
    }
  </script>
</body>
</html>