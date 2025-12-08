<?php
/**
 * Customer Management Page
 * Module E: User & System Administration
 * Displays and manages customer accounts
 */

// Start session and include necessary files
session_start();
require_once 'includes/db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// ==================== DATA FETCHING ====================

// Get filter parameters
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

// Fetch customer statistics
$stats = [
    'total_customers' => 0,
    'active_customers' => 0,
    'new_this_month' => 0,
    'total_spent' => 0
];

// Stats query using users and orders tables
$stats_query = "SELECT 
    (SELECT COUNT(*) FROM users) as total,
    (SELECT COUNT(*) FROM users WHERE status = 'active') as active,
    (SELECT COUNT(*) FROM users WHERE DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')) as new_month,
    (SELECT SUM(total_amount) FROM orders WHERE order_status != 'Cancelled' AND order_status != 'Failed') as total_revenue";

$result = mysqli_query($conn, $stats_query);
if ($result && $row = mysqli_fetch_assoc($result)) {
    $stats['total_customers'] = $row['total'];
    $stats['active_customers'] = $row['active'];
    $stats['new_this_month'] = $row['new_month'];
    $stats['total_spent'] = $row['total_revenue'] ?? 0;
}

// Build customer query with filters
// We join with orders to get aggregated stats per user
$customer_query = "SELECT 
    u.user_id,
    u.full_name,
    u.email,
    u.default_shipping_phone as phone,
    CONCAT(COALESCE(u.default_shipping_city, ''), ', ', COALESCE(u.default_shipping_state, '')) as location,
    u.created_at as registration_date,
    u.status,
    u.profile_image_url as profile_picture,
    COUNT(o.order_id) as total_orders,
    COALESCE(SUM(CASE WHEN o.order_status != 'Cancelled' AND o.order_status != 'Failed' THEN o.total_amount ELSE 0 END), 0) as total_spent
FROM users u
LEFT JOIN orders o ON u.user_id = o.user_id
WHERE 1=1";

$params = [];
$types = '';

if (!empty($search_term)) {
    $customer_query .= " AND (u.full_name LIKE ? OR u.email LIKE ?)";
    $search_param = "%{$search_term}%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'ss';
}

if (!empty($status_filter)) {
    $customer_query .= " AND u.status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

if (!empty($date_from)) {
    $customer_query .= " AND DATE(u.created_at) >= ?";
    $params[] = $date_from;
    $types .= 's';
}

if (!empty($date_to)) {
    $customer_query .= " AND DATE(u.created_at) <= ?";
    $params[] = $date_to;
    $types .= 's';
}

$customer_query .= " GROUP BY u.user_id ORDER BY u.created_at DESC LIMIT 100";

$stmt = mysqli_prepare($conn, $customer_query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$customers_result = mysqli_stmt_get_result($stmt);

// Fetch customers into array
$customers = [];
while ($row = mysqli_fetch_assoc($customers_result)) {
    $customers[] = $row;
}

// ==================== VIEW LAYER ====================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management - Smart Laptop Advisor</title>
    
    <!-- CSS Files -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="source/assets/css/bootstrap.css">
    <link rel="stylesheet" href="source/assets/vendors/iconly/bold.css">
    <link rel="stylesheet" href="source/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="source/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="source/assets/vendors/simple-datatables/style.css">
    <link rel="stylesheet" href="source/assets/css/app.css">
    <link rel="shortcut icon" href="source/assets/images/favicon.svg" type="image/x-icon">
    
    <style>
        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-suspended {
            background-color: #fff3cd;
            color: #856404;
        }
        .filter-section {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
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

            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Customer Management</h3>
                            <p class="text-subtitle text-muted">Manage registered customer accounts and activity</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Customers</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Customer Statistics -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="align-self-center">
                                            <i class="bi bi-people text-primary font-large-2 float-left"></i>
                                        </div>
                                        <div class="media-body text-right ms-auto">
                                            <h3 class="primary"><?php echo number_format($stats['total_customers']); ?></h3>
                                            <span>Total Customers</span>
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
                                            <i class="bi bi-person-check text-success font-large-2 float-left"></i>
                                        </div>
                                        <div class="media-body text-right ms-auto">
                                            <h3 class="success"><?php echo number_format($stats['active_customers']); ?></h3>
                                            <span>Active Customers</span>
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
                                            <i class="bi bi-person-plus text-info font-large-2 float-left"></i>
                                        </div>
                                        <div class="media-body text-right ms-auto">
                                            <h3 class="info"><?php echo number_format($stats['new_this_month']); ?></h3>
                                            <span>New This Month</span>
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
                                            <i class="bi bi-currency-dollar text-warning font-large-2 float-left"></i>
                                        </div>
                                        <div class="media-body text-right ms-auto">
                                            <h3 class="warning currency-price" data-base-price="<?= $stats['total_spent']; ?>">$<?php echo number_format($stats['total_spent'], 2); ?></h3>
                                            <span>Total Revenue</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Actions -->
                <div class="filter-section">
                    <form method="GET" action="" id="filterForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Search customers..." 
                                           value="<?php echo htmlspecialchars($search_term); ?>">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    <option value="suspended" <?php echo $status_filter === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">Date From</label>
                                <input type="date" class="form-control" name="date_from" 
                                       value="<?php echo htmlspecialchars($date_from); ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">Date To</label>
                                <input type="date" class="form-control" name="date_to" 
                                       value="<?php echo htmlspecialchars($date_to); ?>">
                            </div>
                            
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-funnel me-1"></i> Filter
                                    </button>
                                    <a href="admin_customers.php" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                                    </a>
                                    <button type="button" class="btn btn-outline-primary" onclick="exportCustomers()">
                                        <i class="bi bi-download"></i> Export
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Customers Table -->
                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">All Customers (<?php echo count($customers); ?>)</h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="customersTable">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                                            </th>
                                            <th>User</th>
                                            <th>Contact Info</th>
                                            <th>Location</th>
                                            <th>Joined Date</th>
                                            <th>Orders</th>
                                            <th>Total Spent</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($customers) > 0): ?>
                                            <?php foreach ($customers as $customer): ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="customer-checkbox" 
                                                               value="<?php echo $customer['user_id']; ?>">
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <?php 
                                                            $avatar_src = '';
                                                            if (!empty($customer['profile_picture'])) {
                                                                $avatar_path = '../LaptopAdvisor/uploads/' . $customer['profile_picture'];
                                                                if (file_exists($avatar_path)) {
                                                                    $avatar_src = $avatar_path;
                                                                } else {
                                                                    $avatar_path = '../LaptopAdvisor/' . $customer['profile_picture'];
                                                                    if (file_exists($avatar_path)) {
                                                                        $avatar_src = $avatar_path;
                                                                    }
                                                                }
                                                            }
                                                            
                                                            if (!empty($avatar_src)): ?>
                                                                <img src="<?php echo htmlspecialchars($avatar_src); ?>" 
                                                                     alt="Avatar" class="customer-avatar me-2">
                                                            <?php else: ?>
                                                                <div class="avatar avatar-md me-2 bg-light text-primary d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px;">
                                                                    <span class="avatar-content fw-bold">
                                                                        <?php echo strtoupper(substr($customer['full_name'], 0, 1)); ?>
                                                                    </span>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div>
                                                                <strong><?php echo htmlspecialchars($customer['full_name']); ?></strong>
                                                                <br>
                                                                <small class="text-muted">#USR-<?php echo str_pad($customer['user_id'], 4, '0', STR_PAD_LEFT); ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($customer['email']); ?>
                                                        <?php if (!empty($customer['phone'])): ?>
                                                            <br><i class="bi bi-telephone me-1"></i><?php echo htmlspecialchars($customer['phone']); ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <i class="bi bi-geo-alt me-1"></i>
                                                        <?php echo !empty($customer['location']) && $customer['location'] != ', ' ? htmlspecialchars($customer['location']) : '<span class="text-muted">N/A</span>'; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo date('M d, Y', strtotime($customer['registration_date'])); ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light-primary">
                                                            <?php echo $customer['total_orders']; ?> orders
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <strong><span class="currency-price" data-base-price="<?= $customer['total_spent']; ?>">$<?php echo number_format($customer['total_spent'], 2); ?></span></strong>
                                                    </td>
                                                    <td>
                                                        <span class="status-badge status-<?php echo strtolower($customer['status']); ?>">
                                                            <?php echo ucfirst($customer['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                                    onclick="viewCustomer(<?php echo $customer['user_id']; ?>)"
                                                                    title="View Details">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                                    onclick="editCustomer(<?php echo $customer['user_id']; ?>)"
                                                                    title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                                    onclick="suspendCustomer(<?php echo $customer['user_id']; ?>)"
                                                                    title="Suspend">
                                                                <i class="bi bi-exclamation-triangle"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="text-center text-muted py-4">
                                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                    No users found. Try adjusting your filters.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?php include 'includes/admin_footer.php'; ?>
        </div>
    </div>

    <!-- Customer Details Modal -->
    <div class="modal fade" id="customerDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customer Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="customerDetailsContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Customer Modal -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCustomerForm">
                        <input type="hidden" id="edit_user_id" name="user_id">
                        
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveCustomer()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Files -->
    <script src="source/assets/js/bootstrap.js"></script>
    <script src="source/assets/js/app.js"></script>

    <script>
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.customer-checkbox');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
        }

        function viewCustomer(userId) {
            const modal = new bootstrap.Modal(document.getElementById('customerDetailsModal'));
            modal.show();
            
            // Load customer details via AJAX
            fetch(`ajax/get_customer_details.php?id=${userId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('customerDetailsContent').innerHTML = html;
                    if (typeof CurrencyManager !== 'undefined') {
                        CurrencyManager.updatePagePrices();
                    }
                });
        }

        function editCustomer(userId) {
            // Fetch user data
            fetch(`ajax/get_customer_for_edit.php?id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.user;
                        document.getElementById('edit_user_id').value = user.user_id;
                        document.getElementById('edit_full_name').value = user.full_name;
                        document.getElementById('edit_email').value = user.email;
                        document.getElementById('edit_phone').value = user.default_shipping_phone || '';
                        document.getElementById('edit_status').value = user.status;
                        
                        const modal = new bootstrap.Modal(document.getElementById('editCustomerModal'));
                        modal.show();
                    } else {
                        alert('Error fetching user data: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load user data');
                });
        }

        function saveCustomer() {
            const form = document.getElementById('editCustomerForm');
            const formData = new FormData(form);
            
            fetch('ajax/update_customer.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Customer updated successfully');
                    location.reload();
                } else {
                    alert('Error updating customer: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update customer');
            });
        }

        function suspendCustomer(userId) {
            if (confirm('Are you sure you want to suspend this customer? They will not be able to log in.')) {
                updateStatus(userId, 'suspended');
            }
        }

        function updateStatus(userId, status) {
            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('status', status);

            fetch('ajax/update_customer_status.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status updated successfully');
                    location.reload();
                } else {
                    alert('Error updating status: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update status');
            });
        }

        function exportCustomers() {
            // Get current filter parameters
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            
            // Redirect to export endpoint
            window.location.href = `ajax/export_customers.php?${params.toString()}`;
        }
    </script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
