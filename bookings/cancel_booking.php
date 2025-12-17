<?php
session_start();

$PROJECT_ROOT = '/Hotel Management system';
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/config.php');

// 1. Auth check
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please log in first.";
    header("Location: {$PROJECT_ROOT}/auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$booking_id = intval($_GET['id'] ?? 0);

// 2. Basic validation
if ($booking_id <= 0) {
    $_SESSION['error_message'] = "Invalid cancel request.";
    header("Location: {$PROJECT_ROOT}/user/dashboard.php");
    exit;
}

// 3. Check booking ownership + status
$stmt = $conn->prepare("
    SELECT status 
    FROM bookings 
    WHERE booking_id = ? AND user_id = ?
");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Booking not found.";
    header("Location: {$PROJECT_ROOT}/user/dashboard.php");
    exit;
}

$booking = $result->fetch_assoc();

// 4. Prevent cancelling completed / already cancelled
if (in_array($booking['status'], ['Cancelled', 'Completed'])) {
    $_SESSION['error_message'] = "This booking cannot be cancelled.";
    header("Location: {$PROJECT_ROOT}/user/dashboard.php");
    exit;
}

// 5. Cancel booking
$update = $conn->prepare("
    UPDATE bookings 
    SET status = 'Cancelled' 
    WHERE booking_id = ?
");
$update->bind_param("i", $booking_id);

if ($update->execute()) {
    $_SESSION['success_message'] = "Booking #{$booking_id} has been cancelled successfully.";
} else {
    $_SESSION['error_message'] = "Failed to cancel booking. Please try again.";
}

// 6. Redirect back
header("Location: {$PROJECT_ROOT}/user/dashboard.php");
exit;
?>