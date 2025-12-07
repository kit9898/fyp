<?php
include 'includes/header.php';
require_once 'includes/auth_check.php'; 

function getYouTubeId($url) {
    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
    return $matches[1] ?? false;
} 

// --- 1. INITIALIZE VARIABLES ---
$product = null;
$reviews = [];
$similar_products = [];
$average_rating = 0;
$total_reviews = 0;
$review_msg = ''; 
$gallery_items = []; // Holds the images/videos from DB

// Check if product_id is provided and is a valid number
if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // --- 2. HANDLE REVIEW SUBMISSION ---
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $rating = intval($_POST['rating']);
            $review_text = trim($_POST['review_text']);
            
            if ($rating >= 1 && $rating <= 5 && !empty($review_text)) {
                $check_sql = "SELECT review_id FROM product_reviews WHERE product_id = ? AND user_id = ?";
                $check_stmt = $conn->prepare($check_sql);
                $check_stmt->bind_param("ii", $product_id, $user_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();

                if ($check_result->num_rows > 0) {
                    $review_msg = "<div class='alert alert-warning'>You have already reviewed this product.</div>";
                } else {
                    $stmt = $conn->prepare("INSERT INTO product_reviews (product_id, user_id, rating, review_text) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("iiis", $product_id, $user_id, $rating, $review_text);
                    
                    if ($stmt->execute()) {
                        $review_msg = "<div class='alert alert-success'>Review submitted successfully!</div>";
                    } else {
                        $review_msg = "<div class='alert alert-danger'>Error submitting review.</div>";
                    }
                    $stmt->close();
                }
                $check_stmt->close();
            } else {
                $review_msg = "<div class='alert alert-danger'>Please provide a rating and a comment.</div>";
            }
        } else {
            $review_msg = "<div class='alert alert-warning'>You must be logged in to submit a review.</div>";
        }
    }

    // --- 3. FETCH PRODUCT DETAILS ---
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $product = $result->fetch_assoc();

        // --- 3.1 FETCH GALLERY MEDIA (FROM DATABASE) ---
        $check_table = $conn->query("SHOW TABLES LIKE 'product_media'");
        
        if ($check_table && $check_table->num_rows > 0) {
            $media_sql = "SELECT * FROM product_media WHERE product_id = ? ORDER BY display_order ASC";
            if ($media_stmt = $conn->prepare($media_sql)) {
                $media_stmt->bind_param("i", $product_id);
                $media_stmt->execute();
                $media_result = $media_stmt->get_result();
                
                while ($row = $media_result->fetch_assoc()) {
                    $gallery_items[] = [
                        'url' => $row['media_url'],
                        'type' => $row['media_type'] // 'image' or 'video'
                    ];
                }
                $media_stmt->close();
            }
        }

        // Fallback: If no media found in DB, use the main image from products table
        if (empty($gallery_items)) {
            if (!empty($product['image_url'])) {
                // Handle both relative and absolute paths
                $main_img = $product['image_url'];
                if (strpos($main_img, 'http') !== 0 && strpos($main_img, 'LaptopAdvisor/') === 0) {
                    // Path is already relative from root (LaptopAdvisor/images/...)
                    $main_img = '../' . $main_img;
                } elseif (strpos($main_img, 'http') !== 0 && strpos($main_img, 'images/') === 0) {
                    // Path is relative from LaptopAdvisor folder
                    $main_img = $main_img;
                } elseif (strpos($main_img, 'http') !== 0) {
                    // Assume it's just the filename
                    $main_img = 'images/' . basename($main_img);
                }
            } else {
                $main_img = 'https://via.placeholder.com/600x400?text=No+Image';
            }
            $gallery_items[] = ['url' => $main_img, 'type' => 'image'];
        }
        
        // --- 4. FETCH REVIEWS & CALCULATE AVERAGE ---
        $review_sql = "SELECT r.*, u.full_name FROM product_reviews r 
                       LEFT JOIN users u ON r.user_id = u.user_id 
                       WHERE r.product_id = ? 
                       ORDER BY r.created_at DESC";
        $review_stmt = $conn->prepare($review_sql);
        $review_stmt->bind_param("i", $product_id);
        $review_stmt->execute();
        $reviews_result = $review_stmt->get_result();
        
        $rating_sum = 0;
        while ($row = $reviews_result->fetch_assoc()) {
            $reviews[] = $row;
            $rating_sum += $row['rating'];
        }
        $review_stmt->close();

        $total_reviews = count($reviews);
        if ($total_reviews > 0) {
            $average_rating = round($rating_sum / $total_reviews, 1);
        }

        // --- 5. GET SIMILAR PRODUCTS ---
        $similar_sql = "SELECT * FROM products 
                       WHERE primary_use_case = ? AND product_id != ? 
                       ORDER BY ABS(price - ?) ASC 
                       LIMIT 4";
        $similar_stmt = $conn->prepare($similar_sql);
        $similar_stmt->bind_param("sid", $product['primary_use_case'], $product_id, $product['price']);
        $similar_stmt->execute();
        $similar_result = $similar_stmt->get_result();
        while ($row = $similar_result->fetch_assoc()) {
            $similar_products[] = $row;
        }
        $similar_stmt->close();
    }
} else {
    $product = null;
}

