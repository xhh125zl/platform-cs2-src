
CREATE TABLE IF NOT EXISTS `pintuan_order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) DEFAULT NULL,
  `User_ID` varchar(10) DEFAULT NULL COMMENT '会员id',
  `pintuan_id` int(11) DEFAULT NULL COMMENT '商品id',
  `pintuan_order_id` int(11) DEFAULT NULL COMMENT '活动id，',
  `qty` int(10) DEFAULT NULL COMMENT '购买数量',
  `price` varchar(50) DEFAULT NULL COMMENT '拼团价格',
  `total_price` varchar(50) DEFAULT NULL COMMENT '总价格',
  `Order_ID` int(11) DEFAULT NULL COMMENT '订单表id',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品明细表(用于存储每个团内参团的详细记录)';
