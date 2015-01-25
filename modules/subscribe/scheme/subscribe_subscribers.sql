CREATE TABLE IF NOT EXISTS `easyii_subscribe_subscribers` (
`subscriber_id` int(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_subscribe_subscribers` ADD PRIMARY KEY (`subscriber_id`), ADD UNIQUE KEY `email` (`email`);
ALTER TABLE `easyii_subscribe_subscribers` MODIFY `subscriber_id` int(11) NOT NULL AUTO_INCREMENT;