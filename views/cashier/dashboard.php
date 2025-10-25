<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../controllers/POSController.php';
require_once __DIR__ . '/../../controllers/DashboardController.php';

requireRole('cashier');

$user = getUser();
$posController = new POSController();
$dashboardController = new DashboardController();

$stats = $posController->getCashierStats($_SESSION['user_id']);
$recentOrders = $dashboardController->getRecentOrders(5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Dashboard - WC Clone</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: #f8f9fa;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-custom .navbar-brand {
            color: white;
            font-weight: 700;
            font-size: 24px;
        }
        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 8px 15px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .navbar-custom .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .main-content {
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
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
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 15px;
        }
        .stat-card .value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .stat-card .label {
            color: #6c757d;
            font-size: 14px;
        }
        .quick-action-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: 100%;
        }
        .quick-action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        }
        .quick-action-card .icon {
            font-size: 60px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .quick-action-card h4 {
            margin-bottom: 10px;
        }
        .quick-action-card p {
            color: #6c757d;
            font-size: 14px;
        }
        .card-custom {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: none;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
        }
        .welcome-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        .welcome-banner h2 {
            margin-bottom: 10px;
        }
        .welcome-banner p {
            opacity: 0.9;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="/?url=cashier/dashboard">
                <i class="fas fa-store me-2"></i>WC Clone
            </a>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end me-3">
                    <div style="font-size: 12px; opacity: 0.8;">Cashier</div>
                    <div style="font-weight: 600;"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                </div>
                <a href="/?url=logout" class="btn btn-outline-light">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <h2><i class="fas fa-hand-wave me-2"></i>Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</h2>
            <p><i class="fas fa-calendar me-2"></i><?php echo date('l, F j, Y'); ?></p>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="value">$<?php echo number_format($stats['total_sales'] ?? 0, 2); ?></div>
                    <div class="label">Today's Sales</div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="value"><?php echo number_format($stats['total_transactions'] ?? 0); ?></div>
                    <div class="label">Total Transactions</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h5 class="mb-3">Quick Actions</h5>
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <a href="/?url=cashier/pos" style="text-decoration: none; color: inherit;">
                    <div class="quick-action-card">
                        <div class="icon"><i class="fas fa-cash-register"></i></div>
                        <h4>Open POS</h4>
                        <p>Start processing sales and transactions</p>
                    </div>
                </a>
            </div>
            <div class="col-md-6 mb-3">
                <a href="/?url=cashier/transactions" style="text-decoration: none; color: inherit;">
                    <div class="quick-action-card">
                        <div class="icon"><i class="fas fa-history"></i></div>
                        <h4>View Transactions</h4>
                        <p>Check your transaction history</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card card-custom p-4">
            <h5 class="mb-4">Recent Transactions</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($recentOrders)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No transactions yet today</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($recentOrders as $order): ?>
                                <tr>
                                    <td><strong>#<?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td>
                                        <?php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $color = $statusColors[$order['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $color; ?> badge-status">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo safeFormatDate($order['created_at'], 'g:i A'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
