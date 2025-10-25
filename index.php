<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/database.php';
require_once 'config/helpers.php';

$url = $_GET['url'] ?? 'home';
$url = rtrim($url, '/');

$routes = [
    '' => 'views/home.php',
    'home' => 'views/home.php',
    'products' => 'views/products/list.php',
    'product' => 'views/products/single.php',
    'cart' => 'views/cart/view.php',
    'about' => 'views/pages/about.php',
    'contact' => 'views/pages/contact.php',
    'deals' => 'views/pages/deals.php',
    'login' => 'views/auth/login.php',
    'register' => 'views/auth/register.php',
    'profile' => 'views/user/profile.php',
    'orders' => 'views/orders/list.php',
    'wishlist' => 'views/user/wishlist.php',
    'shipping' => 'views/pages/shipping.php',
    'returns' => 'views/pages/returns.php',
    'faq' => 'views/pages/faq.php',
    'privacy' => 'views/pages/privacy.php',
    'terms' => 'views/pages/terms.php',
    'sitemap' => 'views/pages/sitemap.php'
];

// Handle product detail
if (preg_match('#^product/(\d+)$#', $url, $matches)) {
    $_GET['id'] = $matches[1];
    $view_file = 'views/products/single.php';
} else {
    $view_file = $routes[$url] ?? 'views/errors/404.php';
}

if (file_exists($view_file)) {
    include $view_file;
} else {
    http_response_code(404);
    echo "Page not found: " . $url;
}
?>