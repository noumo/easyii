CREATE TABLE IF NOT EXISTS `easyii_admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `auth_key` varchar(128) NOT NULL,
  `access_token` varchar(128) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `easyii_admins` ADD PRIMARY KEY (`admin_id`), ADD UNIQUE KEY `auth_token` (`access_token`);
ALTER TABLE `easyii_admins` MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;