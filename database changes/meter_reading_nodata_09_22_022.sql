-- MySQL dump 10.15  Distrib 10.0.38-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: meter_reading
-- ------------------------------------------------------
-- Server version	10.0.38-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ac_status`
--

DROP TABLE IF EXISTS `ac_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ac_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(100) NOT NULL,
  `date_time` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `live_meter_data`
--

DROP TABLE IF EXISTS `live_meter_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `live_meter_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(30) NOT NULL DEFAULT 'Home',
  `meter_id` varchar(30) NOT NULL,
  `datetime` datetime NOT NULL,
  `vrms_a` double NOT NULL DEFAULT '0',
  `vrms_b` double NOT NULL DEFAULT '0',
  `vrms_c` double NOT NULL,
  `irms_a` double NOT NULL,
  `irms_b` double NOT NULL,
  `irms_c` double NOT NULL,
  `freq` double NOT NULL,
  `pf` double NOT NULL,
  `watt` double NOT NULL,
  `va` double NOT NULL,
  `var` double NOT NULL,
  `wh_del` double NOT NULL,
  `wh_rec` double NOT NULL,
  `wh_net` double NOT NULL,
  `wh_total` double NOT NULL,
  `varh_neg` double NOT NULL,
  `varh_pos` double NOT NULL,
  `varh_net` double NOT NULL,
  `varh_total` double NOT NULL,
  `vah_total` double NOT NULL,
  `max_rec_kw_dmd` double NOT NULL,
  `max_rec_kw_dmd_time` datetime DEFAULT NULL,
  `max_del_kw_dmd` double NOT NULL,
  `max_del_kw_dmd_time` datetime DEFAULT NULL,
  `max_pos_kvar_dmd` double NOT NULL,
  `max_pos_kvar_dmd_time` datetime DEFAULT NULL,
  `max_neg_kvar_dmd` double NOT NULL,
  `max_neg_kvar_dmd_time` datetime DEFAULT NULL,
  `v_ph_angle_a` double NOT NULL COMMENT 'additional for sfelapco inc (JULY 23, 2018)',
  `v_ph_angle_b` double NOT NULL COMMENT 'additional for sfelapco inc (JULY 23, 2018)',
  `v_ph_angle_c` double NOT NULL COMMENT 'additional for sfelapco inc (JULY 23, 2018)',
  `i_ph_angle_a` double NOT NULL COMMENT 'additional for sfelapco inc (JULY 23, 2018)',
  `i_ph_angle_b` double NOT NULL COMMENT 'additional for sfelapco inc (JULY 23, 2018)',
  `i_ph_angle_c` double NOT NULL COMMENT 'additional for sfelapco inc (JULY 23, 2018)',
  `mac_addr` text NOT NULL,
  `soft_rev` text NOT NULL,
  `relay_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34554 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `load_profile`
--

DROP TABLE IF EXISTS `load_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `load_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meter_id` text NOT NULL,
  `datetime` text,
  `event_id` text NOT NULL,
  `ch_1` float NOT NULL COMMENT '1.5.0_kW',
  `ch_2` double NOT NULL COMMENT '1-1:1.30.2_kWh',
  `ch_3` double NOT NULL COMMENT '1-1:3.30.2_kvarh',
  `ch_4` double NOT NULL COMMENT '2.5.0_kW',
  `ch_5` double NOT NULL COMMENT '1-1:2.30.2_kWh',
  `ch_6` double NOT NULL COMMENT '1-1:4.30.2_kvarh',
  `ch_7` double NOT NULL DEFAULT '0',
  `ch_8` double NOT NULL DEFAULT '0',
  `time_import` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `meter_data`
--

