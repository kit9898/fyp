<?php
require_once 'includes/db_connect.php';
require_once 'includes/auth_check.php';
require_once 'includes/benchmark_data.php';

// Get product IDs
$ids = isset($_GET['ids']) ? array_map('intval', array_filter(explode(',', $_GET['ids']))) : [];

if (empty($ids) || count($ids) > 4) {
    die("Invalid product selection");
}

// Fetch products
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types = str_repeat('i', count($ids));
$sql = "SELECT * FROM products WHERE product_id IN ($placeholders)";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$ids);
$stmt->execute();
$result = $stmt->get_result();
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Calculate scores using same algorithm as compare.php (benchmark-based)
function calculatePerformanceScore($product) {
    $score = 0;
    
    // CPU score (0-35 points) - based on benchmark
    $cpu_benchmark = getCPUBenchmarkScore($product['cpu']);
    $cpu_normalized = ($cpu_benchmark / 60000) * 35;
    $score += min(35, $cpu_normalized);
    
    // GPU score (0-40 points) - based on benchmark
    $gpu_benchmark = getGPUBenchmarkScore($product['gpu']);
    $gpu_normalized = ($gpu_benchmark / 35000) * 40;
    $score += min(40, $gpu_normalized);
    
    // RAM score (0-15 points)
    $ram = $product['ram_gb'];
    if ($ram >= 32) $score += 15;
    elseif ($ram >= 16) $score += 12;
    elseif ($ram >= 8) $score += 8;
    else $score += 3;
    
    // Storage score (0-10 points)
    $storage = $product['storage_gb'];
    if ($storage >= 2000) $score += 10;
    elseif ($storage >= 1000) $score += 8;
    elseif ($storage >= 512) $score += 6;
    else $score += 3;
    
    return round($score);
}

// Analyze products
$analysis = [
    'products' => $products,
    'scores' => [],
    'winners' => [],
    'reasons' => []
];

foreach ($products as $index => $product) {
    $analysis['scores'][$index] = calculatePerformanceScore($product);
}

// Determine winners in each category (support ties - multiple winners possible)
$rams = array_column($products, 'ram_gb');
$storages = array_column($products, 'storage_gb');
$displays = array_column($products, 'display_size');
$prices = array_column($products, 'price');

// Find ALL products that tie for best in each category
$max_ram = max($rams);
$max_storage = max($storages);
$max_display = max($displays);
$min_price = min($prices);
$max_performance = max($analysis['scores']);

$analysis['winners']['ram'] = [];
$analysis['winners']['storage'] = [];
$analysis['winners']['display'] = [];
$analysis['winners']['price'] = [];
$analysis['winners']['performance'] = [];

foreach ($products as $index => $product) {
    if ($product['ram_gb'] == $max_ram) $analysis['winners']['ram'][] = $index;
    if ($product['storage_gb'] == $max_storage) $analysis['winners']['storage'][] = $index;
    if ($product['display_size'] == $max_display) $analysis['winners']['display'][] = $index;
    if ($product['price'] == $min_price) $analysis['winners']['price'][] = $index;
    if ($analysis['scores'][$index] == $max_performance) $analysis['winners']['performance'][] = $index;
}

// For backward compatibility, keep the first winner as the primary
$best_overall = $analysis['winners']['performance'][0];
$best_value = $analysis['winners']['price'][0];
$best_ram_winner = $analysis['winners']['ram'][0];
$best_storage_winner = $analysis['winners']['storage'][0];

// Generate detailed reasons
// RAM Analysis
$ram_diff = max($rams) - min($rams);
if ($ram_diff == 0) {
    $analysis['reasons']['ram'] = "All compared products offer " . max($rams) . "GB of RAM. ";
} else {
    $analysis['reasons']['ram'] = "The winner leads with " . max($rams) . "GB RAM, providing " . 
        $ram_diff . "GB more memory than the base option. ";
}

if (max($rams) >= 32) {
    $analysis['reasons']['ram'] .= "This capacity is professional-grade, ideal for 4K video editing, 3D rendering, and running virtual machines without slowdowns.";
} elseif (max($rams) >= 16) {
    $analysis['reasons']['ram'] .= "This is the sweet spot for modern computing, allowing for heavy multitasking, gaming, and creative work.";
} else {
    $analysis['reasons']['ram'] .= "This amount is sufficient for web browsing, office documents, and media consumption.";
}

