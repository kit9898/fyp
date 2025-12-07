<?php
/**
 * Admin Coupons Page
 */

session_start();
require_once 'includes/db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Fetch all coupons
$query = "SELECT * FROM coupons ORDER BY coupon_id DESC";
$result = mysqli_query($conn, $query);
$coupons = mysqli_fetch_all($result, MYSQLI_ASSOC);

$page_title = "Manage Coupons";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Smart Laptop Advisor</title>
    
    <!-- CSS Files -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="source/assets/css/bootstrap.css">
    <link rel="stylesheet" href="source/assets/vendors/iconly/bold.css">
    <link rel="stylesheet" href="source/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="source/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="source/assets/css/app.css">
    <link rel="stylesheet" href="source/assets/vendors/simple-datatables/style.css">
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
                            <h3>Coupon Management</h3>
                            <p class="text-subtitle text-muted">Create and manage discount coupons</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Coupons</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">All Coupons</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCouponModal">
                                <i class="bi bi-plus-circle me-1"></i> Add Coupon
                            </button>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Value</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($coupons as $coupon): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-light-primary text-primary fw-bold" style="font-size: 1rem;">
                                                    <?php echo htmlspecialchars($coupon['code']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo ucfirst($coupon['discount_type']); ?></td>
                                            <td>
                                                <?php 
                                                if ($coupon['discount_type'] == 'percentage') {
                                                    echo $coupon['discount_value'] . '%';
                                                } else {
                                                    echo '$' . number_format($coupon['discount_value'], 2);
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                if ($coupon['expiry_date']) {
                                                    $expiry = new DateTime($coupon['expiry_date']);
                                                    $now = new DateTime();
                                                    $class = $expiry < $now ? 'text-danger' : 'text-success';
                                                    echo '<span class="'.$class.'">' . $expiry->format('Y-m-d') . '</span>';
                                                } else {
                                                    echo '<span class="text-muted">No Expiry</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                if ($coupon['is_active']) {
                                                    echo '<span class="badge bg-success">Active</span>';
                                                } else {
                                                    echo '<span class="badge bg-danger">Inactive</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                                        onclick="editCoupon(<?php echo $coupon['coupon_id']; ?>)" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteCoupon(<?php echo $coupon['coupon_id']; ?>, this)" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>

            <?php include 'includes/admin_footer.php'; ?>
        </div>
    </div>

    <!-- Add Coupon Modal -->
    <div class="modal fade" id="addCouponModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Coupon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addCouponForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" required style="text-transform: uppercase;">
                            <div class="form-text">Code will be automatically uppercase.</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Type</label>
                                <select class="form-select" name="discount_type">
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount ($)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Value <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="discount_value" step="0.01" min="0.01" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Expiry Date (Optional)</label>
                            <input type="date" class="form-control" name="expiry_date">
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="add_is_active" checked>
                                <label class="form-check-label" for="add_is_active">Active Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Coupon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Coupon Modal -->
    <div class="modal fade" id="editCouponModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Coupon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCouponForm">
                    <input type="hidden" name="coupon_id" id="edit_coupon_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" id="edit_code" required style="text-transform: uppercase;">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Type</label>
                                <select class="form-select" name="discount_type" id="edit_discount_type">
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount ($)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Value <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="discount_value" id="edit_discount_value" step="0.01" min="0.01" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" name="expiry_date" id="edit_expiry_date">
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_is_active">
                                <label class="form-check-label" for="edit_is_active">Active Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="source/assets/vendors/simple-datatables/simple-datatables.js"></script>
    <script>
        // Initialize DataTable
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);

        // Add Coupon
        document.getElementById('addCouponForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            // Handle checkbox manually if unticked (not sent by default, but we default to 1 anyway in backend check, 
            // but effectively for clearer intent we can ensure valid value)
            if (!this.querySelector('[name="is_active"]').checked) {
                formData.set('is_active', '0');
            } else {
                formData.set('is_active', '1');
            }

            fetch('ajax/add_coupon.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert('Coupon added successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('An error occurred.');
            });
        });

        // Edit Coupon (Fetch details)
        function editCoupon(id) {
            fetch(`ajax/get_coupon.php?id=${id}`)
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    const coupon = data.data;
                    document.getElementById('edit_coupon_id').value = coupon.coupon_id;
                    document.getElementById('edit_code').value = coupon.code;
                    document.getElementById('edit_discount_type').value = coupon.discount_type;
                    document.getElementById('edit_discount_value').value = coupon.discount_value;
                    document.getElementById('edit_expiry_date').value = coupon.expiry_date || '';
                    document.getElementById('edit_is_active').checked = coupon.is_active == 1;

                    new bootstrap.Modal(document.getElementById('editCouponModal')).show();
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('An error occurred loading coupon details.');
            });
        }

        // Edit Coupon (Submit)
        document.getElementById('editCouponForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            if (!this.querySelector('[name="is_active"]').checked) {
                formData.set('is_active', '0');
            } else {
                formData.set('is_active', '1');
            }

            fetch('ajax/edit_coupon.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert('Coupon updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('An error occurred.');
            });
        });

        // Delete Coupon
        function deleteCoupon(id, btn) {
            if(confirm('Are you sure that you want to delete this coupon?')) {
                let originalContent = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                fetch('ajax/delete_coupon.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({coupon_id: id})
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        alert('Coupon deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                        btn.disabled = false;
                        btn.innerHTML = originalContent;
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('An error occurred.');
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                });
            }
        }
    </script>
</body>
</html>
