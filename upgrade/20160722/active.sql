
CREATE TABLE IF NOT EXISTS `active` (
  `Active_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '活动id',
  `Type_ID` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型：0、拼团，1、云购  2、众筹  3、微砍价 4、微促销',
  `Active_Name` varchar(10) NOT NULL COMMENT '活动名称',
  `Users_ID` varchar(10) NOT NULL COMMENT '商城id',
  `MaxGoodsCount` tinyint(3) NOT NULL COMMENT '拼团活动总共可以参加的产品数量',
  `MaxBizCount` int(8) NOT NULL DEFAULT '0' COMMENT '允许多少个商家参加活动',
  `BizGoodsCount` int(8) NOT NULL DEFAULT '0' COMMENT '允许每个商家推荐多少个产品',
  `IndexBizGoodsCount` tinyint(3) NOT NULL DEFAULT '0' COMMENT '每个商家可以推荐到首页的产品数量',
  `IndexShowGoodsCount` tinyint(3) NOT NULL DEFAULT '0' COMMENT '活动首页可以显示的产品的数量',
  `ListShowGoodsCount` tinyint(3) NOT NULL DEFAULT '0' COMMENT '活动列表页可以显示的产品的数量',
  `BizShowGoodsCount` tinyint(3) NOT NULL DEFAULT '0' COMMENT '商家店铺页可以显示的产品的数量',
  `imgurl` varchar(100) NULL DEFAULT '' COMMENT '活动图片', 
  `Status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '活动状态',
  `addtime` int(11) DEFAULT '0',
  `starttime` int(11) DEFAULT '0' COMMENT '活动开始时间',
  `stoptime` int(11) DEFAULT '0' COMMENT '活动结束时间',

  PRIMARY KEY (`Active_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='拼团活动';
