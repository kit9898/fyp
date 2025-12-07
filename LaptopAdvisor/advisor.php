<?php
require_once 'includes/auth_check.php';
include 'includes/header.php';

// --- Multi-Step Quiz Logic ---
$step = isset($_GET['step']) ? (int)$_GET['step'] : 0;
$category = $_GET['category'] ?? 'Laptop'; // Default to Laptop if not set

// Determine Total Steps based on Category
if ($category === 'Accessories') {
    $total_steps = 3; // 1: Category, 2: Type, 3: Budget
} else {
    $total_steps = 6; // 1: Category, 2: Use Case, 3: Perf, 4: Screen, 5: Portability, 6: Budget
}

$accessory_type = $_GET['accessory_type'] ?? '';
$use_case = $_GET['use_case'] ?? '';
$performance = $_GET['performance'] ?? '';
$screen_size = $_GET['screen_size'] ?? '';
$portability = $_GET['portability'] ?? '';
$budget = $_GET['budget'] ?? '1500';

$results = [];

if ($step > $total_steps) {
    // --- RECOMMENDATION ENGINE ---
    
    if ($category === 'Accessories') {
        // --- ACCESSORY LOGIC ---
        $sql = "SELECT p.*, 
                COALESCE(r.rating, 0) as user_rating,
                (SELECT COUNT(*) FROM recommendation_ratings rr WHERE rr.product_id = p.product_id AND rr.rating = 1) as popularity_bonus
                FROM products p 
                LEFT JOIN recommendation_ratings r ON p.product_id = r.product_id AND r.user_id = ?
                WHERE p.product_category != 'Laptop'
        ";
        
        $params = [$_SESSION['user_id']];
        $types = "i";

        // Filter by Type (Loose match to catch 'Gaming Mouse', 'Wireless Mouse' etc.)
        if (!empty($accessory_type)) {
            $sql .= " AND (p.product_category LIKE ? OR p.product_name LIKE ?)";
            $type_param = "%" . $accessory_type . "%";
            $params[] = $type_param;
            $params[] = $type_param;
            $types .= "ss";
        }

        // Filter by Budget
        $sql .= " AND p.price <= ?";
        $params[] = $budget;
        $types .= "d";

        // Sort by Rating/Popularity/Price
        $sql .= " ORDER BY popularity_bonus DESC, user_rating DESC, p.price DESC LIMIT 6";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result_set = $stmt->get_result();
        
        while($row = $result_set->fetch_assoc()) {
            // Simple score for accessories just to reuse the display logic if needed, 
            // or we can just show them.
            $row['total_score'] = 100; // Default match score for simple filter
            $results[] = $row;
        }
        $stmt->close();

    } else {
        // --- LAPTOP LOGIC (Advanced) ---
        
        // Base SQL
        $sql = "SELECT p.*, 
                COALESCE(r.rating, 0) as user_rating,
                
                -- 1. Use Case Score (30%)
                (CASE 
                    WHEN p.primary_use_case = ? THEN 30
                    WHEN ? = 'General Use' AND p.primary_use_case IN ('Business', 'Student') THEN 20
                    WHEN ? = 'Student' AND p.primary_use_case IN ('General Use', 'Business') THEN 20
                    WHEN ? = 'Business' AND p.primary_use_case IN ('General Use', 'Student') THEN 20
                    WHEN ? = 'Gaming' AND p.primary_use_case = 'Creative' THEN 15
                    WHEN ? = 'Creative' AND p.primary_use_case = 'Gaming' THEN 15
                    ELSE 5
                END) as use_case_score,

                -- 2. Performance Score (25%)
                (CASE 
                    WHEN ? = 'heavy' THEN 
                        (CASE WHEN p.ram_gb >= 16 THEN 25 WHEN p.ram_gb >= 12 THEN 15 ELSE 0 END)
                    WHEN ? = 'everyday' THEN 
                        (CASE WHEN p.ram_gb >= 8 AND p.ram_gb <= 16 THEN 25 WHEN p.ram_gb >= 8 THEN 20 ELSE 5 END)
                    WHEN ? = 'light' THEN 
                        (CASE WHEN p.ram_gb <= 8 THEN 25 ELSE 15 END)
                    ELSE 10
                END) as perf_score,

                -- 3. Screen/Portability Score (25%)
                (CASE 
                    WHEN ? = 'small' THEN 
                        (CASE WHEN p.display_size < 14 THEN 25 WHEN p.display_size <= 14.5 THEN 15 ELSE 0 END)
                    WHEN ? = 'medium' THEN 
                        (CASE WHEN p.display_size >= 14 AND p.display_size < 16 THEN 25 ELSE 10 END)
                    WHEN ? = 'large' THEN 
                        (CASE WHEN p.display_size >= 16 THEN 25 WHEN p.display_size >= 15 THEN 15 ELSE 0 END)
                    ELSE 10
                END) as screen_score,

                -- 4. Value Score (20%)
                (CASE 
                    WHEN p.price <= ? THEN 20
                    WHEN p.price <= (? * 1.1) THEN 10
                    ELSE 0
                END) as value_score,

                -- Popularity Bonus (Extra 5 points)
                (SELECT COUNT(*) FROM recommendation_ratings rr WHERE rr.product_id = p.product_id AND rr.rating = 1) as popularity_bonus

                FROM products p 
                LEFT JOIN recommendation_ratings r ON p.product_id = r.product_id AND r.user_id = ?
                WHERE 1=1
        ";

        // Params for the scoring logic
        $params = [
            $use_case, $use_case, $use_case, $use_case, $use_case, $use_case, // Use Case
            $performance, $performance, $performance, // Performance
            $screen_size, $screen_size, $screen_size, // Screen
            $budget, $budget, // Value
            $_SESSION['user_id'] // User ID for ratings
        ];
        $types = "ssssssssssssddi";

        // Hard Filter: Don't show products way over budget (allow 20% flex)
        $sql .= " AND p.price <= ?";
        $params[] = $budget * 1.2;
        $types .= "d";

        // Category Filter
        $sql .= " AND p.product_category = 'Laptop'";

        $sql .= " ORDER BY (use_case_score + perf_score + screen_score + value_score + popularity_bonus) DESC, p.price ASC LIMIT 6";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result_set = $stmt->get_result();
        
        while($row = $result_set->fetch_assoc()) {
            $row['total_score'] = $row['use_case_score'] + $row['perf_score'] + $row['screen_score'] + $row['value_score'] + $row['popularity_bonus'];
            $results[] = $row;
        }
        $stmt->close();
    }
}
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* Modern Design System */
:root {
    --primary: #4f46e5;
    --primary-hover: #4338ca;
    --bg-body: #f9fafb;
    --bg-card: #ffffff;
    --text-main: #111827;
    --text-muted: #6b7280;
    --border: #e5e7eb;
}

