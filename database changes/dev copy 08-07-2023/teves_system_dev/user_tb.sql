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

-- Dumping structure for table teves_system_dev.user_tb
CREATE TABLE IF NOT EXISTS `user_tb` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` text NOT NULL,
  `user_real_name` text NOT NULL,
  `user_job_title` text NOT NULL,
  `user_password` text NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `created_at` text NOT NULL,
  `updated_at` text NOT NULL,
  PRIMARY KEY (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1250 DEFAULT CHARSET=latin1;

-- Dumping data for table teves_system_dev.user_tb: ~5 rows (approximately)
DELETE FROM `user_tb`;
INSERT INTO `user_tb` (`user_id`, `user_name`, `user_real_name`, `user_job_title`, `user_password`, `user_type`, `created_at`, `updated_at`) VALUES
	(3, 'glezateves', 'GLEZA F. TEVES', 'Proprietress', '$2a$12$RwjIKFZDcZ1OkST4oFoqfulipqIz62D5Og6M2kTNi2R9RUEhcWZbq', 'Admin', '2022-11-23 00:00:00', '2023-06-07 23:29:10'),
	(1244, 'EVA DARLEEN', 'EVA DARLEEN GOZON', 'Accounting', '$2y$10$eRcGDcDYNEGZXXk4O2x2ZOkuEKv7hutJ5MED6w2yg2XZBHSsBXspS', 'User', '2022-12-07 21:37:45', '2023-06-07 23:20:54'),
	(1247, 'LENY', 'LENY TEVES', 'Office Staff', '$2y$10$tt6A6tG8VrsNLNsGiHmoC.D4.8CnvbftjHFunDJgZQc0jHdypP..C', 'User', '2023-04-04 11:14:14', '2023-06-07 23:21:06'),
	(1248, 'RUGEN', 'RUGEN TEVES', 'Office Staff', '$2y$10$8B5.0QSzBl0WYqaTtHdVhukrgZsMPecBa/CLQbB.dYAUsjuiaYSlC', 'User', '2023-05-08 17:59:07', '2023-07-22 11:44:14'),
	(1249, 'admin', 'danny@dev', 'admin', '$2a$12$vxOXGv/kIBMH1I2AMhJ7LeN0HG/l7eW2UNTQ/b2nDO11BZVtWtV6a', 'Admin', '', '');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
