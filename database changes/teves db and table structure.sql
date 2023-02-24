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

-- Data exporting was unselected.

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
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for table teves_system.teves_purchase_order_payment_details
CREATE TABLE IF NOT EXISTS `teves_purchase_order_payment_details` (
  `purchase_order_payment_details_id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_order_idx` int(11) DEFAULT NULL,
  `purchase_order_bank` varchar(255) DEFAULT NULL,
  `purchase_order_date_of_payment` varchar(50) DEFAULT NULL,
  `purchase_order_reference_no` varchar(100) DEFAULT NULL,
  `purchase_order_payment_amount` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`purchase_order_payment_details_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

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
  `purchase_loading_terminal` text DEFAULT NULL,
  `purchase_order_date_of_pickup` text DEFAULT NULL,
  `purchase_order_date_of_arrival` text DEFAULT NULL,
  `purchase_order_gross_amount` double DEFAULT NULL,
  `purchase_order_total_liters` double DEFAULT NULL,
  `purchase_order_net_percentage` double DEFAULT NULL,
  `purchase_order_net_amount` double DEFAULT NULL,
  `purchase_order_less_percentage` double DEFAULT NULL,
  `purchase_order_total_payable` double DEFAULT NULL,
  `hauler_operator` text DEFAULT NULL,
  `lorry_driver` text DEFAULT NULL,
  `plate_number` text DEFAULT NULL,
  `contact_number` text DEFAULT NULL,
  `purchase_destination` text DEFAULT NULL,
  `purchase_destination_address` text DEFAULT NULL,
  `purchase_date_of_departure` text DEFAULT NULL,
  `purchase_date_of_arrival` text DEFAULT NULL,
  `purchase_order_instructions` text DEFAULT NULL,
  `purchase_order_note` text DEFAULT NULL,
  `purchase_status` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`purchase_order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

-- Dumping structure for table teves_system.teves_sales_order_payment_details
CREATE TABLE IF NOT EXISTS `teves_sales_order_payment_details` (
  `sales_order_payment_details_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_order_idx` int(11) DEFAULT NULL,
  `sales_order_mode_of_payment` varchar(255) DEFAULT NULL,
  `sales_order_date_of_payment` varchar(50) DEFAULT NULL,
  `sales_order_reference_no` varchar(100) DEFAULT NULL,
  `sales_order_payment_amount` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sales_order_payment_details_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.

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

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
