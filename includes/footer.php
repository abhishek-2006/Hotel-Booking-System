</main>
<?php $project_root = '/Hotel%20Management%20system'; ?>
    <footer class="main-footer">
        <div class="footer-grid">
            
            <div class="footer-column footer-about">
                <h3>HotelBooking</h3>
                <p>Your premier destination for luxury stays and fine dining reservations. Book rooms, tables, and manage your stay seamlessly.</p>
            </div>

            <div class="footer-column footer-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="<?= $project_root ?>/index.php">Home</a></li>
                    <li><a href="<?= $project_root ?>/rooms.php">Rooms</a></li>
                    <li><a href="<?= $project_root ?>/dining.php">Dining</a></li>
                    <li><a href="<?= $project_root ?>/spa.php">Spa</a></li>
                    <li><a href="<?= $project_root ?>/about.php">About Us</a></li>
                    <li><a href="<?= $project_root ?>/contact.php">Contact Us</a></li>
                </ul>
            </div>

            <div class="footer-column footer-links">
                <h3>Book Now</h3>
                <ul>
                    <li><a href="<?= $project_root ?>/rooms.php">View Rooms</a></li>
                    <li><a href="<?= $project_root ?>/user/book_room.php">Book a Room</a></li>
                    <li><a href="<?= $project_root ?>/user/book_table.php">Reserve a Table</a></li>
                    <li><a href="<?= $project_root ?>/user/spa_booking.php">Spa Services</a></li>
                </ul>
            </div>

            <div class="footer-column footer-contact">
                <h3>Get In Touch</h3>
                <p><i class="fas fa-map-marker-alt"></i> 123 Luxury Lane, Metropolis, 90210</p>
                <p><i class="fas fa-phone"></i> +91 (555) 123-4567</p>
                <p><i class="fas fa-envelope"></i> info@hotelbooking.com</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y'); ?> Hotel Booking System. All Rights Reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Use</a></p>
        </div>
    </footer>
    
    <script src="<?= $project_root ?>/assets/js/main.js"></script> 
</body>
</html>