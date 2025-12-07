<?php 
require_once 'includes/db_connect.php';
include 'includes/header.php';
require_once 'includes/auth_check.php';
require_once 'includes/benchmark_data.php'; 

$products = [];
if (isset($_GET['ids'])) {
    $ids_string = $_GET['ids'];
    $ids = array_map('intval', array_filter(explode(',', $ids_string)));

    if (!empty($ids) && count($ids) <= 4) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $types = str_repeat('i', count($ids));

        $sql = "SELECT * FROM products WHERE product_id IN ($placeholders)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$ids);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
}

// --- Category Detection Logic ---
$is_accessory_mode = false;
$mixed_categories = false;
$current_category = null;

if (!empty($products)) {
    foreach ($products as $product) {
        // Default to 'laptop' if category is null or empty
        $cat = !empty($product['product_category']) ? $product['product_category'] : 'laptop';
        
        if ($current_category === null) {
            $current_category = $cat;
        } elseif ($current_category !== $cat) {
            $mixed_categories = true;
            break;
        }
    }
    
    if (!$mixed_categories && $current_category !== 'laptop') {
        $is_accessory_mode = true;
    }
}

// --- Enhanced logic to find the best specs for highlighting ---
$best_specs = [];
$worst_specs = [];
$best_specs = [];
$worst_specs = [];
$performance_scores = [];
$score_breakdowns = []; // Store detailed breakdown

if (count($products) > 1 && !$mixed_categories) {
    // Price (lower is better)
    $prices = array_column($products, 'price');
    $best_specs['price'] = min($prices);
    $worst_specs['price'] = max($prices);
    
    if (!$is_accessory_mode) {
        // RAM (higher is better)
    $best_specs['ram_gb'] = max(array_column($products, 'ram_gb'));
    $worst_specs['ram_gb'] = min(array_column($products, 'ram_gb'));
    
    // Storage (higher is better)
    $best_specs['storage_gb'] = max(array_column($products, 'storage_gb'));
    $worst_specs['storage_gb'] = min(array_column($products, 'storage_gb'));
    
    // Display (larger is better for comparison purposes)
    $best_specs['display_size'] = max(array_column($products, 'display_size'));
    $worst_specs['display_size'] = min(array_column($products, 'display_size'));
    
    // CPU ranking (higher score is better)
    $cpu_scores = [];
    foreach ($products as $index => $product) {
        $cpu_scores[$index] = rankCPU($product['cpu']);
    }
    $best_specs['cpu_score'] = max($cpu_scores);
    $worst_specs['cpu_score'] = min($cpu_scores);
    
    // GPU ranking (higher score is better)
    $gpu_scores = [];
    foreach ($products as $index => $product) {
        $gpu_scores[$index] = rankGPU($product['gpu']);
    }
    $best_specs['gpu_score'] = max($gpu_scores);
    $worst_specs['gpu_score'] = min($gpu_scores);
    
    // Calculate performance score for each product (now using benchmark data)
    foreach ($products as $index => $product) {
        $score = 0;
        
        // CPU score (0-35 points) - based on benchmark
        $cpu_benchmark = getCPUBenchmarkScore($product['cpu']);
        $cpu_normalized = ($cpu_benchmark / 60000) * 35; // Max 60000 benchmark = 35 points
        $score += min(35, $cpu_normalized);
        
        // GPU score (0-40 points) - based on benchmark
        $gpu_benchmark = getGPUBenchmarkScore($product['gpu']);
        $gpu_normalized = ($gpu_benchmark / 35000) * 40; // Max 35000 benchmark = 40 points
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
        
        $performance_scores[$index] = round($score);
        
        // Store breakdown
        $score_breakdowns[$index] = [
            'cpu' => min(35, $cpu_normalized),
            'gpu' => min(40, $gpu_normalized),
            'ram' => ($ram >= 32 ? 15 : ($ram >= 16 ? 12 : ($ram >= 8 ? 8 : 3))),
            'storage' => ($storage >= 2000 ? 10 : ($storage >= 1000 ? 8 : ($storage >= 512 ? 6 : 3))),
            'cpu_raw' => $cpu_benchmark,
            'gpu_raw' => $gpu_benchmark
        ];
    }
    
    $best_specs['performance'] = max($performance_scores);
    $worst_specs['performance'] = min($performance_scores);
    }
}

// Helper function to get use case icon
function getUseCaseIcon($use_case) {
    $icons = [
        'Gaming' => '🎮',
        'Creative' => '🎨',
        'Business' => '💼',
        'Student' => '📚',
        'General Use' => '🌐'
    ];
    return $icons[$use_case] ?? '💻';
}

// Helper function to calculate value rating
function getValueRating($price, $performance_score) {
    $value_ratio = ($performance_score / $price) * 1000;
    if ($value_ratio >= 50) return 'Excellent Value';
    elseif ($value_ratio >= 35) return 'Good Value';
    elseif ($value_ratio >= 25) return 'Fair Value';
    else return 'Premium';
}

// Benchmark-Powered CPU Ranking Function
function rankCPU($cpu_name) {
    $benchmark_score = getCPUBenchmarkScore($cpu_name);
    return normalizeCPUScore($benchmark_score);
}

// Benchmark-Powered GPU Ranking Function
function rankGPU($gpu_name) {
    $benchmark_score = getGPUBenchmarkScore($gpu_name);
    return normalizeGPUScore($benchmark_score);
}

// Helper function to get GPU performance label
function getGPULabel($score) {
    if ($score >= 8) return '⚡ High Performance (Gaming)';
    if ($score >= 6) return '✓ Good for Gaming';
    if ($score >= 4) return '📊 Integrated Graphics';
    return '📝 Basic Graphics';
}
?>

<style>
/* Advanced Comparison Page Styles */
.comparison-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px 20px;
    margin-bottom: 30px;
    border-radius: 12px;
    text-align: center;
}

.comparison-header h1 {
    margin: 0 0 10px 0;
    font-size: 2.5rem;
}

