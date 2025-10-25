<?php
// api/cart.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';
require_once '../models/Cart.php';

$database = new Database();
$db = $database->getConnection();
$cart = new Cart($db);


// Jika belum login, gunakan session cart
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Default user untuk demo
    $_SESSION['cart'] = $_SESSION['cart'] ?? [];
}

$user_id = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// Handle preflight request
if ($method == 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    switch($method) {
        case 'GET':
            if($action == 'count') {
                // Untuk demo, kita hitung dari session
                $count = 0;
                if (isset($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        $count += $item['quantity'];
                    }
                }
                
                echo json_encode([
                    'success' => true,
                    'count' => $count
                ]);
            } else {
                $items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
                echo json_encode([
                    'success' => true,
                    'data' => $items
                ]);
            }
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            
            if(!$input && !empty($_POST)) {
                $input = $_POST;
            }
            
            // Default values jika tidak ada input
            if (!$input) {
                $input = [
                    'action' => 'add',
                    'product_id' => 1,
                    'quantity' => 1
                ];
            }

            $product_id = $input['product_id'] ?? '';
            $quantity = $input['quantity'] ?? 1;
            $action = $input['action'] ?? 'add';

            // Initialize cart session jika belum ada
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            $result = false;
            $message = '';

            switch($action) {
                case 'add':
                    // Cek apakah product sudah ada di cart
                    $found = false;
                    foreach ($_SESSION['cart'] as &$item) {
                        if ($item['product_id'] == $product_id) {
                            $item['quantity'] += $quantity;
                            $found = true;
                            break;
                        }
                    }
                    
                    if (!$found) {
                        $_SESSION['cart'][] = [
                            'product_id' => $product_id,
                            'quantity' => $quantity,
                            'added_at' => date('Y-m-d H:i:s')
                        ];
                    }
                    $result = true;
                    $message = 'Product added to cart successfully';
                    break;
                    
                case 'update':
                    foreach ($_SESSION['cart'] as &$item) {
                        if ($item['product_id'] == $product_id) {
                            $item['quantity'] = $quantity;
                            $result = true;
                            $message = 'Cart updated successfully';
                            break;
                        }
                    }
                    break;
                    
                case 'remove':
                    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($product_id) {
                        return $item['product_id'] != $product_id;
                    });
                    $result = true;
                    $message = 'Product removed from cart';
                    break;
                    
                default:
                    $result = false;
                    $message = 'Unknown action';
            }

            if($result) {
                // Hitung total items
                $count = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $count += $item['quantity'];
                }
                
                echo json_encode([
                    'success' => true,
                    'message' => $message,
                    'cart_count' => $count,
                    'cart_items' => $_SESSION['cart']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $message
                ]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
?>