<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/session.php';

requireRole('admin');

$database = new Database();
$db = $database->getConnection();

$query = "SELECT role, COUNT(*) as count FROM users GROUP BY role";
$stmt = $db->prepare($query);
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Roles - WC Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../partials/admin_sidebar.php'; ?>
    
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-user-tag me-2"></i>POS Roles Management</h2>
            <button class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Role
            </button>
        </div>

        <div class="row">
            <?php 
            $role_icons = [
                'admin' => ['icon' => 'fa-user-shield', 'color' => 'primary'],
                'cashier' => ['icon' => 'fa-cash-register', 'color' => 'success'],
                'customer' => ['icon' => 'fa-user', 'color' => 'info']
            ];
            foreach($roles as $role): 
                $icon = $role_icons[$role['role']]['icon'] ?? 'fa-user';
                $color = $role_icons[$role['role']]['color'] ?? 'secondary';
            ?>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas <?php echo $icon; ?> fa-3x text-<?php echo $color; ?>"></i>
                        </div>
                        <h5 class="text-capitalize"><?php echo $role['role']; ?></h5>
                        <p class="text-muted mb-3"><?php echo $role['count']; ?> users</p>
                        <button class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-edit"></i> Edit Permissions
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Role Permissions</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Permission</th>
                                <th>Admin</th>
                                <th>Cashier</th>
                                <th>Customer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Access POS</td>
                                <td><i class="fas fa-check text-success"></i></td>
                                <td><i class="fas fa-check text-success"></i></td>
                                <td><i class="fas fa-times text-danger"></i></td>
                            </tr>
                            <tr>
                                <td>Manage Products</td>
                                <td><i class="fas fa-check text-success"></i></td>
                                <td><i class="fas fa-times text-danger"></i></td>
                                <td><i class="fas fa-times text-danger"></i></td>
                            </tr>
                            <tr>
                                <td>View Reports</td>
                                <td><i class="fas fa-check text-success"></i></td>
                                <td><i class="fas fa-check text-success"></i></td>
                                <td><i class="fas fa-times text-danger"></i></td>
                            </tr>
                            <tr>
                                <td>Manage Users</td>
                                <td><i class="fas fa-check text-success"></i></td>
                                <td><i class="fas fa-times text-danger"></i></td>
                                <td><i class="fas fa-times text-danger"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
