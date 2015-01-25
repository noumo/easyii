CREATE TABLE IF NOT EXISTS easyii_loginform (
  log_id int(11) NOT NULL,
  username varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  ip varchar(16) NOT NULL,
  user_agent varchar(1024) NOT NULL,
  `time` int(11) NOT NULL,
  success tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE easyii_loginform ADD PRIMARY KEY (log_id);
ALTER TABLE easyii_loginform MODIFY log_id int(11) NOT NULL AUTO_INCREMENT;