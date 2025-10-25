<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/session.php';

requireRole('admin');

$database = new Database();
$db = $database->getConnection();

// Revenue by date
$query = "SELECT DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as orders
          FROM orders WHERE status = 'completed'
          GROUP BY DATE(created_at) ORDER BY date DESC LIMIT 30";
$stmt = $db->prepare($query);
$stmt->execute();
$revenueData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total stats
$query = "SELECT SUM(total_amount) as total_revenue, COUNT(*) as total_orders FROM orders WHERE status = 'completed'";
$stmt = $db->prepare($query);
$stmt->execute();
$stats = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Analytics - WC Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f8f9fa; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../partials/admin_sidebar.php'; ?>
    
    <div class="main-content">
        <h2 class="mb-4"><i class="fas fa-dollar-sign me-2"></i>Revenue Analytics</h2>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded p-3 me-3">
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0">Total Revenue</p>
                                <h3>Rp <?php echo number_format($stats['total_revenue'] ?? 0, 0, ',', '.'); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-success text-white rounded p-3 me-3">
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0">Total Orders</p>
                                <h3><?php echo $stats['total_orders'] ?? 0; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-info text-white rounded p-3 me-3">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0">Average Order</p>
                                <h3>Rp <?php echo number_format(($stats['total_revenue'] ?? 0) / max($stats['total_orders'], 1), 0, ',', '.'); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Revenue Trend (Last 30 Days)</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const ctx = document.getElementById('revenueChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_reverse(array_column($revenueData, 'date'))); ?>,
            datasets: [{
                label: 'Revenue (Rp)',
                data: <?php echo json_encode(array_reverse(array_column($revenueData, 'revenue'))); ?>,
                borderColor: 'rgb(102, 126, 234)',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    </script>
</body>
</html>
