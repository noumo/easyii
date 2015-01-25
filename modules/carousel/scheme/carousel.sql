CREATE TABLE IF NOT EXISTS `easyii_carousel` (
  `carousel_id` int(11) NOT NULL,
  `image` varchar(128) NOT NULL,
  `title` tinytext NOT NULL,
  `text` text NOT NULL,
  `order_num` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_carousel` ADD PRIMARY KEY (`carousel_id`);
ALTER TABLE `easyii_carousel` MODIFY `carousel_id` int(11) NOT NULL AUTO_INCREMENT;