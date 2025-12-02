<?php
session_start();
include('../includes/config.php');
include('../includes/header.php');
error_reporting(E_ALL);

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please log in first.";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$msg = "";

//  Reset Password Logic
if (isset($_POST['reset_password'])) {

    $current  = trim($_POST['current_password']);
    $new      = trim($_POST['new_password']);
    $confirm  = trim($_POST['confirm_password']);

    // Fetch user's stored password
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    // Validate current password
    if (!password_verify($current, $db_password)) {
        $msg = "<div class='alert error'>Current password is incorrect.</div>";
    }
    else if ($new !== $confirm) {
        $msg = "<div class='alert error'>New passwords do not match.</div>";
    }
    else if (strlen($new) < 6) {
        $msg = "<div class='alert error'>Password must be at least 6 characters.</div>";
    }
    else {

        // Update password
        $update = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $update->bind_param("si", $new, $user_id);

        if ($update->execute()) {
            session_destroy(); // logout after reset
            header("Location: login.php?reset=success");
            exit;
        } else {
            $msg = "<div class='alert error'>Something went wrong. Try again.</div>";
        }
    }
}
?>

<?php if ($msg) echo $msg; ?>

<div class="reset-wrapper">
    <div class="reset-card">
        <h2>Reset Password</h2>
        <p class="subtitle">Update your password to keep your account secure.</p>

        <form method="POST" action="reset_password.php" class="reset-password-form">

            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" required>
            </div>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" required>
            </div>

            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" required>
            </div>

            <button type="submit" name="reset_password" class="btn-primary reset-btn">
                Reset Password
            </button>

        </form>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/Hotel Management system/includes/footer.php'); ?>
