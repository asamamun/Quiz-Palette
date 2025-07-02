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
    // Get input data (no validation)
    $user_name = trim($_POST['user_name'] ?? '');
    $user_email = trim($_POST['user_email'] ?? '');
    $user_phone = trim($_POST['user_phone'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');
    $transaction_id = trim($_POST['transaction_id'] ?? '');
    $payment_phone = trim($_POST['payment_phone'] ?? '');
    $plan_type = trim($_POST['plan_type'] ?? '');
    $amount = floatval($_POST['amount'] ?? 0);
    $exam_id = intval($_POST['exam_id'] ?? 0);
    $duration = trim($_POST['duration'] ?? 'monthly');

    // Start transaction
    $db->beginTransaction();

    // Check if user exists, if not create one
    $user_id = null;
    $check_user = $db->prepare("SELECT id FROM users WHERE email = ?");
    $check_user->execute([$user_email]);
    
    if ($check_user->rowCount() > 0) {
        $user = $check_user->fetch();
        $user_id = $user['id'];
    } else {
        $names = explode(' ', $user_name, 2);
        $first_name = $names[0] ?? '';
        $last_name = isset($names[1]) ? $names[1] : '';
        $username = $user_name ? (strtolower(str_replace(' ', '', $user_name)) . rand(100, 999)) : ('user' . rand(100, 999));
        $password = password_hash('temppass123', PASSWORD_DEFAULT);

        $create_user = $db->prepare("
            INSERT INTO users (username, email, password, first_name, last_name, phone, role, status)
            VALUES (?, ?, ?, ?, ?, ?, 'user', 'active')
        ");
        $create_user->execute([$username, $user_email, $password, $first_name, $last_name, $user_phone]);
        $user_id = $db->lastInsertId();
    }

    // Generate unique transaction ID
    $our_transaction_id = 'QP' . date('YmdHis') . rand(1000, 9999);

    // Insert payment record
    $insert_payment = $db->prepare("
        INSERT INTO payments (
            user_id, exam_id, transaction_id, bkash_transaction_id, amount, 
            currency, payment_method, status, verification_status, payment_date
        ) VALUES (?, ?, ?, ?, ?, 'BDT', ?, 'pending', 'pending', NOW())
    ");
    $insert_payment->execute([$user_id, $exam_id, $our_transaction_id, $transaction_id, $amount, $payment_method]);
    $payment_id = $db->lastInsertId();

    // Insert subscription record (without start_date and end_date)
    $insert_subscription = $db->prepare("
        INSERT INTO subscriptions (
            user_id, plan_name, status, amount, currency, payment_id
        ) VALUES (?, ?, 'pending', ?, 'BDT', ?)
    ");
    $insert_subscription->execute([$user_id, $plan_type, $amount, $payment_id]);

    // Create notification for user
    $notification_title = "Payment Submitted";
    $notification_message = "Your {$plan_type} subscription payment is pending admin approval.";
    $insert_notification = $db->prepare("
        INSERT INTO notifications (user_id, title, message, type)
        VALUES (?, ?, ?, 'info')
    ");
    $insert_notification->execute([$user_id, $notification_title, $notification_message]);

    // Commit transaction
    $db->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Payment submitted successfully. Awaiting admin approval.',
        'transaction_id' => $our_transaction_id,
        'user_id' => $user_id
    ]);
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (PDOException $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    error_log("Database Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred. Please try again later.']);
}
?>