# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 192.168.5.3 (MySQL 5.0.51a-24+lenny5)
# Database: vizra3_test
# Generation Time: 2013-05-11 20:16:43 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table admin_deps
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_deps`;

CREATE TABLE `admin_deps` (
  `adminID` smallint(4) unsigned NOT NULL,
  `depID` smallint(3) unsigned NOT NULL,
  KEY `adminID` (`adminID`,`depID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `admin_deps` WRITE;
/*!40000 ALTER TABLE `admin_deps` DISABLE KEYS */;

INSERT INTO `admin_deps` (`adminID`, `depID`)
VALUES
	(1,1),
	(1,2),
	(1,3),
	(1,4),
	(2,2),
	(2,3),
	(2,4);

/*!40000 ALTER TABLE `admin_deps` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_privs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_privs`;

CREATE TABLE `admin_privs` (
  `adminID` smallint(5) unsigned NOT NULL,
  `pageID` smallint(3) unsigned NOT NULL,
  `priv` bigint(20) NOT NULL,
  UNIQUE KEY `adminID` (`adminID`,`pageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `admin_privs` WRITE;
/*!40000 ALTER TABLE `admin_privs` DISABLE KEYS */;

INSERT INTO `admin_privs` (`adminID`, `pageID`, `priv`)
VALUES
	(2,110,13),
	(2,176,15),
	(2,175,13),
	(2,170,9),
	(2,165,5),
	(2,150,1),
	(2,145,0),
	(2,140,1),
	(2,135,3),
	(2,111,9),
	(2,130,5),
	(2,112,5),
	(2,115,3),
	(2,116,1),
	(2,117,3),
	(2,125,9),
	(2,120,5),
	(2,210,9),
	(2,410,9),
	(2,420,9),
	(2,516,2),
	(2,615,0),
	(2,412,5),
	(2,411,33),
	(2,415,0),
	(2,310,1),
	(2,215,1),
	(2,211,1),
	(2,212,1),
	(2,311,1),
	(2,312,1);

/*!40000 ALTER TABLE `admin_privs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_qreps
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_qreps`;

CREATE TABLE `admin_qreps` (
  `qrepID` smallint(5) unsigned NOT NULL auto_increment,
  `adminID` smallint(5) unsigned NOT NULL,
  `reply` text NOT NULL,
  PRIMARY KEY  (`qrepID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table admin_setting_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_setting_types`;

CREATE TABLE `admin_setting_types` (
  `settingID` smallint(3) unsigned NOT NULL auto_increment,
  `setting` varchar(50) NOT NULL,
  `type` enum('textbox','checkbox','combobox') NOT NULL default 'textbox',
  `size` varchar(10) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `grp` varchar(20) NOT NULL,
  `rowOrder` smallint(3) unsigned NOT NULL,
  PRIMARY KEY  (`settingID`),
  UNIQUE KEY `setting` (`setting`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

LOCK TABLES `admin_setting_types` WRITE;
/*!40000 ALTER TABLE `admin_setting_types` DISABLE KEYS */;

INSERT INTO `admin_setting_types` (`settingID`, `setting`, `type`, `size`, `title`, `description`, `grp`, `rowOrder`)
VALUES
	(1,'staticChatWindow','checkbox','','Sabit Chat Penceresi','','layout',1),
	(2,'staticLogWindow','checkbox','','Sabit Log Penceresi','','layout',2),
	(3,'staticProbesColumn','checkbox','','Sabit Monitör Göstergeleri','','layout',3),
	(4,'ordersSearch_orderStatus','textbox','','','','hidden',0),
	(5,'ordersSearch_serviceID','textbox','','','','hidden',0),
	(6,'ordersSearch_groupID','textbox','','','','hidden',0);

/*!40000 ALTER TABLE `admin_setting_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_settings`;

CREATE TABLE `admin_settings` (
  `settingID` smallint(3) unsigned NOT NULL,
  `adminID` smallint(4) unsigned NOT NULL,
  `value` varchar(100) NOT NULL,
  UNIQUE KEY `settingID` (`settingID`,`adminID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `admin_settings` WRITE;
/*!40000 ALTER TABLE `admin_settings` DISABLE KEYS */;

INSERT INTO `admin_settings` (`settingID`, `adminID`, `value`)
VALUES
	(1,1,'1'),
	(2,1,'1'),
	(3,1,'1'),
	(1,2,''),
	(2,2,''),
	(3,2,'');

/*!40000 ALTER TABLE `admin_settings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admins`;

CREATE TABLE `admins` (
  `adminID` smallint(4) unsigned NOT NULL auto_increment,
  `type` enum('admin','super-admin') NOT NULL default 'admin',
  `status` enum('active','inactive') NOT NULL default 'inactive',
  `adminPassword` varchar(64) NOT NULL,
  `adminEmail` varchar(100) NOT NULL,
  `adminMsn` varchar(100) NOT NULL,
  `adminName` varchar(30) NOT NULL,
  `adminNick` varchar(20) NOT NULL,
  `adminTitle` varchar(100) NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  `dateLogin` int(10) unsigned NOT NULL,
  `ipLogin` varchar(30) NOT NULL,
  PRIMARY KEY  (`adminID`)
) ENGINE=MyISAM AUTO_INCREMENT=162 DEFAULT CHARSET=utf8;

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;

INSERT INTO `admins` (`adminID`, `type`, `status`, `adminPassword`, `adminEmail`, `adminMsn`, `adminName`, `adminNick`, `adminTitle`, `dateAdded`, `dateUpdated`, `dateLogin`, `ipLogin`)
VALUES
	(0,'admin','inactive','','','','','System','',0,0,0,''),
	(1,'super-admin','active','yPfnMgkmCCuUtzZ81QrlH3H32ZrSixu+KzqJGKvRKhg=','admin@vizra.com','','Demo Admin','DemoAdmin','Vizra Administrator',0,1271968896,1274023220,'192.168.5.1'),
	(2,'admin','inactive','iOktGYu/8nMnFRJ1xjm15NbQy4WBZFN8ZGfiNOqDHD0=','eleman@vizra.com','','Demo Eleman','Eleman','Teknik Destek Uzmanı',0,1264612155,1265168275,'10.0.0.1');

/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table announcements
# ------------------------------------------------------------

DROP TABLE IF EXISTS `announcements`;

CREATE TABLE `announcements` (
  `recID` smallint(4) unsigned NOT NULL auto_increment,
  `adminID` smallint(4) unsigned NOT NULL,
  `status` enum('active','clients-only','inactive') NOT NULL default 'active',
  `title` varchar(100) NOT NULL,
  `body` text NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`recID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;

INSERT INTO `announcements` (`recID`, `adminID`, `status`, `title`, `body`, `dateAdded`)
VALUES
	(1,1,'active','Vizra3 Yeniden Yazıldı!','<p>Vizra3 tamamen yeniden yazıldı!</p>',1266536106),
	(2,1,'clients-only','Sayın Müşterilerimiz..','<p>Bu duyuruyu sadece kayıtlı kullanıcılar görebilir..</p>',1266536132);

/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table attrs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `attrs`;

CREATE TABLE `attrs` (
  `attrID` smallint(5) unsigned NOT NULL auto_increment,
  `label` varchar(100) NOT NULL,
  `client_type` enum('all','individual','corporate') NOT NULL default 'all',
  `type` enum('textbox','textarea','checkbox','combobox','db','server') NOT NULL default 'textbox',
  `visibility` enum('required','hidden','optional','system') NOT NULL default 'optional',
  `options` text NOT NULL,
  `description` text NOT NULL,
  `width` smallint(3) unsigned NOT NULL,
  `height` smallint(3) unsigned NOT NULL,
  `encrypted` enum('0','1') NOT NULL default '0',
  `validation` varchar(255) NOT NULL,
  `validation_function` varchar(150) NOT NULL,
  `validation_info` text NOT NULL,
  PRIMARY KEY  (`attrID`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

LOCK TABLES `attrs` WRITE;
/*!40000 ALTER TABLE `attrs` DISABLE KEYS */;

INSERT INTO `attrs` (`attrID`, `label`, `client_type`, `type`, `visibility`, `options`, `description`, `width`, `height`, `encrypted`, `validation`, `validation_function`, `validation_info`)
VALUES
	(1,'TC Kimlik No','individual','textbox','required','','',200,0,'1','','checkTCKN','TC Kimlik No 11 haneli ve sadece rakamlardan oluşmalıdır'),
	(9,'directi_customerID','all','textbox','system','','',0,0,'0','','',''),
	(10,'Vergi Dairesi','corporate','textbox','required','','',200,0,'0','^[a-zA-Z0-9 ]{2,60}$','','Vergi Dairesi alanı kurumsal müşteriler için zorunludur'),
	(11,'Vergi No','corporate','textbox','required','','',200,0,'0','^\\d{4,11}$','','Vergi No alanı kurumsal müşteriler için zorunludur');

/*!40000 ALTER TABLE `attrs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table chat
# ------------------------------------------------------------

DROP TABLE IF EXISTS `chat`;

CREATE TABLE `chat` (
  `messageID` int(10) unsigned NOT NULL auto_increment,
  `adminID` smallint(5) unsigned NOT NULL,
  `message` text NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`messageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table client_contacts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `client_contacts`;

CREATE TABLE `client_contacts` (
  `contactID` mediumint(8) unsigned NOT NULL auto_increment,
  `clientID` mediumint(7) unsigned NOT NULL,
  `default` enum('0','1') NOT NULL default '0',
  `name` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `zip` varchar(50) NOT NULL,
  `country` varchar(2) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `fax` varchar(15) NOT NULL,
  `cell` varchar(15) NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`contactID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table client_extras
# ------------------------------------------------------------

DROP TABLE IF EXISTS `client_extras`;

CREATE TABLE `client_extras` (
  `clientID` mediumint(7) unsigned NOT NULL,
  `attrID` smallint(5) unsigned NOT NULL,
  `value` text NOT NULL,
  UNIQUE KEY `clientID` (`clientID`,`attrID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `client_extras` WRITE;
/*!40000 ALTER TABLE `client_extras` DISABLE KEYS */;

INSERT INTO `client_extras` (`clientID`, `attrID`, `value`)
VALUES
	(3339220,1,''),
	(3339220,9,'');

/*!40000 ALTER TABLE `client_extras` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table client_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `client_groups`;

CREATE TABLE `client_groups` (
  `groupID` smallint(5) unsigned NOT NULL auto_increment,
  `group_name` varchar(150) NOT NULL,
  `discount_rate` decimal(2,2) NOT NULL,
  PRIMARY KEY  (`groupID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `client_groups` WRITE;
/*!40000 ALTER TABLE `client_groups` DISABLE KEYS */;

INSERT INTO `client_groups` (`groupID`, `group_name`, `discount_rate`)
VALUES
	(1,'Genel Müşteriler',0.00);

