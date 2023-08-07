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

-- Dumping structure for table teves_system_dev.teves_receivable_payment
CREATE TABLE IF NOT EXISTS `teves_receivable_payment` (
  `receivable_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `receivable_idx` int(11) DEFAULT NULL,
  `client_idx` int(11) DEFAULT NULL,
  `receivable_date_of_payment` varchar(50) DEFAULT NULL,
  `receivable_mode_of_payment` varchar(150) DEFAULT NULL,
  `receivable_reference` varchar(150) DEFAULT NULL,
  `receivable_payment_amount` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`receivable_payment_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table teves_system_dev.teves_receivable_payment: ~50 rows (approximately)
DELETE FROM `teves_receivable_payment`;
INSERT INTO `teves_receivable_payment` (`receivable_payment_id`, `receivable_idx`, `client_idx`, `receivable_date_of_payment`, `receivable_mode_of_payment`, `receivable_reference`, `receivable_payment_amount`, `created_at`, `updated_at`) VALUES
	(3, 31, NULL, '2023-03-30', 'cash', 'CR No. 89', 80000, '2023-05-03 12:56:48', '2023-05-03 12:56:48'),
	(5, 16, NULL, '2023-05-03', 'cash', 'c/o gleza', 1000, '2023-05-03 13:28:21', '2023-05-03 13:28:21'),
	(6, 16, NULL, '2023-05-03', 'cash', 'c/o gleza', 1000, '2023-05-03 13:28:21', '2023-05-03 13:28:21'),
	(7, 31, NULL, '2023-04-18', 'cash', 'CR NO. 302', 70000, '2023-05-03 17:11:45', '2023-05-03 17:11:45'),
	(8, 31, NULL, '2023-04-25', 'cash', 'CR No. 96', 50000, '2023-05-03 17:11:45', '2023-05-03 17:11:45'),
	(9, 22, NULL, '2023-05-06', 'Cash - Partial', 'CR # 308', 74400, '2023-05-06 10:14:56', '2023-05-06 10:14:56'),
	(10, 31, NULL, '2023-05-04', 'Cash - Partial', 'CR NO. 502', 20000, '2023-05-06 11:06:09', '2023-05-06 11:08:20'),
	(11, 54, NULL, '2023-05-11', 'CASH - PARTIAL', 'CR # 311', 34000, '2023-05-11 15:36:01', '2023-06-03 09:34:11'),
	(12, 31, NULL, '2023-05-11', 'Cash - Partial', 'CR NO. 312', 28000, '2023-05-11 17:51:38', '2023-05-11 17:51:38'),
	(13, 58, NULL, '2023-05-12', 'CASH - PARTIAL', 'NONE', 113200, '2023-05-12 17:58:54', '2023-05-12 17:58:54'),
	(14, 58, NULL, '2023-05-14', 'CASH - PARTIAL', 'NONE', 110000, '2023-05-17 10:40:07', '2023-05-17 10:40:07'),
	(15, 58, NULL, '2023-05-20', 'CASH - PARTIAL', 'NONE', 105200, '2023-05-20 14:44:59', '2023-05-20 14:44:59'),
	(17, 65, NULL, '2023-05-21', 'CASH', 'CR # 317', 34000, '2023-05-22 14:11:38', '2023-05-22 14:11:38'),
	(18, 48, NULL, '2023-04-21', 'CASH', 'N/A', 32838.7, '2023-05-24 02:28:04', '2023-05-24 02:28:04'),
	(19, 67, NULL, '2023-04-17', 'CASH', 'TEVES OR # 2521', 35000, '2023-05-24 02:52:38', '2023-05-24 02:52:38'),
	(20, 54, NULL, '2023-05-24', 'CASH', 'CR # 320', 20000, '2023-05-24 13:57:15', '2023-05-24 13:57:15'),
	(21, 66, NULL, '2023-05-24', 'CASH', 'CR 319', 110000, '2023-05-24 16:29:35', '2023-05-24 16:29:35'),
	(22, 53, NULL, '2023-05-27', 'CASH', 'CR 324', 32670.71, '2023-05-27 15:23:09', '2023-06-03 11:37:34'),
	(23, 68, NULL, '2023-05-25', 'CASH DEPOSIT', 'CPM 63347', 332600, '2023-05-27 17:57:13', '2023-05-27 17:57:25'),
	(24, 70, NULL, '2023-05-27', 'CASH', '-', 45728, '2023-05-27 18:52:19', '2023-05-27 18:52:19'),
	(25, 31, NULL, '2023-05-29', 'CASH - PARTIAL', 'Received by GT', 27000, '2023-05-30 10:58:09', '2023-05-30 10:58:09'),
	(29, 82, NULL, '2023-02-02', 'ONLINE TRANSFER - METROBANK TO RCBC', 'RCBC REF # S01671656', 100000, '2023-06-02 11:26:39', '2023-06-02 11:26:39'),
	(30, 82, NULL, '2023-02-15', 'ONLINE TRANSFER - METROBANK TO RCBC', 'RCBC REF # M120979', 17224.49, '2023-06-02 11:26:39', '2023-06-02 11:26:39'),
	(31, 16, NULL, '2023-05-24', 'GCASH', '9010017073823', 2500, '2023-06-02 11:59:30', '2023-06-02 11:59:30'),
	(32, 54, NULL, '2023-06-03', 'CASH', 'CR # 505', 15080, '2023-06-03 09:34:11', '2023-06-03 09:34:11'),
	(33, 53, NULL, '2023-06-03', 'CASH', 'CR 506', 30000, '2023-06-03 11:37:34', '2023-06-03 11:37:34'),
	(34, 80, NULL, '2023-06-05', 'cash deposit', 'RCBC - F01243110', 399760.73, '2023-06-07 07:57:51', '2023-06-07 07:57:51'),
	(35, 31, NULL, '2023-06-08', 'CASH -PARTIAL', 'CR No. 507', 30000, '2023-06-08 13:23:51', '2023-06-08 13:23:51'),
	(36, 91, NULL, '2023-06-09', 'CASH', 'CR No. 508', 47934.2, '2023-06-09 08:43:26', '2023-06-09 08:43:26'),
	(37, 60, NULL, '2023-06-09', 'cash', 'none', 40438.77, '2023-06-09 15:29:57', '2023-06-09 15:29:57'),
	(38, 50, NULL, '2023-06-09', 'cash', 'none', 38978.92, '2023-06-09 15:30:26', '2023-06-09 15:30:26'),
	(39, 41, NULL, '2023-06-09', 'cash', 'none', 54315.97, '2023-06-09 15:30:46', '2023-06-09 15:30:46'),
	(40, 25, NULL, '2023-06-09', 'Cash', 'None', 49969.69, '2023-06-09 15:31:04', '2023-06-09 15:31:04'),
	(41, 2, NULL, '2023-06-09', 'Cash', 'None', 21647.47, '2023-06-09 15:31:20', '2023-06-09 15:31:20'),
	(42, 64, NULL, '2023-06-09', 'Cash', 'None', 30700.72, '2023-06-09 15:32:13', '2023-06-09 15:32:13'),
	(43, 59, NULL, '2023-06-09', 'Cash', 'None', 147796.14, '2023-06-09 15:33:00', '2023-06-09 15:33:00'),
	(44, 49, NULL, '2023-06-09', 'Cash', 'None', 37329.29, '2023-06-09 15:33:28', '2023-06-09 15:33:28'),
	(45, 43, NULL, '2023-06-09', 'Cash', 'None', 45852.78, '2023-06-09 15:33:57', '2023-06-09 15:33:57'),
	(46, 44, NULL, '2023-06-09', 'Cash', 'None', 46461.86, '2023-06-09 15:34:13', '2023-06-09 15:34:13'),
	(47, 51, NULL, '2023-06-09', 'Cash', 'None', 10512, '2023-06-09 15:48:33', '2023-06-09 15:48:33'),
	(48, 56, NULL, '2023-06-09', 'Cash', 'None', 9498.12, '2023-06-09 15:48:47', '2023-06-09 15:48:47'),
	(49, 28, NULL, '2023-06-09', 'Cash', 'None', 4226.14, '2023-06-09 15:49:09', '2023-06-09 15:49:09'),
	(50, 24, NULL, '2023-06-09', 'Cash', 'None', 34040.2, '2023-06-09 15:50:37', '2023-06-09 15:50:37'),
	(51, 36, NULL, '2023-06-09', 'Cash', 'None', 35363.58, '2023-06-09 15:51:31', '2023-06-09 15:51:31'),
	(52, 26, NULL, '2023-06-09', 'Cash', 'None', 27254.95, '2023-06-09 15:51:47', '2023-06-09 15:51:47'),
	(53, 23, NULL, '2023-06-09', 'Cash', 'None', 308503.12, '2023-06-09 15:52:03', '2023-06-09 15:52:03'),
	(54, 21, NULL, '2023-06-09', 'Cash', 'None', 22528.47, '2023-06-09 15:52:19', '2023-06-09 15:52:19'),
	(55, 99, NULL, '2023-06-14', 'CASH', '000', 699.31, '2023-06-14 08:20:12', '2023-06-14 08:20:22'),
	(56, 53, NULL, '2023-06-17', 'separate billing', '---', 1737, '2023-06-17 14:53:08', '2023-06-17 14:53:08'),
	(57, 53, NULL, '2023-06-17', 'CASH', 'CR512', 29919.44, '2023-06-17 14:56:25', '2023-06-17 14:56:25');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
