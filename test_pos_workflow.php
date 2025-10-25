#!/usr/bin/env php
<?php

session_start();
require_once 'config/database.php';
require_once 'config/session.php';
require_once 'controllers/POSController.php';
require_once 'controllers/AuthController.php';

echo "=== WC Clone POS System Test ===\n\n";

$auth = new AuthController();
$pos = new POSController();

echo "1. Testing Admin Login...\n";
$result = $auth->login('admin', 'password');
if ($result['success']) {
    echo "   ✓ Admin login successful\n";
    echo "   Role: {$result['role']}\n\n";
} else {
    echo "   ✗ Admin login failed: {$result['message']}\n\n";
    exit(1);
}

echo "2. Testing Cashier Login...\n";
@session_destroy();
@session_start();
$result = $auth->login('cashier', 'password');
if ($result['success']) {
    echo "   ✓ Cashier login successful\n";
    echo "   Role: {$result['role']}\n\n";
} else {
    echo "   ✗ Cashier login failed: {$result['message']}\n\n";
    exit(1);
}

echo "3. Testing Product Search...\n";
$products = $pos->searchProducts('laptop');
if (count($products) > 0) {
    echo "   ✓ Found " . count($products) . " products matching 'laptop'\n";
    foreach ($products as $product) {
        echo "     - {$product['name']} (SKU: {$product['sku']}, Barcode: {$product['barcode']}, Price: \${$product['price']}, Stock: {$product['stock_quantity']})\n";
    }
    echo "\n";
} else {
    echo "   ✗ No products found\n\n";
    exit(1);
}

echo "4. Testing Barcode Search...\n";
$product = $pos->getProductByBarcode('1234567890123');
if ($product) {
    echo "   ✓ Product found by barcode\n";
    echo "     - {$product['name']} (Price: \${$product['price']}, Stock: {$product['stock_quantity']})\n\n";
} else {
    echo "   ✗ Product not found by barcode\n\n";
    exit(1);
}

echo "5. Testing Order Creation...\n";
$orderData = [
    'items' => [
        [
            'product_id' => 1,
            'name' => 'Laptop Gaming ASUS ROG',
            'price' => 15000000.00,
            'quantity' => 1,
            'subtotal' => 15000000.00
        ],
        [
            'product_id' => 8,
            'name' => 'Gaming Mouse Logitech',
            'price' => 750000.00,
            'quantity' => 2,
            'subtotal' => 1500000.00
        ]
    ],
    'total_amount' => 16500000.00,
    'payment_method' => 'cash'
];

$result = $pos->createOrder($orderData);
if ($result['success']) {
    echo "   ✓ Order created successfully\n";
    echo "     Order #: {$result['order_number']}\n";
    echo "     Order ID: {$result['order_id']}\n\n";
} else {
    echo "   ✗ Order creation failed: {$result['message']}\n\n";
    exit(1);
}

echo "6. Verifying Inventory Update...\n";
$db = new Database();
$conn = $db->getConnection();
$query = "SELECT id, name, stock_quantity FROM products WHERE id IN (1, 8)";
$stmt = $conn->query($query);
$products = $stmt->fetchAll();
echo "   Updated stock levels:\n";
foreach ($products as $product) {
    echo "     - {$product['name']}: {$product['stock_quantity']} units\n";
}
echo "\n";

echo "7. Verifying Inventory Logs...\n";
$query = "SELECT product_id, action_type, quantity_change, notes FROM inventory_logs ORDER BY created_at DESC LIMIT 3";
$stmt = $conn->query($query);
$logs = $stmt->fetchAll();
echo "   Recent inventory logs:\n";
foreach ($logs as $log) {
    echo "     - Product #{$log['product_id']}: {$log['action_type']}, Qty: {$log['quantity_change']}, Notes: {$log['notes']}\n";
}
echo "\n";

echo "8. Checking Dashboard Statistics...\n";
$query = "SELECT 
            COUNT(*) as total_orders,
            SUM(total_amount) as total_revenue
          FROM orders 
          WHERE DATE(created_at) = CURRENT_DATE";
$stmt = $conn->query($query);
$stats = $stmt->fetch();
echo "   Today's Statistics:\n";
echo "     - Total Orders: {$stats['total_orders']}\n";
echo "     - Total Revenue: \$" . number_format($stats['total_revenue'], 2) . "\n\n";

echo "=== All Tests Completed Successfully! ===\n";
echo "\nLogin Credentials for Manual Testing:\n";
echo "  Admin: username='admin', password='password'\n";
echo "  Cashier: username='cashier', password='password'\n\n";
echo "Access POS System:\n";
echo "  Admin POS: /?url=admin/pos/pos\n";
echo "  Cashier POS: /?url=cashier/pos\n";
?>
