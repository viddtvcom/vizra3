 ALTER TABLE `order_bills` CHANGE `clientID` `clientID` MEDIUMINT( 7 ) UNSIGNED NOT NULL;
 
 INSERT INTO pages VALUES ('631', '0', '6', 'domain_import_generic.php', '1', '225', '1', '0', '0');
 
 INSERT INTO `settings_general` (`setting`, `value`, `encrypted`, `hidden`) VALUES ('automation_suspend_bills', '1', '0', '0');