/*!40000 ALTER TABLE `client_groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table clients
# ------------------------------------------------------------

DROP TABLE IF EXISTS `clients`;

CREATE TABLE `clients` (
  `clientID` mediumint(7) unsigned NOT NULL,
  `groupID` smallint(5) unsigned NOT NULL default '1',
  `status` enum('active','inactive','pending','suspended') NOT NULL,
  `autoSuspend` enum('0','1') NOT NULL default '1',
  `type` enum('individual','corporate') NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(64) NOT NULL,
  `name` varchar(80) NOT NULL,
  `company` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `state` varchar(50) NOT NULL,
  `zip` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(2) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `cell` varchar(30) NOT NULL,
  `notes` text NOT NULL,
  `fnote` varchar(250) NOT NULL,
  `dateAdded` int(11) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  `dateLogin` int(11) NOT NULL,
  `ipReg` varchar(15) NOT NULL,
  `ipLogin` varchar(15) NOT NULL,
  `isVip` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`clientID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;

INSERT INTO `clients` (`clientID`, `groupID`, `status`, `autoSuspend`, `type`, `email`, `password`, `name`, `company`, `address`, `state`, `zip`, `city`, `country`, `phone`, `cell`, `notes`, `fnote`, `dateAdded`, `dateUpdated`, `dateLogin`, `ipReg`, `ipLogin`, `isVip`)
VALUES
	(3339220,1,'active','0','individual','user@vizra.com','UNsRGyeiOd7cys/bu46pMg==','Demo User','','Adres','Semt','34000','Istanbul','TR','212 212 22 22','532 333 44 55','','',1265769135,1271976044,0,'','','no');

/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table countries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `country` varchar(128) NOT NULL,
  `country_code` varchar(2) NOT NULL,
  `calling_code` varchar(64) NOT NULL,
  `calling_code_regex` varchar(255) NOT NULL,
  `calling_code_mask` varchar(30) NOT NULL,
  `default` enum('0','1') NOT NULL default '0',
  UNIQUE KEY `code` (`country_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;

INSERT INTO `countries` (`country`, `country_code`, `calling_code`, `calling_code_regex`, `calling_code_mask`, `default`)
VALUES
	('Afghanistan','AF','93','','','0'),
	('Albania','AL','355','','','0'),
	('Algeria','DZ','213','','','0'),
	('Angola ','AO','244','','','0'),
	('Antarctica','AQ','672','','','0'),
	('Argentina','AR','54','','','0'),
	('Armenia ','AM','374','','','0'),
	('Aruba','AW','297','','','0'),
	('Australia ','AU','61','','','0'),
	('Austria','AT','43','','','0'),
	('Azerbaijan','AZ','994','','','0'),
	('Bahrain','BH','973','','','0'),
	('Bangladesh','BD','880','','','0'),
	('Belgium','BE','32','','','0'),
	('Belize','BZ','501','','','0'),
	('Benin','BJ','229','','','0'),
	('Bhutan','BT','975','','','0'),
	('Bolivia ','BO','591','','','0'),
	('Bosnia & Herzegovina ','BA','387','','','0'),
	('Botswana ','BW','267','','','0'),
	('Brazil ','BR','55','','','0'),
	('Brunei Darussalam','BN','673','','','0'),
	('Bulgaria','BG','359','','','0'),
	('Burkina Faso ','BF','226','','','0'),
	('Burundi','BI','257','','','0'),
	('Cameroon','CM','237','','','0'),
	('Canada','CA','1','','','0'),
	('Cape Verde Islands','CV','238','','','0'),
	('Central African Republic','CF','236','','','0'),
	('Chad ','TD','235','','','0'),
	('Chile ','CL','56','','','0'),
	('China (PRC)','CN','86','','','0'),
	('Christmas Island','CX','61-8','','','0'),
	('Cocos-Keeling Islands','CC','61','','','0'),
	('Colombia ','CO','57','','','0'),
	('Comoros','KM','269','','','0'),
	('Congo','CG','242','','','0'),
	('Congo, Dem. Rep. (former Zaire) ','CD','243','','','0'),
	('Cook Islands','CK','682','','','0'),
	('Costa Rica','CR','506','','','0'),
	('Cote d\'Ivoire (Ivory Coast)','CI','225','','','0'),
	('Croatia','HR','385','','','0'),
	('Cuba','CU','53','','','0'),
	('Curacao','CW','599','','','0'),
	('Cyprus','CY','357','','','0'),
	('Czech Republic','CZ','420','','','0'),
	('Denmark','DK','45','','','0'),
	('Djibouti','DJ','253','','','0'),
	('Ecuador ','EC','593','','','0'),
	('Egypt','EG','20','','','0'),
	('El Salvador','SV','503','','','0'),
	('Eritrea','ER','291','','','0'),
	('Estonia','EE','372','','','0'),
	('Ethiopia','ET','251','','','0'),
	('Falkland Islands (Malvinas)','FK','500','','','0'),
	('Faroe Islands','FO','298','','','0'),
	('Fiji Islands','FJ','679','','','0'),
	('Finland','FI','358','','','0'),
	('France','FR','33','','','0'),
	('Gambia','GM','220','','','0'),
	('Georgia','GE','995','','','0'),
	('Germany','DE','49','','','0'),
	('Ghana ','GH','233','','','0'),
	('Gibraltar ','GI','350','','','0'),
	('Greece ','GR','30','','','0'),
	('Greenland ','GL','299','','','0'),
	('Guadeloupe','GP','590','','','0'),
	('Guatemala ','GT','502','','','0'),
	('Guinea-Bissau ','GW','245','','','0'),
	('Guinea','GN','224','','','0'),
	('Guyana','GY','592','','','0'),
	('Haiti ','HT','509','','','0'),
	('Honduras','HN','504','','','0'),
	('Hong Kong','HK','852','','','0'),
	('Hungary ','HU','36','','','0'),
	('Iceland','IS','354','','','0'),
	('India','IN','91','','','0'),
	('Indonesia','ID','62','','','0'),
	('Iran','IR','98','','','0'),
	('Iraq','IQ','964','','','0'),
	('Ireland','IE','353','','','0'),
	('Israel ','IL','972','','','0'),
	('Italy ','IT','39','','','0'),
	('Japan ','JP','81','','','0'),
	('Jordan','JO','962','','','0'),
	('Kazakhstan','KG','7','','','0'),
	('Kenya','KE','254','','','0'),
	('Kiribati ','KI','686','','','0'),
	('Korea (North)','KP','850','','','0'),
	('Korea (South)','KR','82','','','0'),
	('Kuwait ','KW','965','','','0'),
	('Latvia ','LV','371','','','0'),
	('Lebanon','LB','961','','','0'),
	('Lesotho','LS','266','','','0'),
	('Liberia','LR','231','','','0'),
	('Libya','LY','218','','','0'),
	('Liechtenstein','LI','423','','','0'),
	('Lithuania ','LT','370','','','0'),
	('Luxembourg','LU','352','','','0'),
	('Macao','MO','853','','','0'),
	('Macedonia (Former Yugoslav Rep of.)','MK','389','','','0'),
	('Madagascar','MG','261','','','0'),
	('Malawi ','MW','265','','','0'),
	('Malaysia','MY','60','','','0'),
	('Maldives','MV','960','','','0'),
	('Mali Republic','ML','223','','','0'),
	('Malta','MT','356','','','0'),
	('Marshall Islands','MH','692','','','0'),
	('Martinique','MQ','596','','','0'),
	('Mauritania','MR','222','','','0'),
	('Mauritius','MU','230','','','0'),
	('Mexico','MX','52','','','0'),
	('Moldova ','MD','373','','','0'),
	('Monaco','MC','377','','','0'),
	('Mongolia ','MN','976','','','0'),
	('Montenegro','ME','382','','','0'),
	('Morocco','MA','212','','','0'),
	('Mozambique','MZ','258','','','0'),
	('Myanmar','MM','95','','','0'),
	('Namibia','NA','264','','','0'),
	('Nauru','NR','674','','','0'),
	('Nepal ','NP','977','','','0'),
	('Netherlands','NL','31','','','0'),
	('New Caledonia','NC','687','','','0'),
	('New Zealand','NZ','64','','','0'),
	('Nicaragua','NI','505','','','0'),
	('Niger','NE','227','','','0'),
	('Nigeria','NG','234','','','0'),
	('Niue','NU','683','','','0'),
	('Norfolk Island','NF','672','','','0'),
	('Norway ','NO','47','','','0'),
	('Oman','OM','968','','','0'),
	('Pakistan','PK','92','','','0'),
	('Palau','PW','680','','','0'),
	('Palestinian Settlements','PS','970','','','0'),
	('Papua New Guinea','PG','675','','','0'),
	('Paraguay','PY','595','','','0'),
	('Peru','PE','51','','','0'),
	('Philippines','PH','63','','','0'),
	('Poland','PL','48','','','0'),
	('Portugal','PT','351','','','0'),
	('Qatar','QA','974','','','0'),
	('Romania','RO','40','','','0'),
	('Russia','RU','7','','','0'),
	('Rwandese Republic','RW','250','','','0'),
	('St. Helena','SH','290','','','0'),
	('St. Pierre & Miquelon','PM','508','','','0'),
	('Samoa','WS','685','','','0'),
	('San Marino','SM','378','','','0'),
	('Sao Tome and Principe','ST','239','','','0'),
	('Saudi Arabia','SA','966','','','0'),
	('Senegal ','SN','221','','','0'),
	('Serbia','RS','381','','','0'),
	('Seychelles Republic','SC','248','','','0'),
	('Sierra Leone','SL','232','','','0'),
	('Singapore','SG','65','','','0'),
	('Slovak Republic','SK','421','','','0'),
	('Slovenia ','SI','386','','','0'),
	('Solomon Islands','SB','677','','','0'),
	('South Africa','ZA','27','','','0'),
	('Spain','ES','34','','','0'),
	('Sri Lanka','LK','94','','','0'),
	('Sudan','SD','249','','','0'),
	('Suriname ','SR','597','','','0'),
	('Swaziland','SZ','268','','','0'),
	('Sweden','SE','46','','','0'),
	('Switzerland','CH','41','','','0'),
	('Syria','SY','963','','','0'),
	('Taiwan','TW','886','','','0'),
	('Tajikistan','TJ','992','','','0'),
	('Tanzania','TZ','255','','','0'),
	('Thailand','TH','66','','','0'),
	('Timor Leste','TL','670','','','0'),
	('Tokelau','TK','690','','','0'),
	('Tonga Islands','TO','676','','','0'),
	('Tunisia','TN','216','','','0'),
	('Turkiye','TR','90','^\\d{3} \\d{3} \\d{2} \\d{2}','999 999 99 99','1'),
	('Turkmenistan ','TM','993','','','0'),
	('Tuvalu','TV','688','','','0'),
	('Uganda','UG','256','','','0'),
	('Ukraine','UA','380','','','0'),
	('United Arab Emirates','AE','971','','','0'),
	('United Kingdom','GB','44','','','0'),
	('United States of America','US','1','','','0'),
	('Uruguay','UY','598','','','0'),
	('Uzbekistan','UZ','998','','','0'),
	('Vanuatu','VU','678','','','0'),
	('Vatican City','VA','39','','','0'),
	('Venezuela','VE','58','','','0'),
	('Vietnam','VN','84','','','0'),
	('Yemen','YE','967','','','0'),
	('Zambia ','ZM','260','','','0'),
	('Zimbabwe ','ZW','263','','','0');

/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table coupons
# ------------------------------------------------------------

DROP TABLE IF EXISTS `coupons`;

CREATE TABLE `coupons` (
  `couponID` mediumint(8) unsigned NOT NULL auto_increment,
  `code` varchar(64) NOT NULL,
  `type` enum('percentage','fixed') NOT NULL default 'percentage',
  `amount` int(10) unsigned NOT NULL,
  `services` text NOT NULL,
  `dateExpires` int(10) unsigned NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`couponID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;



# Dump of table crons
# ------------------------------------------------------------

DROP TABLE IF EXISTS `crons`;

CREATE TABLE `crons` (
  `cronID` tinyint(3) unsigned NOT NULL auto_increment,
  `type` enum('minutely','hourly','daily','weekly','monthly') NOT NULL,
  `status` enum('completed','error','running') NOT NULL,
  `filename` varchar(50) NOT NULL,
  `dateStart` int(10) unsigned NOT NULL,
  `dateEnd` int(10) NOT NULL,
  `duration` smallint(4) unsigned NOT NULL,
  `minute` tinyint(2) unsigned zerofill NOT NULL,
  `hour` tinyint(2) unsigned zerofill NOT NULL,
  `day` tinyint(3) unsigned NOT NULL,
  `code` varchar(16) NOT NULL,
  PRIMARY KEY  (`cronID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

LOCK TABLES `crons` WRITE;
/*!40000 ALTER TABLE `crons` DISABLE KEYS */;

