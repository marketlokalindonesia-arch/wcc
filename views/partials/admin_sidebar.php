<?php
$current_page = $_GET['url'] ?? 'admin/dashboard';
$user = getUser();

function isActive($page, $current) {
    return strpos($current, $page) !== false ? 'active' : '';
}

function isParentActive($parent, $current) {
    return strpos($current, $parent) !== false;
}
?>
<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">
        <i class="fas fa-store me-2"></i>WC Clone
    </div>
    
    <nav class="nav flex-column mt-3">
        <!-- Dashboard -->
        <a class="nav-link <?php echo isActive('admin/dashboard', $current_page); ?>" href="/?url=admin/dashboard">
            <i class="fas fa-home me-2"></i>Dashboard
        </a>

        <!-- POS Menu -->
        <div class="menu-section">
            <a class="nav-link menu-toggle <?php echo isParentActive('admin/pos', $current_page) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#posMenu">
                <i class="fas fa-cash-register me-2"></i>POS
                <i class="fas fa-chevron-down ms-auto toggle-icon"></i>
            </a>
            <div class="collapse <?php echo isParentActive('admin/pos', $current_page) ? 'show' : ''; ?>" id="posMenu">
                <a class="nav-link sub-link <?php echo isActive('admin/pos', $current_page); ?>" href="/?url=admin/pos">
                    <i class="fas fa-desktop me-2"></i>POS Dashboard
                </a>
                <a class="nav-link sub-link" href="/?url=admin/pos/roles">
                    <i class="fas fa-user-tag me-2"></i>Roles
                </a>
                <a class="nav-link sub-link" href="/?url=admin/pos/outlet">
                    <i class="fas fa-store-alt me-2"></i>Outlet
                </a>
                <a class="nav-link sub-link" href="/?url=admin/pos/stock-settings">
                    <i class="fas fa-boxes me-2"></i>Stock Settings
                </a>
                <a class="nav-link sub-link" href="/?url=admin/pos/payment">
                    <i class="fas fa-credit-card me-2"></i>Payment
                </a>
            </div>
        </div>

        <!-- Products Menu -->
        <div class="menu-section">
            <a class="nav-link menu-toggle <?php echo isParentActive('admin/product', $current_page) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#productsMenu">
                <i class="fas fa-box me-2"></i>Products
                <i class="fas fa-chevron-down ms-auto toggle-icon"></i>
            </a>
            <div class="collapse <?php echo isParentActive('admin/product', $current_page) ? 'show' : ''; ?>" id="productsMenu">
                <a class="nav-link sub-link <?php echo isActive('admin/products', $current_page); ?>" href="/?url=admin/products">
                    <i class="fas fa-list me-2"></i>All Products
                </a>
                <a class="nav-link sub-link" href="/?url=admin/products/add">
                    <i class="fas fa-plus me-2"></i>Add New Product
                </a>
                <a class="nav-link sub-link" href="/?url=admin/products/categories">
                    <i class="fas fa-folder me-2"></i>Categories
                </a>
                <a class="nav-link sub-link" href="/?url=admin/products/brands">
                    <i class="fas fa-copyright me-2"></i>Brands
                </a>
                <a class="nav-link sub-link" href="/?url=admin/products/tags">
                    <i class="fas fa-tags me-2"></i>Tags
                </a>
                <a class="nav-link sub-link" href="/?url=admin/products/attributes">
                    <i class="fas fa-sliders-h me-2"></i>Attributes
                </a>
                <a class="nav-link sub-link" href="/?url=admin/products/reviews">
                    <i class="fas fa-star me-2"></i>Reviews
                </a>
            </div>
        </div>

        <!-- Orders Menu -->
        <div class="menu-section">
            <a class="nav-link menu-toggle <?php echo isParentActive('admin/order', $current_page) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#ordersMenu">
                <i class="fas fa-shopping-bag me-2"></i>Orders
                <i class="fas fa-chevron-down ms-auto toggle-icon"></i>
            </a>
            <div class="collapse <?php echo isParentActive('admin/order', $current_page) ? 'show' : ''; ?>" id="ordersMenu">
                <a class="nav-link sub-link <?php echo isActive('admin/orders', $current_page); ?>" href="/?url=admin/orders">
                    <i class="fas fa-receipt me-2"></i>All Orders
                </a>
                <a class="nav-link sub-link" href="/?url=admin/orders/pending">
                    <i class="fas fa-clock me-2"></i>Pending Orders
                </a>
                <a class="nav-link sub-link" href="/?url=admin/orders/processing">
                    <i class="fas fa-spinner me-2"></i>Processing
                </a>
                <a class="nav-link sub-link" href="/?url=admin/orders/completed">
                    <i class="fas fa-check-circle me-2"></i>Completed
                </a>
            </div>
        </div>

        <!-- Customers -->
        <a class="nav-link <?php echo isActive('admin/customers', $current_page); ?>" href="/?url=admin/customers">
            <i class="fas fa-users me-2"></i>Customers
        </a>

        <!-- Coupons -->
        <a class="nav-link <?php echo isActive('admin/coupons', $current_page); ?>" href="/?url=admin/coupons">
            <i class="fas fa-ticket-alt me-2"></i>Coupons
        </a>

        <!-- Inventory Menu -->
        <div class="menu-section">
            <a class="nav-link menu-toggle <?php echo isParentActive('admin/inventory', $current_page) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#inventoryMenu">
                <i class="fas fa-warehouse me-2"></i>Inventory
                <i class="fas fa-chevron-down ms-auto toggle-icon"></i>
            </a>
            <div class="collapse <?php echo isParentActive('admin/inventory', $current_page) ? 'show' : ''; ?>" id="inventoryMenu">
                <a class="nav-link sub-link <?php echo isActive('admin/inventory', $current_page) && !strpos($current_page, '/'); ?>" href="/?url=admin/inventory">
                    <i class="fas fa-clipboard-list me-2"></i>Stock Overview
                </a>
                <a class="nav-link sub-link" href="/?url=admin/inventory/logs">
                    <i class="fas fa-history me-2"></i>Inventory Logs
                </a>
                <a class="nav-link sub-link" href="/?url=admin/inventory/low-stock">
                    <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert
                </a>
            </div>
        </div>

        <!-- Analytics Menu -->
        <div class="menu-section">
            <a class="nav-link menu-toggle <?php echo isParentActive('admin/analytics', $current_page) || isActive('admin/reports', $current_page) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#analyticsMenu">
                <i class="fas fa-chart-bar me-2"></i>Analytics
                <i class="fas fa-chevron-down ms-auto toggle-icon"></i>
            </a>
            <div class="collapse <?php echo isParentActive('admin/analytics', $current_page) || isActive('admin/reports', $current_page) ? 'show' : ''; ?>" id="analyticsMenu">
                <a class="nav-link sub-link <?php echo isActive('admin/reports', $current_page); ?>" href="/?url=admin/reports">
                    <i class="fas fa-chart-line me-2"></i>Overview
                </a>
                <a class="nav-link sub-link" href="/?url=admin/analytics/products">
                    <i class="fas fa-box-open me-2"></i>Products
                </a>
                <a class="nav-link sub-link" href="/?url=admin/analytics/revenue">
                    <i class="fas fa-dollar-sign me-2"></i>Revenue
                </a>
                <a class="nav-link sub-link" href="/?url=admin/analytics/orders">
                    <i class="fas fa-shopping-cart me-2"></i>Orders
                </a>
                <a class="nav-link sub-link" href="/?url=admin/analytics/customers">
                    <i class="fas fa-user-friends me-2"></i>Customers
                </a>
                <a class="nav-link sub-link" href="/?url=admin/analytics/stock">
                    <i class="fas fa-cubes me-2"></i>Stock
                </a>
            </div>
        </div>

        <!-- Settings -->
        <a class="nav-link <?php echo isActive('admin/settings', $current_page); ?>" href="/?url=admin/settings">
            <i class="fas fa-cog me-2"></i>Settings
        </a>

        <hr style="border-color: rgba(255,255,255,0.2); margin: 15px 0;">
        
        <!-- Logout -->
        <a class="nav-link" href="/?url=logout">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
        </a>
    </nav>

    <!-- User Profile -->
    <div class="user-profile">
        <div class="text-center">
            <div class="mb-2"><i class="fas fa-user-circle" style="font-size: 40px;"></i></div>
            <div class="user-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
            <div class="user-role">Administrator</div>
        </div>
    </div>
