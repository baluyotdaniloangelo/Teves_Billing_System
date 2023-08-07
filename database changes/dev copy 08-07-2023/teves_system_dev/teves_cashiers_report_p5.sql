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

-- Dumping structure for table teves_system_dev.teves_cashiers_report_p5
CREATE TABLE IF NOT EXISTS `teves_cashiers_report_p5` (
  `cashiers_report_p5_id` int(11) NOT NULL AUTO_INCREMENT,
  `cashiers_report_id` int(11) DEFAULT NULL,
  `user_idx` int(11) DEFAULT NULL,
  `one_thousand_deno` double DEFAULT NULL,
  `five_hundred_deno` double DEFAULT NULL,
  `two_hundred_deno` double DEFAULT NULL,
  `one_hundred_deno` double DEFAULT NULL,
  `fifty_deno` double DEFAULT NULL,
  `twenty_deno` double DEFAULT NULL,
  `ten_deno` double DEFAULT NULL,
  `five_deno` double DEFAULT NULL,
  `one_deno` double DEFAULT NULL,
  `twenty_five_cent_deno` double DEFAULT NULL,
  `cash_drop` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`cashiers_report_p5_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system_dev.teves_cashiers_report_p5: ~2 rows (approximately)
DELETE FROM `teves_cashiers_report_p5`;
INSERT INTO `teves_cashiers_report_p5` (`cashiers_report_p5_id`, `cashiers_report_id`, `user_idx`, `one_thousand_deno`, `five_hundred_deno`, `two_hundred_deno`, `one_hundred_deno`, `fifty_deno`, `twenty_deno`, `ten_deno`, `five_deno`, `one_deno`, `twenty_five_cent_deno`, `cash_drop`, `created_at`, `updated_at`) VALUES
	(1, 1, 1249, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2023-07-29 20:37:59', '2023-07-29 20:37:59'),
	(2, 2, 1249, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2023-07-30 13:16:56', '2023-07-30 13:16:56');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
