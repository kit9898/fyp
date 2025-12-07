<?php
require_once 'includes/auth_check.php';
include 'includes/header.php';

// 1. DEFINE USER VARIABLES FIRST
$user_id = $_SESSION['user_id'];
$user_pref = 'General Use'; // Default

// Fetch the actual user preference from database
if (isset($conn)) {
    $pref_stmt = $conn->prepare("SELECT primary_use_case FROM users WHERE user_id = ?");
    $pref_stmt->bind_param("i", $user_id);
    $pref_stmt->execute();
    $pref_result = $pref_stmt->get_result();
    if ($row = $pref_result->fetch_assoc()) {
        $user_pref = $row['primary_use_case'];
    }
    $pref_stmt->close();
}

// 2. INITIALIZE ML API
require_once 'includes/recommendation_api.php';
$ml_api = new RecommendationAPI();
$ml_available = false;
$ml_product_ids = [];

// Check if Python API is running
if ($ml_api->healthCheck()) {
    $ml_available = true;
    $ml_recs = $ml_api->getRecommendations($user_id, $user_pref, 12);
    
    if ($ml_recs) {
        $ml_product_ids = array_column($ml_recs, 'product_id');
    }
}

// Determine the view...
$view = $_GET['view'] ?? 'browse';

// --- BROWSE/FILTER LOGIC ---
if ($view == 'browse') {
    $search_term = trim($_GET['search'] ?? '');
    $category_filter = trim($_GET['category'] ?? ''); // NEW: Category Filter
    $brand_filter = trim($_GET['brand'] ?? '');
    $use_case_filter = trim($_GET['use_case'] ?? '');
    $min_price_filter = trim($_GET['min_price'] ?? '');
    $max_price_filter = trim($_GET['max_price'] ?? '');
    $min_ram_filter = trim($_GET['min_ram'] ?? '');
    $sort_filter = trim($_GET['sort'] ?? 'price_asc');
    
    $sql = "SELECT * FROM products";
    $where_clauses = []; 
    $params = []; 
    $types = '';
    
    if (!empty($search_term)) { 
        $where_clauses[] = "(product_name LIKE ? OR brand LIKE ? OR cpu LIKE ? OR gpu LIKE ?)"; 
        $like_term = "%".$search_term."%"; 
        $params = array_merge($params, [$like_term, $like_term, $like_term, $like_term]); 
        $types .= 'ssss'; 
    }
    
    // NEW: Category Logic
    if (!empty($category_filter)) { 
        $where_clauses[] = "product_category = ?"; 
        $params[] = $category_filter; 
        $types .= 's'; 
    }

    if (!empty($brand_filter)) { 
        $where_clauses[] = "brand = ?"; 
        $params[] = $brand_filter; 
        $types .= 's'; 
    }
    if (!empty($use_case_filter)) { 
        $where_clauses[] = "primary_use_case = ?"; 
        $params[] = $use_case_filter; 
        $types .= 's'; 
    }
    if (is_numeric($min_price_filter)) { 
        $where_clauses[] = "price >= ?"; 
        $params[] = $min_price_filter; 
        $types .= 'd'; 
    }
    if (is_numeric($max_price_filter)) { 
        $where_clauses[] = "price <= ?"; 
        $params[] = $max_price_filter; 
        $types .= 'd'; 
    }
    if (is_numeric($min_ram_filter)) { 
        $where_clauses[] = "ram_gb >= ?"; 
        $params[] = $min_ram_filter; 
        $types .= 'i'; 
    }
    
    if (!empty($where_clauses)) { 
        $sql .= " WHERE " . implode(" AND ", $where_clauses); 
    }
    
    // Sort Logic
    $sort_options = [
        'price_asc'  => 'price ASC',
        'price_desc' => 'price DESC',
        'name_asc'   => 'product_name ASC',
        'name_desc'  => 'product_name DESC',
        'ram_desc'   => 'ram_gb DESC',
        'newest'     => 'product_id DESC'
    ];
    
    $sort_sql = $sort_options[$sort_filter] ?? 'price ASC';
    $sql .= " ORDER BY " . $sort_sql;
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) { 
        $stmt->bind_param($types, ...$params); 
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Count total results
    $total_results = $result->num_rows;
}

