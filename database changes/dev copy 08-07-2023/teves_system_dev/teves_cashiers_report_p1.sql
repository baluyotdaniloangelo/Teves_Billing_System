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

-- Dumping structure for table teves_system_dev.teves_cashiers_report_p1
CREATE TABLE IF NOT EXISTS `teves_cashiers_report_p1` (
  `cashiers_report_p1_id` int(11) NOT NULL AUTO_INCREMENT,
  `cashiers_report_id` int(11) DEFAULT NULL,
  `user_idx` int(11) DEFAULT NULL,
  `product_idx` int(11) DEFAULT NULL,
  `beginning_reading` double DEFAULT NULL,
  `closing_reading` double DEFAULT NULL,
  `calibration` double DEFAULT NULL,
  `order_quantity` double DEFAULT NULL,
  `product_price` double DEFAULT NULL,
  `order_total_amount` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`cashiers_report_p1_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='\r\n';

-- Dumping data for table teves_system_dev.teves_cashiers_report_p1: ~3 rows (approximately)
DELETE FROM `teves_cashiers_report_p1`;
INSERT INTO `teves_cashiers_report_p1` (`cashiers_report_p1_id`, `cashiers_report_id`, `user_idx`, `product_idx`, `beginning_reading`, `closing_reading`, `calibration`, `order_quantity`, `product_price`, `order_total_amount`, `created_at`, `updated_at`) VALUES
	(2, 1, 1249, 11, 100, 200, 1, 99, 40, 3960, '2023-07-30 16:17:35', '2023-07-30 16:17:35'),
	(3, 2, 1249, 12, 1, 6, 1, 4, 45.9, 183.6, '2023-08-02 20:24:34', '2023-08-02 20:24:34'),
	(4, 1, 1249, 12, 45, 90, 5, 40, 70, 2800, '2023-08-02 22:25:28', '2023-08-02 22:25:28');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
