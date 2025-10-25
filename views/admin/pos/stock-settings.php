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
    <title>POS Stock Settings - WC Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../partials/admin_sidebar.php'; ?>
    
    <div class="main-content">
        <h2 class="mb-4"><i class="fas fa-boxes me-2"></i>POS Stock Settings</h2>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Stock Management Settings</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Low Stock Alert Threshold</label>
                            <input type="number" class="form-control" value="10" placeholder="Enter quantity">
                            <small class="text-muted">Alert when stock falls below this quantity</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Out of Stock Threshold</label>
                            <input type="number" class="form-control" value="0" placeholder="Enter quantity">
                            <small class="text-muted">Consider item out of stock at this quantity</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Allow Negative Stock</label>
                            <select class="form-select">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                            <small class="text-muted">Allow selling when stock is 0 or negative</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Auto Update Stock</label>
                            <select class="form-select">
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            <small class="text-muted">Automatically update stock after POS sales</small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="stockNotif" checked>
                                <label class="form-check-label" for="stockNotif">
                                    Enable low stock email notifications
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="barcodeReq" checked>
                                <label class="form-check-label" for="barcodeReq">
                                    Require barcode for POS transactions
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Settings
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
