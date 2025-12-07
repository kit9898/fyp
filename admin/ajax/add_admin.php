<?php
/**
 * Add New Administrator
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
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$role_id = isset($_POST['role_id']) ? intval($_POST['role_id']) : 0;
$status = $_POST['status'] ?? 'active';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validation
if (empty($first_name) || empty($last_name) || empty($email) || empty($role_id) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit();
}

if ($password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
    exit();
}

if (strlen($password) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long']);
    exit();
}

// Check if email already exists
$stmt = mysqli_prepare($conn, "SELECT admin_id FROM admin_users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo json_encode(['success' => false, 'message' => 'Email address already exists']);
    exit();
}
mysqli_stmt_close($stmt);

// Generate Admin Code (e.g., ADMIN-005)
$result = mysqli_query($conn, "SELECT MAX(admin_id) as max_id FROM admin_users");
$row = mysqli_fetch_assoc($result);
$next_id = ($row['max_id'] ?? 0) + 1;
$admin_code = 'ADMIN-' . str_pad($next_id, 3, '0', STR_PAD_LEFT);

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert new admin
$query = "INSERT INTO admin_users (admin_code, first_name, last_name, email, phone, password_hash, role_id, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
$created_by = $_SESSION['admin_id'] ?? null;

mysqli_stmt_bind_param($stmt, "ssssssisi", $admin_code, $first_name, $last_name, $email, $phone, $password_hash, $role_id, $status, $created_by);

if (mysqli_stmt_execute($stmt)) {
    // Log activity (optional - fails gracefully if table doesn't exist)
    try {
        $admin_id = mysqli_insert_id($conn);
        $log_query = "INSERT INTO admin_activity_log (admin_id, action, module, description, affected_record_type, affected_record_id, ip_address) VALUES (?, 'create', 'admin_users', ?, 'admin', ?, ?)";
        $log_stmt = mysqli_prepare($conn, $log_query);
        if ($log_stmt) {
            $current_admin_id = $_SESSION['admin_id'] ?? 0;
            $description = "Created new admin: $first_name $last_name ($email)";
            $ip_address = $_SERVER['REMOTE_ADDR'];
            
            mysqli_stmt_bind_param($log_stmt, "isis", $current_admin_id, $description, $admin_id, $ip_address);
            @mysqli_stmt_execute($log_stmt);
            @mysqli_stmt_close($log_stmt);
        }
    } catch (Exception $e) {
        // Ignore logging errors
    }
    
    echo json_encode(['success' => true, 'message' => 'Administrator added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
