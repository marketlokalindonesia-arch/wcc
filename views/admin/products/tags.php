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
    <title>Product Tags - WC Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .tag-item {
            display: inline-block;
            margin: 5px;
            padding: 8px 15px;
            background: #667eea;
            color: white;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .tag-item:hover {
            background: #764ba2;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../partials/admin_sidebar.php'; ?>
    
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-tags me-2"></i>Product Tags</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTagModal">
                <i class="fas fa-plus me-2"></i>Add Tag
            </button>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">All Tags</h5>
                    </div>
                    <div class="card-body">
                        <?php 
                        $tags = ['New Arrival', 'Best Seller', 'Sale', 'Featured', 'Limited Edition', 
                                 'Premium', 'Popular', 'Trending', 'Hot Deal', 'Clearance'];
                        foreach($tags as $tag): 
                        ?>
                            <span class="tag-item">
                                <?php echo $tag; ?>
                                <i class="fas fa-times ms-2"></i>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tag Name</th>
                                        <th>Slug</th>
                                        <th>Count</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($tags as $tag): ?>
                                    <tr>
                                        <td><strong><?php echo $tag; ?></strong></td>
                                        <td><code><?php echo strtolower(str_replace(' ', '-', $tag)); ?></code></td>
                                        <td><span class="badge bg-primary"><?php echo rand(5, 50); ?></span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Add New Tag</h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label class="form-label">Tag Name</label>
                                <input type="text" class="form-control" placeholder="e.g. New Arrival">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" class="form-control" placeholder="new-arrival">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Add Tag</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
