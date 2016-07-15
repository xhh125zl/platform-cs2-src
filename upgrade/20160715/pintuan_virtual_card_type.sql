CREATE TABLE IF NOT EXISTS `pintuan_virtual_card_type` (
  `Type_Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Type_Name` varchar(30) NOT NULL COMMENT '类型名称',
  `Users_Id` varchar(10) NOT NULL,
  `Type_CreateTime` int(10) NOT NULL DEFAULT '0',
  `Biz_Id` bigint(20) DEFAULT '0',
  PRIMARY KEY (`Type_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='虚拟卡类型表，用于标注虚拟卡的类型';