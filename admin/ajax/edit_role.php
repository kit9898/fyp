<?php
/**
 * Edit Role
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
$role_id = isset($_POST['role_id']) ? intval($_POST['role_id']) : 0;
$role_name = trim($_POST['role_name'] ?? '');
// role_code is usually immutable or check carefully
$description = trim($_POST['description'] ?? '');
$status = $_POST['status'] ?? 'active';

// Validation
if ($role_id <= 0 || empty($role_name)) {
    echo json_encode(['success' => false, 'message' => 'Role ID and Name are required']);
    exit();
}

// Check if role is system role (optional protection)
$stmt = mysqli_prepare($conn, "SELECT is_system_role FROM roles WHERE role_id = ?");
mysqli_stmt_bind_param($stmt, "i", $role_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$role_data = mysqli_fetch_assoc($result);

if (!$role_data) {
     echo json_encode(['success' => false, 'message' => 'Role not found']);
     exit();
}

// Update query
$query = "UPDATE roles SET role_name = ?, description = ?, status = ? WHERE role_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sssi", $role_name, $description, $status, $role_id);

if (mysqli_stmt_execute($stmt)) {
    // Log activity (optional - fails gracefully if table doesn't exist)
    try {
        $log_query = "INSERT INTO admin_activity_log (admin_id, action, module, description, affected_record_type, affected_record_id, ip_address) VALUES (?, 'update', 'roles', ?, 'role', ?, ?)";
        $log_stmt = mysqli_prepare($conn, $log_query);
        if ($log_stmt) {
            $current_admin_id = $_SESSION['admin_id'] ?? 0;
            $log_desc = "Updated role: $role_name";
            $ip_address = $_SERVER['REMOTE_ADDR'];
            
            mysqli_stmt_bind_param($log_stmt, "isis", $current_admin_id, $log_desc, $role_id, $ip_address);
            @mysqli_stmt_execute($log_stmt);
            @mysqli_stmt_close($log_stmt);
        }
    } catch (Exception $e) {
        // Ignore logging errors
    }
    
    echo json_encode(['success' => true, 'message' => 'Role updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
