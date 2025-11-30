<?php 
error_reporting(E_ALL);
$PROJECT_ROOT = '/Hotel%20Management%20system'; 
include('includes/header.php'); 
?>

<div class="container about-page-container">
    
    <section class="about-hero text-center">
        <h1>Welcome to The Citadel Retreat</h1>
        <p class="lead-text">Your exclusive sanctuary in the city. We are defined by impeccable security, unparalleled luxury, and the commitment to making your stay truly memorable.</p>
    </section>

    <section class="story-section">
        <h2 class="text-center">A Legacy of Secure Luxury</h2>
        <div class="story-content grid-2">
            <div>
                <p>The Citadel Retreat was established for discerning travelers who demand both supreme comfort and absolute privacy. Our architecture is inspired by timeless strength, yet our service is entirely modern. We offer a true retreat where you can conduct business or simply relax, knowing every detail of your security and comfort has been expertly handled.</p>
                <p>We combine cutting-edge technology with traditional, personalized hospitality. Our integrated booking system ensures your access to our exclusive rooms and dining is instant, seamless, and protected from the moment you make a reservation.</p>
            </div>
                <div class="image-box" id="image-box">
                    <img src="assets/images/about.jpg" alt="About The Citadel Retreat"/>
            </div>
        </div>
    </section>

    <section class="quick-view">
        <h2 class="text-center">Experience Our Offerings</h2>
        <div class="quick-view-grid grid-3">
            <div class="card quick-view-card">
                <img src="assets/images/room.jpg" alt="Luxurious Room"/>
                <h3>Luxurious Rooms</h3>
                <p>Discover our range of exquisitely designed rooms, each equipped with state-of-the-art amenities and tailored for your utmost comfort and security.</p>
                <a href="<?= $PROJECT_ROOT ?>/rooms.php" class="btn btn-action btn-small">Explore Rooms</a>
            </div>
            <div class="card quick-view-card">
                <img src="assets/images/dining.jpg" alt="Exclusive Dining"/>
                <h3>Exclusive Dining</h3>
                <p>Savor gourmet cuisine in our signature restaurants, where privacy meets culinary excellence. Reserve your table through our secure booking system.</p>
                <a href="<?= $PROJECT_ROOT ?>/tables.php" class="btn btn-action btn-small">Reserve a Table</a>
            </div>
            <div class="card quick-view-card">
                <img src="assets/images/spa/spa_about.jpg" alt="Rejuvenating Spa"/>
                <h3>Rejuvenating Spa</h3>
                <p>Indulge in our spa services designed to relax and revitalize. Book your treatments with confidence through our secure platform.</p>
                <a href="<?= $PROJECT_ROOT ?>/spa.php" class="btn btn-action btn-small">Book a Session</a>
            </div>
        </div>
    </section>

    <!-- Quick Video Section -->
    <section class="video-section text-center">
        <h2>Take a Virtual Tour of The Citadel Retreat</h2>
        <div class="video-wrapper">
            <video controls>
                <source src="assets/videos/citadel_retreat_tour.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </section>

    <section class="values-section">
        <h2 class="text-center">Our Core Pillars</h2>
        <div class="values-grid grid-3">
            <div class="card value-card">
                <h3><i class="fas fa-shield-alt"></i> Absolute Privacy</h3>
                <p>We guarantee a level of security and discretion unmatched in urban hospitality, making our retreat your personal haven.</p>
            </div>
            <div class="card value-card">
                <h3><i class="fas fa-gem"></i> Impeccable Standards</h3>
                <p>Every room, every meal, and every interaction reflects the highest global standards of luxury and quality.</p>
            </div>
            <div class="card value-card">
                <h3><i class="fas fa-utensils"></i> Exclusive Dining</h3>
                <p>Access our signature restaurants, offering bespoke menus and private dining options, reservable through your personalized dashboard.</p>
            </div>
        </div>
    </section>

    <section class="cta-section text-center">
        <h2>Secure Your Experience at The Citadel Retreat</h2>
        <a href="<?= $PROJECT_ROOT ?>/rooms.php" class="btn btn-action">View Exclusive Rooms</a>
        <a href="<?= $PROJECT_ROOT ?>/contact.php" class="btn btn-secondary">Request Concierge Service</a>
    </section>

</div>

<?php include('includes/footer.php'); ?>