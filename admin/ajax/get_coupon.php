<?php
/**
 * Get Coupon Details
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

$coupon_id = intval($_GET['id'] ?? 0);

if ($coupon_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT * FROM coupons WHERE coupon_id = ?");
mysqli_stmt_bind_param($stmt, "i", $coupon_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode(['success' => true, 'data' => $row]);
} else {
    echo json_encode(['success' => false, 'message' => 'Coupon not found']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
