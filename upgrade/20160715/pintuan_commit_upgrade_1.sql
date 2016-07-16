
CREATE TABLE IF NOT EXISTS `pintuan_commit` (
  `Item_ID` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT '',
  `Biz_ID` int(10) DEFAULT '0',
  `User_ID` int(10) DEFAULT '0',
  `MID` varchar(50) DEFAULT '' COMMENT '什么活动中的商品',
  `Order_ID` int(10) DEFAULT '0' COMMENT '订单号',
  `Product_ID` int(10) DEFAULT '0' COMMENT '商品单号',
  `Score` varchar(10) DEFAULT '0' COMMENT '评价的分',
  `Note` text COMMENT '评价',
  `CreateTime` int(10) DEFAULT '0' COMMENT '生成时间',
  `Status` tinyint(1) DEFAULT '0' COMMENT '审核0：不通过 1：通过',
  `pingfen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Item_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品评论表，用于存储用户对拼团商品的评论信息';
