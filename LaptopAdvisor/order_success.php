<?php
require_once 'includes/auth_check.php'; // Make sure user is logged in
include 'includes/header.php';

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id == 0) {
    // If no order ID is provided, just show a generic message
    echo "<div class='content-box'><p>Your order has been placed successfully. You can view it in your profile.</p></div>";
    include 'includes/footer.php';
    exit();
}

// Fetch the order to confirm it belongs to the logged-in user and display details
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $order = $result->fetch_assoc();
} else {
    // Order not found or doesn't belong to this user. Show a generic message for security.
    echo "<div class='content-box alert alert-danger'>Order not found or access denied.</div>";
    include 'includes/footer.php';
    exit();
}
$stmt->close();
?>

<div class="content-box text-center">
    <div style="font-size: 4rem; color: var(--accent-color);">✔</div>
    <h1>Thank You For Your Order!</h1>
    <p>Your order has been placed successfully and is now being processed.</p>
    <hr style="margin: 2rem 0;">
    <div class="order-summary">
        <p><strong>Order ID:</strong> #<?php echo htmlspecialchars($order['order_id']); ?></p>
        <p><strong>Order Total:</strong> <span class="currency-price" data-base-price="<?= $order['total_amount']; ?>">$<?php echo number_format($order['total_amount'], 2); ?></span></p>
    </div>
    <div style="margin-top: 2rem;">
        <a href="order_details.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-primary">View Order Details</a>
        <a href="products.php" class="btn">Continue Shopping</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>