DROP TABLE IF EXISTS `meter_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meter_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(30) NOT NULL DEFAULT 'Home',
  `meter_id` varchar(30) NOT NULL,
  `datetime` datetime NOT NULL,
  `vrms_a` double NOT NULL DEFAULT '0',
  `vrms_b` double NOT NULL DEFAULT '0',
  `vrms_c` double NOT NULL,
  `irms_a` double NOT NULL,
  `irms_b` double NOT NULL,
  `irms_c` double NOT NULL,
  `freq` double NOT NULL,
  `pf` double NOT NULL,
  `watt` double NOT NULL,
  `va` double NOT NULL,
  `var` double NOT NULL,
  `wh_del` double NOT NULL,
  `wh_rec` double NOT NULL,
  `wh_net` double NOT NULL,
  `wh_total` double NOT NULL,
  `varh_neg` double NOT NULL,
  `varh_pos` double NOT NULL,
  `varh_net` double NOT NULL,
  `varh_total` double NOT NULL,
  `vah_total` double NOT NULL,
  `max_rec_kw_dmd` double NOT NULL,
  `max_rec_kw_dmd_time` datetime DEFAULT NULL,
  `max_del_kw_dmd` double NOT NULL,
  `max_del_kw_dmd_time` datetime DEFAULT NULL,
  `max_pos_kvar_dmd` double NOT NULL,
  `max_pos_kvar_dmd_time` datetime DEFAULT NULL,
  `max_neg_kvar_dmd` double NOT NULL,
  `max_neg_kvar_dmd_time` datetime DEFAULT NULL,
  `v_ph_angle_a` double NOT NULL,
  `v_ph_angle_b` double NOT NULL,
  `v_ph_angle_c` double NOT NULL,
  `i_ph_angle_a` double NOT NULL,
  `i_ph_angle_b` double NOT NULL,
  `i_ph_angle_c` double NOT NULL,
  `mac_addr` text NOT NULL,
  `soft_rev` text NOT NULL,
  `relay_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`meter_id`,`datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=880776151 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `meter_data_daily`
--

DROP TABLE IF EXISTS `meter_data_daily`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meter_data_daily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meter_id` text NOT NULL,
  `location` text NOT NULL,
  `yearmonth` text NOT NULL,
  `01` double NOT NULL,
  `02` double NOT NULL,
  `03` double NOT NULL,
  `04` double NOT NULL,
  `05` double NOT NULL,
  `06` double NOT NULL,
  `07` double NOT NULL,
  `08` double NOT NULL,
  `09` double NOT NULL,
  `10` double NOT NULL,
  `11` double NOT NULL,
  `12` double NOT NULL,
  `13` double NOT NULL,
  `14` double NOT NULL,
  `15` double NOT NULL,
  `16` double NOT NULL,
  `17` double NOT NULL,
  `18` double NOT NULL,
  `19` double NOT NULL,
  `20` double NOT NULL,
  `21` double NOT NULL,
  `22` double NOT NULL,
  `23` double NOT NULL,
  `24` double NOT NULL,
  `25` double NOT NULL,
  `26` double NOT NULL,
  `27` double NOT NULL,
  `28` double NOT NULL,
  `29` double NOT NULL,
  `30` double NOT NULL,
  `31` double NOT NULL,
  `last_column_used` text NOT NULL,
  `cut_off` double NOT NULL,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_log_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1394 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `meter_data_hourly`
--

DROP TABLE IF EXISTS `meter_data_hourly`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meter_data_hourly` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meter_id` text NOT NULL,
  `location` text NOT NULL,
  `year_month_day` text NOT NULL,
  `00` double NOT NULL COMMENT '12am',
  `01` double NOT NULL COMMENT '1am',
  `02` double NOT NULL COMMENT '2am',
  `03` double NOT NULL COMMENT '3am',
  `04` double NOT NULL COMMENT '4am',
  `05` double NOT NULL COMMENT '5am',
  `06` double NOT NULL COMMENT '6am',
  `07` double NOT NULL COMMENT '7am',
  `08` double NOT NULL COMMENT '8am',
  `09` double NOT NULL COMMENT '9am',
  `10` double NOT NULL COMMENT '10am',
  `11` double NOT NULL COMMENT '11am',
  `12` double NOT NULL COMMENT '12pm',
  `13` double NOT NULL COMMENT '1pm',
  `14` double NOT NULL COMMENT '2pm',
  `15` double NOT NULL COMMENT '3pm',
  `16` double NOT NULL COMMENT '4pm',
  `17` double NOT NULL COMMENT '5pm',
  `18` double NOT NULL COMMENT '6pm',
  `19` double NOT NULL COMMENT '7pm',
  `20` double NOT NULL COMMENT '8pm',
  `21` double NOT NULL COMMENT '9pm',
  `22` double NOT NULL COMMENT '10pm',
  `23` double NOT NULL COMMENT '11pm',
  `last_column_used` text NOT NULL,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31110 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `meter_data_interval_report`