if ($view == 'recommendations') {
    // Recommendation engine logic (same as before)
    $sql = "SELECT p.*, 
            COALESCE(r.rating, 0) as user_rating,
            (CASE WHEN r.rating = 1 THEN 40 WHEN r.rating IS NULL THEN 20 ELSE 0 END) as rating_score,
            ((CASE WHEN r.rating = 1 THEN 40 WHEN r.rating IS NULL THEN 20 ELSE 0 END) +
             (CASE WHEN p.primary_use_case = ? THEN 30 ELSE 10 END) +
             (CASE WHEN p.price < 1200 THEN 15 ELSE 5 END) +
             (SELECT COUNT(*) * 2 FROM recommendation_ratings rr WHERE rr.product_id = p.product_id AND rr.rating = 1)
            ) as total_recommendation_score
            FROM products p 
            LEFT JOIN recommendation_ratings r ON p.product_id = r.product_id AND r.user_id = ?
            WHERE (" . ($ml_available && !empty($ml_product_ids) ? "p.product_id IN (" . implode(',', array_map('intval', $ml_product_ids)) . ") OR " : "") . "
                p.primary_use_case = ?
            ) AND (r.rating IS NULL OR r.rating != -1)
            ORDER BY total_recommendation_score DESC LIMIT 12";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $user_pref, $user_id, $user_pref);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_results = $result->num_rows;
}

// Get filter options from DB
$brands_query = $conn->query("SELECT DISTINCT brand FROM products ORDER BY brand ASC");
$use_cases_query = $conn->query("SELECT DISTINCT primary_use_case FROM products ORDER BY primary_use_case ASC");
// NEW: Fetch Categories
$categories_query = $conn->query("SELECT DISTINCT product_category FROM products WHERE product_category IS NOT NULL AND product_category != '' ORDER BY product_category ASC");
?>

