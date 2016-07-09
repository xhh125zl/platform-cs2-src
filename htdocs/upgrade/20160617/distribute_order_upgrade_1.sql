ALTER TABLE `distribute_order`
ADD COLUMN `Order_Peas`  int NULL DEFAULT 0 COMMENT '赠送豆豆' AFTER `UpgradeDistributes`;