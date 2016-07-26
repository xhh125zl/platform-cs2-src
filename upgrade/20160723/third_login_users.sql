CREATE TABLE `third_login_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` char(32) NOT NULL COMMENT '授权用户唯一标识',
  `access_token` varchar(30) NOT NULL COMMENT '接口调用凭证',
  `expires_in` int(10) unsigned NOT NULL COMMENT '有效期,单位秒',
  `refresh_token` varchar(30) NOT NULL COMMENT 'access_token接口调用凭证超时时间，单位（秒）',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `users_id` char(10) NOT NULL COMMENT '商城ID',
  `unionid` char(20) NOT NULL COMMENT '(微信有效)当且仅当该网站应用已获得该用户的userinfo授权时，才会出现该字段',
  `client` enum('qq','weixin') NOT NULL DEFAULT 'weixin' COMMENT '登录客户端',
  PRIMARY KEY (`id`),
  KEY `idx_users_id` (`users_id`,`client`,`openid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='三方登录用户';