// Storage Analysis
$storage_diff = max($storages) - min($storages);
if ($storage_diff == 0) {
    $analysis['reasons']['storage'] = "All products match with " . max($storages) . "GB of storage. ";
} else {
    $analysis['reasons']['storage'] = "The winner offers " . max($storages) . "GB storage, giving you " . 
        $storage_diff . "GB of extra space. ";
}

if (max($storages) >= 1000) {
    $analysis['reasons']['storage'] .= "This 1TB+ capacity can hold approximately 20 AAA games, 250,000 photos, or 500 hours of HD video.";
} elseif (max($storages) >= 512) {
    $analysis['reasons']['storage'] .= "This is a solid baseline, capable of holding the OS, key applications, and a moderate library of games or media.";
} else {
    $analysis['reasons']['storage'] .= "We recommend using cloud storage or an external drive for large media libraries.";
}

// Performance Analysis
$best_perf_product = $products[$analysis['winners']['performance'][0]];
$analysis['reasons']['performance'] = "Achieved the highest score of " . max($analysis['scores']) . "/100. ";

// Identify specific strength
$cpu_score = getCPUBenchmarkScore($best_perf_product['cpu']);
$gpu_score = getGPUBenchmarkScore($best_perf_product['gpu']);

if ($gpu_score > 15000) {
    $analysis['reasons']['performance'] .= "Its dominance is driven by the powerful " . $best_perf_product['gpu'] . " GPU, making it a beast for gaming and 3D work.";
} elseif ($cpu_score > 30000) {
    $analysis['reasons']['performance'] .= "It excels primarily due to the high-end " . $best_perf_product['cpu'] . " processor, ensuring blazing fast computations.";
} else {
    $analysis['reasons']['performance'] .= "It offers the most balanced configuration across all specifications.";
}

// Value Analysis
$price_diff = max($prices) - min($prices);
$best_val_product = $products[$analysis['winners']['price'][0]];
$perf_score = $analysis['scores'][$analysis['winners']['price'][0]];
$price_per_point = $best_val_product['price'] / ($perf_score > 0 ? $perf_score : 1);

$analysis['reasons']['value'] = "Best price point at $" . number_format(min($prices), 2) . ". ";
if ($price_diff > 0) {
    $analysis['reasons']['value'] .= "You save $" . number_format($price_diff, 2) . " compared to the most expensive option. ";
}
$analysis['reasons']['value'] .= "With a cost of roughly $" . number_format($price_per_point, 2) . " per performance point, it delivers exceptional bang for your buck.";

