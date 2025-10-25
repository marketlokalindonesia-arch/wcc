<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('admin');

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM users ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$user = getUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - WC Clone</title>
    
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
        .nav-pills .nav-link {
            color: #667eea;
            border-radius: 10px;
        }
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            <a class="nav-link" href="/?url=admin/reports">
                <i class="fas fa-chart-bar me-2"></i>Reports
            </a>
            <a class="nav-link active" href="/?url=admin/settings">
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
            <h2><i class="fas fa-cog me-2"></i>System Settings</h2>
        </div>

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card card-custom p-3">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item mb-2">
                            <a class="nav-link active" data-bs-toggle="pill" href="#store-info">
                                <i class="fas fa-store me-2"></i>Store Information
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" data-bs-toggle="pill" href="#users">
                                <i class="fas fa-users-cog me-2"></i>User Management
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" data-bs-toggle="pill" href="#payment">
                                <i class="fas fa-credit-card me-2"></i>Payment Methods
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-9">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="store-info">
                        <div class="card card-custom p-4">
                            <h5 class="mb-4">Store Information</h5>
                            <form action="/?url=admin/save-store-info" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Store Name</label>
                                    <input type="text" name="store_name" class="form-control" value="WC Clone Store" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Store Address</label>
                                    <textarea name="store_address" class="form-control" rows="3">123 Main Street, City, State 12345</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="tel" name="store_phone" class="form-control" value="+1 234-567-8900">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="store_email" class="form-control" value="info@wcclone.com">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Currency</label>
                                    <select name="currency" class="form-select">
                                        <option value="USD" selected>USD - US Dollar</option>
                                        <option value="EUR">EUR - Euro</option>
                                        <option value="GBP">GBP - British Pound</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="users">
                        <div class="card card-custom p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0">User Management</h5>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fas fa-plus me-1"></i>Add User
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table id="usersTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($users as $u): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?></td>
                                            <td><?php echo htmlspecialchars($u['username']); ?></td>
                                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                                            <td>
                                                <?php if($u['role'] === 'admin'): ?>
                                                    <span class="badge bg-danger">Admin</span>
                                                <?php elseif($u['role'] === 'cashier'): ?>
                                                    <span class="badge bg-info">Cashier</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Customer</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" onclick="editUser(<?php echo $u['id']; ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <?php if($u['id'] != $_SESSION['user_id']): ?>
                                                    <button class="btn btn-outline-danger" onclick="deleteUser(<?php echo $u['id']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="payment">
                        <div class="card card-custom p-4">
                            <h5 class="mb-4">Payment Methods</h5>
                            <form action="/?url=admin/save-payment-methods" method="POST">
                                <div class="mb-3">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="cash" name="payment_methods[]" value="cash" checked>
                                        <label class="form-check-label" for="cash">
                                            <i class="fas fa-money-bill-wave me-2"></i><strong>Cash</strong>
                                        </label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="card" name="payment_methods[]" value="card" checked>
                                        <label class="form-check-label" for="card">
                                            <i class="fas fa-credit-card me-2"></i><strong>Credit/Debit Card</strong>
                                        </label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="ewallet" name="payment_methods[]" value="e-wallet" checked>
                                        <label class="form-check-label" for="ewallet">
                                            <i class="fas fa-wallet me-2"></i><strong>E-Wallet</strong>
                                        </label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="bank" name="payment_methods[]" value="bank_transfer" checked>
                                        <label class="form-check-label" for="bank">
                                            <i class="fas fa-university me-2"></i><strong>Bank Transfer</strong>
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Payment Methods
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/?url=admin/add-user" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="customer">Customer</option>
                                <option value="cashier">Cashier</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                order: [[4, 'desc']],
                pageLength: 25
            });
        });

        function editUser(userId) {
            window.location.href = `/?url=admin/edit-user&id=${userId}`;
        }

        function deleteUser(userId) {
            if(confirm('Are you sure you want to delete this user?')) {
                window.location.href = `/?url=admin/delete-user&id=${userId}`;
            }
        }
    </script>
</body>
</html>
