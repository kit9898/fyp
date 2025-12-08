<?php
require_once 'includes/auth_check.php';
include 'includes/header.php';

// Initialize variables
$cart = $_SESSION['cart'] ?? [];
$cart_products = [];
$sub_total = 0;
$discount_amount = 0;
$temp_voucher_discount = 0;
$grand_total = 0;
$coupon_error = '';
$coupon_success = '';

// Fetch temp vouchers for the logged-in user
$temp_vouchers = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    $voucher_stmt = $conn->prepare("
        SELECT 
            tv.voucher_id,
            tv.voucher_code, 
            tv.discount_type, 
            tv.discount_value,
            tv.product_id,
            tv.expires_at,
            p.product_name, 
            p.brand
        FROM temp_vouchers tv
        JOIN products p ON tv.product_id = p.product_id
        WHERE tv.user_id = ? 
        AND tv.used = 0
        AND (tv.expires_at IS NULL OR tv.expires_at > NOW())
        ORDER BY tv.created_at DESC
    ");
    
    if ($voucher_stmt) {
        $voucher_stmt->bind_param("i", $user_id);
        $voucher_stmt->execute();
        $voucher_result = $voucher_stmt->get_result();
        
        while ($voucher_row = $voucher_result->fetch_assoc()) {
            $temp_vouchers[] = $voucher_row;
        }
        $voucher_stmt->close();
    }
}

// Handle temp voucher application
if (isset($_POST['apply_temp_voucher']) && isset($_POST['voucher_id'])) {
    $voucher_id = intval($_POST['voucher_id']);
    
    // Fetch the voucher details
    $stmt = $conn->prepare("
        SELECT tv.*, p.product_name, p.brand 
        FROM temp_vouchers tv
        JOIN products p ON tv.product_id = p.product_id
        WHERE tv.voucher_id = ? AND tv.user_id = ? AND tv.used = 0
        AND (tv.expires_at IS NULL OR tv.expires_at > NOW())
    ");
    $stmt->bind_param("ii", $voucher_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($voucher = $result->fetch_assoc()) {
        $_SESSION['temp_voucher'] = [
            'voucher_id' => $voucher['voucher_id'],
            'voucher_code' => $voucher['voucher_code'],
            'discount_type' => $voucher['discount_type'],
            'discount_value' => $voucher['discount_value'],
            'product_id' => $voucher['product_id'],
            'product_name' => $voucher['product_name'],
            'brand' => $voucher['brand']
        ];
        $coupon_success = "Reward voucher applied successfully!";
    } else {
        $coupon_error = "Invalid or expired voucher.";
    }
    $stmt->close();
}

// Handle temp voucher removal
if (isset($_POST['remove_temp_voucher'])) {
    unset($_SESSION['temp_voucher']);
}

// Handle regular coupon application
if (isset($_POST['apply_coupon']) && isset($_POST['coupon_code'])) {
    $coupon_code = trim($_POST['coupon_code']);
    
    if (!empty($coupon_code)) {
        // Updated to use 'code' and 'expiry_date' per database schema
        $stmt = $conn->prepare("SELECT * FROM coupons WHERE code = ? AND is_active = 1");
        $stmt->bind_param("s", $coupon_code);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($coupon = $result->fetch_assoc()) {
            $now = date('Y-m-d H:i:s');
            $expiry = $coupon['expiry_date'];
            
            // Check if expired (if expiry date is set)
            if ($expiry === NULL || $now <= $expiry) {
                $_SESSION['coupon'] = [
                    'code' => $coupon['code'], // Mapped from DB 'code'
                    'discount_type' => $coupon['discount_type'],
                    'discount_value' => $coupon['discount_value']
                ];
                $coupon_success = "Coupon applied successfully!";
            } else {
                $coupon_error = "This coupon has expired (Expiry: $expiry).";
            }
        } else {
            $coupon_error = "Invalid or inactive coupon code.";
        }
        $stmt->close();
    }
}

// Handle regular coupon removal
if (isset($_POST['remove_coupon'])) {
    unset($_SESSION['coupon']);
}

// Fetch cart products from database
if (!empty($cart)) {
    $product_ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $types = str_repeat('i', count($product_ids));
    
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id IN ($placeholders)");
    $stmt->bind_param($types, ...$product_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $row['quantity'] = $cart[$product_id];
        $cart_products[] = $row;
        $sub_total += $row['price'] * $row['quantity'];
    }
    $stmt->close();
}

// Calculate totals
$grand_total = $sub_total;

// Apply regular coupon discount
if (isset($_SESSION['coupon'])) {
    $coupon = $_SESSION['coupon'];
    if ($coupon['discount_type'] == 'percentage') {
        $discount_amount = $sub_total * ($coupon['discount_value'] / 100);
    } elseif ($coupon['discount_type'] == 'fixed') {
        $discount_amount = $coupon['discount_value'];
    }
    $grand_total -= $discount_amount;
    if ($grand_total < 0) $grand_total = 0;
}

// Apply temp voucher discount (only to matching product)
if (isset($_SESSION['temp_voucher'])) {
    $temp_voucher = $_SESSION['temp_voucher'];
    $voucher_product_id = isset($temp_voucher['product_id']) ? intval($temp_voucher['product_id']) : 0;
    
    // Find the matching product in cart
    foreach ($cart_products as $product) {
        if ($product['product_id'] == $voucher_product_id) {
            $line_total = $product['price'] * $product['quantity'];
            
            if ($temp_voucher['discount_type'] == 'percentage') {
                $temp_voucher_discount = $line_total * ($temp_voucher['discount_value'] / 100);
            } elseif ($temp_voucher['discount_type'] == 'fixed') {
                $temp_voucher_discount = min($temp_voucher['discount_value'], $line_total);
            }
            break;
        }
    }
    
    $grand_total -= $temp_voucher_discount;
    if ($grand_total < 0) $grand_total = 0;
}
?>

<style>
:root {
    --primary-color: #2c3e50;
}

.cart-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.cart-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #1e3a5f 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.cart-header h1 {
    margin: 0;
    font-size: 2rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.cart-badge {
    background: rgba(255,255,255,0.2);
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.cart-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 2rem;
}

@media (max-width: 968px) {
    .cart-layout {
        grid-template-columns: 1fr;
    }
}

.cart-items-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    overflow: hidden;
}

.cart-item {
    display: grid;
    grid-template-columns: 100px 1fr auto;
    gap: 1.5rem;
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s;
}

.cart-item:hover {
    background: #f8f9fa;
}

.cart-item:last-child {
    border-bottom: none;
}

.item-image-wrapper {
    position: relative;
}

.item-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #f0f0f0;
}

