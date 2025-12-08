<?php
// ============================================
// Chatbot Analytics - Chatbot Management
// Module D: Smart Laptop Advisor Admin
// ============================================

// Include database connection
require_once 'includes/db_connect.php';

// ============================================
// ============================================
// LOGIC SECTION - Data Fetching
// ============================================

// Set timezone to match database/user location (assuming +08:00 based on user context)
date_default_timezone_set('Asia/Singapore');

// Helper to get last 7 days dates
$dates = [];
for ($i = 6; $i >= 0; $i--) {
    $dates[] = date('Y-m-d', strtotime("-$i days"));
}

// 1. Fetch Today's Analytics (Real-time)
$today_stats_query = "SELECT 
    COUNT(DISTINCT c.conversation_id) as total_conversations,
    COUNT(cm.message_id) as total_messages,
    AVG(cm.response_time_ms) as avg_response_time_ms,
    (SELECT COUNT(*) FROM conversation_messages WHERE intent_detected IS NULL AND message_type = 'user' AND DATE(timestamp) = CURDATE()) as unrecognized_count,
    (SELECT COUNT(*) FROM conversation_messages WHERE intent_detected = 'fallback' AND DATE(timestamp) = CURDATE()) as fallback_count,
    (SELECT AVG(satisfaction_rating) FROM conversations WHERE satisfaction_rating IS NOT NULL AND DATE(started_at) = CURDATE()) as satisfaction_score
FROM conversations c
LEFT JOIN conversation_messages cm ON c.conversation_id = cm.conversation_id AND DATE(cm.timestamp) = CURDATE()
WHERE DATE(c.started_at) = CURDATE()";

$today_result = $conn->query($today_stats_query);
$today_analytics = $today_result->fetch_assoc();

// Calculate Avg Messages Per Session
$today_analytics['avg_messages_per_session'] = $today_analytics['total_conversations'] > 0 
    ? $today_analytics['total_messages'] / $today_analytics['total_conversations'] 
    : 0;

// Calculate Intent Accuracy for Today
$accuracy_query = "SELECT 
    (SUM(CASE WHEN intent_confidence >= 0.7 THEN 1 ELSE 0 END) / COUNT(*)) * 100 as accuracy
FROM conversation_messages 
WHERE message_type = 'user' AND DATE(timestamp) = CURDATE() AND intent_detected IS NOT NULL";
$accuracy_result = $conn->query($accuracy_query);
$accuracy_data = $accuracy_result->fetch_assoc();
$today_analytics['intent_accuracy'] = $accuracy_data['accuracy'] ?? 0;

// Calculate Sentiment Distribution for Today
$sentiment_query = "SELECT 
    sentiment, 
    COUNT(*) as count 
FROM conversations 
WHERE DATE(started_at) = CURDATE() 
GROUP BY sentiment";
$sentiment_result = $conn->query($sentiment_query);
$sentiments = ['positive' => 0, 'neutral' => 0, 'negative' => 0];
$total_sentiments = 0;
while ($row = $sentiment_result->fetch_assoc()) {
    $sent = strtolower($row['sentiment']);
    if (isset($sentiments[$sent])) {
        $sentiments[$sent] = $row['count'];
        $total_sentiments += $row['count'];
    }
}

$today_analytics['positive_sentiment_pct'] = $total_sentiments > 0 ? ($sentiments['positive'] / $total_sentiments) * 100 : 0;
$today_analytics['neutral_sentiment_pct'] = $total_sentiments > 0 ? ($sentiments['neutral'] / $total_sentiments) * 100 : 0;
$today_analytics['negative_sentiment_pct'] = $total_sentiments > 0 ? ($sentiments['negative'] / $total_sentiments) * 100 : 0;

// Resolution Rate (Proxy: Conversations with 'recommendation_made' or 'order_placed' outcome)
$resolution_query = "SELECT 
    (SUM(CASE WHEN outcome IN ('recommendation_made', 'order_placed') THEN 1 ELSE 0 END) / COUNT(*)) * 100 as resolution_rate
FROM conversations 
WHERE DATE(started_at) = CURDATE()";
$resolution_result = $conn->query($resolution_query);
$resolution_data = $resolution_result->fetch_assoc();
$today_analytics['resolution_rate'] = $resolution_data['resolution_rate'] ?? 0;

// Leads Captured (Emails Collected)
$leads_query = "SELECT COUNT(DISTINCT conversation_id) as leads_count 
FROM conversations 
WHERE customer_email IS NOT NULL AND DATE(started_at) = CURDATE()";
$leads_result = $conn->query($leads_query);
$leads_data = $leads_result->fetch_assoc();
$today_analytics['leads_count'] = $leads_data['leads_count'] ?? 0;