// Simple HTML to PDF using browser print
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laptop Comparison Report</title>
    <style>
        @media print {
            @page {
                margin: 1cm;
                size: A4;
            }
            body {
                margin: 0;
            }
            .no-print {
                display: none;
            }
            .page-break {
                page-break-before: always;
            }
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        
        .report-container {
            background: white;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #667eea;
            margin: 0 0 10px 0;
            font-size: 2.5rem;
        }
        
        .header .date {
            color: #666;
            font-size: 0.9rem;
        }
        
        .section {
            margin-bottom: 40px;
        }
        
        .section h2 {
            color: #333;
            border-left: 4px solid #667eea;
            padding-left: 15px;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }
        
        .winner-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            margin-left: 10px;
        }
        
        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .comparison-table th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        
        .comparison-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .comparison-table tr:hover {
            background: #f8f9fa;
        }
        
        .spec-label {
            font-weight: 600;
            color: #495057;
        }
        
        .highlight-best {
            background: #d4edda;
            font-weight: 600;
            color: #155724;
        }
        
        .highlight-worst {
            background: #f8d7da;
            color: #721c24;
        }
        
        .analysis-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .analysis-box h3 {
            margin-top: 0;
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .analysis-box p {
            margin: 10px 0;
            color: #495057;
        }
        
        .trophy {
            font-size: 1.5rem;
        }
        
        .score-card {
            display: inline-block;
            background: white;
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 15px 25px;
            margin: 10px;
            text-align: center;
        }
        
        .score-card .score {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
        }
        
        .score-card .label {
            font-size: 0.85rem;
            color: #666;
            text-transform: uppercase;
        }
        
        .recommendation {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-top: 30px;
        }
        
        .recommendation h2 {
            color: white;
            border: none;
            margin-top: 0;
        }
        
        .recommendation p {
            font-size: 1.1rem;
            line-height: 1.8;
        }
        
        .print-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin: 20px 0;
        }
        
        .print-btn:hover {
            background: #5568d3;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #666;
            font-size: 0.85rem;
        }
        
        .winner-list {
            margin: 10px 0;
        }
        
        .winner-item {
            padding: 8px 0;
            border-bottom: 1px dashed #ddd;
        }
        
        .winner-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="header">
            <h1>🖥️ Laptop Comparison Report</h1>
            <div class="date">Generated on <?php echo date('F j, Y \a\t g:i A'); ?></div>
            <button class="print-btn no-print" onclick="window.print()">📄 Save as PDF</button>
        </div>
        
        <!-- Overall Winner -->
        <div class="section">
            <h2>🏆 Overall Winner<?php if (count($analysis['winners']['performance']) > 1) echo 's'; ?></h2>
            <?php if (count($analysis['winners']['performance']) > 1): ?>
                <div class="analysis-box">
                    <h3><span class="trophy">👑</span> It's a tie!</h3>
                    <p>Multiple products share the top performance score of <?php echo $analysis['scores'][$best_overall]; ?> points:</p>
                    <?php foreach ($analysis['winners']['performance'] as $winner_idx): ?>
                        <div class="score-card">
                            <div class="label"><?php echo htmlspecialchars($products[$winner_idx]['product_name']); ?></div>
                            <div class="score"><?php echo $analysis['scores'][$winner_idx]; ?></div>
                        </div>
                    <?php endforeach; ?>
                    <p><strong>Why they win:</strong> <?php echo $analysis['reasons']['performance']; ?></p>
                </div>
            <?php else: ?>
                <div class="analysis-box">
                    <h3>
                        <span class="trophy">👑</span>
                        <?php echo htmlspecialchars($products[$best_overall]['product_name']); ?>
                    </h3>
                    <div class="score-card">
                        <div class="score"><?php echo $analysis['scores'][$best_overall]; ?></div>
                        <div class="label">Performance Score</div>
                    </div>
                    <p><strong>Why it wins:</strong> <?php echo $analysis['reasons']['performance']; ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Detailed Comparison Table -->
        <div class="section">
            <h2>📊 Detailed Specifications</h2>
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th>Specification</th>
                        <?php foreach ($products as $index => $product): ?>
                            <th>
                                <?php echo htmlspecialchars($product['brand']); ?>
                                <?php if (in_array($index, $analysis['winners']['performance'])): ?>
                                    <span class="winner-badge">Best Overall</span>
                                <?php endif; ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="spec-label">Model</td>
                        <?php foreach ($products as $product): ?>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td class="spec-label">💾 RAM</td>
                        <?php foreach ($products as $index => $product): ?>
                            <td class="<?php echo in_array($index, $analysis['winners']['ram']) ? 'highlight-best' : ''; ?>">
                                <?php echo $product['ram_gb']; ?> GB
                                <?php if (in_array($index, $analysis['winners']['ram'])): ?>
                                    ⭐
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td class="spec-label">💿 Storage</td>
                        <?php foreach ($products as $index => $product): ?>
                            <td class="<?php echo in_array($index, $analysis['winners']['storage']) ? 'highlight-best' : ''; ?>">
                                <?php echo $product['storage_gb']; ?> GB
                                <?php if (in_array($index, $analysis['winners']['storage'])): ?>
                                    ⭐
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td class="spec-label">🖥️ Processor</td>
                        <?php foreach ($products as $product): ?>
                            <td><?php echo htmlspecialchars($product['cpu']); ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td class="spec-label">🎮 Graphics</td>
                        <?php foreach ($products as $product): ?>
                            <td><?php echo htmlspecialchars($product['gpu']); ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td class="spec-label">📺 Display</td>
                        <?php foreach ($products as $index => $product): ?>
                            <td class="<?php echo in_array($index, $analysis['winners']['display']) ? 'highlight-best' : ''; ?>">
                                <?php echo $product['display_size']; ?>"
                                <?php if (in_array($index, $analysis['winners']['display'])): ?>
                                    ⭐
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td class="spec-label">💰 Price</td>
                        <?php foreach ($products as $index => $product): ?>
                            <td class="<?php echo in_array($index, $analysis['winners']['price']) ? 'highlight-best' : ''; ?>">
                                $<?php echo number_format($product['price'], 2); ?>
                                <?php if (in_array($index, $analysis['winners']['price'])): ?>
                                    ⭐ Best Value
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td class="spec-label">⚡ Performance Score</td>
                        <?php foreach ($products as $index => $product): ?>
                            <td class="<?php echo in_array($index, $analysis['winners']['performance']) ? 'highlight-best' : ''; ?>">
                                <?php echo $analysis['scores'][$index]; ?> points
                                <?php if (in_array($index, $analysis['winners']['performance'])): ?>
                                    👑
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Category Winners -->
        <div class="section page-break">
            <h2>🥇 Category Winners & Analysis</h2>
            
            <!-- Best RAM -->
            <div class="analysis-box">
                <h3>💾 Best RAM<?php if (count($analysis['winners']['ram']) > 1) echo ' (Tie)'; ?></h3>
                <?php if (count($analysis['winners']['ram']) > 1): ?>
                    <div class="winner-list">
                        <?php foreach ($analysis['winners']['ram'] as $winner_idx): ?>
                            <div class="winner-item">
                                <strong><?php echo htmlspecialchars($products[$winner_idx]['product_name']); ?></strong> - 
                                <?php echo $products[$winner_idx]['ram_gb']; ?>GB RAM
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p><strong><?php echo htmlspecialchars($products[$best_ram_winner]['product_name']); ?></strong></p>
                <?php endif; ?>
                <p><?php echo $analysis['reasons']['ram']; ?></p>
            </div>
            
            <!-- Best Storage -->
            <div class="analysis-box">
                <h3>💿 Best Storage<?php if (count($analysis['winners']['storage']) > 1) echo ' (Tie)'; ?></h3>
                <?php if (count($analysis['winners']['storage']) > 1): ?>
                    <div class="winner-list">
                        <?php foreach ($analysis['winners']['storage'] as $winner_idx): ?>
                            <div class="winner-item">
                                <strong><?php echo htmlspecialchars($products[$winner_idx]['product_name']); ?></strong> - 
                                <?php echo $products[$winner_idx]['storage_gb']; ?>GB Storage
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p><strong><?php echo htmlspecialchars($products[$best_storage_winner]['product_name']); ?></strong></p>
                <?php endif; ?>
                <p><?php echo $analysis['reasons']['storage']; ?></p>
            </div>
            
            <!-- Best Value -->
            <div class="analysis-box">
                <h3>💰 Best Value<?php if (count($analysis['winners']['price']) > 1) echo ' (Tie)'; ?></h3>
                <?php if (count($analysis['winners']['price']) > 1): ?>
                    <div class="winner-list">
                        <?php foreach ($analysis['winners']['price'] as $winner_idx): ?>
                            <div class="winner-item">
                                <strong><?php echo htmlspecialchars($products[$winner_idx]['product_name']); ?></strong> - 
                                $<?php echo number_format($products[$winner_idx]['price'], 2); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p><strong><?php echo htmlspecialchars($products[$best_value]['product_name']); ?></strong></p>
                <?php endif; ?>
                <p><?php echo $analysis['reasons']['value']; ?></p>
            </div>
        </div>
        
        <!-- Recommendation -->
        <div class="recommendation">
            <h2>💡 Our Recommendation</h2>
            <?php if ($best_overall === $best_value): ?>
                <p>
                    <strong><?php echo htmlspecialchars($products[$best_overall]['product_name']); ?></strong> 
                    is our top recommendation! It offers the best performance AND the best value, 
                    making it an unbeatable choice for price-conscious buyers who don't want to compromise on quality.
                </p>
            <?php else: ?>
                <p>
                    If performance is your priority, choose 
                    <strong><?php echo htmlspecialchars($products[$best_overall]['product_name']); ?></strong> 
                    with a performance score of <?php echo $analysis['scores'][$best_overall]; ?> points.
                </p>
                <p>
                    If budget is more important, go with 
                    <strong><?php echo htmlspecialchars($products[$best_value]['product_name']); ?></strong> 
                    at just $<?php echo number_format($products[$best_value]['price'], 2); ?> - 
                    you'll save $<?php echo number_format($products[$best_overall]['price'] - $products[$best_value]['price'], 2); ?>!
                </p>
            <?php endif; ?>
        </div>
        
        <div class="footer">
            <p>Report generated by Laptop Advisor | <?php echo date('F j, Y'); ?></p>
            <p>This comparison is based on technical specifications and performance analysis.</p>
        </div>
    </div>
    
    <script>
        // Auto-trigger print dialog on load
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('auto_print') === '1') {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            };
        }
    </script>
</body>
</html>