-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.10.2-MariaDB - mariadb.org binary distribution
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


-- Dumping database structure for teves_system
CREATE DATABASE IF NOT EXISTS `teves_system` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `teves_system`;

-- Dumping structure for table teves_system.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table teves_system.sessions: ~0 rows (approximately)

-- Dumping structure for table teves_system.teves_billing_table
CREATE TABLE IF NOT EXISTS `teves_billing_table` (
  `billing_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_idx` int(11) NOT NULL DEFAULT 0,
  `drivers_name` text NOT NULL,
  `plate_no` text NOT NULL,
  `product_price` double NOT NULL DEFAULT 1,
  `client_idx` int(11) NOT NULL DEFAULT 0,
  `order_quantity` double NOT NULL DEFAULT 0,
  `order_total_amount` double NOT NULL DEFAULT 0,
  `order_date` varchar(10) NOT NULL DEFAULT '',
  `order_time` varchar(10) NOT NULL DEFAULT '',
  `order_po_number` text NOT NULL,
  `sales_order_idx` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`billing_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table teves_system.teves_billing_table: ~62 rows (approximately)
INSERT INTO `teves_billing_table` (`billing_id`, `product_idx`, `drivers_name`, `plate_no`, `product_price`, `client_idx`, `order_quantity`, `order_total_amount`, `order_date`, `order_time`, `order_po_number`, `sales_order_idx`, `created_at`, `updated_at`) VALUES
	(3, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(4, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(5, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(6, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(7, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(8, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(9, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(10, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(11, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(12, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(13, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(14, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(15, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(16, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(17, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(18, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(19, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(20, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(21, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(22, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(23, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(24, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(25, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(26, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(27, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(28, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(29, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(30, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(31, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(32, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(33, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(34, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(35, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(36, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(37, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(38, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(39, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(40, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(41, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(42, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(43, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(44, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(45, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(46, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(47, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(48, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(49, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(50, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(51, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(52, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(53, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(54, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(55, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(56, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(57, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(58, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(59, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(60, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(61, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(62, 1, 'Juan dela tore', 'A-12345', 30, 1, 7.6, 228, '2022-12-05', '14:18', '123456', NULL, '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(63, 4, 'rrrrr', 'r425rt', 67.6, 4, 45.4, 3069.04, '2022-12-10', '23:38', 'dw325', NULL, '2022-12-10 23:38:23', '2022-12-10 23:38:23'),
	(64, 1, 'wsd', 'wqrdxs', 30, 1, 12, 360, '2022-12-17', '15:15', 'dddd', NULL, '2022-12-17 15:15:57', '2022-12-17 15:15:57'),
	(65, 1, '99900', '00099', 2, 4, 90, 180, '2023-01-02', '13:22', '000001111', NULL, '2023-01-02 13:22:40', '2023-01-02 14:00:08'),
	(66, 1, 'new', 'new', 43.8, 1, 45, 1971, '2022-11-01', '20:03', 'new', NULL, '2023-01-15 20:03:29', '2023-01-15 20:03:29'),
	(67, 11, 'www', 'www', 1.3, 1, 123, 159.9, '2022-11-01', '15:37', 'ww', NULL, '2023-01-22 15:36:16', '2023-01-22 15:37:01'),
	(68, 11, 'www3', 'www3', 1.3, 1, 1231, 1600.3, '2022-11-02', '15:37', 'ww3', NULL, '2023-01-22 15:37:19', '2023-01-22 15:37:19');

-- Dumping structure for table teves_system.teves_client_table
CREATE TABLE IF NOT EXISTS `teves_client_table` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` text NOT NULL,
  `client_address` text DEFAULT NULL,
  `client_tin` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`client_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table teves_system.teves_client_table: ~3 rows (approximately)
INSERT INTO `teves_client_table` (`client_id`, `client_name`, `client_address`, `client_tin`, `created_at`, `updated_at`) VALUES
	(1, 'Client 11', 'Address 11 237B Address 11', 'SAMPLE TIN-00001111', '2022-11-10 00:08:04', '2023-01-22 00:56:04'),
	(4, 'Client 12', 'Address 12', NULL, '2022-12-04 00:36:06', '2022-12-04 12:25:34'),
	(6, 'aaa', 'dsad', NULL, '2022-12-17 14:36:38', '2022-12-17 14:36:38');

-- Dumping structure for table teves_system.teves_product_table
CREATE TABLE IF NOT EXISTS `teves_product_table` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` text NOT NULL COMMENT 'SITE CODE TO Used on Gateway',
  `product_price` double NOT NULL DEFAULT 0 COMMENT 'BUSINESS_ENTITY/Location/SITE ed SM SA LAZARO',
  `product_category` int(11) NOT NULL DEFAULT 0 COMMENT 'COMPANY_CODE',
  `product_unit_measurement` text DEFAULT NULL COMMENT 'METER_READING_CUTOFF',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`product_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table teves_system.teves_product_table: ~3 rows (approximately)
INSERT INTO `teves_product_table` (`product_id`, `product_name`, `product_price`, `product_category`, `product_unit_measurement`, `created_at`, `updated_at`) VALUES
	(1, 'Product1', 30, 1, 'L', '2022-11-10 00:07:49', '2022-12-04 14:20:06'),
	(4, 'Product2', 67.66, 0, 'L', '2022-12-03 19:13:10', '2023-01-21 23:13:37'),
	(11, 'test', 1.3, 0, 'PC', '2023-01-22 15:35:14', '2023-01-22 15:35:14');

-- Dumping structure for table teves_system.teves_purchase_order_component_table
CREATE TABLE IF NOT EXISTS `teves_purchase_order_component_table` (
  `purchase_order_component_id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_order_idx` int(11) DEFAULT NULL,
  `product_idx` int(11) NOT NULL DEFAULT 0,
  `client_idx` int(11) NOT NULL DEFAULT 0,
  `product_price` double NOT NULL DEFAULT 1,
  `order_quantity` double NOT NULL DEFAULT 0,
  `order_total_amount` double NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`purchase_order_component_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system.teves_purchase_order_component_table: ~5 rows (approximately)
INSERT INTO `teves_purchase_order_component_table` (`purchase_order_component_id`, `purchase_order_idx`, `product_idx`, `client_idx`, `product_price`, `order_quantity`, `order_total_amount`, `created_at`, `updated_at`) VALUES
	(1, 2, 4, 0, 67.66, 1, 67.66, '2023-01-29 15:06:01', '2023-01-29 15:06:01'),
	(2, 2, 1, 0, 3, 23, 69, '2023-01-29 16:06:24', '2023-01-29 16:06:24'),
	(3, 2, 11, 0, 4, 23, 92, '2023-01-29 16:06:24', '2023-01-29 16:06:24'),
	(4, 2, 1, 0, 5, 23, 115, '2023-01-29 16:06:24', '2023-01-29 16:06:24'),
	(5, 2, 11, 0, 3, 2, 6, '2023-01-29 16:06:24', '2023-01-29 16:06:24');

-- Dumping structure for table teves_system.teves_purchase_order_table
CREATE TABLE IF NOT EXISTS `teves_purchase_order_table` (
  `purchase_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_order_client_idx` int(11) DEFAULT NULL,
  `purchase_supplier_name` text DEFAULT NULL,
  `purchase_supplier_tin` text DEFAULT NULL,
  `purchase_supplier_address` text DEFAULT NULL,
  `purchase_order_control_number` text DEFAULT NULL,
  `purchase_order_date` date DEFAULT NULL,
  `purchase_order_sales_order_number` text DEFAULT NULL,
  `purchase_order_collection_receipt_no` text DEFAULT NULL COMMENT 'COLLECTION RECEIPT NO.',
  `purchase_order_official_receipt_no` text DEFAULT NULL,
  `purchase_order_delivery_receipt_no` text DEFAULT NULL,
  `purchase_order_bank` text DEFAULT NULL,
  `purchase_order_date_of_payment` text DEFAULT NULL,
  `purchase_order_reference_no` text DEFAULT NULL,
  `purchase_order_payment_amount` text DEFAULT NULL,
  `purchase_order_delivery_method` text DEFAULT NULL,
  `purchase_order_hauler` text DEFAULT NULL,
  `purchase_order_date_of_pickup` text DEFAULT NULL,
  `purchase_order_date_of_arrival` text DEFAULT NULL,
  `purchase_order_gross_amount` double DEFAULT NULL,
  `purchase_order_total_liters` double DEFAULT NULL,
  `purchase_order_net_percentage` double DEFAULT NULL,
  `purchase_order_net_amount` double DEFAULT NULL,
  `purchase_order_less_percentage` double DEFAULT NULL,
  `purchase_order_total_payable` double DEFAULT NULL,
  `purchase_driver` text DEFAULT NULL,
  `purchase_lorry_plate_no` text DEFAULT NULL,
  `purchase_loading_terminal` text DEFAULT NULL,
  `purchase_terminal_address` text DEFAULT NULL,
  `purchase_destination` text DEFAULT NULL,
  `purchase_destination_address` text DEFAULT NULL,
  `purchase_date_of_departure` text DEFAULT NULL,
  `purchase_date_of_arrival` text DEFAULT NULL,
  `purchase_order_instructions` text DEFAULT NULL,
  `purchase_order_note` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`purchase_order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system.teves_purchase_order_table: ~1 rows (approximately)
INSERT INTO `teves_purchase_order_table` (`purchase_order_id`, `purchase_order_client_idx`, `purchase_supplier_name`, `purchase_supplier_tin`, `purchase_supplier_address`, `purchase_order_control_number`, `purchase_order_date`, `purchase_order_sales_order_number`, `purchase_order_collection_receipt_no`, `purchase_order_official_receipt_no`, `purchase_order_delivery_receipt_no`, `purchase_order_bank`, `purchase_order_date_of_payment`, `purchase_order_reference_no`, `purchase_order_payment_amount`, `purchase_order_delivery_method`, `purchase_order_hauler`, `purchase_order_date_of_pickup`, `purchase_order_date_of_arrival`, `purchase_order_gross_amount`, `purchase_order_total_liters`, `purchase_order_net_percentage`, `purchase_order_net_amount`, `purchase_order_less_percentage`, `purchase_order_total_payable`, `purchase_driver`, `purchase_lorry_plate_no`, `purchase_loading_terminal`, `purchase_terminal_address`, `purchase_destination`, `purchase_destination_address`, `purchase_date_of_departure`, `purchase_date_of_arrival`, `purchase_order_instructions`, `purchase_order_note`, `created_at`, `updated_at`) VALUES
	(2, NULL, 'Danny\'s Shop', '111-111-1111', 'Any Address', '00000002', '2023-01-29', '44533', '3422221', '466644', 'oo', 'o', '2023-01-29', '90988783', '1000.5', 'PICK UP', '0005423', '2023-01-29', '2023-01-29', 349.66, NULL, 1.12, 312.2, 1, 346.54, 'Driver 1', '2023-01-29', 'Terminal 1', '1 Biringan', 'Terminal 2', 'Terminal 3', '2023-01-01', '2023-02-01', 'Instructions 1', 'Notes 1', '2023-01-29 15:06:01', '2023-01-29 17:53:42');

-- Dumping structure for table teves_system.teves_receivable_table
CREATE TABLE IF NOT EXISTS `teves_receivable_table` (
  `receivable_id` int(11) NOT NULL AUTO_INCREMENT,
  `receivable_name` text DEFAULT NULL,
  `client_idx` int(11) DEFAULT NULL,
  `delivered_to` text DEFAULT NULL,
  `control_number` text DEFAULT NULL,
  `billing_date` date DEFAULT NULL,
  `billing_period_start` date DEFAULT NULL,
  `billing_period_end` date DEFAULT NULL,
  `tin_number` text DEFAULT NULL,
  `or_number` text DEFAULT NULL,
  `dr_number` text DEFAULT NULL,
  `payment_term` text DEFAULT NULL,
  `receivable_description` text DEFAULT NULL,
  `receivable_amount` double DEFAULT NULL,
  `receivable_status` text DEFAULT NULL,
  `less_per_liter` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`receivable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table teves_system.teves_receivable_table: ~1 rows (approximately)
INSERT INTO `teves_receivable_table` (`receivable_id`, `receivable_name`, `client_idx`, `delivered_to`, `control_number`, `billing_date`, `billing_period_start`, `billing_period_end`, `tin_number`, `or_number`, `dr_number`, `payment_term`, `receivable_description`, `receivable_amount`, `receivable_status`, `less_per_liter`, `created_at`, `updated_at`) VALUES
	(16, NULL, 1, NULL, '00000001', '2023-01-22', '2022-11-01', '2023-01-22', NULL, 'Test 2', NULL, 'Test 2', 'Test 2', 17617.3, 'Pending', 0.3, '2023-01-22 18:33:41', '2023-01-22 18:33:41');

-- Dumping structure for table teves_system.teves_sales_order_component_table
CREATE TABLE IF NOT EXISTS `teves_sales_order_component_table` (
  `sales_order_component_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_order_idx` int(11) DEFAULT NULL,
  `product_idx` int(11) NOT NULL DEFAULT 0,
  `client_idx` int(11) NOT NULL DEFAULT 0,
  `product_price` double NOT NULL DEFAULT 1,
  `order_quantity` double NOT NULL DEFAULT 0,
  `order_total_amount` double NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sales_order_component_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system.teves_sales_order_component_table: ~0 rows (approximately)

-- Dumping structure for table teves_system.teves_sales_order_table
CREATE TABLE IF NOT EXISTS `teves_sales_order_table` (
  `sales_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_order_client_idx` int(11) DEFAULT NULL,
  `sales_order_control_number` text DEFAULT NULL,
  `sales_order_date` date DEFAULT NULL,
  `sales_order_dr_number` text DEFAULT NULL,
  `sales_order_or_number` text DEFAULT NULL,
  `sales_order_payment_term` text DEFAULT NULL,
  `sales_order_delivered_to` text DEFAULT NULL,
  `sales_order_delivered_to_address` text DEFAULT NULL,
  `sales_order_delivery_method` text DEFAULT NULL,
  `sales_order_gross_amount` double DEFAULT NULL,
  `sales_order_net_percentage` double DEFAULT NULL,
  `sales_order_net_amount` double DEFAULT NULL,
  `sales_order_less_percentage` double DEFAULT NULL,
  `sales_order_total_due` double DEFAULT NULL,
  `sales_order_hauler` text DEFAULT NULL,
  `sales_order_required_date` text DEFAULT NULL,
  `sales_order_instructions` text DEFAULT NULL,
  `sales_order_note` text DEFAULT NULL,
  `sales_order_mode_of_payment` text DEFAULT NULL,
  `sales_order_date_of_payment` text DEFAULT NULL,
  `sales_order_reference_no` text DEFAULT NULL,
  `sales_order_payment_amount` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sales_order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system.teves_sales_order_table: ~0 rows (approximately)

-- Dumping structure for table teves_system.user_tb
CREATE TABLE IF NOT EXISTS `user_tb` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` text NOT NULL,
  `user_real_name` text NOT NULL,
  `user_job_title` text NOT NULL,
  `user_password` text NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `created_at` text NOT NULL,
  `updated_at` text NOT NULL,
  PRIMARY KEY (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1249 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table teves_system.user_tb: ~3 rows (approximately)
INSERT INTO `user_tb` (`user_id`, `user_name`, `user_real_name`, `user_job_title`, `user_password`, `user_type`, `created_at`, `updated_at`) VALUES
	(3, 'admin', 'GLEZA F. TEVES', 'Station Manager', '$2y$10$mf.POMmfskSb1frX84JdOeu.D4iFT0ot1sO0LBthCw0rRKkcavkJi', 'Admin', '2022-11-23 00:00:00', '2022-12-10 18:53:30'),
	(1244, 'dev@danny', 'Developer', 'System Programmer', '$2y$10$bSWs6Q5P.GWHjc5eOyk9x.No.qIomYt.a0a0cMdjVuZ0tt1Agdw1a', 'Admin', '2022-12-07 21:37:45', '2023-01-13 20:55:10'),
	(1248, 'user', 'user', 'user', '$2y$10$udKZRSJtlKpXM02y/8C0m.oSrrgLYjWwPx64mqhzfLHP.92MN.Bhe', 'User', '2023-01-13 20:55:27', '2023-01-13 20:55:27');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
