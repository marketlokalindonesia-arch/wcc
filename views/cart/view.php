<?php
// cart.php
require_once 'config/helpers.php';


$page_title = "Shopping Cart - WC Clone";
$current_page = 'cart';
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
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Shopping Cart</span>
                </div>
            </li>
        </ol>
    </nav>

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <?php if(empty($_SESSION['cart'])): ?>
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <i class="fas fa-shopping-cart text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Your cart is empty</h3>
                    <p class="text-gray-600 mb-6">Looks like you haven't added any items to your cart yet.</p>
                    <a href="<?php echo getBaseUrl(); ?>products.php" 
                       class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-dark transition-colors font-semibold inline-flex items-center space-x-2">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Start Shopping</span>
                    </a>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-sm">
                    <!-- Cart Header -->
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">Cart Items (<?php echo count($_SESSION['cart']); ?>)</h2>
                        <button onclick="clearCart()" class="text-red-600 hover:text-red-700 text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i>
                            Clear Cart
                        </button>
                    </div>

                    <!-- Cart Items -->
                    <div class="divide-y divide-gray-200">
                        <?php foreach($_SESSION['cart'] as $index => $item): ?>
                            <div class="p-6 flex items-center space-x-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" 
                                         alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                         class="w-20 h-20 object-cover rounded-lg">
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-medium text-gray-900 truncate">
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </h3>
                                    <p class="text-gray-500 text-sm">SKU: PROD-<?php echo $item['product_id']; ?></p>
                                    <p class="text-green-600 text-sm font-medium">In Stock</p>
                                </div>

                                <!-- Quantity -->
                                <div class="flex items-center space-x-2">
                                    <button onclick="updateQuantity(<?php echo $item['product_id']; ?>, <?php echo $item['quantity'] - 1; ?>)" 
                                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <span class="w-12 text-center"><?php echo $item['quantity']; ?></span>
                                    <button onclick="updateQuantity(<?php echo $item['product_id']; ?>, <?php echo $item['quantity'] + 1; ?>)" 
                                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>

                                <!-- Price -->
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-gray-900">
                                        $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                    </p>
                                    <p class="text-gray-500 text-sm">$<?php echo number_format($item['price'], 2); ?> each</p>
                                </div>

                                <!-- Remove -->
                                <button onclick="removeFromCart(<?php echo $item['product_id']; ?>)" 
                                        class="text-red-600 hover:text-red-700 p-2">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                
                <div class="space-y-3 mb-6">
                    <?php
                    $subtotal = 0;
                    foreach($_SESSION['cart'] as $item) {
                        $subtotal += $item['price'] * $item['quantity'];
                    }
                    $shipping = $subtotal >= 50 ? 0 : 4.99;
                    $tax = $subtotal * 0.08;
                    $total = $subtotal + $shipping + $tax;
                    ?>
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-medium"><?php echo $shipping == 0 ? 'FREE' : '$' . number_format($shipping, 2); ?></span>
                    </div>
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tax</span>
                        <span class="font-medium">$<?php echo number_format($tax, 2); ?></span>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between text-base font-semibold">
                            <span>Total</span>
                            <span>$<?php echo number_format($total, 2); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Progress -->
                <?php if($subtotal < 50): ?>
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="text-blue-800 font-medium">
                                Add $<?php echo number_format(50 - $subtotal, 2); ?> for FREE shipping!
                            </span>
                        </div>
                        <div class="w-full bg-blue-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo ($subtotal / 50) * 100; ?>%"></div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-green-50 rounded-lg p-4 mb-6">
                        <div class="flex items-center text-sm text-green-800">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>You qualify for FREE shipping!</span>
                        </div>
                    </div>
                <?php endif; ?>

                <button onclick="proceedToCheckout()" 
                        class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-dark transition-colors mb-4 flex items-center justify-center space-x-2">
                    <i class="fas fa-lock"></i>
                    <span>Proceed to Checkout</span>
                </button>

                <div class="text-center text-sm text-gray-600 mb-4">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Secure checkout guaranteed
                </div>

                <a href="<?php echo getBaseUrl(); ?>products.php" 
                   class="w-full border border-gray-300 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors flex items-center justify-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Continue Shopping</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
async function updateQuantity(productId, newQuantity) {
    if (newQuantity < 1) {
        removeFromCart(productId);
        return;
    }

    try {
        const response = await fetch('<?php echo getBaseUrl(); ?>cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'update',
                product_id: productId,
                quantity: newQuantity
            })
        });

        const result = await response.json();
        if (result.success) {
            location.reload();
        }
    } catch (error) {
        console.error('Error updating quantity:', error);
    }
}

async function removeFromCart(productId) {
    try {
        const response = await fetch('<?php echo getBaseUrl(); ?>cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'remove',
                product_id: productId
            })
        });

        const result = await response.json();
        if (result.success) {
            location.reload();
        }
    } catch (error) {
        console.error('Error removing item:', error);
    }
}

async function clearCart() {
    if (!confirm('Are you sure you want to clear your cart?')) return;

    try {
        const response = await fetch('<?php echo getBaseUrl(); ?>cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'clear'
            })
        });

        const result = await response.json();
        if (result.success) {
            location.reload();
        }
    } catch (error) {
        console.error('Error clearing cart:', error);
    }
}

function proceedToCheckout() {
    alert('Checkout functionality would go here!');
    // window.location.href = '<?php echo getBaseUrl(); ?>checkout.php';
}
</script>

<?php include 'views/partials/footer.php'; ?>