CREATE TABLE IF NOT EXISTS `easyii_settings` (
`setting_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `value` varchar(1024) NOT NULL,
  `visibility` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_settings` ADD PRIMARY KEY (`setting_id`), ADD UNIQUE KEY `name` (`name`);
ALTER TABLE `easyii_settings` MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;