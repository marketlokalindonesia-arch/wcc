<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WC Clone - E-Commerce POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .category-card {
            transition: transform 0.3s;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-shopping-cart"></i> WC Clone
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/?url=products">Products</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/?url=admin/dashboard">
                                    <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                                </a>
                            </li>
                        <?php elseif (isCashier()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/?url=cashier/dashboard">
                                    <i class="fas fa-cash-register"></i> Kasir Dashboard
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/?url=logout">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/?url=login">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-3 fw-bold mb-4">Welcome to WC Clone</h1>
            <p class="lead mb-4">Sistem E-Commerce & POS Modern untuk Bisnis Anda</p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="/?url=login" class="btn btn-light btn-lg">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="/?url=products" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-shopping-bag"></i> Browse Products
                </a>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Fitur Unggulan</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 category-card border-0 shadow">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-tachometer-alt fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title">Dashboard Admin Lengkap</h5>
                            <p class="card-text">
                                Statistik real-time, grafik penjualan, manajemen produk & order
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 category-card border-0 shadow">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-cash-register fa-3x text-success"></i>
                            </div>
                            <h5 class="card-title">Sistem POS Modern</h5>
                            <p class="card-text">
                                Point of Sale dengan barcode scanner, quick payment, dan shift management
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 category-card border-0 shadow">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-chart-line fa-3x text-info"></i>
                            </div>
                            <h5 class="card-title">Laporan & Analytics</h5>
                            <p class="card-text">
                                Laporan penjualan harian/bulanan, inventory tracking, dan export data
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 category-card border-0 shadow">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-boxes fa-3x text-warning"></i>
                            </div>
                            <h5 class="card-title">Inventory Management</h5>
                            <p class="card-text">
                                Manajemen stok otomatis, low stock alerts, dan inventory logs
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 category-card border-0 shadow">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-users fa-3x text-danger"></i>
                            </div>
                            <h5 class="card-title">Multi-Role Access</h5>
                            <p class="card-text">
                                Role-based access control: Admin, Kasir, dan Customer
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 category-card border-0 shadow">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-mobile-alt fa-3x text-purple"></i>
                            </div>
                            <h5 class="card-title">Responsive Design</h5>
                            <p class="card-text">
                                UI responsif untuk desktop, tablet, dan mobile devices
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h3 class="mb-4">Siap untuk Memulai?</h3>
            <p class="lead mb-4">Login sekarang untuk mengakses dashboard</p>
            <a href="/?url=login" class="btn btn-primary btn-lg">
                <i class="fas fa-sign-in-alt"></i> Login Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 WC Clone. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
