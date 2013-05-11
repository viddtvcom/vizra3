
CREATE TABLE `client_invoice_contacts` (
	`contactID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	`clientID` mediumint(7) UNSIGNED NOT NULL,
	`type` enum('corporate','individual') NOT NULL DEFAULT 'individual',
	`name` varchar(50) NOT NULL,
	`company` varchar(50) NOT NULL,
	`email` varchar(50) NOT NULL,
	`address` varchar(50) NOT NULL,
	`city` varchar(50) NOT NULL,
	`tckn` varchar(11) NOT NULL,
	`tax_office` varchar(64) NOT NULL,
	`tax_no` varchar(12) NOT NULL,
	`dateAdded` int(10) UNSIGNED NOT NULL,
	`dateUpdated` int(10) UNSIGNED NOT NULL,
	PRIMARY KEY (`contactID`)) ENGINE=`MyISAM` AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;

ALTER TABLE `clients` ADD COLUMN `balance` decimal(12,2) NOT NULL DEFAULT '0.00' after `isVip`;

ALTER TABLE `domains` ADD COLUMN `updated` enum('0','1') CHARACTER SET utf8 DEFAULT '0' after `dateUpdated`;

CREATE TABLE `logs_emails` (
	`recID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`clientID` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
	`orderID` mediumint(8) UNSIGNED DEFAULT NULL,
	`dateAdded` int(10) UNSIGNED DEFAULT NULL,
	`mail_to` varchar(200) DEFAULT NULL,
	`mail_subject` varchar(255) DEFAULT NULL,
	`mail_body` text DEFAULT NULL,
	`delivery_result` enum('sent','failed','unknown') DEFAULT 'unknown',
	PRIMARY KEY (`recID`, `clientID`),
	INDEX `logs_emails_fk1` USING BTREE (clientID)) ENGINE=`InnoDB` AUTO_INCREMENT=27554 DEFAULT CHARACTER SET utf8 COLLATE
	utf8_general_ci;

ALTER TABLE `order_attrs` CHANGE COLUMN `value` `value` varchar(100) CHARACTER SET utf8 NOT NULL after `setting`;

ALTER TABLE `order_bills` ADD INDEX `orderID` USING BTREE (orderID);

CREATE TABLE `order_ccresult` (
	`recID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`customerID` int(11) NOT NULL DEFAULT '0',
	`paymentID` int(11) NOT NULL DEFAULT '0',
	`card_type` enum('vakifbank','worldcard','other') NOT NULL DEFAULT 'other',
	`card_owner` varchar(100) NOT NULL DEFAULT '',
	`card_no` varchar(16) NOT NULL DEFAULT '',
	`exp` varchar(5) NOT NULL DEFAULT '',
	`cv2` varchar(3) NOT NULL DEFAULT '',
	`amount` decimal(8,2) NOT NULL DEFAULT '0.00',
	`installment` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
	`code` varchar(4) NOT NULL,
	`uyeref` varchar(64) NOT NULL,
	`provno` varchar(64) NOT NULL,
	`result` text NOT NULL,
	`ip` varchar(16) NOT NULL DEFAULT '',
	`dateAdded` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`recID`)) ENGINE=`MyISAM` AUTO_INCREMENT=1519 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;



CREATE TABLE `order_history` (
	`recID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`orderID` mediumint(7) UNSIGNED DEFAULT '0',
	`paymentID` mediumint(7) UNSIGNED DEFAULT NULL,
	`jobID` int(10) UNSIGNED DEFAULT NULL,
	`adminID` smallint(5) UNSIGNED DEFAULT '0',
	`action_type` enum('other','plugin') DEFAULT NULL,
	`action` varchar(255) DEFAULT NULL,
	`status` enum('error','success') DEFAULT NULL,
	`description` varchar(255) DEFAULT NULL,
	`dateAdded` int(10) UNSIGNED DEFAULT NULL,
	PRIMARY KEY (`recID`),
	KEY (`orderID`)
	)
	ENGINE=`MyISAM` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;

ALTER TABLE `service_groups` ADD COLUMN `addonparentID` int(11) NOT NULL after `parentID`;
ALTER TABLE `settings_email_templates` CHANGE COLUMN `sms` `sms` text CHARACTER SET utf8 NOT NULL after `body`;
ALTER TABLE `settings_general` CHANGE COLUMN `value` `value` varchar(200) CHARACTER SET utf8 NOT NULL after `setting`;
