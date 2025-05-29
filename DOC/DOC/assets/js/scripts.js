// Static leaderboard data
const leaderboardData = [
    { rank: 1, username: "Rahim", score: 950, badge: "Gold" },
    { rank: 2, username: "Karim", score: 870, badge: "Silver" },
    { rank: 3, username: "Ayesha", score: 820, badge: "Bronze" },
    { rank: 4, username: "Sadia", score: 780, badge: "None" },
    { rank: 5, username: "Tanim", score: 750, badge: "None" }
];

// Static quiz data for search functionality
const quizData = [
    { name: "Math", category: "math", description: "Challenge your math skills with engaging quizzes." },
    { name: "Admission", category: "admission", description: "Prepare for university and college admission tests." },
    { name: "Job Preparation", category: "job", description: "Ace your job interviews with practice quizzes." }
];

// Populate leaderboard
document.addEventListener('DOMContentLoaded', function () {
    const leaderboardBody = document.getElementById('leaderboard-body');
    leaderboardData.forEach(leader => {
        const badgeClass = leader.badge === 'Gold' ? 'badge bg-warning text-dark' :
                          leader.badge === 'Silver' ? 'badge bg-secondary' :
                          leader.badge === 'Bronze' ? 'badge bg-bronze' : '';
        const badgeText = leader.badge !== 'None' ? `<span class="${badgeClass}">${leader.badge}</span>` : 'None';
        leaderboardBody.innerHTML += `
            <tr>
                <td>${leader.rank}</td>
                <td>${leader.username}</td>
                <td>${leader.score}</td>
                <td>${badgeText}</td>
            </tr>
        `;
    });

    // Smooth scrolling for navigation links
    document.querySelectorAll('.nav-link, .breadcrumb-item a').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            if (this.getAttribute('href').startsWith('#')) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 70,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const quizList = document.getElementById('quizList');
    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        quizList.innerHTML = '';
        const filteredQuizzes = quizData.filter(quiz => 
            quiz.name.toLowerCase().includes(query) || 
            quiz.description.toLowerCase().includes(query)
        );
        filteredQuizzes.forEach(quiz => {
            quizList.innerHTML += `
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm quiz-card">
                        <div class="card-body">
                            <h5 class="card-title">${quiz.name}</h5>
                            <p class="card-text">${quiz.description}</p>
                            <a href="#" class="btn btn-primary take-quiz-btn" data-category="${quiz.category}">Take Quiz</a>
                        </div>
                    </div>
                </div>
            `;
        });
        if (filteredQuizzes.length === 0) {
            quizList.innerHTML = '<div class="col-12"><p class="text-center">No quizzes found.</p></div>';
        }
        // Re-attach quiz button event listeners
        attachQuizButtonListeners();
    });

    // Handle quiz button clicks
    function attachQuizButtonListeners() {
        document.querySelectorAll('.take-quiz-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const category = this.getAttribute('data-category');
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
                // For demo, log the action; in production, redirect to quiz page
                console.log('Quiz selected:', category);
            });
        });
    }
    attachQuizButtonListeners();

    // Handle subscription button clicks
    document.querySelectorAll('.subscribe-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const plan = this.getAttribute('data-plan');
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
            // For demo, log the action; in production, redirect to subscription page
            console.log('Subscription selected:', plan);
        });
    });
});