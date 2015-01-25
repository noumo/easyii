CREATE TABLE IF NOT EXISTS `easyii_guestbook` (
`guestbook_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `title` varchar(128) NOT NULL,
  `text` text NOT NULL,
  `answer` text NOT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `new` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_guestbook` ADD PRIMARY KEY (`guestbook_id`);
ALTER TABLE `easyii_guestbook` MODIFY `guestbook_id` int(11) NOT NULL AUTO_INCREMENT;