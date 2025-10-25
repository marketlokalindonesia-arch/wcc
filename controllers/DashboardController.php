<?php
require_once 'config/database.php';
require_once 'config/session.php';

class DashboardController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function getAdminStats() {
        try {
            $stats = [];
            
            // Total products
            $query = "SELECT COUNT(*) as total FROM products";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['total_products'] = $stmt->fetch()['total'];
            
            // Total orders
            $query = "SELECT COUNT(*) as total FROM orders";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['total_orders'] = $stmt->fetch()['total'];
            
            // Total revenue
            $query = "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE payment_status = 'paid'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['total_revenue'] = $stmt->fetch()['total'];
            
            // Total customers
            $query = "SELECT COUNT(*) as total FROM users WHERE role = 'customer'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['total_customers'] = $stmt->fetch()['total'];
            
            // Today's sales
            $query = "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders 
                     WHERE DATE(created_at) = CURRENT_DATE AND payment_status = 'paid'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['today_sales'] = $stmt->fetch()['total'];
            
            // Pending orders
            $query = "SELECT COUNT(*) as total FROM orders WHERE status = 'pending'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['pending_orders'] = $stmt->fetch()['total'];
            
            // Low stock products
            $query = "SELECT COUNT(*) as total FROM products WHERE stock_quantity < 10 AND status = 'publish'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['low_stock'] = $stmt->fetch()['total'];
            
            return $stats;
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    public function getRecentOrders($limit = 10) {
        try {
            $query = "SELECT o.*, u.first_name, u.last_name, u.email 
                     FROM orders o 
                     LEFT JOIN users u ON o.customer_id = u.id 
                     ORDER BY o.created_at DESC LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function getTopProducts($limit = 5) {
        try {
            $query = "SELECT p.*, COALESCE(SUM(oi.quantity), 0) as total_sold 
                     FROM products p 
                     LEFT JOIN order_items oi ON p.id = oi.product_id 
                     GROUP BY p.id 
                     ORDER BY total_sold DESC LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function getSalesData($days = 7) {
        try {
            $query = "SELECT DATE(created_at) as date, COALESCE(SUM(total_amount), 0) as total 
                     FROM orders 
                     WHERE created_at >= CURRENT_DATE - INTERVAL '$days days' 
                     AND payment_status = 'paid'
                     GROUP BY DATE(created_at) 
                     ORDER BY date ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>
