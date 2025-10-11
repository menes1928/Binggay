-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2025 at 08:50 PM
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

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(5, 'Beef'),
(7, 'Best Sellers'),
(3, 'Chicken'),
(1, 'Pasta'),
(4, 'Pork'),
(6, 'Seafood'),
(2, 'Vegetables');

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

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `menu_name`, `menu_desc`, `menu_pax`, `menu_price`, `menu_pic`, `menu_avail`, `created_at`) VALUES
(1, 'Special Pansit', NULL, '10-15 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(2, 'Meaty Spaghetti', NULL, '10-15 pax', 1000.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(3, 'Tuna Carbonara', NULL, '10-15 pax', 1000.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(4, 'Ham/Bacon Carbonara', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(5, 'Tuna Pesto', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(6, 'Special Laing w/ Liempo and Shrimp (Full Pan)', NULL, '10-15 pax', 1500.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(7, 'Special Laing w/ Liempo and Shrimp (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(8, 'Special Chopsuey', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(9, 'Vegetables Kare-kare', NULL, '10-15 pax', 1000.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(10, 'Buttered Mixed Veggies', NULL, '10-15 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(11, 'Special Pakbet', NULL, '10-15 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(12, 'Stirred Veggies w/ Tokwa', NULL, '10-15 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(13, 'Lumpiang Hubad w/ special sauce', NULL, '10-15 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(14, 'Fried Chicken (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(15, 'Fried Chicken (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(16, 'Chicken Afritada (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(17, 'Chicken Afritada (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(18, 'Baked Tahong', '3 kls per pan', '10-15 pax', 1000.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(19, 'Butter Shrimp', '1 kl per pan', '10-15 pax', 1300.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(20, 'Shrimp Salvatore', '1 kl per pan', '10-15 pax', 1500.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(21, 'Fish Fillet', '2 kls per pan', '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(22, 'Relyenong Bangus', 'Min. 5 pcs (500g-600g each)', '5 pcs', 285.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(23, 'Seafood Kare-kare', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(24, 'Pork Barbeque', NULL, 'per piece', 35.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(25, 'Chicken Cordon Bleu (Large Pan)', '12 pcs', '12 pcs', 1500.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(26, 'Chicken Cordon Bleu (Medium Pan)', '6 pcs', '6 pcs', 800.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(27, 'Chicken Cordon Bleu (Tub)', '3 pcs', '3 pcs', 360.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(28, 'Pork Shanghai', NULL, '10-15 pax', 600.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(29, 'Special Puto Cheese', NULL, 'per piece', 5.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(30, 'Lumpiang Sariwa', NULL, 'per piece', 40.00, 'default.jpg', 1, '2025-06-14 05:05:25'),
(31, 'Chicken Adobo (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(32, 'Chicken Adobo (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(33, 'Chicken Adobo w/ Liver and Gizzard (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(34, 'Chicken Adobo w/ Liver and Gizzard (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(35, 'Chicken Buffalo (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(36, 'Chicken Buffalo (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(37, 'Orange Chicken (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(38, 'Orange Chicken (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(39, 'Honey Glazed Chicken (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(40, 'Honey Glazed Chicken (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(41, 'Chicken Caldereta (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(42, 'Chicken Caldereta (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(43, 'Ginataang Manok (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(44, 'Ginataang Manok (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(45, 'Tinola (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(46, 'Tinola (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(47, 'Pastel (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(48, 'Pastel (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(49, 'Pininyahang Manok (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(50, 'Pininyahang Manok (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(51, 'Chicken Mushroom Sauce (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(52, 'Chicken Mushroom Sauce (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(53, 'Chicken Barbeque (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(54, 'Chicken Barbeque (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(55, 'Chicken Lollipop (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(56, 'Chicken Lollipop (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(57, 'Honey Butter Glazed Chicken (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(58, 'Honey Butter Glazed Chicken (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(59, 'Paksiw na Lechon Manok (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(60, 'Paksiw na Lechon Manok (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 05:08:41'),
(61, 'Pork Menudo (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(62, 'Pork Menudo (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(63, 'Pork Afritada (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(64, 'Pork Afritada (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(65, 'Pork Caldereta (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(66, 'Pork Caldereta (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(67, 'Bicol Express (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(68, 'Bicol Express (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(69, 'Pork Binagoongan (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(70, 'Pork Binagoongan (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(71, 'Pork Dinuguan (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(72, 'Pork Dinuguan (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(73, 'Crispy Kare-kare (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(74, 'Crispy Kare-kare (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(75, 'Pata Kare-kare (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(76, 'Pata Kare-kare (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(77, 'Pork Adobo (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(78, 'Pork Adobo (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(79, 'Pork Humba (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(80, 'Pork Humba (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(81, 'Pork Estofado (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(82, 'Pork Estofado (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(83, 'Pork Sinigang (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(84, 'Pork Sinigang (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(85, 'Pork Nilaga (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(86, 'Pork Nilaga (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(87, 'Bbq Spare Ribs (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(88, 'Bbq Spare Ribs (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(89, 'Pork Higado (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(90, 'Pork Higado (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(91, 'Baby Back Ribs (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(92, 'Baby Back Ribs (Full Pan)', '', '10-15', 1400.00, 'menu_68ea6362365639.44210674.jpg', 1, '2025-06-14 05:09:01'),
(93, 'Tokwa\'t Baboy (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(94, 'Tokwa\'t Baboy (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(95, 'Calderobo (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(96, 'Calderobo (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(97, 'Menudillo w/ Quail Egg (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(98, 'Menudillo w/ Quail Egg (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(99, 'Pork and Liver Adobo (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(100, 'Pork and Liver Adobo (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(101, 'Pork Pochero (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(102, 'Pork Pochero (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(103, 'Sweet and Sour Pork (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(104, 'Sweet and Sour Pork (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(105, 'Inihaw na Liempo (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(106, 'Inihaw na Liempo (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(107, 'Pork Steak (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(108, 'Pork Steak (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 05:09:01'),
(109, 'Beef Broccoli (Half Pan)', NULL, '6-8 pax', 900.00, 'default.jpg', 1, '2025-06-14 05:09:20'),
(110, 'Beef Broccoli (Full Pan)', NULL, '10-15 pax', 1600.00, 'default.jpg', 1, '2025-06-14 05:09:20'),
(111, 'Beef in Mushroom Sauce (Half Pan)', NULL, '6-8 pax', 900.00, 'default.jpg', 1, '2025-06-14 05:09:20'),
(112, 'Beef in Mushroom Sauce (Full Pan)', NULL, '10-15 pax', 1600.00, 'default.jpg', 1, '2025-06-14 05:09:20'),
(113, 'Beef Kare-kare (Half Pan)', NULL, '6-8 pax', 900.00, 'default.jpg', 1, '2025-06-14 05:09:20'),
(114, 'Beef Kare-kare (Full Pan)', NULL, '10-15 pax', 1600.00, 'default.jpg', 1, '2025-06-14 05:09:20'),
(115, 'Beef Caldereta (Half Pan)', NULL, '6-8 pax', 900.00, 'default.jpg', 1, '2025-06-14 05:09:20'),
(116, 'Beef Caldereta (Full Pan)', '', '10-15', 1600.00, '', 1, '2025-06-14 05:09:20'),
(117, 'Beef Sinigang (Half Pan)', '', '6-8', 900.00, '', 1, '2025-06-14 05:09:20'),
(118, 'Beef Sinigang (Full Pan)', '', '10-15', 1600.00, '', 1, '2025-06-14 05:09:20'),
(119, 'Beef Nilaga or Bulalo (Half Pan)', '', '6-8', 900.00, '', 1, '2025-06-14 05:09:20'),
(120, 'Beef Nilaga or Bulalo (Full Pan)', '', '10-15', 1600.00, '', 1, '2025-06-14 05:09:20'),
(121, 'Beef Garlic Pepper Steak (Half Pan)', '', '6-8', 900.00, '', 1, '2025-06-14 05:09:20'),
(122, 'Beef Garlic Pepper Steak (Full Pan)', '', '10-15', 1600.00, '', 1, '2025-06-14 05:09:20'),
(123, 'Beef Steak (Half Pan)', '', '6-8', 900.00, 'menu_68ea1f3828bc97.20447460.jpg', 1, '2025-06-14 05:09:20'),
(124, 'Beef Steak (Full Pan)', '', '10-15', 1600.00, 'menu_68ea1cfa2f7320.44914859.jpg', 1, '2025-06-14 05:09:20');

-- --------------------------------------------------------

--
-- Table structure for table `menucategory`
--

CREATE TABLE `menucategory` (
  `mc_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menucategory`
--

INSERT INTO `menucategory` (`mc_id`, `category_id`, `menu_id`) VALUES
(6, 2, 6),
(7, 2, 7),
(8, 2, 8),
(9, 2, 9),
(10, 2, 10),
(11, 2, 11),
(12, 2, 12),
(13, 2, 13),
(14, 3, 14),
(15, 3, 15),
(16, 3, 16),
(17, 3, 17),
(18, 3, 18),
(19, 3, 19),
(20, 3, 20),
(21, 3, 21),
(22, 3, 22),
(23, 3, 23),
(24, 3, 24),
(25, 3, 25),
(26, 3, 26),
(27, 3, 27),
(28, 3, 28),
(29, 3, 29),
(30, 3, 30),
(31, 4, 31),
(32, 4, 32),
(33, 4, 33),
(34, 4, 34),
(35, 4, 35),
(36, 4, 36),
(37, 4, 37),
(38, 4, 38),
(39, 4, 39),
(40, 4, 40),
(41, 4, 41),
(42, 4, 42),
(43, 4, 43),
(44, 4, 44),
(45, 4, 45),
(46, 4, 46),
(47, 4, 47),
(48, 4, 48),
(49, 4, 49),
(50, 4, 50),
(51, 4, 51),
(52, 4, 52),
(53, 4, 53),
(54, 4, 54),
(63, 6, 63),
(64, 6, 64),
(65, 6, 65),
(66, 6, 66),
(67, 6, 67),
(68, 6, 68),
(69, 7, 69),
(70, 7, 70),
(71, 7, 71),
(72, 7, 72),
(73, 7, 73),
(79, 1, 1),
(80, 1, 2),
(81, 1, 3),
(82, 1, 4),
(83, 1, 5),
(84, 1, 8),
(85, 5, 56),
(86, 5, 55),
(87, 5, 58),
(88, 5, 57),
(89, 5, 60),
(90, 5, 59),
(91, 5, 62),
(92, 5, 61);

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
  `package_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`package_id`, `name`, `pax`, `base_price`, `is_active`, `notes`, `package_image`, `created_at`, `updated_at`) VALUES
(1, 'Standard', '50', 35000.00, 1, '', 'uploads/packages/package_1.jpg', '2025-10-09 17:43:42', '2025-10-11 11:00:23'),
(2, 'Wedding Package', '100', 55000.00, 1, '', 'uploads/packages/package_2.jpg', '2025-10-09 18:03:25', '2025-10-11 10:59:54'),
(3, 'Corporate', '150', 78000.00, 0, '', 'uploads/packages/package_3.jpg', '2025-10-09 18:04:56', '2025-10-11 13:57:39');

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
(153, 1, 'Beef Menu', NULL, 'dish', 0, 0, '', '2025-10-11 12:33:52', '2025-10-11 12:33:52'),
(154, 1, 'Pork Menu', NULL, 'dish', 0, 1, '', '2025-10-11 12:33:52', '2025-10-11 12:33:52'),
(155, 1, 'Chicken Menu', NULL, 'dish', 0, 2, '', '2025-10-11 12:33:52', '2025-10-11 12:33:52'),
(156, 1, 'Rice', NULL, 'dish', 0, 3, '', '2025-10-11 12:33:52', '2025-10-11 12:33:52'),
(157, 1, 'Veggies/Pasta/Fish Fillet(choose 1)', NULL, 'dish', 0, 4, '', '2025-10-11 12:33:52', '2025-10-11 12:33:52'),
(158, 1, '50 Cups of Desserts', 50, 'cups', 0, 5, '', '2025-10-11 12:33:52', '2025-10-11 12:33:52'),
(159, 1, 'Backdrop & Platform/ Complete Setup', NULL, 'other', 0, 6, '', '2025-10-11 12:33:52', '2025-10-11 12:33:52'),
(160, 1, 'Elegant Table Buffet', NULL, 'other', 0, 7, '', '2025-10-11 12:33:52', '2025-10-11 12:33:52'),
(161, 1, '6 Chaffing Dish', 6, 'other', 0, 8, '', '2025-10-11 12:33:52', '2025-10-11 12:33:52'),
(162, 1, 'Banquet Complete Setup', NULL, 'other', 0, 9, '', '2025-10-11 12:33:52', '2025-10-11 12:33:52'),
(163, 1, 'Tables and Chairs with cover', NULL, 'other', 0, 10, '', '2025-10-11 12:33:52', '2025-10-11 12:33:52'),
(164, 1, 'Artificial Flowers and Balloons for Decoration', NULL, 'other', 0, 11, '', '2025-10-11 12:33:52', '2025-10-11 12:33:52'),
(165, 1, '60 Pax Silverware and Dinner ware', 60, 'pcs', 0, 12, '', '2025-10-11 12:33:52', '2025-10-11 12:33:52'),
(166, 1, '2 Food Attendants', 2, 'attendants', 0, 13, '', '2025-10-11 12:33:52', '2025-10-11 12:33:52'),
(167, 1, 'Elegant Table Buffet', NULL, 'other', 0, 14, '', '2025-10-11 12:33:52', '2025-10-11 12:33:52'),
(168, 2, 'Beef Menu', NULL, 'dish', 0, 0, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(169, 2, 'Pork Menu', NULL, 'dish', 0, 1, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(170, 2, 'Chicken Menu', NULL, 'dish', 0, 2, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(171, 2, 'Rice', NULL, 'dish', 0, 3, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(172, 2, 'Veggies/Pasta/Fish Fillet', NULL, 'dish', 0, 4, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(173, 2, '100 Cups of Dessert', NULL, 'cups', 0, 5, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(174, 2, 'Drinks', NULL, 'dish', 0, 6, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(175, 2, 'Backdrop & Platform/ Complete Setup', NULL, 'other', 0, 7, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(176, 2, 'Table Buffet w/ Skirting Setup', NULL, 'other', 0, 8, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(177, 2, '7 Chaffing Dish w/ Food Heat Lamp', NULL, 'pcs', 0, 9, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(178, 2, 'Cake & Gift Table w/ Skirting Designs', NULL, 'other', 0, 10, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(179, 2, 'Chairs w/ Cover', NULL, 'other', 0, 11, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(180, 2, 'Tables w/ cover', NULL, 'other', 0, 12, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(181, 2, '100 PAX Silverware, Glassware, Dinner ware', NULL, 'pax', 0, 13, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(182, 2, '100pcs Serving Spoon', NULL, 'pcs', 0, 14, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(183, 2, '4 Food Attendants', NULL, 'attendants', 0, 15, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(184, 2, 'Elegant Table Buffet', NULL, 'other', 0, 16, '', '2025-10-11 12:34:05', '2025-10-11 12:34:05'),
(194, 3, 'Beef Menu', NULL, 'dish', 0, 0, '', '2025-10-11 13:40:59', '2025-10-11 13:40:59'),
(195, 3, 'Chicken Menu', NULL, 'dish', 0, 1, '', '2025-10-11 13:40:59', '2025-10-11 13:40:59'),
(196, 3, 'Pork Menu', NULL, 'dish', 0, 2, '', '2025-10-11 13:40:59', '2025-10-11 13:40:59'),
(197, 3, 'Veggies/Pasta/Fish Fillet', NULL, 'attendants', 0, 3, '', '2025-10-11 13:40:59', '2025-10-11 13:40:59');

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
  `pay_method` enum('Cash','Gcash','Card','Paypal','Paymaya') DEFAULT NULL,
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
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `menucategory`
--
ALTER TABLE `menucategory`
  MODIFY `mc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

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
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `package_items`
--
ALTER TABLE `package_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

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
