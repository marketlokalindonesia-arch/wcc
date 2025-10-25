<?php
// api/products.php

require_once '../config/database.php';
require_once '../models/Product.php';
require_once '../utils/FileUploader.php';
require_once '../controllers/ProductController.php';

header('Content-Type: application/json');

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$database = new Database();
$db = $database->getConnection();

$controller = new ProductController($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $filters = [];
        
        if(isset($_GET['category_id'])) {
            $filters['category_id'] = $_GET['category_id'];
        }
        
        if(isset($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        
        if(isset($_GET['featured'])) {
            $filters['featured'] = $_GET['featured'];
        }
        
        $products = $controller->getProducts($filters);
        echo json_encode(['success' => true, 'data' => $products]);
        break;

    case 'POST':
        $data = $_POST;
        $files = $_FILES;
        
        $result = $controller->createProduct($data, $files);
        echo json_encode($result);
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>