body {
    background-color: var(--bg-body);
    font-family: 'Inter', sans-serif;
    color: var(--text-main);
}

.advisor-wrapper {
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 20px;
}

/* Progress Bar */
.progress-container {
    margin-bottom: 40px;
}

.progress-track {
    background: #e5e7eb;
    height: 8px;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    background: var(--primary);
    height: 100%;
    transition: width 0.4s ease;
}

.progress-labels {
    display: flex;
    justify-content: space-between;
    margin-top: 8px;
    font-size: 0.875rem;
    color: var(--text-muted);
    font-weight: 500;
}

/* Quiz Card */
.quiz-card {
    background: var(--bg-card);
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    padding: 40px;
    text-align: center;
    transition: all 0.3s ease;
}

.quiz-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: var(--text-main);
}

.quiz-subtitle {
    color: var(--text-muted);
    font-size: 1.1rem;
    margin-bottom: 40px;
}

/* Selection Grid */
.selection-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    text-align: left;
}

.option-card {
    position: relative;
    cursor: pointer;
}

.option-card input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.option-content {
    background: #ffffff;
    border: 2px solid var(--border);
    border-radius: 16px;
    padding: 24px;
    height: 100%;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.option-card input:checked + .option-content {
    border-color: var(--primary);
    background: #eef2ff;
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}

.option-icon {
    font-size: 2.5rem;
    margin-bottom: 16px;
}

.option-title {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 8px;
    color: var(--text-main);
}

.option-desc {
    font-size: 0.9rem;
    color: var(--text-muted);
    line-height: 1.4;
}

/* Range Slider */
.range-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px 0;
}

