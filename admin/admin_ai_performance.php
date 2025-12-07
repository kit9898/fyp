<?php
// admin_ai_performance.php - AI Performance Analytics
// Module C: AI Recommendation Engine

// Include database connection
require_once 'includes/db_connect.php';

// ===================== LOGIC SECTION =====================

// Fetch overall KPIs (User Satisfaction)
// Fetch overall KPIs (User Satisfaction)
$kpi_query = "SELECT 
    COUNT(*) as total_ratings,
    SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as total_likes,
    SUM(CASE WHEN rating = -1 THEN 1 ELSE 0 END) as total_dislikes
    FROM recommendation_ratings";
    // Removed WHERE created_at constraint for all-time stats
$kpi_result = mysqli_query($conn, $kpi_query);
$kpis = mysqli_fetch_assoc($kpi_result);

$total_ratings = $kpis['total_ratings'] > 0 ? $kpis['total_ratings'] : 1; // Avoid division by zero
$satisfaction_score = ($kpis['total_likes'] / $total_ratings) * 100;
$dislike_rate = ($kpis['total_dislikes'] / $total_ratings) * 100;

// Fetch performance by persona (based on User's Primary Use Case)
$persona_perf_query = "SELECT 
    p.name as persona_name,
    p.color_theme,
    COUNT(r.rating_id) as total_ratings,
    SUM(CASE WHEN r.rating = 1 THEN 1 ELSE 0 END) as total_likes,
    (SUM(CASE WHEN r.rating = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100 as satisfaction_score
    FROM personas p
    LEFT JOIN users u ON u.primary_use_case = p.name
    LEFT JOIN recommendation_ratings r ON u.user_id = r.user_id
    GROUP BY p.persona_id
    ORDER BY total_ratings DESC";
$persona_perf_result = mysqli_query($conn, $persona_perf_query);

// Fetch performance trends (last 7 days)
$trends_query = "SELECT 
    DATE(created_at) as log_date,
    COUNT(*) as total_ratings,
    (SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100 as daily_satisfaction
    FROM recommendation_ratings
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY log_date ASC";
$trends_result = mysqli_query($conn, $trends_query);

$trend_dates = [];
$trend_satisfaction = [];
$trend_volume = [];

while ($row = mysqli_fetch_assoc($trends_result)) {
    $trend_dates[] = date('M d', strtotime($row['log_date']));
    $trend_satisfaction[] = round($row['daily_satisfaction'], 1);
    $trend_volume[] = $row['total_ratings'];
}

// Fetch Rating Distribution (Likes vs Dislikes)
$dist_query = "SELECT 
    CASE WHEN rating = 1 THEN 'Likes (Positive)' ELSE 'Dislikes (Negative)' END as rating_type,
    COUNT(*) as count
    FROM recommendation_ratings
    GROUP BY rating";
$dist_result = mysqli_query($conn, $dist_query);

$dist_labels = [];
$dist_labels = [];
$dist_values = [];
$dist_colors = [];

while ($row = mysqli_fetch_assoc($dist_result)) {
    $dist_labels[] = $row['rating_type'];
    $dist_values[] = $row['count'];
    
    // Assign color based on type
    if (strpos($row['rating_type'], 'Likes') !== false) {
        $dist_colors[] = '#10b981'; // Green for Likes
    } else {
        $dist_colors[] = '#ef4444'; // Red for Dislikes
    }
}

// Fetch Top Rated Products
$top_products_query = "SELECT 
    p.product_name,
    COUNT(r.rating_id) as rating_count,
    SUM(CASE WHEN r.rating = 1 THEN 1 ELSE 0 END) as likes
    FROM recommendation_ratings r
    JOIN products p ON r.product_id = p.product_id
    GROUP BY p.product_id
    ORDER BY likes DESC
    LIMIT 5";
$top_products_result = mysqli_query($conn, $top_products_query);

// ===================== VIEW SECTION =====================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Performance Analytics - Smart Laptop Advisor Admin</title>
    
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
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>AI Performance Analytics</h3>
                <p class="text-subtitle text-muted">Monitor user satisfaction and recommendation engine performance</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item">AI Engine</li>
                        <li class="breadcrumb-item active" aria-current="page">Performance</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- KPI Cards (Matched with Conversation Logs style) -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="align-self-center">
                                <i class="bi bi-heart-fill text-primary font-large-2 float-left"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3 class="primary"><?php echo round($satisfaction_score, 1); ?>%</h3>
                                <span>Satisfaction Score</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="align-self-center">
                                <i class="bi bi-chat-text-fill text-info font-large-2 float-left"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3 class="info"><?php echo number_format($kpis['total_ratings']); ?></h3>
                                <span>Total Feedback</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="align-self-center">
                                <i class="bi bi-hand-thumbs-up-fill text-success font-large-2 float-left"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3 class="success"><?php echo number_format($kpis['total_likes']); ?></h3>
                                <span>Total Likes</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="align-self-center">
                                <i class="bi bi-hand-thumbs-down-fill text-danger font-large-2 float-left"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3 class="danger"><?php echo number_format($kpis['total_dislikes']); ?></h3>
                                <span>Total Dislikes</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Trends Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Satisfaction Trends (Last 7 Days)</h4>
                </div>
                <div class="card-body">
                    <div style="height: 350px;">
                        <canvas id="performanceTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Distribution & Performance by Persona -->
    <div class="row mb-4">
        <div class="col-12 col-lg-5">
            <div class="card h-100">
                <div class="card-header">
                    <h4>Feedback Distribution</h4>
                </div>
                <div class="card-body">
                    <div style="height: 300px; position: relative;">
                        <canvas id="confidenceDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-7">
            <div class="card h-100">
                <div class="card-header">
                    <h4>Performance by Persona</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Persona</th>
                                    <th>Feedback Vol.</th>
                                    <th>Likes</th>
                                    <th>Satisfaction</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if ($persona_perf_result && mysqli_num_rows($persona_perf_result) > 0):
                                    while ($persona = mysqli_fetch_assoc($persona_perf_result)):
                                ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-light-<?php echo $persona['color_theme'] ?? 'secondary'; ?>">
                                            <?php echo htmlspecialchars($persona['persona_name']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo number_format($persona['total_ratings']); ?></td>
                                    <td><?php echo number_format($persona['total_likes']); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 8px; width: 100px;">
                                                <div class="progress-bar bg-<?php echo $persona['satisfaction_score'] >= 70 ? 'success' : ($persona['satisfaction_score'] >= 40 ? 'warning' : 'danger'); ?>" 
                                                     style="width: <?php echo round($persona['satisfaction_score']); ?>%">
                                                </div>
                                            </div>
                                            <span class="text-sm font-bold"><?php echo round($persona['satisfaction_score']); ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No performance data available</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Rated Products -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Top Rated Recommended Products</h4>
                    <p class="text-subtitle text-muted mb-0">Products with the most positive user feedback</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Total Ratings</th>
                                    <th>Total Likes</th>
                                    <th>Approval Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if ($top_products_result && mysqli_num_rows($top_products_result) > 0):
                                    while ($prod = mysqli_fetch_assoc($top_products_result)):
                                        $approval_rate = ($prod['likes'] / $prod['rating_count']) * 100;
                                ?>
                                <tr>
                                    <td style="font-weight: 600;"><?php echo htmlspecialchars($prod['product_name']); ?></td>
                                    <td><?php echo $prod['rating_count']; ?></td>
                                    <td><?php echo $prod['likes']; ?></td>
                                    <td>
                                        <span class="badge bg-success"><?php echo round($approval_rate, 1); ?>%</span>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <tr>
                                    <td colspan="4" class="text-center p-4">No ratings recorded yet.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Common Chart Options
    Chart.defaults.font.family = "'Nunito', sans-serif";
    Chart.defaults.color = '#6b7280';
    
    // Performance Trends Chart (Line)
    const trendCtx = document.getElementById('performanceTrendsChart').getContext('2d');
    const trendData = {
        labels: <?php echo json_encode($trend_dates); ?>,
        datasets: [{
            label: 'Satisfaction (%)',
            data: <?php echo json_encode($trend_satisfaction); ?>,
            borderColor: '#4f46e5',
            backgroundColor: 'rgba(79, 70, 229, 0.1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#4f46e5',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            yAxisID: 'y'
        }, {
            label: 'Feedback Volume',
            data: <?php echo json_encode($trend_volume); ?>,
            borderColor: '#9ca3af',
            borderWidth: 1,
            borderDash: [5, 5],
            pointRadius: 0,
            fill: false,
            tension: 0.4,
            yAxisID: 'y1'
        }]
    };

    new Chart(trendCtx, {
        type: 'line',
        data: trendData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    min: 0,
                    max: 100,
                    grid: {
                        color: '#f3f4f6',
                        borderDash: [4, 4]
                    },
                    title: {
                        display: true,
                        text: 'Satisfaction Rating'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                    title: {
                        display: true,
                        text: 'Volume'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Feedback Distribution Chart (Doughnut)
    const distCtx = document.getElementById('confidenceDistributionChart').getContext('2d');
    new Chart(distCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($dist_labels); ?>,
            datasets: [{
                data: <?php echo json_encode($dist_values); ?>,
                backgroundColor: <?php echo json_encode($dist_colors); ?>,
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        padding: 20
                    }
                }
            }
        }
    });
</script>

<?php
include 'includes/admin_footer.php';
?>
        </div>
    </div>
</body>
</html>
