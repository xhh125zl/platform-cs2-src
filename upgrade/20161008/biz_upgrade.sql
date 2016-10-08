alter table `biz` add is_agree TinyInt(3) DEFAULT 0 COMMENT '是否签署协议0未签1已签' after Biz_Flag;
alter table `biz` add is_pay TinyInt(3) DEFAULT 0 COMMENT '0未付款1已付款' after is_agree; 
alter table `biz` add bond_free decimal(10,2) DEFAULT 0 COMMENT '保证金' after is_pay;
alter table `biz` add is_biz TinyInt(3) DEFAULT 0 COMMENT '0不是商家1成为商家' after bond_free;
alter table biz add addtype TinyInt(1) DEFAULT 0 COMMENT '0注册1后台添加' after is_biz;
alter table `biz` change Users_ExpiresTime expiredate int(11) DEFAULT 0 COMMENT '到期时间' after is_biz;
alter table `biz` change Users_Verify is_auth TinyInt(3) DEFAULT 0 COMMENT '是否认证0未认证1审核中2认证-1驳回' after is_agree;
