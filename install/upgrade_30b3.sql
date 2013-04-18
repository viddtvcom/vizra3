DROP TABLE IF EXISTS  `order_addons`;

DROP TABLE IF EXISTS `page_modules`;

ALTER TABLE `orders` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `payments` CHANGE `paymentStatus` `paymentStatus` ENUM( 'pending-payment', 'pending-approval', 'paid', 'notfound' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'pending-payment';
 
ALTER TABLE `payments` ADD `adminID` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `moduleID` ; 

ALTER TABLE `order_bills` ADD `mail_count` TINYINT( 2 ) UNSIGNED NOT NULL AFTER `description` ;

ALTER TABLE `pages` ADD `parentID` SMALLINT( 3 ) UNSIGNED NOT NULL AFTER `pageID` ;

UPDATE `pages` SET `parentID` = '110' WHERE `pages`.`pageID` =111 LIMIT 1 ;
UPDATE `pages` SET `parentID` = '115' WHERE `pages`.`pageID` =116 LIMIT 1 ;
UPDATE `pages` SET `parentID` = '310' WHERE `pages`.`pageID` =311 LIMIT 1 ;
UPDATE `pages` SET `parentID` = '410' WHERE `pages`.`pageID` =411 LIMIT 1 ;
UPDATE `pages` SET `parentID` = '510' WHERE `pages`.`pageID` =511 LIMIT 1 ;
UPDATE `pages` SET `parentID` = '515' WHERE `pages`.`pageID` =516 LIMIT 1 ;

INSERT INTO `pages` VALUES ( '620', '0', '6', 'whm_import.php', '1', '200', '1', '0', '0');
INSERT INTO `pages` VALUES ( '625', '0', '6', 'plesk_import.php', '1', '210', '1', '0', '0');

