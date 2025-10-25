<?php
/**
 * Admin Sidebar Component
 * Reusable sidebar for all admin pages
 */

// Get current page from URL
$current_url = $_GET['url'] ?? 'admin/dashboard';
$user = getUser();

// Helper to check if menu is active
$is_dashboard = ($current_url === 'admin/dashboard');
$is_pos = (strpos($current_url, 'admin/pos') !== false);
$is_products = (strpos($current_url, 'admin/product') !== false);
$is_orders = (strpos($current_url, 'admin/order') !== false);
$is_customers = ($current_url === 'admin/customers');
$is_coupons = ($current_url === 'admin/coupons');
$is_inventory = (strpos($current_url, 'admin/inventory') !== false);
$is_analytics = (strpos($current_url, 'admin/analytics') !== false || strpos($current_url, 'admin/reports') !== false);
$is_settings = ($current_url === 'admin/settings');
?>

<!-- Admin Sidebar -->
<div class="admin-sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <i class="fas fa-store me-2"></i>
        <span>WC Clone</span>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <a class="nav-item <?php echo $is_dashboard ? 'active' : ''; ?>" href="/?url=admin/dashboard">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>

        <!-- POS Section -->
        <div class="nav-section">
            <div class="nav-item nav-toggle <?php echo $is_pos ? 'active' : ''; ?>" onclick="toggleSubmenu('posSubmenu', this)">
                <i class="fas fa-cash-register"></i>
                <span>POS</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="submenu <?php echo $is_pos ? 'show' : ''; ?>" id="posSubmenu">
                <a class="submenu-item <?php echo $current_url === 'admin/pos' ? 'active' : ''; ?>" href="/?url=admin/pos">
                    <i class="fas fa-desktop"></i>
                    <span>POS Dashboard</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/pos/roles' ? 'active' : ''; ?>" href="/?url=admin/pos/roles">
                    <i class="fas fa-user-tag"></i>
                    <span>Roles</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/pos/outlet' ? 'active' : ''; ?>" href="/?url=admin/pos/outlet">
                    <i class="fas fa-store-alt"></i>
                    <span>Outlet</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/pos/stock-settings' ? 'active' : ''; ?>" href="/?url=admin/pos/stock-settings">
                    <i class="fas fa-boxes"></i>
                    <span>Stock Settings</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/pos/payment' ? 'active' : ''; ?>" href="/?url=admin/pos/payment">
                    <i class="fas fa-credit-card"></i>
                    <span>Payment</span>
                </a>
            </div>
        </div>

        <!-- Products Section -->
        <div class="nav-section">
            <div class="nav-item nav-toggle <?php echo $is_products ? 'active' : ''; ?>" onclick="toggleSubmenu('productsSubmenu', this)">
                <i class="fas fa-box"></i>
                <span>Products</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="submenu <?php echo $is_products ? 'show' : ''; ?>" id="productsSubmenu">
                <a class="submenu-item <?php echo $current_url === 'admin/products' ? 'active' : ''; ?>" href="/?url=admin/products">
                    <i class="fas fa-list"></i>
                    <span>All Products</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/products/add' ? 'active' : ''; ?>" href="/?url=admin/products/add">
                    <i class="fas fa-plus"></i>
                    <span>Add New</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/products/categories' ? 'active' : ''; ?>" href="/?url=admin/products/categories">
                    <i class="fas fa-folder"></i>
                    <span>Categories</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/products/brands' ? 'active' : ''; ?>" href="/?url=admin/products/brands">
                    <i class="fas fa-copyright"></i>
                    <span>Brands</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/products/tags' ? 'active' : ''; ?>" href="/?url=admin/products/tags">
                    <i class="fas fa-tags"></i>
                    <span>Tags</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/products/attributes' ? 'active' : ''; ?>" href="/?url=admin/products/attributes">
                    <i class="fas fa-sliders-h"></i>
                    <span>Attributes</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/products/reviews' ? 'active' : ''; ?>" href="/?url=admin/products/reviews">
                    <i class="fas fa-star"></i>
                    <span>Reviews</span>
                </a>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="nav-section">
            <div class="nav-item nav-toggle <?php echo $is_orders ? 'active' : ''; ?>" onclick="toggleSubmenu('ordersSubmenu', this)">
                <i class="fas fa-shopping-bag"></i>
                <span>Orders</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="submenu <?php echo $is_orders ? 'show' : ''; ?>" id="ordersSubmenu">
                <a class="submenu-item <?php echo $current_url === 'admin/orders' ? 'active' : ''; ?>" href="/?url=admin/orders">
                    <i class="fas fa-receipt"></i>
                    <span>All Orders</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/orders/pending' ? 'active' : ''; ?>" href="/?url=admin/orders/pending">
                    <i class="fas fa-clock"></i>
                    <span>Pending</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/orders/processing' ? 'active' : ''; ?>" href="/?url=admin/orders/processing">
                    <i class="fas fa-spinner"></i>
                    <span>Processing</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/orders/completed' ? 'active' : ''; ?>" href="/?url=admin/orders/completed">
                    <i class="fas fa-check-circle"></i>
                    <span>Completed</span>
                </a>
            </div>
        </div>

        <!-- Customers -->
        <a class="nav-item <?php echo $is_customers ? 'active' : ''; ?>" href="/?url=admin/customers">
            <i class="fas fa-users"></i>
            <span>Customers</span>
        </a>

        <!-- Coupons -->
        <a class="nav-item <?php echo $is_coupons ? 'active' : ''; ?>" href="/?url=admin/coupons">
            <i class="fas fa-ticket-alt"></i>
            <span>Coupons</span>
        </a>

        <!-- Inventory Section -->
        <div class="nav-section">
            <div class="nav-item nav-toggle <?php echo $is_inventory ? 'active' : ''; ?>" onclick="toggleSubmenu('inventorySubmenu', this)">
                <i class="fas fa-warehouse"></i>
                <span>Inventory</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="submenu <?php echo $is_inventory ? 'show' : ''; ?>" id="inventorySubmenu">
                <a class="submenu-item <?php echo $current_url === 'admin/inventory' ? 'active' : ''; ?>" href="/?url=admin/inventory">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Stock Overview</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/inventory/logs' ? 'active' : ''; ?>" href="/?url=admin/inventory/logs">
                    <i class="fas fa-history"></i>
                    <span>Logs</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/inventory/low-stock' ? 'active' : ''; ?>" href="/?url=admin/inventory/low-stock">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Low Stock</span>
                </a>
            </div>
        </div>

        <!-- Analytics Section -->
        <div class="nav-section">
            <div class="nav-item nav-toggle <?php echo $is_analytics ? 'active' : ''; ?>" onclick="toggleSubmenu('analyticsSubmenu', this)">
                <i class="fas fa-chart-bar"></i>
                <span>Analytics</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="submenu <?php echo $is_analytics ? 'show' : ''; ?>" id="analyticsSubmenu">
                <a class="submenu-item <?php echo $current_url === 'admin/reports' ? 'active' : ''; ?>" href="/?url=admin/reports">
                    <i class="fas fa-chart-line"></i>
                    <span>Overview</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/analytics/products' ? 'active' : ''; ?>" href="/?url=admin/analytics/products">
                    <i class="fas fa-box-open"></i>
                    <span>Products</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/analytics/revenue' ? 'active' : ''; ?>" href="/?url=admin/analytics/revenue">
                    <i class="fas fa-dollar-sign"></i>
                    <span>Revenue</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/analytics/orders' ? 'active' : ''; ?>" href="/?url=admin/analytics/orders">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/analytics/customers' ? 'active' : ''; ?>" href="/?url=admin/analytics/customers">
                    <i class="fas fa-user-friends"></i>
                    <span>Customers</span>
                </a>
                <a class="submenu-item <?php echo $current_url === 'admin/analytics/stock' ? 'active' : ''; ?>" href="/?url=admin/analytics/stock">
                    <i class="fas fa-cubes"></i>
                    <span>Stock</span>
                </a>
            </div>
        </div>

        <!-- Settings -->
        <a class="nav-item <?php echo $is_settings ? 'active' : ''; ?>" href="/?url=admin/settings">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </a>

        <hr class="sidebar-divider">
        
        <!-- Logout -->
        <a class="nav-item" href="/?url=logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </nav>

    <!-- User Profile -->
    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="user-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-info">
                <div class="user-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
    </div>
