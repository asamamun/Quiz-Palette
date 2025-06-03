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
  .add-content-container {
    max-width: 1200px;
    margin: 120px auto 80px auto; /* Increased top margin to prevent overlap with back button */
    padding: 20px;
    position: relative;
  }
  .back-button {
    position: absolute;
    top: 20px;
    left: 20px;
    z-index: 1000;
    background-color: #3b82f6; /* Theme primary color */
    border-color: #3b82f6;
    color: white;
  }
  .back-button:hover {
    background-color: #1e40af; /* Theme hover color */
    border-color: #1e40af;
  }
  .add-content-card {
    border: 1px solid #e0e7ff;
    border-radius: 8px;
    background-color: #f8fafc;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  .add-content-card .card-header {
    background-color: #3b82f6;
    color: white;
    border-radius: 8px 8px 0 0;
    padding: 15px;
    font-weight: 600;
  }
  .add-content-card .card-body {
    padding: 20px;
  }
  .add-content-card .form-label {
    font-weight: 500;
    color: #1e3a8a;
  }
  .add-content-card .form-control,
  .add-content-card .form-select {
    border-color: #bfdbfe;
  }
  .add-content-card .btn-primary {
    background-color: #2563eb;
    border-color: #2563eb;
  }
  .add-content-card .btn-primary:hover {
    background-color: #1e40af;
    border-color: #1e40af;
  }
  .request-card {
    border: 1px solid #e0e7ff;
    border-radius: 8px;
    background-color: #ffffff;
    padding: 15px;
    margin-bottom: 15px;
  }
  .request-card .icon-container {
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
  .request-card .icon-container i {
    color: #1e3a8a;
  }
  .request-card h5 {
    margin: 0;
    color: #1e3a8a;
  }
  .request-card p {
    margin: 5px 0;
    color: #4b5563;
  }
  .request-card .timestamp,
  .request-card .status {
    font-size: 0.9em;
    color: #6b7280;
  }
</style>

<div class="add-content-container">
  <button class="btn back-button" onclick="history.back()">
    <i class="fas fa-arrow-left me-2"></i> Back
  </button>

  <ul class="nav nav-tabs mb-4" id="addContentTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="add-category-tab" data-bs-toggle="tab" data-bs-target="#add-category" type="button" role="tab">
        <i class="fas fa-book me-2"></i> Add Category
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="add-quiz-tab" data-bs-toggle="tab" data-bs-target="#add-quiz" type="button" role="tab">
        <i class="fas fa-question-circle me-2"></i> Add Quiz
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="add-event-tab" data-bs-toggle="tab" data-bs-target="#add-event" type="button" role="tab">
        <i class="fas fa-calendar-alt me-2"></i> Add Event
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="add-subject-tab" data-bs-toggle="tab" data-bs-target="#add-subject" type="button" role="tab">
        <i class="fas fa-graduation-cap me-2"></i> Add Subject
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="add-class-tab" data-bs-toggle="tab" data-bs-target="#add-class" type="button" role="tab">
        <i class="fas fa-chalkboard-teacher me-2"></i> Add Class
      </button>
    </li>
  </ul>

  <div class="tab-content">
    <!-- Add Category -->
    <div class="tab-pane fade show active" id="add-category" role="tabpanel">
      <div class="add-content-card">
        <div class="card-header">Submit New Category</div>
        <div class="card-body">
          <div class="mb-3">
            <label for="categoryTitle" class="form-label">Category Title</label>
            <input type="text" id="categoryTitle" class="form-control" placeholder="Enter category title">
          </div>
          <div class="mb-3">
            <label for="categoryDescription" class="form-label">Description</label>
            <textarea id="categoryDescription" class="form-control" placeholder="Enter description"></textarea>
          </div>
          <button type="button" class="btn btn-primary" onclick="submitRequest('Category')">Submit Category</button>
        </div>
      </div>
    </div>

    <!-- Add Quiz -->
    <div class="tab-pane fade" id="add-quiz" role="tabpanel">
      <div class="add-content-card">
        <div class="card-header">Submit New Quiz</div>
        <div class="card-body">
          <div class="mb-3">
            <label for="quizTitle" class="form-label">Quiz Title</label>
            <input type="text" id="quizTitle" class="form-control" placeholder="Enter quiz title">
          </div>
          <div class="mb-3">
            <label for="quizQuestion" class="form-label">Question</label>
            <input type="text" id="quizQuestion" class="form-control" placeholder="Enter question">
          </div>
          <div class="mb-3">
            <label for="quizOption1" class="form-label">Option 1</label>
            <input type="text" id="quizOption1" class="form-control" placeholder="Enter option 1">
          </div>
          <div class="mb-3">
            <label for="quizOption2" class="form-label">Option 2</label>
            <input type="text" id="quizOption2" class="form-control" placeholder="Enter option 2">
          </div>
          <div class="mb-3">
            <label for="quizOption3" class="form-label">Option 3</label>
            <input type="text" id="quizOption3" class="form-control" placeholder="Enter option 3">
          </div>
          <div class="mb-3">
            <label for="quizOption4" class="form-label">Option 4</label>
            <input type="text" id="quizOption4" class="form-control" placeholder="Enter option 4">
          </div>
          <div class="mb-3">
            <label for="quizCorrectAnswer" class="form-label">Correct Answer</label>
            <select id="quizCorrectAnswer" class="form-select">
              <option value="1">Option 1</option>
              <option value="2">Option 2</option>
              <option value="3">Option 3</option>
              <option value="4">Option 4</option>
            </select>
          </div>
          <button type="button" class="btn btn-primary" onclick="submitRequest('Quiz')">Submit Quiz</button>
        </div>
      </div>
    </div>

    <!-- Add Event -->
    <div class="tab-pane fade" id="add-event" role="tabpanel">
      <div class="add-content-card">
        <div class="card-header">Submit New Event</div>
        <div class="card-body">
          <form id="eventForm" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="eventTitle" class="form-label">Event Title</label>
              <input type="text" id="eventTitle" name="eventTitle" class="form-control" placeholder="Enter event title">
            </div>
            <div class="mb-3">
              <label for="eventDescription" class="form-label">Description</label>
              <textarea id="eventDescription" name="eventDescription" class="form-control" placeholder="Enter description"></textarea>
            </div>
            <div class="mb-3">
              <label for="eventDate" class="form-label">Event Date</label>
              <input type="date" id="eventDate" name="eventDate" class="form-control">
            </div>
            <div class="mb-3">
              <label for="eventType" class="form-label">Event Type</label>
              <select id="eventType" name="eventType" class="form-select">
                <option value="Contest">Contest</option>
                <option value="Quiz Competition">Quiz Competition</option>
                <option value="Workshop">Workshop</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="eventImage" class="form-label">Event Image</label>
              <input type="file" id="eventImage" name="eventImage" class="form-control" accept="image/*">
            </div>
            <button type="button" class="btn btn-primary" onclick="submitRequest('Event')">Submit Event</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Add Subject -->
    <div class="tab-pane fade" id="add-subject" role="tabpanel">
      <div class="add-content-card">
        <div class="card-header">Submit New Subject</div>
        <div class="card-body">
          <div class="mb-3">
            <label for="subjectTitle" class="form-label">Subject Title</label>
            <input type="text" id="subjectTitle" class="form-control" placeholder="Enter subject title">
          </div>
          <div class="mb-3">
            <label for="subjectDescription" class="form-label">Description</label>
            <textarea id="subjectDescription" class="form-control" placeholder="Enter description"></textarea>
          </div>
          <button type="button" class="btn btn-primary" onclick="submitRequest('Subject')">Submit Subject</button>
        </div>
      </div>
    </div>

    <!-- Add Class -->
    <div class="tab-pane fade" id="add-class" role="tabpanel">
      <div class="add-content-card">
        <div class="card-header">Submit New Class</div>
        <div class="card-body">
          <div class="mb-3">
            <label for="classTitle" class="form-label">Class Title</label>
            <input type="text" id="classTitle" class="form-control" placeholder="Enter class title">
          </div>
          <div class="mb-3">
            <label for="classDescription" class="form-label">Description</label>
            <textarea id="classDescription" class="form-control" placeholder="Enter description"></textarea>
          </div>
          <div class="mb-3">
            <label for="classSchedule" class="form-label">Schedule</label>
            <input type="text" id="classSchedule" class="form-control" placeholder="Enter schedule (e.g., Mon/Wed 2-4 PM)">
          </div>
          <button type="button" class="btn btn-primary" onclick="submitRequest('Class')">Submit Class</button>
        </div>
      </div>
    </div>

    <!-- Submitted Requests -->
    <div class="mt-4">
      <h4>Submitted Requests</h4>
      <div id="requestResultsContainer"></div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Load submitted requests from localStorage
    const requestResults = JSON.parse(localStorage.getItem("requestResults")) || [];
    updateRequestResults(requestResults);
  });

  function submitRequest(type) {
    let requestData = {
      type,
      timestamp: new Date().toLocaleString(),
      status: "Pending"
    };

    // Collect data based on request type
    if (type === "Category") {
      requestData.title = document.getElementById("categoryTitle").value;
      requestData.description = document.getElementById("categoryDescription").value;
      if (!requestData.title || !requestData.description) {
        alert("Please fill in both title and description.");
        return;
      }
    } else if (type === "Quiz") {
      requestData.title = document.getElementById("quizTitle").value;
      requestData.question = document.getElementById("quizQuestion").value;
      requestData.option1 = document.getElementById("quizOption1").value;
      requestData.option2 = document.getElementById("quizOption2").value;
      requestData.option3 = document.getElementById("quizOption3").value;
      requestData.option4 = document.getElementById("quizOption4").value;
      requestData.correctAnswer = document.getElementById("quizCorrectAnswer").value;
      if (!requestData.title || !requestData.question || !requestData.option1 || 
          !requestData.option2 || !requestData.option3 || !requestData.option4) {
        alert("Please fill in all quiz fields.");
        return;
      }
    } else if (type === "Event") {
      const form = document.getElementById("eventForm");
      const formData = new FormData(form);
      requestData.title = document.getElementById("eventTitle").value;
      requestData.description = document.getElementById("eventDescription").value;
      requestData.date = document.getElementById("eventDate").value;
      requestData.eventType = document.getElementById("eventType").value;
      const imageFile = document.getElementById("eventImage").files[0];
      
      if (!requestData.title || !requestData.description || !requestData.date) {
        alert("Please fill in all required event fields.");
        return;
      }

      if (imageFile) {
        // In a real application, handle file upload to server
        formData.append('type', 'Event');
        fetch('/api/upload-event-image', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            requestData.imagePath = data.imagePath;
            saveRequest(requestData);
          } else {
            alert("Image upload failed: " + data.message);
          }
        })
        .catch(error => {
          console.error('Error uploading image:', error);
          alert("An error occurred while uploading the image.");
        });
        return; // Exit to wait for async image upload
      } else {
        requestData.imagePath = null;
      }
    } else if (type === "Subject") {
      requestData.title = document.getElementById("subjectTitle").value;
      requestData.description = document.getElementById("subjectDescription").value;
      if (!requestData.title || !requestData.description) {
        alert("Please fill in both title and description.");
        return;
      }
    } else if (type === "Class") {
      requestData.title = document.getElementById("classTitle").value;
      requestData.description = document.getElementById("classDescription").value;
      requestData.schedule = document.getElementById("classSchedule").value;
      if (!requestData.title || !requestData.description || !requestData.schedule) {
        alert("Please fill in all class fields.");
        return;
      }
    }

    saveRequest(requestData);
  }

  function saveRequest(requestData) {
    // Save to localStorage
    const requestResults = JSON.parse(localStorage.getItem("requestResults")) || [];
    requestResults.push(requestData);
    localStorage.setItem("requestResults", JSON.stringify(requestResults));

    // Update UI
    updateRequestResults(requestResults);

    // Clear form
    clearForm(requestData.type);
  }

  function updateRequestResults(requests) {
    const container = document.getElementById("requestResultsContainer");
    container.innerHTML = "";
    if (requests.length === 0) {
      container.innerHTML = `
        <div class="request-card">
          <div class="icon-container"><i class="fas fa-info-circle"></i></div>
          <h5>No Requests Submitted</h5>
          <p>Submit a request to see it listed here.</p>
        </div>
      `;
      return;
    }

    requests.forEach(request => {
      let content = `
        <div class="request-card">
          <div class="icon-container"><i class="fas fa-${request.type === 'Category' ? 'book' : request.type === 'Quiz' ? 'question-circle' : request.type === 'Event' ? 'calendar-alt' : request.type === 'Subject' ? 'graduation-cap' : 'chalkboard-teacher'}"></i></div>
          <h5>${request.type}: ${request.title}</h5>
          <p>${request.description || ''}</p>
      `;
      if (request.type === "Quiz") {
        content += `
          <p><strong>Question:</strong> ${request.question}</p>
          <p><strong>Options:</strong> 1. ${request.option1}, 2. ${request.option2}, 3. ${request.option3}, 4. ${request.option4}</p>
          <p><strong>Correct Answer:</strong> Option ${request.correctAnswer}</p>
        `;
      } else if (request.type === "Event") {
        content += `
          <p><strong>Date:</strong> ${request.date}</p>
          <p><strong>Type:</strong> ${request.eventType}</p>
        `;
        if (request.imagePath) {
          content += `<p><strong>Image:</strong> ${request.imagePath}</p>`;
        }
      } else if (request.type === "Class") {
        content += `
          <p><strong>Schedule:</strong> ${request.schedule}</p>
        `;
      }
      content += `
          <div class="timestamp">Submitted: ${request.timestamp}</div>
          <div class="status">Status: ${request.status}</div>
        </div>
      `;
      container.innerHTML += content;
    });
  }

  function clearForm(type) {
    if (type === "Category") {
      document.getElementById("categoryTitle").value = "";
      document.getElementById("categoryDescription").value = "";
    } else if (type === "Quiz") {
      document.getElementById("quizTitle").value = "";
      document.getElementById("quizQuestion").value = "";
      document.getElementById("quizOption1").value = "";
      document.getElementById("quizOption2").value = "";
      document.getElementById("quizOption3").value = "";
      document.getElementById("quizOption4").value = "";
      document.getElementById("quizCorrectAnswer").value = "1";
    } else if (type === "Event") {
      document.getElementById("eventTitle").value = "";
      document.getElementById("eventDescription").value = "";
      document.getElementById("eventDate").value = "";
      document.getElementById("eventType").value = "Contest";
      document.getElementById("eventImage").value = "";
    } else if (type === "Subject") {
      document.getElementById("subjectTitle").value = "";
      document.getElementById("subjectDescription").value = "";
    } else if (type === "Class") {
      document.getElementById("classTitle").value = "";
      document.getElementById("classDescription").value = "";
      document.getElementById("classSchedule").value = "";
    }
  }
</script>
</body>
</html>