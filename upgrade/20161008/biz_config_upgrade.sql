alter table `biz_config` add year_fee text COMMENT '年费' after JieSuan;
alter table `biz_config` add bond_desc longtext COMMENT '追加保证金页面描述' after year_fee;
 