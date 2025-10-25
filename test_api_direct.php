#!/usr/bin/env php
<?php
// Direct API test tanpa session check - untuk debug
echo "=== Direct POS API Test ===\n\n";

require_once 'config/database.php';
require_once 'controllers/POSController.php';

$pos = new POSController();

echo "1. Testing searchProducts('laptop')...\n";
$products = $pos->searchProducts('laptop');
echo "Result: " . count($products) . " products found\n";
print_r($products);
echo "\n";

echo "2. Testing searchProducts('mouse')...\n";
$products = $pos->searchProducts('mouse');
echo "Result: " . count($products) . " products found\n";
print_r($products);
echo "\n";

echo "3. Testing getProductByBarcode('1234567890123')...\n";
$product = $pos->getProductByBarcode('1234567890123');
echo "Result: " . ($product ? "Found" : "Not found") . "\n";
print_r($product);
echo "\n";

echo "4. Direct database query...\n";
$db = new Database();
$conn = $db->getConnection();
$sql = "SELECT id, name, sku, barcode, price, stock_quantity, status FROM products WHERE status = 'publish' LIMIT 5";
$stmt = $conn->query($sql);
$results = $stmt->fetchAll();
echo "Total products in DB: " . count($results) . "\n";
foreach ($results as $p) {
    echo "  - {$p['name']} (ID: {$p['id']}, Stock: {$p['stock_quantity']}, Status: {$p['status']})\n";
}
?>
