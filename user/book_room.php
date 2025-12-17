<?php
include('../includes/header.php');
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "You must be logged in to book a room.";
    header('Location: ../auth/login.php');
    exit;
}

$room_id = intval($_GET['room_id'] ?? 0);
if (!isset($_GET['room_id']) || empty($_GET['room_id'])) {
    $_SESSION['error_message'] = "No room selected for booking.";
    header('Location: ../rooms.php');
    exit();
}

$check_in_date = $_GET['check_in'] ?? '';
$check_out_date = $_GET['check_out'] ?? '';
$guests = intval($_GET['guests'] ?? 1);

$stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error_message'] = "Invalid room selected. Please go back and select a valid room.";
    header('Location: ' . $PROJECT_ROOT . '/rooms.php');
    exit();
}
$room = $result->fetch_assoc();
$stmt->close();

$isAvailable = ($room['status'] === 'Available'); // Check ENUM status
$availabilityText = $room['status'];

$date_check_passed = (!empty($check_in_date) && !empty($check_out_date));
$can_book = $isAvailable && $date_check_passed;
?>
<div class="container book-room-container">
    <h2 class="page-title text-center">Book Your Room</h2>
    <div class="room-card card <?= !$isAvailable ? 'card-booked' : ''; ?>">
        <div class="room-image-wrapper">
            <img src="../assets/images/rooms/<?= htmlspecialchars($room['image']); ?>" 
                alt="<?= htmlspecialchars($room['room_type']); ?> Room Image" 
                class="room-image">
            <span class="room-status room-status-<?= $isAvailable ? 'available' : 'booked'; ?>">
                <?= $availabilityText; ?>
            </span>
        </div>
        <div class="room-details">
            <h3><?= htmlspecialchars($room['room_type']); ?> Room</h3>
            <ul class="room-specs">
                <li><i class="fas fa-fan"></i> Type: <strong><?= htmlspecialchars($room['ac_type']); ?></strong></li>
                <li><i class="fas fa-users"></i> Max Guests: <strong><?= htmlspecialchars($room['capacity'] ?? 'N/A'); ?></strong></li>
            </ul>
            <p class="room-price">
                <span>Price:</span> 
                <strong>₹<?= number_format($room['price_per_night']); ?></strong> / night
            </p>
            <?php if ($isAvailable): ?>
            <form action="../bookings/bookings_process.php" method="POST" class="booking-form">
                <input type="hidden" name="action" value="book_room">
                <input type="hidden" name="room_id" value="<?= $room['room_id']; ?>">
                <div class="form-group">
                    <label for="check_in">Check-In Date:</label>
                    <input type="date" id="check_in" name="check_in" required>
                </div>
                <div class="form-group">
                    <label for="check_out">Check-Out Date:</label>
                    <input type="date" id="check_out" name="check_out" required>
                </div>
                <button type="submit" class="btn btn-action btn-full-width">Confirm Booking</button>
            </form>
            <?php endif; ?>
            <?php if (!$isAvailable): ?>
            <p class="text-center text-muted">This room is currently booked. Please choose another room.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
include('../includes/footer.php');
?>