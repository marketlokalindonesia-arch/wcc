<?php
// config/helpers.php

// Start session hanya sekali
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    
    // Get current directory
    $current_dir = dirname($_SERVER['SCRIPT_NAME']);
    
    // Build base URL
    $base_url = $protocol . '://' . $host . $current_dir;
    
    // Remove index.php if present
    $base_url = str_replace('/index.php', '', $base_url);
    
    // Ensure it ends with slash
    if (substr($base_url, -1) !== '/') {
        $base_url .= '/';
    }
    
    return $base_url;
}

// Autoload classes
spl_autoload_register(function ($class_name) {
    $paths = [
        'models/' . $class_name . '.php',
        'controllers/' . $class_name . '.php',
        'utils/' . $class_name . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Initialize cart session jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Initialize user session jika belum ada (untuk demo)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Default user untuk demo
    $_SESSION['user_name'] = 'Demo User';
    $_SESSION['user_email'] = 'demo@wcclone.com';
    $_SESSION['user_role'] = 'customer';
}
?>