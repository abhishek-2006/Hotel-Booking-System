-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 17, 2025 at 01:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`log_id`, `user_id`, `action`, `ip_address`, `created_at`) VALUES
(1, 2, 'User registered successfully.', '::1', '2025-11-27 14:02:51'),
(2, 2, 'User logged in successfully.', '::1', '2025-11-27 14:24:23'),
(3, 2, 'User logged in successfully.', '::1', '2025-11-30 05:29:40'),
(4, 2, 'User logged in successfully.', '::1', '2025-11-30 06:34:35'),
(5, 1, 'User logged in successfully.', '::1', '2025-11-30 07:47:20'),
(6, 1, 'User logged in successfully.', '::1', '2025-11-30 07:53:49'),
(7, 2, 'User logged in successfully.', '::1', '2025-11-30 07:54:01'),
(8, 2, 'User logged in successfully.', '::1', '2025-12-02 09:25:32'),
(9, 2, 'User logged in successfully.', '::1', '2025-12-05 12:52:22'),
(10, 2, 'User logged in successfully.', '::1', '2025-12-06 08:12:21'),
(11, 2, 'User logged in successfully.', '::1', '2025-12-17 06:48:35'),
(12, 2, 'Room Booking confirmed: #1 for 2 nights.', '::1', '2025-12-17 06:54:39');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `food_included` enum('Yes','No') DEFAULT 'No',
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Pending','Confirmed','Cancelled','Completed') DEFAULT 'Pending',
  `invoice_no` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rooms_booked` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `room_id`, `table_id`, `check_in`, `check_out`, `check_in_time`, `check_out_time`, `food_included`, `total_price`, `status`, `invoice_no`, `created_at`, `rooms_booked`) VALUES
(1, 2, 4, NULL, '2025-12-17', '2025-12-19', '00:00:00', NULL, '', 24000.00, 'Confirmed', 'BKG-20251217122439-2', '2025-12-17 06:54:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `message_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_menu`
--

CREATE TABLE `food_menu` (
  `food_id` int(11) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `food_type` enum('Salad','Drinks','Starter','Breakfast','Lunch','Dinner','Sizzler','Combo','Dessert') DEFAULT 'Combo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_menu`
--

INSERT INTO `food_menu` (`food_id`, `food_name`, `description`, `price`, `food_type`, `created_at`) VALUES
(1, 'Caesar Salad', 'Crisp romaine lettuce with Caesar dressing, croutons, and parmesan', 280.00, 'Salad', '2025-12-05 14:39:02'),
(2, 'Caprese Salad', 'Fresh mozzarella, tomatoes, and basil drizzled with balsamic glaze', 300.00, 'Salad', '2025-12-05 14:39:02'),
(3, 'Quinoa and Pomegranate Salad', 'A hearty salad with quinoa, fresh mint, cucumber, and a tangy lemon dressing.', 360.00, 'Salad', '2025-12-05 14:39:02'),
(4, 'Mediterranean Feta Salad', 'Mixed greens with olives, tomatoes, cucumbers, bell peppers, and feta cheese.', 380.00, 'Salad', '2025-12-05 14:39:02'),
(5, 'Cold Coffee Frappe', 'Blended iced coffee with cream and sugar', 220.00, 'Drinks', '2025-12-05 14:39:02'),
(6, 'Cold Coco', 'Thick, rich chilled chocolate milk drink, a comforting classic.', 260.00, 'Drinks', '2025-12-05 14:39:02'),
(7, 'Fruit Salad (Beverage)', 'A thick, crushed-ice blend of seasonal fresh fruits with a hint of honey-lime dressing.', 300.00, 'Drinks', '2025-12-05 14:39:02'),
(8, 'Virgin Mojito', 'Refreshing drink with mint, lime, and soda', 190.00, 'Drinks', '2025-12-05 14:39:02'),
(9, 'Lemonade', 'Freshly squeezed lemon juice with sugar and water', 170.00, 'Drinks', '2025-12-05 14:39:02'),
(10, 'Mango Lassi', 'Sweet mango yogurt drink with a hint of cardamom', 210.00, 'Drinks', '2025-12-05 14:39:02'),
(11, 'Masala Chaas', 'Refreshing buttermilk flavored with mint, ginger, and cumin.', 160.00, 'Drinks', '2025-12-05 14:39:02'),
(12, 'Minestrone Soup', 'Hearty Italian vegetable soup with beans and pasta', 260.00, 'Starter', '2025-12-05 14:39:02'),
(13, 'Mushroom Soup', 'Creamy soup made with fresh mushrooms and herbs', 280.00, 'Starter', '2025-12-05 14:39:02'),
(14, 'Tom Yum Soup', 'Hot and sour Thai soup with herbs and veggies', 320.00, 'Starter', '2025-12-05 14:39:02'),
(15, 'Manchow Soup', 'Hot and spicy thick soup with mushrooms, vegetables, and a hint of ginger-garlic.', 340.00, 'Starter', '2025-12-05 14:39:02'),
(16, 'Tomato Soup', 'Classic, creamy tomato soup, seasoned with fresh basil and served with croutons.', 270.00, 'Starter', '2025-12-05 14:39:02'),
(17, 'Almond Broccoli Soup', 'Rich and healthy blend of broccoli puree with a creamy texture and toasted almonds.', 350.00, 'Starter', '2025-12-05 14:39:02'),
(18, 'Bruschetta', 'Grilled bread with tomato, garlic, olive oil, and basil', 300.00, 'Starter', '2025-12-05 14:39:02'),
(19, 'Veg Kebab Platter', 'Assortment of grilled vegetarian kebabs with chutney', 420.00, 'Starter', '2025-12-05 14:39:02'),
(20, 'Paneer Tikka', 'Grilled paneer cubes marinated in Indian spices', 380.00, 'Starter', '2025-12-05 14:39:02'),
(21, 'Masala Dosa', 'Crispy South Indian crepe stuffed with spiced mashed potatoes', 220.00, 'Breakfast', '2025-12-05 14:39:02'),
(22, 'Aloo Paratha', 'Stuffed flatbread with spiced potato filling', 200.00, 'Breakfast', '2025-12-05 14:39:02'),
(23, 'Samosa (2 pcs)', 'Flaky pastry filled with spiced potatoes and peas, served with chutney.', 180.00, 'Breakfast', '2025-12-05 14:39:02'),
(24, 'Idli Sambar', 'Steamed rice cakes served with savory lentil soup (Sambar) and coconut chutney.', 230.00, 'Breakfast', '2025-12-05 14:39:02'),
(25, 'Poha', 'Flattened rice cooked with turmeric, mustard seeds, onions, and curry leaves.', 180.00, 'Breakfast', '2025-12-05 14:39:02'),
(26, 'Upma', 'Thick porridge made from dry-roasted semolina, savory and light.', 190.00, 'Breakfast', '2025-12-05 14:39:02'),
(27, 'Aloo Puri', 'Fried whole wheat bread (Puri) served with spiced potato curry (Aloo Bhaji).', 240.00, 'Breakfast', '2025-12-05 14:39:02'),
(28, 'Bread Toast & Jam', 'Classic white or brown toast served with butter and assorted jams.', 150.00, 'Breakfast', '2025-12-05 14:39:02'),
(29, 'Dal Tadka', 'Yellow lentils tempered with garlic and ghee', 320.00, 'Lunch', '2025-12-05 14:39:02'),
(30, 'Veggie Burger', 'Grilled vegetable patty with lettuce, tomato, and cheese in a bun', 360.00, 'Lunch', '2025-12-05 14:39:02'),
(31, 'Pav Bhaji', 'Smooth mashed vegetable curry served with buttered pav', 330.00, 'Lunch', '2025-12-05 14:39:02'),
(32, 'Pasta Alfredo', 'Creamy white sauce pasta with cheese and herbs', 420.00, 'Lunch', '2025-12-05 14:39:02'),
(33, 'Pasta Arrabbiata', 'Penne pasta tossed in spicy tomato sauce', 400.00, 'Lunch', '2025-12-05 14:39:02'),
(34, 'Vegetable Stir Fry', 'Mixed vegetables sautéed in a savory sauce', 380.00, 'Lunch', '2025-12-05 14:39:02'),
(35, 'Ramen (Veg)', 'Japanese noodle soup with veggies and flavorful broth', 450.00, 'Lunch', '2025-12-05 14:39:02'),
(36, 'Paneer Shawarma', 'Indian-style shawarma stuffed with marinated paneer and veggies', 410.00, 'Lunch', '2025-12-05 14:39:02'),
(37, 'Falafel Wrap', 'Middle Eastern wrap filled with crispy falafel and tahini sauce', 360.00, 'Lunch', '2025-12-05 14:39:02'),
(38, 'Mexican Burrito', 'Tortilla stuffed with beans, rice, veggies, and cheese', 430.00, 'Lunch', '2025-12-05 14:39:02'),
(39, 'Paneer Butter Masala', 'Soft paneer cubes cooked in creamy tomato gravy', 460.00, 'Dinner', '2025-12-05 14:39:02'),
(40, 'Veg Biryani', 'Aromatic basmati rice cooked with mixed vegetables and spices', 420.00, 'Dinner', '2025-12-05 14:39:02'),
(41, 'Palak Paneer', 'Cottage cheese cooked with spinach puree', 440.00, 'Dinner', '2025-12-05 14:39:02'),
(42, 'Malai Kofta', 'Creamy curry with soft paneer-potato dumplings', 430.00, 'Dinner', '2025-12-05 14:39:02'),
(43, 'Margherita Pizza', 'Classic pizza topped with tomato sauce, mozzarella, and basil', 480.00, 'Dinner', '2025-12-05 14:39:02'),
(44, 'Veg Lasagna', 'Layered pasta baked with vegetables, cheese, and tomato sauce', 520.00, 'Dinner', '2025-12-05 14:39:02'),
(45, 'Risotto Primavera', 'Creamy Italian rice dish with mixed vegetables', 460.00, 'Dinner', '2025-12-05 14:39:02'),
(46, 'Fettuccine Alfredo', 'Fettuccine pasta in a rich and creamy Alfredo sauce', 480.00, 'Dinner', '2025-12-05 14:39:02'),
(47, 'Thai Green Curry', 'Coconut-based curry curry cooked with vegetables and aromatic herbs', 520.00, 'Dinner', '2025-12-05 14:39:02'),
(48, 'BBQ Paneer Pizza', 'Pizza topped with smoky BBQ paneer and veggies', 500.00, 'Dinner', '2025-12-05 14:39:02'),
(49, 'Rajasthani Dal Bati & Choorma', 'Traditional dish of baked wheat balls (Bati), mixed lentils (Dal), and sweet crumbled wheat (Choorma).', 650.00, 'Dinner', '2025-12-05 14:39:02'),
(50, 'Veg Momos', 'Steamed Tibetan dumplings filled with finely chopped vegetables and spices.', 280.00, 'Combo', '2025-12-05 14:39:02'),
(51, 'Chole Bhature', 'Spicy chickpeas served with fried bread', 320.00, 'Combo', '2025-12-05 14:39:02'),
(52, 'Veg Manchurian', 'Indo-Chinese dish with fried vegetable balls in spicy sauce', 340.00, 'Combo', '2025-12-05 14:39:02'),
(53, 'Garlic Bread', 'Toasted bread with garlic butter and herbs', 240.00, 'Combo', '2025-12-05 14:39:02'),
(54, 'Spring Rolls', 'Crispy rolls filled with mixed vegetables and served with dipping sauce', 300.00, 'Combo', '2025-12-05 14:39:02'),
(55, 'Hakka Noodles', 'Stir-fried noodles tossed with veggies and soy-based seasoning', 330.00, 'Combo', '2025-12-05 14:39:02'),
(56, 'Sushi Veg Roll', 'Japanese-style rice rolls filled with fresh vegetables', 480.00, 'Combo', '2025-12-05 14:39:02'),
(57, 'Nachos with Cheese Dip', 'Crispy nachos served with melted cheese and salsa', 300.00, 'Combo', '2025-12-05 14:39:02'),
(58, 'Hummus Platter', 'Creamy chickpea spread served with pita bread and veggies', 360.00, 'Combo', '2025-12-05 14:39:02'),
(59, 'Quesadilla', 'Grilled tortilla stuffed with cheese and sautéed vegetables', 340.00, 'Combo', '2025-12-05 14:39:02'),
(60, 'Peri Peri Fries', 'Crispy fries tossed in peri peri seasoning', 220.00, 'Combo', '2025-12-05 14:39:02'),
(61, 'Veg Spring Roll', 'Crispy rolls filled with mixed vegetables', 260.00, 'Combo', '2025-12-05 14:39:02'),
(62, 'Gulab Jamun', 'Sweet milk dumplings soaked in sugar syrup', 180.00, 'Dessert', '2025-12-05 14:39:02'),
(63, 'Tiramisu (Eggless)', 'Classic Italian dessert made with mascarpone and coffee', 320.00, 'Dessert', '2025-12-05 14:39:02'),
(64, 'Chocolate Brownie', 'Rich chocolate brownie served warm', 260.00, 'Dessert', '2025-12-05 14:39:02'),
(65, 'Blueberry Cheesecake', 'Creamy cheesecake topped with blueberry compote', 350.00, 'Dessert', '2025-12-05 14:39:02'),
(66, 'Rasmalai', 'Soft paneer discs soaked in sweetened saffron milk', 280.00, 'Dessert', '2025-12-05 14:39:02'),
(67, 'Jalebi', 'Crispy, deep-fried coils of fermented batter soaked in sugar syrup.', 220.00, 'Dessert', '2025-12-05 14:39:02'),
(68, 'Rajasthani Ghevar', 'Disc-shaped sweet made from flour, soaked in sugar syrup and topped with rabri.', 320.00, 'Dessert', '2025-12-05 14:39:02'),
(69, 'Rasgulla', 'Spongy cottage cheese balls soaked in light sugar syrup.', 260.00, 'Dessert', '2025-12-05 14:39:02'),
(70, 'Basundi', 'Sweetened dense milk prepared by slow-boiling milk until reduced.', 300.00, 'Dessert', '2025-12-05 14:39:02'),
(71, 'Angoor Rabdi', 'Sweet, miniature rasgullas served in thick, flavored milk.', 330.00, 'Dessert', '2025-12-05 14:39:02'),
(72, 'Sitafal Rabdi', 'Thick, creamy rabdi infused with fresh custard apple (sitafal).', 360.00, 'Dessert', '2025-12-05 14:39:02'),
(73, 'Paneer Jalebi', 'Jalebi made from paneer (cottage cheese), offering a richer texture.', 240.00, 'Dessert', '2025-12-05 14:39:02'),
(74, 'Surati Ghari', 'Sweet, round pastry dessert originating from Surat, made with dry fruits and ghee.', 300.00, 'Dessert', '2025-12-05 14:39:02'),
(75, 'Veg Sizzler Platter', 'Assorted grilled vegetables, paneer, fries, rice, and special sizzler sauce served on a hot plate.', 620.00, 'Sizzler', '2025-12-17 08:06:40'),
(76, 'Paneer Sizzler', 'Marinated paneer steaks grilled with vegetables, fries, and spicy sizzler gravy.', 650.00, 'Sizzler', '2025-12-17 08:06:40'),
(77, 'Mexican Veg Sizzler', 'Mexican-style vegetables, beans, rice, fries, and chipotle sauce served sizzling hot.', 670.00, 'Sizzler', '2025-12-17 08:06:40'),
(78, 'Chinese Veg Sizzler', 'Stir-fried vegetables in Schezwan sauce with noodles and crispy fries on a hot plate.', 640.00, 'Sizzler', '2025-12-17 08:06:40'),
(79, 'Italian Veg Sizzler', 'Grilled vegetables with herbed rice, fries, and creamy Italian sauce served sizzling.', 660.00, 'Sizzler', '2025-12-17 08:06:40');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_no` varchar(20) NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `ac_type` enum('AC','Non-AC') DEFAULT 'Non-AC',
  `price_per_night` decimal(10,2) NOT NULL,
  `capacity` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Available','Booked','Maintenance') DEFAULT 'Available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_rooms` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_no`, `room_type`, `ac_type`, `price_per_night`, `capacity`, `description`, `image`, `status`, `created_at`, `total_rooms`) VALUES
(1, '1', 'Economy Single Room', 'Non-AC', 3500.00, 2, 'Budget-friendly single room with essential amenities and clean comfort.', 'economy_single.jpg', 'Available', '2025-11-03 07:37:39', 10),
(2, '2', 'Twin Standard Room', 'AC', 6500.00, 4, 'Cozy twin-bed setup with full amenities, ideal for families or small groups.', 'twin_standard.jpg', 'Available', '2025-11-03 07:37:39', 10),
(3, '3', 'Deluxe King Suite', 'AC', 10000.00, 2, 'Spacious suite with a king bed, city view, and premium bath amenities.', 'deluxe_king.jpg', 'Available', '2025-11-03 07:37:39', 10),
(4, '4', 'Business Studio', 'AC', 12000.00, 2, 'Smartly designed for business travelers — work desk, fast WiFi, and privacy.', 'business_studio.jpg', 'Available', '2025-11-03 07:37:39', 10),
(5, '5', 'Family Connecting Suite', 'AC', 20000.00, 6, 'Two connected rooms with a shared living area, great for large families.', 'family_suite.jpg', 'Available', '2025-11-03 07:37:39', 10),
(6, '6', 'Executive Penthouse', 'AC', 26000.00, 3, 'Luxury penthouse with panoramic views, private balcony, and concierge service.', 'executive_penthouse.jpg', 'Available', '2025-11-03 07:37:39', 10),
(7, '7', 'Presidential Suite', 'AC', 45000.00, 4, 'Top-tier suite with a private jacuzzi, dining area, and dedicated butler.', 'presidential_suite.jpg', 'Available', '2025-11-03 07:37:39', 10),
(8, '8', 'The Imperial Grand', 'AC', 75000.00, 4, 'Includes private pool, 24/7 butler, and a secured private entrance.', 'imperial_grand.jpg', 'Available', '2025-11-03 07:37:39', 10),
(9, '9', 'Midnight Indulgence Suite', 'AC', 85000.00, 2, 'A high-privacy suite with all-night concierge access, curated ambiance, discreet in-room services, and premium entertainment options.', 'midnight_indulgence.jpg', 'Available', '2025-11-03 07:37:39', 5),
(10, '10', 'Lifestyle Concierge Suite', 'AC', 95000.00, 2, 'A premium haven with a dedicated lifestyle concierge available round-the-clock for tailored experiences and seamless stay support.', 'lifestyle_concierge.jpg', 'Available', '2025-11-03 07:37:39', 5),
(11, '11', 'The Diplomat’s Arrangement', 'AC', 150000.00, 2, 'Reinforced suite with secure elevator access and counter-surveillance systems.', 'diplomat_arrangement.jpg', 'Available', '2025-11-03 07:37:39', 5),
(12, '12', 'The Zenith Sovereign', 'AC', 250000.00, 2, 'Pinnacle of luxury: holographic environment, private chef, and daily spa.', 'zenith_sovereign.jpg', 'Available', '2025-11-03 07:37:39', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tables_list`
--

