<?php
require_once 'includes/auth_check.php'; // Ensures only logged-in users can access this

$user_id = $_SESSION['user_id'];

// --- HANDLE DETAILS & PICTURE UPDATE ---
if (isset($_POST['update_details'])) {
    $full_name = trim($_POST['full_name']);
    $image_path = '';
    $primary_use_case = trim($_POST['primary_use_case']); 
    
    // Get address fields
    $default_shipping_name = trim($_POST['default_shipping_name'] ?? '');
    $default_shipping_address = trim($_POST['default_shipping_address'] ?? '');
    $default_shipping_city = trim($_POST['default_shipping_city'] ?? '');
    $default_shipping_state = trim($_POST['default_shipping_state'] ?? '');
    $default_shipping_zip = trim($_POST['default_shipping_zip'] ?? '');
    $default_shipping_country = trim($_POST['default_shipping_country'] ?? '');
    $default_shipping_phone = trim($_POST['default_shipping_phone'] ?? '');

    // Handle file upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png'];
        if (in_array($_FILES['profile_image']['type'], $allowed_types)) {
            $upload_dir = 'uploads/';
            $filename = uniqid() . '-' . basename($_FILES['profile_image']['name']);
            $target_file = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                $image_path = $target_file;
            } else {
                header("Location: edit_profile.php?error=Failed to upload image.");
                exit();
            }
        } else {
            header("Location: edit_profile.php?error=Invalid image type. Only JPEG and PNG allowed.");
            exit();
        }
    }

    // Update database with new details including address
    if (!empty($image_path)) {
        // Update with new image
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, primary_use_case = ?, profile_image_url = ?, 
                                default_shipping_name = ?, default_shipping_address = ?, default_shipping_city = ?, 
                                default_shipping_state = ?, default_shipping_zip = ?, default_shipping_country = ?, 
                                default_shipping_phone = ? 
                                WHERE user_id = ?");
        $stmt->bind_param("ssssssssssi", $full_name, $primary_use_case, $image_path, 
                         $default_shipping_name, $default_shipping_address, $default_shipping_city, 
                         $default_shipping_state, $default_shipping_zip, $default_shipping_country, 
                         $default_shipping_phone, $user_id);
    } else {
        // Update without changing image
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, primary_use_case = ?, 
                                default_shipping_name = ?, default_shipping_address = ?, default_shipping_city = ?, 
                                default_shipping_state = ?, default_shipping_zip = ?, default_shipping_country = ?, 
                                default_shipping_phone = ? 
                                WHERE user_id = ?");
        $stmt->bind_param("sssssssssi", $full_name, $primary_use_case, 
                         $default_shipping_name, $default_shipping_address, $default_shipping_city, 
                         $default_shipping_state, $default_shipping_zip, $default_shipping_country, 
                         $default_shipping_phone, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: edit_profile.php?status=details_success");
    } else {
        header("Location: edit_profile.php?error=Failed to update profile.");
    }
    $stmt->close();
    exit();
}


// --- HANDLE PASSWORD UPDATE ---
if (isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords
    if ($new_password !== $confirm_password) {
        header("Location: edit_profile.php?pwd_error=New passwords do not match.");
        exit();
    }
    if (strlen($new_password) < 6) {
         header("Location: edit_profile.php?pwd_error=Password must be at least 6 characters.");
        exit();
    }

    // Fetch current password hash from DB
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Verify current password
    if (password_verify($current_password, $user['password_hash'])) {
        // Current password is correct, hash the new one
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update the database
        $update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        $update_stmt->bind_param("si", $new_password_hash, $user_id);
        
        if ($update_stmt->execute()) {
            header("Location: edit_profile.php?status=password_success");
        } else {
            header("Location: edit_profile.php?pwd_error=Failed to update password.");
        }
        $update_stmt->close();
    } else {
        header("Location: edit_profile.php?pwd_error=Incorrect current password.");
    }
    $stmt->close();
    exit();
}

// Redirect back if no form was submitted
header("Location: profile.php");
exit();
?>