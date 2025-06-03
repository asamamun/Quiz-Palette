<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require __DIR__."/vendor/autoload.php";
$db = new MysqliDb();
$userid = $_SESSION['user_id'];
$userinfo = $db->where('id', $userid)->getOne('users', array('id', 'username', 'email','first_name', 'last_name','phone', 'avatar'));
require 'includes/header.php';
?>

<style>
  .event-card {
    border: 1px solid #e0e7ff;
    border-radius: 8px;
    background-color: #f8fafc;
    padding: 15px;
    margin-bottom: 15px;
    transition: background-color 0.3s;
    cursor: pointer;
  }
  .event-card:hover {
    background-color: #dbeafe;
  }
  .event-card .icon-container {
    width: 40px;
    height: 40px;
    background-color: #dbeafe;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    float: left;
  }
  .event-card .icon-container i {
    color: #1e3a8a;
  }
  .event-card h5 {
    margin: 0;
    color: #1e3a8a;
  }
  .event-card p {
    margin: 5px 0;
    color: #4b5563;
  }
  .event-card .timestamp,
  .event-card .status {
    font-size: 0.9em;
    color: #6b7280;
  }
  .event-ticker {
    position: relative;
    max-height: 200px;
    overflow: hidden;
    margin-top: 20px;
    padding: 10px;
    background-color: #f8fafc;
    border: 1px solid #e0e7ff;
    border-radius: 8px;
  }
  .event-ticker-item {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #e0e7ff;
    transition: transform 0.5s linear, opacity 0.5s linear;
    opacity: 1;
  }
  .event-ticker-item.hidden {
    opacity: 0;
  }
  .event-ticker-item:hover {
    background-color: #dbeafe;
  }
  .event-ticker-item:last-child {
    border-bottom: none;
  }
  .event-ticker-item h6 {
    margin: 0;
    color: #1e3a8a;
    font-size: 1em;
  }
  .event-ticker-item p {
    margin: 5px 0 0;
    font-size: 0.85em;
    color: #4b5563;
  }
  .highlight {
    background-color: #bfdbfe !important;
  }
  .event-modal .modal-content {
    border-radius: 8px;
    background-color: #f8fafc;
  }
  .event-modal .modal-header {
    background-color: #3b82f6;
    color: white;
    border-radius: 8px 8px 0 0;
  }
  .event-modal .btn-primary {
    background-color: #2563eb;
    border-color: #2563eb;
  }
  .event-modal .btn-primary:hover {
    background-color: #1e40af;
    border-color: #1e40af;
  }
  .event-modal .btn-secondary {
    background-color: #6b7280;
    border-color: #6b7280;
  }
  .event-modal .btn-secondary:hover {
    background-color: #4b5563;
    border-color: #4b5563;
  }
  .event-modal .modal-backdrop {
    background-color: rgba(59, 130, 246, 0.5);
    backdrop-filter: blur(5px);
  }
  .event-buttons .btn {
    margin-right: 10px;
    margin-bottom: 10px;
  }
  .event-buttons .counter {
    font-size: 0.9em;
    color: #1e3a8a;
    margin-left: 5px;
  }
  .create-event-btn {
    background-color: #2563eb;
    border-color: #2563eb;
    color: white;
    margin-top: 15px;
    float: right;
  }
  .create-event-btn:hover {
    background-color: #1e40af;
    border-color: #1e40af;
  }
  .report-card {
    border: 1px solid #e0e7ff;
    border-radius: 8px;
    background-color: #f8fafc;
    padding: 15px;
    margin-bottom: 15px;
  }
  .report-card .icon-container {
    width: 40px;
    height: 40px;
    background-color: #dbeafe;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    float: left;
  }
  .report-card .icon-container i {
    color: #1e3a8a;
  }
  .report-card h5 {
    margin: 0;
    color: #1e3a8a;
  }
  .report-card p {
    margin: 5px 0;
    color: #4b5563;
  }
