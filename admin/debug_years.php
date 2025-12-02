<?php
require_once 'includes/db_connect.php';

$sql = "SELECT YEAR(order_date) as year, COUNT(*) as count FROM orders GROUP BY YEAR(order_date)";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    echo "Year: " . $row['year'] . " - Count: " . $row['count'] . "\n";
}
?>
