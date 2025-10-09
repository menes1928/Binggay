-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2025 at 07:45 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sandokdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cateringpackages`
--

CREATE TABLE `cateringpackages` (
  `cp_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cp_name` varchar(255) NOT NULL,
  `cp_phone` varchar(11) NOT NULL,
  `cp_place` varchar(255) NOT NULL,
  `cp_date` date NOT NULL,
  `cp_price` decimal(10,2) NOT NULL,
  `cp_addon_pax` int(11) DEFAULT NULL,
  `cp_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eventbookings`
--

CREATE TABLE `eventbookings` (
  `eb_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_type_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `eb_name` varchar(255) NOT NULL,
  `eb_contact` varchar(11) NOT NULL,
  `eb_venue` varchar(255) NOT NULL,
  `eb_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `eb_order` varchar(100) NOT NULL,
  `eb_status` varchar(100) NOT NULL,
  `eb_addon_pax` int(11) DEFAULT NULL,
  `eb_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_types`
--

CREATE TABLE `event_types` (
  `event_type_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `min_package_pax` enum('50','100','150','200') DEFAULT NULL,
  `max_package_pax` enum('50','100','150','200') DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_type_packages`
--

CREATE TABLE `event_type_packages` (
  `event_type_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `menu_name` varchar(100) DEFAULT NULL,
  `menu_desc` text DEFAULT NULL,
  `menu_pax` varchar(100) NOT NULL,
  `menu_price` decimal(10,2) NOT NULL,
  `menu_pic` varchar(255) NOT NULL,
  `menu_avail` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menucategory`
--

CREATE TABLE `menucategory` (
  `mc_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderaddress`
--

CREATE TABLE `orderaddress` (
  `oa_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `oa_street` varchar(100) DEFAULT NULL,
  `oa_city` varchar(100) DEFAULT NULL,
  `oa_province` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `oi_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `oi_quantity` decimal(10,2) DEFAULT NULL,
  `oi_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_status` enum('pending','in progress','completed','canceled') DEFAULT NULL,
  `order_amount` decimal(10,2) NOT NULL,
  `order_needed` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `package_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `pax` enum('50','100','150','200') NOT NULL,
  `base_price` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`package_id`, `name`, `pax`, `base_price`, `is_active`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Standard', '50', 35000.00, 1, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42');

-- --------------------------------------------------------

--
-- Table structure for table `package_items`
--

