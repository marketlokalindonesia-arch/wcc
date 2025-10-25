<?php
// test_api.php - Automated API Testing Script

require_once 'config/database.php';
require_once 'config/session.php';

class APITester {
    private $baseUrl;
    private $sessionCookie;
    private $results = [];
    
    public function __construct() {
        $this->baseUrl = 'http://localhost:5000';
    }
    
    private function request($method, $url, $data = null, $headers = []) {
        $ch = curl_init();
        
        $fullUrl = $this->baseUrl . $url;
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        
        if ($this->sessionCookie) {
            $headers[] = 'Cookie: ' . $this->sessionCookie;
        }
        
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data) {
                if (is_array($data)) {
                    $jsonData = json_encode($data);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, ['Content-Type: application/json']));
                } else {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
            }
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Extract headers
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $responseHeaders = substr($response, 0, $headerSize);
        $responseBody = substr($response, $headerSize);
        
        // Extract session cookie
        if (preg_match('/Set-Cookie: PHPSESSID=([^;]+)/', $responseHeaders, $matches)) {
            $this->sessionCookie = 'PHPSESSID=' . $matches[1];
        }
        
        curl_close($ch);
        
        return [
            'status' => $httpCode,
            'headers' => $responseHeaders,
            'body' => $responseBody
        ];
    }
    
    private function login($username, $password) {
        echo "\n🔑 Logging in as $username...\n";
        
        $response = $this->request('POST', '/index.php?url=login/authenticate', 
            http_build_query(['username' => $username, 'password' => $password]),
            ['Content-Type: application/x-www-form-urlencoded']
        );
        
        if ($response['status'] == 302 || strpos($response['headers'], 'Location:') !== false) {
            echo "✅ Login successful\n";
            return true;
        }
        
        echo "❌ Login failed\n";
        return false;
    }
    
    private function test($name, $method, $url, $data = null, $expectedStatus = 200) {
        echo "\n📋 Testing: $name\n";
        echo "   URL: $method $url\n";
        
        $response = $this->request($method, $url, $data);
        $body = json_decode($response['body'], true);
        
        $passed = $response['status'] == $expectedStatus;
        
        if ($passed) {
            echo "✅ PASSED (Status: {$response['status']})\n";
            if ($body) {
                echo "   Response: " . json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            }
        } else {
            echo "❌ FAILED (Expected: $expectedStatus, Got: {$response['status']})\n";
            echo "   Response: " . $response['body'] . "\n";
        }
        
        $this->results[] = [
            'name' => $name,
            'passed' => $passed,
            'status' => $response['status'],
            'response' => $body
        ];
        
        return $body;
    }
    
    public function runAllTests() {
        echo "═══════════════════════════════════════════════════════\n";
        echo "  WC Clone API Test Suite\n";
        echo "═══════════════════════════════════════════════════════\n";
        
        // Login first
        $this->login('cashier', 'password');
        
        echo "\n" . str_repeat("─", 55) . "\n";
        echo "  PRODUCTS API TESTS\n";
        echo str_repeat("─", 55) . "\n";
        
        // Test Products API
        $this->test(
            'Get All Products',
            'GET',
            '/api/products.php'
        );
        
        $this->test(
            'Search Products',
            'GET',
            '/api/products.php?search=laptop'
        );
        
        $this->test(
            'Get Products by Category',
            'GET',
            '/api/products.php?category_id=1'
        );
        
        $this->test(
            'Get Featured Products',
            'GET',
            '/api/products.php?featured=1'
        );
        
        echo "\n" . str_repeat("─", 55) . "\n";
        echo "  POS API TESTS\n";
        echo str_repeat("─", 55) . "\n";
        
        // Test POS API
        $this->test(
            'POS Search Products',
            'GET',
            '/api/pos.php?action=search&q=laptop'
        );
        
        $this->test(
            'POS Get Product by Barcode',
            'GET',
            '/api/pos.php?action=get_by_barcode&barcode=1234567890123'
        );
        
        $this->test(
            'POS Get Cashier Stats',
            'GET',
            '/api/pos.php?action=cashier_stats&date=' . date('Y-m-d')
        );
        
        $orderData = [
            'items' => [
                [
                    'product_id' => 1,
                    'name' => 'Laptop Gaming ASUS ROG',
                    'price' => 14500000,
                    'quantity' => 1
                ]
            ],
            'payment_method' => 'Cash',
            'total_amount' => 14500000
        ];
        
        $this->test(
            'POS Create Order',
            'POST',
            '/api/pos.php?action=create_order',
            $orderData
        );
        
        echo "\n" . str_repeat("─", 55) . "\n";
        echo "  CART API TESTS\n";
        echo str_repeat("─", 55) . "\n";
        
        // Test Cart API
        $this->test(
            'Get Cart Items',
            'GET',
            '/api/cart.php'
        );
        
        $this->test(
            'Get Cart Count',
            'GET',
            '/api/cart.php?action=count'
        );
        
        $this->test(
            'Add Product to Cart',
            'POST',
            '/api/cart.php',
            ['action' => 'add', 'product_id' => 1, 'quantity' => 2]
        );
        
        $this->test(
            'Update Cart Item',
            'POST',
            '/api/cart.php',
            ['action' => 'update', 'product_id' => 1, 'quantity' => 3]
        );
        
        $this->test(
            'Remove Cart Item',
            'POST',
            '/api/cart.php',
            ['action' => 'remove', 'product_id' => 1]
        );
        
        // Summary
        echo "\n" . str_repeat("═", 55) . "\n";
        echo "  TEST SUMMARY\n";
        echo str_repeat("═", 55) . "\n";
        
        $passed = count(array_filter($this->results, fn($r) => $r['passed']));
        $total = count($this->results);
        $failed = $total - $passed;
        
        echo "Total Tests: $total\n";
        echo "✅ Passed: $passed\n";
        echo "❌ Failed: $failed\n";
        echo "\nSuccess Rate: " . round(($passed / $total) * 100, 2) . "%\n";
        echo str_repeat("═", 55) . "\n";
        
        return $this->results;
    }
}

// Run tests
$tester = new APITester();
$tester->runAllTests();
?>
