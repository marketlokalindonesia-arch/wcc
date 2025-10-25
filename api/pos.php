<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../controllers/POSController.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (!in_array($_SESSION['user_role'], ['admin', 'cashier'])) {
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit;
}

$posController = new POSController();
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'search':
            $query = $_GET['q'] ?? '';
            if (strlen($query) < 2) {
                echo json_encode(['success' => true, 'products' => []]);
                exit;
            }
            
            $products = $posController->searchProducts($query);
            echo json_encode(['success' => true, 'products' => $products]);
            break;
            
        case 'get_by_barcode':
            $barcode = $_GET['barcode'] ?? '';
            if (empty($barcode)) {
                echo json_encode(['success' => false, 'message' => 'Barcode required']);
                exit;
            }
            
            $product = $posController->getProductByBarcode($barcode);
            if ($product) {
                echo json_encode(['success' => true, 'product' => $product]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
            }
            break;
            
        case 'create_order':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                exit;
            }
            
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            if (!$data || empty($data['items']) || empty($data['payment_method'])) {
                echo json_encode(['success' => false, 'message' => 'Invalid order data']);
                exit;
            }
            
            $result = $posController->createOrder($data);
            echo json_encode($result);
            break;
            
        case 'cashier_stats':
            $cashier_id = $_SESSION['user_id'];
            $date = $_GET['date'] ?? date('Y-m-d');
            
            $stats = $posController->getCashierStats($cashier_id, $date);
            echo json_encode(['success' => true, 'stats' => $stats]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
