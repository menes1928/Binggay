-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2025 at 02:03 PM
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
-- Database: `sandok`
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
  `cp_name` varchar(255) DEFAULT NULL,
  `cp_desc` text DEFAULT NULL,
  `cp_price` decimal(10,2) DEFAULT NULL,
  `cp_avail` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_id` int(11) NOT NULL,
  `emp_fn` varchar(255) NOT NULL,
  `emp_ln` varchar(255) NOT NULL,
  `emp_sex` varchar(6) NOT NULL,
  `emp_email` varchar(100) NOT NULL,
  `emp_phone` varchar(255) NOT NULL,
  `emp_role` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `emp_fn`, `emp_ln`, `emp_sex`, `emp_email`, `emp_phone`, `emp_role`, `created_at`) VALUES
(1, 'Kyle', 'Vanleet', 'Male', 'f@gmaail.com', '09674535234', 'Chef', '2025-06-05 15:36:36'),
(2, 'John', 'Def', 'Male', 'jd@gmail.com', '09125637121', 'Dishwasher', '2025-06-12 05:02:51'),
(3, 'Francine', 'Diaz', 'Female', 'fd@gmail.com', '0965237482323', 'FoodAttendant', '2025-06-12 05:07:18');

-- --------------------------------------------------------

--
-- Table structure for table `eventbookings`
--

CREATE TABLE `eventbookings` (
  `eb_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `eb_name` varchar(255) NOT NULL,
  `eb_contact` varchar(11) NOT NULL,
  `eb_type` varchar(100) NOT NULL,
  `eb_venue` varchar(255) NOT NULL,
  `eb_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `eb_guest` varchar(100) NOT NULL,
  `eb_order` varchar(100) NOT NULL,
  `eb_req` varchar(255) DEFAULT NULL,
  `eb_status` varchar(100) NOT NULL,
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
  `menu_avail` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `menu_name`, `menu_desc`, `menu_pax`, `menu_price`, `menu_avail`, `created_at`, `is_deleted`) VALUES
(1, 'Sisig na bangus', 'Tenga ng isda', 'pax6-8', 500.00, 0, '2025-06-12 04:54:39', 0),
(2, 'Lomi', 'asdaw', 'pax6-8', 300.00, 1, '2025-06-12 04:55:55', 0),
(3, 'Afritada', 'baboy na may tomato sauce', 'pax10-15', 900.00, 1, '2025-06-12 06:02:19', 0);

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
  `oa_province` varchar(100) DEFAULT NULL
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
-- Table structure for table `orderpromotions`
--

CREATE TABLE `orderpromotions` (
  `op_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `promotion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderreview`
--

CREATE TABLE `orderreview` (
  `or_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `review_id` int(11) NOT NULL
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
  `pay_status` enum('Paid','Pending') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promotion`
--

CREATE TABLE `promotion` (
  `promotion_id` int(11) NOT NULL,
  `promotion_name` varchar(100) DEFAULT NULL,
  `promotion_desc` text DEFAULT NULL,
  `promotion_disc` decimal(5,2) NOT NULL,
  `promotion_start` date DEFAULT NULL,
  `promotion_end` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `promotion_active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotion`
--

INSERT INTO `promotion` (`promotion_id`, `promotion_name`, `promotion_desc`, `promotion_disc`, `promotion_start`, `promotion_end`, `promotion_active`) VALUES
(1, 'Summer FEST', '20% Discount for summmer season', 20.00, '2025-06-12', '2025-06-29 16:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `review_text` text DEFAULT NULL,
  `review_date` date DEFAULT NULL
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
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_fn`, `user_ln`, `user_sex`, `user_email`, `user_phone`, `user_username`, `user_password`, `user_photo`, `user_type`, `created_at`, `updated_at`) VALUES
(1, 'Brian', 'Maines', 'Male', 'bm@gmail.com', '09562348223', 'bm19', '$2y$10$9hyaTdZG9UfqXXdFTlvqqu6zpwOJwkGZnhGYARqbdQHXWzoiHm8la', '../uploads/profile/6846b7a0d3523_Screenshot 2025-06-04 101948.png', 1, '2025-06-09 10:29:52', '2025-06-09 10:30:58'),
(2, 'John', 'Andal', 'Male', 'ja@gmail.com', '09162537182', 'ja19', '$2y$10$HHeB9p799AK/zO/zZWHbW.l/.grez5/kYwq2zcuLekubExZ6hs4ae', '../uploads/profile/68492160a5044_luffy.png', 0, '2025-06-11 06:25:36', '2025-06-11 06:25:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `cateringpackages`
--
ALTER TABLE `cateringpackages`
  ADD PRIMARY KEY (`cp_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `eventbookings`
--
ALTER TABLE `eventbookings`
  ADD PRIMARY KEY (`eb_id`),
  ADD KEY `user_id` (`user_id`);

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
  ADD KEY `category_id` (`category_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `orderaddress`
--
ALTER TABLE `orderaddress`
  ADD PRIMARY KEY (`oa_id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`oi_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `orderpromotions`
--
ALTER TABLE `orderpromotions`
  ADD PRIMARY KEY (`op_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `promotion_id` (`promotion_id`);

--
-- Indexes for table `orderreview`
--
ALTER TABLE `orderreview`
  ADD PRIMARY KEY (`or_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `review_id` (`review_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`pay_id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `payments_ibfk_3` (`cp_id`);

--
-- Indexes for table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`promotion_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_username` (`user_username`);

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
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `eventbookings`
--
ALTER TABLE `eventbookings`
  MODIFY `eb_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `orderpromotions`
--
ALTER TABLE `orderpromotions`
  MODIFY `op_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderreview`
--
ALTER TABLE `orderreview`
  MODIFY `or_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `promotion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `eventbookings`
--
ALTER TABLE `eventbookings`
  ADD CONSTRAINT `eventbookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `menucategory`
--
ALTER TABLE `menucategory`
  ADD CONSTRAINT `menucategory_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menucategory_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE CASCADE;

--
-- Constraints for table `orderaddress`
--
ALTER TABLE `orderaddress`
  ADD CONSTRAINT `orderaddress_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE CASCADE;

--
-- Constraints for table `orderpromotions`
--
ALTER TABLE `orderpromotions`
  ADD CONSTRAINT `orderpromotions_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderpromotions_ibfk_2` FOREIGN KEY (`promotion_id`) REFERENCES `promotion` (`promotion_id`) ON DELETE CASCADE;

--
-- Constraints for table `orderreview`
--
ALTER TABLE `orderreview`
  ADD CONSTRAINT `orderreview_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderreview_ibfk_2` FOREIGN KEY (`review_id`) REFERENCES `review` (`review_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`cp_id`) REFERENCES `cateringpackages` (`cp_id`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
