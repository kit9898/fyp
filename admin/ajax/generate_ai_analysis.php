<?php
session_start();

// Include necessary files
// Adjust paths based on location: admin/ajax/ -> ../../LaptopAdvisor/includes/
require_once '../../LaptopAdvisor/includes/config.php';
require_once '../../LaptopAdvisor/includes/ollama_client.php';

header('Content-Type: application/json');

// Check authentication
if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$reportType = $input['reportType'] ?? 'unknown';
$dateFrom = $input['dateFrom'] ?? '';
$dateTo = $input['dateTo'] ?? '';
$summaryData = $input['data'] ?? []; // Summary stats passed from frontend

if (empty($summaryData)) {
    echo json_encode(['success' => false, 'error' => 'No data provided for analysis']);
    exit();
}

try {
    // Initialize Ollama
    $ollama = new OllamaClient(OLLAMA_API_URL, OLLAMA_MODEL, OLLAMA_TIMEOUT);

    // Construct Prompt based on Report Type
    $promptContext = "You are an expert Data Analyst providing insights for an E-commerce Admin Dashboard.\n";
    $promptContext .= "Report Type: " . ucfirst($reportType) . "\n";
    $promptContext .= "Date Range: $dateFrom to $dateTo\n";
    $promptContext .= "Data Summary:\n" . json_encode($summaryData, JSON_PRETTY_PRINT) . "\n\n";
    
    $promptTask = "Task: Analyze the provided data summary and generate 3-4 concise, high-value insights.\n";
    $promptTask .= "Focus on actionable intelligence, trends, or notable performance metrics.\n";
    $promptTask .= "Format the output as valid user-friendly HTML using an unstyled <ul> list with <li> items. Do NOT include ```html markdown blocks. Just return the <ul> HTML.\n";
    $promptTask .= "Each <li> should use strong tags for key numbers/names. Keep it professional and helpful.";

    $response = $ollama->chat([
        ['role' => 'system', 'content' => 'You are a helpful business analytics assistant. Output valid HTML fragments only.'],
        ['role' => 'user', 'content' => $promptContext . $promptTask]
    ]);

    $analysisHtml = $response['message'] ?? '<p>Unable to generate analysis at this time.</p>';

    // Sanitize basic tags just in case, but rely on instruction for now.
    // If it comes wrapping in markdown, strip it.
    $analysisHtml = preg_replace('/^```html/', '', $analysisHtml);
    $analysisHtml = preg_replace('/```$/', '', $analysisHtml);
    $analysisHtml = trim($analysisHtml);

    echo json_encode([
        'success' => true,
        'analysis' => $analysisHtml
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'error' => 'AI Generation Failed: ' . $e->getMessage()
    ]);
}
?>
