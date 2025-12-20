<?php
include('../includes/header.php');
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "You must be logged in to book a room.";
    header('Location: ../auth/login.php');
    exit;
}

$room_id = intval($_POST['room_id'] ?? 0);
if (!isset($_POST['room_id']) || empty($_POST['room_id'])) {
    $_SESSION['error_message'] = "No room selected for booking.";
    header('Location: ../rooms.php');
    exit();
}

$check_in_date = $_POST['check_in'] ?? '';
$check_out_date = $_POST['check_out'] ?? '';
$guests = intval($_POST['guests'] ?? 1);

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
    <div class="room-image-wrapper compact-image">
        <img 
            src="../assets/images/rooms/<?= htmlspecialchars($room['image']); ?>" 
            alt="<?= htmlspecialchars($room['room_type']); ?> Room"
            class="room-image"
        >
        <span class="room-status room-status-<?= $isAvailable ? 'available' : 'booked'; ?>">
            <?= $availabilityText; ?>
        </span>
    </div>

        <div class="room-details sticky-booking-card">
            <h3><?= htmlspecialchars($room['room_type']); ?> Room</h3>

            <!-- Badges -->
            <div class="room-badges">
                <span class="badge badge-success">Free Cancellation</span>
                <span class="badge badge-info">No Advance Payment</span>
            </div>

            <ul class="room-specs">
                <li><i class="fas fa-fan"></i> Type: <strong><?= htmlspecialchars($room['ac_type']); ?></strong></li>
                <li><i class="fas fa-users"></i> Max Guests: <strong><?= htmlspecialchars($room['capacity'] ?? 'N/A'); ?></strong></li>
            </ul>

            <div class="price-box">
                <span class="price-label">Price per Night</span>
                <div class="price-row">
                    <span class="currency">₹</span>
                    <span class="price-amount"><?= number_format($room['price_per_night']); ?></span>
                    <span class="price-unit">/ night</span>
                </div>
            </div>

            <!-- Booking Form -->
            <form action="../bookings/bookings_process.php" method="POST" class="booking-form modern-form">
                <input type="hidden" name="room_id" value="<?= $room['room_id']; ?>">

                <div class="date-grid">
                    <div class="form-group">
                        <label for="check_in">Check-In</label>
                        <input type="date" id="check_in" name="check_in" required>
                    </div>

                    <div class="form-group">
                        <label for="check_out">Check-Out</label>
                        <input type="date" id="check_out" name="check_out" required>
                    </div>

                    <div class="form-group rooms-count-group">
                        <label for="num_rooms">Number of Rooms</label>
                        <input type="number" id="num_rooms" name="num_rooms" value="1" min="1" max="<?= $room['capacity']; ?>" required>
                    </div>
                </div>

                <div class="total-price-box">
                    <strong>Total Price: ₹<span id="total_price"><?= number_format($room['price_per_night']); ?></span></strong>
                </div>

                <button type="submit" class="btn btn-action btn-full-width book-btn">
                    Confirm Booking
                </button>
            </form>
        </div>
    </div>
</div>
<?php include('../includes/footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const numRoomsInput = document.getElementById('num_rooms');
    const totalPriceSpan = document.getElementById('total_price');
    const pricePerNight = <?= $room['price_per_night']; ?>;

    function calculateTotal() {
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        let nights = 0;

        if(checkIn && checkOut && checkOut > checkIn) {
            nights = Math.ceil((checkOut - checkIn) / (1000*60*60*24));
        }

        const rooms = parseInt(numRoomsInput.value) || 1;
        const total = nights * rooms * pricePerNight;

        totalPriceSpan.textContent = total.toLocaleString('en-IN', {maximumFractionDigits:2});
    }

    // Disable past dates
    const today = new Date().toISOString().split('T')[0];
    checkInInput.min = today;
    checkOutInput.min = today;

    checkInInput.addEventListener('change', () => {
        if(checkOutInput.value <= checkInInput.value){
            const nextDay = new Date(checkInInput.value);
            nextDay.setDate(nextDay.getDate() + 1);
            checkOutInput.value = nextDay.toISOString().split('T')[0];
        }
        checkOutInput.min = new Date(checkInInput.value).toISOString().split('T')[0];
        calculateTotal();
    });

    checkOutInput.addEventListener('change', calculateTotal);
    numRoomsInput.addEventListener('input', calculateTotal);

    calculateTotal();
});
</script>