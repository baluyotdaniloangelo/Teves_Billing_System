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

-- Dumping structure for table teves_system_dev.teves_supplier_table
CREATE TABLE IF NOT EXISTS `teves_supplier_table` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(255) NOT NULL DEFAULT '',
  `supplier_address` varchar(255) DEFAULT NULL,
  `supplier_tin` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`supplier_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Dumping data for table teves_system_dev.teves_supplier_table: ~5 rows (approximately)
DELETE FROM `teves_supplier_table`;
INSERT INTO `teves_supplier_table` (`supplier_id`, `supplier_name`, `supplier_address`, `supplier_tin`, `created_at`, `updated_at`) VALUES
	(1, 'Seaoil Philippines Inc.', '22F The Taipan Place, F. Ortigas Jr. Road, Ortigas Center Pasig City, Manila 1605', '005-054-970-000', NULL, NULL),
	(2, 'Filnix Petroleum Products Distribution', 'Purok Matinatapon, San Jose (Bo. 5), Koronadal City, South Cotabato', '435-468-973-000', NULL, NULL),
	(3, 'VNT TRADE & SUPPLY INC', 'Unit 28 C. Raymundo Ave., Dona Juana Townhomes, Maybunga, Pasig City', '247-546-158-000', NULL, NULL),
	(4, 'FILOIL LOGISTICS CORPORATION', '5F 11TH CORPORATE CENTER, 11TH AVENUE, BGC, TAGUIG', '009-193-030-00000', '2023-05-08 18:01:05', '2023-05-08 18:01:05'),
	(5, 'ANONYMOUS', 'MADRID , SURIGAO DEL SUR', '00 00 00 00', '2023-06-01 16:41:16', '2023-06-01 16:41:16');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
