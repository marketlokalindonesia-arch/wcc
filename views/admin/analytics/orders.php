<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/session.php';

requireRole('admin');

$database = new Database();
$db = $database->getConnection();

// Orders by status
$query = "SELECT status, COUNT(*) as count FROM orders GROUP BY status";
$stmt = $db->prepare($query);
$stmt->execute();
$ordersByStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Orders by payment method
$query = "SELECT payment_method, COUNT(*) as count FROM orders GROUP BY payment_method";
$stmt = $db->prepare($query);
$stmt->execute();
$ordersByPayment = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Analytics - WC Clone</title>
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
        <h2 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Orders Analytics</h2>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Orders by Status</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Orders by Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Order Statistics</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = array_sum(array_column($ordersByStatus, 'count'));
                            foreach($ordersByStatus as $status): 
                                $percentage = ($status['count'] / max($total, 1)) * 100;
                            ?>
                            <tr>
                                <td><span class="badge bg-primary"><?php echo ucfirst($status['status']); ?></span></td>
                                <td><?php echo $status['count']; ?></td>
                                <td><?php echo number_format($percentage, 1); ?>%</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_column($ordersByStatus, 'status')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($ordersByStatus, 'count')); ?>,
                backgroundColor: ['#667eea', '#f093fb', '#4facfe', '#43e97b']
            }]
        }
    });

    new Chart(document.getElementById('paymentChart'), {
        type: 'pie',
        data: {
            labels: <?php echo json_encode(array_column($ordersByPayment, 'payment_method')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($ordersByPayment, 'count')); ?>,
                backgroundColor: ['#667eea', '#f093fb', '#4facfe', '#43e97b', '#fa709a']
            }]
        }
    });
    </script>
</body>
</html>
