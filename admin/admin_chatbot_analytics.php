<?php
session_start();
// ============================================
// Chatbot Analytics - Enhanced Pro Dashboard
// Smart Laptop Advisor Admin Panel
// ============================================

require_once 'includes/db_connect.php';

date_default_timezone_set('Asia/Singapore');

// ============================================
// LOGIC SECTION - Data Aggregation
// ============================================

// Helper: Get date range
$dates = [];
for ($i = 6; $i >= 0; $i--) {
    $dates[] = date('Y-m-d', strtotime("-$i days"));
}

// ============================================
// 1. TODAY'S OVERVIEW METRICS
// ============================================
$today = date('Y-m-d');

// Total Conversations Today
$result = $conn->query("SELECT COUNT(*) as total FROM conversations WHERE DATE(started_at) = CURDATE()");
$today_conversations = ($result && $row = $result->fetch_assoc()) ? ($row['total'] ?? 0) : 0;

// Total Messages Today
$result = $conn->query("SELECT COUNT(*) as total FROM conversation_messages WHERE DATE(timestamp) = CURDATE()");
$today_messages = ($result && $row = $result->fetch_assoc()) ? ($row['total'] ?? 0) : 0;

// Average Messages Per Session
$avg_messages = $today_conversations > 0 ? round($today_messages / $today_conversations, 1) : 0;

// Active Users Today (unique user_ids)
$result = $conn->query("SELECT COUNT(DISTINCT user_id) as total FROM conversations WHERE DATE(started_at) = CURDATE() AND user_id IS NOT NULL");
$active_users_today = ($result && $row = $result->fetch_assoc()) ? ($row['total'] ?? 0) : 0;

// Average Response Time (ms)
$result = $conn->query("SELECT AVG(response_time_ms) as avg_time FROM conversation_messages WHERE DATE(timestamp) = CURDATE() AND response_time_ms IS NOT NULL AND message_type = 'bot'");
$avg_response_time = ($result && $row = $result->fetch_assoc()) ? round($row['avg_time'] ?? 0) : 0;

// Leads Captured Today (conversations with email)
$result = $conn->query("SELECT COUNT(*) as total FROM conversations WHERE DATE(started_at) = CURDATE() AND customer_email IS NOT NULL");
$leads_today = ($result && $row = $result->fetch_assoc()) ? ($row['total'] ?? 0) : 0;

// Total All-Time Leads
$result = $conn->query("SELECT COUNT(*) as total FROM conversations WHERE customer_email IS NOT NULL");
$total_leads = ($result && $row = $result->fetch_assoc()) ? ($row['total'] ?? 0) : 0;

// ============================================
// 2. SENTIMENT ANALYSIS
// ============================================
$sentiments = ['positive' => 0, 'neutral' => 0, 'negative' => 0];
$total_sentiment_count = 0;
$result = $conn->query("SELECT sentiment, COUNT(*) as count FROM conversations WHERE DATE(started_at) = CURDATE() GROUP BY sentiment");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $sent = strtolower($row['sentiment'] ?? 'neutral');
        if (isset($sentiments[$sent])) {
            $sentiments[$sent] = $row['count'];
            $total_sentiment_count += $row['count'];
        }
    }
}

$positive_pct = $total_sentiment_count > 0 ? round(($sentiments['positive'] / $total_sentiment_count) * 100, 1) : 0;
$neutral_pct = $total_sentiment_count > 0 ? round(($sentiments['neutral'] / $total_sentiment_count) * 100, 1) : 0;
$negative_pct = $total_sentiment_count > 0 ? round(($sentiments['negative'] / $total_sentiment_count) * 100, 1) : 0;

// ============================================
// 3. CONVERSATION OUTCOMES
// ============================================
$outcomes = [];
$total_outcomes = 0;
$result = $conn->query("
    SELECT outcome, COUNT(*) as count 
    FROM conversations 
    WHERE outcome IS NOT NULL AND DATE(started_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY outcome 
    ORDER BY count DESC
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $outcomes[$row['outcome']] = $row['count'];
        $total_outcomes += $row['count'];
    }
}

