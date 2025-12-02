<?php
require_once 'includes/db_connect.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Orders Table Dump</h2>";

$sql = "SELECT * FROM orders LIMIT 10";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'><tr>";
    // Fetch fields
    while ($field = $result->fetch_field()) {
        echo "<th>" . $field->name . "</th>";
    }
    echo "</tr>";

    // Fetch data
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach($row as $value) {
            echo "<td>" . $value . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

// Check date format specifically
echo "<h2>Date Format Check</h2>";
$sql = "SELECT order_date FROM orders LIMIT 5";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    echo "Original: " . $row['order_date'] . " | DATE(): " . date('Y-m-d', strtotime($row['order_date'])) . "<br>";
}

$conn->close();
?>
