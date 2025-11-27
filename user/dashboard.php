<?php 
// Final Confirmed Paths
$PROJECT_ROOT = '/Hotel%20Management%20system'; 
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/header.php'); 
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/config.php'); 

// Check for authentication
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please log in to view your dashboard.";
    header('Location: ' . $PROJECT_ROOT . '/auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['full_name'] ?? 'Guest'; 

// Placeholder for session messages (success/error from booking_process.php)
$message = '';
$message_class = '';
if (isset($_SESSION['success_message'])) {
    $message = $_SESSION['success_message'];
    $message_class = 'alert-success';
    unset($_SESSION['success_message']);
} elseif (isset($_SESSION['error_message'])) {
    $message = $_SESSION['error_message'];
    $message_class = 'alert-danger';
    unset($_SESSION['error_message']);
}

// --- Fetch Active Bookings (Corrected Query using b.user_id) ---
// This retrieves current/upcoming Room and Table bookings
$active_bookings_query = $conn->prepare("
    SELECT 
        b.booking_id, b.check_in, b.check_out, b.status, b.total_price,
        r.room_type, r.room_no,
        t.table_no
    FROM bookings b
    LEFT JOIN rooms r ON b.room_id = r.room_id
    LEFT JOIN tables_list t ON b.table_id = t.table_id
    WHERE b.user_id = ? AND b.status IN ('Confirmed', 'Pending')
    ORDER BY b.check_in ASC
");
$active_bookings_query->bind_param("i", $user_id);
$active_bookings_query->execute();
$active_bookings_result = $active_bookings_query->get_result();

// --- Fetch Booking History (Completed/Cancelled) ---
$history_query = $conn->prepare("
    SELECT booking_id, check_in, total_price, status 
    FROM bookings
    WHERE user_id = ? AND status IN ('Completed', 'Cancelled')
    ORDER BY check_in DESC LIMIT 5
");
$history_query->bind_param("i", $user_id);
$history_query->execute();
$history_result = $history_query->get_result();

?>

<div class="container dashboard-page-container">
    <div class="dashboard-header">
        <h1>Welcome Back, <?= htmlspecialchars($user_name); ?>!</h1>
        <p class="lead-text">Manage your stays and reservations at The Citadel Retreat.</p>
    </div>

    <?php if ($message): ?>
        <div class="alert <?= $message_class; ?> mb-4">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Quick Actions and Profile Links -->
    <section class="quick-actions grid-3">
        <div class="card action-card">
            <h3>New Booking</h3>
            <p>Need accommodation? Find the perfect room or suite.</p>
            <a href="<?= $PROJECT_ROOT ?>/rooms.php" class="btn btn-action btn-small">Book Room</a>
        </div>
        <div class="card action-card">
            <h3>Dining Reservation</h3>
            <p>Secure your spot at The Sprout for a memorable meal.</p>
            <a href="<?= $PROJECT_ROOT ?>/tables.php" class="btn btn-primary btn-small">Reserve Table</a>
        </div>
        <div class="card action-card">
            <h3>Manage Profile</h3>
            <p>Update your personal information and change your password.</p>
            <a href="<?= $PROJECT_ROOT ?>/user/profile.php" class="btn btn-secondary btn-small">Update Profile</a>
        </div>
    </section>

    <!-- Active/Upcoming Bookings Section -->
    <section class="active-bookings-section">
        <h2>Your Upcoming Reservations (<?= $active_bookings_result->num_rows; ?>)</h2>
        
        <?php if ($active_bookings_result->num_rows > 0): ?>
            <div class="booking-list">
                <?php while($booking = $active_bookings_result->fetch_assoc()): ?>
                <div class="booking-card card">
                    <div class="booking-info">
                        <h4>
                            <i class="fas <?= $booking['room_type'] ? 'fa-bed' : 'fa-utensils'; ?>"></i>
                            <?= $booking['room_type'] ? htmlspecialchars($booking['room_type']) . " (Room " . htmlspecialchars($booking['room_no']) . ")" : "Table " . htmlspecialchars($booking['table_no']); ?>
                        </h4>
                        <p>
                            <strong>ID:</strong> #<?= $booking['booking_id']; ?> |
                            <strong>Status:</strong> <span class="status status-<?= strtolower($booking['status']); ?>"><?= htmlspecialchars($booking['status']); ?></span>
                        </p>
                        <p class="dates-info">
                            <?php if ($booking['room_type']): ?>
                                From <strong><?= date('M d, Y', strtotime($booking['check_in'])); ?></strong> to <strong><?= date('M d, Y', strtotime($booking['check_out'])); ?></strong>
                            <?php else: ?>
                                Date: <strong><?= date('M d, Y', strtotime($booking['check_in'])); ?></strong>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="booking-actions">
                        <p class="price-display">₹<?= number_format($booking['total_price'], 2); ?></p>
                        <a href="<?= $PROJECT_ROOT ?>/user/view_invoice.php?id=<?= $booking['booking_id']; ?>" class="btn btn-primary btn-view">View</a>
                        <a href="<?= $PROJECT_ROOT ?>/bookings/cancel_booking.php?id=<?= $booking['booking_id']; ?>" class="btn btn-danger btn-cancel">Cancel</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state-card card text-center">
                <i class="fas fa-calendar-alt fa-3x" style="color:var(--color-text-light); margin-bottom:15px;"></i>
                <p>You have no active or pending reservations.</p>
                <a href="<?= $PROJECT_ROOT ?>/rooms.php" class="btn btn-action">Start Planning Your Stay</a>
            </div>
        <?php endif; ?>
    </section>

    <!-- Booking History Section -->
    <section class="history-section">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Recent Booking History</h2>
            <a href="<?= $PROJECT_ROOT ?>/user/booking_history.php" class="btn btn-secondary btn-small">View All History</a>
        </div>
        <!-- This section would be populated using $history_result (data fetched above) -->
        
        <?php if ($history_result->num_rows > 0): ?>
            <div class="history-list mt-3">
                <!-- Loop through $history_result here (similar structure to active bookings, but simpler) -->
                <p>History content will be displayed here...</p>
            </div>
        <?php else: ?>
            <p class="text-light text-center">No completed or cancelled bookings found yet.</p>
        <?php endif; ?>
    </section>
</div>

<?php 
$active_bookings_query->close();
$history_query->close();
include($_SERVER['DOCUMENT_ROOT'] . "{$PROJECT_ROOT}/includes/footer.php"); 
?>