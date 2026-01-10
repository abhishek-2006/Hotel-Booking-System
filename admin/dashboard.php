<?php 
include 'includes/header.php';
error_reporting(E_ALL);
$PROJECT_ROOT = '/Hotel%20Management%20system/admin';
?>
<div class="container dashboard-page-container">
    <h2 class="page-title text-center">Admin Dashboard</h2>
    <div class="dashboard-widgets">
        <div class="widget">
            <h3>Total Bookings</h3>
            <p>150</p>
        </div>
        <div class="widget">
            <h3>Available Rooms</h3>
            <p>45</p>
        </div>
        <div class="widget">
            <h3>Registered Users</h3>
            <p>320</p>
        </div>
        <div class="widget">
            <h3>Pending Requests</h3>
            <p>12</p>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>