INSERT INTO `crons` (`cronID`, `type`, `status`, `filename`, `dateStart`, `dateEnd`, `duration`, `minute`, `hour`, `day`, `code`)
VALUES
	(1,'minutely','completed','minutely.php',1304381701,0,0,05,00,0,'g4JkhYpcWywv7muj'),
	(4,'daily','completed','daily.php',0,0,0,15,00,0,'HnLqLFYG3jBKU86k'),
	(5,'hourly','completed','hourly.php',0,0,0,39,00,0,'LQzgUJuvRs5yYavG'),
	(6,'hourly','completed','currency.php',1268363282,0,0,00,00,0,'');

/*!40000 ALTER TABLE `crons` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dc_cats
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dc_cats`;

CREATE TABLE `dc_cats` (
  `catID` smallint(4) unsigned NOT NULL auto_increment,
  `parentID` smallint(4) unsigned NOT NULL default '0',
  `visibility` enum('everyone','client','admin') NOT NULL default 'everyone',
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `entries` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`catID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `dc_cats` WRITE;
/*!40000 ALTER TABLE `dc_cats` DISABLE KEYS */;

INSERT INTO `dc_cats` (`catID`, `parentID`, `visibility`, `title`, `description`, `entries`)
VALUES
	(2,0,'client','Kullanıcı Dosyaları','Bu kategorideki dosyaları sadece kayıtlı kullanıcılar indirebilir',0),
	(1,0,'everyone','Genel Dosyalar','Bu dosyaları herkes indirebilir',0),
	(3,0,'admin','Gizli Dosyalar','Bu dosyaları sadece belli aktif siparişi olan kullanıcılar indirebilir',0);

/*!40000 ALTER TABLE `dc_cats` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dc_files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dc_files`;

CREATE TABLE `dc_files` (
  `fileID` mediumint(7) unsigned NOT NULL auto_increment,
  `catID` smallint(4) unsigned NOT NULL,
  `adminID` smallint(4) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `origname` varchar(100) NOT NULL,
  `sysname` varchar(100) NOT NULL,
  `size` varchar(32) NOT NULL,
  `extension` varchar(4) NOT NULL,
  `downloads` mediumint(7) unsigned NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`fileID`),
  KEY `catID` (`catID`),
  KEY `adminID` (`adminID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table departments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `departments`;

CREATE TABLE `departments` (
  `depID` smallint(3) unsigned NOT NULL auto_increment,
  `status` enum('active','inactive') NOT NULL default 'active',
  `depTitle` varchar(100) NOT NULL,
  `depEmail` varchar(100) NOT NULL,
  `notifyOnTicket` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`depID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;

INSERT INTO `departments` (`depID`, `status`, `depTitle`, `depEmail`, `notifyOnTicket`)
VALUES
	(1,'active','Satış','','0'),
	(2,'inactive','Muhasebe','','0'),
	(3,'active','Teknik Destek','','0'),
	(4,'active','Müşteri Hizmetleri','','0');

/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table domain_contact_registrar
# ------------------------------------------------------------

DROP TABLE IF EXISTS `domain_contact_registrar`;

CREATE TABLE `domain_contact_registrar` (
  `contactID` mediumint(8) unsigned NOT NULL,
  `moduleID` varchar(20) NOT NULL,
  `registrarID` varchar(20) NOT NULL,
  UNIQUE KEY `contactID` (`contactID`,`moduleID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table domain_contacts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `domain_contacts`;

CREATE TABLE `domain_contacts` (
  `domainID` mediumint(7) unsigned NOT NULL,
  `contactID` mediumint(7) unsigned NOT NULL,
  `type` varchar(20) NOT NULL,
  UNIQUE KEY `domainID` (`domainID`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table domain_extensions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `domain_extensions`;

CREATE TABLE `domain_extensions` (
  `serviceID` smallint(5) unsigned NOT NULL,
  `extension` varchar(15) NOT NULL,
  `periodMax` tinyint(3) unsigned NOT NULL default '1',
  `priceRegister` decimal(6,2) NOT NULL,
  `priceRenew` decimal(6,2) NOT NULL,
  `priceTransfer` decimal(6,2) NOT NULL,
  `status` enum('active','inactive') NOT NULL default 'active',
  `domlock` enum('0','1') NOT NULL default '0',
  `authcode` enum('0','1') NOT NULL default '0',
  `rowOrder` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`serviceID`),
  UNIQUE KEY `serviceID` (`serviceID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `domain_extensions` WRITE;
/*!40000 ALTER TABLE `domain_extensions` DISABLE KEYS */;

INSERT INTO `domain_extensions` (`serviceID`, `extension`, `periodMax`, `priceRegister`, `priceRenew`, `priceTransfer`, `status`, `domlock`, `authcode`, `rowOrder`)
VALUES
	(15,'com',5,5.00,5.00,0.00,'active','0','0',1),
	(16,'net',7,3.00,5.00,0.00,'active','0','0',2),
	(17,'org',10,5.00,5.00,0.00,'active','0','0',3),
	(18,'com.tr',1,20.00,20.00,20.00,'active','0','0',4),
	(52,'tk',10,5.00,5.00,5.00,'active','0','0',5);

/*!40000 ALTER TABLE `domain_extensions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table domains
# ------------------------------------------------------------

DROP TABLE IF EXISTS `domains`;

CREATE TABLE `domains` (
  `domainID` mediumint(8) unsigned NOT NULL auto_increment,
  `orderID` mediumint(8) unsigned NOT NULL,
  `domain` varchar(100) NOT NULL,
  `moduleID` varchar(30) NOT NULL,
  `status` enum('pending','active','expired','deleted','intransfer') NOT NULL default 'pending',
  `ns1` varchar(70) NOT NULL,
  `ns2` varchar(70) NOT NULL,
  `ns3` varchar(70) NOT NULL,
  `ns4` varchar(70) NOT NULL,
  `locked` enum('0','1') NOT NULL default '0',
  `dateReg` int(10) unsigned NOT NULL,
  `dateExp` int(10) unsigned NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`domainID`),
  KEY `orderID` (`orderID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `fileID` int(10) unsigned NOT NULL auto_increment,
  `fileType` enum('ticket','avatar','client','service') NOT NULL,
  `clientID` mediumint(8) unsigned NOT NULL,
  `sysname` varchar(255) NOT NULL,
  `origname` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `dateUploaded` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`fileID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table kb_cats
# ------------------------------------------------------------

DROP TABLE IF EXISTS `kb_cats`;

CREATE TABLE `kb_cats` (
  `catID` smallint(4) unsigned NOT NULL auto_increment,
  `parentID` smallint(4) unsigned NOT NULL default '0',
  `visibility` enum('everyone','client','admin') NOT NULL default 'everyone',
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `entries` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`catID`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

LOCK TABLES `kb_cats` WRITE;
/*!40000 ALTER TABLE `kb_cats` DISABLE KEYS */;

INSERT INTO `kb_cats` (`catID`, `parentID`, `visibility`, `title`, `description`, `entries`)
VALUES
	(25,23,'everyone','Alt Kategori','İstediğiniz sayıda altalta kategori ekleyebilirsiniz',0),
	(24,0,'everyone','Genel Kategori 2','Bu kategori ve altındakileri herkes görebilir',0),
	(26,0,'client','Kullanıcı Özel','Bu kategori ve altındakileri sadece kayıtlı kullanıcılar görebilir',1),
	(27,0,'admin','Yönetici Özel','Bu kategori ve altındakileri sadece yöneticiler görebilir',2),
	(23,0,'everyone','Genel Kategori 1','Bu kategori ve altındakileri herkes görebilir',1);

/*!40000 ALTER TABLE `kb_cats` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table kb_entries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `kb_entries`;

CREATE TABLE `kb_entries` (
  `entryID` mediumint(7) unsigned NOT NULL auto_increment,
  `catID` smallint(4) unsigned NOT NULL,
  `adminID` smallint(4) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `views` mediumint(7) unsigned NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`entryID`),
  KEY `catID` (`catID`),
  KEY `adminID` (`adminID`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

LOCK TABLES `kb_entries` WRITE;
/*!40000 ALTER TABLE `kb_entries` DISABLE KEYS */;

INSERT INTO `kb_entries` (`entryID`, `catID`, `adminID`, `title`, `body`, `views`, `dateAdded`, `dateUpdated`)
VALUES
	(9,26,1,'Kullanıcılara Özel Makale','<p>Sed in velit vitae felis porta consequat. Praesent sed rutrum nisl. Phasellus varius pulvinar leo, vel euismod nunc interdum ac. Nunc blandit diam vel ante interdum at ultrices diam laoreet. <br /><br />Praesent ligula nulla, fermentum nec ultrices ut, convallis faucibus massa. Nam imperdiet nibh quis lacus posuere ac ultrices lorem aliquet.</p>',0,1266459368,1266459368),
	(10,27,1,'Yöneticilere Özel Makale','<p>Sed in velit vitae felis porta consequat. Praesent sed rutrum nisl. Phasellus varius pulvinar leo, vel euismod nunc interdum ac. Nunc blandit diam vel ante interdum at ultrices diam laoreet. <br /><br /><em>Praesent ligula nulla, fermentum nec ultrices ut, convallis faucibus massa. Nam imperdiet nibh quis lacus posuere ac ultrices lorem aliquet. </em></p>',0,1266459413,1266459413),
	(11,27,1,'dasd','<p>asd</p>',0,1266459427,1266459427),
	(8,23,0,'Örnek Makale','<p>Sed in velit vitae felis porta consequat. Praesent sed rutrum nisl. Phasellus varius pulvinar leo, vel euismod nunc interdum ac. Nunc blandit diam vel ante interdum at ultrices diam laoreet. <br /><br />Praesent ligula nulla, fermentum nec ultrices ut, <span style=\"color: #ff0000;\">convallis faucibus</span> massa. Nam imperdiet nibh quis lacus posuere ac ultrices lorem aliquet.</p>',0,1266459263,1266459263);

