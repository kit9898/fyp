<?php
session_start();
require_once 'includes/db_connect.php';

// Handle Status Update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    if ($stmt->execute()) {
        $success_message = "Order status updated successfully.";
    } else {
        $error_message = "Error updating order status.";
    }
    $stmt->close();
}

// Fetch Orders with User Details and Filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

$query = "SELECT o.*, u.full_name, u.email, u.profile_image_url FROM orders o LEFT JOIN users u ON o.user_id = u.user_id WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $query .= " AND (o.order_id LIKE ? OR u.full_name LIKE ? OR u.email LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}

if (!empty($status) && $status !== 'all') {
    $query .= " AND o.order_status = ?";
    $params[] = $status;
    $types .= "s";
}

if (!empty($date_from)) {
    $query .= " AND DATE(o.order_date) >= ?";
    $params[] = $date_from;
    $types .= "s";
}

if (!empty($date_to)) {
    $query .= " AND DATE(o.order_date) <= ?";
    $params[] = $date_to;
    $types .= "s";
}

$query .= " ORDER BY o.order_date DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Fixed Payment Method
        $row['payment_method'] = 'Credit Card';
        
        // Status Badge Logic
        switch (strtolower($row['order_status'])) {
            case 'completed':
            case 'delivered':
                $row['status_badge'] = '<span class="badge bg-success">Delivered</span>';
                break;
            case 'pending':
            case 'processing':
                $row['status_badge'] = '<span class="badge bg-warning">Pending</span>';
                break;
            case 'cancelled':
            case 'failed':
                $row['status_badge'] = '<span class="badge bg-danger">Cancelled</span>';
                break;
            case 'shipped':
                $row['status_badge'] = '<span class="badge bg-info">Shipped</span>';
                break;
            default:
                $row['status_badge'] = '<span class="badge bg-secondary">' . htmlspecialchars($row['order_status']) . '</span>';
        }
        
        $orders[] = $row;
    }
}

// Calculate Stats
$total_orders = count($orders);
$pending_orders = count(array_filter($orders, function($o) { return in_array(strtolower($o['order_status']), ['pending', 'processing']); }));
$completed_orders = count(array_filter($orders, function($o) { return in_array(strtolower($o['order_status']), ['completed', 'delivered']); }));
$cancelled_orders = count(array_filter($orders, function($o) { return in_array(strtolower($o['order_status']), ['cancelled', 'failed']); }));