</style>

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
    <div class="event-ticker" id="eventTicker">
      <h6>Latest Events</h6>
      <!-- Event ticker items will be dynamically inserted here -->
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
      <li class="nav-item menu-item" role="presentation">
        <button class="nav-link menu-link active" id="tab-a" data-bs-toggle="tab" data-bs-target="#content-a" type="button" role="tab">
          <span class="icon fa-solid fa-book"></span> Overview
        </button>
      </li>
      <li class="nav-item menu-item" role="presentation">
        <button class="nav-link menu-link" id="tab-b" data-bs-toggle="tab" data-bs-target="#content-b" type="button" role="tab">
          <span class="icon fa-solid fa-credit-card"></span> Subscription
        </button>
      </li>
      <li class="nav-item menu-item" role="presentation">
        <button class="nav-link menu-link" id="tab-c" data-bs-toggle="tab" data-bs-target="#content-c" type="button" role="tab">
          <span class="icon fa-solid fa-chart-line"></span> Report
        </button>
      </li>
      <li class="nav-item menu-item" role="presentation">
        <button class="nav-link menu-link" id="tab-d" data-bs-toggle="tab" data-bs-target="#content-d" type="button" role="tab">
          <span class="icon fa-solid fa-calendar-alt"></span> Events
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
                <i class="icon-img fa-solid fa-book"></i>
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
                <i class="icon-img fa-solid fa-credit-card"></i>
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
                <i class="icon-img fa-solid fa-wallet"></i>
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
        <div class="card-content row" id="reportContainer">
          <!-- Report stats will be dynamically inserted here -->
        </div>
      </div>
      <div class="tab-pane fade" id="content-d" role="tabpanel">
        <div class="card-content row" id="eventsContainer">
          <!-- Events will be dynamically inserted here -->
        </div>
        <a href="add.php" class="btn create-event-btn">Create New Event</a>
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

