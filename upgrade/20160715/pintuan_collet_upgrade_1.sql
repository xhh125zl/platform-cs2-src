
CREATE TABLE IF NOT EXISTS `pintuan_collet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productid` int(11) NOT NULL COMMENT '拼团-商品id',
  `userid` varchar(10) NOT NULL COMMENT '用户id',
  `addtime` varchar(10) NOT NULL COMMENT ' 添加时间',
  `users_id` varchar(10) NOT NULL COMMENT '商户id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
