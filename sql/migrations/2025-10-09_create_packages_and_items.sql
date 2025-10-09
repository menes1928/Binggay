-- Migration: Create packages and package_items tables
-- Date: 2025-10-09
-- Description: Add database support for Catering Packages with editable inclusion items
-- Compatible with: MariaDB 10.4+

-- --------------------------------------------------------
-- Table structure for table `packages`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `packages` (
  `package_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pax` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `type` varchar(100) DEFAULT 'catering',
  `description` text DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`package_id`),
  KEY `idx_pax` (`pax`),
  KEY `idx_active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `package_items`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `package_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `item_label` varchar(255) NOT NULL,
  `item_type` varchar(50) DEFAULT 'inclusion',
  `qty_text` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`item_id`),
  KEY `idx_package_id` (`package_id`),
  KEY `idx_sort_order` (`sort_order`),
  CONSTRAINT `fk_package_items_package` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Seed data for packages
-- --------------------------------------------------------

-- Package 1: 100 PAX - ₱55,000
INSERT INTO `packages` (`name`, `pax`, `price`, `type`, `description`, `active`) VALUES
('Premium Wedding Package', 100, 55000.00, 'catering', 'A comprehensive wedding package for mid-sized receptions. Features full catering, detailed buffet setup, cake table, and ample serving staff.', 1);

SET @pkg_100 = LAST_INSERT_ID();

-- Package 2: 150 PAX - ₱78,000
INSERT INTO `packages` (`name`, `pax`, `price`, `type`, `description`, `active`) VALUES
('Luxury Wedding Package', 150, 78000.00, 'catering', 'Ideal for medium to large events. Offers extensive meal options, desserts, drinks, a complete and elegant buffet setup, and more attendants for smooth service.', 1);

SET @pkg_150 = LAST_INSERT_ID();

-- Package 3: 200 PAX - ₱99,000
INSERT INTO `packages` (`name`, `pax`, `price`, `type`, `description`, `active`) VALUES
('Grand Wedding Package', 200, 99000.00, 'catering', 'Our largest package for grand events. Provides a full premium catering experience for a substantial guest list, ensuring elegant presentation and efficient service with more staff.', 1);

SET @pkg_200 = LAST_INSERT_ID();

-- --------------------------------------------------------
-- Seed data for package_items (100 PAX Package)
-- --------------------------------------------------------

INSERT INTO `package_items` (`package_id`, `item_label`, `item_type`, `qty_text`, `sort_order`) VALUES
(@pkg_100, 'Beef Menu', 'food', NULL, 1),
(@pkg_100, 'Pork Menu', 'food', NULL, 2),
(@pkg_100, 'Chicken Menu', 'food', NULL, 3),
(@pkg_100, 'Rice', 'food', NULL, 4),
(@pkg_100, 'Veggies or Pasta or Fish Fillet', 'food', NULL, 5),
(@pkg_100, 'Cups of Desserts', 'dessert', '100', 6),
(@pkg_100, 'Drinks', 'beverage', NULL, 7),
(@pkg_100, 'Backdrop and Platform / Complete Setup', 'setup', NULL, 8),
(@pkg_100, 'Table Buffet w/ Skirting Setup', 'setup', NULL, 9),
(@pkg_100, 'Chaffing Dish w/ Food Heat Lamp', 'equipment', '7', 10),
(@pkg_100, 'Cake and Gift Table w/ Skirting Designs', 'setup', NULL, 11),
(@pkg_100, 'Chairs with cover', 'furniture', NULL, 12),
(@pkg_100, 'Tables with cover', 'furniture', NULL, 13),
(@pkg_100, 'Pax Silverware, Glassware, and Dinnerware', 'tableware', '100', 14),
(@pkg_100, 'Serving Spoons', 'tableware', '100pcs', 15),
(@pkg_100, 'Food Attendants', 'staff', '4', 16),
(@pkg_100, 'Elegant Table Buffet', 'setup', NULL, 17);

-- --------------------------------------------------------
-- Seed data for package_items (150 PAX Package)
-- --------------------------------------------------------

INSERT INTO `package_items` (`package_id`, `item_label`, `item_type`, `qty_text`, `sort_order`) VALUES
(@pkg_150, 'Beef Menu', 'food', NULL, 1),
(@pkg_150, 'Pork Menu', 'food', NULL, 2),
(@pkg_150, 'Chicken Menu', 'food', NULL, 3),
(@pkg_150, 'Rice', 'food', NULL, 4),
(@pkg_150, 'Veggies or Pasta or Fish Fillet', 'food', NULL, 5),
(@pkg_150, 'Cups of Desserts', 'dessert', '100', 6),
(@pkg_150, 'Drinks', 'beverage', NULL, 7),
(@pkg_150, 'Backdrop and Platform / Complete Setup', 'setup', NULL, 8),
(@pkg_150, 'Table Buffet w/ Skirting Setup', 'setup', NULL, 9),
(@pkg_150, 'Chaffing Dish w/ Food Heat Lamp', 'equipment', '7', 10),
(@pkg_150, 'Cake and Gift Table w/ Skirting Designs', 'setup', NULL, 11),
(@pkg_150, 'Chairs with cover', 'furniture', NULL, 12),
(@pkg_150, 'Tables with cover', 'furniture', NULL, 13),
(@pkg_150, 'Pax Silverware, Glassware, and Dinnerware', 'tableware', '150', 14),
(@pkg_150, 'Serving Spoons', 'tableware', '150pcs', 15),
(@pkg_150, 'Food Attendants', 'staff', '6', 16),
(@pkg_150, 'Elegant Table Buffet', 'setup', NULL, 17);

-- --------------------------------------------------------
-- Seed data for package_items (200 PAX Package)
-- --------------------------------------------------------

INSERT INTO `package_items` (`package_id`, `item_label`, `item_type`, `qty_text`, `sort_order`) VALUES
(@pkg_200, 'Beef Menu', 'food', NULL, 1),
(@pkg_200, 'Pork Menu', 'food', NULL, 2),
(@pkg_200, 'Chicken Menu', 'food', NULL, 3),
(@pkg_200, 'Rice', 'food', NULL, 4),
(@pkg_200, 'Veggies or Pasta or Fish Fillet', 'food', NULL, 5),
(@pkg_200, 'Cups of Desserts', 'dessert', '200', 6),
(@pkg_200, 'Drinks', 'beverage', NULL, 7),
(@pkg_200, 'Backdrop and Platform / Complete Setup', 'setup', NULL, 8),
(@pkg_200, 'Table Buffet w/ Skirting Setup', 'setup', NULL, 9),
(@pkg_200, 'Chaffing Dish w/ Food Heat Lamp', 'equipment', '7', 10),
(@pkg_200, 'Cake and Gift Table w/ Skirting Designs', 'setup', NULL, 11),
(@pkg_200, 'Chairs with cover', 'furniture', NULL, 12),
(@pkg_200, 'Tables with cover', 'furniture', NULL, 13),
(@pkg_200, 'Pax Silverware, Glassware, and Dinnerware', 'tableware', '200', 14),
(@pkg_200, 'Serving Spoons', 'tableware', '200pcs', 15),
(@pkg_200, 'Food Attendants', 'staff', '8', 16),
(@pkg_200, 'Elegant Table Buffet', 'setup', NULL, 17);
