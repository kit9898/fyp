<?php
// c:\xampp\htdocs\fyp\check_images.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laptop_advisor_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$sql = "SELECT product_id, product_name, image_url FROM products";
$result = $conn->query($sql);
$web_root = 'c:/xampp/htdocs/fyp/'; 

echo "Checking " . $result->num_rows . " products...\n";
$missing_count = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $db_url = $row['image_url'];
        
        // Skip placeholders or empty
        if (empty($db_url) || strpos($db_url, 'placeholder') !== false) continue;

        $final_filepath = $web_root . $db_url;
        
        // Logic mirroring product_details.php path resolution
        if (strpos($db_url, 'LaptopAdvisor/') === 0) {
             $final_filepath = $web_root . $db_url;
        } else if (strpos($db_url, 'images/') === 0) {
             // Try LaptopAdvisor/images/ first
             $try_path = $web_root . 'LaptopAdvisor/' . $db_url;
             if (file_exists($try_path)) {
                 $final_filepath = $try_path;
             } else {
                 $final_filepath = $web_root . $db_url;
             }
        } else {
             // Assume filename, look in LaptopAdvisor/images/
             $final_filepath = $web_root . 'LaptopAdvisor/images/' . basename($db_url);
        }
        
        if (!file_exists($final_filepath)) {
            $missing_count++;
            echo "MISSING: ID: {$row['product_id']} | Name: {$row['product_name']} | DB URL: $db_url | Resolved: $final_filepath\n";
        }
    }
}

if ($missing_count == 0) {
    echo "SUCCESS: All image paths are valid!\n";
} else {
    echo "FOUND $missing_count MISSING IMAGES.\n";
}
$conn->close();
?>
