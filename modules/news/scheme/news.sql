CREATE TABLE IF NOT EXISTS `easyii_news` (
`news_id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `image` varchar(128) NOT NULL,
  `short` varchar(1024) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_news` ADD PRIMARY KEY (`news_id`);
ALTER TABLE `easyii_news` MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT;