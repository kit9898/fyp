<?php
session_start();
require_once 'includes/db_connect.php';

// --- Logic: Fetch Statistics ---

// 1. Total Revenue
$sql_revenue = "SELECT SUM(total_amount) as total_revenue FROM orders WHERE order_status != 'Cancelled'";
$result_revenue = $conn->query($sql_revenue);
$row_revenue = $result_revenue->fetch_assoc();
$total_revenue = $row_revenue['total_revenue'] ?? 0;

// 2. Total Orders
$sql_orders = "SELECT COUNT(*) as total_orders FROM orders";
$result_orders = $conn->query($sql_orders);
$row_orders = $result_orders->fetch_assoc();
$total_orders = $row_orders['total_orders'] ?? 0;

// 3. Total Users
$sql_users = "SELECT COUNT(*) as total_users FROM users";
$result_users = $conn->query($sql_users);
$row_users = $result_users->fetch_assoc();
$total_users = $row_users['total_users'] ?? 0;

// 4. Chatbot Interactions
$sql_chat = "SELECT COUNT(*) as total_interactions FROM chat_history";
$result_chat = $conn->query($sql_chat);
$row_chat = $result_chat->fetch_assoc();
$total_interactions = $row_chat['total_interactions'] ?? 0;

// --- Logic: Sales & Revenue Chart (Current Year) ---
$current_year = date('Y');
$monthly_revenue = array_fill(0, 12, 0);
$monthly_orders = array_fill(0, 12, 0);

$sql_chart = "SELECT MONTH(order_date) as month, SUM(total_amount) as revenue, COUNT(*) as order_count 
              FROM orders 
              WHERE YEAR(order_date) = $current_year AND order_status != 'Cancelled'
              GROUP BY MONTH(order_date)";
$result_chart = $conn->query($sql_chart);

while ($row = $result_chart->fetch_assoc()) {
    $monthly_revenue[$row['month'] - 1] = (float)$row['revenue'];
    $monthly_orders[$row['month'] - 1] = (int)$row['order_count'];
}

// --- Logic: Best Selling Products ---
$sql_top_products = "SELECT p.product_name, SUM(oi.quantity) as total_sold 
                     FROM order_items oi
                     JOIN products p ON oi.product_id = p.product_id
                     JOIN orders o ON oi.order_id = o.order_id
                     WHERE o.order_status != 'Cancelled'
                     GROUP BY oi.product_id
                     ORDER BY total_sold DESC
                     LIMIT 5";
$result_top_products = $conn->query($sql_top_products);

$top_product_names = [];
$top_product_sales = [];
while ($row = $result_top_products->fetch_assoc()) {
    $top_product_names[] = $row['product_name'];
    $top_product_sales[] = (int)$row['total_sold'];
}

// --- Logic: Recent Reviews (Replacement for AI Performance) ---
$sql_reviews = "SELECT r.*, u.full_name, p.product_name 
                FROM product_reviews r 
                JOIN users u ON r.user_id = u.user_id 
                JOIN products p ON r.product_id = p.product_id 
                ORDER BY r.created_at DESC 
                LIMIT 3";
$result_reviews = $conn->query($sql_reviews);
$recent_reviews = [];
while ($row = $result_reviews->fetch_assoc()) {
    $recent_reviews[] = $row;
}

// --- Logic: Recent System Activities ---
$sql_activities = "SELECT a.*, u.first_name, u.last_name 
                   FROM admin_activity_log a
                   LEFT JOIN admin_users u ON a.admin_id = u.admin_id
                   ORDER BY a.created_at DESC
                   LIMIT 5";
$result_activities = $conn->query($sql_activities);

// --- Logic: Fetch Recent Orders ---
$sql_recent = "SELECT o.order_id, u.full_name, o.total_amount, o.order_status, o.order_date 
               FROM orders o 
               JOIN users u ON o.user_id = u.user_id 
               ORDER BY o.order_date DESC 
               LIMIT 5";
$result_recent = $conn->query($sql_recent);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Smart Laptop Advisor Admin</title>
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="source/assets/css/bootstrap.css">
    <link rel="stylesheet" href="source/assets/vendors/iconly/bold.css">
    <link rel="stylesheet" href="source/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="source/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="source/assets/css/app.css">
    <link rel="shortcut icon" href="source/assets/images/favicon.svg" type="image/x-icon">
</head>

<body>
    <div id="app">
        <?php include 'includes/admin_header.php'; ?>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>Analytics Dashboard</h3>
                        <p class="text-subtitle text-muted">Smart Laptop Advisor Platform Overview</p>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <div class="d-none d-md-flex gap-3 align-items-center me-3 card mb-0 flex-row px-3 py-1 shadow-sm border-0">
                            <div class="text-end">
                                <h6 class="mb-0 fw-bold" id="header-time">--:--</h6>
                                <small class="text-muted" id="header-date" style="font-size: 0.75rem;">Loading...</small>
                            </div>
                            <div class="text-end border-start ps-3" id="weather-container">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-cloud-sun fs-5 text-primary" id="weather-icon"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold" id="weather-temp">--°C</h6>
                                        <small class="text-muted text-truncate d-block" id="weather-loc" style="max-width: 80px; font-size: 0.75rem;">Locating...</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-2"></i>Admin User
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="admin_profile.php"><i class="bi bi-person me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="admin_settings.php"><i class="bi bi-gear me-2"></i>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-content">
                <!-- Key Metrics Cards -->
                <section class="row">
                    <div class="col-12">
                        <div class="row">
                            <!-- Total Revenue -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon purple">
                                                    <i class="iconly-boldBuy"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Total Revenue</h6>
                                                <h6 class="font-extrabold mb-0 currency-price" data-base-price="<?= $total_revenue ?>">$<?= number_format($total_revenue, 2) ?></h6>
                                                <small class="text-success"><i class="bi bi-arrow-up"></i> +12.5%</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- New Orders -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon blue">
                                                    <i class="iconly-boldActivity"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Total Orders</h6>
                                                <h6 class="font-extrabold mb-0"><?= number_format($total_orders) ?></h6>
                                                <small class="text-success"><i class="bi bi-arrow-up"></i> +8.2%</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Registered Users -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon green">
                                                    <i class="iconly-boldProfile"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Registered Users</h6>
                                                <h6 class="font-extrabold mb-0"><?= number_format($total_users) ?></h6>
                                                <small class="text-success"><i class="bi bi-arrow-up"></i> +15.3%</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Chatbot Interactions -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon red">
                                                    <i class="iconly-boldChat"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Chat Interactions</h6>
                                                <h6 class="font-extrabold mb-0"><?= number_format($total_interactions) ?></h6>
                                                <small class="text-success"><i class="bi bi-arrow-up"></i> +22.1%</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Charts Section -->
                <section class="row">
                    <div class="col-12 col-lg-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Sales & Revenue Trends</h4>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary active" data-period="30">30 Days</button>
                                    <button class="btn btn-sm btn-outline-primary" data-period="90">90 Days</button>
                                    <button class="btn btn-sm btn-outline-secondary" id="btn-export-csv">
                                        <i class="bi bi-download"></i> Export CSV
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="chart-sales-revenue"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Best-Selling Products</h4>
                            </div>
                            <div class="card-body">
                                <div id="chart-best-products"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- AI Performance & Recent Activities -->
                <section class="row">
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Recent Customer Reviews</h4>
                            </div>
                            <div class="card-body">
                                <?php if (count($recent_reviews) > 0): ?>
                                    <?php foreach ($recent_reviews as $rev): 
                                        $stars = '';
                                        for ($i=1; $i<=5; $i++) {
                                            $stars .= ($i <= $rev['rating']) ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-secondary"></i>';
                                        }
                                        $short_text = (strlen($rev['review_text']) > 50) ? substr($rev['review_text'], 0, 50) . '...' : $rev['review_text'];
                                        // Time ago logic
                                        $time_diff = time() - strtotime($rev['created_at']);
                                        if ($time_diff < 0) $time_diff = 0;
                                        if ($time_diff < 60) $time_ago_rev = 'Just now';
                                        elseif ($time_diff < 3600) $time_ago_rev = floor($time_diff/60) . 'm ago';
                                        elseif ($time_diff < 86400) $time_ago_rev = floor($time_diff/3600) . 'h ago';
                                        else $time_ago_rev = floor($time_diff/86400) . 'd ago';
                                    ?>
                                    <div class="mb-3 border-bottom pb-2">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1"><?= htmlspecialchars($rev['product_name']) ?></h6>
                                            <span class="small text-muted"><?= $time_ago_rev ?></span>
                                        </div>
                                        <div class="mb-1 small">
                                            <?= $stars ?>
                                            <span class="ms-1 fw-bold"><?= htmlspecialchars($rev['full_name']) ?></span>
                                        </div>
                                        <p class="mb-1 small text-muted">"<?= htmlspecialchars($short_text) ?>"</p>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-center text-muted">No reviews yet.</p>
                                <?php endif; ?>
                                
                                <div class="text-center mt-3">
                                    <a href="admin_reviews.php" class="btn btn-outline-primary btn-sm">Manage Reviews</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Recent System Activities</h4>
                            </div>
                            <div class="card-body">
                                <?php if ($result_activities->num_rows > 0): ?>
                                    <?php while($act = $result_activities->fetch_assoc()): 
                                        $icon = 'bi-circle';
                                        $bg_class = 'bg-secondary';
                                        
                                        if ($act['action'] == 'create') { $icon = 'bi-plus'; $bg_class = 'bg-success'; }
                                        elseif ($act['action'] == 'update') { $icon = 'bi-pencil'; $bg_class = 'bg-primary'; }
                                        elseif ($act['action'] == 'delete') { $icon = 'bi-trash'; $bg_class = 'bg-danger'; }
                                        elseif ($act['action'] == 'login') { $icon = 'bi-box-arrow-in-right'; $bg_class = 'bg-info'; }
                                        elseif ($act['action'] == 'logout') { $icon = 'bi-box-arrow-right'; $bg_class = 'bg-warning'; }
                                        
                                        // Calculate time ago
                                        // Set timezone to match DB (Asia/Kuala_Lumpur)
                                        date_default_timezone_set('Asia/Kuala_Lumpur');
                                        $diff = time() - strtotime($act['created_at']);
                                        
                                        if ($diff < 0) $diff = 0; // Handle clock skew
                                        
                                        if ($diff < 60) $time_ago = 'Just now';
                                        elseif ($diff < 3600) $time_ago = floor($diff / 60) . ' min ago';
                                        elseif ($diff < 86400) $time_ago = floor($diff / 3600) . ' hr ago';
                                        else $time_ago = floor($diff / 86400) . ' days ago';
                                    ?>
                                    <div class="activity-item d-flex align-items-center mb-3">
                                        <div class="activity-icon <?= $bg_class ?> text-white rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi <?= $icon ?>"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-0 small"><?= htmlspecialchars($act['description']) ?></p>
                                            <small class="text-muted" style="font-size: 0.75rem;"><?= $time_ago ?> • <?= htmlspecialchars($act['first_name'] ?? 'System') ?></small>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-center text-muted">No recent activities.</p>
                                <?php endif; ?>
                                
                                <div class="text-center mt-3">
                                    <a href="admin_logs.php" class="btn btn-outline-primary btn-sm">View All Activities</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Quick Actions -->
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Quick Actions</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <div class="d-grid">
                                            <button class="btn btn-primary" onclick="location.href='admin_products.php'">
                                                <i class="bi bi-plus-circle me-2"></i>Add New Product
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <div class="d-grid">
                                            <button class="btn btn-success" onclick="location.href='admin_orders.php'">
                                                <i class="bi bi-eye me-2"></i>View Orders
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <div class="d-grid">
                                            <button class="btn btn-warning" onclick="location.href='admin_conversation_logs.php'">
                                                <i class="bi bi-chat-dots me-2"></i>Check Chatbot
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Recent Orders Table (Dynamic) -->
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Recent Orders (Real-Time)</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-lg">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Customer</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result_recent->num_rows > 0): ?>
                                                <?php while($row = $result_recent->fetch_assoc()): ?>
                                                    <tr>
                                                        <td>#<?= htmlspecialchars($row['order_id']) ?></td>
                                                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                                                        <td><span class="currency-price" data-base-price="<?= $row['total_amount'] ?>">$<?= number_format($row['total_amount'], 2) ?></span></td>
                                                        <td>
                                                            <?php 
                                                            $status_class = 'bg-secondary';
                                                            if ($row['order_status'] == 'Completed') $status_class = 'bg-success';
                                                            elseif ($row['order_status'] == 'Pending') $status_class = 'bg-warning';
                                                            elseif ($row['order_status'] == 'Cancelled') $status_class = 'bg-danger';
                                                            elseif ($row['order_status'] == 'Processing') $status_class = 'bg-info';
                                                            ?>
                                                            <span class="badge <?= $status_class ?>"><?= htmlspecialchars($row['order_status']) ?></span>
                                                        </td>
                                                        <td><?= date('M d, Y', strtotime($row['order_date'])) ?></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">No recent orders found.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?php include 'includes/admin_footer.php'; ?>
        </div>
    </div>

    <script>
        // Check for dark mode
        const isDarkMode = document.body.classList.contains('dark') || document.cookie.indexOf('admin_theme=dark') !== -1;
        const chartTheme = isDarkMode ? 'dark' : 'light';
        const chartBg = isDarkMode ? '#1e1e2d' : '#fff';
        const gridColor = isDarkMode ? '#2a2a3c' : '#f3f3f3';
        const textColor = isDarkMode ? '#a6a8aa' : '#333';

        // Sales & Revenue Chart
        var salesOptions = {
            series: [{
                name: 'Revenue',
                data: <?= json_encode($monthly_revenue) ?>
            }, {
                name: 'Orders',
                data: <?= json_encode($monthly_orders) ?>
            }],
            chart: {
                height: 350,
                type: 'area',
                zoom: {
                    enabled: false
                },
                background: 'transparent', // Let CSS handle container bg
                foreColor: textColor
            },
            theme: {
                mode: chartTheme
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            title: {
                text: 'Revenue & Orders (<?= date("Y") ?>)',
                align: 'left',
                style: { color: textColor }
            },
            grid: {
                row: {
                    colors: [gridColor, 'transparent'], // Alternating row colors
                    opacity: 0.1
                },
                borderColor: gridColor
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                labels: { style: { colors: textColor } }
            },
            yaxis: {
                labels: { style: { colors: textColor } }
            },
            colors: ['#435ebe', '#55c6e8']
        };

        var salesChart = new ApexCharts(document.querySelector("#chart-sales-revenue"), salesOptions);
        salesChart.render();

        // Best Products Chart
        var productsOptions = {
            series: <?= json_encode($top_product_sales) ?>,
            chart: {
                width: '100%',
                height: 350,
                type: 'donut',
                background: 'transparent',
                foreColor: textColor,
                fontFamily: 'Nunito, sans-serif'
            },
            theme: {
                mode: chartTheme,
                monochrome: {
                    enabled: false
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '14px',
                                fontFamily: 'Nunito, sans-serif',
                                fontWeight: 600,
                                color: textColor,
                                offsetY: -5
                            },
                            value: {
                                show: true,
                                fontSize: '16px',
                                fontFamily: 'Nunito, sans-serif',
                                fontWeight: 700,
                                color: textColor,
                                offsetY: 5,
                                formatter: function (val) {
                                    return val;
                                }
                            },
                            total: {
                                show: true,
                                showAlways: false,
                                label: 'Total Sold',
                                fontSize: '14px',
                                fontFamily: 'Nunito, sans-serif',
                                fontWeight: 600,
                                color: textColor,
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => {
                                        return a + b
                                    }, 0)
                                }
                            }
                        }
                    }
                }
            },
            labels: <?= json_encode($top_product_names) ?>,
            // Premium Palette: Blue, Purple, Teal, Orange, Pink to pop against dark
            colors: ['#4361ee', '#3a0ca3', '#4cc9f0', '#f72585', '#7209b7'],
            dataLabels: {
                enabled: false // Cleaner look without text on slices
            },
            stroke: {
                show: true,
                width: 2,
                colors: [isDarkMode ? '#1e1e2d' : '#ffffff'] // Matches card bg for spacing effect
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center', 
                offsetY: 0,
                labels: {
                    colors: textColor,
                    useSeriesColors: false
                },
                markers: {
                    width: 10,
                    height: 10,
                    radius: 12,
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 8
                }
            },
            tooltip: {
                theme: chartTheme,
                y: {
                    formatter: function (val) {
                        return val + " units"
                    }
                }
            },
            noData: {
                text: 'No sales data available',
                style: {
                     color: textColor,
                     fontSize: '14px'
                }
            }
        };

        var productsChart = new ApexCharts(document.querySelector("#chart-best-products"), productsOptions);
        productsChart.render();

        // Period buttons functionality
        let currentPeriod = 30; // Default

        const updateChart = (period) => {
            currentPeriod = period;
            
            // Show loading state if desired, or just fetch
            fetch(`ajax/get_dashboard_data.php?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    salesChart.updateOptions({
                        xaxis: {
                            categories: data.categories
                        },
                        series: [{
                            name: 'Revenue',
                            data: data.revenue
                        }, {
                            name: 'Orders',
                            data: data.orders
                        }],
                         title: {
                            text: `Revenue & Orders (Last ${period} Days)`
                        }
                    });
                })
                .catch(err => console.error('Error fetching data:', err));
        };

        document.querySelectorAll('[data-period]').forEach(button => {
            button.addEventListener('click', function() {
                // Update active state
                document.querySelectorAll('[data-period]').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Fetch data
                const period = this.getAttribute('data-period');
                updateChart(period);
            });
        });

        // Export CSV
        document.getElementById('btn-export-csv').addEventListener('click', function() {
            window.location.href = `ajax/get_dashboard_data.php?period=${currentPeriod}&format=csv`;
        });

        // Trigger default load (30 days) to match initial UI state
        // Note: Initial PHP render shows yearly data. We override this to match the "active" button.
        if (document.querySelector('[data-period="30"]').classList.contains('active')) {
            updateChart(30);
        }

        // --- Time & Weather Widget ---
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const dateString = now.toLocaleDateString([], { weekday: 'long', month: 'short', day: 'numeric' });
            
            document.getElementById('header-time').textContent = timeString;
            document.getElementById('header-date').textContent = dateString;
        }

        setInterval(updateTime, 1000);
        updateTime();

        function fetchWeather() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(async function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    
                    try {
                        // 1. Get Weather from Open-Meteo (Free, No Key)
                        const weatherRes = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`);
                        const weatherData = await weatherRes.json();
                        const temp = Math.round(weatherData.current_weather.temperature);
                        const code = weatherData.current_weather.weathercode;
                        
                        // 2. Get Location Name (BigDataCloud Free API)
                        const locRes = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lon}&localityLanguage=en`);
                        const locData = await locRes.json();
                        const city = locData.city || locData.locality || "Local";

                        // Update UI
                        document.getElementById('weather-temp').textContent = `${temp}°C`;
                        document.getElementById('weather-loc').textContent = city;
                        
                        // Icon Logic (WMO Codes)
                        const iconEl = document.getElementById('weather-icon');
                        iconEl.className = 'bi fs-5 text-primary'; // Reset
                        
                        if (code === 0) iconEl.classList.add('bi-sun');
                        else if (code >= 1 && code <= 3) iconEl.classList.add('bi-cloud-sun');
                        else if (code >= 45 && code <= 48) iconEl.classList.add('bi-cloud-fog');
                        else if (code >= 51 && code <= 67) iconEl.classList.add('bi-cloud-rain');
                        else if (code >= 71 && code <= 86) iconEl.classList.add('bi-snow');
                        else if (code >= 95) iconEl.classList.add('bi-cloud-lightning-rain');
                        else iconEl.classList.add('bi-cloud');
                        
                    } catch (error) {
                        console.error('Weather fetch error:', error);
                        document.getElementById('weather-loc').textContent = "Unavailable";
                    }
                }, function(error) {
                    console.log('Geolocation denied:', error);
                    document.getElementById('weather-container').style.display = 'none'; // Hide if denied
                });
            } else {
                document.getElementById('weather-container').style.display = 'none';
            }
        }
        
        fetchWeather();
    </script>
</body>
</html>