// 2. Fetch 7-day trend data
$trend_data = [];
foreach ($dates as $date) {
    // Conversations & Satisfaction
    $day_query = "SELECT 
        COUNT(*) as total_conversations,
        AVG(satisfaction_rating) as satisfaction_score
    FROM conversations
    WHERE DATE(started_at) = '$date'";
    $day_result = $conn->query($day_query);
    $day_data = $day_result->fetch_assoc();
    
    // Intent Accuracy
    $acc_query = "SELECT 
        (SUM(CASE WHEN intent_confidence >= 0.7 THEN 1 ELSE 0 END) / COUNT(*)) * 100 as accuracy
    FROM conversation_messages
    WHERE message_type = 'user' AND DATE(timestamp) = '$date' AND intent_detected IS NOT NULL";
    $acc_result = $conn->query($acc_query);
    $acc_data = $acc_result->fetch_assoc();
    
    $trend_data[] = [
        'date' => $date,
        'total_conversations' => $day_data['total_conversations'] ?? 0,
        'satisfaction_score' => $day_data['satisfaction_score'] ?? 0,
        'intent_accuracy' => $acc_data['accuracy'] ?? 0
    ];
}

// 3. Fetch Top Intents
$top_intents_query = "SELECT 
    intent_detected as intent_name,
    COUNT(*) as usage_count,
    AVG(intent_confidence) * 100 as success_rate
FROM conversation_messages
WHERE message_type = 'user' AND intent_detected IS NOT NULL
GROUP BY intent_detected
ORDER BY usage_count DESC
LIMIT 10";
$top_intents_result = $conn->query($top_intents_query);
$top_intents = [];
while ($row = $top_intents_result->fetch_assoc()) {
    $row['display_name'] = ucwords(str_replace('_', ' ', $row['intent_name']));
    $top_intents[] = $row;
}

// 4. Fetch Recent Unrecognized Queries
$unrecognized_query = "SELECT 
    message_content,
    timestamp,
    COUNT(*) as occurrences
FROM conversation_messages
WHERE message_type = 'user' 
AND intent_detected IS NULL
AND timestamp >= DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY message_content
ORDER BY occurrences DESC
LIMIT 10";
$unrecognized_result = $conn->query($unrecognized_query);
$unrecognized_queries = [];
while ($row = $unrecognized_result->fetch_assoc()) {
    $unrecognized_queries[] = $row;
}

// Prepare chart data
$chart_dates = [];
$chart_conversations = [];
$chart_accuracy = [];
$chart_satisfaction = [];

foreach ($trend_data as $day) {
    $chart_dates[] = date('M d', strtotime($day['date']));
    $chart_conversations[] = $day['total_conversations'];
    $chart_accuracy[] = round($day['intent_accuracy'], 1);
    $chart_satisfaction[] = round($day['satisfaction_score'], 1);
}

// ============================================
// VIEW SECTION - HTML Output
// ============================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Analytics - Smart Laptop Advisor Admin</title>
    
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
                <h3>Chatbot Analytics</h3>
                <p class="text-subtitle text-muted">Monitor and analyze chatbot performance metrics</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item">Chatbot</li>
                        <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon purple">
                                <i class="iconly-boldChat"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Conversations</h6>
                            <h6 class="font-extrabold mb-0"><?php echo number_format($today_analytics['total_conversations'] ?? 0); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                            <h6 class="text-muted font-semibold">Intent Accuracy</h6>
                            <h6 class="font-extrabold mb-0"><?php echo number_format($today_analytics['intent_accuracy'] ?? 0, 1); ?>%</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon green">
                                <i class="iconly-boldTicket"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Resolution Rate</h6>
                            <h6 class="font-extrabold mb-0"><?php echo number_format($today_analytics['resolution_rate'] ?? 0, 1); ?>%</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon red">
                                <i class="iconly-boldStar"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Satisfaction</h6>
                            <h6 class="font-extrabold mb-0"><?php echo number_format($today_analytics['satisfaction_score'] ?? 0, 1); ?>/5.0</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- New Row for Business Leads -->
    <div class="row">
         <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon orange" style="background: #ff9f43;">
                                <i class="bi bi-envelope-check-fill"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Leads Captured</h6>
                            <h6 class="font-extrabold mb-0"><?php echo number_format($today_analytics['leads_count'] ?? 0); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <!-- Conversation Trends Chart -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Conversation Trends (Last 7 Days)</h4>
                </div>
                <div class="card-body">
                    <canvas id="conversationTrendChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Sentiment Distribution -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Sentiment Distribution</h4>
                </div>
                <div class="card-body">
                    <canvas id="sentimentChart"></canvas>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-circle-fill text-success me-2"></i>Positive</span>
                            <strong><?php echo number_format($today_analytics['positive_sentiment_pct'] ?? 0, 1); ?>%</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-circle-fill text-secondary me-2"></i>Neutral</span>
                            <strong><?php echo number_format($today_analytics['neutral_sentiment_pct'] ?? 0, 1); ?>%</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-circle-fill text-danger me-2"></i>Negative</span>
                            <strong><?php echo number_format($today_analytics['negative_sentiment_pct'] ?? 0, 1); ?>%</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Intent Accuracy & Response Time -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Intent Accuracy Over Time</h4>
                </div>
                <div class="card-body">
                    <canvas id="accuracyChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Satisfaction Rating Trend</h4>
                </div>
                <div class="card-body">
                    <canvas id="satisfactionChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performing Intents & Unrecognized Queries -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Top Performing Intents</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Intent</th>
                                    <th>Usage</th>
                                    <th>Success Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($top_intents as $intent): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($intent['display_name']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($intent['intent_name']); ?></small>
                                        </td>
                                        <td><?php echo number_format($intent['usage_count']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $intent['success_rate'] >= 90 ? 'success' : ($intent['success_rate'] >= 70 ? 'warning' : 'danger'); ?>">
                                                <?php echo number_format($intent['success_rate'], 1); ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Unrecognized Queries</h4>
                    <button class="btn btn-sm btn-outline-primary" onclick="exportUnrecognized()">
                        <i class="bi bi-download me-1"></i>Export
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Query</th>
                                    <th>Occurrences</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($unrecognized_queries) > 0): ?>
                                    <?php foreach ($unrecognized_queries as $query): ?>
                                        <tr>
                                            <td>
                                                <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                                                    <?php echo htmlspecialchars($query['message_content']); ?>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-danger"><?php echo $query['occurrences']; ?></span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-success" onclick="createIntent('<?php echo htmlspecialchars($query['message_content'], ENT_QUOTES); ?>')">
                                                    <i class="bi bi-plus-circle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No unrecognized queries found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Performance Summary</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-2">
                            <h6 class="text-muted">Avg Messages/Session</h6>
                            <h4><?php echo number_format($today_analytics['avg_messages_per_session'] ?? 0, 1); ?></h4>
                        </div>
                        <div class="col-md-2">
                            <h6 class="text-muted">Avg Response Time</h6>
                            <h4><?php echo number_format($today_analytics['avg_response_time_ms'] ?? 0); ?>ms</h4>
                        </div>
                        <div class="col-md-2">
                            <h6 class="text-muted">Total Messages</h6>
                            <h4><?php echo number_format($today_analytics['total_messages'] ?? 0); ?></h4>
                        </div>
                        <div class="col-md-2">
                            <h6 class="text-muted">Unrecognized</h6>
                            <h4 class="text-danger"><?php echo number_format($today_analytics['unrecognized_intent_count'] ?? 0); ?></h4>
                        </div>
                        <div class="col-md-2">
                            <h6 class="text-muted">Fallback Count</h6>
                            <h4 class="text-warning"><?php echo number_format($today_analytics['fallback_count'] ?? 0); ?></h4>
                        </div>
                        <div class="col-md-2">
                            <h6 class="text-muted">Intent Accuracy</h6>
                            <h4 class="text-success"><?php echo number_format($today_analytics['intent_accuracy'] ?? 0, 1); ?>%</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}
