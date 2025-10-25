<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/session.php';

requireRole('admin');

$database = new Database();
$db = $database->getConnection();

// Top customers
$query = "SELECT u.first_name, u.last_name, u.email, COUNT(o.id) as order_count, SUM(o.total_amount) as total_spent
          FROM users u
          LEFT JOIN orders o ON u.id = o.customer_id
          WHERE u.role = 'customer'
          GROUP BY u.id, u.first_name, u.last_name, u.email
          ORDER BY total_spent DESC LIMIT 10";
$stmt = $db->prepare($query);
$stmt->execute();
$topCustomers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Customer stats
$query = "SELECT COUNT(*) as total_customers FROM users WHERE role = 'customer'";
$stmt = $db->prepare($query);
$stmt->execute();
$stats = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Analytics - WC Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../partials/admin_sidebar.php'; ?>
    
    <div class="main-content">
        <h2 class="mb-4"><i class="fas fa-user-friends me-2"></i>Customer Analytics</h2>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h3><?php echo $stats['total_customers']; ?></h3>
                        <p class="text-muted mb-0">Total Customers</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-user-plus fa-3x text-success mb-3"></i>
                        <h3>0</h3>
                        <p class="text-muted mb-0">New This Month</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-crown fa-3x text-warning mb-3"></i>
                        <h3><?php echo count($topCustomers); ?></h3>
                        <p class="text-muted mb-0">VIP Customers</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Top Customers by Spending</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Orders</th>
                                <th>Total Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $rank = 1;
                            foreach($topCustomers as $customer): 
                            ?>
                            <tr>
                                <td>
                                    <?php if($rank <= 3): ?>
                                        <span class="badge bg-warning">#<?php echo $rank; ?></span>
                                    <?php else: ?>
                                        #<?php echo $rank; ?>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                <td><?php echo $customer['order_count'] ?? 0; ?></td>
                                <td>Rp <?php echo number_format($customer['total_spent'] ?? 0, 0, ',', '.'); ?></td>
                            </tr>
                            <?php 
                            $rank++;
                            endforeach; 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
