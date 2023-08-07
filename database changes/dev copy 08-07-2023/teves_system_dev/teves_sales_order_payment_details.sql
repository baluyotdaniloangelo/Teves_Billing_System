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

-- Dumping structure for table teves_system_dev.teves_sales_order_payment_details
CREATE TABLE IF NOT EXISTS `teves_sales_order_payment_details` (
  `sales_order_payment_details_id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_order_idx` int(11) DEFAULT NULL,
  `sales_order_date` varchar(50) DEFAULT NULL,
  `sales_order_mode_of_payment` varchar(255) DEFAULT NULL,
  `sales_order_date_of_payment` varchar(50) DEFAULT NULL,
  `sales_order_reference_no` varchar(100) DEFAULT NULL,
  `sales_order_payment_amount` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sales_order_payment_details_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system_dev.teves_sales_order_payment_details: ~17 rows (approximately)
DELETE FROM `teves_sales_order_payment_details`;
INSERT INTO `teves_sales_order_payment_details` (`sales_order_payment_details_id`, `sales_order_idx`, `sales_order_date`, `sales_order_mode_of_payment`, `sales_order_date_of_payment`, `sales_order_reference_no`, `sales_order_payment_amount`, `created_at`, `updated_at`) VALUES
	(1, 4, NULL, 'CASH', '2023-02-17', 'N/A', 461200, '2023-02-25 21:21:03', '2023-02-25 21:21:03'),
	(5, 9, NULL, '0', '2023-03-03', '0', 0, '2023-03-03 16:03:08', '2023-03-03 16:03:08'),
	(7, 11, NULL, 'CASH', '2023-03-17', '-', 351900, '2023-03-17 17:42:21', '2023-03-17 17:42:21'),
	(8, 12, NULL, 'CASH', '2023-03-15', 'N/A', 111600, '2023-03-17 18:04:41', '2023-03-17 18:04:41'),
	(9, 13, NULL, 'CASH', '2023-03-22', '-', 110900, '2023-03-25 17:20:21', '2023-03-25 17:20:21'),
	(10, 16, NULL, 'CASH DEPOSIT', '2023-03-29', 'M95862', 816000, '2023-03-31 12:28:23', '2023-03-31 12:28:23'),
	(11, 18, NULL, 'CASH', '2023-04-22', '-', 558567.86, '2023-04-22 14:09:40', '2023-04-22 14:09:40'),
	(87, 69, NULL, 'CASH DEPOSIT', '2023-05-05', 'F01333878', 225200, '2023-05-24 03:37:32', '2023-05-24 03:37:32'),
	(88, 69, NULL, 'CASH DEPOSIT', '2023-05-05', 'F01333878- PAYMENT FOR SHORT', 200, '2023-05-24 03:37:32', '2023-05-24 03:37:32'),
	(89, 70, NULL, 'CASH DEPOSIT', '2023-05-12', 'F01537047', 333000, '2023-05-24 03:43:03', '2023-05-24 03:43:03'),
	(90, 71, NULL, 'CASH DEPOSIT', '2023-05-19', 'F01768441', 333000, '2023-05-24 03:50:45', '2023-05-24 03:50:45'),
	(91, 67, NULL, 'CASH', '2023-05-30', 'GT CR # 325', 110000, '2023-05-30 17:59:13', '2023-05-30 17:59:13'),
	(94, 66, NULL, 'CASH', '2023-06-05', 'Received By Len Teves', 112000, '2023-06-05 18:11:01', '2023-06-05 18:11:01'),
	(96, 83, NULL, 'CASH DEPOSIT', '2023-06-07', '3F05PFE2CK4843', 771450, '2023-06-07 23:36:42', '2023-06-07 23:36:42'),
	(97, 76, NULL, 'cash', '2023-06-09', 'None', 109800, '2023-06-09 15:25:18', '2023-06-09 15:25:18'),
	(98, 76, NULL, 'S.O. # 66 Overpayment', '2023-06-05', 'None', 2000, '2023-06-09 15:25:18', '2023-06-09 15:25:57'),
	(99, 82, NULL, 'CASH', '2023-06-16', 'NONE', 111800, '2023-06-16 07:44:59', '2023-06-16 07:44:59');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
