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

$query = "SELECT COUNT(*) as total FROM coupons";
$stmt = $db->prepare($query);
$stmt->execute();
$totalCoupons = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$query = "SELECT COUNT(*) as total FROM coupons WHERE is_active = true";
$stmt = $db->prepare($query);
$stmt->execute();
$activeCoupons = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$query = "SELECT COUNT(*) as total FROM coupons 
          WHERE expiry_date < CURRENT_DATE OR usage_count >= usage_limit";
$stmt = $db->prepare($query);
$stmt->execute();
$expiredCoupons = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$user = getUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coupons - WC Clone</title>
    
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
        .coupon-code {
            font-family: 'Courier New', monospace;
            background: #f8f9fa;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-ticket-alt me-2"></i>Coupons & Discounts</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCouponModal">
                <i class="fas fa-plus me-2"></i>Create Coupon
            </button>
        </div>

        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="value text-primary"><?php echo number_format($totalCoupons); ?></div>
                    <div class="label"><i class="fas fa-ticket-alt me-1"></i>Total Coupons</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="value text-success"><?php echo number_format($activeCoupons); ?></div>
                    <div class="label"><i class="fas fa-check-circle me-1"></i>Active Coupons</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="value text-danger"><?php echo number_format($expiredCoupons); ?></div>
                    <div class="label"><i class="fas fa-times-circle me-1"></i>Expired/Used Up</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-list me-2"></i>All Coupons</h5>
                        <div class="table-responsive">
                            <table id="couponsTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Discount</th>
                                        <th>Usage</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($coupons) > 0): ?>
                                        <?php foreach ($coupons as $coupon): 
                                            $isExpired = strtotime($coupon['expiry_date']) < time();
                                            $isUsedUp = $coupon['usage_count'] >= $coupon['usage_limit'];
                                        ?>
                                            <tr>
                                                <td><span class="coupon-code"><?php echo htmlspecialchars($coupon['code']); ?></span></td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        <?php echo ucfirst(htmlspecialchars($coupon['discount_type'])); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong>
                                                        <?php 
                                                        if ($coupon['discount_type'] == 'percentage') {
                                                            echo number_format($coupon['discount_value']) . '%';
                                                        } else {
                                                            echo '$' . number_format($coupon['discount_value'], 2);
                                                        }
                                                        ?>
                                                    </strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <?php echo number_format($coupon['usage_count']); ?> / 
                                                        <?php echo number_format($coupon['usage_limit']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php echo safeFormatDate($coupon['expiry_date'], 'M d, Y'); ?>
                                                    <?php if ($isExpired): ?>
                                                        <br><small class="text-danger">Expired</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!$coupon['is_active']): ?>
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    <?php elseif ($isExpired || $isUsedUp): ?>
                                                        <span class="badge bg-danger">Expired</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-outline-info" title="Duplicate">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-ticket-alt fa-3x mb-3 d-block"></i>
                                                <p>No coupons created yet. Click "Create Coupon" to get started!</p>
                                            </td>
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

    <div class="modal fade" id="addCouponModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Create New Coupon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addCouponForm" action="/?url=admin/create-coupon" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Coupon Code</label>
                            <input type="text" name="code" class="form-control" placeholder="e.g., SAVE20" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Type</label>
                                <select name="discount_type" class="form-select" required>
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount ($)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Value</label>
                                <input type="number" name="discount_value" class="form-control" step="0.01" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Usage Limit</label>
                                <input type="number" name="usage_limit" class="form-control" value="100" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" name="expiry_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Minimum Order Amount</label>
                            <input type="number" name="min_order_amount" class="form-control" step="0.01" placeholder="0.00">
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="addCouponForm" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Create Coupon
                    </button>
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
            $('#couponsTable').DataTable({
                order: [[0, 'asc']],
                pageLength: 25
            });
        });
    </script>
</body>
</html>
