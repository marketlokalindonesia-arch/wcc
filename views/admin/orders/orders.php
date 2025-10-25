<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('admin');

$database = new Database();
$db = $database->getConnection();

$query = "SELECT o.*, u.first_name, u.last_name, u.email 
          FROM orders o 
          LEFT JOIN users u ON o.customer_id = u.id 
          ORDER BY o.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$user = getUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management - WC Clone</title>
    
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
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../partials/admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-shopping-bag me-2"></i>Order Management</h2>
        </div>

        <div class="card card-custom p-4">
            <div class="table-responsive">
                <table id="ordersTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($orders)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-shopping-bag fa-3x mb-3" style="opacity: 0.3;"></i>
                                    <p>No orders found</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($orders as $order): ?>
                            <tr>
                                <td><strong>#<?php echo htmlspecialchars($order['id']); ?></strong></td>
                                <td>
                                    <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($order['email']); ?></small>
                                </td>
                                <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                <td>
                                    <?php
                                    $method_icons = [
                                        'cash' => 'fa-money-bill-wave',
                                        'card' => 'fa-credit-card',
                                        'e-wallet' => 'fa-wallet',
                                        'bank_transfer' => 'fa-university'
                                    ];
                                    $icon = $method_icons[$order['payment_method']] ?? 'fa-question';
                                    ?>
                                    <i class="fas <?php echo $icon; ?> me-1"></i>
                                    <?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?>
                                </td>
                                <td>
                                    <?php if($order['payment_status'] === 'paid'): ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php elseif($order['payment_status'] === 'pending'): ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Failed</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($order['status'] === 'pending'): ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php elseif($order['status'] === 'processing'): ?>
                                        <span class="badge bg-info">Processing</span>
                                    <?php elseif($order['status'] === 'completed'): ?>
                                        <span class="badge bg-success">Completed</span>
                                    <?php elseif($order['status'] === 'cancelled'): ?>
                                        <span class="badge bg-danger">Cancelled</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo ucfirst($order['status']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" onclick="viewOrder(<?php echo $order['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-info" onclick="editOrder(<?php echo $order['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if($order['status'] !== 'cancelled'): ?>
                                        <button class="btn btn-outline-danger" onclick="cancelOrder(<?php echo $order['id']; ?>)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable({
                order: [[0, 'desc']],
                pageLength: 25
            });
        });

        function viewOrder(orderId) {
            window.location.href = `/?url=orders/detail&id=${orderId}`;
        }

        function editOrder(orderId) {
            window.location.href = `/?url=admin/edit-order&id=${orderId}`;
        }

        function cancelOrder(orderId) {
            if(confirm('Are you sure you want to cancel this order?')) {
                window.location.href = `/?url=admin/cancel-order&id=${orderId}`;
            }
        }
    </script>
</body>
</html>
