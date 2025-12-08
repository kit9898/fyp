<?php
$conn = new mysqli("localhost", "root", "", "laptop_advisor_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fix ID 8 - Point to valid existing webp file
$sql8 = "UPDATE products SET image_url = 'LaptopAdvisor/images/product_8_692eee1113b23.webp' WHERE product_id = 8";
if ($conn->query($sql8) === TRUE) echo "Record 8 updated successfully\n";
else echo "Error updating record 8: " . $conn->error . "\n";

// Fix ID 117 - Point to valid existing png file (hash matches old db entry)
$sql117 = "UPDATE products SET image_url = 'LaptopAdvisor/images/product_692d9b1204696.png' WHERE product_id = 117";
if ($conn->query($sql117) === TRUE) echo "Record 117 updated successfully\n";
else echo "Error updating record 117: " . $conn->error . "\n";

$conn->close();
?>