</div>

<style>
    .sidebar {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        width: 260px;
        padding: 20px 15px;
        z-index: 1000;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }
    
    .sidebar::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
    }
    
    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.3);
        border-radius: 10px;
    }
    
    .sidebar .logo {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
        text-align: center;
        padding: 10px 0;
    }
    
    .sidebar .nav-link {
        color: rgba(255,255,255,0.85);
        padding: 10px 15px;
        border-radius: 8px;
        margin-bottom: 3px;
        transition: all 0.3s;
        text-decoration: none;
        display: flex;
        align-items: center;
        font-size: 14px;
        position: relative;
    }
    
    .sidebar .nav-link:hover {
        background: rgba(255,255,255,0.15);
        color: white;
        transform: translateX(3px);
    }
    
    .sidebar .nav-link.active {
        background: rgba(255,255,255,0.25);
        color: white;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .sidebar .nav-link i:first-child {
        width: 20px;
        text-align: center;
    }
    
    .menu-section {
        margin-bottom: 2px;
    }
    
    .menu-toggle {
        cursor: pointer;
        user-select: none;
    }
    
    .toggle-icon {
        font-size: 10px;
        transition: transform 0.3s;
        margin-left: auto;
    }
    
    .menu-toggle[aria-expanded="true"] .toggle-icon {
        transform: rotate(180deg);
    }
    
    .sub-link {
        padding-left: 45px !important;
        font-size: 13px;
        color: rgba(255,255,255,0.75) !important;
    }
    
    .sub-link:hover {
        background: rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.95) !important;
    }
    
    .sub-link.active {
        background: rgba(255,255,255,0.2);
        color: white !important;
        font-weight: 500;
    }
    
    .user-profile {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,0.2);
    }
    
    .user-name {
        font-size: 14px;
        font-weight: 600;
    }
    
    .user-role {
        font-size: 12px;
        opacity: 0.7;
        margin-top: 3px;
    }
    
    .main-content {
        margin-left: 260px;
        padding: 30px;
        min-height: 100vh;
    }
    
    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            position: relative;
        }
        .main-content {
            margin-left: 0;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle menu collapse
    const menuToggles = document.querySelectorAll('.menu-toggle');
    menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const bsCollapse = new bootstrap.Collapse(targetElement, {
                    toggle: true
                });
            }
        });
    });
});
</script>