<!-- Event Details Modal -->
<div class="modal fade event-modal" id="eventModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalTitle"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Description:</strong> <span id="eventModalDescription"></span></p>
        <p><strong>Date:</strong> <span id="eventModalDate"></span></p>
        <p><strong>Type:</strong> <span id="eventModalType"></span></p>
        <p><strong>Status:</strong> <span id="eventModalStatus"></span></p>
        <p><strong>Posted:</strong> <span id="eventModalTimestamp"></span></p>
        <div class="event-buttons">
          <button type="button" class="btn btn-primary" onclick="updateEventStatus('going')">
            Going <span id="goingCounter" class="counter"></span>
          </button>
          <button type="button" class="btn btn-secondary" onclick="updateEventStatus('interested')">
            Interested <span id="interestedCounter" class="counter"></span>
          </button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    const userId = "<?= $userid ?>"; // Get userId from PHP session
    const requests = JSON.parse(localStorage.getItem("requestResults")) || [];
    let events = JSON.parse(localStorage.getItem("events")) || [
      {
        id: "1",
        title: "Math Quiz Competition",
        description: "Join our annual math quiz competition!",
        date: "2025-06-05",
        status: "Upcoming",
        type: "Quiz Competition",
        timestamp: "2025-06-01 09:00:00",
        going: 0,
        interested: 0,
        userId: userId // Sample event assigned to user
      },
      {
        id: "2",
        title: "Science Fair",
        description: "Showcase your science projects.",
        date: "2025-06-01",
        status: "Ongoing",
        type: "Contest",
        timestamp: "2025-05-30 10:00:00",
        going: 0,
        interested: 0,
        userId: "other_user" // Sample event by another user
      }
    ];

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
              <i class="fa-solid fa-book"></i>
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
            <i class="fa-solid fa-book"></i>
          </div>
          <div class="result-summary">
            <h5>No Quiz Results</h5>
            <p>Complete a quiz to see your results here.</p>
          </div>
        </div>
      `;
      quizResultsContainer.insertBefore(noResultsCard, quizResultsContainer.firstChild);
    }

    // Load events into cards
    const eventsContainer = document.getElementById("eventsContainer");
    if (events.length > 0) {
      events.forEach(event => {
        const eventCard = document.createElement("div");
        eventCard.className = "col-md-6 mb-3";
        eventCard.id = `event-${event.id}`;
        eventCard.innerHTML = `
          <div class="event-card" data-bs-toggle="modal" data-bs-target="#eventModal" data-event-id="${event.id}">
            <div class="icon-container">
              <i class="fa-solid fa-calendar-alt"></i>
            </div>
            <div class="result-summary">
              <h5>${event.title}</h5>
              <p>${event.description}</p>
              <p><strong>Date:</strong> ${event.date}</p>
              <p><strong>Type:</strong> ${event.type}</p>
              <p><strong>Going:</strong> <span id="going-${event.id}">${event.going || 0}</span></p>
              <p><strong>Interested:</strong> <span id="interested-${event.id}">${event.interested || 0}</span></p>
              <div class="status">Status: ${event.status}</div>
              <div class="timestamp">Posted: ${event.timestamp}</div>
            </div>
          </div>
        `;
        eventCard.addEventListener("click", () => showEventModal(event));
        eventsContainer.appendChild(eventCard);
      });
    } else {
      const noEventsCard = document.createElement("div");
      noEventsCard.className = "col-md-6 mb-3";
      noEventsCard.innerHTML = `
        <div class="event-card">
          <div class="icon-container">
            <i class="fa-solid fa-calendar-alt"></i>
          </div>
          <div class="result-summary">
            <h5>No Events</h5>
            <p>No ongoing or upcoming events at the moment.</p>
          </div>
        </div>
      `;
      eventsContainer.appendChild(noEventsCard);
    }

    // Load event ticker
    const eventTicker = document.getElementById("eventTicker");
    if (events.length > 0) {
      events.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp)); // Sort by latest
      events.slice(0, 3).forEach(event => {
        const tickerItem = document.createElement("div");
        tickerItem.className = "event-ticker-item";
        tickerItem.dataset.eventId = event.id;
        tickerItem.innerHTML = `
          <h6>${event.title}</h6>
          <p>${event.status} - ${event.date}</p>
        `;
        tickerItem.addEventListener("click", () => {
          const tab = new bootstrap.Tab(document.getElementById("tab-d"));
          tab.show();
          const eventCard = document.getElementById(`event-${event.id}`);
          if (eventCard) {
            eventCard.classList.add("highlight");
            setTimeout(() => eventCard.classList.remove("highlight"), 2000);
            eventCard.scrollIntoView({ behavior: "smooth" });
            showEventModal(event);
          }
        });
        eventTicker.appendChild(tickerItem);
      });
    } else {
      eventTicker.innerHTML += `
        <div class="event-ticker-item">
          <h6>No Events</h6>
          <p>Check back for upcoming events!</p>
        </div>
      `;
    }

    // Animate ticker
    if (events.length > 1) {
      let currentIndex = 0;
      const tickerItems = document.querySelectorAll(".event-ticker-item");
      setInterval(() => {
        tickerItems.forEach(item => {
          item.classList.add("hidden");
          setTimeout(() => {
            item.style.transform = `translateY(-${currentIndex * 60}px)`;
            item.classList.remove("hidden");
          }, 500);
        });
        currentIndex = (currentIndex + 1) % tickerItems.length;
      }, 4000); // Smooth transition
    }

    // Load report stats
    const reportContainer = document.getElementById("reportContainer");
    const userRequests = requests.filter(req => req.userId === userId);
    const userEvents = events.filter(event => event.userId === userId);
    const goingEvents = events.filter(event => (event.goingUsers || []).includes(userId));
    const interestedEvents = events.filter(event => (event.interestedUsers || []).includes(userId));

    const stats = [
      { title: "Quizzes Added", count: userRequests.filter(req => req.type === "Quiz").length, icon: "fa-question-circle" },
      { title: "Categories Added", count: userRequests.filter(req => req.type === "Category").length, icon: "fa-book" },
      { title: "Subjects Added", count: userRequests.filter(req => req.type === "Subject").length, icon: "fa-graduation-cap" },
      { title: "Events Added", count: userEvents.length, icon: "fa-calendar-alt" },
      { title: "Events Going", count: goingEvents.length, icon: "fa-check-circle" },
      { title: "Events Interested", count: interestedEvents.length, icon: "fa-star" }
    ];

    stats.forEach(stat => {
      const statCard = document.createElement("div");
      statCard.className = "col-md-4 mb-3";
      statCard.innerHTML = `
        <div class="report-card">
          <div class="icon-container">
            <i class="fa-solid ${stat.icon}"></i>
          </div>
          <div class="result-summary">
            <h5>${stat.title}</h5>
            <p>${stat.count}</p>
          </div>
        </div>
      `;
      reportContainer.appendChild(statCard);
    });

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

    // Example: Fetch events and requests from server
    // fetch('/api/events', {
    //   method: 'GET',
    //   headers: { 'Content-Type': 'application/json' }
    // }).then(response => response.json()).then(data => {
    //   localStorage.setItem("events", JSON.stringify(data));
    // });
    // fetch('/api/requests', {
    //   method: 'GET',
    //   headers: { 'Content-Type': 'application/json' }
    // }).then(response => response.json()).then(data => {
    //   localStorage.setItem("requestResults", JSON.stringify(data));
    // });
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

  function showEventModal(event) {
    const modal = document.getElementById("eventModal");
    modal.dataset.eventId = event.id;
    document.getElementById("eventModalTitle").textContent = event.title;
    document.getElementById("eventModalDescription").textContent = event.description;
    document.getElementById("eventModalDate").textContent = event.date;
    document.getElementById("eventModalType").textContent = event.type;
    document.getElementById("eventModalStatus").textContent = event.status;
    document.getElementById("eventModalTimestamp").textContent = event.timestamp;
    document.getElementById("goingCounter").textContent = event.going || 0;
    document.getElementById("interestedCounter").textContent = event.interested || 0;
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
  }

  function updateEventStatus(status) {
    const eventId = document.getElementById("eventModal").dataset.eventId;
    const userId = "<?= $userid ?>";
    const events = JSON.parse(localStorage.getItem("events")) || [];
    const event = events.find(e => e.id === eventId);
    if (event) {
      if (status === "going") {
        event.going = (event.going || 0) + 1;
        event.goingUsers = event.goingUsers || [];
        if (!event.goingUsers.includes(userId)) event.goingUsers.push(userId);
      } else if (status === "interested") {
        event.interested = (event.interested || 0) + 1;
        event.interestedUsers = event.interestedUsers || [];
        if (!event.interestedUsers.includes(userId)) event.interestedUsers.push(userId);
      }
      localStorage.setItem("events", JSON.stringify(events));
      document.getElementById(`${status}-${eventId}`).textContent = event[status];
      document.getElementById(`${status}Counter`).textContent = event[status];
      // Update report stats
      updateReportStats();
      // In a real application, update server
      // fetch('/api/update-event-status', {
      //   method: 'POST',
      //   headers: { 'Content-Type': 'application/json' },
      //   body: JSON.stringify({ eventId, status, userId })
      // }).then(response => response.json()).then(data => console.log(data));
    }
  }

  function updateReportStats() {
    const userId = "<?= $userid ?>";
    const reportContainer = document.getElementById("reportContainer");
    const requests = JSON.parse(localStorage.getItem("requestResults")) || [];
    const events = JSON.parse(localStorage.getItem("events")) || [];
    const userRequests = requests.filter(req => req.userId === userId);
    const userEvents = events.filter(event => event.userId === userId);
    const goingEvents = events.filter(event => (event.goingUsers || []).includes(userId));
    const interestedEvents = events.filter(event => (event.interestedUsers || []).includes(userId));

    const stats = [
      { title: "Quizzes Added", count: userRequests.filter(req => req.type === "Quiz").length, icon: "fa-question-circle" },
      { title: "Categories Added", count: userRequests.filter(req => req.type === "Category").length, icon: "fa-book" },
      { title: "Subjects Added", count: userRequests.filter(req => req.type === "Subject").length, icon: "fa-graduation-cap" },
      { title: "Events Added", count: userEvents.length, icon: "fa-calendar-alt" },
      { title: "Events Going", count: goingEvents.length, icon: "fa-check-circle" },
      { title: "Events Interested", count: interestedEvents.length, icon: "fa-star" }
    ];

    reportContainer.innerHTML = "";
    stats.forEach(stat => {
      const statCard = document.createElement("div");
      statCard.className = "col-md-4 mb-3";
      statCard.innerHTML = `
        <div class="report-card">
          <div class="icon-container">
            <i class="fa-solid ${stat.icon}"></i>
          </div>
          <div class="result-summary">
            <h5>${stat.title}</h5>
            <p>${stat.count}</p>
          </div>
        </div>
      `;
      reportContainer.appendChild(statCard);
    });
  }
</script>
</body>
</html>