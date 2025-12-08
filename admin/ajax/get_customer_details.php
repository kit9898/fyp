<?php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<div class="alert alert-danger">Invalid user ID</div>';
    exit();
}

$user_id = intval($_GET['id']);

// Fetch user details
$user_query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows === 0) {
    echo '<div class="alert alert-danger">User not found</div>';
    exit();
}

$user = $user_result->fetch_assoc();
$stmt->close();

// Fetch user stats
$stats_query = "SELECT 
    COUNT(order_id) as total_orders,
    COALESCE(SUM(CASE WHEN order_status != 'Cancelled' AND order_status != 'Failed' THEN total_amount ELSE 0 END), 0) as total_spent
FROM orders 
WHERE user_id = ?";
$stmt = $conn->prepare($stats_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stats_result = $stmt->get_result();
$stats = $stats_result->fetch_assoc();
$stmt->close();

// Fetch recent orders
$orders_query = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 5";
$stmt = $conn->prepare($orders_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();
?>

<div class="row mb-4">
    <div class="col-md-4 text-center">
        <?php 
        $profile_img = '';
        if (!empty($user['profile_image_url'])) {
            // Check if path already has ../
            if (strpos($user['profile_image_url'], '../') === 0) {
                $profile_img = $user['profile_image_url'];
            } else {
                $profile_img = '../LaptopAdvisor/' . $user['profile_image_url'];
            }
        }
        
        if (!empty($profile_img) && file_exists('../../' . str_replace('../', '', $profile_img))): 
        ?>
            <img src="<?php echo htmlspecialchars($profile_img); ?>" 
                 alt="Profile" class="rounded-circle img-thumbnail mb-3" 
                 style="width: 120px; height: 120px; object-fit: cover;">
        <?php else: ?>
            <div class="avatar avatar-xl bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                 style="width: 120px; height: 120px; font-size: 3rem;">
                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
            </div>
        <?php endif; ?>
        
        <h4><?php echo htmlspecialchars($user['full_name']); ?></h4>
        <p class="text-muted mb-1">#USR-<?php echo str_pad($user['user_id'], 4, '0', STR_PAD_LEFT); ?></p>
        <span class="badge bg-<?php echo $user['status'] === 'active' ? 'success' : 'secondary'; ?>">
            <?php echo ucfirst($user['status']); ?>
        </span>
    </div>
    
    <div class="col-md-8">
        <h5 class="border-bottom pb-2 mb-3">Contact Information</h5>
        <div class="row mb-3">
            <div class="col-sm-4 text-muted">Email</div>
            <div class="col-sm-8 fw-bold"><?php echo htmlspecialchars($user['email']); ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4 text-muted">Phone</div>
            <div class="col-sm-8"><?php echo htmlspecialchars($user['default_shipping_phone'] ?? 'N/A'); ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4 text-muted">Location</div>
            <div class="col-sm-8">
                <?php 
                $location = [];
                if (!empty($user['default_shipping_city'])) $location[] = $user['default_shipping_city'];
                if (!empty($user['default_shipping_state'])) $location[] = $user['default_shipping_state'];
                if (!empty($user['default_shipping_country'])) $location[] = $user['default_shipping_country'];
                echo !empty($location) ? implode(', ', $location) : 'N/A';
                ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4 text-muted">Joined Date</div>
            <div class="col-sm-8"><?php echo date('F d, Y', strtotime($user['created_at'])); ?></div>
        </div>
        
        <h5 class="border-bottom pb-2 mb-3 mt-4">Statistics</h5>
        <div class="row g-3">
            <div class="col-6">
                <div class="p-3 border rounded bg-light text-center">
                    <h3 class="text-primary mb-0"><?php echo $stats['total_orders']; ?></h3>
                    <small class="text-muted">Total Orders</small>
                </div>
            </div>
            <div class="col-6">
                <div class="p-3 border rounded bg-light text-center">
                    <h3 class="text-success mb-0 currency-price" data-base-price="<?= $stats['total_spent']; ?>">$<?php echo number_format($stats['total_spent'], 2); ?></h3>
                    <small class="text-muted">Total Spent</small>
                </div>
            </div>
        </div>
    </div>
</div>

<h5 class="border-bottom pb-2 mb-3">Recent Orders</h5>
<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Status</th>
                <th class="text-end">Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($orders_result->num_rows > 0): ?>
                <?php while ($order = $orders_result->fetch_assoc()): ?>
                    <tr>
                        <td>#ORD-<?php echo str_pad($order['order_id'], 4, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                        <td>
                            <span class="badge bg-<?php 
                                echo match(strtolower($order['order_status'])) {
                                    'completed', 'delivered' => 'success',
                                    'pending' => 'warning',
                                    'processing', 'shipped' => 'primary',
                                    'cancelled', 'failed' => 'danger',
                                    default => 'secondary'
                                };
                            ?>">
                                <?php echo ucfirst($order['order_status']); ?>
                            </span>
                        </td>
                        <td class="text-end"><span class="currency-price" data-base-price="<?= $order['total_amount']; ?>">$<?php echo number_format($order['total_amount'], 2); ?></span></td>
                        <td>
                            <a href="admin_orders.php?search=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">No orders found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
