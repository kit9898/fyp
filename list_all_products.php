<?php
$conn = new mysqli('localhost', 'root', '', 'laptop_advisor_db');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$res = $conn->query("SELECT product_id, product_name, primary_use_case, price, is_active FROM products");
echo "Total Products: " . $res->num_rows . "\n";
echo "ID | Name | Category | Price | Active\n";
echo str_repeat("-", 50) . "\n";

while($row = $res->fetch_assoc()) {
    // highlighting suspected matches
    if (stripos($row['product_name'], 'Razer') !== false || stripos($row['product_name'], 'BlackWidow') !== false) {
         echo ">>> FOUND MATCH: ";
    }
    echo $row['product_id'] . " | " . $row['product_name'] . " | " . $row['primary_use_case'] . " | " . $row['price'] . " | " . $row['is_active'] . "\n";
}
?>
