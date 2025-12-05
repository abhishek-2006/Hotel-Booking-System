<?php
include('includes/config.php'); // Included before header/content
include('includes/header.php'); // Starts HTML, loads CSS, opens <main>
if (isset($_SESSION['user_id'])) {
    header('Location: user/dashboard.php');
    exit;
}
?>

    <section class="hero">
        <div class="hero-content">
            <h1>Find Your Perfect Stay</h1>
            <p>Quickly search and secure your accommodation with instant confirmation.</p>
            
            <form class="search-widget" action="rooms.php" method="POST">
                <div class="form-group">
                    <label for="check_in_date" class="sr-only">Check-in</label>
                    <input type="date" id="check_in_date" name="check_in" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="check_out_date" class="sr-only">Check-out</label>
                    <input type="date" id="check_out_date" name="check_out" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="num_guests" class="sr-only">Guests</label>
                    <input type="number" id="num_guests" name="guests" class="form-control" placeholder="Guests" min="1" required>
                </div>
                
                <button type="submit" class="btn btn-action search-btn">
                    Search Rooms
                </button>
            </form>
        </div>
    </section>

    <section class="container features-section">
        <h2 class="text-center">Why Book With Us?</h2>
        <div class="grid-3">
            <div class="card feature">
                <h3>Flexible Room Types</h3>
                <p>Select from AC, Non-AC, and luxury suites. Pricing is always transparent.</p>
            </div>
            <div class="card feature">
                <h3>Integrated Dining</h3>
                <p>Book a room and reserve a table at our top-rated restaurant, all in one seamless process.</p>
                <a href="user/book_table.php" class="btn btn-primary btn-small">Reserve a Table</a>
            </div>
            <div class="card feature">
                <h3>Instant Confirmation</h3>
                <p>Get immediate booking confirmation and an email invoice sent straight to your inbox.</p>
            </div>
        </div>
    </section>
    
    <section class="container text-center cta-section">
        <h2></h2>
        <h2>Have Questions?</h2>
        <p>Learn more about our hotel and services, or get in touch with our front desk team.</p>
        <a href="about.php" class="btn btn-primary">About Us</a>
        <a href="contact.php" class="btn btn-action">Contact Us</a>
    </section>

<script src="assets/js/main.js"></script> <?php 
include('includes/footer.php');
?>