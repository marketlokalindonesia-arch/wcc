<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('admin');

$database = new Database();
$db = $database->getConnection();

$status_filter = $_GET['status'] ?? 'all';

$where = '';
if ($status_filter !== 'all') {
    $where = "WHERE o.status = :status";
}

$query = "SELECT o.*, u.username as customer_name, u.email as customer_email,
          (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
          FROM orders o 
          LEFT JOIN users u ON o.customer_id = u.id 
          $where
          ORDER BY o.created_at DESC";
$stmt = $db->prepare($query);
if ($status_filter !== 'all') {
    $stmt->bindParam(':status', $status_filter);
}
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT COUNT(*) as total FROM orders";
$stmt = $db->prepare($query);
$stmt->execute();
$totalOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$query = "SELECT COUNT(*) as total FROM orders WHERE status = 'pending'";
$stmt = $db->prepare($query);
$stmt->execute();
$pendingOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$query = "SELECT COUNT(*) as total FROM orders WHERE status = 'completed'";
$stmt = $db->prepare($query);
$stmt->execute();
$completedOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$query = "SELECT COALESCE(SUM(total_amount), 0) as revenue FROM orders WHERE status = 'completed'";
$stmt = $db->prepare($query);
$stmt->execute();
$totalRevenue = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'];

$user = getUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - WC Clone</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        body { background: #f8f9fa; }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-card .value {
            font-size: 28px;
            font-weight: 700;
            margin: 5px 0;
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
        .filter-btn {
            border-radius: 20px;
            padding: 8px 20px;
        }
        .filter-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-shopping-cart me-2"></i>Orders</h2>
            <div>
                <button class="btn btn-outline-primary btn-sm me-2">
                    <i class="fas fa-download me-1"></i>Export
                </button>
                <button class="btn btn-primary btn-sm">
                    <i class="fas fa-filter me-1"></i>Filters
                </button>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="value text-primary"><?php echo number_format($totalOrders); ?></div>
                    <div class="label"><i class="fas fa-shopping-cart me-1"></i>Total Orders</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="value text-warning"><?php echo number_format($pendingOrders); ?></div>
                    <div class="label"><i class="fas fa-clock me-1"></i>Pending Orders</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="value text-success"><?php echo number_format($completedOrders); ?></div>
                    <div class="label"><i class="fas fa-check-circle me-1"></i>Completed</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="value text-info">$<?php echo number_format($totalRevenue, 2); ?></div>
                    <div class="label"><i class="fas fa-dollar-sign me-1"></i>Total Revenue</div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card card-custom p-3">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="/?url=admin/orders" class="btn btn-sm filter-btn <?php echo $status_filter == 'all' ? 'active' : 'btn-outline-primary'; ?>">
                            All Orders
                        </a>
                        <a href="/?url=admin/orders&status=pending" class="btn btn-sm filter-btn <?php echo $status_filter == 'pending' ? 'active' : 'btn-outline-primary'; ?>">
                            Pending
                        </a>
                        <a href="/?url=admin/orders&status=processing" class="btn btn-sm filter-btn <?php echo $status_filter == 'processing' ? 'active' : 'btn-outline-primary'; ?>">
                            Processing
                        </a>
                        <a href="/?url=admin/orders&status=completed" class="btn btn-sm filter-btn <?php echo $status_filter == 'completed' ? 'active' : 'btn-outline-primary'; ?>">
                            Completed
                        </a>
                        <a href="/?url=admin/orders&status=cancelled" class="btn btn-sm filter-btn <?php echo $status_filter == 'cancelled' ? 'active' : 'btn-outline-primary'; ?>">
                            Cancelled
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-list me-2"></i>Orders List</h5>
                        <div class="table-responsive">
                            <table id="ordersTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Items</th>
                                        <th>Amount</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                                            <td>
                                                <?php echo htmlspecialchars($order['customer_name'] ?? 'Guest'); ?><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($order['customer_email'] ?? ''); ?></small>
                                            </td>
                                            <td><?php echo number_format($order['item_count']); ?> items</td>
                                            <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo ucfirst(htmlspecialchars($order['payment_method'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'processing' => 'info',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                                $badgeColor = $statusColors[$order['status']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $badgeColor; ?>">
                                                    <?php echo ucfirst(htmlspecialchars($order['status'])); ?>
                                                </span>
                                            </td>
                                            <td><?php echo safeFormatDate($order['created_at'], 'M d, Y'); ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success" title="Print">
                                                        <i class="fas fa-print"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
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
            $('#ordersTable').DataTable({
                order: [[6, 'desc']],
                pageLength: 25
            });
        });
    </script>
</body>
</html>
