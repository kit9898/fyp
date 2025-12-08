<?php
require_once 'includes/auth_check.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];

// Fetch all user details
$user_stmt = $conn->prepare("SELECT full_name, email, profile_image_url FROM users WHERE user_id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$user_stmt->close();

// Fetch order history
$orders = [];
$order_stmt = $conn->prepare("SELECT order_id, total_amount, order_status, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$result = $order_stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$order_stmt->close();

// Calculate statistics
$total_orders = count($orders);
$total_spent = array_sum(array_column($orders, 'total_amount'));
$pending_orders = count(array_filter($orders, function($order) {
    return $order['order_status'] === 'Pending';
}));
?>

<style>
/* Enhanced Profile Page Styles */
.profile-page-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.profile-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 40px;
    margin-bottom: 30px;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.profile-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 15s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.1); opacity: 0.3; }
}

.profile-content {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 30px;
    align-items: center;
    position: relative;
    z-index: 1;
}

.profile-avatar-wrapper {
    position: relative;
}

.profile-avatar {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    border: 5px solid white;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    overflow: hidden;
    background: white;
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-info {
    color: white;
}

.profile-info h1 {
    margin: 0 0 10px 0;
    font-size: 2.5rem;
    font-weight: 700;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.profile-info .email {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1.1rem;
    opacity: 0.95;
    margin-bottom: 25px;
}

.profile-actions {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.profile-actions .btn {
    padding: 12px 28px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    border: none;
    cursor: pointer;
}

.profile-actions .btn-primary {
    background: white;
    color: #667eea;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.profile-actions .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
}

.profile-actions .btn-secondary {
    background: rgba(255,255,255,0.2);
    color: white;
    border: 2px solid white;
    backdrop-filter: blur(10px);
}

.profile-actions .btn-secondary:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-2px);
}

/* Statistics Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.stat-card-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.stat-info h3 {
    margin: 0;
    font-size: 0.875rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
}

/* Order History Section */
.orders-section {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.orders-section h2 {
    margin: 0 0 25px 0;
    font-size: 1.75rem;
    color: #1a1a1a;
    font-weight: 700;
}

.orders-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.orders-table thead {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.orders-table thead th {
    padding: 15px 20px;
    text-align: left;
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.orders-table thead th:first-child {
    border-radius: 12px 0 0 0;
}

.orders-table thead th:last-child {
    border-radius: 0 12px 0 0;
}

.orders-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f0f0f0;
}

.orders-table tbody tr:hover {
    background: #f8f9fa;
    transform: scale(1.01);
}

.orders-table tbody td {
    padding: 18px 20px;
    color: #495057;
}

.order-id {
    font-weight: 600;
    color: #667eea;
}

.order-status {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-processing {
    background: #cfe2ff;
    color: #084298;
}

.status-shipped {
    background: #d1e7dd;
    color: #0f5132;
}

.status-delivered {
    background: #d1e7dd;
    color: #0a3622;
}

.status-cancelled {
    background: #f8d7da;
    color: #842029;
}

.orders-table .btn {
    padding: 8px 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    display: inline-block;
}

.orders-table .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
}

.empty-state-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h3 {
    margin: 0 0 10px 0;
    color: #495057;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 25px;
}

.empty-state a {
    display: inline-block;
    padding: 12px 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.empty-state a:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-hero {
        padding: 30px 20px;
    }
    
    .profile-content {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 20px;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        margin: 0 auto;
    }
    
    .profile-info h1 {
        font-size: 2rem;
    }
    
    .profile-info .email {
        justify-content: center;
    }
    
    .profile-actions {
        justify-content: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .orders-section {
        padding: 20px;
        overflow-x: auto;
    }
    
    .orders-table {
        min-width: 600px;
    }
}
</style>

<div class="profile-page-wrapper">
    <!-- Profile Hero Section -->
    <div class="profile-hero">
        <div class="profile-content">
            <div class="profile-avatar-wrapper">
                <div class="profile-avatar">
                    <img src="<?php echo htmlspecialchars($user['profile_image_url']); ?>" alt="Profile Picture">
                </div>
            </div>
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($user['full_name']); ?></h1>
                <p class="email">
                    <span>📧</span>
                    <?php echo htmlspecialchars($user['email']); ?>
                </p>
                <div class="profile-actions">
                    <a href="edit_profile.php" class="btn btn-primary">
                        <span>✏️</span> Edit Profile
                    </a>
                    <a href="change_password.php" class="btn btn-secondary">
                        <span>🔒</span> Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Dashboard -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">📦</div>
                <div class="stat-info">
                    <h3>Total Orders</h3>
                </div>
            </div>
            <p class="stat-value"><?php echo $total_orders; ?></p>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">💰</div>
                <div class="stat-info">
                    <h3>Total Spent</h3>
                </div>
            </div>
            <p class="stat-value currency-price" data-base-price="<?= $total_spent; ?>">$<?php echo number_format($total_spent, 2); ?></p>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">⏳</div>
                <div class="stat-info">
                    <h3>Pending Orders</h3>
                </div>
            </div>
            <p class="stat-value"><?php echo $pending_orders; ?></p>
        </div>
    </div>

    <!-- Order History Section -->
    <div class="orders-section">
        <h2>📋 Order History</h2>
        <?php if (!empty($orders)): ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="order-id">#<?php echo $order['order_id']; ?></td>
                            <td><?php echo date("F j, Y", strtotime($order['order_date'])); ?></td>
                            <td><strong><span class="currency-price" data-base-price="<?= $order['total_amount']; ?>">$<?php echo number_format($order['total_amount'], 2); ?></span></strong></td>
                            <td>
                                <?php 
                                $status = strtolower($order['order_status']);
                                $statusClass = 'status-' . str_replace(' ', '-', $status);
                                ?>
                                <span class="order-status <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($order['order_status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="order_details.php?order_id=<?php echo $order['order_id']; ?>" class="btn">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">🛒</div>
                <h3>No Orders Yet</h3>
                <p>You haven't placed any orders yet. Start shopping to see your order history here!</p>
                <a href="products.php">Browse Products</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>