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

-- Dumping structure for table teves_system_dev.teves_purchase_order_table
CREATE TABLE IF NOT EXISTS `teves_purchase_order_table` (
  `purchase_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_order_supplier_idx` int(11) DEFAULT NULL,
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
  `company_header` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`purchase_order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system_dev.teves_purchase_order_table: ~21 rows (approximately)
DELETE FROM `teves_purchase_order_table`;
INSERT INTO `teves_purchase_order_table` (`purchase_order_id`, `purchase_order_supplier_idx`, `purchase_supplier_name`, `purchase_supplier_tin`, `purchase_supplier_address`, `purchase_order_control_number`, `purchase_order_date`, `purchase_order_sales_order_number`, `purchase_order_collection_receipt_no`, `purchase_order_official_receipt_no`, `purchase_order_delivery_receipt_no`, `purchase_order_bank`, `purchase_order_date_of_payment`, `purchase_order_reference_no`, `purchase_order_payment_amount`, `purchase_order_delivery_method`, `purchase_loading_terminal`, `purchase_order_date_of_pickup`, `purchase_order_date_of_arrival`, `purchase_order_gross_amount`, `purchase_order_total_liters`, `purchase_order_net_percentage`, `purchase_order_net_amount`, `purchase_order_less_percentage`, `purchase_order_total_payable`, `hauler_operator`, `lorry_driver`, `plate_number`, `contact_number`, `purchase_destination`, `purchase_destination_address`, `purchase_date_of_departure`, `purchase_date_of_arrival`, `purchase_order_instructions`, `purchase_order_note`, `purchase_status`, `company_header`, `created_at`, `updated_at`) VALUES
	(10, 1, 'Seaoil Philippines Inc.', '005-054-970-000', '22F The Taipan Place, F. Ortigas Jr. Road, Ortigas Center Pasig City, Manila 1605', '00000001', '2023-01-20', 'SPI 132564', '-', 'SPI 00113780', 'SPI 00286732', 'Overpayment  from FT', '2023-01-13', 'F01906739', '1017000.00', 'Pick Up', 'Teves', '2023-01-20', '2023-02-21', 1713000, NULL, 1.12, 1529464.29, 1, 1697705.36, 'Ronald Cabatuan', NULL, '', '', 'Teves Gas Station', 'MADRID SDS', '2023-01-09', '2023-01-21', 'None', 'None', 'Paid', NULL, '2023-02-08 12:50:37', '2023-02-25 22:53:29'),
	(11, 2, 'Filnix Petroleum Products Distribution', '435-468-973-000', 'Purok Matinatapon, San Jose (Bo. 5), Koronadal City, South Cotabato', '00000011', '2023-02-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pick Up', 'Teves', '2023-02-08', NULL, 896200, NULL, NULL, 0, NULL, 896200, 'Erwin Budias', 'MAC 4767', 'IOC', '', 'Teves Gas Station', 'MADRID SDS', NULL, NULL, NULL, NULL, 'Paid', NULL, '2023-02-08 13:06:54', '2023-02-25 22:53:28'),
	(12, 1, 'Seaoil Philippines Inc.', '005-054-970-000', '22F The Taipan Place, F. Ortigas Jr. Road, Ortigas Center Pasig City, Manila 1605', '00000012', '2023-02-06', 'SPI 135976', 'SPI 00118426', 'SPI 00162633', 'SPI 00291171', 'RCBC CHECK DEPOSIT', '2023-02-06', 'F01614599', '1214400', 'PICK UP', 'TEVES', '2023-02-06', '2023-02-07', 1214400, NULL, NULL, 0, NULL, 1214400, 'Erwin Budias', 'MAC 4767', '', '', 'DAVAO GULF MARINE SERVICES INC', 'TEFASCO PANACAN DAVAO CITY', '2023-02-06', '2023-02-07', NULL, NULL, 'Paid', NULL, '2023-02-14 12:27:37', '2023-02-25 22:53:29'),
	(15, 1, 'Seaoil Philippines Inc.', '005-054-970-000', '22F The Taipan Place, F. Ortigas Jr. Road, Ortigas Center Pasig City, Manila 1605', '00000013', '2023-02-16', 'SPI 137967', 'SPI 00119816', 'SPI 00164133', NULL, 'RCBC FUND TRANSFER', '2023-02-14', 'F01880217', '1157200', 'PICK UP', 'ADMIN - TEVES', '2023-02-15', '2023-02-15', 1157200, NULL, NULL, 0, NULL, 1157200, 'ERWIN BUDIAS', 'MAC 4767', '', '', 'DAVAO GULF MARINE SERVICES INC', 'TEFASCO PANACAN DAVAO CITY', '2023-02-15', '2023-02-15', NULL, NULL, 'Paid', NULL, '2023-02-16 14:03:14', '2023-02-25 22:53:23'),
	(16, 1, 'Seaoil Philippines Inc.', '005-054-970-000', '22F The Taipan Place, F. Ortigas Jr. Road, Ortigas Center Pasig City, Manila 1605', '00000016', '2023-02-14', 'SPI 137973', 'SPI00119817', 'SPI 00164847', NULL, 'RCBC FUND TRANSFER', '2023-02-14', 'F01879874', '1202800', 'PICK UP', 'TEVES', '2023-02-16', '2023-02-17', 1202800, NULL, NULL, 0, NULL, 1202800, 'Erwin Budias', 'MAC 4767', '', '', 'Teves Gas Station', 'MADRID SDS', '2023-02-16', '2023-02-17', 'DELIVERY TO TANDAG DIESEL 4KL\nSHIP TO SAN MIGUEL PREM 4KL / DIESEL 4KL', NULL, 'Paid', NULL, '2023-02-16 14:20:11', '2023-02-25 22:53:28'),
	(19, 3, 'VNT TRADE & SUPPLY INC', '247-546-158-000', 'Unit 28 C. Raymundo Ave., Dona Juana Townhomes, Maybunga, Pasig City', '00000017', '2023-02-16', 'ATL 822', NULL, NULL, '068211', NULL, NULL, NULL, NULL, 'Delivered', 'IOC INSULAR', NULL, NULL, 2475000, NULL, NULL, 0, NULL, 2475000, 'VNT', '-', 'CBP 8139', '-', 'Teves Gas Station', 'MADRID SDS', '2023-02-20', '2023-02-21', NULL, NULL, 'Paid', NULL, '2023-03-02 15:40:07', '2023-03-04 16:51:08'),
	(23, 1, 'Seaoil Philippines Inc.', '005-054-970-000', '22F The Taipan Place, F. Ortigas Jr. Road, Ortigas Center Pasig City, Manila 1605', '00000020', '2023-03-03', 'SPI 142574', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PICK UP', 'SEAOIL PH INC - LUDO, CEBU', NULL, NULL, 841600, NULL, 1.12, 751428.57, 1, 834085.71, 'PICK UP - CLIENT', 'JUMILAN INDIOLA', 'NEV 8522', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', NULL, '2023-03-03 16:21:38', '2023-04-02 15:32:58'),
	(24, 2, 'Filnix Petroleum Products Distribution', '435-468-973-000', 'Purok Matinatapon, San Jose (Bo. 5), Koronadal City, South Cotabato', '00000024', '2023-03-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PICK UP', 'IOC- Insular', NULL, NULL, 1506000, NULL, NULL, 0, NULL, 1506000, 'Teves', 'Ronald Cabatuan', NULL, NULL, 'DAVAO GULF MARINE SERVICES INC', 'TEFASCO PANACAN DAVAO CITY', NULL, NULL, NULL, NULL, 'Paid', NULL, '2023-03-21 12:50:57', '2023-04-28 13:05:36'),
	(26, 2, 'Filnix Petroleum Products Distribution', '435-468-973-000', 'Purok Matinatapon, San Jose (Bo. 5), Koronadal City, South Cotabato', '00000025', '2023-03-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PICK UP', 'IOC- Insular', NULL, NULL, 1104400, NULL, NULL, 0, NULL, 1104400, 'Teves', 'Erwin Budias', NULL, NULL, 'Madrid', 'Madrid', NULL, NULL, 'Kleen Gas Diesel 4kl\nGemmalyn Choa Diesel 6kl', NULL, 'Paid', NULL, '2023-03-21 12:56:36', '2023-04-28 13:05:37'),
	(27, 2, 'Filnix Petroleum Products Distribution', '435-468-973-000', 'Purok Matinatapon, San Jose (Bo. 5), Koronadal City, South Cotabato', '00000027', '2023-03-23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PICK UP', 'Phoenix', NULL, NULL, 1560300, NULL, NULL, 0, NULL, 1560300, 'TEVES', 'Ronald Cabatuan', NULL, NULL, 'Teves Gas Station', 'MADRID SDS', NULL, NULL, NULL, NULL, 'Paid', NULL, '2023-03-23 14:54:53', '2023-04-28 13:05:33'),
	(28, 2, 'Filnix Petroleum Products Distribution', '-', 'Purok Matinatapon, San Jose (Bo. 5), Koronadal City, South Cotabato', '00000028', '2023-02-09', '-', '-', '-', 'IOC 455744 / 458307', NULL, NULL, NULL, NULL, 'PICK UP', 'IOC INSULAR', NULL, NULL, 1244800, NULL, NULL, 0, NULL, 1244800, 'TEVES', 'Erwin Budias', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', NULL, '2023-03-25 17:08:42', '2023-04-28 13:05:39'),
	(29, 1, 'Seaoil Philippines Inc.', '005-054-970-000', '22F The Taipan Place, F. Ortigas Jr. Road, Ortigas Center Pasig City, Manila 1605', '00000029', '2023-03-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PICK UP', 'Seaoil Sta. Cruz', NULL, NULL, 3904600, NULL, NULL, 0, NULL, 3904600, 'TEVES', '-', '-', '-', '-', '-', NULL, NULL, NULL, NULL, 'Paid', NULL, '2023-03-29 14:26:54', '2023-04-28 13:05:31'),
	(30, 3, NULL, NULL, NULL, '00000030', '2023-04-25', 'ATL # 1936', NULL, NULL, '72368', NULL, NULL, NULL, NULL, 'Pick Up', 'IOC INSULAR', NULL, NULL, 1225800, NULL, 1.12, 1094464.29, 1, 1214855.36, 'TEVES', 'Erwin Budias', '-', NULL, 'MADRID', 'MADRID SDS', NULL, NULL, NULL, NULL, 'Paid', NULL, '2023-04-28 13:08:15', '2023-04-28 13:08:41'),
	(31, 1, NULL, NULL, NULL, '00000031', '2023-04-28', 'SPI 155806', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pick Up', 'Seaoil Sta. Cruz', NULL, NULL, 1467000, NULL, 0, 0, 0, 1467000, 'TEVES', 'Ronald Cabatuan', '-', NULL, 'DAVAO GULF MARINE SERVICES INC', 'TEFASCO PANACAN DAVAO CITY', NULL, NULL, NULL, NULL, 'Paid', NULL, '2023-04-28 13:11:21', '2023-04-28 13:14:46'),
	(32, 1, NULL, NULL, NULL, '00000032', '2023-05-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PICK UP', 'Seaoil Sta. Cruz', NULL, NULL, 5234400, NULL, NULL, 0, NULL, 5234400, 'TEVES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'GT', '2023-05-02 14:18:18', '2023-05-07 22:07:21'),
	(34, 1, NULL, NULL, NULL, '00000033', '2023-05-09', 'SPI 158379', 'CR # SPI 00135647  /  SPI 00136815', NULL, NULL, NULL, NULL, NULL, NULL, 'PICK UP', 'Seaoil Sta. Cruz', NULL, NULL, 4749400, NULL, NULL, 0, NULL, 4749400, 'TEVES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Teves', '2023-05-18 09:49:37', '2023-05-18 09:52:13'),
	(36, 1, NULL, NULL, NULL, '00000035', '2023-05-24', 'SO 162492', 'SPI00140444 / SPI001400442', 'SPI00192757 / 00191456', '327525/329138/327509', NULL, NULL, NULL, NULL, 'PICK UP', 'Seaoil Sta. Cruz', NULL, NULL, 3845600, NULL, NULL, 0, NULL, 3845600, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Revised Purchased Order from P.O # 35', 'Paid', 'Teves', '2023-05-24 14:48:10', '2023-05-31 14:30:30'),
	(38, 3, NULL, NULL, NULL, '00000037', '2023-05-24', 'ATL # 2657', NULL, NULL, '73209', NULL, NULL, NULL, NULL, 'PICK UP', 'IOC-SASA, DAVAO', NULL, NULL, 1094800, NULL, 1.12, 977500, 1, 1085025, 'TEVES', 'Erwin Budias', 'MAC 4767', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Teves', '2023-05-24 16:26:24', '2023-05-24 16:26:55'),
	(40, 1, NULL, NULL, NULL, '00000039', '2023-05-31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PICK UP', 'Seaoil Sta. Cruz', NULL, NULL, 2494800, NULL, 0, 0, 0, 2494800, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'GT', '2023-05-31 14:32:35', '2023-06-04 18:11:05'),
	(41, 5, NULL, NULL, NULL, '00000041', '2023-06-01', '-', '-', '-', '-', NULL, NULL, NULL, NULL, 'Delivered', 'NONE', NULL, NULL, 68640, NULL, NULL, 0, NULL, 68640, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'GT', '2023-06-01 16:41:57', '2023-06-04 18:09:02'),
	(45, 1, NULL, NULL, NULL, '00000042', '2023-06-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PICK UP', 'Jetti - Tagoloan', NULL, NULL, 1180800, NULL, 1.12, 1054285.71, 1, 1170257.14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Paid', 'Teves', '2023-06-16 14:52:05', '2023-06-17 15:29:09');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
