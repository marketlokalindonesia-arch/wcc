<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../controllers/DashboardController.php';

requireRole('admin');

$dashboardController = new DashboardController();
$salesData = $dashboardController->getSalesData(7);

$database = new Database();
$db = $database->getConnection();

$query = "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE payment_status = 'paid' AND DATE(created_at) = CURRENT_DATE";
$stmt = $db->prepare($query);
$stmt->execute();
$todaySales = $stmt->fetch()['total'];

$query = "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE payment_status = 'paid' AND created_at >= CURRENT_DATE - INTERVAL '7 days'";
$stmt = $db->prepare($query);
$stmt->execute();
$weekSales = $stmt->fetch()['total'];

$query = "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE payment_status = 'paid' AND created_at >= CURRENT_DATE - INTERVAL '30 days'";
$stmt = $db->prepare($query);
$stmt->execute();
$monthSales = $stmt->fetch()['total'];

$query = "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE payment_status = 'paid'";
$stmt = $db->prepare($query);
$stmt->execute();
$totalSales = $stmt->fetch()['total'];

$query = "SELECT p.name, COALESCE(SUM(oi.quantity), 0) as total_sold 
          FROM products p 
          LEFT JOIN order_items oi ON p.id = oi.product_id 
          GROUP BY p.id, p.name 
          ORDER BY total_sold DESC LIMIT 5";
$stmt = $db->prepare($query);
$stmt->execute();
$topProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT payment_method, COUNT(*) as count, COALESCE(SUM(total_amount), 0) as total 
          FROM orders 
          WHERE payment_status = 'paid'
          GROUP BY payment_method";
$stmt = $db->prepare($query);
$stmt->execute();
$paymentMethods = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    
    <style>
        body { background: #f8f9fa; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding: 20px;
        }
        .sidebar .logo {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 5px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }
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
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-store me-2"></i>WC Clone
        </div>
        <nav class="nav flex-column">
            <a class="nav-link" href="/?url=admin/dashboard">
                <i class="fas fa-chart-line me-2"></i>Dashboard
            </a>
            <a class="nav-link" href="/?url=admin/pos">
                <i class="fas fa-cash-register me-2"></i>POS
            </a>
            <a class="nav-link" href="/?url=admin/products">
                <i class="fas fa-box me-2"></i>Products
            </a>
            <a class="nav-link" href="/?url=admin/orders">
                <i class="fas fa-shopping-bag me-2"></i>Orders
            </a>
            <a class="nav-link" href="/?url=admin/customers">
                <i class="fas fa-users me-2"></i>Customers
            </a>
            <a class="nav-link" href="/?url=admin/inventory">
                <i class="fas fa-warehouse me-2"></i>Inventory
            </a>
            <a class="nav-link active" href="/?url=admin/reports">
                <i class="fas fa-chart-bar me-2"></i>Reports
            </a>
            <a class="nav-link" href="/?url=admin/settings">
                <i class="fas fa-cog me-2"></i>Settings
            </a>
            <hr style="border-color: rgba(255,255,255,0.2)">
            <a class="nav-link" href="/?url=logout">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </a>
        </nav>
        <div class="mt-4 pt-4" style="border-top: 1px solid rgba(255,255,255,0.2);">
            <div class="text-center">
                <div class="mb-2"><i class="fas fa-user-circle" style="font-size: 40px;"></i></div>
                <div style="font-size: 14px;"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                <div style="font-size: 12px; opacity: 0.7;">Administrator</div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-chart-bar me-2"></i>Reports & Analytics</h2>
            <div>
                <button class="btn btn-outline-primary me-2">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </button>
                <button class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </button>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="label"><i class="fas fa-calendar-day me-1"></i>Today's Sales</div>
                    <div class="value text-primary">$<?php echo number_format($todaySales, 2); ?></div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="label"><i class="fas fa-calendar-week me-1"></i>This Week</div>
                    <div class="value text-info">$<?php echo number_format($weekSales, 2); ?></div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="label"><i class="fas fa-calendar-alt me-1"></i>This Month</div>
                    <div class="value text-warning">$<?php echo number_format($monthSales, 2); ?></div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="label"><i class="fas fa-chart-line me-1"></i>Total Sales</div>
                    <div class="value text-success">$<?php echo number_format($totalSales, 2); ?></div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-custom p-4">
                    <h5 class="mb-4">Sales Trend (Last 7 Days)</h5>
                    <canvas id="salesTrendChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-8 mb-4">
                <div class="card card-custom p-4">
                    <h5 class="mb-4">Top 5 Products</h5>
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card card-custom p-4">
                    <h5 class="mb-4">Payment Methods</h5>
                    <canvas id="paymentMethodsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        const salesData = <?php echo json_encode($salesData); ?>;
        const topProducts = <?php echo json_encode($topProducts); ?>;
        const paymentMethods = <?php echo json_encode($paymentMethods); ?>;

        const salesDates = salesData.map(d => new Date(d.date).toLocaleDateString('en-US', {month: 'short', day: 'numeric'}));
        const salesTotals = salesData.map(d => parseFloat(d.total));

        new Chart(document.getElementById('salesTrendChart'), {
            type: 'line',
            data: {
                labels: salesDates,
                datasets: [{
                    label: 'Sales',
                    data: salesTotals,
                    borderColor: 'rgb(102, 126, 234)',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
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

        new Chart(document.getElementById('topProductsChart'), {
            type: 'bar',
            data: {
                labels: topProducts.map(p => p.name),
                datasets: [{
                    label: 'Units Sold',
                    data: topProducts.map(p => parseInt(p.total_sold)),
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(118, 75, 162, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        new Chart(document.getElementById('paymentMethodsChart'), {
            type: 'pie',
            data: {
                labels: paymentMethods.map(p => p.payment_method.replace('_', ' ').toUpperCase()),
                datasets: [{
                    data: paymentMethods.map(p => parseFloat(p.total)),
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(118, 75, 162, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
</body>
</html>