.range-value {
    font-size: 3rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 20px;
    display: block;
}

input[type=range] {
    width: 100%;
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    outline: none;
    -webkit-appearance: none;
}

input[type=range]::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 28px;
    height: 28px;
    background: var(--primary);
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    transition: transform 0.1s;
}

input[type=range]::-webkit-slider-thumb:hover {
    transform: scale(1.1);
}

/* Results Page */
.results-header {
    text-align: center;
    margin-bottom: 40px;
}

.results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.result-card {
    background: var(--bg-card);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
    border: 1px solid var(--border);
    display: flex;
    flex-direction: column;
}

.result-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.top-match-badge {
    background: #10b981;
    color: white;
    text-align: center;
    padding: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    letter-spacing: 0.05em;
}

.result-img {
    width: 100%;
    height: 220px;
    object-fit: contain;
    padding: 20px;
    background: #f9fafb;
}

.result-body {
    padding: 24px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.match-score {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 12px;
}

.score-high { background: #d1fae5; color: #065f46; }
.score-med { background: #e0e7ff; color: #3730a3; }

.result-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0 0 4px 0;
    color: var(--text-main);
}

.result-brand {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin-bottom: 16px;
}

.specs-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 20px;
}

.spec-tag {
    background: #f3f4f6;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.8rem;
    color: #4b5563;
    font-weight: 500;
}

.result-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-main);
    margin-top: auto;
    margin-bottom: 16px;
}

.btn-view {
    display: block;
    width: 100%;
    padding: 12px;
    background: var(--primary);
    color: white;
    text-align: center;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.2s;
}

.btn-view:hover {
    background: var(--primary-hover);
}

.btn-restart {
    display: inline-block;
    margin-top: 40px;
    padding: 12px 30px;
    background: white;
    border: 1px solid var(--border);
    color: var(--text-main);
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-restart:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

/* Rationale Styles */
.rationale-box {
    background: #f3f4f6;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 16px;
}

.rationale-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-main);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.rationale-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.rationale-item {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 4px;
    padding-left: 16px;
    position: relative;
}

.rationale-item::before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #10b981;
    font-weight: bold;
}
</style>

