<?php
// Mock session
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'TestUser';

// Get IDs from CLI
$input_ids = isset($argv[1]) ? $argv[1] : '';
$_GET['ids'] = $input_ids;

// Capture output
ob_start();
try {
    include 'compare.php';
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
$output = ob_get_clean();

// Analyze
$hasPerformance = strpos($output, '⚡ Performance Score') !== false;
$hasDescription = strpos($output, '📝 Description') !== false;
$hasMixedError = strpos($output, 'Mixed Categories Selected') !== false;
$hasRAM = strpos($output, '💾 RAM') !== false;

echo "Input IDs: $input_ids\n";
echo "Has Performance: " . ($hasPerformance ? 'Yes' : 'No') . "\n";
echo "Has Description: " . ($hasDescription ? 'Yes' : 'No') . "\n";
echo "Has Mixed Error: " . ($hasMixedError ? 'Yes' : 'No') . "\n";
echo "Has RAM: " . ($hasRAM ? 'Yes' : 'No') . "\n";
?>
