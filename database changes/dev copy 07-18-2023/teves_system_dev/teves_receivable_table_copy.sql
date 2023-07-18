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

-- Dumping structure for table teves_system_dev.teves_receivable_table_copy
CREATE TABLE IF NOT EXISTS `teves_receivable_table_copy` (
  `receivable_id` int(11) NOT NULL AUTO_INCREMENT,
  `receivable_name` varchar(255) DEFAULT NULL,
  `client_idx` int(11) DEFAULT NULL,
  `control_number` text DEFAULT NULL,
  `billing_date` date DEFAULT NULL,
  `billing_period_start` date DEFAULT NULL,
  `billing_period_end` date DEFAULT NULL,
  `tin_number` varchar(255) DEFAULT NULL,
  `or_number` varchar(255) DEFAULT NULL,
  `payment_term` text DEFAULT NULL,
  `receivable_description` varchar(255) DEFAULT NULL,
  `receivable_gross_amount` double DEFAULT NULL,
  `receivable_vatable_sales` double DEFAULT NULL,
  `receivable_vat_amount` double DEFAULT NULL,
  `receivable_net_value_percentage` double DEFAULT NULL,
  `receivable_withholding_tax_percentage` double DEFAULT NULL,
  `receivable_withholding_tax` double DEFAULT NULL,
  `withholding_tax_percentage` double DEFAULT NULL,
  `receivable_amount` double DEFAULT NULL,
  `receivable_remaining_balance` double DEFAULT NULL,
  `receivable_status` varchar(255) DEFAULT NULL,
  `less_per_liter` double DEFAULT NULL,
  `company_header` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`receivable_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system_dev.teves_receivable_table_copy: ~0 rows (approximately)
DELETE FROM `teves_receivable_table_copy`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
