<?php
require_once 'config/database.php';
require_once 'config/session.php';

class POSController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function searchProducts($query) {
        try {
            $search = "%$query%";
            $sql = "SELECT p.*, 
                          (SELECT image_url FROM product_images WHERE product_id = p.id AND is_featured = true LIMIT 1) as image
                   FROM products p 
                   WHERE (p.name ILIKE :search OR p.sku ILIKE :search OR p.barcode ILIKE :search)
                   AND p.status = 'publish' AND p.stock_quantity > 0 
                   ORDER BY p.name ASC LIMIT 20";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':search', $search);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function getProductByBarcode($barcode) {
        try {
            $query = "SELECT p.*, 
                           (SELECT image_url FROM product_images WHERE product_id = p.id AND is_featured = true LIMIT 1) as image
                     FROM products p 
                     WHERE p.barcode = :barcode AND p.status = 'publish' LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':barcode', $barcode);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    public function createOrder($data) {
        try {
            $this->db->beginTransaction();
            
            // Generate order number
            $order_number = 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // Insert order
            $query = "INSERT INTO orders (order_number, customer_id, status, total_amount, payment_method, payment_status, created_by) 
                     VALUES (:order_number, :customer_id, :status, :total_amount, :payment_method, :payment_status, :created_by) 
                     RETURNING id";
            $stmt = $this->db->prepare($query);
            
            $customer_id = $data['customer_id'] ?? 1;
            $status = 'completed';
            $payment_status = 'paid';
            
            $stmt->bindParam(':order_number', $order_number);
            $stmt->bindParam(':customer_id', $customer_id);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':total_amount', $data['total_amount']);
            $stmt->bindParam(':payment_method', $data['payment_method']);
            $stmt->bindParam(':payment_status', $payment_status);
            $stmt->bindParam(':created_by', $_SESSION['user_id']);
            $stmt->execute();
            
            $order_id = $stmt->fetch()['id'];
            
            // Insert order items
            foreach ($data['items'] as $item) {
                $query = "INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal) 
                         VALUES (:order_id, :product_id, :product_name, :product_price, :quantity, :subtotal)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':order_id', $order_id);
                $stmt->bindParam(':product_id', $item['product_id']);
                $stmt->bindParam(':product_name', $item['name']);
                $stmt->bindParam(':product_price', $item['price']);
                $stmt->bindParam(':quantity', $item['quantity']);
                $stmt->bindParam(':subtotal', $item['subtotal']);
                $stmt->execute();
                
                // Update stock
                $query = "UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE id = :product_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':quantity', $item['quantity']);
                $stmt->bindParam(':product_id', $item['product_id']);
                $stmt->execute();
                
                // Log inventory
                $query = "INSERT INTO inventory_logs (product_id, user_id, action_type, quantity_change, notes) 
                         VALUES (:product_id, :user_id, 'sale', :quantity, :notes)";
                $stmt = $this->db->prepare($query);
                $notes = 'POS Sale - Order #' . $order_number;
                $quantity_neg = -$item['quantity'];
                $stmt->bindParam(':product_id', $item['product_id']);
                $stmt->bindParam(':user_id', $_SESSION['user_id']);
                $stmt->bindParam(':quantity', $quantity_neg);
                $stmt->bindParam(':notes', $notes);
                $stmt->execute();
            }
            
            // Create transaction
            $query = "INSERT INTO transactions (order_id, transaction_type, amount, payment_method, cashier_id) 
                     VALUES (:order_id, 'sale', :amount, :payment_method, :cashier_id)";
            $stmt = $this->db->prepare($query);
            $type = 'sale';
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':amount', $data['total_amount']);
            $stmt->bindParam(':payment_method', $data['payment_method']);
            $stmt->bindParam(':cashier_id', $_SESSION['user_id']);
            $stmt->execute();
            
            $this->db->commit();
            
            return ['success' => true, 'order_id' => $order_id, 'order_number' => $order_number];
        } catch (PDOException $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function getCashierStats($cashier_id, $date = null) {
        try {
            if (!$date) {
                $date = date('Y-m-d');
            }
            
            $stats = [];
            
            // Today's sales
            $query = "SELECT COUNT(*) as count, COALESCE(SUM(total_amount), 0) as total 
                     FROM orders 
                     WHERE created_by = :cashier_id 
                     AND DATE(created_at) = :date 
                     AND payment_status = 'paid'";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':cashier_id', $cashier_id);
            $stmt->bindParam(':date', $date);
            $stmt->execute();
            $result = $stmt->fetch();
            $stats['total_sales'] = $result['total'];
            $stats['total_transactions'] = $result['count'];
            
            return $stats;
        } catch (PDOException $e) {
            return ['total_sales' => 0, 'total_transactions' => 0];
        }
    }
}
?>
