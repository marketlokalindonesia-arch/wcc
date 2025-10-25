<?php
require_once '../config/database.php';
require_once '../models/Category.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

$database = new Database();
$db = $database->getConnection();
$category = new Category($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $filters = [];
    
    if (isset($_GET['parent_id'])) {
        $filters['parent_id'] = $_GET['parent_id'];
    }
    
    $stmt = $category->read($filters);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $categories
    ]);
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
}
?>
