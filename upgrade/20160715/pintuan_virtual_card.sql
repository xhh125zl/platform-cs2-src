CREATE TABLE IF NOT EXISTS  `pintuan_virtual_card` (
  `Card_Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '卡密编号',
  `Users_Id` varchar(10) NOT NULL,
  `Card_Name` varchar(30) NOT NULL COMMENT '账号',
  `Card_Password` varchar(30) NOT NULL COMMENT '虚拟卡密码',
  `Type_Id` int(10) unsigned NOT NULL DEFAULT '0',
  `Products_Relation_ID` int(11) NOT NULL DEFAULT '0' COMMENT '关联产品编号',
  `Card_Status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '卡密状态',
  `Card_CreateTime` int(10) NOT NULL DEFAULT '0',
  `Card_UpadteTime` int(10) NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `Card_Description` varchar(200) DEFAULT NULL COMMENT '卡备注',
  `Biz_Id` bigint(20) unsigned DEFAULT '0',
  PRIMARY KEY (`Card_Id`),
  KEY `Users_ID` (`Users_Id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='虚拟卡表，用于存储选择商品类型为“其他”的表';