.comparison-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 1.1rem;
}

.comparison-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding: 15px 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    flex-wrap: wrap;
    gap: 15px;
}

.view-toggle {
    display: flex;
    gap: 10px;
}

.view-btn {
    padding: 10px 20px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-weight: 600;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 8px;
}

.view-btn:hover,
.view-btn.active {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.comparison-actions {
    display: flex;
    gap: 10px;
}

.compare-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 30px;
}

/* Table View Styles */
.compare-table-wrapper {
    overflow-x: auto;
}

.compare-table {
    width: 100%;
    border-collapse: separate; /* Changed for better border control */
    border-spacing: 0;
    font-size: 0.95rem;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.compare-table thead {
    background: #f8f9fa;
    position: sticky;
    top: 0;
    z-index: 20; /* Higher than first column */
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.compare-table th {
    padding: 20px 15px;
    text-align: center;
    border-bottom: 3px solid #e9ecef;
}

.compare-table th:first-child {
    text-align: left;
    font-weight: 700;
    color: #495057;
    width: 180px;
    position: sticky;
    left: 0;
    background: rgba(248, 249, 250, 0.95);
    backdrop-filter: blur(8px);
    z-index: 10;
    box-shadow: 2px 0 5px rgba(0,0,0,0.05);
}

.product-header-cell {
    min-width: 200px;
    position: relative; /* Fix for absolute positioning of winner badge */
    transition: all 0.3s ease;
}

.product-header-cell:hover {
    background: #f1f5f9;
}

.product-header-img {
    width: 100%;
    max-width: 180px;
    height: 140px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.product-header-cell h3 {
    font-size: 1.1rem;
    margin: 12px 0 5px 0;
    color: #111827;
    line-height: 1.4;
    font-weight: 700;
}

.product-header-cell .brand {
    font-size: 0.8rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.compare-table tbody tr {
    border-bottom: 1px solid #e9ecef;
    transition: background 0.2s;
}

.compare-table tbody tr:hover {
    background: #f8f9fa;
}

.compare-table td {
    padding: 18px 15px;
    text-align: center;
    vertical-align: middle;
}

.compare-table td:first-child {
    text-align: left;
    font-weight: 600;
    color: #495057;
    position: sticky;
    left: 0;
    background: white;
    z-index: 5;
}

.compare-table tbody tr:hover td:first-child {
    background: #f8f9fa;
}

/* Highlighting */
.highlight-best {
    background: #d1fae5 !important;
    font-weight: 700;
    color: #065f46;
    position: relative;
}

.highlight-best::after {
    content: '✓';
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 0.85rem;
    color: #10b981;
}

.highlight-worst {
    background: #fee2e2;
    color: #991b1b;
}

/* Performance Badge */
.performance-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85rem;
    margin-top: 8px;
}

.performance-excellent {
    background: #d1fae5;
    color: #065f46;
}

.performance-good {
    background: #dbeafe;
    color: #1e40af;
}

.performance-fair {
    background: #fef3c7;
    color: #92400e;
}

/* Value Rating */
.value-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: 5px;
}

.value-excellent {
    background: #10b981;
    color: white;
}

.value-good {
    background: #3b82f6;
    color: white;
}

.value-fair {
    background: #f59e0b;
    color: white;
}

.value-premium {
    background: #8b5cf6;
    color: white;
}

/* Action Buttons in Table */
.action-cell {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 10px;
}

.action-cell .btn {
    margin: 0;
    white-space: nowrap;
}

/* Card View Styles */
.compare-cards {
    display: none;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    padding: 20px;
}

.compare-cards.active {
    display: grid;
}

.compare-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s;
    position: relative;
}

.compare-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.compare-card.winner {
    border-color: #10b981;
    border-width: 3px;
}

.winner-badge {
    position: absolute;
    top: -10px;
    right: 10px;
    background: linear-gradient(135deg, #FFD700 0%, #FDB931 100%);
    color: #8a6d3b;
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: 800;
    font-size: 0.75rem;
    z-index: 10;
    box-shadow: 0 4px 15px rgba(253, 185, 49, 0.4);
    text-transform: uppercase;
    letter-spacing: 1px;
    border: 2px solid white;
}

.compare-card img {
    width: 100%;
    height: 220px;
    object-fit: cover;
}

.compare-card-content {
    padding: 20px;
}

.compare-card-header {
    margin-bottom: 15px;
}

.compare-card-header .brand {
    font-size: 0.8rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.compare-card-header h3 {
    font-size: 1.2rem;
    margin: 8px 0;
    color: #1a1a1a;
}

.compare-card-price {
    font-size: 1.8rem;
    font-weight: 700;
    color: #667eea;
    margin: 10px 0;
}

.spec-comparison {
    margin: 20px 0;
}

.spec-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #e9ecef;
}

.spec-row:last-child {
    border-bottom: none;
}

.spec-label {
    font-weight: 600;
    color: #666;
    font-size: 0.9rem;
}

.spec-value {
    font-weight: 600;
    color: #1a1a1a;
    text-align: right;
}

.spec-value.best {
    color: #10b981;
}

.compare-card-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 20px;
}

/* Winner Summary */
.winner-summary {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 30px;
    text-align: center;
}

.winner-summary h3 {
    margin: 0 0 10px 0;
    font-size: 1.5rem;
}

.winner-summary p {
    margin: 0;
    opacity: 0.95;
}

/* Quick Stats */
.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    text-align: center;
}

.stat-card-icon {
    font-size: 2rem;
    margin-bottom: 10px;
}

.stat-card-label {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 5px;
}

.stat-card-value {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1a1a1a;
}

/* Empty State */
.empty-comparison {
    text-align: center;
    padding: 80px 20px;
}

.empty-comparison-icon {
    font-size: 5rem;
    margin-bottom: 20px;
}

.empty-comparison h2 {
    margin-bottom: 15px;
    color: #1a1a1a;
}

