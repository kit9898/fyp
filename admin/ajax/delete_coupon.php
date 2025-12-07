<?php
/**
 * Delete Coupon
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

// Get JSON Input
$data = json_decode(file_get_contents('php://input'), true);
$coupon_id = $data['coupon_id'] ?? 0;

if (!$coupon_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    exit();
}

// Delete coupon
$stmt = mysqli_prepare($conn, "DELETE FROM coupons WHERE coupon_id = ?");
mysqli_stmt_bind_param($stmt, "i", $coupon_id);

if (mysqli_stmt_execute($stmt)) {
    // Log activity
    try {
        $log_query = "INSERT INTO admin_activity_log (admin_id, action, module, description, affected_record_type, affected_record_id, ip_address) VALUES (?, 'delete', 'coupons', ?, 'coupon', ?, ?)";
        $log_stmt = mysqli_prepare($conn, $log_query);
        
        if ($log_stmt) {
            $current_admin_id = $_SESSION['admin_id'] ?? 0;
            $description = "Deleted coupon ID: $coupon_id";
            $ip_address = $_SERVER['REMOTE_ADDR'];
            
            mysqli_stmt_bind_param($log_stmt, "isis", $current_admin_id, $description, $coupon_id, $ip_address);
            @mysqli_stmt_execute($log_stmt);
            @mysqli_stmt_close($log_stmt);
        }
    } catch (Exception $e) {
        // Ignore logging errors
    }
    
    echo json_encode(['success' => true, 'message' => 'Coupon deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
