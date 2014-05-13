# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.17)
# Database: db_emts
# Generation Time: 2014-05-13 07:30:31 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table lst_item_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lst_item_type`;

CREATE TABLE `lst_item_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `label` text,
  `description` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `lst_item_type` WRITE;
/*!40000 ALTER TABLE `lst_item_type` DISABLE KEYS */;

INSERT INTO `lst_item_type` (`id`, `label`, `description`)
VALUES
	(1,'Consumable',NULL),
	(2,'Computer Peripherals',NULL),
	(3,'Office Equipments',NULL),
	(4,'Office Tools',NULL);

/*!40000 ALTER TABLE `lst_item_type` ENABLE KEYS */;
UNLOCK TABLES;


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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

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
  `item_type` bigint(20) DEFAULT NULL,
  `item_description` text,
  `date_of_purchase` date DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

LOCK TABLES `tbl_items` WRITE;
/*!40000 ALTER TABLE `tbl_items` DISABLE KEYS */;

INSERT INTO `tbl_items` (`item_id`, `item_serial_no`, `item_model_no`, `item_name`, `item_type`, `item_description`, `date_of_purchase`)
VALUES
	(1,'CHK-MLD-ABK-039','938-29193-92-DKS','iMac',2,NULL,'2014-08-05'),
	(2,'LKM-DLS-WOE-234','938-29193-83-MLD','iMouse',2,NULL,'2014-08-05'),
	(3,'PLE-DOS-EOW-3234','293-293-291-PED','SanDisk USB Flashdrive',2,'A temporary storage file','2014-05-15'),
	(4,'JKL-OM-EOW-3234','832-839-321-LDK','SanDisk USB Flashdrive',2,'A temporary storage file','2014-05-15'),
	(5,'PLK-DLW-DL-3292','392-3948-392-LSK','ASUS Monitor',2,'ASUS Monitor','2014-05-22'),
	(6,'LSLE-WOE-EO-2938','928-3939-4939-29','Dell CPU',2,'Just an Item','2014-05-09');

/*!40000 ALTER TABLE `tbl_items` ENABLE KEYS */;
UNLOCK TABLES;


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

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
  `owner_employee_id` bigint(20) unsigned DEFAULT NULL,
  `ownership_type` text,
  `item_id` bigint(20) unsigned DEFAULT NULL,
  `item_status` varchar(45) DEFAULT NULL,
  `date_of_possession` date DEFAULT NULL,
  `date_of_release` date DEFAULT NULL,
  PRIMARY KEY (`ownership_id`),
  KEY `item_owned_idx` (`item_id`),
  KEY `item_owner_idx` (`owner_employee_id`)
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `tbl_packages` WRITE;
/*!40000 ALTER TABLE `tbl_packages` DISABLE KEYS */;

INSERT INTO `tbl_packages` (`package_id`, `package_name`, `package_serial_no`, `package_description`, `package_items`, `date_of_purchase`)
VALUES
	(1,'Apple Package','ABC-DK-ECH-2938','Apple items',NULL,'2014-05-24'),
	(2,'Apple Package','DLK-OD-PLD-3943','iMac 360',NULL,'2014-05-28');

/*!40000 ALTER TABLE `tbl_packages` ENABLE KEYS */;
UNLOCK TABLES;


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `tbl_persons` WRITE;
/*!40000 ALTER TABLE `tbl_persons` DISABLE KEYS */;

INSERT INTO `tbl_persons` (`person_id`, `firstname`, `middlename`, `lastname`, `suffix`, `gender`, `birthdate`, `home_address`, `current_address`, `contact_address`, `email_address`, `contact_no`, `mobile_no`, `tel_no`)
VALUES
	(1,'Palmer','Cacdac','Gawaban','Jr.','f','1993-04-01','','','','pakunjr@gmail.com','','',NULL),
	(2,'Richard',NULL,'So',NULL,'m','1857-05-25',NULL,NULL,NULL,'ricardo@gmail.com',NULL,NULL,NULL);

/*!40000 ALTER TABLE `tbl_persons` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
