<?php
session_start();
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotel_booking';

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");

?>