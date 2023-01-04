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
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
	(65, 1, '99900', '00099', 2, 4, 90, 180, '2023-01-02', '13:22', '000001111', NULL, '2023-01-02 13:22:40', '2023-01-02 14:00:08');

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
	(1, 'Client 11', 'Address 11, Address 11, Address 11 237B Address 11', 'SAMPLE TIN-00001111', '2022-11-10 00:08:04', '2022-12-24 13:36:14'),
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table teves_system.teves_product_table: ~2 rows (approximately)
INSERT INTO `teves_product_table` (`product_id`, `product_name`, `product_price`, `product_category`, `product_unit_measurement`, `created_at`, `updated_at`) VALUES
	(1, 'Product1', 30, 1, 'L', '2022-11-10 00:07:49', '2022-12-04 14:20:06'),
	(4, 'Product2', 67.6, 0, 'L', '2022-12-03 19:13:10', '2022-12-04 12:25:04');

-- Dumping structure for table teves_system.teves_receivable_table
CREATE TABLE IF NOT EXISTS `teves_receivable_table` (
  `receivable_id` int(11) NOT NULL AUTO_INCREMENT,
  `receivable_name` text DEFAULT NULL,
  `client_idx` int(11) DEFAULT NULL,
  `delivered_to` text DEFAULT NULL,
  `control_number` text DEFAULT NULL,
  `billing_date` date DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table teves_system.teves_receivable_table: ~2 rows (approximately)
INSERT INTO `teves_receivable_table` (`receivable_id`, `receivable_name`, `client_idx`, `delivered_to`, `control_number`, `billing_date`, `tin_number`, `or_number`, `dr_number`, `payment_term`, `receivable_description`, `receivable_amount`, `receivable_status`, `less_per_liter`, `created_at`, `updated_at`) VALUES
	(1, NULL, 4, NULL, '00000001', '2023-01-02', NULL, 'ddr', NULL, 'ddr', 'ddr', 180, 'Pending', NULL, '2023-01-02 15:14:11', '2023-01-02 15:38:29'),
	(2, NULL, 1, NULL, '00000002', '2023-01-02', NULL, '0002', NULL, 'ddd', 'ddd', 14040, 'Remaining Balance', NULL, '2023-01-02 15:39:08', '2023-01-02 15:39:08'),
	(3, NULL, 1, NULL, '00000003', '2023-01-02', NULL, 'f', NULL, 'f', 'f', 14040, NULL, NULL, '2023-01-02 16:26:33', '2023-01-02 16:26:33');

-- Dumping structure for table teves_system.teves_sales_order_component_table
CREATE TABLE IF NOT EXISTS `teves_sales_order_component_table` (
  `sales_order_component_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_order_idx` int(11) DEFAULT NULL,
  `product_idx` int(11) NOT NULL DEFAULT 0,
  `client_idx` int(11) NOT NULL DEFAULT 0,
  `drivers_name` text NOT NULL,
  `plate_no` text NOT NULL,
  `product_price` double NOT NULL DEFAULT 1,
  `order_quantity` double NOT NULL DEFAULT 0,
  `order_total_amount` double NOT NULL DEFAULT 0,
  `order_date` varchar(10) NOT NULL DEFAULT '',
  `order_time` varchar(10) NOT NULL DEFAULT '',
  `order_po_number` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sales_order_component_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system.teves_sales_order_component_table: ~63 rows (approximately)
INSERT INTO `teves_sales_order_component_table` (`sales_order_component_id`, `sales_order_idx`, `product_idx`, `client_idx`, `drivers_name`, `plate_no`, `product_price`, `order_quantity`, `order_total_amount`, `order_date`, `order_time`, `order_po_number`, `created_at`, `updated_at`) VALUES
	(3, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(4, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(5, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(6, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(7, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(8, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(9, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(10, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(11, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(12, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(13, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(14, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(15, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(16, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(17, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(18, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(19, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(20, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(21, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(22, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(23, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(24, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(25, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(26, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(27, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(28, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(29, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(30, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(31, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(32, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(33, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(34, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(35, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(36, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(37, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(38, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(39, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(40, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(41, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(42, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(43, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(44, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(45, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(46, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(47, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(48, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(49, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(50, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(51, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(52, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(53, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(54, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(55, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(56, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(57, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(58, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(59, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(60, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(61, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(62, NULL, 1, 1, 'Juan dela tore', 'A-12345', 30, 7.6, 228, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-10 17:47:31'),
	(63, NULL, 4, 4, 'rrrrr', 'r425rt', 67.6, 45.4, 3069.04, '2022-12-10', '23:38', 'dw325', '2022-12-10 23:38:23', '2022-12-10 23:38:23'),
	(64, NULL, 1, 1, 'wsd', 'wqrdxs', 30, 12, 360, '2022-12-17', '15:15', 'dddd', '2022-12-17 15:15:57', '2022-12-17 15:15:57'),
	(65, NULL, 1, 4, '99900', '00099', 2, 90, 180, '2023-01-02', '13:22', '000001111', '2023-01-02 13:22:40', '2023-01-02 14:00:08');

-- Dumping structure for table teves_system.teves_sales_order_table
CREATE TABLE IF NOT EXISTS `teves_sales_order_table` (
  `sales_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `receivable_name` text DEFAULT NULL,
  `client_idx` int(11) DEFAULT NULL,
  `control_number` text DEFAULT NULL,
  `sales_order_date` date DEFAULT NULL,
  `dr_number` text DEFAULT NULL,
  `or_number` text DEFAULT NULL,
  `delivered_to` text DEFAULT NULL,
  `delivered_to_address` text DEFAULT NULL,
  `delivery_method` text DEFAULT NULL,
  `gross_amount` double DEFAULT NULL,
  `net_amount` double DEFAULT NULL,
  `total_due` double DEFAULT NULL,
  `hauler` text DEFAULT NULL,
  `required_date` text DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `mode_of_payment` text DEFAULT NULL,
  `date_of_payment` text DEFAULT NULL,
  `reference_no` text DEFAULT NULL,
  `payment_amount` text DEFAULT NULL,
  `payment_term` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sales_order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system.teves_sales_order_table: ~9 rows (approximately)
INSERT INTO `teves_sales_order_table` (`sales_order_id`, `receivable_name`, `client_idx`, `control_number`, `sales_order_date`, `dr_number`, `or_number`, `delivered_to`, `delivered_to_address`, `delivery_method`, `gross_amount`, `net_amount`, `total_due`, `hauler`, `required_date`, `instructions`, `note`, `mode_of_payment`, `date_of_payment`, `reference_no`, `payment_amount`, `payment_term`, `created_at`, `updated_at`) VALUES
	(1, NULL, 1, '00000001', '2022-12-27', NULL, '000223', NULL, NULL, NULL, NULL, NULL, 14040, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1 month 11', '2022-12-17 23:06:55', '2022-12-25 21:40:38'),
	(11, NULL, 1, '00000002', '2022-12-25', NULL, 'SMPOR-0001', NULL, NULL, NULL, NULL, NULL, 14040, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TERM 1', '2022-12-25 19:13:16', '2022-12-26 15:29:45'),
	(13, NULL, 1, '00000013', '2022-12-26', NULL, 'OR111', NULL, NULL, NULL, NULL, NULL, 14040, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2022-12-26 14:54:43', '2022-12-26 14:54:43'),
	(15, NULL, 1, '00000015', '2022-12-26', NULL, '3121', NULL, NULL, NULL, NULL, NULL, 14040, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '24321', '2022-12-26 15:03:44', '2022-12-26 15:03:44'),
	(16, NULL, 1, '00000016', '2022-12-30', NULL, 'sss', NULL, NULL, NULL, NULL, NULL, 14040, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ss', '2022-12-30 19:10:50', '2022-12-30 19:10:50'),
	(17, NULL, 1, '00000017', '2022-12-30', NULL, 'sssg', NULL, NULL, NULL, NULL, NULL, 14040, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ssg', '2022-12-30 21:46:26', '2022-12-30 21:46:26'),
	(18, NULL, 1, '00000018', '2022-12-30', NULL, 'sssg', NULL, NULL, NULL, NULL, NULL, 14040, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ssg', '2022-12-30 21:55:05', '2022-12-30 21:55:05'),
	(19, NULL, 1, '00000019', '2022-12-31', NULL, 'grvdsax', NULL, NULL, NULL, NULL, NULL, 14040, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'gwfecs', '2022-12-31 00:00:02', '2022-12-31 00:00:02'),
	(20, NULL, 4, '00000020', '2023-01-02', NULL, 'rfesafw', NULL, NULL, NULL, NULL, NULL, 180, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'gfwe', '2023-01-02 14:01:23', '2023-01-02 14:01:23');

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
) ENGINE=InnoDB AUTO_INCREMENT=1248 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table teves_system.user_tb: ~2 rows (approximately)
INSERT INTO `user_tb` (`user_id`, `user_name`, `user_real_name`, `user_job_title`, `user_password`, `user_type`, `created_at`, `updated_at`) VALUES
	(3, 'admin', 'GLEZA F. TEVES', 'Station Manager', '$2y$10$mf.POMmfskSb1frX84JdOeu.D4iFT0ot1sO0LBthCw0rRKkcavkJi', 'Admin', '2022-11-23 00:00:00', '2022-12-10 18:53:30'),
	(1244, 'dev@danny', 'Developer', 'System Programmer', '$2y$10$bSWs6Q5P.GWHjc5eOyk9x.No.qIomYt.a0a0cMdjVuZ0tt1Agdw1a', 'User', '2022-12-07 21:37:45', '2022-12-11 14:26:57');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
