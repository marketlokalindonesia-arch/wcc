<?php
// models/Category.php

class Category {
    private $conn;
    private $table_name = "product_categories";

    public $id;
    public $name;
    public $slug;
    public $description;
    public $parent_id;
    public $product_count;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($filters = []) {
        $query = "SELECT c.*, COUNT(p.id) as product_count 
                 FROM " . $this->table_name . " c
                 LEFT JOIN product_category_relationships pcr ON c.id = pcr.category_id
                 LEFT JOIN products p ON pcr.product_id = p.id AND p.status = 'publish'
                 WHERE 1=1";

        $params = [];

        if(isset($filters['parent_id'])) {
            $query .= " AND c.parent_id = :parent_id";
            $params[':parent_id'] = $filters['parent_id'];
        } else {
            $query .= " AND c.parent_id IS NULL";
        }

        $query .= " GROUP BY c.id ORDER BY c.name ASC";

        if(isset($filters['limit'])) {
            $query .= " LIMIT :limit";
        }

        $stmt = $this->conn->prepare($query);

        foreach($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        if(isset($filters['limit'])) {
            $stmt->bindValue(':limit', (int)$filters['limit'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt;
    }

    public function getHierarchy() {
        $query = "SELECT c.*, COUNT(p.id) as product_count,
                         (SELECT COUNT(*) FROM product_categories sc WHERE sc.parent_id = c.id) as child_count
                 FROM " . $this->table_name . " c
                 LEFT JOIN product_category_relationships pcr ON c.id = pcr.category_id
                 LEFT JOIN products p ON pcr.product_id = p.id AND p.status = 'publish'
                 GROUP BY c.id
                 ORDER BY c.parent_id IS NULL DESC, c.name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->buildTree($categories);
    }

    private function buildTree($categories, $parent_id = null) {
        $tree = [];
        foreach($categories as $category) {
            if($category['parent_id'] == $parent_id) {
                $children = $this->buildTree($categories, $category['id']);
                if($children) {
                    $category['children'] = $children;
                }
                $tree[] = $category;
            }
        }
        return $tree;
    }
}
?>