<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Hotel Management system/includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Hotel Management system/includes/header.php');
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please log in first.";
    header("Location: /Hotel Management system/auth/login.php");
    exit;
}
$user_id = $_SESSION['user_id'];