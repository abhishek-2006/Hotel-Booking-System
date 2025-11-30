<?php 
$PROJECT_ROOT = '/Hotel Management system';
include($_SERVER['DOCUMENT_ROOT'] . $PROJECT_ROOT . '/includes/header.php'); 
?>

<link rel="stylesheet" href="<?= $PROJECT_ROOT ?>/assets/css/styles.css">

<section class="spa-hero">
    <div class="spa-overlay"></div>
    <div class="spa-hero-content">
        <h1>Relax. Rejuvenate. Refresh.</h1>
        <p>Step into our sanctuary of peace and experience therapies crafted for absolute wellbeing.</p>
        <a href="user/spa_booking.php" class="btn-primary big-btn">Book Your Session</a>
    </div>
</section>

<section class="spa-about section-padding">
    <h2 style="font-size: 2.5rem; margin-bottom: 15px; color: var(--color-brand);">
        About Our Spa
    </h2>
    <div class="spa-grid" style="font-size: 1.1rem; line-height: 1.8; color: var(--color-text-light); margin-bottom: 20px;">
        <p style="font-size: 1.1rem; line-height: 1.8; color: var(--color-text-light); margin-bottom: 20px;">
            Discover a space where tranquillity meets luxury. Our treatments blend ancient wellness methods 
            with modern relaxation techniques to give you a holistic, calming escape.
        </p>
        <div class="spa-img"></div>
    </div>
</section>

<section class="spa-services section-padding">
    <h2 style="font-size: 2.5rem; margin-bottom: 15px; color: var(--color-brand);">
        Signature Services
    </h2>

    <div class="service-grid">
        <div class="service-card">
            <i class="fas fa-spa"></i>
            <h3 style="font-size: 1.4rem; margin-bottom: 10px; color: var(--color-brand);">
                Full Body Massage
            </h3>
            <p style="font-size: 1.1rem; line-height: 1.8; color: var(--color-text-light); margin-bottom: 20px;">
                A deep relaxation therapy that melts stress and improves blood flow.
            </p>
        </div>

        <div class="service-card">
            <i class="fas fa-leaf"></i>
            <h3 style="font-size: 1.4rem; margin-bottom: 10px; color: var(--color-brand);">
                Aroma Therapy
            </h3>
            <p style="font-size: 1.1rem; line-height: 1.8; color: var(--color-text-light); margin-bottom: 20px;">
                Experience the healing power of essential oils to uplift your mind and body.
            </p>
        </div>

        <div class="service-card">
            <i class="fas fa-hot-tub"></i>
            <h3 style="font-size: 1.4rem; margin-bottom: 10px; color: var(--color-brand);">
                Steam & Sauna
            </h3>
            <p style="font-size: 1.1rem; line-height: 1.8; color: var(--color-text-light); margin-bottom: 20px;">
                Detoxify and rejuvenate your skin with our state-of-the-art steam and sauna facilities.
            </p>
        </div>
    </div>
</section>

<section class="spa-gallery section-padding">
    <h2 style="font-size: 2.5rem; margin-bottom: 15px; color: var(--color-brand);">
        Gallery
    </h2>
    <div class="gallery-grid">
        <img src="assets/images/spa/spa1.jpg" alt="Spa image 1">
        <img src="assets/images/spa/spa2.jpg" alt="Spa image 2">
        <img src="assets/images/spa/spa3.jpg" alt="Spa image 3">
    </div>
</section>

<?php include 'includes/footer.php'; ?>
