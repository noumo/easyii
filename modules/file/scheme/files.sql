CREATE TABLE IF NOT EXISTS `easyii_files` (
`file_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `file` varchar(256) NOT NULL,
  `size` int(11) NOT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `downloads` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  `order_num` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_files` ADD PRIMARY KEY (`file_id`), ADD UNIQUE KEY `slug` (`slug`);
ALTER TABLE `easyii_files` MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT;