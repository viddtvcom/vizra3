ALTER TABLE `logs_sys` CHANGE `adminID` `label` VARCHAR( 50 ) NOT NULL ;
 
ALTER TABLE `settings_modules` CHANGE `module_type` `module_type` ENUM( 'payment', 'service', 'domain', 'system' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `settings_email_templates` ADD `sms` VARCHAR( 160 ) NOT NULL AFTER `body` ;

ALTER TABLE `servers` ADD `loadavg` VARCHAR( 100 ) NOT NULL AFTER `password` ;

ALTER TABLE `order_bills` ADD `clientID` MEDIUMINT( 7 ) NOT NULL AFTER `orderID`;

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE IF NOT EXISTS `announcements` (
  `recID` smallint(4) unsigned NOT NULL auto_increment,
  `adminID` smallint(4) unsigned NOT NULL,
  `status` enum('active','clients-only','inactive') NOT NULL default 'active',
  `title` varchar(100) NOT NULL,
  `body` text NOT NULL,
  `dateAdded` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`recID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `pages` (`pageID`, `parentID`, `moduleID`, `filename`, `showOnSubmenu`, `rowOrder`, `actions`, `dateAdded`, `dateUpdated`) VALUES
(220, 0, 2, 'kb.php', '1', 150, '1234', 0, 1264570249),
(225, 0, 2, 'announcements.php', '1', 200, '1234', 0, 1264570249);



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
  `title` varchar(100) NOT NULL,
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


ALTER TABLE `service_groups` ADD `description` TEXT NOT NULL AFTER `group_name` ;