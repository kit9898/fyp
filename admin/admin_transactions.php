<?php
session_start();
require_once 'includes/db_connect.php';

// Handle CSV Export
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="transactions_report_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Transaction ID', 'Order ID', 'Customer', 'Email', 'Amount', 'Payment Method', 'Status', 'Date', 'Flagged']);
    
    // Build query for export (same as display but no pagination)
    $where_clauses = [];
    $params = [];
    $types = "";

    if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
        $where_clauses[] = "o.order_date BETWEEN ? AND ?";
        $params[] = $_GET['start_date'] . " 00:00:00";
        $params[] = $_GET['end_date'] . " 23:59:59";
        $types .= "ss";
    }
    
    if (!empty($_GET['status'])) {
        $where_clauses[] = "o.order_status = ?";
        $params[] = ucfirst($_GET['status']);
        $types .= "s";
    }

    if (!empty($_GET['payment_method'])) {
        $where_clauses[] = "o.payment_method = ?";
        $params[] = $_GET['payment_method']; // e.g., 'Credit Card'
        $types .= "s";
    }

    if (isset($_GET['flagged']) && $_GET['flagged'] == '1') {
        $where_clauses[] = "o.is_flagged = 1";
    }

    $sql = "SELECT o.*, u.full_name, u.email FROM orders o LEFT JOIN users u ON o.user_id = u.user_id";
    if (!empty($where_clauses)) {
        $sql .= " WHERE " . implode(" AND ", $where_clauses);
    }
    $sql .= " ORDER BY o.order_date DESC";
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $txn_id = 'TXN-' . date('Y', strtotime($row['order_date'])) . '-' . str_pad($row['order_id'], 5, '0', STR_PAD_LEFT);
        fputcsv($output, [
            $txn_id,
            $row['order_id'],
            $row['full_name'] ?? 'Guest',
            $row['email'] ?? '',
            $row['total_amount'],
            $row['payment_method'] ?? 'Credit Card',
            $row['order_status'],
            $row['order_date'],
            $row['is_flagged'] ? 'Yes' : 'No'
        ]);
    }
    fclose($output);
    exit;
}

// Regular Page Load
$page_title = "Transaction Management";

// Build Query
$where_clauses = [];
$params = [];
$types = "";

// Filters
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$status_filter = $_GET['status'] ?? '';
$payment_method_filter = $_GET['payment_method'] ?? '';
$flagged_filter = $_GET['flagged'] ?? '';

if (!empty($start_date) && !empty($end_date)) {
    $where_clauses[] = "o.order_date BETWEEN ? AND ?";
    $params[] = $start_date . " 00:00:00";
    $params[] = $end_date . " 23:59:59";
    $types .= "ss";
}

if (!empty($status_filter)) {
    $where_clauses[] = "o.order_status = ?";
    $params[] = ucfirst($status_filter);
    $types .= "s";
}

if (!empty($payment_method_filter)) {
    $where_clauses[] = "o.payment_method = ?";
    $params[] = $payment_method_filter;
    $types .= "s";
}

if ($flagged_filter == '1') {
    $where_clauses[] = "o.is_flagged = 1";
}

$query = "SELECT o.*, u.full_name, u.email, u.profile_image_url FROM orders o LEFT JOIN users u ON o.user_id = u.user_id";
if (!empty($where_clauses)) {
    $query .= " WHERE " . implode(" AND ", $where_clauses);
}
$query .= " ORDER BY o.order_date DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$transactions = [];
$total_revenue = 0;
$successful_txns = 0;
$pending_txns = 0;
$failed_txns = 0;

