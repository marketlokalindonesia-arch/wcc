<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('admin');

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM coupons ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coupons Management - WC Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/admin_sidebar.php'; ?>
    
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-ticket-alt me-2"></i>Coupons Management</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCouponModal">
                <i class="fas fa-plus me-2"></i>Add New Coupon
            </button>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Discount Type</th>
                                <th>Discount Value</th>
                                <th>Min. Amount</th>
                                <th>Usage</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($coupons)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="fas fa-ticket-alt fa-3x mb-3" style="opacity: 0.3;"></i>
                                        <p>No coupons found. Create your first coupon!</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($coupons as $coupon): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($coupon['code']); ?></strong></td>
                                    <td><?php echo ucfirst($coupon['discount_type']); ?></td>
                                    <td>
                                        <?php if($coupon['discount_type'] === 'percentage'): ?>
                                            <?php echo $coupon['discount_value']; ?>%
                                        <?php else: ?>
                                            Rp <?php echo number_format($coupon['discount_value'], 0, ',', '.'); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>Rp <?php echo number_format($coupon['minimum_amount'], 0, ',', '.'); ?></td>
                                    <td><?php echo $coupon['used_count']; ?> / <?php echo $coupon['usage_limit']; ?></td>
                                    <td><?php echo safeFormatDate($coupon['expiry_date'], 'd M Y'); ?></td>
                                    <td>
                                        <?php if($coupon['status'] === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
