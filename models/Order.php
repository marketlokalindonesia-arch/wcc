<?php
// models/Order.php

class Order {
    private $conn;
    private $table_name = "orders";

    public $id;
    public $order_number;
    public $customer_id;
    public $status;
    public $total_amount;
    public $payment_method;
    public $payment_status;
    public $billing_address;
    public $shipping_address;
    public $customer_note;
    public $items = [];

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new order
    public function create() {
        // Generate unique order number
        $this->order_number = $this->generateOrderNumber();

        $query = "INSERT INTO " . $this->table_name . "
                SET order_number=:order_number, customer_id=:customer_id, 
                status=:status, total_amount=:total_amount, payment_method=:payment_method,
                payment_status=:payment_status, billing_address=:billing_address,
                shipping_address=:shipping_address, customer_note=:customer_note";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(":order_number", $this->order_number);
        $stmt->bindParam(":customer_id", $this->customer_id);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":total_amount", $this->total_amount);
        $stmt->bindParam(":payment_method", $this->payment_method);
        $stmt->bindParam(":payment_status", $this->payment_status);
        $stmt->bindParam(":billing_address", $this->billing_address);
        $stmt->bindParam(":shipping_address", $this->shipping_address);
        $stmt->bindParam(":customer_note", $this->customer_note);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            
            // Add order items
            if(!empty($this->items)) {
                $this->addOrderItems($this->items);
            }
            
            return true;
        }
        return false;
    }

    // Add order items
    private function addOrderItems($items) {
        $query = "INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal) 
                 VALUES ";
        
        $values = [];
        $params = [];

        foreach($items as $index => $item) {
            $values[] = "(:order_id, :product_id_{$index}, :product_name_{$index}, 
                         :product_price_{$index}, :quantity_{$index}, :subtotal_{$index})";
            
            $params[":product_id_{$index}"] = $item['product_id'];
            $params[":product_name_{$index}"] = $item['product_name'];
            $params[":product_price_{$index}"] = $item['product_price'];
            $params[":quantity_{$index}"] = $item['quantity'];
            $params[":subtotal_{$index}"] = $item['subtotal'];
        }

        $query .= implode(", ", $values);
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $this->id);

        foreach($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        return $stmt->execute();
    }

    // Generate unique order number
    private function generateOrderNumber() {
        $prefix = "ORD";
        $timestamp = time();
        $random = mt_rand(1000, 9999);
        return $prefix . $timestamp . $random;
    }

    // Get order by ID
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->order_number = $row['order_number'];
            $this->customer_id = $row['customer_id'];
            $this->status = $row['status'];
            $this->total_amount = $row['total_amount'];
            $this->payment_method = $row['payment_method'];
            $this->payment_status = $row['payment_status'];
            $this->billing_address = $row['billing_address'];
            $this->shipping_address = $row['shipping_address'];
            $this->customer_note = $row['customer_note'];
            
            // Get order items
            $this->items = $this->getOrderItems();
            
            return true;
        }

        return false;
    }

    // Get order items
    public function getOrderItems() {
        $query = "SELECT oi.*, p.sku 
                 FROM order_items oi
                 LEFT JOIN products p ON oi.product_id = p.id
                 WHERE oi.order_id = :order_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $this->id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update order status
    public function updateStatus($new_status) {
        $query = "UPDATE " . $this->table_name . " 
                 SET status = :status 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $new_status);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    // Get customer orders
    public function getCustomerOrders($customer_id, $limit = null, $offset = null) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE customer_id = :customer_id 
                 ORDER BY created_at DESC";

        if($limit) {
            $query .= " LIMIT :limit";
            if($offset) {
                $query .= " OFFSET :offset";
            }
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":customer_id", $customer_id);

        if($limit) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            if($offset) {
                $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>