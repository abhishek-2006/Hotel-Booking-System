<?php
$PROJECT_ROOT = '/Hotel Management system';
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/header.php');
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please login to access your dashboard.";
    header('Location: ' . $PROJECT_ROOT . '/auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['full_name'] ?? 'Guest';

// --- Flash Messages ---
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

// --- Active Bookings ---
$active_bookings_query = $conn->prepare("
    SELECT b.booking_id, b.check_in, b.check_out, b.status, b.total_price,
           r.room_type, r.room_no,
           t.table_no
    FROM bookings b
    LEFT JOIN rooms r ON b.room_id = r.room_id
    LEFT JOIN tables_list t ON b.table_id = t.table_id
    WHERE b.user_id = ? AND b.status IN ('Confirmed','Pending')
    ORDER BY b.check_in ASC
");
$active_bookings_query->bind_param("i", $user_id);
$active_bookings_query->execute();
$active_bookings = $active_bookings_query->get_result();

// --- History ---
$history_query = $conn->prepare("
    SELECT booking_id, check_in, status, total_price
    FROM bookings
    WHERE user_id = ? AND status IN ('Completed','Cancelled')
    ORDER BY check_in DESC LIMIT 5
");
$history_query->bind_param("i", $user_id);
$history_query->execute();
$history = $history_query->get_result();
?>

<!-- DASHBOARD UI -->
<div class="container dashboard-page-container">

    <div class="dashboard-header">
        <h1>Hello, <?= htmlspecialchars($user_name) ?> 👋</h1>
        <p class="lead-text">Manage stays, dining, spa, and your profile from one place.</p>
    </div>

    <?php if ($message): ?>
        <div class="alert <?= $message_class ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- QUICK ACTION GRID -->
    <section class="quick-actions grid-4">

        <div class="card action-card">
            <h3><i class="fas fa-bed"></i> Book a Room</h3>
            <p>Search rooms, check availability, and book instantly.</p>
            <a href="<?= $PROJECT_ROOT ?>/rooms.php" class="btn btn-primary btn-small">Book Now</a>
        </div>

        <div class="card action-card">
            <h3><i class="fas fa-utensils"></i> Dining Reservation</h3>
            <p>Reserve a table at our premium restaurant.</p>
            <a href="<?= $PROJECT_ROOT ?>/dining.php" class="btn btn-action btn-small">Reserve Table</a>
        </div>

        <div class="card action-card">
            <h3><i class="fas fa-spa"></i> Spa Services</h3>
            <p>Relax with our premium spa treatments.</p>
            <a href="<?= $PROJECT_ROOT ?>/spa.php" class="btn btn-secondary btn-small">Explore Spa</a>
        </div>

        <div class="card action-card">
            <h3><i class="fas fa-calendar-check"></i> Spa Booking</h3>
            <p>Book a spa session (only for guests staying today).</p>
            <a href="<?= $PROJECT_ROOT ?>/spa_booking.php" class="btn btn-primary btn-small">Book Spa</a>
        </div>

    </section>

    <!-- PROFILE SECTION -->
    <section class="profile-actions grid-3 mt-5">
        <div class="card action-card">
            <h3><i class="fas fa-user"></i> View Profile</h3>
            <p>Your details, contact info & membership details.</p>
            <a href="<?= $PROJECT_ROOT ?>/user/view_profile.php" class="btn btn-secondary btn-small">View Profile</a>
        </div>

        <div class="card action-card">
            <h3><i class="fas fa-user-edit"></i> Update Profile</h3>
            <p>Edit your name, email, mobile number, and password.</p>
            <a href="<?= $PROJECT_ROOT ?>/user/update_profile.php" class="btn btn-primary btn-small">Update</a>
        </div>

        <div class="card action-card">
            <h3><i class="fas fa-receipt"></i> Booking History</h3>
            <p>See your past stays, payments & reservations.</p>
            <a href="<?= $PROJECT_ROOT ?>/user/booking_history.php" class="btn btn-action btn-small">View All</a>
        </div>
    </section>

    <!-- ACTIVE BOOKINGS -->
    <section class="active-bookings-section mt-5">
        <h2>Upcoming Reservations (<?= $active_bookings->num_rows ?>)</h2>

        <?php if ($active_bookings->num_rows > 0): ?>
            <div class="booking-list">
                <?php while ($b = $active_bookings->fetch_assoc()): ?>
                    <div class="booking-card card">
                        <h4>
                            <i class="fas <?= $b['room_type'] ? 'fa-bed' : 'fa-utensils' ?>"></i>
                            <?= $b['room_type'] ? $b['room_type']." (Room ".$b['room_no'].")" : "Table ".$b['table_no'] ?>
                        </h4>

                        <p><strong>ID:</strong> #<?= $b['booking_id'] ?></p>
                        <p><strong>Status:</strong> 
                            <span class="status status-<?= strtolower($b['status']) ?>"><?= $b['status'] ?></span>
                        </p>

                        <p class="dates-info">
                            <?php if ($b['room_type']): ?>
                                <?= date("M d, Y", strtotime($b['check_in'])) ?> → 
                                <?= date("M d, Y", strtotime($b['check_out'])) ?>
                            <?php else: ?>
                                <?= date("M d, Y", strtotime($b['check_in'])) ?>
                            <?php endif; ?>
                        </p>

                        <p class="price-display">₹<?= number_format($b['total_price']) ?></p>

                        <div class="booking-actions">
                            <a href="<?= $PROJECT_ROOT ?>/user/view_invoice.php?id=<?= $b['booking_id'] ?>" class="btn btn-primary btn-small">View</a>
                            <a href="<?= $PROJECT_ROOT ?>/bookings/cancel_booking.php?id=<?= $b['booking_id'] ?>" class="btn btn-danger btn-small">Cancel</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

        <?php else: ?>
            <div class="empty-state-card card text-center">
                <i class="fas fa-calendar-alt fa-3x"></i>
                <p>No active or pending bookings.</p>
                <a href="<?= $PROJECT_ROOT ?>/rooms.php" class="btn btn-action">Book Now</a>
            </div>
        <?php endif; ?>
    </section>

    <!-- HISTORY -->
    <section class="history-section mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Recent Booking History</h2>
            <a href="<?= $PROJECT_ROOT ?>/user/booking_history.php" class="btn btn-secondary btn-small">View All</a>
        </div>

        <?php if ($history->num_rows > 0): ?>
            <ul class="history-list mt-3">
                <?php while ($h = $history->fetch_assoc()): ?>
                    <li class="history-item card">
                        <strong>#<?= $h['booking_id'] ?></strong> —
                        <?= date("M d, Y", strtotime($h['check_in'])) ?> —
                        <span class="status status-<?= strtolower($h['status']) ?>"><?= $h['status'] ?></span> —
                        ₹<?= number_format($h['total_price']) ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="text-light text-center">No previous bookings found.</p>
        <?php endif; ?>
    </section>

</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "$PROJECT_ROOT/includes/footer.php"); ?>
