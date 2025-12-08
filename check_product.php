<?php
$conn = new mysqli("localhost", "root", "", "laptop_advisor_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT product_id, product_name, is_active FROM products WHERE product_name LIKE '%Pavilion 15%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "FOUND:\n";
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["product_id"]. " - Name: " . $row["product_name"]. " - Active: " . $row["is_active"]. "\n";
    }
} else {
    echo "NOT FOUND";
}
$conn->close();
?>