.empty-comparison p {
    color: #666;
    margin-bottom: 25px;
    font-size: 1.1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .compare-table th:first-child,
    .compare-table td:first-child {
        position: relative;
    }
    
    .comparison-controls {
        flex-direction: column;
        align-items: stretch;
    }
    
    .view-toggle,
    .comparison-actions {
        width: 100%;
        justify-content: center;
    }
    
    .compare-cards {
        grid-template-columns: 1fr;
    }
    
    .quick-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Print Styles */
@media print {
    .comparison-controls,
    .comparison-actions,
    .action-cell {
        display: none !important;
    }
}

/* Animations */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.compare-container {
    animation: slideIn 0.5s ease;
}

/* Tooltips */
.tooltip-trigger {
    position: relative;
    cursor: help;
    border-bottom: 1px dashed #666;
}

.tooltip-trigger:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 8px 12px;
    background: rgba(0,0,0,0.9);
    color: white;
    font-size: 0.8rem;
    border-radius: 6px;
    white-space: nowrap;
    z-index: 100;
    margin-bottom: 5px;
}

/* Visual Bars */
.spec-bar-container {
    width: 100%;
    height: 4px;
    background: #e2e8f0;
    border-radius: 2px;
    margin-top: 8px;
    overflow: hidden;
}

.spec-bar-fill {
    height: 100%;
    border-radius: 2px;
    transition: width 0.5s ease;
}

.remove-product-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #fee2e2;
    color: #ef4444;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.8rem;
    transition: all 0.2s;
    z-index: 15; /* Above winner badge if needed, but winner badge is top -10 */
}

.remove-product-btn:hover {
    background: #ef4444;
    color: white;
}
</style>

<div class="comparison-header">
    <h1>⚖️ Smart Product Comparison</h1>
    <p>Compare specifications side-by-side to make an informed decision</p>
</div>

<?php if ($mixed_categories): ?>
    <div class="empty-comparison" style="padding: 40px; background: #fff3cd; border: 1px solid #ffeeba; border-radius: 12px; color: #856404;">
        <div class="empty-comparison-icon" style="font-size: 3rem; margin-bottom: 10px;">⚠️</div>
        <h2 style="color: #856404;">Mixed Categories Selected</h2>
        <p style="color: #856404;">You are trying to compare different types of products (e.g., a Laptop and a Mouse).<br>Please select products from the same category for a meaningful comparison.</p>
        <a href="products.php" class="btn btn-primary" style="margin-top: 15px;">Return to Products</a>
    </div>