// Resolution Rate (recommendation_made + order_placed)
$resolved = ($outcomes['recommendation_made'] ?? 0) + ($outcomes['order_placed'] ?? 0) + ($outcomes['product_recommendation'] ?? 0);
$resolution_rate = $total_outcomes > 0 ? round(($resolved / $total_outcomes) * 100, 1) : 0;

// ============================================
// 4. TOP INTENTS USED
// ============================================
$top_intents = [];
$max_intent_usage = 1;
$result = $conn->query("
    SELECT i.intent_id, i.intent_name, i.display_name, i.usage_count, i.success_count,
           (SELECT COUNT(*) FROM training_phrases tp WHERE tp.intent_id = i.intent_id) as phrase_count
    FROM intents i 
    WHERE i.is_active = 1 
    ORDER BY i.usage_count DESC 
    LIMIT 8
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $row['success_rate'] = $row['usage_count'] > 0 ? round(($row['success_count'] / $row['usage_count']) * 100, 1) : 0;
        $top_intents[] = $row;
        if ($row['usage_count'] > $max_intent_usage) {
            $max_intent_usage = $row['usage_count'];
        }
    }
}

// ============================================
// 5. HOURLY ACTIVITY (Last 24 hours)
// ============================================
$hourly_data = array_fill(0, 24, 0);
$result = $conn->query("
    SELECT HOUR(timestamp) as hour, COUNT(*) as count 
    FROM conversation_messages 
    WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    GROUP BY HOUR(timestamp)
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $hourly_data[$row['hour']] = $row['count'];
    }
}

// ============================================
// 6. 7-DAY TRENDS
// ============================================
$trend_data = [];
foreach ($dates as $date) {
    $stmt = $conn->prepare("
        SELECT 
            COUNT(DISTINCT c.conversation_id) as conversations,
            COUNT(cm.message_id) as messages,
            AVG(c.satisfaction_rating) as satisfaction
        FROM conversations c
        LEFT JOIN conversation_messages cm ON c.conversation_id = cm.conversation_id AND DATE(cm.timestamp) = ?
        WHERE DATE(c.started_at) = ?
    ");
    $stmt->bind_param("ss", $date, $date);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    // Get leads for this day
    $stmt2 = $conn->prepare("SELECT COUNT(*) as leads FROM conversations WHERE DATE(started_at) = ? AND customer_email IS NOT NULL");
    $stmt2->bind_param("s", $date);
    $stmt2->execute();
    $leads = $stmt2->get_result()->fetch_assoc()['leads'] ?? 0;
    
    $trend_data[] = [
        'date' => $date,
        'conversations' => $result['conversations'] ?? 0,
        'messages' => $result['messages'] ?? 0,
        'satisfaction' => round($result['satisfaction'] ?? 0, 1),
        'leads' => $leads
    ];
}

// ============================================
// 7. POPULAR PRODUCTS (Top viewed/sold)
// ============================================
// Get popular products based on order_items (most purchased)
$popular_products = [];
$pop_stmt = $conn->query("
    SELECT p.product_id, p.product_name, p.brand, p.price, p.image_url,
           COALESCE(oi.order_count, 0) as mention_count
    FROM products p
    LEFT JOIN (
        SELECT product_id, COUNT(*) as order_count 
        FROM order_items 
        WHERE order_id IN (SELECT order_id FROM orders WHERE order_date >= DATE_SUB(NOW(), INTERVAL 30 DAY))
        GROUP BY product_id
    ) oi ON p.product_id = oi.product_id
    WHERE p.is_active = 1
    ORDER BY mention_count DESC, p.product_name ASC
    LIMIT 6
");
if ($pop_stmt) {
    while ($row = $pop_stmt->fetch_assoc()) {
        $popular_products[] = $row;
    }
}

// ============================================
// 8. UNRECOGNIZED QUERIES
// ============================================
$unrecognized_queries = [];
$result = $conn->query("
    SELECT cm.message_content, COUNT(*) as occurrences, MAX(cm.timestamp) as last_seen
    FROM conversation_messages cm
    WHERE cm.message_type = 'user' 
    AND cm.intent_detected IS NULL
    AND cm.timestamp >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    AND LENGTH(cm.message_content) > 2
    GROUP BY cm.message_content 
    ORDER BY occurrences DESC 
    LIMIT 8
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $unrecognized_queries[] = $row;
    }
}

// ============================================
// 9. RECENT CONVERSATIONS SUMMARY
// ============================================
$recent_conversations = [];
$result = $conn->query("
    SELECT c.conversation_id, c.session_id, c.user_id, c.sentiment, c.outcome,
           c.message_count, c.started_at, c.duration_seconds,
           u.full_name as user_name,
           (SELECT message_content FROM conversation_messages WHERE conversation_id = c.conversation_id ORDER BY timestamp ASC LIMIT 1) as first_message
    FROM conversations c
    LEFT JOIN users u ON c.user_id = u.user_id
    ORDER BY c.started_at DESC
    LIMIT 5
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recent_conversations[] = $row;
    }
}

