CREATE TABLE IF NOT EXISTS `easyii_texts` (
`text_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `slug` varchar(128) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_texts` ADD PRIMARY KEY (`text_id`), ADD UNIQUE KEY `slug` (`slug`);
ALTER TABLE `easyii_texts` MODIFY `text_id` int(11) NOT NULL AUTO_INCREMENT;