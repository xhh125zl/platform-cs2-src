ALTER TABLE biz add Biz_PayConfig varchar(255) DEFAULT NULL;
ALTER TABLE biz add Biz_Flag tinyint(1) DEFAULT 0;

ALTER TABLE shop_sales_payment add Payment_Type tinyint(1) DEFAULT 0;
ALTER TABLE shop_sales_payment add OpenID varchar(100) DEFAULT NULL;
ALTER TABLE shop_sales_payment add aliPayNo varchar(100) DEFAULT NULL;
ALTER TABLE shop_sales_payment add aliPayName varchar(20) DEFAULT NULL;
ALTER TABLE shop_sales_payment add Msg varchar(100) DEFAULT NULL;

DROP TABLE IF EXISTS `users_schedule`;
CREATE TABLE `users_schedule` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(20) NOT NULL,
  `Money` float(11,2) DEFAULT NULL,
  `StartRunTime` time DEFAULT NULL COMMENT '开始执行时间',
  `RunType` tinyint(1) DEFAULT '1' COMMENT '执行方式：1、定时执行  2、按天执行  3、按月执行',
  `Status` tinyint(1) DEFAULT '0' COMMENT '计划任务执行状态：0、未执行  1、执行过',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;