CREATE TABLE `package_items` (
  `item_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `item_label` varchar(255) NOT NULL,
  `qty` int(11) DEFAULT NULL,
  `unit` enum('pax','pcs','cups','attendants','dish','other') DEFAULT 'other',
  `is_optional` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `item_pic` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package_items`
--

INSERT INTO `package_items` (`item_id`, `package_id`, `item_label`, `qty`, `unit`, `is_optional`, `sort_order`, `item_pic`, `created_at`, `updated_at`) VALUES
(1, 1, 'Beef Menu', NULL, 'dish', 0, 0, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42'),
(2, 1, 'Pork Menu', NULL, 'dish', 0, 1, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42'),
(3, 1, 'Chicken Menu', NULL, 'dish', 0, 2, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42'),
(4, 1, 'Rice', NULL, 'dish', 0, 3, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42'),
(5, 1, 'Veggies/Pasta/Fish Fillet(choose 1)', NULL, 'dish', 0, 4, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42'),
(6, 1, '50 Cups of Desserts', 50, 'cups', 0, 5, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42'),
(7, 1, 'Backdrop & Platform/ Complete Setup', NULL, 'other', 0, 6, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42'),
(8, 1, 'Elegant Table Buffet', NULL, 'other', 0, 7, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42'),
(9, 1, '6 Chaffing Dish', 6, 'other', 0, 8, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42'),
(10, 1, 'Banquet Complete Setup', NULL, 'other', 0, 9, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42'),
(11, 1, 'Tables and Chairs with cover', NULL, 'other', 0, 10, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42'),
(12, 1, 'Artificial Flowers and Balloons for Decoration', NULL, 'other', 0, 11, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42'),
(13, 1, '60 Pax Silverware and Dinner ware', 60, 'pcs', 0, 12, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42'),
(14, 1, '2 Food Attendants', 2, 'attendants', 0, 13, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42'),
(15, 1, 'Elegant Table Buffet', NULL, 'other', 0, 14, '', '2025-10-09 17:43:42', '2025-10-09 17:43:42');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `pay_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `cp_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `pay_amount` decimal(10,2) DEFAULT NULL,
  `pay_method` enum('Cash','Online','Credit') DEFAULT NULL,
  `pay_status` enum('Paid','Partial','Pending') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_fn` varchar(255) NOT NULL,
  `user_ln` varchar(255) NOT NULL,
  `user_sex` varchar(6) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_phone` varchar(255) NOT NULL,
  `user_username` varchar(255) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_photo` varchar(255) DEFAULT NULL,
  `user_type` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `uk_category_name` (`category_name`);

--
-- Indexes for table `cateringpackages`
--
ALTER TABLE `cateringpackages`
  ADD PRIMARY KEY (`cp_id`),
  ADD KEY `idx_cateringpackages_user_id` (`user_id`);

--
-- Indexes for table `eventbookings`
--
ALTER TABLE `eventbookings`
  ADD PRIMARY KEY (`eb_id`),
  ADD KEY `idx_eventbookings_user_id` (`user_id`),
  ADD KEY `idx_eventbookings_event_type` (`event_type_id`),
  ADD KEY `idx_eventbookings_package` (`package_id`),
  ADD KEY `fk_eventbookings_allowed_package` (`event_type_id`,`package_id`);

--
-- Indexes for table `event_types`
--
ALTER TABLE `event_types`
  ADD PRIMARY KEY (`event_type_id`),
  ADD UNIQUE KEY `uk_event_types_name` (`name`);

--
-- Indexes for table `event_type_packages`
--
ALTER TABLE `event_type_packages`
  ADD PRIMARY KEY (`event_type_id`,`package_id`),
  ADD KEY `fk_etp_package` (`package_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `menucategory`
--
ALTER TABLE `menucategory`
  ADD PRIMARY KEY (`mc_id`),
  ADD KEY `idx_mc_category_id` (`category_id`),
  ADD KEY `idx_mc_menu_id` (`menu_id`);

--
-- Indexes for table `orderaddress`
--
ALTER TABLE `orderaddress`
  ADD PRIMARY KEY (`oa_id`),
  ADD UNIQUE KEY `uk_oa_order_id` (`order_id`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`oi_id`),
  ADD KEY `idx_oi_order_id` (`order_id`),
  ADD KEY `idx_oi_menu_id` (`menu_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `idx_orders_user_id` (`user_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`package_id`),
  ADD UNIQUE KEY `uk_packages_name_pax` (`name`,`pax`),
  ADD KEY `idx_packages_pax` (`pax`);

--
-- Indexes for table `package_items`
--
ALTER TABLE `package_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `idx_package_items_package_id` (`package_id`,`sort_order`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`pay_id`),
  ADD UNIQUE KEY `uk_payments_order_id` (`order_id`),
  ADD KEY `idx_payments_cp_id` (`cp_id`),
  ADD KEY `idx_payments_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uk_users_username` (`user_username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cateringpackages`
--
ALTER TABLE `cateringpackages`
  MODIFY `cp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eventbookings`
--
ALTER TABLE `eventbookings`
  MODIFY `eb_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_types`
--
ALTER TABLE `event_types`
  MODIFY `event_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menucategory`
--
ALTER TABLE `menucategory`
  MODIFY `mc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderaddress`
--
ALTER TABLE `orderaddress`
  MODIFY `oa_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `oi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `package_items`
--
ALTER TABLE `package_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cateringpackages`
--
ALTER TABLE `cateringpackages`
  ADD CONSTRAINT `fk_cateringpackages_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `eventbookings`
--
ALTER TABLE `eventbookings`
  ADD CONSTRAINT `fk_eventbookings_allowed_package` FOREIGN KEY (`event_type_id`,`package_id`) REFERENCES `event_type_packages` (`event_type_id`, `package_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_eventbookings_event_type` FOREIGN KEY (`event_type_id`) REFERENCES `event_types` (`event_type_id`),
  ADD CONSTRAINT `fk_eventbookings_package` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`),
  ADD CONSTRAINT `fk_eventbookings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `event_type_packages`
--
ALTER TABLE `event_type_packages`
  ADD CONSTRAINT `fk_etp_event_type` FOREIGN KEY (`event_type_id`) REFERENCES `event_types` (`event_type_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_etp_package` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`) ON DELETE CASCADE;

--
-- Constraints for table `menucategory`
--
ALTER TABLE `menucategory`
  ADD CONSTRAINT `fk_mc_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_mc_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE CASCADE;

--
-- Constraints for table `orderaddress`
--
ALTER TABLE `orderaddress`
  ADD CONSTRAINT `fk_oa_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `fk_oi_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_oi_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `package_items`
--
ALTER TABLE `package_items`
  ADD CONSTRAINT `fk_package_items_package` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_cp` FOREIGN KEY (`cp_id`) REFERENCES `cateringpackages` (`cp_id`),
  ADD CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `fk_payments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
