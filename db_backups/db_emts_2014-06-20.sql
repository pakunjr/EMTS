# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.17)
# Database: db_emts
# Generation Time: 2014-06-20 09:24:47 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table lst_access_level
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lst_access_level`;

CREATE TABLE `lst_access_level` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `lst_access_level` WRITE;
/*!40000 ALTER TABLE `lst_access_level` DISABLE KEYS */;

INSERT INTO `lst_access_level` (`id`, `name`, `description`)
VALUES
	(1,'Administrator','A user that has access to all modules. - A master user.'),
	(2,'Report Generator','A user that can do the actions of a content provider and can generate report.'),
	(3,'Content Provider','A user that can do the actions of a viewer and can provide content for the system.'),
	(4,'Viewer','A user that can only view information in the system.');

/*!40000 ALTER TABLE `lst_access_level` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lst_item_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lst_item_type`;

CREATE TABLE `lst_item_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `label` text,
  `description` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

LOCK TABLES `lst_item_type` WRITE;
/*!40000 ALTER TABLE `lst_item_type` DISABLE KEYS */;

INSERT INTO `lst_item_type` (`id`, `label`, `description`)
VALUES
	(1,'Consumable','Items which are consumed.\nE.g. Bond paper, pen, pencil, ink refill for printers, and the likes.'),
	(2,'Devices','Items which are used for computing.\nE.g. Computer hardwares, biometric equipments, walkie-talkie, and the likes.'),
	(4,'Tools','Items which are classified as tools.\nE.g. Screwdrivers, rulers, plies, and the likes.'),
	(6,'Necessities','Items which are a necessity for everyday office use.\nE.g. Tables, chairs, drawers, lockers, and the likes.');

/*!40000 ALTER TABLE `lst_item_type` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lst_ownership_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lst_ownership_type`;

CREATE TABLE `lst_ownership_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ownership_label` varchar(50) DEFAULT '',
  `ownership_description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `lst_ownership_type` WRITE;
/*!40000 ALTER TABLE `lst_ownership_type` DISABLE KEYS */;

INSERT INTO `lst_ownership_type` (`id`, `ownership_label`, `ownership_description`)
VALUES
	(1,'employee','The owner is categorized as an employee.'),
	(2,'department','The owner is categorized as the department wherein the person liable of the item is the department head.'),
	(3,'guest','The owner is from the outside party whom is not part of the company.');

/*!40000 ALTER TABLE `lst_ownership_type` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_accounts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_accounts`;

