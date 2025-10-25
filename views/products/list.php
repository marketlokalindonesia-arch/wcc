<?php
// products.php
require_once 'config/helpers.php';
require_once 'config/database.php';
require_once 'models/Product.php';
require_once 'models/Category.php';


$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$category = new Category($db);

// Get filters
$filters = ['status' => 'publish'];

if(isset($_GET['category']) && !empty($_GET['category'])) {
    $filters['category_id'] = $_GET['category'];
}

if(isset($_GET['search']) && !empty($_GET['search'])) {
    $filters['search'] = $_GET['search'];
}

if(isset($_GET['featured']) && $_GET['featured'] == '1') {
    $filters['featured'] = true;
}

// Get products
$products_result = $product->read($filters);
$products = $products_result->fetchAll(PDO::FETCH_ASSOC);

// Get categories
$categories = $category->read()->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Products - WC Clone";
$current_page = 'products';
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
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Products</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                <h3 class="text-lg font-semibold mb-4">Filters</h3>
                
                <!-- Categories -->
                <div class="mb-6">
                    <h4 class="font-medium text-gray-900 mb-3">Categories</h4>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="category" value="" class="text-primary focus:ring-primary" 
                                   <?php echo !isset($_GET['category']) ? 'checked' : ''; ?> onchange="window.location.href='<?php echo getBaseUrl(); ?>products.php'">
                            <span class="ml-2 text-sm text-gray-700">All Categories</span>
                        </label>
                        <?php foreach($categories as $cat): ?>
                            <label class="flex items-center">
                                <input type="radio" name="category" value="<?php echo $cat['id']; ?>" 
                                       class="text-primary focus:ring-primary"
                                       <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'checked' : ''; ?>
                                       onchange="window.location.href='<?php echo getBaseUrl(); ?>products.php?category=<?php echo $cat['id']; ?>'">
                                <span class="ml-2 text-sm text-gray-700"><?php echo htmlspecialchars($cat['name']); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="mb-6">
                    <h4 class="font-medium text-gray-900 mb-3">Price Range</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">$0</span>
                            <span class="text-sm text-gray-600">$1000</span>
                        </div>
                        <input type="range" min="0" max="1000" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                    </div>
                </div>

                <!-- Featured -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded text-primary focus:ring-primary" 
                               <?php echo isset($_GET['featured']) ? 'checked' : ''; ?>
                               onchange="window.location.href='<?php echo getBaseUrl(); ?>products.php?featured=1'">
                        <span class="ml-2 text-sm text-gray-700">Featured Products</span>
                    </label>
                </div>

                <button onclick="window.location.href='<?php echo getBaseUrl(); ?>products.php'" 
                        class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors">
                    Clear Filters
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:w-3/4">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Our Products</h1>
                        <p class="text-gray-600 mt-1"><?php echo count($products); ?> products found</p>
                    </div>
                    
                    <div class="mt-4 md:mt-0">
                        <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5">
                            <option selected>Sort by: Newest</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>Name: A-Z</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <?php if(empty($products)): ?>
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No products found</h3>
                    <p class="text-gray-600 mb-4">Try adjusting your search or filter criteria</p>
                    <button onclick="window.location.href='<?php echo getBaseUrl(); ?>products.php'" 
                            class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark transition-colors">
                        Clear Filters
                    </button>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach($products as $product_item): ?>
                        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="relative overflow-hidden rounded-t-lg">
                                <img src="<?php echo $product_item['image_url'] ?: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'; ?>" 
                                     alt="<?php echo htmlspecialchars($product_item['name']); ?>" 
                                     class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">
                                
                                <?php if($product_item['sale_price']): ?>
                                    <div class="absolute top-3 left-3 bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">
                                        SALE
                                    </div>
                                <?php endif; ?>
                                
                                <div class="absolute top-3 right-3 opacity-0 hover:opacity-100 transition-opacity duration-300">
                                    <button class="bg-white text-gray-700 p-2 rounded-full shadow-md hover:text-red-500 transition-colors">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                    <a href="<?php echo getBaseUrl(); ?>product.php?id=<?php echo $product_item['id']; ?>" 
                                       class="hover:text-primary transition-colors">
                                        <?php echo htmlspecialchars($product_item['name']); ?>
                                    </a>
                                </h3>
                                
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                    <?php echo htmlspecialchars($product_item['short_description']); ?>
                                </p>
                                
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg font-bold text-primary">
                                            $<?php echo number_format($product_item['sale_price'] ?: $product_item['price'], 2); ?>
                                        </span>
                                        <?php if($product_item['sale_price']): ?>
                                            <span class="text-sm text-gray-500 line-through">
                                                $<?php echo number_format($product_item['price'], 2); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="flex text-yellow-400 text-sm">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                </div>
                                
                                <button class="w-full bg-primary text-white py-2 rounded-lg font-semibold hover:bg-primary-dark transition-colors flex items-center justify-center space-x-2 add-to-cart-btn"
                                        data-product-id="<?php echo $product_item['id']; ?>">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>Add to Cart</span>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Load More -->
                <div class="text-center mt-8">
                    <button class="bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition-colors font-semibold">
                        Load More Products
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'views/partials/footer.php'; ?>