<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../controllers/POSController.php';

requireRole('admin');

$posController = new POSController();
$database = new Database();
$db = $database->getConnection();

$query = "SELECT COUNT(*) as total FROM orders WHERE DATE(created_at) = CURRENT_DATE";
$stmt = $db->prepare($query);
$stmt->execute();
$todaySales = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$query = "SELECT COALESCE(SUM(total_amount), 0) as revenue FROM orders WHERE DATE(created_at) = CURRENT_DATE";
$stmt = $db->prepare($query);
$stmt->execute();
$todayRevenue = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'];

$query = "SELECT COUNT(DISTINCT created_by) as total FROM orders WHERE DATE(created_at) = CURRENT_DATE";
$stmt = $db->prepare($query);
$stmt->execute();
$activeCashiers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$query = "SELECT o.*, u.username as cashier_name FROM orders o 
          LEFT JOIN users u ON o.created_by = u.id 
          WHERE DATE(o.created_at) = CURRENT_DATE 
          ORDER BY o.created_at DESC LIMIT 10";
$stmt = $db->prepare($query);
$stmt->execute();
$recentTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$user = getUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Dashboard - WC Clone</title>
    
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
            <h2><i class="fas fa-cash-register me-2"></i>POS Dashboard</h2>
            <a href="/?url=cashier/pos" class="btn btn-primary">
                <i class="fas fa-shopping-cart me-2"></i>Open POS Terminal
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="value">$<?php echo number_format($todayRevenue, 2); ?></div>
                    <div class="label">Today's Revenue</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="value"><?php echo number_format($todaySales); ?></div>
                    <div class="label">Today's Transactions</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="value"><?php echo number_format($activeCashiers); ?></div>
                    <div class="label">Active Cashiers</div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card card-custom p-3">
                    <h5 class="mb-3"><i class="fas fa-th me-2"></i>Quick Actions</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="/?url=admin/pos/roles" class="quick-action-btn d-block text-decoration-none">
                                <i class="fas fa-user-tag fa-2x mb-2 text-primary"></i>
                                <h6>Manage Roles</h6>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/?url=admin/pos/outlet" class="quick-action-btn d-block text-decoration-none">
                                <i class="fas fa-store-alt fa-2x mb-2 text-success"></i>
                                <h6>Outlet Settings</h6>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/?url=admin/pos/stock-settings" class="quick-action-btn d-block text-decoration-none">
                                <i class="fas fa-boxes fa-2x mb-2 text-warning"></i>
                                <h6>Stock Settings</h6>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/?url=admin/pos/payment" class="quick-action-btn d-block text-decoration-none">
                                <i class="fas fa-credit-card fa-2x mb-2 text-info"></i>
                                <h6>Payment Methods</h6>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-history me-2"></i>Recent Transactions</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Cashier</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($recentTransactions) > 0): ?>
                                        <?php foreach ($recentTransactions as $transaction): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($transaction['order_number']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($transaction['cashier_name'] ?? 'N/A'); ?></td>
                                                <td>$<?php echo number_format($transaction['total_amount'], 2); ?></td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        <?php echo ucfirst(htmlspecialchars($transaction['payment_method'])); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">
                                                        <?php echo ucfirst(htmlspecialchars($transaction['status'])); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo safeFormatDate($transaction['created_at'], 'h:i A'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No transactions today</td>
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
