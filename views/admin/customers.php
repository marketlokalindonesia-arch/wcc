<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('admin');

$database = new Database();
$db = $database->getConnection();

$query = "SELECT u.*, 
          COUNT(o.id) as total_orders, 
          COALESCE(SUM(o.total_amount), 0) as total_spent 
          FROM users u 
          LEFT JOIN orders o ON u.id = o.customer_id AND o.payment_status = 'paid'
          WHERE u.role = 'customer'
          GROUP BY u.id
          ORDER BY total_spent DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$user = getUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers Management - WC Clone</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
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
            <a class="nav-link active" href="/?url=admin/customers">
                <i class="fas fa-users me-2"></i>Customers
            </a>
            <a class="nav-link" href="/?url=admin/inventory">
                <i class="fas fa-warehouse me-2"></i>Inventory
            </a>
            <a class="nav-link" href="/?url=admin/reports">
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
            <h2><i class="fas fa-users me-2"></i>Customer Management</h2>
        </div>

        <div class="card card-custom p-4">
            <div class="table-responsive">
                <table id="customersTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Total Orders</th>
                            <th>Total Spent</th>
                            <th>Join Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($customers)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-users fa-3x mb-3" style="opacity: 0.3;"></i>
                                    <p>No customers found</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($customers as $customer): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></strong><br>
                                    <small class="text-muted">@<?php echo htmlspecialchars($customer['username']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                <td><?php echo htmlspecialchars($customer['phone'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="badge bg-primary"><?php echo $customer['total_orders']; ?> orders</span>
                                </td>
                                <td><strong>$<?php echo number_format($customer['total_spent'], 2); ?></strong></td>
                                <td><?php echo date('M d, Y', strtotime($customer['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-outline-primary btn-sm" onclick="viewCustomer(<?php echo $customer['id']; ?>)">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
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

        function viewCustomer(customerId) {
            window.location.href = `/?url=admin/customer-details&id=${customerId}`;
        }
    </script>
</body>
</html>