/*!40000 ALTER TABLE `kb_entries` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table logs_sys
# ------------------------------------------------------------

DROP TABLE IF EXISTS `logs_sys`;

CREATE TABLE `logs_sys` (
  `logID` int(10) unsigned NOT NULL auto_increment,
  `label` varchar(50) NOT NULL,
  `type` enum('info','warning','error') NOT NULL default 'info',
  `message` text NOT NULL,
  `dateAdded` int(11) NOT NULL,
  PRIMARY KEY  (`logID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table object_history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `object_history`;

CREATE TABLE `object_history` (
  `recID` int(10) unsigned NOT NULL auto_increment,
  `objectID` varchar(128) character set latin5 NOT NULL,
  `subject` varchar(100) character set latin5 NOT NULL,
  `isadmin` enum('0','1') character set latin5 NOT NULL,
  `event` varchar(100) character set latin5 NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`recID`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;



# Dump of table order_attrs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_attrs`;

CREATE TABLE `order_attrs` (
  `orderID` mediumint(7) unsigned NOT NULL,
  `setting` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  `clientCanSee` enum('0','1') NOT NULL default '1',
  UNIQUE KEY `orderID` (`orderID`,`setting`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `order_attrs` WRITE;
/*!40000 ALTER TABLE `order_attrs` DISABLE KEYS */;

INSERT INTO `order_attrs` (`orderID`, `setting`, `value`, `clientCanSee`)
VALUES
	(7897384,'cpanel_bwlimit','2000','1'),
	(7897384,'cpanel_cgi','0','1'),
	(7897384,'cpanel_cpmod','',''),
	(7897384,'cpanel_frontpage','0','1'),
	(7897384,'cpanel_maxaddon','0','1'),
	(7897384,'cpanel_maxftp','','1'),
	(7897384,'cpanel_maxpark','1','1'),
	(7897384,'cpanel_maxpop','','1'),
	(7897384,'cpanel_maxsql','','1'),
	(7897384,'cpanel_maxsub','10','1'),
	(7897384,'cpanel_plan','',''),
	(7897384,'cpanel_quota','100','1'),
	(7897384,'domain','demodomain.com','1'),
	(7897384,'password','','1'),
	(7897384,'username','','1');

/*!40000 ALTER TABLE `order_attrs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table order_bills
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_bills`;

CREATE TABLE `order_bills` (
  `billID` int(10) unsigned NOT NULL,
  `parentID` int(10) unsigned NOT NULL,
  `orderID` int(10) unsigned NOT NULL,
  `clientID` mediumint(7) unsigned NOT NULL,
  `paymentID` mediumint(7) unsigned NOT NULL default '0',
  `status` enum('paid','unpaid') NOT NULL,
  `type` enum('recurring','onetime') NOT NULL default 'recurring',
  `amount` decimal(10,2) NOT NULL,
  `paycurID` tinyint(3) unsigned NOT NULL,
  `xamount` decimal(10,4) unsigned NOT NULL,
  `description` text NOT NULL,
  `mail_count` tinyint(2) unsigned NOT NULL,
  `dateDue` int(10) unsigned NOT NULL,
  `datePayed` int(10) unsigned NOT NULL,
  `dateStart` int(10) unsigned NOT NULL,
  `dateEnd` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`billID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `order_bills` WRITE;
/*!40000 ALTER TABLE `order_bills` DISABLE KEYS */;

INSERT INTO `order_bills` (`billID`, `parentID`, `orderID`, `clientID`, `paymentID`, `status`, `type`, `amount`, `paycurID`, `xamount`, `description`, `mail_count`, `dateDue`, `datePayed`, `dateStart`, `dateEnd`)
VALUES
	(3994293,0,7897384,0,0,'paid','recurring',10.00,1,0.0000,'',0,1266530400,1266536170,1266530400,1268949600),
	(1545164,0,7897384,0,0,'paid','onetime',5.00,1,0.0000,'Kurulum',0,1266530400,1266536170,1266530400,1266530400);

/*!40000 ALTER TABLE `order_bills` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table order_history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_history`;

CREATE TABLE `order_history` (
  `recID` int(11) unsigned NOT NULL auto_increment,
  `orderID` mediumint(7) unsigned default '0',
  `paymentID` mediumint(7) unsigned default NULL,
  `jobID` int(10) unsigned default NULL,
  `adminID` smallint(5) unsigned default '0',
  `action_type` enum('other','plugin') default NULL,
  `action` varchar(255) default NULL,
  `status` enum('error','success') default NULL,
  `description` varchar(255) default NULL,
  `dateAdded` int(10) unsigned default NULL,
  PRIMARY KEY  (`recID`),
  KEY `orderID` (`orderID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `orderID` mediumint(7) unsigned NOT NULL,
  `parentID` mediumint(7) unsigned NOT NULL default '0',
  `clientID` mediumint(7) unsigned NOT NULL,
  `serviceID` smallint(5) unsigned NOT NULL,
  `serverID` mediumint(7) unsigned NOT NULL default '0',
  `couponID` mediumint(8) unsigned NOT NULL default '0',
  `status` enum('pending-payment','pending-provision','active','suspended','deleted','inactive') NOT NULL default 'pending-payment',
  `autoSuspend` enum('0','1') NOT NULL default '1',
  `payType` enum('free','onetime','recurring') NOT NULL default 'recurring',
  `price` decimal(10,2) NOT NULL,
  `paycurID` tinyint(3) unsigned NOT NULL,
  `period` tinyint(4) unsigned NOT NULL default '0',
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `dateStart` int(10) NOT NULL,
  `dateEnd` int(10) NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`orderID`),
  KEY `clientID` (`clientID`),
  KEY `serviceID` (`serviceID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;

INSERT INTO `orders` (`orderID`, `parentID`, `clientID`, `serviceID`, `serverID`, `couponID`, `status`, `autoSuspend`, `payType`, `price`, `paycurID`, `period`, `title`, `description`, `dateStart`, `dateEnd`, `dateAdded`, `dateUpdated`)
VALUES
	(7897384,0,3339220,1,7,0,'active','1','recurring',10.00,1,1,'cPanel Hosting (demodomain.com)','',1266530400,1268949600,1266536170,0);

/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `pageID` smallint(3) unsigned NOT NULL auto_increment,
  `parentID` smallint(3) unsigned NOT NULL,
  `moduleID` tinyint(4) NOT NULL,
  `filename` varchar(150) NOT NULL,
  `showOnSubmenu` enum('0','1') NOT NULL default '1',
  `rowOrder` tinyint(3) unsigned NOT NULL default '10',
  `actions` varchar(16) NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`pageID`)
) ENGINE=MyISAM AUTO_INCREMENT=711 DEFAULT CHARSET=utf8;

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;

INSERT INTO `pages` (`pageID`, `parentID`, `moduleID`, `filename`, `showOnSubmenu`, `rowOrder`, `actions`, `dateAdded`, `dateUpdated`)
VALUES
	(110,0,1,'admins.php','1',15,'1234',0,0),
	(111,110,1,'admin_details.php','0',0,'1234',1259692847,1263905646),
	(112,0,1,'admin_settings.php','0',0,'1234',1259692847,1263905646),
	(115,0,1,'services.php','1',30,'12345',0,1264570843),
	(116,115,1,'service_details.php','0',0,'1234',1259954625,1259957239),
	(117,0,1,'service_group_details.php','0',35,'1234',1259692847,1265224240),
	(120,0,1,'service_attrs.php','0',50,'1234',0,1271896101),
	(125,0,1,'servers.php','1',130,'1234',0,0),
	(130,0,1,'pages.php','0',70,'1234',0,1271896127),
	(135,0,1,'email_templates.php','1',80,'1234',1259187266,1259187266),
	(140,0,1,'general.php','1',10,'1234',1259595627,1259595627),
	(145,0,1,'domains.php','1',100,'1234',1259692847,1259692847),
	(150,0,1,'server_probes.php','0',140,'1234',1259692847,1271896110),
	(165,0,1,'currencies.php','1',20,'1234',1259692847,1259692847),
	(170,0,1,'modules.php','1',80,'1234',1259692847,1259692847),
	(175,0,1,'departments.php','1',120,'1234',1259692847,1259692847),
	(176,0,1,'department_details.php','0',0,'1234',1259692847,1259692847),
	(210,0,2,'tickets.php','1',100,'1234',0,0),
	(211,0,2,'ticket_search.php','1',100,'14',0,0),
	(212,0,2,'ticket_details.php','0',100,'1234',0,0),
	(215,0,2,'chat.php','1',100,'12345',0,1264570249),
	(310,0,3,'clients.php','1',100,'1234',0,0),
	(311,310,3,'client_details.php','0',100,'1234',0,0),
	(312,0,3,'add_client.php','1',100,'1234',0,0),
	(410,0,4,'orders.php','1',100,'1234',0,0),
	(411,410,4,'order_details.php','0',100,'12346',0,1264570530),
	(412,0,4,'add_order.php','0',100,'1234',0,1263776244),
	(415,0,4,'addon_details.php','0',255,'1234',0,1264357782),
	(420,0,4,'domains.php','1',255,'1234',0,0),
	(510,0,5,'payments.php','1',100,'1234',0,0),
	(511,510,5,'payment_details.php','0',200,'1234',0,0),
	(515,0,5,'bills.php','1',255,'1234',0,0),
	(516,515,5,'bill_details.php','0',255,'1234',0,0),
	(610,0,6,'logs.php','1',100,'1234',0,0),
	(615,0,6,'queue.php','1',100,'1234',0,0),
	(620,0,6,'whm_import.php','1',200,'1',0,0),
	(625,0,6,'plesk_import.php','1',210,'1',0,0),
	(220,0,2,'kb.php','1',150,'1234',0,1264570249),
	(225,0,2,'announcements.php','1',200,'1234',0,1264570249),
	(180,0,1,'custom_fields.php','1',150,'1234',1259692847,1259692847),
	(230,0,2,'downloads.php','1',150,'1234',0,1264570249),
	(630,0,6,'domain_import.php','1',220,'1',0,0),
	(635,0,6,'license_info.php','1',230,'1',0,0),
	(640,0,6,'mass_mail.php','1',250,'1',0,0),
	(710,0,7,'tib_csv.php','1',10,'1',0,0),
	(185,0,1,'shop.php','1',160,'1234',1259692847,1259692847),
	(177,0,1,'coupons.php','1',170,'1234',1259692847,1259692847),
	(178,0,1,'coupon_details.php','0',0,'1234',1259692847,1259692847),
	(631,0,6,'domain_import_generic.php','1',225,'1',0,0);

/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table payments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `payments`;

CREATE TABLE `payments` (
  `paymentID` mediumint(7) unsigned NOT NULL,
  `clientID` mediumint(8) unsigned NOT NULL,
  `moduleID` varchar(20) NOT NULL,
  `adminID` smallint(5) unsigned NOT NULL default '0',
  `paymentStatus` enum('pending-payment','pending-approval','paid','notfound') NOT NULL default 'pending-payment',
  `datePayed` int(10) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paycurID` tinyint(4) unsigned NOT NULL,
  `xamount` decimal(10,4) NOT NULL,
  `description` varchar(100) NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`paymentID`),
  KEY `clientID` (`clientID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table queue
# ------------------------------------------------------------

DROP TABLE IF EXISTS `queue`;

CREATE TABLE `queue` (
  `jobID` int(10) unsigned NOT NULL auto_increment,
  `orderID` mediumint(7) unsigned NOT NULL,
  `paymentID` mediumint(7) unsigned NOT NULL default '0',
  `status` enum('inprocess','pending','pending-cron','pending-payment','completed','error','scheduled') NOT NULL default 'pending',
  `code` varchar(64) NOT NULL,
  `job` varchar(50) NOT NULL,
  `params` longtext NOT NULL,
  `result` text NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  `dateFire` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`jobID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table server_probes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `server_probes`;

CREATE TABLE `server_probes` (
  `probeID` smallint(5) unsigned NOT NULL auto_increment,
  `serverID` smallint(5) unsigned NOT NULL,
  `status` enum('on','off') NOT NULL default 'off',
  `title` varchar(20) NOT NULL,
  `port` smallint(5) NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  `dateNotified` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`probeID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

LOCK TABLES `server_probes` WRITE;
/*!40000 ALTER TABLE `server_probes` DISABLE KEYS */;

