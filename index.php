<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';
require_once 'config/helpers.php';
require_once 'config/session.php';

$url = $_GET['url'] ?? 'home';
$url = rtrim($url, '/');
$url = ltrim($url, '/');

// Handle POST requests for login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $url === 'login') {
    require_once 'controllers/AuthController.php';
    $auth = new AuthController();
    $result = $auth->login($_POST['username'], $_POST['password']);
    
    if ($result['success']) {
        $role = $result['role'];
        if ($role === 'admin') {
            header('Location: /?url=admin/dashboard');
        } elseif ($role === 'cashier') {
            header('Location: /?url=cashier/dashboard');
        } else {
            header('Location: /');
        }
        exit();
    } else {
        $_SESSION['login_error'] = $result['message'];
        header('Location: /?url=login');
        exit();
    }
}

// Handle logout
if ($url === 'logout') {
    require_once 'controllers/AuthController.php';
    $auth = new AuthController();
    $auth->logout();
    header('Location: /?url=login');
    exit();
}

// Define routes
$routes = [
    '' => 'views/home.php',
    'home' => 'views/home.php',
    'login' => 'views/auth/login.php',
    'register' => 'views/auth/register.php',
    'products' => 'views/products/list.php',
    'product' => 'views/products/single.php',
    'cart' => 'views/cart/view.php',
    
    // Admin routes
    'admin/dashboard' => 'views/admin/dashboard.php',
    
    // POS routes
    'admin/pos' => 'views/admin/pos.php',
    'admin/pos/roles' => 'views/admin/pos/roles.php',
    'admin/pos/outlet' => 'views/admin/pos/outlet.php',
    'admin/pos/stock-settings' => 'views/admin/pos/stock-settings.php',
    'admin/pos/payment' => 'views/admin/pos/payment.php',
    
    // Products routes
    'admin/products' => 'views/admin/products.php',
    'admin/products/add' => 'views/admin/products/add.php',
    'admin/products/categories' => 'views/admin/products/categories.php',
    'admin/products/brands' => 'views/admin/products/brands.php',
    'admin/products/tags' => 'views/admin/products/tags.php',
    'admin/products/attributes' => 'views/admin/products/attributes.php',
    'admin/products/reviews' => 'views/admin/products/reviews.php',
    
    // Orders routes
    'admin/orders' => 'views/admin/orders.php',
    'admin/orders/pending' => 'views/admin/orders/pending.php',
    'admin/orders/processing' => 'views/admin/orders/processing.php',
    'admin/orders/completed' => 'views/admin/orders/completed.php',
    
    // Customers
    'admin/customers' => 'views/admin/customers.php',
    
    // Coupons
    'admin/coupons' => 'views/admin/coupons.php',
    
    // Inventory routes
    'admin/inventory' => 'views/admin/inventory.php',
    'admin/inventory/logs' => 'views/admin/inventory/logs.php',
    'admin/inventory/low-stock' => 'views/admin/inventory/low-stock.php',
    
    // Analytics/Reports routes
    'admin/reports' => 'views/admin/reports.php',
    'admin/analytics/products' => 'views/admin/analytics/products.php',
    'admin/analytics/revenue' => 'views/admin/analytics/revenue.php',
    'admin/analytics/orders' => 'views/admin/analytics/orders.php',
    'admin/analytics/customers' => 'views/admin/analytics/customers.php',
    'admin/analytics/stock' => 'views/admin/analytics/stock.php',
    
    // Settings
    'admin/settings' => 'views/admin/settings.php',
    
    // Cashier routes
    'cashier/dashboard' => 'views/cashier/dashboard.php',
    'cashier/pos' => 'views/cashier/pos.php',
    'cashier/transactions' => 'views/cashier/transactions.php',
];

// Handle product detail
if (preg_match('#^product/(\d+)$#', $url, $matches)) {
    $_GET['id'] = $matches[1];
    $view_file = 'views/products/single.php';
} else {
    $view_file = $routes[$url] ?? 'views/home.php';
}

if (file_exists($view_file)) {
    include $view_file;
} else {
    http_response_code(404);
    echo "<h1>404 - Page not found</h1>";
    echo "<p>The page you're looking for doesn't exist: " . htmlspecialchars($url) . "</p>";
    echo "<a href='/'>Go Home</a>";
}
?>
