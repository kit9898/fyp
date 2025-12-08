<?php
session_start();
// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ensure we have POST data
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action']) || $_POST['action'] !== 'checkout') {
    header("Location: checkout.php");
    exit();
}

// Retrieve form data to preserve it
$shipping_name = $_POST['shipping_name'] ?? '';
$shipping_address = $_POST['shipping_address'] ?? '';
$shipping_city = $_POST['shipping_city'] ?? '';
$shipping_state = $_POST['shipping_state'] ?? '';
$shipping_zip = $_POST['shipping_zip'] ?? '';
$shipping_country = $_POST['shipping_country'] ?? '';
$shipping_phone = $_POST['shipping_phone'] ?? '';
$payment_method = $_POST['payment_method'] ?? 'credit_card';

// We don't store or pass card info for security (even simulated), 
// but we might display the last 4 digits for realism if we had them.
// Since this is a transition page, valid data is presumed from checkout.php validation.

// Calculate total again purely for display (or accept it passed, but better to recalc or read session)
// Actually, let's just use what's in SESSION['cart'] like logic in checkout.php for consistency
// Or just show a generic "Processing Payment" if we want to avoid re-calcs.
// But the user wants "add something like redirect to the bank pages".
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment Gateway - Bank Verification</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f8; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .bank-container { background: white; width: 100%; max-width: 450px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; position: relative; }
        .bank-header { background: #004b8d; color: white; padding: 15px 20px; display: flex; align-items: center; justify-content: space-between; }
        .bank-logo { font-weight: bold; font-size: 1.2rem; display: flex; align-items: center; gap: 10px; }
        .bank-body { padding: 30px; }
        .transaction-details { background: #f9f9f9; padding: 15px; border-radius: 6px; margin-bottom: 25px; border: 1px solid #e0e0e0; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.9rem; color: #555; }
        .detail-row:last-child { margin-bottom: 0; font-weight: bold; color: #333; font-size: 1rem; border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px; }
        .otp-section { text-align: center; }
        .otp-inputs { display: flex; justify-content: center; gap: 10px; margin: 20px 0; }
        .otp-input { width: 40px; height: 50px; border: 2px solid #ddd; border-radius: 6px; text-align: center; font-size: 1.5rem; font-weight: bold; transition: border 0.3s; }
        .otp-input:focus { border-color: #004b8d; outline: none; }
        .info-text { font-size: 0.9rem; color: #666; margin-bottom: 20px; line-height: 1.5; }
        .btn-confirm { background: #004b8d; color: white; border: none; padding: 12px 0; width: 100%; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.3s; }
        .btn-confirm:hover { background: #003666; }
        .btn-confirm:disabled { background: #ccc; cursor: not-allowed; }
        .loader { display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.9); z-index: 10; flex-direction: column; justify-content: center; align-items: center; }
        .spinner { border: 4px solid #f3f3f3; border-top: 4px solid #004b8d; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin-bottom: 15px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .resend-link { display: block; margin-top: 15px; color: #004b8d; text-decoration: none; font-size: 0.85rem; cursor: pointer; }
        .resend-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="bank-container">
    <div class="loader" id="loader">
        <div class="spinner"></div>
        <div style="font-weight: 600; color: #333;">Processing Payment...</div>
        <div style="font-size: 0.85rem; color: #666; margin-top: 5px;">Please do not close this window</div>
    </div>

    <div class="bank-header">
        <div class="bank-logo"><i class="fas fa-shield-alt"></i> SecurePay Bank</div>
        <div style="font-size: 0.8rem; opacity: 0.8;">Verified by Visa</div>
    </div>

    <div class="bank-body">
        <div style="text-align: center; margin-bottom: 20px;">
            <i class="fas fa-lock" style="font-size: 2rem; color: #004b8d; margin-bottom: 10px;"></i>
            <h2 style="margin: 0; font-size: 1.4rem; color: #333;">Authentication Required</h2>
        </div>

        <div class="transaction-details">
            <div class="detail-row">
                <span>Merchant:</span>
                <span>LaptopAdvisor Inc.</span>
            </div>
            <div class="detail-row">
                <span>Date:</span>
                <span><?php echo date('d M Y, H:i'); ?></span>
            </div>
            <div class="detail-row">
                <span>Card Number:</span>
                <span>**** **** **** <?php echo isset($_POST['card_number']) ? substr(str_replace(' ','',$_POST['card_number']), -4) : '4242'; ?></span>
            </div>
            <div class="detail-row">
                <span>Total Amount:</span>
                <!-- We don't have the grand total passed directly, but we can assume it's calculated. 
                     For simplicity in this visual demo, we'll hide the specific amount or show 'Calculated at Checkout' 
                     OR we could have passed it as a hidden field. Let's just show 'USD' for now or fetch from session if we really wanted. -->
                <span>USD (Pending)</span>
            </div>
        </div>

        <div class="otp-section">
            <p class="info-text">
                A One-Time Password (OTP) has been sent to your registered mobile number ending in <strong>****88</strong>.
                <br>Please enter the code below to authorize this transaction.
            </p>

            <div class="otp-inputs" id="otp-container">
                <input type="text" class="otp-input" maxlength="1" pattern="\d*">
                <input type="text" class="otp-input" maxlength="1" pattern="\d*">
                <input type="text" class="otp-input" maxlength="1" pattern="\d*">
                <input type="text" class="otp-input" maxlength="1" pattern="\d*">
                <input type="text" class="otp-input" maxlength="1" pattern="\d*">
                <input type="text" class="otp-input" maxlength="1" pattern="\d*">
            </div>

            <button class="btn-confirm" id="confirmBtn" onclick="processPayment()">Confirm Payment</button>
            <a class="resend-link" onclick="alert('New code sent!')">Resend Code</a>
        </div>
    </div>
</div>

<!-- Hidden form to forward data to the original processor -->
<form id="forwardForm" action="cart_process.php" method="POST" style="display: none;">
    <input type="hidden" name="action" value="checkout">
    <input type="hidden" name="payment_method" value="<?php echo htmlspecialchars($payment_method); ?>">
    
    <input type="hidden" name="shipping_name" value="<?php echo htmlspecialchars($shipping_name); ?>">
    <input type="hidden" name="shipping_address" value="<?php echo htmlspecialchars($shipping_address); ?>">
    <input type="hidden" name="shipping_city" value="<?php echo htmlspecialchars($shipping_city); ?>">
    <input type="hidden" name="shipping_state" value="<?php echo htmlspecialchars($shipping_state); ?>">
    <input type="hidden" name="shipping_zip" value="<?php echo htmlspecialchars($shipping_zip); ?>">
    <input type="hidden" name="shipping_country" value="<?php echo htmlspecialchars($shipping_country); ?>">
    <input type="hidden" name="shipping_phone" value="<?php echo htmlspecialchars($shipping_phone); ?>">
</form>

<script>
    // Auto-focus logic for OTP inputs
    const inputs = document.querySelectorAll('.otp-input');
    inputs.forEach((input, index) => {
        input.addEventListener('keyup', (e) => {
            if (e.key >= 0 && e.key <= 9) {
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            } else if (e.key === 'Backspace') {
                if (index > 0) {
                    inputs[index - 1].focus();
                }
            }
        });
        
        // Allow pasting
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text').slice(0, 6).split('');
            pasteData.forEach((char, i) => {
                if (inputs[i]) inputs[i].value = char;
            });
            inputs[Math.min(pasteData.length, inputs.length) - 1].focus();
        });
    });

    // Simulate Payment Processing
    function processPayment() {
        // Basic validation
        let otp = '';
        inputs.forEach(input => otp += input.value);
        if (otp.length < 6) {
            alert('Please enter the valid 6-digit OTP code sent to your mobile.');
            return;
        }

        // Show loader
        document.getElementById('loader').style.display = 'flex';
        
        // Specific user request: "add something like redirect to the bank pages but it (fake) because we need to demo"
        // We simulate a network delay
        setTimeout(() => {
            // Submit the actual data
            document.getElementById('forwardForm').submit();
        }, 2000); // 2 second delay for "processing"
    }

    // Auto-fill OTP for demo purposes after 3 seconds (optional, but helpful for testing)
    setTimeout(() => {
        const demoCode = '123456';
        demoCode.split('').forEach((char, i) => {
            if (inputs[i]) inputs[i].value = char;
        });
        // alert('Demo: OTP auto-filled for testing convenience.');
    }, 1500);
</script>

</body>
</html>
