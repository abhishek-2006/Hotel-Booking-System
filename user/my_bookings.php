<?php 
// Define project root and includes
$PROJECT_ROOT = '/Hotel%20Management%20system'; 
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/header.php'); 
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/config.php');

// 1. AUTHENTICATION CHECK
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . $PROJECT_ROOT . '/auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. FETCH ALL BOOKINGS
$query = "
    SELECT 
        b.booking_id, b.check_in, b.check_out, b.status, b.total_price, b.created_at,
        r.room_type, r.room_no,
        t.table_no, t.capacity AS table_capacity
    FROM bookings b
    LEFT JOIN rooms r ON b.room_id = r.room_id
    LEFT JOIN tables_list t ON b.table_id = t.table_id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [
    'upcoming' => [],
    'past' => [],
    'cancelled' => []
];

while ($row = $result->fetch_assoc()) {
    $status = strtolower($row['status']);
    if ($status == 'cancelled') {
        $bookings['cancelled'][] = $row;
    } elseif ($status == 'completed') {
        $bookings['past'][] = $row;
    } else {
        // Booked, Confirmed, Pending are treated as upcoming
        $bookings['upcoming'][] = $row;
    }
}
$stmt->close();
?>

<div class="container my-bookings-container">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="fade-in">My Reservations</h1>
            <p class="text-light">Manage your stays and dining experiences at The Citadel.</p>
        </div>
        <div class="header-stats fade-in">
            <div class="stat-item">
                <span class="stat-value"><?= count($bookings['upcoming']); ?></span>
                <span class="stat-label">Active</span>
            </div>
        </div>
    </div>

    <!-- Booking Navigation Tabs -->
    <div class="booking-tabs fade-in">
        <button class="tab-btn active" onclick="filterBookings('upcoming')">Upcoming & Active</button>
        <button class="tab-btn" onclick="filterBookings('past')">Past Stays</button>
        <button class="tab-btn" onclick="filterBookings('cancelled')">Cancelled</button>
    </div>

    <div id="bookings-wrapper" class="bookings-list">
        <?php foreach ($bookings as $category => $list): ?>
            <div id="<?= $category ?>-section" class="booking-group <?= $category == 'upcoming' ? 'active' : 'hidden' ?>">
                <?php if (empty($list)): ?>
                    <div class="empty-state card text-center p-5">
                        <i class="fas fa-calendar-times mb-3" style="font-size: 3rem; color: var(--color-border);"></i>
                        <h3>No <?= $category ?> bookings found.</h3>
                        <p>Looking for a getaway? Explore our luxury rooms.</p>
                        <a href="<?= $PROJECT_ROOT ?>/rooms.php" class="btn btn-primary mt-3">Browse Rooms</a>
                    </div>
                <?php else: ?>
                    <div class="booking-grid">
                        <?php foreach ($list as $booking): 
                            $isRoom = !empty($booking['room_type']);
                            $typeIcon = $isRoom ? 'fa-bed' : 'fa-utensils';
                            $typeName = $isRoom ? $booking['room_type'] . ' Room' : 'Dining Table ' . $booking['table_no'];
                        ?>
                            <div class="booking-card card fade-in-card">
                                <div class="card-status-bar status-<?= strtolower($booking['status']) ?>"></div>
                                <div class="booking-card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="type-badge">
                                            <i class="fas <?= $typeIcon ?>"></i> <?= $isRoom ? 'Stay' : 'Dining' ?>
                                        </div>
                                        <span class="booking-id">#ID-<?= $booking['booking_id'] ?></span>
                                    </div>
                                    
                                    <h3 class="mt-2"><?= htmlspecialchars($typeName) ?></h3>
                                    
                                    <div class="booking-info-grid">
                                        <div class="info-item">
                                            <label>Check In</label>
                                            <span><?= date('D, M j, Y', strtotime($booking['check_in'])) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <label>Check Out</label>
                                            <span><?= date('D, M j, Y', strtotime($booking['check_out'])) ?></span>
                                        </div>
                                    </div>

                                    <div class="booking-footer d-flex justify-content-between align-items-center mt-4">
                                        <div class="price-info">
                                            <label>Total Paid</label>
                                            <span class="price-value">₹<?= number_format($booking['total_price'], 2) ?></span>
                                        </div>
                                        
                                        <div class="card-actions">
                                            <?php if ($category == 'upcoming'): ?>
                                                <button class="btn btn-outline-danger btn-small" onclick="cancelBooking(<?= $booking['booking_id'] ?>)">Cancel</button>
                                            <?php endif; ?>
                                            
                                            <?php if ($category == 'past'): ?>
                                                <a href="view_invoice.php?id=<?= $booking['booking_id'] ?>" class="btn btn-secondary btn-small">Invoice</a>
                                            <?php endif; ?>
                                            
                                            <a href="booking_details.php?id=<?= $booking['booking_id'] ?>" class="btn btn-primary btn-small">Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function filterBookings(category) {
    // Update Tab Buttons
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');

    // Show/Hide Sections
    document.querySelectorAll('.booking-group').forEach(group => {
        group.classList.add('hidden');
        group.classList.remove('active');
    });
    
    document.getElementById(category + '-section').classList.remove('hidden');
    document.getElementById(category + '-section').classList.add('active');
}

function cancelBooking(id) {
    if(confirm('Are you sure you want to cancel this reservation? This action cannot be undone.')) {
        window.location.href = '../bookings/booking_process.php?action=cancel&id=' + id;
    }
}
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/footer.php'); ?>