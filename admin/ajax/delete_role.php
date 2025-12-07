<?php
/**
 * Delete Role
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

$input = json_decode(file_get_contents('php://input'), true);
$role_id = $input['role_id'] ?? 0;

if ($role_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid role ID']);
    exit();
}

// Check details
$stmt = mysqli_prepare($conn, "SELECT is_system_role FROM roles WHERE role_id = ?");
mysqli_stmt_bind_param($stmt, "i", $role_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$role = mysqli_fetch_assoc($result);

if (!$role) {
    echo json_encode(['success' => false, 'message' => 'Role not found']);
    exit();
}

if ($role['is_system_role'] == 1) {
    echo json_encode(['success' => false, 'message' => 'Cannot delete system roles']);
    exit();
}

// Check usage
$check_usage = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM admin_users WHERE role_id = ?");
mysqli_stmt_bind_param($check_usage, "i", $role_id);
mysqli_stmt_execute($check_usage);
$usage_result = mysqli_fetch_assoc(mysqli_stmt_get_result($check_usage));

if ($usage_result['count'] > 0) {
    echo json_encode(['success' => false, 'message' => 'Cannot delete role because it is assigned to users. Reassign them first.']);
    exit();
}

// Delete role permissions first
mysqli_query($conn, "DELETE FROM role_permissions WHERE role_id = $role_id");

// Delete role
$query = "DELETE FROM roles WHERE role_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $role_id);

if (mysqli_stmt_execute($stmt)) {
    // Log activity (optional - fails gracefully if table doesn't exist)
    try {
        $log_query = "INSERT INTO admin_activity_log (admin_id, action, module, description, affected_record_type, affected_record_id, ip_address) VALUES (?, 'delete', 'roles', ?, 'role', ?, ?)";
        $log_stmt = mysqli_prepare($conn, $log_query);
        if ($log_stmt) {
            $current_admin_id = $_SESSION['admin_id'] ?? 0;
            $log_desc = "Deleted role ID: $role_id";
            $ip_address = $_SERVER['REMOTE_ADDR'];
            
            mysqli_stmt_bind_param($log_stmt, "isis", $current_admin_id, $log_desc, $role_id, $ip_address);
            @mysqli_stmt_execute($log_stmt);
            @mysqli_stmt_close($log_stmt);
        }
    } catch (Exception $e) {
        // Ignore logging errors
    }
    
    echo json_encode(['success' => true, 'message' => 'Role deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
