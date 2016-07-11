ALTER TABLE `distribute_config`
ADD COLUMN `TxCustomize`  tinyint(1) NULL DEFAULT 1 COMMENT '提现是否审核' AFTER `Distribute_ShopOpen`;