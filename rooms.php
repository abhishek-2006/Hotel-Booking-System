<?php
$PROJECT_ROOT = '/Hotel%20Management%20system';
include('includes/header.php');
error_reporting(E_ALL);

// 1. INPUT (POST ONLY)
$check_in  = $_POST['check_in']  ?? date('Y-m-d');
$check_out = $_POST['check_out'] ?? date('Y-m-d', strtotime('+1 day'));
$guests    = max(1, (int)($_POST['guests'] ?? 1));
$rooms_req = max(1, (int)($_POST['rooms'] ?? 1));

$check_in  = mysqli_real_escape_string($conn, $check_in);
$check_out = mysqli_real_escape_string($conn, $check_out);

// 2. MAIN QUERY
$query_sql = "
SELECT 
    r.*,
    COALESCE(SUM(b.rooms_booked), 0) AS booked_rooms,
    (
        SELECT COALESCE(SUM(bb.rooms_booked),0)
        FROM bookings bb
        WHERE bb.room_id = r.room_id
        AND bb.status IN ('Confirmed','Completed')
    ) AS total_booked_all_time
FROM rooms r
LEFT JOIN bookings b 
    ON b.room_id = r.room_id
    AND b.status IN ('Confirmed','Pending')
    AND b.check_in < '$check_out'
    AND b.check_out > '$check_in'
WHERE r.capacity >= $guests
  AND r.status = 'Available'
GROUP BY r.room_id
HAVING (r.total_rooms - booked_rooms) >= $rooms_req
ORDER BY total_booked_all_time DESC, r.price_per_night ASC
";

$result = mysqli_query($conn, $query_sql);

if (!$result) {
    error_log(mysqli_error($conn));
}
?>

<div class="container rooms-page-container">

    <h2 class="page-title text-center">
        Available Rooms
        <span class="date-range-text">
            <?= date('M d, Y', strtotime($check_in)); ?> – <?= date('M d, Y', strtotime($check_out)); ?>
        </span>
    </h2>

    <!-- SEARCH BAR -->
    <form class="search-widget modern-search-widget" action="rooms.php" method="POST">
        <div class="search-grid">

            <div class="form-group">
                <label>Check-in</label>
                <input type="date" name="check_in" value="<?= $check_in ?>" required>
            </div>

            <div class="form-group">
                <label>Check-out</label>
                <input type="date" name="check_out" value="<?= $check_out ?>" required>
            </div>

            <div class="form-group">
                <label>Guests</label>
                <input type="number" name="guests" value="<?= $guests ?>" min="1" required>
            </div>

            <div class="form-group room-count-group">
                <label for="rooms">Rooms</label>
                <select name="rooms" required>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>" <?= $i == $rooms_req ? 'selected' : '' ?>>
                            <?= $i ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-action">
                <i class="fas fa-search"></i> Check Availability
            </button>

        </div>
    </form>

    <!-- ROOMS GRID -->
    <div class="rooms-grid">

        <?php if (mysqli_num_rows($result) > 0): 
            while ($row = mysqli_fetch_assoc($result)):

                $available_rooms = $row['total_rooms'] - $row['booked_rooms'];
                $isMostBooked = $row['total_booked_all_time'] >= 10;
        ?>

        <div class="room-card card fade-in-card" data-delay="<?= $room_count * 0.1; ?>">

            <div class="room-image-wrapper">
                <img 
                    src="<?= $PROJECT_ROOT ?>/assets/images/rooms/<?= htmlspecialchars($row['image']); ?>" 
                    alt="<?= htmlspecialchars($row['room_type']); ?> Room Image" class="room-image">

                <span class="room-status">
                    <?= $available_rooms ?> rooms left
                </span>

                <?php if ($isMostBooked): ?>
                    <span class="room-badge badge-popular">🔥 Most Booked</span>
                <?php endif; ?>
            </div>

            <div class="room-details">

                <h3><?= htmlspecialchars($row['room_type']); ?> Room</h3>

                <ul class="room-specs">
                    <li><i class="fas fa-users"></i> Max Guests: <strong><?= $row['capacity']; ?></strong></li>
                    <li><i class="fas fa-door-open"></i> Total Rooms: <strong><?= $row['total_rooms']; ?></strong></li>
                </ul>

                <div class="price-box fixed-price">
                    <span class="price-label">Price per Night</span>
                    <div class="price-row">
                        <span class="currency">₹</span>
                        <span class="price-amount"><?= number_format($row['price_per_night']); ?></span>
                        <span class="price-unit">/ room</span>
                    </div>
                </div>

                <form action="<?= $PROJECT_ROOT ?>/user/book_room.php" method="POST">
                    <input type="hidden" name="room_id" value="<?= $row['room_id']; ?>">
                    <input type="hidden" name="check_in" value="<?= $check_in; ?>">
                    <input type="hidden" name="check_out" value="<?= $check_out; ?>">
                    <input type="hidden" name="guests" value="<?= $guests; ?>">
                    <input type="hidden" name="rooms" value="<?= $rooms_req; ?>">

                    <button type="submit" class="btn btn-action btn-full-width">
                        Book <?= $rooms_req ?> Room<?= $rooms_req > 1 ? 's' : '' ?>
                    </button>
                </form>

            </div>
        </div>

        <?php endwhile; else: ?>

            <p class="text-center empty-state">
                No rooms available for the selected dates and requirements.
            </p>

        <?php endif; ?>

    </div>
</div>

<?php include('includes/footer.php'); ?>