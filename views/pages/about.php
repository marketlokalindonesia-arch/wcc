<?php
// about.php
require_once 'config/helpers.php';

$page_title = "About Us - WC Clone";
$current_page = 'about';
?>

<?php include 'views/partials/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="<?php echo getBaseUrl(); ?>" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary">
                    <i class="fas fa-home mr-2"></i>
                    Home
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">About Us</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary to-purple-600 rounded-2xl text-white p-8 mb-12">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">About WC Clone</h1>
            <p class="text-xl text-blue-100 mb-8">Your trusted shopping destination for quality products and exceptional service</p>
        </div>
    </div>

    <!-- Our Story -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16 items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Story</h2>
            <p class="text-gray-600 mb-4 leading-relaxed">
                Founded in 2019, WC Clone started as a small family business with a simple mission: to make quality products accessible to everyone at affordable prices. What began as a modest online store has grown into a trusted e-commerce platform serving thousands of customers worldwide.
            </p>
            <p class="text-gray-600 mb-4 leading-relaxed">
                Our journey has been guided by our core values of integrity, customer satisfaction, and continuous improvement. We believe that shopping online should be convenient, secure, and enjoyable.
            </p>
            <p class="text-gray-600 leading-relaxed">
                Today, we're proud to offer a curated selection of products across multiple categories, all backed by our commitment to quality and service excellence.
            </p>
        </div>
        <div class="order-first lg:order-last">
            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                 alt="Our Story" 
                 class="rounded-2xl shadow-lg w-full">
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-16">
        <div class="text-center">
            <div class="text-3xl font-bold text-primary mb-2">50K+</div>
            <div class="text-gray-600">Happy Customers</div>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold text-primary mb-2">10K+</div>
            <div class="text-gray-600">Products</div>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold text-primary mb-2">5+</div>
            <div class="text-gray-600">Years Experience</div>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold text-primary mb-2">24/7</div>
            <div class="text-gray-600">Customer Support</div>
        </div>
    </div>

    <!-- Values -->
    <div class="mb-16">
        <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Our Values</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Customer First</h3>
                <p class="text-gray-600">Our customers are at the heart of everything we do. We listen, we care, and we constantly strive to exceed expectations.</p>
            </div>
            <div class="text-center p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-award text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Quality Assurance</h3>
                <p class="text-gray-600">We meticulously curate our product selection and maintain strict quality standards to ensure your complete satisfaction.</p>
            </div>
            <div class="text-center p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-balance-scale text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Transparency</h3>
                <p class="text-gray-600">We believe in honest pricing, clear policies, and open communication. No hidden fees, no surprises.</p>
            </div>
            <div class="text-center p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-rocket text-orange-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Innovation</h3>
                <p class="text-gray-600">We continuously improve our platform and services to provide you with the best possible shopping experience.</p>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-primary rounded-2xl text-white p-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Start Shopping?</h2>
        <p class="text-blue-100 text-xl mb-6">Join thousands of satisfied customers and discover why WC Clone is the preferred choice for online shopping.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo getBaseUrl(); ?>products.php" 
               class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                Shop Now
            </a>
            <a href="<?php echo getBaseUrl(); ?>contact.php" 
               class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition-colors">
                Contact Us
            </a>
        </div>
    </div>
</div>

<?php include 'views/partials/footer.php'; ?>