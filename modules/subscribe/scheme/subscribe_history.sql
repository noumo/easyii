CREATE TABLE IF NOT EXISTS `easyii_subscribe_history` (
`history_id` int(11) NOT NULL,
  `subject` varchar(128) NOT NULL,
  `body` text NOT NULL,
  `sent` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_subscribe_history` ADD PRIMARY KEY (`history_id`);
ALTER TABLE `easyii_subscribe_history` MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;