<?php elseif (count($products) >= 2): ?>
    <!-- Quick Statistics -->
    <!-- Quick Statistics -->
    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-card-icon">💰</div>
            <div class="stat-card-label">Price Range</div>
            <div class="stat-card-value">
                $<?php echo number_format(min($prices), 0); ?> - $<?php echo number_format(max($prices), 0); ?>
            </div>
        </div>
        <?php if (!$is_accessory_mode): ?>
        <div class="stat-card">
            <div class="stat-card-icon">💾</div>
            <div class="stat-card-label">RAM Range</div>
            <div class="stat-card-value">
                <?php echo $worst_specs['ram_gb']; ?>GB - <?php echo $best_specs['ram_gb']; ?>GB
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon">💿</div>
            <div class="stat-card-label">Storage Range</div>
            <div class="stat-card-value">
                <?php echo $worst_specs['storage_gb']; ?>GB - <?php echo $best_specs['storage_gb']; ?>GB
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon">📺</div>
            <div class="stat-card-label">Display Range</div>
            <div class="stat-card-value">
                <?php echo $worst_specs['display_size']; ?>" - <?php echo $best_specs['display_size']; ?>"
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Winner Summary -->
    <?php if (!$is_accessory_mode): ?>
    <?php 
    $winner_index = array_search($best_specs['performance'], $performance_scores);
    $winner = $products[$winner_index];
    ?>
    <div class="winner-summary">
        <h3>🏆 Best Overall Performance</h3>
        <p style="font-size: 1.2rem; margin: 10px 0;">
            <strong><?php echo htmlspecialchars($winner['product_name']); ?></strong>
        </p>
        <p>Performance Score: <?php echo $performance_scores[$winner_index]; ?>/100</p>
    </div>
    <?php endif; ?>

    <!-- Comparison Controls -->
    <div class="comparison-controls">
        <div class="view-toggle">
            <button class="view-btn active" onclick="switchView('table')" id="btn-table">
                <i class="fas fa-table"></i> Table View
            </button>
            <button class="view-btn" onclick="switchView('card')" id="btn-card">
                <i class="fas fa-th-large"></i> Card View
            </button>
        </div>
        
        <div class="comparison-actions">
            <button class="view-btn" onclick="toggleDifferences()" id="btn-diff">
                <i class="fas fa-adjust"></i> Highlight Differences
            </button>
            <button class="btn" onclick="window.print()">
                🖨️ Print
            </button>
            <button class="btn btn-primary" onclick="exportToPDF()">
                📄 Export as PDF
            </button>
            <button class="btn" onclick="exportComparison()">
                📥 Export TXT
            </button>
            <a href="products.php" class="btn">
                ➕ Add More
            </a>
        </div>
    </div>

    <!-- Table View -->
    <div class="compare-container" id="tableView">
        <div class="compare-table-wrapper">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th>Specification</th>
                        <?php foreach ($products as $index => $product): ?>
                            <th class="product-header-cell">
                                <?php if (!$is_accessory_mode && $index === $winner_index): ?>
                                    <span class="winner-badge">🏆 Winner</span>
                                <?php endif; ?>
                                <button class="remove-product-btn" onclick="removeProduct(<?php echo $product['product_id']; ?>)" title="Remove from comparison">
                                    <i class="fas fa-times"></i>
                                </button>
                                <img class="product-header-img" 
                                     src="<?php echo !empty($product['image_url']) ? htmlspecialchars($product['image_url']) : 'https://via.placeholder.com/180'; ?>" 
                                     alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                <p class="brand"><?php echo htmlspecialchars($product['brand']); ?></p>
                                <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Performance Score -->
                    <?php if (!$is_accessory_mode): ?>
                    <tr>
                        <td>
                            <span class="tooltip-trigger" data-tooltip="Overall performance based on RAM, GPU, and storage">
                                ⚡ Performance Score
                            </span>
                        </td>
                        <?php foreach ($products as $index => $product): ?>
                            <td class="<?php if ($performance_scores[$index] == $best_specs['performance']) echo 'highlight-best'; ?>">
                                <strong><?php echo $performance_scores[$index]; ?>/100</strong>
                                <?php 
                                $score = $performance_scores[$index];
                                $badge_class = $score >= 80 ? 'performance-excellent' : 
                                              ($score >= 60 ? 'performance-good' : 'performance-fair');
                                $badge_text = $score >= 80 ? 'Excellent' : 
                                             ($score >= 60 ? 'Good' : 'Fair');
                                ?>
                                <div class="performance-badge <?php echo $badge_class; ?>">
                                    <?php echo $badge_text; ?>
                                </div>
                                <button class="btn-link" onclick="showScoreBreakdown(<?php echo $index; ?>)" style="display: block; margin: 5px auto 0; font-size: 0.75rem; color: #666; text-decoration: underline; background: none; border: none; cursor: pointer;">
                                    View Breakdown
                                </button>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endif; ?>

                    <!-- Price -->
                    <tr>
                        <td>💰 Price</td>
                        <?php foreach ($products as $product): ?>
                            <td class="<?php if ($product['price'] == $best_specs['price']) echo 'highlight-best'; 
                                             elseif ($product['price'] == $worst_specs['price']) echo 'highlight-worst'; ?>">
                                <strong>$<?php echo number_format($product['price'], 2); ?></strong>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- Value Rating -->
                    <?php if (!$is_accessory_mode): ?>
                    <tr>
                        <td>💎 Value Rating</td>
                        <?php foreach ($products as $index => $product): ?>
                            <?php 
                            $value_rating = getValueRating($product['price'], $performance_scores[$index]);
                            $badge_class = '';
                            if ($value_rating == 'Excellent Value') $badge_class = 'value-excellent';
                            elseif ($value_rating == 'Good Value') $badge_class = 'value-good';
                            elseif ($value_rating == 'Fair Value') $badge_class = 'value-fair';
                            else $badge_class = 'value-premium';
                            ?>
                            <td>
                                <span class="value-badge <?php echo $badge_class; ?>">
                                    <?php echo $value_rating; ?>
                                </span>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endif; ?>

                    <!-- RAM -->
                    <?php if (!$is_accessory_mode): ?>
                    <tr>
                        <td>💾 RAM</td>
                        <?php foreach ($products as $product): ?>
                            <td class="<?php if ($product['ram_gb'] == $best_specs['ram_gb']) echo 'highlight-best'; 
                                             elseif ($product['ram_gb'] == $worst_specs['ram_gb']) echo 'highlight-worst'; ?>">
                                <strong><?php echo htmlspecialchars($product['ram_gb']); ?> GB</strong>
                                <div class="spec-bar-container">
                                    <div class="spec-bar-fill" style="width: <?php echo min(100, ($product['ram_gb'] / 64) * 100); ?>%; background: #059669;"></div>
                                </div>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- Storage -->
                    <tr>
                        <td>💿 Storage</td>
                        <?php foreach ($products as $product): ?>
                            <td class="<?php if ($product['storage_gb'] == $best_specs['storage_gb']) echo 'highlight-best'; 
                                             elseif ($product['storage_gb'] == $worst_specs['storage_gb']) echo 'highlight-worst'; ?>">
                                <strong><?php echo htmlspecialchars($product['storage_gb']); ?> GB</strong>
                                <div style="font-size: 0.85rem; color: #666; margin-top: 4px;">
                                    <?php echo htmlspecialchars($product['storage_type']); ?>
                                </div>
                                <div class="spec-bar-container">
                                    <div class="spec-bar-fill" style="width: <?php echo min(100, ($product['storage_gb'] / 4096) * 100); ?>%; background: #d97706;"></div>
                                </div>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- Display Size -->
                    <tr>
                        <td>📺 Display</td>
                        <?php foreach ($products as $product): ?>
                            <td class="<?php if ($product['display_size'] == $best_specs['display_size']) echo 'highlight-best'; 
                                             elseif ($product['display_size'] == $worst_specs['display_size']) echo 'highlight-worst'; ?>">
                                <strong><?php echo htmlspecialchars($product['display_size']); ?>"</strong>
                                <div class="spec-bar-container">
                                    <div class="spec-bar-fill" style="width: <?php echo min(100, ($product['display_size'] / 18) * 100); ?>%; background: #3b82f6;"></div>
                                </div>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- CPU -->
                    <tr>
                        <td>🔧 Processor (CPU)</td>
                        <?php foreach ($products as $index => $product): ?>
                            <?php 
                            $cpu_score = rankCPU($product['cpu']);
                            $cpu_benchmark = getCPUBenchmarkScore($product['cpu']);
                            ?>
                            <td class="<?php if ($cpu_score == $best_specs['cpu_score']) echo 'highlight-best'; 
                                             elseif ($cpu_score == $worst_specs['cpu_score']) echo 'highlight-worst'; ?>">
                                <strong><?php echo htmlspecialchars($product['cpu']); ?></strong>
                                <div style="font-size: 0.75rem; color: #666; margin-top: 4px;">
                                    Rating: <?php echo $cpu_score; ?>/10
                                </div>
                                <div style="font-size: 0.7rem; color: #3b82f6; margin-top: 2px;">
                                    📊 Benchmark: <?php echo number_format($cpu_benchmark); ?> pts
                                </div>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- GPU -->
                    <tr>
                        <td>🎮 Graphics (GPU)</td>
                        <?php foreach ($products as $index => $product): ?>
                            <?php 
                            $gpu_score = rankGPU($product['gpu']);
                            $gpu_benchmark = getGPUBenchmarkScore($product['gpu']);
                            ?>
                            <td class="<?php if ($gpu_score == $best_specs['gpu_score']) echo 'highlight-best'; 
                                             elseif ($gpu_score == $worst_specs['gpu_score']) echo 'highlight-worst'; ?>">
                                <strong><?php echo htmlspecialchars($product['gpu']); ?></strong>
                                <div style="font-size: 0.75rem; color: #666; margin-top: 4px;">
                                    <?php echo getGPULabel($gpu_score); ?>
                                </div>
                                <div style="font-size: 0.7rem; color: #3b82f6; margin-top: 2px;">
                                    📊 3DMark: <?php echo number_format($gpu_benchmark); ?> pts
                                </div>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endif; ?>

                    <!-- Use Case -->
                    <tr>
                        <td>🎯 Best For</td>
                        <?php foreach ($products as $product): ?>
                            <td>
                                <span style="font-size: 1.3rem;"><?php echo getUseCaseIcon($product['primary_use_case']); ?></span>
                                <div style="margin-top: 5px;">
                                    <strong><?php echo htmlspecialchars($product['primary_use_case']); ?></strong>
                                </div>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- Brand -->
                    <tr>
                        <td>🏷️ Brand</td>
                        <?php foreach ($products as $product): ?>
                            <td><?php echo htmlspecialchars($product['brand']); ?></td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- Description (For Accessories) -->
                    <?php if ($is_accessory_mode): ?>
                    <tr>
                        <td>📝 Description</td>
                        <?php foreach ($products as $product): ?>
                            <td>
                                <div style="font-size: 0.9rem; color: #555; line-height: 1.5;">
                                    <?php echo htmlspecialchars($product['description']); ?>
                                </div>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endif; ?>

                    <!-- Actions -->
                    <tr>
                        <td><strong>Actions</strong></td>
                        <?php foreach ($products as $product): ?>
                            <td>
                                <div class="action-cell">
                                    <a href="product_details.php?product_id=<?php echo $product['product_id']; ?>" 
                                       class="btn" style="background: #6c757d;">
                                        👁️ View Details
                                    </a>
                                    <form action="cart_process.php" method="post" style="margin: 0;">
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary">
                                            🛒 Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Card View -->
    <div class="compare-cards" id="cardsView">
        <?php foreach ($products as $index => $product): ?>
            <div class="compare-card <?php if (!$is_accessory_mode && $index === $winner_index) echo 'winner'; ?>">
                <?php if (!$is_accessory_mode && $index === $winner_index): ?>
                    <div class="winner-badge">🏆 Best Overall</div>
                <?php endif; ?>
                
                <img src="<?php echo !empty($product['image_url']) ? htmlspecialchars($product['image_url']) : 'https://via.placeholder.com/280'; ?>" 
                     alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                
                <div class="compare-card-content">
                    <div class="compare-card-header">
                        <p class="brand"><?php echo htmlspecialchars($product['brand']); ?></p>
                        <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                    </div>
                    
                    <div class="compare-card-price">
                        $<?php echo number_format($product['price'], 2); ?>
                    </div>
                    
                    <?php 
                    if (!$is_accessory_mode) {
                        $value_rating = getValueRating($product['price'], $performance_scores[$index]);
                        $badge_class = '';
                        if ($value_rating == 'Excellent Value') $badge_class = 'value-excellent';
                        elseif ($value_rating == 'Good Value') $badge_class = 'value-good';
                        elseif ($value_rating == 'Fair Value') $badge_class = 'value-fair';
                        else $badge_class = 'value-premium';
                        ?>
                        <span class="value-badge <?php echo $badge_class; ?>">
                            <?php echo $value_rating; ?>
                        </span>
                    <?php } ?>
                    
                    <div class="spec-comparison">
                        <?php if (!$is_accessory_mode): ?>
                        <div class="spec-row">
                            <span class="spec-label">⚡ Performance</span>
                            <span class="spec-value <?php if ($performance_scores[$index] == $best_specs['performance']) echo 'best'; ?>">
                                <?php echo $performance_scores[$index]; ?>/100
                            </span>
                        </div>
                        
                        <div class="spec-row">
                            <span class="spec-label">💾 RAM</span>
                            <span class="spec-value <?php if ($product['ram_gb'] == $best_specs['ram_gb']) echo 'best'; ?>">
                                <?php echo $product['ram_gb']; ?> GB
                            </span>
                        </div>
                        
                        <div class="spec-row">
                            <span class="spec-label">💿 Storage</span>
                            <span class="spec-value <?php if ($product['storage_gb'] == $best_specs['storage_gb']) echo 'best'; ?>">
                                <?php echo $product['storage_gb']; ?> GB <?php echo $product['storage_type']; ?>
                            </span>
                        </div>
                        
                        <div class="spec-row">
                            <span class="spec-label">📺 Display</span>
                            <span class="spec-value <?php if ($product['display_size'] == $best_specs['display_size']) echo 'best'; ?>">
                                <?php echo $product['display_size']; ?>"
                            </span>
                        </div>
                        
                        <div class="spec-row">
                            <span class="spec-label">🔧 CPU</span>
                            <span class="spec-value">
                                <?php echo htmlspecialchars($product['cpu']); ?>
                            </span>
                        </div>
                        
                        <div class="spec-row">
                            <span class="spec-label">🎮 GPU</span>
                            <span class="spec-value">
                                <?php echo htmlspecialchars($product['gpu']); ?>
                            </span>
                        </div>
                        <?php else: ?>
                        <div class="spec-row">
                            <span class="spec-label">📝 Description</span>
                            <span class="spec-value" style="font-size: 0.85rem; font-weight: normal;">
                                <?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="spec-row">
                            <span class="spec-label">🎯 Best For</span>
                            <span class="spec-value">
                                <?php echo getUseCaseIcon($product['primary_use_case']); ?> 
                                <?php echo htmlspecialchars($product['primary_use_case']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="compare-card-actions">
                        <a href="product_details.php?product_id=<?php echo $product['product_id']; ?>" 
                           class="btn" style="background: #6c757d; color: white; text-align: center;">
                            👁️ View Details
                        </a>
                        <form action="cart_process.php" method="post" style="margin: 0;">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                🛒 Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Comparison Insights -->
    <div class="compare-container" style="margin-top: 30px; padding: 25px;">
        <h3 style="margin-bottom: 20px; color: #1a1a1a;">📊 Comparison Insights</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            <!-- Best Price -->
            <div style="padding: 20px; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #10b981;">
                <h4 style="color: #10b981; margin: 0 0 10px 0;">💰 Best Price</h4>
                <?php 
                $cheapest = null;
                $cheapest_price = PHP_FLOAT_MAX;
                foreach ($products as $p) {
                    if ($p['price'] < $cheapest_price) {
                        $cheapest_price = $p['price'];
                        $cheapest = $p;
                    }
                }
                ?>
                <p style="margin: 0; font-size: 0.95rem; color: #495057;">
                    <strong><?php echo htmlspecialchars($cheapest['product_name']); ?></strong><br>
                    at $<?php echo number_format($cheapest['price'], 2); ?>
                </p>
            </div>
            
            <!-- Best Performance -->
            <?php if (!$is_accessory_mode): ?>
            <div style="padding: 20px; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #3b82f6;">
                <h4 style="color: #3b82f6; margin: 0 0 10px 0;">⚡ Best Performance</h4>
                <p style="margin: 0; font-size: 0.95rem; color: #495057;">
                    <strong><?php echo htmlspecialchars($winner['product_name']); ?></strong><br>
                    Score: <?php echo $performance_scores[$winner_index]; ?>/100
                </p>
            </div>
            
            <!-- Most RAM -->
            <div style="padding: 20px; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #8b5cf6;">
                <h4 style="color: #8b5cf6; margin: 0 0 10px 0;">💾 Most RAM</h4>
                <?php 
                foreach ($products as $p) {
                    if ($p['ram_gb'] == $best_specs['ram_gb']) {
                        echo '<p style="margin: 0; font-size: 0.95rem; color: #495057;">';
                        echo '<strong>' . htmlspecialchars($p['product_name']) . '</strong><br>';
                        echo $p['ram_gb'] . ' GB RAM';
                        echo '</p>';
                        break;
                    }
                }
                ?>
            </div>
            
            <!-- Largest Display -->
            <div style="padding: 20px; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #f59e0b;">
                <h4 style="color: #f59e0b; margin: 0 0 10px 0;">📺 Largest Display</h4>
                <?php 
                foreach ($products as $p) {
                    if ($p['display_size'] == $best_specs['display_size']) {
                        echo '<p style="margin: 0; font-size: 0.95rem; color: #495057;">';
                        echo '<strong>' . htmlspecialchars($p['product_name']) . '</strong><br>';
                        echo $p['display_size'] . '" Screen';
                        echo '</p>';
                        break;
                    }
                }
                ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Price Difference Analysis -->
        <div style="margin-top: 25px; padding: 20px; background: #fef3c7; border-radius: 10px;">
            <h4 style="margin: 0 0 10px 0; color: #92400e;">💡 Quick Analysis</h4>
            <?php 
            $price_diff = max($prices) - min($prices);
            $avg_price = array_sum($prices) / count($prices);
            ?>
            <p style="margin: 5px 0; color: #78350f; line-height: 1.6;">
                • Price difference: <strong>$<?php echo number_format($price_diff, 2); ?></strong><br>
                • Average price: <strong>$<?php echo number_format($avg_price, 2); ?></strong><br>
                • The most expensive option costs <strong><?php echo round(($price_diff / min($prices)) * 100); ?>%</strong> more than the cheapest
            </p>
        </div>
    </div>

<?php elseif (count($products) == 1): ?>
    <!-- Only one product selected -->
    <div class="empty-comparison">
        <div class="empty-comparison-icon">⚖️</div>
        <h2>Add More Products to Compare</h2>
        <p>You need at least 2 products to make a comparison. Add more from the catalog!</p>
        <a href="products.php" class="btn btn-primary">Browse Products</a>
    </div>

<?php else: ?>
    <!-- No products selected -->
    <div class="empty-comparison">
        <div class="empty-comparison-icon">🔍</div>
        <h2>No Products Selected</h2>
        <p>Select 2 to 4 products from our catalog to compare their specifications side-by-side.</p>
        <div style="margin-top: 30px;">
            <h3 style="color: #495057; font-size: 1.2rem; margin-bottom: 15px;">How to Compare:</h3>
            <ol style="text-align: left; max-width: 500px; margin: 0 auto; color: #666; line-height: 2;">
                <li>Go to the <a href="products.php" style="color: #3b82f6; font-weight: 600;">Products Page</a></li>
                <li>Check the "Add to Compare" box on products you're interested in</li>
                <li>Click "Compare Now" when you've selected 2-4 products</li>
                <li>View detailed side-by-side comparisons</li>
            </ol>
        </div>
        <a href="products.php" class="btn btn-primary" style="margin-top: 25px;">Start Shopping</a>
    </div>
<?php endif; ?>

<script>
// View switcher
function switchView(view) {
    const tableView = document.getElementById('tableView');
    const cardsView = document.getElementById('cardsView');
    const tableBtn = document.getElementById('tableViewBtn');
    const cardsBtn = document.getElementById('cardsViewBtn');
    
    if (view === 'table') {
        tableView.style.display = 'block';
        cardsView.classList.remove('active');
        tableBtn.classList.add('active');
        cardsBtn.classList.remove('active');
    } else {
        tableView.style.display = 'none';
        cardsView.classList.add('active');
        cardsBtn.classList.add('active');
        tableBtn.classList.remove('active');
    }
    
    // Save preference
    localStorage.setItem('compareView', view);
}

// Restore view preference
window.addEventListener('load', function() {
    const savedView = localStorage.getItem('compareView');
    if (savedView === 'cards') {
        switchView('cards');
    }
});

// Export comparison to TXT
function exportComparison() {
    // Create a simple text export
    let exportText = "LAPTOP COMPARISON REPORT\n";
    exportText += "========================\n\n";
    
    const table = document.querySelector('.compare-table');
    if (table) {
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length > 0) {
                const feature = cells[0].textContent.trim();
                exportText += feature + ":\n";
                for (let i = 1; i < cells.length; i++) {
                    exportText += "  Product " + i + ": " + cells[i].textContent.trim() + "\n";
                }
                exportText += "\n";
            }
        });
    }
    
    // Create and download file
    const blob = new Blob([exportText], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'laptop-comparison-' + new Date().getTime() + '.txt';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

// Export to PDF with detailed analysis
function exportToPDF() {
    // Get the current product IDs from URL
    const urlParams = new URLSearchParams(window.location.search);
    const ids = urlParams.get('ids');
    
    if (ids) {
        // Open PDF export page in new window
        window.open(`export_comparison_pdf.php?ids=${ids}`, '_blank');
    } else {
        alert('No products selected for comparison.');
    }
}


// Enhanced export with CSV format
function exportToCSV() {
    let csv = "Feature,";
    
    // Get product names for headers
    const productHeaders = document.querySelectorAll('.product-header-cell h3');
    const productNames = Array.from(productHeaders).map(h => h.textContent.trim());
    csv += productNames.join(',') + "\n";
    
    // Get all rows
    const rows = document.querySelectorAll('.compare-table tbody tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
            const rowData = Array.from(cells).map(cell => {
                let text = cell.textContent.trim();
                // Clean up text (remove extra whitespace, newlines)
                text = text.replace(/\s+/g, ' ');
                // Escape commas
                if (text.includes(',')) {
                    text = '"' + text + '"';
                }
                return text;
            });
            csv += rowData.join(',') + "\n";
        }
    });
    
    // Download CSV
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'laptop-comparison-' + new Date().getTime() + '.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