.item-details {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.item-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.item-brand {
    font-size: 0.9rem;
    color: #7f8c8d;
    margin-bottom: 0.5rem;
}

.item-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary-color);
}

.item-actions {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-end;
}

.quantity-control {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #f8f9fa;
    padding: 0.5rem;
    border-radius: 8px;
}

.quantity-btn {
    background: white;
    border: 1px solid #dee2e6;
    width: 30px;
    height: 30px;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    transition: all 0.2s;
}

.quantity-btn:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.quantity-input {
    width: 50px;
    text-align: center;
    border: 1px solid #dee2e6;
    padding: 0.4rem;
    border-radius: 4px;
    font-weight: 600;
}

.remove-btn {
    background: transparent;
    border: 1px solid #dc3545;
    color: #dc3545;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.remove-btn:hover {
    background: #dc3545;
    color: white;
}

.cart-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.coupon-card, .summary-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.coupon-input-wrapper {
    display: flex;
    gap: 0.5rem;
}

.coupon-input {
    flex: 1;
    padding: 0.8rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: border-color 0.2s;
}

.coupon-input:focus {
    outline: none;
    border-color: var(--primary-color);
}

.apply-btn {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.apply-btn:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

.alert {
    padding: 0.8rem;
    border-radius: 6px;
    margin-top: 0.8rem;
    font-size: 0.9rem;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.8rem 0;
    font-size: 0.95rem;
}

.summary-row.discount {
    color: #28a745;
    font-weight: 600;
    position: relative;
}

.summary-row.temp-voucher {
    color: #10b981;
    font-weight: 600;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    padding: 0.8rem;
    border-radius: 6px;
    margin: 0.5rem 0;
}

.remove-coupon-btn {
    background: transparent;
    border: none;
    color: #dc3545;
    cursor: pointer;
    font-size: 1.2rem;
    margin-left: 0.5rem;
    padding: 0;
    line-height: 1;
}

.summary-divider {
    height: 1px;
    background: #dee2e6;
    margin: 1rem 0;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    padding: 1rem 0;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--primary-color);
}