// --- 6. AI RECOMMENDATIONS LOGIC ---
$accessory_recommendations = [];
if ($product && isset($product['product_category']) && $product['product_category'] == 'laptop') {
    $api_url = 'http://127.0.0.1:5000/api/accessory-recommendations';
    $post_data = json_encode([
        'user_id' => $_SESSION['user_id'] ?? 1,
        'laptop_id' => $product['product_id'],
        'use_case' => $product['primary_use_case'],
        'limit' => 4,
        'method' => 'hybrid'
    ]);

    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    $api_response = curl_exec($ch);
    $api_error = curl_error($ch);
    curl_close($ch);

    if ($api_response && !$api_error) {
        $api_data = json_decode($api_response, true);
        if (isset($api_data['success']) && $api_data['success'] && !empty($api_data['recommendations'])) {
            $accessory_ids = array_column($api_data['recommendations'], 'product_id');
            if (!empty($accessory_ids)) {
                $placeholders = implode(',', array_fill(0, count($accessory_ids), '?'));
                $stmt = $conn->prepare("SELECT * FROM products WHERE product_id IN ($placeholders) LIMIT 4");
                $stmt->bind_param(str_repeat('i', count($accessory_ids)), ...$accessory_ids);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $accessory_recommendations[] = $row;
                }
                $stmt->close();
            }
        }
    }
}

// Helper functions
function getPerformanceRating($ram, $gpu) {
    $score = 0;
    if ($ram >= 32) $score += 50; elseif ($ram >= 16) $score += 40; elseif ($ram >= 8) $score += 25; else $score += 10;
    if (stripos($gpu, 'RTX 4090') !== false) $score += 50;
    elseif (stripos($gpu, 'RTX 4080') !== false || stripos($gpu, 'RTX 3090') !== false) $score += 45;
    elseif (stripos($gpu, 'RTX 4070') !== false || stripos($gpu, 'RTX 3080') !== false) $score += 40;
    elseif (stripos($gpu, 'RTX 4060') !== false || stripos($gpu, 'RTX 3070') !== false) $score += 35;
    elseif (stripos($gpu, 'RTX 3060') !== false || stripos($gpu, 'RTX 4050') !== false) $score += 30;
    elseif (stripos($gpu, 'GTX') !== false) $score += 20;
    elseif (stripos($gpu, 'Integrated') !== false || stripos($gpu, 'Iris') !== false || stripos($gpu, 'Radeon') !== false) $score += 15;
    else $score += 10;
    return min(100, $score);
}

function getUseCaseBadge($use_case) {
    $badges = [
        'Gaming' => ['icon' => '🎮', 'color' => '#e74c3c'],
        'Creative' => ['icon' => '🎨', 'color' => '#9b59b6'],
        'Business' => ['icon' => '💼', 'color' => '#3498db'],
        'Student' => ['icon' => '📚', 'color' => '#2ecc71'],
        'General Use' => ['icon' => '🌐', 'color' => '#95a5a6']
    ];
    return $badges[$use_case] ?? ['icon' => '💻', 'color' => '#34495e'];
}
?>

<link rel="stylesheet" href="css/product-details.css">

