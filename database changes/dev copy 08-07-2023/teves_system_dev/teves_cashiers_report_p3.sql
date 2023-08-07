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

-- Dumping structure for table teves_system_dev.teves_cashiers_report_p3
CREATE TABLE IF NOT EXISTS `teves_cashiers_report_p3` (
  `cashiers_report_p3_id` int(11) NOT NULL AUTO_INCREMENT,
  `cashiers_report_id` int(11) DEFAULT NULL,
  `user_idx` int(11) DEFAULT NULL,
  `product_idx` int(11) DEFAULT NULL,
  `order_quantity` double DEFAULT NULL,
  `cashiers_report_p1_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Cashier''s Report Phase 1 ID Source',
  `pump_price` double DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `discounted_price` double DEFAULT NULL,
  `order_total_amount` double DEFAULT NULL,
  `miscellaneous_items_type` varchar(10) DEFAULT NULL,
  `co_name_cash_out` varchar(100) DEFAULT NULL,
  `date_cashout` varchar(10) DEFAULT NULL,
  `time_cash_out` varchar(10) DEFAULT NULL,
  `cashout_amount` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`cashiers_report_p3_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system_dev.teves_cashiers_report_p3: ~6 rows (approximately)
DELETE FROM `teves_cashiers_report_p3`;
INSERT INTO `teves_cashiers_report_p3` (`cashiers_report_p3_id`, `cashiers_report_id`, `user_idx`, `product_idx`, `order_quantity`, `cashiers_report_p1_id`, `pump_price`, `unit_price`, `discounted_price`, `order_total_amount`, `miscellaneous_items_type`, `co_name_cash_out`, `date_cashout`, `time_cash_out`, `cashout_amount`, `created_at`, `updated_at`) VALUES
	(13, 2, 1249, 12, 34, 0, 1, 1, 33.9, 1152.6, NULL, NULL, NULL, NULL, NULL, '2023-08-02 20:24:49', '2023-08-02 20:24:49'),
	(16, 1, 1249, 12, 45, 0, 70, 0, 0, 3150, NULL, NULL, NULL, NULL, NULL, '2023-08-02 22:25:45', '2023-08-02 23:12:39'),
	(20, 1, 1249, 14, 1, 0, 0, 13, 0, 13, NULL, NULL, NULL, NULL, NULL, '2023-08-02 22:44:21', '2023-08-02 23:14:46'),
	(21, 1, 1249, 14, 1, 0, 0, 12, 0, 12, NULL, NULL, NULL, NULL, NULL, '2023-08-02 22:44:48', '2023-08-02 22:59:28'),
	(22, 1, 1249, 11, 3, 0, 40, 2, 38, 114, NULL, NULL, NULL, NULL, NULL, '2023-08-02 23:15:07', '2023-08-02 23:15:07');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
