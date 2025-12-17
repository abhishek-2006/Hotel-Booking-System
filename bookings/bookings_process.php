<?php
$PROJECT_ROOT = '/Hotel Management system';
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/header.php');
if (!isset($conn)) {
    include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/config.php');
}
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please log in to view your booking history.";
    header("Location: {$PROJECT_ROOT}/auth/login.php");
    exit;
}

$user_id   = $_SESSION['user_id'];
$user_name = $_SESSION['full_name'] ?? 'Guest';

$stmt = $conn->prepare("
    SELECT 
        b.booking_id, b.check_in, b.check_out, b.status, b.total_price,
        r.room_type, r.room_no,
        t.table_no
    FROM bookings b
    LEFT JOIN rooms r ON b.room_id = r.room_id
    LEFT JOIN tables_list t ON b.table_id = t.table_id
    WHERE b.user_id = ?
    ORDER BY b.booking_id DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container dashboard-page-container">

    <div class="dashboard-header">
        <h1><?= htmlspecialchars($user_name); ?>’s Bookings</h1>
        <p class="lead-text">View, manage, and track all your reservations.</p>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <div class="booking-history-grid grid-3">

        <?php while ($b = $result->fetch_assoc()): ?>
            <div class="booking-card card">

                <h3>
                    <i class="fas <?= $b['room_type'] ? 'fa-bed' : 'fa-utensils'; ?>"></i>
                    <?= $b['room_type']
                        ? htmlspecialchars($b['room_type']) . " (Room " . htmlspecialchars($b['room_no']) . ")"
                        : "Table " . htmlspecialchars($b['table_no']); ?>
                </h3>

                <p>
                    <strong>ID:</strong> #<?= $b['booking_id']; ?><br>
                    <strong>Status:</strong>
                    <span class="status status-<?= strtolower($b['status']); ?>">
                        <?= htmlspecialchars($b['status']); ?>
                    </span>
                </p>

                <p>
                    <?php if ($b['check_out']): ?>
                        <?= date('M d, Y', strtotime($b['check_in'])); ?>
                        → <?= date('M d, Y', strtotime($b['check_out'])); ?>
                    <?php else: ?>
                        <?= date('M d, Y', strtotime($b['check_in'])); ?>
                    <?php endif; ?>
                </p>

                <p class="price-display">₹<?= number_format($b['total_price'], 2); ?></p>

                <div class="booking-actions">
                    <a href="<?= $PROJECT_ROOT ?>/user/view_invoice.php?id=<?= $b['booking_id']; ?>"
                       class="btn btn-primary btn-small">Invoice</a>

                    <?php if ($b['status'] === 'Confirmed'): ?>
                        <form method="POST" action="cancel_booking.php"
                              onsubmit="return confirm('Cancel this booking?');">
                            <input type="hidden" name="booking_id" value="<?= $b['booking_id']; ?>">
                            <button class="btn btn-danger btn-small">Cancel</button>
                        </form>
                    <?php endif; ?>
                </div>

            </div>
        <?php endwhile; ?>

        </div>
    <?php else: ?>
        <div class="empty-state-card card text-center">
            <p>No bookings found.</p>
            <a href="<?= $PROJECT_ROOT ?>/rooms.php" class="btn btn-action">Book Now</a>
        </div>
    <?php endif; ?>

</div>

<?php
$stmt->close();
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/footer.php');
?>
