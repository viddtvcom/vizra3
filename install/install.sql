-- phpMyAdmin SQL Dump
-- version 2.11.8.1deb5+lenny3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 03, 2011 at 03:15 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.6-1+lenny9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `vizra_v3`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=162 ;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` VALUES(0, 'admin', 'inactive', '', '', '', '', 'System', '', 0, 0, 0, '');
INSERT INTO `admins` VALUES(1, 'super-admin', 'active', 'yPfnMgkmCCuUtzZ81QrlH3H32ZrSixu+KzqJGKvRKhg=', 'admin@vizra.com', '', 'Demo Admin', 'DemoAdmin', 'Vizra Administrator', 0, 1271968896, 1274023220, '192.168.5.1');
INSERT INTO `admins` VALUES(2, 'admin', 'inactive', 'iOktGYu/8nMnFRJ1xjm15NbQy4WBZFN8ZGfiNOqDHD0=', 'eleman@vizra.com', '', 'Demo Eleman', 'Eleman', 'Teknik Destek Uzmanı', 0, 1264612155, 1265168275, '10.0.0.1');

-- --------------------------------------------------------

--
-- Table structure for table `admin_deps`
--

CREATE TABLE IF NOT EXISTS `admin_deps` (
  `adminID` smallint(4) unsigned NOT NULL,
  `depID` smallint(3) unsigned NOT NULL,
  KEY `adminID` (`adminID`,`depID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_deps`
--

INSERT INTO `admin_deps` VALUES(1, 1);
INSERT INTO `admin_deps` VALUES(1, 2);
INSERT INTO `admin_deps` VALUES(1, 3);
INSERT INTO `admin_deps` VALUES(1, 4);
INSERT INTO `admin_deps` VALUES(2, 2);
INSERT INTO `admin_deps` VALUES(2, 3);
INSERT INTO `admin_deps` VALUES(2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `admin_privs`
--

CREATE TABLE IF NOT EXISTS `admin_privs` (
  `adminID` smallint(5) unsigned NOT NULL,
  `pageID` smallint(3) unsigned NOT NULL,
  `priv` bigint(20) NOT NULL,
  UNIQUE KEY `adminID` (`adminID`,`pageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_privs`
--

INSERT INTO `admin_privs` VALUES(2, 110, 13);
INSERT INTO `admin_privs` VALUES(2, 176, 15);
INSERT INTO `admin_privs` VALUES(2, 175, 13);
INSERT INTO `admin_privs` VALUES(2, 170, 9);
INSERT INTO `admin_privs` VALUES(2, 165, 5);
INSERT INTO `admin_privs` VALUES(2, 150, 1);
INSERT INTO `admin_privs` VALUES(2, 145, 0);
INSERT INTO `admin_privs` VALUES(2, 140, 1);
INSERT INTO `admin_privs` VALUES(2, 135, 3);
INSERT INTO `admin_privs` VALUES(2, 111, 9);
INSERT INTO `admin_privs` VALUES(2, 130, 5);
INSERT INTO `admin_privs` VALUES(2, 112, 5);
INSERT INTO `admin_privs` VALUES(2, 115, 3);
INSERT INTO `admin_privs` VALUES(2, 116, 1);
INSERT INTO `admin_privs` VALUES(2, 117, 3);
INSERT INTO `admin_privs` VALUES(2, 125, 9);
INSERT INTO `admin_privs` VALUES(2, 120, 5);
INSERT INTO `admin_privs` VALUES(2, 210, 9);
INSERT INTO `admin_privs` VALUES(2, 410, 9);
INSERT INTO `admin_privs` VALUES(2, 420, 9);
INSERT INTO `admin_privs` VALUES(2, 516, 2);
INSERT INTO `admin_privs` VALUES(2, 615, 0);
INSERT INTO `admin_privs` VALUES(2, 412, 5);
INSERT INTO `admin_privs` VALUES(2, 411, 33);
INSERT INTO `admin_privs` VALUES(2, 415, 0);
INSERT INTO `admin_privs` VALUES(2, 310, 1);
INSERT INTO `admin_privs` VALUES(2, 215, 1);
INSERT INTO `admin_privs` VALUES(2, 211, 1);
INSERT INTO `admin_privs` VALUES(2, 212, 1);
INSERT INTO `admin_privs` VALUES(2, 311, 1);
INSERT INTO `admin_privs` VALUES(2, 312, 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin_qreps`
--

CREATE TABLE IF NOT EXISTS `admin_qreps` (
  `qrepID` smallint(5) unsigned NOT NULL auto_increment,
  `adminID` smallint(5) unsigned NOT NULL,
  `reply` text NOT NULL,
  PRIMARY KEY  (`qrepID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `admin_qreps`
--


-- --------------------------------------------------------

--
-- Table structure for table `admin_settings`
--

CREATE TABLE IF NOT EXISTS `admin_settings` (
  `settingID` smallint(3) unsigned NOT NULL,
  `adminID` smallint(4) unsigned NOT NULL,
  `value` varchar(100) NOT NULL,
  UNIQUE KEY `settingID` (`settingID`,`adminID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_settings`
--

INSERT INTO `admin_settings` VALUES(1, 1, '1');
INSERT INTO `admin_settings` VALUES(2, 1, '1');
INSERT INTO `admin_settings` VALUES(3, 1, '1');
INSERT INTO `admin_settings` VALUES(1, 2, '');
INSERT INTO `admin_settings` VALUES(2, 2, '');
INSERT INTO `admin_settings` VALUES(3, 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `admin_setting_types`
--

CREATE TABLE IF NOT EXISTS `admin_setting_types` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `admin_setting_types`
--

INSERT INTO `admin_setting_types` VALUES(1, 'staticChatWindow', 'checkbox', '', 'Sabit Chat Penceresi', '', 'layout', 1);
INSERT INTO `admin_setting_types` VALUES(2, 'staticLogWindow', 'checkbox', '', 'Sabit Log Penceresi', '', 'layout', 2);
INSERT INTO `admin_setting_types` VALUES(3, 'staticProbesColumn', 'checkbox', '', 'Sabit Monitör Göstergeleri', '', 'layout', 3);
INSERT INTO `admin_setting_types` VALUES(4, 'ordersSearch_orderStatus', 'textbox', '', '', '', 'hidden', 0);
INSERT INTO `admin_setting_types` VALUES(5, 'ordersSearch_serviceID', 'textbox', '', '', '', 'hidden', 0);
INSERT INTO `admin_setting_types` VALUES(6, 'ordersSearch_groupID', 'textbox', '', '', '', 'hidden', 0);

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE IF NOT EXISTS `announcements` (
  `recID` smallint(4) unsigned NOT NULL auto_increment,
  `adminID` smallint(4) unsigned NOT NULL,
  `status` enum('active','clients-only','inactive') NOT NULL default 'active',
  `title` varchar(100) NOT NULL,
  `body` text NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`recID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` VALUES(1, 1, 'active', 'Vizra3 Yeniden Yazıldı!', '<p>Vizra3 tamamen yeniden yazıldı!</p>', 1266536106);
INSERT INTO `announcements` VALUES(2, 1, 'clients-only', 'Sayın Müşterilerimiz..', '<p>Bu duyuruyu sadece kayıtlı kullanıcılar görebilir..</p>', 1266536132);

-- --------------------------------------------------------

--
-- Table structure for table `attrs`
--

CREATE TABLE IF NOT EXISTS `attrs` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `attrs`
--

INSERT INTO `attrs` VALUES(1, 'TC Kimlik No', 'individual', 'textbox', 'required', '', '', 200, 0, '1', '', 'checkTCKN', 'TC Kimlik No 11 haneli ve sadece rakamlardan oluşmalıdır');
INSERT INTO `attrs` VALUES(9, 'directi_customerID', 'all', 'textbox', 'system', '', '', 0, 0, '0', '', '', '');
INSERT INTO `attrs` VALUES(10, 'Vergi Dairesi', 'corporate', 'textbox', 'required', '', '', 200, 0, '0', '^[a-zA-Z0-9 ]{2,60}$', '', 'Vergi Dairesi alanı kurumsal müşteriler için zorunludur');
INSERT INTO `attrs` VALUES(11, 'Vergi No', 'corporate', 'textbox', 'required', '', '', 200, 0, '0', '^\\d{4,11}$', '', 'Vergi No alanı kurumsal müşteriler için zorunludur');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `messageID` int(10) unsigned NOT NULL auto_increment,
  `adminID` smallint(5) unsigned NOT NULL,
  `message` text NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`messageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `chat`
--


-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
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

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` VALUES(3339220, 1, 'active', '0', 'individual', 'user@vizra.com', 'UNsRGyeiOd7cys/bu46pMg==', 'Demo User', '', 'Adres', 'Semt', '34000', 'Istanbul', 'TR', '212 212 22 22', '532 333 44 55', '', '', 1265769135, 1271976044, 0, '', '', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `client_contacts`
--

CREATE TABLE IF NOT EXISTS `client_contacts` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `client_contacts`
--


-- --------------------------------------------------------

--
-- Table structure for table `client_extras`
--

CREATE TABLE IF NOT EXISTS `client_extras` (
  `clientID` mediumint(7) unsigned NOT NULL,
  `attrID` smallint(5) unsigned NOT NULL,
  `value` text NOT NULL,
  UNIQUE KEY `clientID` (`clientID`,`attrID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `client_extras`
--

INSERT INTO `client_extras` VALUES(3339220, 1, '');
INSERT INTO `client_extras` VALUES(3339220, 9, '');

-- --------------------------------------------------------

--
-- Table structure for table `client_groups`
--

CREATE TABLE IF NOT EXISTS `client_groups` (
  `groupID` smallint(5) unsigned NOT NULL auto_increment,
  `group_name` varchar(150) NOT NULL,
  `discount_rate` decimal(2,2) NOT NULL,
  PRIMARY KEY  (`groupID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `client_groups`
--

INSERT INTO `client_groups` VALUES(1, 'Genel Müşteriler', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `country` varchar(128) NOT NULL,
  `country_code` varchar(2) NOT NULL,
  `calling_code` varchar(64) NOT NULL,
  `calling_code_regex` varchar(255) NOT NULL,
  `calling_code_mask` varchar(30) NOT NULL,
  `default` enum('0','1') NOT NULL default '0',
  UNIQUE KEY `code` (`country_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` VALUES('Afghanistan', 'AF', '93', '', '', '0');
INSERT INTO `countries` VALUES('Albania', 'AL', '355', '', '', '0');
INSERT INTO `countries` VALUES('Algeria', 'DZ', '213', '', '', '0');
INSERT INTO `countries` VALUES('Angola ', 'AO', '244', '', '', '0');
INSERT INTO `countries` VALUES('Antarctica', 'AQ', '672', '', '', '0');
INSERT INTO `countries` VALUES('Argentina', 'AR', '54', '', '', '0');
INSERT INTO `countries` VALUES('Armenia ', 'AM', '374', '', '', '0');
INSERT INTO `countries` VALUES('Aruba', 'AW', '297', '', '', '0');
INSERT INTO `countries` VALUES('Australia ', 'AU', '61', '', '', '0');
INSERT INTO `countries` VALUES('Austria', 'AT', '43', '', '', '0');
INSERT INTO `countries` VALUES('Azerbaijan', 'AZ', '994', '', '', '0');
INSERT INTO `countries` VALUES('Bahrain', 'BH', '973', '', '', '0');
INSERT INTO `countries` VALUES('Bangladesh', 'BD', '880', '', '', '0');
INSERT INTO `countries` VALUES('Belgium', 'BE', '32', '', '', '0');
INSERT INTO `countries` VALUES('Belize', 'BZ', '501', '', '', '0');
INSERT INTO `countries` VALUES('Benin', 'BJ', '229', '', '', '0');
INSERT INTO `countries` VALUES('Bhutan', 'BT', '975', '', '', '0');
INSERT INTO `countries` VALUES('Bolivia ', 'BO', '591', '', '', '0');
INSERT INTO `countries` VALUES('Bosnia & Herzegovina ', 'BA', '387', '', '', '0');
INSERT INTO `countries` VALUES('Botswana ', 'BW', '267', '', '', '0');
INSERT INTO `countries` VALUES('Brazil ', 'BR', '55', '', '', '0');
INSERT INTO `countries` VALUES('Brunei Darussalam', 'BN', '673', '', '', '0');
INSERT INTO `countries` VALUES('Bulgaria', 'BG', '359', '', '', '0');
INSERT INTO `countries` VALUES('Burkina Faso ', 'BF', '226', '', '', '0');
INSERT INTO `countries` VALUES('Burundi', 'BI', '257', '', '', '0');
INSERT INTO `countries` VALUES('Cameroon', 'CM', '237', '', '', '0');
INSERT INTO `countries` VALUES('Canada', 'CA', '1', '', '', '0');
INSERT INTO `countries` VALUES('Cape Verde Islands', 'CV', '238', '', '', '0');
INSERT INTO `countries` VALUES('Central African Republic', 'CF', '236', '', '', '0');
INSERT INTO `countries` VALUES('Chad ', 'TD', '235', '', '', '0');
INSERT INTO `countries` VALUES('Chile ', 'CL', '56', '', '', '0');
INSERT INTO `countries` VALUES('China (PRC)', 'CN', '86', '', '', '0');
INSERT INTO `countries` VALUES('Christmas Island', 'CX', '61-8', '', '', '0');
INSERT INTO `countries` VALUES('Cocos-Keeling Islands', 'CC', '61', '', '', '0');
INSERT INTO `countries` VALUES('Colombia ', 'CO', '57', '', '', '0');
INSERT INTO `countries` VALUES('Comoros', 'KM', '269', '', '', '0');
INSERT INTO `countries` VALUES('Congo', 'CG', '242', '', '', '0');
INSERT INTO `countries` VALUES('Congo, Dem. Rep. (former Zaire) ', 'CD', '243', '', '', '0');
INSERT INTO `countries` VALUES('Cook Islands', 'CK', '682', '', '', '0');
INSERT INTO `countries` VALUES('Costa Rica', 'CR', '506', '', '', '0');
INSERT INTO `countries` VALUES('Cote d''Ivoire (Ivory Coast)', 'CI', '225', '', '', '0');
INSERT INTO `countries` VALUES('Croatia', 'HR', '385', '', '', '0');
INSERT INTO `countries` VALUES('Cuba', 'CU', '53', '', '', '0');
INSERT INTO `countries` VALUES('Curacao', 'CW', '599', '', '', '0');
INSERT INTO `countries` VALUES('Cyprus', 'CY', '357', '', '', '0');
INSERT INTO `countries` VALUES('Czech Republic', 'CZ', '420', '', '', '0');
INSERT INTO `countries` VALUES('Denmark', 'DK', '45', '', '', '0');
INSERT INTO `countries` VALUES('Djibouti', 'DJ', '253', '', '', '0');
INSERT INTO `countries` VALUES('Ecuador ', 'EC', '593', '', '', '0');
INSERT INTO `countries` VALUES('Egypt', 'EG', '20', '', '', '0');
INSERT INTO `countries` VALUES('El Salvador', 'SV', '503', '', '', '0');
INSERT INTO `countries` VALUES('Eritrea', 'ER', '291', '', '', '0');
INSERT INTO `countries` VALUES('Estonia', 'EE', '372', '', '', '0');
INSERT INTO `countries` VALUES('Ethiopia', 'ET', '251', '', '', '0');
INSERT INTO `countries` VALUES('Falkland Islands (Malvinas)', 'FK', '500', '', '', '0');
INSERT INTO `countries` VALUES('Faroe Islands', 'FO', '298', '', '', '0');
INSERT INTO `countries` VALUES('Fiji Islands', 'FJ', '679', '', '', '0');
INSERT INTO `countries` VALUES('Finland', 'FI', '358', '', '', '0');
INSERT INTO `countries` VALUES('France', 'FR', '33', '', '', '0');
INSERT INTO `countries` VALUES('Gambia', 'GM', '220', '', '', '0');
INSERT INTO `countries` VALUES('Georgia', 'GE', '995', '', '', '0');
INSERT INTO `countries` VALUES('Germany', 'DE', '49', '', '', '0');
INSERT INTO `countries` VALUES('Ghana ', 'GH', '233', '', '', '0');
INSERT INTO `countries` VALUES('Gibraltar ', 'GI', '350', '', '', '0');
INSERT INTO `countries` VALUES('Greece ', 'GR', '30', '', '', '0');
INSERT INTO `countries` VALUES('Greenland ', 'GL', '299', '', '', '0');
INSERT INTO `countries` VALUES('Guadeloupe', 'GP', '590', '', '', '0');
INSERT INTO `countries` VALUES('Guatemala ', 'GT', '502', '', '', '0');
INSERT INTO `countries` VALUES('Guinea-Bissau ', 'GW', '245', '', '', '0');
INSERT INTO `countries` VALUES('Guinea', 'GN', '224', '', '', '0');
INSERT INTO `countries` VALUES('Guyana', 'GY', '592', '', '', '0');
INSERT INTO `countries` VALUES('Haiti ', 'HT', '509', '', '', '0');
INSERT INTO `countries` VALUES('Honduras', 'HN', '504', '', '', '0');
INSERT INTO `countries` VALUES('Hong Kong', 'HK', '852', '', '', '0');
INSERT INTO `countries` VALUES('Hungary ', 'HU', '36', '', '', '0');
INSERT INTO `countries` VALUES('Iceland', 'IS', '354', '', '', '0');
INSERT INTO `countries` VALUES('India', 'IN', '91', '', '', '0');
INSERT INTO `countries` VALUES('Indonesia', 'ID', '62', '', '', '0');
INSERT INTO `countries` VALUES('Iran', 'IR', '98', '', '', '0');
INSERT INTO `countries` VALUES('Iraq', 'IQ', '964', '', '', '0');
INSERT INTO `countries` VALUES('Ireland', 'IE', '353', '', '', '0');
INSERT INTO `countries` VALUES('Israel ', 'IL', '972', '', '', '0');
INSERT INTO `countries` VALUES('Italy ', 'IT', '39', '', '', '0');
INSERT INTO `countries` VALUES('Japan ', 'JP', '81', '', '', '0');
INSERT INTO `countries` VALUES('Jordan', 'JO', '962', '', '', '0');
INSERT INTO `countries` VALUES('Kazakhstan', 'KG', '7', '', '', '0');
INSERT INTO `countries` VALUES('Kenya', 'KE', '254', '', '', '0');
INSERT INTO `countries` VALUES('Kiribati ', 'KI', '686', '', '', '0');
INSERT INTO `countries` VALUES('Korea (North)', 'KP', '850', '', '', '0');
INSERT INTO `countries` VALUES('Korea (South)', 'KR', '82', '', '', '0');
INSERT INTO `countries` VALUES('Kuwait ', 'KW', '965', '', '', '0');
INSERT INTO `countries` VALUES('Latvia ', 'LV', '371', '', '', '0');
INSERT INTO `countries` VALUES('Lebanon', 'LB', '961', '', '', '0');
INSERT INTO `countries` VALUES('Lesotho', 'LS', '266', '', '', '0');
INSERT INTO `countries` VALUES('Liberia', 'LR', '231', '', '', '0');
INSERT INTO `countries` VALUES('Libya', 'LY', '218', '', '', '0');
INSERT INTO `countries` VALUES('Liechtenstein', 'LI', '423', '', '', '0');
INSERT INTO `countries` VALUES('Lithuania ', 'LT', '370', '', '', '0');
INSERT INTO `countries` VALUES('Luxembourg', 'LU', '352', '', '', '0');
INSERT INTO `countries` VALUES('Macao', 'MO', '853', '', '', '0');
INSERT INTO `countries` VALUES('Macedonia (Former Yugoslav Rep of.)', 'MK', '389', '', '', '0');
INSERT INTO `countries` VALUES('Madagascar', 'MG', '261', '', '', '0');
INSERT INTO `countries` VALUES('Malawi ', 'MW', '265', '', '', '0');
INSERT INTO `countries` VALUES('Malaysia', 'MY', '60', '', '', '0');
INSERT INTO `countries` VALUES('Maldives', 'MV', '960', '', '', '0');
INSERT INTO `countries` VALUES('Mali Republic', 'ML', '223', '', '', '0');
INSERT INTO `countries` VALUES('Malta', 'MT', '356', '', '', '0');
INSERT INTO `countries` VALUES('Marshall Islands', 'MH', '692', '', '', '0');
INSERT INTO `countries` VALUES('Martinique', 'MQ', '596', '', '', '0');
INSERT INTO `countries` VALUES('Mauritania', 'MR', '222', '', '', '0');
INSERT INTO `countries` VALUES('Mauritius', 'MU', '230', '', '', '0');
INSERT INTO `countries` VALUES('Mexico', 'MX', '52', '', '', '0');
INSERT INTO `countries` VALUES('Moldova ', 'MD', '373', '', '', '0');
INSERT INTO `countries` VALUES('Monaco', 'MC', '377', '', '', '0');
INSERT INTO `countries` VALUES('Mongolia ', 'MN', '976', '', '', '0');
INSERT INTO `countries` VALUES('Montenegro', 'ME', '382', '', '', '0');
INSERT INTO `countries` VALUES('Morocco', 'MA', '212', '', '', '0');
INSERT INTO `countries` VALUES('Mozambique', 'MZ', '258', '', '', '0');
INSERT INTO `countries` VALUES('Myanmar', 'MM', '95', '', '', '0');
INSERT INTO `countries` VALUES('Namibia', 'NA', '264', '', '', '0');
INSERT INTO `countries` VALUES('Nauru', 'NR', '674', '', '', '0');
INSERT INTO `countries` VALUES('Nepal ', 'NP', '977', '', '', '0');
INSERT INTO `countries` VALUES('Netherlands', 'NL', '31', '', '', '0');
INSERT INTO `countries` VALUES('New Caledonia', 'NC', '687', '', '', '0');
INSERT INTO `countries` VALUES('New Zealand', 'NZ', '64', '', '', '0');
INSERT INTO `countries` VALUES('Nicaragua', 'NI', '505', '', '', '0');
INSERT INTO `countries` VALUES('Niger', 'NE', '227', '', '', '0');
INSERT INTO `countries` VALUES('Nigeria', 'NG', '234', '', '', '0');
INSERT INTO `countries` VALUES('Niue', 'NU', '683', '', '', '0');
INSERT INTO `countries` VALUES('Norfolk Island', 'NF', '672', '', '', '0');
INSERT INTO `countries` VALUES('Norway ', 'NO', '47', '', '', '0');
INSERT INTO `countries` VALUES('Oman', 'OM', '968', '', '', '0');
INSERT INTO `countries` VALUES('Pakistan', 'PK', '92', '', '', '0');
INSERT INTO `countries` VALUES('Palau', 'PW', '680', '', '', '0');
INSERT INTO `countries` VALUES('Palestinian Settlements', 'PS', '970', '', '', '0');
INSERT INTO `countries` VALUES('Papua New Guinea', 'PG', '675', '', '', '0');
INSERT INTO `countries` VALUES('Paraguay', 'PY', '595', '', '', '0');
INSERT INTO `countries` VALUES('Peru', 'PE', '51', '', '', '0');
INSERT INTO `countries` VALUES('Philippines', 'PH', '63', '', '', '0');
INSERT INTO `countries` VALUES('Poland', 'PL', '48', '', '', '0');
INSERT INTO `countries` VALUES('Portugal', 'PT', '351', '', '', '0');
INSERT INTO `countries` VALUES('Qatar', 'QA', '974', '', '', '0');
INSERT INTO `countries` VALUES('Romania', 'RO', '40', '', '', '0');
INSERT INTO `countries` VALUES('Russia', 'RU', '7', '', '', '0');
INSERT INTO `countries` VALUES('Rwandese Republic', 'RW', '250', '', '', '0');
INSERT INTO `countries` VALUES('St. Helena', 'SH', '290', '', '', '0');
INSERT INTO `countries` VALUES('St. Pierre & Miquelon', 'PM', '508', '', '', '0');
INSERT INTO `countries` VALUES('Samoa', 'WS', '685', '', '', '0');
INSERT INTO `countries` VALUES('San Marino', 'SM', '378', '', '', '0');
INSERT INTO `countries` VALUES('Sao Tome and Principe', 'ST', '239', '', '', '0');
INSERT INTO `countries` VALUES('Saudi Arabia', 'SA', '966', '', '', '0');
INSERT INTO `countries` VALUES('Senegal ', 'SN', '221', '', '', '0');
INSERT INTO `countries` VALUES('Serbia', 'RS', '381', '', '', '0');
INSERT INTO `countries` VALUES('Seychelles Republic', 'SC', '248', '', '', '0');
INSERT INTO `countries` VALUES('Sierra Leone', 'SL', '232', '', '', '0');
INSERT INTO `countries` VALUES('Singapore', 'SG', '65', '', '', '0');
INSERT INTO `countries` VALUES('Slovak Republic', 'SK', '421', '', '', '0');
INSERT INTO `countries` VALUES('Slovenia ', 'SI', '386', '', '', '0');
INSERT INTO `countries` VALUES('Solomon Islands', 'SB', '677', '', '', '0');
INSERT INTO `countries` VALUES('South Africa', 'ZA', '27', '', '', '0');
INSERT INTO `countries` VALUES('Spain', 'ES', '34', '', '', '0');
INSERT INTO `countries` VALUES('Sri Lanka', 'LK', '94', '', '', '0');
INSERT INTO `countries` VALUES('Sudan', 'SD', '249', '', '', '0');
INSERT INTO `countries` VALUES('Suriname ', 'SR', '597', '', '', '0');
INSERT INTO `countries` VALUES('Swaziland', 'SZ', '268', '', '', '0');
INSERT INTO `countries` VALUES('Sweden', 'SE', '46', '', '', '0');
INSERT INTO `countries` VALUES('Switzerland', 'CH', '41', '', '', '0');
INSERT INTO `countries` VALUES('Syria', 'SY', '963', '', '', '0');
INSERT INTO `countries` VALUES('Taiwan', 'TW', '886', '', '', '0');
INSERT INTO `countries` VALUES('Tajikistan', 'TJ', '992', '', '', '0');
INSERT INTO `countries` VALUES('Tanzania', 'TZ', '255', '', '', '0');
INSERT INTO `countries` VALUES('Thailand', 'TH', '66', '', '', '0');
INSERT INTO `countries` VALUES('Timor Leste', 'TL', '670', '', '', '0');
INSERT INTO `countries` VALUES('Tokelau', 'TK', '690', '', '', '0');
INSERT INTO `countries` VALUES('Tonga Islands', 'TO', '676', '', '', '0');
INSERT INTO `countries` VALUES('Tunisia', 'TN', '216', '', '', '0');
INSERT INTO `countries` VALUES('Turkiye', 'TR', '90', '^\\d{3} \\d{3} \\d{2} \\d{2}', '999 999 99 99', '1');
INSERT INTO `countries` VALUES('Turkmenistan ', 'TM', '993', '', '', '0');
INSERT INTO `countries` VALUES('Tuvalu', 'TV', '688', '', '', '0');
INSERT INTO `countries` VALUES('Uganda', 'UG', '256', '', '', '0');
INSERT INTO `countries` VALUES('Ukraine', 'UA', '380', '', '', '0');
INSERT INTO `countries` VALUES('United Arab Emirates', 'AE', '971', '', '', '0');
INSERT INTO `countries` VALUES('United Kingdom', 'GB', '44', '', '', '0');
INSERT INTO `countries` VALUES('United States of America', 'US', '1', '', '', '0');
INSERT INTO `countries` VALUES('Uruguay', 'UY', '598', '', '', '0');
INSERT INTO `countries` VALUES('Uzbekistan', 'UZ', '998', '', '', '0');
INSERT INTO `countries` VALUES('Vanuatu', 'VU', '678', '', '', '0');
INSERT INTO `countries` VALUES('Vatican City', 'VA', '39', '', '', '0');
INSERT INTO `countries` VALUES('Venezuela', 'VE', '58', '', '', '0');
INSERT INTO `countries` VALUES('Vietnam', 'VN', '84', '', '', '0');
INSERT INTO `countries` VALUES('Yemen', 'YE', '967', '', '', '0');
INSERT INTO `countries` VALUES('Zambia ', 'ZM', '260', '', '', '0');
INSERT INTO `countries` VALUES('Zimbabwe ', 'ZW', '263', '', '', '0');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE IF NOT EXISTS `coupons` (
  `couponID` mediumint(8) unsigned NOT NULL auto_increment,
  `code` varchar(64) NOT NULL,
  `type` enum('percentage','fixed') NOT NULL default 'percentage',
  `amount` int(10) unsigned NOT NULL,
  `services` text NOT NULL,
  `dateExpires` int(10) unsigned NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`couponID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `coupons`
--


-- --------------------------------------------------------

--
-- Table structure for table `crons`
--

CREATE TABLE IF NOT EXISTS `crons` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `crons`
--

INSERT INTO `crons` VALUES(1, 'minutely', 'completed', 'minutely.php', 1304381701, 0, 0, 05, 00, 0, 'g4JkhYpcWywv7muj');
INSERT INTO `crons` VALUES(4, 'daily', 'completed', 'daily.php', 0, 0, 0, 15, 00, 0, 'HnLqLFYG3jBKU86k');
INSERT INTO `crons` VALUES(5, 'hourly', 'completed', 'hourly.php', 0, 0, 0, 39, 00, 0, 'LQzgUJuvRs5yYavG');
INSERT INTO `crons` VALUES(6, 'hourly', 'completed', 'currency.php', 1268363282, 0, 0, 00, 00, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `dc_cats`
--

CREATE TABLE IF NOT EXISTS `dc_cats` (
  `catID` smallint(4) unsigned NOT NULL auto_increment,
  `parentID` smallint(4) unsigned NOT NULL default '0',
  `visibility` enum('everyone','client','admin') NOT NULL default 'everyone',
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `entries` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`catID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `dc_cats`
--

INSERT INTO `dc_cats` VALUES(2, 0, 'client', 'Kullanıcı Dosyaları', 'Bu kategorideki dosyaları sadece kayıtlı kullanıcılar indirebilir', 0);
INSERT INTO `dc_cats` VALUES(1, 0, 'everyone', 'Genel Dosyalar', 'Bu dosyaları herkes indirebilir', 0);
INSERT INTO `dc_cats` VALUES(3, 0, 'admin', 'Gizli Dosyalar', 'Bu dosyaları sadece belli aktif siparişi olan kullanıcılar indirebilir', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dc_files`
--

CREATE TABLE IF NOT EXISTS `dc_files` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `dc_files`
--


-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE IF NOT EXISTS `departments` (
  `depID` smallint(3) unsigned NOT NULL auto_increment,
  `status` enum('active','inactive') NOT NULL default 'active',
  `depTitle` varchar(100) NOT NULL,
  `depEmail` varchar(100) NOT NULL,
  `notifyOnTicket` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`depID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` VALUES(1, 'active', 'Satış', '', '0');
INSERT INTO `departments` VALUES(2, 'inactive', 'Muhasebe', '', '0');
INSERT INTO `departments` VALUES(3, 'active', 'Teknik Destek', '', '0');
INSERT INTO `departments` VALUES(4, 'active', 'Müşteri Hizmetleri', '', '0');

-- --------------------------------------------------------

--
-- Table structure for table `domains`
--

CREATE TABLE IF NOT EXISTS `domains` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `domains`
--


-- --------------------------------------------------------

--
-- Table structure for table `domain_contacts`
--

CREATE TABLE IF NOT EXISTS `domain_contacts` (
  `domainID` mediumint(7) unsigned NOT NULL,
  `contactID` mediumint(7) unsigned NOT NULL,
  `type` varchar(20) NOT NULL,
  UNIQUE KEY `domainID` (`domainID`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `domain_contacts`
--


-- --------------------------------------------------------

--
-- Table structure for table `domain_contact_registrar`
--

CREATE TABLE IF NOT EXISTS `domain_contact_registrar` (
  `contactID` mediumint(8) unsigned NOT NULL,
  `moduleID` varchar(20) NOT NULL,
  `registrarID` varchar(20) NOT NULL,
  UNIQUE KEY `contactID` (`contactID`,`moduleID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `domain_contact_registrar`
--


-- --------------------------------------------------------

--
-- Table structure for table `domain_extensions`
--

CREATE TABLE IF NOT EXISTS `domain_extensions` (
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

--
-- Dumping data for table `domain_extensions`
--

INSERT INTO `domain_extensions` VALUES(15, 'com', 5, 5.00, 5.00, 0.00, 'active', '0', '0', 1);
INSERT INTO `domain_extensions` VALUES(16, 'net', 7, 3.00, 5.00, 0.00, 'active', '0', '0', 2);
INSERT INTO `domain_extensions` VALUES(17, 'org', 10, 5.00, 5.00, 0.00, 'active', '0', '0', 3);
INSERT INTO `domain_extensions` VALUES(18, 'com.tr', 1, 20.00, 20.00, 20.00, 'active', '0', '0', 4);
INSERT INTO `domain_extensions` VALUES(52, 'tk', 10, 5.00, 5.00, 5.00, 'active', '0', '0', 5);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `fileID` int(10) unsigned NOT NULL auto_increment,
  `fileType` enum('ticket','avatar','client','service') NOT NULL,
  `clientID` mediumint(8) unsigned NOT NULL,
  `sysname` varchar(255) NOT NULL,
  `origname` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `dateUploaded` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`fileID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `files`
--


-- --------------------------------------------------------

--
-- Table structure for table `kb_cats`
--

CREATE TABLE IF NOT EXISTS `kb_cats` (
  `catID` smallint(4) unsigned NOT NULL auto_increment,
  `parentID` smallint(4) unsigned NOT NULL default '0',
  `visibility` enum('everyone','client','admin') NOT NULL default 'everyone',
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `entries` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`catID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `kb_cats`
--

INSERT INTO `kb_cats` VALUES(25, 23, 'everyone', 'Alt Kategori', 'İstediğiniz sayıda altalta kategori ekleyebilirsiniz', 0);
INSERT INTO `kb_cats` VALUES(24, 0, 'everyone', 'Genel Kategori 2', 'Bu kategori ve altındakileri herkes görebilir', 0);
INSERT INTO `kb_cats` VALUES(26, 0, 'client', 'Kullanıcı Özel', 'Bu kategori ve altındakileri sadece kayıtlı kullanıcılar görebilir', 1);
INSERT INTO `kb_cats` VALUES(27, 0, 'admin', 'Yönetici Özel', 'Bu kategori ve altındakileri sadece yöneticiler görebilir', 2);
INSERT INTO `kb_cats` VALUES(23, 0, 'everyone', 'Genel Kategori 1', 'Bu kategori ve altındakileri herkes görebilir', 1);

-- --------------------------------------------------------

--
-- Table structure for table `kb_entries`
--

CREATE TABLE IF NOT EXISTS `kb_entries` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `kb_entries`
--

INSERT INTO `kb_entries` VALUES(9, 26, 1, 'Kullanıcılara Özel Makale', '<p>Sed in velit vitae felis porta consequat. Praesent sed rutrum nisl. Phasellus varius pulvinar leo, vel euismod nunc interdum ac. Nunc blandit diam vel ante interdum at ultrices diam laoreet. <br /><br />Praesent ligula nulla, fermentum nec ultrices ut, convallis faucibus massa. Nam imperdiet nibh quis lacus posuere ac ultrices lorem aliquet.</p>', 0, 1266459368, 1266459368);
INSERT INTO `kb_entries` VALUES(10, 27, 1, 'Yöneticilere Özel Makale', '<p>Sed in velit vitae felis porta consequat. Praesent sed rutrum nisl. Phasellus varius pulvinar leo, vel euismod nunc interdum ac. Nunc blandit diam vel ante interdum at ultrices diam laoreet. <br /><br /><em>Praesent ligula nulla, fermentum nec ultrices ut, convallis faucibus massa. Nam imperdiet nibh quis lacus posuere ac ultrices lorem aliquet. </em></p>', 0, 1266459413, 1266459413);
INSERT INTO `kb_entries` VALUES(11, 27, 1, 'dasd', '<p>asd</p>', 0, 1266459427, 1266459427);
INSERT INTO `kb_entries` VALUES(8, 23, 0, 'Örnek Makale', '<p>Sed in velit vitae felis porta consequat. Praesent sed rutrum nisl. Phasellus varius pulvinar leo, vel euismod nunc interdum ac. Nunc blandit diam vel ante interdum at ultrices diam laoreet. <br /><br />Praesent ligula nulla, fermentum nec ultrices ut, <span style="color: #ff0000;">convallis faucibus</span> massa. Nam imperdiet nibh quis lacus posuere ac ultrices lorem aliquet.</p>', 0, 1266459263, 1266459263);

-- --------------------------------------------------------

--
-- Table structure for table `logs_sys`
--

CREATE TABLE IF NOT EXISTS `logs_sys` (
  `logID` int(10) unsigned NOT NULL auto_increment,
  `label` varchar(50) NOT NULL,
  `type` enum('info','warning','error') NOT NULL default 'info',
  `message` text NOT NULL,
  `dateAdded` int(11) NOT NULL,
  PRIMARY KEY  (`logID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `logs_sys`
--


-- --------------------------------------------------------

--
-- Table structure for table `object_history`
--

CREATE TABLE IF NOT EXISTS `object_history` (
  `recID` int(10) unsigned NOT NULL auto_increment,
  `objectID` varchar(128) character set latin5 NOT NULL,
  `subject` varchar(100) character set latin5 NOT NULL,
  `isadmin` enum('0','1') character set latin5 NOT NULL,
  `event` varchar(100) character set latin5 NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`recID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `object_history`
--


-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
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

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` VALUES(7897384, 0, 3339220, 1, 7, 0, 'active', '1', 'recurring', 10.00, 1, 1, 'cPanel Hosting (demodomain.com)', '', 1266530400, 1268949600, 1266536170, 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_attrs`
--

CREATE TABLE IF NOT EXISTS `order_attrs` (
  `orderID` mediumint(7) unsigned NOT NULL,
  `setting` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  `clientCanSee` enum('0','1') NOT NULL default '1',
  UNIQUE KEY `orderID` (`orderID`,`setting`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_attrs`
--

INSERT INTO `order_attrs` VALUES(7897384, 'cpanel_bwlimit', '2000', '1');
INSERT INTO `order_attrs` VALUES(7897384, 'cpanel_cgi', '0', '1');
INSERT INTO `order_attrs` VALUES(7897384, 'cpanel_cpmod', '', '');
INSERT INTO `order_attrs` VALUES(7897384, 'cpanel_frontpage', '0', '1');
INSERT INTO `order_attrs` VALUES(7897384, 'cpanel_maxaddon', '0', '1');
INSERT INTO `order_attrs` VALUES(7897384, 'cpanel_maxftp', '', '1');
INSERT INTO `order_attrs` VALUES(7897384, 'cpanel_maxpark', '1', '1');
INSERT INTO `order_attrs` VALUES(7897384, 'cpanel_maxpop', '', '1');
INSERT INTO `order_attrs` VALUES(7897384, 'cpanel_maxsql', '', '1');
INSERT INTO `order_attrs` VALUES(7897384, 'cpanel_maxsub', '10', '1');
INSERT INTO `order_attrs` VALUES(7897384, 'cpanel_plan', '', '');
INSERT INTO `order_attrs` VALUES(7897384, 'cpanel_quota', '100', '1');
INSERT INTO `order_attrs` VALUES(7897384, 'domain', 'demodomain.com', '1');
INSERT INTO `order_attrs` VALUES(7897384, 'password', '', '1');
INSERT INTO `order_attrs` VALUES(7897384, 'username', '', '1');

-- --------------------------------------------------------

--
-- Table structure for table `order_bills`
--

CREATE TABLE IF NOT EXISTS `order_bills` (
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

--
-- Dumping data for table `order_bills`
--

INSERT INTO `order_bills` VALUES(3994293, 0, 7897384, 0, 0, 'paid', 'recurring', 10.00, 1, 0.0000, '', 0, 1266530400, 1266536170, 1266530400, 1268949600);
INSERT INTO `order_bills` VALUES(1545164, 0, 7897384, 0, 0, 'paid', 'onetime', 5.00, 1, 0.0000, 'Kurulum', 0, 1266530400, 1266536170, 1266530400, 1266530400);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=711 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` VALUES(110, 0, 1, 'admins.php', '1', 15, '1234', 0, 0);
INSERT INTO `pages` VALUES(111, 110, 1, 'admin_details.php', '0', 0, '1234', 1259692847, 1263905646);
INSERT INTO `pages` VALUES(112, 0, 1, 'admin_settings.php', '0', 0, '1234', 1259692847, 1263905646);
INSERT INTO `pages` VALUES(115, 0, 1, 'services.php', '1', 30, '12345', 0, 1264570843);
INSERT INTO `pages` VALUES(116, 115, 1, 'service_details.php', '0', 0, '1234', 1259954625, 1259957239);
INSERT INTO `pages` VALUES(117, 0, 1, 'service_group_details.php', '0', 35, '1234', 1259692847, 1265224240);
INSERT INTO `pages` VALUES(120, 0, 1, 'service_attrs.php', '0', 50, '1234', 0, 1271896101);
INSERT INTO `pages` VALUES(125, 0, 1, 'servers.php', '1', 130, '1234', 0, 0);
INSERT INTO `pages` VALUES(130, 0, 1, 'pages.php', '0', 70, '1234', 0, 1271896127);
INSERT INTO `pages` VALUES(135, 0, 1, 'email_templates.php', '1', 80, '1234', 1259187266, 1259187266);
INSERT INTO `pages` VALUES(140, 0, 1, 'general.php', '1', 10, '1234', 1259595627, 1259595627);
INSERT INTO `pages` VALUES(145, 0, 1, 'domains.php', '1', 100, '1234', 1259692847, 1259692847);
INSERT INTO `pages` VALUES(150, 0, 1, 'server_probes.php', '0', 140, '1234', 1259692847, 1271896110);
INSERT INTO `pages` VALUES(165, 0, 1, 'currencies.php', '1', 20, '1234', 1259692847, 1259692847);
INSERT INTO `pages` VALUES(170, 0, 1, 'modules.php', '1', 80, '1234', 1259692847, 1259692847);
INSERT INTO `pages` VALUES(175, 0, 1, 'departments.php', '1', 120, '1234', 1259692847, 1259692847);
INSERT INTO `pages` VALUES(176, 0, 1, 'department_details.php', '0', 0, '1234', 1259692847, 1259692847);
INSERT INTO `pages` VALUES(210, 0, 2, 'tickets.php', '1', 100, '1234', 0, 0);
INSERT INTO `pages` VALUES(211, 0, 2, 'ticket_search.php', '1', 100, '14', 0, 0);
INSERT INTO `pages` VALUES(212, 0, 2, 'ticket_details.php', '0', 100, '1234', 0, 0);
INSERT INTO `pages` VALUES(215, 0, 2, 'chat.php', '1', 100, '12345', 0, 1264570249);
INSERT INTO `pages` VALUES(310, 0, 3, 'clients.php', '1', 100, '1234', 0, 0);
INSERT INTO `pages` VALUES(311, 310, 3, 'client_details.php', '0', 100, '1234', 0, 0);
INSERT INTO `pages` VALUES(312, 0, 3, 'add_client.php', '1', 100, '1234', 0, 0);
INSERT INTO `pages` VALUES(410, 0, 4, 'orders.php', '1', 100, '1234', 0, 0);
INSERT INTO `pages` VALUES(411, 410, 4, 'order_details.php', '0', 100, '12346', 0, 1264570530);
INSERT INTO `pages` VALUES(412, 0, 4, 'add_order.php', '0', 100, '1234', 0, 1263776244);
INSERT INTO `pages` VALUES(415, 0, 4, 'addon_details.php', '0', 255, '1234', 0, 1264357782);
INSERT INTO `pages` VALUES(420, 0, 4, 'domains.php', '1', 255, '1234', 0, 0);
INSERT INTO `pages` VALUES(510, 0, 5, 'payments.php', '1', 100, '1234', 0, 0);
INSERT INTO `pages` VALUES(511, 510, 5, 'payment_details.php', '0', 200, '1234', 0, 0);
INSERT INTO `pages` VALUES(515, 0, 5, 'bills.php', '1', 255, '1234', 0, 0);
INSERT INTO `pages` VALUES(516, 515, 5, 'bill_details.php', '0', 255, '1234', 0, 0);
INSERT INTO `pages` VALUES(610, 0, 6, 'logs.php', '1', 100, '1234', 0, 0);
INSERT INTO `pages` VALUES(615, 0, 6, 'queue.php', '1', 100, '1234', 0, 0);
INSERT INTO `pages` VALUES(620, 0, 6, 'whm_import.php', '1', 200, '1', 0, 0);
INSERT INTO `pages` VALUES(625, 0, 6, 'plesk_import.php', '1', 210, '1', 0, 0);
INSERT INTO `pages` VALUES(220, 0, 2, 'kb.php', '1', 150, '1234', 0, 1264570249);
INSERT INTO `pages` VALUES(225, 0, 2, 'announcements.php', '1', 200, '1234', 0, 1264570249);
INSERT INTO `pages` VALUES(180, 0, 1, 'custom_fields.php', '1', 150, '1234', 1259692847, 1259692847);
INSERT INTO `pages` VALUES(230, 0, 2, 'downloads.php', '1', 150, '1234', 0, 1264570249);
INSERT INTO `pages` VALUES(630, 0, 6, 'domain_import.php', '1', 220, '1', 0, 0);
INSERT INTO `pages` VALUES(635, 0, 6, 'license_info.php', '1', 230, '1', 0, 0);
INSERT INTO `pages` VALUES(640, 0, 6, 'mass_mail.php', '1', 250, '1', 0, 0);
INSERT INTO `pages` VALUES(710, 0, 7, 'tib_csv.php', '1', 10, '1', 0, 0);
INSERT INTO `pages` VALUES(185, 0, 1, 'shop.php', '1', 160, '1234', 1259692847, 1259692847);
INSERT INTO `pages` VALUES(177, 0, 1, 'coupons.php', '1', 170, '1234', 1259692847, 1259692847);
INSERT INTO `pages` VALUES(178, 0, 1, 'coupon_details.php', '0', 0, '1234', 1259692847, 1259692847);
INSERT INTO `pages` VALUES(631, 0, 6, 'domain_import_generic.php', '1', 225, '1', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
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

--
-- Dumping data for table `payments`
--


-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE IF NOT EXISTS `queue` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `queue`
--


-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

CREATE TABLE IF NOT EXISTS `servers` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `servers`
--

INSERT INTO `servers` VALUES(7, 'cpanel', 'active', 'Demo Server', '127.0.0.1', 'localhost', '', '', '', 1263340334, 1266536242);
INSERT INTO `servers` VALUES(12, 'cpanel', 'active', 'Demo Cpanel Server', '', '', '', '', '', 1265244548, 1265244548);
INSERT INTO `servers` VALUES(13, 'plesk', 'active', 'Demo PLESK Server', '', '', '', '', '', 1265244561, 1265244563);
INSERT INTO `servers` VALUES(14, 'directadmin', 'active', 'Demo DirectAdmin Server', '', '', '', '', '', 1265768990, 1265769000);

-- --------------------------------------------------------

--
-- Table structure for table `server_probes`
--

CREATE TABLE IF NOT EXISTS `server_probes` (
  `probeID` smallint(5) unsigned NOT NULL auto_increment,
  `serverID` smallint(5) unsigned NOT NULL,
  `status` enum('on','off') NOT NULL default 'off',
  `title` varchar(20) NOT NULL,
  `port` smallint(5) NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  `dateUpdated` int(10) unsigned NOT NULL,
  `dateNotified` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`probeID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `server_probes`
--

INSERT INTO `server_probes` VALUES(8, 7, 'on', 'http', 80, 1263399625, 1304381701, 0);
INSERT INTO `server_probes` VALUES(7, 7, 'on', 'smtp', 25, 1263340357, 1304381701, 0);

-- --------------------------------------------------------

--
-- Table structure for table `server_settings`
--

CREATE TABLE IF NOT EXISTS `server_settings` (
  `serverID` smallint(5) unsigned NOT NULL,
  `setting` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `encrypted` enum('0','1') NOT NULL default '0',
  UNIQUE KEY `serverID` (`serverID`,`setting`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `server_settings`
--

INSERT INTO `server_settings` VALUES(14, 'use_ssl', '', '0');
INSERT INTO `server_settings` VALUES(7, 'ns1', 'ns1.localhost', '0');
INSERT INTO `server_settings` VALUES(7, 'ns2', 'ns2.localhost', '0');
INSERT INTO `server_settings` VALUES(7, 'ns1_ip', '127.0.0.1', '0');
INSERT INTO `server_settings` VALUES(7, 'ns2_ip', '127.0.0.2', '0');
INSERT INTO `server_settings` VALUES(7, 'load_monitor', '', '0');
INSERT INTO `server_settings` VALUES(7, 'cpu_count', '', '0');
INSERT INTO `server_settings` VALUES(7, 'critical_load', '', '0');
INSERT INTO `server_settings` VALUES(7, 'use_ssl', '', '0');
INSERT INTO `server_settings` VALUES(7, 'auth', 'pass', '0');
INSERT INTO `server_settings` VALUES(7, 'server_hash', '', '1');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE IF NOT EXISTS `services` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

--
-- Dumping data for table `services`
--

INSERT INTO `services` VALUES(1, 20, 12, 1, 'cpanel', 15, 5, 'active', 'shared', 'cPanel Hosting', 'cpanel-hosting', 'auto', '', '0', '<strong>+ 10 GB</strong> Disk Alanı<br><strong>+ 100 GB</strong> Aylık \r\nTrafik<br><strong>+ Sınırsız</strong> Domain Ekleme<br><strong>+ \r\nSınırsız</strong> MySQL Veritabanı Hesabı<br><strong>+ Sınırsız</strong>\r\n E-Posta Hesabı', '<strong>Linux Hosting          Paketleri (PHP, MYSQL)</strong><br> \r\nCentos sunucularımızda php kullanmanız için uygun paketlerdir.          \r\nVeritabanı olarak mysql kullanabilirsiniz. Ayrıca sunucumuzda gd , curl \r\n         , ioncube, freetype ve zend bileşenlerini de kullanabilirsiniz.', 0, 5.00, 0.00, '', '0', '', 0, 0, 1, 10);
INSERT INTO `services` VALUES(27, 30, 0, 1, 'plesk', 0, 0, 'active', 'shared', 'Ekstra Disk Alanı 1GB  - PLESK', '', 'auto', 'setDiskQuota', '1', '', '', 0, 0.00, 0.00, '', '0', '', 0, 0, 0, 0);
INSERT INTO `services` VALUES(15, 10, 0, 1, '', 0, 0, 'active', 'domain', '.com Alan Adı Tescil Hizmeti', '', 'auto', '', '0', '', '', 0, 0.00, 0.00, '', '0', '', 0, 0, 1, 0);
INSERT INTO `services` VALUES(12, 107, 12, 1, 'cpanel', 16, 5, 'active', 'reseller', 'cPanel Reseller Hosting', 'cpanel-reseller-hosting', 'auto', '', '0', '<strong>+ 10 GB</strong> Disk Alanı<br><strong>+ 100 GB</strong> Aylık \r\nTrafik<br><strong>+ Sınırsız</strong> Domain Ekleme<br><strong>+ \r\nSınırsız</strong> MySQL Veritabanı Hesabı<br><strong>+ Sınırsız</strong>\r\n E-Posta Hesabı', '', 0, 0.00, 0.00, '', '0', '', 0, 0, 1, 40);
INSERT INTO `services` VALUES(17, 10, 0, 1, '', 0, 0, 'active', 'domain', '.org Alan Adı Tescil Hizmeti', '', 'auto', '', '0', '', '', 0, 0.00, 0.00, '', '0', '', 0, 0, 2, 0);
INSERT INTO `services` VALUES(16, 10, 0, 1, '', 0, 0, 'active', 'domain', '.net Alan Adı Tescil Hizmeti', '', 'auto', '', '0', '', '', 0, 0.00, 0.00, '', '0', '', 0, 0, 3, 0);
INSERT INTO `services` VALUES(18, 10, 0, 1, '', 0, 0, 'active', 'domain', '.com.tr Alan Adı Tescil Hizmeti', '', 'auto', '', '0', '', '', 0, 0.00, 0.00, '', '0', '', 0, 0, 4, 0);
INSERT INTO `services` VALUES(32, 30, 13, 1, 'plesk', 0, 5, 'active', 'shared', 'Plesk Hosting', 'plesk-hosting', 'auto', '', '0', '<strong>+ 10 GB</strong> Disk Alanı<br><strong>+ 100 GB</strong> Aylık \r\nTrafik<br><strong>+ Sınırsız</strong> Domain Ekleme<br><strong>+ \r\nSınırsız</strong> MySQL Veritabanı Hesabı<br><strong>+ Sınırsız</strong>\r\n E-Posta Hesabı', '<strong>Linux Hosting          Paketleri (PHP, MYSQL)</strong><br> \r\nCentos sunucularımızda php kullanmanız için uygun paketlerdir.          \r\nVeritabanı olarak mysql kullanabilirsiniz. Ayrıca sunucumuzda gd , curl \r\n         , ioncube, freetype ve zend bileşenlerini de kullanabilirsiniz.', 0, 6.00, 0.00, '', '0', '', 0, 0, 1, 20);
INSERT INTO `services` VALUES(33, 108, 13, 1, 'plesk', 0, 5, 'active', 'reseller', 'Plesk Reseller Hosting', 'plesk-reseller-hosting', 'auto', '', '0', '<strong>+ 10 GB</strong> Disk Alanı<br><strong>+ 100 GB</strong> Aylık \r\nTrafik<br><strong>+ Sınırsız</strong> Domain Ekleme<br><strong>+ \r\nSınırsız</strong> MySQL Veritabanı Hesabı<br><strong>+ Sınırsız</strong>\r\n E-Posta Hesabı', '', 0, 0.00, 0.00, '', '0', '', 0, 0, 1, 50);
INSERT INTO `services` VALUES(40, 30, 0, 1, 'plesk', 0, 0, 'active', 'shared', 'Ekstra Web Trafik 1GB - PLESK', '', 'auto', 'setTraffic', '1', '', '', 0, 0.00, 0.00, '', '0', '', 0, 0, 0, 0);
INSERT INTO `services` VALUES(41, 20, 0, 1, 'cpanel', 0, 0, 'active', 'shared', 'Ekstra Disk Alanı 1GB - cPanel', '', 'auto', 'setDiskQuota', '1', '', '', 0, 0.00, 0.00, '', '0', '', 0, 0, 0, 0);
INSERT INTO `services` VALUES(45, 20, 0, 1, 'cpanel', 0, 0, 'active', 'shared', 'Ekstra Web Trafik  1GB - cPanel', '', 'auto', 'setTraffic', '1', '', '', 0, 0.00, 0.00, '', '0', '', 0, 0, 0, 0);
INSERT INTO `services` VALUES(46, 108, 0, 1, 'plesk', 0, 0, 'active', 'reseller', 'Ekstra Disk Alanı 1GB - PLESK Reseller', '', 'auto', 'setDiskQuota', '1', '', '', 0, 5.00, 0.00, '', '0', '', 0, 0, 0, 0);
INSERT INTO `services` VALUES(47, 108, 0, 1, 'plesk', 0, 0, 'active', 'reseller', 'Ekstra Web Trafik  1GB - PLESK Reseller', '', 'auto', 'setTraffic', '1', '', '', 0, 4.00, 0.00, '', '0', '', 0, 0, 0, 0);
INSERT INTO `services` VALUES(48, 107, 0, 1, 'cpanel', 0, 0, 'active', 'reseller', 'Ekstra Disk Alanı 1GB - cPanel Reseller', '', 'auto', 'setDiskQuota', '1', '', '', 0, 0.00, 0.00, '', '0', '', 0, 0, 0, 0);
INSERT INTO `services` VALUES(49, 107, 0, 1, 'cpanel', 0, 0, 'active', 'reseller', 'Ekstra Web Trafik  1GB - cPanel Reseller', '', 'auto', 'setTraffic', '1', '', '', 0, 0.00, 0.00, '', '0', '', 0, 0, 0, 0);
INSERT INTO `services` VALUES(50, 20, 14, 1, 'directadmin', 0, 5, 'active', 'shared', 'DirectAdmin Hosting', 'directadmin-hosting', 'auto', '', '0', '<strong>+ 10 GB</strong> Disk Alanı<br><strong>+ 100 GB</strong> Aylık \r\nTrafik<br><strong>+ Sınırsız</strong> Domain Ekleme<br><strong>+ \r\nSınırsız</strong> MySQL Veritabanı Hesabı<br><strong>+ Sınırsız</strong>\r\n E-Posta Hesabı', '<strong>Linux Hosting          Paketleri (PHP, MYSQL)</strong><br> \r\nCentos sunucularımızda php kullanmanız için uygun paketlerdir.          \r\nVeritabanı olarak mysql kullanabilirsiniz. Ayrıca sunucumuzda gd , curl \r\n         , ioncube, freetype ve zend bileşenlerini de kullanabilirsiniz.', 0, 0.00, 0.00, '', '0', '', 0, 0, 2, 30);
INSERT INTO `services` VALUES(51, 107, 14, 1, 'directadmin', 0, 5, 'active', 'reseller', 'DirectAdmin Reseller Hosting', 'directadmin-reseller-hosting', 'auto', '', '0', '<strong>+ 10 GB</strong> Disk Alanı<br><strong>+ 100 GB</strong> Aylık \r\nTrafik<br><strong>+ Sınırsız</strong> Domain Ekleme<br><strong>+ \r\nSınırsız</strong> MySQL Veritabanı Hesabı<br><strong>+ Sınırsız</strong>\r\n E-Posta Hesabı', '', 0, 0.00, 0.00, '', '0', '', 0, 0, 2, 60);
INSERT INTO `services` VALUES(52, 10, 0, 1, '', 0, 0, 'active', 'domain', '.tk Alan Adı Tescil Hizmeti', '', 'auto', '', '0', '', '', 0, 0.00, 0.00, '', '0', '', 0, 0, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `service_attrs`
--

CREATE TABLE IF NOT EXISTS `service_attrs` (
  `attrID` smallint(5) unsigned NOT NULL auto_increment,
  `serviceID` smallint(5) unsigned NOT NULL,
  `source` enum('module','custom','custom-locked') NOT NULL default 'module',
  `setting` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  `clientCanSee` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`attrID`),
  UNIQUE KEY `serviceID` (`serviceID`,`setting`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1026 ;

--
-- Dumping data for table `service_attrs`
--

INSERT INTO `service_attrs` VALUES(998, 12, 'module', 'cpanel_enable_resource_limits', '1', '');
INSERT INTO `service_attrs` VALUES(997, 12, 'module', 'cpanel_bandwidth_limit', '666', '1');
INSERT INTO `service_attrs` VALUES(928, 32, 'custom-locked', 'domain', '', '1');
INSERT INTO `service_attrs` VALUES(927, 32, 'custom-locked', 'password', '', '1');
INSERT INTO `service_attrs` VALUES(926, 32, 'custom-locked', 'username', '', '1');
INSERT INTO `service_attrs` VALUES(929, 27, 'module', 'plesk_disk_space', '333', '1');
INSERT INTO `service_attrs` VALUES(930, 27, 'module', 'plesk_max_traffic', '', '1');
INSERT INTO `service_attrs` VALUES(925, 32, 'module', 'plesk_max_dom', '', '1');
INSERT INTO `service_attrs` VALUES(924, 32, 'module', 'plesk_max_dom_aliases', '8', '1');
INSERT INTO `service_attrs` VALUES(923, 32, 'module', 'plesk_max_subdom', '7', '1');
INSERT INTO `service_attrs` VALUES(931, 40, 'module', 'plesk_disk_space', '', '1');
INSERT INTO `service_attrs` VALUES(932, 40, 'module', 'plesk_max_traffic', '444', '1');
INSERT INTO `service_attrs` VALUES(996, 12, 'module', 'cpanel_diskspace_limit', '333', '1');
INSERT INTO `service_attrs` VALUES(995, 12, 'module', 'cpanel_account_limit', '4', '1');
INSERT INTO `service_attrs` VALUES(994, 12, 'module', 'cpanel_acllist', '', '');
INSERT INTO `service_attrs` VALUES(975, 33, 'module', 'plesk_max_dom', '3', '1');
INSERT INTO `service_attrs` VALUES(976, 33, 'custom-locked', 'username', '', '1');
INSERT INTO `service_attrs` VALUES(977, 33, 'custom-locked', 'password', '', '1');
INSERT INTO `service_attrs` VALUES(978, 33, 'custom-locked', 'domain', '', '1');
INSERT INTO `service_attrs` VALUES(922, 32, 'module', 'plesk_max_box', '6', '1');
INSERT INTO `service_attrs` VALUES(921, 32, 'module', 'plesk_max_mssql_db', '', '1');
INSERT INTO `service_attrs` VALUES(904, 15, 'custom', 'vps_cpu', '11', '1');
INSERT INTO `service_attrs` VALUES(902, 1, 'custom-locked', 'domain', '', '1');
INSERT INTO `service_attrs` VALUES(901, 1, 'custom-locked', 'password', '', '1');
INSERT INTO `service_attrs` VALUES(900, 1, 'custom-locked', 'username', '', '1');
INSERT INTO `service_attrs` VALUES(898, 1, 'module', 'cpanel_frontpage', '0', '1');
INSERT INTO `service_attrs` VALUES(897, 1, 'module', 'cpanel_cgi', '0', '1');
INSERT INTO `service_attrs` VALUES(896, 1, 'module', 'cpanel_maxsub', '10', '1');
INSERT INTO `service_attrs` VALUES(895, 1, 'module', 'cpanel_maxpop', '', '1');
INSERT INTO `service_attrs` VALUES(894, 1, 'module', 'cpanel_maxsql', '', '1');
INSERT INTO `service_attrs` VALUES(920, 32, 'module', 'plesk_max_db', '5', '1');
INSERT INTO `service_attrs` VALUES(919, 32, 'module', 'plesk_max_traffic', '600', '1');
INSERT INTO `service_attrs` VALUES(918, 32, 'module', 'plesk_disk_space', '150', '1');
INSERT INTO `service_attrs` VALUES(917, 32, 'module', 'plesk_template_name', '', '1');
INSERT INTO `service_attrs` VALUES(905, 41, 'module', 'cpanel_quota', '1024', '1');
INSERT INTO `service_attrs` VALUES(906, 41, 'module', 'cpanel_bwlimit', '', '1');
INSERT INTO `service_attrs` VALUES(999, 12, 'module', 'cpanel_enable_overselling_bandwidth', '1', '');
INSERT INTO `service_attrs` VALUES(979, 47, 'module', 'plesk_disk_space', '', '1');
INSERT INTO `service_attrs` VALUES(980, 47, 'module', 'plesk_max_traffic', '1000', '1');
INSERT INTO `service_attrs` VALUES(992, 12, 'module', 'cpanel_frontpage', '0', '1');
INSERT INTO `service_attrs` VALUES(991, 12, 'module', 'cpanel_cgi', '0', '1');
INSERT INTO `service_attrs` VALUES(990, 12, 'module', 'cpanel_maxsub', '', '1');
INSERT INTO `service_attrs` VALUES(989, 12, 'module', 'cpanel_maxpop', '', '1');
INSERT INTO `service_attrs` VALUES(988, 12, 'module', 'cpanel_maxsql', '', '1');
INSERT INTO `service_attrs` VALUES(987, 12, 'module', 'cpanel_maxftp', '', '1');
INSERT INTO `service_attrs` VALUES(986, 12, 'module', 'cpanel_maxaddon', '', '1');
INSERT INTO `service_attrs` VALUES(985, 12, 'module', 'cpanel_maxpark', '', '1');
INSERT INTO `service_attrs` VALUES(984, 12, 'module', 'cpanel_bwlimit', '222', '1');
INSERT INTO `service_attrs` VALUES(983, 12, 'module', 'cpanel_quota', '111', '1');
INSERT INTO `service_attrs` VALUES(982, 12, 'module', 'cpanel_cpmod', '', '');
INSERT INTO `service_attrs` VALUES(981, 12, 'module', 'cpanel_plan', '', '');
INSERT INTO `service_attrs` VALUES(893, 1, 'module', 'cpanel_maxftp', '', '1');
INSERT INTO `service_attrs` VALUES(892, 1, 'module', 'cpanel_maxaddon', '0', '1');
INSERT INTO `service_attrs` VALUES(891, 1, 'module', 'cpanel_maxpark', '1', '1');
INSERT INTO `service_attrs` VALUES(890, 1, 'module', 'cpanel_bwlimit', '2000', '1');
INSERT INTO `service_attrs` VALUES(889, 1, 'module', 'cpanel_quota', '100', '1');
INSERT INTO `service_attrs` VALUES(888, 1, 'module', 'cpanel_cpmod', '', '');
INSERT INTO `service_attrs` VALUES(887, 1, 'module', 'cpanel_plan', '', '');
INSERT INTO `service_attrs` VALUES(914, 45, 'module', 'cpanel_quota', '', '1');
INSERT INTO `service_attrs` VALUES(915, 45, 'module', 'cpanel_bwlimit', '1024', '1');
INSERT INTO `service_attrs` VALUES(972, 33, 'module', 'plesk_template_name', '', '1');
INSERT INTO `service_attrs` VALUES(973, 33, 'module', 'plesk_disk_space', '555', '1');
INSERT INTO `service_attrs` VALUES(974, 33, 'module', 'plesk_max_traffic', '777', '1');
INSERT INTO `service_attrs` VALUES(946, 46, 'module', 'plesk_disk_space', '1000', '1');
INSERT INTO `service_attrs` VALUES(947, 46, 'module', 'plesk_max_traffic', '', '1');
INSERT INTO `service_attrs` VALUES(1000, 12, 'module', 'cpanel_enable_overselling_diskspace', '1', '');
INSERT INTO `service_attrs` VALUES(1001, 12, 'custom-locked', 'username', '', '1');
INSERT INTO `service_attrs` VALUES(1002, 12, 'custom-locked', 'password', '', '1');
INSERT INTO `service_attrs` VALUES(1003, 12, 'custom-locked', 'domain', '', '1');
INSERT INTO `service_attrs` VALUES(1004, 48, 'module', 'cpanel_quota', '', '1');
INSERT INTO `service_attrs` VALUES(1005, 48, 'module', 'cpanel_bwlimit', '', '1');
INSERT INTO `service_attrs` VALUES(1006, 48, 'module', 'cpanel_diskspace_limit', '1000', '1');
INSERT INTO `service_attrs` VALUES(1007, 48, 'module', 'cpanel_bandwidth_limit', '', '1');
INSERT INTO `service_attrs` VALUES(1008, 49, 'module', 'cpanel_quota', '', '1');
INSERT INTO `service_attrs` VALUES(1009, 49, 'module', 'cpanel_bwlimit', '', '1');
INSERT INTO `service_attrs` VALUES(1010, 49, 'module', 'cpanel_diskspace_limit', '', '1');
INSERT INTO `service_attrs` VALUES(1011, 49, 'module', 'cpanel_bandwidth_limit', '1000', '1');
INSERT INTO `service_attrs` VALUES(1012, 50, 'module', 'directadmin_package', '', '1');
INSERT INTO `service_attrs` VALUES(1013, 50, 'module', 'directadmin_quota', '100', '1');
INSERT INTO `service_attrs` VALUES(1014, 50, 'module', 'directadmin_bandwidth', '500', '1');
INSERT INTO `service_attrs` VALUES(1015, 50, 'module', 'directadmin_vdomains', '1', '1');
INSERT INTO `service_attrs` VALUES(1016, 50, 'custom-locked', 'username', '', '1');
INSERT INTO `service_attrs` VALUES(1017, 50, 'custom-locked', 'password', '', '1');
INSERT INTO `service_attrs` VALUES(1018, 50, 'custom-locked', 'domain', '', '1');
INSERT INTO `service_attrs` VALUES(1019, 51, 'module', 'directadmin_package', '', '1');
INSERT INTO `service_attrs` VALUES(1020, 51, 'module', 'directadmin_quota', '1000', '1');
INSERT INTO `service_attrs` VALUES(1021, 51, 'module', 'directadmin_bandwidth', '5000', '1');
INSERT INTO `service_attrs` VALUES(1022, 51, 'module', 'directadmin_vdomains', '10', '1');
INSERT INTO `service_attrs` VALUES(1023, 51, 'custom-locked', 'username', '', '1');
INSERT INTO `service_attrs` VALUES(1024, 51, 'custom-locked', 'password', '', '1');
INSERT INTO `service_attrs` VALUES(1025, 51, 'custom-locked', 'domain', '', '1');

-- --------------------------------------------------------

--
-- Table structure for table `service_attr_types`
--

CREATE TABLE IF NOT EXISTS `service_attr_types` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=110 ;

--
-- Dumping data for table `service_attr_types`
--

INSERT INTO `service_attr_types` VALUES(1, 1, '', 'module', 'username', 'Kullanıcı Adı', 'textbox', '', '', 200, 0, '0', '^[a-zA-Z][a-zA-Z0-9_]{4,12}$', 'Kullanıcı adı en az 4, en fazla 12 karakter olmalı, harf ile başlamalı ve sadece latin harfleri ile rakamlardan oluşmalıdır.');
INSERT INTO `service_attr_types` VALUES(2, 1, '', 'module', 'password', 'Şifre', 'textbox', '', '', 200, 0, '1', '^(?=.*[0-9]+.*)(?=.*[a-zA-Z]+.*)[0-9a-zA-Z]{6,}$', 'Şifre en az 1 harf, en az 1 rakam içermeli ve en az 6 karakterden oluşmalıdır.');
INSERT INTO `service_attr_types` VALUES(5, 1, '', 'client', 'domain', 'Alan Adı', 'textbox', '', '', 200, 0, '0', '^[a-z0-9-]+(\\.[a-z0-9-]+)*(\\.[a-z]{2,4})$', 'Alan adı abc.com veya xxx.abc.com formatında olmalıdır');
INSERT INTO `service_attr_types` VALUES(17, 1, '', 'service', 'vps_ram', 'RAM', 'textbox', '', '', 50, 0, '1', '', '');
INSERT INTO `service_attr_types` VALUES(18, 1, '', 'service', 'vps_cpu', 'CPU', 'textbox', '', '', 50, 0, '0', '', '');
INSERT INTO `service_attr_types` VALUES(107, 1, '', 'client', 'ethernet', 'Ethernet Baglanti Hizi', 'combobox', '100=>100mbps,200=>200mbps,300=>300mbps', 'Sunucunun Hızını Belirtir', 200, 0, '0', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `service_groups`
--

CREATE TABLE IF NOT EXISTS `service_groups` (
  `groupID` smallint(5) unsigned NOT NULL auto_increment,
  `parentID` smallint(5) unsigned NOT NULL,
  `status` enum('active','inactive') NOT NULL default 'active',
  `group_name` varchar(200) NOT NULL,
  `seolink` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `rowOrder` smallint(3) unsigned NOT NULL,
  PRIMARY KEY  (`groupID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=109 ;

--
-- Dumping data for table `service_groups`
--

INSERT INTO `service_groups` VALUES(10, 1, 'active', 'Alan Adı Hizmetleri', 'alan-adi-hizmetleri', '', 1);
INSERT INTO `service_groups` VALUES(1, 0, 'active', 'Genel', 'genel', '', 0);
INSERT INTO `service_groups` VALUES(108, 1, 'active', 'Windows Reseller Hosting', 'windows-reseller-hosting', '', 5);
INSERT INTO `service_groups` VALUES(107, 1, 'active', 'Linux Reseller Hosting', 'linux-reseller-hosting', '', 4);
INSERT INTO `service_groups` VALUES(30, 1, 'active', 'Windows Web Hosting', 'windows-web-hosting', '', 3);
INSERT INTO `service_groups` VALUES(20, 1, 'active', 'Linux Web Hosting', 'linux-web-hosting', '<p><strong>Linux Hosting          Paketleri (PHP, MYSQL)</strong><br /> Centos sunucularımızda php kullanmanız için uygun paketlerdir.           Veritabanı olarak mysql kullanabilirsiniz. Ayrıca sunucumuzda gd , curl           , ioncube, freetype ve zend bileşenlerini de kullanabilirsiniz.</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<hr />\r\n<p>&nbsp;</p>', 2);

-- --------------------------------------------------------

--
-- Table structure for table `service_price_options`
--

CREATE TABLE IF NOT EXISTS `service_price_options` (
  `serviceID` smallint(5) unsigned NOT NULL,
  `period` tinyint(2) unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `default` enum('0','1') NOT NULL default '0',
  UNIQUE KEY `serviceID` (`serviceID`,`period`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `service_price_options`
--

INSERT INTO `service_price_options` VALUES(1, 1, 10.00, 0.00, '1');
INSERT INTO `service_price_options` VALUES(1, 3, 20.00, 0.00, '0');
INSERT INTO `service_price_options` VALUES(12, 1, 10.00, 0.00, '1');
INSERT INTO `service_price_options` VALUES(32, 3, 40.00, 0.00, '1');
INSERT INTO `service_price_options` VALUES(41, 1, 10.00, 0.00, '1');
INSERT INTO `service_price_options` VALUES(45, 1, 5.00, 0.00, '1');
INSERT INTO `service_price_options` VALUES(27, 1, 5.00, 0.00, '1');
INSERT INTO `service_price_options` VALUES(40, 1, 6.00, 0.00, '1');
INSERT INTO `service_price_options` VALUES(33, 1, 10.00, 0.00, '1');
INSERT INTO `service_price_options` VALUES(46, 1, 10.00, 0.00, '1');
INSERT INTO `service_price_options` VALUES(47, 1, 11.00, 0.00, '1');
INSERT INTO `service_price_options` VALUES(48, 1, 5.00, 0.00, '1');
INSERT INTO `service_price_options` VALUES(49, 1, 3.00, 0.00, '1');
INSERT INTO `service_price_options` VALUES(50, 1, 10.00, 0.00, '1');
INSERT INTO `service_price_options` VALUES(51, 1, 30.00, 0.00, '1');

-- --------------------------------------------------------

--
-- Table structure for table `settings_banners`
--

CREATE TABLE IF NOT EXISTS `settings_banners` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `settings_banners`
--

INSERT INTO `settings_banners` VALUES(1, 'Çoklu Kur Desteği', '25', '585858', 'İstediğiniz ürünlerin satışını farklı kurlar ile yapabilir, ödemelerinizi farklı kurla alabilirsiniz', '20', '585858', '', '1', 1);
INSERT INTO `settings_banners` VALUES(2, 'Otomatik Kur Güncelleyici', '25', '585858', 'İstediğiniz araklılar ile sistemin kur bilgileri otomatik olarak güncellenir', '20', '585858', '', '1', 2);
INSERT INTO `settings_banners` VALUES(3, 'cPanel, PLESK, DirectAdmin, WHMSONIC, HyperVM Servis Modülleri', '25', '585858', 'Bu sunucular ile tam entegrasyon, otomatik hesap açma, kapama, askıya alma, trafik ve disk kotaları güncelleme', '20', '585858', '', '2', 3);
INSERT INTO `settings_banners` VALUES(4, 'Ödeme Modülleri', '25', '585858', 'Ödeme modülleri ile, müşteriniz ödemesini online yaptığı anda, hesaplar otomatik olarak açılır.', '20', '585858', '', '3', 4);

-- --------------------------------------------------------

--
-- Table structure for table `settings_currencies`
--

CREATE TABLE IF NOT EXISTS `settings_currencies` (
  `curID` tinyint(3) unsigned NOT NULL auto_increment,
  `status` enum('active','inactive') NOT NULL,
  `ratio` decimal(9,5) NOT NULL default '1.00000',
  `code` varchar(5) NOT NULL,
  `symbol` varchar(5) NOT NULL,
  `description` varchar(100) NOT NULL,
  `rowOrder` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`curID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `settings_currencies`
--

INSERT INTO `settings_currencies` VALUES(1, 'active', 1.00000, 'TRY', 'TL', 'Türk Lirası', 1);
INSERT INTO `settings_currencies` VALUES(2, 'inactive', 1.53600, 'USD', '$', 'United States Dollars', 2);
INSERT INTO `settings_currencies` VALUES(3, 'inactive', 2.09740, 'EUR', '€', 'European Union Currency', 3);
INSERT INTO `settings_currencies` VALUES(4, 'inactive', 2.30600, 'GBP', '£', 'British Pound', 4);

-- --------------------------------------------------------

--
-- Table structure for table `settings_email_templates`
--

CREATE TABLE IF NOT EXISTS `settings_email_templates` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `settings_email_templates`
--

INSERT INTO `settings_email_templates` VALUES(14, 'order', 'Sipariş Onayı', 'tr', 'Vizra Teknik Destek', '', '', 'Siparişiniz Alındı', '<p>Sayın {$Client_name},</p><p>{$Order_orderID} - {$Service_service_name} siparişiniz sistemimize kaydedilmiştir.<br><br>Siparişinize bağlı olan servis aktif edildiği zaman, detayları içeren bir email daha alacaksınız.</p>\r\n<p>Siparişinizin son durumu: {$Order_status}</p>', '', '', 1263072311, 1272161767);
INSERT INTO `settings_email_templates` VALUES(3, 'finance', 'Ödeme Onayı', 'tr', 'Vizra Finans Departmanı', '', '', 'Ödemeniz Onaylandı', '<p>Sayın {$Client_name},</p><p>{$Payment_dateAdded} tarihinde sistemimize girilmiş olan {$Payment_amount} {$Payment_paycurID} tutarındaki ödemeniz onaylanarak bakiyenize eklenmiştir.</p>', '', '', 0, 1272161866);
INSERT INTO `settings_email_templates` VALUES(10, 'domain', 'Alan Adı Tescili', 'tr', 'Vizra Teknik Destek', '', '', 'Alan adınız tescil edildi', '<p>Sayın {$Client_name}</p>\r\n<p>{$Domain_domain} alan adınız başarı ile tescil edilmiştir.</p>\r\n<p>Alan adınızın bitiş tarihi: {$Domain_dateExp}</p>', '', '', 1263059577, 1272161907);
INSERT INTO `settings_email_templates` VALUES(7, 'support', 'Biletiniz kapandı', 'tr', '', '', '', 'Biletiniz kapandı', '', '', '', 1259266937, 1265430216);
INSERT INTO `settings_email_templates` VALUES(8, 'support', 'Bilete Cevap Verildi', 'tr', 'Vizra Teknik Destek', '', '', '{$Ticket_ticketID} numaralı biletinize cevap verildi', '<p>Sayın Müşterimiz,</p>\r\n<p>Sistemimize girmiş olduğunuz <strong>{$Ticket_subject}</strong> konulu bilete cevap verildi.</p>\r\n<p>{$vurl} adresinden hesabınıza giriş yaparak biletinize verilen cevabı görüntüleyebilirsiniz.</p>\r\n<p><a href="%7B$vurl%7D?p=user&amp;s=support&amp;a=viewTicket&amp;tID=%7B$Ticket_ticketID%7D">Detayları görüntülemek buraya tıklayınız</a></p>\r\n<p>Teşekkür eder, iyi çalışmalar dileriz.</p>\r\n<p>&nbsp;</p>', '', '', 1259591049, 1272162024);
INSERT INTO `settings_email_templates` VALUES(9, 'user', 'Vizra Giriş Bilgileri', 'tr', 'Vizra Teknik Destek', '', '', 'Vizra Giriş Bilgileriniz', '<p>Sayın {$Client_name}, aşağıdaki bilgiler ile sistemimize login olabilirsiniz :</p>\r\n<p><strong>email</strong>: {$Client_email} <br><strong>şifre	:</strong> {$Client_password} <br><strong>adres	:</strong> {$vurl}  <br><br>Butun bilgileriniz bu panelde tutuldugu icin bu maili en kisa zaman silip, sifrenizi degistirmenizi tavsiye ediyoruz.</p>', '', '', 0, 1272162036);
INSERT INTO `settings_email_templates` VALUES(11, 'domain', 'Alan Adı Yenileme Hatırlatması', 'tr', 'Vizra Teknik Destek', '', '', 'Alan adınızın süresi dolmak üzere', '<p>Sayın {$Client_name},</p><p><strong>{$Domain_domain}</strong> alan adınızın süresi {$Domain_dateExp} tarihinde sona erecektir.</p>\r\n<p><br></p>', '', '', 1263069352, 1272161917);
INSERT INTO `settings_email_templates` VALUES(12, 'finance', 'Ödeme Hatırlatma', 'tr', 'Vizra Finans Departmanı', '', '', 'Ödeme hatırlatma', '<p>Sayın {$Client_name},</p><p>{$Order_orderID} - {$Service_service_name} siparişinize ait {$OrderBill_billID} numaralı ödenmemiş bir kaydınız bulunmaktadır.</p>\r\n<p>Miktar: {$OrderBill_amount} {$OrderBill_paycurID}</p>\r\n<p>Son Ödeme Tarihi: {$OrderBill_dateDue}</p>\r\n<p>&nbsp;</p>', '', '', 1263070623, 1272161882);
INSERT INTO `settings_email_templates` VALUES(13, 'finance', 'Ödeme İptal Edildi', 'tr', 'Vizra Finans Departmanı', '', '', 'Ödemeniz iptal edildi', '<p>Sayın {$Client_name},</p>\r\n\r\n<p>{$Payment_dateAdded} tarihinde sistemimize girilmiş olan {$Payment_amount} {$Payment_paycurID} tutarındaki ödemeniz iptal edilerek kayıtlarımızdan silinmiştir.</p><p>Bunun bir hata olduğunu düşünüyorsanız en kısa zaman bizimle irtibata geçiniz.</p>', '', '', 1263072081, 1272161901);
INSERT INTO `settings_email_templates` VALUES(15, 'welcome', 'cPanel Hesap Açılış', 'tr', 'Vizra Teknik Destek', '', '', 'Hesabınız açıldı', '<p>Hesabınız açıldı</p>', '', '', 0, 1265430270);
INSERT INTO `settings_email_templates` VALUES(16, 'welcome', 'cPanel Reseller Hesap Açılış', 'tr', '', '', '', 'Reseller hesabınız açıldı', '<p>Reseller hesabınız açıldı</p>', '', '', 1263073894, 1265430273);
INSERT INTO `settings_email_templates` VALUES(17, 'order', 'Sipariş Askıda', 'tr', '', '', '', 'Siparişiniz Askıya Alındı', '<p>Sayın {$Client_name},</p><p>{$Order_orderID} - {$Service_service_name} siparişiniz askıya alınmıştır.</p>', '', '', 1263075200, 1272161818);
INSERT INTO `settings_email_templates` VALUES(18, 'order', 'Sipariş Askıdan Alındı', 'tr', '', '', '', 'Siparişiniz askıdan alındı', '<p>Sayın {$Client_name},</p><p>{$Order_orderID} - {$Service_service_name} siparişiniz askıdan alınarak aktif edilmiştir.</p>', '', '', 1263075329, 1272161830);
INSERT INTO `settings_email_templates` VALUES(19, 'order', 'Sipariş Silindi', 'tr', '', '', '', 'Siparişiniz silindi', '<p>Sayın {$Client_name},</p><p>{$Order_orderID} - {$Service_service_name} siparişiniz sistemimizden silinmiştir.</p>', '', '', 1263075824, 1272161842);
INSERT INTO `settings_email_templates` VALUES(20, 'domain', 'Alan Adı Yenileme', 'tr', '', '', '', 'Alan adınız yenilendi', '<p>Sayın {$Client_name}</p>\r\n<p>{$Domain_domain} alan adınız başarı ile yenilenmiştir.</p>\r\n<p>Alan adınızın yeni bitiş tarihi: {$Domain_dateExp}</p>', '', '', 1263135233, 1272161927);
INSERT INTO `settings_email_templates` VALUES(21, 'order', 'Sipariş Yenileme', 'tr', '', '', '', 'Siparişiniz yenilendi', '<p>Sayın {$Client_name},</p><p>{$Order_orderID} - {$Order_title} siparişiniz yenilenmiştir.</p>\r\n<p>Siparişinizin yeni bitiş tarihi: {$Order_dateEnd}</p>', '', '', 1263332973, 1272161854);
INSERT INTO `settings_email_templates` VALUES(6, 'support', 'Yönetici Bilet Oluşturdu', 'tr', '', '', '', 'Sizin için bir bilet oluşturuldu', '<p>Sayın Müşterimiz,</p>\r\n<p>Sistem yöneticisi tarafından, <strong>{$Ticket_subject}</strong> konulu bir bilet oluşturuldu.</p>\r\n<p><a href="{$vurl}?p=user&amp;s=support&amp;a=viewTicket&amp;tID={$Ticket_ticketID}">Detayları görüntülemek buraya tıklayınız</a></p>\r\n<p>&nbsp;</p>\r\n<p>Teşekkür eder, iyi çalışmalar dileriz.</p>', '', '', 1267754132, 1267755577);
INSERT INTO `settings_email_templates` VALUES(22, 'domain', 'Alan adı Transfer Kodu', 'tr', '', '', '', 'Alan Adı Transfer Kodu', '<p>Sayın {$Client_name}</p>\r\n<p>{$Domain_domain} alan adınıza ait transfer kodunuz aşağıda belirtilmiştir.</p>\r\n\r\n\r\n<p>Transfer kodunuz: {$authcode} </p>', '', '', 1270413674, 1272161936);
INSERT INTO `settings_email_templates` VALUES(100, 'custom', 'Özel Amaçlı Mail', 'tr', '', '', '', 'Özel Amaçlı Email Konusu', 'Sayın {$Client_name},', '', '', 1271969010, 1271969030);
INSERT INTO `settings_email_templates` VALUES(23, 'order', 'Sipariş Süresi Bitti', 'tr', '', '', '', 'Siparişinizin süresi bitti', '<p>Sayın {$Client_name},</p><p>{$Order_orderID} - {$Service_service_name} siparişinizin süresi bittiği için kapatılmıştır.</p>', '', '', 1263075200, 1272161818);

-- --------------------------------------------------------

--
-- Table structure for table `settings_general`
--

CREATE TABLE IF NOT EXISTS `settings_general` (
  `setting` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `encrypted` enum('0','1') NOT NULL default '0',
  `hidden` enum('0','1') NOT NULL default '0',
  UNIQUE KEY `setting` (`setting`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_general`
--

INSERT INTO `settings_general` VALUES('compinfo_email', 'noreply@vizra.net', '0', '0');
INSERT INTO `settings_general` VALUES('compinfo_name', 'Vizra Soft', '0', '0');
INSERT INTO `settings_general` VALUES('commset_mail_method', 'phpmail', '0', '0');
INSERT INTO `settings_general` VALUES('payments_remenabled', '1', '0', '0');
INSERT INTO `settings_general` VALUES('domains_rem4', '10', '0', '0');
INSERT INTO `settings_general` VALUES('domains_rem3', '20', '0', '0');
INSERT INTO `settings_general` VALUES('main_cur_id', '1', '0', '1');
INSERT INTO `settings_general` VALUES('domains_rem2', '30', '0', '0');
INSERT INTO `settings_general` VALUES('domains_rem1', '40', '0', '0');
INSERT INTO `settings_general` VALUES('domains_remenabled', '1', '0', '0');
INSERT INTO `settings_general` VALUES('tickets_filetypes', 'jpg,jpeg,png,gif,pdf,doc,rar,docx', '0', '0');
INSERT INTO `settings_general` VALUES('tickets_filesize', '10', '0', '0');
INSERT INTO `settings_general` VALUES('payments_rem1', '35', '0', '0');
INSERT INTO `settings_general` VALUES('payments_rem2', '25', '0', '0');
INSERT INTO `settings_general` VALUES('payments_rem3', '15', '0', '0');
INSERT INTO `settings_general` VALUES('payments_rem4', '5', '0', '0');
INSERT INTO `settings_general` VALUES('domains_ns1', 'ns1.onlyfordemo.net', '0', '0');
INSERT INTO `settings_general` VALUES('domains_ns2', 'ns2.onlyfordemo.net', '0', '0');
INSERT INTO `settings_general` VALUES('payments_billgen', '15', '0', '0');
INSERT INTO `settings_general` VALUES('commset_smtp_ssl', '', '0', '0');
INSERT INTO `settings_general` VALUES('portal_tpl', 'vt_no2', '0', '0');
INSERT INTO `settings_general` VALUES('portal_lang', 'Turkish', '0', '0');
INSERT INTO `settings_general` VALUES('automation_suspend_bills', '1', '0', '0');
INSERT INTO `settings_general` VALUES('shop_banner_size', '700x200', '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `settings_modules`
--

CREATE TABLE IF NOT EXISTS `settings_modules` (
  `moduleID` varchar(20) NOT NULL,
  `module_type` enum('payment','service','domain','system') NOT NULL,
  `setting` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `encrypted` enum('0','1') NOT NULL default '0',
  UNIQUE KEY `moduleID` (`moduleID`,`setting`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_modules`
--

INSERT INTO `settings_modules` VALUES('offline', 'payment', 'status', 'active', '0');
INSERT INTO `settings_modules` VALUES('offline', 'payment', 'convert', '1', '0');
INSERT INTO `settings_modules` VALUES('offline', 'payment', 'title', 'Banka Havalesi', '0');
INSERT INTO `settings_modules` VALUES('offline', 'payment', 'instructions', '<p><strong>Ödeme No:</strong> {$paymentID}</p><p><strong>Ödeme Miktarı:</strong> {$amount} {$currency}</p><p>Lütfen DİKKAT! Ödeme açıklamanızda mutlaka ödeme numarınızı belirtiniz. Ödeme yaptıktan sonra en kısa zamanda onaylanabilmesi için, panelinizin Finans bölümünden ödeme bildirimi yapınız.</p>\r\n\r\n<p>&nbsp;</p>\r\n<p><span style="text-decoration: underline;">Banka bilgilerimiz:</span></p><p><span style="text-decoration: underline;">x</span></p><p><span style="text-decoration: underline;">x</span></p><p><span style="text-decoration: underline;"><br></span></p>', '0');
INSERT INTO `settings_modules` VALUES('webpos', 'payment', 'status', 'inactive', '0');
INSERT INTO `settings_modules` VALUES('webpos', 'payment', 'convert', '1', '0');
INSERT INTO `settings_modules` VALUES('webpos', 'payment', 'title', 'Kredi Kartı WebPos', '0');
INSERT INTO `settings_modules` VALUES('webpos', 'payment', 'merchantID', '', '0');
INSERT INTO `settings_modules` VALUES('webpos', 'payment', 'username', '', '0');
INSERT INTO `settings_modules` VALUES('webpos', 'payment', 'password', '', '1');
INSERT INTO `settings_modules` VALUES('webpos', 'payment', 'posUrl', '', '0');
INSERT INTO `settings_modules` VALUES('paypal', 'payment', 'status', 'inactive', '0');
INSERT INTO `settings_modules` VALUES('paypal', 'payment', 'convert', '2', '0');
INSERT INTO `settings_modules` VALUES('paypal', 'payment', 'title', 'PayPal', '0');
INSERT INTO `settings_modules` VALUES('paypal', 'payment', 'paypal_email', '', '0');
INSERT INTO `settings_modules` VALUES('paypal', 'payment', 'test_mode', '', '0');
INSERT INTO `settings_modules` VALUES('directadmin', 'service', 'status', 'active', '0');
INSERT INTO `settings_modules` VALUES('directadmin', 'service', 'title', 'DirectAdmin', '0');
INSERT INTO `settings_modules` VALUES('plesk', 'service', 'status', 'active', '0');
INSERT INTO `settings_modules` VALUES('plesk', 'service', 'title', 'PLESK', '0');
INSERT INTO `settings_modules` VALUES('cpanel', 'service', 'status', 'active', '0');
INSERT INTO `settings_modules` VALUES('cpanel', 'service', 'title', 'cPanel', '0');
INSERT INTO `settings_modules` VALUES('cpanel', 'service', 'tmp_url', 'http://{$server_mainip}/~{$user}', '0');
INSERT INTO `settings_modules` VALUES('cpanel', 'service', 'webmail_url', 'http://{$server_hostname}/webmail', '0');
INSERT INTO `settings_modules` VALUES('cpanel', 'service', 'cpanel_url', 'http://{$server_hostname}:2082/login/?user={$user}&pass={$pass}', '0');
INSERT INTO `settings_modules` VALUES('cpanel', 'service', 'whm_url', 'http://{$server_hostname}:2086/login/?user={$user}&pass={$pass}', '0');
INSERT INTO `settings_modules` VALUES('cpanel', 'service', 'debug', '', '0');
INSERT INTO `settings_modules` VALUES('plesk', 'service', 'plesk_url', 'https://{$server_hostname}:8443', '0');
INSERT INTO `settings_modules` VALUES('webpos', 'payment', 'debug', '', '0');
INSERT INTO `settings_modules` VALUES('paypal', 'payment', 'debug', '', '0');
INSERT INTO `settings_modules` VALUES('sms', 'system', 'status', 'inactive', '0');
INSERT INTO `settings_modules` VALUES('sms', 'system', 'title', 'SMS', '0');
INSERT INTO `settings_modules` VALUES('sms', 'system', 'gateway', 'clickatell', '0');
INSERT INTO `settings_modules` VALUES('sms', 'system', 'username', '', '0');
INSERT INTO `settings_modules` VALUES('sms', 'system', 'password', '', '1');
INSERT INTO `settings_modules` VALUES('sms', 'system', 'originator', '', '0');
INSERT INTO `settings_modules` VALUES('sms', 'system', 'param1', '', '0');
INSERT INTO `settings_modules` VALUES('sms', 'system', 'param2', '', '0');
INSERT INTO `settings_modules` VALUES('sms', 'system', 'param3', '', '0');
INSERT INTO `settings_modules` VALUES('sms', 'system', 'debug', '', '0');
INSERT INTO `settings_modules` VALUES('offline', 'payment', 'debug', '', '0');
INSERT INTO `settings_modules` VALUES('tco', 'payment', 'status', 'inactive', '0');
INSERT INTO `settings_modules` VALUES('tco', 'payment', 'convert', '2', '0');
INSERT INTO `settings_modules` VALUES('tco', 'payment', 'title', '2CheckOut', '0');
INSERT INTO `settings_modules` VALUES('tco', 'payment', 'sid', '', '0');
INSERT INTO `settings_modules` VALUES('tco', 'payment', 'key', '', '0');
INSERT INTO `settings_modules` VALUES('tco', 'payment', 'auto_approve', '', '0');
INSERT INTO `settings_modules` VALUES('tco', 'payment', 'demo_mode', '', '0');
INSERT INTO `settings_modules` VALUES('tco', 'payment', 'debug', '', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE IF NOT EXISTS `tickets` (
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

--
-- Dumping data for table `tickets`
--


-- --------------------------------------------------------

--
-- Table structure for table `ticket_attachments`
--

CREATE TABLE IF NOT EXISTS `ticket_attachments` (
  `fileID` mediumint(8) unsigned NOT NULL auto_increment,
  `ticketID` char(9) NOT NULL,
  `adminID` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`fileID`),
  KEY `ticketID` (`ticketID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ticket_attachments`
--


-- --------------------------------------------------------

--
-- Table structure for table `ticket_responses`
--

CREATE TABLE IF NOT EXISTS `ticket_responses` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ticket_responses`
--

