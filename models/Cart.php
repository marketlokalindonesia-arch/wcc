<?php
// models/Cart.php
class Cart {
    private $conn;
    private $table_name = "cart";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method untuk demo - akan menggunakan session
    public function getItemCount($user_id) {
        // Untuk demo, kita return nilai default
        return 0;
    }

    public function addItem($user_id, $product_id, $quantity = 1) {
        // Implementation untuk database
        $query = "INSERT INTO " . $this->table_name . " 
                 (user_id, product_id, quantity) 
                 VALUES (:user_id, :product_id, :quantity)
                 ON DUPLICATE KEY UPDATE quantity = quantity + :quantity";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->bindParam(":quantity", $quantity);
        
        return $stmt->execute();
    }

    public function updateQuantity($user_id, $product_id, $quantity) {
        if($quantity <= 0) {
            return $this->removeItem($user_id, $product_id);
        }

        $query = "UPDATE " . $this->table_name . " 
                 SET quantity = :quantity 
                 WHERE user_id = :user_id AND product_id = :product_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":product_id", $product_id);
        
        return $stmt->execute();
    }

    public function removeItem($user_id, $product_id) {
        $query = "DELETE FROM " . $this->table_name . " 
                 WHERE user_id = :user_id AND product_id = :product_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":product_id", $product_id);
        
        return $stmt->execute();
    }

    public function getCartItems($user_id) {
        $query = "SELECT c.*, p.name, p.price, p.sale_price, p.stock_quantity, 
                         pi.image_url as product_image
                 FROM " . $this->table_name . " c
                 INNER JOIN products p ON c.product_id = p.id
                 LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_featured = 1
                 WHERE c.user_id = :user_id
                 ORDER BY c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCartTotal($user_id) {
        $items = $this->getCartItems($user_id);
        $total = 0;

        foreach($items as $item) {
            $price = $item['sale_price'] ? $item['sale_price'] : $item['price'];
            $total += $price * $item['quantity'];
        }

        return $total;
    }

    public function clearCart($user_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        
        return $stmt->execute();
    }
}
?>