$(document).ready(function () {
    // Load global leaderboard on page load
    loadLeaderboard('global');

    // Tab click event
    $('#leaderboardTabs .nav-link').on('click', function () {
        const type = $(this).data('type');
        $('#filters select').hide();
        if (type === 'category') {
            $('#categoryFilter').show();
        } else if (type === 'class') {
            $('#categoryFilter, #classFilter').show();
        } else if (type === 'subject') {
            $('#categoryFilter, #classFilter, #subjectFilter').show();
        } else if (type === 'event') {
            $('#eventFilter').show();
        }
        $('#filters').show();
        loadLeaderboard(type);
    });

    // Filter change events
    $('#categoryFilter').on('change', function () {
        const categoryId = $(this).val();
        if (categoryId) {
            $.get('get_classes.php?category_id=' + categoryId, function (data) {
                $('#classFilter').html('<option value="">Select Class</option>');
                data.forEach(function (cls) {
                    $('#classFilter').append(`<option value="${cls.id}">${cls.name}</option>`);
                });
            });
            loadLeaderboard('category', { category_id: categoryId });
        }
    });

    $('#classFilter').on('change', function () {
        const classId = $(this).val();
        if (classId) {
            $.get('get_subjects.php?class_id=' + classId, function (data) {
                $('#subjectFilter').html('<option value="">Select Subject</option>');
                data.forEach(function (subject) {
                    $('#subjectFilter').append(`<option value="${subject.id}">${subject.name}</option>`);
                });
            });
            loadLeaderboard('class', { class_id: classId });
        }
    });

    $('#subjectFilter').on('change', function () {
        const subjectId = $(this).val();
        if (subjectId) {
            loadLeaderboard('subject', { subject_id: subjectId });
        }
    });

    $('#eventFilter').on('change', function () {
        const quizId = $(this).val();
        if (quizId) {
            loadLeaderboard('event', { quiz_id: quizId });
        }
    });

    // Load leaderboard data via AJAX
    function loadLeaderboard(type, filters = {}) {
        $.ajax({
            url: 'get_leaderboard_data.php',
            method: 'GET',
            data: { type: type, ...filters },
            success: function (data) {
                let html = '';
                data.forEach(function (entry, index) {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${entry.user_name}</td>
                            <td>${entry.total_score}</td>
                            <td>${entry.badges.map(b => `<span class="badge badge-${b.toLowerCase()}">${b}</span>`).join(' ')}</td>
                            <td>${entry.details || '-'}</td>
                        </tr>
                    `;
                });
                $('#leaderboardBody').html(html);
            },
            error: function () {
                $('#leaderboardBody').html('<tr><td colspan="5">Error loading leaderboard</td></tr>');
            }
        });
    }
});