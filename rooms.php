<?php 
// NOTE: This file is assumed to be in the project root.
$PROJECT_ROOT = '/Hotel%20Management%20system'; 
include('includes/header.php'); 
error_reporting(E_ALL);

// --- 1. RETRIEVE & SANITIZE SEARCH FILTERS ---
// Data comes from the Homepage (GET) or the Filter bar (POST)
$check_in = $_POST['check_in'] ?? $_GET['check_in'] ?? date('Y-m-d');
$check_out = $_POST['check_out'] ?? $_GET['check_out'] ?? date('Y-m-d', strtotime('+1 day'));
$guests = (int)($_POST['guests'] ?? $_GET['guests'] ?? 1);

// Ensure dates are safe and logical
$check_in = mysqli_real_escape_string($conn, $check_in);
$check_out = mysqli_real_escape_string($conn, $check_out);
$guests = max(1, $guests); 

// --- 2. BUILD SQL QUERY (FETCH ALL FILTERED ROOMS) ---
// This complex subquery finds rooms NOT currently booked for ANY day in the range.
$filter_query_condition = "
    r.capacity >= $guests AND
    r.room_id NOT IN (
        SELECT b.room_id
        FROM bookings b
        WHERE b.status IN ('Confirmed', 'Pending') 
        AND b.room_id IS NOT NULL 
        AND b.check_in < '$check_out' 
        AND b.check_out > '$check_in'
    ) AND r.status = 'Available'
";

$main_query = "
    SELECT r.*
    FROM rooms r
    WHERE $filter_query_condition
    ORDER BY r.price_per_night ASC
";
$query = mysqli_query($conn, $main_query);

// Check if query failed (for debugging)
if (!$query) {
    error_log("Room Query Failed: " . mysqli_error($conn));
}
?>

<div class="container rooms-page-container">
    <h2 class="page-title text-center">
        Available Rooms: 
        <span class="date-range-text"><?= date('M d, Y', strtotime($check_in)) ?> - <?= date('M d, Y', strtotime($check_out)) ?></span>
    </h2>

    <!-- Compact Search Filter Bar -->
    <form class="search-widget compact-search-widget" action="rooms.php" method="POST">
        <input type="hidden" name="action" value="search">
        <div class="form-group">
            <label for="check_in_date">Check-in</label>
            <input type="date" id="check_in_date" name="check_in" class="form-control" value="<?= $check_in ?>" required>
        </div>
        <div class="form-group">
            <label for="check_out_date">Check-out</label>
            <input type="date" id="check_out_date" name="check_out" class="form-control" value="<?= $check_out ?>" required>
        </div>
        <div class="form-group">
            <label for="num_guests">Guests</label>
            <input type="number" id="num_guests" name="guests" class="form-control" value="<?= $guests ?>" min="1" required>
        </div>
        <button type="submit" class="btn btn-primary search-btn">
            Filter Results
        </button>
    </form>
    
    <div class="rooms-grid" id="rooms-container">
        <?php
        $room_count = 0;
        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
                $isAvailable = true; 
                $availabilityText = 'Available'; 
                $room_count++;
        ?>
        
        <div class="room-card card fade-in-card" data-delay="<?= $room_count * 0.1; ?>">
            <div class="room-image-wrapper">
                <img src="<?= $PROJECT_ROOT ?>/assets/images/rooms/<?= htmlspecialchars($row['image']); ?>" 
                    alt="<?= htmlspecialchars($row['room_type']); ?> Room Image" 
                    class="room-image">
                <span class="room-status room-status-available">
                    <?= $availabilityText; ?>
                </span>
            </div>
            
            <div class="room-details">
                <h3><?= htmlspecialchars($row['room_type']); ?> Room</h3>
                
                <ul class="room-specs">
                    <li><i class="fas fa-fan"></i> Type: <strong><?= htmlspecialchars($row['ac_type']); ?></strong></li>
                    <li><i class="fas fa-users"></i> Max Guests: <strong><?= htmlspecialchars($row['capacity'] ?? 'N/A'); ?></strong></li>
                </ul>

                <p class="room-price">
                    <span>Price:</span> 
                    <strong>₹<?= number_format($row['price_per_night']); ?></strong> / night
                </p>
                
                <a href="<?= $PROJECT_ROOT ?>/user/book_room.php?room_id=<?= $row['room_id']; ?>&check_in=<?= $check_in; ?>&check_out=<?= $check_out; ?>&guests=<?= $guests; ?>" 
                    class="btn btn-action btn-full-width animate-pulse-hover">
                    Book This Room
                </a>
            </div>
        </div>
        <?php 
            }
        } else {
            echo "<p class='text-center empty-state'>We are sorry, but no rooms are available for the selected criteria ($guests guests, $check_in to $check_out). Please try adjusting your dates or guest count.</p>";
        }
        ?>
    </div>
</div>

<?php 
include('includes/footer.php'); 
?>

<script>
// --- JavaScript for Staggered Fade-In Animation on Room Cards ---
document.addEventListener('DOMContentLoaded', function() {
    const roomCards = document.querySelectorAll('.rooms-grid, .fade-in-card');
    roomCards.forEach(card => {
        const delay = card.getAttribute('data-delay') || 0;
        card.style.animationDelay = `${delay}s`;
        card.classList.add('animated'); // Trigger animation
    });
});
</script>