CREATE TABLE IF NOT EXISTS `easyii_photos` (
  `photo_id` int(11) NOT NULL,
  `module` varchar(32) NOT NULL,
  `item_id` int(11) NOT NULL,
  `thumb` varchar(128) NOT NULL,
  `image` varchar(128) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `order_num` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_photos` ADD PRIMARY KEY (`photo_id`), ADD KEY `module` (`module`);
ALTER TABLE `easyii_photos` MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT;