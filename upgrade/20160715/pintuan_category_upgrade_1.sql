
CREATE TABLE IF NOT EXISTS `pintuan_category` (
  `cate_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `Users_ID` varchar(10) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL COMMENT '上级id',
  `cate_name` varchar(50) DEFAULT NULL COMMENT '分类名',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  `sort` int(5) DEFAULT '0' COMMENT '排序',
  `Category_Img` varchar(50) DEFAULT NULL COMMENT '分类图片',
  `Category_ListTypeID` int(5) DEFAULT NULL,
  `istop` tinyint(3) DEFAULT '0' COMMENT '首页置顶分类',
  PRIMARY KEY (`cate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='商品分类表(拼团商品分类)';
