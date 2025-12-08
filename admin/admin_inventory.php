<?php
session_start();
require_once 'includes/db_connect.php';

// Fetch Inventory (Products)
// Fetch Inventory (Products)
// Build Query with Filters
$where_clauses = ["is_active = 1"];
$params = [];
$types = "";

$current_category = $_GET['category'] ?? '';
$current_status = $_GET['status'] ?? '';

if (!empty($current_category)) {
    $where_clauses[] = "product_category = ?";
    $params[] = $current_category;
    $types .= "s";
}

// Logic for stock status filter
if (!empty($current_status)) {
    if ($current_status == 'in_stock') {
        $where_clauses[] = "stock_quantity > min_stock_level";
    } elseif ($current_status == 'low_stock') {
        $where_clauses[] = "stock_quantity <= min_stock_level AND stock_quantity > 0";
    } elseif ($current_status == 'out_of_stock') {
        $where_clauses[] = "stock_quantity = 0";
    }
}

$sql = "SELECT * FROM products WHERE " . implode(" AND ", $where_clauses) . " ORDER BY product_id DESC";
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$inventory = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Use real DB columns: stock_quantity, min_stock_level
        // Generate SKU if not exists (mock for display if needed, or use ID)
        $row['sku'] = 'SKU-' . str_pad($row['product_id'], 5, '0', STR_PAD_LEFT);
        
        // Status Logic
        if ($row['stock_quantity'] == 0) {
            $row['status_badge'] = '<span class="badge bg-danger">Out of Stock</span>';
            $row['stock_text_class'] = 'text-danger';
            $row['status_code'] = 'out_of_stock';
        } elseif ($row['stock_quantity'] < $row['min_stock_level']) {
            $row['status_badge'] = '<span class="badge bg-warning">Low Stock</span>';
            $row['stock_text_class'] = 'text-warning';
            $row['status_code'] = 'low_stock';
        } else {
            $row['status_badge'] = '<span class="badge bg-success">In Stock</span>';
            $row['stock_text_class'] = 'text-success';
            $row['status_code'] = 'in_stock';
        }
        
        $inventory[] = $row;
    }
}

// Stats
$total_products = count($inventory);
$in_stock = count(array_filter($inventory, function($i) { return $i['stock_quantity'] >= $i['min_stock_level']; }));
$low_stock = count(array_filter($inventory, function($i) { return $i['stock_quantity'] > 0 && $i['stock_quantity'] < $i['min_stock_level']; }));
$out_of_stock = count(array_filter($inventory, function($i) { return $i['stock_quantity'] == 0; }));