INSERT INTO `server_probes` (`probeID`, `serverID`, `status`, `title`, `port`, `dateAdded`, `dateUpdated`, `dateNotified`)
VALUES
	(8,7,'on','http',80,1263399625,1304381701,0),
	(7,7,'on','smtp',25,1263340357,1304381701,0);

/*!40000 ALTER TABLE `server_probes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table server_settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `server_settings`;

CREATE TABLE `server_settings` (
  `serverID` smallint(5) unsigned NOT NULL,
  `setting` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `encrypted` enum('0','1') NOT NULL default '0',
  UNIQUE KEY `serverID` (`serverID`,`setting`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `server_settings` WRITE;
/*!40000 ALTER TABLE `server_settings` DISABLE KEYS */;

INSERT INTO `server_settings` (`serverID`, `setting`, `value`, `encrypted`)
VALUES
	(14,'use_ssl','','0'),
	(7,'ns1','ns1.localhost','0'),
	(7,'ns2','ns2.localhost','0'),
	(7,'ns1_ip','127.0.0.1','0'),
	(7,'ns2_ip','127.0.0.2','0'),
	(7,'load_monitor','','0'),
	(7,'cpu_count','','0'),
	(7,'critical_load','','0'),
	(7,'use_ssl','','0'),
	(7,'auth','pass','0'),
	(7,'server_hash','','1');

/*!40000 ALTER TABLE `server_settings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table servers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `servers`;

CREATE TABLE `servers` (
  `serverID` smallint(5) unsigned NOT NULL auto_increment,
  `moduleID` varchar(20) NOT NULL,
  `status` enum('active','inactive') NOT NULL default 'active',
  `serverName` varchar(50) NOT NULL,
  `mainIp` varchar(15) NOT NULL,
  `hostname` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(256) NOT NULL,
  `loadavg` varchar(100) NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`serverID`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

LOCK TABLES `servers` WRITE;
/*!40000 ALTER TABLE `servers` DISABLE KEYS */;

INSERT INTO `servers` (`serverID`, `moduleID`, `status`, `serverName`, `mainIp`, `hostname`, `username`, `password`, `loadavg`, `dateAdded`, `dateUpdated`)
VALUES
	(7,'cpanel','active','Demo Server','127.0.0.1','localhost','','','',1263340334,1266536242),
	(12,'cpanel','active','Demo Cpanel Server','','','','','',1265244548,1265244548),
	(13,'plesk','active','Demo PLESK Server','','','','','',1265244561,1265244563),
	(14,'directadmin','active','Demo DirectAdmin Server','','','','','',1265768990,1265769000);

/*!40000 ALTER TABLE `servers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table service_attr_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `service_attr_types`;

CREATE TABLE `service_attr_types` (
  `settingID` smallint(5) unsigned NOT NULL auto_increment,
  `groupID` smallint(3) unsigned NOT NULL,
  `moduleID` varchar(25) NOT NULL,
  `valueBy` enum('client','service','module') NOT NULL default 'service',
  `setting` varchar(50) NOT NULL,
  `label` varchar(100) NOT NULL,
  `type` enum('textbox','textarea','checkbox','combobox','db','hidden','server') NOT NULL default 'textbox',
  `options` text NOT NULL,
  `description` text NOT NULL,
  `width` smallint(3) unsigned NOT NULL,
  `height` smallint(3) unsigned NOT NULL,
  `encrypted` enum('0','1') NOT NULL default '0',
  `validation` varchar(100) NOT NULL,
  `validation_info` text NOT NULL,
  PRIMARY KEY  (`settingID`),
  UNIQUE KEY `attr` (`setting`)
) ENGINE=MyISAM AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;

LOCK TABLES `service_attr_types` WRITE;
/*!40000 ALTER TABLE `service_attr_types` DISABLE KEYS */;

INSERT INTO `service_attr_types` (`settingID`, `groupID`, `moduleID`, `valueBy`, `setting`, `label`, `type`, `options`, `description`, `width`, `height`, `encrypted`, `validation`, `validation_info`)
VALUES
	(1,1,'','module','username','Kullanıcı Adı','textbox','','',200,0,'0','^[a-zA-Z][a-zA-Z0-9_]{4,12}$','Kullanıcı adı en az 4, en fazla 12 karakter olmalı, harf ile başlamalı ve sadece latin harfleri ile rakamlardan oluşmalıdır.'),
	(2,1,'','module','password','Şifre','textbox','','',200,0,'1','^(?=.*[0-9]+.*)(?=.*[a-zA-Z]+.*)[0-9a-zA-Z]{6,}$','Şifre en az 1 harf, en az 1 rakam içermeli ve en az 6 karakterden oluşmalıdır.'),
	(5,1,'','client','domain','Alan Adı','textbox','','',200,0,'0','^[a-z0-9-]+(\\.[a-z0-9-]+)*(\\.[a-z]{2,4})$','Alan adı abc.com veya xxx.abc.com formatında olmalıdır'),
	(17,1,'','service','vps_ram','RAM','textbox','','',50,0,'1','',''),
	(18,1,'','service','vps_cpu','CPU','textbox','','',50,0,'0','',''),
	(107,1,'','client','ethernet','Ethernet Baglanti Hizi','combobox','100=>100mbps,200=>200mbps,300=>300mbps','Sunucunun Hızını Belirtir',200,0,'0','','');

/*!40000 ALTER TABLE `service_attr_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table service_attrs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `service_attrs`;

CREATE TABLE `service_attrs` (
  `attrID` smallint(5) unsigned NOT NULL auto_increment,
  `serviceID` smallint(5) unsigned NOT NULL,
  `source` enum('module','custom','custom-locked') NOT NULL default 'module',
  `setting` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  `clientCanSee` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`attrID`),
  UNIQUE KEY `serviceID` (`serviceID`,`setting`)
) ENGINE=MyISAM AUTO_INCREMENT=1026 DEFAULT CHARSET=utf8;

LOCK TABLES `service_attrs` WRITE;
/*!40000 ALTER TABLE `service_attrs` DISABLE KEYS */;

INSERT INTO `service_attrs` (`attrID`, `serviceID`, `source`, `setting`, `value`, `clientCanSee`)
VALUES
	(998,12,'module','cpanel_enable_resource_limits','1',''),
	(997,12,'module','cpanel_bandwidth_limit','666','1'),
	(928,32,'custom-locked','domain','','1'),
	(927,32,'custom-locked','password','','1'),
	(926,32,'custom-locked','username','','1'),
	(929,27,'module','plesk_disk_space','333','1'),
	(930,27,'module','plesk_max_traffic','','1'),
	(925,32,'module','plesk_max_dom','','1'),
	(924,32,'module','plesk_max_dom_aliases','8','1'),
	(923,32,'module','plesk_max_subdom','7','1'),
	(931,40,'module','plesk_disk_space','','1'),
	(932,40,'module','plesk_max_traffic','444','1'),
	(996,12,'module','cpanel_diskspace_limit','333','1'),
	(995,12,'module','cpanel_account_limit','4','1'),
	(994,12,'module','cpanel_acllist','',''),
	(975,33,'module','plesk_max_dom','3','1'),
	(976,33,'custom-locked','username','','1'),
	(977,33,'custom-locked','password','','1'),
	(978,33,'custom-locked','domain','','1'),
	(922,32,'module','plesk_max_box','6','1'),
	(921,32,'module','plesk_max_mssql_db','','1'),
	(904,15,'custom','vps_cpu','11','1'),
	(902,1,'custom-locked','domain','','1'),
	(901,1,'custom-locked','password','','1'),
	(900,1,'custom-locked','username','','1'),
	(898,1,'module','cpanel_frontpage','0','1'),
	(897,1,'module','cpanel_cgi','0','1'),
	(896,1,'module','cpanel_maxsub','10','1'),
	(895,1,'module','cpanel_maxpop','','1'),
	(894,1,'module','cpanel_maxsql','','1'),
	(920,32,'module','plesk_max_db','5','1'),
	(919,32,'module','plesk_max_traffic','600','1'),
	(918,32,'module','plesk_disk_space','150','1'),
	(917,32,'module','plesk_template_name','','1'),
	(905,41,'module','cpanel_quota','1024','1'),
	(906,41,'module','cpanel_bwlimit','','1'),
	(999,12,'module','cpanel_enable_overselling_bandwidth','1',''),
	(979,47,'module','plesk_disk_space','','1'),
	(980,47,'module','plesk_max_traffic','1000','1'),
	(992,12,'module','cpanel_frontpage','0','1'),
	(991,12,'module','cpanel_cgi','0','1'),
	(990,12,'module','cpanel_maxsub','','1'),
	(989,12,'module','cpanel_maxpop','','1'),
	(988,12,'module','cpanel_maxsql','','1'),
	(987,12,'module','cpanel_maxftp','','1'),
	(986,12,'module','cpanel_maxaddon','','1'),
	(985,12,'module','cpanel_maxpark','','1'),
	(984,12,'module','cpanel_bwlimit','222','1'),
	(983,12,'module','cpanel_quota','111','1'),
	(982,12,'module','cpanel_cpmod','',''),
	(981,12,'module','cpanel_plan','',''),
	(893,1,'module','cpanel_maxftp','','1'),
	(892,1,'module','cpanel_maxaddon','0','1'),
	(891,1,'module','cpanel_maxpark','1','1'),
	(890,1,'module','cpanel_bwlimit','2000','1'),
	(889,1,'module','cpanel_quota','100','1'),
	(888,1,'module','cpanel_cpmod','',''),
	(887,1,'module','cpanel_plan','',''),
	(914,45,'module','cpanel_quota','','1'),
	(915,45,'module','cpanel_bwlimit','1024','1'),
	(972,33,'module','plesk_template_name','','1'),
	(973,33,'module','plesk_disk_space','555','1'),
	(974,33,'module','plesk_max_traffic','777','1'),
	(946,46,'module','plesk_disk_space','1000','1'),
	(947,46,'module','plesk_max_traffic','','1'),
	(1000,12,'module','cpanel_enable_overselling_diskspace','1',''),
	(1001,12,'custom-locked','username','','1'),
	(1002,12,'custom-locked','password','','1'),
	(1003,12,'custom-locked','domain','','1'),
	(1004,48,'module','cpanel_quota','','1'),
	(1005,48,'module','cpanel_bwlimit','','1'),
	(1006,48,'module','cpanel_diskspace_limit','1000','1'),
	(1007,48,'module','cpanel_bandwidth_limit','','1'),
	(1008,49,'module','cpanel_quota','','1'),
	(1009,49,'module','cpanel_bwlimit','','1'),
	(1010,49,'module','cpanel_diskspace_limit','','1'),
	(1011,49,'module','cpanel_bandwidth_limit','1000','1'),
	(1012,50,'module','directadmin_package','','1'),
	(1013,50,'module','directadmin_quota','100','1'),
	(1014,50,'module','directadmin_bandwidth','500','1'),
	(1015,50,'module','directadmin_vdomains','1','1'),
	(1016,50,'custom-locked','username','','1'),
	(1017,50,'custom-locked','password','','1'),
	(1018,50,'custom-locked','domain','','1'),
	(1019,51,'module','directadmin_package','','1'),
	(1020,51,'module','directadmin_quota','1000','1'),
	(1021,51,'module','directadmin_bandwidth','5000','1'),
	(1022,51,'module','directadmin_vdomains','10','1'),
	(1023,51,'custom-locked','username','','1'),
	(1024,51,'custom-locked','password','','1'),
	(1025,51,'custom-locked','domain','','1');

