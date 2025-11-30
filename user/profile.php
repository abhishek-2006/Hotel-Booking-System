<?php
session_start();
$PROJECT_ROOT = '/Hotel Management system';
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/header.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please log in first.";
    header("Location: {$PROJECT_ROOT}/auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT full_name, email, phone, role, created_at FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<div class="container profile-container">
    <h2 class="page-title">Your Profile</h2>

    <div class="profile-card card">
        <p><strong>Full Name:</strong> <?= htmlspecialchars($user['full_name']); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']); ?></p>
        <p><strong>Member Since:</strong> <?= date("d M Y", strtotime($user['created_at'])); ?></p>

        <a href="<?= $PROJECT_ROOT ?>/user/update_profile.php" class="btn btn-primary mt-3">Edit Profile</a>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "{$PROJECT_ROOT}/includes/footer.php"); ?>
