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

-- Dumping structure for table teves_system.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table teves_system.sessions: ~2 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('0U6uioKywwLnzehVlyLhv2dL7qdQ6Yw8zGvb1Qn4', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0U2THVvUXdpTFdlckNXNkNJN1JKZk5TSno0SXpEbWlNdG1Hd2FMVCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fX0=', 1661472782),
	('uSrfnlc3knDogJyrTJFXKkrafSnBJbdI3Xh7Q85g', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOVNGNlllZDRDVTY4bk5RY1FlV0sySnJxbUJaNHNuQnR0b1Q3aVBZSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jcmVhdGVfc2l0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NzoibG9naW5JRCI7aTozO30=', 1661526439);

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
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`billing_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table teves_system.teves_billing_table: ~5 rows (approximately)
DELETE FROM `teves_billing_table`;
INSERT INTO `teves_billing_table` (`billing_id`, `product_idx`, `drivers_name`, `plate_no`, `product_price`, `client_idx`, `order_quantity`, `order_total_amount`, `order_date`, `order_time`, `order_po_number`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Driver 1', '', 20, 1, 2, 40, '11-10-2022', '00:09:29', '00001', '2022-11-10 00:09:16', '2022-11-10 00:09:17'),
	(2, 1, '12', '12', 1, 1, 1, 0, '2022-11-25', '00:28', '12', '2022-11-25 00:34:55', '2022-11-25 00:34:55'),
	(3, 1, '12', '12', 1, 1, 1, 0, '2022-11-25', '00:28', '12', '2022-11-25 00:35:20', '2022-11-25 00:35:20'),
	(4, 1, 'w', 'w', 20, 1, 12, 0, '2022-11-04', '00:44', 'w', '2022-11-25 00:44:10', '2022-11-25 00:44:10'),
	(5, 1, '12', '12', 20, 1, 1, 20, '2022-11-10', '01:48', 'w', '2022-11-25 00:50:06', '2022-11-25 00:50:06');

-- Dumping structure for table teves_system.teves_client_table
CREATE TABLE IF NOT EXISTS `teves_client_table` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`client_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table teves_system.teves_client_table: ~0 rows (approximately)
DELETE FROM `teves_client_table`;
INSERT INTO `teves_client_table` (`client_id`, `client_name`, `created_at`, `updated_at`) VALUES
	(1, 'Client 1', '2022-11-10 00:08:04', '2022-11-10 00:08:04');

-- Dumping structure for table teves_system.teves_product_table
CREATE TABLE IF NOT EXISTS `teves_product_table` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` text NOT NULL COMMENT 'SITE CODE TO Used on Gateway',
  `product_category` int(11) NOT NULL DEFAULT 0 COMMENT 'COMPANY_CODE',
  `product_price` text NOT NULL COMMENT 'BUSINESS_ENTITY/Location/SITE ed SM SA LAZARO',
  `product_unit_measurement` text DEFAULT NULL COMMENT 'METER_READING_CUTOFF',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`product_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table teves_system.teves_product_table: ~0 rows (approximately)
DELETE FROM `teves_product_table`;
INSERT INTO `teves_product_table` (`product_id`, `product_name`, `product_category`, `product_price`, `product_unit_measurement`, `created_at`, `updated_at`) VALUES
	(1, 'Product1', 1, '20', 'Liters', '2022-11-10 00:07:49', '2022-11-10 00:07:49');

-- Dumping structure for table teves_system.user_tb
CREATE TABLE IF NOT EXISTS `user_tb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `user_real_name` text NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `created_by` varchar(200) NOT NULL,
  `date_created` varchar(200) NOT NULL,
  `modified_by` varchar(200) NOT NULL,
  `date_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1236 DEFAULT CHARSET=latin1;

-- Dumping data for table teves_system.user_tb: ~1 rows (approximately)
DELETE FROM `user_tb`;
INSERT INTO `user_tb` (`id`, `user_name`, `user_real_name`, `user_password`, `user_type`, `created_by`, `date_created`, `modified_by`, `date_modified`) VALUES
	(3, 'admin', 'Teves Gasoline', '$2y$10$mf.POMmfskSb1frX84JdOeu.D4iFT0ot1sO0LBthCw0rRKkcavkJi', 'Admin', '', '', 'admin', '2022-11-09 13:50:15');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
