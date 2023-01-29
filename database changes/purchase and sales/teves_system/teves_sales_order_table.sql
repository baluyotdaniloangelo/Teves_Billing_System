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

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
