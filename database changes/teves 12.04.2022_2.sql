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
DROP TABLE IF EXISTS `sessions`;
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

-- Data exporting was unselected.

-- Dumping structure for table teves_system.teves_billing_table
DROP TABLE IF EXISTS `teves_billing_table`;
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

-- Data exporting was unselected.

-- Dumping structure for table teves_system.teves_client_table
DROP TABLE IF EXISTS `teves_client_table`;
CREATE TABLE IF NOT EXISTS `teves_client_table` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` text NOT NULL,
  `client_address` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`client_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table teves_system.teves_product_table
DROP TABLE IF EXISTS `teves_product_table`;
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

-- Data exporting was unselected.

-- Dumping structure for table teves_system.user_tb
DROP TABLE IF EXISTS `user_tb`;
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

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
