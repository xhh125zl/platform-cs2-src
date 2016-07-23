
CREATE TABLE IF NOT EXISTS `pintuan_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '团id',
  `productid` int(11) NOT NULL COMMENT '商品id',
  `users_id` varchar(10) NOT NULL COMMENT '商家id',
  `teamnum` int(11) NOT NULL COMMENT '团的人数',
  `teamstatus` int(11) NOT NULL COMMENT '团的状态，0拼团中，1拼团成功，2已中奖，3未中奖，4拼团失败',
  `addtime` int(11) NOT NULL,
  `userid` varchar(11) NOT NULL COMMENT '第一个参团的用户id',
  `starttime` int(11) DEFAULT NULL COMMENT '拼团开始时间',
  `stoptime` int(11) DEFAULT NULL COMMENT '拼团结束时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='拼团表，只有在用户开团时进行创建，用于存储团的相关信息';
