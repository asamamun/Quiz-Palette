<?php
session_start();
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

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

$database = new Database();
$db = $database->getConnection();

try {
    $payment_id = $_POST['payment_id'] ?? null;
    $admin_id = $_SESSION['user_id'] ?? 1; // Replace with actual admin ID

    $db->beginTransaction();

    // Get payment details
    $stmt = $db->prepare("
        SELECT p.user_id, p.amount, p.currency, p.exam_id, s.plan_name, s.duration
        FROM payments p
        LEFT JOIN subscriptions s ON p.id = s.payment_id
        WHERE p.id = ?
    ");
    $stmt->execute([$payment_id]);
    $payment = $stmt->fetch();

    // Update payment
    $stmt = $db->prepare("
        UPDATE payments
        SET status = 'completed', verification_status = 'verified', verified_by = ?, verified_at = NOW()
        WHERE id = ?
    ");
    $stmt->execute([$admin_id, $payment_id]);

    // Calculate subscription dates
    $start_date = date('Y-m-d H:i:s');
    $end_date = ($payment['duration'] ?? 'monthly') === 'yearly' ? date('Y-m-d H:i:s', strtotime('+1 year')) : date('Y-m-d H:i:s', strtotime('+1 month'));

    // Update subscription
    $stmt = $db->prepare("
        UPDATE subscriptions
        SET start_date = ?, end_date = ?, status = 'active', amount = ?, currency = ?
        WHERE payment_id = ?
    ");
    $stmt->execute([$start_date, $end_date, $payment['amount'] ?? 0, $payment['currency'] ?? 'BDT', $payment_id]);

    // Create notification for user
    $notification_title = "Subscription Activated";
    $notification_message = "Your {$payment['plan_name']} subscription has been activated!";
    $stmt = $db->prepare("
        INSERT INTO notifications (user_id, title, message, type)
        VALUES (?, ?, ?, 'success')
    ");
    $stmt->execute([$payment['user_id'] ?? 0, $notification_title, $notification_message]);

    $db->commit();

    echo json_encode(['success' => true, 'message' => 'Payment approved and subscription activated']);
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
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>