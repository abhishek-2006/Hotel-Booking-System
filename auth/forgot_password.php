<?php
session_start();
include('../includes/config.php');

$msg = "";
$showPasswordFields = false;
$user_id = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Step 1: Check email & mobile
    if (isset($_POST['email']) && isset($_POST['mobile'])) {
        $email = trim($_POST['email']);
        $mobile = trim($_POST['mobile']);

        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND phone = ? LIMIT 1");
        $stmt->bind_param("ss", $email, $mobile);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id);
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            $showPasswordFields = true;
        } else {
            $msg = "<div class='alert error'>Invalid email or mobile number.</div>";
        }
    }

    // Step 2: Update password if new password fields submitted
    if (isset($_POST['new_password'], $_POST['confirm_password'], $_POST['user_id'])) {
        $user_id = (int)$_POST['user_id'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            $msg = "<div class='alert error'>Passwords do not match.</div>";
            $showPasswordFields = true;
        } else {
            $update = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $update->bind_param("si", $new_password, $user_id);
            $update->execute();

            $_SESSION['success_message'] = "Password reset successful! Please log in.";
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>

    <link rel="stylesheet" href="../assets/css/styles.css">

    <style>
        body {
            background: linear-gradient(135deg, #e9f1ff, #ffffff);
            font-family: 'Poppins', sans-serif;
        }

        .forgot-wrapper {
            min-height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .forgot-card {
            width: 100%;
            max-width: 420px;
            padding: 35px;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 18px;
            backdrop-filter: blur(16px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.12);
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .forgot-card h2 {
            text-align: center;
            color: var(--color-brand);
            font-weight: 700;
            font-size: 1.7rem;
            margin-bottom: 8px;
        }

        .forgot-card p {
            text-align: center;
            color: #555;
            font-size: 0.95rem;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 0.95rem;
            transition: 0.2s;
        }

        .form-group input:focus {
            border-color: var(--color-brand);
            box-shadow: 0 0 0 3px rgba(0,123,255,0.15);
            outline: none;
        }

        .alert {
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .alert.success {
            background: #e6ffed;
            border-left: 5px solid #28c96d;
            color: #1d5e36;
        }

        .alert.error {
            background: #ffeaea;
            border-left: 5px solid #ff5353;
            color: #a11f1f;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border-radius: 10px;
        }

        .back-link {
            margin-top: 15px;
            display: block;
            text-align: center;
            color: var(--color-brand);
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
        }

        .back-link:hover {
            opacity: 0.7;
        }
    </style>
</head>

<body>

<div class="forgot-wrapper">
    <div class="forgot-card">

        <h2>Forgot Password</h2>
        <p>Enter your registered email to check availability.</p>

        <?= $msg ?>

        <form method="POST">
            <?= $msg ?>

            <?php if (!$showPasswordFields): ?>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter your registered email" required>
                </div>
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="text" name="mobile" placeholder="Enter your mobile number" required>
                </div>
                <button type="submit" class="btn-primary">Check Email</button>
            <?php else: ?>
                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" placeholder="Enter new password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Confirm new password" required>
                </div>
                <button type="submit" class="btn-primary">Reset Password</button>
            <?php endif; ?>
        </form>

        <a href="login.php" class="back-link">← Back to Login</a>
    </div>
</div>

</body>
</html>
