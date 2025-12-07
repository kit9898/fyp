<?php
require_once 'includes/db_connect.php';

// Check if profile_image column exists in users table
$result = $conn->query("SHOW COLUMNS FROM users LIKE 'profile_image'");
if ($result->num_rows > 0) {
    echo "Column 'profile_image' exists.\n";
} else {
    echo "Column 'profile_image' does NOT exist.\n";
    
    // Add the column
    $sql = "ALTER TABLE users ADD COLUMN profile_image VARCHAR(255) DEFAULT 'default.png'";
    if ($conn->query($sql) === TRUE) {
        echo "Column 'profile_image' added successfully.\n";
    } else {
        echo "Error adding column: " . $conn->error . "\n";
    }
}

// Show all columns
$result = $conn->query("SHOW COLUMNS FROM users");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
?>