// Highlight differences on hover
document.querySelectorAll('.compare-table tbody tr').forEach(row => {
    row.addEventListener('mouseenter', function() {
        this.style.background = '#f1f5f9';
    });
    
    row.addEventListener('mouseleave', function() {
        this.style.background = '';
    });
});

// Add keyboard navigation
document.addEventListener('keydown', function(e) {
    // 'T' for table view
    if (e.key === 't' || e.key === 'T') {
        switchView('table');
    }
    // 'C' for card view
    if (e.key === 'c' || e.key === 'C') {
        switchView('cards');
    }
    // 'P' for print
    if (e.key === 'p' || e.key === 'P') {
        e.preventDefault();
        window.print();
    }
});

// Smooth scroll to top
window.scrollTo({ top: 0, behavior: 'smooth' });

// Add tooltips to specs
const specs = document.querySelectorAll('.compare-table tbody td:first-child');
const tooltips = {
    'Performance Score': 'Combined rating based on RAM, GPU, and storage capabilities',
    'Price': 'Current retail price',
    'Value Rating': 'Performance-to-price ratio',
    'RAM': 'Random Access Memory - affects multitasking capability',
    'Storage': 'Internal storage capacity and type',
    'Display': 'Screen size in inches',
    'Processor (CPU)': 'Central Processing Unit - the brain of the laptop',
    'Graphics (GPU)': 'Graphics Processing Unit - handles visual processing',
    'Best For': 'Primary recommended use case'
};

