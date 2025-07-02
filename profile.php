<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . "/vendor/autoload.php";
$db = new MysqliDb();
$userid = $_SESSION['user_id'];
$userinfo = $db->where('id', $userid)->getOne('users', ['id', 'username', 'email', 'first_name', 'last_name', 'phone']);
require 'includes/header.php';

// Fetch data from database
$quizzes = $db->where('created_by', $userid)->get('quizzes');
$categories = $db->get('categories');
$pending_questions = $db->where('status', 'pending')->get('pending_questions');
$events = $db->orderBy('event_date', 'desc')->get('events');
$subscription = $db->where('user_id', $userid)->getOne('subscriptions');

$leaderboard_results = $db->rawQuery("
    SELECT l.*, e.title AS exam_name, c.name AS category_name, 
           cl.name AS class_name, s.name AS subject_name
    FROM leaderboards l
    LEFT JOIN exams e ON l.exam_id = e.id
    LEFT JOIN categories c ON l.category_id = c.id
    LEFT JOIN classes cl ON l.class_id = cl.id
    LEFT JOIN subjects s ON l.subject_id = s.id
    WHERE l.user_id = ?
", [$userid]);
?>
<link rel="stylesheet" href="assets/css/profile.css">
<style>
  /* Global Styles */
  :root {
    --primary-color: #096B68;
    --primary-light: #2C8F8B;
    --primary-dark: #054B49;
    --background-light: #F5F7FA;
    --text-dark: #1F2937;
    --text-muted: #6B7280;
  }

  body {
    font-family: 'Inter', sans-serif;
    background-color: var(--background-light);
    color: var(--text-dark);
  }

  /* Card Styles */
  .event-card, .report-card, .quiz-result-card {
    background-color: #FFFFFF;
    border: 2px solid var(--primary-color);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
  }

  .event-card:hover, .report-card:hover, .quiz-result-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
  }

  .icon-container {
    width: 48px;
    height: 48px;
    background-color: var(--primary-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    float: left;
    color: #FFFFFF;
    font-size: 1.2rem;
  }

  /* Add Quiz Form */
  .add-quiz-form {
    background-color: #FFFFFF;
    padding: 25px;
    border-radius: 12px;
    border: 2px solid var(--primary-color);
    margin-bottom: 25px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .add-quiz-form .form-label {
    font-weight: 600;
    color: var(--text-dark);
  }

  .add-quiz-form .form-control, .add-quiz-form .form-select {
    border-color: var(--primary-light);
    border-radius: 8px;
    transition: border-color 0.2s;
  }

  .add-quiz-form .form-control:focus, .add-quiz-form .form-select:focus {
    border-color: var(--primary-dark);
    box-shadow: 0 0 0 0.2rem rgba(9, 107, 104, 0.25);
  }

  .add-quiz-form .btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 600;
    transition: background-color 0.2s, border-color 0.2s;
  }

  .add-quiz-form .btn-primary:hover {
    background-color: var(--primary-dark);
    border-color: var(--primary-dark);
  }

  /* Profile Image Styles */
  .profile-pic {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #FFFFFF;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  #coverImage {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: 12px;
  }

  .cover-upload, .edit-icon-profile {
    background-color: var(--primary-color);
    color: #FFFFFF;
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 0.9rem;
    transition: background-color 0.2s;
  }

  .cover-upload:hover, .edit-icon-profile:hover {
    background-color: var(--primary-dark);
  }

  /* Sidebar and Content Area */
  .sidebar {
    background-color: #FFFFFF;
    border-radius unquestionably: 12px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .sidebar-header {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .sidebar-header .icon {
    color: var(--primary-color);
    font-size: 1.5rem;
  }

  .event-ticker-item {
    padding: 10px 0;
    border-bottom: 1px solid var(--primary-light);
  }

  .event-ticker-item:last-child {
    border-bottom: none;
  }

  /* Tabs */
  .nav-tabs .nav-link {
    color: var(--text-muted);
    font-weight: 500;
    border-radius: 8px;
  }

  .nav-tabs .nav-link.active {
    background-color: var(--primary-color);
    color: #FFFFFF;
    border-color: var(--primary-color);
  }

  .nav-tabs .nav-link:hover {
    background-color: var(--primary-light);
    color: #FFFFFF;
  }

  /* Buttons */
  .btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    border-radius: 8px;
    font-weight: 600;
  }

  .btn-primary:hover {
    background-color: var(--primary-dark);
    border-color: var(--primary-dark);
  }

  /* Alerts */
  .alert-info {
    background-color: var(--primary-light);
    color: #FFFFFF;
    border-color: var(--primary-color);
    border-radius: 8px;
  }
</style>

<div class="container" style="margin-top: 80px;"></div>
<div class="app-container">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <span class="icon fa-solid fa-user"></span>
      <h2><?= htmlspecialchars($userinfo['first_name'] . ' ' . $userinfo['last_name']) ?></h2>
    </div>
    <div>
      <h5>Bio <i class="fas fa-pen edit-icon-bio" data-bs-toggle="modal" data-bs-target="#editModal"></i></h5>
      <p id="bioText">No bio yet</p>
      <h6>Contact</h6>
      <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($userinfo['email']) ?></p>
      <p><i class="fas fa-phone"></i> <?= htmlspecialchars($userinfo['phone']) ?></p>
    </div>
    <div class="event-ticker">
      <h6>Latest Events</h6>
      <?php foreach (array_slice($events, 0, 3) as $event): ?>
        <div class="event-ticker-item">
          <h6><?= htmlspecialchars($event['name']) ?></h6>
          <p><?= $event['event_date'] ? date('M j, Y', strtotime($event['event_date'])) : 'No date set' ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content-area">
    <!-- Profile Header -->
    <div class="header">
      <input type="file" id="coverInput" accept="image/*" hidden>
      <label for="coverInput" class="cover-upload btn btn-sm">
        <i class="fas fa-pen"></i> Cover
      </label>
      <img id="coverImage" src="assets/images/default-cover.jpg" alt="Cover">
      
      <input type="file" id="profileInput" accept="image/*" hidden>
      <img id="profilePic" src="assets/images/default-profile.jpg" class="profile-pic" alt="Profile">
      <label for="profileInput" class="edit-icon-profile">
        <i class="fas fa-pen"></i>
      </label>
    </div>
    <br><br>

    <!-- Tabs -->
    <ul class="nav nav-tabs mt-4">
      <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#overview">Overview</button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#add-quiz">Add Quiz</button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#subscription">Subscription</button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#events">Events</button>
      </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3">
      <!-- Overview Tab -->
      <div class="tab-pane fade show active" id="overview">
        <div class="row">
          <div class="col-md-8">
            <!-- Your Results Section -->
            <h4 class="mt-4">Your Results</h4>
            <div class="row">
              <?php if (!empty($leaderboard_results)): ?>
                <?php foreach ($leaderboard_results as $index => $result): ?>
                  <div class="col-md-6 mb-3">
<div class="card mb-4 shadow-lg" style="border: none; border-radius: 1rem; background-color: #ffffff;">
  <!-- Header -->
  <div class="card-header d-flex align-items-center" style="background-color: #ffe082; border-bottom: none; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
    <div class="me-3 d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="background-color:#096B68; width: 55px; height: 55px;">
      <i class="fa-solid fa-trophy text-white fa-lg"></i>
    </div>
    <div>
      <h5 class="mb-0 text-dark">
        <?php
          if (!empty($result['exam_name'])) {
            echo htmlspecialchars($result['exam_name']);
          } else {
            $display = [];
            if ($result['category_name']) $display[] = htmlspecialchars($result['category_name']);
            if ($result['class_name']) $display[] = htmlspecialchars($result['class_name']);
            if ($result['subject_name']) $display[] = htmlspecialchars($result['subject_name']);
            echo !empty($display) ? implode(' - ', $display) : 'General Quiz';
          }
        ?>
      </h5>
      <small class="text-muted">Last Updated: <?= date('M j, Y', strtotime($result['last_updated'])) ?></small>
    </div>
  </div>

  <!-- Body -->
  <div class="card-body">
    <div class="row g-3">

      <div class="col-md-6">
        <div class="p-3 rounded" style="background-color: #b2ebf2;">
          <i class="fa-solid fa-layer-group text-success me-2"></i>
          <strong>Level:</strong> <?= htmlspecialchars($result['level'] ?? 'N/A') ?>
        </div>
      </div>

      <div class="col-md-6">
        <div class="p-3 rounded" style="background-color: #ffe0b2;">
          <i class="fa-solid fa-star" style="color: #ff8f00;"></i>
          <strong>Total Score:</strong>
          <span class="ms-1 text-dark"><?= number_format($result['total_score'], 2) ?>%</span>
        </div>
      </div>

      <div class="col-md-6">
        <div class="p-3 rounded" style="background-color: #c8e6c9;">
          <i class="fa-solid fa-chart-line text-primary me-2"></i>
          <strong>Average Score:</strong>
          <span class="ms-1 text-dark"><?= number_format($result['average_score'], 2) ?>%</span>
        </div>
      </div>

      <div class="col-md-6">
        <div class="p-3 rounded" style="background-color: #d1c4e9;">
          <i class="fa-solid fa-rotate-left text-info me-2"></i>
          <strong>Attempts:</strong> <?= $result['total_attempts'] ?>
        </div>
      </div>

      <div class="col-md-6">
        <div class="p-3 rounded" style="background-color: #f8bbd0;">
          <i class="fa-solid fa-ranking-star" style="color: #d81b60;"></i>
          <strong>Rank:</strong> <?= $result['rank_position'] ?: 'Not Ranked' ?>
        </div>
      </div>

    </div>

    <!-- Button -->
    <div class="text-end mt-4">
  <button class="btn btn-md text-white px-4 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2" style="background-color:#096B68;"
          data-bs-toggle="modal" data-bs-target="#resultModal"
          onclick="showResultDetails(<?= $index ?>)">
    <i class="fa-solid fa-eye"></i> View Details
  </button>
</div>

  </div>
</div>

                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="col-12">
                  <div class="alert alert-info">No results found. Start taking quizzes to see your progress!</div>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <div class="col-md-4">
            <h4>Your Stats</h4>
            <div class="report-card">
              <div class="icon-container">
                <i class="fa-solid fa-chart-pie"></i>
              </div>
              <div>
                <h5>Quizzes Added</h5>
                <p><?= count($quizzes) ?></p>
              </div>
            </div>
            <div class="report-card mt-3">
              <div class="icon-container">
                <i class="fa-solid fa-question-circle"></i>
              </div>
              <div>
                <h5>Pending Questions</h5>
                <p><?= count($pending_questions) ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add Quiz Tab -->
      <div class="tab-pane fade" id="add-quiz">
        <div class="add-quiz-form">
          <h4>Add New Quiz Question</h4>
          <form id="quizForm">
            <div class="row">
              <div class="col-4">
                <label for="categories" class="form-label">Categories</label>
                <select name="category_id" id="categories" class="form-select" required>
                  <option value="">Select Category</option>
                  <?php
                  $cats = $db->get("categories", null, ['id', 'name']);
                  foreach ($cats as $cat) {
                    echo "<option value='" . $cat['id'] . "'>" . htmlspecialchars($cat['name']) . "</option>";
                  }
                  ?>
                </select>
              </div>
              <div class="col-4">
                <label for="classes" class="form-label">Classes</label>
                <select name="class_id" id="classes" class="form-select" required>
                  <option value="">Select Class</option>
                </select>
              </div>
              <div class="col-4">
                <label for="subjects" class="form-label">Subject</label>
                <select name="subject_id" id="subjects" class="form-select" required>
                  <option value="">Select Subject</option>
                </select>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Question</label>
              <textarea class="form-control" name="question" rows="4" required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Options</label>
              <input type="text" class="form-control mb-2" name="option_a" placeholder="Option A" required>
              <input type="text" class="form-control mb-2" name="option_b" placeholder="Option B" required>
              <input type="text" class="form-control mb-2" name="option_c" placeholder="Option C" required>
              <input type="text" class="form-control mb-2" name="option_d" placeholder="Option D" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Correct Answer</label>
              <select class="form-select" name="correct_option" required>
                <option value="">Select Correct Option</option>
                <option value="a">Option A</option>
                <option value="b">Option B</option>
                <option value="c">Option C</option>
                <option value="d">Option D</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit for Approval</button>
          </form>
        </div>
      </div>

      <!-- Subscription Tab -->
      <div class="tab-pane fade" id="subscription">
        <div class="row">
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Your Subscription</h5>
                <?php if ($subscription): ?>
                  <p class="card-text">
                    <strong>Plan:</strong> <?= htmlspecialchars($subscription['plan_name']) ?><br>
                    <strong>Status:</strong> <?= ucfirst($subscription['status']) ?><br>
                    <strong>Expires:</strong> <?= $subscription['end_date'] ? date('M j, Y', strtotime($subscription['end_date'])) : 'N/A' ?>
                  </p>
                <?php else: ?>
                  <p class="card-text">No active subscription</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Events Tab -->
      <div class="tab-pane fade" id="events">
        <div class="row">
          <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
              <div class="col-md-6 mb-3">
                <div class="event-card">
                  <div class="icon-container">
                    <i class="fa-solid fa-calendar-alt"></i>
                  </div>
                  <div>
                    <h5><?= htmlspecialchars($event['name']) ?></h5>
                    <p><?= htmlspecialchars($event['description'] ?? 'No description') ?></p>
                    <small class="text-muted">Date: <?= $event['event_date'] ? date('M j, Y', strtotime($event['event_date'])) : 'No date set' ?></small>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12">
              <div class="alert alert-info">No events found</div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Edit Bio Modal -->
<div class="modal fade" id="editModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Bio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Bio</label>
          <textarea id="bioInput" class="form-control" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveBio()">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Result Details Modal -->
<div class="modal fade" id="resultModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Result Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Exam:</strong> <span id="modalExamName"></span></p>
        <p><strong>Category:</strong> <span id="modalCategory"></span></p>
        <p><strong>Class:</strong> <span id="modalClass"></span></p>
        <p><strong>Subject:</strong> <span id="modalSubject"></span></p>
        <p><strong>Level:</strong> <span id="modalLevel"></span></p>
        <p><strong>Total Score:</strong> <span id="modalTotalScore"></span>%</p>
        <p><strong>Average Score:</strong> <span id="modalAverageScore"></span>%</p>
        <p><strong>Total Attempts:</strong> <span id="modalAttempts"></span></p>
        <p><strong>Rank:</strong> <span id="modalRank"></span></p>
        <p><strong>Last Updated:</strong> <span id="modalLastUpdated"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color:#096B68">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= settings()['root'] ?>/assets/js/jquery-3.7.1.min.js"></script>
<script>
// Store leaderboard results for modal access
const leaderboardResults = <?= json_encode($leaderboard_results) ?>;

document.addEventListener("DOMContentLoaded", function() {
    // Load saved data from localStorage
    if (localStorage.getItem('profilePic')) {
        document.getElementById('profilePic').src = localStorage.getItem('profilePic');
    }
    if (localStorage.getItem('coverImage')) {
        document.getElementById('coverImage').src = localStorage.getItem('coverImage');
    }
    if (localStorage.getItem('bioText')) {
        document.getElementById('bioText').textContent = localStorage.getItem('bioText');
    }

    // Profile picture upload
    document.getElementById("profileInput").addEventListener("change", function(e) {
        if (e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById("profilePic").src = event.target.result;
                localStorage.setItem('profilePic', event.target.result);
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Cover image upload
    document.getElementById("coverInput").addEventListener("change", function(e) {
        if (e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById("coverImage").src = event.target.result;
                localStorage.setItem('coverImage', event.target.result);
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Initialize edit modal with current bio
    document.getElementById('editModal').addEventListener('show.bs.modal', function() {
        document.getElementById('bioInput').value = localStorage.getItem('bioText') || '';
    });
    
    // AJAX for dynamic dropdowns
    $("#categories").change(function() {
        var category_id = $(this).val();
        $("#classes").empty().append('<option value="">Select Class</option>');
        $.ajax({
            url: "admin/ajax/get_classes.php",
            method: "POST",
            data: { cat_id: category_id },
            dataType: "json",
            success: function(response) {
                response.data?.forEach(element => {
                    $("#classes").append('<option value="' + element.id + '">' + element.name + '</option>');
                });
            },
            error: function() {
                console.error('Failed to fetch classes');
            }
        });
    });

    $("#classes").change(function() {
        var class_id = $(this).val();
        $("#subjects").empty().append('<option value="">Select Subject</option>');
        $.ajax({
            url: "admin/ajax/get_subjects.php",
            method: "POST",
            data: { class_id: class_id },
            dataType: "json",
            success: function(response) {
                response.data?.forEach(element => {
                    $("#subjects").append('<option value="' + element.id + '">' + element.name + '</option>');
                });
            },
            error: function() {
                console.error('Failed to fetch subjects');
            }
        });
    });

    // Form submission handler for quiz question
    document.getElementById('quizForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: 'admin/ajax/submit_question.php',
        method: 'POST',
        data: $(this).serialize(),  // Use standard form data
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('Question submitted for approval!');
                document.getElementById('quizForm').reset();
                $("#classes").empty().append('<option value="">Select Class</option>');
                $("#subjects").empty().append('<option value="">Select Subject</option>');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('Failed to submit question. Please try again.');
            console.error('AJAX error:', status, error);
        }
    });
});

});

function saveBio() {
    const bio = document.getElementById('bioInput').value;
    document.getElementById('bioText').textContent = bio || 'No bio yet';
    localStorage.setItem('bioText', bio);
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
    modal.hide();
}

function showResultDetails(index) {
    const result = leaderboardResults[index];
    document.getElementById('modalExamName').textContent = result.exam_name || 'General Quiz';
    document.getElementById('modalCategory').textContent = result.category_name || 'N/A';
    document.getElementById('modalClass').textContent = result.class_name || 'N/A';
    document.getElementById('modalSubject').textContent = result.subject_name || 'N/A';
    document.getElementById('modalLevel').textContent = result.level || 'N/A';
    document.getElementById('modalTotalScore').textContent = Number(result.total_score).toFixed(2);
    document.getElementById('modalAverageScore').textContent = Number(result.average_score).toFixed(2);
    document.getElementById('modalAttempts').textContent = result.total_attempts;
    document.getElementById('modalRank').textContent = result.rank_position || 'Not Ranked';
    document.getElementById('modalLastUpdated').textContent = new Date(result.last_updated).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
</script>

</body>
</html>