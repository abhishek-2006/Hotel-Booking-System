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

$message = '';
$message_class = '';

if (isset($_POST['update_profile'])) {

    $name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);

    if ($name === '' || $phone === '') {
        $message = "All fields are required.";
        $message_class = "alert-danger";
    } else {
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ? WHERE user_id = ?");
        $stmt->bind_param("ssi", $name, $phone, $user_id);

        if ($stmt->execute()) {
            $_SESSION['full_name'] = $name;
            $message = "Profile updated successfully!";
            $message_class = "alert-success";
        } else {
            $message = "Error updating profile.";
            $message_class = "alert-danger";
        }
        $stmt->close();
    }
}

$stmt = $conn->prepare("SELECT full_name, email, phone FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<div class="container profile-container">
    <h2 class="page-title">Edit Profile</h2>

    <?php if ($message): ?>
        <div class="alert <?= $message_class; ?>"><?= $message; ?></div>
    <?php endif; ?>

    <form method="POST" class="profile-form card">
        <label>Full Name</label>
        <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']); ?>" required>

        <label>Email (read-only)</label>
        <input type="email" value="<?= htmlspecialchars($user['email']); ?>" readonly>

        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']); ?>" required>

        <button type="submit" name="update_profile" class="btn btn-primary mt-3">Save Changes</button>
    </form>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "{$PROJECT_ROOT}/includes/footer.php"); ?>