specs.forEach(spec => {
    const text = spec.textContent.trim();
    // Remove emoji if present
    const cleanText = text.replace(/[^\w\s]/gi, '').trim();
    
    if (tooltips[cleanText]) {
        spec.classList.add('tooltip-trigger');
        spec.setAttribute('data-tooltip', tooltips[cleanText]);
    }
});

// Animate winner badge
const winnerBadges = document.querySelectorAll('.winner-badge');
winnerBadges.forEach(badge => {
    badge.style.animation = 'pulse 2s infinite';
});

// Add pulse animation
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
`;
document.head.appendChild(style);

// Share comparison
function shareComparison() {
    const url = window.location.href;
    if (navigator.share) {
        navigator.share({
            title: 'Laptop Comparison',
            text: 'Check out this laptop comparison',
            url: url
        }).catch(err => console.log('Error sharing:', err));
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            alert('Comparison link copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy:', err);
        });
    }
}

// Add comparison to favorites (placeholder)
function saveComparison() {
    const url = window.location.href;
    localStorage.setItem('savedComparison', url);
    alert('Comparison saved! You can access it from your profile.');
}

// Performance monitoring
if (window.performance && window.performance.timing) {
    window.addEventListener('load', function() {
        const timing = window.performance.timing;
        const loadTime = timing.loadEventEnd - timing.navigationStart;
        console.log(`Comparison page loaded in ${loadTime}ms`);
    });
}

// Add sticky header on scroll for table view
let lastScrollTop = 0;
window.addEventListener('scroll', function() {
    const tableHeader = document.querySelector('.compare-table thead');
    if (tableHeader) {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > 300) {
            tableHeader.style.position = 'sticky';
            tableHeader.style.top = '0';
            tableHeader.style.zIndex = '100';
            tableHeader.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        } else {
            tableHeader.style.position = 'static';
            tableHeader.style.boxShadow = 'none';
        }
        lastScrollTop = scrollTop;
    }
});

// Highlight row on click
document.querySelectorAll('.compare-table tbody tr').forEach(row => {
    row.style.cursor = 'pointer';
    row.addEventListener('click', function() {
        // Remove previous highlights
        document.querySelectorAll('.compare-table tbody tr').forEach(r => {
            r.style.outline = 'none';
        });
        // Add highlight to clicked row
        this.style.outline = '2px solid #3b82f6';
        this.style.outlineOffset = '-2px';
    });
});

// Auto-select best value product
window.addEventListener('load', function() {
    const valueBadges = document.querySelectorAll('.value-excellent');
    if (valueBadges.length > 0) {
        console.log('Best value products highlighted');
    }
});

// Add print styles dynamically
const printStyles = document.createElement('style');
printStyles.textContent = `
    @media print {
        body { background: white; }
        .comparison-header,
        .comparison-controls,
        .comparison-actions,
        .action-cell { display: none !important; }
        .compare-table { font-size: 0.8rem; }
        .compare-card-actions { display: none !important; }
    }
