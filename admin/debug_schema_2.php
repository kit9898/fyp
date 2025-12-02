<?php
require_once 'includes/db_connect.php';

function describeTable($conn, $tableName) {
    echo "<h3>Table: $tableName</h3>";
    $result = $conn->query("DESCRIBE $tableName");
    if ($result) {
        echo "<table border='1'><tr><th>Field</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row['Field'] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "Error: " . $conn->error . "<br>";
    }
}

describeTable($conn, 'order_items');
?>
