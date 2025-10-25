<?php
// models/Product.php

class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $slug;
    public $description;
    public $short_description;
    public $price;
    public $sale_price;
    public $stock_quantity;
    public $sku;
    public $featured;
    public $status;
    public $vendor_id;
    public $categories = [];
    public $images = [];

    public function __construct($db) {
        $this->conn = $db;
    }

// models/Product.php
public function getCategoryIcon($categoryName) {
    $icons = [
        'Electronics' => 'laptop',
        'Fashion' => 'tshirt',
        'Home' => 'home',
        'Sports' => 'basketball-ball',
        'Beauty' => 'spa',
        'Toys' => 'gamepad',
        'Books' => 'book',
        'Food' => 'utensils'
    ];
    
    return $icons[$categoryName] ?? 'shopping-bag';
}

public function getCategoryName($productId) {
    // Simple implementation - you might want to join with categories table
    $categories = ['Electronics', 'Fashion', 'Home', 'Sports', 'Beauty'];
    return $categories[array_rand($categories)];
}
    // Create product
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET name=:name, slug=:slug, description=:description, 
                short_description=:short_description, price=:price, sale_price=:sale_price,
                stock_quantity=:stock_quantity, sku=:sku, featured=:featured, 
                status=:status, vendor_id=:vendor_id";

        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->slug = htmlspecialchars(strip_tags($this->slug));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->short_description = htmlspecialchars(strip_tags($this->short_description));

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":slug", $this->slug);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":short_description", $this->short_description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":sale_price", $this->sale_price);
        $stmt->bindParam(":stock_quantity", $this->stock_quantity);
        $stmt->bindParam(":sku", $this->sku);
        $stmt->bindParam(":featured", $this->featured);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":vendor_id", $this->vendor_id);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            
            // Add categories if any
            if(!empty($this->categories)) {
                $this->addCategories($this->categories);
            }
            
            return true;
        }
        return false;
    }

    // Read products with pagination and filters
    public function read($filters = []) {
        $query = "SELECT p.*, u.username as vendor_name 
                FROM " . $this->table_name . " p
                LEFT JOIN users u ON p.vendor_id = u.id
                WHERE 1=1";

        $params = [];

        // Apply filters
        if(isset($filters['category_id'])) {
            $query .= " AND p.id IN (SELECT product_id FROM product_category_relationships WHERE category_id = :category_id)";
            $params[':category_id'] = $filters['category_id'];
        }

        if(isset($filters['status'])) {
            $query .= " AND p.status = :status";
            $params[':status'] = $filters['status'];
        }

        if(isset($filters['featured'])) {
            $query .= " AND p.featured = :featured";
            $params[':featured'] = $filters['featured'];
        }

        if(isset($filters['search'])) {
            $query .= " AND (p.name LIKE :search OR p.description LIKE :search OR p.sku LIKE :search OR p.barcode LIKE :search)";
            $params[':search'] = "%" . $filters['search'] . "%";
        }

        $query .= " ORDER BY p.created_at DESC";

        // Add pagination
        if(isset($filters['limit'])) {
            $query .= " LIMIT :limit";
            if(isset($filters['offset'])) {
                $query .= " OFFSET :offset";
            }
        }

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        foreach($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        if(isset($filters['limit'])) {
            $stmt->bindValue(':limit', (int)$filters['limit'], PDO::PARAM_INT);
            if(isset($filters['offset'])) {
                $stmt->bindValue(':offset', (int)$filters['offset'], PDO::PARAM_INT);
            }
        }

        $stmt->execute();
        return $stmt;
    }

    // Update product
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET name=:name, slug=:slug, description=:description,
                short_description=:short_description, price=:price, sale_price=:sale_price,
                stock_quantity=:stock_quantity, sku=:sku, featured=:featured,
                status=:status, vendor_id=:vendor_id
                WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->slug = htmlspecialchars(strip_tags($this->slug));

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":slug", $this->slug);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":short_description", $this->short_description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":sale_price", $this->sale_price);
        $stmt->bindParam(":stock_quantity", $this->stock_quantity);
        $stmt->bindParam(":sku", $this->sku);
        $stmt->bindParam(":featured", $this->featured);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":vendor_id", $this->vendor_id);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            // Update categories
            if(!empty($this->categories)) {
                $this->updateCategories($this->categories);
            }
            return true;
        }
        return false;
    }

    // Delete product
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Add categories to product
    private function addCategories($categories) {
        $query = "INSERT INTO product_category_relationships (product_id, category_id) VALUES ";
        $values = [];
        $params = [];

        foreach($categories as $index => $category_id) {
            $values[] = "(:product_id, :category_id_" . $index . ")";
            $params[":category_id_" . $index] = $category_id;
        }

        $query .= implode(", ", $values);
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $this->id);

        foreach($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        return $stmt->execute();
    }

    // Update categories
    private function updateCategories($categories) {
        // First remove existing categories
        $deleteQuery = "DELETE FROM product_category_relationships WHERE product_id = :product_id";
        $deleteStmt = $this->conn->prepare($deleteQuery);
        $deleteStmt->bindParam(":product_id", $this->id);
        $deleteStmt->execute();

        // Then add new categories
        return $this->addCategories($categories);
    }

    // Get product categories
    public function getCategories() {
        $query = "SELECT c.* FROM product_categories c
                 INNER JOIN product_category_relationships pcr ON c.id = pcr.category_id
                 WHERE pcr.product_id = :product_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $this->id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add product image
    public function addImage($image_url, $alt_text = '', $is_featured = false) {
        $query = "INSERT INTO product_images (product_id, image_url, alt_text, is_featured) 
                 VALUES (:product_id, :image_url, :alt_text, :is_featured)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $this->id);
        $stmt->bindParam(":image_url", $image_url);
        $stmt->bindParam(":alt_text", $alt_text);
        $stmt->bindParam(":is_featured", $is_featured);
        
        return $stmt->execute();
    }

    // Get product images
    public function getImages() {
        $query = "SELECT * FROM product_images WHERE product_id = :product_id ORDER BY is_featured DESC, sort_order ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $this->id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>