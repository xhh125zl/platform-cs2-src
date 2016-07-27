
CREATE TABLE IF NOT EXISTS `biz_active` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `Users_ID` varchar(10) NOT NULL COMMENT '商城id',
  `Active_ID` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `Biz_ID` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '商家ID',
  `ListConfig` varchar(255) NOT NULL DEFAULT '' COMMENT '存储列表推荐的产品ID列表',
  `IndexConfig` varchar(255) NOT NULL DEFAULT '' COMMENT '存储推荐到首页的产品ID列表',
  `Status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '申请参加活动的状态  0 未参加  1  申请中  2 已同意  3 已拒绝',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '申请参加活动的时间',

  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='商家参与活动';
