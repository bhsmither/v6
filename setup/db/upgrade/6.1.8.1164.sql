CREATE TABLE `CubeCart_seo_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `past_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `item_id` int(25) unsigned DEFAULT NULL,
  `custom` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`past_path`),
  KEY `id` (`id`),
  KEY `type` (`type`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1; #EOQ
