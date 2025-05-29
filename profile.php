<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require __DIR__."/vendor/autoload.php";
$db = new MysqliDb();
$userid = $_SESSION['user_id'];
$userinfo = $db->where('id', $userid)->getOne('users', array('id', 'username', 'email','first_name', 'last_name','phone', 'avatar'));
// var_dump($userinfo);
// exit;
require 'includes/header.php';
?>

  <div class="container d-flex flex-column justify-content-center fixed" style="margin-top: 80px;"></div>
  <div class="app-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="sidebar-header">
        <span class="icon fa-solid fa-user"></span>
        <h2 id="userName"><?= $userinfo['first_name'] . ' ' . $userinfo['last_name'] ?></h2>
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
        <p><i class="fas fa-envelope"></i> <span id="emailText"><?= $userinfo['email'] ?></span></p>
        <p><i class="fas fa-phone"></i> <span id="phoneText"><?= $userinfo['phone'] ?></span></p>
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