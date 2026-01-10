<?php 
require 'includes/config.php';
if (!isset($PROJECT_ROOT)) {
    $PROJECT_ROOT = '/Hotel%20Management%20system';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<!-- Colorflul Admin Header -->
<body class="admin-body bg-gray-100 text-gray-900 font-sans leading-normal tracking-normal">
    <header class="admin-header bg-white shadow-md border-b border-gray-200">
        <div class="container mx-auto flex justify-between items-center p-4">
            <h1 class="text-2xl font-bold text-blue-600">Hotel Management System - Admin</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="<?= $PROJECT_ROOT ?>/admin/dashboard.php" class="text-gray-700 hover:text-blue-600">Dashboard</a></li>
                    <li><a href="<?= $PROJECT_ROOT ?>/admin/rooms.php" class="text-gray-700 hover:text-blue-600">Rooms</a></li>
                    <li><a href="<?= $PROJECT_ROOT ?>/admin/bookings.php" class="text-gray-700 hover:text-blue-600">Bookings</a></li>
                    <li><a href="<?= $PROJECT_ROOT ?>/admin/users.php" class="text-gray-700 hover:text-blue-600">Users</a></li>
                    <li><a href="<?= $PROJECT_ROOT ?>/admin/settings.php" class="text-gray-700 hover:text-blue-600">Settings</a></li>
                    <li><a href="<?= $PROJECT_ROOT ?>/logout.php" class="text-red-600 hover:text-red-800">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="admin-main container mx-auto p-4"> 