<div class="product-detail-wrapper">
    <?php if ($product): ?>
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb">
            <a href="products.php">Products</a> / 
            <a href="products.php?view=browse&use_case=<?php echo urlencode($product['primary_use_case']); ?>">
                <?php echo htmlspecialchars($product['primary_use_case']); ?>
            </a> / 
            <span><?php echo htmlspecialchars($product['product_name']); ?></span>
        </div>

        <div class="product-detail-container">
            <!-- LEFT COLUMN: AMAZON STYLE GALLERY WITH DATABASE DATA -->
            <div class="amazon-gallery-container">
                <!-- 1. Vertical Thumbnails List -->
                <div class="thumbnails-column">
                    <?php foreach ($gallery_items as $index => $item): 
                        // Handle image paths for thumbnails
                        $thumb_url = $item['url'];
                        $media_type = $item['type'];
                        $yt_id = false;

                        if ($media_type == 'video') {
                            $yt_id = getYouTubeId($thumb_url);
                            if ($yt_id) {
                                $media_type = 'youtube';
                                $thumb_url = "https://img.youtube.com/vi/$yt_id/0.jpg";
                            }
                        }
                        if ($item['type'] == 'image' && strpos($thumb_url, 'http') !== 0) {
                            if (strpos($thumb_url, 'LaptopAdvisor/') === 0) {
                                $thumb_url = '../' . $thumb_url;
                            } elseif (strpos($thumb_url, '../') !== 0 && strpos($thumb_url, 'images/') !== 0) {
                                $thumb_url = 'images/' . basename($thumb_url);
                            }
                        }
                    ?>
                        <div class="thumb-item <?php echo $index === 0 ? 'active' : ''; ?>" 
                             onclick="swapMedia('<?php echo $media_type == 'youtube' ? $yt_id : htmlspecialchars($thumb_url); ?>', '<?php echo $media_type; ?>', this)">
                            <?php if ($media_type == 'video'): ?>
                                <video src="<?php echo htmlspecialchars($thumb_url); ?>" muted></video>
                                <div class="play-icon-overlay">▶</div>
                            <?php elseif ($media_type == 'youtube'): ?>
                                <img src="<?php echo htmlspecialchars($thumb_url); ?>" alt="Thumbnail" loading="lazy">
                                <div class="play-icon-overlay">▶</div>
                            <?php else: ?>
                                <img src="<?php echo htmlspecialchars($thumb_url); ?>" alt="Thumbnail" onerror="this.src='https://via.placeholder.com/50x50?text=?'" loading="lazy">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- 2. Main Large Image/Video Area -->
                <div class="main-image-area" id="mainMediaContainer" onclick="openLightbox()">
                    <?php 
                        // Initial render of the first item
                        $first_item = $gallery_items[0];
                        $first_url = $first_item['url'];
                        
                        $first_type = $first_item['type'];
                        $first_yt_id = false;

                        if ($first_type == 'video') {
                            $first_yt_id = getYouTubeId($first_url);
                            if ($first_yt_id) {
                                $first_type = 'youtube';
                            }
                        }
                        
                        // Handle paths for main image
                        if ($first_item['type'] == 'image' && strpos($first_url, 'http') !== 0) {
                            if (strpos($first_url, 'LaptopAdvisor/') === 0) {
                                $first_url = '../' . $first_url;
                            } elseif (strpos($first_url, '../') !== 0 && strpos($first_url, 'images/') !== 0) {
                                $first_url = 'images/' . basename($first_url);
                            }
                        }
                        
                        // Render based on type
                        if ($first_type == 'youtube'): 
                    ?>
                        <iframe id="mainProductMedia" class="main-media-element" 
                                src="https://www.youtube.com/embed/<?php echo htmlspecialchars($first_yt_id); ?>" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                        </iframe>
                    <?php elseif ($first_type == 'video'): ?>
                        <video id="mainProductMedia" class="main-media-element" controls>
                            <source src="<?php echo htmlspecialchars($first_url); ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php else: ?>
                        <img id="mainProductMedia" class="main-media-element" 
                             src="<?php echo htmlspecialchars($first_url); ?>" 
                             alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                             onerror="this.src='https://via.placeholder.com/600x400?text=Image+Not+Found'" loading="lazy">
                        <div class="zoom-hint"></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RIGHT COLUMN: PRODUCT INFO -->
            <div class="product-info-section">
                <?php $badge = getUseCaseBadge($product['primary_use_case']); ?>
                <div class="use-case-badge" style="background-color: <?php echo $badge['color']; ?>">
                    <span><?php echo $badge['icon']; ?></span>
                    <span><?php echo htmlspecialchars($product['primary_use_case']); ?></span>
                </div>
                
                <p class="brand-tag-large"><?php echo htmlspecialchars($product['brand']); ?></p>
                <h1 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                
                <div class="price-section">
                    <div class="price-label">Price</div>
                    <div class="product-price-display">$<?php echo number_format($product['price'], 2); ?></div>
                </div>

                <p class="description" style="margin: 20px 0; color: #495057; line-height: 1.6;">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </p>

                <!-- Key Specifications -->
                <h3 style="margin: 25px 0 15px 0; font-size: 1.2rem;">Key Specifications</h3>
                <div class="key-features">
                    <?php if (isset($product['product_category']) && $product['product_category'] == 'laptop'): ?>
                        <div class="feature-box"><strong>Processor</strong><span><?php echo htmlspecialchars($product['cpu']); ?></span></div>
                        <div class="feature-box"><strong>Graphics</strong><span><?php echo htmlspecialchars($product['gpu']); ?></span></div>
                        <div class="feature-box"><strong>Memory</strong><span><?php echo htmlspecialchars($product['ram_gb']); ?> GB RAM</span></div>
                        <div class="feature-box"><strong>Storage</strong><span><?php echo htmlspecialchars($product['storage_gb']); ?> GB <?php echo htmlspecialchars($product['storage_type']); ?></span></div>
                    <?php else: ?>
                        <div class="feature-box"><strong>Category</strong><span><?php echo ucwords(str_replace('_', ' ', $product['product_category'] ?? 'Accessory')); ?></span></div>
                        <div class="feature-box"><strong>Brand</strong><span><?php echo htmlspecialchars($product['brand']); ?></span></div>
                        <div class="feature-box"><strong>Best For</strong><span><?php echo htmlspecialchars($product['primary_use_case']); ?></span></div>
                        <div class="feature-box"><strong>Compatibility</strong><span><?php echo !empty($product['related_to_category']) ? htmlspecialchars($product['related_to_category']) . " Laptops" : "Universal"; ?></span></div>
                    <?php endif; ?>
                </div>

                <!-- Performance Rating -->
                <?php if (isset($product['product_category']) && $product['product_category'] == 'laptop'): ?>
                <?php $performance_score = getPerformanceRating($product['ram_gb'], $product['gpu']); ?>
                <div class="performance-meter">
                    <div class="meter-label"><span>Performance Rating</span><span><?php echo $performance_score; ?>/100</span></div>
                    <div class="meter-bar"><div class="meter-fill" style="width: <?php echo $performance_score; ?>%"></div></div>
                    <p style="margin-top: 8px; font-size: 0.85rem; color: #666;">
                        <?php if ($performance_score >= 80) echo "⚡ Exceptional performance"; elseif ($performance_score >= 60) echo "✓ Great performance"; else echo "→ Good for everyday computing"; ?>
                    </p>
                </div>
                <?php endif; ?>

                <!-- Add to Cart -->
                <div class="cart-section">
                    <?php 
                    // Check if stock_quantity field exists and has value
                    $stock_qty = isset($product['stock_quantity']) ? intval($product['stock_quantity']) : 50; // Default to 50 if not set
                    $low_stock_threshold = 10;
                    
                    if ($stock_qty > 0): 
                        $stock_status_class = $stock_qty > $low_stock_threshold ? 'text-success' : 'text-warning';
                        $stock_icon = $stock_qty > $low_stock_threshold ? '✓' : '⚠️';
                    ?>
                        <div style="font-weight: 600; margin-bottom: 15px;" class="<?php echo $stock_status_class; ?>">
                            <?php echo $stock_icon; ?> 
                            <?php if ($stock_qty > $low_stock_threshold): ?>
                                In Stock (<?php echo $stock_qty; ?> available)
                            <?php else: ?>
                                Low Stock - Only <?php echo $stock_qty; ?> left!
                            <?php endif; ?>
                        </div>
                        <form action="cart_process.php" method="post" id="cartForm">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="hidden" name="quantity" id="quantityInput" value="1">
                            <label style="font-weight: 600; display: block; margin-bottom: 10px;">Quantity</label>
                            <div class="quantity-selector">
                                <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">−</button>
                                <span class="quantity-display" id="quantityDisplay">1</span>
                                <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
                            </div>
                            <button type="submit" class="add-to-cart-btn">🛒 Add to Cart</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-danger" style="text-align: center;"><strong>🚫 Out of Stock</strong></div>
                        <div class="quick-actions" style="margin-top: 15px;">
                            <button class="add-to-cart-btn" style="background: #ccc; cursor: not-allowed;" disabled>Out of Stock</button>
                        </div>
                    <?php endif; ?>
                    <div class="quick-actions">
                        <button onclick="addToCompare(<?php echo $product['product_id']; ?>, '<?php echo $product['product_category'] ?? 'laptop'; ?>')" class="quick-action-btn" style="border:1px solid #e9ecef; width:100%; font-size:1rem; font-family:inherit;">⚖️ Compare</button>
                        <a href="products.php?view=browse&use_case=<?php echo urlencode($product['primary_use_case']); ?>" class="quick-action-btn">🔍 Similar Items</a>
                    </div>
                </div>
                
                <div class="trust-badges">
                    <div class="trust-badge"><span>✓</span> Free Shipping</div>
                    <div class="trust-badge"><span>↩️</span> 30-Day Returns</div>
                    <div class="trust-badge"><span>★</span> Warranty Included</div>
                </div>
            </div>
        </div>

        <!-- Detailed Specifications Tabs -->
        <div class="content-box" style="margin-top: 40px;">
            <div class="spec-tabs">
                <button class="spec-tab active" onclick="switchTab('overview')">Overview</button>
                <button class="spec-tab" onclick="switchTab('specs')">Full Specifications</button>
                <button class="spec-tab" onclick="switchTab('performance')">Performance Analysis</button>
                <button class="spec-tab" onclick="switchTab('reviews')">Reviews (<?php echo $total_reviews; ?>)</button>
            </div>

            <div id="overview" class="tab-content active">
                <h3 style="margin-bottom: 15px;">Product Overview</h3>
                <p style="line-height: 1.8; color: #495057;">
                    The <strong><?php echo htmlspecialchars($product['product_name']); ?></strong> from 
                    <strong><?php echo htmlspecialchars($product['brand']); ?></strong> is designed for 
                    <strong><?php echo htmlspecialchars($product['primary_use_case']); ?></strong>. 
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </p>
                <div style="margin-top: 25px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
                    <h4 style="margin-bottom: 10px;">Best For:</h4>
                    <ul style="line-height: 2; color: #495057;">
                        <?php
                        $use_case = $product['primary_use_case'];
                        if ($use_case == 'Gaming') {
                            echo "<li>AAA gaming titles with high FPS</li>";
                            echo "<li>VR and immersive experiences</li>";
                            echo "<li>Live streaming and content creation</li>";
                        } elseif ($use_case == 'Creative') {
                            echo "<li>Video editing and rendering</li>";
                            echo "<li>3D modeling and animation</li>";
                            echo "<li>Graphic design and photo editing</li>";
                        } elseif ($use_case == 'Business') {
                            echo "<li>Productivity applications</li>";
                            echo "<li>Video conferencing</li>";
                            echo "<li>Professional presentations</li>";
                        } elseif ($use_case == 'Student') {
                            echo "<li>Note-taking and research</li>";
                            echo "<li>Online learning</li>";
                            echo "<li>Document creation</li>";
                        } else {
                            echo "<li>Web browsing and email</li>";
                            echo "<li>Media streaming</li>";
                            echo "<li>Light multitasking</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <div id="specs" class="tab-content">
                <h3 style="margin-bottom: 15px;">Complete Technical Specifications</h3>
                <table class="spec-table">
                    <tr><td>Brand</td><td><?php echo htmlspecialchars($product['brand']); ?></td></tr>
                    <tr><td>Model</td><td><?php echo htmlspecialchars($product['product_name']); ?></td></tr>
                    <?php if (isset($product['product_category']) && $product['product_category'] == 'laptop'): ?>
                    <tr><td>Processor (CPU)</td><td><?php echo htmlspecialchars($product['cpu']); ?></td></tr>
                    <tr><td>Graphics (GPU)</td><td><?php echo htmlspecialchars($product['gpu']); ?></td></tr>
                    <tr><td>Memory (RAM)</td><td><?php echo htmlspecialchars( $product['ram_gb']); ?> GB</td></tr>
                    <tr><td>Storage</td><td><?php echo htmlspecialchars($product['storage_gb']); ?> GB <?php echo htmlspecialchars($product['storage_type']); ?></td></tr>
                    <tr><td>Display Size</td><td><?php echo htmlspecialchars($product['display_size']); ?> inches</td></tr>
                    <?php else: ?>
                    <tr><td>Product Type</td><td><?php echo ucwords(str_replace('_', ' ', $product['product_category'] ?? 'Accessory')); ?></td></tr>
                    <?php endif; ?>
                    <tr><td>Price</td><td style="font-size: 1.2rem; font-weight: 600; color: #667eea;">$<?php echo number_format($product['price'], 2); ?></td></tr>
                </table>
            </div>

            <div id="performance" class="tab-content">
                <?php if (isset($product['product_category']) && $product['product_category'] == 'laptop'): ?>
                    <h3 style="margin-bottom: 15px;">Performance Analysis</h3>
                    <div style="display: grid; gap: 20px;">
                        <div style="padding: 20px; background: #f8f9fa; border-radius: 10px;">
                            <h4 style="margin-bottom: 10px; color: #3b82f6;">💻 Processing Power</h4>
                            <p style="color: #495057; line-height: 1.6;">
                                Equipped with <strong><?php echo htmlspecialchars($product['cpu']); ?></strong>, 
                                this laptop provides 
                                <?php 
                                if (stripos($product['cpu'], 'i9') !== false || stripos($product['cpu'], 'Ryzen 9') !== false) {
                                    echo "exceptional multi-core performance for the most demanding applications.";
                                } elseif (stripos($product['cpu'], 'i7') !== false || stripos($product['cpu'], 'Ryzen 7') !== false) {
                                    echo "excellent performance for multitasking and professional applications.";
                                } elseif (stripos($product['cpu'], 'i5') !== false || stripos($product['cpu'], 'Ryzen 5') !== false) {
                                    echo "balanced performance for everyday computing and moderate workloads.";
                                } else {
                                    echo "reliable performance for standard computing tasks.";
                                }
                                ?>
                            </p>
                        </div>
                        
                        <div style="padding: 20px; background: #f8f9fa; border-radius: 10px;">
                            <h4 style="margin-bottom: 10px; color: #10b981;">🎮 Graphics Performance</h4>
                            <p style="color: #495057; line-height: 1.6;">
                                The <strong><?php echo htmlspecialchars($product['gpu']); ?></strong> 
                                <?php 
                                if (stripos($product['gpu'], 'RTX 40') !== false || stripos($product['gpu'], 'RTX 3090') !== false) {
                                    echo "delivers cutting-edge graphics performance, perfect for 4K gaming and professional 3D work.";
                                } elseif (stripos($product['gpu'], 'RTX 30') !== false) {
                                    echo "provides excellent graphics performance for gaming and creative applications.";
                                } elseif (stripos($product['gpu'], 'GTX') !== false) {
                                    echo "handles gaming and light creative work with good performance.";
                                } else {
                                    echo "is suitable for everyday tasks, video playback, and light photo editing.";
                                }
                                ?>
                            </p>
                        </div>
                        
                        <div style="padding: 20px; background: #f8f9fa; border-radius: 10px;">
                            <h4 style="margin-bottom: 10px; color: #f59e0b;">⚡ Memory & Storage</h4>
                            <p style="color: #495057; line-height: 1.6;">
                                With <strong><?php echo htmlspecialchars($product['ram_gb']); ?>GB RAM</strong>, 
                                you can run 
                                <?php 
                                if ($product['ram_gb'] >= 32) echo "numerous demanding applications simultaneously";
                                elseif ($product['ram_gb'] >= 16) echo "multiple applications smoothly with room for heavy multitasking";
                                elseif ($product['ram_gb'] >= 8) echo "several applications comfortably for everyday use";
                                else echo "essential applications for basic computing needs";
                                ?>. 
                                The <strong><?php echo htmlspecialchars($product['storage_gb']); ?>GB <?php echo htmlspecialchars($product['storage_type']); ?></strong> 
                                provides <?php echo $product['storage_gb'] >= 1000 ? 'ample' : 'sufficient'; ?> space 
                                with fast read/write speeds.
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <h3 style="margin-bottom: 15px;">Usage & Compatibility</h3>
                    <div style="display: grid; gap: 20px;">
                        <div style="padding: 20px; background: #f8f9fa; border-radius: 10px;">
                            <h4 style="margin-bottom: 10px; color: #3b82f6;">🔌 Compatibility</h4>
                            <p style="color: #495057; line-height: 1.6;">
                                This <strong><?php echo ucwords(str_replace('_', ' ', $product['product_category'] ?? 'accessory')); ?></strong> is designed to work seamlessly with modern laptops and desktop computers. 
                                <?php if (!empty($product['related_to_category'])): ?>
                                    It is specifically optimized for <strong><?php echo htmlspecialchars($product['related_to_category']); ?></strong> systems.
                                <?php else: ?>
                                    It features standard connectivity suitable for most Windows, macOS, and Linux systems.
                                <?php endif; ?>
                            </p>
                        </div>
                        
                        <div style="padding: 20px; background: #f8f9fa; border-radius: 10px;">
                            <h4 style="margin-bottom: 10px; color: #10b981;">🎯 Recommended Usage</h4>
                            <p style="color: #495057; line-height: 1.6;">
                                Best suited for <strong><?php echo htmlspecialchars($product['primary_use_case']); ?></strong> environments. 
                                <?php 
                                $use_case = $product['primary_use_case'];
                                if ($use_case == 'Gaming') {
                                    echo "It offers high responsiveness and durability essential for competitive gaming sessions.";
                                } elseif ($use_case == 'Creative') {
                                    echo "It provides the precision and comfort needed for long editing or design sessions.";
                                } elseif ($use_case == 'Business') {
                                    echo "Reliable and professional, it ensures productivity during your workday.";
                                } else {
                                    echo "A great addition to your daily setup for comfort and efficiency.";
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div id="reviews" class="tab-content">
                <?php echo $review_msg; ?>
                <h3 style="margin-bottom: 15px;">Customer Reviews</h3>
                <div class="review-summary">
                    <div style="text-align: center;"><div class="big-rating"><?php echo $average_rating; ?></div><div style="font-size: 0.9rem; color: #666;">out of 5</div></div>
                    <div><div style="font-size: 1.2rem; color: #f59e0b;"><?php for ($i = 1; $i <= 5; $i++) echo ($i <= round($average_rating)) ? '★' : '<span style="color: #e9ecef">★</span>'; ?></div><p style="margin: 0; color: #666;"><?php echo $total_reviews; ?> verified ratings</p></div>
                </div>
                <div class="reviews-list">
                    <?php if ($total_reviews > 0): foreach ($reviews as $rev): ?>
                        <div class="review-card">
                            <div class="review-header"><strong><?php echo htmlspecialchars($rev['full_name'] ?? 'User'); ?></strong><span style="color: #f59e0b;"><?php echo str_repeat('★', $rev['rating']); ?></span></div>
                            <p><?php echo nl2br(htmlspecialchars($rev['review_text'])); ?></p>
                        </div>
                    <?php endforeach; else: ?><p style="text-align: center; color: #666; padding: 20px;">No reviews yet.</p><?php endif; ?>
                </div>
                <div class="content-box" style="margin-top: 30px; border: 1px solid #e9ecef; padding: 25px; border-radius: 10px;">
                    <h4 style="margin-bottom: 15px;">Write a Review</h4>
                    <form method="POST" action=""><label style="font-weight: 600; display: block;">Your Rating</label><div class="star-rating-input"><input type="radio" id="star5" name="rating" value="5" required /><label for="star5">★</label><input type="radio" id="star4" name="rating" value="4" /><label for="star4">★</label><input type="radio" id="star3" name="rating" value="3" /><label for="star3">★</label><input type="radio" id="star2" name="rating" value="2" /><label for="star2">★</label><input type="radio" id="star1" name="rating" value="1" /><label for="star1">★</label></div><label style="font-weight: 600; display: block; margin-bottom: 8px;">Your Review</label><textarea name="review_text" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; margin-bottom:15px;" rows="4" required></textarea><button type="submit" name="submit_review" class="add-to-cart-btn" style="width: auto; padding: 10px 30px; font-size: 1rem;">Submit Review</button></form>
                </div>
            </div>
        </div>

        <!-- Similar Products Section (Preserved) -->
        <?php if (!empty($similar_products)): ?>
            <div class="similar-products-section">
                <h3>You May Also Like</h3>
                <div class="similar-grid">
                    <?php foreach ($similar_products as $similar): ?>
                        <div class="product-card">
                            <a href="product_details.php?product_id=<?php echo $similar['product_id']; ?>">
                                <img src="<?php echo !empty($similar['image_url']) ? htmlspecialchars($similar['image_url']) : 'https://via.placeholder.com/280'; ?>" 
                                     alt="<?php echo htmlspecialchars($similar['product_name']); ?>" loading="lazy">
                                <div class="product-card-info">
                                    <p class="brand"><?php echo htmlspecialchars($similar['brand']); ?></p>
                                    <h3><?php echo htmlspecialchars($similar['product_name']); ?></h3>
                                    <p class="product-price">$<?php echo number_format($similar['price'], 2); ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- AI Recommendations (Preserved) -->
        <?php if (!empty($accessory_recommendations)): ?>
        <div class="accessories-recommendation-section" style="margin-top: 50px; padding-top: 40px; border-top: 2px solid #e9ecef;">
            <div style="margin-bottom: 25px;">
                <h3 style="font-size: 1.8rem; margin-bottom: 10px; color: #1a1a1a;">✨ Recommended Accessories</h3>
                <p style="color: #666; font-size: 1rem;">AI-powered recommendations for your <?php echo htmlspecialchars($product['primary_use_case']); ?> laptop</p>
            </div>
            <div class="accessory-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
                <?php foreach ($accessory_recommendations as $accessory): ?>
                <div class="accessory-card" style="background: white; border: 2px solid #e9ecef; border-radius: 12px; padding: 20px;">
                    
                    <div style="display: inline-block; padding: 4px 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; font-size: 0.7rem; font-weight: 600; margin-bottom: 12px;">
                        <?php 
                        $category_icons = [
                            'mouse' => '🖱️', 'keyboard' => '⌨️', 'headset' => '🎧',
                            'monitor' => '🖥️', 'bag' => '🎒', 'webcam' => '📷',
                            'mousepad' => '⬛', 'docking_station' => '🔌', 'external_drive' => '💾'
                        ];
                        echo ($category_icons[$accessory['product_category']] ?? '🛠️') . ' ';
                        echo str_replace('_', ' ', ucwords($accessory['product_category'], '_'));
                        ?>
                    </div>



                    <?php 
                    $img_url = $accessory['image_url'];
                    // Fix path if it contains the project folder name incorrectly
                    $img_url = str_replace('LaptopAdvisor/', '', $img_url);
                    
                    // Fallback if empty
                    if (empty($img_url)) {
                        $img_url = 'https://via.placeholder.com/220?text=No+Image';
                    }
                    ?>
                    <img src="<?php echo htmlspecialchars($img_url); ?>" 
                         style="width: 100%; height: 150px; object-fit: contain; margin-bottom: 12px;" 
                         loading="lazy"
                         onerror="this.onerror=null; this.src='https://via.placeholder.com/220?text=Image+Not+Found';">
                    <h4 style="font-size: 0.95rem; margin: 10px 0 8px 0; min-height: 40px;"><?php echo htmlspecialchars($accessory['product_name']); ?></h4>
                    <p style="font-size: 0.85rem; color: #666; margin-bottom: 10px;"><?php echo htmlspecialchars($accessory['brand']); ?></p>
                    
                    <?php if ($accessory['stock_quantity'] > 0): ?>
                        <p style="font-size: 0.75rem; color: #10b981; margin-bottom: 10px;">✓ In Stock (<?php echo $accessory['stock_quantity']; ?> available)</p>
                    <?php else: ?>
                        <p style="font-size: 0.75rem; color: #ef4444; margin-bottom: 10px;">⚠️ Out of Stock</p>
                    <?php endif; ?>

                    <p style="font-size: 1.3rem; font-weight: 700; color: #667eea; margin: 12px 0;">$<?php echo number_format($accessory['price'], 2); ?></p>
                    
                    <div style="display: flex; gap: 8px;">
                        <?php if ($accessory['stock_quantity'] > 0): ?>
                        <form action="cart_process.php" method="post" style="flex: 1; margin: 0;">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $accessory['product_id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" style="width: 100%; padding: 10px; background: #667eea; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">🛒 Add to Cart</button>
                        </form>
                        <?php else: ?>
                        <button disabled style="flex: 1; padding: 10px; background: #ccc; color: #666; border: none; border-radius: 8px; font-weight: 600; cursor: not-allowed;">Out of Stock</button>
                        <?php endif; ?>
                        <a href="product_details.php?product_id=<?php echo $accessory['product_id']; ?>" style="flex: 1; padding: 10px; background: #fff; color: #495057; border: 1px solid #ddd; border-radius: 8px; text-decoration: none; text-align: center; font-weight: 600;">View</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div style="text-align: center; margin-top: 30px; padding: 15px; background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-radius: 10px;">
                <p style="margin: 0; color: #667eea; font-size: 0.85rem; font-weight: 600;">🤖 Powered by AI Machine Learning • Personalized just for you</p>
            </div>
        </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-danger" style="text-align: center; padding: 60px 20px;"><h2>Product Not Found</h2><a href="products.php" class="btn btn-primary">Browse All Products</a></div>
    <?php endif; ?>
</div>

<!-- Lightbox Overlay -->
<div id="imageLightbox" class="lightbox-overlay" onclick="closeLightbox(event)">
    <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
    <div id="lightboxContentWrapper" class="lightbox-content"></div>
</div>

<script>
// --- MEDIA GALLERY FUNCTIONS ---
function swapMedia(url, type, element) {
    const container = document.getElementById("mainMediaContainer");
    container.innerHTML = ''; // Clear container

    // Create new media element based on type
    let newElement;
    if (type === 'youtube') {
        // Create YouTube iframe
        newElement = document.createElement('iframe');
        newElement.className = 'main-media-element';
        newElement.src = 'https://www.youtube.com/embed/' + url;
        newElement.setAttribute('frameborder', '0');
        newElement.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
        newElement.setAttribute('allowfullscreen', 'true');
    } else if (type === 'video') {
        newElement = document.createElement('video');
        newElement.className = 'main-media-element';
        newElement.controls = true;
        newElement.autoplay = true;
        const source = document.createElement('source');
        source.src = url;
        source.type = 'video/mp4';
        newElement.appendChild(source);
    } else {
        newElement = document.createElement('img');
        newElement.className = 'main-media-element';
        newElement.src = url;
        newElement.alt = "Product Image";
        // Add zoom hint only for images
        const zoomHint = document.createElement('div');
        zoomHint.className = 'zoom-hint';
        zoomHint.innerText = '';
        container.appendChild(zoomHint);
    }
    container.appendChild(newElement);

    // Update thumbnails active state
    let thumbnails = document.querySelectorAll(".thumb-item");
    thumbnails.forEach(thumb => thumb.classList.remove("active"));
    if (element) element.classList.add("active");
}

function openLightbox() {
    var modal = document.getElementById("imageLightbox");
    var container = document.getElementById("mainMediaContainer");
    var wrapper = document.getElementById("lightboxContentWrapper");
    
    // Get currently displayed media (img or video)
    var currentMedia = container.querySelector('img, video');
    
    if (currentMedia) {
        wrapper.innerHTML = ''; // Clear previous content
        var clone = currentMedia.cloneNode(true);
        
        // Ensure video controls are enabled in lightbox
        if (clone.tagName === 'VIDEO') {
            clone.controls = true;
            clone.autoplay = true;
            clone.style.width = "100%";
            clone.style.height = "auto";
        } else {
            clone.style.maxHeight = "90vh";
            clone.style.maxWidth = "90vw";
        }
        
        wrapper.appendChild(clone);
        modal.style.display = "block";
        document.body.style.overflow = "hidden"; 
    }
}

function closeLightbox(event) {
    if (event && event.target.className !== "lightbox-overlay" && event.target.className !== "lightbox-close") return;
    document.getElementById("imageLightbox").style.display = "none";
    document.getElementById("lightboxContentWrapper").innerHTML = ''; // Clear content to stop video
    document.body.style.overflow = "auto";
}

document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        document.getElementById("imageLightbox").style.display = "none";
        document.getElementById("lightboxContentWrapper").innerHTML = '';
        document.body.style.overflow = "auto";
    }
});

