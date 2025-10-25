<?php
// views/partials/header.php
if (!isset($page_title)) {
    $page_title = "WC Clone - Modern E-Commerce";
}
if (!isset($current_page)) {
    $current_page = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        'primary-dark': '#1d4ed8',
                        secondary: '#6b7280',
                        success: '#10b981',
                        danger: '#ef4444',
                        warning: '#f59e0b',
                        dark: '#1f2937',
                        light: '#f8fafc'
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .hover-lift:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="font-sans bg-gray-50">
    <!-- Notification -->
    <div id="notification" class="fixed top-4 right-4 z-50"></div>

    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-40">
        <!-- Top Bar -->
        <div class="bg-primary text-white py-2 text-sm">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center">
                    <div class="flex space-x-4">
                        <span>🔥 Hot Sale: Up to 50% off!</span>
                        <span class="hidden md:inline">🚚 Free shipping on orders over $50</span>
                    </div>
                    <div class="flex space-x-4">
                        <a href="<?php echo getBaseUrl(); ?>track-order" class="hover:text-gray-200">
                            <i class="fas fa-truck mr-1"></i>Track Order
                        </a>
                        <a href="<?php echo getBaseUrl(); ?>help" class="hover:text-gray-200">
                            <i class="fas fa-headset mr-1"></i>Help
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="<?php echo getBaseUrl(); ?>" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-primary to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-store text-white text-lg"></i>
                    </div>
                    <span class="text-2xl font-bold text-gradient">WC Clone</span>
                </a>

                <!-- Search Bar -->
                <div class="hidden lg:flex flex-1 max-w-2xl mx-8">
                    <div class="relative w-full">
                        <input 
                            type="text" 
                            placeholder="Search for products..." 
                            class="w-full pl-4 pr-12 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                        <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-primary text-white p-2 rounded-full hover:bg-primary-dark">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-6">
                    <!-- Search Mobile -->
                    <button class="lg:hidden text-gray-600 hover:text-primary">
                        <i class="fas fa-search text-xl"></i>
                    </button>

                    <!-- User Account -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-primary">
                                <div class="w-8 h-8 bg-gradient-to-r from-primary to-purple-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <span class="hidden md:inline">My Account</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                <div class="p-2">
                                    <a href="<?php echo getBaseUrl(); ?>profile" class="flex items-center space-x-2 px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                                        <i class="fas fa-user-circle text-gray-400"></i>
                                        <span>Profile</span>
                                    </a>
                                    <a href="<?php echo getBaseUrl(); ?>orders" class="flex items-center space-x-2 px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                                        <i class="fas fa-shopping-bag text-gray-400"></i>
                                        <span>Orders</span>
                                    </a>
                                    <a href="<?php echo getBaseUrl(); ?>wishlist" class="flex items-center space-x-2 px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                                        <i class="fas fa-heart text-gray-400"></i>
                                        <span>Wishlist</span>
                                    </a>
                                    <a href="<?php echo getBaseUrl(); ?>logout" class="flex items-center space-x-2 px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Logout</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center space-x-4">
                            <a href="<?php echo getBaseUrl(); ?>login" class="text-gray-700 hover:text-primary">
                                <i class="fas fa-user text-xl"></i>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Wishlist -->
                    <a href="<?php echo getBaseUrl(); ?>wishlist" class="text-gray-700 hover:text-primary relative">
                        <i class="fas fa-heart text-xl"></i>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                    </a>

                    <!-- Cart -->
                    <a href="<?php echo getBaseUrl(); ?>cart" class="text-gray-700 hover:text-primary relative">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span id="cartCount" class="absolute -top-2 -right-2 bg-primary text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                    </a>

                    <!-- Mobile Menu Button -->
                    <button class="lg:hidden text-gray-700 hover:text-primary" id="mobileMenuBtn">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Categories Navigation -->
            <div class="hidden lg:flex items-center justify-center space-x-8 mt-4">
                <a href="<?php echo getBaseUrl(); ?>" class="text-gray-700 hover:text-primary font-medium <?php echo $current_page == 'home' ? 'text-primary border-b-2 border-primary' : ''; ?>">
                    Home
                </a>
                <a href="<?php echo getBaseUrl(); ?>products" class="text-gray-700 hover:text-primary font-medium <?php echo $current_page == 'products' ? 'text-primary border-b-2 border-primary' : ''; ?>">
                    Shop
                </a>
                <a href="<?php echo getBaseUrl(); ?>deals" class="text-gray-700 hover:text-primary font-medium">
                    Hot Deals
                </a>
                <a href="<?php echo getBaseUrl(); ?>about" class="text-gray-700 hover:text-primary font-medium <?php echo $current_page == 'about' ? 'text-primary border-b-2 border-primary' : ''; ?>">
                    About
                </a>
                <a href="<?php echo getBaseUrl(); ?>contact" class="text-gray-700 hover:text-primary font-medium <?php echo $current_page == 'contact' ? 'text-primary border-b-2 border-primary' : ''; ?>">
                    Contact
                </a>
            </div>
        </nav>
    </header>

    <!-- Mobile Navigation -->
    <div id="mobileNav" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden lg:hidden">
        <div class="fixed right-0 top-0 h-full w-80 bg-white shadow-xl transform transition-transform duration-300 translate-x-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl font-bold">Menu</h2>
                    <button id="mobileNavClose" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                
                <div class="space-y-6">
                    <a href="<?php echo getBaseUrl(); ?>" class="block py-3 text-lg font-medium text-gray-700 hover:text-primary">Home</a>
                    <a href="<?php echo getBaseUrl(); ?>products" class="block py-3 text-lg font-medium text-gray-700 hover:text-primary">Shop</a>
                    <a href="<?php echo getBaseUrl(); ?>deals" class="block py-3 text-lg font-medium text-gray-700 hover:text-primary">Hot Deals</a>
                    <a href="<?php echo getBaseUrl(); ?>about" class="block py-3 text-lg font-medium text-gray-700 hover:text-primary">About</a>
                    <a href="<?php echo getBaseUrl(); ?>contact" class="block py-3 text-lg font-medium text-gray-700 hover:text-primary">Contact</a>
                    
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-800 mb-4">Account</h3>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="<?php echo getBaseUrl(); ?>profile" class="flex items-center space-x-3 py-2 text-gray-600 hover:text-primary">
                                <i class="fas fa-user-circle"></i>
                                <span>My Profile</span>
                            </a>
                            <a href="<?php echo getBaseUrl(); ?>orders" class="flex items-center space-x-3 py-2 text-gray-600 hover:text-primary">
                                <i class="fas fa-shopping-bag"></i>
                                <span>My Orders</span>
                            </a>
                            <a href="<?php echo getBaseUrl(); ?>wishlist" class="flex items-center space-x-3 py-2 text-gray-600 hover:text-primary">
                                <i class="fas fa-heart"></i>
                                <span>Wishlist</span>
                            </a>
                            <a href="<?php echo getBaseUrl(); ?>logout" class="flex items-center space-x-3 py-2 text-red-600 hover:text-red-700">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo getBaseUrl(); ?>login" class="flex items-center space-x-3 py-2 text-gray-600 hover:text-primary">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Login</span>
                            </a>
                            <a href="<?php echo getBaseUrl(); ?>register" class="flex items-center space-x-3 py-2 text-primary hover:text-primary-dark">
                                <i class="fas fa-user-plus"></i>
                                <span>Register</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="min-h-screen">