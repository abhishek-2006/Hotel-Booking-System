<?php
session_start();
include('../includes/config.php');

$user_id = $_SESSION['id'];
$date = $_POST['date'];

// Check if date lies inside user's stay
$query = $dbh->prepare("
    SELECT * FROM bookings 
    WHERE user_id = :uid
        AND status = 'Confirmed'
        AND :d BETWEEN check_in_date AND check_out_date
");
$query->execute(['uid' => $user_id, 'd' => $date]);

if ($query->rowCount() == 0) {
    echo "<p class='msg-error'>This date is not within your stay.</p>";
} else {
    echo "<p class='msg-success'>This date is valid for spa booking.</p>";
}
?>
