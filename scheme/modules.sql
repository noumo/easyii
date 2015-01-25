CREATE TABLE IF NOT EXISTS `easyii_modules` (
`module_id` smallint(6) NOT NULL,
  `name` varchar(64) NOT NULL,
  `title` varchar(64) NOT NULL,
  `icon` varchar(32) NOT NULL,
  `settings` text NOT NULL,
  `notice` int(11) NOT NULL,
  `order_num` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_modules` ADD PRIMARY KEY (`module_id`), ADD UNIQUE KEY `name` (`name`);
ALTER TABLE `easyii_modules` MODIFY `module_id` smallint(6) NOT NULL AUTO_INCREMENT;