/*!40000 ALTER TABLE `service_attrs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table service_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `service_groups`;

CREATE TABLE `service_groups` (
  `groupID` smallint(5) unsigned NOT NULL auto_increment,
  `parentID` smallint(5) unsigned NOT NULL,
  `status` enum('active','inactive') NOT NULL default 'active',
  `group_name` varchar(200) NOT NULL,
  `seolink` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `rowOrder` smallint(3) unsigned NOT NULL,
  PRIMARY KEY  (`groupID`)
) ENGINE=MyISAM AUTO_INCREMENT=109 DEFAULT CHARSET=utf8;

LOCK TABLES `service_groups` WRITE;
/*!40000 ALTER TABLE `service_groups` DISABLE KEYS */;

INSERT INTO `service_groups` (`groupID`, `parentID`, `status`, `group_name`, `seolink`, `description`, `rowOrder`)
VALUES
	(10,1,'active','Alan Adı Hizmetleri','alan-adi-hizmetleri','',1),
	(1,0,'active','Genel','genel','',0),
	(108,1,'active','Windows Reseller Hosting','windows-reseller-hosting','',5),
	(107,1,'active','Linux Reseller Hosting','linux-reseller-hosting','',4),
	(30,1,'active','Windows Web Hosting','windows-web-hosting','',3),
	(20,1,'active','Linux Web Hosting','linux-web-hosting','<p><strong>Linux Hosting          Paketleri (PHP, MYSQL)</strong><br /> Centos sunucularımızda php kullanmanız için uygun paketlerdir.           Veritabanı olarak mysql kullanabilirsiniz. Ayrıca sunucumuzda gd , curl           , ioncube, freetype ve zend bileşenlerini de kullanabilirsiniz.</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<hr />\r\n<p>&nbsp;</p>',2);

/*!40000 ALTER TABLE `service_groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table service_price_options
# ------------------------------------------------------------

DROP TABLE IF EXISTS `service_price_options`;

CREATE TABLE `service_price_options` (
  `serviceID` smallint(5) unsigned NOT NULL,
  `period` tinyint(2) unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `default` enum('0','1') NOT NULL default '0',
  UNIQUE KEY `serviceID` (`serviceID`,`period`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `service_price_options` WRITE;
/*!40000 ALTER TABLE `service_price_options` DISABLE KEYS */;

INSERT INTO `service_price_options` (`serviceID`, `period`, `price`, `discount`, `default`)
VALUES
	(1,1,10.00,0.00,'1'),
	(1,3,20.00,0.00,'0'),
	(12,1,10.00,0.00,'1'),
	(32,3,40.00,0.00,'1'),
	(41,1,10.00,0.00,'1'),
	(45,1,5.00,0.00,'1'),
	(27,1,5.00,0.00,'1'),
	(40,1,6.00,0.00,'1'),
	(33,1,10.00,0.00,'1'),
	(46,1,10.00,0.00,'1'),
	(47,1,11.00,0.00,'1'),
	(48,1,5.00,0.00,'1'),
	(49,1,3.00,0.00,'1'),
	(50,1,10.00,0.00,'1'),
	(51,1,30.00,0.00,'1');

/*!40000 ALTER TABLE `service_price_options` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table services
# ------------------------------------------------------------

DROP TABLE IF EXISTS `services`;

CREATE TABLE `services` (
  `serviceID` smallint(5) unsigned NOT NULL auto_increment,
  `groupID` smallint(5) unsigned NOT NULL,
  `serverID` smallint(5) unsigned NOT NULL,
  `paycurID` tinyint(3) unsigned NOT NULL,
  `moduleID` varchar(20) NOT NULL,
  `templateID` smallint(3) unsigned NOT NULL,
  `settingID` smallint(5) unsigned NOT NULL,
  `status` enum('active','inactive') NOT NULL default 'inactive',
  `type` enum('shared','reseller','product','service','domain') NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `seolink` varchar(100) NOT NULL,
  `provisionType` enum('auto','manual') NOT NULL default 'manual',
  `moduleCmd` varchar(255) NOT NULL,
  `addon` enum('0','1') NOT NULL default '0',
  `description` text NOT NULL,
  `details` text NOT NULL,
  `notifyOnOrderDepID` smallint(3) NOT NULL default '0',
  `setup` decimal(10,2) unsigned NOT NULL default '0.00',
  `setup_discount` decimal(10,2) NOT NULL,
  `file_cats` varchar(255) NOT NULL,
  `has_support` enum('0','1') NOT NULL default '0',
  `expires` varchar(10) NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  `rowOrder` smallint(5) unsigned NOT NULL,
  `sfOrder` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`serviceID`),
  KEY `groupID` (`groupID`),
  KEY `moduleID` (`moduleID`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;

INSERT INTO `services` (`serviceID`, `groupID`, `serverID`, `paycurID`, `moduleID`, `templateID`, `settingID`, `status`, `type`, `service_name`, `seolink`, `provisionType`, `moduleCmd`, `addon`, `description`, `details`, `notifyOnOrderDepID`, `setup`, `setup_discount`, `file_cats`, `has_support`, `expires`, `dateAdded`, `dateUpdated`, `rowOrder`, `sfOrder`)
VALUES
	(1,20,12,1,'cpanel',15,5,'active','shared','cPanel Hosting','cpanel-hosting','auto','','0','<strong>+ 10 GB</strong> Disk Alanı<br><strong>+ 100 GB</strong> Aylık \r\nTrafik<br><strong>+ Sınırsız</strong> Domain Ekleme<br><strong>+ \r\nSınırsız</strong> MySQL Veritabanı Hesabı<br><strong>+ Sınırsız</strong>\r\n E-Posta Hesabı','<strong>Linux Hosting          Paketleri (PHP, MYSQL)</strong><br> \r\nCentos sunucularımızda php kullanmanız için uygun paketlerdir.          \r\nVeritabanı olarak mysql kullanabilirsiniz. Ayrıca sunucumuzda gd , curl \r\n         , ioncube, freetype ve zend bileşenlerini de kullanabilirsiniz.',0,5.00,0.00,'','0','',0,0,1,10),
	(27,30,0,1,'plesk',0,0,'active','shared','Ekstra Disk Alanı 1GB  - PLESK','','auto','setDiskQuota','1','','',0,0.00,0.00,'','0','',0,0,0,0),
	(15,10,0,1,'',0,0,'active','domain','.com Alan Adı Tescil Hizmeti','','auto','','0','','',0,0.00,0.00,'','0','',0,0,1,0),
	(12,107,12,1,'cpanel',16,5,'active','reseller','cPanel Reseller Hosting','cpanel-reseller-hosting','auto','','0','<strong>+ 10 GB</strong> Disk Alanı<br><strong>+ 100 GB</strong> Aylık \r\nTrafik<br><strong>+ Sınırsız</strong> Domain Ekleme<br><strong>+ \r\nSınırsız</strong> MySQL Veritabanı Hesabı<br><strong>+ Sınırsız</strong>\r\n E-Posta Hesabı','',0,0.00,0.00,'','0','',0,0,1,40),
	(17,10,0,1,'',0,0,'active','domain','.org Alan Adı Tescil Hizmeti','','auto','','0','','',0,0.00,0.00,'','0','',0,0,2,0),
	(16,10,0,1,'',0,0,'active','domain','.net Alan Adı Tescil Hizmeti','','auto','','0','','',0,0.00,0.00,'','0','',0,0,3,0),
	(18,10,0,1,'',0,0,'active','domain','.com.tr Alan Adı Tescil Hizmeti','','auto','','0','','',0,0.00,0.00,'','0','',0,0,4,0),
	(32,30,13,1,'plesk',0,5,'active','shared','Plesk Hosting','plesk-hosting','auto','','0','<strong>+ 10 GB</strong> Disk Alanı<br><strong>+ 100 GB</strong> Aylık \r\nTrafik<br><strong>+ Sınırsız</strong> Domain Ekleme<br><strong>+ \r\nSınırsız</strong> MySQL Veritabanı Hesabı<br><strong>+ Sınırsız</strong>\r\n E-Posta Hesabı','<strong>Linux Hosting          Paketleri (PHP, MYSQL)</strong><br> \r\nCentos sunucularımızda php kullanmanız için uygun paketlerdir.          \r\nVeritabanı olarak mysql kullanabilirsiniz. Ayrıca sunucumuzda gd , curl \r\n         , ioncube, freetype ve zend bileşenlerini de kullanabilirsiniz.',0,6.00,0.00,'','0','',0,0,1,20),
	(33,108,13,1,'plesk',0,5,'active','reseller','Plesk Reseller Hosting','plesk-reseller-hosting','auto','','0','<strong>+ 10 GB</strong> Disk Alanı<br><strong>+ 100 GB</strong> Aylık \r\nTrafik<br><strong>+ Sınırsız</strong> Domain Ekleme<br><strong>+ \r\nSınırsız</strong> MySQL Veritabanı Hesabı<br><strong>+ Sınırsız</strong>\r\n E-Posta Hesabı','',0,0.00,0.00,'','0','',0,0,1,50),
	(40,30,0,1,'plesk',0,0,'active','shared','Ekstra Web Trafik 1GB - PLESK','','auto','setTraffic','1','','',0,0.00,0.00,'','0','',0,0,0,0),
	(41,20,0,1,'cpanel',0,0,'active','shared','Ekstra Disk Alanı 1GB - cPanel','','auto','setDiskQuota','1','','',0,0.00,0.00,'','0','',0,0,0,0),
	(45,20,0,1,'cpanel',0,0,'active','shared','Ekstra Web Trafik  1GB - cPanel','','auto','setTraffic','1','','',0,0.00,0.00,'','0','',0,0,0,0),
	(46,108,0,1,'plesk',0,0,'active','reseller','Ekstra Disk Alanı 1GB - PLESK Reseller','','auto','setDiskQuota','1','','',0,5.00,0.00,'','0','',0,0,0,0),
	(47,108,0,1,'plesk',0,0,'active','reseller','Ekstra Web Trafik  1GB - PLESK Reseller','','auto','setTraffic','1','','',0,4.00,0.00,'','0','',0,0,0,0),
	(48,107,0,1,'cpanel',0,0,'active','reseller','Ekstra Disk Alanı 1GB - cPanel Reseller','','auto','setDiskQuota','1','','',0,0.00,0.00,'','0','',0,0,0,0),
	(49,107,0,1,'cpanel',0,0,'active','reseller','Ekstra Web Trafik  1GB - cPanel Reseller','','auto','setTraffic','1','','',0,0.00,0.00,'','0','',0,0,0,0),
	(50,20,14,1,'directadmin',0,5,'active','shared','DirectAdmin Hosting','directadmin-hosting','auto','','0','<strong>+ 10 GB</strong> Disk Alanı<br><strong>+ 100 GB</strong> Aylık \r\nTrafik<br><strong>+ Sınırsız</strong> Domain Ekleme<br><strong>+ \r\nSınırsız</strong> MySQL Veritabanı Hesabı<br><strong>+ Sınırsız</strong>\r\n E-Posta Hesabı','<strong>Linux Hosting          Paketleri (PHP, MYSQL)</strong><br> \r\nCentos sunucularımızda php kullanmanız için uygun paketlerdir.          \r\nVeritabanı olarak mysql kullanabilirsiniz. Ayrıca sunucumuzda gd , curl \r\n         , ioncube, freetype ve zend bileşenlerini de kullanabilirsiniz.',0,0.00,0.00,'','0','',0,0,2,30),
	(51,107,14,1,'directadmin',0,5,'active','reseller','DirectAdmin Reseller Hosting','directadmin-reseller-hosting','auto','','0','<strong>+ 10 GB</strong> Disk Alanı<br><strong>+ 100 GB</strong> Aylık \r\nTrafik<br><strong>+ Sınırsız</strong> Domain Ekleme<br><strong>+ \r\nSınırsız</strong> MySQL Veritabanı Hesabı<br><strong>+ Sınırsız</strong>\r\n E-Posta Hesabı','',0,0.00,0.00,'','0','',0,0,2,60),
	(52,10,0,1,'',0,0,'active','domain','.tk Alan Adı Tescil Hizmeti','','auto','','0','','',0,0.00,0.00,'','0','',0,0,5,0);

/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table settings_banners
# ------------------------------------------------------------

DROP TABLE IF EXISTS `settings_banners`;

CREATE TABLE `settings_banners` (
  `bannerID` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `title_size` varchar(2) NOT NULL,
  `title_color` varchar(6) NOT NULL,
  `spot` text NOT NULL,
  `spot_size` varchar(2) NOT NULL,
  `spot_color` varchar(6) NOT NULL,
  `url` varchar(255) NOT NULL,
  `trans_type` enum('1','2','3','4','5') NOT NULL default '1',
  `rowOrder` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`bannerID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `settings_banners` WRITE;
/*!40000 ALTER TABLE `settings_banners` DISABLE KEYS */;

