<?php
require_once 'config/database.php';
require_once 'config/session.php';

class InventoryController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function getLowStockProducts($threshold = 10) {
        try {
            $query = "SELECT p.*, 
                           (SELECT image_url FROM product_images WHERE product_id = p.id AND is_featured = true LIMIT 1) as image
                     FROM products p 
                     WHERE p.stock_quantity <= :threshold AND p.status = 'publish'
                     ORDER BY p.stock_quantity ASC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':threshold', $threshold, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function adjustStock($product_id, $quantity, $action, $notes = '') {
        try {
            $this->db->beginTransaction();
            
            // Get current stock
            $query = "SELECT stock_quantity FROM products WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $product_id);
            $stmt->execute();
            $product = $stmt->fetch();
            
            if (!$product) {
                throw new Exception('Product not found');
            }
            
            $stock_before = $product['stock_quantity'];
            $quantity_change = ($action === 'add') ? $quantity : -$quantity;
            $stock_after = $stock_before + $quantity_change;
            
            if ($stock_after < 0) {
                throw new Exception('Insufficient stock');
            }
            
            // Update product stock
            $query = "UPDATE products SET stock_quantity = :stock, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':stock', $stock_after);
            $stmt->bindParam(':id', $product_id);
            $stmt->execute();
            
            // Log inventory change
            $query = "INSERT INTO inventory_logs (product_id, user_id, action_type, quantity_change, stock_before, stock_after, notes) 
                     VALUES (:product_id, :user_id, :action_type, :quantity_change, :stock_before, :stock_after, :notes)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->bindParam(':action_type', $action);
            $stmt->bindParam(':quantity_change', $quantity_change);
            $stmt->bindParam(':stock_before', $stock_before);
            $stmt->bindParam(':stock_after', $stock_after);
            $stmt->bindParam(':notes', $notes);
            $stmt->execute();
            
            $this->db->commit();
            
            return ['success' => true, 'stock_before' => $stock_before, 'stock_after' => $stock_after];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function getInventoryLogs($product_id = null, $limit = 100) {
        try {
            $where = $product_id ? "WHERE il.product_id = :product_id" : "";
            
            $query = "SELECT il.*, p.name as product_name, p.sku, u.username 
                     FROM inventory_logs il
                     LEFT JOIN products p ON il.product_id = p.id
                     LEFT JOIN users u ON il.user_id = u.id
                     $where
                     ORDER BY il.created_at DESC
                     LIMIT :limit";
            $stmt = $this->db->prepare($query);
            
            if ($product_id) {
                $stmt->bindParam(':product_id', $product_id);
            }
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function getStockSummary() {
        try {
            $stats = [];
            
            // Total products
            $query = "SELECT COUNT(*) as total FROM products WHERE status = 'publish'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['total_products'] = $stmt->fetch()['total'];
            
            // Low stock products
            $query = "SELECT COUNT(*) as total FROM products WHERE stock_quantity < 10 AND status = 'publish'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['low_stock'] = $stmt->fetch()['total'];
            
            // Out of stock
            $query = "SELECT COUNT(*) as total FROM products WHERE stock_quantity = 0 AND status = 'publish'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['out_of_stock'] = $stmt->fetch()['total'];
            
            // Total stock value
            $query = "SELECT COALESCE(SUM(price * stock_quantity), 0) as total FROM products WHERE status = 'publish'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['total_value'] = $stmt->fetch()['total'];
            
            return $stats;
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>
