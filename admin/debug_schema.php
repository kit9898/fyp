<?php
require_once 'includes/db_connect.php';

function describeTable($conn, $tableName) {
    echo "<h3>Table: $tableName</h3>";
    $result = $conn->query("DESCRIBE $tableName");
    if ($result) {
        echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $val) echo "<td>$val</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Table $tableName does not exist or error: " . $conn->error . "<br>";
    }
}

$tables = ['products', 'order_items', 'chat_history', 'recommendation_logs', 'users', 'orders'];

foreach ($tables as $table) {
    describeTable($conn, $table);
}
?>
