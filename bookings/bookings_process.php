<?php
include('../includes/config.php');
session_start();
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

$user_id     = $_SESSION['user_id'];
$room_id     = (int) $_POST['room_id'];
$check_in    = $_POST['check_in'];
$check_out   = $_POST['check_out'];
$num_rooms   = (int) ($_POST['num_rooms'] ?? 1);

if (!$room_id || !$check_in || !$check_out || $num_rooms < 1) {
    die("Invalid booking data");
}

$stmt = $conn->prepare("
    SELECT room_type, price_per_night 
    FROM rooms 
    WHERE room_id = ? AND status = 'Available'
");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$roomData = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$roomData) {
    die("Room not available");
}

$room_type = $roomData['room_type'];
$price     = $roomData['price_per_night'];

$stmt = $conn->prepare("
    SELECT room_id 
    FROM rooms 
    WHERE room_type = ?
    AND status = 'Available'
    AND room_id NOT IN (
        SELECT room_id FROM bookings
        WHERE status IN ('Confirmed','Pending')
        AND check_in < ?
        AND check_out > ?
    )
    LIMIT ?
");
$stmt->bind_param("sssi", $room_type, $check_out, $check_in, $num_rooms);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows < $num_rooms) {
    die("Not enough rooms available");
}

$nights = (strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24);
$total_price = $nights * $price;

$conn->begin_transaction();

try {
    while ($r = $result->fetch_assoc()) {
        $insert = $conn->prepare("
            INSERT INTO bookings 
            (user_id, room_id, check_in, check_out, total_price, status)
            VALUES (?, ?, ?, ?, ?, 'Confirmed')
        ");
        $insert->bind_param(
            "iissd",
            $user_id,
            $r['room_id'],
            $check_in,
            $check_out,
            $total_price
        );
        $insert->execute();
    }

    $conn->commit();
    header("Location: ../user/my_bookings.php");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    die("Booking failed. Try again.");
}
?>