<link rel="stylesheet" href="css/products.css"> 
<link rel="stylesheet" href="css/ml-enhancements.css"> 
<style>
/* CSS Styles */
.page-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 20px; margin-bottom: 30px; border-radius: 12px; }
.page-header h1 { margin: 0 0 10px 0; font-size: 2.5rem; }
.page-header p { margin: 0; opacity: 0.9; font-size: 1.1rem; }
.tabs { display: flex; gap: 10px; margin-bottom: 30px; border-bottom: 2px solid #e9ecef; }
.tab { padding: 15px 30px; background: none; border: none; color: #666; text-decoration: none; font-weight: 600; font-size: 1rem; border-bottom: 3px solid transparent; transition: all 0.3s; cursor: pointer; }
.tab:hover { color: #3b82f6; }
.tab.active { color: #3b82f6; border-bottom-color: #3b82f6; }
.filter-section { background: white; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); margin-bottom: 30px; overflow: hidden; }
.search-bar-wrapper { background: #f8f9fa; padding: 25px; border-bottom: 1px solid #e9ecef; }
.search-input-container { position: relative; max-width: 100%; }
.search-input-container input { width: 100%; padding: 14px 50px 14px 20px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; transition: all 0.3s; background: white; }
.search-input-container input:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
.search-icon { position: absolute; right: 18px; top: 50%; transform: translateY(-50%); font-size: 1.2rem; color: #667eea; pointer-events: none; }
.filters-container { padding: 25px; }
.filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 20px; }
.filter-group { display: flex; flex-direction: column; }
.filter-group label { font-weight: 600; margin-bottom: 8px; color: #495057; font-size: 0.85rem; display: flex; align-items: center; gap: 6px; }
.filter-group input, .filter-group select { padding: 11px 14px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s; background: white; }
.filter-group select { cursor: pointer; }
.price-range-inputs { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.filter-actions { display: flex; gap: 12px; justify-content: flex-end; padding-top: 15px; border-top: 1px solid #e9ecef; }
.filter-actions .btn { padding: 12px 28px; border-radius: 8px; font-weight: 600; transition: all 0.3s; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
.filter-actions .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.filter-toggle { display: none; margin-bottom: 15px; }
.results-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
.results-info { font-size: 1.1rem; color: #495057; }
.product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; margin-bottom: 40px; }
.product-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s; position: relative; }
.product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
.product-card img { width: 100%; height: 250px; object-fit: cover; }
.product-badge { position: absolute; top: 15px; right: 15px; padding: 6px 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px; font-size: 0.75rem; font-weight: 600; z-index: 1; }
.quick-view-badge { position: absolute; top: 15px; left: 15px; padding: 6px 12px; background: rgba(0,0,0,0.7); color: white; border-radius: 20px; font-size: 0.75rem; font-weight: 600; z-index: 1; opacity: 0; transition: opacity 0.3s; }
.product-card:hover .quick-view-badge { opacity: 1; }
.product-card-info { padding: 20px; }
.brand { color: #666; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
.product-card h3 { font-size: 1.1rem; margin: 8px 0; color: #1a1a1a; line-height: 1.4; }
.product-specs { display: flex; flex-wrap: wrap; gap: 8px; margin: 10px 0; }
.spec-tag { padding: 4px 10px; background: #f8f9fa; border-radius: 6px; font-size: 0.75rem; color: #666; }
.product-price { font-size: 1.4rem; font-weight: 700; color: #667eea; margin-top: 10px; }
.product-actions { padding: 0 20px 20px 20px; display: flex; gap: 10px; }
.quick-add-btn { flex: 1; padding: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
.compare-section { padding: 15px 20px; background: #f8f9fa; border-top: 1px solid #e9ecef; }
.compare-checkbox-container { display: flex; align-items: center; gap: 8px; }
.compare-checkbox { width: 18px; height: 18px; cursor: pointer; }
.sticky-compare-bar { position: fixed; bottom: 20px; right: 20px; background: white; padding: 15px 25px; border-radius: 50px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); display: none; align-items: center; gap: 15px; z-index: 1000; animation: slideUp 0.3s; }
.sticky-compare-bar.show { display: flex; }
@keyframes slideUp { from { transform: translateY(100px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
.compare-count { padding: 6px 12px; background: #3b82f6; color: white; border-radius: 20px; font-weight: 600; font-size: 0.9rem; }
.rating-form { padding: 15px 20px; display: flex; justify-content: center; gap: 15px; background: #f8f9fa; border-top: 1px solid #e9ecef; }
.rating-btn { padding: 8px 20px; background: white; border: 2px solid #e9ecef; border-radius: 25px; cursor: pointer; transition: all 0.3s; font-size: 1.2rem; }
.rating-btn.active { background: #3b82f6; border-color: #3b82f6; transform: scale(1.15); }
.empty-state { text-align: center; padding: 80px 20px; background: #f8f9fa; border-radius: 12px; }
.empty-state-icon { font-size: 4rem; margin-bottom: 20px; }
.recommendation-reason { position: absolute; top: 15px; left: 15px; padding: 6px 12px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 20px; font-size: 0.75rem; font-weight: 600; z-index: 1; }

@media (max-width: 768px) {
    .filter-grid { grid-template-columns: 1fr; }
    .filter-toggle { display: block; }
    .filter-section.collapsed .search-bar-wrapper, .filter-section.collapsed .filters-container { display: none; }
}
</style>

<?php if ($view == 'browse'): ?>
    <div class="page-header">
        <h1>Explore Our Collection</h1>
        <p>Find the perfect laptop & accessories from our curated selection</p>
    </div>
<?php else: ?>
    <div class="page-header">
        <h1>✨ Personalized For You</h1>
        <p>Handpicked recommendations based on your interests in <strong><?php echo htmlspecialchars($user_pref); ?></strong></p>
        <?php if (isset($ml_available) && $ml_available): ?>
            <div style="margin-top: 15px;">
                <span style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4); padding: 5px 12px; border-radius: 20px; font-size: 0.9rem;">
                    🤖 AI-Powered by Python ML
                </span>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="tabs">
    <a href="?view=browse" class="tab <?php if ($view == 'browse') echo 'active'; ?>">
        💻 Browse All Products
    </a>
    <a href="?view=recommendations" class="tab <?php if ($view == 'recommendations') echo 'active'; ?>">
        💻 For You
    </a>
</div>

<?php if ($view == 'browse'): ?>
    <button class="btn filter-toggle" onclick="toggleFilters()">
        🎚️ Filters
    </button>
    
    <div class="filter-section" id="filterSection">
        <form action="products.php" method="get" id="filterForm">
            <input type="hidden" name="view" value="browse">
            
            <div class="search-bar-wrapper">
                <div class="search-input-container">
                    <input type="text" id="search" name="search" 
                           placeholder="Search for laptops, brands, accessories..." 
                           value="<?php echo htmlspecialchars($search_term); ?>">
                    <span class="search-icon">🔍</span>
                </div>
            </div>
            
            <div class="filters-container">
                <div class="filter-grid">
                    <!-- NEW CATEGORY FILTER -->
                    <div class="filter-group">
                        <label for="category">📁 Category</label>
                        <select id="category" name="category">
                            <option value="">All Categories</option>
                            <?php 
                            if ($categories_query) {
                                while ($cat_row = $categories_query->fetch_assoc()): 
                                    $cat_val = $cat_row['product_category'];
                                    // Icons mapping
                                    $icon = '📦';
                                    if(strpos($cat_val, 'laptop') !== false) $icon = '💻';
                                    elseif(strpos($cat_val, 'mouse') !== false) $icon = '🖱️';
                                    elseif(strpos($cat_val, 'keyboard') !== false) $icon = '⌨️';
                                    elseif(strpos($cat_val, 'headphone') !== false || strpos($cat_val, 'headset') !== false) $icon = '🎧';
                                    elseif(strpos($cat_val, 'bag') !== false || strpos($cat_val, 'backpack') !== false) $icon = '🎒';
                                ?>
                                <option value="<?php echo htmlspecialchars($cat_val); ?>"
                                        <?php if ($category_filter == $cat_val) echo 'selected'; ?>>
                                    <?php echo $icon . ' ' . ucwords(str_replace('_', ' ', $cat_val)); ?>
                                </option>
                            <?php endwhile; 
                            } ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="brand">🏷️ Brand</label>
                        <select id="brand" name="brand">
                            <option value="">All Brands</option>
                            <?php while ($brand_row = $brands_query->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($brand_row['brand']); ?>"
                                        <?php if ($brand_filter == $brand_row['brand']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($brand_row['brand']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="use_case">💼 Use Case</label>
                        <select id="use_case" name="use_case">
                            <option value="">All Use Cases</option>
                            <?php while ($case_row = $use_cases_query->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($case_row['primary_use_case']); ?>"
                                        <?php if ($use_case_filter == $case_row['primary_use_case']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($case_row['primary_use_case']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="min_ram">💾 Minimum RAM (Laptops)</label>
                        <select id="min_ram" name="min_ram">
                            <option value="">Any</option>
                            <option value="4" <?php if ($min_ram_filter == '4') echo 'selected'; ?>>4 GB+</option>
                            <option value="8" <?php if ($min_ram_filter == '8') echo 'selected'; ?>>8 GB+</option>
                            <option value="16" <?php if ($min_ram_filter == '16') echo 'selected'; ?>>16 GB+</option>
                            <option value="32" <?php if ($min_ram_filter == '32') echo 'selected'; ?>>32 GB+</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>💰 Price Range</label>
                        <div class="price-range-inputs">
                            <input type="number" name="min_price" placeholder="Min $" 
                                   value="<?php echo htmlspecialchars($min_price_filter); ?>" min="0">
                            <input type="number" name="max_price" placeholder="Max $" 
                                   value="<?php echo htmlspecialchars($max_price_filter); ?>" min="0">
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <label for="sort">📊 Sort By</label>
                        <select id="sort" name="sort">
                            <option value="price_asc" <?php if($sort_filter == 'price_asc') echo 'selected'; ?>>
                                Price: Low to High
                            </option>
                            <option value="price_desc" <?php if($sort_filter == 'price_desc') echo 'selected'; ?>>
                                Price: High to Low
                            </option>
                            <option value="name_asc" <?php if($sort_filter == 'name_asc') echo 'selected'; ?>>
                                Name: A-Z
                            </option>
                            <option value="ram_desc" <?php if($sort_filter == 'ram_desc') echo 'selected'; ?>>
                                RAM: High to Low
                            </option>
                            <option value="newest" <?php if($sort_filter == 'newest') echo 'selected'; ?>>
                                Newest First
                            </option>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        ✓ Apply Filters
                    </button>
                    <a href="products.php?view=browse" class="btn" style="background-color:#6c757d; color: white;">
                        ↺ Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
    
    <div class="results-header">
        <div class="results-info">
            Showing <strong><?php echo $total_results; ?></strong> 
            <?php echo $total_results == 1 ? 'product' : 'products'; ?>
        </div>
        <!-- List View Buttons Removed as requested -->
    </div>
    
    <?php if ($total_results > 0): ?>
        <div class="product-grid" id="productGrid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <span class="quick-view-badge">👁️ Quick View</span>
                    <?php if ($row['primary_use_case'] == 'Gaming'): ?>
                        <span class="product-badge">🎮 Gaming</span>
                    <?php elseif (isset($row['ram_gb']) && $row['ram_gb'] >= 32): ?>
                        <span class="product-badge">⚡ High Performance</span>
                    <?php endif; ?>
                    
                    <a href="product_details.php?product_id=<?php echo $row['product_id']; ?>">
                        <?php 
                        $image_src = 'https://via.placeholder.com/280x250?text=No+Image';
                        if (!empty($row['image_url'])) {
                            $img_url = $row['image_url'];
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
                             alt="<?php echo htmlspecialchars($row['product_name']); ?>"
                             onerror="this.src='https://via.placeholder.com/280x250?text=No+Image'">
                        <div class="product-card-info">
                            <p class="brand"><?php echo htmlspecialchars($row['brand']); ?></p>
                            <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                            
                            <!-- Modified specs display to handle accessories gracefully -->
                            <div class="product-specs">
                                <?php if($row['product_category'] == 'laptop' || empty($row['product_category'])): ?>
                                    <span class="spec-tag">💾 <?php echo $row['ram_gb']; ?>GB</span>
                                    <span class="spec-tag">💿 <?php echo $row['storage_gb']; ?>GB</span>
                                    <span class="spec-tag">📺 <?php echo $row['display_size']; ?>"</span>
                                <?php else: ?>
                                    <span class="spec-tag">📦 <?php echo ucwords(str_replace('_', ' ', $row['product_category'])); ?></span>
                                    <span class="spec-tag">✨ <?php echo htmlspecialchars($row['primary_use_case']); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <p class="product-price">$<?php echo number_format($row['price'], 2); ?></p>
                        </div>
                    </a>
                    
                    <div class="product-actions">
                        <button class="quick-add-btn" onclick="quickAddToCart(<?php echo $row['product_id']; ?>)">
                           🛒 Add to Cart
                        </button>
                    </div>
                    
                    <div class="compare-section">
                        <div class="compare-checkbox-container">
                            <input type="checkbox" class="compare-checkbox" 
                                   data-id="<?php echo $row['product_id']; ?>" 
                                   id="compare-<?php echo $row['product_id']; ?>"
                                   onchange="updateCompareBar()">
                            <label for="compare-<?php echo $row['product_id']; ?>">
                              ⚖️ Add to Compare
                            </label>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">🔍</div>
            <h3>No Products Found</h3>
            <p>We couldn't find any products matching your criteria.</p>
            <a href="products.php?view=browse" class="btn btn-primary">Clear Filters</a>
        </div>
    <?php endif; ?>
    
    <div class="sticky-compare-bar" id="compareBar">
        <span class="compare-count" id="compareCount">0 Selected</span>
        <button class="btn btn-primary" onclick="goToCompare()" id="compareNowBtn">
            Compare Now
        </button>
    </div>
<?php else: ?>
    <?php if ($total_results > 0): ?>
        <div class="product-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card recommendation-card">
                    <span class="recommendation-reason">
                        ✨ Recommended for <?php echo htmlspecialchars($user_pref); ?>
                    </span>
                    
                    <a href="product_details.php?product_id=<?php echo $row['product_id']; ?>">
                        <?php 
                        $rec_image_src = 'https://via.placeholder.com/280x250?text=No+Image';
                        if (!empty($row['image_url'])) {
                            $rec_img_url = $row['image_url'];
                            if (strpos($rec_img_url, 'http') === 0) {
                                $rec_image_src = $rec_img_url;
                            } elseif (strpos($rec_img_url, 'LaptopAdvisor/') === 0) {
                                $rec_image_src = '../' . $rec_img_url;
                            } elseif (strpos($rec_img_url, 'images/') === 0) {
                                $rec_image_src = $rec_img_url;
                            } else {
                                $rec_image_src = 'images/' . basename($rec_img_url);
                            }
                        }
                        ?>
                        <img src="<?php echo htmlspecialchars($rec_image_src); ?>" 
                             alt="<?php echo htmlspecialchars($row['product_name']); ?>"
                             onerror="this.src='https://via.placeholder.com/280x250?text=No+Image'">
                        <div class="product-card-info">
                            <p class="brand"><?php echo htmlspecialchars($row['brand']); ?></p>
                            <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                            
                            <div class="product-specs">
                                <?php if($row['product_category'] == 'laptop' || empty($row['product_category'])): ?>
                                    <span class="spec-tag">💾 <?php echo $row['ram_gb']; ?>GB</span>
                                    <span class="spec-tag">💿 <?php echo $row['storage_gb']; ?>GB</span>
                                <?php else: ?>
                                    <span class="spec-tag">📦 Accessory</span>
                                <?php endif; ?>
                            </div>
                            
                            <p class="product-price">$<?php echo number_format($row['price'], 2); ?></p>
                        </div>
                    </a>
                    
                    <div class="rating-form">
                        <form action="rate_recommendation.php" method="post" style="display: contents;">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <button type="submit" name="rating" value="1" 
                                    class="rating-btn <?php if(isset($row['user_rating']) && $row['user_rating'] == 1) echo 'active'; ?>"
                                    title="I like this">
                                👍
                            </button>
                            <button type="submit" name="rating" value="-1" 
                                    class="rating-btn <?php if(isset($row['user_rating']) && $row['user_rating'] == -1) echo 'active'; ?>"
                                    title="Not interested">
                                👎
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">✨</div>
            <h3>No Recommendations Yet</h3>
            <p>We need to know your interests better. Please set your primary use case in your profile.</p>
            <a href="edit_profile.php" class="btn btn-primary">Update Profile</a>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if (isset($stmt)) $stmt->close(); ?>

<script>
// JS for Filters and Cart (View Switcher Removed)

function toggleFilters() {
    const filterSection = document.getElementById('filterSection');
    filterSection.classList.toggle('collapsed');
}

// Compare functionality
let selectedProducts = new Set();

function updateCompareBar() {
    const checkboxes = document.querySelectorAll('.compare-checkbox:checked');
    selectedProducts.clear();
    checkboxes.forEach(cb => selectedProducts.add(cb.dataset.id));
    
    const compareBar = document.getElementById('compareBar');
    const compareCount = document.getElementById('compareCount');
    const compareBtn = document.getElementById('compareNowBtn');
    
    if (selectedProducts.size > 0) {
        compareBar.classList.add('show');
        compareCount.textContent = `${selectedProducts.size} Selected`;
        
        if (selectedProducts.size < 2) {
            compareBtn.disabled = true;
            compareBtn.style.opacity = '0.5';
            compareBtn.textContent = 'Select 2+ to Compare';
        } else if (selectedProducts.size > 4) {
            compareBtn.disabled = true;
            compareBtn.style.opacity = '0.5';
            compareBtn.textContent = 'Max 4 Products';
        } else {
            compareBtn.disabled = false;
            compareBtn.style.opacity = '1';
            compareBtn.textContent = `Compare ${selectedProducts.size} Products`;
        }
    } else {
        compareBar.classList.remove('show');
    }
}

function goToCompare() {
    if (selectedProducts.size >= 2 && selectedProducts.size <= 4) {
        const ids = Array.from(selectedProducts).join(',');
        window.location.href = `compare.php?ids=${ids}`;
    } else {
        alert('Please select 2 to 4 products to compare.');
    }
}

function clearCompare() {
    selectedProducts.clear();
    document.querySelectorAll('.compare-checkbox').forEach(cb => cb.checked = false);
    updateCompareBar();
}

function quickAddToCart(productId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'cart_process.php';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'add';
    
    const productInput = document.createElement('input');
    productInput.type = 'hidden';
    productInput.name = 'product_id';
    productInput.value = productId;
    
    const quantityInput = document.createElement('input');
    quantityInput.type = 'hidden';
    quantityInput.name = 'quantity';
    quantityInput.value = '1';
    
    form.appendChild(actionInput);
    form.appendChild(productInput);
    form.appendChild(quantityInput);
    document.body.appendChild(form);
    form.submit();
}

if (window.location.search) {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function showActiveFilters() {
    const params = new URLSearchParams(window.location.search);
    let activeCount = 0;
    ['search', 'category', 'brand', 'use_case', 'min_price', 'max_price', 'min_ram'].forEach(param => {
        if (params.get(param)) activeCount++;
    });
    
    if (activeCount > 0) {
        const filterToggle = document.querySelector('.filter-toggle');
        if (filterToggle) {
            filterToggle.innerHTML = `🎚️ Filters (${activeCount} active)`;
            filterToggle.style.background = '#3b82f6';
            filterToggle.style.color = 'white';
        }
    }
}
showActiveFilters();

document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.getElementById('search');
        if (searchInput) { searchInput.focus(); searchInput.select(); }
    }
    if (e.key === 'Escape') {
        const searchInput = document.getElementById('search');
        if (searchInput && document.activeElement === searchInput) { searchInput.value = ''; }
    }
});

// ============================================
// PRODUCT CLICK TRACKING & VOUCHER SYSTEM
// ============================================

let productClicks = JSON.parse(localStorage.getItem('productClicks')) || {};
const CLICK_THRESHOLD = 3; 

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.product-card a, .quick-add-btn').forEach(element => {
        const card = element.closest('.product-card');
        if (!card) return;
        
        let productId = null;
        const compareCheckbox = card.querySelector('.compare-checkbox');
        const addButton = card.querySelector('.quick-add-btn');
        
        if (compareCheckbox) {
            productId = compareCheckbox.dataset.id;
        } else if (addButton) {
            const onclickAttr = addButton.getAttribute('onclick');
            const match = onclickAttr ? onclickAttr.match(/quickAddToCart\((\d+)\)/) : null;
            if (match) productId = match[1];
        }
        
        if (productId && element.tagName === 'A') {
            element.addEventListener('click', function(e) {
                trackProductClick(productId, card);
            });
        }
    });
});

function trackProductClick(productId, cardElement) {
    const now = Date.now();
    const productName = cardElement.querySelector('h3')?.textContent || 'Product';
    const brand = cardElement.querySelector('.brand')?.textContent || '';
    
    if (!productClicks[productId]) {
        productClicks[productId] = { 
            count: 0, 
            lastClick: now,
            name: productName,
            brand: brand
        };
    }
    
    productClicks[productId].count++;
    productClicks[productId].lastClick = now;
    productClicks[productId].name = productName;
    
    localStorage.setItem('productClicks', JSON.stringify(productClicks));
    
    if (productClicks[productId].count === CLICK_THRESHOLD) {
        generateVoucher(productId, productName, brand);
    }
}

function generateVoucher(productId, productName, brand) {
    const loadingToast = showToast('Generating your reward voucher...', 'info');
    
    const formData = new FormData();
    formData.append('product_id', productId);

    fetch('generate_voucher.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (loadingToast) loadingToast.remove();
        
        if (data.success) {
            productClicks[productId].count = 0;
            localStorage.setItem('productClicks', JSON.stringify(productClicks));
            showVoucherPopup(data.voucher, data.existing || false, true); 
        } else {
            showToast('Error: ' + (data.error || 'Failed to generate voucher'), 'error');
        }
    })
    .catch(error => {
        if (loadingToast) loadingToast.remove();
        console.error('Voucher generation error:', error);
        showToast('Network error. Please try again.', 'error');
    });
}

function showVoucherPopup(voucher, isExisting, isAutoApplied = false) {
    const modal = document.createElement('div');
    modal.id = 'voucherModal';
    modal.style.cssText = `
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.7); display: flex; align-items: center; justify-content: center;
        z-index: 9999; animation: fadeIn 0.3s ease;
    `;
    
    const modalContent = document.createElement('div');
    modalContent.style.cssText = `
        background: white; padding: 2.5rem; border-radius: 20px;
        max-width: 500px; width: 90%; text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideUp 0.4s ease;
        position: relative;
    `;
    
    const title = isExisting ? '🎉 Voucher Already Active!' : '🎉 Discount Unlocked!';
    let message = 'You unlocked a special discount!';
    let subMessage = 'Valid until used (saved to your account)';
    
    if (isAutoApplied) {
        message = 'We noticed you like this product!';
        subMessage = '<span style="color:#28a745; font-weight:bold;">✅ Voucher automatically applied to your cart!</span>';
    }

    modalContent.innerHTML = `
        <button onclick="closeVoucherModal()" style="position: absolute; top: 15px; right: 15px; background: transparent; border: none; font-size: 1.5rem; cursor: pointer; color: #999;">×</button>
        
        <div style="font-size: 3rem; margin-bottom: 1rem;">🎁</div>
        <h2 style="color: #667eea; margin-bottom: 0.5rem; font-size: 1.8rem;">${title}</h2>
        <p style="color: #666; margin-bottom: 1.5rem; font-size: 1.1rem;">${message}</p>
        
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
            <div style="color: rgba(255,255,255,0.9); font-size: 0.9rem; margin-bottom: 0.5rem;">YOUR VOUCHER CODE</div>
            <div id="voucherCode" style="background: white; color: #667eea; padding: 1rem; border-radius: 8px; font-size: 1.5rem; font-weight: bold; letter-spacing: 2px; margin-bottom: 0.5rem; font-family: monospace;">${voucher.code}</div>
            <div style="color: white; font-size: 1.2rem; font-weight: 600;">
                ${voucher.discount_value}% OFF
            </div>
        </div>
        
        <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: left;">
            <div style="font-size: 0.85rem; color: #666; margin-bottom: 0.5rem;">
                <strong>Product:</strong> ${voucher.brand || ''} ${voucher.product_name}
            </div>
            <div style="font-size: 0.85rem; color: #666;">
                ${subMessage}
            </div>
        </div>
        
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button onclick="copyVoucherCode('${voucher.code}')" style="flex: 1; padding: 12px 24px; background: #f8f9fa; border: 2px solid #e9ecef; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s;">📋 Copy Code</button>
            <a href="cart.php" style="flex: 1; padding: 12px 24px; background: linear-gradient(135deg, #28a745 0%, #218838 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                🛒 Checkout Now
            </a>
        </div>
    `;
    
    modal.appendChild(modalContent);
    document.body.appendChild(modal);
    
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    `;
    document.head.appendChild(style);
    
    try {
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIHmS57+mfTgwOUKXh8LdhGwU4jtfyzHkqBS51yPDdlksTFFyz6eqmUxUKRp/g8r5rHgUrgs/y2Yk1CCBiuO/pn04MDlCl4PG3YhsGOI/X8sx5KgUudcjw3JdLExRcs+nqpVMVCkaR6+euUw==');
        audio.volume = 0.3;
        audio.play().catch(() => {}); 
    } catch(e) {}
}

function closeVoucherModal() {
    const modal = document.getElementById('voucherModal');
    if (modal) {
        modal.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => modal.remove(), 300);
    }
}

function copyVoucherCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        showToast('✓ Voucher code copied!', 'success');
    }).catch(() => {
        const temp = document.createElement('textarea');
        temp.value = code;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);
        showToast('✓ Voucher code copied!', 'success');
    });
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    const bgColors = { 'success': '#28a745', 'error': '#dc3545', 'info': '#17a2b8' };
    
    toast.style.cssText = `
        position: fixed; top: 20px; right: 20px; background: ${bgColors[type] || bgColors.info};
        color: white; padding: 1rem 1.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 10000; animation: slideInRight 0.3s ease; font-weight: 600;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
    return toast;
}

const toastStyle = document.createElement('style');
toastStyle.textContent = `
    @keyframes slideInRight { from { transform: translateX(400px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes slideOutRight { from { transform: translateX(0); opacity: 1; } to { transform: translateX(400px); opacity: 0; } }
    @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
`;
document.head.appendChild(toastStyle);
</script>

<?php include 'includes/footer.php'; ?>