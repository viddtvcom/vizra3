
INSERT INTO `pages` VALUES ('180', '0', '1', 'custom_fields.php', '1', '150', '1234', '1259692847', '1259692847');
INSERT INTO `pages` VALUES (230, 0, 2, 'downloads.php', '1', 150, '1234', 0, 1264570249);

ALTER TABLE `service_attr_types` CHANGE `type` `type` ENUM( 'textbox', 'textarea', 'checkbox', 'combobox', 'db', 'hidden', 'server' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'textbox' ;


INSERT INTO `settings_general` VALUES ('portal_tpl', 'default', '0', '0');

ALTER TABLE `settings_email_templates`  AUTO_INCREMENT = 100; 

INSERT INTO `settings_email_templates`  VALUES (6, 'domain', 'Yönetici Bilet Oluşturdu', 'tr', '', '', '', 'Sizin için bir bilet oluşturuldu', '<p>Sayın Müşterimiz,</p>\r\n<p>Sistem yöneticisi tarafından, <strong>{$Ticket_subject}</strong> konulu bir bilet oluşturuldu.</p>\r\n<p><a href="{$vurl}?p=user&amp;s=support&amp;a=viewTicket&amp;tID={$Ticket_ticketID}">Detayları görüntülemek buraya tıklayınız</a></p>\r\n<p>&nbsp;</p>\r\n<p>Teşekkür eder, iyi çalışmalar dileriz.</p>', '', '', 1267754132, 1267755577);




DROP TABLE `attributes`;

DROP TABLE IF EXISTS `client_extras`;
CREATE TABLE IF NOT EXISTS `client_extras` (
  `clientID` mediumint(7) unsigned NOT NULL,
  `attrID` smallint(5) unsigned NOT NULL,
  `value` text NOT NULL,
  UNIQUE KEY `clientID` (`clientID`,`attrID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `attrs`;
CREATE TABLE IF NOT EXISTS `attrs` (
  `attrID` smallint(5) unsigned NOT NULL auto_increment,
  `label` varchar(100) NOT NULL,
  `type` enum('textbox','textarea','checkbox','combobox','db','server') NOT NULL default 'textbox',
  `visibility` enum('required','hidden','optional') NOT NULL default 'optional',
  `options` text NOT NULL,
  `description` text NOT NULL,
  `width` smallint(3) unsigned NOT NULL,
  `height` smallint(3) unsigned NOT NULL,
  `encrypted` enum('0','1') NOT NULL default '0',
  `validation` varchar(255) NOT NULL,
  `validation_info` text NOT NULL,
  PRIMARY KEY  (`attrID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

INSERT INTO `attrs` VALUES(1, 'TC Kimlik No', 'textbox', 'required', '', '', 200, 0, '1', '^\\d{11}$', 'TC Kimlik No 11 haneli ve sadece rakamlardan oluşmalıdır');
INSERT INTO `attrs` VALUES(2, 'MSN Adresiniz', 'textbox', 'optional', '', '', 100, 0, '0', '^[_a-zA-Z0-9-]+(\\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\\.[a-zA-Z0-9-]+)*\\.(([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|museum|name))', 'Geçerli bir email adresi giriniz.');
INSERT INTO `attrs` VALUES(3, 'Gizli Alan', 'textbox', 'hidden', '', 'Gizli alandır müşteri tarafından görülemez', 100, 0, '0', '', '');
INSERT INTO `attrs` VALUES(4, 'Combo Box', 'combobox', 'required', '1=>Bir,2=>İki,3=>üç', 'Açıklama', 0, 0, '0', '', '');
INSERT INTO `attrs` VALUES(8, 'Evet / Hayır?', 'checkbox', 'required', '', 'Evet ise işaretleyiniz.', 0, 0, '0', '', '');




CREATE TABLE IF NOT EXISTS `dc_cats` (
  `catID` smallint(4) unsigned NOT NULL auto_increment,
  `parentID` smallint(4) unsigned NOT NULL default '0',
  `visibility` enum('everyone','client','admin') NOT NULL default 'everyone',
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `entries` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`catID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `dc_cats` VALUES(2, 0, 'client', 'Kullanıcı Dosyaları', 'Bu kategorideki dosyaları sadece kayıtlı kullanıcılar indirebilir', 0);
INSERT INTO `dc_cats` VALUES(1, 0, 'everyone', 'Genel Dosyalar', 'Bu dosyaları herkes indirebilir', 0);
INSERT INTO `dc_cats` VALUES(3, 0, 'admin', 'Gizli Dosyalar', 'Bu dosyaları sadece belli aktif siparişi olan kullanıcılar indirebilir', 0);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `client_contacts`;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `domain_contacts`;
CREATE TABLE IF NOT EXISTS `domain_contacts` (
  `domainID` mediumint(7) unsigned NOT NULL,
  `contactID` mediumint(7) unsigned NOT NULL,
  `type` varchar(20) NOT NULL,
  UNIQUE KEY `domainID` (`domainID`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `domain_contact_registrar`;
CREATE TABLE IF NOT EXISTS `domain_contact_registrar` (
  `contactID` mediumint(8) unsigned NOT NULL,
  `moduleID` varchar(20) NOT NULL,
  `registrarID` varchar(20) NOT NULL,
  UNIQUE KEY `contactID` (`contactID`,`moduleID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



ALTER TABLE `services` ADD `file_cats` VARCHAR( 255 ) NOT NULL AFTER `setup` ;

ALTER TABLE `service_attr_types` ADD `moduleID` VARCHAR( 25 ) NOT NULL AFTER `groupID` ;

ALTER TABLE `service_groups` ADD `seolink` VARCHAR( 200 ) NOT NULL AFTER `group_name` ;
ALTER TABLE `services` ADD `seolink` VARCHAR( 100 ) NOT NULL AFTER `service_name` ;
ALTER TABLE `services` ADD `details` TEXT NOT NULL AFTER `description` ;

  

INSERT INTO `crons` VALUES ( NULL , 'hourly', 'completed', 'currency.php', '', '', '', '', '', '', '');

UPDATE crons SET dateStart = 0, dateEnd = 0 WHERE 1=1;


 

  