$page_title = "Inventory Management";
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
                <h3>Inventory Management</h3>
                <p class="text-subtitle text-muted">Monitor stock levels, manage suppliers, and track inventory movements</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Inventory Management</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Inventory Statistics -->
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
                            <h6 class="text-muted font-semibold">Total Products</h6>
                            <h6 class="font-extrabold mb-0"><?php echo $total_products; ?></h6>
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
                            <h6 class="text-muted font-semibold">In Stock</h6>
                            <h6 class="font-extrabold mb-0"><?php echo $in_stock; ?></h6>
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
                            <h6 class="text-muted font-semibold">Low Stock</h6>
                            <h6 class="font-extrabold mb-0"><?php echo $low_stock; ?></h6>
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
                            <h6 class="text-muted font-semibold">Out of Stock</h6>
                            <h6 class="font-extrabold mb-0"><?php echo $out_of_stock; ?></h6>
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
                            <button class="btn btn-outline-secondary" onclick="window.open('print_inventory.php', '_blank')">
                                <i class="bi bi-printer me-2"></i>Print Stock Report
                            </button>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-success" onclick="exportCSV()">
                                <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export CSV
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <!-- Category Filter -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-tag me-2"></i>Category <?php if ($current_category): ?><span class="badge bg-primary"><?= htmlspecialchars($current_category) ?></span><?php endif; ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?= empty($current_category) ? 'active' : '' ?>" href="admin_inventory.php<?= !empty($current_status) ? '?status=' . urlencode($current_status) : '' ?>">All Categories</a></li>
                        <?php
                        $cat_query = "SELECT DISTINCT product_category FROM products WHERE product_category IS NOT NULL ORDER BY product_category";
                        $cat_result = $conn->query($cat_query);
                        if ($cat_result):
                            while ($cat = $cat_result->fetch_assoc()):
                        ?>
                            <li><a class="dropdown-item <?= $current_category === $cat['product_category'] ? 'active' : '' ?>" href="admin_inventory.php?category=<?= urlencode($cat['product_category']) ?><?= !empty($current_status) ? '&status=' . urlencode($current_status) : '' ?>"><?= htmlspecialchars(ucfirst($cat['product_category'])) ?></a></li>
                        <?php 
                            endwhile;
                        endif;
                        ?>
                    </ul>
                </div>

                <!-- Stock Status Filter -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-box-seam me-2"></i>Status <?php if ($current_status): ?><span class="badge bg-primary"><?= ucwords(str_replace('_', ' ', $current_status)) ?></span><?php endif; ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?= empty($current_status) ? 'active' : '' ?>" href="admin_inventory.php<?= !empty($current_category) ? '?category=' . urlencode($current_category) : '' ?>">All Statuses</a></li>
                        <li><a class="dropdown-item <?= $current_status === 'in_stock' ? 'active' : '' ?>" href="admin_inventory.php?status=in_stock<?= !empty($current_category) ? '&category=' . urlencode($current_category) : '' ?>">In Stock</a></li>
                        <li><a class="dropdown-item <?= $current_status === 'low_stock' ? 'active' : '' ?>" href="admin_inventory.php?status=low_stock<?= !empty($current_category) ? '&category=' . urlencode($current_category) : '' ?>">Low Stock</a></li>
                        <li><a class="dropdown-item <?= $current_status === 'out_of_stock' ? 'active' : '' ?>" href="admin_inventory.php?status=out_of_stock<?= !empty($current_category) ? '&category=' . urlencode($current_category) : '' ?>">Out of Stock</a></li>
                    </ul>
                </div>

                <?php if ($current_category || $current_status): ?>
                <a href="admin_inventory.php" class="btn btn-outline-danger">
                    <i class="bi bi-x-circle me-2"></i>Clear Filters
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Inventory Content -->
    <div class="row">
        <div class="col-12 col-xl-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Current Inventory</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Min Stock</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inventory as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?php 
                                                $img_url = $item['image_url'];
                                                $image_src = '../LaptopAdvisor/images/logo.png'; // Default
                                                if (!empty($img_url)) {
                                                    if (strpos($img_url, 'http') === 0) {
                                                        $image_src = $img_url;
                                                    } elseif (strpos($img_url, 'LaptopAdvisor/') === 0) {
                                                        $image_src = '../' . $img_url;
                                                    } elseif (strpos($img_url, 'images/') === 0) {
                                                        $image_src = '../LaptopAdvisor/' . $img_url;
                                                    } else {
                                                        $image_src = '../LaptopAdvisor/images/' . basename($img_url);
                                                    }
                                                }
                                                echo htmlspecialchars($image_src); 
                                            ?>" alt="Product" class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($item['product_name']); ?></h6>
                                                <small class="text-muted"><?php echo htmlspecialchars($item['brand']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo $item['sku']; ?></td>
                                    <td><span class="badge bg-info"><?php echo htmlspecialchars($item['product_category']); ?></span></td>
                                    <td>
                                        <span class="<?php echo $item['stock_text_class']; ?>">
                                            <strong><?php echo $item['stock_quantity']; ?></strong> units
                                        </span>
                                    </td>
                                    <td><?php echo $item['min_stock_level']; ?></td>
                                    <td><span class="currency-price" data-base-price="<?= $item['price']; ?>">$<?php echo number_format($item['price'], 2); ?></span></td>
                                    <td><?php echo $item['status_badge']; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="updateStock(<?php echo $item['product_id']; ?>, <?php echo $item['stock_quantity']; ?>, <?php echo $item['min_stock_level']; ?>)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewHistory(<?php echo $item['product_id']; ?>)">
                                                <i class="bi bi-clock-history"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="reorderStock(<?php echo $item['product_id']; ?>)">
                                                <i class="bi bi-plus-minus"></i>
                                            </button>
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
        
        <div class="col-12 col-xl-3">
            <!-- Low Stock Alerts -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Low Stock Alerts</h4>
                </div>
                <div class="card-body">
                    <?php 
                    $low_stock_items = array_filter($inventory, function($i) { return $i['stock_quantity'] < $i['min_stock_level']; });
                    if (empty($low_stock_items)): 
                    ?>
                        <div class="text-center py-4">
                            <i class="bi bi-check-circle text-success fs-1"></i>
                            <p class="mt-2">All stock levels are healthy!</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach (array_slice($low_stock_items, 0, 5) as $item): ?>
                            <div class="list-group-item px-0 bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($item['product_name']); ?></h6>
                                        <small class="text-danger"><?php echo $item['stock_quantity']; ?> units left</small>
                                    </div>
                                    <button class="btn btn-sm btn-outline-danger" onclick="reorderStock(<?php echo $item['product_id']; ?>)">Order</button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Stock Modal -->
<div class="modal fade" id="editStockModal" tabindex="-1" aria-labelledby="editStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStockModalLabel">Update Stock Levels</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editStockForm">
                    <input type="hidden" id="editProductId">
                    <div class="mb-3">
                        <label for="editStockQuantity" class="form-label">Current Stock</label>
                        <input type="number" class="form-control" id="editStockQuantity" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="editMinStock" class="form-label">Minimum Stock Level</label>
                        <input type="number" class="form-control" id="editMinStock" required min="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveStock()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Restock Modal -->
<div class="modal fade" id="restockModal" tabindex="-1" aria-labelledby="restockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restockModalLabel">Restock Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="restockForm">
                    <input type="hidden" id="restockProductId">
                    <div class="mb-3">
                        <label for="restockQuantity" class="form-label">Quantity to Add</label>
                        <input type="number" class="form-control" id="restockQuantity" required min="1">
                        <div class="form-text">This amount will be added to the current stock.</div>
                    </div>
                    <div class="mb-3">
                        <label for="restockNote" class="form-label">Note (Optional)</label>
                        <input type="text" class="form-control" id="restockNote" placeholder="e.g., New shipment received">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitRestock()">Confirm Restock</button>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">Stock History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm" id="historyTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Change</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            <!-- Populated via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


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
            placeholder: "Search products...",
            noRows: "No entries found",
            info: "Showing {start} to {end} of {rows} entries"
        },
        layout: {
            top: "{select}{search}",
            bottom: "{info}{pager}"
        }
    });

    function filterTable() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;

        const rows = document.querySelectorAll('#table1 tbody tr');

        rows.forEach(row => {
            const productName = row.querySelector('td:nth-child(1) h6').innerText.toLowerCase();
            const brand = row.querySelector('td:nth-child(1) small').innerText.toLowerCase();
            const sku = row.querySelector('td:nth-child(2)').innerText.toLowerCase();
            const category = row.querySelector('td:nth-child(3)').innerText.toLowerCase();
            // We need to get the status code from a data attribute or class logic
            // Let's assume we can infer it or add a data attribute. 
            // For now, let's look at the badge text.
            const statusBadge = row.querySelector('td:nth-child(7) .badge').innerText.toLowerCase();
            
            let statusMatch = true;
            if (statusFilter === 'in_stock') statusMatch = statusBadge === 'in stock';
            else if (statusFilter === 'low_stock') statusMatch = statusBadge === 'low stock';
            else if (statusFilter === 'out_of_stock') statusMatch = statusBadge === 'out of stock';

            const searchMatch = productName.includes(searchInput) || brand.includes(searchInput) || sku.includes(searchInput);
            const categoryMatch = categoryFilter === '' || category === categoryFilter;

            if (searchMatch && categoryMatch && statusMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('categoryFilter').value = '';
        document.getElementById('statusFilter').value = '';
        filterTable();
    }

    function exportCSV() {
        let csv = [];
        const rows = document.querySelectorAll("table tr");
        
        for (let i = 0; i < rows.length; i++) {
            let row = [], cols = rows[i].querySelectorAll("td, th");
            
            for (let j = 0; j < cols.length - 1; j++) { // Exclude Actions column
                // Clean up text: remove newlines and extra spaces
                let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").replace(/\s+/g, " ").trim();
                row.push('"' + data + '"');
            }
            
            csv.push(row.join(","));
        }

        downloadCSV(csv.join("\n"), "inventory_report.csv");
    }

    function downloadCSV(csv, filename) {
        let csvFile;
        let downloadLink;

        csvFile = new Blob([csv], {type: "text/csv"});
        downloadLink = document.createElement("a");
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();
    }

    function updateStock(id, currentStock, minStock) {
        document.getElementById('editProductId').value = id;
        document.getElementById('editStockQuantity').value = currentStock;
        document.getElementById('editMinStock').value = minStock;
        
        var myModal = new bootstrap.Modal(document.getElementById('editStockModal'));
        myModal.show();
    }

    function saveStock() {
        const id = document.getElementById('editProductId').value;
        const stock = document.getElementById('editStockQuantity').value;
        const minStock = document.getElementById('editMinStock').value;

        const formData = new FormData();
        formData.append('product_id', id);
        formData.append('stock_quantity', stock);
        formData.append('min_stock_level', minStock);

        fetch('ajax/update_inventory.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to show updates (or update DOM directly)
                location.reload(); 
            } else {
                alert('Error updating stock: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating stock.');
        });
    }

    function viewHistory(id) {
        document.getElementById('historyTableBody').innerHTML = '<tr><td colspan="4" class="text-center">Loading...</td></tr>';
        var myModal = new bootstrap.Modal(document.getElementById('historyModal'));
        myModal.show();

        fetch('ajax/get_inventory_history.php?product_id=' + id)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('historyTableBody');
            tbody.innerHTML = '';
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No history found.</td></tr>';
                return;
            }

            data.forEach(log => {
                let badgeClass = 'bg-secondary';
                if (log.change_type === 'restock') badgeClass = 'bg-success';
                else if (log.change_type === 'sale') badgeClass = 'bg-primary';
                else if (log.change_type === 'adjustment') badgeClass = 'bg-warning text-dark';
                else if (log.change_type === 'return') badgeClass = 'bg-info';

                const row = `
                    <tr>
                        <td>${log.created_at}</td>
                        <td><span class="badge ${badgeClass}">${log.change_type.toUpperCase()}</span></td>
                        <td>${log.change_amount > 0 ? '+' + log.change_amount : log.change_amount}</td>
                        <td>${log.note || '-'}</td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('historyTableBody').innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error loading history.</td></tr>';
        });
    }

    function reorderStock(id) {
        document.getElementById('restockProductId').value = id;
        document.getElementById('restockQuantity').value = '';
        document.getElementById('restockNote').value = '';
        var myModal = new bootstrap.Modal(document.getElementById('restockModal'));
        myModal.show();
    }

    function submitRestock() {
        const id = document.getElementById('restockProductId').value;
        const quantity = document.getElementById('restockQuantity').value;
        const note = document.getElementById('restockNote').value;

        if (!quantity || quantity <= 0) {
            alert('Please enter a valid quantity.');
            return;
        }

        const formData = new FormData();
        formData.append('product_id', id);
        formData.append('quantity', quantity);
        formData.append('note', note);

        fetch('ajax/restock_product.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error restocking: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while restocking.');
        });
    }
</script>

<?php include 'includes/admin_footer.php'; ?>
        </div>
    </div>
</body>
</html>
