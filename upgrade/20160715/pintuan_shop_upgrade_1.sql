
CREATE TABLE IF NOT EXISTS `pintuan_shop` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT '表的主键',
  `usersid` varchar(255) DEFAULT NULL,
  `userid` int(255) DEFAULT NULL,
  `goodsid` int(50) DEFAULT NULL,
  `goods_name` varchar(50) DEFAULT NULL,
  `goods_canshu` varchar(100) DEFAULT '',
  `goods_num` int(50) DEFAULT NULL,
  `goods_price` varchar(20) DEFAULT NULL,
  `is_Draw` tinyint(1) DEFAULT NULL COMMENT '是否抽奖 0是 1不是 ',
  `is_One` tinyint(1) DEFAULT NULL COMMENT '是不是单购 0是  1不是',
  `is_vgoods` tinyint(1) DEFAULT NULL COMMENT '是否是虚拟  0是 1不是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8  COMMENT='拼团购物车表，存储用户拼团时对商品信息的临时存储';
