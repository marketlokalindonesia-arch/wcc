<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../controllers/InventoryController.php';

requireRole('admin');

$inventoryController = new InventoryController();
$database = new Database();
$db = $database->getConnection();

$stats = $inventoryController->getStockSummary();
$lowStockProducts = $inventoryController->getLowStockProducts(10);

$query = "SELECT p.*, 
          (SELECT image_url FROM product_images WHERE product_id = p.id AND is_featured = true LIMIT 1) as image
          FROM products p 
          WHERE p.stock_quantity = 0 AND p.status = 'publish'
          ORDER BY p.name ASC
          LIMIT 10";
$stmt = $db->prepare($query);
$stmt->execute();
$outOfStockProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$recentLogs = $inventoryController->getInventoryLogs(null, 20);

$user = getUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - WC Clone</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background: #f8f9fa; }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        .stat-card .value {
            font-size: 32px;
            font-weight: 700;
            margin: 10px 0 5px;
        }
        .stat-card .label {
            color: #6c757d;
            font-size: 14px;
        }
        .card-custom {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: none;
        }
        .product-image {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 8px;
        }
        .quick-action-btn {
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s;
            border: 2px solid #e9ecef;
        }
        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-warehouse me-2"></i>Inventory Management</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adjustStockModal">
                <i class="fas fa-plus-minus me-2"></i>Adjust Stock
            </button>
        </div>

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="value"><?php echo number_format($stats['total_products'] ?? 0); ?></div>
                    <div class="label">Total Products</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="value"><?php echo number_format($stats['low_stock'] ?? 0); ?></div>
                    <div class="label">Low Stock Items</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="value"><?php echo number_format($stats['out_of_stock'] ?? 0); ?></div>
                    <div class="label">Out of Stock</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="value">$<?php echo number_format($stats['stock_value'] ?? 0, 0); ?></div>
                    <div class="label">Stock Value</div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card card-custom p-3">
                    <h5 class="mb-3"><i class="fas fa-th me-2"></i>Quick Actions</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="/?url=admin/inventory/low-stock" class="quick-action-btn d-block text-decoration-none">
                                <i class="fas fa-exclamation-triangle fa-2x mb-2 text-warning"></i>
                                <h6>Low Stock Alert</h6>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="/?url=admin/inventory/logs" class="quick-action-btn d-block text-decoration-none">
                                <i class="fas fa-history fa-2x mb-2 text-info"></i>
                                <h6>Inventory Logs</h6>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="/?url=admin/products" class="quick-action-btn d-block text-decoration-none">
                                <i class="fas fa-box fa-2x mb-2 text-primary"></i>
                                <h6>All Products</h6>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card card-custom">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Low Stock Products</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($lowStockProducts) > 0): ?>
                                        <?php foreach ($lowStockProducts as $product): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if ($product['image']): ?>
                                                            <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                                                 alt="" class="product-image me-2">
                                                        <?php endif; ?>
                                                        <span><?php echo htmlspecialchars($product['name']); ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning">
                                                        <?php echo number_format($product['stock_quantity']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" title="Adjust Stock">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No low stock products</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card card-custom">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-times-circle text-danger me-2"></i>Out of Stock</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($outOfStockProducts) > 0): ?>
                                        <?php foreach ($outOfStockProducts as $product): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if ($product['image']): ?>
                                                            <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                                                 alt="" class="product-image me-2">
                                                        <?php endif; ?>
                                                        <span><?php echo htmlspecialchars($product['name']); ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" title="Restock">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No out of stock products</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-history me-2"></i>Recent Inventory Changes</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Action</th>
                                        <th>Change</th>
                                        <th>User</th>
                                        <th>Notes</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($recentLogs) > 0): ?>
                                        <?php foreach ($recentLogs as $log): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($log['product_name'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        <?php echo ucfirst(htmlspecialchars($log['action_type'])); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="<?php echo $log['quantity_change'] > 0 ? 'text-success' : 'text-danger'; ?>">
                                                        <?php echo $log['quantity_change'] > 0 ? '+' : ''; ?>
                                                        <?php echo number_format($log['quantity_change']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($log['username'] ?? 'System'); ?></td>
                                                <td><?php echo htmlspecialchars($log['notes'] ?? ''); ?></td>
                                                <td><?php echo safeFormatDate($log['created_at'], 'M d, Y H:i'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No inventory logs found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
