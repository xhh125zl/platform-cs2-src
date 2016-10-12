alter table `biz_config` add year_fee text COMMENT '年费' after JieSuan;
alter table `biz_config` add bond_desc longtext COMMENT '追加保证金页面描述' after year_fee;
alter table biz_config add bannerimg varchar(250) DEFAULT NULL COMMENT '注册页面banner图片';
alter table biz_config add join_desc text  DEFAULT NULL COMMENT '加入我们描述';
 