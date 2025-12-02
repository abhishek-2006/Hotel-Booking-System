<?php
include('../includes/config.php');
include('../includes/header.php');
error_reporting(E_ALL);
if (!isset($_GET['room_id']) || empty($_GET['room_id'])) {
    echo "<script>alert('No room selected. Please go back and select a room.'); window.location.href='rooms.php';</script>";
    exit();
}
$room_id = intval($_GET['room_id']);
$query = mysqli_query($conn, "SELECT * FROM rooms WHERE room_id = $room_id");
if (mysqli_num_rows($query) == 0) {
    echo "<script>alert('Invalid room selected. Please go back and select a valid room.'); window.location.href='rooms.php';</script>";
    exit();
}
$room = mysqli_fetch_assoc($query);
$isAvailable = $room['status'];
$availabilityText = $isAvailable ? 'Available' : 'Booked';
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
            <form action="confirm_booking.php" method="POST" class="booking-form">
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