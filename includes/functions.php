<?php
// Define the icon mapping based on your food_type categories
function get_food_icon($food_type) {
    switch ($food_type) {
        case 'Salad':
            return 'fas fa-leaf';
        case 'Drinks':
            return 'fas fa-cocktail';
        case 'Starter':
            return 'fas fa-fire-alt';
        case 'Breakfast':
            return 'fas fa-mug-hot';
        case 'Lunch':
            return 'fas fa-pizza-slice';
        case 'Dinner':
            return 'fas fa-utensils';
        case 'Combo':
            return 'fas fa-hotdog';
        case 'Dessert':
            return 'fas fa-ice-cream';
        default:
            return 'fas fa-burger';
    }
}