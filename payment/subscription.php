<?php
session_start();
require_once 'database.php';

$database = new Database();
$db = $database->getConnection();

// Fetch exams for dropdown
$exams = [];
try {
    $stmt = $db->query("SELECT id, title FROM exams WHERE status = 'active'");
    $exams = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching exams: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Package - Quiz Palette</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #096B68;
            --primary-dark: #074d4a;
            --accent-color: #00d4aa;
            --text-light: #ffffff;
            --text-muted: #b0bec5;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, #0a4f4c 100%);
            min-height: 100vh;
            color: var(--text-light);
        }

        .hero-section {
            padding: 80px 0 60px;
            text-align: center;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #fff, var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--text-muted);
            margin-bottom: 50px;
        }

        .pricing-container {
            padding: 60px 0;
        }

        .pricing-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px 30px;
            margin-bottom: 30px;
            position: relative;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            height: 100%;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border-color: var(--accent-color);
        }

        .pricing-card.featured {
            border: 2px solid var(--accent-color);
            transform: scale(1.05);
        }

        .pricing-card.featured .badge {
            background: var(--accent-color);
            color: var(--primary-color);
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
        }

        .plan-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        .plan-price {
            font-size: 3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 10px;
            color: var(--accent-color);
        }

        .plan-duration {
            text-align: center;
            color: var(--text-muted);
            margin-bottom: 30px;
        }

        .features-list {
            list-style: none;
            margin-bottom: 40px;
        }

        .features-list li {
            padding: 8px 0;
            position: relative;
            padding-left: 30px;
        }

        .features-list li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--accent-color);
            font-weight: bold;
            font-size: 1.2rem;
        }

        .btn-subscribe {
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            border: 2px solid var(--accent-color);
            background: transparent;
            color: var(--accent-color);
            transition: all 0.3s ease;
        }

        .btn-subscribe:hover {
            background: var(--accent-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .btn-subscribe.featured {
            background: var(--accent-color);
            color: var(--primary-color);
        }

        .modal-content {
            background: var(--primary-color);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
        }

        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-title {
            color: var(--text-light);
        }

        .btn-close {
            filter: invert(1);
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .payment-method {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            border-color: var(--accent-color);
            background: rgba(0, 212, 170, 0.1);
        }

        .payment-method.selected {
            border-color: var(--accent-color);
            background: rgba(0, 212, 170, 0.2);
        }

        .payment-method div {
            padding: 10px;
            border-radius: 5px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-light);
            border-radius: 8px;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 212, 170, 0.25);
            color: var(--text-light);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-label {
            color: var(--text-light);
            font-weight: 500;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid #28a745;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            .pricing-card.featured {
                transform: none;
            }
            .plan-price {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Choose Your Favorite Package</h1>
            <p class="hero-subtitle">Select the plan that fits your learning journey and get unlimited access to exams</p>
        </div>
    </section>

    <section class="pricing-container" id="pricing">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="pricing-card">
                        <h3 class="plan-name">Basic</h3>
                        <div class="plan-price">Free</div>
                        <div class="plan-duration">Forever</div>
                        <ul class="features-list">
                            <li>Access to free exams</li>
                            <li>Basic progress tracking</li>
                            <li>Limited exam attempts</li>
                            <li>Community forum access</li>
                            <li>Mobile app access</li>
                        </ul>
                        <button class="btn btn-subscribe" data-plan="basic" data-price="0" data-duration="lifetime">
                            Get Started
                        </button>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="pricing-card featured">
                        <div class="badge">Most Popular</div>
                        <h3 class="plan-name">Standard</h3>
                        <div class="plan-price">৳299<small style="font-size: 1rem;">/mo</small></div>
                        <div class="plan-duration">Monthly billing</div>
                        <ul class="features-list">
                            <li>Everything in Basic</li>
                            <li>Unlimited exam attempts</li>
                            <li>Advanced analytics</li>
                            <li>Priority support</li>
                            <li>Downloadable certificates</li>
                            <li>Ad-free experience</li>
                        </ul>
                        <button class="btn btn-subscribe featured" data-plan="standard" data-price="299" data-duration="monthly">
                            Get Standard
                        </button>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="pricing-card">
                        <h3 class="plan-name">Premium</h3>
                        <div class="plan-price">৳699<small style="font-size: 1rem;">/mo</small></div>
                        <div class="plan-duration">Monthly billing</div>
                        <ul class="features-list">
                            <li>Everything in Standard</li>
                            <li>Personal learning dashboard</li>
                            <li>Custom exam creation</li>
                            <li>Advanced reporting</li>
                            <li>1-on-1 tutoring sessions</li>
                            <li>Early access to new features</li>
                        </ul>
                        <button class="btn btn-subscribe" data-plan="premium" data-price="699" data-duration="monthly">
                            Get Premium
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Complete Your Subscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="alertContainer"></div>
                    <div class="mb-4">
                        <h6>Selected Plan: <span id="selectedPlan" class="text-info"></span></h6>
                        <h5>Total Amount: ৳<span id="totalAmount"></span></h5>
                    </div>
                    <form id="paymentForm">
                        <div class="mb-3">
                            <label class="form-label">Select Exam</label>
                            <select name="exam_id" id="exam_id" class="form-control">
                                <option value="">Select an exam</option>
                                <?php foreach ($exams as $exam): ?>
                                    <option value="<?php echo $exam['id']; ?>"><?php echo htmlspecialchars($exam['title']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Select Payment Method</label>
                            <div class="payment-methods">
                                <div class="payment-method" data-method="bkash">
                                    <div style="background: #e2136e; color: white; padding: 10px; border-radius: 5px;">
                                        <i class="fas fa-mobile-alt fa-2x"></i>
                                        <div class="mt-2">bKash</div>
                                    </div>
                                </div>
                                <div class="payment-method" data-method="nagad">
                                    <div style="background: #eb5822; color: white; padding: 10px; border-radius: 5px;">
                                        <i class="fas fa-wallet fa-2x"></i>
                                        <div class="mt-2">Nagad</div>
                                    </div>
                                </div>
                                <div class="payment-method" data-method="rocket">
                                    <div style="background: #8e44ad; color: white; padding: 10px; border-radius: 5px;">
                                        <i class="fas fa-rocket fa-2x"></i>
                                        <div class="mt-2">Rocket</div>
                                    </div>
                                </div>
                                <div class="payment-method" data-method="dbbl">
                                    <div style="background: #1e3a8a; color: white; padding: 10px; border-radius: 5px;">
                                        <i class="fas fa-credit-card fa-2x"></i>
                                        <div class="mt-2">DBBL Nexus</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="userPhone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="userPhone" name="user_phone" placeholder="01XXXXXXXXX">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="userEmail" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="userEmail" name="user_email" placeholder="your@email.com">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="userName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="userName" name="user_name" placeholder="Your Full Name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="transactionId" class="form-label">Transaction ID</label>
                                <input type="text" class="form-control" id="transactionId" name="transaction_id" placeholder="Enter Transaction ID">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="paymentPhone" class="form-label">Payment Account Number</label>
                            <input type="tel" class="form-control" id="paymentPhone" name="payment_phone" placeholder="Number used for payment">
                        </div>
                        <input type="hidden" id="selectedMethod" name="payment_method">
                        <input type="hidden" id="planType" name="plan_type">
                        <input type="hidden" id="planPrice" name="amount">
                        <input type="hidden" id="planDuration" name="duration">
                        <button type="submit" class="btn btn-subscribe featured w-100" id="submitPayment">
                            <i class="fas fa-lock me-2"></i>Complete Payment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Approval Section -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <section class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-4">Pending Payments</h2>
                <div id="adminAlertContainer"></div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>User ID</th>
                            <th>Exam ID</th>
                            <th>Plan Name</th>
                            <th>Amount</th>
                            <th>Transaction ID</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="pendingPayments">
                        <?php
                        try {
                            $stmt = $db->query("
                                SELECT p.id, p.user_id, p.exam_id, s.plan_name, p.amount, p.currency, p.bkash_transaction_id 
                                FROM payments p 
                                LEFT JOIN subscriptions s ON p.id = s.payment_id 
                                WHERE p.status = 'pending' AND p.verification_status = 'pending'
                            ");
                            while ($row = $stmt->fetch()) {
                                echo "<tr>";
                                echo "<td>{$row['id']}</td>";
                                echo "<td>{$row['user_id']}</td>";
                                echo "<td>{$row['exam_id']}</td>";
                                echo "<td>{$row['plan_name']}</td>";
                                echo "<td>{$row['amount']} {$row['currency']}</td>";
                                echo "<td>{$row['bkash_transaction_id']}</td>";
                                echo "<td><button class='btn btn-success btn-sm approve-btn' data-id='{$row['id']}'>Approve</button></td>";
                                echo "</tr>";
                            }
                        } catch (PDOException $e) {
                            error_log("Error fetching pending payments: " . $e->getMessage());
                            echo "<tr><td colspan='7'>Error loading pending payments.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            let selectedPlan = {};

            // Show alert function
            function showAlert(type, message, container = '#alertContainer') {
                $(container).html(`
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
            }

            // Handle subscription button click
            $('.btn-subscribe').click(function() {
                const plan = $(this).data('plan');
                const price = $(this).data('price');
                const duration = $(this).data('duration');

                if (plan === 'basic') {
                    window.location.href = 'register.php?plan=basic';
                    return;
                }

                selectedPlan = { name: plan, price: price, duration: duration };
                $('#selectedPlan').text(plan.charAt(0).toUpperCase() + plan.slice(1));
                $('#totalAmount').text(price);
                $('#planType').val(plan);
                $('#planPrice').val(price);
                $('#planDuration').val(duration);
                $('#paymentModal').modal('show');
            });

            // Handle payment method selection
            $('.payment-method').click(function() {
                $('.payment-method').removeClass('selected');
                $(this).addClass('selected');
                $('#selectedMethod').val($(this).data('method'));
            });

            // Handle phone input (keep formatting but remove validation)
            $('#userPhone, #paymentPhone').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                if (value.length > 11) value = value.substring(0, 11);
                $(this).val(value);
            });

            // Handle form submission (no validation)
            $('#paymentForm').submit(function(e) {
                e.preventDefault();

                const formData = $(this).serialize();
                $('#submitPayment').html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...').prop('disabled', true);

                $.ajax({
                    url: 'process_subscription.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message);
                            setTimeout(() => {
                                window.location.href = 'dashboard.php?payment=success&txn=' + response.transaction_id;
                            }, 2000);
                        } else {
                            showAlert('danger', response.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showAlert('danger', errorMessage);
                    },
                    complete: function() {
                        $('#submitPayment').html('<i class="fas fa-lock me-2"></i>Complete Payment').prop('disabled', false);
                    }
                });
            });

            // Handle payment approval
            $('.approve-btn').on('click', function() {
                const paymentId = $(this).data('id');
                $.ajax({
                    url: 'approve_payment.php',
                    type: 'POST',
                    data: { payment_id: paymentId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message, '#adminAlertContainer');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showAlert('danger', response.message, '#adminAlertContainer');
                        }
                    },
                    error: function() {
                        showAlert('danger', 'Error approving payment.', '#adminAlertContainer');
                    }
                });
            });
        });
    </script>
</body>
</html>