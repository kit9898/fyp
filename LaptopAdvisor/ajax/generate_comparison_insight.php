<?php
header('Content-Type: application/json');
require_once '../includes/config.php';
require_once '../includes/ollama_client.php';

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$products = $input['products'] ?? [];

if (empty($products) || count($products) < 2) {
    echo json_encode(['success' => false, 'error' => 'Need at least 2 products to compare']);
    exit;
}

// Construct plain text summary for the AI
$productText = "";
foreach ($products as $p) {
    // Only include key specs to avoid token limit issues and keep focus sharp
    $name = $p['name'] ?? 'Unknown';
    $price = $p['price'] ?? 'Unknown';
    $ram = $p['ram'] ?? 'Unknown';
    $storage = $p['storage'] ?? 'Unknown';
    $cpu = $p['cpu'] ?? 'Unknown';
    $gpu = $p['gpu'] ?? 'Unknown';
    $screen = $p['display'] ?? 'Unknown';
    
    $productText .= "Product: $name\n";
    $productText .= " - Price: $price\n";
    $productText .= " - Specs: RAM: {$ram}GB, Storage: {$storage}GB, CPU: $cpu, GPU: $gpu, Display: $screen\n\n";
}

$prompt = "You are an expert tech reviewer. Compare the following products provided below:\n\n";
$prompt .= $productText;
$prompt .= "Task: Provide a short, insightful comparison summary (approx. 100-150 words). \n";
$prompt .= "1. Identify the 'Best Value' option and explain why.\n";
$prompt .= "2. Identify the 'Best Performance' option and explain why.\n";
$prompt .= "3. Mention who should buy which product.\n";
$prompt .= "Format: Return the response as clean HTML using <p> tags for paragraphs and <strong> for emphasis. Do NOT use markdown or ```html blocks. Just raw HTML content.";

try {
    // Check if Ollama is reachable first
    $ollama = new OllamaClient(OLLAMA_API_URL, OLLAMA_MODEL, OLLAMA_TIMEOUT);
    
    // Using chat method
    $response = $ollama->chat([
        ['role' => 'system', 'content' => 'You are a helpful tech assistant. Output HTML only.'],
        ['role' => 'user', 'content' => $prompt]
    ]);
    
    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
