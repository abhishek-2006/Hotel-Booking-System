<?php
$PROJECT_ROOT = '/Hotel Management system';
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/header.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please log in to view invoice.";
    header("Location: {$PROJECT_ROOT}/auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$booking_id = intval($_GET['id'] ?? 0);

if ($booking_id <= 0) {
    $_SESSION['error_message'] = "Invalid invoice request.";
    header("Location: {$PROJECT_ROOT}/user/booking_history.php");
    exit;
}

// Fetch booking + room/table details
$stmt = $conn->prepare("
    SELECT 
        b.booking_id, b.invoice_no, b.check_in, b.check_out, 
        b.total_price, b.status, b.created_at,
        r.room_type, r.room_no,
        t.table_no
    FROM bookings b
    LEFT JOIN rooms r ON b.room_id = r.room_id
    LEFT JOIN tables_list t ON b.table_id = t.table_id
    WHERE b.booking_id = ? AND b.user_id = ?
    LIMIT 1
");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Invoice not found.";
    header("Location: {$PROJECT_ROOT}/user/booking_history.php");
    exit;
}

$invoice = $result->fetch_assoc();
$stmt->close();

$isRoomBooking = !empty($invoice['room_type']);
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@500;600;700&display=swap');

.invoice-container {
    max-width: 800px;
    margin: 40px auto;
    font-family: Arial, sans-serif;
}
.invoice-card {
    font-family: 'Inter', sans-serif;
    padding: 30px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
}
.invoice-header, .invoice-footer {
    text-align: center;
}
.invoice-header img {
    max-height: 120px;
}
.invoice-header h1 {
    font-family: 'Playfair Display', serif;
    font-weight: 600;
    font-size: 2em;
    letter-spacing: 0.5px;
    margin: 10px 0 0 0;
}
.invoice-meta, .invoice-details, .invoice-total {
    margin: 20px 0;
}
.invoice-details p, .invoice-meta p {
    margin: 5px 0;
}
.amount {
    font-family: 'Inter', sans-serif;
    font-size: 1.5em;
    font-weight: bold;
    color: #333;
}
.status {
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
    color: #fff;
}
.status-confirmed { background-color: #28a745; }
.status-cancelled { background-color: #dc3545; }
.status-completed { background-color: #007bff; }
.invoice-actions {
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
}

/* Global text spacing */
.invoice-card {
    line-height: 1.6;
}

/* Sections spacing */
.invoice-header {
    margin-bottom: 20px;
}

.invoice-meta,
.invoice-details,
.invoice-total {
    margin: 25px 0;
}

/* Paragraph spacing */
.invoice-meta p,
.invoice-details p,
.invoice-total p {
    margin: 8px 0;
}

/* Headings spacing */
.invoice-details h3,
.invoice-total h3 {
    font-family: 'Playfair Display', serif;
    font-weight: 600;
    font-size: 1.4em;
    margin-bottom: 12px;
}

/* Horizontal rule spacing */
.invoice-card hr {
    margin: 22px 0;
    border: none;
    border-top: 1px solid #ddd;
}

/* Footer spacing */
.invoice-footer {
    margin-top: 30px;
    line-height: 1.5;
}

@media print {
    @page{
        margin: 20mm;
        size: A4;
    }
    header, footer, .invoice-actions {
        display: none;
    }
    body{
        margin: 0;
        padding: 0;
    }
    .invoice-header img {
        max-height: 120px;
    }
    .invoice-container, .invoice-container * {
        margin: 0 auto;
        padding: 0;
    }
    .invoice-actions { 
        display: none !important; 
    }
    .invoice-card {
        border: none;
        box-shadow: none;
        width: 100%;
        break-inside: avoid;
    }
    table, tr, td, th {
        break-inside: avoid;
    }

}
</style>

<div class="invoice-container">
    <div class="invoice-card">
        <!-- Header -->
        <div class="invoice-header">
            <img src="<?= $PROJECT_ROOT ?>/assets/logo.png" alt="Hotel Logo">
            <h1>The Citadel Retreat</h1>
            <p>123 Main Street, City Name, State, 123456</p>
            <p>Phone: +91 98765 43210 | Email: info@thecitadelretreat.com</p>
        </div>

        <hr>

        <!-- Invoice Meta -->
        <div class="invoice-meta">
            <p><strong>Invoice No:</strong> <?= htmlspecialchars($invoice['invoice_no']); ?></p>
            <p><strong>Booking ID:</strong> #<?= $invoice['booking_id']; ?></p>
            <p><strong>Date:</strong> <?= date('d M Y', strtotime($invoice['created_at'])); ?></p>
            <p class="status status-<?= strtolower($invoice['status']); ?>"><?= htmlspecialchars($invoice['status']); ?></p>
        </div>

        <hr>

        <!-- Booking Details -->
        <div class="invoice-details">
            <h3>Booking Details</h3>

            <?php if ($isRoomBooking): ?>
                <p><strong>Room:</strong> <?= htmlspecialchars($invoice['room_type']); ?> (Room <?= htmlspecialchars($invoice['room_no']); ?>)</p>
                <p><strong>Check-In:</strong> <?= date('d M Y', strtotime($invoice['check_in'])); ?></p>
                <p><strong>Check-Out:</strong> <?= date('d M Y', strtotime($invoice['check_out'])); ?></p>
                <p><strong>Stay Duration:</strong> 
                    <?= (new DateTime($invoice['check_in']))->diff(new DateTime($invoice['check_out']))->days; ?> nights
                </p>
            <?php else: ?>
                <p><strong>Table No:</strong> <?= htmlspecialchars($invoice['table_no']); ?></p>
                <p><strong>Date:</strong> <?= date('d M Y', strtotime($invoice['check_in'])); ?></p>
            <?php endif; ?>
        </div>

        <hr>

        <!-- Amount -->
        <div class="invoice-total">
            <h3>Total Amount</h3>
            <p class="amount">₹<?= number_format($invoice['total_price'], 2); ?></p>
            <p><em>Including applicable taxes (GST) and charges</em></p>
        </div>

        <hr>

        <!-- Footer -->
        <div class="invoice-footer" style="margin-top:30px; text-align:center;">
            <p>Thank you for choosing The Citadel Retreat!</p>
        </div>

        <!-- Actions -->
        <div class="invoice-actions">
            <a href="<?= $PROJECT_ROOT ?>/user/booking_history.php" class="btn btn-secondary">
                ← Back to History
            </a>
            <button onclick="window.print()" class="btn btn-action">Print Invoice</button>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/footer.php'); ?>
