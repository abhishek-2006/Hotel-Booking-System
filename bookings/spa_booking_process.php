<?php
session_start();
include('../includes/config.php');

if (!isset($_POST['booking_id'], $_POST['spa_service_id'], $_POST['spa_date'], $_POST['spa_time'])) {
    die("Invalid request");
}

$user_id = $_SESSION['id'];
$booking_id = $_POST['booking_id'];
$service_id = $_POST['spa_service_id'];
$spa_date = $_POST['spa_date'];
$spa_time = $_POST['spa_time'];

// Validate date inside stay range
$check = $dbh->prepare("
    SELECT * FROM bookings 
    WHERE booking_id = :bid 
      AND user_id = :uid
      AND :d BETWEEN check_in_date AND check_out_date
");
$check->execute(['bid' => $booking_id, 'uid' => $user_id, 'd' => $spa_date]);

if ($check->rowCount() == 0) {
    die("<h3>❌ Invalid date — not within your stay!</h3>");
}

// Insert spa booking
$stmt = $dbh->prepare("
    INSERT INTO spa_booking (user_id, booking_id, spa_service_id, spa_date, spa_time, status)
    VALUES (:uid, :bid, :sid, :d, :t, 'Pending')
");

$stmt->execute([
    'uid' => $user_id,
    'bid' => $booking_id,
    'sid' => $service_id,
    'd' => $spa_date,
    't' => $spa_time
]);

echo "<h2>✔ Spa Booking Confirmed!</h2>";
echo "<a href='../user/dashboard.php'>Go Back to Dashboard</a>";
?>
