<?php
/**
 * System Logs Page
 * Additional Tools Module
 * Monitor system activities, errors, and security events
 */

session_start();
require_once 'includes/db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// --- Helper Function for User Agent Parsing (Simple) ---
function getBrowserOS($userAgent) {
    if (empty($userAgent)) {
        return "System / API";
    }

    $browser = "Unknown";
    $os = "Unknown";

    // Simple detection
    if (preg_match('/windows/i', $userAgent)) $os = 'Windows';
    elseif (preg_match('/macintosh|mac os x/i', $userAgent)) $os = 'Mac';
    elseif (preg_match('/linux/i', $userAgent)) $os = 'Linux';
    elseif (preg_match('/android/i', $userAgent)) $os = 'Android';
    elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) $os = 'iOS';

    if (preg_match('/chrome/i', $userAgent)) $browser = 'Chrome';
    elseif (preg_match('/firefox/i', $userAgent)) $browser = 'Firefox';
    elseif (preg_match('/safari/i', $userAgent)) $browser = 'Safari';
    elseif (preg_match('/edge/i', $userAgent)) $browser = 'Edge';
    elseif (preg_match('/msie|trident/i', $userAgent)) $browser = 'IE';

    return "$os / $browser";
}

function formatIP($ip) {
    if ($ip === '::1' || $ip === '127.0.0.1') {
        return 'Localhost';
    }
    return $ip;
}

// --- Filter Parameters ---
$action = $_GET['action'] ?? '';
$module = $_GET['module'] ?? '';
$dateFrom = $_GET['dateFrom'] ?? '';
$dateTo = $_GET['dateTo'] ?? '';
$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// --- Build Query ---
$whereClauses = ["1=1"];
$params = [];
$types = "";

if (!empty($action)) {
    $whereClauses[] = "al.action = ?";
    $params[] = $action;
    $types .= "s";
}
if (!empty($module)) {
    $whereClauses[] = "al.module = ?";
    $params[] = $module;
    $types .= "s";
}
if (!empty($dateFrom)) {
    $whereClauses[] = "DATE(al.created_at) >= ?";
    $params[] = $dateFrom;
    $types .= "s";
}
if (!empty($dateTo)) {
    $whereClauses[] = "DATE(al.created_at) <= ?";
    $params[] = $dateTo;
    $types .= "s";
}
if (!empty($search)) {
    $whereClauses[] = "(al.description LIKE ? OR al.ip_address LIKE ? OR CONCAT(au.first_name, ' ', au.last_name) LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "sss";
}

$whereSql = implode(" AND ", $whereClauses);

// --- Export Logic ---
if (isset($_GET['export']) && $_GET['export'] == 'true') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="system_logs_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Log ID', 'Timestamp', 'Action', 'Module', 'User', 'Description', 'IP Address', 'User Agent']);

    $sql = "SELECT al.*, CONCAT(au.first_name, ' ', au.last_name) as admin_name
            FROM admin_activity_log al
            LEFT JOIN admin_users au ON al.admin_id = au.admin_id
            WHERE $whereSql
            ORDER BY al.created_at DESC";
            
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['log_id'],
            $row['created_at'],
            $row['action'],
            $row['module'],
            $row['admin_name'] ?? 'System',
            $row['description'],
            $row['ip_address'],
            $row['user_agent']
        ]);
    }
    fclose($output);
    exit();
}

// --- Pagination Count ---
$countSql = "SELECT COUNT(*) as total 
             FROM admin_activity_log al 
             LEFT JOIN admin_users au ON al.admin_id = au.admin_id 
             WHERE $whereSql";
$stmt = $conn->prepare($countSql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$totalLogs = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalLogs / $limit);

