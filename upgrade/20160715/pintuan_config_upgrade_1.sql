
CREATE TABLE IF NOT EXISTS `pintuan_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) DEFAULT NULL,
  `banner_img` text COMMENT 'banner图片',
  `SiteName` varchar(10) DEFAULT NULL COMMENT '拼团网名称',
  `info` text,
  `banner_url` text COMMENT '图片链接',
  `is_ems` int(2) DEFAULT '0' COMMENT '是否物流',
  `is_back` int(2) DEFAULT '0' COMMENT '是否启用退款',
  `CallEnable` int(2) DEFAULT NULL,
  `CallPhoneNumber` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='拼团配置表(拼团系统配置)';
