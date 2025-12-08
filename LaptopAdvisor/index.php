<?php 
// We don't need auth_check here, the homepage should be public.
// We will check the session status inside the page instead.
require_once 'includes/db_connect.php'; 
include 'includes/header.php'; 
?>

<div class="hero">
    <?php if (isset($_SESSION['full_name'])): ?>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION["full_name"]); ?>!</h1>
    <?php else: ?>
        <h1>Find Your Perfect Laptop</h1>
    <?php endif; ?>
    
    <p>Let our Smart Advisor guide you to the laptop that fits your needs.</p>
    <a href="advisor.php" class="btn btn-primary">Start the Advisor</a>
    <a href="products.php" class="btn">Browse All Laptops</a>
</div>

<div class="container">
    <h2>Featured Laptops</h2>
    <div class="product-grid">
        <?php
        // Fetch a few random products from the database
        $sql = "SELECT * FROM products ORDER BY RAND() LIMIT 4";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Using the consistent, professional product card structure
                echo '<div class="product-card">';
                
                // THE BUG FIX IS HERE: Changed 'id' to 'product_id'
                echo '<a href="product_details.php?product_id=' . $row["product_id"] . '">';
                
                echo '<img src="' . (!empty($row['image_url']) ? htmlspecialchars($row['image_url']) : 'https://via.placeholder.com/280') . '" alt="' . htmlspecialchars($row["product_name"]) . '">';
                echo '<div class="product-card-info">';
                echo '<p class="brand">' . htmlspecialchars($row["brand"]) . '</p>';
                echo '<h3>' . htmlspecialchars($row["product_name"]) . '</h3>';
                echo '<p class="product-price currency-price" data-base-price="' . $row["price"] . '">$' . number_format($row["price"], 2) . '</p>';
                echo '</div>'; // end product-card-info
                echo '</a>';
                echo '</div>'; // end product-card
            }
        } else {
            echo "<p>No featured products available at the moment.</p>";
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>