-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.14-MariaDB - mariadb.org binary distribution
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
CREATE DATABASE IF NOT EXISTS `teves_system` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `teves_system`;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table teves_system.teves_billing_table: ~2 rows (approximately)
INSERT INTO `teves_billing_table` (`billing_id`, `product_idx`, `drivers_name`, `plate_no`, `product_price`, `client_idx`, `order_quantity`, `order_total_amount`, `order_date`, `order_time`, `order_po_number`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Juan', 'A-12345', 20.9, 1, 7.6, 158.84, '2022-12-05', '14:18', '123456', '2022-12-04 14:18:21', '2022-12-04 14:18:21'),
	(2, 1, 'Juan', 'a-03853', 30, 1, 12, 360, '2022-12-06', '14:20', '93643', '2022-12-04 14:20:46', '2022-12-04 14:20:46');

-- Dumping structure for table teves_system.teves_client_table
CREATE TABLE IF NOT EXISTS `teves_client_table` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` text NOT NULL,
  `client_address` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`client_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table teves_system.teves_client_table: ~3 rows (approximately)
INSERT INTO `teves_client_table` (`client_id`, `client_name`, `client_address`, `created_at`, `updated_at`) VALUES
	(1, 'Client 11', 'Address 11', '2022-11-10 00:08:04', '2022-11-30 20:17:40'),
	(4, 'Client 12', 'Address 12', '2022-12-04 00:36:06', '2022-12-04 12:25:34');

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table teves_system.teves_product_table: ~3 rows (approximately)
INSERT INTO `teves_product_table` (`product_id`, `product_name`, `product_price`, `product_category`, `product_unit_measurement`, `created_at`, `updated_at`) VALUES
	(1, 'Product1', 30, 1, 'L', '2022-11-10 00:07:49', '2022-12-04 14:20:06'),
	(4, 'Product2', 67.6, 0, 'L', '2022-12-03 19:13:10', '2022-12-04 12:25:04'),
	(7, 'Product3', 56, 0, 'PC', '2022-12-04 14:04:39', '2022-12-04 14:04:39');

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
) ENGINE=InnoDB AUTO_INCREMENT=1247 DEFAULT CHARSET=latin1;

-- Dumping data for table teves_system.user_tb: ~3 rows (approximately)
INSERT INTO `user_tb` (`user_id`, `user_name`, `user_real_name`, `user_job_title`, `user_password`, `user_type`, `created_at`, `updated_at`) VALUES
	(3, 'admin', 'GLEZA F. TEVES', '', '$2y$10$mf.POMmfskSb1frX84JdOeu.D4iFT0ot1sO0LBthCw0rRKkcavkJi', 'Admin', '2022-11-23 00:00:00', '2022-12-07 21:31:01'),
	(1244, 'dev@danny', 'Developer', '', '$2y$10$bSWs6Q5P.GWHjc5eOyk9x.No.qIomYt.a0a0cMdjVuZ0tt1Agdw1a', 'User', '2022-12-07 21:37:45', '2022-12-07 21:37:45');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