// ============================================
// 10. SATISFACTION RATINGS
// ============================================
$satisfaction_distribution = [];
$result = $conn->query("
    SELECT satisfaction_rating, COUNT(*) as count
    FROM conversations 
    WHERE satisfaction_rating IS NOT NULL
    GROUP BY satisfaction_rating
    ORDER BY satisfaction_rating DESC
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $satisfaction_distribution[$row['satisfaction_rating']] = $row['count'];
    }
}

$avg_satisfaction = 0;
$result = $conn->query("SELECT AVG(satisfaction_rating) as avg_rating FROM conversations WHERE satisfaction_rating IS NOT NULL");
if ($result && $row = $result->fetch_assoc()) {
    $avg_satisfaction = round($row['avg_rating'] ?? 0, 1);
}

// ============================================
// PREPARE CHART DATA
// ============================================
$chart_dates = [];
$chart_conversations = [];
$chart_messages = [];
$chart_leads = [];

foreach ($trend_data as $day) {
    $chart_dates[] = date('M d', strtotime($day['date']));
    $chart_conversations[] = $day['conversations'];
    $chart_messages[] = $day['messages'];
    $chart_leads[] = $day['leads'];
}

$hourly_labels = [];
for ($i = 0; $i < 24; $i++) {
    $hourly_labels[] = sprintf('%02d:00', $i);
}

