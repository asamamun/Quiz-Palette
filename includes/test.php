<!-- Add this right after the description paragraph -->
<div class="row mb-4">
    <div class="col-md-6 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pb-0">
                <h5 class="card-title text-center mb-0" style="color: #129990;">Top Performers</h5>
            </div>
            <div class="card-body p-3 pt-0">
                <ul class="list-group list-group-flush border-top" id="topPerformersList">
                    <!-- Top 4 will be loaded here via AJAX -->
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <div class="d-flex align-items-center">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            <span>Loading top performers...</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    // Function to load top 4 performers
function loadTopPerformers() {
    $.get('api/leaderboard.php?action=get_leaderboard&type=global&limit=4', function(data) {
        console.log('Top performers data received:', data);
        $('#topPerformersList').empty();
        
        if (data.error || data.length === 0) {
            $('#topPerformersList').append(`
                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                    <span class="text-muted">No top performers data available</span>
                </li>
            `);
            return;
        }
        
        // Define badge classes for each position
        const positionClasses = [
            { badge: 'bg-warning text-dark', text: 'bg-warning bg-opacity-10 text-warning' }, // 1st
            { badge: 'bg-secondary', text: 'bg-secondary bg-opacity-10 text-secondary' },     // 2nd
            { badge: 'bg-danger', text: 'bg-danger bg-opacity-10 text-danger' },            // 3rd
            { badge: 'bg-primary', text: 'bg-primary bg-opacity-10 text-primary' }          // 4th
        ];
        
        data.forEach(function(entry, index) {
            const position = positionClasses[index] || positionClasses[3]; // Default to 4th style if more than 4
            $('#topPerformersList').append(`
                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                    <div class="d-flex align-items-center">
                        <span class="badge ${position.badge} me-2">${index + 1}</span>
                        <span>${entry.username}</span>
                    </div>
                    <span class="badge ${position.text} fw-medium">${entry.total_score} pts</span>
                </li>
            `);
        });
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error('Error fetching top performers:', textStatus, errorThrown);
        $('#topPerformersList').empty().append(`
            <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                <span class="text-danger">Error loading top performers</span>
            </li>
        `);
    });
}

// Call both functions on page load
loadTopPerformers();
loadLeaderboard('global');
</script>