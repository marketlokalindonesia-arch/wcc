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
    <title>POS Outlet - WC Clone</title>
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
            <h2><i class="fas fa-store-alt me-2"></i>POS Outlet Management</h2>
            <button class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Outlet
            </button>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded p-3 me-3">
                                <i class="fas fa-store fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Main Store</h5>
                                <small class="text-muted">Jl. Sudirman No. 123, Jakarta</small>
                            </div>
                        </div>
                        <div class="row text-center mt-3">
                            <div class="col-4">
                                <p class="text-muted mb-0">Active Cashiers</p>
                                <h4>3</h4>
                            </div>
                            <div class="col-4">
                                <p class="text-muted mb-0">Today's Sales</p>
                                <h4>52</h4>
                            </div>
                            <div class="col-4">
                                <p class="text-muted mb-0">Revenue</p>
                                <h4>12.5M</h4>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-outline-primary btn-sm me-2"><i class="fas fa-edit"></i> Edit</button>
                            <button class="btn btn-outline-info btn-sm"><i class="fas fa-eye"></i> View Details</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success text-white rounded p-3 me-3">
                                <i class="fas fa-store fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Branch Store</h5>
                                <small class="text-muted">Jl. Thamrin No. 456, Jakarta</small>
                            </div>
                        </div>
                        <div class="row text-center mt-3">
                            <div class="col-4">
                                <p class="text-muted mb-0">Active Cashiers</p>
                                <h4>2</h4>
                            </div>
                            <div class="col-4">
                                <p class="text-muted mb-0">Today's Sales</p>
                                <h4>38</h4>
                            </div>
                            <div class="col-4">
                                <p class="text-muted mb-0">Revenue</p>
                                <h4>8.2M</h4>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-outline-primary btn-sm me-2"><i class="fas fa-edit"></i> Edit</button>
                            <button class="btn btn-outline-info btn-sm"><i class="fas fa-eye"></i> View Details</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
