/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : 103hbqw999

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-09-13 11:08:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for biz_pay
-- ----------------------------
DROP TABLE IF EXISTS `biz_pay`;
CREATE TABLE `biz_pay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT NULL,
  `Biz_Account` varchar(50) DEFAULT NULL COMMENT '商家帐号',
  `bond_free` decimal(10,2) DEFAULT '0.00' COMMENT '保证金',
  `years` varchar(55) DEFAULT NULL COMMENT '年限(平台使用时间)',
  `year_free` decimal(10,2) DEFAULT '0.00' COMMENT '平台使用费',
  `total_money` decimal(10,2) DEFAULT '0.00',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态:0未付款1已付款',
  `order_paymentmethod` varchar(255) DEFAULT NULL COMMENT '支付方式',
  `type` tinyint(3) DEFAULT '1' COMMENT '1首次开通2年费3保证金',
  `addtime` int(11) DEFAULT NULL,
  `paytime` int(11) DEFAULT '0' COMMENT '支付时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商家认证支付记录';
