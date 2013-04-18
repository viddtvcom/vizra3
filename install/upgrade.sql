ALTER TABLE `payments` CHANGE `xamount` `xamount` DECIMAL( 10, 4 ) NOT NULL;
ALTER TABLE `attrs` ADD `validation_function` VARCHAR( 150 ) NOT NULL AFTER `validation` ;
ALTER TABLE `attrs` ADD `client_type` ENUM( 'all', 'individual', 'corporate' ) NOT NULL DEFAULT 'all' AFTER `label` ;