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

-- Dumping structure for table teves_system_dev.teves_purchase_order_payment_details
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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system_dev.teves_purchase_order_payment_details: ~13 rows (approximately)
DELETE FROM `teves_purchase_order_payment_details`;
INSERT INTO `teves_purchase_order_payment_details` (`purchase_order_payment_details_id`, `purchase_order_idx`, `purchase_order_bank`, `purchase_order_date_of_payment`, `purchase_order_reference_no`, `purchase_order_payment_amount`, `created_at`, `updated_at`) VALUES
	(2, 19, 'RCBC - CARRASCAL', '2023-02-23', 'F01153000', 1223200, '2023-03-02 15:40:07', '2023-03-02 15:40:07'),
	(4, 19, 'RCBC ONLINE TRANSFER', '2023-03-02', '39583', 14300, '2023-03-03 16:18:43', '2023-03-03 16:18:43'),
	(5, 23, 'RCBC - CARRASCAL', '2023-03-03', 'F01444470', 834085.71, '2023-03-03 16:21:38', '2023-03-03 16:21:38'),
	(6, 27, 'RCBC - FT CARRASCAL', '2023-03-23', '-', 0, '2023-03-23 14:54:53', '2023-03-23 14:54:53'),
	(7, 28, 'RCBC - FT CARRASCAL', '2023-02-08', '-', 0, '2023-03-25 17:08:42', '2023-03-25 17:08:42'),
	(8, 29, 'RCBC - FT CARRASCAL', '2023-03-29', '0', 0, '2023-03-29 14:26:54', '2023-03-29 14:26:54'),
	(9, 34, 'RCBC - CARRASCAL', '2023-05-09', 'CR # SPI 00136815', 2093400, '2023-05-18 09:49:37', '2023-05-18 09:49:37'),
	(10, 34, 'overpayment', '2023-05-02', 'CR # SPI 00135647', 2656000, '2023-05-18 09:49:37', '2023-05-18 09:49:37'),
	(12, 38, 'RCBC - CARRASCAL', '2023-05-23', 'F01876566', 1085025, '2023-05-24 16:26:24', '2023-05-24 16:26:24'),
	(13, 36, 'REFER TO P.O. 35', '2023-05-24', '-', 5540000, '2023-05-31 14:30:30', '2023-05-31 14:30:30'),
	(14, 40, 'OVERPAYMENT P. O # 35', '2023-05-24', '-', 1694400, '2023-05-31 14:32:35', '2023-05-31 14:32:35'),
	(15, 41, 'CASH', '2023-06-01', '-', 20000, '2023-06-01 16:41:57', '2023-06-01 16:41:57'),
	(26, 40, 'RCBC - FT CARRASCAL', '2023-06-01', 'F01178955', 800400, '2023-06-04 18:11:05', '2023-06-04 18:11:05');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
