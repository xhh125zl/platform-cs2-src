alter table `shop_category` add Category_Bond decimal(10,2) DEFAULT 0 COMMENT '保证金' after Category_IndexShow; 
alter table `shop_category` add Category_CommissionRate int(11) DEFAULT 0 COMMENT '佣金发放比例' after Category_Bond; 
alter table `shop_category` add Category_ProfitRate int(11) DEFAULT 0 COMMENT '网站提成比例' after Category_CommissionRate; 