<?php 
// Corrected paths for includes using the project root variable
$PROJECT_ROOT = '/Hotel%20Management%20system';
require('../includes/header.php'); 

// Check if user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: {$PROJECT_ROOT}/user/dashboard.php");
    exit;
}

// Placeholder for error message display
$error_message = '';
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); 
}
?>

<?php if (isset($_GET['reset_success'])): ?>
    <div class="alert success">Password reset successfully! Please login.</div>
<?php endif; ?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h2>Welcome Back</h2>
            <p>Sign in to manage your bookings and profile.</p>
        </div>

        <?php if ($error_message): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="<?= $PROJECT_ROOT ?>/bookings/auth_process.php" method="POST">
            <input type="hidden" name="action" value="login">

            <div class="form-group form-control-icon">
                <i class="fas fa-envelope"></i>
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="your.email@example.com" required>
            </div>

            <div class="form-group form-control-icon">
                <i class="fas fa-lock"></i>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                <a href="auth/forgot_password.php" class="forgot-password-link">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary ">
                LOGIN
            </button>
        </form>

        <div class="auth-footer">
            <p>
                Don't have an account? 
                <a href="register.php">Create one now.</a>
            </p>
        </div>
    </div>
</div>
<?php include('../includes/footer.php'); ?>