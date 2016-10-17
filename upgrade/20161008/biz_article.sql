/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : 103hbqw999

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-09-07 16:37:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for biz_article
-- ----------------------------
DROP TABLE IF EXISTS `biz_article`;
CREATE TABLE `biz_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) DEFAULT NULL,
  `atricle_title` varchar(255) DEFAULT NULL,
  `atricle_content` text COMMENT '文章内容',
  `category_id` tinyint(3) DEFAULT '0' COMMENT '文章分类',
  `Article_Editor` varchar(255) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
