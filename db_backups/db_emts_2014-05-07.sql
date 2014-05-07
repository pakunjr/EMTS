# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.17)
# Database: db_emts
# Generation Time: 2014-05-07 08:47:23 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table tbl_accounts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_accounts`;

CREATE TABLE `tbl_accounts` (
  `account_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL DEFAULT '',
  `password` varchar(100) DEFAULT NULL,
  `password_salt` varchar(100) DEFAULT NULL,
  `owner_id` bigint(20) unsigned DEFAULT NULL,
  `access_level` int(11) DEFAULT NULL,
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `username` (`username`),
  KEY `account_owner_idx` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `tbl_accounts` WRITE;
/*!40000 ALTER TABLE `tbl_accounts` DISABLE KEYS */;

INSERT INTO `tbl_accounts` (`account_id`, `username`, `password`, `password_salt`, `owner_id`, `access_level`)
VALUES
	(1,'admin','4f064cef27d450e827cce6a57535ea4509b2d420b26b4ae9016cc9030345d7bd','alskdfkl',1,1),
	(2,'supervisor','40e918154a955918ec76e6b8b7b1ded8a8d3a06f6e4df2b862fe59794473f1f9','9774065b429301e7195b22fbc00736ebbddf5867b92b8f124b6c2912a69aa44f',1,2),
	(3,'encoder','40e918154a955918ec76e6b8b7b1ded8a8d3a06f6e4df2b862fe59794473f1f9','9774065b429301e7195b22fbc00736ebbddf5867b92b8f124b6c2912a69aa44f',1,3),
	(4,'viewer','40e918154a955918ec76e6b8b7b1ded8a8d3a06f6e4df2b862fe59794473f1f9','9774065b429301e7195b22fbc00736ebbddf5867b92b8f124b6c2912a69aa44f',1,4);

/*!40000 ALTER TABLE `tbl_accounts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_departments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_departments`;

CREATE TABLE `tbl_departments` (
  `department_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `department_name_short` tinytext,
  `department_name` text,
  `department_head_id` bigint(20) unsigned DEFAULT NULL,
  `department_description` text,
  PRIMARY KEY (`department_id`),
  KEY `department_head_idx` (`department_head_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `tbl_departments` WRITE;
/*!40000 ALTER TABLE `tbl_departments` DISABLE KEYS */;

INSERT INTO `tbl_departments` (`department_id`, `department_name_short`, `department_name`, `department_head_id`, `department_description`)
VALUES
	(1,'ITS','IT Services',NULL,NULL),
	(2,'CCSE','College of Computer Studies and Engineering',NULL,NULL),
	(3,'CAS','College of Arts and Sciences',NULL,NULL),
	(4,'CON','College of Nursing',NULL,NULL),
	(5,'CMLS','College of Medical Laboratory Science',NULL,NULL),
	(6,'CMA','College of Management and Accountancy',NULL,NULL);

/*!40000 ALTER TABLE `tbl_departments` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_employees
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_employees`;

CREATE TABLE `tbl_employees` (
  `employee_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_no` varchar(45) NOT NULL DEFAULT '',
  `person_id` bigint(20) unsigned DEFAULT NULL,
  `occupation` text,
  `designated_office_id` bigint(20) unsigned DEFAULT NULL,
  `designated_department_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `tbl_employees` WRITE;
/*!40000 ALTER TABLE `tbl_employees` DISABLE KEYS */;

INSERT INTO `tbl_employees` (`employee_id`, `employee_no`, `person_id`, `occupation`, `designated_office_id`, `designated_department_id`)
VALUES
	(1,'1553',1,'Web Developer',1,1);

/*!40000 ALTER TABLE `tbl_employees` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_items`;

CREATE TABLE `tbl_items` (
  `item_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_serial_no` varchar(80) DEFAULT NULL,
  `item_model_no` varchar(80) DEFAULT NULL,
  `item_name` text,
  `item_type` text,
  `item_description` text,
  `date_of_purchase` date DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table tbl_offices
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_offices`;

CREATE TABLE `tbl_offices` (
  `office_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `office_name` text,
  `office_head_id` bigint(20) unsigned DEFAULT NULL,
  `office_description` text,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`office_id`),
  KEY `office_head_idx` (`office_head_id`),
  KEY `office_under_department_idx` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `tbl_offices` WRITE;
/*!40000 ALTER TABLE `tbl_offices` DISABLE KEYS */;

INSERT INTO `tbl_offices` (`office_id`, `office_name`, `office_head_id`, `office_description`, `department_id`)
VALUES
	(1,'IT Services',NULL,NULL,NULL),
	(2,'Business Office',NULL,NULL,NULL);

/*!40000 ALTER TABLE `tbl_offices` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_ownerships
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_ownerships`;

CREATE TABLE `tbl_ownerships` (
  `ownership_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` bigint(20) unsigned DEFAULT NULL,
  `ownership_type` text,
  `item_id` bigint(20) unsigned DEFAULT NULL,
  `item_status` varchar(45) DEFAULT NULL,
  `date_of_possession` date DEFAULT NULL,
  `date_of_release` date DEFAULT NULL,
  PRIMARY KEY (`ownership_id`),
  KEY `item_owned_idx` (`item_id`),
  KEY `item_owner_idx` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table tbl_packages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_packages`;

CREATE TABLE `tbl_packages` (
  `package_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_name` text,
  `package_serial_no` varchar(45) DEFAULT NULL,
  `package_description` mediumtext,
  `package_items` longtext,
  `date_of_purchase` date DEFAULT NULL,
  PRIMARY KEY (`package_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table tbl_persons
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_persons`;

CREATE TABLE `tbl_persons` (
  `person_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` text,
  `middlename` text,
  `lastname` text,
  `suffix` text,
  `gender` char(1) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `home_address` text,
  `current_address` text,
  `contact_address` text,
  `email_address` varchar(200) DEFAULT NULL,
  `contact_no` varchar(45) DEFAULT NULL,
  `mobile_no` varchar(45) DEFAULT NULL,
  `tel_no` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `tbl_persons` WRITE;
/*!40000 ALTER TABLE `tbl_persons` DISABLE KEYS */;

INSERT INTO `tbl_persons` (`person_id`, `firstname`, `middlename`, `lastname`, `suffix`, `gender`, `birthdate`, `home_address`, `current_address`, `contact_address`, `email_address`, `contact_no`, `mobile_no`, `tel_no`)
VALUES
	(1,'Palmer','Cacdac','Gawaban','Jr.','f','1993-04-01','','','','pakunjr@gmail.com','','',NULL);

/*!40000 ALTER TABLE `tbl_persons` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
