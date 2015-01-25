CREATE TABLE IF NOT EXISTS `easyii_pages` (
`page_id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `text` text NOT NULL,
  `slug` varchar(128) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_pages` ADD PRIMARY KEY (`page_id`), ADD UNIQUE KEY `slug` (`slug`);
ALTER TABLE `easyii_pages` MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT;