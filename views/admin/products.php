<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

requireRole('admin');

$database = new Database();
$db = $database->getConnection();

$query = "SELECT p.*, 
          (SELECT image_url FROM product_images WHERE product_id = p.id AND is_featured = true LIMIT 1) as image,
          (SELECT COUNT(*) FROM order_items WHERE product_id = p.id) as total_sales
          FROM products p 
          ORDER BY p.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT COUNT(*) as total FROM products";
$stmt = $db->prepare($query);
$stmt->execute();
$totalProducts = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$query = "SELECT COUNT(*) as total FROM products WHERE status = 'publish'";
$stmt = $db->prepare($query);
$stmt->execute();
$publishedProducts = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$query = "SELECT COUNT(*) as total FROM products WHERE stock_quantity < 10";
$stmt = $db->prepare($query);
$stmt->execute();
$lowStockProducts = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$user = getUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - WC Clone</title>
    
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
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-box me-2"></i>Products</h2>
            <a href="/?url=admin/products/add" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Product
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="value text-primary"><?php echo number_format($totalProducts); ?></div>
                    <div class="label"><i class="fas fa-box me-1"></i>Total Products</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="value text-success"><?php echo number_format($publishedProducts); ?></div>
                    <div class="label"><i class="fas fa-check-circle me-1"></i>Published</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="value text-warning"><?php echo number_format($lowStockProducts); ?></div>
                    <div class="label"><i class="fas fa-exclamation-triangle me-1"></i>Low Stock</div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card card-custom p-3">
                    <div class="d-flex gap-2">
                        <a href="/?url=admin/products/categories" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-folder me-1"></i>Categories
                        </a>
                        <a href="/?url=admin/products/brands" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-copyright me-1"></i>Brands
                        </a>
                        <a href="/?url=admin/products/tags" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-tags me-1"></i>Tags
                        </a>
                        <a href="/?url=admin/products/attributes" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-sliders-h me-1"></i>Attributes
                        </a>
                        <a href="/?url=admin/products/reviews" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-star me-1"></i>Reviews
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-list me-2"></i>All Products</h5>
                        <div class="table-responsive">
                            <table id="productsTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>SKU</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Sales</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td>
                                                <?php if ($product['image']): ?>
                                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                                         class="product-image">
                                                <?php else: ?>
                                                    <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td><strong><?php echo htmlspecialchars($product['name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?></td>
                                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                                            <td>
                                                <span class="badge <?php echo $product['stock_quantity'] < 10 ? 'bg-warning' : 'bg-success'; ?>">
                                                    <?php echo number_format($product['stock_quantity']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-status <?php echo $product['status'] == 'publish' ? 'bg-success' : 'bg-secondary'; ?>">
                                                    <?php echo ucfirst(htmlspecialchars($product['status'])); ?>
                                                </span>
                                            </td>
                                            <td><?php echo number_format($product['total_sales']); ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
            $('#productsTable').DataTable({
                order: [[1, 'asc']],
                pageLength: 25
            });
        });
    </script>
</body>
</html>