<div class="advisor-wrapper">
    
    <?php if ($step <= $total_steps): ?>
        <!-- PROGRESS BAR -->
        <div class="progress-container">
            <div class="progress-track">
                <div class="progress-fill" style="width: <?php echo ($step / $total_steps) * 100; ?>%;"></div>
            </div>
            <div class="progress-labels">
                <span>Start</span>
                <span>Step <?php echo $step; ?> of <?php echo $total_steps; ?></span>
                <span>Results</span>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($step == 0): ?>
        <!-- STEP 0: WELCOME -->
        <div class="quiz-card">
            <div style="font-size: 4rem; margin-bottom: 20px;">🤖</div>
            <h1 class="quiz-title">Let's Find Your Perfect Match</h1>
            <p class="quiz-subtitle">Answer a few quick questions and our AI-powered engine will find the best product for your needs.</p>
            
            <a href="?step=1" class="btn-view" style="max-width: 200px; margin: 0 auto; display: inline-block;">Start Advisor</a>
        </div>

    <?php elseif ($step == 1): ?>
        <!-- STEP 1: CATEGORY SELECTION -->
        <div class="quiz-card">
            <h2 class="quiz-title">What are you looking for?</h2>
            <p class="quiz-subtitle">We'll tailor the questions based on your product type.</p>
            
            <div class="selection-grid" style="justify-content: center;">
                <!-- Laptop Option -->
                <form action="advisor.php" method="GET" style="display: contents;">
                    <input type="hidden" name="step" value="2">
                    <input type="hidden" name="category" value="Laptop">
                    <label class="option-card">
                        <button type="submit" style="display: none;"></button>
                        <div class="option-content" onclick="this.previousElementSibling.click()">
                            <div class="option-icon">💻</div>
                            <div class="option-title">Laptop</div>
                            <div class="option-desc">I need a new computer for work, gaming, or school.</div>
                        </div>
                    </label>
                </form>

                <!-- Accessory Option -->
                <form action="advisor.php" method="GET" style="display: contents;">
                    <input type="hidden" name="step" value="2">
                    <input type="hidden" name="category" value="Accessories">
                    <label class="option-card">
                        <button type="submit" style="display: none;"></button>
                        <div class="option-content" onclick="this.previousElementSibling.click()">
                            <div class="option-icon">🖱️</div>
                            <div class="option-title">Accessories</div>
                            <div class="option-desc">I'm looking for peripherals like mice, keyboards, or headsets.</div>
                        </div>
                    </label>
                </form>
            </div>
        </div>

    <?php elseif ($step == 2 && $category === 'Accessories'): ?>
        <!-- STEP 2 (Accessories): TYPE -->
        <div class="quiz-card">
            <h2 class="quiz-title">What type of accessory?</h2>
            <p class="quiz-subtitle">Select the category you are interested in.</p>
            
            <form action="advisor.php" method="GET">
                <input type="hidden" name="step" value="3">
                <input type="hidden" name="category" value="Accessories">
                
                <div class="selection-grid">
                    <label class="option-card">
                        <input type="radio" name="accessory_type" value="Mouse" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">🖱️</div>
                            <div class="option-title">Mouse</div>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="accessory_type" value="Keyboard" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">⌨️</div>
                            <div class="option-title">Keyboard</div>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="accessory_type" value="Headset" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">🎧</div>
                            <div class="option-title">Headset</div>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="accessory_type" value="Monitor" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">🖥️</div>
                            <div class="option-title">Monitor</div>
                        </div>
                    </label>
                </div>
            </form>
        </div>

    <?php elseif ($step == 3 && $category === 'Accessories'): ?>
        <!-- STEP 3 (Accessories): BUDGET -->
        <div class="quiz-card">
            <h2 class="quiz-title">What's your budget?</h2>
            <p class="quiz-subtitle">We'll find the best value within your range.</p>
            
            <form action="advisor.php" method="GET">
                <input type="hidden" name="step" value="4">
                <input type="hidden" name="category" value="Accessories">
                <input type="hidden" name="accessory_type" value="<?php echo htmlspecialchars($accessory_type); ?>">
                
                <div class="range-container">
                    <span class="range-value" id="budgetValue">$100</span>
                    <input type="range" name="budget" id="budgetRange" min="10" max="500" step="10" value="100">
                    <div style="display: flex; justify-content: space-between; color: var(--text-muted); margin-top: 10px;">
                        <span>$10</span>
                        <span>$500+</span>
                    </div>
                </div>

                <button type="submit" class="btn-view" style="max-width: 300px; margin: 40px auto 0;">Show Accessories ✨</button>
            </form>
        </div>
        
        <script>
            const range = document.getElementById('budgetRange');
            const value = document.getElementById('budgetValue');
            range.addEventListener('input', (e) => {
                value.textContent = '$' + parseInt(e.target.value).toLocaleString();
            });
        </script>

    <?php elseif ($step == 2 && $category !== 'Accessories'): ?>
        <!-- STEP 2 (Laptop): USE CASE -->
        <div class="quiz-card">
            <h2 class="quiz-title">What will you use it for?</h2>
            <p class="quiz-subtitle">We'll prioritize features based on your primary activity.</p>
            
            <form action="advisor.php" method="GET">
                <input type="hidden" name="step" value="3">
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                <div class="selection-grid">
                    <label class="option-card">
                        <input type="radio" name="use_case" value="General Use" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">🌐</div>
                            <div class="option-title">General Use</div>
                            <div class="option-desc">Web browsing, streaming, email, and light tasks.</div>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="use_case" value="Student" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">📚</div>
                            <div class="option-title">Student</div>
                            <div class="option-desc">Note-taking, research, assignments, and portability.</div>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="use_case" value="Business" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">💼</div>
                            <div class="option-title">Business</div>
                            <div class="option-desc">Productivity, multitasking, meetings, and reliability.</div>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="use_case" value="Gaming" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">🎮</div>
                            <div class="option-title">Gaming</div>
                            <div class="option-desc">High-performance graphics for modern games.</div>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="use_case" value="Creative" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">🎨</div>
                            <div class="option-title">Creative</div>
                            <div class="option-desc">Video editing, 3D rendering, and graphic design.</div>
                        </div>
                    </label>
                </div>
            </form>
        </div>

    <?php elseif ($step == 3 && $category !== 'Accessories'): ?>
        <!-- STEP 3 (Laptop): PERFORMANCE -->
        <div class="quiz-card">
            <h2 class="quiz-title">How much power do you need?</h2>
            <p class="quiz-subtitle">This determines the processor and memory requirements.</p>
            
            <form action="advisor.php" method="GET">
                <input type="hidden" name="step" value="4">
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                <input type="hidden" name="use_case" value="<?php echo htmlspecialchars($use_case); ?>">
                
                <div class="selection-grid">
                    <label class="option-card">
                        <input type="radio" name="performance" value="light" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">🍃</div>
                            <div class="option-title">Light</div>
                            <div class="option-desc">Basic tasks. I don't run many apps at once.</div>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="performance" value="everyday" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">⚡</div>
                            <div class="option-title">Balanced</div>
                            <div class="option-desc">Smooth multitasking. I keep many tabs open.</div>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="performance" value="heavy" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">🚀</div>
                            <div class="option-title">Heavy Duty</div>
                            <div class="option-desc">Intense workloads, large files, and pro software.</div>
                        </div>
                    </label>
                </div>
            </form>
        </div>

    <?php elseif ($step == 4 && $category !== 'Accessories'): ?>
        <!-- STEP 4 (Laptop): SCREEN SIZE -->
        <div class="quiz-card">
            <h2 class="quiz-title">Pick your preferred size</h2>
            <p class="quiz-subtitle">Balance between portability and screen real estate.</p>
            
            <form action="advisor.php" method="GET">
                <input type="hidden" name="step" value="5">
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                <input type="hidden" name="use_case" value="<?php echo htmlspecialchars($use_case); ?>">
                <input type="hidden" name="performance" value="<?php echo htmlspecialchars($performance); ?>">
                
                <div class="selection-grid">
                    <label class="option-card">
                        <input type="radio" name="screen_size" value="small" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">📱</div>
                            <div class="option-title">Compact (13-14")</div>
                            <div class="option-desc">Ultra-portable, fits in any bag. Great for travel.</div>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="screen_size" value="medium" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">💻</div>
                            <div class="option-title">Standard (15")</div>
                            <div class="option-desc">The sweet spot. Good screen size and portability.</div>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="screen_size" value="large" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">🖥️</div>
                            <div class="option-title">Large (16"+)</div>
                            <div class="option-desc">Maximum workspace. Best for editing and gaming.</div>
                        </div>
                    </label>
                </div>
            </form>
        </div>

    <?php elseif ($step == 5 && $category !== 'Accessories'): ?>
        <!-- STEP 5 (Laptop): PORTABILITY -->
        <div class="quiz-card">
            <h2 class="quiz-title">How mobile are you?</h2>
            <p class="quiz-subtitle">We'll check the weight and battery life for you.</p>
            
            <form action="advisor.php" method="GET">
                <input type="hidden" name="step" value="6">
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                <input type="hidden" name="use_case" value="<?php echo htmlspecialchars($use_case); ?>">
                <input type="hidden" name="performance" value="<?php echo htmlspecialchars($performance); ?>">
                <input type="hidden" name="screen_size" value="<?php echo htmlspecialchars($screen_size); ?>">
                
                <div class="selection-grid">
                    <label class="option-card">
                        <input type="radio" name="portability" value="stationary" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">🏠</div>
                            <div class="option-title">Mostly Stationary</div>
                            <div class="option-desc">It will mostly stay on a desk. Weight doesn't matter.</div>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="portability" value="occasional" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">🚶</div>
                            <div class="option-title">Occasional</div>
                            <div class="option-desc">I take it to coffee shops or meetings sometimes.</div>
                        </div>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="portability" value="frequent" required onchange="this.form.submit()">
                        <div class="option-content">
                            <div class="option-icon">✈️</div>
                            <div class="option-title">Road Warrior</div>
                            <div class="option-desc">I carry it everywhere, every day. Lightness is key.</div>
                        </div>
                    </label>
                </div>
            </form>
        </div>

    <?php elseif ($step == 6 && $category !== 'Accessories'): ?>
        <!-- STEP 6 (Laptop): BUDGET -->
        <div class="quiz-card">
            <h2 class="quiz-title">What's your budget?</h2>
            <p class="quiz-subtitle">We'll find the best value within your range.</p>
            
            <form action="advisor.php" method="GET">
                <input type="hidden" name="step" value="7">
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                <input type="hidden" name="use_case" value="<?php echo htmlspecialchars($use_case); ?>">
                <input type="hidden" name="performance" value="<?php echo htmlspecialchars($performance); ?>">
                <input type="hidden" name="screen_size" value="<?php echo htmlspecialchars($screen_size); ?>">
                <input type="hidden" name="portability" value="<?php echo htmlspecialchars($portability); ?>">
                
                <div class="range-container">
                    <span class="range-value" id="budgetValue">$1,500</span>
                    <input type="range" name="budget" id="budgetRange" min="500" max="5000" step="100" value="1500">
                    <div style="display: flex; justify-content: space-between; color: var(--text-muted); margin-top: 10px;">
                        <span>$500</span>
                        <span>$5,000+</span>
                    </div>
                </div>

                <button type="submit" class="btn-view" style="max-width: 300px; margin: 40px auto 0;">Reveal My Matches ✨</button>
            </form>
        </div>
        
        <script>
            const range = document.getElementById('budgetRange');
            const value = document.getElementById('budgetValue');
            range.addEventListener('input', (e) => {
                value.textContent = '$' + parseInt(e.target.value).toLocaleString();
            });
        </script>

    <?php else: ?>
        <!-- RESULTS PAGE -->
        <div class="results-header">
            <h1 class="quiz-title">We Found <?php echo count($results); ?> Perfect Matches!</h1>
            <?php if ($category === 'Accessories'): ?>
                <p class="quiz-subtitle">Showing <strong><?php echo htmlspecialchars($accessory_type); ?></strong> options under <strong>$<?php echo number_format($budget); ?></strong>.</p>
            <?php else: ?>
                <p class="quiz-subtitle">Based on your needs for <strong><?php echo htmlspecialchars($use_case); ?></strong>, <strong><?php echo ucfirst($performance); ?></strong> performance, and budget of <strong>$<?php echo number_format($budget); ?></strong>.</p>
            <?php endif; ?>
        </div>

        <?php if (empty($results)): ?>
            <div class="quiz-card">
                <div style="font-size: 3rem; margin-bottom: 20px;">🔍</div>
                <h3>No exact matches found</h3>
                <p>Try increasing your budget or adjusting your filters to see more options.</p>
                <a href="advisor.php" class="btn-restart">Start Over</a>
            </div>
        <?php else: ?>
            <div class="results-grid">
                <?php foreach($results as $index => $row): ?>
                    <div class="result-card">
                        <?php if ($index === 0): ?>
                            <div class="top-match-badge">🏆 TOP RECOMMENDATION</div>
                        <?php endif; ?>
                        
                        <img src="<?php echo !empty($row['image_url']) ? htmlspecialchars($row['image_url']) : 'https://via.placeholder.com/300'; ?>" 
                             class="result-img" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                        
                        <div class="result-body">
                            <?php if ($category !== 'Accessories'): ?>
                                <div class="match-score <?php echo $row['total_score'] > 80 ? 'score-high' : 'score-med'; ?>">
                                    <?php echo min(100, round($row['total_score'])); ?>% Match
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="result-title"><?php echo htmlspecialchars($row['product_name']); ?></h3>
                            <div class="result-brand"><?php echo htmlspecialchars($row['brand']); ?></div>
                            
                            <?php if ($category !== 'Accessories'): ?>
                                <div class="specs-list">
                                    <span class="spec-tag"><?php echo $row['cpu']; ?></span>
                                    <span class="spec-tag"><?php echo $row['ram_gb']; ?>GB RAM</span>
                                    <span class="spec-tag"><?php echo $row['storage_gb']; ?>GB SSD</span>
                                    <span class="spec-tag"><?php echo $row['display_size']; ?>"</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="result-price">$<?php echo number_format($row['price'], 2); ?></div>

                            <?php
                            if ($category !== 'Accessories') {
                                // Generate Rationale for Laptops
                                $reasons = [];
                                
                                // Use Case
                                if ($row['use_case_score'] >= 25) {
                                    $reasons[] = "Perfect match for <strong>" . htmlspecialchars($use_case) . "</strong>";
                                } elseif ($row['use_case_score'] >= 15) {
                                    $reasons[] = "Capable for " . htmlspecialchars($use_case);
                                }
                                
                                // Performance
                                if ($row['perf_score'] >= 25) {
                                    $reasons[] = "<strong>High Performance</strong> specs for your needs";
                                } elseif ($row['perf_score'] >= 20) {
                                    $reasons[] = "Solid performance for everyday tasks";
                                }
                                
                                // Screen/Portability
                                if ($row['screen_score'] >= 25) {
                                    $reasons[] = "Ideal <strong>" . $row['display_size'] . "\" screen</strong> size";
                                }
                                
                                // Value
                                if ($row['value_score'] >= 20) {
                                    $reasons[] = "<strong>Great Value</strong> within your budget";
                                }
                                
                                // Popularity
                                if ($row['popularity_bonus'] > 0) {
                                    $reasons[] = "Highly rated by other users";
                                }
                                
                                // Limit to 3 reasons
                                $reasons = array_slice($reasons, 0, 3);
                                
                                if (!empty($reasons)): ?>
                                    <div class="rationale-box">
                                        <div class="rationale-title">💡 Why This Laptop?</div>
                                        <ul class="rationale-list">
                                            <?php foreach ($reasons as $reason): ?>
                                                <li class="rationale-item"><?php echo $reason; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; 
                            }
                            ?>
                            
                            <a href="product_details.php?product_id=<?php echo $row['product_id']; ?>" class="btn-view">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div style="text-align: center;">
                <a href="advisor.php" class="btn-restart">Start New Search</a>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>