<?php
// config/autoload.php

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Autoload classes
spl_autoload_register(function ($class_name) {
    $file = BASE_PATH . '/models/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Helper function untuk require files dengan path yang benar
function requireModel($model) {
    require_once BASE_PATH . '/models/' . $model . '.php';
}

function requireConfig($config) {
    require_once BASE_PATH . '/config/' . $config . '.php';
}
?>