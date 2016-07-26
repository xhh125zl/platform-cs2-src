CREATE TABLE `third_login_config` (
  `users_id` char(10) NOT NULL COMMENT '商城id',
  `appid` char(18) NOT NULL COMMENT 'appid',
  `secret` varchar(50) NOT NULL COMMENT 'app secret',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:启用 0:不启用',
  `type` enum('qq','weixin') NOT NULL DEFAULT 'qq',
  UNIQUE KEY `uniq_users_id` (`users_id`,`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信登录配置表';

