<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$page_title = "Review Management";

// Handle Filters
$filter_rating = $_GET['rating'] ?? 'all';
$filter_status = $_GET['status'] ?? 'all';

// Build Query
$query = "SELECT r.*, u.full_name, p.product_name, p.image_url 
          FROM product_reviews r 
          LEFT JOIN users u ON r.user_id = u.user_id 
          LEFT JOIN products p ON r.product_id = p.product_id 
          WHERE 1=1";

if ($filter_rating !== 'all') {
    if ($filter_rating == 'low') {
        $query .= " AND r.rating <= 3";
    } elseif ($filter_rating == 'high') {
        $query .= " AND r.rating >= 4";
    }
}

if ($filter_status !== 'all') {
    if ($filter_status == 'pending') {
        $query .= " AND r.admin_response IS NULL";
    } elseif ($filter_status == 'responded') {
        $query .= " AND r.admin_response IS NOT NULL";
    }
}

$query .= " ORDER BY r.created_at DESC";
$result = $conn->query($query);

// Fetch stats
$total_reviews = $conn->query("SELECT COUNT(*) as c FROM product_reviews")->fetch_assoc()['c'];
$pending_reviews = $conn->query("SELECT COUNT(*) as c FROM product_reviews WHERE admin_response IS NULL")->fetch_assoc()['c'];
$low_rated_reviews = $conn->query("SELECT COUNT(*) as c FROM product_reviews WHERE rating <= 3")->fetch_assoc()['c'];

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
    <link rel="stylesheet" href="source/assets/vendors/simple-datatables/style.css">
    <link rel="stylesheet" href="source/assets/css/app.css">
    <link rel="shortcut icon" href="source/assets/images/favicon.svg" type="image/x-icon">
    <style>
        .review-card-media img { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; margin-right: 5px; cursor: pointer; }
        .rating-stars { color: #ffc107; }
    </style>
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
                            <h3>Review Management</h3>
                            <p class="text-subtitle text-muted">Manage customer reviews and feedback</p>
                        </div>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="row">
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon purple"><i class="iconly-boldChat"></i></div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Total Reviews</h6>
                                        <h6 class="font-extrabold mb-0"><?php echo $total_reviews; ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon red"><i class="iconly-boldTime-Circle"></i></div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Pending Response</h6>
                                        <h6 class="font-extrabold mb-0"><?php echo $pending_reviews; ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon orange"><i class="iconly-boldStar"></i></div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Low Rated (<=3★)</h6>
                                        <h6 class="font-extrabold mb-0"><?php echo $low_rated_reviews; ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters & List -->
                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>Recent Reviews</h4>
                            <div>
                                <select class="form-select d-inline-block w-auto" onchange="window.location.href='?rating='+this.value">
                                    <option value="all" <?= $filter_rating=='all'?'selected':'' ?>>All Ratings</option>
                                    <option value="low" <?= $filter_rating=='low'?'selected':'' ?>>Low (1-3 Stars)</option>
                                    <option value="high" <?= $filter_rating=='high'?'selected':'' ?>>High (4-5 Stars)</option>
                                </select>
                                <select class="form-select d-inline-block w-auto" onchange="window.location.href='?status='+this.value">
                                    <option value="all" <?= $filter_status=='all'?'selected':'' ?>>All Status</option>
                                    <option value="pending" <?= $filter_status=='pending'?'selected':'' ?>>Pending Response</option>
                                    <option value="responded" <?= $filter_status=='responded'?'selected':'' ?>>Responded</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="table1">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>User</th>
                                            <th>Rating</th>
                                            <th>Review</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result && $result->num_rows > 0): ?>
                                            <?php while($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-md">
                                                                <?php 
                                                                    $img_path = $row['image_url'];
                                                                    if (!empty($img_path) && strpos($img_path, 'LaptopAdvisor/') === false) {
                                                                        $img_path = 'LaptopAdvisor/' . ltrim($img_path, '/');
                                                                    }
                                                                    $final_src = '../' . $img_path;
                                                                ?>
                                                                <img src="<?= htmlspecialchars($final_src) ?>" onerror="this.src='source/assets/images/faces/2.jpg'" style="object-fit: cover;">
                                                            </div>
                                                            <p class="font-bold ms-3 mb-0"><?= htmlspecialchars($row['product_name']) ?></p>
                                                        </div>
                                                    </td>
                                                    <td><?= htmlspecialchars($row['full_name'] ?? 'Guest') ?></td>
                                                    <td>
                                                        <span class="rating-stars"><?= str_repeat('★', $row['rating']) ?></span>
                                                    </td>
                                                    <td style="max-width:300px;">
                                                        <p class="text-truncate mb-0"><?= htmlspecialchars($row['review_text']) ?></p>
                                                    </td>
                                                    <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                                                    <td>
                                                        <?php if($row['admin_response']): ?>
                                                            <span class="badge bg-success">Responded</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning">Pending</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary" onclick="openReviewModal(<?= $row['review_id'] ?>)">
                                                            View & Respond
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="7" class="text-center">No reviews found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Review Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="reviewModalBody">
                    <div class="text-center py-5"><div class="spinner-border" role="status"></div></div>
                </div>
            </div>
        </div>
    </div>

    <script src="source/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="source/assets/js/bootstrap.bundle.min.js"></script>
    <script src="source/assets/js/main.js"></script>
    <script>
        function openReviewModal(reviewId) {
            var modal = new bootstrap.Modal(document.getElementById('reviewModal'));
            modal.show();
            
            fetch('ajax/get_review_details.php?review_id=' + reviewId + '&t=' + new Date().getTime())
                .then(response => response.text())
                .then(html => {
                    document.getElementById('reviewModalBody').innerHTML = html;
                });
        }

        // Handle Form Submission dynamically
        document.addEventListener('submit', function(e) {
            if (e.target && e.target.id === 'responseForm') {
                e.preventDefault();
                
                const form = e.target;
                const btn = form.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';
                
                const formData = new FormData(form);
                
                fetch('ajax/admin_respond_review.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Response posted successfully!');
                        location.reload(); // Reload to update status in table
                    } else {
                        alert('Error: ' + data.message);
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while posting the response.');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
            }
        });
    </script>
</body>
</html>