.stats-icon.purple { background: #7367f0; }
.stats-icon.blue { background: #00cfe8; }
.stats-icon.green { background: #28c76f; }
.stats-icon.red { background: #ea5455; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Conversation Trend Chart
const conversationTrendCtx = document.getElementById('conversationTrendChart').getContext('2d');
new Chart(conversationTrendCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($chart_dates); ?>,
        datasets: [{
            label: 'Conversations',
            data: <?php echo json_encode($chart_conversations); ?>,
            borderColor: '#7367f0',
            backgroundColor: 'rgba(115, 103, 240, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: true }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Sentiment Distribution Pie Chart
const sentimentCtx = document.getElementById('sentimentChart').getContext('2d');
new Chart(sentimentCtx, {
    type: 'doughnut',
    data: {
        labels: ['Positive', 'Neutral', 'Negative'],
        datasets: [{
            data: [
                <?php echo $today_analytics['positive_sentiment_pct'] ?? 0; ?>,
                <?php echo $today_analytics['neutral_sentiment_pct'] ?? 0; ?>,
                <?php echo $today_analytics['negative_sentiment_pct'] ?? 0; ?>
            ],
            backgroundColor: ['#28c76f', '#6c757d', '#ea5455'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false }
        }
    }
});

// Intent Accuracy Chart
const accuracyCtx = document.getElementById('accuracyChart').getContext('2d');
new Chart(accuracyCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($chart_dates); ?>,
        datasets: [{
            label: 'Intent Accuracy (%)',
            data: <?php echo json_encode($chart_accuracy); ?>,
            borderColor: '#00cfe8',
            backgroundColor: 'rgba(0, 207, 232, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: true }
        },
        scales: {
            y: { beginAtZero: true, max: 100 }
        }
    }
});

// Satisfaction Rating Chart
const satisfactionCtx = document.getElementById('satisfactionChart').getContext('2d');
new Chart(satisfactionCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($chart_dates); ?>,
        datasets: [{
            label: 'Satisfaction Score',
            data: <?php echo json_encode($chart_satisfaction); ?>,
            borderColor: '#28c76f',
            backgroundColor: 'rgba(40, 199, 111, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: true }
        },
        scales: {
            y: { beginAtZero: true, max: 5 }
        }
    }
});

function createIntent(query) {
    window.location.href = 'admin_intent_management.php?create=' + encodeURIComponent(query);
}

function exportUnrecognized() {
    window.location.href = 'ajax/export_unrecognized_queries.php';
}
</script>

<?php include 'includes/admin_footer.php'; ?>
        </div>
    </div>
</body>
</html>