CREATE TABLE `tbl_accounts` (
  `account_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL DEFAULT '',
  `password` varchar(100) DEFAULT NULL,
  `password_salt` varchar(100) DEFAULT NULL,
  `access_level` int(11) DEFAULT NULL,
  `owner_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `username` (`username`),
  KEY `account_owner_idx` (`owner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `tbl_accounts` WRITE;
/*!40000 ALTER TABLE `tbl_accounts` DISABLE KEYS */;

INSERT INTO `tbl_accounts` (`account_id`, `username`, `password`, `password_salt`, `access_level`, `owner_id`)
VALUES
	(1,'admin','4f064cef27d450e827cce6a57535ea4509b2d420b26b4ae9016cc9030345d7bd','alskdfkl',1,1),
	(2,'supervisor','40e918154a955918ec76e6b8b7b1ded8a8d3a06f6e4df2b862fe59794473f1f9','9774065b429301e7195b22fbc00736ebbddf5867b92b8f124b6c2912a69aa44f',2,1),
	(3,'encoder','40e918154a955918ec76e6b8b7b1ded8a8d3a06f6e4df2b862fe59794473f1f9','9774065b429301e7195b22fbc00736ebbddf5867b92b8f124b6c2912a69aa44f',3,1),
	(4,'viewer','40e918154a955918ec76e6b8b7b1ded8a8d3a06f6e4df2b862fe59794473f1f9','9774065b429301e7195b22fbc00736ebbddf5867b92b8f124b6c2912a69aa44f',4,1);

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

LOCK TABLES `tbl_departments` WRITE;
/*!40000 ALTER TABLE `tbl_departments` DISABLE KEYS */;

INSERT INTO `tbl_departments` (`department_id`, `department_name_short`, `department_name`, `department_head_id`, `department_description`)
VALUES
	(1,'ITS','IT Services',1,NULL),
	(2,'CCSE','College of Computer Studies and Engineering',2,NULL),
	(3,'CAS','College of Arts and Sciences',4,NULL),
	(4,'CON','College of Nursing',2,NULL),
	(5,'CMLS','College of Medical Laboratory Science',3,NULL),
	(6,'CMA','College of Management and Accountancy',2,NULL);

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
  `primary_department_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `tbl_employees` WRITE;
/*!40000 ALTER TABLE `tbl_employees` DISABLE KEYS */;

INSERT INTO `tbl_employees` (`employee_id`, `employee_no`, `person_id`, `occupation`, `primary_department_id`)
VALUES
	(1,'1553',1,'Web Developer',1);

/*!40000 ALTER TABLE `tbl_employees` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_inspections
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_inspections`;

CREATE TABLE `tbl_inspections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table tbl_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_items`;

CREATE TABLE `tbl_items` (
  `item_id` char(15) NOT NULL DEFAULT '',
  `item_serial_no` varchar(80) DEFAULT NULL,
  `item_model_no` varchar(80) DEFAULT NULL,
  `item_name` text,
  `item_type` bigint(20) DEFAULT NULL,
  `item_description` longtext,
  `date_of_purchase` date DEFAULT NULL,
  `package_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `tbl_items` WRITE;
/*!40000 ALTER TABLE `tbl_items` DISABLE KEYS */;

INSERT INTO `tbl_items` (`item_id`, `item_serial_no`, `item_model_no`, `item_name`, `item_type`, `item_description`, `date_of_purchase`, `package_id`)
VALUES
	('1','CHK-MLD-ABK-039','938-29193-92-DKS','iMac',2,NULL,'2014-08-05',NULL),
	('2','LKM-DLS-WOE-234','938-29193-83-MLD','iMouse',2,NULL,'2014-08-05',NULL),
	('3','PLE-DOS-EOW-3234','293-293-291-PED','SanDisk USB Flashdrive',2,'A temporary storage file','2014-05-15',NULL),
	('4','JKL-OM-EOW-3234','832-839-321-LDK','SanDisk USB Flashdrive',2,'A temporary storage file','2014-05-15',NULL),
	('5','PLK-DLW-DL-3292','392-3948-392-LSK','ASUS Monitor',2,'ASUS Monitor','2014-05-22',NULL),
	('6','LSLE-WOE-EO-2938','928-3939-4939-29','Dell CPU',2,'Just an Item','2014-05-09',NULL);

/*!40000 ALTER TABLE `tbl_items` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_owner_guest
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_owner_guest`;

CREATE TABLE `tbl_owner_guest` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` bigint(20) DEFAULT NULL,
  `additional_information` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



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
  `ownership_status` char(1) DEFAULT NULL,
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
  `package_description` longtext,
  `date_of_purchase` date DEFAULT NULL,
  PRIMARY KEY (`package_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `tbl_packages` WRITE;
/*!40000 ALTER TABLE `tbl_packages` DISABLE KEYS */;

INSERT INTO `tbl_packages` (`package_id`, `package_name`, `package_serial_no`, `package_description`, `date_of_purchase`)
VALUES
	(1,'Apple Package','ABC-DK-ECH-2938','Apple items','2014-05-24'),
	(2,'Apple Package','DLK-OD-PLD-3943','iMac 360','2014-05-28');

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
  `mobile_no_a` varchar(45) DEFAULT NULL,
  `mobile_no_b` varchar(45) DEFAULT NULL,
  `tel_no` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `tbl_persons` WRITE;
/*!40000 ALTER TABLE `tbl_persons` DISABLE KEYS */;

INSERT INTO `tbl_persons` (`person_id`, `firstname`, `middlename`, `lastname`, `suffix`, `gender`, `birthdate`, `home_address`, `current_address`, `contact_address`, `email_address`, `mobile_no_a`, `mobile_no_b`, `tel_no`)
VALUES
	(1,'Palmer','Cacdac','Gawaban','Jr.','m','1993-04-01','','','','pakunjr@gmail.com','',NULL,NULL),
	(2,'Sherolot',NULL,'So',NULL,'f','1857-05-25',NULL,NULL,NULL,'sherolot@lorma.edu',NULL,NULL,NULL),
	(3,'Myla',NULL,'Dy',NULL,'f','1895-03-01',NULL,NULL,NULL,'myla.dy@lorma.edu','09083071394',NULL,NULL),
	(4,'Gintoki','Salazar','Himura','Jr.','m','1890-04-23',NULL,NULL,NULL,'gintoki.himura@lorma.edu','09123071394',NULL,NULL);

/*!40000 ALTER TABLE `tbl_persons` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
