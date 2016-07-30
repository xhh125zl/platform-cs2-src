ALTER TABLE `cloud_products` ADD COLUMN `Biz_ID`  int(11) NOT NULL DEFAULT 0 COMMENT '商家ID';
ALTER TABLE `cloud_products` ADD COLUMN `Products_Status`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '商品状态';