// Other Scripts
let quantity = 1;
const maxQuantity = <?php echo ($product && isset($product['stock_quantity']) && $product['stock_quantity'] > 0) ? min(10, intval($product['stock_quantity'])) : 10; ?>;
function changeQuantity(change) {
    if (maxQuantity === 0) return;
    quantity = Math.max(1, Math.min(maxQuantity, quantity + change));
    document.getElementById('quantityDisplay').textContent = quantity;
    document.getElementById('quantityInput').value = quantity;
}
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.spec-tab').forEach(btn => btn.classList.remove('active'));
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <span>${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}</span>
        <span>${message}</span>
    `;
    document.body.appendChild(toast);

    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease forwards';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function updateCompareBar() {
    const selected = JSON.parse(localStorage.getItem('compare_ids')) || [];
    const bar = document.getElementById('compareBar');
    const countSpan = document.getElementById('compareCount');
    
    if (selected.length > 0) {
        bar.classList.add('show');
        countSpan.textContent = `${selected.length} Selected`;
    } else {
        bar.classList.remove('show');
    }
}

function goToCompare() {
    const selected = JSON.parse(localStorage.getItem('compare_ids')) || [];
    if (selected.length > 0) {
        window.location.href = 'compare.php?ids=' + selected.join(',');
    } else {
        showToast('No items selected to compare', 'error');
    }
}

function clearCompare() {
    localStorage.removeItem('compare_ids');
    localStorage.removeItem('compare_category');
    updateCompareBar();
    showToast('Comparison list cleared', 'info');
}

function addToCompare(productId, category) {
    let selected = new Set(JSON.parse(localStorage.getItem('compare_ids')) || []);
    let currentCategory = localStorage.getItem('compare_category');

    if (selected.has(String(productId))) {
        showToast('This product is already in your comparison list.', 'info');
        return;
    }

    if (selected.size >= 4) {
        showToast('You can compare up to 4 products.', 'error');
        // Optional: Offer to go to compare page
        return;
    }

    // Check category mismatch
    if (selected.size > 0 && currentCategory && currentCategory !== category) {
        if (confirm(`You are trying to compare a ${category} with a ${currentCategory}. Start a new comparison?`)) {
            selected.clear();
            currentCategory = category;
        } else {
            return; // User cancelled
        }
    } else if (selected.size === 0) {
        currentCategory = category;
    }

    selected.add(String(productId));
    localStorage.setItem('compare_ids', JSON.stringify(Array.from(selected)));
    localStorage.setItem('compare_category', currentCategory);

    showToast('Product added to comparison!', 'success');
    updateCompareBar();
}

// Initialize bar on load
document.addEventListener('DOMContentLoaded', updateCompareBar);

</script>

<!-- Sticky Compare Bar -->
<div class="sticky-compare-bar" id="compareBar">
    <span class="compare-count" id="compareCount">0 Selected</span>
    <button class="btn btn-secondary" onclick="clearCompare()" style="margin-top: 0; padding: 8px 20px; background: #6c757d; color: white; border: none;">
        Clear
    </button>
    <button class="btn btn-primary" onclick="goToCompare()" id="compareNowBtn" style="margin-top: 0; padding: 8px 20px;">
        Compare Now
    </button>
</div>

<?php include 'includes/footer.php'; ?>
