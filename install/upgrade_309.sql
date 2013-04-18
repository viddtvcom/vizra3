ALTER TABLE `kb_entries` CHANGE `title` `title` VARCHAR( 255 ) NOT NULL;  

ALTER TABLE `orders` ADD `couponID` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0' AFTER `serverID` ;

INSERT INTO `pages` (`pageID`, `parentID`, `moduleID`, `filename`, `showOnSubmenu`, `rowOrder`, `actions`, `dateAdded`, `dateUpdated`) VALUES
(177, 0, 1, 'coupons.php', '1', 170, '1234', 1259692847, 1259692847),
(178, 0, 1, 'coupon_details.php', '0', 0, '1234', 1259692847, 1259692847);

ALTER TABLE `admins` ADD `adminMsn` VARCHAR( 100 ) NOT NULL AFTER `adminEmail` ;

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