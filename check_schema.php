<?php
require_once 'LaptopAdvisor/includes/db_connect.php';

// Check if customer_email column exists in conversations table
$check = $conn->query("SHOW COLUMNS FROM conversations LIKE 'customer_email'");

if ($check->num_rows == 0) {
    echo "Column not found. Adding it...\n";
    $sql = "ALTER TABLE conversations ADD COLUMN customer_email VARCHAR(255) DEFAULT NULL AFTER user_id";
    if ($conn->query($sql)) {
        echo "Column added successfully.\n";
    } else {
        echo "Error adding column: " . $conn->error . "\n";
    }
} else {
    echo "Column already exists.\n";
}

// Also check to make sure products table has necessary colums for cross-selling
// We need 'product_category' (I know it exists but good to double check content)
echo "\nChecking product categories:\n";
$cats = $conn->query("SELECT DISTINCT product_category FROM products LIMIT 5");
while($row = $cats->fetch_assoc()) {
    print_r($row);
}
?>
