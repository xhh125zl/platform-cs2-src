
CREATE TABLE IF NOT EXISTS `pintuan_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '活动订单id',
  `Users_ID` varchar(10) NOT NULL,
  `User_ID` varchar(10) NOT NULL COMMENT '会员id',
  `pintuan_id` int(11) DEFAULT NULL COMMENT '商品id',
  `pintuan_status` int(3) DEFAULT NULL COMMENT '拼团状态 0：拼团成功 1：生成拼团订单2：拼团中3：拼团失败',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  `pintuan_endtime` int(11) DEFAULT NULL,
  `order_status` int(3) DEFAULT NULL COMMENT '订单状态:0：待确认1：订单已生成未付款2：已付款3：已发货4：完成  5：退款中   6已退款  7 手动退款成功',
  `products_status` int(3) DEFAULT NULL COMMENT '商品活动状态 0：单购1：团购2：抽奖',
  `is_vgoods` tinyint(1) DEFAULT NULL COMMENT '是否是虚拟  0是 1不是',
  `is_ok` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='拼团订单扩展表(用于存储用户拼团过程中对参团信息的描述)';
