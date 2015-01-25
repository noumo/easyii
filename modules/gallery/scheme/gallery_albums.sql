CREATE TABLE IF NOT EXISTS `easyii_gallery_albums` (
  `album_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `thumb` varchar(128) NOT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `order_num` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_gallery_albums` ADD PRIMARY KEY (`album_id`), ADD UNIQUE KEY `slug` (`slug`);
ALTER TABLE `easyii_gallery_albums` MODIFY `album_id` int(11) NOT NULL AUTO_INCREMENT;