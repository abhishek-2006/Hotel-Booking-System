<?php
include('../../includes/config.php'); 

date_default_timezone_set('Asia/Kolkata');

// --- 1. RETRIEVE PAGINATION AND FILTER DATA ---
$offset = (int)($_POST['offset'] ?? 0);
$check_in = mysqli_real_escape_string($conn, $_POST['check_in'] ?? date('Y-m-d'));
$check_out = mysqli_real_escape_string($conn, $_POST['check_out'] ?? date('Y-m-d', strtotime('+1 day')));
$guests = (int)($_POST['guests'] ?? 1);
$guests = max(1, $guests); // Ensure guest count is at least 1

// Define PROJECT_ROOT for asset paths in the generated HTML (CRITICAL)
$PROJECT_ROOT = '/Hotel%20Management%20system';

// --- 2. BUILD FILTER CONDITION (Exact same as in rooms.php) ---
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

$html = '';
if($query && mysqli_num_rows($query) > 0){
    while($row = mysqli_fetch_assoc($query)){
        // Since the room passed the SQL filter, it IS available for the dates selected.
        $isAvailable = true; 
        $availabilityText = 'Available'; 
        
        // Build the HTML card structure
        $html .= '<div class="room-card card">'; // No card-booked class needed as they are filtered out
        $html .= '  <div class="room-image-wrapper">';
        
        $html .= '    <img src="' . $PROJECT_ROOT . '/assets/images/rooms/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['room_type']) . ' Room Image" class="room-image">';
        
        $html .= '    <span class="room-status room-status-available">';
        $html .= '      ' . $availabilityText;
        $html .= '      </span>';
        $html .= '  </div>';
        $html .= '  <div class="room-details">';
        $html .= '      <h3>' . htmlspecialchars($row['room_type']) . ' Room</h3>';
        $html .= '      <ul class="room-specs">';
        $html .= '          <li><i class="fas fa-fan"></i> Type: <strong>' . htmlspecialchars($row['ac_type']) . '</strong></li>';
        $html .= '          <li><i class="fas fa-users"></i> Max Guests: <strong>' . htmlspecialchars($row['capacity'] ?? 'N/A') . '</strong></li>';
        $html .= '      </ul>';
        $html .= '      <p class="room-price">';
        $html .= '          <span>Price:</span> ';
        $html .= '          <strong>₹' . number_format($row['price_per_night']) . '</strong> / night';
        $html .= '      </p>';
        
        $booking_url = "{$PROJECT_ROOT}/user/book_room.php?room_id={$row['room_id']}&check_in={$check_in}&check_out={$check_out}&guests={$guests}";
        
        $html .= '      <a href="' . $booking_url . '" class="btn btn-action btn-full-width">';
        $html .= '          Book This Room';
        $html .= '      </a>';
        $html .= '  </div>';
        $html .= '</div>';
    }
}

// Output the generated HTML to the AJAX request
echo $html;

// Stop execution here
exit;