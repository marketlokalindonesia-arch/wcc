<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('cashier');

$user = getUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - WC Clone</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: #f8f9fa;
            overflow-x: hidden;
        }
        .pos-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .pos-container {
            display: flex;
            height: calc(100vh - 70px);
        }
        .products-section {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }
        .cart-section {
            width: 400px;
            background: white;
            border-left: 1px solid #dee2e6;
            display: flex;
            flex-direction: column;
        }
        
        .category-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
        }
        .category-tab {
            padding: 10px 20px;
            border-radius: 25px;
            border: 2px solid #e0e0e0;
            background: white;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            font-size: 14px;
        }
        .category-tab:hover {
            border-color: #667eea;
            background: #f0f3ff;
        }
        .category-tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }
        .category-tab i {
            font-size: 16px;
        }
        
        .search-box {
            position: relative;
            margin-bottom: 20px;
        }
        .search-box input {
            padding: 12px 45px 12px 15px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            width: 100%;
        }
        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
        }
        .product-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-3px);
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        .product-card .stock-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .product-card .stock-badge.low-stock {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .product-card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .product-card .name {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .product-card .price {
            color: #667eea;
            font-weight: 700;
            font-size: 16px;
        }
        .cart-header {
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
        }
        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
        }
        .cart-item {
            display: flex;
            align-items: center;
            padding: 12px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .cart-item .item-info {
            flex: 1;
        }
        .cart-item .item-name {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 3px;
        }
        .cart-item .item-price {
            color: #6c757d;
            font-size: 13px;
        }
        .cart-item .qty-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .cart-item .qty-btn {
            width: 30px;
            height: 30px;
            border-radius: 5px;
            border: none;
            background: #667eea;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cart-item .qty-btn:hover {
            background: #5568d3;
        }
        .cart-item .qty {
            min-width: 30px;
            text-align: center;
            font-weight: 600;
        }
        .cart-footer {
            border-top: 1px solid #dee2e6;
            padding: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 700;
        }
        .payment-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }
        .payment-btn {
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        .payment-btn:hover {
            border-color: #667eea;
            background: #f0f3ff;
        }
        .payment-btn.selected {
            border-color: #667eea;
            background: #667eea;
            color: white;
        }
        .checkout-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
        }
        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 233, 123, 0.4);
        }
        .checkout-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .empty-cart {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }
        .empty-cart i {
            font-size: 60px;
            margin-bottom: 15px;
            opacity: 0.3;
        }
        .loading {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="pos-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0"><i class="fas fa-cash-register me-2"></i>Point of Sale</h4>
            </div>
            <div class="d-flex gap-3 align-items-center">
                <div class="text-end me-3">
                    <div style="font-size: 12px; opacity: 0.8;">Cashier</div>
                    <div style="font-weight: 600;"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                </div>
                <a href="/?url=cashier/dashboard" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
                <a href="/?url=logout" class="btn btn-outline-light">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="pos-container">
        <div class="products-section">
            <div class="category-tabs" id="categoryTabs">
                <div class="category-tab active" data-category="" data-category-id="">
                    <i class="fas fa-th"></i>
                    <span>All Categories</span>
                </div>
            </div>
            
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search products by name, SKU, or scan barcode..." autofocus>
                <i class="fas fa-search"></i>
            </div>
            
            <div id="productsGrid" class="product-grid">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin fa-3x mb-3"></i>
                    <p>Loading products...</p>
                </div>
            </div>
        </div>

        <div class="cart-section">
            <div class="cart-header">
                <h5 class="mb-0">Current Order</h5>
            </div>

            <div class="cart-items" id="cartItems">
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Cart is empty</p>
                </div>
            </div>

            <div class="cart-footer">
                <div class="total-row">
                    <span>Total:</span>
                    <span id="totalAmount">Rp 0</span>
                </div>

                <div class="payment-buttons">
                    <div class="payment-btn" data-method="cash">
                        <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                        <div>Cash</div>
                    </div>
                    <div class="payment-btn" data-method="card">
                        <i class="fas fa-credit-card fa-2x mb-2"></i>
                        <div>Card</div>
                    </div>
                    <div class="payment-btn" data-method="e-wallet">
                        <i class="fas fa-wallet fa-2x mb-2"></i>
                        <div>E-Wallet</div>
                    </div>
                    <div class="payment-btn" data-method="bank_transfer">
                        <i class="fas fa-university fa-2x mb-2"></i>
                        <div>Transfer</div>
                    </div>
                </div>

                <button class="checkout-btn" id="checkoutBtn" disabled>
                    <i class="fas fa-check-circle me-2"></i>Complete Order
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let cart = [];
        let selectedPayment = null;
        let searchTimeout = null;
        let allProducts = [];
        let allCategories = [];
        let currentCategoryId = '';

        function formatRupiah(amount) {
            return 'Rp ' + Math.round(amount).toLocaleString('id-ID');
        }

        function getCategoryIcon(categoryName) {
            const icons = {
                'Electronics': 'laptop',
                'Fashion': 'tshirt',
                'Home & Garden': 'home',
                'Home': 'home',
                'Sports': 'basketball-ball',
                'Beauty': 'spa',
                'Toys': 'gamepad',
                'Books': 'book',
                'Food': 'utensils',
                'Makanan': 'utensils',
                'Minuman': 'mug-hot',
                'Drinks': 'mug-hot',
                'Beverages': 'coffee'
            };
            return icons[categoryName] || 'box';
        }

        function loadCategories() {
            fetch('/api/categories.php')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        allCategories = data.data || [];
                        displayCategoryTabs();
                    }
                })
                .catch(err => {
                    console.error('Categories load error:', err);
                });
        }

        function displayCategoryTabs() {
            const container = document.getElementById('categoryTabs');
            const allTab = container.querySelector('[data-category-id=""]');
            
            allCategories.forEach(category => {
                const tab = document.createElement('div');
                tab.className = 'category-tab';
                tab.dataset.categoryId = category.id;
                tab.dataset.category = category.name;
                tab.innerHTML = `
                    <i class="fas fa-${getCategoryIcon(category.name)}"></i>
                    <span>${category.name}</span>
                `;
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentCategoryId = this.dataset.categoryId;
                    document.getElementById('searchInput').value = '';
                    filterProductsByCategory(currentCategoryId);
                });
                container.appendChild(tab);
            });

            allTab.addEventListener('click', function() {
                document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                currentCategoryId = '';
                document.getElementById('searchInput').value = '';
                displayProducts(allProducts);
            });
        }

        function loadAllProducts() {
            fetch('/api/products.php')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        allProducts = data.data || [];
                        displayProducts(allProducts);
                    } else {
                        document.getElementById('productsGrid').innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-exclamation-triangle fa-3x mb-3" style="opacity: 0.3;"></i><p>Failed to load products</p></div>';
                    }
                })
                .catch(err => {
                    console.error('Load error:', err);
                    document.getElementById('productsGrid').innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-exclamation-triangle fa-3x mb-3" style="opacity: 0.3;"></i><p>Error loading products</p></div>';
                });
        }

        document.getElementById('searchInput').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim().toLowerCase();
            
            searchTimeout = setTimeout(() => {
                if (query.length === 0) {
                    filterProductsByCategory(currentCategoryId);
                } else {
                    const filtered = allProducts.filter(product => 
                        product.name.toLowerCase().includes(query) || 
                        (product.sku && product.sku.toLowerCase().includes(query)) ||
                        (product.barcode && product.barcode.toLowerCase().includes(query))
                    );
                    displayProducts(filtered);
                }
            }, 300);
        });

        function filterProductsByCategory(categoryId) {
            if (!categoryId) {
                displayProducts(allProducts);
            } else {
                const filtered = allProducts.filter(product => {
                    if (!product.categories || product.categories.length === 0) {
                        return false;
                    }
                    return product.categories.some(cat => cat.id == categoryId);
                });
                displayProducts(filtered);
            }
        }

        function displayProducts(products) {
            const grid = document.getElementById('productsGrid');
            
            if (products.length === 0) {
                grid.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-box-open fa-3x mb-3" style="opacity: 0.3;"></i><p>No products found</p></div>';
                return;
            }

            grid.innerHTML = products.map(product => {
                const stock = parseInt(product.stock_quantity) || 0;
                const stockBadgeClass = stock < 10 ? 'low-stock' : '';
                const displayPrice = product.sale_price && parseFloat(product.sale_price) > 0 
                    ? parseFloat(product.sale_price) 
                    : parseFloat(product.price) || 0;
                
                if (stock <= 0) {
                    return '';
                }
                
                return `
                    <div class="product-card" onclick="addToCart(${product.id}, '${(product.name || '').replace(/'/g, "\\'")}', ${displayPrice}, ${stock})">
                        <div class="stock-badge ${stockBadgeClass}">${stock}</div>
                        <img src="${product.image || '/uploads/products/placeholder.jpg'}" alt="${product.name || 'Product'}" onerror="this.src='/uploads/products/placeholder.jpg'">
                        <div class="name">${product.name || 'Unnamed Product'}</div>
                        <div class="price">${formatRupiah(displayPrice)}</div>
                    </div>
                `;
            }).join('');
        }

        function addToCart(id, name, price, stock) {
            if (!id || !name || !price) {
                console.error('Invalid product data:', { id, name, price });
                alert('Cannot add product - invalid product data');
                return;
            }

            if (stock <= 0) {
                alert('Out of stock!');
                return;
            }

            const existingItem = cart.find(item => item.id === id);
            
            if (existingItem) {
                if (existingItem.quantity < stock) {
                    existingItem.quantity++;
                } else {
                    alert('Insufficient stock! Only ' + stock + ' available.');
                    return;
                }
            } else {
                cart.push({ id, name, price: parseFloat(price) || 0, quantity: 1, stock });
            }
            
            updateCart();
        }

        function updateQuantity(id, delta) {
            const item = cart.find(i => i.id === id);
            if (!item) return;
            
            const newQty = item.quantity + delta;
            
            if (newQty <= 0) {
                cart = cart.filter(i => i.id !== id);
            } else if (newQty <= item.stock) {
                item.quantity = newQty;
            } else {
                alert('Insufficient stock!');
                return;
            }
            
            updateCart();
        }

        function updateCart() {
            const container = document.getElementById('cartItems');
            
            if (cart.length === 0) {
                container.innerHTML = '<div class="empty-cart"><i class="fas fa-shopping-cart"></i><p>Cart is empty</p></div>';
                document.getElementById('totalAmount').textContent = 'Rp 0';
                document.getElementById('checkoutBtn').disabled = true;
                return;
            }

            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            container.innerHTML = cart.map(item => `
                <div class="cart-item">
                    <div class="item-info">
                        <div class="item-name">${item.name}</div>
                        <div class="item-price">${formatRupiah(item.price)} × ${item.quantity}</div>
                    </div>
                    <div class="qty-controls">
                        <button class="qty-btn" onclick="updateQuantity(${item.id}, -1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <div class="qty">${item.quantity}</div>
                        <button class="qty-btn" onclick="updateQuantity(${item.id}, 1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            `).join('');

            document.getElementById('totalAmount').textContent = formatRupiah(total);
            document.getElementById('checkoutBtn').disabled = !selectedPayment;
        }

        document.querySelectorAll('.payment-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.payment-btn').forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
                selectedPayment = this.dataset.method;
                document.getElementById('checkoutBtn').disabled = cart.length === 0;
            });
        });

        document.getElementById('checkoutBtn').addEventListener('click', function() {
            if (cart.length === 0 || !selectedPayment) return;

            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            const orderData = {
                items: cart.map(item => ({
                    product_id: item.id,
                    name: item.name,
                    price: item.price,
                    quantity: item.quantity,
                    subtotal: item.price * item.quantity
                })),
                total_amount: total,
                payment_method: selectedPayment
            };

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

            fetch('/api/pos.php?action=create_order', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(orderData)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Order completed successfully!\nOrder #: ' + data.order_number);
                    cart = [];
                    selectedPayment = null;
                    document.querySelectorAll('.payment-btn').forEach(b => b.classList.remove('selected'));
                    updateCart();
                    document.getElementById('searchInput').value = '';
                    loadAllProducts();
                } else {
                    alert('Error: ' + (data.message || 'Failed to create order'));
                }
            })
            .catch(err => {
                console.error('Checkout error:', err);
                alert('Error processing order');
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-check-circle me-2"></i>Complete Order';
            });
        });

        let barcodeBuffer = '';
        let barcodeTimeout = null;

        document.addEventListener('keypress', function(e) {
            if (e.target.id === 'searchInput') return;
            
            clearTimeout(barcodeTimeout);
            barcodeBuffer += e.key;
            
            barcodeTimeout = setTimeout(() => {
                if (barcodeBuffer.length > 3) {
                    document.getElementById('searchInput').value = barcodeBuffer;
                    const query = barcodeBuffer.toLowerCase();
                    const filtered = allProducts.filter(product => 
                        product.name.toLowerCase().includes(query) || 
                        (product.sku && product.sku.toLowerCase().includes(query)) ||
                        (product.barcode && product.barcode.toLowerCase().includes(query))
                    );
                    displayProducts(filtered);
                }
                barcodeBuffer = '';
            }, 100);
        });

        loadCategories();
        loadAllProducts();
    </script>
</body>
</html>
