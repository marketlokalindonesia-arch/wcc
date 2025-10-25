<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../controllers/DashboardController.php';

requireRole('admin');

$dashboardController = new DashboardController();
$stats = $dashboardController->getAdminStats();
$recentOrders = $dashboardController->getRecentOrders(10);
$topProducts = $dashboardController->getTopProducts(5);
$salesData = $dashboardController->getSalesData(7);

$user = getUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - WC Clone</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: #f8f9fa;
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
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/admin_sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Dashboard Overview</h2>
            <div class="text-muted">
                <i class="fas fa-calendar me-2"></i><?php echo date('l, F j, Y'); ?>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="value">$<?php echo number_format($stats['total_revenue'] ?? 0, 2); ?></div>
                    <div class="label">Total Revenue</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="value"><?php echo number_format($stats['total_orders'] ?? 0); ?></div>
                    <div class="label">Total Orders</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="value"><?php echo number_format($stats['total_products'] ?? 0); ?></div>
                    <div class="label">Total Products</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="value"><?php echo number_format($stats['total_customers'] ?? 0); ?></div>
                    <div class="label">Total Customers</div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="value">$<?php echo number_format($stats['today_sales'] ?? 0, 2); ?></div>
                    <div class="label"><i class="fas fa-calendar-day me-1"></i>Today's Sales</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="value"><?php echo number_format($stats['pending_orders'] ?? 0); ?></div>
                    <div class="label"><i class="fas fa-clock me-1"></i>Pending Orders</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="value text-danger"><?php echo number_format($stats['low_stock'] ?? 0); ?></div>
                    <div class="label"><i class="fas fa-exclamation-triangle me-1"></i>Low Stock Items</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <a href="/?url=admin/pos" class="btn btn-primary w-100">
                        <i class="fas fa-cash-register me-2"></i>Open POS
                    </a>
                </div>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-custom p-4">
                    <h5 class="mb-4">Sales Overview (Last 7 Days)</h5>
                    <canvas id="salesChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Orders and Top Products -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card card-custom p-4">
                    <h5 class="mb-4">Recent Orders</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($recentOrders)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No orders yet</td>
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
                                            <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card card-custom p-4">
                    <h5 class="mb-4">Top Products</h5>
                    <?php if(empty($topProducts)): ?>
                        <p class="text-muted text-center">No product sales yet</p>
                    <?php else: ?>
                        <?php foreach($topProducts as $product): ?>
                            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                <div class="flex-grow-1">
                                    <div class="fw-bold"><?php echo htmlspecialchars($product['name']); ?></div>
                                    <small class="text-muted">Sold: <?php echo number_format($product['total_sold']); ?> units</small>
                                </div>
                                <div class="text-end">
                                    <div class="text-success fw-bold">$<?php echo number_format($product['price'], 2); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <script>
        // Sales Chart
        const salesData = <?php echo json_encode($salesData); ?>;
        const labels = salesData.map(d => {
            const date = new Date(d.date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        const data = salesData.map(d => parseFloat(d.total));

        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales ($)',
                    data: data,
                    borderColor: 'rgb(102, 126, 234)',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
