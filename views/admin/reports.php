<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../controllers/DashboardController.php';

requireRole('admin');

$dashboardController = new DashboardController();
$database = new Database();
$db = $database->getConnection();

$stats = $dashboardController->getAdminStats();
$salesData = $dashboardController->getSalesData(30);

$query = "SELECT DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue 
          FROM orders 
          WHERE created_at >= CURRENT_DATE - INTERVAL '30 days'
          GROUP BY DATE(created_at) 
          ORDER BY date DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$dailySales = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT p.name, p.sku, SUM(oi.quantity) as total_sold, SUM(oi.subtotal) as revenue
          FROM order_items oi
          JOIN products p ON oi.product_id = p.id
          GROUP BY p.id, p.name, p.sku
          ORDER BY total_sold DESC
          LIMIT 10";
$stmt = $db->prepare($query);
$stmt->execute();
$topProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$user = getUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - WC Clone</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
        .report-card {
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s;
            border: 2px solid #e9ecef;
            text-decoration: none;
            display: block;
        }
        .report-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-chart-line me-2"></i>Reports & Analytics</h2>
            <div>
                <button class="btn btn-outline-primary btn-sm me-2">
                    <i class="fas fa-calendar me-1"></i>Date Range
                </button>
                <button class="btn btn-primary btn-sm">
                    <i class="fas fa-download me-1"></i>Export Report
                </button>
            </div>
        </div>

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
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="value">$<?php echo number_format(($stats['total_revenue'] ?? 0) / max(($stats['total_orders'] ?? 1), 1), 2); ?></div>
                    <div class="label">Average Order Value</div>
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
            <div class="col-md-12">
                <div class="card card-custom p-4">
                    <h5 class="mb-3"><i class="fas fa-chart-area me-2"></i>Sales Overview (Last 30 Days)</h5>
                    <canvas id="salesChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card card-custom p-3">
                    <h5 class="mb-3"><i class="fas fa-file-alt me-2"></i>Available Reports</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="/?url=admin/analytics/products" class="report-card">
                                <i class="fas fa-box fa-2x mb-2 text-primary"></i>
                                <h6>Product Analytics</h6>
                                <small class="text-muted">Product performance & insights</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/?url=admin/analytics/revenue" class="report-card">
                                <i class="fas fa-dollar-sign fa-2x mb-2 text-success"></i>
                                <h6>Revenue Report</h6>
                                <small class="text-muted">Revenue trends & analysis</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/?url=admin/analytics/orders" class="report-card">
                                <i class="fas fa-shopping-cart fa-2x mb-2 text-info"></i>
                                <h6>Orders Analytics</h6>
                                <small class="text-muted">Order statistics & trends</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/?url=admin/analytics/customers" class="report-card">
                                <i class="fas fa-users fa-2x mb-2 text-warning"></i>
                                <h6>Customer Insights</h6>
                                <small class="text-muted">Customer behavior & data</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/?url=admin/analytics/stock" class="report-card">
                                <i class="fas fa-warehouse fa-2x mb-2 text-danger"></i>
                                <h6>Stock Analytics</h6>
                                <small class="text-muted">Inventory levels & trends</small>
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
                        <h5 class="mb-3"><i class="fas fa-trophy me-2"></i>Top Selling Products</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>SKU</th>
                                        <th>Units Sold</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($topProducts) > 0): ?>
                                        <?php foreach ($topProducts as $index => $product): ?>
                                            <tr>
                                                <td><strong><?php echo $index + 1; ?></strong></td>
                                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                                <td><?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?></td>
                                                <td><?php echo number_format($product['total_sold']); ?></td>
                                                <td><strong>$<?php echo number_format($product['revenue'], 2); ?></strong></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No sales data available</td>
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
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesData = <?php echo json_encode(array_reverse($dailySales)); ?>;
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesData.map(d => d.date),
                datasets: [{
                    label: 'Revenue',
                    data: salesData.map(d => d.revenue),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Orders',
                    data: salesData.map(d => d.orders),
                    borderColor: '#f5576c',
                    backgroundColor: 'rgba(245, 87, 108, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
