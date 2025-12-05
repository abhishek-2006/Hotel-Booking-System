<?php 
$PROJECT_ROOT = '/Hotel Management system';
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/header.php'); 

// Check user login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please log in to view your booking history.";
    header('Location: ' . $PROJECT_ROOT . '/auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['full_name'] ?? 'Guest'; 

// Fetch all booking history
$history_query = $conn->prepare("
    SELECT 
        b.booking_id, b.check_in, b.check_out, b.status, b.total_price,
        r.room_type, r.room_no,
        t.table_no
    FROM bookings b
    LEFT JOIN rooms r ON b.room_id = r.room_id
    LEFT JOIN tables_list t ON b.table_id = t.table_id
    WHERE b.user_id = ? AND b.status IN ('Completed', 'Cancelled')
    ORDER BY b.check_in DESC
");
$history_query->bind_param("i", $user_id);
$history_query->execute();
$history_result = $history_query->get_result();
?>

<div class="container dashboard-page-container">
    <div class="dashboard-header">
        <h1><?= htmlspecialchars($user_name); ?>'s Booking History</h1>
        <p class="lead-text">All your completed and cancelled bookings are displayed below.</p>
    </div>

    <?php if ($history_result->num_rows > 0): ?>
        <div class="booking-history-grid grid-3">
            <?php while($booking = $history_result->fetch_assoc()): ?>
            <div class="booking-card card">
                <div class="booking-info">
                    <h3>
                        <i class="fas <?= $booking['room_type'] ? 'fa-bed' : 'fa-utensils'; ?>"></i>
                        <?= $booking['room_type'] ? htmlspecialchars($booking['room_type']) . " (Room " . htmlspecialchars($booking['room_no']) . ")" : "Table " . htmlspecialchars($booking['table_no']); ?>
                    </h3>
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
                    <a href="<?= $PROJECT_ROOT ?>/user/view_invoice.php?id=<?= $booking['booking_id']; ?>" class="btn btn-primary btn-small">View Invoice</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state-card card text-center">
            <i class="fas fa-calendar-alt fa-3x" style="color:var(--color-text-light); margin-bottom:15px;"></i>
            <p>No completed or cancelled bookings found yet.</p>
            <a href="<?= $PROJECT_ROOT ?>/rooms.php" class="btn btn-action">Start Booking</a>
        </div>
    <?php endif; ?>
</div>

<?php 
$history_query->close();
include($_SERVER['DOCUMENT_ROOT'] . "{$PROJECT_ROOT}/includes/footer.php"); 
?>
