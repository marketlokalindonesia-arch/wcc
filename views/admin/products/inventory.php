<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../controllers/InventoryController.php';

requireRole('admin');

$inventoryController = new InventoryController();
$stockSummary = $inventoryController->getStockSummary();
$lowStockProducts = $inventoryController->getLowStockProducts(10);
$inventoryLogs = $inventoryController->getInventoryLogs(null, 50);

$database = new Database();
$db = $database->getConnection();

$query = "SELECT id, name, sku, stock_quantity FROM products WHERE status = 'publish' ORDER BY name";
$stmt = $db->prepare($query);
$stmt->execute();
$allProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$user = getUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management - WC Clone</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        body { background: #f8f9fa; }
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
        .card-custom {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: none;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
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
        .product-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../partials/admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-warehouse me-2"></i>Inventory Management</h2>
        </div>

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="label"><i class="fas fa-boxes me-1"></i>Total Products</div>
                    <div class="value text-primary"><?php echo number_format($stockSummary['total_products']); ?></div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="label"><i class="fas fa-exclamation-triangle me-1"></i>Low Stock</div>
                    <div class="value text-warning"><?php echo number_format($stockSummary['low_stock']); ?></div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="label"><i class="fas fa-times-circle me-1"></i>Out of Stock</div>
                    <div class="value text-danger"><?php echo number_format($stockSummary['out_of_stock']); ?></div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="label"><i class="fas fa-dollar-sign me-1"></i>Total Value</div>
                    <div class="value text-success">$<?php echo number_format($stockSummary['total_value'], 2); ?></div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card card-custom p-4">
                    <h5 class="mb-4"><i class="fas fa-exclamation-circle text-warning me-2"></i>Low Stock Alerts</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($lowStockProducts)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            <i class="fas fa-check-circle text-success me-2"></i>All products have sufficient stock
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($lowStockProducts as $product): ?>
                                    <tr>
                                        <td>
                                            <img src="<?php echo htmlspecialchars($product['image'] ?? 'https://via.placeholder.com/40'); ?>" class="product-img" alt="Product">
                                        </td>
                                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td><?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $product['stock_quantity'] == 0 ? 'danger' : 'warning'; ?>">
                                                <?php echo $product['stock_quantity']; ?> units
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-custom p-4">
                    <h5 class="mb-4"><i class="fas fa-exchange-alt me-2"></i>Stock Adjustment</h5>
                    <form action="/?url=admin/process-stock-adjustment" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Product</label>
                            <select name="product_id" class="form-select" required>
                                <option value="">Select Product</option>
                                <?php foreach($allProducts as $product): ?>
                                <option value="<?php echo $product['id']; ?>">
                                    <?php echo htmlspecialchars($product['name']); ?> 
                                    (Stock: <?php echo $product['stock_quantity']; ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Action</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="action" id="actionAdd" value="add" required>
                                <label class="btn btn-outline-success" for="actionAdd">
                                    <i class="fas fa-plus me-1"></i>Add Stock
                                </label>
                                
                                <input type="radio" class="btn-check" name="action" id="actionRemove" value="remove" required>
                                <label class="btn btn-outline-danger" for="actionRemove">
                                    <i class="fas fa-minus me-1"></i>Remove Stock
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Adjust Stock
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-custom p-4">
                    <h5 class="mb-4"><i class="fas fa-history me-2"></i>Inventory Logs</h5>
                    <div class="table-responsive">
                        <table id="inventoryLogsTable" class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Action</th>
                                    <th>Change</th>
                                    <th>Before</th>
                                    <th>After</th>
                                    <th>User</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($inventoryLogs)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-3">No inventory logs found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($inventoryLogs as $log): ?>
                                    <tr>
                                        <td><?php echo date('M d, Y H:i', strtotime($log['created_at'])); ?></td>
                                        <td><?php echo htmlspecialchars($log['product_name']); ?></td>
                                        <td><?php echo htmlspecialchars($log['sku'] ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if($log['action_type'] === 'add'): ?>
                                                <span class="badge bg-success">Add</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Remove</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong class="text-<?php echo $log['quantity_change'] > 0 ? 'success' : 'danger'; ?>">
                                                <?php echo $log['quantity_change'] > 0 ? '+' : ''; ?><?php echo $log['quantity_change']; ?>
                                            </strong>
                                        </td>
                                        <td><?php echo $log['stock_before']; ?></td>
                                        <td><?php echo $log['stock_after']; ?></td>
                                        <td><?php echo htmlspecialchars($log['username']); ?></td>
                                        <td><?php echo htmlspecialchars($log['notes'] ?? '-'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#inventoryLogsTable').DataTable({
                order: [[0, 'desc']],
                pageLength: 25
            });
        });
    </script>
</body>
</html>
