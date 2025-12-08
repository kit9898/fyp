<?php
header('Content-Type: application/json');
require_once '../includes/config.php';
require_once '../includes/ollama_client.php';

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$spendingData = $input['spending_data'] ?? [];

if (empty($spendingData)) {
    echo json_encode(['success' => false, 'error' => 'No spending data provided']);
    exit;
}

// Construct summary for AI
$dataText = "User Spending Data:\n";
$dataText .= "Top Brands: " . implode(", ", array_map(
    function($k, $v) { return "$k ($".number_format($v,2).")"; }, 
    array_keys($spendingData['brand']), 
    $spendingData['brand']
)) . "\n";
$dataText .= "Top Categories: " . implode(", ", array_map(
    function($k, $v) { return "$k ($".number_format($v,2).")"; }, 
    array_keys($spendingData['category']), 
    $spendingData['category']
)) . "\n";
$dataText .= "Primary Use Cases: " . implode(", ", array_map(
    function($k, $v) { return "$k ($".number_format($v,2).")"; }, 
    array_keys($spendingData['use_case']), 
    $spendingData['use_case']
)) . "\n";

// Monthly Trend summary
$trendText = "";
if (!empty($spendingData['trend']['labels'])) {
    $trendText = "Monthly Trend: ";
    foreach ($spendingData['trend']['labels'] as $i => $month) {
        $amount = $spendingData['trend']['data'][$i] ?? 0;
        $trendText .= "$month: $" . number_format($amount, 2) . "; ";
    }
}
$dataText .= $trendText . "\n";

$prompt = "You are a financial advisor for a tech enthusiast. Analyze the following spending data:\n\n";
$prompt .= $dataText;
$prompt .= "\nTask: Provide a concise, friendly financial insight summary (approx. 100 words).\n";
$prompt .= "1. Comment on their brand loyalty or preference.\n";
$prompt .= "2. Observe usage patterns (e.g., 'You invest heavily in gaming gear').\n";
$prompt .= "3. Give a short tip on future purchases based on this trend.\n";
$prompt .= "Format: Return the response as clean HTML using <p> tags. Do NOT use markdown. Start directly with the insight.";

try {
    $ollama = new OllamaClient(OLLAMA_API_URL, OLLAMA_MODEL, OLLAMA_TIMEOUT);
    
    $response = $ollama->chat([
        ['role' => 'system', 'content' => 'You are a helpful financial assistant. Output HTML only.'],
        ['role' => 'user', 'content' => $prompt]
    ]);
    
    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