--

DROP TABLE IF EXISTS `meter_data_interval_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meter_data_interval_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(30) NOT NULL DEFAULT 'Home',
  `meter_id` varchar(30) NOT NULL,
  `datetime_last_log` datetime NOT NULL,
  `datetime_next_log` datetime NOT NULL,
  `log_duration` varchar(200) NOT NULL,
  `log_duration_minute` double NOT NULL,
  `interval_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `meter_data_sap`
--

DROP TABLE IF EXISTS `meter_data_sap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meter_data_sap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meter_id` text NOT NULL,
  `location` text NOT NULL,
  `yearmonth` text NOT NULL,
  `01` double NOT NULL,
  `02` double NOT NULL,
  `03` double NOT NULL,
  `04` double NOT NULL,
  `05` double NOT NULL,
  `06` double NOT NULL,
  `07` double NOT NULL,
  `08` double NOT NULL,
  `09` double NOT NULL,
  `10` double NOT NULL,
  `11` double NOT NULL,
  `12` double NOT NULL,
  `13` double NOT NULL,
  `14` double NOT NULL,
  `15` double NOT NULL,
  `16` double NOT NULL,
  `17` double NOT NULL,
  `18` double NOT NULL,
  `19` double NOT NULL,
  `20` double NOT NULL,
  `21` double NOT NULL,
  `22` double NOT NULL,
  `23` double NOT NULL,
  `24` double NOT NULL,
  `25` double NOT NULL,
  `26` double NOT NULL,
  `27` double NOT NULL,
  `28` double NOT NULL,
  `29` double NOT NULL,
  `30` double NOT NULL,
  `31` double NOT NULL,
  `last_column_used` text NOT NULL,
  `cut_off` double NOT NULL,
  `cut_off_datetime` datetime NOT NULL,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_log_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='FOR SAP REPORT';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `meter_details`
--

DROP TABLE IF EXISTS `meter_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meter_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meter_name` varchar(70) NOT NULL DEFAULT '',
  `meter_name_addressable` int(11) NOT NULL DEFAULT '1',
  `meter_load_profile` varchar(50) NOT NULL DEFAULT 'NO',
  `meter_default_name` text NOT NULL,
  `physical_location` text NOT NULL,
  `meter_site_id` int(11) NOT NULL,
  `rtu_sn_number_id` int(11) NOT NULL,
  `rtu_sn_number` text NOT NULL,
  `meter_model` text NOT NULL,
  `meter_model_id` int(11) NOT NULL,
  `meter_config_file` text NOT NULL,
  `meter_type` text NOT NULL,
  `meter_role` varchar(100) NOT NULL DEFAULT 'Client Meter',
  `created_by` text NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `modified_by` text NOT NULL,
  `date_modified` datetime NOT NULL,
  `added_by` int(11) NOT NULL,
  `soft_rev` text NOT NULL,
  `meter_list_src` text NOT NULL,
  `last_log_update` datetime NOT NULL,
  `last_wh_total` double NOT NULL,
  `company_no` text NOT NULL COMMENT 'COMPANY_CODE',
  `meter_site_name` varchar(200) NOT NULL COMMENT 'Site Code to use on gateway or grouping of Mall or CPG',
  `business_entity` varchar(200) NOT NULL COMMENT 'use to group building pweding sa isang entity iba ibang stablishment like 3ecom 2ecom nasa isang entity sila',
  `building` text NOT NULL COMMENT 'BUILDING use to group meter per building',
  `building_description` text NOT NULL,
  `land` text NOT NULL COMMENT 'LAND',
  `land_description` text NOT NULL,
  `rental_object` text NOT NULL COMMENT 'RENTAL_OBJECT_NO',
  `rental_object_name` text NOT NULL COMMENT 'RENTAL_OBJECT_NAME',
  `usage_type` text NOT NULL COMMENT 'USAGE_TYPE',
  `ro_valid_from` text NOT NULL COMMENT 'RO_VALID_FROM',
  `ro_valid_to` text NOT NULL COMMENT 'RO_VALID_TO',
  `contract_number` text NOT NULL COMMENT 'CONTRACT_NUMBER',
  `customer_name` text NOT NULL COMMENT 'CONTRACT_NAME/Tenant Name',
  `meter_characteristic` varchar(100) NOT NULL DEFAULT 'NORMALPOWER' COMMENT 'METER_CHARACTERISTIC',
  `measuring_point` varchar(100) NOT NULL COMMENT 'MEASPONT',
  `measuring_point_description` text NOT NULL COMMENT 'METER_DESCRIPTION/Meter Serial Number',
  `measurement_sequence` text NOT NULL COMMENT 'MEASUREMENT_SEQUENCE',
  `measurement_separator` text NOT NULL COMMENT 'MEASUREMENT_SEPARATOR',
  `meter_multiplier` double NOT NULL DEFAULT '1' COMMENT 'MEASUREMENT_MULTIPLIER',
  `meter_reading_date` text COMMENT 'METER_READING_DATE',
  `meter_reading` double DEFAULT NULL COMMENT 'METER_READING',
  `meter_status` text NOT NULL COMMENT 'METER_STATUS',
  `participation_group` text NOT NULL COMMENT 'PARTICIPATION GROUP',
  `creation_date` text NOT NULL COMMENT 'CREATION DATE',
  `creation_by` text NOT NULL COMMENT 'CREATION_TIME',
  `last_change_on` text NOT NULL COMMENT 'LAST_CHANGE_ON',
  `last_change_by` text NOT NULL COMMENT 'LAST_CHANGE_BY',
  PRIMARY KEY (`id`),
  KEY `meter_name_characteristic_site` (`id`,`meter_name`,`meter_characteristic`,`meter_site_name`),
  KEY `meter_name_characteristic_site_measuring_point` (`meter_name`,`meter_characteristic`,`meter_site_name`,`measuring_point`)
) ENGINE=InnoDB AUTO_INCREMENT=233599 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `meter_import_logs`
--