while ($row = $result->fetch_assoc()) {
    // Calculate stats based on ALL fetched data (respecting filters)
    $total_revenue += $row['total_amount'];
    $st = strtolower($row['order_status']);
    if ($st == 'completed' || $st == 'delivered' || $st == 'shipped') $successful_txns++;
    elseif ($st == 'pending' || $st == 'processing') $pending_txns++;
    elseif ($st == 'cancelled' || $st == 'failed' || $st == 'refunded') $failed_txns++;

    // Prepare display data
    $row['transaction_id'] = 'TXN-' . date('Y', strtotime($row['order_date'])) . '-' . str_pad($row['order_id'], 5, '0', STR_PAD_LEFT);
    $row['fee'] = $row['total_amount'] * 0.029 + 0.30; // Mock fee
    $row['payment_method'] = $row['payment_method'] ?: 'Credit Card'; // Default
    
    // Badge
    switch (strtolower($row['order_status'])) {
        case 'completed': 
        case 'delivered': 
        case 'shipped':
            $row['status_badge'] = '<span class="badge bg-success">'.ucfirst($row['order_status']).'</span>'; 
            break;
        case 'pending': 
        case 'processing':
            $row['status_badge'] = '<span class="badge bg-warning">'.ucfirst($row['order_status']).'</span>'; 
            break;
        case 'refunded': 
        case 'cancelled':
            $row['status_badge'] = '<span class="badge bg-info">'.ucfirst($row['order_status']).'</span>'; 
            break;
        default: 
            $row['status_badge'] = '<span class="badge bg-danger">'.ucfirst($row['order_status']).'</span>'; 
            break;
    }
    
    $transactions[] = $row;
}
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
    <!-- Toastify -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body>
    <div id="app">
        <?php require_once 'includes/admin_header.php'; ?>

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
                <h3>Transaction Management</h3>
                <p class="text-subtitle text-muted">Monitor financial transactions, payments, and revenue streams</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Transactions</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Transaction Statistics -->
    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon purple">
                                <i class="iconly-boldWallet"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Total Revenue</h6>
                            <h6 class="font-extrabold mb-0">$<?php echo number_format($total_revenue, 2); ?></h6>
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
                                <i class="iconly-boldTick-Square"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Successful</h6>
                            <h6 class="font-extrabold mb-0"><?php echo $successful_txns; ?></h6>
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
                                <i class="iconly-boldTime-Circle"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Pending</h6>
                            <h6 class="font-extrabold mb-0"><?php echo $pending_txns; ?></h6>
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
                                <i class="iconly-boldDanger"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Failed/Refunded</h6>
                            <h6 class="font-extrabold mb-0"><?php echo $failed_txns; ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" onclick="exportReport()">
                                <i class="bi bi-download me-2"></i>Export Report
                            </button>
                            <button class="btn btn-outline-secondary" onclick="printStatement()">
                                <i class="bi bi-printer me-2"></i>Print Statement
                            </button>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="?flagged=1" class="btn btn-outline-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>Review Flagged
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Transactions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="GET" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Date Range</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="start_date" value="<?= $start_date ?>">
                                    <span class="input-group-text">to</span>
                                    <input type="date" class="form-control" name="end_date" value="<?= $end_date ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Payment Method</label>
                                <select class="form-select" name="payment_method">
                                    <option value="">All Methods</option>
                                    <option value="Credit Card" <?= $payment_method_filter == 'Credit Card' ? 'selected' : '' ?>>Credit Card</option>
                                    <option value="PayPal" <?= $payment_method_filter == 'PayPal' ? 'selected' : '' ?>>PayPal</option>
                                    <option value="Bank Transfer" <?= $payment_method_filter == 'Bank Transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="completed" <?= $status_filter == 'completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="pending" <?= $status_filter == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="refunded" <?= $status_filter == 'refunded' ? 'selected' : '' ?>>Refunded</option>
                                    <option value="failed" <?= $status_filter == 'failed' ? 'selected' : '' ?>>Failed</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-filter me-2"></i>Filter Transactions
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Transaction History</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Customer</th>
                                    <th>Order ID</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $txn): ?>
                                <tr id="row-<?= $txn['order_id'] ?>">
                                    <td>
                                        <strong><?php echo $txn['transaction_id']; ?></strong><br>
                                        <?php if ($txn['is_flagged']): ?>
                                            <span class="badge bg-danger">Flagged</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-md me-3">
                                                <?php 
                                                $avatar_src = "source/assets/images/faces/" . rand(1, 8) . ".jpg";
                                                // Check if user has a custom avatar
                                                if (!empty($txn['profile_image_url'])) {
                                                    $avatar_path = '../LaptopAdvisor/uploads/' . $txn['profile_image_url'];
                                                    if (file_exists($avatar_path)) {
                                                        $avatar_src = $avatar_path;
                                                    } else {
                                                        // Try without uploads prefix if path already contains it or is just filename
                                                        $avatar_path = '../LaptopAdvisor/' . $txn['profile_image_url'];
                                                        if (file_exists($avatar_path)) {
                                                            $avatar_src = $avatar_path;
                                                        }
                                                    }
                                                }
                                                ?>
                                                <img src="<?php echo htmlspecialchars($avatar_src); ?>" alt="Face" style="object-fit: cover;">
                                            </div>
                                            <div>
                                                <strong><?php echo htmlspecialchars($txn['full_name'] ?? 'Guest'); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($txn['email'] ?? 'No Email'); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#" class="text-primary">ORD-<?php echo str_pad($txn['order_id'], 4, '0', STR_PAD_LEFT); ?></a>
                                    </td>
                                    <td>
                                        <strong class="text-success">$<?php echo number_format($txn['total_amount'], 2); ?></strong><br>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?php echo $txn['payment_method']; ?></span>
                                    </td>
                                    <td>
                                        <div>
                                            <small><?php echo date('Y-m-d', strtotime($txn['order_date'])); ?></small><br>
                                            <small class="text-muted"><?php echo date('H:i:s', strtotime($txn['order_date'])); ?></small>
                                        </div>
                                    </td>
                                    <td><?php echo $txn['status_badge']; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewTransaction(<?php echo $txn['order_id']; ?>)" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <?php if (strtolower($txn['order_status']) == 'pending'): ?>
                                            <button class="btn btn-sm btn-outline-success" onclick="verifyPayment(<?php echo $txn['order_id']; ?>)" title="Verify Payment">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                            <?php endif; ?>
                                            
                                            <button class="btn btn-sm <?php echo $txn['is_flagged'] ? 'btn-danger' : 'btn-outline-danger'; ?>" onclick="toggleFlag(<?php echo $txn['order_id']; ?>)" title="Toggle Flag">
                                                <i class="bi bi-flag"></i>
                                            </button>
                                            
                                            <a href="invoice.php?order_id=<?php echo $txn['order_id']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Download Invoice">
                                                <i class="bi bi-download"></i>
                                            </a>

                                            <?php if (in_array(strtolower($txn['order_status']), ['refunded', 'cancelled'])): ?>
                                            <button class="btn btn-sm btn-outline-dark" onclick="deleteTransaction(<?php echo $txn['order_id']; ?>)" title="Remove Transaction">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Details Modal -->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transaction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <div class="text-center"><div class="spinner-border" role="status"></div></div>
            </div>
        </div>
    </div>
