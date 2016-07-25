CREATE TABLE IF NOT EXISTS `users_schedule` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(20) NOT NULL,
  `Money` float(11,2) DEFAULT NULL,
  `StartRunTime` time DEFAULT NULL COMMENT '开始执行时间',
  `RunType` tinyint(1) DEFAULT '1' COMMENT '执行方式：1、定时执行  2、按天执行  3、按月执行',
  `LastRunTime` int(11) DEFAULT 0 COMMENT "最后执行时间",
  `day` tinyint(2) DEFAULT 0 COMMENT "间隔执行的天数",
  `Status` tinyint(1) DEFAULT '0' COMMENT '计划任务执行状态：0、未执行  1、执行过',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
