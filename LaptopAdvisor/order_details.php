<?php
require_once 'includes/auth_check.php';
include 'includes/header.php';

// Validate order ID
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    header("Location: profile.php");
    exit();
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['user_id'];

// Fetch main order details, ensuring it belongs to the current user
$order_stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$order_stmt->bind_param("ii", $order_id, $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

if ($order_result->num_rows != 1) {
    // Order not found or doesn't belong to user
    echo "<div class='alert alert-danger'>Order not found or you do not have permission to view it.</div>";
    include 'includes/footer.php';
    exit();
}
$order = $order_result->fetch_assoc();
$order_stmt->close();

// Fetch all items associated with this order
$items = [];
$items_stmt = $conn->prepare(
    "SELECT oi.quantity, oi.price_at_purchase, p.product_name, p.image_url 
     FROM order_items oi 
     JOIN products p ON oi.product_id = p.product_id 
     WHERE oi.order_id = ?"
);
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();
while ($row = $items_result->fetch_assoc()) {
    $items[] = $row;
}
$items_stmt->close();
?>

<style>
.order-details-container {
    max-width: 1000px;
    margin: 0 auto;
}

.order-header-card {
    background: linear-gradient(135deg, var(--primary-color) 0%, #1e3a5f 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.order-header-card h1 {
    margin: 0 0 1rem 0;
    font-size: 1.8rem;
    font-weight: 600;
}

.order-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.order-meta-item {
    background: rgba(255,255,255,0.1);
    padding: 1rem;
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

.order-meta-item label {
    display: block;
    font-size: 0.85rem;
    opacity: 0.9;
    margin-bottom: 0.3rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.order-meta-item .value {
    font-size: 1.2rem;
    font-weight: 600;
}

.status-badge {
    display: inline-block;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    background: #4CAF50;
    color: white;
}

.status-badge.pending {
    background: #FF9800;
}

.status-badge.processing {
    background: #2196F3;
}

.status-badge.completed {
    background: #4CAF50;
}

.status-badge.cancelled {
    background: #f44336;
}

.two-column-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .two-column-layout {
        grid-template-columns: 1fr;
    }
}

.section-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.section-card h3 {
    margin: 0 0 1.5rem 0;
    font-size: 1.2rem;
    color: var(--primary-color);
    border-bottom: 2px solid var(--primary-color);
    padding-bottom: 0.5rem;
}

.order-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.order-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    margin-right: 1rem;
}

.item-details {
    flex: 1;
}

.item-name {
    font-weight: 600;
    margin-bottom: 0.3rem;
    color: #333;
}

.item-meta {
    font-size: 0.9rem;
    color: #666;
}

.item-price {
    text-align: right;
    font-weight: 600;
    color: var(--primary-color);
}

.address-info {
    line-height: 1.8;
}

.address-info p {
    margin: 0.5rem 0;
    display: flex;
    align-items: flex-start;
}

.address-info strong {
    min-width: 80px;
    color: #666;
    font-weight: 500;
}

.order-summary {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-top: 1.5rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.8rem 0;
    border-bottom: 1px solid #e0e0e0;
}

.summary-row:last-child {
    border-bottom: none;
    padding-top: 1rem;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--primary-color);
}

.action-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 2rem;
}

.pdf-button {
    background: var(--primary-color);
    color: white;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.pdf-button:hover {
    opacity: 0.9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style>

<div class="order-details-container">
    <!-- Order Header -->
    <div class="order-header-card">
        <h1>📋 Order #<?php echo htmlspecialchars($order['order_id']); ?></h1>
        <div class="order-meta">
            <div class="order-meta-item">
                <label>Order Date</label>
                <div class="value"><?php echo date("M j, Y", strtotime($order['order_date'])); ?></div>
                <div style="font-size: 0.85rem; opacity: 0.8; margin-top: 0.2rem;">
                    <?php echo date("g:i A", strtotime($order['order_date'])); ?>
                </div>
            </div>
            <div class="order-meta-item">
                <label>Status</label>
                <div class="value">
                    <span class="status-badge <?php echo strtolower($order['order_status']); ?>">
                        <?php echo htmlspecialchars($order['order_status']); ?>
                    </span>
                </div>
            </div>
            <div class="order-meta-item">
                <label>Total Amount</label>
                <div class="value currency-price" data-base-price="<?= $order['total_amount']; ?>">$<?php echo number_format($order['total_amount'], 2); ?></div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="two-column-layout">
        <!-- Left Column: Order Items -->
        <div>
            <div class="section-card">
                <h3>📦 Order Items</h3>
                <?php foreach ($items as $item): ?>
                    <div class="order-item">
                        <?php 
                        $image_src = 'https://via.placeholder.com/60';
                        if (!empty($item['image_url'])) {
                            $img_url = $item['image_url'];
                            if (strpos($img_url, 'http') === 0) {
                                $image_src = $img_url;
                            } elseif (strpos($img_url, 'LaptopAdvisor/') === 0) {
                                $image_src = '../' . $img_url;
                            } elseif (strpos($img_url, 'images/') === 0) {
                                $image_src = $img_url;
                            } else {
                                $image_src = 'images/' . basename($img_url);
                            }
                        }
                        ?>
                        <img src="<?php echo htmlspecialchars($image_src); ?>" 
                             alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                             class="item-image">
                        <div class="item-details">
                            <div class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                            <div class="item-meta">
                                Qty: <?php echo $item['quantity']; ?> × 
                                $<span class="currency-price" data-base-price="<?= $item['price_at_purchase']; ?>"><?php echo number_format($item['price_at_purchase'], 2); ?></span>
                            </div>
                        </div>
                        <div class="item-price">
                            <span class="currency-price" data-base-price="<?= $item['price_at_purchase'] * $item['quantity']; ?>">$<?php echo number_format($item['price_at_purchase'] * $item['quantity'], 2); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span class="currency-price" data-base-price="<?= $order['total_amount']; ?>">$<?php echo number_format($order['total_amount'], 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="summary-row">
                        <span>Total</span>
                        <span class="currency-price" data-base-price="<?= $order['total_amount']; ?>">$<?php echo number_format($order['total_amount'], 2); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Shipping Address -->
        <div>
            <div class="section-card">
                <h3>🚚 Shipping Address</h3>
                <div class="address-info">
                    <?php if (!empty($order['shipping_name'])): ?>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['shipping_name']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
                        <p><strong>City:</strong> <?php echo htmlspecialchars($order['shipping_city']); ?></p>
                        <p><strong>State:</strong> <?php echo htmlspecialchars($order['shipping_state']); ?></p>
                        <p><strong>ZIP:</strong> <?php echo htmlspecialchars($order['shipping_zip']); ?></p>
                        <p><strong>Country:</strong> <?php echo htmlspecialchars($order['shipping_country']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['shipping_phone']); ?></p>
                    <?php else: ?>
                        <p style="color: #999; font-style: italic;">
                            Shipping address not available for this order.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="generate_receipt_pdf.php?order_id=<?php echo $order_id; ?>" class="btn pdf-button">
            📄 Download PDF Receipt
        </a>
        <a href="profile.php" class="btn">
            ← Back to My Orders
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>