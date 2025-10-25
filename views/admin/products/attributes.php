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
    <title>Product Attributes - WC Clone</title>
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
            <h2><i class="fas fa-sliders-h me-2"></i>Product Attributes</h2>
            <button class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Attribute
            </button>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Attribute</th>
                                <th>Type</th>
                                <th>Terms</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Color</strong></td>
                                <td><span class="badge bg-info">Select</span></td>
                                <td>
                                    <span class="badge bg-secondary me-1">Red</span>
                                    <span class="badge bg-secondary me-1">Blue</span>
                                    <span class="badge bg-secondary me-1">Green</span>
                                    <span class="badge bg-secondary me-1">Black</span>
                                    <span class="badge bg-secondary me-1">White</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i> Configure</button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Size</strong></td>
                                <td><span class="badge bg-info">Select</span></td>
                                <td>
                                    <span class="badge bg-secondary me-1">XS</span>
                                    <span class="badge bg-secondary me-1">S</span>
                                    <span class="badge bg-secondary me-1">M</span>
                                    <span class="badge bg-secondary me-1">L</span>
                                    <span class="badge bg-secondary me-1">XL</span>
                                    <span class="badge bg-secondary me-1">XXL</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i> Configure</button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Material</strong></td>
                                <td><span class="badge bg-info">Select</span></td>
                                <td>
                                    <span class="badge bg-secondary me-1">Cotton</span>
                                    <span class="badge bg-secondary me-1">Polyester</span>
                                    <span class="badge bg-secondary me-1">Leather</span>
                                    <span class="badge bg-secondary me-1">Wool</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i> Configure</button>
                                </td>
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
