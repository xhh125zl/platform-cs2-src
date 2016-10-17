/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : 103hbqw999

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-09-07 16:38:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for biz_article_cate
-- ----------------------------
DROP TABLE IF EXISTS `biz_article_cate`;
CREATE TABLE `biz_article_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) DEFAULT NULL,
  `category_name` varchar(250) DEFAULT NULL COMMENT '分类名',
  `Category_Index` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='文章分类';
