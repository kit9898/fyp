<?php
/**
 * Edit Administrator
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
$admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : 0;
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$role_id = isset($_POST['role_id']) ? intval($_POST['role_id']) : 0;
$status = $_POST['status'] ?? 'active';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validation
if ($admin_id <= 0 || empty($first_name) || empty($last_name) || empty($email) || empty($role_id)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit();
}

// Check if email exists for other users
$stmt = mysqli_prepare($conn, "SELECT admin_id FROM admin_users WHERE email = ? AND admin_id != ?");
mysqli_stmt_bind_param($stmt, "si", $email, $admin_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo json_encode(['success' => false, 'message' => 'Email address already used by another admin']);
    exit();
}
mysqli_stmt_close($stmt);

// Handle password update
$password_update_sql = "";
$types = "ssssisi";
$params = [$first_name, $last_name, $email, $phone, $role_id, $status, $admin_id];

if (!empty($password)) {
    if ($password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
        exit();
    }
    
    if (strlen($password) < 8) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long']);
        exit();
    }
    
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $password_update_sql = ", password_hash = ?";
    
    // Insert password param before ID
    array_splice($params, 6, 0, $password_hash); // Insert at index 6
    $types = "ssssissi"; // Add string type for password
}

// Update query
$query = "UPDATE admin_users SET first_name = ?, last_name = ?, email = ?, phone = ?, role_id = ?, status = ? $password_update_sql WHERE admin_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, $types, ...$params);

if (mysqli_stmt_execute($stmt)) {
    // Log activity (optional - fails gracefully if table doesn't exist)
    try {
        $log_query = "INSERT INTO admin_activity_log (admin_id, action, module, description, affected_record_type, affected_record_id, ip_address) VALUES (?, 'update', 'admin_users', ?, 'admin', ?, ?)";
        $log_stmt = mysqli_prepare($conn, $log_query);
        if ($log_stmt) {
            $current_admin_id = $_SESSION['admin_id'] ?? 0;
            $description = "Updated admin details: $first_name $last_name";
            $ip_address = $_SERVER['REMOTE_ADDR'];
            
            mysqli_stmt_bind_param($log_stmt, "isis", $current_admin_id, $description, $admin_id, $ip_address);
            @mysqli_stmt_execute($log_stmt);
            @mysqli_stmt_close($log_stmt);
        }
    } catch (Exception $e) {
        // Ignore logging errors
    }
    
    echo json_encode(['success' => true, 'message' => 'Administrator updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
