<?php
require 'admin/includes/db_connect.php';

$sql = "SELECT log_id, ip_address, user_agent FROM admin_activity_log ORDER BY created_at DESC LIMIT 10";
$result = $conn->query($sql);

echo "Log Data Inspection:\n";
echo "--------------------------------------------------\n";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row['log_id'] . "\n";
        echo "IP: " . $row['ip_address'] . "\n";
        echo "UA: " . ($row['user_agent'] ? $row['user_agent'] : '[EMPTY]') . "\n";
        echo "--------------------------------------------------\n";
    }
} else {
    echo "No logs found.\n";
}
?>