CREATE TABLE `tables_list` (
  `table_id` int(11) NOT NULL,
  `table_no` varchar(20) NOT NULL,
  `capacity` int(11) NOT NULL,
  `price_per_hour` decimal(10,2) NOT NULL,
  `status` enum('Available','Booked','Maintenance') DEFAULT 'Available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `phone`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@hotel.com', '9999999999', 'admin123', 'admin', '2025-11-03 06:01:59'),
(2, 'Abhishek', 'test@gmail.com', '1234567890', '123', 'user', '2025-11-27 14:02:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD UNIQUE KEY `invoice_no` (`invoice_no`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `food_menu`
--
ALTER TABLE `food_menu`
  ADD PRIMARY KEY (`food_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD UNIQUE KEY `invoice_no` (`invoice_no`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD UNIQUE KEY `room_no` (`room_no`);

--
-- Indexes for table `tables_list`
--
ALTER TABLE `tables_list`
  ADD PRIMARY KEY (`table_id`),
  ADD UNIQUE KEY `table_no` (`table_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_menu`
--
ALTER TABLE `food_menu`
  MODIFY `food_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tables_list`
--
ALTER TABLE `tables_list`
  MODIFY `table_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`table_id`) REFERENCES `tables_list` (`table_id`) ON DELETE SET NULL;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
