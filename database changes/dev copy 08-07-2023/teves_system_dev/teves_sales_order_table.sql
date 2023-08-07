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

-- Dumping structure for table teves_system_dev.teves_sales_order_table
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
  `sales_order_status` varchar(255) DEFAULT NULL,
  `sales_order_delivery_status` varchar(255) DEFAULT NULL,
  `company_header` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sales_order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system_dev.teves_sales_order_table: ~34 rows (approximately)
DELETE FROM `teves_sales_order_table`;
INSERT INTO `teves_sales_order_table` (`sales_order_id`, `sales_order_client_idx`, `sales_order_control_number`, `sales_order_date`, `sales_order_dr_number`, `sales_order_or_number`, `sales_order_payment_term`, `sales_order_delivered_to`, `sales_order_delivered_to_address`, `sales_order_delivery_method`, `sales_order_gross_amount`, `sales_order_net_percentage`, `sales_order_net_amount`, `sales_order_less_percentage`, `sales_order_total_due`, `sales_order_hauler`, `sales_order_required_date`, `sales_order_instructions`, `sales_order_note`, `sales_order_mode_of_payment`, `sales_order_date_of_payment`, `sales_order_reference_no`, `sales_order_payment_amount`, `sales_order_status`, `sales_order_delivery_status`, `company_header`, `created_at`, `updated_at`) VALUES
	(1, 108, '00000001', '2023-01-30', NULL, NULL, 'CBD', 'PORT OF TEFASCO WHARF', 'PANACAN, DAVAO CITY', 'DELIVERED', 1452000, 1.12, 1296428.57, 1, 1439035.71, 'RAFEMA GAZZ', '2023-01-30', 'SAMAL - 12KL\nFORTIS 7 - 10KL', NULL, 'CHECK DEPOSIT - RCBC', '2023-01-31', 'KQP64155', '1439035.71', 'Paid', 'Delivered', NULL, '2023-02-05 16:33:59', '2023-06-03 23:22:48'),
	(2, 108, '00000002', '2023-02-06', NULL, NULL, 'COD', 'PANACAN, DAVAO CITY', 'PANACAN, DAVAO CITY', 'DELIVERED', 1403600, 1.12, 1253214.29, 1, 1391067.86, 'Teves', '2023-02-06', 'Fortis 8 - 10kl\nFortis 5 - 6kl\nSigaboy - 6kl', NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', NULL, '2023-02-08 13:19:54', '2023-06-03 23:22:51'),
	(4, 40, '00000003', '2023-02-17', 'n/a', NULL, 'COD', 'KLEEN GAS STATION', 'MAHAYAG SAN MIGUEL SDS', 'DELIVERED', 461200, 1.12, 411785.71, 1, 457082.14, 'Teves', '2023-02-17', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', NULL, '2023-02-25 21:21:03', '2023-06-03 23:22:54'),
	(9, 114, '00000009', '2023-03-03', NULL, NULL, 'COD', 'PICK UP', 'PICK UP', 'PICK UP - SEAOIL - LUDO CEBU', 912000, NULL, 0, NULL, 912000, 'PICK UP', '2023-03-03', NULL, NULL, '["0"]', '["2023-03-03"]', '["0"]', '["0"]', 'Pending', 'Delivered', 'Teves', '2023-03-03 16:00:57', '2023-06-04 22:02:36'),
	(10, 103, '00000010', '2023-03-16', '-', '-', 'COD', '-', '-', 'N/A', 19196, NULL, 0, NULL, 19196, 'N/A', '2023-03-16', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', NULL, '2023-03-16 10:30:49', '2023-06-04 22:02:47'),
	(11, 40, '00000011', '2023-03-17', NULL, NULL, 'COD', 'KLEEN GAS STATION', 'MAHAYAG SAN MIGUEL SDS', 'DELIVERED', 462800, 1.12, 413214.29, 1, 458667.86, 'Teves', '2023-03-17', 'TO FOLLOW DIESEL 2 KL', NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', NULL, '2023-03-17 17:42:21', '2023-06-04 22:02:50'),
	(12, 115, '00000012', '2023-03-07', '05654', '0', 'COD', 'CKA HARDWARE', 'TIGAO, CORTES, SURIGAO DEL SUR', NULL, 111600, NULL, 0, NULL, 111600, NULL, '2023-03-07', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', NULL, '2023-03-17 18:04:41', '2023-06-04 22:02:52'),
	(13, 40, '00000013', '2023-03-25', '5659', '-', 'COD', 'KLEEN GAS STATION', 'MAHAYAG SAN MIGUEL SDS', 'DELIVERED', 110900, NULL, 0, NULL, 110900, 'Teves', '2023-03-22', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', NULL, '2023-03-25 17:20:21', '2023-06-04 22:02:54'),
	(14, 115, '00000014', '2023-03-15', '-', '-', 'COD', 'CKA HARDWARE', 'TIGAO, CORTES, SURIGAO DEL SUR', 'DELIVERED', 110000, NULL, 0, NULL, 110000, 'Teves', '2023-03-19', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', NULL, '2023-03-25 17:25:30', '2023-06-04 22:03:08'),
	(15, 108, '00000015', '2023-03-24', '06852', NULL, 'COD', 'PORT OF TEFASCO WHARF', 'PANACAN, DAVAO CITY', 'DELIVERED', 1632400, 1.12, 1457500, 1, 1617825, 'Teves', '2023-03-24', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', NULL, '2023-03-30 11:04:22', '2023-06-04 22:03:06'),
	(16, 118, '00000016', '2023-03-29', NULL, NULL, 'COD', 'GASPAR BODEGA', 'PUROK 5 BRGY. GABI COMPOSTELA, NABUNTURAN', 'DELIVERED', 816000, NULL, 0, NULL, 816000, 'TEVES- NICANOR', '2023-03-29', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', NULL, '2023-03-31 12:28:23', '2023-06-04 22:03:04'),
	(17, 40, '00000017', '2023-03-31', NULL, NULL, 'COD', 'KLEEN GAS STATION', 'MAHAYAG SAN MIGUEL SDS', 'DELIVERED', 345200, NULL, 0, NULL, 345200, 'TEVES- NICANOR', '2023-03-31', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', NULL, '2023-03-31 12:34:27', '2023-06-04 22:03:03'),
	(18, 40, '00000018', '2023-04-22', NULL, '29103', 'COD', 'MAHAYAG SAN MIGUEL SDS', 'MAHAYAG SAN MIGUEL SDS', 'DELIVERED', 563600, 1.12, 503214.29, 1, 558567.86, 'TEVES - RONALD', '2023-04-22', NULL, NULL, '["CASH"]', '["2023-04-22"]', '["-"]', '["558567.86"]', 'Paid', 'Delivered', NULL, '2023-04-22 11:34:01', '2023-06-04 22:03:01'),
	(64, 115, '00000019', '2023-05-01', '10451', NULL, 'COD', 'CKA HARDWARE', 'TIGAO, CORTES, SURIGAO DEL SUR', 'DELIVERED', 106000, NULL, 0, NULL, 106000, 'Teves', '2023-05-01', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', 'GT', '2023-05-18 09:54:04', '2023-06-03 23:23:21'),
	(65, 115, '00000065', '2023-05-18', '0', NULL, 'COD', 'CKA HARDWARE', 'TIGAO, CORTES, SURIGAO DEL SUR', 'DELIVERED', 98000, NULL, 0, NULL, 98000, 'Teves', '2023-05-18', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', 'GT', '2023-05-18 09:55:00', '2023-06-03 23:23:15'),
	(66, 124, '00000066', '2023-05-24', 'DR # 10457', '-', 'WEEKLY', 'CORTES, SURIGAO DEL SUR', 'CORTES, SURIGAO DEL SUR', 'DELIVERED', 110000, NULL, 0, NULL, 110000, 'TEVES', '2023-05-12', NULL, NULL, '["CASH"]', '["2023-06-05"]', '["Received By Len Teves"]', '["112000"]', 'Paid', 'Delivered', 'GT', '2023-05-24 02:25:55', '2023-06-05 18:11:18'),
	(67, 124, '00000067', '2023-05-24', 'DR # 10463', 'GT CR # 325', 'WEEKLY', 'CORTES, SURIGAO DEL SUR', 'CORTES, SURIGAO DEL SUR', 'DELIVERED', 110000, NULL, 0, NULL, 110000, 'TEVES', '2023-05-12', NULL, NULL, '["CASH"]', '["2023-05-30"]', '["GT CR # 325"]', '["110000"]', 'Paid', 'Delivered', 'GT', '2023-05-24 02:26:36', '2023-06-03 23:23:14'),
	(68, 40, '00000068', '2023-05-18', '10458', 'SI NO. 29108', 'COD', 'KLEEN GAS STATION', 'MAHAYAG SAN MIGUEL SDS', 'DELIVERED', 291000, 1.12, 259821.43, 1, 288401.79, 'Teves', '2023-08-18', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', 'GT', '2023-05-24 03:20:34', '2023-06-03 23:23:16'),
	(69, 82, '00000069', '2023-02-16', '-', '-', 'MONTHLY', 'GEMMALYN CHOA', 'ROSARIO, TANDAG SURIGAO DEL SUR', 'DELIVERED', 225200, NULL, 0, NULL, 225200, 'Teves', '2023-02-16', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', 'GT', '2023-05-24 03:37:32', '2023-06-03 23:22:52'),
	(70, 82, '00000070', '2023-03-07', '5655', 'N/A', 'MONTHLY', 'GEMMALYN CHOA', 'ROSARIO, TANDAG SURIGAO DEL SUR', 'DELIVERED', 333000, NULL, 0, NULL, 333000, 'TEVES', '2023-03-07', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', 'GT', '2023-05-24 03:43:03', '2023-06-03 23:24:11'),
	(71, 82, '00000071', '2023-03-22', '5660', 'N/A', 'MONTHLY', 'GEMMALYN CHOA', 'ROSARIO, TANDAG SURIGAO DEL SUR', 'DELIVERED', 319800, NULL, 0, NULL, 319800, 'TEVES', '2023-03-22', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', 'GT', '2023-05-24 03:50:45', '2023-06-03 23:24:13'),
	(72, 82, '00000072', '2023-04-06', 'n/a', 'N/A', 'MONTHLY', 'GEMMALYN CHOA', 'ROSARIO, TANDAG SURIGAO DEL SUR', 'DELIVERED', 321600, NULL, 0, NULL, 321600, 'TEVES', '2023-04-06', NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Delivered', 'GT', '2023-05-24 04:07:38', '2023-06-03 23:24:14'),
	(73, 82, '00000073', '2023-04-23', '6862', 'N/A', 'MONTHLY', 'ROSARIO, TANDAG SURIGAO DEL SUR', 'ROSARIO, TANDAG SURIGAO DEL SUR', 'DELIVERED', 427200, NULL, 0, NULL, 427200, 'TEVES', '2023-04-23', NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Delivered', 'GT', '2023-05-24 04:08:36', '2023-06-03 23:23:23'),
	(74, 82, '00000074', '2023-05-11', '10456', 'N/A', 'MONTHLY', 'GEMMALYN CHOA', 'ROSARIO, TANDAG SURIGAO DEL SUR', 'DELIVERED', 291000, NULL, 0, NULL, 291000, 'TEVES', '2023-05-11', NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Delivered', 'GT', '2023-05-24 04:10:06', '2023-06-03 23:23:19'),
	(75, 82, '00000075', '2023-05-18', '10459', 'N/A', 'MONTHLY', 'GEMMALYN CHOA', 'ROSARIO, TANDAG SURIGAO DEL SUR', 'DELIVERED', 97000, NULL, 0, NULL, 97000, 'TEVES', '2023-05-18', NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Delivered', 'GT', '2023-05-24 04:11:09', '2023-06-03 23:23:17'),
	(76, 124, '00000076', '2023-05-25', '10464', '-', 'WEEKLY', 'CORTES, SURIGAO DEL SUR', 'CORTES, SURIGAO DEL SUR', 'DELIVERED', 111800, 0, 0, 0, 111800, 'TEVES', '2023-05-25', NULL, NULL, '["cash","S.O. # 66 Overpayment"]', '["2023-06-09","2023-06-05"]', '["None","None"]', '["109800","2000"]', 'Paid', 'Delivered', 'GT', '2023-05-29 16:18:20', '2023-06-09 15:25:57'),
	(77, 40, '00000077', '2023-05-30', NULL, NULL, 'COD', 'KLEEN GAS STATION', 'MAHAYAG SAN MIGUEL SDS', 'DELIVERED', 525600, NULL, 0, NULL, 525600, 'TEVES', '2023-05-30', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', 'GT', '2023-05-30 12:18:25', '2023-06-03 23:23:07'),
	(78, 114, '00000078', '2023-03-06', NULL, NULL, 'COD', 'PICK UP', 'PICK UP', NULL, 1152000, NULL, 0, NULL, 1152000, NULL, '2023-03-06', NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Delivered', 'Teves', '2023-06-01 12:53:05', '2023-06-04 22:03:16'),
	(81, 82, '00000079', '2023-05-25', '10008', 'N/A', 'MONTHLY', 'GEMMALYN CHOA', 'ROSARIO, TANDAG SURIGAO DEL SUR', 'DELIVERED', 294000, NULL, 0, NULL, 294000, 'Teves', '2023-05-25', NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Delivered', 'GT', '2023-06-03 23:27:50', '2023-06-03 23:28:30'),
	(82, 124, '00000082', '2023-06-05', '10466', '-', 'WEEKLY', 'CORTES, SURIGAO DEL SUR', 'CORTES, SURIGAO DEL SUR', 'DELIVERED', 111800, 1.12, 99821.43, 0, 111800, 'TEVES', '2023-05-30', NULL, NULL, '["CASH"]', '["2023-06-16"]', '["NONE"]', '["111800.00"]', 'Paid', 'Delivered', 'GT', '2023-06-05 18:15:16', '2023-06-16 07:44:59'),
	(83, 128, '00000083', '2023-06-05', NULL, '29112', 'CBD', 'RMF Northlane Gas Station Tandag', 'PHOENIX GAS STATION - TANDAG', 'DELIVERED', 778400, 1.12, 695000, 1, 771450, 'TEVES', '2023-06-05', NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Delivered', 'Teves', '2023-06-07 23:36:42', '2023-06-07 23:37:33'),
	(84, 124, '00000084', '2023-06-09', '10467', '-', 'Weekly', 'CORTES, SURIGAO DEL SUR', 'CORTES, SURIGAO DEL SUR', 'DELIVERED', 111800, 1.12, 99821.43, 0, 111800, 'TEVES', '2023-06-05', NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Delivered', 'GT', '2023-06-09 15:27:04', '2023-06-09 15:39:08'),
	(85, 128, '00000085', '2023-06-05', '68767', NULL, 'COD', 'PHOENIX GAS STATION - TANDAG', 'PHOENIX GAS STATION - TANDAG', 'Delivered', 778400, 1.12, 695000, 1, 771450, 'Teves', '2023-06-05', NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Delivered', 'Teves', '2023-06-13 17:04:40', '2023-06-13 17:11:29'),
	(86, 126, '00000086', '2023-06-08', '10468', '-', 'COD', 'SUMO-SUMO, TAGO, SURIGAO DEL SUR', 'SUMO-SUMO, TAGO, SURIGAO DEL SUR', 'DELIVERED', 100000, 0, 0, 0, 100000, 'TEVES', '2023-06-08', NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Delivered', 'GT', '2023-06-14 16:06:43', '2023-06-14 16:07:25');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
