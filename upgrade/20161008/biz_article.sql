DROP TABLE IF EXISTS `biz_article`;
CREATE TABLE `biz_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) DEFAULT NULL,
  `atricle_title` varchar(255) DEFAULT NULL,
  `atricle_content` text COMMENT '文章内容',
  `category_id` tinyint(3) DEFAULT '0' COMMENT '文章分类',
  `Article_Editor` varchar(255) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
