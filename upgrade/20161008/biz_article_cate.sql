DROP TABLE IF EXISTS `biz_article_cate`;
CREATE TABLE `biz_article_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) DEFAULT NULL,
  `category_name` varchar(250) DEFAULT NULL COMMENT '分类名',
  `Category_Index` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='文章分类';
