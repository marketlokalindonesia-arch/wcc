<?php
// views/partials/footer.php
?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-r from-primary to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-store text-white text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold text-gradient">WC Clone</span>
                    </div>
                    <p class="text-gray-400 mb-6 leading-relaxed">
                        Your trusted online destination for quality products. We offer the best prices, 
                        fast shipping, and excellent customer service.
                    </p>
                    
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-primary transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-400 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-pink-600 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="<?php echo getBaseUrl(); ?>" class="text-gray-400 hover:text-white transition-colors">Home</a></li>
                        <li><a href="<?php echo getBaseUrl(); ?>products" class="text-gray-400 hover:text-white transition-colors">Shop</a></li>
                        <li><a href="<?php echo getBaseUrl(); ?>about" class="text-gray-400 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="<?php echo getBaseUrl(); ?>contact" class="text-gray-400 hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Customer Service -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">Customer Service</h3>
                    <ul class="space-y-3">
                        <li><a href="<?php echo getBaseUrl(); ?>shipping" class="text-gray-400 hover:text-white transition-colors">Shipping Info</a></li>
                        <li><a href="<?php echo getBaseUrl(); ?>returns" class="text-gray-400 hover:text-white transition-colors">Returns</a></li>
                        <li><a href="<?php echo getBaseUrl(); ?>faq" class="text-gray-400 hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="<?php echo getBaseUrl(); ?>privacy" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>

            <!-- Trust Badges -->
            <div class="border-t border-gray-800 mt-12 pt-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    <div class="flex items-center justify-center space-x-4">
                        <div class="flex items-center space-x-3 text-gray-400">
                            <i class="fas fa-shield-alt text-2xl text-green-500"></i>
                            <div>
                                <div class="font-semibold text-white">Secure Payment</div>
                                <div class="text-sm">100% Protected</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-center space-x-4">
                        <div class="flex items-center space-x-3 text-gray-400">
                            <i class="fas fa-truck text-2xl text-blue-500"></i>
                            <div>
                                <div class="font-semibold text-white">Free Shipping</div>
                                <div class="text-sm">On orders over $50</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-center space-x-4">
                        <div class="flex items-center space-x-3 text-gray-400">
                            <i class="fas fa-undo-alt text-2xl text-purple-500"></i>
                            <div>
                                <div class="font-semibold text-white">Easy Returns</div>
                                <div class="text-sm">30-Day Policy</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="border-t border-gray-800">
            <div class="container mx-auto px-4 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="text-gray-400 text-sm">
                        &copy; 2024 WC Clone. All rights reserved.
                    </div>
                    
                    <div class="flex items-center space-x-6 text-sm">
                        <a href="<?php echo getBaseUrl(); ?>privacy" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a>
                        <a href="<?php echo getBaseUrl(); ?>terms" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a>
                        <a href="<?php echo getBaseUrl(); ?>sitemap" class="text-gray-400 hover:text-white transition-colors">Sitemap</a>
                    </div>
                    
                    <button id="backToTop" class="bg-primary text-white p-3 rounded-full hover:bg-primary-dark transition-colors hover-lift">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
    // Base URL
    const BASE_URL = '<?php echo getBaseUrl(); ?>';

    // Mobile menu functionality
    document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
        document.getElementById('mobileNav').classList.remove('hidden');
        document.querySelector('#mobileNav > div').classList.remove('translate-x-full');
    });

    document.getElementById('mobileNavClose')?.addEventListener('click', () => {
        document.getElementById('mobileNav').classList.add('hidden');
        document.querySelector('#mobileNav > div').classList.add('translate-x-full');
    });

    // Cart count update
    async function updateCartCount() {
        try {
            const response = await fetch(BASE_URL + 'api/cart.php?action=count');
            const data = await response.json();
            if (data.success) {
                document.getElementById('cartCount').textContent = data.count;
            }
        } catch (error) {
            console.error('Failed to fetch cart count:', error);
            document.getElementById('cartCount').textContent = '0';
        }
    }

    // Add to cart functionality
    async function addToCart(productId, quantity = 1) {
        try {
            const response = await fetch(BASE_URL + 'api/cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'add',
                    product_id: parseInt(productId),
                    quantity: parseInt(quantity)
                })
            });

            const result = await response.json();
            
            if (result.success) {
                showNotification('Product added to cart successfully!', 'success');
                updateCartCount();
            } else {
                showNotification(result.message || 'Failed to add product to cart', 'error');
            }
        } catch (error) {
            showNotification('An error occurred while adding to cart', 'error');
            console.error('Add to cart error:', error);
        }
    }

    // Notification system
    function showNotification(message, type = 'info') {
        const notification = document.getElementById('notification');
        if (!notification) return;

        const bgColor = {
            success: 'bg-green-50 border-green-500',
            error: 'bg-red-50 border-red-500',
            warning: 'bg-yellow-50 border-yellow-500',
            info: 'bg-blue-50 border-blue-500'
        }[type];

        const textColor = {
            success: 'text-green-800',
            error: 'text-red-800',
            warning: 'text-yellow-800',
            info: 'text-blue-800'
        }[type];

        const notificationElement = document.createElement('div');
        notificationElement.className = `${bgColor} border-l-4 p-4 rounded shadow-lg mb-2 max-w-sm ${textColor}`;
        notificationElement.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'}-circle"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.remove()" class="inline-flex rounded-md p-1.5 hover:bg-gray-100">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;

        notification.appendChild(notificationElement);
        
        setTimeout(() => {
            if (notificationElement.parentElement) {
                notificationElement.remove();
            }
        }, 5000);
    }

    // Back to top
    document.getElementById('backToTop')?.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateCartCount();
        
        // Handle add to cart buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.add-to-cart-btn')) {
                const button = e.target.closest('.add-to-cart-btn');
                const productId = button.dataset.productId || '1';
                addToCart(productId, 1);
            }
        });
    });
    </script>
</body>
</html>