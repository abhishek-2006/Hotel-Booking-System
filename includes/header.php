<?php
session_start();
$project_root = '/Hotel%20Management%20system';

$currentPage = basename($_SERVER['PHP_SELF']);
if (strpos($_SERVER['REQUEST_URI'], '/user/') !== false) {
    $currentPage = 'dashboard.php'; // Handle user folder pages
}
require 'config.php';

$is_logged_in = isset($_SESSION['user_id']);
$primary_link_url = $is_logged_in ? "{$project_root}/user/dashboard.php" : "{$project_root}/index.php";
$primary_link_text = $is_logged_in ? "Dashboard" : "Home";

// Determine if the current page is the active landing page
$is_home_active = ($currentPage == 'index.php' || ($is_logged_in && $currentPage == 'dashboard.php'));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking System</title>
    <link rel="stylesheet" href="<?= $project_root ?>/assets/css/styles.css"> 
    <link rel="icon" type="image/png" href="<?= $project_root ?>/assets/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <nav class="navbar">
            <div class="logo">
                <a href="<?= $project_root ?>/index.php">
                    <img src="<?= $project_root ?>/assets/images/logo.png" alt="Hotel Logo">
                </a>
            </div>
            
            <button class="menu-toggle" aria-label="Toggle navigation menu">
                <i class="fas fa-bars"></i>
            </button>
            
            <ul class="nav-links">
                <li class="<?= $is_home_active ? 'active' : '' ?>">
                    <a href="<?= $primary_link_url ?>"><?= $primary_link_text ?></a>
                </li>
                <li class="<?= ($currentPage == 'rooms.php') ? 'active' : '' ?>">
                    <a href="<?= $project_root ?>/rooms.php">Rooms</a>
                </li>
                <li class="<?= ($currentPage == 'menu.php') ? 'active' : '' ?>">
                    <a href="<?= $project_root ?>/menu.php">Menu</a>
                </li>
                <li class="<?= ($currentPage == 'dining.php') ? 'active' : '' ?>">
                    <a href="<?= $project_root ?>/dining.php">Dining</a>
                </li>
                <li class="<?= ($currentPage == 'spa.php') ? 'active' : '' ?>">
                    <a href="<?= $project_root ?>/spa.php">Spa</a>
                </li>
                <li class="<?= ($currentPage == 'about.php') ? 'active' : '' ?>">
                    <a href="<?= $project_root ?>/about.php">About Us</a>
                </li>
                <li class="<?= ($currentPage == 'contact.php') ? 'active' : '' ?>">
                    <a href="<?= $project_root ?>/contact.php">Contact Us</a>
                </li>
                
                <?php if($is_logged_in): ?>
                    <li class="auth-link">
                        <a href="<?= $project_root ?>/auth/logout.php" class="btn-link btn-primary">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="auth-link">
                        <a href="<?= $project_root ?>/auth/login.php" class="btn-link btn-secondary">Login</a>
                    </li>
                    <li class="auth-link">
                        <a href="<?= $project_root ?>/auth/register.php" class="btn-link btn-primary">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main> 