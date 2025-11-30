<?php
$PROJECT_ROOT = '/Hotel Management system'; 
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/header.php'); 
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/config.php'); 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please log in to book a spa session.";
    header("Location: {$PROJECT_ROOT}/auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['full_name'] ?? 'Guest';
$today = date('Y-m-d');

// Fetch valid hotel bookings for today
$booking_check_stmt = $conn->prepare("
    SELECT check_in, check_out FROM bookings 
    WHERE user_id = ? AND status = 'Confirmed' AND ? BETWEEN check_in AND check_out
");
$booking_check_stmt->bind_param("is", $user_id, $today);
$booking_check_stmt->execute();
$booking_result = $booking_check_stmt->get_result();

$has_valid_booking = $booking_result->num_rows > 0;
$valid_booking_dates = [];

while ($row = $booking_result->fetch_assoc()) {
    $valid_booking_dates[] = date('M d, Y', strtotime($row['check_in'])) . " to " . date('M d, Y', strtotime($row['check_out']));
}

// Fetch spa services
$spa_services_query = $conn->query("SELECT * FROM spa_services ORDER BY service_name ASC");
?>

<div class="container spa-booking-page">
    <h1 class="text-center">Spa Booking</h1>
    <p class="text-center lead-text">
        Relax, rejuvenate, and refresh. <br>
        <strong>Note:</strong> Spa booking is available only for guests currently staying at the hotel or with a valid booking.
    </p>

    <?php if (!$has_valid_booking): ?>
        <div class="alert alert-warning text-center">
            You do not have a valid hotel booking at the moment. Spa sessions are available only for guests currently staying or with a confirmed booking.
        </div>
    <?php else: ?>
        <div class="alert alert-success text-center">
            Your valid hotel stay: <strong><?= implode(', ', $valid_booking_dates); ?></strong>.<br>
            You can book spa sessions only on these dates.
        </div>
        <div class="spa-services-grid grid-3">
            <?php while($service = $spa_services_query->fetch_assoc()): ?>
                <div class="card spa-service-card">
                    <div class="service-image-wrapper">
                        <img src="<?= $PROJECT_ROOT ?>/assets/images/spa/<?= htmlspecialchars($service['image']); ?>" 
                            alt="<?= htmlspecialchars($service['service_name']); ?>">
                    </div>
                    <div class="service-details">
                        <h3><?= htmlspecialchars($service['service_name']); ?></h3>
                        <p><?= htmlspecialchars($service['description']); ?></p>
                        <p><strong>Price:</strong> ₹<?= number_format($service['price']); ?></p>
                        <form method="POST" action="<?= $PROJECT_ROOT ?>/user/process_spa_booking.php">
                            <input type="hidden" name="service_id" value="<?= $service['service_id']; ?>">
                            <input type="hidden" name="booking_date" value="<?= $today; ?>">
                            <button type="submit" class="btn btn-primary btn-full-width">Book Now</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.spa-booking-page { padding: 30px 0; }
.spa-booking-page h1 { color: var(--color-brand); margin-bottom: 10px; }
.spa-booking-page .lead-text { font-size: 1.1rem; margin-bottom: 30px; }
.spa-services-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; }
.spa-service-card { border-radius: var(--border-radius-md); box-shadow: var(--shadow-pro); overflow: hidden; transition: transform 0.3s; }
.spa-service-card:hover { transform: translateY(-5px); }
.service-image-wrapper img { width: 100%; height: 180px; object-fit: cover; }
.service-details { padding: 15px; text-align: center; }
.service-details h3 { margin-bottom: 10px; color: var(--color-brand); }
.service-details p { margin-bottom: 10px; font-size: 0.95rem; color: var(--color-text); }
.btn-full-width { width: 100%; }
</style>

<?php
$booking_check_stmt->close();
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/footer.php');
?>
