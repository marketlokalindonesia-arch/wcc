<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../../controllers/InventoryController.php';

requireRole('admin');

$inventoryController = new InventoryController();
$logs = $inventoryController->getInventoryLogs(null, 100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Logs - WC Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../partials/admin_sidebar.php'; ?>
    
    <div class="main-content">
        <h2 class="mb-4"><i class="fas fa-history me-2"></i>Inventory Activity Logs</h2>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Action</th>
                                <th>Quantity Change</th>
                                <th>Stock Before</th>
                                <th>Stock After</th>
                                <th>User</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($logs)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="fas fa-history fa-3x mb-3" style="opacity: 0.3;"></i>
                                        <p>No inventory logs found</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($logs as $log): ?>
                                <tr>
                                    <td><?php echo date('d M Y H:i', strtotime($log['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($log['product_name'] ?? 'N/A'); ?></td>
                                    <td>
                                        <?php
                                        $action_badges = [
                                            'add' => 'success',
                                            'remove' => 'danger',
                                            'adjust' => 'warning',
                                            'sale' => 'info'
                                        ];
                                        $badge = $action_badges[$log['action_type']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $badge; ?>"><?php echo ucfirst($log['action_type']); ?></span>
                                    </td>
                                    <td>
                                        <?php 
                                        $change = $log['quantity_change'];
                                        $color = $change > 0 ? 'success' : 'danger';
                                        ?>
                                        <strong class="text-<?php echo $color; ?>"><?php echo ($change > 0 ? '+' : '') . $change; ?></strong>
                                    </td>
                                    <td><?php echo $log['stock_before']; ?></td>
                                    <td><?php echo $log['stock_after']; ?></td>
                                    <td><?php echo htmlspecialchars($log['user_name'] ?? 'System'); ?></td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
