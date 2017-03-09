# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.15)
# Datenbank: test
# Erstellt am: 2017-03-09 07:36:55 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Export von Tabelle ko_access
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ko_access`;

CREATE TABLE `ko_access` (
  `id` varchar(11) NOT NULL DEFAULT '',
  `user` varchar(128) DEFAULT NULL,
  `pass` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `ko_access` WRITE;
/*!40000 ALTER TABLE `ko_access` DISABLE KEYS */;

INSERT INTO `ko_access` (`id`, `user`, `pass`)
VALUES
	('master','yourUsername','yourMD5Pass');

/*!40000 ALTER TABLE `ko_access` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle ko_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ko_logs`;

CREATE TABLE `ko_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  `image` varchar(128) DEFAULT NULL,
  `sum` varchar(11) DEFAULT NULL,
  `type` int(1) DEFAULT '0',
  `time` varchar(7) DEFAULT NULL,
  `user` varchar(128) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle ko_templates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ko_templates`;

CREATE TABLE `ko_templates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `description` varchar(128) DEFAULT '0',
  `image` varchar(128) DEFAULT NULL,
  `sum` varchar(11) DEFAULT NULL,
  `type` int(1) DEFAULT '0',
  `user` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `ko_templates` WRITE;
/*!40000 ALTER TABLE `ko_templates` DISABLE KEYS */;

INSERT INTO `ko_templates` (`id`, `name`, `description`, `image`, `sum`, `type`, `user`)
VALUES
	(1,'Amazon','','amazon',NULL,0,'public'),
	(2,'EC','','ec',NULL,0,'public'),
	(3,'Tanken','','tanken',NULL,0,'public'),
	(4,'K+K','','kk',NULL,0,'public'),
	(5,'Edeka','','edeka',NULL,0,'public'),
	(6,'Lidl','','lidl',NULL,0,'public'),
	(7,'Essen','','essen',NULL,0,'public'),
	(8,'Imbiss','','imbiss',NULL,0,'public'),
	(9,'Clothing','','clothing',NULL,0,'public'),
	(10,'Sonstiges','','sonstiges',NULL,0,'public'),
	(11,'Miete','','miete','999',-1,'master'),
	(12,'Netflix','','netflix','12',-1,'master'),
	(13,'Spotify','','spotify','10',-1,'master'),
	(14,'Sport','','sport','999',-1,'master'),
	(15,'Auto','','auto','999',-1,'master'),
	(16,'Bauspar','','bauspar','999',-1,'master'),
	(17,'Versicherung','','versicherung','999',-1,'master'),
	(18,'Handy','','handy','999',-1,'master'),
	(19,'Konto','','konto','999',-1,'master'),
	(20,'Internet','','internet','999',-1,'master'),
	(21,'Gehalt','','gehalt',NULL,1,'public'),
	(22,'EC','','ec',NULL,1,'public'),
	(28,'Friseur','','friseur',NULL,0,'public'),
	(29,'Sparen','','sparen',NULL,0,'public'),
	(30,'Gesundheit','','drugs',NULL,0,'public'),
	(31,'Hunde','','hunde',NULL,0,'public'),
	(32,'Geschenke','','geschenke',NULL,0,'public'),
	(33,'Kino','','kino',NULL,0,'public'),
	(34,'Musik','','musik',NULL,0,'public'),
	(35,'Supplements','','supplements',NULL,0,'public'),
	(36,'Party','','party',NULL,0,'public');

/*!40000 ALTER TABLE `ko_templates` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
