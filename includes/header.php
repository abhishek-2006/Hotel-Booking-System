<?php
session_start();
$project_root = '/Hotel%20Management%20system';
// Define current page to highlight active link in the navigation
$currentPage = basename($_SERVER['PHP_SELF']);
if (strpos($_SERVER['REQUEST_URI'], '/user/') !== false) {
    $currentPage = 'dashboard.php'; // Handle user folder pages
}
include('config.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking System</title>
    <link rel="stylesheet" href="<?= $project_root ?>/assets/css/styles.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <nav class="navbar">
            <div class="logo">
                <a href="<?= $project_root ?>/index.php">Hotel<span class="logo-accent">Booking</span></a>
            </div>
            
            <button class="menu-toggle" aria-label="Toggle navigation menu">
                <i class="fas fa-bars"></i>
            </button>
            
            <ul class="nav-links">
                <li class="<?= ($currentPage == 'index.php') ? 'active' : '' ?>">
                    <a href="<?= $project_root ?>/index.php">Home</a>
                </li>
                <li class="<?= ($currentPage == 'rooms.php') ? 'active' : '' ?>">
                    <a href="<?= $project_root ?>/rooms.php">Rooms</a>
                </li>
                <li class="<?= ($currentPage == 'tables.php') ? 'active' : '' ?>">
                    <a href="<?= $project_root ?>/tables.php">Tables</a>
                </li>
                <li class="<?= ($currentPage == 'about.php') ? 'active' : '' ?>">
                    <a href="<?= $project_root ?>/about.php">About</a>
                </li>
                <li class="<?= ($currentPage == 'contact.php') ? 'active' : '' ?>">
                    <a href="<?= $project_root ?>/contact.php">Contact</a>
                </li>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
                        <a href="<?= $project_root ?>/user/dashboard.php">Dashboard</a>
                    </li>
                    <li class="auth-link">
                        <a href="<?= $project_root ?>/auth/logout.php" class="btn-link">Logout</a>
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