<?php
// Get current page filename
$current_page = basename($_SERVER['PHP_SELF']);

// Function to check if current page belongs to a module
function isModulePage($pages) {
    global $current_page;
    return in_array($current_page, $pages);
}

// Define module pages
$ecommerce_pages = ['admin_products.php', 'admin_orders.php', 'admin_inventory.php', 'admin_transactions.php', 'admin_coupons.php'];
$ai_pages = ['admin_personas.php', 'admin_recommendation_logs.php', 'admin_ai_performance.php'];
$chatbot_pages = ['admin_conversation_logs.php', 'admin_intent_management.php', 'admin_chatbot_training.php', 'admin_chatbot_analytics.php'];
$admin_pages = ['admin_customers.php', 'admin_admins.php', 'admin_roles.php'];

// Check for dark mode
$theme = isset($_COOKIE['admin_theme']) ? $_COOKIE['admin_theme'] : 'light';
?>
<!-- Dark Mode Support -->
<link rel="stylesheet" href="source/assets/css/dark.css">
<script>
    if (document.cookie.indexOf('admin_theme=dark') !== -1) {
        document.body.classList.add('dark');
    }
</script>
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <a href="admin_dashboard.php">
                                <img src="source/assets/images/logo/logo.png" alt="Logo" srcset="">
                                <span class="ms-2 fw-bold">Smart Laptop Advisor</span>
                            </a>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <!-- Dashboard (Analytics & Reporting) -->
                        <li class="sidebar-item <?php echo ($current_page == 'admin_dashboard.php') ? 'active' : ''; ?>">
                            <a href="admin_dashboard.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <!-- E-commerce & Operations -->
                        <li class="sidebar-item has-sub <?php echo isModulePage($ecommerce_pages) ? 'active' : ''; ?>">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-shop"></i>
                                <span>E-commerce & Operations</span>
                            </a>
                            <ul class="submenu <?php echo isModulePage($ecommerce_pages) ? 'active' : ''; ?>">
                                <li class="submenu-item <?php echo ($current_page == 'admin_products.php') ? 'active' : ''; ?>">
                                    <a href="admin_products.php">Product Management</a>
                                </li>
                                <li class="submenu-item <?php echo ($current_page == 'admin_orders.php') ? 'active' : ''; ?>">
                                    <a href="admin_orders.php">Order Management</a>
                                </li>
                                <li class="submenu-item <?php echo ($current_page == 'admin_inventory.php') ? 'active' : ''; ?>">
                                    <a href="admin_inventory.php">Inventory Control</a>
                                </li>
                                <li class="submenu-item <?php echo ($current_page == 'admin_transactions.php') ? 'active' : ''; ?>">
                                    <a href="admin_transactions.php">Transactions</a>
                                </li>
                                <li class="submenu-item <?php echo ($current_page == 'admin_coupons.php') ? 'active' : ''; ?>">
                                    <a href="admin_coupons.php">Coupon Management</a>
                                </li>
                            </ul>
                        </li>

                        <!-- AI Recommendation Engine -->
                        <li class="sidebar-item has-sub <?php echo isModulePage($ai_pages) ? 'active' : ''; ?>">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-cpu"></i>
                                <span>AI Recommendation Engine</span>
                            </a>
                            <ul class="submenu <?php echo isModulePage($ai_pages) ? 'active' : ''; ?>">
                                <li class="submenu-item <?php echo ($current_page == 'admin_personas.php') ? 'active' : ''; ?>">
                                    <a href="admin_personas.php">Persona Management</a>
                                </li>
                                <li class="submenu-item <?php echo ($current_page == 'admin_recommendation_logs.php') ? 'active' : ''; ?>">
                                    <a href="admin_recommendation_logs.php">Recommendation Logs</a>
                                </li>
                                <li class="submenu-item <?php echo ($current_page == 'admin_ai_performance.php') ? 'active' : ''; ?>">
                                    <a href="admin_ai_performance.php">Performance Analytics</a>
                                </li>
                            </ul>
                        </li>

                        <!-- Chatbot Management -->
                        <li class="sidebar-item has-sub <?php echo isModulePage($chatbot_pages) ? 'active' : ''; ?>">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-chat-dots"></i>
                                <span>Chatbot Management</span>
                            </a>
                            <ul class="submenu <?php echo isModulePage($chatbot_pages) ? 'active' : ''; ?>">
                                <li class="submenu-item <?php echo ($current_page == 'admin_conversation_logs.php') ? 'active' : ''; ?>">
                                    <a href="admin_conversation_logs.php">Conversation Logs</a>
                                </li>
                                <li class="submenu-item <?php echo ($current_page == 'admin_intent_management.php') ? 'active' : ''; ?>">
                                    <a href="admin_intent_management.php">Intent & Response Management</a>
                                </li>
                                <li class="submenu-item <?php echo ($current_page == 'admin_chatbot_analytics.php') ? 'active' : ''; ?>">
                                    <a href="admin_chatbot_analytics.php">Chatbot Analytics</a>
                                </li>
                            </ul>
                        </li>

                        <!-- User & System Administration -->
                        <li class="sidebar-item has-sub <?php echo isModulePage($admin_pages) ? 'active' : ''; ?>">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-people"></i>
                                <span>User & System Admin</span>
                            </a>
                            <ul class="submenu <?php echo isModulePage($admin_pages) ? 'active' : ''; ?>">
                                <li class="submenu-item <?php echo ($current_page == 'admin_customers.php') ? 'active' : ''; ?>">
                                    <a href="admin_customers.php">Customer Management</a>
                                </li>
                                <li class="submenu-item <?php echo ($current_page == 'admin_admins.php') ? 'active' : ''; ?>">
                                    <a href="admin_admins.php">Administrator Management</a>
                                </li>
                                <li class="submenu-item <?php echo ($current_page == 'admin_roles.php') ? 'active' : ''; ?>">
                                    <a href="admin_roles.php">Role Management</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-title">Additional Tools</li>

                        <li class="sidebar-item <?php echo ($current_page == 'admin_reports.php') ? 'active' : ''; ?>">
                            <a href="admin_reports.php" class='sidebar-link'>
                                <i class="bi bi-graph-up"></i>
                                <span>Advanced Reports</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?php echo ($current_page == 'admin_logs.php') ? 'active' : ''; ?>">
                            <a href="admin_logs.php" class='sidebar-link'>
                                <i class="bi bi-journal-text"></i>
                                <span>System Logs</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