$page_title = "Order Management";
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
    <link rel="stylesheet" href="source/assets/vendors/simple-datatables/style.css">
    <link rel="shortcut icon" href="source/assets/images/favicon.svg" type="image/x-icon">
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
                <h3>Order Management</h3>
                <p class="text-subtitle text-muted">Track and manage customer orders</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order Management</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Order Statistics -->
    <div class="row">
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
                            <h6 class="text-muted font-semibold">Total Orders</h6>
                            <h6 class="font-extrabold mb-0"><?php echo $total_orders; ?></h6>
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
                            <h6 class="font-extrabold mb-0"><?php echo $pending_orders; ?></h6>
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
                            <h6 class="text-muted font-semibold">Completed</h6>
                            <h6 class="font-extrabold mb-0"><?php echo $completed_orders; ?></h6>
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
                                <i class="iconly-boldClose-Square"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Cancelled</h6>
                            <h6 class="font-extrabold mb-0"><?php echo $cancelled_orders; ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <!-- Status Filter -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-funnel me-2"></i>Status <?php if (!empty($status)): ?><span class="badge bg-primary"><?= ucfirst(htmlspecialchars($status)) ?></span><?php endif; ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?= empty($status) ? 'active' : '' ?>" href="admin_orders.php">All Orders</a></li>
                        <li><a class="dropdown-item <?= $status === 'pending' ? 'active' : '' ?>" href="admin_orders.php?status=pending">Pending</a></li>
                        <li><a class="dropdown-item <?= $status === 'processing' ? 'active' : '' ?>" href="admin_orders.php?status=processing">Processing</a></li>
                        <li><a class="dropdown-item <?= $status === 'shipped' ? 'active' : '' ?>" href="admin_orders.php?status=shipped">Shipped</a></li>
                        <li><a class="dropdown-item <?= $status === 'delivered' ? 'active' : '' ?>" href="admin_orders.php?status=delivered">Delivered</a></li>
                        <li><a class="dropdown-item <?= $status === 'cancelled' ? 'active' : '' ?>" href="admin_orders.php?status=cancelled">Cancelled</a></li>
                    </ul>
                </div>

                <?php if (!empty($status)): ?>
                <a href="admin_orders.php" class="btn btn-outline-danger">
                    <i class="bi bi-x-circle me-2"></i>Clear Filters
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                <button class="btn btn-outline-primary" onclick="exportCSV()">
                    <i class="bi bi-download me-2"></i>Export CSV
                </button>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Recent Orders</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>ORD-<?php echo str_pad($order['order_id'], 4, '0', STR_PAD_LEFT); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-md me-3">
                                            <?php 
                                            $avatar_src = "source/assets/images/faces/" . rand(1, 8) . ".jpg";
                                            // Check if user has a custom avatar
                                            if (!empty($order['profile_image_url'])) {
                                                $avatar_path = '../LaptopAdvisor/uploads/' . $order['profile_image_url'];
                                                if (file_exists($avatar_path)) {
                                                    $avatar_src = $avatar_path;
                                                } else {
                                                    // Try without uploads prefix if path already contains it or is just filename
                                                    $avatar_path = '../LaptopAdvisor/' . $order['profile_image_url'];
                                                    if (file_exists($avatar_path)) {
                                                        $avatar_src = $avatar_path;
                                                    }
                                                }
                                            }
                                            ?>
                                            <img src="<?php echo htmlspecialchars($avatar_src); ?>" alt="Face" style="object-fit: cover;">
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?php echo htmlspecialchars($order['full_name'] ?? 'Guest'); ?></h6>
                                            <small class="text-muted"><?php echo htmlspecialchars($order['email'] ?? 'No Email'); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                <td><span class="currency-price" data-base-price="<?= $order['total_amount']; ?>">$<?php echo number_format($order['total_amount'], 2); ?></span></td>
                                <td><?php echo $order['payment_method']; ?></td>
                                <td><?php echo $order['status_badge']; ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?php echo $order['order_id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                            Update Status
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $order['order_id']; ?>">
                                            <li>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                                    <input type="hidden" name="update_status" value="1">
                                                    <input type="hidden" name="status" value="Pending">
                                                    <button class="dropdown-item" type="submit">Pending</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                                    <input type="hidden" name="update_status" value="1">
                                                    <input type="hidden" name="status" value="Shipped">
                                                    <button class="dropdown-item" type="submit">Shipped</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                                    <input type="hidden" name="update_status" value="1">
                                                    <input type="hidden" name="status" value="Delivered">
                                                    <button class="dropdown-item" type="submit">Delivered</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                                    <input type="hidden" name="update_status" value="1">
                                                    <input type="hidden" name="status" value="Cancelled">
                                                    <button class="dropdown-item" type="submit">Cancelled</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="orderDetailsContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" onclick="printOrder()">
                        <i class="bi bi-printer me-2"></i>Print Invoice
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'includes/admin_footer.php'; ?>
<script src="source/assets/vendors/simple-datatables/simple-datatables.js"></script>
<script>
    // Initialize DataTable
    let table1 = document.querySelector('#table1');
    let dataTable = new simpleDatatables.DataTable(table1, {
        searchable: true,
        fixedHeight: false,
        perPage: 10,
        perPageSelect: [5, 10, 25, 50, 100],
        labels: {
            placeholder: "Search orders...",
            noRows: "No orders found",
            info: "Showing {start} to {end} of {rows} entries"
        },
        layout: {
            top: "{select}{search}",
            bottom: "{info}{pager}"
        }
    });

    // Filter Functions
    function applyFilters() {
        const search = document.getElementById('searchInput').value;
        const status = new URLSearchParams(window.location.search).get('status') || '';
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;

        const params = new URLSearchParams();
        if (search) params.set('search', search);
        if (status) params.set('status', status);
        if (dateFrom) params.set('date_from', dateFrom);
        if (dateTo) params.set('date_to', dateTo);

        window.location.href = '?' + params.toString();
    }

    function filterByStatus(status) {
        const currentParams = new URLSearchParams(window.location.search);
        if (status === 'all') {
            currentParams.delete('status');
        } else {
            currentParams.set('status', status);
        }
        window.location.href = '?' + currentParams.toString();
    }

    // Event Listeners
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') applyFilters();
    });
    
    document.querySelector('.btn-outline-secondary i.bi-search').parentElement.addEventListener('click', applyFilters);
    
    document.getElementById('dateFrom').addEventListener('change', applyFilters);
    document.getElementById('dateTo').addEventListener('change', applyFilters);

    // Set initial values from URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search')) document.getElementById('searchInput').value = urlParams.get('search');
    if (window.location.search.includes('date_from')) document.getElementById('dateFrom').value = urlParams.get('date_from');
    if (window.location.search.includes('date_to')) document.getElementById('dateTo').value = urlParams.get('date_to');

    // Export Function
    function exportOrders() {
        const params = new URLSearchParams(window.location.search);
        window.location.href = 'ajax/export_orders.php?' + params.toString();
    }

    // Bind Export Button
    document.querySelector('.btn-outline-primary i.bi-download').parentElement.addEventListener('click', exportOrders);

    // Order Details Functions
    function loadOrderDetails(orderId) {
        const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
        modal.show();
        
        // Reset content to loading spinner
        document.getElementById('orderDetailsContent').innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        document.getElementById('orderDetailsModalLabel').textContent = `Order Details - #${orderId}`;

        fetch(`ajax/get_order_details.php?id=${orderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayOrderDetails(data.order);
                } else {
                    document.getElementById('orderDetailsContent').innerHTML = 
                        `<div class="alert alert-danger">${data.error}</div>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('orderDetailsContent').innerHTML = 
                    '<div class="alert alert-danger">Failed to load order details.</div>';
            });
    }

    function displayOrderDetails(order) {
        const statusColors = {
            'pending': 'warning',
            'processing': 'info',
            'shipped': 'primary',
            'delivered': 'success',
            'completed': 'success',
            'cancelled': 'danger',
            'failed': 'danger'
        };
        const statusColor = statusColors[order.order_status.toLowerCase()] || 'secondary';

        let itemsHtml = order.items.map(item => `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="${item.image_url}" alt="${item.product_name}" 
                             class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0">${item.product_name}</h6>
                            <small class="text-muted">${item.brand} | ${item.product_category}</small>
                        </div>
                    </div>
                </td>
                <td class="text-center">${item.quantity}</td>
                <td class="text-end"><span class="currency-price" data-base-price="${item.price_at_purchase}">$${parseFloat(item.price_at_purchase).toFixed(2)}</span></td>
                <td class="text-end"><span class="currency-price" data-base-price="${item.subtotal}">$${parseFloat(item.subtotal).toFixed(2)}</span></td>
            </tr>
        `).join('');

        const content = `
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="border-bottom pb-2">Customer Information</h6>
                    <p class="mb-1"><strong>Name:</strong> ${order.customer.full_name}</p>
                    <p class="mb-1"><strong>Email:</strong> ${order.customer.email}</p>
                    <p class="mb-1"><strong>Phone:</strong> ${order.customer.phone}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="border-bottom pb-2">Order Summary</h6>
                    <p class="mb-1"><strong>Order Date:</strong> ${new Date(order.order_date).toLocaleDateString()}</p>
                    <p class="mb-1"><strong>Status:</strong> <span class="badge bg-${statusColor}">${order.order_status}</span></p>
                    <p class="mb-1"><strong>Payment Method:</strong> Credit Card</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="border-bottom pb-2">Shipping Details</h6>
                    <p class="mb-1">${order.shipping.name}</p>
                    <p class="mb-1">${order.shipping.address}</p>
                    <p class="mb-0">${order.shipping.city}, ${order.shipping.state} ${order.shipping.zip}</p>
                    <p class="mb-0">${order.shipping.country}</p>
                </div>
            </div>

            <h6 class="border-bottom pb-2">Order Items</h6>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${itemsHtml}
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                            <td class="text-end"><span class="currency-price" data-base-price="${order.subtotal}">$${order.subtotal.toFixed(2)}</span></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td class="text-end"><strong><span class="currency-price" data-base-price="${order.total}">$${order.total.toFixed(2)}</span></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        `;
        
        document.getElementById('orderDetailsContent').innerHTML = content;
        if (typeof CurrencyManager !== 'undefined') {
            CurrencyManager.updatePagePrices();
        }
    }

    function printOrder() {
        window.print();
    }
</script>


        </div>
    </div>
</body>
</html>