</div>

<!-- Sidebar Styles -->
<style>
.admin-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 260px;
    height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    overflow-y: auto;
    overflow-x: hidden;
    z-index: 1000;
    display: flex;
    flex-direction: column;
}

.admin-sidebar::-webkit-scrollbar {
    width: 6px;
}

.admin-sidebar::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.1);
}

.admin-sidebar::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 3px;
}

.sidebar-logo {
    padding: 20px;
    text-align: center;
    font-size: 24px;
    font-weight: 700;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.sidebar-nav {
    flex: 1;
    padding: 15px 10px;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    color: rgba(255,255,255,0.85);
    text-decoration: none;
    border-radius: 8px;
    margin-bottom: 3px;
    transition: all 0.3s ease;
    cursor: pointer;
    font-size: 14px;
}

.nav-item:hover {
    background: rgba(255,255,255,0.15);
    color: white;
    transform: translateX(3px);
}

.nav-item.active {
    background: rgba(255,255,255,0.25);
    color: white;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.nav-item i {
    width: 24px;
    margin-right: 12px;
    text-align: center;
    font-size: 16px;
}

.nav-toggle {
    position: relative;
}

.toggle-icon {
    margin-left: auto;
    font-size: 10px;
    transition: transform 0.3s ease;
}

.nav-toggle.expanded .toggle-icon {
    transform: rotate(180deg);
}

.submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.submenu.show {
    max-height: 500px;
}

.submenu-item {
    display: flex;
    align-items: center;
    padding: 10px 15px 10px 50px;
    color: rgba(255,255,255,0.75);
    text-decoration: none;
    border-radius: 6px;
    margin: 2px 0;
    transition: all 0.3s ease;
    font-size: 13px;
}

.submenu-item:hover {
    background: rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.95);
    transform: translateX(3px);
}

.submenu-item.active {
    background: rgba(255,255,255,0.2);
    color: white;
    font-weight: 500;
}

.submenu-item i {
    width: 20px;
    margin-right: 10px;
    font-size: 14px;
}

.sidebar-divider {
    border: none;
    border-top: 1px solid rgba(255,255,255,0.2);
    margin: 15px 10px;
}

.sidebar-footer {
    padding: 15px;
    border-top: 1px solid rgba(255,255,255,0.2);
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar i {
    font-size: 40px;
}

.user-info {
    flex: 1;
}

.user-name {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 2px;
}

.user-role {
    font-size: 12px;
    opacity: 0.7;
}

.main-content {
    margin-left: 260px;
    padding: 30px;
    min-height: 100vh;
    background: #f8f9fa;
}

@media (max-width: 768px) {
    .admin-sidebar {
        width: 100%;
        position: relative;
        height: auto;
    }
    .main-content {
        margin-left: 0;
    }
}
</style>

<!-- Sidebar JavaScript -->
<script>
function toggleSubmenu(submenuId, toggleElement) {
    const submenu = document.getElementById(submenuId);
    
    if (submenu) {
        submenu.classList.toggle('show');
        toggleElement.classList.toggle('expanded');
    }
}

// Auto-expand active sections on page load
document.addEventListener('DOMContentLoaded', function() {
    const activeSubmenus = document.querySelectorAll('.submenu.show');
    activeSubmenus.forEach(function(submenu) {
        const toggle = submenu.previousElementSibling;
        if (toggle && toggle.classList.contains('nav-toggle')) {
            toggle.classList.add('expanded');
        }
    });
});
</script>
