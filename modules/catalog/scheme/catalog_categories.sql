CREATE TABLE IF NOT EXISTS `easyii_catalog_categories` (
  `category_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `fields` text NOT NULL,
  `thumb` varchar(128) NOT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `order_num` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_catalog_categories` ADD PRIMARY KEY (`category_id`), ADD UNIQUE KEY `mark` (`slug`);
ALTER TABLE `easyii_catalog_categories` MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;