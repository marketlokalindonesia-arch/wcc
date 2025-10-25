<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../models/Product.php';

requireRole('admin');

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

$stmt = $product->read([]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($products as &$prod) {
    $product->id = $prod['id'];
    $images = $product->getImages();
    $prod['image'] = !empty($images) ? $images[0]['image_url'] : 'https://via.placeholder.com/100';
}

$user = getUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management - WC Clone</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        body {
            background: #f8f9fa;
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
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
        .stock-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .stock-low { background: #fee; color: #c00; }
        .stock-medium { background: #ffc; color: #860; }
        .stock-good { background: #efe; color: #060; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../partials/admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-box me-2"></i>Product Management</h2>
            <button class="btn btn-primary" onclick="window.location.href='/?url=admin/add-product'">
                <i class="fas fa-plus me-2"></i>Add New Product
            </button>
        </div>

        <div class="card card-custom p-4">
            <div class="table-responsive">
                <table id="productsTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Barcode</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($products)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-box-open fa-3x mb-3" style="opacity: 0.3;"></i>
                                    <p>No products found</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($products as $p): ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($p['image']); ?>" class="product-img" alt="Product"></td>
                                <td><?php echo htmlspecialchars($p['name']); ?></td>
                                <td><?php echo htmlspecialchars($p['sku'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($p['barcode'] ?? 'N/A'); ?></td>
                                <td>$<?php echo number_format($p['price'], 2); ?></td>
                                <td>
                                    <?php 
                                    $stock = $p['stock_quantity'];
                                    if($stock < 10) {
                                        $class = 'stock-low';
                                    } elseif($stock < 20) {
                                        $class = 'stock-medium';
                                    } else {
                                        $class = 'stock-good';
                                    }
                                    ?>
                                    <span class="stock-badge <?php echo $class; ?>">
                                        <?php echo $stock; ?> units
                                    </span>
                                    <div class="btn-group btn-group-sm mt-1">
                                        <button class="btn btn-outline-success btn-sm" onclick="adjustStock(<?php echo $p['id']; ?>, 'add')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="adjustStock(<?php echo $p['id']; ?>, 'remove')">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <?php if($p['status'] === 'publish'): ?>
                                        <span class="badge bg-success">Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" onclick="editProduct(<?php echo $p['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" onclick="deleteProduct(<?php echo $p['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
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
            $('#productsTable').DataTable({
                order: [[1, 'asc']],
                pageLength: 25
            });
        });

        function adjustStock(productId, action) {
            const qty = prompt(`Enter quantity to ${action}:`, '1');
            if(qty && !isNaN(qty) && qty > 0) {
                const notes = prompt('Notes (optional):', '');
                window.location.href = `/?url=admin/adjust-stock&product_id=${productId}&action=${action}&quantity=${qty}&notes=${encodeURIComponent(notes || '')}`;
            }
        }

        function editProduct(productId) {
            window.location.href = `/?url=admin/edit-product&id=${productId}`;
        }

        function deleteProduct(productId) {
            if(confirm('Are you sure you want to delete this product?')) {
                window.location.href = `/?url=admin/delete-product&id=${productId}`;
            }
        }
    </script>
</body>
</html>
