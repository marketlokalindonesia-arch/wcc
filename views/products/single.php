<?php
// product.php
require_once 'config/helpers.php';
require_once 'config/database.php';
require_once 'models/Product.php';
require_once 'models/Category.php';


$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$category = new Category($db);

$product_id = isset($_GET['id']) ? $_GET['id'] : die('Product ID is required');

// Get product details
$product->id = $product_id;
if(!$product->readOne()) {
    header('Location: ' . getBaseUrl() . 'products.php');
    exit;
}

// Get product images
$images = $product->getImages();

// Get related products (dummy data untuk demo)
$related_products = $product->read(['limit' => 4])->fetchAll(PDO::FETCH_ASSOC);

$page_title = $product->name . " - WC Clone";
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
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="<?php echo getBaseUrl(); ?>products.php" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary">Products</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2"><?php echo htmlspecialchars($product->name); ?></span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Images -->
            <div>
                <div class="rounded-lg overflow-hidden mb-4">
                    <img src="<?php echo $images[0]['image_url'] ?? 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'; ?>" 
                         alt="<?php echo htmlspecialchars($product->name); ?>" 
                         class="w-full h-96 object-cover" id="mainProductImage">
                </div>
                
                <?php if(count($images) > 1): ?>
                    <div class="grid grid-cols-4 gap-2">
                        <?php foreach($images as $index => $image): ?>
                            <button class="border-2 border-transparent hover:border-primary rounded-lg overflow-hidden transition-colors <?php echo $index === 0 ? 'border-primary' : ''; ?>"
                                    onclick="document.getElementById('mainProductImage').src = '<?php echo $image['image_url']; ?>'; 
                                             document.querySelectorAll('.thumbnail-btn').forEach(btn => btn.classList.remove('border-primary'));
                                             this.classList.add('border-primary');">
                                <img src="<?php echo $image['image_url']; ?>" 
                                     alt="<?php echo htmlspecialchars($image['alt_text'] ?? $product->name); ?>" 
                                     class="w-full h-20 object-cover thumbnail-btn">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($product->name); ?></h1>
                
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex items-center">
                        <div class="flex text-yellow-400 mr-2">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="text-sm text-gray-600">(42 reviews)</span>
                    </div>
                    <span class="text-sm text-gray-600">SKU: <?php echo $product->sku; ?></span>
                </div>

                <div class="mb-6">
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="text-3xl font-bold text-primary">$<?php echo number_format($product->sale_price ?: $product->price, 2); ?></span>
                        <?php if($product->sale_price): ?>
                            <span class="text-xl text-gray-500 line-through">$<?php echo number_format($product->price, 2); ?></span>
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-semibold">
                                Save $<?php echo number_format($product->price - $product->sale_price, 2); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <span class="<?php echo $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-600'; ?> font-semibold">
                            <?php echo $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock'; ?>
                        </span>
                        <span>•</span>
                        <span><?php echo $product->stock_quantity; ?> units available</span>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="text-gray-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($product->description)); ?></p>
                </div>

                <form class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <label class="text-sm font-medium text-gray-700">Quantity:</label>
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button type="button" class="px-3 py-2 text-gray-600 hover:text-gray-700">-</button>
                            <input type="number" value="1" min="1" max="<?php echo $product->stock_quantity; ?>" 
                                   class="w-16 text-center border-0 focus:ring-0">
                            <button type="button" class="px-3 py-2 text-gray-600 hover:text-gray-700">+</button>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <button type="button" 
                                class="flex-1 bg-primary text-white py-3 px-6 rounded-lg font-semibold hover:bg-primary-dark transition-colors flex items-center justify-center space-x-2 add-to-cart-btn"
                                data-product-id="<?php echo $product->id; ?>"
                                <?php echo $product->stock_quantity <= 0 ? 'disabled' : ''; ?>>
                            <i class="fas fa-shopping-cart"></i>
                            <span><?php echo $product->stock_quantity > 0 ? 'Add to Cart' : 'Out of Stock'; ?></span>
                        </button>
                        
                        <button type="button" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="far fa-heart text-gray-600"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-6 space-y-3 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-truck text-primary mr-2"></i>
                        <span>Free shipping on orders over $50</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-undo-alt text-primary mr-2"></i>
                        <span>30-day return policy</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt text-primary mr-2"></i>
                        <span>2-year warranty included</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Tabs -->
    <div class="bg-white rounded-lg shadow-sm mb-8">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8">
                <button class="py-4 px-1 border-b-2 border-primary text-sm font-medium text-primary">Description</button>
                <button class="py-4 px-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700">Reviews (42)</button>
                <button class="py-4 px-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700">Shipping & Returns</button>
            </nav>
        </div>
        
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Product Description</h3>
            <div class="prose max-w-none">
                <p><?php echo nl2br(htmlspecialchars($product->description)); ?></p>
                
                <?php if($product->short_description): ?>
                    <h4 class="text-md font-semibold mt-6 mb-3">Key Features</h4>
                    <p><?php echo nl2br(htmlspecialchars($product->short_description)); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if(!empty($related_products)): ?>
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach($related_products as $related_product): ?>
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="relative overflow-hidden rounded-t-lg">
                            <img src="<?php echo $related_product['image_url'] ?: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'; ?>" 
                                 alt="<?php echo htmlspecialchars($related_product['name']); ?>" 
                                 class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                <a href="<?php echo getBaseUrl(); ?>product.php?id=<?php echo $related_product['id']; ?>" 
                                   class="hover:text-primary transition-colors">
                                    <?php echo htmlspecialchars($related_product['name']); ?>
                                </a>
                            </h3>
                            
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-lg font-bold text-primary">
                                    $<?php echo number_format($related_product['sale_price'] ?: $related_product['price'], 2); ?>
                                </span>
                            </div>
                            
                            <button class="w-full bg-primary text-white py-2 rounded-lg font-semibold hover:bg-primary-dark transition-colors flex items-center justify-center space-x-2 add-to-cart-btn"
                                    data-product-id="<?php echo $related_product['id']; ?>">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Add to Cart</span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/partials/footer.php'; ?>