INSERT INTO `settings_banners` (`bannerID`, `title`, `title_size`, `title_color`, `spot`, `spot_size`, `spot_color`, `url`, `trans_type`, `rowOrder`)
VALUES
	(1,'Çoklu Kur Desteği','25','585858','İstediğiniz ürünlerin satışını farklı kurlar ile yapabilir, ödemelerinizi farklı kurla alabilirsiniz','20','585858','','1',1),
	(2,'Otomatik Kur Güncelleyici','25','585858','İstediğiniz araklılar ile sistemin kur bilgileri otomatik olarak güncellenir','20','585858','','1',2),
	(3,'cPanel, PLESK, DirectAdmin, WHMSONIC, HyperVM Servis Modülleri','25','585858','Bu sunucular ile tam entegrasyon, otomatik hesap açma, kapama, askıya alma, trafik ve disk kotaları güncelleme','20','585858','','2',3),
	(4,'Ödeme Modülleri','25','585858','Ödeme modülleri ile, müşteriniz ödemesini online yaptığı anda, hesaplar otomatik olarak açılır.','20','585858','','3',4);

/*!40000 ALTER TABLE `settings_banners` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table settings_currencies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `settings_currencies`;

CREATE TABLE `settings_currencies` (
  `curID` tinyint(3) unsigned NOT NULL auto_increment,
  `status` enum('active','inactive') NOT NULL,
  `ratio` decimal(9,5) NOT NULL default '1.00000',
  `code` varchar(5) NOT NULL,
  `symbol` varchar(5) NOT NULL,
  `description` varchar(100) NOT NULL,
  `rowOrder` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`curID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `settings_currencies` WRITE;
/*!40000 ALTER TABLE `settings_currencies` DISABLE KEYS */;

INSERT INTO `settings_currencies` (`curID`, `status`, `ratio`, `code`, `symbol`, `description`, `rowOrder`)
VALUES
	(1,'active',1.00000,'TRY','TL','Türk Lirası',1),
	(2,'inactive',1.53600,'USD','$','United States Dollars',2),
	(3,'inactive',2.09740,'EUR','€','European Union Currency',3),
	(4,'inactive',2.30600,'GBP','£','British Pound',4);

/*!40000 ALTER TABLE `settings_currencies` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table settings_email_templates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `settings_email_templates`;

CREATE TABLE `settings_email_templates` (
  `templateID` smallint(5) unsigned NOT NULL auto_increment,
  `type` enum('domain','finance','support','order','user','welcome','custom') NOT NULL,
  `title` varchar(150) NOT NULL,
  `language` varchar(2) NOT NULL default 'tr',
  `fromName` varchar(100) NOT NULL,
  `fromEmail` varchar(100) NOT NULL,
  `copyTo` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `sms` varchar(160) NOT NULL,
  `variables` text NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`templateID`)
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;

LOCK TABLES `settings_email_templates` WRITE;
/*!40000 ALTER TABLE `settings_email_templates` DISABLE KEYS */;

INSERT INTO `settings_email_templates` (`templateID`, `type`, `title`, `language`, `fromName`, `fromEmail`, `copyTo`, `subject`, `body`, `sms`, `variables`, `dateAdded`, `dateUpdated`)
VALUES
	(14,'order','Sipariş Onayı','tr','Vizra Teknik Destek','','','Siparişiniz Alındı','<p>Sayın {$Client_name},</p><p>{$Order_orderID} - {$Service_service_name} siparişiniz sistemimize kaydedilmiştir.<br><br>Siparişinize bağlı olan servis aktif edildiği zaman, detayları içeren bir email daha alacaksınız.</p>\r\n<p>Siparişinizin son durumu: {$Order_status}</p>','','',1263072311,1272161767),
	(3,'finance','Ödeme Onayı','tr','Vizra Finans Departmanı','','','Ödemeniz Onaylandı','<p>Sayın {$Client_name},</p><p>{$Payment_dateAdded} tarihinde sistemimize girilmiş olan {$Payment_amount} {$Payment_paycurID} tutarındaki ödemeniz onaylanarak bakiyenize eklenmiştir.</p>','','',0,1272161866),
	(10,'domain','Alan Adı Tescili','tr','Vizra Teknik Destek','','','Alan adınız tescil edildi','<p>Sayın {$Client_name}</p>\r\n<p>{$Domain_domain} alan adınız başarı ile tescil edilmiştir.</p>\r\n<p>Alan adınızın bitiş tarihi: {$Domain_dateExp}</p>','','',1263059577,1272161907),
	(7,'support','Biletiniz kapandı','tr','','','','Biletiniz kapandı','','','',1259266937,1265430216),
	(8,'support','Bilete Cevap Verildi','tr','Vizra Teknik Destek','','','{$Ticket_ticketID} numaralı biletinize cevap verildi','<p>Sayın Müşterimiz,</p>\r\n<p>Sistemimize girmiş olduğunuz <strong>{$Ticket_subject}</strong> konulu bilete cevap verildi.</p>\r\n<p>{$vurl} adresinden hesabınıza giriş yaparak biletinize verilen cevabı görüntüleyebilirsiniz.</p>\r\n<p><a href=\"%7B$vurl%7D?p=user&amp;s=support&amp;a=viewTicket&amp;tID=%7B$Ticket_ticketID%7D\">Detayları görüntülemek buraya tıklayınız</a></p>\r\n<p>Teşekkür eder, iyi çalışmalar dileriz.</p>\r\n<p>&nbsp;</p>','','',1259591049,1272162024),
	(9,'user','Vizra Giriş Bilgileri','tr','Vizra Teknik Destek','','','Vizra Giriş Bilgileriniz','<p>Sayın {$Client_name}, aşağıdaki bilgiler ile sistemimize login olabilirsiniz :</p>\r\n<p><strong>email</strong>: {$Client_email} <br><strong>şifre	:</strong> {$Client_password} <br><strong>adres	:</strong> {$vurl}  <br><br>Butun bilgileriniz bu panelde tutuldugu icin bu maili en kisa zaman silip, sifrenizi degistirmenizi tavsiye ediyoruz.</p>','','',0,1272162036),
	(11,'domain','Alan Adı Yenileme Hatırlatması','tr','Vizra Teknik Destek','','','Alan adınızın süresi dolmak üzere','<p>Sayın {$Client_name},</p><p><strong>{$Domain_domain}</strong> alan adınızın süresi {$Domain_dateExp} tarihinde sona erecektir.</p>\r\n<p><br></p>','','',1263069352,1272161917),
	(12,'finance','Ödeme Hatırlatma','tr','Vizra Finans Departmanı','','','Ödeme hatırlatma','<p>Sayın {$Client_name},</p><p>{$Order_orderID} - {$Service_service_name} siparişinize ait {$OrderBill_billID} numaralı ödenmemiş bir kaydınız bulunmaktadır.</p>\r\n<p>Miktar: {$OrderBill_amount} {$OrderBill_paycurID}</p>\r\n<p>Son Ödeme Tarihi: {$OrderBill_dateDue}</p>\r\n<p>&nbsp;</p>','','',1263070623,1272161882),
	(13,'finance','Ödeme İptal Edildi','tr','Vizra Finans Departmanı','','','Ödemeniz iptal edildi','<p>Sayın {$Client_name},</p>\r\n\r\n<p>{$Payment_dateAdded} tarihinde sistemimize girilmiş olan {$Payment_amount} {$Payment_paycurID} tutarındaki ödemeniz iptal edilerek kayıtlarımızdan silinmiştir.</p><p>Bunun bir hata olduğunu düşünüyorsanız en kısa zaman bizimle irtibata geçiniz.</p>','','',1263072081,1272161901),
	(15,'welcome','cPanel Hesap Açılış','tr','Vizra Teknik Destek','','','Hesabınız açıldı','<p>Hesabınız açıldı</p>','','',0,1265430270),
	(16,'welcome','cPanel Reseller Hesap Açılış','tr','','','','Reseller hesabınız açıldı','<p>Reseller hesabınız açıldı</p>','','',1263073894,1265430273),
	(17,'order','Sipariş Askıda','tr','','','','Siparişiniz Askıya Alındı','<p>Sayın {$Client_name},</p><p>{$Order_orderID} - {$Service_service_name} siparişiniz askıya alınmıştır.</p>','','',1263075200,1272161818),
	(18,'order','Sipariş Askıdan Alındı','tr','','','','Siparişiniz askıdan alındı','<p>Sayın {$Client_name},</p><p>{$Order_orderID} - {$Service_service_name} siparişiniz askıdan alınarak aktif edilmiştir.</p>','','',1263075329,1272161830),
	(19,'order','Sipariş Silindi','tr','','','','Siparişiniz silindi','<p>Sayın {$Client_name},</p><p>{$Order_orderID} - {$Service_service_name} siparişiniz sistemimizden silinmiştir.</p>','','',1263075824,1272161842),
	(20,'domain','Alan Adı Yenileme','tr','','','','Alan adınız yenilendi','<p>Sayın {$Client_name}</p>\r\n<p>{$Domain_domain} alan adınız başarı ile yenilenmiştir.</p>\r\n<p>Alan adınızın yeni bitiş tarihi: {$Domain_dateExp}</p>','','',1263135233,1272161927),
	(21,'order','Sipariş Yenileme','tr','','','','Siparişiniz yenilendi','<p>Sayın {$Client_name},</p><p>{$Order_orderID} - {$Order_title} siparişiniz yenilenmiştir.</p>\r\n<p>Siparişinizin yeni bitiş tarihi: {$Order_dateEnd}</p>','','',1263332973,1272161854),
	(6,'support','Yönetici Bilet Oluşturdu','tr','','','','Sizin için bir bilet oluşturuldu','<p>Sayın Müşterimiz,</p>\r\n<p>Sistem yöneticisi tarafından, <strong>{$Ticket_subject}</strong> konulu bir bilet oluşturuldu.</p>\r\n<p><a href=\"{$vurl}?p=user&amp;s=support&amp;a=viewTicket&amp;tID={$Ticket_ticketID}\">Detayları görüntülemek buraya tıklayınız</a></p>\r\n<p>&nbsp;</p>\r\n<p>Teşekkür eder, iyi çalışmalar dileriz.</p>','','',1267754132,1267755577),
	(22,'domain','Alan adı Transfer Kodu','tr','','','','Alan Adı Transfer Kodu','<p>Sayın {$Client_name}</p>\r\n<p>{$Domain_domain} alan adınıza ait transfer kodunuz aşağıda belirtilmiştir.</p>\r\n\r\n\r\n<p>Transfer kodunuz: {$authcode} </p>','','',1270413674,1272161936),
	(100,'custom','Özel Amaçlı Mail','tr','','','','Özel Amaçlı Email Konusu','Sayın {$Client_name},','','',1271969010,1271969030),
	(23,'order','Sipariş Süresi Bitti','tr','','','','Siparişinizin süresi bitti','<p>Sayın {$Client_name},</p><p>{$Order_orderID} - {$Service_service_name} siparişinizin süresi bittiği için kapatılmıştır.</p>','','',1263075200,1272161818);

