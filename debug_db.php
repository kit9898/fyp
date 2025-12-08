<?php
$conn = new mysqli('localhost', 'root', '', 'laptop_advisor_db');

echo "Tables:\n";
$tables = [];
$res = $conn->query("SHOW TABLES");
while($row = $res->fetch_row()) {
    $tables[] = $row[0];
    echo "- " . $row[0] . "\n";
}

echo "\nSearching for 'BlackWidow' in all tables:\n";
foreach($tables as $table) {
    // Get columns
    $cols = [];
    $cres = $conn->query("SHOW COLUMNS FROM $table");
    while($crow = $cres->fetch_assoc()) {
        $cols[] = $crow['Field'];
    }
    
    // Construct search query
    $where = [];
    foreach($cols as $col) {
        $where[] = "`$col` LIKE '%BlackWidow%'";
    }
    $sql = "SELECT * FROM `$table` WHERE " . implode(" OR ", $where);
    
    try {
        $search = $conn->query($sql);
        if ($search && $search->num_rows > 0) {
            echo "FOUND IN TABLE '$table':\n";
            while($found = $search->fetch_assoc()) {
                print_r($found);
            }
        }
    } catch (Exception $e) {
        // ignore errors on incompatible types
    }
}
?>
