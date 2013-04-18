INSERT INTO `pages` VALUES(630, 0, 6, 'domain_import.php', '1', 220, '1', 0, 0);
INSERT INTO `pages` VALUES('635', '0', '6', 'license_info.php', '1', '230', '1', '0', '0');

ALTER TABLE `attrs` CHANGE `visibility` `visibility` ENUM( 'required', 'hidden', 'optional', 'system' ) NOT NULL DEFAULT 'optional';
 
INSERT INTO `attrs` VALUES(NULL, 'directi_customerID', 'textbox', 'system', '', '', 0, 0, '0', '', '');
 
ALTER TABLE `domain_extensions` ADD `domlock` ENUM( '0', '1' ) NOT NULL DEFAULT '0' AFTER `status` , ADD `authcode` ENUM( '0', '1' ) NOT NULL DEFAULT '0' AFTER `domlock` ;


ALTER TABLE `domains` DROP `secret`;
ALTER TABLE `domains` ADD `locked` ENUM( '0', '1' ) NOT NULL DEFAULT '0' AFTER `ns4` ;


UPDATE `settings_email_templates` SET `type` = 'support' WHERE `settings_email_templates`.`templateID` = 6;
INSERT INTO `settings_email_templates` VALUES(22, 'domain', 'Alan adı Transfer Kodu', 'tr', '', '', '', 'Alan Adı Transfer Kodu', '<p>Sayın {$Client_name}</p>\r\n<p>{$Domain_domain} alan adınıza ait transfer kodunuz aşağıda belirtilmiştir.</p>\r\n<p>&nbsp;</p>\r\n<p>Transfer kodunuz: {$authcode}</p>\r\n<p>&nbsp;</p>\r\n<p>Saygılar,</p>', '', '', 1270413674, 1270413836);


ALTER TABLE `services` ADD `rowOrder` SMALLINT UNSIGNED NOT NULL ; 

ALTER TABLE `settings_email_templates` CHANGE `type` `type` ENUM( 'domain', 'finance', 'support', 'order', 'user', 'welcome', 'custom' ) NOT NULL;  

INSERT INTO `settings_modules` VALUES('cpanel', 'service', 'tmp_url', 'http://{$server_mainip}/~{$user}', '0');
INSERT INTO `settings_modules` VALUES('cpanel', 'service', 'webmail_url', 'http://{$server_hostname}/webmail', '0');
INSERT INTO `settings_modules` VALUES('cpanel', 'service', 'cpanel_url', 'http://{$server_hostname}:2082/login/?user={$user}&pass={$pass}', '0');
INSERT INTO `settings_modules` VALUES('cpanel', 'service', 'whm_url', 'http://{$server_hostname}:2086/login/?user={$user}&pass={$pass}', '0');
INSERT INTO `settings_modules` VALUES('plesk', 'service', 'plesk_url', 'https://{$server_hostname}:8443', '0');


UPDATE `settings_general` SET `setting` = 'notify_notifycell' WHERE CONVERT( `settings_general`.`setting` USING utf8 ) = 'compinfo_notifycell' LIMIT 1;
UPDATE `settings_general` SET `setting` = 'notify_notifymail' WHERE CONVERT( `settings_general`.`setting` USING utf8 ) = 'compinfo_notifymail' LIMIT 1;
 
ALTER TABLE `clients` ADD `notes` TEXT NOT NULL AFTER `cell`;
ALTER TABLE `clients` ADD `fnote` VARCHAR( 250 ) NOT NULL AFTER `notes`;
ALTER TABLE `services` ADD `has_support` ENUM( '0', '1' ) NOT NULL DEFAULT '0' AFTER `file_cats`;
ALTER TABLE `services` ADD `expires` VARCHAR( 10 ) NOT NULL AFTER `has_support`; 
ALTER TABLE `service_price_options` ADD `discount` DECIMAL( 10, 2 ) NOT NULL AFTER `price`;
ALTER TABLE `service_price_options` ADD `default` ENUM( '0', '1' ) NOT NULL DEFAULT '0';
ALTER TABLE `services` ADD `setup_discount` DECIMAL( 10, 2 ) NOT NULL AFTER `setup`;
UPDATE `pages` SET `showOnSubmenu` = '0' WHERE `pages`.`pageID` =150 LIMIT 1;
UPDATE `pages` SET `showOnSubmenu` = '0' WHERE `pages`.`pageID` =120 LIMIT 1;
INSERT INTO `settings_general` VALUES('portal_lang', 'Turkish', '0', '0');
INSERT INTO `admin_setting_types` VALUES(4, 'ordersSearch_orderStatus', 'textbox', '', '', '', 'hidden', 0);
INSERT INTO `admin_setting_types` VALUES(5, 'ordersSearch_serviceID', 'textbox', '', '', '', 'hidden', 0);
INSERT INTO `admin_setting_types` VALUES(6, 'ordersSearch_groupID', 'textbox', '', '', '', 'hidden', 0);



