<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$database = new Database();
$db = $database->getConnection();

try {
    // Validate required fields
    $required_fields = ['user_name', 'user_email', 'user_phone', 'payment_method', 
                       'transaction_id', 'payment_phone', 'plan_type', 'amount'];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Please fill in all required fields");
        }
    }

    // Sanitize input data
    $user_name = trim($_POST['user_name']);
    $user_email = trim($_POST['user_email']);
    $user_phone = trim($_POST['user_phone']);
    $payment_method = trim($_POST['payment_method']);
    $transaction_id = trim($_POST['transaction_id']);
    $payment_phone = trim($_POST['payment_phone']);
    $plan_type = trim($_POST['plan_type']);
    $amount = floatval($_POST['amount']);
    $duration = trim($_POST['duration']) ?? 'monthly';

    // Validate email format
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Please enter a valid email address");
    }

    // Validate phone number (Bangladesh format)
    if (!preg_match('/^01[3-9]\d{8}$/', $user_phone)) {
        throw new Exception("Please enter a valid Bangladesh phone number");
    }

    // Validate payment methods
    $valid_methods = ['bkash', 'nagad', 'rocket', 'dbbl'];
    if (!in_array($payment_method, $valid_methods)) {
        throw new Exception("Invalid payment method selected");
    }

    // Check if transaction ID already exists
    $check_transaction = $db->prepare("SELECT id FROM payments WHERE bkash_transaction_id = ?");
    $check_transaction->execute([$transaction_id]);
    if ($check_transaction->rowCount() > 0) {
        throw new Exception("This transaction ID has already been used");
    }

    // Start transaction
    $db->beginTransaction();

    // Check if user exists, if not create one
    $user_id = null;
    $check_user = $db->prepare("SELECT id FROM users WHERE email = ?");
    $check_user->execute([$user_email]);
    
    if ($check_user->rowCount() > 0) {
        $user = $check_user->fetch(PDO::FETCH_ASSOC);
        $user_id = $user['id'];
    } else {
        // Create new user
        $names = explode(' ', $user_name, 2);
        $first_name = $names[0];
        $last_name = isset($names[1]) ? $names[1] : '';
        $username = strtolower(str_replace(' ', '', $user_name)) . rand(100, 999);
        $password = password_hash('temppass123', PASSWORD_DEFAULT);

        $create_user = $db->prepare("
            INSERT INTO users (username, email, password, first_name, last_name, phone, role, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'user', 'active')
        ");
        
        $create_user->execute([
            $username, $user_email, $password, $first_name, $last_name, $user_phone
        ]);
        
        $user_id = $db->lastInsertId();
    }

    // Generate unique transaction ID for our system
    $our_transaction_id = 'QP' . date('YmdHis') . rand(1000, 9999);

    // Create quiz record (for payment tracking - using dummy quiz_id = 1)
    $quiz_id = 1;

    // Insert payment record
    $insert_payment = $db->prepare("
        INSERT INTO payments (
            user_id, quiz_id, transaction_id, bkash_transaction_id, amount, 
            currency, payment_method, status, verification_status
        ) VALUES (?, ?, ?, ?, ?, 'BDT', ?, 'pending', 'pending')
    ");
    
    $insert_payment->execute([
        $user_id, $quiz_id, $our_transaction_id, $transaction_id, 
        $amount, $payment_method
    ]);
    
    $payment_id = $db->lastInsertId();

    // Calculate subscription end date
    $start_date = date('Y-m-d H:i:s');
    $end_date = null;
    
    if ($duration === 'monthly') {
        $end_date = date('Y-m-d H:i:s', strtotime('+1 month'));
    } elseif ($duration === 'yearly') {
        $end_date = date('Y-m-d H:i:s', strtotime('+1 year'));
    }

    // Insert subscription record
    $insert_subscription = $db->prepare("
        INSERT INTO subscriptions (
            user_id, plan_name, start_date, end_date, status, 
            amount, currency, payment_id
        ) VALUES (?, ?, ?, ?, 'active', ?, 'BDT', ?)
    ");
    
    $insert_subscription->execute([
        $user_id, $plan_type, $start_date, $end_date, $amount, $payment_id
    ]);

    // Create notification for user
    $notification_title = "Subscription Activated";
    $notification_message = "Your {$plan_type} subscription has been activated successfully!";
    
    $insert_notification = $db->prepare("
        INSERT INTO notifications (user_id, title, message, type) 
        VALUES (?, ?, ?, 'success')
    ");
    $insert_notification->execute([$user_id, $notification_title, $notification_message]);

    // Commit transaction
    $db->commit();

    // Send success response
    echo json_encode([
        'success' => true,
        'message' => 'Subscription activated successfully! Redirecting to dashboard...',
        'transaction_id' => $our_transaction_id,
        'user_id' => $user_id
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    if ($db->inTransaction()) {
        $db->rollback();
    }
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} catch (PDOException $e) {
    // Rollback transaction on database error
    if ($db->inTransaction()) {
        $db->rollback();
    }
    
    error_log("Database Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred. Please try again later.'
    ]);
}