<?php
require_once 'includes/auth_check.php';
include 'includes/header.php';

// --- Calculate Final Cart Total ---
// We do this again here to ensure the data is accurate before payment.
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    // If cart is empty, redirect back to cart page. No checkout needed.
    header("Location: cart.php");
    exit();
}

$sub_total = 0;
$product_ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));
$types = str_repeat('i', count($product_ids));
$sql = "SELECT product_id, price FROM products WHERE product_id IN ($placeholders)";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$product_ids);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $sub_total += $row['price'] * $cart[$row['product_id']];
}
$stmt->close();

// Apply coupon discount
$discount_amount = 0;
$grand_total = $sub_total;
if (isset($_SESSION['coupon'])) {
    $coupon = $_SESSION['coupon'];
    if ($coupon['discount_type'] == 'percentage') {
        $discount_amount = $sub_total * ($coupon['discount_value'] / 100);
    } elseif ($coupon['discount_type'] == 'fixed') {
        $discount_amount = $coupon['discount_value'];
    }
    $grand_total = $sub_total - $discount_amount;
    if ($grand_total < 0) $grand_total = 0;
}

// --- Auto-fill shipping address: Priority order ---
// 1. User's default shipping address from profile (if set)
// 2. Fallback: Last order address (if exists)
// 3. Empty fields for new users
$user_id = $_SESSION['user_id'];
$default_address = [
    'shipping_name' => '',
    'shipping_address' => '',
    'shipping_city' => '',
    'shipping_state' => '',
    'shipping_zip' => '',
    'shipping_country' => '',
    'shipping_phone' => ''
];
$address_source = ''; // Track where address came from for user notification

