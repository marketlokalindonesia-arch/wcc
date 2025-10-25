<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    
    $current_dir = dirname($_SERVER['SCRIPT_NAME']);
    
    $base_url = $protocol . '://' . $host . $current_dir;
    
    $base_url = str_replace('/index.php', '', $base_url);
    
    if (substr($base_url, -1) !== '/') {
        $base_url .= '/';
    }
    
    return $base_url;
}

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

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function safeFormatDate($datetime, $format = 'M d, Y') {
    if (empty($datetime) || $datetime === null) {
        return '-';
    }
    return date($format, strtotime($datetime));
}

?>