</div>

<script src="source/assets/vendors/simple-datatables/simple-datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    // Initialize DataTable
    let table1 = document.querySelector('#table1');
    let dataTable = new simpleDatatables.DataTable(table1, {
        searchable: true,
        fixedHeight: true,
        perPage: 10
    });

    function showToast(msg, type = 'success') {
        Toastify({
            text: msg,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: type === 'success' ? "#4fbe87" : "#f3616d",
        }).showToast();
    }

    function exportReport() {
        // Get current URL parameters
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'csv');
        window.location.href = '?' + params.toString();
    }

    function printStatement() {
        // Open print page with current query parameters
        const params = window.location.search;
        window.open('print_transactions.php' + params, '_blank');
    }

    function viewTransaction(id) {
        var myModal = new bootstrap.Modal(document.getElementById('transactionModal'));
        myModal.show();
        
        document.getElementById('modalContent').innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div></div>';
        
        fetch('ajax/transaction_actions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=get_details&order_id=' + id
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Server response:', text);
                    document.getElementById('modalContent').innerHTML = '<p class="text-danger">Server Error: ' + text.substring(0, 100) + '...</p>';
                    throw new Error('Invalid JSON response');
                }
            });
        })
        .then(data => {
            if(data.success) {
                let html = `
                    <div class="row mb-3">
                        <div class="col-6">
                            <h6>Order Info</h6>
                            <p>ID: ${data.order.order_id}<br>
                            Date: ${data.order.order_date}<br>
                            Status: ${data.order.order_status}</p>
                        </div>
                        <div class="col-6 text-end">
                            <h6>Payment</h6>
                            <p>Total: $${data.order.total_amount}<br>
                            Method: ${data.order.payment_method || 'Credit Card'}</p>
                        </div>
                    </div>
                    <h6>Items</h6>
                    <table class="table table-sm">
                        <thead><tr><th>Product</th><th>Qty</th><th>Price</th></tr></thead>
                        <tbody>
                `;
                data.items.forEach(item => {
                    html += `<tr>
                        <td>${item.model}</td>
                        <td>${item.quantity}</td>
                        <td>$${item.price_at_purchase}</td>
                    </tr>`;
                });
                html += `</tbody></table>`;
                document.getElementById('modalContent').innerHTML = html;
            } else {
                document.getElementById('modalContent').innerHTML = '<p class="text-danger">Failed to load details</p>';
            }
        });
    }

    function verifyPayment(id) {
        if(confirm('Mark this payment as verified/completed?')) {
            fetch('ajax/transaction_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=verify_payment&order_id=' + id
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showToast(data.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message, 'error');
                }
            });
        }
    }

    function toggleFlag(id) {
        fetch('ajax/transaction_actions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=toggle_flag&order_id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showToast(data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message, 'error');
            }
        });
    }

    function deleteTransaction(id) {
        if(confirm('Are you sure you want to remove this transaction record? This cannot be undone.')) {
            fetch('ajax/transaction_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=delete_transaction&order_id=' + id
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showToast(data.message);
                    document.getElementById('row-' + id).remove();
                } else {
                    showToast(data.message, 'error');
                }
            });
        }
    }
</script>

<?php include 'includes/admin_footer.php'; ?>
        </div>
    </div>
</body>
</html>