// PRIORITY 1: Try to get default shipping address from user profile
$user_stmt = $conn->prepare("SELECT default_shipping_name, default_shipping_address, default_shipping_city, 
                                     default_shipping_state, default_shipping_zip, default_shipping_country, 
                                     default_shipping_phone 
                              FROM users 
                              WHERE user_id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
if ($user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
    // Check if user has actually set a default address (not just empty fields)
    if (!empty($user_data['default_shipping_name']) && !empty($user_data['default_shipping_address'])) {
        // Map the default_shipping_* fields to shipping_* format for form compatibility
        $default_address = [
            'shipping_name' => $user_data['default_shipping_name'],
            'shipping_address' => $user_data['default_shipping_address'],
            'shipping_city' => $user_data['default_shipping_city'],
            'shipping_state' => $user_data['default_shipping_state'],
            'shipping_zip' => $user_data['default_shipping_zip'],
            'shipping_country' => $user_data['default_shipping_country'],
            'shipping_phone' => $user_data['default_shipping_phone']
        ];
        $address_source = 'default';
    }
}
$user_stmt->close();

// PRIORITY 2: If no default address, fallback to last order address
if (empty($default_address['shipping_name'])) {
    $order_stmt = $conn->prepare("SELECT shipping_name, shipping_address, shipping_city, shipping_state, 
                                          shipping_zip, shipping_country, shipping_phone 
                                   FROM orders 
                                   WHERE user_id = ? AND shipping_name IS NOT NULL 
                                   ORDER BY order_date DESC 
                                   LIMIT 1");
    $order_stmt->bind_param("i", $user_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();
    if ($order_result->num_rows > 0) {
        $default_address = $order_result->fetch_assoc();
        $address_source = 'last_order';
    }
    $order_stmt->close();
}
?>

<h2>Checkout</h2>

<?php if ($address_source == 'default'): ?>
    <div class="alert alert-info" style="max-width: 800px; margin: 0 auto 1rem auto;">
        ✓ <strong>Using your saved default address.</strong> Feel free to update if needed.
    </div>
<?php elseif ($address_source == 'last_order'): ?>
    <div class="alert alert-info" style="max-width: 800px; margin: 0 auto 1rem auto;">
        ℹ️ <strong>Using address from your last order.</strong> You can save a default address in your <a href="edit_profile.php">profile settings</a>.
    </div>
<?php endif; ?>

<div class="checkout-container">
    <!-- Order Summary Column -->
    <div class="order-summary-box content-box">
        <h3>Order Summary</h3>
        <div class="total-row">
            <span>Subtotal:</span>
            <span class="currency-price" data-base-price="<?= $sub_total; ?>">$<?php echo number_format($sub_total, 2); ?></span>
        </div>
        <?php if (isset($_SESSION['coupon'])): ?>
            <div class="total-row discount">
                <span>Discount (<?php echo htmlspecialchars($_SESSION['coupon']['code']); ?>):</span>
                <span class="currency-price" data-base-price="<?= -$discount_amount; ?>">-$<?php echo number_format($discount_amount, 2); ?></span>
            </div>
        <?php endif; ?>
        <hr>
        <div class="total-row grand-total">
            <span>Total to Pay:</span>
            <span class="currency-price" data-base-price="<?= $grand_total; ?>">$<?php echo number_format($grand_total, 2); ?></span>
        </div>
    </div>

    <!-- Shipping & Payment Form -->
    <div class="payment-details-box content-box">
        <form action="bank_simulation.php" method="post" id="checkoutForm">
            <input type="hidden" name="action" value="checkout">
            <input type="hidden" name="payment_method" value="credit_card">
            
            <!-- Shipping Address Section -->
            <h3>📦 Shipping Address</h3>
            <div class="form-group">
                <label for="shipping_name">Full Name *</label>
                <input type="text" id="shipping_name" name="shipping_name" placeholder="John Doe" 
                       value="<?php echo htmlspecialchars($default_address['shipping_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="shipping_address">Street Address *</label>
                <input type="text" id="shipping_address" name="shipping_address" placeholder="123 Main Street, Apt 4B" 
                       value="<?php echo htmlspecialchars($default_address['shipping_address']); ?>" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="shipping_city">City *</label>
                    <input type="text" id="shipping_city" name="shipping_city" placeholder="New York" 
                           value="<?php echo htmlspecialchars($default_address['shipping_city']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="shipping_state">State/Province *</label>
                    <input type="text" id="shipping_state" name="shipping_state" placeholder="NY" 
                           value="<?php echo htmlspecialchars($default_address['shipping_state']); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="shipping_zip">ZIP/Postal Code *</label>
                    <input type="text" id="shipping_zip" name="shipping_zip" placeholder="10001" 
                           value="<?php echo htmlspecialchars($default_address['shipping_zip']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="shipping_country">Country *</label>
                    <input type="text" id="shipping_country" name="shipping_country" placeholder="USA" 
                           value="<?php echo htmlspecialchars($default_address['shipping_country']); ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="shipping_phone">Phone Number *</label>
                <input type="tel" id="shipping_phone" name="shipping_phone" placeholder="+1 (555) 123-4567" 
                       value="<?php echo htmlspecialchars($default_address['shipping_phone']); ?>" required>
            </div>
            
            <hr style="margin: 2rem 0;">
            
            <!-- Payment Details Section -->
            <h3>💳 Payment Details</h3>
            <p style="font-size: 0.9em; color: #6c757d; margin-bottom: 1.5rem;">
                <strong>Note:</strong> This is a simulated payment form. Please do not enter real credit card details.
            </p>
            
            <div class="form-group">
                <label>Payment Method</label>
                <div class="payment-method-selector">
                    <label class="payment-option selected">
                        <input type="radio" name="payment_method_display" value="credit_card" checked disabled>
                        <span class="payment-icon">💳</span>
                        <span>Credit Card</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="cardholder_name">Cardholder Name *</label>
                <input type="text" id="cardholder_name" name="cardholder_name" 
                       placeholder="John Doe" value="Test User" required>
            </div>
            
            <div class="form-group">
                <label for="card_number">Card Number *</label>
                <input type="text" id="card_number" name="card_number" 
                       placeholder="1234 5678 9012 3456" 
                       value="4242424242424242" 
                       maxlength="19" 
                       required>
                <small style="color: #6c757d;">Test card: 4242 4242 4242 4242</small>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="expiry_date">Expiry Date *</label>
                    <input type="text" id="expiry_date" name="expiry_date" 
                           placeholder="MM/YY" 
                           value="12/26" 
                           maxlength="5" 
                           required>
                </div>
                <div class="form-group">
                    <label for="cvc">CVC *</label>
                    <input type="text" id="cvc" name="cvc" 
                           placeholder="123" 
                           value="123" 
                           maxlength="4" 
                           required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 1.5rem;">
                🔒 Pay <span class="currency-price" data-base-price="<?= $grand_total; ?>">$<?php echo number_format($grand_total, 2); ?></span>
            </button>
        </form>
    </div>
</div>

<script>
// Card number formatting
document.getElementById('card_number').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    e.target.value = formattedValue;
});

// Expiry date formatting
document.getElementById('expiry_date').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
    }
    e.target.value = value;
});

// Form validation
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
    const expiryDate = document.getElementById('expiry_date').value;
    const cvc = document.getElementById('cvc').value;
    
    // Validate card number (basic check)
    if (cardNumber.length < 13 || cardNumber.length > 19) {
        e.preventDefault();
        alert('Please enter a valid card number');
        return false;
    }
    
    // Validate expiry date
    if (!/^\d{2}\/\d{2}$/.test(expiryDate)) {
        e.preventDefault();
        alert('Please enter expiry date in MM/YY format');
        return false;
    }
    
    // Validate CVC
    if (cvc.length < 3 || cvc.length > 4) {
        e.preventDefault();
        alert('Please enter a valid CVC (3-4 digits)');
        return false;
    }
    
    return true;
});
</script>

<?php include 'includes/footer.php'; ?>