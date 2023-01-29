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
  `purchase_order_hauler` text DEFAULT NULL,
  `purchase_order_date_of_pickup` text DEFAULT NULL,
  `purchase_order_date_of_arrival` text DEFAULT NULL,
  `purchase_order_gross_amount` double DEFAULT NULL,
  `purchase_order_total_liters` double DEFAULT NULL,
  `purchase_order_net_percentage` double DEFAULT NULL,
  `purchase_order_net_amount` double DEFAULT NULL,
  `purchase_order_less_percentage` double DEFAULT NULL,
  `purchase_order_total_payable` double DEFAULT NULL,
  `purchase_driver` text DEFAULT NULL,
  `purchase_lorry_plate_no` text DEFAULT NULL,
  `purchase_loading_terminal` text DEFAULT NULL,
  `purchase_terminal_address` text DEFAULT NULL,
  `purchase_destination` text DEFAULT NULL,
  `purchase_destination_address` text DEFAULT NULL,
  `purchase_date_of_departure` text DEFAULT NULL,
  `purchase_date_of_arrival` text DEFAULT NULL,
  `purchase_order_instructions` text DEFAULT NULL,
  `purchase_order_note` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`purchase_order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system.teves_purchase_order_table: ~0 rows (approximately)
INSERT INTO `teves_purchase_order_table` (`purchase_order_id`, `purchase_order_client_idx`, `purchase_supplier_name`, `purchase_supplier_tin`, `purchase_supplier_address`, `purchase_order_control_number`, `purchase_order_date`, `purchase_order_sales_order_number`, `purchase_order_collection_receipt_no`, `purchase_order_official_receipt_no`, `purchase_order_delivery_receipt_no`, `purchase_order_bank`, `purchase_order_date_of_payment`, `purchase_order_reference_no`, `purchase_order_payment_amount`, `purchase_order_delivery_method`, `purchase_order_hauler`, `purchase_order_date_of_pickup`, `purchase_order_date_of_arrival`, `purchase_order_gross_amount`, `purchase_order_total_liters`, `purchase_order_net_percentage`, `purchase_order_net_amount`, `purchase_order_less_percentage`, `purchase_order_total_payable`, `purchase_driver`, `purchase_lorry_plate_no`, `purchase_loading_terminal`, `purchase_terminal_address`, `purchase_destination`, `purchase_destination_address`, `purchase_date_of_departure`, `purchase_date_of_arrival`, `purchase_order_instructions`, `purchase_order_note`, `created_at`, `updated_at`) VALUES
	(2, NULL, 'Danny\'s Shop', '111-111-1111', 'Any Address', '00000002', '2023-01-29', '44533', '3422221', '466644', 'oo', 'o', '2023-01-29', '90988783', '1000.5', 'PICK UP', '0005423', '2023-01-29', '2023-01-29', 349.66, NULL, 1.12, 312.2, 1, 346.54, 'Driver 1', '2023-01-29', 'Terminal 1', '1 Biringan', 'Terminal 2', 'Terminal 3', '2023-01-01', '2023-02-01', 'Instructions 1', 'Notes 1', '2023-01-29 15:06:01', '2023-01-29 17:53:42');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
