<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('admin');

$database = new Database();
$db = $database->getConnection();

$query = "SELECT u.*, 
          COUNT(DISTINCT o.id) as total_orders,
          COALESCE(SUM(o.total_amount), 0) as total_spent
          FROM users u
          LEFT JOIN orders o ON u.id = o.customer_id
          WHERE u.role = 'customer'
          GROUP BY u.id
          ORDER BY total_spent DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT COUNT(*) as total FROM users WHERE role = 'customer'";
$stmt = $db->prepare($query);
$stmt->execute();
$totalCustomers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$query = "SELECT COUNT(DISTINCT customer_id) as total FROM orders 
          WHERE created_at >= CURRENT_DATE - INTERVAL '30 days'";
$stmt = $db->prepare($query);
$stmt->execute();
$activeCustomers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$query = "SELECT COUNT(*) as total FROM users 
          WHERE role = 'customer' AND created_at >= CURRENT_DATE - INTERVAL '30 days'";
$stmt = $db->prepare($query);
$stmt->execute();
$newCustomers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$user = getUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - WC Clone</title>
    
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
        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-users me-2"></i>Customers</h2>
            <div>
                <button class="btn btn-outline-primary btn-sm me-2">
                    <i class="fas fa-download me-1"></i>Export
                </button>
                <button class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Add Customer
                </button>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="value text-primary"><?php echo number_format($totalCustomers); ?></div>
                    <div class="label"><i class="fas fa-users me-1"></i>Total Customers</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="value text-success"><?php echo number_format($activeCustomers); ?></div>
                    <div class="label"><i class="fas fa-user-check me-1"></i>Active (30 Days)</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="value text-info"><?php echo number_format($newCustomers); ?></div>
                    <div class="label"><i class="fas fa-user-plus me-1"></i>New (30 Days)</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-list me-2"></i>Customer List</h5>
                        <div class="table-responsive">
                            <table id="customersTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Orders</th>
                                        <th>Total Spent</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $colors = ['#667eea', '#f5576c', '#4facfe', '#43e97b', '#fa709a'];
                                    foreach ($customers as $index => $customer): 
                                        $initials = strtoupper(substr($customer['username'], 0, 2));
                                        $color = $colors[$index % count($colors)];
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="customer-avatar me-2" style="background: <?php echo $color; ?>">
                                                        <?php echo $initials; ?>
                                                    </div>
                                                    <strong><?php echo htmlspecialchars($customer['username']); ?></strong>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['phone'] ?? 'N/A'); ?></td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <?php echo number_format($customer['total_orders']); ?>
                                                </span>
                                            </td>
                                            <td><strong>$<?php echo number_format($customer['total_spent'], 2); ?></strong></td>
                                            <td><?php echo safeFormatDate($customer['created_at'], 'M d, Y'); ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success" title="Orders">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                    <button class="btn btn-outline-info" title="Email">
                                                        <i class="fas fa-envelope"></i>
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
            $('#customersTable').DataTable({
                order: [[4, 'desc']],
                pageLength: 25
            });
        });
    </script>
</body>
</html>