.checkout-btn {
    width: 100%;
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 1rem;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    margin-top: 1rem;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-decoration: none;
}

.checkout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.2);
}

.empty-cart {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.empty-cart-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.3;
}

.empty-cart h3 {
    margin-bottom: 1rem;
    color: #2c3e50;
}

.continue-shopping {
    display: inline-block;
    margin-top: 1.5rem;
    padding: 0.8rem 2rem;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s;
}

.continue-shopping:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.savings-badge {
    background: #d4edda;
    color: #155724;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-block;
    margin-top: 0.5rem;
}

.temp-voucher-item {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 2px solid #10b981;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 0.8rem;
    cursor: pointer;
    transition: all 0.2s;
    width: 100%;
    text-align: left;
}

.temp-voucher-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}

.temp-voucher-code {
    font-family: monospace;
    font-weight: bold;
    color: #059669;
    font-size: 0.9rem;
    margin-bottom: 0.3rem;
}

.temp-voucher-details {
    font-size: 0.75rem;
    color: #065f46;
}
</style>

<div class="cart-container">
    <!-- Cart Header -->
    <div class="cart-header">
        <h1>
            🛒 Shopping Cart
            <?php if (count($cart_products) > 0): ?>
                <span class="cart-badge"><?php echo count($cart_products); ?> <?php echo count($cart_products) == 1 ? 'Item' : 'Items'; ?></span>
            <?php endif; ?>
        </h1>
    </div>

    <?php if (!empty($cart_products)): ?>
        <div class="cart-layout">
            <!-- Cart Items Section -->
            <div class="cart-items-section">
                <?php foreach ($cart_products as $product): ?>
                    <div class="cart-item">
                        <div class="item-image-wrapper">
                            <?php 
                            $image_src = 'https://via.placeholder.com/100';
                            if (!empty($product['image_url'])) {
                                $img_url = $product['image_url'];
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
                                 alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                                 class="item-image">
                        </div>
                        
                        <div class="item-details">
                            <div>
                                <div class="item-name"><?php echo htmlspecialchars($product['product_name']); ?></div>
                                <div class="item-brand"><?php echo htmlspecialchars($product['brand'] ?? 'LaptopAdvisor'); ?></div>
                            </div>
                            <div class="item-price currency-price" data-base-price="<?= $product['price']; ?>">$<?php echo number_format($product['price'], 2); ?></div>
                        </div>
                        
                        <div class="item-actions">
                            <form action="cart_process.php" method="post" id="qty-form-<?php echo $product['product_id']; ?>">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                <div class="quantity-control">
                                    <button type="button" class="quantity-btn" onclick="changeQuantity(<?php echo $product['product_id']; ?>, -1)">−</button>
                                    <input type="number" 
                                           name="quantity" 
                                           value="<?php echo $product['quantity']; ?>" 
                                           min="1" 
                                           max="10" 
                                           class="quantity-input"
                                           id="qty-<?php echo $product['product_id']; ?>"
                                           onchange="this.form.submit()">
                                    <button type="button" class="quantity-btn" onclick="changeQuantity(<?php echo $product['product_id']; ?>, 1)">+</button>
                                </div>
                            </form>
                            
                            <form action="cart_process.php" method="post">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                <button type="submit" class="remove-btn">🗑️ Remove</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Sidebar -->
            <div class="cart-sidebar">
                <!-- Temp Vouchers Card -->
                <?php if (!empty($temp_vouchers)): ?>
                <div class="coupon-card">
                    <div class="card-title">🎁 Your Reward Vouchers</div>
                    <?php foreach ($temp_vouchers as $voucher): ?>
                        <form action="cart.php" method="post">
                            <input type="hidden" name="voucher_id" value="<?php echo $voucher['voucher_id']; ?>">
                            <button type="submit" name="apply_temp_voucher" class="temp-voucher-item">
                                <div class="temp-voucher-code">
                                    🎟️ <?php echo htmlspecialchars($voucher['voucher_code']); ?> 
                                    <span style="float: right; color: #059669;"><?php echo $voucher['discount_value']; ?>% OFF</span>
                                </div>
                                <div class="temp-voucher-details">
                                    <?php echo htmlspecialchars($voucher['brand']) . ' ' . htmlspecialchars($voucher['product_name']); ?>
                                </div>
                            </button>
                        </form>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <!-- Regular Coupon Card -->
                <div class="coupon-card">
                    <div class="card-title">🎟️ Have a Coupon?</div>
                    <form action="cart.php" method="post">
                        <div class="coupon-input-wrapper">
                            <input type="text" 
                                   name="coupon_code" 
                                   placeholder="Enter code" 
                                   class="coupon-input">
                            <button type="submit" name="apply_coupon" class="apply-btn">Apply</button>
                        </div>
                    </form>
                    <?php if ($coupon_error): ?>
                        <div class="alert alert-danger"><?php echo $coupon_error; ?></div>
                    <?php endif; ?>
                    <?php if ($coupon_success): ?>
                        <div class="alert alert-success"><?php echo $coupon_success; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Summary Card -->
                <div class="summary-card">
                    <div class="card-title">📊 Order Summary</div>
                    
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span class="currency-price" data-base-price="<?= $sub_total; ?>">$<?php echo number_format($sub_total, 2); ?></span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span style="color: #28a745; font-weight: 600;">FREE</span>
                    </div>
                    
                    <?php if (isset($_SESSION['coupon'])): ?>
                        <div class="summary-row discount">
                            <span>
                                Discount (<?php echo htmlspecialchars($_SESSION['coupon']['code']); ?>)
                                <form action="cart.php" method="post" style="display:inline;">
                                    <button type="submit" name="remove_coupon" class="remove-coupon-btn">×</button>
                                </form>
                            </span>
                            <span class="currency-price" data-base-price="<?= -$discount_amount; ?>">-$<?php echo number_format($discount_amount, 2); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['temp_voucher'])): ?>
                        <div class="summary-row temp-voucher">
                            <span>
                                🎁 Reward Voucher (<?php echo htmlspecialchars($_SESSION['temp_voucher']['voucher_code']); ?>)
                                <form action="cart.php" method="post" style="display:inline;">
                                    <button type="submit" name="remove_temp_voucher" class="remove-coupon-btn">×</button>
                                </form>
                            </span>
                            <span class="currency-price" data-base-price="<?= -$temp_voucher_discount; ?>">-$<?php echo number_format($temp_voucher_discount, 2); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($discount_amount > 0 || $temp_voucher_discount > 0): ?>
                        <span class="savings-badge">🎉 You're saving $<?php echo number_format($discount_amount + $temp_voucher_discount, 2); ?>!</span>
                    <?php endif; ?>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="summary-total">
                        <span>Total:</span>
                        <span class="currency-price" data-base-price="<?= $grand_total; ?>">$<?php echo number_format($grand_total, 2); ?></span>
                    </div>
                    
                    <a href="checkout.php" class="checkout-btn">
                        Proceed to Checkout →
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="empty-cart">
            <div class="empty-cart-icon">🛒</div>
            <h3>Your cart is empty</h3>
            <p>Looks like you haven't added any laptops to your cart yet.</p>
            <a href="products.php" class="continue-shopping">Browse Laptops</a>
        </div>
    <?php endif; ?>
</div>

<script>
function changeQuantity(productId, change) {
    const input = document.getElementById('qty-' + productId);
    let newVal = parseInt(input.value) + change;
    
    if (newVal >= 1 && newVal <= 10) {
        input.value = newVal;
        document.getElementById('qty-form-' + productId).submit();
    }
}
</script>

<?php include 'includes/footer.php'; ?>