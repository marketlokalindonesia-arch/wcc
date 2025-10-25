<!-- views/orders/list.php -->
<?php
require_once '../../config/database.php';
require_once '../../models/Order.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: /wc-clone/login.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);
$user_id = $_SESSION['user_id'];

// Get orders
$orders = $order->getCustomerOrders($user_id, 10);

$page_title = "Order History - WC Clone";
$current_page = 'orders';
?>

<?php include '../partials/header.php'; ?>

<div class="main-content">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Order History</h1>
            <ul class="breadcrumb">
                <li><a href="/wc-clone">Home</a></li>
                <li><a href="/wc-clone/profile.php">My Account</a></li>
                <li>Order History</li>
            </ul>
        </div>

        <div class="account-layout">
            <?php include '../user/account-sidebar.php'; ?>

            <main class="account-main">
                <div class="account-card">
                    <div class="card-header">
                        <h2>My Orders</h2>
                        <p>View and track your orders</p>
                    </div>

                    <?php if(empty($orders)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <h3>No orders yet</h3>
                            <p>You haven't placed any orders yet. Start shopping to see your order history here.</p>
                            <a href="/wc-clone/products.php" class="btn btn-primary">Start Shopping</a>
                        </div>
                    <?php else: ?>
                        <div class="orders-list">
                            <?php foreach($orders as $order): ?>
                                <div class="order-card">
                                    <div class="order-header">
                                        <div class="order-info">
                                            <h3>Order #<?php echo $order['order_number']; ?></h3>
                                            <div class="order-meta">
                                                <span class="order-date">
                                                    <i class="fas fa-calendar"></i>
                                                    <?php echo date('F j, Y', strtotime($order['created_at'])); ?>
                                                </span>
                                                <span class="order-status status-<?php echo $order['status']; ?>">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                                <span class="order-total">
                                                    $<?php echo number_format($order['total_amount'], 2); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="order-actions">
                                            <a href="/wc-clone/order-detail.php?id=<?php echo $order['id']; ?>" class="btn btn-outline btn-sm">
                                                View Details
                                            </a>
                                            <?php if($order['status'] == 'pending' || $order['status'] == 'processing'): ?>
                                                <button class="btn btn-text btn-sm">Cancel Order</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="order-items-preview">
                                        <?php
                                        $order_items = $order->getOrderItems($order['id']);
                                        $preview_items = array_slice($order_items, 0, 3);
                                        ?>
                                        <?php foreach($preview_items as $item): ?>
                                            <div class="order-item-preview">
                                                <img src="/wc-clone/assets/images/placeholder.jpg" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                                <span class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></span>
                                                <span class="item-quantity">Qty: <?php echo $item['quantity']; ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                        
                                        <?php if(count($order_items) > 3): ?>
                                            <div class="more-items">
                                                +<?php echo count($order_items) - 3; ?> more items
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="pagination">
                            <a href="#" class="page-link disabled">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                            <a href="#" class="page-link active">1</a>
                            <a href="#" class="page-link">2</a>
                            <a href="#" class="page-link">3</a>
                            <a href="#" class="page-link">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>