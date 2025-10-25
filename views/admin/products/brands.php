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
    <title>Product Brands - WC Clone</title>
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
            <h2><i class="fas fa-copyright me-2"></i>Product Brands</h2>
            <button class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Brand
            </button>
        </div>

        <div class="row">
            <?php 
            $brands = [
                ['name' => 'Apple', 'products' => 15, 'logo' => 'fab fa-apple'],
                ['name' => 'Samsung', 'products' => 23, 'logo' => 'fas fa-mobile'],
                ['name' => 'Sony', 'products' => 12, 'logo' => 'fas fa-headphones'],
                ['name' => 'Nike', 'products' => 18, 'logo' => 'fas fa-shoe-prints'],
                ['name' => 'Adidas', 'products' => 20, 'logo' => 'fas fa-tshirt'],
                ['name' => 'LG', 'products' => 9, 'logo' => 'fas fa-tv'],
            ];
            foreach($brands as $brand): 
            ?>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="<?php echo $brand['logo']; ?> fa-3x text-primary"></i>
                        </div>
                        <h5><?php echo $brand['name']; ?></h5>
                        <p class="text-muted mb-3"><?php echo $brand['products']; ?> products</p>
                        <button class="btn btn-outline-primary btn-sm me-2"><i class="fas fa-edit"></i> Edit</button>
                        <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
