-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.24-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.0.0.6468
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table teves_system_dev.teves_product_table
CREATE TABLE IF NOT EXISTS `teves_product_table` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'SITE CODE TO Used on Gateway',
  `product_price` double NOT NULL DEFAULT 0 COMMENT 'BUSINESS_ENTITY/Location/SITE ed SM SA LAZARO',
  `product_category` int(11) NOT NULL DEFAULT 0 COMMENT 'COMPANY_CODE',
  `product_unit_measurement` varchar(50) DEFAULT NULL COMMENT 'METER_READING_CUTOFF',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`product_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

-- Dumping data for table teves_system_dev.teves_product_table: ~56 rows (approximately)
DELETE FROM `teves_product_table`;
INSERT INTO `teves_product_table` (`product_id`, `product_name`, `product_price`, `product_category`, `product_unit_measurement`, `created_at`, `updated_at`) VALUES
	(11, 'Super 91', 66.5, 0, 'L', '2022-12-05 14:39:19', '2023-02-14 10:18:09'),
	(12, 'Diesel', 61.2, 0, 'L', '2022-12-26 22:58:27', '2023-02-14 10:17:56'),
	(13, 'Premium 95', 67.4, 0, 'L', '2022-12-26 22:59:06', '2023-02-14 10:18:21'),
	(14, 'Zoelo Extreme 1L', 284.45, 0, 'L', '2023-01-11 14:02:41', '2023-05-08 18:02:28'),
	(15, 'Brake Fluid 900ml', 274.61, 0, 'PC', '2023-01-11 14:09:04', '2023-05-08 18:03:13'),
	(16, 'Petrolubes Gear Oil 140', 4359, 0, 'Pail', '2023-01-13 09:19:15', '2023-04-20 15:08:26'),
	(17, '800ML Landex Brake Fluid DOT 4', 192, 0, 'PC', '2023-01-13 09:24:59', '2023-01-13 09:24:59'),
	(18, 'Radiator Coolant 1L', 199, 0, 'PC', '2023-01-16 11:26:47', '2023-01-16 11:27:05'),
	(19, 'ATF Oil 1L', 275, 0, 'PC', '2023-01-16 13:40:56', '2023-01-16 13:40:56'),
	(20, 'Air Freshener (E - World)', 89, 0, 'PC', '2023-01-16 14:16:02', '2023-01-16 14:16:02'),
	(21, 'Zoelo Extreme 1 pail', 4420, 0, 'PC', '2023-01-17 09:03:37', '2023-01-17 09:03:37'),
	(22, 'Zoelo Extreme 4L', 1080, 0, 'PC', '2023-01-17 09:04:09', '2023-01-17 09:04:09'),
	(23, 'Deruibo 599 Mining Lug 12.00R20 PR', 18320, 0, 'PC', '2023-01-17 10:55:14', '2023-01-17 10:55:14'),
	(24, 'Grease MP3', 5178.3, 0, 'Pail', '2023-01-19 09:01:54', '2023-04-20 15:09:51'),
	(25, 'Cyclomax Titan 4T 1L', 251, 0, 'PC', '2023-01-19 16:15:16', '2023-01-19 16:15:16'),
	(26, 'Premium Coolant White 1L', 130, 0, 'PC', '2023-01-25 14:48:03', '2023-01-25 14:48:03'),
	(27, 'Ligid 12R20', 16250, 0, 'PC', '2023-01-25 15:12:13', '2023-01-25 15:12:13'),
	(28, 'Gear Oil 140 1L', 243.88, 0, 'PC', '2023-01-25 15:41:57', '2023-01-31 14:46:22'),
	(29, 'Zoelo Syntech (Diesel)', 680, 0, 'L', '2023-01-25 15:43:02', '2023-01-25 15:43:02'),
	(30, 'WD 40 277ml', 308, 0, 'PC', '2023-01-26 16:57:09', '2023-01-26 16:57:09'),
	(31, 'Brake Fluid Phoenix 250ml', 109, 0, 'PC', '2023-01-31 10:50:05', '2023-01-31 10:50:05'),
	(32, 'Grasa MP3 500g', 182, 0, 'PC', '2023-01-31 12:22:29', '2023-01-31 12:22:29'),
	(33, 'Cyclomax Titan 4T 800ml', 241, 0, 'PC', '2023-01-31 14:42:38', '2023-01-31 14:42:38'),
	(34, 'Gear Oil 90 1L', 234, 0, 'PC', '2023-01-31 14:45:50', '2023-01-31 14:45:50'),
	(35, 'Phoenix Cyclomax 2T 1L', 260, 0, 'PC', '2023-02-03 09:16:46', '2023-02-03 09:16:46'),
	(36, 'Petrolubes Oil 10 1pail', 3330, 0, 'PC', '2023-02-06 08:50:55', '2023-02-06 08:50:55'),
	(37, 'Accelerate Extra Prem Monograde', 250, 0, 'PC', '2023-02-07 10:33:36', '2023-02-07 10:33:36'),
	(38, 'Oil 40 1pail', 3230, 0, 'PC', '2023-02-07 10:41:21', '2023-02-07 10:41:21'),
	(39, 'Delo Sporty 5W40 1L', 1200, 0, 'PC', '2023-02-07 15:02:55', '2023-02-07 15:03:11'),
	(40, 'LPG', 1250, 0, 'PC', '2023-02-14 10:22:27', '2023-02-14 10:22:27'),
	(41, 'WD40 352ml', 359, 0, 'PC', '2023-02-14 14:21:41', '2023-02-14 14:21:41'),
	(42, 'Hydraulic Oil 68', 3230, 0, 'Pail', '2023-02-16 14:09:26', '2023-04-20 15:08:54'),
	(43, 'Landex Fluid 900ml', 226, 0, 'PC', '2023-02-16 14:09:48', '2023-02-16 14:09:48'),
	(44, 'Racing 800ml', 207, 0, 'PC', '2023-02-17 08:59:04', '2023-02-17 08:59:04'),
	(45, 'Phoenix Oil 40 1drum', 24100, 0, 'PC', '2023-02-21 16:11:55', '2023-02-21 16:11:55'),
	(46, 'Oil 10 1pail', 2830, 0, 'PC', '2023-02-22 10:53:34', '2023-02-22 10:53:34'),
	(47, 'Racing 4T 1L', 304, 0, 'PC', '2023-02-27 13:20:52', '2023-02-27 13:20:52'),
	(48, 'WD 40 100ml', 148, 0, 'PC', '2023-02-28 16:53:54', '2023-02-28 16:53:54'),
	(49, 'Pail Gear Oil 90', 4081.2, 0, 'PC', '2023-03-15 16:22:46', '2023-03-15 16:22:46'),
	(50, 'Road Cruza 195 R15 RA350', 4799, 0, 'PC', '2023-03-16 10:29:40', '2023-03-16 10:29:40'),
	(51, '1Case Cyclomax Titan 4T 800ML', 2801.95, 0, 'PC', '2023-03-21 09:34:28', '2023-03-21 09:34:28'),
	(52, '1Case Cyclomax Titan 4T 1L', 2867.44, 0, 'PC', '2023-03-21 09:35:29', '2023-03-21 09:35:29'),
	(53, '1Case Cyclomax Racing 4T 800ML', 2831.95, 0, 'PC', '2023-03-21 09:38:39', '2023-03-21 09:38:39'),
	(54, '1Case Cyclomax Racing 4T 1L', 3225.77, 0, 'PC', '2023-03-21 09:39:44', '2023-03-21 09:39:44'),
	(55, '1Case Cyclomax Force 4T 800ML', 1819.14, 0, 'PC', '2023-03-21 09:40:27', '2023-03-21 09:40:27'),
	(56, '1Case Cyclomax Force 4T 1L', 2846.77, 0, 'PC', '2023-03-21 09:40:56', '2023-03-21 09:40:56'),
	(57, '1Case Fork Oil 200ML', 2763, 0, 'PC', '2023-03-21 09:41:38', '2023-03-21 09:41:38'),
	(58, 'DRUM Petrolubes 0il 40', 25550, 0, 'PC', '2023-03-23 15:24:46', '2023-03-23 15:24:46'),
	(59, 'DRUM Phoenix Hydraulic Oil 68', 25550, 0, 'PC', '2023-03-23 15:25:18', '2023-03-23 15:25:18'),
	(60, 'Armor All Protectant 118ml', 150, 0, 'PC', '2023-03-24 10:15:53', '2023-03-24 10:15:53'),
	(61, 'Motolite Battery', 8700, 0, 'PC', '2023-03-27 11:56:20', '2023-03-27 11:56:20'),
	(62, 'Fuel Filter', 950, 0, 'PC', '2023-04-05 09:48:47', '2023-04-05 09:48:47'),
	(63, 'Engine Oil 40', 3230, 0, 'Pail', '2023-04-20 15:08:15', '2023-04-20 15:08:15'),
	(64, 'Engine Oil 30', 3230, 0, 'Pail', '2023-04-20 15:10:42', '2023-04-20 15:10:42'),
	(65, 'DERUIBO 559 12R20', 17000, 0, 'PC', '2023-05-21 14:52:00', '2023-05-21 14:52:00'),
	(66, 'DJ 2T 180 ML', 52.38, 0, 'mL', '2023-05-29 15:36:02', '2023-05-29 15:36:02');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
