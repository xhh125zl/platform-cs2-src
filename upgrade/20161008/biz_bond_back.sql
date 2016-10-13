/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : 103hbqw999

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-10-10 10:53:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for biz_bond_back
-- ----------------------------
DROP TABLE IF EXISTS `biz_bond_back`;
CREATE TABLE `biz_bond_back` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Users_Id` varchar(20) DEFAULT NULL,
  `Biz_Account` varchar(50) DEFAULT NULL COMMENT '商家帐号',
  `back_money` decimal(10,2) DEFAULT '0.00' COMMENT '退款金额',
  `info` text COMMENT '退款原因',
  `status` tinyint(3) DEFAULT '1' COMMENT '1申请中2成功3已退款-1驳回',
  `addtime` int(11) DEFAULT NULL,
  `alipay_account` varchar(255) DEFAULT NULL COMMENT '支付宝账号',
  `alipay_username` varchar(255) DEFAULT NULL COMMENT '支付宝账户姓名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='保证金退还表';
