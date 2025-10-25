<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/session.php';

requireRole('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Payment Settings - WC Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../partials/admin_sidebar.php'; ?>
    
    <div class="main-content">
        <h2 class="mb-4"><i class="fas fa-credit-card me-2"></i>POS Payment Methods</h2>

        <div class="row">
            <?php 
            $payment_methods = [
                ['name' => 'Cash', 'icon' => 'fa-money-bill-wave', 'color' => 'success', 'enabled' => true],
                ['name' => 'Card', 'icon' => 'fa-credit-card', 'color' => 'primary', 'enabled' => true],
                ['name' => 'E-Wallet', 'icon' => 'fa-wallet', 'color' => 'info', 'enabled' => true],
                ['name' => 'Bank Transfer', 'icon' => 'fa-university', 'color' => 'warning', 'enabled' => false],
                ['name' => 'QRIS', 'icon' => 'fa-qrcode', 'color' => 'danger', 'enabled' => false],
            ];
            foreach($payment_methods as $method): 
            ?>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas <?php echo $method['icon']; ?> fa-3x text-<?php echo $method['color']; ?>"></i>
                        </div>
                        <h5><?php echo $method['name']; ?></h5>
                        <div class="form-check form-switch d-flex justify-content-center mt-3">
                            <input class="form-check-input" type="checkbox" <?php echo $method['enabled'] ? 'checked' : ''; ?>>
                            <label class="form-check-label ms-2">
                                <?php echo $method['enabled'] ? 'Enabled' : 'Disabled'; ?>
                            </label>
                        </div>
                        <button class="btn btn-outline-primary btn-sm mt-3">
                            <i class="fas fa-cog"></i> Configure
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
