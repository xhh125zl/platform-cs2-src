alter table users add domenable tinyint(1) DEFAULT '0' COMMENT '是否绑定了自己的域名' after Users_Password;
alter table users add domname varchar(50) DEFAULT '' COMMENT '绑定的域名' after domenable;