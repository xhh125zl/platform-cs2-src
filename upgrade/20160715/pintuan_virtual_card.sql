CREATE TABLE IF NOT EXISTS  `pintuan_virtual_card` (
  `Card_Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '卡密编号',
  `User_Id` varchar(10) NOT NULL,
  `Card_Name` varchar(30) NOT NULL COMMENT '账号',
  `Card_Password` varchar(30) NOT NULL COMMENT '虚拟卡密码',
  `Type_Id` int(10) unsigned NOT NULL DEFAULT '0',
  `Products_Relation_ID` int(11) NOT NULL DEFAULT '0' COMMENT '关联产品编号',
  `Card_Status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '卡密状态',
  `Card_CreateTime` int(10) NOT NULL DEFAULT '0',
  `Card_UpadteTime` int(10) NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `Card_Description` varchar(200) DEFAULT NULL COMMENT '卡备注',
  PRIMARY KEY (`Card_Id`),
  KEY `userid` (`User_Id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;