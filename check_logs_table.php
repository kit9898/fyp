<?php
require 'admin/includes/db_connect.php';

$table = 'admin_activity_log';
$result = $conn->query("SHOW TABLES LIKE '$table'");

if ($result->num_rows > 0) {
    echo "Table '$table' exists.\n";
    $columns = $conn->query("DESCRIBE $table");
    while ($row = $columns->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Table '$table' does not exist.\n";
}
?>
