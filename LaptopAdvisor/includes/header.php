<?php
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptop Advisor</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <a href="index.php" class="logo">LAPTOP ADVISOR</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Show these links only if the user is logged in -->
                    <li><a href="products.php">All Products</a></li>
                    <li><a href="advisor.php">Recommendation Quiz</a></li>
                    <li><a href="reports.php">Reports</a></li> 
                    <li><a href="cart.php">Cart</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <!-- Show these links if the user is a guest -->
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
                <li>
                    <div class="currency-wrapper" style="display: flex; align-items: center; margin-left: 15px; border: 1px solid #ddd; border-radius: 20px; padding: 5px 10px;">
                        <span style="font-size: 1.2rem; margin-right: 5px;">🌐</span>
                        <select class="currency-selector" style="border: none; background: transparent; font-family: inherit; font-weight: 500; cursor: pointer; outline: none; color: #333;">
                            <option value="USD">USD ($)</option>
                            <option value="MYR">MYR (RM)</option>
                            <option value="CNY">CNY (¥)</option>
                        </select>
                    </div>
                </li>
            </ul>
    <script src="js/currency_manager.js"></script>
            <div class="hamburger">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
        </div>
    </header>
    <main class="container">