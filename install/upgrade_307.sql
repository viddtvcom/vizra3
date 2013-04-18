INSERT INTO `pages` VALUES ('640', '0', '6', 'mass_mail.php', '1', '250', '1', '0', '0');
INSERT INTO `pages` VALUES (185, 0, 1, 'shop.php', '1', 160, '1234', 1259692847, 1259692847);
INSERT INTO `pages` VALUES ('710', '0', '7', 'tib_csv.php', '1', '10', '1', '0', '0');   

ALTER TABLE `order_bills` ADD `xamount` DECIMAL( 10, 4 ) UNSIGNED NOT NULL AFTER `paycurID`;
ALTER TABLE `payments` ADD `xamount` DECIMAL( 10, 4 ) NOT NULL AFTER `paycurID`;

ALTER TABLE `queue` ADD `dateFire` INT( 10 ) UNSIGNED NOT NULL ;
ALTER TABLE `queue` CHANGE `status` `status` ENUM( 'inprocess', 'pending', 'pending-payment', 'completed', 'error', 'scheduled' ) NOT NULL DEFAULT 'pending' ;


DELETE FROM `settings_general` WHERE `setting` = 'security_use_ssl' LIMIT 1;

ALTER TABLE `services` ADD `sfOrder` SMALLINT UNSIGNED NOT NULL DEFAULT '0';

INSERT INTO `settings_email_templates` VALUES ('23', 'order', 'Sipariş Süresi Bitti', 'tr', '', '', '', 'Siparişinizin süresi bitti', '<p>Sayın {$Client_name},</p><p>{$Order_orderID} - {$Order_title} siparişinizin süresi bittiği için kapatılmıştır.</p>', '', '', '1263075200', '1272161818');


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


INSERT INTO `settings_banners` VALUES(1, 'Çoklu Kur Desteği', '25', '585858', 'İstediğiniz ürünlerin satışını farklı kurlar ile yapabilir, ödemelerinizi farklı kurla alabilirsiniz', '20', '585858', '', '1', 1);
INSERT INTO `settings_banners` VALUES(2, 'Otomatik Kur Güncelleyici', '25', '585858', 'İstediğiniz araklılar ile sistemin kur bilgileri otomatik olarak güncellenir', '20', '585858', '', '1', 2);
INSERT INTO `settings_banners` VALUES(3, 'cPanel, PLESK, DirectAdmin, WHMSONIC, HyperVM Servis Modülleri', '25', '585858', 'Bu sunucular ile tam entegrasyon, otomatik hesap açma, kapama, askıya alma, trafik ve disk kotaları güncelleme', '20', '585858', '', '2', 3);
INSERT INTO `settings_banners` VALUES(4, 'Ödeme Modülleri', '25', '585858', 'Ödeme modülleri ile, müşteriniz ödemesini online yaptığı anda, hesaplar otomatik olarak açılır.', '20', '585858', '', '3', 4);