/*!40000 ALTER TABLE `settings_email_templates` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table settings_general
# ------------------------------------------------------------

DROP TABLE IF EXISTS `settings_general`;

CREATE TABLE `settings_general` (
  `setting` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `encrypted` enum('0','1') NOT NULL default '0',
  `hidden` enum('0','1') NOT NULL default '0',
  UNIQUE KEY `setting` (`setting`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `settings_general` WRITE;
/*!40000 ALTER TABLE `settings_general` DISABLE KEYS */;

INSERT INTO `settings_general` (`setting`, `value`, `encrypted`, `hidden`)
VALUES
	('compinfo_email','noreply@vizra.net','0','0'),
	('compinfo_name','Vizra Soft','0','0'),
	('commset_mail_method','phpmail','0','0'),
	('payments_remenabled','1','0','0'),
	('domains_rem4','10','0','0'),
	('domains_rem3','20','0','0'),
	('main_cur_id','1','0','1'),
	('domains_rem2','30','0','0'),
	('domains_rem1','40','0','0'),
	('domains_remenabled','1','0','0'),
	('tickets_filetypes','jpg,jpeg,png,gif,pdf,doc,rar,docx','0','0'),
	('tickets_filesize','10','0','0'),
	('payments_rem1','35','0','0'),
	('payments_rem2','25','0','0'),
	('payments_rem3','15','0','0'),
	('payments_rem4','5','0','0'),
	('domains_ns1','ns1.onlyfordemo.net','0','0'),
	('domains_ns2','ns2.onlyfordemo.net','0','0'),
	('payments_billgen','15','0','0'),
	('commset_smtp_ssl','','0','0'),
	('portal_tpl','vt_no2','0','0'),
	('portal_lang','Turkish','0','0'),
	('automation_suspend_bills','1','0','0'),
	('shop_banner_size','700x200','0','0');

/*!40000 ALTER TABLE `settings_general` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table settings_modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `settings_modules`;

CREATE TABLE `settings_modules` (
  `moduleID` varchar(20) NOT NULL,
  `module_type` enum('payment','service','domain','system') NOT NULL,
  `setting` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `encrypted` enum('0','1') NOT NULL default '0',
  UNIQUE KEY `moduleID` (`moduleID`,`setting`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `settings_modules` WRITE;
/*!40000 ALTER TABLE `settings_modules` DISABLE KEYS */;

INSERT INTO `settings_modules` (`moduleID`, `module_type`, `setting`, `value`, `encrypted`)
VALUES
	('offline','payment','status','active','0'),
	('offline','payment','convert','1','0'),
	('offline','payment','title','Banka Havalesi','0'),
	('offline','payment','instructions','<p><strong>Ödeme No:</strong> {$paymentID}</p><p><strong>Ödeme Miktarı:</strong> {$amount} {$currency}</p><p>Lütfen DİKKAT! Ödeme açıklamanızda mutlaka ödeme numarınızı belirtiniz. Ödeme yaptıktan sonra en kısa zamanda onaylanabilmesi için, panelinizin Finans bölümünden ödeme bildirimi yapınız.</p>\r\n\r\n<p>&nbsp;</p>\r\n<p><span style=\"text-decoration: underline;\">Banka bilgilerimiz:</span></p><p><span style=\"text-decoration: underline;\">x</span></p><p><span style=\"text-decoration: underline;\">x</span></p><p><span style=\"text-decoration: underline;\"><br></span></p>','0'),
	('webpos','payment','status','inactive','0'),
	('webpos','payment','convert','1','0'),
	('webpos','payment','title','Kredi Kartı WebPos','0'),
	('webpos','payment','merchantID','','0'),
	('webpos','payment','username','','0'),
	('webpos','payment','password','','1'),
	('webpos','payment','posUrl','','0'),
	('paypal','payment','status','inactive','0'),
	('paypal','payment','convert','2','0'),
	('paypal','payment','title','PayPal','0'),
	('paypal','payment','paypal_email','','0'),
	('paypal','payment','test_mode','','0'),
	('directadmin','service','status','active','0'),
	('directadmin','service','title','DirectAdmin','0'),
	('plesk','service','status','active','0'),
	('plesk','service','title','PLESK','0'),
	('cpanel','service','status','active','0'),
	('cpanel','service','title','cPanel','0'),
	('cpanel','service','tmp_url','http://{$server_mainip}/~{$user}','0'),
	('cpanel','service','webmail_url','http://{$server_hostname}/webmail','0'),
	('cpanel','service','cpanel_url','http://{$server_hostname}:2082/login/?user={$user}&pass={$pass}','0'),
	('cpanel','service','whm_url','http://{$server_hostname}:2086/login/?user={$user}&pass={$pass}','0'),
	('cpanel','service','debug','','0'),
	('plesk','service','plesk_url','https://{$server_hostname}:8443','0'),
	('webpos','payment','debug','','0'),
	('paypal','payment','debug','','0'),
	('sms','system','status','inactive','0'),
	('sms','system','title','SMS','0'),
	('sms','system','gateway','clickatell','0'),
	('sms','system','username','','0'),
	('sms','system','password','','1'),
	('sms','system','originator','','0'),
	('sms','system','param1','','0'),
	('sms','system','param2','','0'),
	('sms','system','param3','','0'),
	('sms','system','debug','','0'),
	('offline','payment','debug','','0'),
	('tco','payment','status','inactive','0'),
	('tco','payment','convert','2','0'),
	('tco','payment','title','2CheckOut','0'),
	('tco','payment','sid','','0'),
	('tco','payment','key','','0'),
	('tco','payment','auto_approve','','0'),
	('tco','payment','demo_mode','','0'),
	('tco','payment','debug','','0');

/*!40000 ALTER TABLE `settings_modules` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ticket_attachments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ticket_attachments`;

CREATE TABLE `ticket_attachments` (
  `fileID` mediumint(8) unsigned NOT NULL auto_increment,
  `ticketID` char(9) NOT NULL,
  `adminID` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`fileID`),
  KEY `ticketID` (`ticketID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table ticket_responses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ticket_responses`;

CREATE TABLE `ticket_responses` (
  `responseID` int(11) unsigned NOT NULL auto_increment,
  `ticketID` varchar(9) NOT NULL default '',
  `adminID` smallint(11) unsigned NOT NULL default '0',
  `response` text NOT NULL,
  `private` enum('0','1') NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`responseID`),
  KEY `ticketID` (`ticketID`),
  KEY `adminID` (`adminID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table tickets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tickets`;

CREATE TABLE `tickets` (
  `ticketID` char(9) NOT NULL,
  `clientID` mediumint(8) unsigned NOT NULL default '0',
  `depID` tinyint(11) unsigned NOT NULL default '0',
  `adminID` smallint(5) NOT NULL default '-1',
  `subject` varchar(150) NOT NULL default '',
  `status` enum('closed','new','client-responded','awaiting-reply','investigating') NOT NULL default 'new',
  `priority` enum('1','2','3','4','5') NOT NULL default '1',
  `unread` enum('1','0') NOT NULL default '0',
  `archived` enum('yes','no') NOT NULL default 'no',
  `sticky` enum('no','yes') NOT NULL default 'no',
  `responses` smallint(5) unsigned NOT NULL default '0',
  `dateAdded` int(10) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`ticketID`),
  KEY `clientID` (`clientID`),
  KEY `adminID` (`adminID`),
  KEY `depID` (`depID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


