<!-- views/auth/login.php -->
<?php
require_once '../../config/database.php';
require_once '../../models/User.php';

// Redirect jika sudah login
if(isset($_SESSION['user_id'])) {
    header('Location: /wc-clone');
    exit;
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$error = '';
$success = '';

// Handle form submission
if($_POST) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if(empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        if($user->login($email, $password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_name'] = $user->first_name . ' ' . $user->last_name;
            $_SESSION['user_role'] = $user->role;
            
            // Redirect based on role
            if($user->role == 'admin' || $user->role == 'vendor') {
                header('Location: /wc-clone/admin/dashboard.php');
            } else {
                header('Location: /wc-clone');
            }
            exit;
        } else {
            $error = 'Invalid email or password';
        }
    }
}

$page_title = "Login - WC Clone";
$current_page = 'login';
?>

<?php include '../partials/header.php'; ?>

<div class="main-content">
    <div class="container">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <h1>Welcome Back</h1>
                    <p>Sign in to your account to continue shopping</p>
                </div>

                <?php if($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?php echo $_POST['email'] ?? ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-options">
                        <label class="checkbox">
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                            Remember me
                        </label>
                        <a href="/wc-clone/forgot-password.php" class="forgot-link">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block">Sign In</button>
                </form>

                <div class="auth-footer">
                    <p>Don't have an account? <a href="/wc-clone/register.php" class="auth-link">Sign up here</a></p>
                </div>

                <div class="auth-divider">
                    <span>Or continue with</span>
                </div>

                <div class="social-auth">
                    <button type="button" class="btn btn-social btn-google">
                        <i class="fab fa-google"></i>
                        Google
                    </button>
                    <button type="button" class="btn btn-social btn-facebook">
                        <i class="fab fa-facebook-f"></i>
                        Facebook
                    </button>
                </div>
            </div>

            <div class="auth-features">
                <div class="feature">
                    <i class="fas fa-shipping-fast"></i>
                    <h3>Fast Shipping</h3>
                    <p>Free shipping on orders over $50</p>
                </div>
                <div class="feature">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Secure Shopping</h3>
                    <p>Your data is always protected</p>
                </div>
                <div class="feature">
                    <i class="fas fa-headset"></i>
                    <h3>24/7 Support</h3>
                    <p>We're here to help you</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>