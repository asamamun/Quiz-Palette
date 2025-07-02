<?php
require_once 'db.php';
require_once 'includes/header.php';

// Fetch categories
$categories_query = "SELECT id, name FROM categories WHERE status = 'active' ORDER BY name";
$categories_result = $conn->query($categories_query);
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch events
$events_query = "SELECT id, name, event_date FROM events WHERE status = 'active' ORDER BY event_date DESC";
$events_result = $conn->query($events_query);
$event_quizzes = [];
while ($row = $events_result->fetch_assoc()) {
    $event_quizzes[] = [
        'id' => $row['id'],
        'title' => $row['name'],
        'event_start_datetime' => $row['event_date'] ? $row['event_date'] : 'N/A'
    ];
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - QuizPallete</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/leaderboard.css">
</head>
<body>
    <div class="container my-5 mt-5 pt-5">
        <h1 class="text-center mb-4" style="color: #129990;">QuizPallete Leaderboard</h1>
        <p class="text-center text-muted mb-4">Compete with others and see where you stand!</p>

        <!-- Tabs for Leaderboard Types -->
        <ul class="nav nav-tabs mb-4 justify-content-center" id="leaderboardTabs" role="tablist">
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

        <!-- Filters -->
        <div class="row mb-4" id="filters" style="display: none;">
            <div class="col-md-3">
                <label for="categoryFilter" class="form-label">Category</label>
                <select class="form-select" id="categoryFilter" style="display: none;">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="classFilter" class="form-label">Class</label>
                <select class="form-select" id="classFilter" style="display: none;">
                    <option value="">All Classes</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="subjectFilter" class="form-label">Subject</label>
                <select class="form-select" id="subjectFilter" style="display: none;">
                    <option value="">All Subjects</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="eventFilter" class="form-label">Event</label>
                <select class="form-select" id="eventFilter" style="display: none;">
                    <option value="">All Events</option>
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
                        <th scope="col">Rank</th>
                        <th scope="col">User</th>
                        <th scope="col">Score</th>
                        <th scope="col">Progress</th>
                        <th scope="col">Badges</th>
                        <th scope="col">Details</th>
                    </tr>
                </thead>
                <tbody id="leaderboardBody">
                    <!-- Populated via AJAX -->
                </tbody>
            </table>
        </div>

        <!-- User Details Modal -->
        <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="userDetailsContent">
                            <!-- Populated via AJAX -->
                            <div class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
$(document).ready(function () {
    // Show/hide filters based on tab selection
    $('#leaderboardTabs .nav-link').on('click', function () {
        const type = $(this).data('type');
        $('#filters').hide();
        $('#categoryFilter, #classFilter, #subjectFilter, #eventFilter').hide();
        // Reset filters
        $('#categoryFilter').val('');
        $('#classFilter').empty().append('<option value="">All Classes</option>');
        $('#subjectFilter').empty().append('<option value="">All Subjects</option>');
        $('#eventFilter').val('');

        if (type === 'category') {
            $('#filters').show();
            $('#categoryFilter').show();
        } else if (type === 'class') {
            $('#filters').show();
            $('#categoryFilter, #classFilter').show();
        } else if (type === 'subject') {
            $('#filters').show();
            $('#categoryFilter, #classFilter, #subjectFilter').show();
        } else if (type === 'event') {
            $('#filters').show();
            $('#eventFilter').show();
        }

        loadLeaderboard(type);
    });

    // Populate classes when category changes
    $('#categoryFilter').on('change', function () {
        const categoryId = $(this).val();
        console.log('Category selected:', categoryId);
        if (categoryId) {
            $.get('api/leaderboard.php?action=get_classes&category_id=' + categoryId, function (data) {
                console.log('Classes received:', data);
                $('#classFilter').empty().append('<option value="">All Classes</option>');
                data.forEach(function (cls) {
                    $('#classFilter').append(`<option value="${cls.id}">${cls.name}</option>`);
                });
                $('#subjectFilter').empty().append('<option value="">All Subjects</option>');
                loadLeaderboard('category');
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.error('Error fetching classes:', textStatus, errorThrown);
                $('#leaderboardBody').empty().append('<tr><td colspan="6">Error loading classes</td></tr>');
            });
        } else {
            $('#classFilter').empty().append('<option value="">All Classes</option>');
            $('#subjectFilter').empty().append('<option value="">All Subjects</option>');
            loadLeaderboard('category');
        }
    });

    // Populate subjects when class changes
    $('#classFilter').on('change', function () {
        const classId = $(this).val();
        console.log('Class selected:', classId);
        if (classId) {
            $.get('api/leaderboard.php?action=get_subjects&class_id=' + classId, function (data) {
                console.log('Subjects received:', data);
                $('#subjectFilter').empty().append('<option value="">All Subjects</option>');
                data.forEach(function (subject) {
                    $('#subjectFilter').append(`<option value="${subject.id}">${subject.name}</option>`);
                });
                loadLeaderboard('class');
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.error('Error fetching subjects:', textStatus, errorThrown);
                $('#leaderboardBody').empty().append('<tr><td colspan="6">Error loading subjects</td></tr>');
            });
        } else {
            $('#subjectFilter').empty().append('<option value="">All Subjects</option>');
            loadLeaderboard('class');
        }
    });

    // Load leaderboard when subject or event changes
    $('#subjectFilter, #eventFilter').on('change', function () {
        const activeTab = $('#leaderboardTabs .nav-link.active').data('type');
        console.log('Filter changed, active tab:', activeTab);
        loadLeaderboard(activeTab);
    });

    // Function to load leaderboard data
    function loadLeaderboard(type) {
        const categoryId = $('#categoryFilter').val();
        const classId = $('#classFilter').val();
        const subjectId = $('#subjectFilter').val();
        const eventId = $('#eventFilter').val();

        let url = `api/leaderboard.php?action=get_leaderboard&type=${type}`;
        if (categoryId) url += `&category_id=${categoryId}`;
        if (classId) url += `&class_id=${classId}`;
        if (subjectId) url += `&subject_id=${subjectId}`;
        if (eventId) url += `&event_id=${eventId}`;

        console.log('Loading leaderboard with URL:', url);

        $.get(url, function (data) {
            console.log('Leaderboard data received:', data);
            $('#leaderboardBody').empty();
            if (data.error) {
                $('#leaderboardBody').append(`<tr><td colspan="6">Error: ${data.error}</td></tr>`);
                return;
            }
            if (data.length === 0) {
                $('#leaderboardBody').append('<tr><td colspan="6">No data available for this filter</td></tr>');
                return;
            }
            data.forEach(function (entry, index) {
                // Calculate progress percentage (max score 100)
                const progress = Math.min((entry.total_score / 100) * 100, 100);
                // Determine progress bar color based on score
                let progressClass = 'bg-success';
                if (progress < 50) {
                    progressClass = 'bg-danger';
                } else if (progress < 80) {
                    progressClass = 'bg-warning';
                }
                const badgeTooltip = entry.badge_names ? entry.badge_names.replace(/,/g, ', ') : 'No badges';
                $('#leaderboardBody').append(`
                    <tr>
                        <td>${entry.rank_position || (index + 1)}</td>
                        <td>${entry.username}</td>
                        <td>${entry.total_score} (Avg: ${parseFloat(entry.average_score).toFixed(2)})</td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar ${progressClass}" role="progressbar" style="width: ${progress}%;" aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100">
                                    ${progress.toFixed(0)}%
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="${badgeTooltip}">
                                ${entry.badge_count}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary view-details-btn" data-bs-toggle="modal" data-bs-target="#userDetailsModal" data-user-id="${entry.user_id}" data-username="${entry.username}" style="background-color: #129990 !important;">
                                View
                            </button>
                        </td>
                    </tr>
                `);
            });
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Handle View Details button click
            $('.view-details-btn').on('click', function () {
                const userId = $(this).data('user-id');
                const username = $(this).data('username');
                $('#userDetailsModalLabel').text(`User Details: ${username}`);
                $('#userDetailsContent').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');

                $.get(`api/user_details.php?user_id=${userId}`, function (data) {
                    console.log('User details received:', data);
                    if (data.error) {
                        $('#userDetailsContent').html(`<p class="text-danger">${data.error}</p>`);
                        return;
                    }
                    let html = `
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Result Summary:</h4>
                                <p><strong>Username:</strong> ${data.username}</p>
                                <p><strong>Total Score:</strong> ${data.total_score}</p>
                                <p><strong>Average Score:</strong> ${parseFloat(data.average_score).toFixed(2)}</p>
                                <p><strong>Total Attempts:</strong> ${data.total_attempts}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Badges</h6>
                                ${data.badges.length ? data.badges.map(badge => `
                                    <div class="badge-item mb-2">
                                        <span class="badge bg-success">${badge.name}</span>
                                        <small class="text-muted d-block">${badge.description}</small>
                                    </div>
                                `).join('') : '<p>No badges earned.</p>'}
                            </div>
                        </div>
                        <hr>
                        <h6>Performance by Category</h6>
                        <ul class="list-group">
                            ${data.performance_by_category.length ? data.performance_by_category.map(perf => `
                                <li class="list-group-item">
                                    <strong>${perf.category_name}</strong>: ${perf.total_score} (Avg: ${parseFloat(perf.average_score).toFixed(2)})
                                </li>
                            `).join('') : '<li class="list-group-item">No category data available.</li>'}
                        </ul>
                    `;
                    $('#userDetailsContent').html(html);
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    console.error('Error fetching user details:', textStatus, errorThrown);
                    $('#userDetailsContent').html('<p class="text-danger">Error loading user details.</p>');
                });
            });
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.error('Error fetching leaderboard:', textStatus, errorThrown);
            $('#leaderboardBody').empty().append('<tr><td colspan="6">Error loading leaderboard</td></tr>');
        });
    }

    // Load global leaderboard on page load
    loadLeaderboard('global');
});
    </script>
    <?php include "includes/footer.php"; ?>
</body>
</html>
<?php $conn->close(); ?>