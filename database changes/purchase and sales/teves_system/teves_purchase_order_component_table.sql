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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system.teves_purchase_order_component_table: ~0 rows (approximately)
INSERT INTO `teves_purchase_order_component_table` (`purchase_order_component_id`, `purchase_order_idx`, `product_idx`, `client_idx`, `product_price`, `order_quantity`, `order_total_amount`, `created_at`, `updated_at`) VALUES
	(1, 2, 4, 0, 67.66, 1, 67.66, '2023-01-29 15:06:01', '2023-01-29 15:06:01'),
	(2, 2, 1, 0, 3, 23, 69, '2023-01-29 16:06:24', '2023-01-29 16:06:24'),
	(3, 2, 11, 0, 4, 23, 92, '2023-01-29 16:06:24', '2023-01-29 16:06:24'),
	(4, 2, 1, 0, 5, 23, 115, '2023-01-29 16:06:24', '2023-01-29 16:06:24'),
	(5, 2, 11, 0, 3, 2, 6, '2023-01-29 16:06:24', '2023-01-29 16:06:24');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