DROP TABLE IF EXISTS `meter_import_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meter_import_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime_import` datetime DEFAULT NULL COMMENT 'date time',
  `file_name` text COMMENT 'file name',
  `import_path` text COMMENT 'Path Directory',
  `moved_path` text COMMENT 'Moved to Directory',
  `moved_file_name` text,
  `backup_file` blob COMMENT 'FILE',
  `datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3145 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `meter_info_view`
--

DROP TABLE IF EXISTS `meter_info_view`;
/*!50001 DROP VIEW IF EXISTS `meter_info_view`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `meter_info_view` (
  `location` tinyint NOT NULL,
  `meter_id` tinyint NOT NULL,
  `meter_load_profile` tinyint NOT NULL,
  `customer_name` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `meter_model_config`
--

DROP TABLE IF EXISTS `meter_model_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meter_model_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meter_model` text NOT NULL,
  `config_file` text NOT NULL,
  `created_by` varchar(300) NOT NULL,
  `date_created` varchar(200) NOT NULL,
  `modified_by` varchar(200) NOT NULL,
  `date_modified` timestamp NULL DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_update_meter_model_config` AFTER UPDATE ON `meter_model_config`
 FOR EACH ROW update meter_details
       set meter_model = NEW.meter_model, meter_config_file = NEW.config_file
       where meter_model_id = NEW.id */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `meter_rtu`
--

DROP TABLE IF EXISTS `meter_rtu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meter_rtu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rtu_sn_number` text NOT NULL,
  `rtu_physical_location` text NOT NULL,
  `connection_type` text NOT NULL,
  `phone_no_or_ip_address` text NOT NULL,
  `ip_netmask` text NOT NULL,
  `ip_gateway` text NOT NULL,
  `mac_addr` text NOT NULL,
  `rtu_server_ip` text NOT NULL,
  `gateway_description` text NOT NULL,
  `update_rtu` int(11) NOT NULL DEFAULT '0',
  `update_rtu_location` int(11) NOT NULL,
  `update_rtu_ssh` int(11) NOT NULL,
  `update_rtu_force_lp` int(11) NOT NULL,
  `rtu_site_id` int(11) NOT NULL,
  `idf_number` text,
  `switch_name` text,
  `idf_port` text,
  `rtu_site_name` varchar(200) NOT NULL,
  `created_by` varchar(300) NOT NULL,
  `date_created` varchar(200) NOT NULL,
  `modified_by` varchar(200) NOT NULL,
  `date_modified` timestamp NULL DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `last_log_update` datetime NOT NULL,
  `soft_rev` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1591 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `meter_site`
