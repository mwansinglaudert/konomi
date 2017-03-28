# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.17)
# Database: mis_konomi
# Generation Time: 2017-03-27 07:05:15 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `log`;

CREATE TABLE `log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(128) DEFAULT NULL,
  `description` varchar(512) DEFAULT NULL,
  `sum` varchar(11) DEFAULT NULL,
  `type` int(1) NOT NULL DEFAULT '0',
  `user` varchar(128) DEFAULT NULL,
  `createstamp` datetime NOT NULL DEFAULT '2017-01-01 00:00:00',
  `timestamp` datetime NOT NULL DEFAULT '2017-01-01 00:00:00',
  `link` varchar(512) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;

INSERT INTO `log` (`id`, `code`, `description`, `sum`, `type`, `user`, `createstamp`, `timestamp`, `link`, `deleted`)
VALUES
	(1,'car','test','1.00',0,'master','2017-03-24 14:59:20','2017-03-24 14:59:20',NULL,1),
	(3,'rental','','2.00',-1,'master','2017-03-01 00:00:00','2017-03-01 00:00:00','',0),
	(4,'kk','','12.56',0,'master','2017-03-27 08:09:41','2017-03-27 08:09:41',NULL,0),
	(5,'edeka','','17.80',0,'master','2017-03-27 08:10:01','2017-03-27 08:10:01',NULL,0),
	(6,'lidl','','3.44',0,'master','2017-03-27 08:18:20','2017-03-27 08:18:20',NULL,0),
	(8,'salary','','100.00',-2,'master','2017-03-01 00:00:00','2017-03-01 00:00:00','',0);

/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table template
# ------------------------------------------------------------

DROP TABLE IF EXISTS `template`;

CREATE TABLE `template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(128) DEFAULT NULL,
  `description` varchar(512) DEFAULT NULL,
  `sum` varchar(11) DEFAULT NULL,
  `type` int(1) NOT NULL DEFAULT '0',
  `user` varchar(128) DEFAULT NULL,
  `createstamp` datetime NOT NULL DEFAULT '2017-01-01 00:00:00',
  `timestamp` datetime NOT NULL DEFAULT '2017-01-01 00:00:00',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `template` WRITE;
/*!40000 ALTER TABLE `template` DISABLE KEYS */;

INSERT INTO `template` (`id`, `code`, `description`, `sum`, `type`, `user`, `createstamp`, `timestamp`, `deleted`)
VALUES
	(1,'salary','',NULL,1,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(2,'ec','',NULL,1,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(3,'food','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(4,'snack','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(5,'edeka','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(6,'kk','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(7,'lidl','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(8,'drugs','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(9,'hair','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(10,'account','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(11,'rental','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(12,'buildingsavings','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(13,'insurance','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(14,'savings','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(15,'internet','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(16,'mobile','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(17,'amazon','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(18,'netflix','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(19,'spotify','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(20,'movie','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(21,'music','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(22,'sport','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(23,'car','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(24,'fuel','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(25,'dog','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(26,'clothing','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(27,'gift','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(28,'party','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(29,'supplements','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(30,'ec','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(31,'other','',NULL,0,'public','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(32,'salary','','100.00',-2,'master','2017-01-01 00:00:00','2017-01-01 00:00:00',0),
	(33,'rental',NULL,'2.00',-1,'master','2017-01-01 00:00:00','2017-01-01 00:00:00',0);

/*!40000 ALTER TABLE `template` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` varchar(11) NOT NULL DEFAULT '',
  `user` varchar(128) DEFAULT NULL,
  `pass` varchar(128) DEFAULT NULL,
  `role` varchar(128) DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '0',
  `timestamp` datetime NOT NULL DEFAULT '2017-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `user`, `pass`, `role`, `is_active`, `timestamp`)
VALUES
	('1','master','$2y$13$A5grfCZROiCjaTcols43B.LXsknGVUqu1nJjAtPD./kgZORukqvxG','ROLE_ADMIN',0,'2017-01-01 00:00:00');

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
