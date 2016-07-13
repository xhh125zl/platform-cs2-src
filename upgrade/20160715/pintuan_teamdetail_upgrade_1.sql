CREATE TABLE IF NOT EXISTS `pintuan_teamdetail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teamid` int(11) NOT NULL COMMENT '团id',
  `userid` int(11) NOT NULL COMMENT '参团的用户id',
  `addtime` varchar(10) NOT NULL COMMENT '参团时间',
  `order_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='团的人员表';