--

DROP TABLE IF EXISTS `meter_site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meter_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_code` text NOT NULL COMMENT 'SITE CODE TO Used on Gateway',
  `company_no` int(11) NOT NULL COMMENT 'COMPANY_CODE',
  `site_name` text NOT NULL COMMENT 'BUSINESS_ENTITY/Location/SITE ed SM SA LAZARO',
  `site_cut_off` text NOT NULL COMMENT 'METER_READING_CUTOFF',
  `device_ip_range` text,
  `ip_netmask` text,
  `ip_gateway` text,
  `access_list_src` text NOT NULL,
  `created_by` varchar(200) NOT NULL,
  `date_created` varchar(100) NOT NULL,
  `modified_by` varchar(200) NOT NULL,
  `modified_by_id` int(11) NOT NULL,
  `date_modified` varchar(100) NOT NULL,
  `last_log_update` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `service_charge_key` text COMMENT 'SERVICE_CHARGE_KEY',
  `participation_group` text COMMENT 'PARTICIPATION GROUP',
  `settlement_unit` text COMMENT 'SETTLEMENT_UNIT',
  `settlement_variant_text` text COMMENT 'SETTLEMENT VARIANT TEXT',
  `settlement_valid_from` text COMMENT 'SETTLEMENT VALID FROM',
  `settlement_valid_to` text COMMENT 'SETTLEMENT VALID TO',
  `created_on` text COMMENT 'CREATED_ON',
  `created_at` text COMMENT 'CREATED_AT',
  `last_edited_on` text COMMENT 'LAST_EDITED_ON',
  `last_edited_at` text COMMENT 'LAST_EDITED_AT',
  `business_entity` text NOT NULL COMMENT 'BUSINESS_ENTITY GROUP kng wala same lang ng SITE CODE. Gagamitin sa tulad ng SMPH business entity na madaming sakop like 3ECOM,2ecom etc.',
  `building_type` text NOT NULL COMMENT 'CPG/MALL',
  `sap_server` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `meter_site_user`
--

