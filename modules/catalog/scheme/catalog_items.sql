CREATE TABLE IF NOT EXISTS `easyii_catalog_items` (
  `item_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `data` text NOT NULL,
  `thumb` varchar(128) NOT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `order_num` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_catalog_items` ADD PRIMARY KEY (`item_id`), ADD UNIQUE KEY `mark` (`slug`);
ALTER TABLE `easyii_catalog_items` MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;