// --- Fetch Logs ---
$sql = "SELECT al.*, CONCAT(au.first_name, ' ', au.last_name) as admin_name
        FROM admin_activity_log al
        LEFT JOIN admin_users au ON al.admin_id = au.admin_id
        WHERE $whereSql
        ORDER BY al.created_at DESC
        LIMIT ? OFFSET ?";
        
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$logs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$page_title = "System Logs";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Smart Laptop Advisor Admin</title>
    
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
                            <h3>System Activity Logs</h3>
                            <p class="text-subtitle text-muted">Monitor system activities, errors, and security events</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">System Logs</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                
                <!-- Log Filters -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Log Filters & Search</h4>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="" id="filterForm">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="actionFilter">Action Type</label>
                                                <select class="form-select" id="actionFilter" name="action">
                                                    <option value="">All Actions</option>
                                                    <option value="login" <?= $action == 'login' ? 'selected' : '' ?>>Login</option>
                                                    <option value="logout" <?= $action == 'logout' ? 'selected' : '' ?>>Logout</option>
                                                    <option value="create" <?= $action == 'create' ? 'selected' : '' ?>>Create</option>
                                                    <option value="update" <?= $action == 'update' ? 'selected' : '' ?>>Update</option>
                                                    <option value="delete" <?= $action == 'delete' ? 'selected' : '' ?>>Delete</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="moduleFilter">Module</label>
                                                <select class="form-select" id="moduleFilter" name="module">
                                                    <option value="">All Modules</option>
                                                    <option value="auth" <?= $module == 'auth' ? 'selected' : '' ?>>Auth</option>
                                                    <option value="products" <?= $module == 'products' ? 'selected' : '' ?>>Products</option>
                                                    <option value="orders" <?= $module == 'orders' ? 'selected' : '' ?>>Orders</option>
                                                    <option value="users" <?= $module == 'users' ? 'selected' : '' ?>>Users</option>
                                                    <option value="system" <?= $module == 'system' ? 'selected' : '' ?>>System</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="dateFrom">From Date</label>
                                                <input type="date" class="form-control" id="dateFrom" name="dateFrom" value="<?= htmlspecialchars($dateFrom) ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="dateTo">To Date</label>
                                                <input type="date" class="form-control" id="dateTo" name="dateTo" value="<?= htmlspecialchars($dateTo) ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="logSearch">Search</label>
                                                <input type="text" class="form-control" id="logSearch" name="search" 
                                                       placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <i class="bi bi-search"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-success w-100" onclick="exportLogs()">
                                                        <i class="bi bi-download"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Log Statistics -->
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon blue">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Total Logs</h6>
                                        <h6 class="font-extrabold mb-0"><?= $totalLogs ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Log Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>System Log History</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Timestamp</th>
                                                <th>Action</th>
                                                <th>Module</th>
                                                <th>User</th>
                                                <th>Description</th>
                                                <th>IP / Device</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($logs as $log): ?>
                                                <tr>
                                                    <td>#<?= $log['log_id'] ?></td>
                                                    <td><?= date('Y-m-d H:i:s', strtotime($log['created_at'])) ?></td>
                                                    <td>
                                                        <?php
                                                        $badgeClass = 'bg-secondary';
                                                        switch($log['action']) {
                                                            case 'login': $badgeClass = 'bg-success'; break;
                                                            case 'logout': $badgeClass = 'bg-warning'; break;
                                                            case 'create': $badgeClass = 'bg-primary'; break;
                                                            case 'update': $badgeClass = 'bg-info'; break;
                                                            case 'delete': $badgeClass = 'bg-danger'; break;
                                                        }
                                                        ?>
                                                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars(ucfirst($log['action'])) ?></span>
                                                    </td>
                                                    <td><span class="badge bg-light-secondary text-dark"><?= htmlspecialchars(ucfirst($log['module'])) ?></span></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm me-2">
                                                                <img src="source/assets/images/faces/1.jpg" alt="Avatar">
                                                            </div>
                                                            <span><?= htmlspecialchars($log['admin_name'] ?? 'System') ?></span>
                                                        </div>
                                                    </td>
                                                    <td><?= htmlspecialchars($log['description'] ?? '') ?></td>
                                                    <td>
                                                        <small class="d-block text-muted"><?= htmlspecialchars(formatIP($log['ip_address'] ?? 'N/A')) ?></small>
                                                        <small class="d-block text-muted" style="font-size: 0.75rem;">
                                                            <?= htmlspecialchars(getBrowserOS($log['user_agent'] ?? '')) ?>
                                                        </small>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (count($logs) === 0): ?>
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-4">
                                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                        No logs found matching your criteria
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <?php if ($totalPages > 1): ?>
                                <nav aria-label="Page navigation" class="mt-4">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?page=<?= $page - 1 ?>&action=<?= $action ?>&module=<?= $module ?>&dateFrom=<?= $dateFrom ?>&dateTo=<?= $dateTo ?>&search=<?= $search ?>">Previous</a>
                                        </li>
                                        
                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <?php if ($i == 1 || $i == $totalPages || ($i >= $page - 2 && $i <= $page + 2)): ?>
                                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                                    <a class="page-link" href="?page=<?= $i ?>&action=<?= $action ?>&module=<?= $module ?>&dateFrom=<?= $dateFrom ?>&dateTo=<?= $dateTo ?>&search=<?= $search ?>"><?= $i ?></a>
                                                </li>
                                            <?php elseif ($i == $page - 3 || $i == $page + 3): ?>
                                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        
                                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?page=<?= $page + 1 ?>&action=<?= $action ?>&module=<?= $module ?>&dateFrom=<?= $dateFrom ?>&dateTo=<?= $dateTo ?>&search=<?= $search ?>">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include 'includes/admin_footer.php'; ?>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="source/assets/js/bootstrap.js"></script>
    <script src="source/assets/js/app.js"></script>

    <script>
        function exportLogs() {
            // Get current filter values
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'true');
            
            // Redirect to trigger download
            window.location.href = '?' + params.toString();
        }
    </script>
</body>
</html>
<?php
// Close database connection
if (isset($conn)) {
    mysqli_close($conn);
}
?>
