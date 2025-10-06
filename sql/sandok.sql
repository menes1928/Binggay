-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2025 at 06:45 PM
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
  `cp_desc` text DEFAULT NULL,
  `cp_price` decimal(10,2) NOT NULL,
  `cp_avail` tinyint(1) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cateringpackages`
--

INSERT INTO `cateringpackages` (`cp_id`, `user_id`, `cp_name`, `cp_phone`, `cp_place`, `cp_date`, `cp_desc`, `cp_price`, `cp_avail`, `is_deleted`) VALUES
(1, 6, 'Cxyris Tan', '09603070809', 'San Sebastian Cathedral, P.Laygo St., Lipa City, Batangas', '2025-06-21', 'hello', 99000.00, NULL, 0);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `emp_fn`, `emp_ln`, `emp_sex`, `emp_email`, `emp_phone`, `emp_role`, `created_at`, `is_deleted`) VALUES
(1, 'Kyle', 'Vanleet', 'Male', 'f@gmaail.com', '09674535234', 'Chef', '2025-06-05 15:36:36', 0),
(2, 'John', 'Def', 'Male', 'jd@gmail.com', '09125637121', 'Dishwasher', '2025-06-12 05:02:51', 0),
(3, 'Francine', 'Diaz', 'Female', 'fd@gmail.com', '0965237482323', 'FoodAttendant', '2025-06-12 05:07:18', 0);

-- --------------------------------------------------------

--
-- Table structure for table `eventbookings`
--

CREATE TABLE `eventbookings` (
  `eb_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `eb_name` varchar(255) NOT NULL,
  `eb_contact` varchar(11) NOT NULL,
  `eb_type` varchar(100) NOT NULL,
  `eb_venue` varchar(255) NOT NULL,
  `eb_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `eb_guest` varchar(100) NOT NULL,
  `eb_order` varchar(100) NOT NULL,
  `eb_status` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventbookings`
--