DROP TABLE IF EXISTS `meter_site_user`;
/*!50001 DROP VIEW IF EXISTS `meter_site_user`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `meter_site_user` (
  `id` tinyint NOT NULL,
  `site_code` tinyint NOT NULL,
  `company_no` tinyint NOT NULL,
  `settlement_valid_from` tinyint NOT NULL,
  `settlement_valid_to` tinyint NOT NULL,
  `site_name` tinyint NOT NULL,
  `site_cut_off` tinyint NOT NULL,
  `created_by` tinyint NOT NULL,
  `date_created` tinyint NOT NULL,
  `modified_by` tinyint NOT NULL,
  `date_modified` tinyint NOT NULL,
  `last_log_update` tinyint NOT NULL,
  `added_by` tinyint NOT NULL,
  `user_id` tinyint NOT NULL,
  `business_entity` tinyint NOT NULL,
  `access_list_src` tinyint NOT NULL,
  `user_site_access_expiration` tinyint NOT NULL,
  `meter_site_id` tinyint NOT NULL,
  `building_type` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `user_access_group`
--

DROP TABLE IF EXISTS `user_access_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_access_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` text NOT NULL COMMENT 'USER_ID from SAP',
  `user_name` text NOT NULL COMMENT 'USER_NAME',
  `user_expiration` date NOT NULL COMMENT 'USER_ID_VALID_TO',
  `meter_site_id` int(11) NOT NULL,
  `company_no` int(11) NOT NULL COMMENT 'COMPANY_NO/COMPANY',
  `business_entity` text NOT NULL COMMENT 'BUSINESS_ENTITY',
  `user_site_access_expiration` date NOT NULL COMMENT 'BUSINESS_ENTITY_VALID_TO',
  `user_type` text NOT NULL COMMENT 'FUNCTION',
  `position_expiration` date NOT NULL COMMENT 'FUNCTION_VALID_TO',
  `created_by` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL,
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `added_by` int(11) NOT NULL,
  `access_list_src` text NOT NULL,
  `site_code` text NOT NULL COMMENT 'Location Access',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6839 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_client_meter_access`
--

DROP TABLE IF EXISTS `user_client_meter_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_client_meter_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `meter_name` varchar(200) NOT NULL,
  `meter_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `user_meter_group_access`
--

DROP TABLE IF EXISTS `user_meter_group_access`;
/*!50001 DROP VIEW IF EXISTS `user_meter_group_access`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `user_meter_group_access` (
  `location` tinyint NOT NULL,
  `meter_id` tinyint NOT NULL,
  `meter_load_profile` tinyint NOT NULL,
  `customer_name` tinyint NOT NULL,
  `user_id` tinyint NOT NULL,
  `group_to_access` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `user_tb`
--

DROP TABLE IF EXISTS `user_tb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_tb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id_sap` text NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_real_name` text NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `user_expiration` date NOT NULL,
  `default_location` text NOT NULL COMMENT 'Use for Load Profile Login',
  `created_by` varchar(200) NOT NULL,
  `date_created` varchar(200) NOT NULL,
  `modified_by` varchar(200) NOT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_list_src` varchar(100) NOT NULL DEFAULT 'AMR',
  `user_access` varchar(100) NOT NULL DEFAULT 'Selected' COMMENT 'All/Selected if all user will have an access to all site, if Selected only assigned site to user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1637 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Final view structure for view `meter_info_view`
--

/*!50001 DROP TABLE IF EXISTS `meter_info_view`*/;
/*!50001 DROP VIEW IF EXISTS `meter_info_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `meter_info_view` AS select distinct `meter_details`.`meter_site_name` AS `location`,`meter_details`.`meter_name` AS `meter_id`,`meter_details`.`meter_load_profile` AS `meter_load_profile`,`meter_details`.`customer_name` AS `customer_name` from `meter_details` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `meter_site_user`
--

/*!50001 DROP TABLE IF EXISTS `meter_site_user`*/;
/*!50001 DROP VIEW IF EXISTS `meter_site_user`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `meter_site_user` AS select `user_access_group`.`id` AS `id`,`meter_site`.`site_code` AS `site_code`,`meter_site`.`company_no` AS `company_no`,`meter_site`.`settlement_valid_from` AS `settlement_valid_from`,`meter_site`.`settlement_valid_to` AS `settlement_valid_to`,`meter_site`.`site_name` AS `site_name`,`meter_site`.`site_cut_off` AS `site_cut_off`,`meter_site`.`created_by` AS `created_by`,`meter_site`.`date_created` AS `date_created`,`meter_site`.`modified_by` AS `modified_by`,`meter_site`.`date_modified` AS `date_modified`,`meter_site`.`last_log_update` AS `last_log_update`,`meter_site`.`added_by` AS `added_by`,`user_access_group`.`user_id` AS `user_id`,`meter_site`.`business_entity` AS `business_entity`,`user_access_group`.`access_list_src` AS `access_list_src`,`user_access_group`.`user_site_access_expiration` AS `user_site_access_expiration`,`meter_site`.`id` AS `meter_site_id`,`meter_site`.`building_type` AS `building_type` from (`meter_site` left join `user_access_group` on((`meter_site`.`site_code` = `user_access_group`.`site_code`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `user_meter_group_access`
--

/*!50001 DROP TABLE IF EXISTS `user_meter_group_access`*/;
/*!50001 DROP VIEW IF EXISTS `user_meter_group_access`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `user_meter_group_access` AS select `meter_details`.`meter_site_name` AS `location`,`meter_details`.`meter_name` AS `meter_id`,`meter_details`.`meter_load_profile` AS `meter_load_profile`,`meter_details`.`customer_name` AS `customer_name`,`user_access_group`.`user_id` AS `user_id`,`user_access_group`.`business_entity` AS `group_to_access` from (`user_access_group` join `meter_details` on((convert(`user_access_group`.`business_entity` using utf8) = convert(`meter_details`.`meter_site_name` using utf8)))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-09-22 15:35:22