// Outcome labels for pie chart
$outcome_labels = array_keys($outcomes);
$outcome_values = array_values($outcomes);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Analytics - Smart Laptop Advisor</title>
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="source/assets/css/bootstrap.css">
    <link rel="stylesheet" href="source/assets/vendors/iconly/bold.css">
    <link rel="stylesheet" href="source/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="source/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="source/assets/css/app.css">
    <link rel="shortcut icon" href="source/assets/images/favicon.svg" type="image/x-icon">
    
    <style>
    :root {
        --accent-primary: #6366f1;
        --accent-secondary: #8b5cf6;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #06b6d4;
        --dark: #0f172a;
        --light: #f8fafc;
        --gradient-primary: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        --gradient-danger: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --gradient-info: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    }
    
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #f1f5f9;
    }
    
    /* Page Header */
    .analytics-header {
        background: var(--gradient-primary);
        color: white;
        padding: 32px;
        border-radius: 24px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }
    
    .analytics-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -15%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .analytics-header::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: 15%;
        width: 250px;
        height: 250px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }
    
    .analytics-header h3 {
        font-size: 1.875rem;
        font-weight: 800;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }
    
    .analytics-header p {
        opacity: 0.9;
        margin-bottom: 0;
        position: relative;
        z-index: 1;
        font-weight: 500;
    }
    
    /* Metric Cards */
    .metric-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.04);
    }
    
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(99, 102, 241, 0.15);
    }
    
    .metric-card .metric-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-bottom: 16px;
    }
    
    .metric-card .metric-value {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--dark);
        line-height: 1;
        margin-bottom: 6px;
    }
    
    .metric-card .metric-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 600;
    }
    
    .metric-card .metric-trend {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 5px 12px;
        border-radius: 24px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .metric-trend.up { background: rgba(16, 185, 129, 0.1); color: var(--success); }
    .metric-trend.down { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
    .metric-trend.neutral { background: rgba(99, 102, 241, 0.1); color: var(--accent-primary); }
    
    /* Chart Cards */
    .chart-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.04);
    }
    
    .chart-card .card-header {
        background: transparent;
        border-bottom: 1px solid #e2e8f0;
        padding: 20px 24px;
    }
    
    .chart-card .card-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .chart-card .card-title i {
        color: var(--accent-primary);
    }
    
    .chart-card .card-body {
        padding: 24px;
    }
    
    /* Intent List */
    .intent-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .intent-item:last-child {
        border-bottom: none;
    }
    
    .intent-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        flex-shrink: 0;
    }
    
    .intent-info {
        flex: 1;
        min-width: 0;
    }
    
    .intent-name {
        font-weight: 700;
        color: var(--dark);
        font-size: 0.9rem;
        margin-bottom: 6px;
    }
    
    .intent-progress {
        height: 6px;
        border-radius: 3px;
        background: #e2e8f0;
        overflow: hidden;
    }
    
    .intent-progress-bar {
        height: 100%;
        border-radius: 3px;
        transition: width 0.6s ease;
    }
    
    .intent-stats {
        display: flex;
        gap: 12px;
        align-items: center;
    }
    
    .intent-usage {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
    }
    
    .intent-rate {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    /* Query Items */
    .query-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        background: #f8fafc;
        border-radius: 14px;
        margin-bottom: 10px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    
    .query-item:hover {
        background: white;
        border-color: var(--accent-primary);
        box-shadow: 0 4px 16px rgba(99, 102, 241, 0.1);
    }
    
    .query-text {
        flex: 1;
        font-size: 0.9rem;
        color: var(--dark);
        font-weight: 500;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .query-count {
        padding: 4px 12px;
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
    }
    
    .query-action {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        background: white;
        color: var(--success);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .query-action:hover {
        background: var(--success);
        border-color: var(--success);
        color: white;
    }
    
    /* Product Mention Cards */
    .product-mention {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px;
        background: #f8fafc;
        border-radius: 14px;
        margin-bottom: 10px;
    }
    
    .product-mention-img {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        object-fit: cover;
        background: white;
    }
    
    .product-mention-info {
        flex: 1;
    }
    
    .product-mention-name {
        font-weight: 700;
        color: var(--dark);
        font-size: 0.875rem;
        margin-bottom: 2px;
    }
    
    .product-mention-brand {
        font-size: 0.75rem;
        color: #64748b;
    }
    
    .product-mention-count {
        padding: 4px 12px;
        background: var(--gradient-primary);
        color: white;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
    }
    
    /* Conversation Row */
    .conversation-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px;
        background: #f8fafc;
        border-radius: 14px;
        margin-bottom: 10px;
    }
    
    .conversation-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: var(--gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
    }
    
    .conversation-info {
        flex: 1;
    }
    
    .conversation-message {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.875rem;
        margin-bottom: 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 300px;
    }
    
    .conversation-meta {
        font-size: 0.75rem;
        color: #64748b;
    }
    
    .sentiment-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .sentiment-badge.positive { background: rgba(16, 185, 129, 0.1); color: var(--success); }
    .sentiment-badge.neutral { background: rgba(100, 116, 139, 0.1); color: #64748b; }
    .sentiment-badge.negative { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
    
    /* Sentiment Donut Stats */
    .sentiment-stats {
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-top: 20px;
    }
    
    .sentiment-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .sentiment-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
    }
    
    .sentiment-label {
        flex: 1;
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 500;
    }
    
    .sentiment-value {
        font-weight: 700;
        color: var(--dark);
    }
    
    /* Outcome Badge */
    .outcome-badge {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    /* Live Status Indicator */
    .live-indicator {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: rgba(16, 185, 129, 0.1);
        border-radius: 24px;
        color: var(--success);
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .live-dot {
        width: 8px;
        height: 8px;
        background: var(--success);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.1); }
    }
    
    /* Hourly Heatmap */
    .hourly-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 6px;
    }
    
    .hourly-cell {
        aspect-ratio: 1;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        color: white;
        transition: transform 0.2s;
    }
    
    .hourly-cell:hover {
        transform: scale(1.1);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .metric-card .metric-value {
            font-size: 1.75rem;
        }
        
        .hourly-grid {
            grid-template-columns: repeat(6, 1fr);
        }
    }
    </style>
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

            <!-- Analytics Header -->
            <div class="analytics-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3><i class="bi bi-robot me-2"></i>AI Chatbot Analytics</h3>
                        <p>Real-time performance metrics and conversation insights</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <span class="live-indicator">
                            <span class="live-dot"></span>
                            Live Dashboard
                        </span>
                        <button class="btn btn-light btn-sm ms-2" onclick="window.location.reload()">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Key Metrics Row -->
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="metric-card">
                        <div class="metric-icon" style="background: var(--gradient-primary);">
                            <i class="bi bi-chat-dots-fill"></i>
                        </div>
                        <div class="metric-value"><?php echo number_format($today_conversations); ?></div>
                        <div class="metric-label">Conversations Today</div>
                        <span class="metric-trend neutral"><i class="bi bi-activity"></i> Active</span>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="metric-card">
                        <div class="metric-icon" style="background: var(--gradient-info);">
                            <i class="bi bi-chat-text-fill"></i>
                        </div>
                        <div class="metric-value"><?php echo number_format($today_messages); ?></div>
                        <div class="metric-label">Messages Today</div>
                        <span class="metric-trend neutral"><?php echo $avg_messages; ?> avg/session</span>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="metric-card">
                        <div class="metric-icon" style="background: var(--gradient-success);">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="metric-value"><?php echo $resolution_rate; ?>%</div>
                        <div class="metric-label">Resolution Rate</div>
                        <span class="metric-trend <?php echo $resolution_rate >= 70 ? 'up' : 'down'; ?>">
                            <i class="bi bi-<?php echo $resolution_rate >= 70 ? 'arrow-up' : 'arrow-down'; ?>"></i> 7 Days
                        </span>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="metric-card">
                        <div class="metric-icon" style="background: var(--gradient-warning);">
                            <i class="bi bi-envelope-check-fill"></i>
                        </div>
                        <div class="metric-value"><?php echo number_format($leads_today); ?></div>
                        <div class="metric-label">Leads Captured Today</div>
                        <span class="metric-trend up"><?php echo $total_leads; ?> Total</span>
                    </div>
                </div>
            </div>

            <!-- Secondary Metrics -->
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="metric-card">
                        <div class="metric-icon" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="metric-value"><?php echo number_format($active_users_today); ?></div>
                        <div class="metric-label">Active Users Today</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="metric-card">
                        <div class="metric-icon" style="background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);">
                            <i class="bi bi-lightning-fill"></i>
                        </div>
                        <div class="metric-value"><?php echo number_format($avg_response_time); ?><small style="font-size: 0.5em;">ms</small></div>
                        <div class="metric-label">Avg Response Time</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="metric-card">
                        <div class="metric-icon" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div class="metric-value"><?php echo $avg_satisfaction; ?><small style="font-size: 0.5em;">/5</small></div>
                        <div class="metric-label">Avg Satisfaction</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="metric-card">
                        <div class="metric-icon" style="background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);">
                            <i class="bi bi-emoji-smile-fill"></i>
                        </div>
                        <div class="metric-value"><?php echo $positive_pct; ?>%</div>
                        <div class="metric-label">Positive Sentiment</div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="chart-card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title"><i class="bi bi-graph-up"></i> 7-Day Conversation Trends</h5>
                            <small class="text-muted">Messages & Conversations</small>
                        </div>
                        <div class="card-body">
                            <canvas id="trendChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-card h-100">
                        <div class="card-header">
                            <h5 class="card-title"><i class="bi bi-emoji-smile"></i> Sentiment Distribution</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="sentimentChart" height="150"></canvas>
                            <div class="sentiment-stats">
                                <div class="sentiment-item">
                                    <div class="sentiment-dot" style="background: var(--success);"></div>
                                    <span class="sentiment-label">Positive</span>
                                    <span class="sentiment-value"><?php echo $positive_pct; ?>%</span>
                                </div>
                                <div class="sentiment-item">
                                    <div class="sentiment-dot" style="background: #64748b;"></div>
                                    <span class="sentiment-label">Neutral</span>
                                    <span class="sentiment-value"><?php echo $neutral_pct; ?>%</span>
                                </div>
                                <div class="sentiment-item">
                                    <div class="sentiment-dot" style="background: var(--danger);"></div>
                                    <span class="sentiment-label">Negative</span>
                                    <span class="sentiment-value"><?php echo $negative_pct; ?>%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hourly Activity & Outcomes -->
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="chart-card h-100">
                        <div class="card-header">
                            <h5 class="card-title"><i class="bi bi-clock-history"></i> Hourly Activity (Last 24h)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="hourlyChart" height="80"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-card h-100">
                        <div class="card-header">
                            <h5 class="card-title"><i class="bi bi-pie-chart"></i> Conversation Outcomes</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="outcomeChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Intents & Unrecognized Queries -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="chart-card h-100">
                        <div class="card-header">
                            <h5 class="card-title"><i class="bi bi-lightning-charge"></i> Top Performing Intents</h5>
                        </div>
                        <div class="card-body">
                            <?php 
                            $intent_colors = ['#6366f1', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#06b6d4', '#ec4899', '#14b8a6'];
                            $i = 0;
                            foreach ($top_intents as $intent): 
                                $color = $intent_colors[$i % count($intent_colors)];
                                $progress = ($intent['usage_count'] / max($max_intent_usage, 1)) * 100;
                                $rate_class = $intent['success_rate'] >= 80 ? 'bg-success text-white' : ($intent['success_rate'] >= 60 ? 'bg-warning' : 'bg-danger text-white');
                            ?>
                            <div class="intent-item">
                                <div class="intent-icon" style="background: <?php echo $color; ?>;">
                                    <i class="bi bi-lightning"></i>
                                </div>
                                <div class="intent-info">
                                    <div class="intent-name"><?php echo htmlspecialchars($intent['display_name']); ?></div>
                                    <div class="intent-progress">
                                        <div class="intent-progress-bar" style="width: <?php echo $progress; ?>%; background: <?php echo $color; ?>;"></div>
                                    </div>
                                </div>
                                <div class="intent-stats">
                                    <span class="intent-usage"><?php echo number_format($intent['usage_count']); ?> uses</span>
                                    <span class="intent-rate <?php echo $rate_class; ?>"><?php echo $intent['success_rate']; ?>%</span>
                                </div>
                            </div>
                            <?php $i++; endforeach; ?>
                            <?php if (empty($top_intents)): ?>
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mt-2 mb-0">No intent data available yet</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title"><i class="bi bi-question-circle"></i> Unrecognized Queries</h5>
                            <button class="btn btn-sm btn-outline-primary" onclick="exportUnrecognized()">
                                <i class="bi bi-download me-1"></i>Export
                            </button>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($unrecognized_queries)): ?>
                                <?php foreach ($unrecognized_queries as $query): ?>
                                <div class="query-item">
                                    <div class="query-text" title="<?php echo htmlspecialchars($query['message_content']); ?>">
                                        <?php echo htmlspecialchars(substr($query['message_content'], 0, 50)) . (strlen($query['message_content']) > 50 ? '...' : ''); ?>
                                    </div>
                                    <span class="query-count"><?php echo $query['occurrences']; ?>x</span>
                                    <button class="query-action" onclick="trainQuery('<?php echo htmlspecialchars(addslashes($query['message_content'])); ?>')" title="Add to Training">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-check-circle fs-1 text-success"></i>
                                    <p class="mt-2 mb-0">All queries recognized!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Products & Recent Conversations -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="chart-card h-100">
                        <div class="card-header">
                            <h5 class="card-title"><i class="bi bi-laptop"></i> Popular Products Mentioned</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($popular_products)): ?>
                                <?php foreach ($popular_products as $product): ?>
                                <?php 
                                    // Handle different image_url formats
                                    $img_url = $product['image_url'] ?? '';
                                    if (empty($img_url)) {
                                        $img_path = '../LaptopAdvisor/images/laptop1.png';
                                    } elseif (strpos($img_url, 'LaptopAdvisor/') === 0) {
                                        // New format: LaptopAdvisor/images/product_xxx.webp
                                        $img_path = '../' . $img_url;
                                    } else {
                                        // Old format: images/laptop1.png
                                        $img_path = '../LaptopAdvisor/' . $img_url;
                                    }
                                ?>
                                <div class="product-mention">
                                    <img src="<?php echo htmlspecialchars($img_path); ?>" 
                                         alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                                         class="product-mention-img"
                                         onerror="this.src='../LaptopAdvisor/images/laptop1.png'">
                                    <div class="product-mention-info">
                                        <div class="product-mention-name"><?php echo htmlspecialchars($product['product_name']); ?></div>
                                        <div class="product-mention-brand"><?php echo htmlspecialchars($product['brand']); ?> • $<?php echo number_format($product['price'], 2); ?></div>
                                    </div>
                                    <span class="product-mention-count"><?php echo $product['mention_count']; ?> mentions</span>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-box fs-1"></i>
                                    <p class="mt-2 mb-0">No product mentions detected yet</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-card h-100">
                        <div class="card-header">
                            <h5 class="card-title"><i class="bi bi-chat-left-text"></i> Recent Conversations</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recent_conversations)): ?>
                                <?php foreach ($recent_conversations as $conv): ?>
                                <div class="conversation-row">
                                    <div class="conversation-avatar">
                                        <?php echo $conv['user_name'] ? strtoupper(substr($conv['user_name'], 0, 1)) : '<i class="bi bi-person"></i>'; ?>
                                    </div>
                                    <div class="conversation-info">
                                        <div class="conversation-message"><?php echo htmlspecialchars($conv['first_message'] ?? 'New conversation'); ?></div>
                                        <div class="conversation-meta">
                                            <?php echo $conv['user_name'] ?? 'Guest'; ?> • 
                                            <?php echo date('M d, H:i', strtotime($conv['started_at'])); ?> • 
                                            <?php echo $conv['message_count']; ?> messages
                                        </div>
                                    </div>
                                    <span class="sentiment-badge <?php echo $conv['sentiment'] ?? 'neutral'; ?>">
                                        <?php echo ucfirst($conv['sentiment'] ?? 'neutral'); ?>
                                    </span>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-chat fs-1"></i>
                                    <p class="mt-2 mb-0">No recent conversations</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leads Trend Chart -->
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="chart-card">
                        <div class="card-header">
                            <h5 class="card-title"><i class="bi bi-envelope-plus"></i> Leads Captured (7 Days)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="leadsChart" height="60"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include 'includes/admin_footer.php'; ?>

    <!-- Training Modal -->
    <div class="modal fade" id="trainQueryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 20px; overflow: hidden; border: none;">
                <div class="modal-header border-0" style="background: var(--gradient-primary); padding: 24px 30px;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-mortarboard-fill text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title text-white mb-0 fw-bold">Train AI Assistant</h5>
                            <small class="text-white-50">Add this query to improve responses</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="mb-4" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 16px; padding: 20px; border-left: 4px solid var(--accent-primary);">
                        <label class="text-muted small fw-semibold text-uppercase mb-1">Unrecognized Query</label>
                        <p id="trainQueryText" class="mb-0 fs-5 fw-semibold" style="color: var(--dark);"></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Intent</label>
                        <select id="intentSelect" class="form-select form-select-lg" style="border-radius: 12px; border: 2px solid #e2e8f0; padding: 12px 16px;">
                            <option value="">-- Select an Intent --</option>
                            <?php foreach ($top_intents as $intent): ?>
                            <option value="<?php echo $intent['intent_id']; ?>"><?php echo htmlspecialchars($intent['display_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Cancel</button>
                    <button type="button" class="btn btn-primary px-4 d-flex align-items-center gap-2" onclick="submitTraining()" style="border-radius: 10px; background: var(--gradient-primary); border: none;">
                        <i class="bi bi-check2-circle"></i>
                        <span>Train AI</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="successToast" class="toast align-items-center border-0" role="alert" style="background: var(--gradient-success); border-radius: 14px;">
            <div class="d-flex">
                <div class="toast-body text-white d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <span id="toastMessage">Action completed successfully!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <script>
    // Chart.js Global Defaults
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.font.size = 12;
    
    // 7-Day Trend Chart
    new Chart(document.getElementById('trendChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chart_dates); ?>,
            datasets: [{
                label: 'Conversations',
                data: <?php echo json_encode($chart_conversations); ?>,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#6366f1'
            }, {
                label: 'Messages',
                data: <?php echo json_encode($chart_messages); ?>,
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.05)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#8b5cf6'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { 
                    position: 'top',
                    labels: { usePointStyle: true, padding: 20 }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: '#e2e8f0' }
                },
                x: { grid: { display: false } }
            }
        }
    });
    
    // Sentiment Chart
    new Chart(document.getElementById('sentimentChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Positive', 'Neutral', 'Negative'],
            datasets: [{
                data: [<?php echo $positive_pct; ?>, <?php echo $neutral_pct; ?>, <?php echo $negative_pct; ?>],
                backgroundColor: ['#10b981', '#64748b', '#ef4444'],
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } }
        }
    });
    
    // Hourly Activity Chart
    new Chart(document.getElementById('hourlyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($hourly_labels); ?>,
            datasets: [{
                label: 'Messages',
                data: <?php echo json_encode(array_values($hourly_data)); ?>,
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                borderColor: '#6366f1',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#e2e8f0' } },
                x: { grid: { display: false } }
            }
        }
    });
    
    // Outcome Chart
    new Chart(document.getElementById('outcomeChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_map(function($o) { return ucwords(str_replace('_', ' ', $o)); }, $outcome_labels)); ?>,
            datasets: [{
                data: <?php echo json_encode($outcome_values); ?>,
                backgroundColor: ['#6366f1', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#06b6d4'],
                borderWidth: 0,
                cutout: '60%'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, padding: 12, font: { size: 11 } }
                }
            }
        }
    });
    
    // Leads Chart
    new Chart(document.getElementById('leadsChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($chart_dates); ?>,
            datasets: [{
                label: 'Leads Captured',
                data: <?php echo json_encode($chart_leads); ?>,
                backgroundColor: 'rgba(245, 158, 11, 0.85)',
                borderColor: '#f59e0b',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#e2e8f0' } },
                x: { grid: { display: false } }
            }
        }
    });
    
    // Training Functions
    let currentTrainingQuery = '';
    const trainModal = new bootstrap.Modal(document.getElementById('trainQueryModal'));
    const successToast = new bootstrap.Toast(document.getElementById('successToast'));
    
    function trainQuery(query) {
        currentTrainingQuery = query;
        document.getElementById('trainQueryText').textContent = query;
        document.getElementById('intentSelect').value = '';
        trainModal.show();
    }
    
    async function submitTraining() {
        const intentId = document.getElementById('intentSelect').value;
        if (!intentId) {
            alert('Please select an intent');
            return;
        }
        
        try {
            const response = await fetch('ajax/train_query.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=add_phrase&intent_id=${intentId}&phrase=${encodeURIComponent(currentTrainingQuery)}`
            });
            
            const data = await response.json();
            if (data.success) {
                trainModal.hide();
                document.getElementById('toastMessage').textContent = 'Training phrase added successfully!';
                successToast.show();
                setTimeout(() => location.reload(), 1500);
            } else {
                alert(data.error || 'Training failed');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Network error. Please try again.');
        }
    }
    
    function exportUnrecognized() {
        window.location.href = 'ajax/export_unrecognized_queries.php';
    }
    </script>
</body>
</html>
