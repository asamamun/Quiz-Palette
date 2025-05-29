<?php
require 'includes/header.php';



// // Fetch categories
// $result = $conn->query("SELECT * FROM categories");
// $categories = [];
// while ($row = $result->fetch_assoc()) {
//     $categories[] = $row;
// }
// $result->free();

// // Fetch event quizzes
// $result = $conn->query("SELECT id, title, event_start_datetime FROM quizzes WHERE is_event_based = 1");
// $event_quizzes = [];
// while ($row = $result->fetch_assoc()) {
//     $event_quizzes[] = $row;
// }
// $result->free();
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="assets/css/leaderboard.css">


<div class="container d-flex flex-column justify-content-center" style="min-height: 60vh;">

    <h1 class="text-center mb-4">Leaderboard</h1>


        <!-- Tabs for Leaderboard Types -->
        <ul class="nav nav-tabs mb-4" id="leaderboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="global-tab" data-bs-toggle="tab" data-type="global" type="button">Global</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="category-tab" data-bs-toggle="tab" data-type="category" type="button">Category</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="class-tab" data-bs-toggle="tab" data-type="class" type="button">Class</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="subject-tab" data-bs-toggle="tab" data-type="subject" type="button">Subject</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="event-tab" data-bs-toggle="tab" data-type="event" type="button">Event</button>
            </li>
        </ul>

        <!-- Filters for Category, Class, Subject, Event -->
        <div class="row mb-4" id="filters" style="display: none;">
            <div class="col-md-4">
                <select class="form-select" id="categoryFilter" style="display: none;">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="classFilter" style="display: none;">
                    <option value="">Select Class</option>
                    <!-- Populated via AJAX -->
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="subjectFilter" style="display: none;">
                    <option value="">Select Subject</option>
                    <!-- Populated via AJAX -->
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="eventFilter" style="display: none;">
                    <option value="">Select Event Quiz</option>
                    <?php foreach ($event_quizzes as $quiz): ?>
                        <option value="<?php echo $quiz['id']; ?>">
                            <?php echo htmlspecialchars($quiz['title'] . ' (' . $quiz['event_start_datetime'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Leaderboard Table -->
        <div class="table-responsive">
            <table class="table table-striped leaderboard-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>User</th>
                        <th>Score</th>
                        <th>Badges</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody id="leaderboardBody">
                    <!-- Populated via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="assets/js/leaderboard.js"></script>
</body>
</html>

<?php
// $conn->close();
?>