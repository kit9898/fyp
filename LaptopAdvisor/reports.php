<?php
require_once 'includes/auth_check.php';
include 'includes/header.php';

$view = $_GET['view'] ?? 'dashboard'; // Default to the dashboard view
$user_id = $_SESSION['user_id'];
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* Global Reset & Typography */
:root {
    --primary-color: #4f46e5;
    --primary-hover: #4338ca;
    --text-main: #111827;
    --text-muted: #6b7280;
    --bg-body: #f3f4f6;
    --bg-card: #ffffff;
    --border-color: #e5e7eb;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
}

body {
    background-color: var(--bg-body);
    font-family: 'Inter', sans-serif; /* Professional font stack */
    color: var(--text-main);
}

/* Layout Grid */
.dashboard-wrapper {
    display: grid;
    grid-template-columns: 260px 1fr;
    min-height: calc(100vh - 80px); /* Adjust based on header height */
    max-width: 1600px;
    margin: 0 auto;
}

/* Sidebar */
.sidebar {
    background: var(--bg-card);
    border-right: 1px solid var(--border-color);
    padding: 24px;
    display: flex;
    flex-direction: column;
    position: sticky;
    top: 0;
    height: calc(100vh - 80px);
    overflow-y: auto;
}

.sidebar-header {
    margin-bottom: 32px;
    padding-left: 12px;
}

.sidebar-title {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-muted);
    font-weight: 600;
}

.nav-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.nav-item a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 8px;
    color: var(--text-muted);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.nav-item a:hover {
    background-color: #f9fafb;
    color: var(--text-main);
}

.nav-item a.active {
    background-color: #eef2ff;
    color: var(--primary-color);
}

.nav-icon {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Main Content */
.main-content {
    padding: 32px;
    overflow-y: auto;
}

/* Header Section */
.content-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 32px;
}

.header-left h1 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 0 4px 0;
    color: var(--text-main);
}

.header-left p {
    color: var(--text-muted);
    margin: 0;
    font-size: 0.95rem;
}

.header-actions {
    display: flex;
    gap: 12px;
}

.btn-action {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    padding: 8px 16px;
    border-radius: 6px;
    color: var(--text-main);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.btn-action:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

.btn-primary-action {
    background: var(--primary-color);
    color: white;
    border: 1px solid transparent;
}

.btn-primary-action:hover {
    background: var(--primary-hover);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
}

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.stat-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-muted);
}

.stat-icon-wrapper {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.stat-value {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--text-main);
    line-height: 1.2;
    margin-bottom: 4px;
}

.stat-trend {
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 4px;
}

.trend-up { color: var(--success-color); }
.trend-down { color: var(--danger-color); }
.trend-neutral { color: var(--text-muted); }

/* Content Cards */
.card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    margin-bottom: 24px;
    overflow: hidden;
}

.card-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
    color: var(--text-main);
}

.card-body {
    padding: 24px;
}

/* Tables/Lists */
.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    text-align: left;
    padding: 12px 24px;
    font-size: 0.75rem;
    text-transform: uppercase;
    color: var(--text-muted);
    font-weight: 600;
    background: #f9fafb;
    border-bottom: 1px solid var(--border-color);
}

.data-table td {
    padding: 16px 24px;
    font-size: 0.875rem;
    color: var(--text-main);
    border-bottom: 1px solid var(--border-color);
}

