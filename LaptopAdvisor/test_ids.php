<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    require_once 'includes/db_connect.php';
    $res = $conn->query("SELECT product_id, product_name, product_category FROM products WHERE product_category = 'mouse' LIMIT 5");
    while($row = $res->fetch_assoc()) {
        echo $row['product_id'] . ': ' . $row['product_name'] . ' (' . $row['product_category'] . ")\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
