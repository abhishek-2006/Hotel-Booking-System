<?php
// Database credentials
$host = 'localhost';
$username = 'root'; 
$password = '';
$database = 'hotel_booking';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Set character set for proper data handling
$conn->set_charset("utf8");

function get_food_icon($type) {
    switch ($type) {
        case 'Salad': return 'fas fa-leaf';
        case 'Drinks': return 'fas fa-glass-martini-alt';
        case 'Starter': return 'fas fa-utensils';
        case 'Breakfast': return 'fas fa-coffee';
        case 'Lunch': return 'fas fa-hamburger';
        case 'Dinner': return 'fas fa-concierge-bell';
        case 'Sizzler': return 'fas fa-fire';
        case 'Combo': return 'fas fa-layer-group';
        case 'Dessert': return 'fas fa-ice-cream';
        default: return 'fas fa-utensils';
    }
}
?>