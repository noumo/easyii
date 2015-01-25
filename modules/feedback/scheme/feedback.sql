CREATE TABLE IF NOT EXISTS `easyii_feedback` (
`feedback_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `title` varchar(128) NOT NULL,
  `text` text NOT NULL,
  `answer` text NOT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_feedback` ADD PRIMARY KEY (`feedback_id`);
ALTER TABLE `easyii_feedback` MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;