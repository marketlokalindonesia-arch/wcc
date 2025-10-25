<?php
// views/home.php

// Setup
$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$category = new Category($db);

// Get data
$products = $product->read(['limit' => 8])->fetchAll(PDO::FETCH_ASSOC);
$categories = $category->read(['limit' => 6])->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Home - WC Clone";
$current_page = 'home';
?>

<?php include 'partials/header.php'; ?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Welcome to WC Clone</h1>
            <p class="text-xl mb-8">Your modern e-commerce solution</p>
            <a href="<?php echo getBaseUrl(); ?>products" 
               class="bg-white text-blue-600 px-8 py-4 rounded-lg font-bold hover:bg-gray-100 inline-block">
                Start Shopping
            </a>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Shop by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <?php foreach($categories as $cat): ?>
                <a href="<?php echo getBaseUrl(); ?>products?category=<?php echo $cat['id']; ?>" 
                   class="bg-gray-100 rounded-lg p-4 text-center hover:bg-blue-100 transition-colors">
                    <div class="text-2xl text-blue-600 mb-2">
                        <i class="fas fa-<?php echo getCategoryIcon($cat['name']); ?>"></i>
                    </div>
                    <h3 class="font-semibold"><?php echo htmlspecialchars($cat['name']); ?></h3>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Products -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Featured Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach($products as $product_item): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" 
                         alt="<?php echo htmlspecialchars($product_item['name']); ?>" 
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2"><?php echo htmlspecialchars($product_item['name']); ?></h3>
                        <p class="text-blue-600 font-bold text-xl mb-4">$<?php echo number_format($product_item['price'], 2); ?></p>
                        <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 add-to-cart-btn"
                                data-product-id="<?php echo $product_item['id']; ?>">
                            Add to Cart
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>

<?php
// Helper function for category icons
function getCategoryIcon($categoryName) {
    $icons = [
        'Electronics' => 'laptop',
        'Fashion' => 'tshirt',
        'Home' => 'home',
        'Sports' => 'basketball-ball',
        'Beauty' => 'spa',
        'Toys' => 'gamepad'
    ];
    return $icons[$categoryName] ?? 'shopping-bag';
}
?>