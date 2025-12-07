<?php
/**
 * Add New Role
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
$role_name = trim($_POST['role_name'] ?? '');
$role_code = trim($_POST['role_code'] ?? '');
$description = trim($_POST['description'] ?? '');
$status = $_POST['status'] ?? 'active';

// Validation
if (empty($role_name) || empty($role_code)) {
    echo json_encode(['success' => false, 'message' => 'Role Name and Code are required']);
    exit();
}

// Validate role code format (only lowercase, numbers, underscores)
if (!preg_match('/^[a-z0-9_]+$/', $role_code)) {
    echo json_encode(['success' => false, 'message' => 'Role Code must contain only lowercase letters, numbers, and underscores']);
    exit();
}

// Check if role code already exists
$stmt = mysqli_prepare($conn, "SELECT role_id FROM roles WHERE role_code = ?");
mysqli_stmt_bind_param($stmt, "s", $role_code);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo json_encode(['success' => false, 'message' => 'Role Code already exists']);
    exit();
}
mysqli_stmt_close($stmt);

// Insert new role
$query = "INSERT INTO roles (role_name, role_code, description, status, is_system_role) VALUES (?, ?, ?, ?, 0)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssss", $role_name, $role_code, $description, $status);

if (mysqli_stmt_execute($stmt)) {
    // Log activity (optional - fails gracefully if table doesn't exist)
    try {
        $role_id = mysqli_insert_id($conn);
        $log_query = "INSERT INTO admin_activity_log (admin_id, action, module, description, affected_record_type, affected_record_id, ip_address) VALUES (?, 'create', 'roles', ?, 'role', ?, ?)";
        $log_stmt = mysqli_prepare($conn, $log_query);
        if ($log_stmt) {
            $current_admin_id = $_SESSION['admin_id'] ?? 0;
            $log_desc = "Created new role: $role_name";
            $ip_address = $_SERVER['REMOTE_ADDR'];
            
            mysqli_stmt_bind_param($log_stmt, "isis", $current_admin_id, $log_desc, $role_id, $ip_address);
            @mysqli_stmt_execute($log_stmt);
            @mysqli_stmt_close($log_stmt);
        }
    } catch (Exception $e) {
        // Ignore logging errors
    }
    
    echo json_encode(['success' => true, 'message' => 'Role added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
