<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/session.php';

requireRole('admin');

$database = new Database();
$db = $database->getConnection();

$query = "SELECT o.*, u.first_name, u.last_name FROM orders o 
          LEFT JOIN users u ON o.customer_id = u.id 
          WHERE o.status = 'pending' ORDER BY o.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Orders - WC Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../partials/admin_sidebar.php'; ?>
    
    <div class="main-content">
        <h2 class="mb-4"><i class="fas fa-clock me-2 text-warning"></i>Pending Orders</h2>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($orders)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="fas fa-inbox fa-3x mb-3" style="opacity: 0.3;"></i>
                                        <p>No pending orders</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($orders as $order): ?>
                                <tr>
                                    <td><strong>#<?php echo $order['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                                    <td>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                                    <td><span class="badge bg-warning"><?php echo ucfirst($order['payment_method']); ?></span></td>
                                    <td><?php echo safeFormatDate($order['created_at'], 'd M Y H:i'); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-success"><i class="fas fa-check"></i> Process</button>
                                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
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