INSERT INTO `eventbookings` (`eb_id`, `user_id`, `eb_name`, `eb_contact`, `eb_type`, `eb_venue`, `eb_date`, `eb_guest`, `eb_order`, `eb_status`, `created_at`, `is_deleted`) VALUES
(4, 6, 'Cxyris Tan', '09603070809', 'marriage', 'San Sebastian Cathedral, P.Laygo Street, Lipa City, Batangas', '2025-06-21 05:00:00', '1000', 'party trays', 'Pending', '2025-06-15 10:45:54', 0);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `menu_name`, `menu_desc`, `menu_pax`, `menu_price`, `menu_pic`, `menu_avail`, `created_at`, `is_deleted`) VALUES
(1, 'Special Pansit', NULL, '10-15 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(2, 'Meaty Spaghetti', NULL, '10-15 pax', 1000.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(3, 'Tuna Carbonara', NULL, '10-15 pax', 1000.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(4, 'Ham/Bacon Carbonara', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(5, 'Tuna Pesto', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(6, 'Special Laing w/ Liempo and Shrimp (Full Pan)', NULL, '10-15 pax', 1500.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(7, 'Special Laing w/ Liempo and Shrimp (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(8, 'Special Chopsuey', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(9, 'Vegetables Kare-kare', NULL, '10-15 pax', 1000.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(10, 'Buttered Mixed Veggies', NULL, '10-15 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(11, 'Special Pakbet', NULL, '10-15 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(12, 'Stirred Veggies w/ Tokwa', NULL, '10-15 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(13, 'Lumpiang Hubad w/ special sauce', NULL, '10-15 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(14, 'Fried Chicken (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(15, 'Fried Chicken (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(16, 'Chicken Afritada (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(17, 'Chicken Afritada (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(18, 'Baked Tahong', '3 kls per pan', '10-15 pax', 1000.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(19, 'Butter Shrimp', '1 kl per pan', '10-15 pax', 1300.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(20, 'Shrimp Salvatore', '1 kl per pan', '10-15 pax', 1500.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(21, 'Fish Fillet', '2 kls per pan', '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(22, 'Relyenong Bangus', 'Min. 5 pcs (500g-600g each)', '5 pcs', 285.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(23, 'Seafood Kare-kare', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(24, 'Pork Barbeque', NULL, 'per piece', 35.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(25, 'Chicken Cordon Bleu (Large Pan)', '12 pcs', '12 pcs', 1500.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(26, 'Chicken Cordon Bleu (Medium Pan)', '6 pcs', '6 pcs', 800.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(27, 'Chicken Cordon Bleu (Tub)', '3 pcs', '3 pcs', 360.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(28, 'Pork Shanghai', NULL, '10-15 pax', 600.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(29, 'Special Puto Cheese', NULL, 'per piece', 5.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(30, 'Lumpiang Sariwa', NULL, 'per piece', 40.00, 'default.jpg', 1, '2025-06-14 13:05:25', 0),
(31, 'Chicken Adobo (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(32, 'Chicken Adobo (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(33, 'Chicken Adobo w/ Liver and Gizzard (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(34, 'Chicken Adobo w/ Liver and Gizzard (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(35, 'Chicken Buffalo (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(36, 'Chicken Buffalo (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(37, 'Orange Chicken (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(38, 'Orange Chicken (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(39, 'Honey Glazed Chicken (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(40, 'Honey Glazed Chicken (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(41, 'Chicken Caldereta (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(42, 'Chicken Caldereta (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(43, 'Ginataang Manok (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(44, 'Ginataang Manok (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(45, 'Tinola (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(46, 'Tinola (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(47, 'Pastel (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(48, 'Pastel (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(49, 'Pininyahang Manok (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(50, 'Pininyahang Manok (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(51, 'Chicken Mushroom Sauce (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(52, 'Chicken Mushroom Sauce (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(53, 'Chicken Barbeque (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(54, 'Chicken Barbeque (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(55, 'Chicken Lollipop (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(56, 'Chicken Lollipop (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(57, 'Honey Butter Glazed Chicken (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(58, 'Honey Butter Glazed Chicken (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(59, 'Paksiw na Lechon Manok (Half Pan)', NULL, '6-8 pax', 700.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(60, 'Paksiw na Lechon Manok (Full Pan)', NULL, '10-15 pax', 1200.00, 'default.jpg', 1, '2025-06-14 13:08:41', 0),
(61, 'Pork Menudo (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(62, 'Pork Menudo (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(63, 'Pork Afritada (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(64, 'Pork Afritada (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(65, 'Pork Caldereta (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(66, 'Pork Caldereta (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(67, 'Bicol Express (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(68, 'Bicol Express (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(69, 'Pork Binagoongan (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(70, 'Pork Binagoongan (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(71, 'Pork Dinuguan (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(72, 'Pork Dinuguan (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(73, 'Crispy Kare-kare (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(74, 'Crispy Kare-kare (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(75, 'Pata Kare-kare (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(76, 'Pata Kare-kare (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(77, 'Pork Adobo (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(78, 'Pork Adobo (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(79, 'Pork Humba (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(80, 'Pork Humba (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(81, 'Pork Estofado (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(82, 'Pork Estofado (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(83, 'Pork Sinigang (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(84, 'Pork Sinigang (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(85, 'Pork Nilaga (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(86, 'Pork Nilaga (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(87, 'Bbq Spare Ribs (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(88, 'Bbq Spare Ribs (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(89, 'Pork Higado (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(90, 'Pork Higado (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(91, 'Baby Back Ribs (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(92, 'Baby Back Ribs (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(93, 'Tokwa\'t Baboy (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(94, 'Tokwa\'t Baboy (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(95, 'Calderobo (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(96, 'Calderobo (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(97, 'Menudillo w/ Quail Egg (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(98, 'Menudillo w/ Quail Egg (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(99, 'Pork and Liver Adobo (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(100, 'Pork and Liver Adobo (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(101, 'Pork Pochero (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(102, 'Pork Pochero (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(103, 'Sweet and Sour Pork (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(104, 'Sweet and Sour Pork (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(105, 'Inihaw na Liempo (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(106, 'Inihaw na Liempo (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(107, 'Pork Steak (Half Pan)', NULL, '6-8 pax', 800.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(108, 'Pork Steak (Full Pan)', NULL, '10-15 pax', 1400.00, 'default.jpg', 1, '2025-06-14 13:09:01', 0),
(109, 'Beef Broccoli (Half Pan)', NULL, '6-8 pax', 900.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0),
(110, 'Beef Broccoli (Full Pan)', NULL, '10-15 pax', 1600.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0),
(111, 'Beef in Mushroom Sauce (Half Pan)', NULL, '6-8 pax', 900.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0),
(112, 'Beef in Mushroom Sauce (Full Pan)', NULL, '10-15 pax', 1600.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0),
(113, 'Beef Kare-kare (Half Pan)', NULL, '6-8 pax', 900.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0),
(114, 'Beef Kare-kare (Full Pan)', NULL, '10-15 pax', 1600.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0),
(115, 'Beef Caldereta (Half Pan)', NULL, '6-8 pax', 900.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0),
(116, 'Beef Caldereta (Full Pan)', NULL, '10-15 pax', 1600.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0),
(117, 'Beef Sinigang (Half Pan)', NULL, '6-8 pax', 900.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0),
(118, 'Beef Sinigang (Full Pan)', NULL, '10-15 pax', 1600.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0),
(119, 'Beef Nilaga or Bulalo (Half Pan)', NULL, '6-8 pax', 900.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0),
(120, 'Beef Nilaga or Bulalo (Full Pan)', NULL, '10-15 pax', 1600.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0),
(121, 'Beef Garlic Pepper Steak (Half Pan)', NULL, '6-8 pax', 900.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0),
(122, 'Beef Garlic Pepper Steak (Full Pan)', NULL, '10-15 pax', 1600.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0),
(123, 'Beef Steak (Half Pan)', NULL, '6-8 pax', 900.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0),
(124, 'Beef Steak (Full Pan)', NULL, '10-15 pax', 1600.00, 'default.jpg', 1, '2025-06-14 13:09:20', 0);

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
(55, 5, 55),
(56, 5, 56),
(57, 5, 57),
(58, 5, 58),
(59, 5, 59),
(60, 5, 60),
(61, 5, 61),
(62, 5, 62),
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
(84, 1, 8);

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

--
-- Dumping data for table `orderaddress`
--

INSERT INTO `orderaddress` (`oa_id`, `order_id`, `oa_street`, `oa_city`, `oa_province`) VALUES
(5, 1, 'P.Laygo Street', 'Lipa City', 'Batangas'),
(6, 2, 'P.Laygo Street', 'Lipa City', 'Batangas'),
(7, 3, 'Bonto Street', 'Lipa City', 'Batangas');

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

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`oi_id`, `order_id`, `menu_id`, `oi_quantity`, `oi_price`) VALUES
(2, 1, 58, 1.00, 1200.00),
(3, 1, 55, 1.00, 700.00),
(4, 1, 56, 1.00, 1200.00),
(5, 1, 59, 1.00, 700.00),
(6, 1, 69, 1.00, 800.00),
(7, 2, 61, 1.00, 800.00),
(8, 2, 58, 1.00, 1200.00),
(9, 2, 55, 1.00, 700.00),
(10, 3, 58, 1.00, 1200.00),
(11, 3, 55, 1.00, 700.00),
(12, 3, 56, 1.00, 1200.00);

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `order_status`, `order_amount`, `order_needed`, `created_at`, `updated_at`, `is_deleted`) VALUES
(1, 5, '2025-06-14 05:00:00', 'pending', 4600.00, '2025-06-15', '2025-06-14 18:25:00', '2025-06-14 18:25:00', 0),
(2, 5, '2025-06-15 05:00:00', 'pending', 2700.00, '2025-06-20', '2025-06-15 10:10:35', '2025-06-15 10:10:35', 0),
(3, 5, '2025-06-15 05:00:00', 'pending', 3100.00, '2025-06-15', '2025-06-15 12:33:22', '2025-06-15 12:33:22', 0);

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

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`pay_id`, `order_id`, `cp_id`, `user_id`, `pay_date`, `pay_amount`, `pay_method`, `pay_status`) VALUES
(1, 1, NULL, 5, '2025-06-14', 4600.00, 'Cash', 'Pending'),
(2, 2, NULL, 5, '2025-06-15', 2700.00, 'Cash', 'Pending'),
(3, NULL, 1, 6, '2025-06-15', 49500.00, 'Online', ''),
(4, 3, NULL, 5, '2025-06-15', 3100.00, 'Cash', 'Pending');

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
(5, 'Cxyris', 'Tan', 'Female', 'cxyris0419@gmail.com', '09603070809', 'Starla', '$2y$10$h3waEaRN0p6ae/zLyByOK.sOwvQ3L2Z19VHrHFboussWSWG5Xjlti', '../uploads/profile/684db2f743cb9_RobloxScreenShot20250505_173643852.png', 0, '2025-06-14 17:35:51', '2025-06-14 17:35:51'),
(6, 'Cxyris', 'Tan', 'Female', 'cxyris@gmail.com', '09603070809', 'Starladmin', '$2y$10$ZB5ptS5oQu6b5IjzhSRImOty68ViKJ08uwelvH7FSwNpD9IM6IylC', '../uploads/profile/684db30fb77e4_RobloxScreenShot20250511_132908374.png', 1, '2025-06-14 17:36:15', '2025-06-14 17:36:30');

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
  ADD PRIMARY KEY (`cp_id`),
  ADD KEY `cateringpackages_ibfk_1` (`user_id`);

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `orders_ibfk_1` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`pay_id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `payments_ibfk_3` (`cp_id`),
  ADD KEY `payment_ibfk_1` (`user_id`);

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
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cateringpackages`
--
ALTER TABLE `cateringpackages`
  MODIFY `cp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `eventbookings`
--
ALTER TABLE `eventbookings`
  MODIFY `eb_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `menucategory`
--
ALTER TABLE `menucategory`
  MODIFY `mc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `orderaddress`
--
ALTER TABLE `orderaddress`
  MODIFY `oa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `oi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cateringpackages`
--
ALTER TABLE `cateringpackages`
  ADD CONSTRAINT `cateringpackages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

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
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
