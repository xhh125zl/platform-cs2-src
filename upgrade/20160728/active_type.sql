
CREATE TABLE IF NOT EXISTS `active_type` (
  `Type_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '类型id',
  `Type_Name` varchar(10) NOT NULL COMMENT '类型名称',
  `module` varchar(20) NOT NULL COMMENT '模型',
  `Users_ID` varchar(10) NOT NULL COMMENT '商城id',
  `Status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型状态',
  `addtime` int(11) DEFAULT '0',

  PRIMARY KEY (`Type_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='活动类型';
