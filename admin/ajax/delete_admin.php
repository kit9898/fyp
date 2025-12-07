<?php
/**
 * Delete Administrator
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

// Check permission logic here if needed (e.g. only super admin can delete)

require_once '../includes/db_connect.php';

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$admin_id = $input['admin_id'] ?? 0;

if ($admin_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid admin ID']);
    exit();
}

// Prevent deleting self
if ($admin_id == $_SESSION['admin_id']) {
    echo json_encode(['success' => false, 'message' => 'You cannot delete your own account']);
    exit();
}

// Check if trying to delete a Super Admin (role_id 1 usually)
// Assuming role_id 1 is Super Admin who cannot be deleted easily, or perhaps just check status
$stmt = mysqli_prepare($conn, "SELECT role_id FROM admin_users WHERE admin_id = ?");
mysqli_stmt_bind_param($stmt, "i", $admin_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$admin_data = mysqli_fetch_assoc($result);

if (!$admin_data) {
    echo json_encode(['success' => false, 'message' => 'Administrator not found']);
    exit();
}

// For this implementation, we will delete the record.
// Alternatively, setting status to 'deleted' or 'suspended' is safer.
// Let's delete it for now as per "Delete" action implies removal.

$query = "DELETE FROM admin_users WHERE admin_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $admin_id);

if (mysqli_stmt_execute($stmt)) {
    // Log activity (optional - fails gracefully if table doesn't exist)
    try {
        $log_query = "INSERT INTO admin_activity_log (admin_id, action, module, description, affected_record_type, affected_record_id, ip_address) VALUES (?, 'delete', 'admin_users', ?, 'admin', ?, ?)";
        $log_stmt = mysqli_prepare($conn, $log_query);
        if ($log_stmt) {
            $current_admin_id = $_SESSION['admin_id'] ?? 0;
            $description = "Deleted admin ID: $admin_id";
            $ip_address = $_SERVER['REMOTE_ADDR'];
            
            mysqli_stmt_bind_param($log_stmt, "isis", $current_admin_id, $description, $admin_id, $ip_address);
            @mysqli_stmt_execute($log_stmt);
            @mysqli_stmt_close($log_stmt);
        }
    } catch (Exception $e) {
        // Ignore logging errors
    }
    
    echo json_encode(['success' => true, 'message' => 'Administrator deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