.data-table tr:last-child td {
    border-bottom: none;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 2px 8px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-success { background: #d1fae5; color: #065f46; }
.status-warning { background: #fef3c7; color: #92400e; }

/* Responsive */
@media (max-width: 1024px) {
    .dashboard-wrapper {
        grid-template-columns: 1fr;
    }
    .sidebar {
        display: none; /* Or convert to mobile menu */
    }
}
</style>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-title">Analytics</div>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="?view=dashboard" class="<?php if ($view == 'dashboard') echo 'active'; ?>">
                    <span class="nav-icon">📊</span> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="?view=purchase" class="<?php if ($view == 'purchase') echo 'active'; ?>">
                    <span class="nav-icon">💳</span> Purchase Analysis
                </a>
            </li>
            <li class="nav-item">
                <a href="?view=recommendations" class="<?php if ($view == 'recommendations') echo 'active'; ?>">
                    <span class="nav-icon">💡</span> Insights
                </a>
            </li>
        </ul>

        <div style="margin-top: auto; padding: 16px; background: #f3f4f6; border-radius: 8px;">
            <div style="font-size: 0.875rem; font-weight: 600; margin-bottom: 4px;">Need Help?</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 12px;">Check our documentation or contact support.</div>
            <button class="btn-action" style="width: 100%; justify-content: center; background: white;">Contact Support</button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        
        <?php if ($view == 'dashboard'): ?>
            <!-- DASHBOARD VIEW -->
            <div class="content-header">
                <div class="header-left">
                    <h1>Dashboard</h1>
                    <p>Overview of your activity and performance.</p>
                </div>
                <div class="header-actions">
                    <button class="btn-action">
                        <span>📅</span> <?php echo date('M d, Y'); ?>
                    </button>
                    <button class="btn-action btn-primary-action">
                        <span>⬇️</span> Export Report
                    </button>
                </div>
            </div>

            <?php
            // Fetch summary stats
            $order_stmt = $conn->prepare("SELECT COUNT(order_id) as order_count, SUM(total_amount) as total_spent FROM orders WHERE user_id = ?");
            $order_stmt->bind_param("i", $user_id);
            $order_stmt->execute();
            $summary = $order_stmt->get_result()->fetch_assoc();
            $order_stmt->close();
            
            $rating_stmt = $conn->prepare("SELECT COUNT(rating_id) as rating_count FROM recommendation_ratings WHERE user_id = ?");
            $rating_stmt->bind_param("i", $user_id);
            $rating_stmt->execute();
            $rating_count = $rating_stmt->get_result()->fetch_assoc()['rating_count'];
            $rating_stmt->close();
            
            $avg_order = ($summary['order_count'] > 0) ? $summary['total_spent'] / $summary['order_count'] : 0;
            ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Total Spent</span>
                        <div class="stat-icon-wrapper" style="background: #eef2ff; color: #4f46e5;">💰</div>
                    </div>
                    <div class="stat-value currency-price" data-base-price="<?= $summary['total_spent'] ?? 0; ?>">$<?php echo number_format($summary['total_spent'] ?? 0, 2); ?></div>
                    <div class="stat-trend trend-up">
                        <span>↗</span> 12% <span class="trend-neutral">vs last month</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Orders Placed</span>
                        <div class="stat-icon-wrapper" style="background: #ecfdf5; color: #10b981;">📦</div>
                    </div>
                    <div class="stat-value"><?php echo $summary['order_count'] ?? 0; ?></div>
                    <div class="stat-trend trend-neutral">
                        <span>-</span> 0% <span class="trend-neutral">vs last month</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Avg. Order Value</span>
                        <div class="stat-icon-wrapper" style="background: #fff7ed; color: #f59e0b;">📈</div>
                    </div>
                    <div class="stat-value currency-price" data-base-price="<?= $avg_order; ?>">$<?php echo number_format($avg_order, 2); ?></div>
                    <div class="stat-trend trend-up">
                        <span>↗</span> 5% <span class="trend-neutral">vs last month</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Engagement</span>
                        <div class="stat-icon-wrapper" style="background: #fef2f2; color: #ef4444;">⭐</div>
                    </div>
                    <div class="stat-value"><?php echo $rating_count ?? 0; ?></div>
                    <div class="stat-trend trend-neutral">
                        <span class="trend-neutral">Products Rated</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Orders</h3>
                    <a href="profile.php" style="font-size: 0.875rem; color: var(--primary-color); text-decoration: none; font-weight: 500;">View All</a>
                </div>
                <div class="card-body" style="padding: 0;">
                    <?php
                    $recent_orders = [];
                    $ro_stmt = $conn->prepare("SELECT order_id, total_amount, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 5");
                    $ro_stmt->bind_param("i", $user_id);
                    $ro_stmt->execute();
                    $ro_result = $ro_stmt->get_result();
                    while ($row = $ro_result->fetch_assoc()) $recent_orders[] = $row;
                    $ro_stmt->close();
                    ?>
                    <?php if (!empty($recent_orders)): ?>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recent_orders as $order): ?>
                                        <tr>
                                            <td style="font-family: monospace; font-weight: 600;">#<?php echo $order['order_id']; ?></td>
                                            <td><?php echo date("M j, Y", strtotime($order['order_date'])); ?></td>
                                            <td style="font-weight: 600;"><span class="currency-price" data-base-price="<?= $order['total_amount']; ?>">$<?php echo number_format($order['total_amount'], 2); ?></span></td>
                                            <td><span class="status-badge status-success">Completed</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="padding: 32px; text-align: center; color: var(--text-muted);">
                            No recent orders found.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php elseif ($view == 'purchase'): ?>
            <!-- PURCHASE ANALYSIS VIEW -->
            <div class="content-header">
                <div class="header-left">
                    <h1>Purchase Analysis</h1>
                    <p>Detailed breakdown of your spending habits.</p>
                </div>
                <div class="header-actions">
                    <button class="btn-action"><span>📅</span> Last 12 Months</button>
                </div>
            </div>

            <?php
            // Fetch Spending Data by Multiple Dimensions
            $sql = "SELECT p.brand, p.product_category, p.primary_use_case, oi.price_at_purchase, oi.quantity 
                    FROM orders o 
                    JOIN order_items oi ON o.order_id = oi.order_id 
                    JOIN products p ON oi.product_id = p.product_id 
                    WHERE o.user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $spending_by_brand = [];
            $spending_by_category = [];
            $spending_by_use_case = [];
            
            while ($row = $result->fetch_assoc()) {
                $line_total = $row['price_at_purchase'] * $row['quantity'];
                
                // Aggregate by Brand
                $brand = $row['brand'] ?: 'Unknown';
                $spending_by_brand[$brand] = ($spending_by_brand[$brand] ?? 0) + $line_total;
                
                // Aggregate by Category
                $cat = $row['product_category'] ? ucwords(str_replace('_', ' ', $row['product_category'])) : 'Other';
                $spending_by_category[$cat] = ($spending_by_category[$cat] ?? 0) + $line_total;
                
                // Aggregate by Use Case
                $use = $row['primary_use_case'] ?: 'General';
                $spending_by_use_case[$use] = ($spending_by_use_case[$use] ?? 0) + $line_total;
            }
            $stmt->close();
            
            // Sort all arrays descending
            arsort($spending_by_brand);
            arsort($spending_by_category);
            arsort($spending_by_use_case);

            // Monthly Trend
            $trend_sql = "SELECT DATE_FORMAT(order_date, '%Y-%m') as month, SUM(total_amount) as total FROM orders WHERE user_id = ? GROUP BY month ORDER BY month ASC LIMIT 12";
            $trend_stmt = $conn->prepare($trend_sql);
            $trend_stmt->bind_param("i", $user_id);
            $trend_stmt->execute();
            $trend_result = $trend_stmt->get_result();
            $months = []; $monthly_totals = [];
            while ($row = $trend_result->fetch_assoc()) {
                $months[] = date("M Y", strtotime($row['month'] . "-01"));
                $monthly_totals[] = $row['total'];
            }
            $trend_stmt->close();
            ?>

            <div style="margin-bottom: 24px;">
                <!-- AI Spending Analysis -->
                <div style="padding: 24px; background: linear-gradient(to right, #f0f9ff, #e0f2fe); border: 1px solid #bae6fd; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h3 style="margin: 0; color: #0369a1; display: flex; align-items: center; gap: 10px; font-size: 1.1rem;">
                            <span style="font-size: 1.4rem;">🤖</span> AI Financial Insights
                        </h3>
                        <button id="generateAiBtn" onclick="generateSpendingInsight()" class="btn-action btn-primary-action" style="background: #0ea5e9; border: none;">
                            <span>✨</span> Analyze Spending
                        </button>
                    </div>
                    
                    <div id="ai-analysis-container" style="color: #0c4a6e; line-height: 1.6; min-height: 50px;">
                        <p>Click "Analyze Spending" to get an AI-powered breakdown of your purchasing habits.</p>
                    </div>
                    <div id="ai-loader" style="display: none; text-align: center; color: #0284c7; padding: 20px;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 1.5rem;"></i><br>
                        <span style="font-size: 0.9rem; margin-top: 5px; display: block;">Crunching the numbers...</span>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 24px;">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Spending Breakdown</h3>
                        <select id="breakdownSelect" onchange="updateBreakdownChart()" style="padding: 6px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.875rem; color: var(--text-main); outline: none;">
                            <option value="brand">By Brand</option>
                            <option value="category">By Category</option>
                            <option value="use_case">By Use Case</option>
                        </select>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <?php if (!empty($spending_by_brand)): ?>
                                <canvas id="breakdownChart"></canvas>
                            <?php else: ?>
                                <p style="text-align: center; color: var(--text-muted); padding-top: 100px;">No data available.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Monthly Spending Trend</h3>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <?php if (!empty($months)): ?>
                                <canvas id="trendChart"></canvas>
                            <?php else: ?>
                                <p style="text-align: center; color: var(--text-muted); padding-top: 100px;">No data available.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // Common Chart Options
                Chart.defaults.font.family = "'Inter', sans-serif";
                Chart.defaults.color = '#6b7280';
                
                // Data for Breakdown Chart
                const breakdownData = {
                    brand: {
                        labels: <?php echo json_encode(array_keys($spending_by_brand)); ?>,
                        data: <?php echo json_encode(array_values($spending_by_brand)); ?>
                    },
                    category: {
                        labels: <?php echo json_encode(array_keys($spending_by_category)); ?>,
                        data: <?php echo json_encode(array_values($spending_by_category)); ?>
                    },
                    use_case: {
                        labels: <?php echo json_encode(array_keys($spending_by_use_case)); ?>,
                        data: <?php echo json_encode(array_values($spending_by_use_case)); ?>
                    }
                };

                let breakdownChartInstance = null;

                function initBreakdownChart() {
                    const ctx = document.getElementById('breakdownChart');
                    if (!ctx) return;

                    // Default to Brand
                    const initialData = breakdownData.brand;

                    breakdownChartInstance = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: initialData.labels,
                            datasets: [{
                                data: initialData.data,
                                backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#6366f1'],
                                borderWidth: 0,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '75%',
                            plugins: {
                                legend: { position: 'right', labels: { boxWidth: 12, usePointStyle: true } }
                            }
                        }
                    });
                }

                function updateBreakdownChart() {
                    const select = document.getElementById('breakdownSelect');
                    const type = select.value;
                    const newData = breakdownData[type];

                    if (breakdownChartInstance) {
                        breakdownChartInstance.data.labels = newData.labels;
                        breakdownChartInstance.data.datasets[0].data = newData.data;
                        breakdownChartInstance.update();
                    }
                }

                // Initialize Charts
                document.addEventListener('DOMContentLoaded', () => {
                    initBreakdownChart();

                    const trendCtx = document.getElementById('trendChart');
                    if (trendCtx) {
                        new Chart(trendCtx, {
                            type: 'line',
                            data: {
                                labels: <?php echo json_encode($months); ?>,
                                datasets: [{
                                    label: 'Spending',
                                    data: <?php echo json_encode($monthly_totals); ?>,
                                    borderColor: '#4f46e5',
                                    backgroundColor: 'rgba(79, 70, 229, 0.05)',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: true,
                                    fill: true,
                                    pointRadius: 4, // Increased size to make single points visible
                                    pointHoverRadius: 7
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#f3f4f6' } },
                                    x: { grid: { display: false } }
                                }
                            }
                        });
                    }
                });

                // AI Insight Generator
                function generateSpendingInsight() {
                    const container = document.getElementById('ai-analysis-container');
                    const loader = document.getElementById('ai-loader');
                    const btn = document.getElementById('generateAiBtn');
                    
                    // Show loader
                    container.style.display = 'none';
                    loader.style.display = 'block';
                    btn.disabled = true;
                    btn.textContent = 'Analyzing...';
                    
                    // Prepare data from PHP variables
                    const spendingData = {
                        brand: <?php echo json_encode($spending_by_brand); ?>,
                        category: <?php echo json_encode($spending_by_category); ?>,
                        use_case: <?php echo json_encode($spending_by_use_case); ?>,
                        trend: {
                            labels: <?php echo json_encode($months); ?>,
                            data: <?php echo json_encode($monthly_totals); ?>
                        }
                    };

                    fetch('ajax/generate_spending_insight.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ spending_data: spendingData })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            container.innerHTML = data.message;
                        } else {
                            container.innerHTML = '<p style="color: #ef4444;">Could not generate insight: ' + (data.message || 'Unknown error') + '</p>';
                        }
                    })
                    .catch(error => {
                        container.innerHTML = '<p style="color: #ef4444;">Network error. Please try again.</p>';
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        loader.style.display = 'none';
                        container.style.display = 'block';
                        btn.disabled = false;
                        btn.textContent = '✨ Re-analyze';
                    });
                }
            </script>

        <?php elseif ($view == 'recommendations'): ?>
            <!-- INSIGHTS VIEW -->
            <div class="content-header">
                <div class="header-left">
                    <h1>Insights</h1>
                    <p>Understanding your preferences.</p>
                </div>
            </div>

            <?php
            $user_stmt = $conn->prepare("SELECT primary_use_case FROM users WHERE user_id = ?");
            $user_stmt->bind_param("i", $user_id);
            $user_stmt->execute();
            $user_pref = $user_stmt->get_result()->fetch_assoc()['primary_use_case'];
            $user_stmt->close();

            $liked_products = []; $disliked_products = [];
            $rating_sql = "SELECT p.product_id, p.product_name, p.brand, r.rating FROM recommendation_ratings r JOIN products p ON r.product_id = p.product_id WHERE r.user_id = ?";
            $rating_stmt = $conn->prepare($rating_sql);
            $rating_stmt->bind_param("i", $user_id);
            $rating_stmt->execute();
            $rating_result = $rating_stmt->get_result();
            while($row = $rating_result->fetch_assoc()) {
                if ($row['rating'] == 1) $liked_products[] = $row;
                if ($row['rating'] == -1) $disliked_products[] = $row;
            }
            ?>

            <div class="card" style="background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); border: none; color: white;">
                <div class="card-body" style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h3 style="margin: 0 0 8px 0; font-size: 1.25rem;">Advisor Persona: <?php echo htmlspecialchars($user_pref); ?></h3>
                        <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">Based on your activity, we've tailored your recommendations for <strong><?php echo htmlspecialchars($user_pref); ?></strong>.</p>
                    </div>
                    <div style="font-size: 2.5rem; opacity: 0.8;">🎯</div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px;">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Liked Products</h3>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        <?php if(!empty($liked_products)): ?>
                            <table class="data-table">
                                <tbody>
                                    <?php foreach(array_slice($liked_products, 0, 5) as $p): ?>
                                        <tr>
                                            <td width="40"><div style="width: 32px; height: 32px; background: #ecfdf5; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #10b981;">👍</div></td>
                                            <td>
                                                <div style="font-weight: 500;"><?php echo htmlspecialchars($p['product_name']); ?></div>
                                                <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo htmlspecialchars($p['brand']); ?></div>
                                            </td>
                                            <td align="right"><a href="product_details.php?product_id=<?php echo $p['product_id']; ?>" style="color: var(--primary-color); text-decoration: none; font-size: 0.875rem;">View</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div style="padding: 24px; text-align: center; color: var(--text-muted);">No liked products yet.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Disliked Products</h3>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        <?php if(!empty($disliked_products)): ?>
                            <table class="data-table">
                                <tbody>
                                    <?php foreach(array_slice($disliked_products, 0, 5) as $p): ?>
                                        <tr>
                                            <td width="40"><div style="width: 32px; height: 32px; background: #fef2f2; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #ef4444;">👎</div></td>
                                            <td>
                                                <div style="font-weight: 500;"><?php echo htmlspecialchars($p['product_name']); ?></div>
                                                <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo htmlspecialchars($p['brand']); ?></div>
                                            </td>
                                            <td align="right"><a href="product_details.php?product_id=<?php echo $p['product_id']; ?>" style="color: var(--primary-color); text-decoration: none; font-size: 0.875rem;">View</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div style="padding: 24px; text-align: center; color: var(--text-muted);">No disliked products yet.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </main>
</div>

<?php include 'includes/footer.php'; ?>