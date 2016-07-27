CREATE TABLE IF NOT EXISTS `pintuan_aword` (
  `Aword_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '抽奖id',
  `Users_ID` varchar(30) NOT NULL DEFAULT '',
  `goodsConfig` text NOT NULL COMMENT '商品统计信息，格式：数量和商品id列表,{total:3,list:[{id:3,AllowCount:5}]}',
  `teamTotal` int(10) NOT NULL DEFAULT '0' COMMENT '允许抽奖的团的总团数',
  `orderTotal` int(10) NOT NULL DEFAULT '0' COMMENT '参与抽奖的订单总数',
  `orderlist` text NOT NULL COMMENT '以逗号分割的订单列表id',
  `goodsTotal` int(10) NOT NULL COMMENT '开团里边的商品总数',
  `awordTeamlist` text NOT NULL   COMMENT '已中奖的团id列表',
  `noneAwordTeamlist` text NOT NULL  COMMENT '未中奖团id列表',
  `addtime` int(11) DEFAULT '0' COMMENT '抽奖的时间',
  PRIMARY KEY (`Aword_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='抽奖结果统计表';