`;
document.head.appendChild(printStyles);

// Score Breakdown Modal
function showScoreBreakdown(index) {
    const products = <?php echo json_encode($products); ?>;
    const breakdowns = <?php echo json_encode($score_breakdowns); ?>;
    const product = products[index];
    const breakdown = breakdowns[index];
    
    if (!product || !breakdown) return;
    
    // Create modal
    const modalId = 'breakdownModal';
    let modal = document.getElementById(modalId);
    
    if (modal) modal.remove();
    
    modal = document.createElement('div');
    modal.id = modalId;
    modal.style.cssText = `
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center;
        z-index: 10000; animation: fadeIn 0.2s ease;
    `;
    
    const content = `
        <div style="background: white; padding: 25px; border-radius: 15px; width: 90%; max-width: 450px; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <button onclick="document.getElementById('${modalId}').remove()" style="position: absolute; top: 15px; right: 15px; border: none; background: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
            
            <h3 style="margin: 0 0 5px 0; color: #1a1a1a;">${product.product_name}</h3>
            <p style="margin: 0 0 20px 0; color: #666; font-size: 0.9rem;">Performance Score Breakdown</p>
            
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 25px;">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: #f0f9ff; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: bold; color: #0284c7; border: 4px solid #bae6fd;">
                    ${Math.round(breakdown.cpu + breakdown.gpu + breakdown.ram + breakdown.storage)}
                </div>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <!-- CPU -->
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600; color: #333;">🔧 CPU</div>
                        <div style="font-size: 0.75rem; color: #666;">Benchmark: ${Math.round(breakdown.cpu_raw).toLocaleString()}</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 700; color: #0284c7;">${breakdown.cpu.toFixed(1)} / 35</div>
                        <div style="width: 100px; height: 6px; background: #e0e0e0; border-radius: 3px; margin-top: 4px; overflow: hidden;">
                            <div style="width: ${(breakdown.cpu/35)*100}%; height: 100%; background: #0284c7;"></div>
                        </div>
                    </div>
                </div>
                
                <!-- GPU -->
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600; color: #333;">🎮 GPU</div>
                        <div style="font-size: 0.75rem; color: #666;">Benchmark: ${Math.round(breakdown.gpu_raw).toLocaleString()}</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 700; color: #7c3aed;">${breakdown.gpu.toFixed(1)} / 40</div>
                        <div style="width: 100px; height: 6px; background: #e0e0e0; border-radius: 3px; margin-top: 4px; overflow: hidden;">
                            <div style="width: ${(breakdown.gpu/40)*100}%; height: 100%; background: #7c3aed;"></div>
                        </div>
                    </div>
                </div>
                
                <!-- RAM -->
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600; color: #333;">💾 RAM</div>
                        <div style="font-size: 0.75rem; color: #666;">${product.ram_gb} GB</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 700; color: #059669;">${breakdown.ram} / 15</div>
                        <div style="width: 100px; height: 6px; background: #e0e0e0; border-radius: 3px; margin-top: 4px; overflow: hidden;">
                            <div style="width: ${(breakdown.ram/15)*100}%; height: 100%; background: #059669;"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Storage -->
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600; color: #333;">💿 Storage</div>
                        <div style="font-size: 0.75rem; color: #666;">${product.storage_gb} GB</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 700; color: #d97706;">${breakdown.storage} / 10</div>
                        <div style="width: 100px; height: 6px; background: #e0e0e0; border-radius: 3px; margin-top: 4px; overflow: hidden;">
                            <div style="width: ${(breakdown.storage/10)*100}%; height: 100%; background: #d97706;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; font-size: 0.8rem; color: #888; text-align: center;">
                Scores are based on synthetic benchmarks and hardware specifications.
            </div>
        </div>
    `;
    
    modal.innerHTML = content;
    document.body.appendChild(modal);
    
    // Close on outside click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.remove();
    });
}

// Highlight Differences Logic
function toggleDifferences() {
    const btn = document.getElementById('btn-diff');
    const isActive = btn.classList.toggle('active');
    
    if (isActive) {
        btn.style.background = '#e0f2fe';
        btn.style.color = '#0284c7';
        btn.style.borderColor = '#0284c7';
    } else {
        btn.style.background = 'white';
        btn.style.color = '#64748b';
        btn.style.borderColor = '#e2e8f0';
    }
    
    const rows = document.querySelectorAll('.compare-table tbody tr');
    
    rows.forEach(row => {
        const cells = Array.from(row.querySelectorAll('td')).slice(1); // Skip label cell
        if (cells.length < 2) return;
        
        const firstValue = cells[0].textContent.trim();
        const allSame = cells.every(cell => cell.textContent.trim() === firstValue);
        
        if (isActive && allSame) {
            row.style.display = 'none';
        } else {
            row.style.display = '';
        }
    });
}

// Remove Product Logic
function removeProduct(productId) {
    const urlParams = new URLSearchParams(window.location.search);
    let ids = urlParams.get('ids');
    
    if (ids) {
        let idArray = ids.split(',');
        idArray = idArray.filter(id => id != productId);
        
        if (idArray.length < 2) {
            alert('You need at least 2 products to compare.');
            return;
        }
        
        urlParams.set('ids', idArray.join(','));
        window.location.search = urlParams.toString();
    }
}

// Sync localStorage with URL on load
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const ids = urlParams.get('ids');
    if (ids) {
        const idArray = ids.split(',');
        localStorage.setItem('compare_ids', JSON.stringify(idArray));
    }
});
</script>

<?php include 'includes/footer.php'; ?>
