/*
SQLyog Ultimate v11.26 (64 bit)
MySQL - 5.5.36 : Database - sykes_interview
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sykes_interview` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `sykes_interview`;

/*Table structure for table `bookings` */

DROP TABLE IF EXISTS `bookings`;

CREATE TABLE `bookings` (
  `__pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_fk_property` int(10) unsigned DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`__pk`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `bookings` */

insert  into `bookings`(`__pk`,`_fk_property`,`start_date`,`end_date`) values
	(1,1,'2017-08-26','2017-09-02'),
	(2,1,'2017-12-06','2017-12-13');

/*Table structure for table `department` */

DROP TABLE IF EXISTS `department`;

CREATE TABLE `department` (
  `__pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `department_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`__pk`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `department` */

insert  into `department`(`__pk`,`department_name`) values
	(1,'Reservations'),
	(2,'Owners'),
	(3,'Customer Care'),
	(4,'Marketing'),
	(5,'IT');

/*Table structure for table `discounts` */

DROP TABLE IF EXISTS `discounts`;

CREATE TABLE `discounts` (
  `__pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_fk_property` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `percentage` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`__pk`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `discounts` */

insert  into `discounts`(`__pk`,`_fk_property`,`start_date`,`end_date`,`percentage`) values
	(1,1,'2017-08-30','2017-10-02','10.00'),
	(2,2,'2017-01-04','2017-05-02','20.00'),
	(3,3,'2017-05-31','2017-08-29','25.00');

/*Table structure for table `employee` */

DROP TABLE IF EXISTS `employee`;

CREATE TABLE `employee` (
  `__pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_fk_department` int(10) unsigned DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  PRIMARY KEY (`__pk`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `employee` */

insert  into `employee`(`__pk`,`_fk_department`,`first_name`,`last_name`,`age`) values
	(1,3,'James','Rafferty',21),
	(2,1,'Ben','Jones',23),
	(3,NULL,'Ross','Steinberg',56),
	(4,4,'Christine','Robinson',44),
	(5,5,'John','Smith',38),
	(6,2,'Claire','Jenkins',41),
	(7,2,'Zoe','Dean',21);

/*Table structure for table `locations` */

DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations` (
  `__pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`__pk`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `locations` */

insert  into `locations`(`__pk`,`location_name`) values
	(1,'Cornwall'),
	(2,'Lake District'),
	(3,'Yorkshire'),
	(4,'Wales'),
	(5,'Scotland');

/*Table structure for table `price_bands` */

DROP TABLE IF EXISTS `price_bands`;

CREATE TABLE `price_bands` (
  `__pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_fk_property` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`__pk`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `price_bands` */

insert  into `price_bands`(`__pk`,`_fk_property`,`start_date`,`end_date`,`price`) values
	(1,1,'2017-01-04','2017-05-02','250.00'),
	(2,1,'2017-05-03','2017-05-30','300.00'),
	(3,1,'2017-05-31','2017-09-12','500.00'),
	(4,1,'2017-09-13','2017-11-28','300.00'),
	(5,1,'2017-11-29','2016-01-02','100.00'),
	(6,2,'2017-01-04','2016-01-02','900.00'),
	(7,3,'2017-01-04','2017-05-02','333.00'),
	(8,4,'2017-05-03','2017-10-03','666.00'),
	(9,4,'2017-10-04','2016-01-02','333.00'),
	(10,5,'2017-01-04','2017-05-02','250.00'),
	(11,5,'2017-05-03','2017-05-30','500.00'),
	(12,5,'2017-05-31','2017-09-12','750.00'),
	(13,5,'2017-09-13','2017-11-28','500.00'),
	(14,5,'2017-11-29','2016-01-02','250.00');

/*Table structure for table `properties` */

DROP TABLE IF EXISTS `properties`;

CREATE TABLE `properties` (
  `__pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_fk_location` int(10) unsigned DEFAULT NULL,
  `property_name` varchar(255) DEFAULT NULL,
  `near_beach` tinyint(1) unsigned DEFAULT NULL,
  `accepts_pets` tinyint(1) unsigned DEFAULT NULL,
  `sleeps` tinyint(3) unsigned DEFAULT NULL,
  `beds` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`__pk`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `properties` */

insert  into `properties`(`__pk`,`_fk_location`,`property_name`,`near_beach`,`accepts_pets`,`sleeps`,`beds`) values
	(1,1,'Sea View',1,1,4,2),
	(2,3,'Cosey',0,0,6,4),
	(3,5,'The Retreat',1,0,2,1),
	(4,5,'Coach House',0,1,5,3),
	(5,4,'Beach Cottage',1,1,8,6);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
