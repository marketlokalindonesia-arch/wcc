<?php
// controllers/ProductController.php

class ProductController {
    private $product;
    private $uploader;

    public function __construct($db) {
        $this->product = new Product($db);
        $this->uploader = new FileUploader();
    }

    public function createProduct($data, $files) {
        // Set product data
        $this->product->name = $data['name'];
        $this->product->slug = $this->generateSlug($data['name']);
        $this->product->description = $data['description'];
        $this->product->price = $data['price'];
        $this->product->stock_quantity = $data['stock_quantity'];
        $this->product->status = $data['status'];
        $this->product->vendor_id = $data['vendor_id'];

        if(isset($data['categories'])) {
            $this->product->categories = $data['categories'];
        }

        // Create product
        if($this->product->create()) {
            // Handle image uploads
            if(!empty($files['images'])) {
                $this->handleImageUploads($files['images'], $this->product->id);
            }

            return [
                'success' => true,
                'product_id' => $this->product->id,
                'message' => 'Product created successfully'
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to create product'
        ];
    }

    private function handleImageUploads($images, $product_id) {
        $featured_set = false;

        foreach($images['tmp_name'] as $index => $tmp_name) {
            if($images['error'][$index] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $images['name'][$index],
                    'type' => $images['type'][$index],
                    'tmp_name' => $tmp_name,
                    'error' => $images['error'][$index],
                    'size' => $images['size'][$index]
                ];

                $upload_result = $this->uploader->uploadProductImage($file, $product_id);
                
                if($upload_result) {
                    $is_featured = (!$featured_set && $index === 0);
                    $this->product->addImage(
                        $upload_result['url'],
                        pathinfo($file['name'], PATHINFO_FILENAME),
                        $is_featured
                    );
                    
                    if($is_featured) {
                        $featured_set = true;
                    }
                }
            }
        }
    }

    private function generateSlug($title) {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return $slug;
    }

    public function getProducts($filters = []) {
        $stmt = $this->product->read($filters);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get images for each product
        foreach($products as &$product) {
            $this->product->id = $product['id'];
            $product['images'] = $this->product->getImages();
            $product['categories'] = $this->product->getCategories();
        }

        return $products;
    }
}
?>