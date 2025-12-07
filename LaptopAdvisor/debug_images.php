<?php
require_once 'includes/db_connect.php';

$names = ['Logitech G', 'Razer BlackWidow V3', 'SteelSeries Arctis 7'];
$placeholders = implode(',', array_fill(0, count($names), '?'));
$types = str_repeat('s', count($names));

$sql = "SELECT product_name, image_url FROM products WHERE product_name IN ($placeholders) OR product_name LIKE '%Logitech%' OR product_name LIKE '%Razer%' OR product_name LIKE '%SteelSeries%'";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$names);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "Product: " . $row['product_name'] . "\n";
    echo "Image URL: " . $row['image_url'] . "\n";
    echo "File Exists: " . (file_exists($row['image_url']) ? 'Yes' : 'No') . "\n";
    echo "-------------------\n";
}
?>
