<?php
/**
 * Edit Coupon
 */

// Disable error reporting for production/AJAX to prevent JSON breakage
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

require_once '../includes/db_connect.php';

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Get form data
$coupon_id = intval($_POST['coupon_id'] ?? 0);
$code = strtoupper(trim($_POST['code'] ?? ''));
$discount_type = $_POST['discount_type'] ?? 'percentage';
$discount_value = floatval($_POST['discount_value'] ?? 0);
$expiry_date = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : NULL;
$is_active = isset($_POST['is_active']) ? intval($_POST['is_active']) : 1;

if ($coupon_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid coupon ID']);
    exit();
}

// Validation
if (empty($code)) {
    echo json_encode(['success' => false, 'message' => 'Coupon code is required']);
    exit();
}

if ($discount_value <= 0) {
    echo json_encode(['success' => false, 'message' => 'Discount value must be greater than 0']);
    exit();
}

// Check if code exists for OTHER coupon
$stmt = mysqli_prepare($conn, "SELECT coupon_id FROM coupons WHERE code = ? AND coupon_id != ?");
mysqli_stmt_bind_param($stmt, "si", $code, $coupon_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo json_encode(['success' => false, 'message' => 'Coupon code already exists']);
    exit();
}
mysqli_stmt_close($stmt);

// Update coupon
$query = "UPDATE coupons SET code = ?, discount_type = ?, discount_value = ?, expiry_date = ?, is_active = ? WHERE coupon_id = ?";
$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param($stmt, "ssdsii", $code, $discount_type, $discount_value, $expiry_date, $is_active, $coupon_id);

if (mysqli_stmt_execute($stmt)) {
    // Log activity
    try {
        $log_query = "INSERT INTO admin_activity_log (admin_id, action, module, description, affected_record_type, affected_record_id, ip_address) VALUES (?, 'update', 'coupons', ?, 'coupon', ?, ?)";
        $log_stmt = mysqli_prepare($conn, $log_query);
        
        if ($log_stmt) {
            $current_admin_id = $_SESSION['admin_id'] ?? 0;
            $description = "Updated coupon: $code";
            $ip_address = $_SERVER['REMOTE_ADDR'];
            
            mysqli_stmt_bind_param($log_stmt, "isis", $current_admin_id, $description, $coupon_id, $ip_address);
            @mysqli_stmt_execute($log_stmt);
            @mysqli_stmt_close($log_stmt);
        }
    } catch (Exception $e) {
        // Ignore logging errors
    }
    
    echo json_encode(['success' => true, 'message' => 'Coupon updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
