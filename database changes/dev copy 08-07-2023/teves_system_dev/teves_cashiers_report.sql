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

-- Dumping structure for table teves_system_dev.teves_cashiers_report
CREATE TABLE IF NOT EXISTS `teves_cashiers_report` (
  `cashiers_report_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_idx` int(11) NOT NULL DEFAULT 0 COMMENT 'Cashier''s ID from user_tb',
  `cashiers_name` varchar(255) NOT NULL DEFAULT '0',
  `teves_branch` varchar(255) NOT NULL DEFAULT '' COMMENT 'GT/TEVES this will serves as header',
  `forecourt_attendant` varchar(150) DEFAULT NULL COMMENT 'Forecourt Attendant',
  `report_date` varchar(10) NOT NULL DEFAULT '',
  `shift` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`cashiers_report_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='This will serve as primary info for cashiers report';

-- Dumping data for table teves_system_dev.teves_cashiers_report: ~0 rows (approximately)
DELETE FROM `teves_cashiers_report`;
INSERT INTO `teves_cashiers_report` (`cashiers_report_id`, `user_idx`, `cashiers_name`, `teves_branch`, `forecourt_attendant`, `report_date`, `shift`, `created_at`, `updated_at`) VALUES
	(1, 1249, '0', 'Teves', 'DAnny', '2023-07-29', '8-5pm', '2023-07-29 20:37:59', '2023-07-29 20:37:59'),
	(2, 1249, 'New Cashier\'s Name', 'GT', 'New Employee\'s On Duty - Updated', '2023-07-30', 'Shift A-Z', '2023-07-30 13:16:56', '2023-07-30 15:38:48');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
