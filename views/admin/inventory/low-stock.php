<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../../controllers/InventoryController.php';

requireRole('admin');

$inventoryController = new InventoryController();
$lowStockProducts = $inventoryController->getLowStockProducts(50);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Alert - WC Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../partials/admin_sidebar.php'; ?>
    
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Low Stock Alert</h2>
            <div class="alert alert-warning mb-0">
                <i class="fas fa-info-circle me-2"></i>
                <strong><?php echo count($lowStockProducts); ?></strong> products need restocking
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Current Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($lowStockProducts)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="fas fa-check-circle fa-3x mb-3 text-success" style="opacity: 0.3;"></i>
                                        <p>All products have sufficient stock!</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($lowStockProducts as $product): ?>
                                <tr class="<?php echo $product['stock_quantity'] == 0 ? 'table-danger' : 'table-warning'; ?>">
                                    <td><strong><?php echo htmlspecialchars($product['name']); ?></strong></td>
                                    <td><code><?php echo htmlspecialchars($product['sku']); ?></code></td>
                                    <td>
                                        <strong><?php echo $product['stock_quantity']; ?></strong>
                                    </td>
                                    <td>
                                        <?php if($product['stock_quantity'] == 0): ?>
                                            <span class="badge bg-danger">Out of Stock</span>
                                        <?php elseif($product['stock_quantity'] <= 5): ?>
                                            <span class="badge bg-danger">Critical</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Low Stock</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-1"></i>Restock
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
