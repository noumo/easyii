CREATE TABLE IF NOT EXISTS `easyii_seotext` (
  `seotext_id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(128) NOT NULL,
  `item_id` int(11) NOT NULL,
  `h1` varchar(128) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `keywords` varchar(128) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`seotext_id`),
  UNIQUE KEY `model_item` (`model`,`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `easyii_article_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `thumb` varchar(128) DEFAULT NULL,
  `order_num` int(11) NOT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `easyii_article_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `thumb` varchar(128) DEFAULT NULL,
  `short` varchar(1024) DEFAULT NULL,
  `text` text NOT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  `order_num` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `easyii_faq` (
  `faq_id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `order_num` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`faq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `easyii_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `easyii_migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1424341510),
('m000000_000000_install', 1424341510);

ALTER TABLE `easyii_migration` ADD PRIMARY KEY (`version`);


ALTER TABLE `easyii_news` ADD  `slug` VARCHAR( 128 ) DEFAULT NULL AFTER  `text` , ADD UNIQUE (`slug`);
ALTER TABLE `easyii_news` CHANGE  `title`  `title` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `easyii_news` CHANGE `image` `thumb` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
UPDATE `easyii_modules` SET `settings` = '{"enableThumb":true,"thumbWidth":100,"thumbHeight":"","thumbCrop":false,"enableShort":true,"shortMaxLength":256}' WHERE `name` = 'news';

ALTER TABLE `easyii_photos` DROP INDEX `module`;
ALTER TABLE `easyii_photos` CHANGE  `module`  `model` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `easyii_photos` ADD INDEX (  `model` ,  `item_id` ) COMMENT  '';
UPDATE `easyii_photos` SET `model` = 'yii\\easyii\\modules\\gallery\\models\\Album' WHERE `model` = 'gallery';
UPDATE `easyii_photos` SET `model` = 'yii\\easyii\\modules\\catalog\\models\\Item' WHERE `model` = 'catalog';

ALTER TABLE `easyii_modules` ADD  `class` VARCHAR( 128 ) NOT NULL AFTER  `name`;
UPDATE `easyii_modules` SET `class` = 'yii\\easyii\\modules\\text\\CarouselModule' WHERE `name` = 'carousel' LIMIT 1;
UPDATE `easyii_modules` SET `class` = 'yii\\easyii\\modules\\page\\CatalogModule' WHERE `name` = 'catalog' LIMIT 1;
UPDATE `easyii_modules` SET `class` = 'yii\\easyii\\modules\\text\\FeedbackModule' WHERE `name` = 'feedback' LIMIT 1;
UPDATE `easyii_modules` SET `class` = 'yii\\easyii\\modules\\text\\FileModule' WHERE `name` = 'file' LIMIT 1;
UPDATE `easyii_modules` SET `class` = 'yii\\easyii\\modules\\text\\GalleryModule' WHERE `name` = 'gallery' LIMIT 1;
UPDATE `easyii_modules` SET `class` = 'yii\\easyii\\modules\\text\\GuestbookModule' WHERE `name` = 'guestbook' LIMIT 1;
UPDATE `easyii_modules` SET `class` = 'yii\\easyii\\modules\\text\\NewsModule' WHERE `name` = 'news' LIMIT 1;
UPDATE `easyii_modules` SET `class` = 'yii\\easyii\\modules\\text\\PageModule' WHERE `name` = 'page' LIMIT 1;
UPDATE `easyii_modules` SET `class` = 'yii\\easyii\\modules\\text\\SubscribeModule' WHERE `name` = 'subscribe' LIMIT 1;
UPDATE `easyii_modules` SET `class` = 'yii\\easyii\\modules\\text\\TextModule' WHERE `name` = 'text' LIMIT 1;

INSERT INTO `easyii_modules` (`name`, `class`, `title`, `icon`, `settings`, `notice`, `order_num`, `status`) VALUES
('faq', 'yii\\easyii\\modules\\faq\\FaqModule', 'FAQ', 'question-sign', '[]', 0, 45, 1),
('article', 'yii\\easyii\\modules\\article\\ArticleModule', 'Articles', 'pencil', '{"categoryThumb":true,"categoryThumbCrop":true,"categoryThumbWidth":100,"categoryThumbHeight":100,"articleThumb":true,"articleThumbCrop":true,"articleThumbWidth":100,"articleThumbHeight":100,"enableShort":true,"shortMaxLength":255,"categoryAutoSlug":true,"itemAutoSlug":true}', 0, 65, 1);
