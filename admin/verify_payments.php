<?php
session_start();
require __DIR__."/../vendor/autoload.php";
require __DIR__."/admincheck.php";
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quizpallete";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle payment verification/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['payment_id']) && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    $payment_id = intval($_POST['payment_id']);
    $action = $_POST['action'];
    
    try {
        $db->beginTransaction();

        // Fetch payment details
        $payment_query = $db->prepare("SELECT user_id FROM payments WHERE id = ? AND status = 'pending'");
        $payment_query->execute([$payment_id]);
        if ($payment_query->rowCount() === 0) {
            throw new Exception("Payment not found or already processed");
        }
        $payment = $payment_query->fetch(PDO::FETCH_ASSOC);
        $user_id = $payment['user_id'];

        // Update payment status
        $new_status = $action === 'verify' ? 'completed' : 'failed';
        $new_verification_status = $action === 'verify' ? 'verified' : 'rejected';
        $update_payment = $db->prepare("UPDATE payments SET status = ?, verification_status = ?, updated_at = NOW() WHERE id = ?");
        $update_payment->execute([$new_status, $new_verification_status, $payment_id]);

        // Update subscription status
        $subscription_status = $action === 'verify' ? 'active' : 'inactive';
        $update_subscription = $db->prepare("UPDATE subscriptions SET status = ?, updated_at = NOW() WHERE payment_id = ?");
        $update_subscription->execute([$subscription_status, $payment_id]);

        // Create notification
        $notification_title = $action === 'verify' ? "Payment Verified" : "Payment Rejected";
        $notification_message = $action === 'verify' 
            ? "Your payment has been successfully verified. Your subscription is now active!"
            : "Your payment was rejected. Please contact support for assistance.";
        $notification_type = $action === 'verify' ? 'success' : 'error';
        
        $insert_notification = $db->prepare("
            INSERT INTO notifications (user_id, title, message, type, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $insert_notification->execute([$user_id, $notification_title, $notification_message, $notification_type]);

        $db->commit();
        $message = ['type' => 'success', 'text' => "Payment $action successfully"];
    } catch (Exception $e) {
        $db->rollBack();
        $message = ['type' => 'danger', 'text' => $e->getMessage()];
    }
}

// Fetch pending payments
$query = "
    SELECT p.id, p.user_id, p.transaction_id, p.bkash_transaction_id, p.amount, p.currency, p.payment_method, p.created_at, u.username
    FROM payments p
    JOIN users u ON p.user_id = u.id
    WHERE p.status = 'pending'
    ORDER BY p.created_at DESC
";
$result = $db->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Payments - QuizPallete</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --main-color: #129990;
            --main-hover: #0f7c7c;
            --secondary-color: #f8f9fa;
            --gradient-bg: linear-gradient(135deg, var(--main-color), var(--main-hover));
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--secondary-color);
            color: #333;
        }

        .sidebar {
            background: var(--gradient-bg);
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            background: var(--gradient-bg) !important;
        }

        .nav-link {
            color: white !important;
            transition: all 0.3s ease;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 5px 10px;
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .btn-primary, .btn-verify, .btn-reject {
            background: var(--main-color);
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
            margin-right: 5px;
        }

        .btn-verify:hover, .btn-reject:hover {
            background: var(--main-hover);
            transform: translateY(-2px);
        }

        .btn-reject {
            background: #dc3545;
        }

        .btn-reject:hover {
            background: #c82333;
        }

        .table {
            background: white;
            border-radius: 10px;
        }

        .table th {
            background: var(--main-color);
            color: white;
            cursor: pointer;
        }

        .table tbody tr:hover {
            background-color: rgba(18, 153, 144, 0.05);
        }

        .search-container {
            max-width: 400px;
            margin-bottom: 20px;
        }

        .pagination .page-link {
            color: var(--main-color);
        }

        .pagination .page-item.active .page-link {
            background: var(--main-color);
            border-color: var(--main-color);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        @media (max-width: 767.98px) {
            .btn-verify, .btn-reject {
                width: 100%;
                margin-bottom: 10px;
            }

            .table-responsive {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">
            <!-- Sidebar for large screens -->
            <div class="col-md-2 d-none d-md-block sidebar">
                <nav class="navbar border-bottom border-white">
                    <div class="container-fluid">
                        <span class="navbar-brand text-white">QuizPallete Admin</span>
                    </div>
                </nav>
                <nav class="nav flex-column">
                    <a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                    <a class="nav-link" data-bs-toggle="collapse" href="#quizSubMenu" role="button" aria-expanded="false" aria-controls="quizSubMenu">
                        <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
                    </a>
                    <div class="collapse ms-3" id="quizSubMenu">
                        <a class="nav-link" href="create_quiz.php"><i class="bi bi-plus-circle me-2"></i>Manage Courses</a>
                        <a class="nav-link" href="set_exams.php"><i class="bi bi-book me-2"></i>Manage Exams</a>
                        <a class="nav-link" href="quiz_list.php"><i class="bi bi-list-ul me-2"></i>Quiz List</a>
                    </div>
                    <a class="nav-link" href="manage_users.php"><i class="bi bi-people-fill me-2"></i>Manage Users</a>
                    <a class="nav-link" href="track_results.php"><i class="bi bi-graph-up-arrow me-2"></i>Track Results</a>
                    <a class="nav-link" href="leaderboards.php"><i class="bi bi-trophy-fill me-2"></i>Leaderboards</a>
                    <a class="nav-link" href="requests.php"><i class="bi bi-inbox-fill me-2"></i>Requests</a>
                    <a class="nav-link" href="certificate.php"><i class="bi bi-award-fill me-2"></i>Certificates</a>
                    <a class="nav-link active" href="verify_payments.php"><i class="bi bi-credit-card me-2"></i>Verify Payments</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 col-12">
                <!-- Top navbar with hamburger -->
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <button class="btn text-white d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile" aria-controls="sidebarMobile">
                            <i class="bi bi-list"></i>
                        </button>
                        <span class="navbar-brand text-white d-md-none">QuizPallete Admin</span>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- Offcanvas sidebar for mobile -->
                <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMobile" style="background: var(--gradient-bg);">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title text-white">Menu</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <nav class="nav flex-column">
                            <a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                            <a class="nav-link" data-bs-toggle="collapse" href="#quizSubMenuMobile" role="button" aria-expanded="false" aria-controls="quizSubMenuMobile">
                                <i class="bi bi-ui-checks-grid me-2"></i>Manage Quizzes
                            </a>
                            <div class="collapse ms-3" id="quizSubMenuMobile">
                                <a class="nav-link" href="create_quiz.php"><i class="bi bi-plus-circle me-2"></i>Manage Courses</a>
                                <a class="nav-link" href="set_exams.php"><i class="bi bi-book me-2"></i>Manage Exams</a>
                                <a class="nav-link" href="quiz_list.php"><i class="bi bi-list-ul me-2"></i>Quiz List</a>
                            </div>
                            <a class="nav-link" href="manage_users.php"><i class="bi bi-people-fill me-2"></i>Manage Users</a>
                            <a class="nav-link" href="track_results.php"><i class="bi bi-graph-up-arrow me-2"></i>Track Results</a>
                            <a class="nav-link" href="leaderboards.php"><i class="bi bi-trophy-fill me-2"></i>Leaderboards</a>
                            <a class="nav-link" href="requests.php"><i class="bi bi-inbox-fill me-2"></i>Requests</a>
                            <a class="nav-link" href="certificate.php"><i class="bi bi-award-fill me-2"></i>Certificates</a>
                            <a class="nav-link active" href="verify_payments.php"><i class="bi bi-credit-card me-2"></i>Verify Payments</a>
                        </nav>
                    </div>
                </div>

                <!-- Verify Payments Content -->
                <div class="p-4">
                    <h2 class="text-primary-emphasis mb-4"><i class="bi bi-credit-card me-2"></i>Verify Payments</h2>
                    <div class="card">
                        <div class="card-body p-4">
                            <?php if (isset($message)): ?>
                                <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>" role="alert">
                                    <?php echo htmlspecialchars($message['text']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($result && $result->rowCount() > 0): ?>
                                <!-- Search Bar -->
                                <div class="search-container mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                        <input type="text" id="searchInput" class="form-control" placeholder="Search by username or transaction ID..." aria-label="Search payments">
                                    </div>
                                </div>
                                <!-- Table -->
                                <div class="table-responsive">
                                    <table class="table table-striped" id="paymentsTable">
                                        <thead>
                                            <tr>
                                                <th scope="col" data-sort="id">Payment ID</th>
                                                <th scope="col" data-sort="username">Username</th>
                                                <th scope="col" data-sort="transaction_id">Transaction ID</th>
                                                <th scope="col" data-sort="bkash_transaction_id">External Transaction ID</th>
                                                <th scope="col" data-sort="payment_method">Payment Method</th>
                                                <th scope="col" data-sort="amount">Amount</th>
                                                <th scope="col" data-sort="created_at">Created At</th>
                                                <th scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['transaction_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['bkash_transaction_id']); ?></td>
                                                    <td><?php echo htmlspecialchars(ucfirst($row['payment_method'])); ?></td>
                                                    <td><?php echo htmlspecialchars($row['amount'] . ' ' . $row['currency']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                                    <td>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="payment_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                                            <input type="hidden" name="action" value="verify">
                                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                                            <button type="submit" class="btn btn-verify btn-sm" title="Verify Payment">
                                                                <i class="bi bi-check-circle"></i> Verify
                                                            </button>
                                                        </form>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="payment_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                                            <input type="hidden" name="action" value="reject">
                                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                                            <button type="submit" class="btn btn-reject btn-sm" title="Reject Payment">
                                                                <i class="bi bi-x-circle"></i> Reject
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Pagination -->
                                <nav aria-label="Table pagination" class="mt-4">
                                    <ul class="pagination justify-content-center" id="pagination"></ul>
                                </nav>
                            <?php else: ?>
                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>No pending payments found.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#searchInput').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#paymentsTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Sorting functionality
            $('th[data-sort]').click(function() {
                const column = $(this).data('sort');
                const rows = $('#paymentsTable tbody tr').get();

                rows.sort(function(a, b) {
                    const A = $(a).children('td').eq($('th').index($(`th[data-sort="${column}"]`))).text().toLowerCase();
                    const B = $(b).children('td').eq($('th').index($(`th[data-sort="${column}"]`))).text().toLowerCase();
                    return A < B ? -1 : A > B ? 1 : 0;
                });

                $.each(rows, function(index, row) {
                    $('#paymentsTable tbody').append(row);
                });
            });

            // Pagination (client-side)
            const rowsPerPage = 10;
            const rows = $('#paymentsTable tbody tr');
            const rowsCount = rows.length;
            const pageCount = Math.ceil(rowsCount / rowsPerPage);
            const pagination = $('#pagination');

            function displayRows(page) {
                rows.hide();
                rows.slice((page - 1) * rowsPerPage, page * rowsPerPage).show();
            }

            if (pageCount > 1) {
                for (let i = 1; i <= pageCount; i++) {
                    pagination.append(`<li class="page-item"><a class="page-link" href="#">${i}</a></li>`);
                }
                $('.page-item').first().addClass('active');
                displayRows(1);

                $('.page-link').click(function(e) {
                    e.preventDefault();
                    $('.page-item').removeClass('active');
                    $(this).parent().addClass('active');
                    displayRows($(this).text());
                });
            } else {
                rows.show();
            }

            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[title]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        });
    </script>
</body>
</html>
