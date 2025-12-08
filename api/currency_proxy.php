<?php
header('Content-Type: application/json');

// Cache file path
$cacheFile = '../uploads/currency_rates.json';
$cacheTime = 3600; // 1 hour

// Check if cache exists and is fresh
if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    echo file_get_contents($cacheFile);
    exit;
}

// Fetch new rates
$apiUrl = 'https://open.er-api.com/v6/latest/USD';

try {
    $response = file_get_contents($apiUrl);
    
    if ($response === FALSE) {
        throw new Exception("Failed to fetch rates");
    }

    $data = json_decode($response, true);
    
    if (!isset($data['result']) || $data['result'] !== 'success') {
        throw new Exception("Invalid API response");
    }

    // Filter only needed currencies to save space (optional, but good practice)
    $neededRates = [
        'USD' => $data['rates']['USD'],
        'MYR' => $data['rates']['MYR'],
        'CNY' => $data['rates']['CNY']
    ];

    $output = [
        'status' => 'success',
        'updated' => time(),
        'rates' => $neededRates
    ];

    $jsonOutput = json_encode($output);

    // Save to cache
    file_put_contents($cacheFile, $jsonOutput);

    echo $jsonOutput;

} catch (Exception $e) {
    // Fallback if API fails: Try to serve stale cache
    if (file_exists($cacheFile)) {
        echo file_get_contents($cacheFile);
    } else {
        // Hard fallback if no cache
        echo json_encode([
            'status' => 'error', 
            'message' => $e->getMessage(),
            'rates' => ['USD' => 1, 'MYR' => 4.5, 'CNY' => 7.2] // Rough estimates
        ]);
    }
}
?>
