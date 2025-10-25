<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/session.php';

requireRole('admin');

$database = new Database();
$db = $database->getConnection();

// Stock stats
$query = "SELECT 
            SUM(stock_quantity) as total_stock,
            COUNT(*) as total_products,
            COUNT(CASE WHEN stock_quantity <= 10 THEN 1 END) as low_stock,
            COUNT(CASE WHEN stock_quantity = 0 THEN 1 END) as out_of_stock
          FROM products WHERE status = 'publish'";
$stmt = $db->prepare($query);
$stmt->execute();
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Products by stock status
$query = "SELECT name, sku, stock_quantity FROM products WHERE status = 'publish' ORDER BY stock_quantity ASC LIMIT 20";
$stmt = $db->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Analytics - WC Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../partials/admin_sidebar.php'; ?>
    
    <div class="main-content">
        <h2 class="mb-4"><i class="fas fa-cubes me-2"></i>Stock Analytics</h2>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded p-3 me-3">
                                <i class="fas fa-boxes fa-2x"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0">Total Stock</p>
                                <h3><?php echo number_format($stats['total_stock'] ?? 0); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-info text-white rounded p-3 me-3">
                                <i class="fas fa-box fa-2x"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0">Total Products</p>
                                <h3><?php echo $stats['total_products'] ?? 0; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning text-white rounded p-3 me-3">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0">Low Stock</p>
                                <h3><?php echo $stats['low_stock'] ?? 0; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger text-white rounded p-3 me-3">
                                <i class="fas fa-times-circle fa-2x"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0">Out of Stock</p>
                                <h3><?php echo $stats['out_of_stock'] ?? 0; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Stock Levels by Product</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Stock Quantity</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $product): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($product['name']); ?></strong></td>
                                <td><code><?php echo htmlspecialchars($product['sku']); ?></code></td>
                                <td><?php echo $product['stock_quantity']; ?></td>
                                <td>
                                    <?php if($product['stock_quantity'] == 0): ?>
                                        <span class="badge bg-danger">Out of Stock</span>
                                    <?php elseif($product['stock_quantity'] <= 10): ?>
                                        <span class="badge bg-warning">Low Stock</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">In Stock</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
