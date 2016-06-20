/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : 103hbqw999

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-06-20 10:31:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for action_num_record
-- ----------------------------
DROP TABLE IF EXISTS `action_num_record`;
CREATE TABLE `action_num_record` (
  `ID` int(20) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(20) DEFAULT NULL,
  `S_Module` varchar(30) DEFAULT NULL,
  `S_CreateTime` int(10) DEFAULT NULL,
  `User_ID` int(20) DEFAULT NULL,
  `AllDayLotteryTimes_have` int(10) NOT NULL,
  `Act_ID` int(10) DEFAULT NULL COMMENT '活动id',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of action_num_record
-- ----------------------------

-- ----------------------------
-- Table structure for ad_advertising
-- ----------------------------
DROP TABLE IF EXISTS `ad_advertising`;
CREATE TABLE `ad_advertising` (
  `AD_IDS` int(11) NOT NULL AUTO_INCREMENT,
  `Model_ID` int(10) DEFAULT '0',
  `AD_Name` varchar(255) DEFAULT NULL,
  `AD_Status` int(1) DEFAULT '0',
  `AD_Width` varchar(20) DEFAULT NULL,
  `AD_Height` varchar(20) DEFAULT NULL,
  `AD_Text` text,
  `Users_ID` varchar(11) DEFAULT NULL,
  `AD_CreateTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`AD_IDS`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ad_advertising
-- ----------------------------

-- ----------------------------
-- Table structure for ad_list
-- ----------------------------
DROP TABLE IF EXISTS `ad_list`;
CREATE TABLE `ad_list` (
  `AD_ID` int(11) NOT NULL AUTO_INCREMENT,
  `AD_IDS` int(11) DEFAULT NULL,
  `AD_Img` varchar(255) DEFAULT NULL,
  `AD_Link` varchar(255) DEFAULT NULL,
  `AD_StarTime` int(10) DEFAULT NULL,
  `AD_EndTime` int(10) DEFAULT NULL,
  `Users_ID` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`AD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ad_list
-- ----------------------------

-- ----------------------------
-- Table structure for ad_model
-- ----------------------------
DROP TABLE IF EXISTS `ad_model`;
CREATE TABLE `ad_model` (
  `Model_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Model_Name` varchar(255) DEFAULT NULL,
  `Model_Width` varchar(20) DEFAULT NULL,
  `Model_Height` varchar(20) DEFAULT NULL,
  `Model_Text` text,
  PRIMARY KEY (`Model_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ad_model
-- ----------------------------
INSERT INTO `ad_model` VALUES ('2', '刮刮卡顶部广告', '640', '60', '刮刮卡顶部广告');
INSERT INTO `ad_model` VALUES ('3', '水果达人顶部广告', '640', '60', '水果达人顶部广告');
INSERT INTO `ad_model` VALUES ('4', '欢乐大转盘顶部广告', '640', '60', '欢乐大转盘顶部广告');
INSERT INTO `ad_model` VALUES ('5', '一战到底顶部广告', '640', '60', '一战到底顶部广告');
INSERT INTO `ad_model` VALUES ('6', '抢红包首页', '640', '60', '抢红包首页');
INSERT INTO `ad_model` VALUES ('7', '抢红包邀请好友页面', '640', '60', '抢红包邀请好友页面');
INSERT INTO `ad_model` VALUES ('8', '抢红包好友帮助页面', '640', '60', '好友帮助拆红包页面');

-- ----------------------------
-- Table structure for agent
-- ----------------------------
DROP TABLE IF EXISTS `agent`;
CREATE TABLE `agent` (
  `Agent_ID` varchar(10) NOT NULL DEFAULT '',
  `Level_ID` varchar(10) DEFAULT NULL,
  `Agent_Name` varchar(50) DEFAULT NULL,
  `Agent_Sex` varchar(1) DEFAULT NULL,
  `Agent_Account` varchar(50) DEFAULT NULL,
  `Agent_Password` varchar(50) DEFAULT NULL,
  `Agent_Phone` varchar(50) DEFAULT NULL,
  `Agent_Mobile` varchar(50) DEFAULT NULL,
  `Agent_Email` varchar(50) DEFAULT NULL,
  `Agent_Status` bigint(1) DEFAULT '0',
  `Agent_Money` int(10) DEFAULT '0',
  `Agent_Notes` text,
  `Agent_CreateTime` int(10) DEFAULT '0',
  `Agent_Logo` varchar(255) DEFAULT NULL,
  `Agent_Copyright` text,
  `Agent_Title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Agent_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of agent
-- ----------------------------

-- ----------------------------
-- Table structure for agent_back_tie
-- ----------------------------
DROP TABLE IF EXISTS `agent_back_tie`;
CREATE TABLE `agent_back_tie` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '0',
  `Users_ID` varchar(10) NOT NULL DEFAULT '',
  `Disname` varchar(50) DEFAULT '' COMMENT '分销商',
  `Barjson` text,
  `Order_CreateTime` int(10) DEFAULT '0' COMMENT '下单时间',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of agent_back_tie
-- ----------------------------
INSERT INTO `agent_back_tie` VALUES ('1', '2', 'pl2hu3uczz', '7', '开启股东', '1465291773');
INSERT INTO `agent_back_tie` VALUES ('2', '2', 'pl2hu3uczz', '7', '关闭股东', '1465291781');
INSERT INTO `agent_back_tie` VALUES ('3', '2', 'pl2hu3uczz', '8', '开启股东', '1465291828');
INSERT INTO `agent_back_tie` VALUES ('4', '2', 'pl2hu3uczz', '8', '关闭股东', '1465291846');
INSERT INTO `agent_back_tie` VALUES ('5', '2', 'pl2hu3uczz', '2', '关闭股东', '1465374641');
INSERT INTO `agent_back_tie` VALUES ('6', '2', 'pl2hu3uczz', '2', '开启股东', '1465375568');
INSERT INTO `agent_back_tie` VALUES ('7', '2', 'pl2hu3uczz', '9', '关闭股东', '1465377517');
INSERT INTO `agent_back_tie` VALUES ('8', '2', 'pl2hu3uczz', '9', '开启股东', '1465377607');
INSERT INTO `agent_back_tie` VALUES ('9', '2', 'pl2hu3uczz', '9', '关闭股东', '1465379702');
INSERT INTO `agent_back_tie` VALUES ('10', '2', 'pl2hu3uczz', '6', '开启股东', '1465380732');
INSERT INTO `agent_back_tie` VALUES ('11', '2', 'pl2hu3uczz', '6', '关闭股东', '1465380797');
INSERT INTO `agent_back_tie` VALUES ('12', '2', 'pl2hu3uczz', '6', '开启股东', '1465380865');
INSERT INTO `agent_back_tie` VALUES ('13', '2', 'pl2hu3uczz', '6', '开启股东', '1465380967');
INSERT INTO `agent_back_tie` VALUES ('14', '2', 'pl2hu3uczz', '6', '开启股东', '1465381014');
INSERT INTO `agent_back_tie` VALUES ('15', '2', 'pl2hu3uczz', '6', '开启股东', '1465381094');
INSERT INTO `agent_back_tie` VALUES ('16', '2', 'pl2hu3uczz', '9', '开启股东', '1465381186');
INSERT INTO `agent_back_tie` VALUES ('17', '2', 'pl2hu3uczz', '15', '开启股东', '1466046050');
INSERT INTO `agent_back_tie` VALUES ('18', '2', 'pl2hu3uczz', '15', '关闭股东', '1466046094');
INSERT INTO `agent_back_tie` VALUES ('19', '2', 'pl2hu3uczz', '11', '关闭股东', '1466046592');

-- ----------------------------
-- Table structure for agent_level
-- ----------------------------
DROP TABLE IF EXISTS `agent_level`;
CREATE TABLE `agent_level` (
  `Level_ID` varchar(10) NOT NULL DEFAULT '',
  `Level_Name` varchar(50) DEFAULT NULL,
  `Level_Price_Web` int(10) DEFAULT '0',
  `Level_Price_Shop` int(10) DEFAULT '0',
  `Level_Price_Kefu` int(10) DEFAULT '0',
  `Level_Index` tinyint(2) DEFAULT NULL,
  `Level_Status` tinyint(1) DEFAULT NULL,
  `Level_Notes` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Level_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of agent_level
-- ----------------------------

-- ----------------------------
-- Table structure for agent_order
-- ----------------------------
DROP TABLE IF EXISTS `agent_order`;
CREATE TABLE `agent_order` (
  `Order_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL DEFAULT '',
  `User_ID` varchar(10) NOT NULL DEFAULT '',
  `Applyfor_Name` varchar(50) DEFAULT '' COMMENT '联系人',
  `Applyfor_Mobile` varchar(20) DEFAULT '' COMMENT '手机号',
  `Applyfor_WeixinID` varchar(100) DEFAULT '' COMMENT '购买人微信号',
  `Order_PaymentMethod` varchar(10) DEFAULT '' COMMENT '支付方式',
  `Order_PaymentInfo` varchar(255) DEFAULT '' COMMENT '支付信息  线下支付有用',
  `Order_TotalPrice` decimal(11,2) DEFAULT '0.00' COMMENT '订单金额',
  `Owner_ID` int(10) DEFAULT '0' COMMENT '上级ID',
  `Order_PayTime` int(10) DEFAULT '0' COMMENT '订单支付时间',
  `Order_PayID` varchar(100) DEFAULT '' COMMENT '订单支付号',
  `ProvinceId` int(10) unsigned DEFAULT '0' COMMENT '省份编号',
  `CityId` int(10) unsigned DEFAULT '0' COMMENT '城市编号',
  `AreaId` int(10) unsigned DEFAULT '0' COMMENT '区域编号',
  `Level_ID` int(10) DEFAULT '0' COMMENT '分销级别ID',
  `Level_Name` varchar(100) DEFAULT '' COMMENT '分销级别名称',
  `Order_Status` tinyint(1) DEFAULT '0' COMMENT '订单状态   0 待审核 1待付款 2已付款(已完成) 3取消申请',
  `Refuse_Be` varchar(255) DEFAULT '' COMMENT '申请拒绝原因',
  `Order_CreateTime` int(10) DEFAULT '0' COMMENT '下单时间',
  `Area` tinyint(1) unsigned DEFAULT NULL COMMENT '申请区域1，省级2市级3县级',
  `AreaMark` varchar(20) DEFAULT NULL COMMENT '地区描述',
  `Area_Concat` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`Order_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of agent_order
-- ----------------------------

-- ----------------------------
-- Table structure for anli
-- ----------------------------
DROP TABLE IF EXISTS `anli`;
CREATE TABLE `anli` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Descrition` longtext,
  `CreateTime` int(10) DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anli
-- ----------------------------

-- ----------------------------
-- Table structure for announce
-- ----------------------------
DROP TABLE IF EXISTS `announce`;
CREATE TABLE `announce` (
  `Announce_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Category_ID` int(10) DEFAULT '0',
  `Announce_Title` varchar(255) DEFAULT NULL,
  `Announce_Content` text,
  `Announce_Status` tinyint(1) DEFAULT '0',
  `Announce_CreateTime` int(10) DEFAULT '0',
  `Announce_Hits` int(10) DEFAULT '0',
  PRIMARY KEY (`Announce_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='公告存储表';

-- ----------------------------
-- Records of announce
-- ----------------------------

-- ----------------------------
-- Table structure for announce_category
-- ----------------------------
DROP TABLE IF EXISTS `announce_category`;
CREATE TABLE `announce_category` (
  `Category_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Category_Name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Category_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='公告分类表';

-- ----------------------------
-- Records of announce_category
-- ----------------------------

-- ----------------------------
-- Table structure for area
-- ----------------------------
DROP TABLE IF EXISTS `area`;
CREATE TABLE `area` (
  `area_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '索引ID',
  `area_name` varchar(50) NOT NULL COMMENT '地区名称',
  `area_parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '地区父ID',
  `area_sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `area_deep` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '地区深度，从1开始',
  `area_region` varchar(3) DEFAULT NULL COMMENT '大区名称',
  `area_code` int(10) DEFAULT '0',
  PRIMARY KEY (`area_id`),
  KEY `area_parent_id` (`area_parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45056 DEFAULT CHARSET=utf8 COMMENT='地区表';

-- ----------------------------
-- Records of area
-- ----------------------------
INSERT INTO `area` VALUES ('1', '北京', '0', '0', '1', '华北', '0');
INSERT INTO `area` VALUES ('2', '天津', '0', '0', '1', '华北', '0');
INSERT INTO `area` VALUES ('3', '河北', '0', '0', '1', '华北', '0');
INSERT INTO `area` VALUES ('4', '山西', '0', '0', '1', '华北', '0');
INSERT INTO `area` VALUES ('5', '内蒙古', '0', '0', '1', '华北', '0');
INSERT INTO `area` VALUES ('6', '辽宁', '0', '0', '1', '东北', '0');
INSERT INTO `area` VALUES ('7', '吉林', '0', '0', '1', '东北', '0');
INSERT INTO `area` VALUES ('8', '黑龙江', '0', '0', '1', '东北', '0');
INSERT INTO `area` VALUES ('9', '上海', '0', '0', '1', '华东', '0');
INSERT INTO `area` VALUES ('10', '江苏', '0', '0', '1', '华东', '0');
INSERT INTO `area` VALUES ('11', '浙江', '0', '0', '1', '华东', '0');
INSERT INTO `area` VALUES ('12', '安徽', '0', '0', '1', '华东', '0');
INSERT INTO `area` VALUES ('13', '福建', '0', '0', '1', '华南', '0');
INSERT INTO `area` VALUES ('14', '江西', '0', '0', '1', '华东', '0');
INSERT INTO `area` VALUES ('15', '山东', '0', '0', '1', '华东', '0');
INSERT INTO `area` VALUES ('16', '河南', '0', '0', '1', '华中', '0');
INSERT INTO `area` VALUES ('17', '湖北', '0', '0', '1', '华中', '0');
INSERT INTO `area` VALUES ('18', '湖南', '0', '0', '1', '华中', '0');
INSERT INTO `area` VALUES ('19', '广东', '0', '0', '1', '华南', '0');
INSERT INTO `area` VALUES ('20', '广西', '0', '0', '1', '华南', '0');
INSERT INTO `area` VALUES ('21', '海南', '0', '0', '1', '华南', '0');
INSERT INTO `area` VALUES ('22', '重庆', '0', '0', '1', '西南', '0');
INSERT INTO `area` VALUES ('23', '四川', '0', '0', '1', '西南', '0');
INSERT INTO `area` VALUES ('24', '贵州', '0', '0', '1', '西南', '0');
INSERT INTO `area` VALUES ('25', '云南', '0', '0', '1', '西南', '0');
INSERT INTO `area` VALUES ('26', '西藏', '0', '0', '1', '西南', '0');
INSERT INTO `area` VALUES ('27', '陕西', '0', '0', '1', '西北', '0');
INSERT INTO `area` VALUES ('28', '甘肃', '0', '0', '1', '西北', '0');
INSERT INTO `area` VALUES ('29', '青海', '0', '0', '1', '西北', '0');
INSERT INTO `area` VALUES ('30', '宁夏', '0', '0', '1', '西北', '0');
INSERT INTO `area` VALUES ('31', '新疆', '0', '0', '1', '西北', '0');
INSERT INTO `area` VALUES ('32', '台湾', '0', '0', '1', '港澳台', '0');
INSERT INTO `area` VALUES ('33', '香港', '0', '0', '1', '港澳台', '0');
INSERT INTO `area` VALUES ('34', '澳门', '0', '0', '1', '港澳台', '0');
INSERT INTO `area` VALUES ('35', '海外', '0', '0', '1', '海外', '0');
INSERT INTO `area` VALUES ('36', '北京市', '1', '0', '2', null, '131');
INSERT INTO `area` VALUES ('37', '东城区', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('38', '西城区', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('39', '上海市', '9', '0', '2', null, '289');
INSERT INTO `area` VALUES ('40', '天津市', '2', '0', '2', null, '332');
INSERT INTO `area` VALUES ('41', '朝阳区', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('42', '丰台区', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('43', '石景山区', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('44', '海淀区', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('45', '门头沟区', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('46', '房山区', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('47', '通州区', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('48', '顺义区', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('49', '昌平区', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('50', '大兴区', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('51', '怀柔区', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('52', '平谷区', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('53', '密云县', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('54', '延庆县', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('55', '和平区', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('56', '河东区', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('57', '河西区', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('58', '南开区', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('59', '河北区', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('60', '红桥区', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('61', '塘沽区', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('62', '重庆市', '22', '0', '2', null, '132');
INSERT INTO `area` VALUES ('64', '东丽区', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('65', '西青区', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('66', '津南区', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('67', '北辰区', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('68', '武清区', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('69', '宝坻区', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('70', '宁河县', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('71', '静海县', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('72', '蓟县', '40', '0', '3', null, '0');
INSERT INTO `area` VALUES ('73', '石家庄市', '3', '0', '2', null, '150');
INSERT INTO `area` VALUES ('74', '唐山市', '3', '0', '2', null, '265');
INSERT INTO `area` VALUES ('75', '秦皇岛市', '3', '0', '2', null, '148');
INSERT INTO `area` VALUES ('76', '邯郸市', '3', '0', '2', null, '151');
INSERT INTO `area` VALUES ('77', '邢台市', '3', '0', '2', null, '266');
INSERT INTO `area` VALUES ('78', '保定市', '3', '0', '2', null, '307');
INSERT INTO `area` VALUES ('79', '张家口市', '3', '0', '2', null, '264');
INSERT INTO `area` VALUES ('80', '承德市', '3', '0', '2', null, '207');
INSERT INTO `area` VALUES ('81', '衡水市', '3', '0', '2', null, '208');
INSERT INTO `area` VALUES ('82', '廊坊市', '3', '0', '2', null, '191');
INSERT INTO `area` VALUES ('83', '沧州市', '3', '0', '2', null, '149');
INSERT INTO `area` VALUES ('84', '太原市', '4', '0', '2', null, '176');
INSERT INTO `area` VALUES ('85', '大同市', '4', '0', '2', null, '355');
INSERT INTO `area` VALUES ('86', '阳泉市', '4', '0', '2', null, '357');
INSERT INTO `area` VALUES ('87', '长治市', '4', '0', '2', null, '356');
INSERT INTO `area` VALUES ('88', '晋城市', '4', '0', '2', null, '290');
INSERT INTO `area` VALUES ('89', '朔州市', '4', '0', '2', null, '237');
INSERT INTO `area` VALUES ('90', '晋中市', '4', '0', '2', null, '238');
INSERT INTO `area` VALUES ('91', '运城市', '4', '0', '2', null, '328');
INSERT INTO `area` VALUES ('92', '忻州市', '4', '0', '2', null, '367');
INSERT INTO `area` VALUES ('93', '临汾市', '4', '0', '2', null, '368');
INSERT INTO `area` VALUES ('94', '吕梁市', '4', '0', '2', null, '327');
INSERT INTO `area` VALUES ('95', '呼和浩特市', '5', '0', '2', null, '321');
INSERT INTO `area` VALUES ('96', '包头市', '5', '0', '2', null, '229');
INSERT INTO `area` VALUES ('97', '乌海市', '5', '0', '2', null, '123');
INSERT INTO `area` VALUES ('98', '赤峰市', '5', '0', '2', null, '297');
INSERT INTO `area` VALUES ('99', '通辽市', '5', '0', '2', null, '64');
INSERT INTO `area` VALUES ('100', '鄂尔多斯市', '5', '0', '2', null, '283');
INSERT INTO `area` VALUES ('101', '呼伦贝尔市', '5', '0', '2', null, '61');
INSERT INTO `area` VALUES ('102', '巴彦淖尔市', '5', '0', '2', null, '169');
INSERT INTO `area` VALUES ('103', '乌兰察布市', '5', '0', '2', null, '168');
INSERT INTO `area` VALUES ('104', '兴安盟', '5', '0', '2', null, '62');
INSERT INTO `area` VALUES ('105', '锡林郭勒盟', '5', '0', '2', null, '63');
INSERT INTO `area` VALUES ('106', '阿拉善盟', '5', '0', '2', null, '230');
INSERT INTO `area` VALUES ('107', '沈阳市', '6', '0', '2', null, '58');
INSERT INTO `area` VALUES ('108', '大连市', '6', '0', '2', null, '167');
INSERT INTO `area` VALUES ('109', '鞍山市', '6', '0', '2', null, '320');
INSERT INTO `area` VALUES ('110', '抚顺市', '6', '0', '2', null, '184');
INSERT INTO `area` VALUES ('111', '本溪市', '6', '0', '2', null, '227');
INSERT INTO `area` VALUES ('112', '丹东市', '6', '0', '2', null, '282');
INSERT INTO `area` VALUES ('113', '锦州市', '6', '0', '2', null, '166');
INSERT INTO `area` VALUES ('114', '营口市', '6', '0', '2', null, '281');
INSERT INTO `area` VALUES ('115', '阜新市', '6', '0', '2', null, '59');
INSERT INTO `area` VALUES ('116', '辽阳市', '6', '0', '2', null, '351');
INSERT INTO `area` VALUES ('117', '盘锦市', '6', '0', '2', null, '228');
INSERT INTO `area` VALUES ('118', '铁岭市', '6', '0', '2', null, '60');
INSERT INTO `area` VALUES ('119', '朝阳市', '6', '0', '2', null, '280');
INSERT INTO `area` VALUES ('120', '葫芦岛市', '6', '0', '2', null, '319');
INSERT INTO `area` VALUES ('121', '长春市', '7', '0', '2', null, '53');
INSERT INTO `area` VALUES ('122', '吉林市', '7', '0', '2', null, '55');
INSERT INTO `area` VALUES ('123', '四平市', '7', '0', '2', null, '56');
INSERT INTO `area` VALUES ('124', '辽源市', '7', '0', '2', null, '183');
INSERT INTO `area` VALUES ('125', '通化市', '7', '0', '2', null, '165');
INSERT INTO `area` VALUES ('126', '白山市', '7', '0', '2', null, '57');
INSERT INTO `area` VALUES ('127', '松原市', '7', '0', '2', null, '52');
INSERT INTO `area` VALUES ('128', '白城市', '7', '0', '2', null, '51');
INSERT INTO `area` VALUES ('129', '延边朝鲜族自治州', '7', '0', '2', null, '54');
INSERT INTO `area` VALUES ('130', '哈尔滨市', '8', '0', '2', null, '48');
INSERT INTO `area` VALUES ('131', '齐齐哈尔市', '8', '0', '2', null, '41');
INSERT INTO `area` VALUES ('132', '鸡西市', '8', '0', '2', null, '46');
INSERT INTO `area` VALUES ('133', '鹤岗市', '8', '0', '2', null, '43');
INSERT INTO `area` VALUES ('134', '双鸭山市', '8', '0', '2', null, '45');
INSERT INTO `area` VALUES ('135', '大庆市', '8', '0', '2', null, '50');
INSERT INTO `area` VALUES ('136', '伊春市', '8', '0', '2', null, '40');
INSERT INTO `area` VALUES ('137', '佳木斯市', '8', '0', '2', null, '42');
INSERT INTO `area` VALUES ('138', '七台河市', '8', '0', '2', null, '47');
INSERT INTO `area` VALUES ('139', '牡丹江市', '8', '0', '2', null, '49');
INSERT INTO `area` VALUES ('140', '黑河市', '8', '0', '2', null, '39');
INSERT INTO `area` VALUES ('141', '绥化市', '8', '0', '2', null, '44');
INSERT INTO `area` VALUES ('142', '大兴安岭地区', '8', '0', '2', null, '38');
INSERT INTO `area` VALUES ('143', '黄浦区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('144', '卢湾区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('145', '徐汇区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('146', '长宁区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('147', '静安区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('148', '普陀区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('149', '闸北区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('150', '虹口区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('151', '杨浦区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('152', '闵行区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('153', '宝山区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('154', '嘉定区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('155', '浦东新区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('156', '金山区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('157', '松江区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('158', '青浦区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('159', '南汇区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('160', '奉贤区', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('161', '崇明县', '39', '0', '3', null, '0');
INSERT INTO `area` VALUES ('162', '南京市', '10', '0', '2', null, '315');
INSERT INTO `area` VALUES ('163', '无锡市', '10', '0', '2', null, '317');
INSERT INTO `area` VALUES ('164', '徐州市', '10', '0', '2', null, '316');
INSERT INTO `area` VALUES ('165', '常州市', '10', '0', '2', null, '348');
INSERT INTO `area` VALUES ('166', '苏州市', '10', '0', '2', null, '224');
INSERT INTO `area` VALUES ('167', '南通市', '10', '0', '2', null, '161');
INSERT INTO `area` VALUES ('168', '连云港市', '10', '0', '2', null, '347');
INSERT INTO `area` VALUES ('169', '淮安市', '10', '0', '2', null, '162');
INSERT INTO `area` VALUES ('170', '盐城市', '10', '0', '2', null, '223');
INSERT INTO `area` VALUES ('171', '扬州市', '10', '0', '2', null, '346');
INSERT INTO `area` VALUES ('172', '镇江市', '10', '0', '2', null, '160');
INSERT INTO `area` VALUES ('173', '泰州市', '10', '0', '2', null, '276');
INSERT INTO `area` VALUES ('174', '宿迁市', '10', '0', '2', null, '277');
INSERT INTO `area` VALUES ('175', '杭州市', '11', '0', '2', null, '179');
INSERT INTO `area` VALUES ('176', '宁波市', '11', '0', '2', null, '180');
INSERT INTO `area` VALUES ('177', '温州市', '11', '0', '2', null, '178');
INSERT INTO `area` VALUES ('178', '嘉兴市', '11', '0', '2', null, '334');
INSERT INTO `area` VALUES ('179', '湖州市', '11', '0', '2', null, '294');
INSERT INTO `area` VALUES ('180', '绍兴市', '11', '0', '2', null, '293');
INSERT INTO `area` VALUES ('181', '舟山市', '11', '0', '2', null, '245');
INSERT INTO `area` VALUES ('182', '衢州市', '11', '0', '2', null, '243');
INSERT INTO `area` VALUES ('183', '金华市', '11', '0', '2', null, '333');
INSERT INTO `area` VALUES ('184', '台州市', '11', '0', '2', null, '244');
INSERT INTO `area` VALUES ('185', '丽水市', '11', '0', '2', null, '292');
INSERT INTO `area` VALUES ('186', '合肥市', '12', '0', '2', null, '127');
INSERT INTO `area` VALUES ('187', '芜湖市', '12', '0', '2', null, '129');
INSERT INTO `area` VALUES ('188', '蚌埠市', '12', '0', '2', null, '126');
INSERT INTO `area` VALUES ('189', '淮南市', '12', '0', '2', null, '250');
INSERT INTO `area` VALUES ('190', '马鞍山市', '12', '0', '2', null, '358');
INSERT INTO `area` VALUES ('191', '淮北市', '12', '0', '2', null, '253');
INSERT INTO `area` VALUES ('192', '铜陵市', '12', '0', '2', null, '337');
INSERT INTO `area` VALUES ('193', '安庆市', '12', '0', '2', null, '130');
INSERT INTO `area` VALUES ('194', '黄山市', '12', '0', '2', null, '252');
INSERT INTO `area` VALUES ('195', '滁州市', '12', '0', '2', null, '189');
INSERT INTO `area` VALUES ('196', '阜阳市', '12', '0', '2', null, '128');
INSERT INTO `area` VALUES ('197', '宿州市', '12', '0', '2', null, '370');
INSERT INTO `area` VALUES ('198', '巢湖市', '12', '0', '2', null, '251');
INSERT INTO `area` VALUES ('199', '六安市', '12', '0', '2', null, '298');
INSERT INTO `area` VALUES ('200', '亳州市', '12', '0', '2', null, '188');
INSERT INTO `area` VALUES ('201', '池州市', '12', '0', '2', null, '299');
INSERT INTO `area` VALUES ('202', '宣城市', '12', '0', '2', null, '190');
INSERT INTO `area` VALUES ('203', '福州市', '13', '0', '2', null, '300');
INSERT INTO `area` VALUES ('204', '厦门市', '13', '0', '2', null, '194');
INSERT INTO `area` VALUES ('205', '莆田市', '13', '0', '2', null, '195');
INSERT INTO `area` VALUES ('206', '三明市', '13', '0', '2', null, '254');
INSERT INTO `area` VALUES ('207', '泉州市', '13', '0', '2', null, '134');
INSERT INTO `area` VALUES ('208', '漳州市', '13', '0', '2', null, '255');
INSERT INTO `area` VALUES ('209', '南平市', '13', '0', '2', null, '133');
INSERT INTO `area` VALUES ('210', '龙岩市', '13', '0', '2', null, '193');
INSERT INTO `area` VALUES ('211', '宁德市', '13', '0', '2', null, '192');
INSERT INTO `area` VALUES ('212', '南昌市', '14', '0', '2', null, '163');
INSERT INTO `area` VALUES ('213', '景德镇市', '14', '0', '2', null, '225');
INSERT INTO `area` VALUES ('214', '萍乡市', '14', '0', '2', null, '350');
INSERT INTO `area` VALUES ('215', '九江市', '14', '0', '2', null, '349');
INSERT INTO `area` VALUES ('216', '新余市', '14', '0', '2', null, '164');
INSERT INTO `area` VALUES ('217', '鹰潭市', '14', '0', '2', null, '279');
INSERT INTO `area` VALUES ('218', '赣州市', '14', '0', '2', null, '365');
INSERT INTO `area` VALUES ('219', '吉安市', '14', '0', '2', null, '318');
INSERT INTO `area` VALUES ('220', '宜春市', '14', '0', '2', null, '278');
INSERT INTO `area` VALUES ('221', '抚州市', '14', '0', '2', null, '226');
INSERT INTO `area` VALUES ('222', '上饶市', '14', '0', '2', null, '364');
INSERT INTO `area` VALUES ('223', '济南市', '15', '0', '2', null, '288');
INSERT INTO `area` VALUES ('224', '青岛市', '15', '0', '2', null, '236');
INSERT INTO `area` VALUES ('225', '淄博市', '15', '0', '2', null, '354');
INSERT INTO `area` VALUES ('226', '枣庄市', '15', '0', '2', null, '172');
INSERT INTO `area` VALUES ('227', '东营市', '15', '0', '2', null, '174');
INSERT INTO `area` VALUES ('228', '烟台市', '15', '0', '2', null, '326');
INSERT INTO `area` VALUES ('229', '潍坊市', '15', '0', '2', null, '287');
INSERT INTO `area` VALUES ('230', '济宁市', '15', '0', '2', null, '286');
INSERT INTO `area` VALUES ('231', '泰安市', '15', '0', '2', null, '325');
INSERT INTO `area` VALUES ('232', '威海市', '15', '0', '2', null, '175');
INSERT INTO `area` VALUES ('233', '日照市', '15', '0', '2', null, '173');
INSERT INTO `area` VALUES ('234', '莱芜市', '15', '0', '2', null, '124');
INSERT INTO `area` VALUES ('235', '临沂市', '15', '0', '2', null, '234');
INSERT INTO `area` VALUES ('236', '德州市', '15', '0', '2', null, '372');
INSERT INTO `area` VALUES ('237', '聊城市', '15', '0', '2', null, '366');
INSERT INTO `area` VALUES ('238', '滨州市', '15', '0', '2', null, '235');
INSERT INTO `area` VALUES ('239', '菏泽市', '15', '0', '2', null, '353');
INSERT INTO `area` VALUES ('240', '郑州市', '16', '0', '2', null, '268');
INSERT INTO `area` VALUES ('241', '开封市', '16', '0', '2', null, '210');
INSERT INTO `area` VALUES ('242', '洛阳市', '16', '0', '2', null, '153');
INSERT INTO `area` VALUES ('243', '平顶山市', '16', '0', '2', null, '213');
INSERT INTO `area` VALUES ('244', '安阳市', '16', '0', '2', null, '267');
INSERT INTO `area` VALUES ('245', '鹤壁市', '16', '0', '2', null, '215');
INSERT INTO `area` VALUES ('246', '新乡市', '16', '0', '2', null, '152');
INSERT INTO `area` VALUES ('247', '焦作市', '16', '0', '2', null, '211');
INSERT INTO `area` VALUES ('248', '濮阳市', '16', '0', '2', null, '209');
INSERT INTO `area` VALUES ('249', '许昌市', '16', '0', '2', null, '155');
INSERT INTO `area` VALUES ('250', '漯河市', '16', '0', '2', null, '344');
INSERT INTO `area` VALUES ('251', '三门峡市', '16', '0', '2', null, '212');
INSERT INTO `area` VALUES ('252', '南阳市', '16', '0', '2', null, '309');
INSERT INTO `area` VALUES ('253', '商丘市', '16', '0', '2', null, '154');
INSERT INTO `area` VALUES ('254', '信阳市', '16', '0', '2', null, '214');
INSERT INTO `area` VALUES ('255', '周口市', '16', '0', '2', null, '308');
INSERT INTO `area` VALUES ('256', '驻马店市', '16', '0', '2', null, '269');
INSERT INTO `area` VALUES ('257', '济源市', '16', '0', '2', null, '1277');
INSERT INTO `area` VALUES ('258', '武汉市', '17', '0', '2', null, '218');
INSERT INTO `area` VALUES ('259', '黄石市', '17', '0', '2', null, '311');
INSERT INTO `area` VALUES ('260', '十堰市', '17', '0', '2', null, '216');
INSERT INTO `area` VALUES ('261', '宜昌市', '17', '0', '2', null, '270');
INSERT INTO `area` VALUES ('262', '襄樊市', '17', '0', '2', null, '156');
INSERT INTO `area` VALUES ('263', '鄂州市', '17', '0', '2', null, '122');
INSERT INTO `area` VALUES ('264', '荆门市', '17', '0', '2', null, '217');
INSERT INTO `area` VALUES ('265', '孝感市', '17', '0', '2', null, '310');
INSERT INTO `area` VALUES ('266', '荆州市', '17', '0', '2', null, '157');
INSERT INTO `area` VALUES ('267', '黄冈市', '17', '0', '2', null, '271');
INSERT INTO `area` VALUES ('268', '咸宁市', '17', '0', '2', null, '362');
INSERT INTO `area` VALUES ('269', '随州市', '17', '0', '2', null, '371');
INSERT INTO `area` VALUES ('270', '恩施土家族苗族自治州', '17', '0', '2', null, '373');
INSERT INTO `area` VALUES ('271', '仙桃市', '17', '0', '2', null, '0');
INSERT INTO `area` VALUES ('272', '潜江市', '17', '0', '2', null, '0');
INSERT INTO `area` VALUES ('273', '天门市', '17', '0', '2', null, '0');
INSERT INTO `area` VALUES ('274', '神农架林区', '17', '0', '2', null, '0');
INSERT INTO `area` VALUES ('275', '长沙市', '18', '0', '2', null, '158');
INSERT INTO `area` VALUES ('276', '株洲市', '18', '0', '2', null, '222');
INSERT INTO `area` VALUES ('277', '湘潭市', '18', '0', '2', null, '313');
INSERT INTO `area` VALUES ('278', '衡阳市', '18', '0', '2', null, '159');
INSERT INTO `area` VALUES ('279', '邵阳市', '18', '0', '2', null, '273');
INSERT INTO `area` VALUES ('280', '岳阳市', '18', '0', '2', null, '220');
INSERT INTO `area` VALUES ('281', '常德市', '18', '0', '2', null, '219');
INSERT INTO `area` VALUES ('282', '张家界市', '18', '0', '2', null, '312');
INSERT INTO `area` VALUES ('283', '益阳市', '18', '0', '2', null, '272');
INSERT INTO `area` VALUES ('284', '郴州市', '18', '0', '2', null, '275');
INSERT INTO `area` VALUES ('285', '永州市', '18', '0', '2', null, '314');
INSERT INTO `area` VALUES ('286', '怀化市', '18', '0', '2', null, '363');
INSERT INTO `area` VALUES ('287', '娄底市', '18', '0', '2', null, '221');
INSERT INTO `area` VALUES ('288', '湘西土家族苗族自治州', '18', '0', '2', null, '274');
INSERT INTO `area` VALUES ('289', '广州市', '19', '0', '2', null, '257');
INSERT INTO `area` VALUES ('290', '韶关市', '19', '0', '2', null, '137');
INSERT INTO `area` VALUES ('291', '深圳市', '19', '0', '2', null, '340');
INSERT INTO `area` VALUES ('292', '珠海市', '19', '0', '2', null, '140');
INSERT INTO `area` VALUES ('293', '汕头市', '19', '0', '2', null, '303');
INSERT INTO `area` VALUES ('294', '佛山市', '19', '0', '2', null, '138');
INSERT INTO `area` VALUES ('295', '江门市', '19', '0', '2', null, '302');
INSERT INTO `area` VALUES ('296', '湛江市', '19', '0', '2', null, '198');
INSERT INTO `area` VALUES ('297', '茂名市', '19', '0', '2', null, '139');
INSERT INTO `area` VALUES ('298', '肇庆市', '19', '0', '2', null, '338');
INSERT INTO `area` VALUES ('299', '惠州市', '19', '0', '2', null, '301');
INSERT INTO `area` VALUES ('300', '梅州市', '19', '0', '2', null, '141');
INSERT INTO `area` VALUES ('301', '汕尾市', '19', '0', '2', null, '339');
INSERT INTO `area` VALUES ('302', '河源市', '19', '0', '2', null, '200');
INSERT INTO `area` VALUES ('303', '阳江市', '19', '0', '2', null, '199');
INSERT INTO `area` VALUES ('304', '清远市', '19', '0', '2', null, '197');
INSERT INTO `area` VALUES ('305', '东莞市', '19', '0', '2', null, '119');
INSERT INTO `area` VALUES ('306', '中山市', '19', '0', '2', null, '187');
INSERT INTO `area` VALUES ('307', '潮州市', '19', '0', '2', null, '201');
INSERT INTO `area` VALUES ('308', '揭阳市', '19', '0', '2', null, '259');
INSERT INTO `area` VALUES ('309', '云浮市', '19', '0', '2', null, '258');
INSERT INTO `area` VALUES ('310', '南宁市', '20', '0', '2', null, '261');
INSERT INTO `area` VALUES ('311', '柳州市', '20', '0', '2', null, '305');
INSERT INTO `area` VALUES ('312', '桂林市', '20', '0', '2', null, '142');
INSERT INTO `area` VALUES ('313', '梧州市', '20', '0', '2', null, '304');
INSERT INTO `area` VALUES ('314', '北海市', '20', '0', '2', null, '295');
INSERT INTO `area` VALUES ('315', '防城港市', '20', '0', '2', null, '204');
INSERT INTO `area` VALUES ('316', '钦州市', '20', '0', '2', null, '145');
INSERT INTO `area` VALUES ('317', '贵港市', '20', '0', '2', null, '341');
INSERT INTO `area` VALUES ('318', '玉林市', '20', '0', '2', null, '361');
INSERT INTO `area` VALUES ('319', '百色市', '20', '0', '2', null, '203');
INSERT INTO `area` VALUES ('320', '贺州市', '20', '0', '2', null, '260');
INSERT INTO `area` VALUES ('321', '河池市', '20', '0', '2', null, '143');
INSERT INTO `area` VALUES ('322', '来宾市', '20', '0', '2', null, '202');
INSERT INTO `area` VALUES ('323', '崇左市', '20', '0', '2', null, '144');
INSERT INTO `area` VALUES ('324', '海口市', '21', '0', '2', null, '125');
INSERT INTO `area` VALUES ('325', '三亚市', '21', '0', '2', null, '121');
INSERT INTO `area` VALUES ('326', '五指山市', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('327', '琼海市', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('328', '儋州市', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('329', '文昌市', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('330', '万宁市', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('331', '东方市', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('332', '定安县', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('333', '屯昌县', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('334', '澄迈县', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('335', '临高县', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('336', '白沙黎族自治县', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('337', '昌江黎族自治县', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('338', '乐东黎族自治县', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('339', '陵水黎族自治县', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('340', '保亭黎族苗族自治县', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('341', '琼中黎族苗族自治县', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('342', '西沙群岛', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('343', '南沙群岛', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('344', '中沙群岛的岛礁及其海域', '21', '0', '2', null, '0');
INSERT INTO `area` VALUES ('345', '万州区', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('346', '涪陵区', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('347', '渝中区', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('348', '大渡口区', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('349', '江北区', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('350', '沙坪坝区', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('351', '九龙坡区', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('352', '南岸区', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('353', '北碚区', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('354', '双桥区', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('355', '万盛区', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('356', '渝北区', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('357', '巴南区', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('358', '黔江区', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('359', '长寿区', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('360', '綦江县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('361', '潼南县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('362', '铜梁县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('363', '大足县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('364', '荣昌县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('365', '璧山县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('366', '梁平县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('367', '城口县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('368', '丰都县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('369', '垫江县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('370', '武隆县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('371', '忠县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('372', '开县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('373', '云阳县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('374', '奉节县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('375', '巫山县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('376', '巫溪县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('377', '石柱土家族自治县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('378', '秀山土家族苗族自治县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('379', '酉阳土家族苗族自治县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('380', '彭水苗族土家族自治县', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('381', '江津市', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('382', '合川市', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('383', '永川市', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('384', '南川市', '62', '0', '3', null, '0');
INSERT INTO `area` VALUES ('385', '成都市', '23', '0', '2', null, '75');
INSERT INTO `area` VALUES ('386', '自贡市', '23', '0', '2', null, '78');
INSERT INTO `area` VALUES ('387', '攀枝花市', '23', '0', '2', null, '81');
INSERT INTO `area` VALUES ('388', '泸州市', '23', '0', '2', null, '331');
INSERT INTO `area` VALUES ('389', '德阳市', '23', '0', '2', null, '74');
INSERT INTO `area` VALUES ('390', '绵阳市', '23', '0', '2', null, '240');
INSERT INTO `area` VALUES ('391', '广元市', '23', '0', '2', null, '329');
INSERT INTO `area` VALUES ('392', '遂宁市', '23', '0', '2', null, '330');
INSERT INTO `area` VALUES ('393', '内江市', '23', '0', '2', null, '248');
INSERT INTO `area` VALUES ('394', '乐山市', '23', '0', '2', null, '79');
INSERT INTO `area` VALUES ('395', '南充市', '23', '0', '2', null, '291');
INSERT INTO `area` VALUES ('396', '眉山市', '23', '0', '2', null, '77');
INSERT INTO `area` VALUES ('397', '宜宾市', '23', '0', '2', null, '186');
INSERT INTO `area` VALUES ('398', '广安市', '23', '0', '2', null, '241');
INSERT INTO `area` VALUES ('399', '达州市', '23', '0', '2', null, '369');
INSERT INTO `area` VALUES ('400', '雅安市', '23', '0', '2', null, '76');
INSERT INTO `area` VALUES ('401', '巴中市', '23', '0', '2', null, '239');
INSERT INTO `area` VALUES ('402', '资阳市', '23', '0', '2', null, '242');
INSERT INTO `area` VALUES ('403', '阿坝藏族羌族自治州', '23', '0', '2', null, '185');
INSERT INTO `area` VALUES ('404', '甘孜藏族自治州', '23', '0', '2', null, '73');
INSERT INTO `area` VALUES ('405', '凉山彝族自治州', '23', '0', '2', null, '80');
INSERT INTO `area` VALUES ('406', '贵阳市', '24', '0', '2', null, '146');
INSERT INTO `area` VALUES ('407', '六盘水市', '24', '0', '2', null, '147');
INSERT INTO `area` VALUES ('408', '遵义市', '24', '0', '2', null, '262');
INSERT INTO `area` VALUES ('409', '安顺市', '24', '0', '2', null, '263');
INSERT INTO `area` VALUES ('410', '铜仁地区', '24', '0', '2', null, '205');
INSERT INTO `area` VALUES ('411', '黔西南布依族苗族自治州', '24', '0', '2', null, '343');
INSERT INTO `area` VALUES ('412', '毕节地区', '24', '0', '2', null, '206');
INSERT INTO `area` VALUES ('413', '黔东南苗族侗族自治州', '24', '0', '2', null, '342');
INSERT INTO `area` VALUES ('414', '黔南布依族苗族自治州', '24', '0', '2', null, '306');
INSERT INTO `area` VALUES ('415', '昆明市', '25', '0', '2', null, '104');
INSERT INTO `area` VALUES ('416', '曲靖市', '25', '0', '2', null, '249');
INSERT INTO `area` VALUES ('417', '玉溪市', '25', '0', '2', null, '106');
INSERT INTO `area` VALUES ('418', '保山市', '25', '0', '2', null, '112');
INSERT INTO `area` VALUES ('419', '昭通市', '25', '0', '2', null, '336');
INSERT INTO `area` VALUES ('420', '丽江市', '25', '0', '2', null, '114');
INSERT INTO `area` VALUES ('421', '思茅市', '25', '0', '2', null, '0');
INSERT INTO `area` VALUES ('422', '临沧市', '25', '0', '2', null, '110');
INSERT INTO `area` VALUES ('423', '楚雄彝族自治州', '25', '0', '2', null, '105');
INSERT INTO `area` VALUES ('424', '红河哈尼族彝族自治州', '25', '0', '2', null, '107');
INSERT INTO `area` VALUES ('425', '文山壮族苗族自治州', '25', '0', '2', null, '177');
INSERT INTO `area` VALUES ('426', '西双版纳傣族自治州', '25', '0', '2', null, '109');
INSERT INTO `area` VALUES ('427', '大理白族自治州', '25', '0', '2', null, '111');
INSERT INTO `area` VALUES ('428', '德宏傣族景颇族自治州', '25', '0', '2', null, '116');
INSERT INTO `area` VALUES ('429', '怒江傈僳族自治州', '25', '0', '2', null, '113');
INSERT INTO `area` VALUES ('430', '迪庆藏族自治州', '25', '0', '2', null, '115');
INSERT INTO `area` VALUES ('431', '拉萨市', '26', '0', '2', null, '100');
INSERT INTO `area` VALUES ('432', '昌都地区', '26', '0', '2', null, '99');
INSERT INTO `area` VALUES ('433', '山南地区', '26', '0', '2', null, '97');
INSERT INTO `area` VALUES ('434', '日喀则地区', '26', '0', '2', null, '102');
INSERT INTO `area` VALUES ('435', '那曲地区', '26', '0', '2', null, '101');
INSERT INTO `area` VALUES ('436', '阿里地区', '26', '0', '2', null, '103');
INSERT INTO `area` VALUES ('437', '林芝地区', '26', '0', '2', null, '98');
INSERT INTO `area` VALUES ('438', '西安市', '27', '0', '2', null, '233');
INSERT INTO `area` VALUES ('439', '铜川市', '27', '0', '2', null, '232');
INSERT INTO `area` VALUES ('440', '宝鸡市', '27', '0', '2', null, '171');
INSERT INTO `area` VALUES ('441', '咸阳市', '27', '0', '2', null, '323');
INSERT INTO `area` VALUES ('442', '渭南市', '27', '0', '2', null, '170');
INSERT INTO `area` VALUES ('443', '延安市', '27', '0', '2', null, '284');
INSERT INTO `area` VALUES ('444', '汉中市', '27', '0', '2', null, '352');
INSERT INTO `area` VALUES ('445', '榆林市', '27', '0', '2', null, '231');
INSERT INTO `area` VALUES ('446', '安康市', '27', '0', '2', null, '324');
INSERT INTO `area` VALUES ('447', '商洛市', '27', '0', '2', null, '285');
INSERT INTO `area` VALUES ('448', '兰州市', '28', '0', '2', null, '36');
INSERT INTO `area` VALUES ('449', '嘉峪关市', '28', '0', '2', null, '33');
INSERT INTO `area` VALUES ('450', '金昌市', '28', '0', '2', null, '34');
INSERT INTO `area` VALUES ('451', '白银市', '28', '0', '2', null, '35');
INSERT INTO `area` VALUES ('452', '天水市', '28', '0', '2', null, '196');
INSERT INTO `area` VALUES ('453', '武威市', '28', '0', '2', null, '118');
INSERT INTO `area` VALUES ('454', '张掖市', '28', '0', '2', null, '117');
INSERT INTO `area` VALUES ('455', '平凉市', '28', '0', '2', null, '359');
INSERT INTO `area` VALUES ('456', '酒泉市', '28', '0', '2', null, '37');
INSERT INTO `area` VALUES ('457', '庆阳市', '28', '0', '2', null, '135');
INSERT INTO `area` VALUES ('458', '定西市', '28', '0', '2', null, '136');
INSERT INTO `area` VALUES ('459', '陇南市', '28', '0', '2', null, '256');
INSERT INTO `area` VALUES ('460', '临夏回族自治州', '28', '0', '2', null, '182');
INSERT INTO `area` VALUES ('461', '甘南藏族自治州', '28', '0', '2', null, '247');
INSERT INTO `area` VALUES ('462', '西宁市', '29', '0', '2', null, '66');
INSERT INTO `area` VALUES ('463', '海东地区', '29', '0', '2', null, '69');
INSERT INTO `area` VALUES ('464', '海北藏族自治州', '29', '0', '2', null, '67');
INSERT INTO `area` VALUES ('465', '黄南藏族自治州', '29', '0', '2', null, '70');
INSERT INTO `area` VALUES ('466', '海南藏族自治州', '29', '0', '2', null, '68');
INSERT INTO `area` VALUES ('467', '果洛藏族自治州', '29', '0', '2', null, '72');
INSERT INTO `area` VALUES ('468', '玉树藏族自治州', '29', '0', '2', null, '71');
INSERT INTO `area` VALUES ('469', '海西蒙古族藏族自治州', '29', '0', '2', null, '65');
INSERT INTO `area` VALUES ('470', '银川市', '30', '0', '2', null, '360');
INSERT INTO `area` VALUES ('471', '石嘴山市', '30', '0', '2', null, '335');
INSERT INTO `area` VALUES ('472', '吴忠市', '30', '0', '2', null, '322');
INSERT INTO `area` VALUES ('473', '固原市', '30', '0', '2', null, '246');
INSERT INTO `area` VALUES ('474', '中卫市', '30', '0', '2', null, '181');
INSERT INTO `area` VALUES ('475', '乌鲁木齐市', '31', '0', '2', null, '92');
INSERT INTO `area` VALUES ('476', '克拉玛依市', '31', '0', '2', null, '95');
INSERT INTO `area` VALUES ('477', '吐鲁番地区', '31', '0', '2', null, '89');
INSERT INTO `area` VALUES ('478', '哈密地区', '31', '0', '2', null, '91');
INSERT INTO `area` VALUES ('479', '昌吉回族自治州', '31', '0', '2', null, '93');
INSERT INTO `area` VALUES ('480', '博尔塔拉蒙古自治州', '31', '0', '2', null, '88');
INSERT INTO `area` VALUES ('481', '巴音郭楞蒙古自治州', '31', '0', '2', null, '86');
INSERT INTO `area` VALUES ('482', '阿克苏地区', '31', '0', '2', null, '85');
INSERT INTO `area` VALUES ('483', '克孜勒苏柯尔克孜自治州', '31', '0', '2', null, '84');
INSERT INTO `area` VALUES ('484', '喀什地区', '31', '0', '2', null, '83');
INSERT INTO `area` VALUES ('485', '和田地区', '31', '0', '2', null, '82');
INSERT INTO `area` VALUES ('486', '伊犁哈萨克自治州', '31', '0', '2', null, '90');
INSERT INTO `area` VALUES ('487', '塔城地区', '31', '0', '2', null, '94');
INSERT INTO `area` VALUES ('488', '阿勒泰地区', '31', '0', '2', null, '96');
INSERT INTO `area` VALUES ('489', '石河子市', '31', '0', '2', null, '0');
INSERT INTO `area` VALUES ('490', '阿拉尔市', '31', '0', '2', null, '0');
INSERT INTO `area` VALUES ('491', '图木舒克市', '31', '0', '2', null, '0');
INSERT INTO `area` VALUES ('492', '五家渠市', '31', '0', '2', null, '0');
INSERT INTO `area` VALUES ('493', '台北市', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('494', '高雄市', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('495', '基隆市', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('496', '台中市', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('497', '台南市', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('498', '新竹市', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('499', '嘉义市', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('500', '台北县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('501', '宜兰县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('502', '桃园县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('503', '新竹县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('504', '苗栗县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('505', '台中县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('506', '彰化县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('507', '南投县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('508', '云林县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('509', '嘉义县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('510', '台南县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('511', '高雄县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('512', '屏东县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('513', '澎湖县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('514', '台东县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('515', '花莲县', '32', '0', '2', null, '0');
INSERT INTO `area` VALUES ('516', '中西区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('517', '东区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('518', '九龙城区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('519', '观塘区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('520', '南区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('521', '深水埗区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('522', '黄大仙区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('523', '湾仔区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('524', '油尖旺区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('525', '离岛区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('526', '葵青区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('527', '北区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('528', '西贡区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('529', '沙田区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('530', '屯门区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('531', '大埔区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('532', '荃湾区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('533', '元朗区', '33', '0', '2', null, '0');
INSERT INTO `area` VALUES ('534', '澳门特别行政区', '34', '0', '2', null, '2911');
INSERT INTO `area` VALUES ('535', '美国', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('536', '加拿大', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('537', '澳大利亚', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('538', '新西兰', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('539', '英国', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('540', '法国', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('541', '德国', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('542', '捷克', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('543', '荷兰', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('544', '瑞士', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('545', '希腊', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('546', '挪威', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('547', '瑞典', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('548', '丹麦', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('549', '芬兰', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('550', '爱尔兰', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('551', '奥地利', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('552', '意大利', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('553', '乌克兰', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('554', '俄罗斯', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('555', '西班牙', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('556', '韩国', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('557', '新加坡', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('558', '马来西亚', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('559', '印度', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('560', '泰国', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('561', '日本', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('562', '巴西', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('563', '阿根廷', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('564', '南非', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('565', '埃及', '45055', '0', '3', null, '0');
INSERT INTO `area` VALUES ('566', '其他', '36', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1126', '井陉县', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1127', '井陉矿区', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1128', '元氏县', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1129', '平山县', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1130', '新乐市', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1131', '新华区', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1132', '无极县', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1133', '晋州市', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1134', '栾城县', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1135', '桥东区', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1136', '桥西区', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1137', '正定县', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1138', '深泽县', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1139', '灵寿县', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1140', '藁城市', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1141', '行唐县', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1142', '裕华区', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1143', '赞皇县', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1144', '赵县', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1145', '辛集市', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1146', '长安区', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1147', '高邑县', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1148', '鹿泉市', '73', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1149', '丰南区', '74', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1150', '丰润区', '74', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1151', '乐亭县', '74', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1152', '古冶区', '74', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1153', '唐海县', '74', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1154', '开平区', '74', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1155', '滦南县', '74', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1156', '滦县', '74', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1157', '玉田县', '74', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1158', '路北区', '74', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1159', '路南区', '74', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1160', '迁安市', '74', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1161', '迁西县', '74', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1162', '遵化市', '74', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1163', '北戴河区', '75', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1164', '卢龙县', '75', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1165', '山海关区', '75', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1166', '抚宁县', '75', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1167', '昌黎县', '75', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1168', '海港区', '75', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1169', '青龙满族自治县', '75', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1170', '丛台区', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1171', '临漳县', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1172', '复兴区', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1173', '大名县', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1174', '峰峰矿区', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1175', '广平县', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1176', '成安县', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1177', '曲周县', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1178', '武安市', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1179', '永年县', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1180', '涉县', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1181', '磁县', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1182', '肥乡县', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1183', '邯山区', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1184', '邯郸县', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1185', '邱县', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1186', '馆陶县', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1187', '魏县', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1188', '鸡泽县', '76', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1189', '临城县', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1190', '临西县', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1191', '任县', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1192', '内丘县', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1193', '南和县', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1194', '南宫市', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1195', '威县', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1196', '宁晋县', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1197', '巨鹿县', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1198', '平乡县', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1199', '广宗县', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1200', '新河县', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1201', '柏乡县', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1202', '桥东区', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1203', '桥西区', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1204', '沙河市', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1205', '清河县', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1206', '邢台县', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1207', '隆尧县', '77', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1208', '北市区', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1209', '南市区', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1210', '博野县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1211', '唐县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1212', '安国市', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1213', '安新县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1214', '定兴县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1215', '定州市', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1216', '容城县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1217', '徐水县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1218', '新市区', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1219', '易县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1220', '曲阳县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1221', '望都县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1222', '涞水县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1223', '涞源县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1224', '涿州市', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1225', '清苑县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1226', '满城县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1227', '蠡县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1228', '阜平县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1229', '雄县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1230', '顺平县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1231', '高碑店市', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1232', '高阳县', '78', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1233', '万全县', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1234', '下花园区', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1235', '宣化区', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1236', '宣化县', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1237', '尚义县', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1238', '崇礼县', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1239', '康保县', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1240', '张北县', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1241', '怀安县', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1242', '怀来县', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1243', '桥东区', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1244', '桥西区', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1245', '沽源县', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1246', '涿鹿县', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1247', '蔚县', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1248', '赤城县', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1249', '阳原县', '79', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1250', '丰宁满族自治县', '80', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1251', '兴隆县', '80', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1252', '双桥区', '80', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1253', '双滦区', '80', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1254', '围场满族蒙古族自治县', '80', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1255', '宽城满族自治县', '80', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1256', '平泉县', '80', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1257', '承德县', '80', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1258', '滦平县', '80', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1259', '隆化县', '80', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1260', '鹰手营子矿区', '80', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1261', '冀州市', '81', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1262', '安平县', '81', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1263', '故城县', '81', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1264', '景县', '81', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1265', '枣强县', '81', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1266', '桃城区', '81', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1267', '武强县', '81', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1268', '武邑县', '81', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1269', '深州市', '81', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1270', '阜城县', '81', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1271', '饶阳县', '81', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1272', '三河市', '82', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1273', '固安县', '82', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1274', '大厂回族自治县', '82', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1275', '大城县', '82', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1276', '安次区', '82', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1277', '广阳区', '82', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1278', '文安县', '82', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1279', '永清县', '82', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1280', '霸州市', '82', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1281', '香河县', '82', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1282', '东光县', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1283', '任丘市', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1284', '南皮县', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1285', '吴桥县', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1286', '孟村回族自治县', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1287', '新华区', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1288', '沧县', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1289', '河间市', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1290', '泊头市', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1291', '海兴县', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1292', '献县', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1293', '盐山县', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1294', '肃宁县', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1295', '运河区', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1296', '青县', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1297', '黄骅市', '83', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1298', '万柏林区', '84', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1299', '古交市', '84', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1300', '娄烦县', '84', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1301', '小店区', '84', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1302', '尖草坪区', '84', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1303', '晋源区', '84', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1304', '杏花岭区', '84', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1305', '清徐县', '84', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1306', '迎泽区', '84', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1307', '阳曲县', '84', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1308', '南郊区', '85', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1309', '城区', '85', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1310', '大同县', '85', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1311', '天镇县', '85', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1312', '左云县', '85', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1313', '广灵县', '85', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1314', '新荣区', '85', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1315', '浑源县', '85', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1316', '灵丘县', '85', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1317', '矿区', '85', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1318', '阳高县', '85', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1319', '城区', '86', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1320', '平定县', '86', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1321', '盂县', '86', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1322', '矿区', '86', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1323', '郊区', '86', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1324', '城区', '87', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1325', '壶关县', '87', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1326', '屯留县', '87', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1327', '平顺县', '87', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1328', '武乡县', '87', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1329', '沁县', '87', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1330', '沁源县', '87', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1331', '潞城市', '87', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1332', '襄垣县', '87', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1333', '郊区', '87', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1334', '长子县', '87', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1335', '长治县', '87', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1336', '黎城县', '87', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1337', '城区', '88', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1338', '沁水县', '88', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1339', '泽州县', '88', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1340', '阳城县', '88', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1341', '陵川县', '88', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1342', '高平市', '88', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1343', '右玉县', '89', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1344', '山阴县', '89', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1345', '平鲁区', '89', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1346', '应县', '89', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1347', '怀仁县', '89', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1348', '朔城区', '89', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1349', '介休市', '90', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1350', '和顺县', '90', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1351', '太谷县', '90', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1352', '寿阳县', '90', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1353', '左权县', '90', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1354', '平遥县', '90', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1355', '昔阳县', '90', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1356', '榆次区', '90', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1357', '榆社县', '90', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1358', '灵石县', '90', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1359', '祁县', '90', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1360', '万荣县', '91', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1361', '临猗县', '91', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1362', '垣曲县', '91', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1363', '夏县', '91', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1364', '平陆县', '91', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1365', '新绛县', '91', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1366', '永济市', '91', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1367', '河津市', '91', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1368', '盐湖区', '91', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1369', '稷山县', '91', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1370', '绛县', '91', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1371', '芮城县', '91', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1372', '闻喜县', '91', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1373', '五台县', '92', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1374', '五寨县', '92', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1375', '代县', '92', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1376', '保德县', '92', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1377', '偏关县', '92', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1378', '原平市', '92', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1379', '宁武县', '92', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1380', '定襄县', '92', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1381', '岢岚县', '92', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1382', '忻府区', '92', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1383', '河曲县', '92', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1384', '神池县', '92', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1385', '繁峙县', '92', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1386', '静乐县', '92', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1387', '乡宁县', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1388', '侯马市', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1389', '古县', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1390', '吉县', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1391', '大宁县', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1392', '安泽县', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1393', '尧都区', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1394', '曲沃县', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1395', '永和县', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1396', '汾西县', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1397', '洪洞县', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1398', '浮山县', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1399', '翼城县', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1400', '蒲县', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1401', '襄汾县', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1402', '隰县', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1403', '霍州市', '93', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1404', '中阳县', '94', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1405', '临县', '94', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1406', '交口县', '94', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1407', '交城县', '94', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1408', '兴县', '94', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1409', '孝义市', '94', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1410', '岚县', '94', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1411', '文水县', '94', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1412', '方山县', '94', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1413', '柳林县', '94', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1414', '汾阳市', '94', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1415', '石楼县', '94', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1416', '离石区', '94', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1417', '和林格尔县', '95', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1418', '回民区', '95', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1419', '土默特左旗', '95', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1420', '托克托县', '95', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1421', '新城区', '95', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1422', '武川县', '95', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1423', '清水河县', '95', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1424', '玉泉区', '95', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1425', '赛罕区', '95', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1426', '东河区', '96', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1427', '九原区', '96', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1428', '固阳县', '96', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1429', '土默特右旗', '96', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1430', '昆都仑区', '96', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1431', '白云矿区', '96', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1432', '石拐区', '96', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1433', '达尔罕茂明安联合旗', '96', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1434', '青山区', '96', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1435', '乌达区', '97', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1436', '海勃湾区', '97', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1437', '海南区', '97', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1438', '元宝山区', '98', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1439', '克什克腾旗', '98', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1440', '喀喇沁旗', '98', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1441', '宁城县', '98', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1442', '巴林右旗', '98', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1443', '巴林左旗', '98', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1444', '敖汉旗', '98', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1445', '松山区', '98', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1446', '林西县', '98', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1447', '红山区', '98', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1448', '翁牛特旗', '98', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1449', '阿鲁科尔沁旗', '98', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1450', '奈曼旗', '99', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1451', '库伦旗', '99', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1452', '开鲁县', '99', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1453', '扎鲁特旗', '99', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1454', '科尔沁区', '99', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1455', '科尔沁左翼中旗', '99', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1456', '科尔沁左翼后旗', '99', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1457', '霍林郭勒市', '99', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1458', '东胜区', '100', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1459', '乌审旗', '100', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1460', '伊金霍洛旗', '100', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1461', '准格尔旗', '100', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1462', '杭锦旗', '100', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1463', '达拉特旗', '100', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1464', '鄂东胜区', '100', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1465', '鄂托克前旗', '100', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1466', '鄂托克旗', '100', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1467', '扎兰屯市', '101', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1468', '新巴尔虎右旗', '101', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1469', '新巴尔虎左旗', '101', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1470', '根河市', '101', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1471', '海拉尔区', '101', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1472', '满洲里市', '101', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1473', '牙克石市', '101', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1474', '莫力达瓦达斡尔族自治旗', '101', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1475', '鄂伦春自治旗', '101', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1476', '鄂温克族自治旗', '101', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1477', '阿荣旗', '101', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1478', '陈巴尔虎旗', '101', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1479', '额尔古纳市', '101', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1480', '临河区', '102', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1481', '乌拉特中旗', '102', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1482', '乌拉特前旗', '102', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1483', '乌拉特后旗', '102', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1484', '五原县', '102', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1485', '杭锦后旗', '102', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1486', '磴口县', '102', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1487', '丰镇市', '103', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1488', '兴和县', '103', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1489', '凉城县', '103', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1490', '化德县', '103', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1491', '卓资县', '103', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1492', '商都县', '103', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1493', '四子王旗', '103', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1494', '察哈尔右翼中旗', '103', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1495', '察哈尔右翼前旗', '103', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1496', '察哈尔右翼后旗', '103', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1497', '集宁区', '103', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1498', '乌兰浩特市', '104', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1499', '扎赉特旗', '104', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1500', '科尔沁右翼中旗', '104', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1501', '科尔沁右翼前旗', '104', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1502', '突泉县', '104', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1503', '阿尔山市', '104', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1504', '东乌珠穆沁旗', '105', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1505', '二连浩特市', '105', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1506', '多伦县', '105', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1507', '太仆寺旗', '105', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1508', '正蓝旗', '105', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1509', '正镶白旗', '105', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1510', '苏尼特右旗', '105', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1511', '苏尼特左旗', '105', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1512', '西乌珠穆沁旗', '105', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1513', '锡林浩特市', '105', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1514', '镶黄旗', '105', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1515', '阿巴嘎旗', '105', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1516', '阿拉善右旗', '106', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1517', '阿拉善左旗', '106', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1518', '额济纳旗', '106', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1519', '东陵区', '107', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1520', '于洪区', '107', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1521', '和平区', '107', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1522', '大东区', '107', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1523', '康平县', '107', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1524', '新民市', '107', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1525', '沈北新区', '107', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1526', '沈河区', '107', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1527', '法库县', '107', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1528', '皇姑区', '107', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1529', '苏家屯区', '107', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1530', '辽中县', '107', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1531', '铁西区', '107', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1532', '中山区', '108', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1533', '庄河市', '108', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1534', '旅顺口区', '108', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1535', '普兰店市', '108', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1536', '沙河口区', '108', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1537', '瓦房店市', '108', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1538', '甘井子区', '108', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1539', '西岗区', '108', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1540', '金州区', '108', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1541', '长海县', '108', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1542', '千山区', '109', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1543', '台安县', '109', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1544', '岫岩满族自治县', '109', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1545', '海城市', '109', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1546', '立山区', '109', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1547', '铁东区', '109', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1548', '铁西区', '109', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1549', '东洲区', '110', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1550', '抚顺县', '110', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1551', '新宾满族自治县', '110', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1552', '新抚区', '110', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1553', '望花区', '110', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1554', '清原满族自治县', '110', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1555', '顺城区', '110', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1556', '南芬区', '111', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1557', '平山区', '111', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1558', '明山区', '111', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1559', '本溪满族自治县', '111', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1560', '桓仁满族自治县', '111', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1561', '溪湖区', '111', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1562', '东港市', '112', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1563', '元宝区', '112', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1564', '凤城市', '112', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1565', '宽甸满族自治县', '112', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1566', '振兴区', '112', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1567', '振安区', '112', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1568', '义县', '113', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1569', '凌河区', '113', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1570', '凌海市', '113', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1571', '北镇市', '113', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1572', '古塔区', '113', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1573', '太和区', '113', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1574', '黑山县', '113', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1575', '大石桥市', '114', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1576', '盖州市', '114', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1577', '站前区', '114', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1578', '老边区', '114', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1579', '西市区', '114', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1580', '鲅鱼圈区', '114', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1581', '太平区', '115', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1582', '彰武县', '115', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1583', '新邱区', '115', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1584', '海州区', '115', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1585', '清河门区', '115', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1586', '细河区', '115', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1587', '蒙古族自治县', '115', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1588', '太子河区', '116', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1589', '宏伟区', '116', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1590', '弓长岭区', '116', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1591', '文圣区', '116', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1592', '灯塔市', '116', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1593', '白塔区', '116', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1594', '辽阳县', '116', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1595', '兴隆台区', '117', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1596', '双台子区', '117', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1597', '大洼县', '117', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1598', '盘山县', '117', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1599', '开原市', '118', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1600', '昌图县', '118', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1601', '清河区', '118', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1602', '西丰县', '118', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1603', '调兵山市', '118', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1604', '铁岭县', '118', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1605', '银州区', '118', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1606', '凌源市', '119', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1607', '北票市', '119', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1608', '双塔区', '119', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1609', '喀喇沁左翼蒙古族自治县', '119', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1610', '建平县', '119', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1611', '朝阳县', '119', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1612', '龙城区', '119', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1613', '兴城市', '120', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1614', '南票区', '120', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1615', '建昌县', '120', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1616', '绥中县', '120', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1617', '连山区', '120', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1618', '龙港区', '120', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1619', '九台市', '121', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1620', '二道区', '121', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1621', '农安县', '121', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1622', '南关区', '121', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1623', '双阳区', '121', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1624', '宽城区', '121', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1625', '德惠市', '121', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1626', '朝阳区', '121', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1627', '榆树市', '121', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1628', '绿园区', '121', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1629', '丰满区', '122', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1630', '昌邑区', '122', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1631', '桦甸市', '122', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1632', '永吉县', '122', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1633', '磐石市', '122', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1634', '舒兰市', '122', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1635', '船营区', '122', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1636', '蛟河市', '122', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1637', '龙潭区', '122', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1638', '伊通满族自治县', '123', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1639', '公主岭市', '123', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1640', '双辽市', '123', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1641', '梨树县', '123', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1642', '铁东区', '123', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1643', '铁西区', '123', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1644', '东丰县', '124', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1645', '东辽县', '124', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1646', '西安区', '124', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1647', '龙山区', '124', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1648', '东昌区', '125', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1649', '二道江区', '125', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1650', '柳河县', '125', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1651', '梅河口市', '125', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1652', '辉南县', '125', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1653', '通化县', '125', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1654', '集安市', '125', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1655', '临江市', '126', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1656', '八道江区', '126', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1657', '抚松县', '126', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1658', '江源区', '126', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1659', '长白朝鲜族自治县', '126', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1660', '靖宇县', '126', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1661', '干安县', '127', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1662', '前郭尔罗斯蒙古族自治县', '127', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1663', '宁江区', '127', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1664', '扶余县', '127', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1665', '长岭县', '127', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1666', '大安市', '128', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1667', '洮北区', '128', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1668', '洮南市', '128', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1669', '通榆县', '128', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1670', '镇赉县', '128', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1671', '和龙市', '129', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1672', '图们市', '129', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1673', '安图县', '129', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1674', '延吉市', '129', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1675', '敦化市', '129', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1676', '汪清县', '129', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1677', '珲春市', '129', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1678', '龙井市', '129', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1679', '五常市', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1680', '依兰县', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1681', '南岗区', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1682', '双城市', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1683', '呼兰区', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1684', '哈尔滨市道里区', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1685', '宾县', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1686', '尚志市', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1687', '巴彦县', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1688', '平房区', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1689', '延寿县', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1690', '方正县', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1691', '木兰县', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1692', '松北区', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1693', '通河县', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1694', '道外区', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1695', '阿城区', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1696', '香坊区', '130', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1697', '依安县', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1698', '克东县', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1699', '克山县', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1700', '富拉尔基区', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1701', '富裕县', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1702', '建华区', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1703', '拜泉县', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1704', '昂昂溪区', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1705', '梅里斯达斡尔族区', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1706', '泰来县', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1707', '甘南县', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1708', '碾子山区', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1709', '讷河市', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1710', '铁锋区', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1711', '龙江县', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1712', '龙沙区', '131', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1713', '城子河区', '132', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1714', '密山市', '132', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1715', '恒山区', '132', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1716', '梨树区', '132', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1717', '滴道区', '132', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1718', '虎林市', '132', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1719', '鸡东县', '132', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1720', '鸡冠区', '132', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1721', '麻山区', '132', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1722', '东山区', '133', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1723', '兴安区', '133', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1724', '兴山区', '133', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1725', '南山区', '133', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1726', '向阳区', '133', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1727', '工农区', '133', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1728', '绥滨县', '133', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1729', '萝北县', '133', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1730', '友谊县', '134', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1731', '四方台区', '134', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1732', '宝山区', '134', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1733', '宝清县', '134', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1734', '尖山区', '134', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1735', '岭东区', '134', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1736', '集贤县', '134', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1737', '饶河县', '134', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1738', '大同区', '135', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1739', '杜尔伯特蒙古族自治县', '135', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1740', '林甸县', '135', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1741', '红岗区', '135', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1742', '肇州县', '135', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1743', '肇源县', '135', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1744', '胡路区', '135', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1745', '萨尔图区', '135', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1746', '龙凤区', '135', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1747', '上甘岭区', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1748', '乌伊岭区', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1749', '乌马河区', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1750', '五营区', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1751', '伊春区', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1752', '南岔区', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1753', '友好区', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1754', '嘉荫县', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1755', '带岭区', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1756', '新青区', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1757', '汤旺河区', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1758', '红星区', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1759', '美溪区', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1760', '翠峦区', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1761', '西林区', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1762', '金山屯区', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1763', '铁力市', '136', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1764', '东风区', '137', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1765', '前进区', '137', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1766', '同江市', '137', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1767', '向阳区', '137', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1768', '富锦市', '137', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1769', '抚远县', '137', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1770', '桦南县', '137', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1771', '桦川县', '137', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1772', '汤原县', '137', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1773', '郊区', '137', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1774', '勃利县', '138', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1775', '新兴区', '138', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1776', '桃山区', '138', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1777', '茄子河区', '138', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1778', '东宁县', '139', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1779', '东安区', '139', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1780', '宁安市', '139', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1781', '林口县', '139', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1782', '海林市', '139', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1783', '爱民区', '139', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1784', '穆棱市', '139', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1785', '绥芬河市', '139', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1786', '西安区', '139', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1787', '阳明区', '139', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1788', '五大连池市', '140', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1789', '北安市', '140', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1790', '嫩江县', '140', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1791', '孙吴县', '140', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1792', '爱辉区', '140', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1793', '车逊克县', '140', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1794', '逊克县', '140', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1795', '兰西县', '141', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1796', '安达市', '141', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1797', '庆安县', '141', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1798', '明水县', '141', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1799', '望奎县', '141', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1800', '海伦市', '141', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1801', '绥化市北林区', '141', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1802', '绥棱县', '141', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1803', '肇东市', '141', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1804', '青冈县', '141', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1805', '呼玛县', '142', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1806', '塔河县', '142', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1807', '大兴安岭地区加格达奇区', '142', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1808', '大兴安岭地区呼中区', '142', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1809', '大兴安岭地区新林区', '142', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1810', '大兴安岭地区松岭区', '142', '0', '3', null, '0');
INSERT INTO `area` VALUES ('1811', '漠河县', '142', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2027', '下关区', '162', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2028', '六合区', '162', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2029', '建邺区', '162', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2030', '栖霞区', '162', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2031', '江宁区', '162', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2032', '浦口区', '162', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2033', '溧水县', '162', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2034', '玄武区', '162', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2035', '白下区', '162', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2036', '秦淮区', '162', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2037', '雨花台区', '162', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2038', '高淳县', '162', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2039', '鼓楼区', '162', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2040', '北塘区', '163', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2041', '南长区', '163', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2042', '宜兴市', '163', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2043', '崇安区', '163', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2044', '惠山区', '163', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2045', '江阴市', '163', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2046', '滨湖区', '163', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2047', '锡山区', '163', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2048', '丰县', '164', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2049', '九里区', '164', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2050', '云龙区', '164', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2051', '新沂市', '164', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2052', '沛县', '164', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2053', '泉山区', '164', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2054', '睢宁县', '164', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2055', '贾汪区', '164', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2056', '邳州市', '164', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2057', '铜山县', '164', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2058', '鼓楼区', '164', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2059', '天宁区', '165', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2060', '戚墅堰区', '165', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2061', '新北区', '165', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2062', '武进区', '165', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2063', '溧阳市', '165', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2064', '金坛市', '165', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2065', '钟楼区', '165', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2066', '吴中区', '166', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2067', '吴江市', '166', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2068', '太仓市', '166', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2069', '常熟市', '166', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2070', '平江区', '166', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2071', '张家港市', '166', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2072', '昆山市', '166', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2073', '沧浪区', '166', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2074', '相城区', '166', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2075', '苏州工业园区', '166', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2076', '虎丘区', '166', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2077', '金阊区', '166', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2078', '启东市', '167', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2079', '如东县', '167', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2080', '如皋市', '167', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2081', '崇川区', '167', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2082', '海安县', '167', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2083', '海门市', '167', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2084', '港闸区', '167', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2085', '通州市', '167', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2086', '东海县', '168', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2087', '新浦区', '168', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2088', '海州区', '168', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2089', '灌云县', '168', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2090', '灌南县', '168', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2091', '赣榆县', '168', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2092', '连云区', '168', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2093', '楚州区', '169', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2094', '洪泽县', '169', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2095', '涟水县', '169', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2096', '淮阴区', '169', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2097', '清河区', '169', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2098', '清浦区', '169', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2099', '盱眙县', '169', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2100', '金湖县', '169', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2101', '东台市', '170', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2102', '亭湖区', '170', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2103', '响水县', '170', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2104', '大丰市', '170', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2105', '射阳县', '170', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2106', '建湖县', '170', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2107', '滨海县', '170', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2108', '盐都区', '170', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2109', '阜宁县', '170', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2110', '仪征市', '171', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2111', '宝应县', '171', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2112', '广陵区', '171', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2113', '江都市', '171', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2114', '维扬区', '171', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2115', '邗江区', '171', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2116', '高邮市', '171', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2117', '丹徒区', '172', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2118', '丹阳市', '172', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2119', '京口区', '172', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2120', '句容市', '172', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2121', '扬中市', '172', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2122', '润州区', '172', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2123', '兴化市', '173', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2124', '姜堰市', '173', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2125', '泰兴市', '173', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2126', '海陵区', '173', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2127', '靖江市', '173', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2128', '高港区', '173', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2129', '宿城区', '174', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2130', '宿豫区', '174', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2131', '沭阳县', '174', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2132', '泗洪县', '174', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2133', '泗阳县', '174', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2134', '上城区', '175', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2135', '下城区', '175', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2136', '临安市', '175', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2137', '余杭区', '175', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2138', '富阳市', '175', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2139', '建德市', '175', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2140', '拱墅区', '175', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2141', '桐庐县', '175', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2142', '江干区', '175', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2143', '淳安县', '175', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2144', '滨江区', '175', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2145', '萧山区', '175', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2146', '西湖区', '175', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2147', '余姚市', '176', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2148', '北仑区', '176', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2149', '奉化市', '176', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2150', '宁海县', '176', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2151', '慈溪市', '176', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2152', '江东区', '176', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2153', '江北区', '176', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2154', '海曙区', '176', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2155', '象山县', '176', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2156', '鄞州区', '176', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2157', '镇海区', '176', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2158', '乐清市', '177', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2159', '平阳县', '177', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2160', '文成县', '177', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2161', '永嘉县', '177', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2162', '泰顺县', '177', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2163', '洞头县', '177', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2164', '瑞安市', '177', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2165', '瓯海区', '177', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2166', '苍南县', '177', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2167', '鹿城区', '177', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2168', '龙湾区', '177', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2169', '南湖区', '178', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2170', '嘉善县', '178', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2171', '平湖市', '178', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2172', '桐乡市', '178', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2173', '海宁市', '178', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2174', '海盐县', '178', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2175', '秀洲区', '178', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2176', '南浔区', '179', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2177', '吴兴区', '179', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2178', '安吉县', '179', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2179', '德清县', '179', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2180', '长兴县', '179', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2181', '上虞市', '180', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2182', '嵊州市', '180', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2183', '新昌县', '180', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2184', '绍兴县', '180', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2185', '诸暨市', '180', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2186', '越城区', '180', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2187', '定海区', '181', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2188', '岱山县', '181', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2189', '嵊泗县', '181', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2190', '普陀区', '181', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2191', '常山县', '182', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2192', '开化县', '182', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2193', '柯城区', '182', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2194', '江山市', '182', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2195', '衢江区', '182', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2196', '龙游县', '182', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2197', '东阳市', '183', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2198', '义乌市', '183', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2199', '兰溪市', '183', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2200', '婺城区', '183', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2201', '武义县', '183', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2202', '永康市', '183', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2203', '浦江县', '183', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2204', '磐安县', '183', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2205', '金东区', '183', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2206', '三门县', '184', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2207', '临海市', '184', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2208', '仙居县', '184', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2209', '天台县', '184', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2210', '椒江区', '184', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2211', '温岭市', '184', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2212', '玉环县', '184', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2213', '路桥区', '184', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2214', '黄岩区', '184', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2215', '云和县', '185', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2216', '庆元县', '185', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2217', '景宁畲族自治县', '185', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2218', '松阳县', '185', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2219', '缙云县', '185', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2220', '莲都区', '185', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2221', '遂昌县', '185', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2222', '青田县', '185', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2223', '龙泉市', '185', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2224', '包河区', '186', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2225', '庐阳区', '186', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2226', '瑶海区', '186', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2227', '肥东县', '186', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2228', '肥西县', '186', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2229', '蜀山区', '186', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2230', '长丰县', '186', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2231', '三山区', '187', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2232', '南陵县', '187', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2233', '弋江区', '187', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2234', '繁昌县', '187', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2235', '芜湖县', '187', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2236', '镜湖区', '187', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2237', '鸠江区', '187', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2238', '五河县', '188', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2239', '固镇县', '188', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2240', '怀远县', '188', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2241', '淮上区', '188', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2242', '禹会区', '188', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2243', '蚌山区', '188', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2244', '龙子湖区', '188', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2245', '八公山区', '189', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2246', '凤台县', '189', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2247', '大通区', '189', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2248', '潘集区', '189', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2249', '田家庵区', '189', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2250', '谢家集区', '189', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2251', '当涂县', '190', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2252', '花山区', '190', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2253', '金家庄区', '190', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2254', '雨山区', '190', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2255', '杜集区', '191', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2256', '濉溪县', '191', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2257', '烈山区', '191', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2258', '相山区', '191', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2259', '狮子山区', '192', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2260', '郊区', '192', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2261', '铜官山区', '192', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2262', '铜陵县', '192', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2263', '大观区', '193', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2264', '太湖县', '193', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2265', '宜秀区', '193', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2266', '宿松县', '193', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2267', '岳西县', '193', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2268', '怀宁县', '193', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2269', '望江县', '193', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2270', '枞阳县', '193', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2271', '桐城市', '193', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2272', '潜山县', '193', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2273', '迎江区', '193', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2274', '休宁县', '194', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2275', '屯溪区', '194', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2276', '徽州区', '194', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2277', '歙县', '194', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2278', '祁门县', '194', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2279', '黄山区', '194', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2280', '黟县', '194', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2281', '全椒县', '195', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2282', '凤阳县', '195', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2283', '南谯区', '195', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2284', '天长市', '195', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2285', '定远县', '195', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2286', '明光市', '195', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2287', '来安县', '195', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2288', '琅玡区', '195', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2289', '临泉县', '196', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2290', '太和县', '196', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2291', '界首市', '196', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2292', '阜南县', '196', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2293', '颍东区', '196', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2294', '颍州区', '196', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2295', '颍泉区', '196', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2296', '颖上县', '196', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2297', '埇桥区', '197', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2298', '泗县辖', '197', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2299', '灵璧县', '197', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2300', '砀山县', '197', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2301', '萧县', '197', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2302', '含山县', '198', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2303', '和县', '198', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2304', '居巢区', '198', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2305', '庐江县', '198', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2306', '无为县', '198', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2307', '寿县', '199', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2308', '舒城县', '199', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2309', '裕安区', '199', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2310', '金安区', '199', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2311', '金寨县', '199', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2312', '霍山县', '199', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2313', '霍邱县', '199', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2314', '利辛县', '200', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2315', '涡阳县', '200', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2316', '蒙城县', '200', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2317', '谯城区', '200', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2318', '东至县', '201', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2319', '石台县', '201', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2320', '贵池区', '201', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2321', '青阳县', '201', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2322', '宁国市', '202', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2323', '宣州区', '202', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2324', '广德县', '202', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2325', '旌德县', '202', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2326', '泾县', '202', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2327', '绩溪县', '202', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2328', '郎溪县', '202', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2329', '仓山区', '203', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2330', '台江区', '203', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2331', '平潭县', '203', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2332', '晋安区', '203', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2333', '永泰县', '203', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2334', '福清市', '203', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2335', '罗源县', '203', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2336', '连江县', '203', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2337', '长乐市', '203', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2338', '闽侯县', '203', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2339', '闽清县', '203', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2340', '马尾区', '203', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2341', '鼓楼区', '203', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2342', '同安区', '204', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2343', '思明区', '204', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2344', '海沧区', '204', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2345', '湖里区', '204', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2346', '翔安区', '204', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2347', '集美区', '204', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2348', '仙游县', '205', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2349', '城厢区', '205', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2350', '涵江区', '205', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2351', '秀屿区', '205', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2352', '荔城区', '205', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2353', '三元区', '206', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2354', '大田县', '206', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2355', '宁化县', '206', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2356', '将乐县', '206', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2357', '尤溪县', '206', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2358', '建宁县', '206', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2359', '明溪县', '206', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2360', '梅列区', '206', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2361', '永安市', '206', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2362', '沙县', '206', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2363', '泰宁县', '206', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2364', '清流县', '206', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2365', '丰泽区', '207', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2366', '南安市', '207', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2367', '安溪县', '207', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2368', '德化县', '207', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2369', '惠安县', '207', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2370', '晋江市', '207', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2371', '永春县', '207', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2372', '泉港区', '207', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2373', '洛江区', '207', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2374', '石狮市', '207', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2375', '金门县', '207', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2376', '鲤城区', '207', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2377', '东山县', '208', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2378', '云霄县', '208', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2379', '华安县', '208', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2380', '南靖县', '208', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2381', '平和县', '208', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2382', '漳浦县', '208', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2383', '芗城区', '208', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2384', '诏安县', '208', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2385', '长泰县', '208', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2386', '龙文区', '208', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2387', '龙海市', '208', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2388', '光泽县', '209', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2389', '延平区', '209', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2390', '建瓯市', '209', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2391', '建阳市', '209', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2392', '政和县', '209', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2393', '松溪县', '209', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2394', '武夷山市', '209', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2395', '浦城县', '209', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2396', '邵武市', '209', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2397', '顺昌县', '209', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2398', '上杭县', '210', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2399', '新罗区', '210', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2400', '武平县', '210', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2401', '永定县', '210', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2402', '漳平市', '210', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2403', '连城县', '210', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2404', '长汀县', '210', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2405', '古田县', '211', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2406', '周宁县', '211', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2407', '寿宁县', '211', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2408', '屏南县', '211', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2409', '柘荣县', '211', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2410', '福安市', '211', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2411', '福鼎市', '211', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2412', '蕉城区', '211', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2413', '霞浦县', '211', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2414', '东湖区', '212', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2415', '南昌县', '212', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2416', '安义县', '212', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2417', '新建县', '212', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2418', '湾里区', '212', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2419', '西湖区', '212', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2420', '进贤县', '212', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2421', '青云谱区', '212', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2422', '青山湖区', '212', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2423', '乐平市', '213', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2424', '昌江区', '213', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2425', '浮梁县', '213', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2426', '珠山区', '213', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2427', '上栗县', '214', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2428', '安源区', '214', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2429', '湘东区', '214', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2430', '芦溪县', '214', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2431', '莲花县', '214', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2432', '九江县', '215', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2433', '修水县', '215', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2434', '庐山区', '215', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2435', '彭泽县', '215', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2436', '德安县', '215', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2437', '星子县', '215', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2438', '武宁县', '215', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2439', '永修县', '215', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2440', '浔阳区', '215', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2441', '湖口县', '215', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2442', '瑞昌市', '215', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2443', '都昌县', '215', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2444', '分宜县', '216', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2445', '渝水区', '216', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2446', '余江县', '217', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2447', '月湖区', '217', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2448', '贵溪市', '217', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2449', '上犹县', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2450', '于都县', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2451', '会昌县', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2452', '信丰县', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2453', '全南县', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2454', '兴国县', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2455', '南康市', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2456', '大余县', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2457', '宁都县', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2458', '安远县', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2459', '定南县', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2460', '寻乌县', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2461', '崇义县', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2462', '瑞金市', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2463', '石城县', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2464', '章贡区', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2465', '赣县', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2466', '龙南县', '218', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2467', '万安县', '219', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2468', '井冈山市', '219', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2469', '吉安县', '219', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2470', '吉州区', '219', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2471', '吉水县', '219', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2472', '安福县', '219', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2473', '峡江县', '219', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2474', '新干县', '219', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2475', '永丰县', '219', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2476', '永新县', '219', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2477', '泰和县', '219', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2478', '遂川县', '219', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2479', '青原区', '219', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2480', '万载县', '220', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2481', '上高县', '220', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2482', '丰城市', '220', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2483', '奉新县', '220', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2484', '宜丰县', '220', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2485', '樟树市', '220', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2486', '袁州区', '220', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2487', '铜鼓县', '220', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2488', '靖安县', '220', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2489', '高安市', '220', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2490', '东乡县', '221', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2491', '临川区', '221', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2492', '乐安县', '221', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2493', '南丰县', '221', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2494', '南城县', '221', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2495', '宜黄县', '221', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2496', '崇仁县', '221', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2497', '广昌县', '221', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2498', '资溪县', '221', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2499', '金溪县', '221', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2500', '黎川县', '221', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2501', '万年县', '222', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2502', '上饶县', '222', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2503', '余干县', '222', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2504', '信州区', '222', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2505', '婺源县', '222', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2506', '广丰县', '222', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2507', '弋阳县', '222', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2508', '德兴市', '222', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2509', '横峰县', '222', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2510', '玉山县', '222', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2511', '鄱阳县', '222', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2512', '铅山县', '222', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2513', '历下区', '223', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2514', '历城区', '223', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2515', '商河县', '223', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2516', '天桥区', '223', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2517', '市中区', '223', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2518', '平阴县', '223', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2519', '槐荫区', '223', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2520', '济阳县', '223', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2521', '章丘市', '223', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2522', '长清区', '223', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2523', '即墨市', '224', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2524', '四方区', '224', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2525', '城阳区', '224', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2526', '崂山区', '224', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2527', '市北区', '224', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2528', '市南区', '224', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2529', '平度市', '224', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2530', '李沧区', '224', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2531', '胶南市', '224', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2532', '胶州市', '224', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2533', '莱西市', '224', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2534', '黄岛区', '224', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2535', '临淄区', '225', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2536', '博山区', '225', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2537', '周村区', '225', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2538', '张店区', '225', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2539', '桓台县', '225', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2540', '沂源县', '225', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2541', '淄川区', '225', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2542', '高青县', '225', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2543', '台儿庄区', '226', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2544', '山亭区', '226', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2545', '峄城区', '226', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2546', '市中区', '226', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2547', '滕州市', '226', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2548', '薛城区', '226', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2549', '东营区', '227', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2550', '利津县', '227', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2551', '垦利县', '227', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2552', '广饶县', '227', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2553', '河口区', '227', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2554', '招远市', '228', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2555', '栖霞市', '228', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2556', '海阳市', '228', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2557', '牟平区', '228', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2558', '福山区', '228', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2559', '芝罘区', '228', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2560', '莱山区', '228', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2561', '莱州市', '228', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2562', '莱阳市', '228', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2563', '蓬莱市', '228', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2564', '长岛县', '228', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2565', '龙口市', '228', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2566', '临朐县', '229', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2567', '坊子区', '229', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2568', '奎文区', '229', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2569', '安丘市', '229', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2570', '寒亭区', '229', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2571', '寿光市', '229', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2572', '昌乐县', '229', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2573', '昌邑市', '229', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2574', '潍城区', '229', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2575', '诸城市', '229', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2576', '青州市', '229', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2577', '高密市', '229', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2578', '任城区', '230', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2579', '兖州市', '230', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2580', '嘉祥县', '230', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2581', '市中区', '230', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2582', '微山县', '230', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2583', '曲阜市', '230', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2584', '梁山县', '230', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2585', '汶上县', '230', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2586', '泗水县', '230', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2587', '邹城市', '230', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2588', '金乡县', '230', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2589', '鱼台县', '230', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2590', '东平县', '231', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2591', '宁阳县', '231', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2592', '岱岳区', '231', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2593', '新泰市', '231', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2594', '泰山区', '231', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2595', '肥城市', '231', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2596', '乳山市', '232', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2597', '文登市', '232', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2598', '环翠区', '232', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2599', '荣成市', '232', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2600', '东港区', '233', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2601', '五莲县', '233', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2602', '岚山区', '233', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2603', '莒县', '233', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2604', '莱城区', '234', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2605', '钢城区', '234', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2606', '临沭县', '235', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2607', '兰山区', '235', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2608', '平邑县', '235', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2609', '沂南县', '235', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2610', '沂水县', '235', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2611', '河东区', '235', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2612', '罗庄区', '235', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2613', '苍山县', '235', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2614', '莒南县', '235', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2615', '蒙阴县', '235', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2616', '费县', '235', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2617', '郯城县', '235', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2618', '临邑县', '236', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2619', '乐陵市', '236', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2620', '夏津县', '236', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2621', '宁津县', '236', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2622', '平原县', '236', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2623', '庆云县', '236', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2624', '德城区', '236', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2625', '武城县', '236', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2626', '禹城市', '236', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2627', '陵县', '236', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2628', '齐河县', '236', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2629', '东昌府区', '237', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2630', '东阿县', '237', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2631', '临清市', '237', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2632', '冠县', '237', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2633', '茌平县', '237', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2634', '莘县', '237', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2635', '阳谷县', '237', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2636', '高唐县', '237', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2637', '博兴县', '238', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2638', '惠民县', '238', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2639', '无棣县', '238', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2640', '沾化县', '238', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2641', '滨城区', '238', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2642', '邹平县', '238', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2643', '阳信县', '238', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2644', '东明县', '239', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2645', '单县', '239', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2646', '定陶县', '239', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2647', '巨野县', '239', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2648', '成武县', '239', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2649', '曹县', '239', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2650', '牡丹区', '239', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2651', '郓城县', '239', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2652', '鄄城县', '239', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2653', '上街区', '240', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2654', '中原区', '240', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2655', '中牟县', '240', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2656', '二七区', '240', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2657', '巩义市', '240', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2658', '惠济区', '240', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2659', '新密市', '240', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2660', '新郑市', '240', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2661', '登封市', '240', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2662', '管城回族区', '240', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2663', '荥阳市', '240', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2664', '金水区', '240', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2665', '兰考县', '241', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2666', '尉氏县', '241', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2667', '开封县', '241', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2668', '杞县', '241', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2669', '禹王台区', '241', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2670', '通许县', '241', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2671', '金明区', '241', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2672', '顺河回族区', '241', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2673', '鼓楼区', '241', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2674', '龙亭区', '241', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2675', '伊川县', '242', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2676', '偃师市', '242', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2677', '吉利区', '242', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2678', '孟津县', '242', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2679', '宜阳县', '242', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2680', '嵩县', '242', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2681', '新安县', '242', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2682', '栾川县', '242', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2683', '汝阳县', '242', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2684', '洛宁县', '242', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2685', '洛龙区', '242', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2686', '涧西区', '242', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2687', '瀍河回族区', '242', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2688', '老城区', '242', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2689', '西工区', '242', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2690', '卫东区', '243', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2691', '叶县', '243', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2692', '宝丰县', '243', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2693', '新华区', '243', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2694', '汝州市', '243', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2695', '湛河区', '243', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2696', '石龙区', '243', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2697', '舞钢市', '243', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2698', '郏县', '243', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2699', '鲁山县', '243', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2700', '内黄县', '244', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2701', '北关区', '244', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2702', '安阳县', '244', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2703', '文峰区', '244', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2704', '林州市', '244', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2705', '殷都区', '244', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2706', '汤阴县', '244', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2707', '滑县', '244', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2708', '龙安区', '244', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2709', '山城区', '245', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2710', '浚县', '245', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2711', '淇县', '245', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2712', '淇滨区', '245', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2713', '鹤山区', '245', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2714', '凤泉区', '246', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2715', '卫滨区', '246', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2716', '卫辉市', '246', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2717', '原阳县', '246', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2718', '封丘县', '246', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2719', '延津县', '246', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2720', '新乡县', '246', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2721', '牧野区', '246', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2722', '红旗区', '246', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2723', '获嘉县', '246', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2724', '辉县市', '246', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2725', '长垣县', '246', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2726', '中站区', '247', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2727', '修武县', '247', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2728', '博爱县', '247', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2729', '孟州市', '247', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2730', '山阳区', '247', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2731', '武陟县', '247', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2732', '沁阳市', '247', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2733', '温县', '247', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2734', '解放区', '247', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2735', '马村区', '247', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2736', '华龙区', '248', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2737', '南乐县', '248', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2738', '台前县', '248', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2739', '清丰县', '248', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2740', '濮阳县', '248', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2741', '范县', '248', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2742', '禹州市', '249', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2743', '襄城县', '249', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2744', '许昌县', '249', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2745', '鄢陵县', '249', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2746', '长葛市', '249', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2747', '魏都区', '249', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2748', '临颍县', '250', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2749', '召陵区', '250', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2750', '源汇区', '250', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2751', '舞阳县', '250', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2752', '郾城区', '250', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2753', '义马市', '251', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2754', '卢氏县', '251', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2755', '渑池县', '251', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2756', '湖滨区', '251', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2757', '灵宝市', '251', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2758', '陕县', '251', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2759', '内乡县', '252', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2760', '南召县', '252', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2761', '卧龙区', '252', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2762', '唐河县', '252', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2763', '宛城区', '252', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2764', '新野县', '252', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2765', '方城县', '252', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2766', '桐柏县', '252', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2767', '淅川县', '252', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2768', '社旗县', '252', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2769', '西峡县', '252', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2770', '邓州市', '252', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2771', '镇平县', '252', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2772', '夏邑县', '253', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2773', '宁陵县', '253', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2774', '柘城县', '253', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2775', '民权县', '253', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2776', '永城市', '253', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2777', '睢县', '253', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2778', '睢阳区', '253', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2779', '粱园区', '253', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2780', '虞城县', '253', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2781', '光山县', '254', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2782', '商城县', '254', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2783', '固始县', '254', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2784', '平桥区', '254', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2785', '息县', '254', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2786', '新县', '254', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2787', '浉河区', '254', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2788', '淮滨县', '254', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2789', '潢川县', '254', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2790', '罗山县', '254', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2791', '商水县', '255', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2792', '太康县', '255', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2793', '川汇区', '255', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2794', '扶沟县', '255', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2795', '沈丘县', '255', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2796', '淮阳县', '255', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2797', '西华县', '255', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2798', '郸城县', '255', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2799', '项城市', '255', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2800', '鹿邑县', '255', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2801', '上蔡县', '256', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2802', '平舆县', '256', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2803', '新蔡县', '256', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2804', '正阳县', '256', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2805', '汝南县', '256', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2806', '泌阳县', '256', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2807', '确山县', '256', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2808', '西平县', '256', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2809', '遂平县', '256', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2810', '驿城区', '256', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2811', '济源市', '257', '0', '3', null, '1277');
INSERT INTO `area` VALUES ('2812', '东西湖区', '258', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2813', '新洲区', '258', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2814', '武昌区', '258', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2815', '汉南区', '258', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2816', '汉阳区', '258', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2817', '江夏区', '258', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2818', '江岸区', '258', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2819', '江汉区', '258', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2820', '洪山区', '258', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2821', '硚口区', '258', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2822', '蔡甸区', '258', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2823', '青山区', '258', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2824', '黄陂区', '258', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2825', '下陆区', '259', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2826', '大冶市', '259', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2827', '西塞山区', '259', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2828', '铁山区', '259', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2829', '阳新县', '259', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2830', '黄石港区', '259', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2831', '丹江口市', '260', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2832', '张湾区', '260', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2833', '房县', '260', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2834', '竹山县', '260', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2835', '竹溪县', '260', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2836', '茅箭区', '260', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2837', '郧县', '260', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2838', '郧西县', '260', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2839', '五峰土家族自治县', '261', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2840', '伍家岗区', '261', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2841', '兴山县', '261', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2842', '夷陵区', '261', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2843', '宜都市', '261', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2844', '当阳市', '261', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2845', '枝江市', '261', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2846', '点军区', '261', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2847', '秭归县', '261', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2848', '虢亭区', '261', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2849', '西陵区', '261', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2850', '远安县', '261', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2851', '长阳土家族自治县', '261', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2852', '保康县', '262', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2853', '南漳县', '262', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2854', '宜城市', '262', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2855', '枣阳市', '262', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2856', '樊城区', '262', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2857', '老河口市', '262', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2858', '襄城区', '262', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2859', '襄阳区', '262', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2860', '谷城县', '262', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2861', '华容区', '263', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2862', '粱子湖', '263', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2863', '鄂城区', '263', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2864', '东宝区', '264', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2865', '京山县', '264', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2866', '掇刀区', '264', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2867', '沙洋县', '264', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2868', '钟祥市', '264', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2869', '云梦县', '265', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2870', '大悟县', '265', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2871', '孝南区', '265', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2872', '孝昌县', '265', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2873', '安陆市', '265', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2874', '应城市', '265', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2875', '汉川市', '265', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2876', '公安县', '266', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2877', '松滋市', '266', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2878', '江陵县', '266', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2879', '沙市区', '266', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2880', '洪湖市', '266', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2881', '监利县', '266', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2882', '石首市', '266', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2883', '荆州区', '266', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2884', '团风县', '267', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2885', '武穴市', '267', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2886', '浠水县', '267', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2887', '红安县', '267', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2888', '罗田县', '267', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2889', '英山县', '267', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2890', '蕲春县', '267', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2891', '麻城市', '267', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2892', '黄州区', '267', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2893', '黄梅县', '267', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2894', '咸安区', '268', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2895', '嘉鱼县', '268', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2896', '崇阳县', '268', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2897', '赤壁市', '268', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2898', '通城县', '268', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2899', '通山县', '268', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2900', '广水市', '269', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2901', '曾都区', '269', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2902', '利川市', '270', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2903', '咸丰县', '270', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2904', '宣恩县', '270', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2905', '巴东县', '270', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2906', '建始县', '270', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2907', '恩施市', '270', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2908', '来凤县', '270', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2909', '鹤峰县', '270', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2910', '仙桃市', '271', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2911', '潜江市', '272', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2912', '天门市', '273', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2913', '神农架林区', '274', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2914', '天心区', '275', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2915', '宁乡县', '275', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2916', '岳麓区', '275', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2917', '开福区', '275', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2918', '望城县', '275', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2919', '浏阳市', '275', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2920', '芙蓉区', '275', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2921', '长沙县', '275', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2922', '雨花区', '275', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2923', '天元区', '276', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2924', '攸县', '276', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2925', '株洲县', '276', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2926', '炎陵县', '276', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2927', '石峰区', '276', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2928', '芦淞区', '276', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2929', '茶陵县', '276', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2930', '荷塘区', '276', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2931', '醴陵市', '276', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2932', '岳塘区', '277', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2933', '湘乡市', '277', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2934', '湘潭县', '277', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2935', '雨湖区', '277', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2936', '韶山市', '277', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2937', '南岳区', '278', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2938', '常宁市', '278', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2939', '珠晖区', '278', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2940', '石鼓区', '278', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2941', '祁东县', '278', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2942', '耒阳市', '278', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2943', '蒸湘区', '278', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2944', '衡东县', '278', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2945', '衡南县', '278', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2946', '衡山县', '278', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2947', '衡阳县', '278', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2948', '雁峰区', '278', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2949', '北塔区', '279', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2950', '双清区', '279', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2951', '城步苗族自治县', '279', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2952', '大祥区', '279', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2953', '新宁县', '279', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2954', '新邵县', '279', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2955', '武冈市', '279', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2956', '洞口县', '279', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2957', '绥宁县', '279', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2958', '邵东县', '279', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2959', '邵阳县', '279', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2960', '隆回县', '279', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2961', '临湘市', '280', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2962', '云溪区', '280', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2963', '华容县', '280', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2964', '君山区', '280', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2965', '岳阳县', '280', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2966', '岳阳楼区', '280', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2967', '平江县', '280', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2968', '汨罗市', '280', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2969', '湘阴县', '280', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2970', '临澧县', '281', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2971', '安乡县', '281', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2972', '桃源县', '281', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2973', '武陵区', '281', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2974', '汉寿县', '281', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2975', '津市市', '281', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2976', '澧县', '281', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2977', '石门县', '281', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2978', '鼎城区', '281', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2979', '慈利县', '282', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2980', '桑植县', '282', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2981', '武陵源区', '282', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2982', '永定区', '282', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2983', '南县', '283', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2984', '安化县', '283', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2985', '桃江县', '283', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2986', '沅江市', '283', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2987', '资阳区', '283', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2988', '赫山区', '283', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2989', '临武县', '284', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2990', '北湖区', '284', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2991', '嘉禾县', '284', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2992', '安仁县', '284', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2993', '宜章县', '284', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2994', '桂东县', '284', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2995', '桂阳县', '284', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2996', '永兴县', '284', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2997', '汝城县', '284', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2998', '苏仙区', '284', '0', '3', null, '0');
INSERT INTO `area` VALUES ('2999', '资兴市', '284', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3000', '东安县', '285', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3001', '冷水滩区', '285', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3002', '双牌县', '285', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3003', '宁远县', '285', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3004', '新田县', '285', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3005', '江华瑶族自治县', '285', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3006', '江永县', '285', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3007', '祁阳县', '285', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3008', '蓝山县', '285', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3009', '道县', '285', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3010', '零陵区', '285', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3011', '中方县', '286', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3012', '会同县', '286', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3013', '新晃侗族自治县', '286', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3014', '沅陵县', '286', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3015', '洪江市/洪江区', '286', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3016', '溆浦县', '286', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3017', '芷江侗族自治县', '286', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3018', '辰溪县', '286', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3019', '通道侗族自治县', '286', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3020', '靖州苗族侗族自治县', '286', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3021', '鹤城区', '286', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3022', '麻阳苗族自治县', '286', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3023', '冷水江市', '287', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3024', '双峰县', '287', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3025', '娄星区', '287', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3026', '新化县', '287', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3027', '涟源市', '287', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3028', '保靖县', '288', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3029', '凤凰县', '288', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3030', '古丈县', '288', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3031', '吉首市', '288', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3032', '永顺县', '288', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3033', '泸溪县', '288', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3034', '花垣县', '288', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3035', '龙山县', '288', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3036', '萝岗区', '289', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3037', '南沙区', '289', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3038', '从化市', '289', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3039', '增城市', '289', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3040', '天河区', '289', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3041', '海珠区', '289', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3042', '番禺区', '289', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3043', '白云区', '289', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3044', '花都区', '289', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3045', '荔湾区', '289', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3046', '越秀区', '289', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3047', '黄埔区', '289', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3048', '乐昌市', '290', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3049', '乳源瑶族自治县', '290', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3050', '仁化县', '290', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3051', '南雄市', '290', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3052', '始兴县', '290', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3053', '新丰县', '290', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3054', '曲江区', '290', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3055', '武江区', '290', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3056', '浈江区', '290', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3057', '翁源县', '290', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3058', '南山区', '291', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3059', '宝安区', '291', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3060', '盐田区', '291', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3061', '福田区', '291', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3062', '罗湖区', '291', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3063', '龙岗区', '291', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3064', '斗门区', '292', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3065', '金湾区', '292', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3066', '香洲区', '292', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3067', '南澳县', '293', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3068', '潮南区', '293', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3069', '潮阳区', '293', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3070', '澄海区', '293', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3071', '濠江区', '293', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3072', '金平区', '293', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3073', '龙湖区', '293', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3074', '三水区', '294', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3075', '南海区', '294', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3076', '禅城区', '294', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3077', '顺德区', '294', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3078', '高明区', '294', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3079', '台山市', '295', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3080', '开平市', '295', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3081', '恩平市', '295', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3082', '新会区', '295', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3083', '江海区', '295', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3084', '蓬江区', '295', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3085', '鹤山市', '295', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3086', '吴川市', '296', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3087', '坡头区', '296', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3088', '廉江市', '296', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3089', '徐闻县', '296', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3090', '赤坎区', '296', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3091', '遂溪县', '296', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3092', '雷州市', '296', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3093', '霞山区', '296', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3094', '麻章区', '296', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3095', '信宜市', '297', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3096', '化州市', '297', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3097', '电白县', '297', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3098', '茂南区', '297', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3099', '茂港区', '297', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3100', '高州市', '297', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3101', '四会市', '298', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3102', '封开县', '298', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3103', '广宁县', '298', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3104', '德庆县', '298', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3105', '怀集县', '298', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3106', '端州区', '298', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3107', '高要市', '298', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3108', '鼎湖区', '298', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3109', '博罗县', '299', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3110', '惠东县', '299', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3111', '惠城区', '299', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3112', '惠阳区', '299', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3113', '龙门县', '299', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3114', '丰顺县', '300', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3115', '五华县', '300', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3116', '兴宁市', '300', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3117', '大埔县', '300', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3118', '平远县', '300', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3119', '梅县', '300', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3120', '梅江区', '300', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3121', '蕉岭县', '300', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3122', '城区', '301', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3123', '海丰县', '301', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3124', '陆丰市', '301', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3125', '陆河县', '301', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3126', '东源县', '302', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3127', '和平县', '302', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3128', '源城区', '302', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3129', '紫金县', '302', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3130', '连平县', '302', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3131', '龙川县', '302', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3132', '江城区', '303', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3133', '阳东县', '303', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3134', '阳春市', '303', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3135', '阳西县', '303', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3136', '佛冈县', '304', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3137', '清城区', '304', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3138', '清新县', '304', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3139', '英德市', '304', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3140', '连南瑶族自治县', '304', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3141', '连山壮族瑶族自治县', '304', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3142', '连州市', '304', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3143', '阳山县', '304', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3144', '东莞市', '305', '0', '3', null, '119');
INSERT INTO `area` VALUES ('3145', '中山市', '306', '0', '3', null, '187');
INSERT INTO `area` VALUES ('3146', '湘桥区', '307', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3147', '潮安县', '307', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3148', '饶平县', '307', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3149', '惠来县', '308', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3150', '揭东县', '308', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3151', '揭西县', '308', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3152', '普宁市', '308', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3153', '榕城区', '308', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3154', '云城区', '309', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3155', '云安县', '309', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3156', '新兴县', '309', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3157', '罗定市', '309', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3158', '郁南县', '309', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3159', '上林县', '310', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3160', '兴宁区', '310', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3161', '宾阳县', '310', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3162', '横县', '310', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3163', '武鸣县', '310', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3164', '江南区', '310', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3165', '良庆区', '310', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3166', '西乡塘区', '310', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3167', '邕宁区', '310', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3168', '隆安县', '310', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3169', '青秀区', '310', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3170', '马山县', '310', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3171', '三江侗族自治县', '311', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3172', '城中区', '311', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3173', '柳北区', '311', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3174', '柳南区', '311', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3175', '柳城县', '311', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3176', '柳江县', '311', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3177', '融安县', '311', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3178', '融水苗族自治县', '311', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3179', '鱼峰区', '311', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3180', '鹿寨县', '311', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3181', '七星区', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3182', '临桂县', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3183', '全州县', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3184', '兴安县', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3185', '叠彩区', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3186', '平乐县', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3187', '恭城瑶族自治县', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3188', '永福县', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3189', '灌阳县', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3190', '灵川县', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3191', '秀峰区', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3192', '荔浦县', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3193', '象山区', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3194', '资源县', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3195', '阳朔县', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3196', '雁山区', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3197', '龙胜各族自治县', '312', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3198', '万秀区', '313', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3199', '岑溪市', '313', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3200', '苍梧县', '313', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3201', '蒙山县', '313', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3202', '藤县', '313', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3203', '蝶山区', '313', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3204', '长洲区', '313', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3205', '合浦县', '314', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3206', '海城区', '314', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3207', '铁山港区', '314', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3208', '银海区', '314', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3209', '上思县', '315', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3210', '东兴市', '315', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3211', '港口区', '315', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3212', '防城区', '315', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3213', '浦北县', '316', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3214', '灵山县', '316', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3215', '钦北区', '316', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3216', '钦南区', '316', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3217', '平南县', '317', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3218', '桂平市', '317', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3219', '港北区', '317', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3220', '港南区', '317', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3221', '覃塘区', '317', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3222', '兴业县', '318', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3223', '北流市', '318', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3224', '博白县', '318', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3225', '容县', '318', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3226', '玉州区', '318', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3227', '陆川县', '318', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3228', '乐业县', '319', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3229', '凌云县', '319', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3230', '右江区', '319', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3231', '平果县', '319', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3232', '德保县', '319', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3233', '田东县', '319', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3234', '田林县', '319', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3235', '田阳县', '319', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3236', '西林县', '319', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3237', '那坡县', '319', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3238', '隆林各族自治县', '319', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3239', '靖西县', '319', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3240', '八步区', '320', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3241', '富川瑶族自治县', '320', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3242', '昭平县', '320', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3243', '钟山县', '320', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3244', '东兰县', '321', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3245', '凤山县', '321', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3246', '南丹县', '321', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3247', '大化瑶族自治县', '321', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3248', '天峨县', '321', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3249', '宜州市', '321', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3250', '巴马瑶族自治县', '321', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3251', '环江毛南族自治县', '321', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3252', '罗城仫佬族自治县', '321', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3253', '都安瑶族自治县', '321', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3254', '金城江区', '321', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3255', '兴宾区', '322', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3256', '合山市', '322', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3257', '忻城县', '322', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3258', '武宣县', '322', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3259', '象州县', '322', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3260', '金秀瑶族自治县', '322', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3261', '凭祥市', '323', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3262', '大新县', '323', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3263', '天等县', '323', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3264', '宁明县', '323', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3265', '扶绥县', '323', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3266', '江州区', '323', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3267', '龙州县', '323', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3268', '琼山区', '324', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3269', '秀英区', '324', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3270', '美兰区', '324', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3271', '龙华区', '324', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3272', '三亚市', '325', '0', '3', null, '121');
INSERT INTO `area` VALUES ('3273', '五指山市', '326', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3274', '琼海市', '327', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3275', '儋州市', '328', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3276', '文昌市', '329', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3277', '万宁市', '330', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3278', '东方市', '331', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3279', '定安县', '332', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3280', '屯昌县', '333', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3281', '澄迈县', '334', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3282', '临高县', '335', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3283', '白沙黎族自治县', '336', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3284', '昌江黎族自治县', '337', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3285', '乐东黎族自治县', '338', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3286', '陵水黎族自治县', '339', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3287', '保亭黎族苗族自治县', '340', '0', '3', null, '0');
INSERT INTO `area` VALUES ('3288', '琼中黎族苗族自治县', '341', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4209', '双流县', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4210', '大邑县', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4211', '崇州市', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4212', '彭州市', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4213', '成华区', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4214', '新津县', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4215', '新都区', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4216', '武侯区', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4217', '温江区', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4218', '蒲江县', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4219', '邛崃市', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4220', '郫县', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4221', '都江堰市', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4222', '金堂县', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4223', '金牛区', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4224', '锦江区', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4225', '青白江区', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4226', '青羊区', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4227', '龙泉驿区', '385', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4228', '大安区', '386', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4229', '富顺县', '386', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4230', '沿滩区', '386', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4231', '自流井区', '386', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4232', '荣县', '386', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4233', '贡井区', '386', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4234', '东区', '387', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4235', '仁和区', '387', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4236', '盐边县', '387', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4237', '米易县', '387', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4238', '西区', '387', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4239', '叙永县', '388', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4240', '古蔺县', '388', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4241', '合江县', '388', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4242', '江阳区', '388', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4243', '泸县', '388', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4244', '纳溪区', '388', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4245', '龙马潭区', '388', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4246', '中江县', '389', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4247', '什邡市', '389', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4248', '广汉市', '389', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4249', '旌阳区', '389', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4250', '绵竹市', '389', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4251', '罗江县', '389', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4252', '三台县', '390', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4253', '北川羌族自治县', '390', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4254', '安县', '390', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4255', '平武县', '390', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4256', '梓潼县', '390', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4257', '江油市', '390', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4258', '涪城区', '390', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4259', '游仙区', '390', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4260', '盐亭县', '390', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4261', '元坝区', '391', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4262', '利州区', '391', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4263', '剑阁县', '391', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4264', '旺苍县', '391', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4265', '朝天区', '391', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4266', '苍溪县', '391', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4267', '青川县', '391', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4268', '大英县', '392', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4269', '安居区', '392', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4270', '射洪县', '392', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4271', '船山区', '392', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4272', '蓬溪县', '392', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4273', '东兴区', '393', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4274', '威远县', '393', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4275', '市中区', '393', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4276', '资中县', '393', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4277', '隆昌县', '393', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4278', '五通桥区', '394', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4279', '井研县', '394', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4280', '夹江县', '394', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4281', '峨眉山市', '394', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4282', '峨边彝族自治县', '394', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4283', '市中区', '394', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4284', '沐川县', '394', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4285', '沙湾区', '394', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4286', '犍为县', '394', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4287', '金口河区', '394', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4288', '马边彝族自治县', '394', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4289', '仪陇县', '395', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4290', '南充市嘉陵区', '395', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4291', '南部县', '395', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4292', '嘉陵区', '395', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4293', '营山县', '395', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4294', '蓬安县', '395', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4295', '西充县', '395', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4296', '阆中市', '395', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4297', '顺庆区', '395', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4298', '高坪区', '395', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4299', '东坡区', '396', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4300', '丹棱县', '396', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4301', '仁寿县', '396', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4302', '彭山县', '396', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4303', '洪雅县', '396', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4304', '青神县', '396', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4305', '兴文县', '397', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4306', '南溪县', '397', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4307', '宜宾县', '397', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4308', '屏山县', '397', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4309', '江安县', '397', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4310', '珙县', '397', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4311', '筠连县', '397', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4312', '翠屏区', '397', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4313', '长宁县', '397', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4314', '高县', '397', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4315', '华蓥市', '398', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4316', '岳池县', '398', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4317', '广安区', '398', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4318', '武胜县', '398', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4319', '邻水县', '398', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4320', '万源市', '399', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4321', '大竹县', '399', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4322', '宣汉县', '399', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4323', '开江县', '399', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4324', '渠县', '399', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4325', '达县', '399', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4326', '通川区', '399', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4327', '名山县', '400', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4328', '天全县', '400', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4329', '宝兴县', '400', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4330', '汉源县', '400', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4331', '石棉县', '400', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4332', '芦山县', '400', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4333', '荥经县', '400', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4334', '雨城区', '400', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4335', '南江县', '401', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4336', '巴州区', '401', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4337', '平昌县', '401', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4338', '通江县', '401', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4339', '乐至县', '402', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4340', '安岳县', '402', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4341', '简阳市', '402', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4342', '雁江区', '402', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4343', '九寨沟县', '403', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4344', '壤塘县', '403', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4345', '小金县', '403', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4346', '松潘县', '403', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4347', '汶川县', '403', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4348', '理县', '403', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4349', '红原县', '403', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4350', '若尔盖县', '403', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4351', '茂县', '403', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4352', '金川县', '403', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4353', '阿坝县', '403', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4354', '马尔康县', '403', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4355', '黑水县', '403', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4356', '丹巴县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4357', '乡城县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4358', '巴塘县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4359', '康定县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4360', '得荣县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4361', '德格县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4362', '新龙县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4363', '泸定县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4364', '炉霍县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4365', '理塘县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4366', '甘孜县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4367', '白玉县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4368', '石渠县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4369', '稻城县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4370', '色达县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4371', '道孚县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4372', '雅江县', '404', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4373', '会东县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4374', '会理县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4375', '冕宁县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4376', '喜德县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4377', '宁南县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4378', '布拖县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4379', '德昌县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4380', '昭觉县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4381', '普格县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4382', '木里藏族自治县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4383', '甘洛县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4384', '盐源县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4385', '美姑县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4386', '西昌', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4387', '越西县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4388', '金阳县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4389', '雷波县', '405', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4390', '乌当区', '406', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4391', '云岩区', '406', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4392', '修文县', '406', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4393', '南明区', '406', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4394', '小河区', '406', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4395', '开阳县', '406', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4396', '息烽县', '406', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4397', '清镇市', '406', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4398', '白云区', '406', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4399', '花溪区', '406', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4400', '六枝特区', '407', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4401', '水城县', '407', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4402', '盘县', '407', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4403', '钟山区', '407', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4404', '习水县', '408', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4405', '仁怀市', '408', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4406', '余庆县', '408', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4407', '凤冈县', '408', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4408', '务川仡佬族苗族自治县', '408', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4409', '桐梓县', '408', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4410', '正安县', '408', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4411', '汇川区', '408', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4412', '湄潭县', '408', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4413', '红花岗区', '408', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4414', '绥阳县', '408', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4415', '赤水市', '408', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4416', '道真仡佬族苗族自治县', '408', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4417', '遵义县', '408', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4418', '关岭布依族苗族自治县', '409', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4419', '平坝县', '409', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4420', '普定县', '409', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4421', '紫云苗族布依族自治县', '409', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4422', '西秀区', '409', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4423', '镇宁布依族苗族自治县', '409', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4424', '万山特区', '410', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4425', '印江土家族苗族自治县', '410', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4426', '德江县', '410', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4427', '思南县', '410', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4428', '松桃苗族自治县', '410', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4429', '江口县', '410', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4430', '沿河土家族自治县', '410', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4431', '玉屏侗族自治县', '410', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4432', '石阡县', '410', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4433', '铜仁市', '410', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4434', '兴义市', '411', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4435', '兴仁县', '411', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4436', '册亨县', '411', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4437', '安龙县', '411', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4438', '普安县', '411', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4439', '晴隆县', '411', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4440', '望谟县', '411', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4441', '贞丰县', '411', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4442', '大方县', '412', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4443', '威宁彝族回族苗族自治县', '412', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4444', '毕节市', '412', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4445', '纳雍县', '412', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4446', '织金县', '412', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4447', '赫章县', '412', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4448', '金沙县', '412', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4449', '黔西县', '412', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4450', '三穗县', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4451', '丹寨县', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4452', '从江县', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4453', '凯里市', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4454', '剑河县', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4455', '台江县', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4456', '天柱县', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4457', '岑巩县', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4458', '施秉县', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4459', '榕江县', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4460', '锦屏县', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4461', '镇远县', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4462', '雷山县', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4463', '麻江县', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4464', '黄平县', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4465', '黎平县', '413', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4466', '三都水族自治县', '414', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4467', '平塘县', '414', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4468', '惠水县', '414', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4469', '独山县', '414', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4470', '瓮安县', '414', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4471', '福泉市', '414', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4472', '罗甸县', '414', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4473', '荔波县', '414', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4474', '贵定县', '414', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4475', '都匀市', '414', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4476', '长顺县', '414', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4477', '龙里县', '414', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4478', '东川区', '415', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4479', '五华区', '415', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4480', '呈贡县', '415', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4481', '安宁市', '415', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4482', '官渡区', '415', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4483', '宜良县', '415', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4484', '富民县', '415', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4485', '寻甸回族彝族自治县', '415', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4486', '嵩明县', '415', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4487', '晋宁县', '415', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4488', '盘龙区', '415', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4489', '石林彝族自治县', '415', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4490', '禄劝彝族苗族自治县', '415', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4491', '西山区', '415', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4492', '会泽县', '416', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4493', '宣威市', '416', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4494', '富源县', '416', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4495', '师宗县', '416', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4496', '沾益县', '416', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4497', '罗平县', '416', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4498', '陆良县', '416', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4499', '马龙县', '416', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4500', '麒麟区', '416', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4501', '元江哈尼族彝族傣族自治县', '417', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4502', '华宁县', '417', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4503', '峨山彝族自治县', '417', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4504', '新平彝族傣族自治县', '417', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4505', '易门县', '417', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4506', '江川县', '417', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4507', '澄江县', '417', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4508', '红塔区', '417', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4509', '通海县', '417', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4510', '施甸县', '418', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4511', '昌宁县', '418', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4512', '腾冲县', '418', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4513', '隆阳区', '418', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4514', '龙陵县', '418', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4515', '大关县', '419', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4516', '威信县', '419', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4517', '巧家县', '419', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4518', '彝良县', '419', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4519', '昭阳区', '419', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4520', '水富县', '419', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4521', '永善县', '419', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4522', '盐津县', '419', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4523', '绥江县', '419', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4524', '镇雄县', '419', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4525', '鲁甸县', '419', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4526', '华坪县', '420', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4527', '古城区', '420', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4528', '宁蒗彝族自治县', '420', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4529', '永胜县', '420', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4530', '玉龙纳西族自治县', '420', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4531', '临翔区', '422', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4532', '云县', '422', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4533', '凤庆县', '422', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4534', '双江拉祜族佤族布朗族傣族自治县', '422', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4535', '永德县', '422', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4536', '沧源佤族自治县', '422', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4537', '耿马傣族佤族自治县', '422', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4538', '镇康县', '422', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4539', '元谋县', '423', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4540', '南华县', '423', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4541', '双柏县', '423', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4542', '大姚县', '423', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4543', '姚安县', '423', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4544', '楚雄市', '423', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4545', '武定县', '423', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4546', '永仁县', '423', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4547', '牟定县', '423', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4548', '禄丰县', '423', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4549', '个旧市', '424', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4550', '元阳县', '424', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4551', '屏边苗族自治县', '424', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4552', '建水县', '424', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4553', '开远市', '424', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4554', '弥勒县', '424', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4555', '河口瑶族自治县', '424', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4556', '泸西县', '424', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4557', '石屏县', '424', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4558', '红河县', '424', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4559', '绿春县', '424', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4560', '蒙自县', '424', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4561', '金平苗族瑶族傣族自治县', '424', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4562', '丘北县', '425', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4563', '富宁县', '425', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4564', '广南县', '425', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4565', '文山县', '425', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4566', '砚山县', '425', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4567', '西畴县', '425', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4568', '马关县', '425', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4569', '麻栗坡县', '425', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4570', '勐海县', '426', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4571', '勐腊县', '426', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4572', '景洪市', '426', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4573', '云龙县', '427', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4574', '剑川县', '427', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4575', '南涧彝族自治县', '427', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4576', '大理市', '427', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4577', '宾川县', '427', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4578', '巍山彝族回族自治县', '427', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4579', '弥渡县', '427', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4580', '永平县', '427', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4581', '洱源县', '427', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4582', '漾濞彝族自治县', '427', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4583', '祥云县', '427', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4584', '鹤庆县', '427', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4585', '梁河县', '428', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4586', '潞西市', '428', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4587', '瑞丽市', '428', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4588', '盈江县', '428', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4589', '陇川县', '428', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4590', '德钦县', '430', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4591', '维西傈僳族自治县', '430', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4592', '香格里拉县', '430', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4593', '城关区', '431', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4594', '堆龙德庆县', '431', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4595', '墨竹工卡县', '431', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4596', '尼木县', '431', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4597', '当雄县', '431', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4598', '曲水县', '431', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4599', '林周县', '431', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4600', '达孜县', '431', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4601', '丁青县', '432', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4602', '八宿县', '432', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4603', '察雅县', '432', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4604', '左贡县', '432', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4605', '昌都县', '432', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4606', '江达县', '432', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4607', '洛隆县', '432', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4608', '类乌齐县', '432', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4609', '芒康县', '432', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4610', '贡觉县', '432', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4611', '边坝县', '432', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4612', '乃东县', '433', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4613', '加查县', '433', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4614', '扎囊县', '433', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4615', '措美县', '433', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4616', '曲松县', '433', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4617', '桑日县', '433', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4618', '洛扎县', '433', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4619', '浪卡子县', '433', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4620', '琼结县', '433', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4621', '贡嘎县', '433', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4622', '错那县', '433', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4623', '隆子县', '433', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4624', '亚东县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4625', '仁布县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4626', '仲巴县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4627', '南木林县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4628', '吉隆县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4629', '定日县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4630', '定结县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4631', '岗巴县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4632', '康马县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4633', '拉孜县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4634', '日喀则市', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4635', '昂仁县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4636', '江孜县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4637', '白朗县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4638', '聂拉木县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4639', '萨嘎县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4640', '萨迦县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4641', '谢通门县', '434', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4642', '嘉黎县', '435', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4643', '安多县', '435', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4644', '尼玛县', '435', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4645', '巴青县', '435', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4646', '比如县', '435', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4647', '班戈县', '435', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4648', '申扎县', '435', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4649', '索县', '435', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4650', '聂荣县', '435', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4651', '那曲县', '435', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4652', '噶尔县', '436', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4653', '措勤县', '436', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4654', '改则县', '436', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4655', '日土县', '436', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4656', '普兰县', '436', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4657', '札达县', '436', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4658', '革吉县', '436', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4659', '墨脱县', '437', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4660', '察隅县', '437', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4661', '工布江达县', '437', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4662', '朗县', '437', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4663', '林芝县', '437', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4664', '波密县', '437', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4665', '米林县', '437', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4666', '临潼区', '438', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4667', '周至县', '438', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4668', '户县', '438', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4669', '新城区', '438', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4670', '未央区', '438', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4671', '灞桥区', '438', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4672', '碑林区', '438', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4673', '莲湖区', '438', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4674', '蓝田县', '438', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4675', '长安区', '438', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4676', '阎良区', '438', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4677', '雁塔区', '438', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4678', '高陵县', '438', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4679', '印台区', '439', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4680', '宜君县', '439', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4681', '王益区', '439', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4682', '耀州区', '439', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4683', '凤县', '440', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4684', '凤翔县', '440', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4685', '千阳县', '440', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4686', '太白县', '440', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4687', '岐山县', '440', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4688', '扶风县', '440', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4689', '渭滨区', '440', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4690', '眉县', '440', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4691', '金台区', '440', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4692', '陇县', '440', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4693', '陈仓区', '440', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4694', '麟游县', '440', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4695', '三原县', '441', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4696', '干县', '441', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4697', '兴平市', '441', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4698', '彬县', '441', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4699', '旬邑县', '441', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4700', '杨陵区', '441', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4701', '武功县', '441', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4702', '永寿县', '441', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4703', '泾阳县', '441', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4704', '淳化县', '441', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4705', '渭城区', '441', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4706', '礼泉县', '441', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4707', '秦都区', '441', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4708', '长武县', '441', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4709', '临渭区', '442', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4710', '华县', '442', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4711', '华阴市', '442', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4712', '合阳县', '442', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4713', '大荔县', '442', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4714', '富平县', '442', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4715', '潼关县', '442', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4716', '澄城县', '442', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4717', '白水县', '442', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4718', '蒲城县', '442', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4719', '韩城市', '442', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4720', '吴起县', '443', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4721', '子长县', '443', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4722', '安塞县', '443', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4723', '宜川县', '443', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4724', '宝塔区', '443', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4725', '富县', '443', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4726', '延川县', '443', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4727', '延长县', '443', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4728', '志丹县', '443', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4729', '洛川县', '443', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4730', '甘泉县', '443', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4731', '黄陵县', '443', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4732', '黄龙县', '443', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4733', '佛坪县', '444', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4734', '勉县', '444', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4735', '南郑县', '444', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4736', '城固县', '444', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4737', '宁强县', '444', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4738', '汉台区', '444', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4739', '洋县', '444', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4740', '留坝县', '444', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4741', '略阳县', '444', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4742', '西乡县', '444', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4743', '镇巴县', '444', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4744', '佳县', '445', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4745', '吴堡县', '445', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4746', '子洲县', '445', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4747', '定边县', '445', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4748', '府谷县', '445', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4749', '榆林市榆阳区', '445', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4750', '横山县', '445', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4751', '清涧县', '445', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4752', '神木县', '445', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4753', '米脂县', '445', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4754', '绥德县', '445', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4755', '靖边县', '445', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4756', '宁陕县', '446', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4757', '岚皋县', '446', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4758', '平利县', '446', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4759', '旬阳县', '446', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4760', '汉滨区', '446', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4761', '汉阴县', '446', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4762', '白河县', '446', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4763', '石泉县', '446', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4764', '紫阳县', '446', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4765', '镇坪县', '446', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4766', '丹凤县', '447', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4767', '商南县', '447', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4768', '商州区', '447', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4769', '山阳县', '447', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4770', '柞水县', '447', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4771', '洛南县', '447', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4772', '镇安县', '447', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4773', '七里河区', '448', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4774', '城关区', '448', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4775', '安宁区', '448', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4776', '榆中县', '448', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4777', '永登县', '448', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4778', '皋兰县', '448', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4779', '红古区', '448', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4780', '西固区', '448', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4781', '嘉峪关市', '449', '0', '3', null, '33');
INSERT INTO `area` VALUES ('4782', '永昌县', '450', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4783', '金川区', '450', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4784', '会宁县', '451', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4785', '平川区', '451', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4786', '景泰县', '451', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4787', '白银区', '451', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4788', '靖远县', '451', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4789', '张家川回族自治县', '452', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4790', '武山县', '452', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4791', '清水县', '452', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4792', '甘谷县', '452', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4793', '秦安县', '452', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4794', '秦州区', '452', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4795', '麦积区', '452', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4796', '凉州区', '453', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4797', '古浪县', '453', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4798', '天祝藏族自治县', '453', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4799', '民勤县', '453', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4800', '临泽县', '454', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4801', '山丹县', '454', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4802', '民乐县', '454', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4803', '甘州区', '454', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4804', '肃南裕固族自治县', '454', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4805', '高台县', '454', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4806', '华亭县', '455', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4807', '崆峒区', '455', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4808', '崇信县', '455', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4809', '庄浪县', '455', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4810', '泾川县', '455', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4811', '灵台县', '455', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4812', '静宁县', '455', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4813', '敦煌市', '456', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4814', '玉门市', '456', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4815', '瓜州县（原安西县）', '456', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4816', '肃北蒙古族自治县', '456', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4817', '肃州区', '456', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4818', '金塔县', '456', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4819', '阿克塞哈萨克族自治县', '456', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4820', '华池县', '457', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4821', '合水县', '457', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4822', '宁县', '457', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4823', '庆城县', '457', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4824', '正宁县', '457', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4825', '环县', '457', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4826', '西峰区', '457', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4827', '镇原县', '457', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4828', '临洮县', '458', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4829', '安定区', '458', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4830', '岷县', '458', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4831', '渭源县', '458', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4832', '漳县', '458', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4833', '通渭县', '458', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4834', '陇西县', '458', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4835', '两当县', '459', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4836', '宕昌县', '459', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4837', '康县', '459', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4838', '徽县', '459', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4839', '成县', '459', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4840', '文县', '459', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4841', '武都区', '459', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4842', '礼县', '459', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4843', '西和县', '459', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4844', '东乡族自治县', '460', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4845', '临夏县', '460', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4846', '临夏市', '460', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4847', '和政县', '460', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4848', '广河县', '460', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4849', '康乐县', '460', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4850', '永靖县', '460', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4851', '积石山保安族东乡族撒拉族自治县', '460', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4852', '临潭县', '461', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4853', '卓尼县', '461', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4854', '合作市', '461', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4855', '夏河县', '461', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4856', '玛曲县', '461', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4857', '碌曲县', '461', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4858', '舟曲县', '461', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4859', '迭部县', '461', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4860', '城东区', '462', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4861', '城中区', '462', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4862', '城北区', '462', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4863', '城西区', '462', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4864', '大通回族土族自治县', '462', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4865', '湟中县', '462', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4866', '湟源县', '462', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4867', '乐都县', '463', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4868', '互助土族自治县', '463', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4869', '化隆回族自治县', '463', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4870', '平安县', '463', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4871', '循化撒拉族自治县', '463', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4872', '民和回族土族自治县', '463', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4873', '刚察县', '464', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4874', '海晏县', '464', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4875', '祁连县', '464', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4876', '门源回族自治县', '464', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4877', '同仁县', '465', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4878', '尖扎县', '465', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4879', '河南蒙古族自治县', '465', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4880', '泽库县', '465', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4881', '共和县', '466', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4882', '兴海县', '466', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4883', '同德县', '466', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4884', '贵南县', '466', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4885', '贵德县', '466', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4886', '久治县', '467', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4887', '玛多县', '467', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4888', '玛沁县', '467', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4889', '班玛县', '467', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4890', '甘德县', '467', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4891', '达日县', '467', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4892', '囊谦县', '468', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4893', '曲麻莱县', '468', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4894', '杂多县', '468', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4895', '治多县', '468', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4896', '玉树县', '468', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4897', '称多县', '468', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4898', '乌兰县', '469', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4899', '冷湖行委', '469', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4900', '大柴旦行委', '469', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4901', '天峻县', '469', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4902', '德令哈市', '469', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4903', '格尔木市', '469', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4904', '茫崖行委', '469', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4905', '都兰县', '469', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4906', '兴庆区', '470', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4907', '永宁县', '470', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4908', '灵武市', '470', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4909', '西夏区', '470', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4910', '贺兰县', '470', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4911', '金凤区', '470', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4912', '大武口区', '471', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4913', '平罗县', '471', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4914', '惠农区', '471', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4915', '利通区', '472', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4916', '同心县', '472', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4917', '盐池县', '472', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4918', '青铜峡市', '472', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4919', '原州区', '473', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4920', '彭阳县', '473', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4921', '泾源县', '473', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4922', '西吉县', '473', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4923', '隆德县', '473', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4924', '中宁县', '474', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4925', '沙坡头区', '474', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4926', '海原县', '474', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4927', '东山区', '475', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4928', '乌鲁木齐县', '475', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4929', '天山区', '475', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4930', '头屯河区', '475', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4931', '新市区', '475', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4932', '水磨沟区', '475', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4933', '沙依巴克区', '475', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4934', '达坂城区', '475', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4935', '乌尔禾区', '476', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4936', '克拉玛依区', '476', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4937', '独山子区', '476', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4938', '白碱滩区', '476', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4939', '吐鲁番市', '477', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4940', '托克逊县', '477', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4941', '鄯善县', '477', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4942', '伊吾县', '478', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4943', '哈密市', '478', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4944', '巴里坤哈萨克自治县', '478', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4945', '吉木萨尔县', '479', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4946', '呼图壁县', '479', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4947', '奇台县', '479', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4948', '昌吉市', '479', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4949', '木垒哈萨克自治县', '479', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4950', '玛纳斯县', '479', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4951', '米泉市', '479', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4952', '阜康市', '479', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4953', '博乐市', '480', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4954', '温泉县', '480', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4955', '精河县', '480', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4956', '博湖县', '481', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4957', '和硕县', '481', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4958', '和静县', '481', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4959', '尉犁县', '481', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4960', '库尔勒市', '481', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4961', '焉耆回族自治县', '481', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4962', '若羌县', '481', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4963', '轮台县', '481', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4964', '乌什县', '482', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4965', '库车县', '482', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4966', '拜城县', '482', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4967', '新和县', '482', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4968', '柯坪县', '482', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4969', '沙雅县', '482', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4970', '温宿县', '482', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4971', '阿克苏市', '482', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4972', '阿瓦提县', '482', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4973', '乌恰县', '483', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4974', '阿克陶县', '483', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4975', '阿合奇县', '483', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4976', '阿图什市', '483', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4977', '伽师县', '484', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4978', '叶城县', '484', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4979', '喀什市', '484', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4980', '塔什库尔干塔吉克自治县', '484', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4981', '岳普湖县', '484', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4982', '巴楚县', '484', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4983', '泽普县', '484', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4984', '疏勒县', '484', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4985', '疏附县', '484', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4986', '英吉沙县', '484', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4987', '莎车县', '484', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4988', '麦盖提县', '484', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4989', '于田县', '485', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4990', '和田县', '485', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4991', '和田市', '485', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4992', '墨玉县', '485', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4993', '民丰县', '485', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4994', '洛浦县', '485', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4995', '皮山县', '485', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4996', '策勒县', '485', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4997', '伊宁县', '486', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4998', '伊宁市', '486', '0', '3', null, '0');
INSERT INTO `area` VALUES ('4999', '奎屯市', '486', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5000', '察布查尔锡伯自治县', '486', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5001', '尼勒克县', '486', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5002', '巩留县', '486', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5003', '新源县', '486', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5004', '昭苏县', '486', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5005', '特克斯县', '486', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5006', '霍城县', '486', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5007', '乌苏市', '487', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5008', '和布克赛尔蒙古自治县', '487', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5009', '塔城市', '487', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5010', '托里县', '487', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5011', '沙湾县', '487', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5012', '裕民县', '487', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5013', '额敏县', '487', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5014', '吉木乃县', '488', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5015', '哈巴河县', '488', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5016', '富蕴县', '488', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5017', '布尔津县', '488', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5018', '福海县', '488', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5019', '阿勒泰市', '488', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5020', '青河县', '488', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5021', '石河子市', '489', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5022', '阿拉尔市', '490', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5023', '图木舒克市', '491', '0', '3', null, '0');
INSERT INTO `area` VALUES ('5024', '五家渠市', '492', '0', '3', null, '0');
INSERT INTO `area` VALUES ('45055', '海外', '35', '0', '2', null, '0');

-- ----------------------------
-- Table structure for battle
-- ----------------------------
DROP TABLE IF EXISTS `battle`;
CREATE TABLE `battle` (
  `Users_ID` varchar(10) NOT NULL,
  `Battle_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Battle_Keywords` varchar(15) DEFAULT NULL,
  `Battle_Title` varchar(50) NOT NULL,
  `Battle_ActivityName` varchar(50) DEFAULT NULL,
  `Battle_QuestionNum` int(11) DEFAULT NULL,
  `Battle_AnswerQuertionNum` int(11) DEFAULT NULL,
  `Battle_Integral` int(11) DEFAULT NULL,
  `Battle_BackgroundMusic` varchar(50) DEFAULT NULL,
  `Battle_MusicPath` varchar(50) DEFAULT NULL,
  `Battle_IsSound` tinyint(1) DEFAULT NULL,
  `Battle_LimitTime` int(11) DEFAULT NULL,
  `Battle_StartTime` int(10) NOT NULL DEFAULT '0',
  `Battle_EndTime` int(10) NOT NULL DEFAULT '0',
  `Battle_LotteryTimes` int(11) DEFAULT '0',
  `Battle_EveryDayLotteryTimes` int(11) DEFAULT '0',
  `Battle_Rule1` varchar(60) DEFAULT NULL,
  `Battle_Rule2` varchar(60) DEFAULT NULL,
  `Battle_Rule3` varchar(60) DEFAULT NULL,
  `Battle_Rule4` varchar(60) DEFAULT NULL,
  `Battle_Rule5` varchar(60) DEFAULT NULL,
  `Battle_Game1` varchar(60) DEFAULT NULL,
  `Battle_Game2` varchar(60) DEFAULT NULL,
  `Battle_Game3` varchar(60) DEFAULT NULL,
  `Battle_Game4` varchar(60) DEFAULT NULL,
  `Battle_Game5` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`Battle_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of battle
-- ----------------------------

-- ----------------------------
-- Table structure for battle_act
-- ----------------------------
DROP TABLE IF EXISTS `battle_act`;
CREATE TABLE `battle_act` (
  `Act_ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT NULL,
  `User_ID` int(10) DEFAULT NULL,
  `Act_Time` int(10) DEFAULT NULL,
  `Act_Score` int(10) DEFAULT NULL,
  PRIMARY KEY (`Act_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of battle_act
-- ----------------------------

-- ----------------------------
-- Table structure for battle_exam
-- ----------------------------
DROP TABLE IF EXISTS `battle_exam`;
CREATE TABLE `battle_exam` (
  `Exam_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) DEFAULT NULL,
  `Exam_Name` varchar(50) DEFAULT NULL,
  `Exam_AnswerA` varchar(50) DEFAULT NULL,
  `Exam_AnswerB` varchar(50) DEFAULT NULL,
  `Exam_AnswerC` varchar(50) DEFAULT NULL,
  `Exam_AnswerD` varchar(50) DEFAULT NULL,
  `Exam_CorrectAnswer` tinyint(1) DEFAULT NULL,
  `Exam_CreateTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`Exam_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of battle_exam
-- ----------------------------

-- ----------------------------
-- Table structure for battle_sn
-- ----------------------------
DROP TABLE IF EXISTS `battle_sn`;
CREATE TABLE `battle_sn` (
  `SN_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `Battle_ID` int(11) DEFAULT NULL,
  `User_Name` varchar(50) DEFAULT NULL,
  `User_Mobile` varchar(50) DEFAULT NULL,
  `User_Head` varchar(255) DEFAULT NULL,
  `SN_Integral` int(11) DEFAULT NULL,
  `SN_CreateTime` int(10) DEFAULT NULL,
  `Act_ID` int(11) DEFAULT '0',
  `Act_Time` varchar(25) DEFAULT NULL,
  `Boss_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`SN_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of battle_sn
-- ----------------------------

-- ----------------------------
-- Table structure for bianmin_server
-- ----------------------------
DROP TABLE IF EXISTS `bianmin_server`;
CREATE TABLE `bianmin_server` (
  `Users_ID` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '平台所有者id',
  `Server_ID` int(10) NOT NULL AUTO_INCREMENT COMMENT '服务id',
  `Server_Name` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT ' ',
  `Server_Type` smallint(6) NOT NULL COMMENT '1 为手机    2为流量',
  `Server_Parameter` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '参数',
  `Server_Newprice` varchar(255) NOT NULL COMMENT '新价格',
  `Server_Oldprice` varchar(255) NOT NULL COMMENT '老价格',
  `Server_Profit` int(11) DEFAULT NULL COMMENT '利润',
  `nobi_ratio` decimal(10,2) DEFAULT '0.00' COMMENT '爵位比例',
  `commission_ratio` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '佣金比例   ',
  `Server_Distributes` text CHARACTER SET utf8 NOT NULL COMMENT '佣金明细',
  `Server_BriefDescription` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '服务描述',
  `State` int(11) DEFAULT NULL COMMENT '状态   1 启用   0挂关闭',
  `Service_Provider` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '服务商',
  `Count` int(10) NOT NULL COMMENT '话费金额(设置数量)',
  PRIMARY KEY (`Server_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bianmin_server
-- ----------------------------

-- ----------------------------
-- Table structure for biz
-- ----------------------------
DROP TABLE IF EXISTS `biz`;
CREATE TABLE `biz` (
  `Biz_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT '',
  `Biz_Account` varchar(50) DEFAULT '',
  `Biz_PassWord` varchar(32) DEFAULT '',
  `Biz_Name` varchar(255) DEFAULT '',
  `Biz_Province` int(10) DEFAULT '0',
  `Biz_City` int(10) DEFAULT '0',
  `Biz_Area` int(10) DEFAULT '0',
  `Biz_Address` varchar(255) DEFAULT '',
  `Biz_Contact` varchar(255) DEFAULT '',
  `Biz_Phone` varchar(255) DEFAULT '',
  `Biz_Email` varchar(255) DEFAULT '',
  `Biz_Homepage` varchar(255) DEFAULT '',
  `Biz_Introduce` longtext,
  `Biz_Status` tinyint(1) DEFAULT '0',
  `Biz_CreateTime` int(10) DEFAULT '0',
  `Biz_SmsPhone` varchar(50) DEFAULT '',
  `Shipping` text COMMENT '快递公司及其对应模板',
  `Default_Shipping` smallint(6) NOT NULL DEFAULT '0' COMMENT '默认的快递公司',
  `Default_Business` varchar(10) NOT NULL DEFAULT 'express' COMMENT '默认的业务',
  `Biz_RecieveProvince` int(10) DEFAULT '0',
  `Biz_RecieveCity` int(10) DEFAULT '0',
  `Biz_RecieveArea` int(10) DEFAULT '0',
  `Biz_RecieveAddress` varchar(255) DEFAULT '',
  `Biz_RecieveName` varchar(255) DEFAULT '',
  `Biz_RecieveMobile` varchar(255) DEFAULT '',
  `Skin_ID` int(10) DEFAULT '0',
  `Biz_Logo` varchar(255) DEFAULT '',
  `Biz_Kfcode` text,
  `Group_ID` int(10) DEFAULT '0',
  `Finance_Type` tinyint(4) DEFAULT '0' COMMENT '0 按照交易比例 1 单个产品设置',
  `Finance_Rate` decimal(10,2) DEFAULT '0.00' COMMENT '交易结算利率网站提成百分比',
  `PC_Skin_ID` int(5) DEFAULT '1',
  `pc_banner` varchar(150) DEFAULT NULL,
  `pc_slide` text,
  `pc_bg_color` varchar(16) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL COMMENT '前台绑定会员',
  `PaymenteRate` decimal(10,2) DEFAULT '100.00' COMMENT '结算比例',
  `Invitation_Code` varchar(10) DEFAULT NULL COMMENT '邀请码',
  PRIMARY KEY (`Biz_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of biz
-- ----------------------------
INSERT INTO `biz` VALUES ('1', 'pl2hu3uczz', 'shangjia', 'e10adc3949ba59abbe56e057f20f883e', '商家', '0', '0', '0', '郑州市', '测试', '12222222222222223', '2591218372@qq.com', '', '这是一个测试商家', '0', '1464760844', '12222222222222223', '{\"1\":\"1\"}', '1', 'express', '0', '0', '0', '', '', '', '1', '/uploadfiles/pl2hu3uczz/image/574e79d21a.png', null, '1', '0', '50.00', '1', null, null, null, '2', '55.00', 'I64F2KL6');
INSERT INTO `biz` VALUES ('2', 'pl2hu3uczz', '123123', '202cb962ac59075b964b07152d234b70', '1231123123', '0', '0', '0', '权威的气味', '123123', '123123', '123', '123', '132', '0', '1464864289', '1231231', null, '0', 'express', '0', '0', '0', '', '', '', '1', '', null, '1', '0', '22.00', '1', null, null, null, null, null, null);
INSERT INTO `biz` VALUES ('3', 'pl2hu3uczz', 'were', '202cb962ac59075b964b07152d234b70', 'qewqw', '0', '0', '0', 'qew', '测试先生', '15517101234', '', '', '', '0', '1465185158', '15517101234', null, '0', 'express', '0', '0', '0', '', '', '', '1', '', null, '1', '0', '55.00', '1', null, null, null, null, '66.00', null);
INSERT INTO `biz` VALUES ('4', 'pl2hu3uczz', 'qweqw', '698d51a19d8a121ce581499d7b701668', 'qwe', '0', '0', '0', 'qweqwe', '123123', '15517101234', '', '', '', '0', '1465194304', '15517101234', null, '0', 'express', '0', '0', '0', '', '', '', '1', '', null, '1', '0', '22.00', '1', null, null, null, null, null, null);
INSERT INTO `biz` VALUES ('5', 'pl2hu3uczz', '31211', '202cb962ac59075b964b07152d234b70', '123', '0', '0', '0', '11', '123123', '15517101234', '', '', '', '0', '1465194399', '15517101234', null, '0', 'express', '0', '0', '0', '', '', '', '1', '', null, '1', '1', '0.00', '1', null, null, null, null, '10.00', null);
INSERT INTO `biz` VALUES ('6', 'pl2hu3uczz', 'ceshishangjia', '202cb962ac59075b964b07152d234b70', '测试邀请商家', '0', '0', '0', '东大街', '王测试', '15511111111', '15511111111@qq.com', '', '试试事实上水水水水水水水水水水水水水水水水谁谁谁水水水水', '0', '1465781826', '15511111111', null, '0', 'express', '0', '0', '0', '', '', '', '1', '', null, '1', '0', '80.00', '1', null, null, null, null, '50.00', null);
INSERT INTO `biz` VALUES ('7', 'pl2hu3uczz', 'ceshishangjia1', '202cb962ac59075b964b07152d234b70', '测试邀请商家', '0', '0', '0', '东大街', '王测试', '15511111111', '15511111111@qq.com', 'sssss', '谁谁谁水水水水谁谁谁水水水水', '0', '1465782271', '15511111111', null, '0', 'express', '0', '0', '0', '', '', '', '1', '', null, '1', '0', '80.00', '1', null, null, null, null, '50.00', 'I64F2KL6');
INSERT INTO `biz` VALUES ('8', 'pl2hu3uczz', 'zhanghao1', '202cb962ac59075b964b07152d234b70', '测试业务商家', '0', '0', '0', '西大街', '孙测试', '15517105555', '15517105555@qq.com ', 'sssssss', '谁谁谁谁谁谁水水水水谁谁谁', '0', '1465803763', '15517105555', '{\"2\":\"2\"}', '2', 'express', '0', '0', '0', '', '', '', '1', '/uploadfiles/pl2hu3uczz/image/574e7a5875.jpg', null, '1', '0', '80.00', '1', null, null, null, '13', '50.00', 'FLPIDR28');

-- ----------------------------
-- Table structure for biz_apply
-- ----------------------------
DROP TABLE IF EXISTS `biz_apply`;
CREATE TABLE `biz_apply` (
  `ItemID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT '',
  `Category_ID` int(10) DEFAULT '0',
  `Biz_Name` varchar(255) DEFAULT '',
  `IsRead` tinyint(1) DEFAULT '0',
  `CreateTime` int(10) DEFAULT '0',
  `Email` varchar(255) DEFAULT '',
  `Contact` varchar(255) DEFAULT '',
  `Mobile` varchar(255) DEFAULT '',
  `Invitation_Code` varchar(12) DEFAULT '' COMMENT '邀请码',
  PRIMARY KEY (`ItemID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of biz_apply
-- ----------------------------
INSERT INTO `biz_apply` VALUES ('1', 'pl2hu3uczz', '2', '测试企业', '1', '1465183806', '', '测试先生', '15517105581', null);
INSERT INTO `biz_apply` VALUES ('2', 'pl2hu3uczz', '1', '测试邀请商家', '1', '1465780555', '15511111111@qq.com', '王测试', '15511111111', 'I64F2KL6');

-- ----------------------------
-- Table structure for biz_category
-- ----------------------------
DROP TABLE IF EXISTS `biz_category`;
CREATE TABLE `biz_category` (
  `Category_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Category_Index` int(11) NOT NULL,
  `Category_Name` varchar(50) NOT NULL,
  `Category_ParentID` int(11) NOT NULL DEFAULT '0',
  `Biz_ID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Category_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of biz_category
-- ----------------------------

-- ----------------------------
-- Table structure for biz_config
-- ----------------------------
DROP TABLE IF EXISTS `biz_config`;
CREATE TABLE `biz_config` (
  `ItemID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT '',
  `BaoZhengJin` longtext,
  `NianFei` longtext,
  `JieSuan` longtext,
  PRIMARY KEY (`ItemID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of biz_config
-- ----------------------------
INSERT INTO `biz_config` VALUES ('1', 'pl2hu3uczz', '测试1', '测试2', '测试3');

-- ----------------------------
-- Table structure for biz_group
-- ----------------------------
DROP TABLE IF EXISTS `biz_group`;
CREATE TABLE `biz_group` (
  `Group_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT '',
  `Group_Name` varchar(100) DEFAULT '',
  `Group_Index` int(10) DEFAULT '0',
  `Group_IsStore` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`Group_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of biz_group
-- ----------------------------
INSERT INTO `biz_group` VALUES ('1', 'pl2hu3uczz', '测试分组', '0', '0');

-- ----------------------------
-- Table structure for biz_home
-- ----------------------------
DROP TABLE IF EXISTS `biz_home`;
CREATE TABLE `biz_home` (
  `Home_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Biz_ID` int(11) NOT NULL,
  `Skin_ID` int(11) NOT NULL,
  `Home_Json` text,
  PRIMARY KEY (`Home_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of biz_home
-- ----------------------------
INSERT INTO `biz_home` VALUES ('1', 'pl2hu3uczz', '1', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/biz/1/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"}]');
INSERT INTO `biz_home` VALUES ('2', 'pl2hu3uczz', '2', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/biz/1/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"}]');
INSERT INTO `biz_home` VALUES ('3', 'pl2hu3uczz', '3', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/biz/1/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"}]');
INSERT INTO `biz_home` VALUES ('4', 'pl2hu3uczz', '4', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/biz/1/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"}]');
INSERT INTO `biz_home` VALUES ('5', 'pl2hu3uczz', '5', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/biz/1/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"}]');
INSERT INTO `biz_home` VALUES ('6', 'pl2hu3uczz', '6', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/biz/1/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"}]');
INSERT INTO `biz_home` VALUES ('7', 'pl2hu3uczz', '7', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/biz/1/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"}]');
INSERT INTO `biz_home` VALUES ('8', 'pl2hu3uczz', '8', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/biz/1/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"}]');

-- ----------------------------
-- Table structure for biz_menu
-- ----------------------------
DROP TABLE IF EXISTS `biz_menu`;
CREATE TABLE `biz_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(40) NOT NULL,
  `menu_target` tinyint(1) DEFAULT NULL,
  `menu_link` varchar(100) DEFAULT NULL,
  `Users_ID` varchar(10) DEFAULT NULL,
  `Biz_ID` int(10) DEFAULT NULL,
  `menu_sort` int(10) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of biz_menu
-- ----------------------------

-- ----------------------------
-- Table structure for biz_skin
-- ----------------------------
DROP TABLE IF EXISTS `biz_skin`;
CREATE TABLE `biz_skin` (
  `Skin_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT NULL,
  `Skin_Name` varchar(20) DEFAULT NULL,
  `Skin_ImgPath` varchar(255) DEFAULT NULL,
  `Skin_Json` text,
  PRIMARY KEY (`Skin_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of biz_skin
-- ----------------------------
INSERT INTO `biz_skin` VALUES ('1', 't47djimutm', '模板1', '/static/member/images/shop/biz/skin001.jpg', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/biz/1/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"}]');

-- ----------------------------
-- Table structure for cloud_category
-- ----------------------------
DROP TABLE IF EXISTS `cloud_category`;
CREATE TABLE `cloud_category` (
  `Category_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` char(10) DEFAULT NULL,
  `Category_Index` int(10) DEFAULT NULL,
  `Category_Name` char(100) DEFAULT NULL,
  `Category_ParentID` int(11) DEFAULT NULL,
  `Category_IndexShow` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Category_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cloud_category
-- ----------------------------

-- ----------------------------
-- Table structure for cloud_products
-- ----------------------------
DROP TABLE IF EXISTS `cloud_products`;
CREATE TABLE `cloud_products` (
  `Products_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` char(10) DEFAULT NULL,
  `Products_Name` char(150) DEFAULT NULL,
  `Products_Category` int(10) DEFAULT '0',
  `Products_PriceX` decimal(10,2) DEFAULT '1.00',
  `Products_PriceY` decimal(10,2) DEFAULT NULL,
  `Products_Profit` int(10) DEFAULT '0',
  `commission_ratio` int(10) DEFAULT '0' COMMENT '分佣比例',
  `Products_Distributes` char(50) DEFAULT NULL,
  `Products_JSON` text,
  `Products_Description` text,
  `Products_SoldOut` tinyint(1) DEFAULT '0',
  `Products_IsNew` tinyint(1) DEFAULT '0',
  `Products_IsRecommend` tinyint(1) DEFAULT '0',
  `Products_IsHot` tinyint(1) DEFAULT '0',
  `Products_IsShippingFree` tinyint(1) DEFAULT '0',
  `Products_CreateTime` int(10) DEFAULT NULL,
  `Products_Weight` int(10) DEFAULT NULL,
  `Products_Qrcode` char(150) DEFAULT NULL,
  `Products_IsVirtual` tinyint(1) DEFAULT NULL,
  `Products_IsRecieve` tinyint(1) DEFAULT NULL,
  `Products_Shipping` int(6) DEFAULT '0',
  `Products_Business` char(10) DEFAULT NULL,
  `Shipping_Free_Company` int(6) DEFAULT '0' COMMENT '免运费时所指定的快递公司，0为所有快递公司均可',
  `zongrenci` int(10) DEFAULT '0',
  `canyurenshu` int(10) DEFAULT '0',
  `qishu` int(10) DEFAULT '1' COMMENT '第一期开始',
  `Products_Order` int(10) DEFAULT '0' COMMENT '排序',
  `Products_xiangoutimes` int(10) DEFAULT '0' COMMENT '限购次数',
  PRIMARY KEY (`Products_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cloud_products
-- ----------------------------

-- ----------------------------
-- Table structure for cloud_products_detail
-- ----------------------------
DROP TABLE IF EXISTS `cloud_products_detail`;
CREATE TABLE `cloud_products_detail` (
  `Cloud_Detail_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` char(11) DEFAULT NULL,
  `Products_ID` int(10) DEFAULT NULL,
  `qishu` int(10) DEFAULT NULL,
  `User_ID` int(10) DEFAULT '0' COMMENT '中奖人ID',
  `User_Info` char(200) DEFAULT NULL COMMENT '{昵称，来自，人次}',
  `Luck_Sn` char(20) DEFAULT NULL COMMENT '中奖码()',
  `Products_End_Time` char(20) DEFAULT '0' COMMENT '揭晓时间(本期结束时间)',
  `Products_PriceX` decimal(10,2) DEFAULT '0.00',
  `Products_Profit` int(10) DEFAULT NULL,
  `commission_ratio` int(10) DEFAULT '0' COMMENT '分佣比例',
  `Products_Distributes` char(50) DEFAULT NULL,
  `Result` text COMMENT '得奖计算结果',
  PRIMARY KEY (`Cloud_Detail_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cloud_products_detail
-- ----------------------------

-- ----------------------------
-- Table structure for cloud_record
-- ----------------------------
DROP TABLE IF EXISTS `cloud_record`;
CREATE TABLE `cloud_record` (
  `Record_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `User_ID` int(10) DEFAULT NULL,
  `Products_ID` int(11) DEFAULT NULL,
  `Add_Time` char(20) DEFAULT '0',
  `Cloud_Code` int(10) DEFAULT NULL,
  `qishu` int(10) DEFAULT '0',
  `Order_ID` int(11) DEFAULT '0' COMMENT '订单id',
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cloud_record
-- ----------------------------

-- ----------------------------
-- Table structure for cloud_shopcodes
-- ----------------------------
DROP TABLE IF EXISTS `cloud_shopcodes`;
CREATE TABLE `cloud_shopcodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `s_id` int(10) unsigned NOT NULL,
  `s_cid` smallint(5) unsigned NOT NULL,
  `s_len` smallint(5) DEFAULT NULL,
  `s_codes` text,
  `s_codes_tmp` text,
  PRIMARY KEY (`id`),
  KEY `s_id` (`s_id`),
  KEY `s_cid` (`s_cid`),
  KEY `s_len` (`s_len`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cloud_shopcodes
-- ----------------------------

-- ----------------------------
-- Table structure for cloud_slide
-- ----------------------------
DROP TABLE IF EXISTS `cloud_slide`;
CREATE TABLE `cloud_slide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` char(11) DEFAULT NULL,
  `slide_index` int(10) DEFAULT '0' COMMENT '排序',
  `slide_title` char(40) DEFAULT NULL,
  `slide_link` char(200) DEFAULT NULL,
  `slide_img` char(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cloud_slide
-- ----------------------------

-- ----------------------------
-- Table structure for comein
-- ----------------------------
DROP TABLE IF EXISTS `comein`;
CREATE TABLE `comein` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(255) NOT NULL DEFAULT '',
  `industry` int(10) NOT NULL DEFAULT '0',
  `contact` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `telephone` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(50) NOT NULL DEFAULT '',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of comein
-- ----------------------------

-- ----------------------------
-- Table structure for distribute_account
-- ----------------------------
DROP TABLE IF EXISTS `distribute_account`;
CREATE TABLE `distribute_account` (
  `Users_ID` varchar(10) NOT NULL,
  `Account_ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_ID` int(11) DEFAULT NULL,
  `User_Name` varchar(30) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL,
  `Real_Name` varchar(400) DEFAULT NULL COMMENT '真实姓名',
  `Shop_Name` varchar(50) DEFAULT '',
  `Shop_Logo` varchar(200) DEFAULT '',
  `Shop_Announce` varchar(500) DEFAULT NULL,
  `Email` varchar(20) DEFAULT NULL,
  `ID_Card` varchar(20) DEFAULT NULL COMMENT '身份证号',
  `Alipay_Account` varchar(25) DEFAULT NULL,
  `Bank_Name` varchar(25) DEFAULT NULL,
  `Bank_Card` varchar(25) DEFAULT NULL,
  `Total_Income` decimal(10,2) DEFAULT '0.00',
  `Account_CreateTime` varchar(10) DEFAULT NULL,
  `Group_Num` int(10) DEFAULT '0',
  `invite_id` int(10) DEFAULT NULL COMMENT '邀请人的id',
  `Dis_Path` varchar(100) DEFAULT '' COMMENT '分销商关系',
  `Account_Mobile` varchar(11) DEFAULT NULL,
  `Is_Audit` tinyint(1) DEFAULT '0' COMMENT '是否通过审核',
  `Is_Regeposter` tinyint(1) DEFAULT '0' COMMENT '是否需要重新生产推广海报',
  `Total_Sales` float(10,2) DEFAULT '0.00',
  `Group_Sales` float(10,2) DEFAULT '0.00' COMMENT '团队总销售额',
  `Up_Group_Sales` float(10,2) DEFAULT '0.00',
  `Up_Group_Num` int(10) DEFAULT '0',
  `last_award_income` float(10,2) DEFAULT '0.00',
  `Professional_Title` tinyint(1) DEFAULT '0',
  `Ex_Bonus` float(10,2) DEFAULT '0.00',
  `Enable_Tixian` tinyint(1) DEFAULT '0',
  `Enable_Agent` tinyint(1) DEFAULT '0' COMMENT '0 开启代理 1股东',
  `deleted_at` varchar(30) DEFAULT NULL,
  `Fanxian_Remainder` int(10) DEFAULT '1' COMMENT '距离下次返现还剩直接下属数',
  `Fanxian_Count` int(10) DEFAULT '0' COMMENT '返现次数',
  `Is_Dongjie` tinyint(1) DEFAULT '0' COMMENT '用户是否被冻结 0 正常 1 已冻结',
  `Is_Delete` tinyint(1) DEFAULT '0' COMMENT '用户是否被删除 0 否 1 是',
  `Level_ID` int(10) DEFAULT '0',
  `sha_level` int(3) DEFAULT NULL COMMENT '股东级别',
  `Is_Salesman` tinyint(3) DEFAULT '0' COMMENT '是否业务员',
  `Invitation_Code` varchar(10) DEFAULT '' COMMENT '业务邀请码',
  `Qrcode` varchar(255) DEFAULT NULL COMMENT '二维码地址',
  `Salesman_Income` decimal(12,2) DEFAULT '0.00' COMMENT '业务可提现余额',
  `Salesman_Deltime` int(10) DEFAULT '0',
  PRIMARY KEY (`Account_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_account
-- ----------------------------
INSERT INTO `distribute_account` VALUES ('pl2hu3uczz', '2', '2', null, '66.42', '1', '暂无姓名', '的店', '', null, null, null, null, null, null, '66.42', '1464778344', '1', '0', '', null, '1', '0', '0.00', '0.00', '0.00', '0', '0.00', '0', '0.00', '1', '1', null, '0', '0', '0', '0', '391', '3', '1', null, null, null, '1466222092');
INSERT INTO `distribute_account` VALUES ('pl2hu3uczz', '3', '3', '', '0.00', '1', '暂无姓名', '的店', '', '', '', '', '', '', '', '0.00', '1464828069', '1', '0', '', '', '1', '0', '0.00', '0.00', '0.00', '0', '0.00', '0', '0.00', '1', '0', '', '0', '0', '0', '0', '391', null, null, null, null, null, null);
INSERT INTO `distribute_account` VALUES ('pl2hu3uczz', '4', '4', '', '0.00', '1', '暂无姓名', '的店', '', '', '', '', '', '', '', '0.00', '1464839390', '1', '0', '', '', '1', '0', '0.00', '0.00', '0.00', '0', '0.00', '0', '0.00', '0', '0', '', '0', '0', '0', '0', '0', null, null, null, null, null, null);
INSERT INTO `distribute_account` VALUES ('pl2hu3uczz', '5', '5', '', '0.00', '1', '暂无姓名', '的店', '', '', '', '', '', '', '', '0.00', '1464849373', '1', '3', ',3,', '', '1', '0', '0.00', '0.00', '0.00', '0', '0.00', '0', '0.00', '0', '0', '', '0', '0', '0', '0', '0', null, null, null, null, null, null);
INSERT INTO `distribute_account` VALUES ('pl2hu3uczz', '6', '3', null, '17.54', '1', null, '的店', null, null, null, null, null, null, null, '17.54', '1464854547', '1', '0', '', '', '1', '0', '0.00', '0.00', '0.00', '0', '0.00', '0', '0.00', '1', '1', null, '0', '0', '0', '0', '391', '2', null, null, null, null, null);
INSERT INTO `distribute_account` VALUES ('pl2hu3uczz', '7', '4', null, '0.00', '1', null, '的店', null, null, null, null, null, null, null, '0.00', '1464854620', '1', '0', '', '', '1', '0', '0.00', '0.00', '0.00', '0', '0.00', '0', '0.00', '0', '0', null, '0', '0', '0', '0', '391', null, null, null, null, null, null);
INSERT INTO `distribute_account` VALUES ('pl2hu3uczz', '8', '6', null, '0.00', '1', null, '的店', null, null, null, null, null, null, null, '0.00', '1464916471', '1', '0', '', '', '1', '0', '0.00', '0.00', '0.00', '0', '0.00', '0', '0.00', '1', '0', null, '0', '0', '0', '0', '391', null, null, null, null, null, null);
INSERT INTO `distribute_account` VALUES ('pl2hu3uczz', '9', '7', null, '18.04', '1', null, '的店', null, null, null, null, null, null, null, '18.04', '1465357484', '1', '0', '', '', '1', '0', '0.00', '0.00', '0.00', '0', '0.00', '0', '0.00', '1', '1', null, '0', '0', '0', '0', '391', '2', null, null, null, null, null);
INSERT INTO `distribute_account` VALUES ('pl2hu3uczz', '10', '8', null, '33.30', '1', null, '的店', null, null, null, null, null, null, null, '33.30', '1465357540', '1', '0', '', '', '1', '0', '0.00', '0.00', '0.00', '0', '0.00', '0', '0.00', '1', '1', null, '0', '0', '0', '0', '391', '3', null, null, null, null, null);
INSERT INTO `distribute_account` VALUES ('pl2hu3uczz', '11', '9', null, '8.32', '1', '', '我是银牌的店', null, null, null, null, null, null, null, '8.32', '1465694822', '1', '0', '', '', '1', '0', '0.00', '0.00', '0.00', '0', '0.00', '0', '0.00', '1', '1', null, '0', '0', '0', '0', '391', '2', '1', 'I64F2KL6', '/data/temp/test3082db7c757e87ca1b15b3593fe7cbf4.png', null, null);
INSERT INTO `distribute_account` VALUES ('pl2hu3uczz', '12', '10', null, '37.92', '1', '三级业务', '三级业务的店', null, null, null, null, null, null, null, '24.48', '1465802257', '3', '0', '', '', '1', '0', '0.00', '0.00', '0.00', '0', '0.00', '1', '0.00', '1', '0', null, '0', '0', '0', '0', '391', null, '1', 'HKCI6PMK', '/data/temp/test489d8fcd6dedc908bdacd4d9d13f71e8.png', '13.44', null);
INSERT INTO `distribute_account` VALUES ('pl2hu3uczz', '13', '11', null, '68.16', '1', '二级业务', '二级业务的店', null, null, null, null, null, null, null, '48.00', '1465802612', '2', '10', ',10,', '', '1', '0', '0.00', '0.00', '0.00', '0', '0.00', '1', '0.00', '1', '0', null, '0', '0', '0', '0', '391', null, '1', '', null, '20.16', null);
INSERT INTO `distribute_account` VALUES ('pl2hu3uczz', '14', '12', null, '48.00', '1', '购买者一级', '购买者一级的店', null, null, null, null, null, null, null, '14.40', '1465802956', '1', '11', ',10,11,', '', '1', '0', '0.00', '0.00', '0.00', '0', '0.00', '1', '0.00', '1', '0', null, '0', '0', '0', '0', '391', null, '1', 'FLPIDR28', '/data/temp/testcf95c6f9c2532fbe7fbda4b1a6460bd4.png', '33.60', null);
INSERT INTO `distribute_account` VALUES ('pl2hu3uczz', '15', '13', null, '5.76', '1', '不是创始人', '不是创始人的店', null, null, null, null, null, null, null, '5.76', '1465869925', '1', '0', '', '', '1', '0', '0.00', '0.00', '0.00', '0', '0.00', '0', '0.00', '1', '0', null, '0', '0', '0', '0', '391', '1', '0', '', null, '0.00', null);
INSERT INTO `distribute_account` VALUES ('pl2hu3uczz', '16', '14', null, '2.88', '1', '测试pc', '测试pc的店', null, null, null, null, null, null, null, '2.88', '1465950962', '1', '0', '', '', '1', '0', '0.00', '0.00', '0.00', '0', '0.00', '0', '0.00', '1', '0', null, '0', '0', '0', '0', '391', null, '0', '', null, '0.00', null);

-- ----------------------------
-- Table structure for distribute_account_message
-- ----------------------------
DROP TABLE IF EXISTS `distribute_account_message`;
CREATE TABLE `distribute_account_message` (
  `Mes_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `UsersID` varchar(10) NOT NULL COMMENT '平台编号',
  `User_ID` int(11) unsigned NOT NULL COMMENT '发消息的分销商编号',
  `Receiver_User_ID` int(11) unsigned NOT NULL COMMENT '消息接收人的分销商编号',
  `Mess_Content` text NOT NULL,
  `Mess_Status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '消息状态0未读，1已读',
  `Mess_CreateTime` int(11) unsigned DEFAULT NULL COMMENT '消息发送时间',
  PRIMARY KEY (`Mes_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_account_message
-- ----------------------------

-- ----------------------------
-- Table structure for distribute_account_record
-- ----------------------------
DROP TABLE IF EXISTS `distribute_account_record`;
CREATE TABLE `distribute_account_record` (
  `Users_ID` varchar(10) NOT NULL,
  `Record_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Ds_Record_ID` int(11) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL COMMENT '奖金获得者ID',
  `level` int(2) DEFAULT NULL,
  `Record_Sn` varchar(20) DEFAULT NULL,
  `Account_Info` varchar(200) DEFAULT NULL,
  `Record_Qty` int(10) DEFAULT '0' COMMENT '数量',
  `Record_Price` decimal(10,2) DEFAULT '0.00' COMMENT '数量为1时佣金金额，用于退款',
  `Record_Money` decimal(10,2) DEFAULT '0.00',
  `Record_Description` varchar(200) DEFAULT NULL,
  `Record_Type` tinyint(1) DEFAULT '0' COMMENT '获得奖金为0,提现为1',
  `Record_Status` tinyint(1) DEFAULT '0' COMMENT '针对获取佣金,状态0为已生成，状态1为已付款，状态2位已完成 ;针对提现，状态0为已生成，状态1才为已执行,状态2为驳回',
  `Nobi_Money` decimal(30,2) DEFAULT '0.00' COMMENT '爵位奖金',
  `Nobi_Description` char(100) DEFAULT '' COMMENT '爵位奖描述',
  `Record_CreateTime` int(10) DEFAULT NULL,
  `Nobi_Level` char(10) DEFAULT NULL,
  `deleted_at` varchar(30) DEFAULT NULL,
  `Owner_ID` int(10) DEFAULT NULL,
  `CartID` int(10) DEFAULT '0' COMMENT '购物车ID，用于退款',
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_account_record
-- ----------------------------
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '1', '15', '9', '1', 'WD20160612131787', null, '1', '0.00', '0.00', '自己销售自己购买三星手机&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465694977', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '2', '16', '9', '1', 'WD20160612011647', null, '1', '2.40', '2.40', '自己销售自己购买苹果手机&yen;200.00成功，获取奖金', '0', '1', '0.00', '您还没有爵位', '1465714282', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '3', '17', '11', '1', 'WD20160613433217', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465804778', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '4', '17', '10', '2', 'WD20160613425704', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465804778', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '5', '17', '12', '3', 'WD20160613257026', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465804778', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '6', '18', '11', '1', 'WD20160613724300', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465805019', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '7', '18', '10', '2', 'WD20160613876062', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465805019', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '8', '18', '12', '3', 'WD20160613199691', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465805019', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '9', '19', '11', '1', 'WD20160613384566', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465805040', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '10', '19', '10', '2', 'WD20160613889412', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465805040', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '11', '19', '12', '3', 'WD20160613390257', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465805040', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '12', '20', '11', '1', 'WD20160613225739', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465805775', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '13', '20', '10', '2', 'WD20160613532369', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465805775', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '14', '20', '12', '3', 'WD20160613675946', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465805775', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '15', '21', '11', '1', 'WD20160613818842', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465806640', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '16', '21', '10', '2', 'WD20160613457168', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465806640', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '17', '21', '12', '3', 'WD20160613052918', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465806640', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '18', '22', '11', '1', 'WD20160613846217', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465806880', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '19', '22', '10', '2', 'WD20160613035033', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465806880', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '20', '22', '12', '3', 'WD20160613120883', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465806880', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '21', '23', '11', '1', 'WD20160613054352', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465807054', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '22', '23', '10', '2', 'WD20160613230820', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465807054', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '23', '23', '12', '3', 'WD20160613272776', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465807054', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '24', '24', '11', '1', 'WD20160613590534', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465808378', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '25', '24', '10', '2', 'WD20160613959290', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465808378', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '26', '24', '12', '3', 'WD20160613082543', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465808378', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '27', '25', '11', '1', 'WD20160613439894', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465809904', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '28', '25', '10', '2', 'WD20160613195329', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465809904', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '29', '25', '12', '3', 'WD20160613714441', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465809904', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '30', '26', '11', '1', 'WD20160613136006', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810158', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '31', '26', '10', '2', 'WD20160613735007', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810158', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '32', '26', '12', '3', 'WD20160613637234', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810158', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '33', '27', '11', '1', 'WD20160613471118', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810214', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '34', '27', '10', '2', 'WD20160613327326', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810214', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '35', '27', '12', '3', 'WD20160613242885', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810214', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '36', '28', '11', '1', 'WD20160613587527', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810308', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '37', '28', '10', '2', 'WD20160613682607', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810308', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '38', '28', '12', '3', 'WD20160613049505', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810308', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '39', '29', '11', '1', 'WD20160613098063', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810336', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '40', '29', '10', '2', 'WD20160613855512', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810336', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '41', '29', '12', '3', 'WD20160613676354', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810336', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '42', '30', '11', '1', 'WD20160613399449', null, '2', '9.60', '19.20', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810377', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '43', '30', '10', '2', 'WD20160613594387', null, '2', '3.84', '7.68', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810377', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '44', '30', '12', '3', 'WD20160613700829', null, '2', '2.88', '5.76', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810377', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '45', '31', '11', '1', 'WD20160613446558', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810420', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '46', '31', '10', '2', 'WD20160613545395', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810420', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '47', '31', '12', '3', 'WD20160613544226', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810420', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '48', '32', '11', '1', 'WD20160613941754', null, '1', '12.00', '12.00', '下属分销商分销苹果手机&yen;200.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810475', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '49', '32', '10', '2', 'WD20160613270298', null, '1', '4.80', '4.80', '下属分销商分销苹果手机&yen;200.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810475', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '50', '32', '12', '3', 'WD20160613424809', null, '1', '3.60', '3.60', '自己销售自己购买苹果手机&yen;200.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810475', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '51', '33', '11', '1', 'WD20160613191990', null, '1', '12.00', '12.00', '下属分销商分销苹果手机&yen;200.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810513', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '52', '33', '10', '2', 'WD20160613666591', null, '1', '4.80', '4.80', '下属分销商分销苹果手机&yen;200.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810513', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '53', '33', '12', '3', 'WD20160613138312', null, '1', '3.60', '3.60', '自己销售自己购买苹果手机&yen;200.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810513', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '54', '34', '11', '1', 'WD20160613941754', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810567', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '55', '34', '10', '2', 'WD20160613270298', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810567', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '56', '34', '12', '3', 'WD20160613424809', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810567', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '57', '35', '11', '1', 'WD20160613972986', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810694', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '58', '35', '10', '2', 'WD20160613587135', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810694', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '59', '35', '12', '3', 'WD20160613995172', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810694', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '60', '36', '11', '1', 'WD20160613399449', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810826', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '61', '36', '10', '2', 'WD20160613594387', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810826', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '62', '36', '12', '3', 'WD20160613700829', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810826', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '63', '37', '11', '1', 'WD20160613928884', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810920', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '64', '37', '10', '2', 'WD20160613170953', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810920', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '65', '37', '12', '3', 'WD20160613527958', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810920', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '66', '38', '11', '1', 'WD20160613941754', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810984', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '67', '38', '10', '2', 'WD20160613270298', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810984', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '68', '38', '12', '3', 'WD20160613424809', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465810984', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '69', '39', '11', '1', 'WD20160613571907', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465811185', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '70', '39', '10', '2', 'WD20160613365363', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465811185', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '71', '39', '12', '3', 'WD20160613630036', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465811185', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '72', '40', '11', '1', 'WD20160613063397', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465811490', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '73', '40', '10', '2', 'WD20160613084127', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465811490', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '74', '40', '12', '3', 'WD20160613144881', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465811490', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '75', '41', '11', '1', 'WD20160613972986', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465811850', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '76', '41', '10', '2', 'WD20160613587135', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465811850', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '77', '41', '12', '3', 'WD20160613995172', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465811850', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '78', '42', '11', '1', 'WD20160613928884', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465812005', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '79', '42', '10', '2', 'WD20160613170953', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465812005', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '80', '42', '12', '3', 'WD20160613527958', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465812005', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '81', '43', '11', '1', 'WD20160613226817', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '1', '0.00', '您还没有爵位', '1465812528', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '82', '43', '10', '2', 'WD20160613185707', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '1', '0.00', '您还没有爵位', '1465812528', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '83', '43', '12', '3', 'WD20160613773350', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '1', '0.00', '您还没有爵位', '1465812528', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '84', '43', '11', '1', '2016061400448', '', '1', '9.60', '-9.60', '用户退款，减少佣金9.6元', '0', '1', '0.00', '您还没有爵位', '1465864958', '无爵位', null, '0', '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '85', '43', '10', '2', '2016061412995', '', '1', '3.84', '-3.84', '用户退款，减少佣金3.84元', '0', '1', '0.00', '您还没有爵位', '1465864959', '无爵位', null, '0', '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '86', '43', '12', '3', '2016061497800', '', '1', '2.88', '-2.88', '用户退款，减少佣金2.88元', '0', '1', '0.00', '您还没有爵位', '1465864959', '无爵位', null, '0', '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '87', '44', '11', '1', 'WD20160614466489', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465867057', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '88', '44', '10', '2', 'WD20160614843582', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465867057', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '89', '44', '12', '3', 'WD20160614119683', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465867057', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '90', '45', '11', '1', 'WD20160614034360', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465867765', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '91', '45', '10', '2', 'WD20160614689670', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465867765', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '92', '45', '12', '3', 'WD20160614372815', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465867765', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '93', '46', '11', '1', 'WD20160614994462', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '1', '0.00', '您还没有爵位', '1465868022', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '94', '46', '10', '2', 'WD20160614489083', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '1', '0.00', '您还没有爵位', '1465868022', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '95', '46', '12', '3', 'WD20160614722890', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '1', '0.00', '您还没有爵位', '1465868022', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '96', '47', '11', '1', 'WD20160614522824', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465868264', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '97', '47', '10', '2', 'WD20160614167441', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465868264', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '98', '47', '12', '3', 'WD20160614763977', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465868264', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '99', '48', '13', '1', 'WD20160614355401', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465870064', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '100', '49', '13', '1', 'WD20160614956594', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465871170', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '101', '50', '13', '1', 'WD20160614868485', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465871280', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '102', '51', '13', '1', 'WD20160614039506', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465874259', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '103', '52', '14', '1', 'WD20160615654475', null, '1', '0.00', '0.00', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465957204', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '104', '53', '14', '1', 'WD20160615228492', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465957587', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '105', '54', '10', '1', 'WD20160615940795', null, '1', '0.00', '0.00', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465960933', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '106', '61', '10', '1', 'WD20160615971140', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1465961949', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '107', '67', '10', '1', 'WD20160615025954', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465966410', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '108', '68', '11', '1', 'WD20160615793141', null, '1', '9.60', '9.60', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465966716', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '109', '68', '10', '2', 'WD20160615083605', null, '1', '3.84', '3.84', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465966716', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '110', '68', '12', '3', 'WD20160615490618', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1465966716', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '111', '69', '2', '1', 'WD20160616699518', null, '2', '2.88', '5.76', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1466042623', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '112', '70', '2', '1', 'WD20160616398843', null, '3', '3.60', '10.80', '自己销售自己购买苹果手机&yen;200.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1466042624', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '113', '71', '2', '1', 'WD20160616135436', null, '2', '3.60', '7.20', '自己销售自己购买苹果手机&yen;200.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1466042697', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '114', '72', '2', '1', 'WD20160616554910', null, '1', '0.00', '0.00', '自己销售自己购买三星手机&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1466042697', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '115', '73', '2', '1', 'WD20160616378957', null, '2', '2.88', '5.76', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1466043252', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '116', '74', '2', '1', 'WD20160616512510', null, '2', '2.88', '5.76', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1466043402', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '117', '75', '2', '1', 'WD20160616162460', null, '2', '2.88', '5.76', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1466043632', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '118', '76', '2', '1', 'WD20160616536323', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1466043970', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '119', '77', '2', '1', 'WD20160616772870', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1466044465', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '120', '78', '2', '1', 'WD20160616054619', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '0', '0.00', '您还没有爵位', '1466044480', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '121', '79', '2', '1', 'WD20160616174570', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1466044694', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '122', '80', '2', '1', 'WD20160616073487', null, '1', '2.88', '2.88', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1466058977', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '123', '81', '10', '1', 'WD20160617974393', null, '1', '0.48', '0.48', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1466133854', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '124', '82', '10', '1', 'WD20160617424322', null, '1', '0.48', '0.48', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1466134315', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '125', '83', '10', '1', 'WD20160617426804', null, '1', '1.44', '1.44', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1466149319', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '129', '85', '11', '1', 'WD20160617007193', null, '1', '4.80', '4.80', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '1', '0.00', '您还没有爵位', '1466154795', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '130', '85', '10', '2', 'WD20160617602835', null, '1', '1.92', '1.92', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '1', '0.00', '您还没有爵位', '1466154795', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '131', '85', '12', '3', 'WD20160617839134', null, '1', '1.44', '1.44', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '1', '0.00', '您还没有爵位', '1466154795', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '132', '86', '2', '1', 'WD20160618487194', null, '1', '1.44', '1.44', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1466216570', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '133', '87', '2', '1', 'WD20160618377167', null, '1', '1.44', '1.44', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1466217471', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '134', '88', '2', '1', 'WD20160618274459', null, '1', '1.44', '1.44', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '2', '0.00', '您还没有爵位', '1466218104', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '135', '89', '2', '1', 'WD20160618647064', null, '1', '1.44', '1.44', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '1', '0.00', '您还没有爵位', '1466222200', '无爵位', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '136', '90', '11', '1', 'WD20160620179390', null, '1', '4.80', '4.80', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '1', '0.00', '皇冠奖金', '1466389466', '皇冠', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '137', '90', '10', '2', 'WD20160620341163', null, '1', '1.92', '1.92', '下属分销商分销测试业务产品&yen;100.00成功，获取奖金', '0', '1', '0.00', '皇冠奖金', '1466389466', '皇冠', null, null, '0');
INSERT INTO `distribute_account_record` VALUES ('pl2hu3uczz', '138', '90', '12', '3', 'WD20160620856654', null, '1', '1.44', '1.44', '自己销售自己购买测试业务产品&yen;100.00成功，获取奖金', '0', '1', '0.00', '皇冠奖金', '1466389466', '皇冠', null, null, '0');

-- ----------------------------
-- Table structure for distribute_agent_areas
-- ----------------------------
DROP TABLE IF EXISTS `distribute_agent_areas`;
CREATE TABLE `distribute_agent_areas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '0' COMMENT '类型 1 省代理，2市代理',
  `Users_ID` varchar(10) DEFAULT NULL,
  `Account_ID` int(10) DEFAULT NULL COMMENT '代理人用户ID',
  `area_id` int(10) DEFAULT NULL COMMENT '代理地区ID',
  `area_name` varchar(20) DEFAULT NULL COMMENT '地区别名，也就是地区名的拼音',
  `create_at` int(10) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态 0 为禁用 1 为启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_agent_areas
-- ----------------------------

-- ----------------------------
-- Table structure for distribute_agent_rec
-- ----------------------------
DROP TABLE IF EXISTS `distribute_agent_rec`;
CREATE TABLE `distribute_agent_rec` (
  `Record_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) DEFAULT NULL,
  `Account_ID` int(10) DEFAULT '0',
  `Real_Name` varchar(400) DEFAULT NULL COMMENT '真实姓名',
  `Account_Mobile` varchar(11) DEFAULT NULL,
  `Record_Money` float(10,2) DEFAULT NULL,
  `Record_Type` tinyint(1) DEFAULT '0' COMMENT '代理类型 1 省代理 2城市代理 3县（区）代理',
  `area_id` int(10) DEFAULT NULL COMMENT '代理地区ID',
  `Record_CreateTime` int(10) DEFAULT NULL,
  `Order_ID` int(11) unsigned DEFAULT '0' COMMENT '订单编号',
  `Order_CreateTime` int(11) unsigned DEFAULT '0' COMMENT '订单创建时间',
  `Products_Name` varchar(50) DEFAULT NULL COMMENT '商品名称',
  `Products_Qty` int(10) unsigned DEFAULT '0' COMMENT '商品购买数量',
  `Products_PriceX` decimal(10,2) DEFAULT NULL COMMENT '商品价格',
  `area_Proxy_Reward` int(10) unsigned DEFAULT '0' COMMENT '区域代理提成比例',
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_agent_rec
-- ----------------------------

-- ----------------------------
-- Table structure for distribute_config
-- ----------------------------
DROP TABLE IF EXISTS `distribute_config`;
CREATE TABLE `distribute_config` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Dis_Level` int(11) DEFAULT '1' COMMENT '分销级别',
  `Dis_Mobile_Level` int(10) DEFAULT '1' COMMENT '用户手机界面显示多少级分销商',
  `Dis_Self_Bonus` tinyint(1) DEFAULT '0' COMMENT '分销商在自己店购买得佣金',
  `Distribute_Type` tinyint(1) DEFAULT '1' COMMENT '分销商门槛  0 直接购买 1 消费额 2 购买商品',
  `Distribute_Audit` tinyint(1) DEFAULT '0' COMMENT '0表示禁用审核，1表示启用审核',
  `Withdraw_Type` tinyint(1) DEFAULT '0' COMMENT '分销商提现门槛\r\n 0 无限制 1 佣金限制\r\n 2 购买商品',
  `Withdraw_Limit` varchar(255) DEFAULT '' COMMENT '分销商提现门槛',
  `Withdraw_PerLimit` int(10) DEFAULT '0',
  `Distribute_Customize` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许分销商自定义店名和头像，默认不允许0',
  `Dis_Agent_Type` tinyint(1) DEFAULT '0' COMMENT '分销代理类别 0 关闭代理，1地区代理',
  `Sha_Agent_Type` tinyint(1) DEFAULT '0' COMMENT '股东类别 0 关闭股，1股东开启',
  `Agent_Rate` text COMMENT '代理利润率',
  `Sha_Rate` text COMMENT '股东设置串',
  `Distribute_Share` tinyint(1) DEFAULT '0',
  `Distribute_ShareScore` int(10) DEFAULT '0',
  `QrcodeBg` varchar(255) DEFAULT '' COMMENT '我的二维码背景图片',
  `ApplyBanner` varchar(255) DEFAULT '' COMMENT '成为分销商页面顶部banner',
  `HIncomelist_Open` tinyint(1) DEFAULT '0' COMMENT '总部财富排行榜是否公开',
  `H_Incomelist_Limit` float(10,2) DEFAULT '0.00' COMMENT '总部财富排行榜入榜最低佣金限制',
  `Fanben_Open` tinyint(1) DEFAULT '0' COMMENT '返本规则开关  0 关闭 1 开启',
  `Fanben_Rules` varchar(255) DEFAULT '' COMMENT '返本规则详情  三个参数  直接下属，返现金额，返现次数',
  `Fanben_Type` tinyint(1) DEFAULT '0' COMMENT '返本限制类型 针对下级  0 无限制 1 购买指定产品',
  `Fanben_Limit` varchar(255) DEFAULT '' COMMENT '返本具体限制',
  `Fuxiao_Open` tinyint(1) DEFAULT '0',
  `Fuxiao_Rules` varchar(255) DEFAULT '' COMMENT '返本规则详情 三个参数  消费金额  提醒提前天数 冻结时间 ',
  `Balance_Ratio` decimal(10,2) DEFAULT '0.00' COMMENT '提现余额分配比例',
  `Poundage_Ratio` decimal(10,2) DEFAULT '0.00' COMMENT '提现手续费',
  `Pro_Title_Level` text COMMENT '爵位称呼',
  `Pro_Title_Status` tinyint(1) DEFAULT '4' COMMENT '爵位晋级金额计入状态',
  `Level_UpdateAuto` tinyint(1) DEFAULT '1' COMMENT '是否自动升级 0 禁止 1 开启',
  `Index_Professional_Json` text,
  `Distribute_Limit` tinyint(1) DEFAULT '0' COMMENT '分销商人数限制是否开启',
  `Distribute_Agreement` longtext,
  `Distribute_AgreementTitle` varchar(255) DEFAULT '',
  `Distribute_AgreementOpen` tinyint(1) DEFAULT '0',
  `Distribute_ShopOpen` tinyint(1) DEFAULT '0' COMMENT '成为分销商才能进入商城 0 关闭 1 开启',
  `Salesman` int(10) DEFAULT '0' COMMENT '业务员限制',
  `Salesman_ImgPath` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=302 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_config
-- ----------------------------
INSERT INTO `distribute_config` VALUES ('301', 'pl2hu3uczz', '3', '3', '1', '1', '0', '0', '', '0', '0', '0', '1', '', '{\"sha\":{\"1\":{\"name\":\"铜牌\",\"Province\":\"0\",\"Level\":\"391\",\"Protitle\":\"1\",\"Selfpro\":\"0\",\"Teampro\":\"0\",\"price\":\"0\"},\"2\":{\"name\":\"银牌\",\"Province\":\"0\",\"Level\":\"391\",\"Protitle\":\"0\",\"Selfpro\":\"0\",\"Teampro\":\"0\",\"price\":\"0\"},\"3\":{\"name\":\"金牌\",\"Province\":\"0\",\"Level\":\"391\",\"Protitle\":\"0\",\"Selfpro\":\"0\",\"Teampro\":\"0\",\"price\":\"0\"}},\"Shaenable\":\"1\"}', '0', '0', '/static/api/distribute/images/qrcode_bg.jpg', '/static/api/distribute/images/apply_distribute.png', '0', '0.00', '0', '', '0', '', '0', '', '0.00', '0.00', '{\"1\":{\"Name\":\"皇冠\",\"Consume\":\"0\",\"Sales_Self\":\"0\",\"Sales_Group\":\"0\",\"Bonus\":\"0\",\"ImgPath\":\"\"},\"2\":{\"Name\":\"\",\"Consume\":\"\",\"Sales_Self\":\"\",\"Sales_Group\":\"\",\"Bonus\":\"\",\"ImgPath\":\"\"},\"3\":{\"Name\":\"\",\"Consume\":\"\",\"Sales_Self\":\"\",\"Sales_Group\":\"\",\"Bonus\":\"\",\"ImgPath\":\"\"},\"4\":{\"Name\":\"\",\"Consume\":\"\",\"Sales_Self\":\"\",\"Sales_Group\":\"\",\"Bonus\":\"\",\"ImgPath\":\"\"}}', '2', '1', null, '0', '', '', '0', '0', '10', '');

-- ----------------------------
-- Table structure for distribute_fanben_record
-- ----------------------------
DROP TABLE IF EXISTS `distribute_fanben_record`;
CREATE TABLE `distribute_fanben_record` (
  `Item_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT NULL,
  `User_ID` int(10) DEFAULT '0',
  `Record_Money` decimal(10,2) DEFAULT '0.00',
  `Note` varchar(255) DEFAULT NULL,
  `CreateTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`Item_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of distribute_fanben_record
-- ----------------------------

-- ----------------------------
-- Table structure for distribute_fuxiao
-- ----------------------------
DROP TABLE IF EXISTS `distribute_fuxiao`;
CREATE TABLE `distribute_fuxiao` (
  `Record_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT '',
  `User_ID` int(10) DEFAULT '0',
  `User_OpenID` varchar(255) DEFAULT '',
  `Account_ID` int(10) DEFAULT '0' COMMENT '分销商ID',
  `Fuxiao_StartTime` int(10) DEFAULT '0' COMMENT '复销开始时间',
  `Fuxiao_Status` tinyint(1) DEFAULT '0' COMMENT '账号状态  0 正常 1 冻结 2 删除',
  `Fuxiao_Count` int(10) DEFAULT '0' COMMENT '该账户进行复销总次数',
  `Fuxiao_SubNoticeCount` int(10) DEFAULT '0' COMMENT '当月复销提醒剩余天数（冻结前）',
  `Fuxiao_LastNoticeTime` int(10) DEFAULT '0' COMMENT '最近发送提醒消息时间(冻结前)',
  `Fuxiao_SubDenedCount` int(10) DEFAULT '0' COMMENT '冻结提醒提醒剩余天数（冻结后）',
  `Fuxiao_LastDenedTime` int(10) DEFAULT '0' COMMENT '最近发送提醒消息时间(冻结后)',
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_fuxiao
-- ----------------------------

-- ----------------------------
-- Table structure for distribute_level
-- ----------------------------
DROP TABLE IF EXISTS `distribute_level`;
CREATE TABLE `distribute_level` (
  `Level_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT '',
  `Level_Name` varchar(50) DEFAULT '' COMMENT '级别名称',
  `Level_Sort` int(10) DEFAULT '0' COMMENT '级别排序',
  `Level_LimitType` tinyint(1) DEFAULT '0' COMMENT '0 具体价格  1 消费额 2 购买商品 3 无门槛 ',
  `Level_LimitValue` varchar(255) DEFAULT '',
  `Level_PeopleLimit` text COMMENT '级别限制策略',
  `Level_Distributes` text COMMENT '佣金策略 具体金额',
  `Level_CreateTime` int(10) DEFAULT '0',
  `Level_ImgPath` varchar(255) DEFAULT '',
  `Level_UpdateType` tinyint(1) DEFAULT '0' COMMENT '升级门槛 0 补差价  1 购买指定产品',
  `Level_UpdateValue` varchar(255) DEFAULT '' COMMENT '升级具体设置',
  `Level_UpdateDistributes` varchar(255) DEFAULT '' COMMENT '补差价佣金设置',
  PRIMARY KEY (`Level_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=392 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_level
-- ----------------------------
INSERT INTO `distribute_level` VALUES ('391', 'pl2hu3uczz', '普通分销商', '0', '3', '', '{\"1\":0,\"2\":0,\"3\":0}', null, '1464431894', '', '0', '', '');

-- ----------------------------
-- Table structure for distribute_order
-- ----------------------------
DROP TABLE IF EXISTS `distribute_order`;
CREATE TABLE `distribute_order` (
  `Order_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL DEFAULT '',
  `User_ID` varchar(10) NOT NULL DEFAULT '',
  `Address_Name` varchar(50) DEFAULT '' COMMENT '姓名',
  `Address_Mobile` varchar(11) DEFAULT '' COMMENT '手机号',
  `Address_Detail` varchar(255) DEFAULT '' COMMENT '详细地址',
  `Address_WeixinID` varchar(100) DEFAULT '' COMMENT '购买人微信号',
  `Order_PaymentMethod` varchar(10) DEFAULT '' COMMENT '支付方式',
  `Order_PaymentInfo` varchar(255) DEFAULT '' COMMENT '支付信息  线下支付有用',
  `Order_TotalPrice` decimal(11,2) DEFAULT '0.00' COMMENT '订单金额',
  `Owner_ID` int(10) DEFAULT '0' COMMENT '上级ID',
  `Order_PayTime` int(10) DEFAULT '0' COMMENT '订单支付时间',
  `Order_PayID` varchar(100) DEFAULT '' COMMENT '订单支付号',
  `Level_ID` int(10) DEFAULT '0' COMMENT '分销级别ID',
  `Level_Name` varchar(100) DEFAULT '',
  `Order_Status` tinyint(1) DEFAULT '0' COMMENT '订单状态   1 待付款 4 已付款',
  `Order_CreateTime` int(10) DEFAULT '0' COMMENT '下单时间',
  `Order_Type` tinyint(1) DEFAULT '0' COMMENT '订单类型 0 购买级别  1 在线升级',
  `UpgradeDistributes` varchar(255) DEFAULT '' COMMENT '升级佣金明细',
  PRIMARY KEY (`Order_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_order
-- ----------------------------

-- ----------------------------
-- Table structure for distribute_order_record
-- ----------------------------
DROP TABLE IF EXISTS `distribute_order_record`;
CREATE TABLE `distribute_order_record` (
  `Users_ID` varchar(10) NOT NULL,
  `Record_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Order_ID` int(10) DEFAULT '0',
  `User_ID` int(11) DEFAULT NULL COMMENT '奖金获得者ID',
  `level` int(2) DEFAULT NULL,
  `Record_Money` decimal(10,2) DEFAULT '0.00',
  `Record_Description` varchar(200) DEFAULT NULL,
  `Record_Status` tinyint(1) DEFAULT '0' COMMENT '针对获取佣金,状态0为已生成，状态1为已付款，状态2位已完成 ;针对提现，状态0为已生成，状态1才为已执行,状态2为驳回',
  `Record_CreateTime` int(10) DEFAULT NULL,
  `Owner_ID` int(10) DEFAULT NULL,
  `Buyer_ID` int(10) DEFAULT '0',
  `Price` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_order_record
-- ----------------------------

-- ----------------------------
-- Table structure for distribute_record
-- ----------------------------
DROP TABLE IF EXISTS `distribute_record`;
CREATE TABLE `distribute_record` (
  `Users_ID` varchar(10) NOT NULL,
  `Record_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Buyer_ID` int(11) DEFAULT NULL COMMENT '商品购买者ID',
  `Owner_ID` int(11) DEFAULT NULL,
  `Order_ID` int(11) DEFAULT NULL,
  `Product_ID` int(11) DEFAULT NULL,
  `Product_Price` float(10,2) DEFAULT '0.00',
  `Qty` smallint(6) DEFAULT '0',
  `Bonous_1` float(10,2) DEFAULT '0.00',
  `Bonous_2` float(10,2) DEFAULT NULL,
  `Bonous_3` float(10,2) DEFAULT NULL,
  `status` tinyint(2) DEFAULT '0' COMMENT '0为已下单,1为已完成',
  `Record_CreateTime` int(10) DEFAULT NULL,
  `deleted_at` varchar(30) DEFAULT NULL,
  `Biz_ID` int(10) DEFAULT '0',
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_record
-- ----------------------------
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '1', '3', '3', '1', '1', '100.00', '1', '0.00', null, null, '1', '1464849028', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '3', '2', '2', '3', '1', '100.00', '1', '0.00', null, null, '0', '1464938256', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '4', '2', '2', '4', '1', '100.00', '1', '0.00', null, null, '0', '1464938309', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '5', '2', '2', '5', '1', '100.00', '1', '0.00', null, null, '0', '1464938335', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '6', '2', '2', '6', '1', '100.00', '1', '0.00', null, null, '0', '1464938372', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '7', '2', '2', '7', '1', '100.00', '1', '0.00', null, null, '0', '1464938424', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '8', '2', '2', '8', '1', '100.00', '1', '0.00', null, null, '0', '1464938471', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '9', '2', '2', '9', '1', '100.00', '1', '0.00', null, null, '0', '1464938513', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '10', '2', '2', '10', '1', '100.00', '1', '0.00', null, null, '0', '1464938546', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '11', '2', '2', '11', '1', '100.00', '1', '0.00', null, null, '0', '1464938559', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '12', '2', '2', '12', '1', '100.00', '1', '0.00', null, null, '0', '1464945377', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '13', '3', '3', '13', '1', '100.00', '1', '0.00', null, null, '0', '1465177071', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '14', '2', '2', '15', '2', '200.00', '1', '0.00', null, null, '0', '1465350853', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '15', '9', '9', '16', '1', '100.00', '1', '0.00', null, null, '1', '1465694977', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '16', '9', '9', '17', '2', '200.00', '1', '0.00', null, null, '0', '1465714281', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '17', '12', '12', '18', '3', '100.00', '1', '0.00', null, null, '0', '1465804778', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '18', '12', '12', '19', '3', '100.00', '1', '0.00', null, null, '0', '1465805019', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '19', '12', '12', '20', '3', '100.00', '1', '0.00', null, null, '0', '1465805040', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '20', '12', '12', '21', '3', '100.00', '1', '0.00', null, null, '0', '1465805775', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '21', '12', '12', '22', '3', '100.00', '1', '0.00', null, null, '0', '1465806640', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '22', '12', '12', '23', '3', '100.00', '1', '0.00', null, null, '0', '1465806880', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '23', '12', '12', '24', '3', '100.00', '1', '0.00', null, null, '0', '1465807054', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '24', '12', '12', '25', '3', '100.00', '1', '0.00', null, null, '0', '1465808378', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '25', '12', '12', '26', '3', '100.00', '1', '0.00', null, null, '0', '1465809904', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '26', '12', '12', '27', '3', '100.00', '1', '0.00', null, null, '0', '1465810158', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '27', '12', '12', '28', '3', '100.00', '1', '0.00', null, null, '0', '1465810214', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '28', '12', '12', '29', '3', '100.00', '1', '0.00', null, null, '0', '1465810308', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '29', '12', '12', '30', '3', '100.00', '1', '0.00', null, null, '0', '1465810336', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '30', '12', '12', '31', '3', '100.00', '2', '0.00', null, null, '0', '1465810377', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '31', '12', '12', '32', '3', '100.00', '1', '0.00', null, null, '0', '1465810420', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '32', '12', '12', '33', '2', '200.00', '1', '0.00', null, null, '0', '1465810475', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '33', '12', '12', '34', '2', '200.00', '1', '0.00', null, null, '0', '1465810513', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '34', '12', '12', '35', '3', '100.00', '1', '0.00', null, null, '0', '1465810567', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '35', '12', '12', '36', '3', '100.00', '1', '0.00', null, null, '0', '1465810694', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '36', '12', '12', '37', '3', '100.00', '1', '0.00', null, null, '0', '1465810826', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '37', '12', '12', '38', '3', '100.00', '1', '0.00', null, null, '0', '1465810920', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '38', '12', '12', '39', '3', '100.00', '1', '0.00', null, null, '0', '1465810984', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '39', '12', '12', '40', '3', '100.00', '1', '0.00', null, null, '0', '1465811185', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '40', '12', '12', '41', '3', '100.00', '1', '0.00', null, null, '0', '1465811490', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '41', '12', '12', '42', '3', '100.00', '1', '0.00', null, null, '0', '1465811850', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '42', '12', '12', '43', '3', '100.00', '1', '0.00', null, null, '1', '1465812005', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '43', '12', '12', '44', '3', '100.00', '1', '0.00', null, null, '0', '1465812528', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '44', '12', '12', '45', '3', '100.00', '1', '0.00', null, null, '1', '1465867057', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '45', '12', '12', '46', '3', '100.00', '1', '0.00', null, null, '1', '1465867765', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '46', '12', '12', '47', '3', '100.00', '1', '0.00', null, null, '0', '1465868022', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '47', '12', '12', '48', '3', '100.00', '1', '0.00', null, null, '1', '1465868264', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '48', '13', '13', '49', '3', '100.00', '1', '0.00', null, null, '1', '1465870064', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '49', '13', '13', '50', '3', '100.00', '1', '0.00', null, null, '0', '1465871170', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '50', '13', '13', '51', '3', '100.00', '1', '0.00', null, null, '0', '1465871280', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '51', '13', '13', '52', '3', '100.00', '1', '0.00', null, null, '1', '1465874259', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '52', '14', '14', '53', '3', '100.00', '1', '0.00', null, null, '1', '1465957204', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '53', '14', '14', '54', '3', '100.00', '1', '0.00', null, null, '1', '1465957587', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '54', '10', '10', '55', '3', '100.00', '1', '0.00', null, null, '0', '1465960933', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '55', '10', '10', '56', '3', '100.00', '1', '0.00', null, null, '0', '1465961028', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '56', '10', '10', '57', '3', '100.00', '1', '0.00', null, null, '0', '1465961110', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '57', '10', '10', '58', '3', '100.00', '1', '0.00', null, null, '0', '1465961373', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '58', '10', '10', '59', '3', '100.00', '1', '0.00', null, null, '0', '1465961466', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '59', '10', '10', '60', '3', '100.00', '1', '0.00', null, null, '0', '1465961610', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '60', '10', '10', '61', '3', '100.00', '1', '0.00', null, null, '0', '1465961687', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '61', '10', '10', '62', '3', '100.00', '1', '0.00', null, null, '0', '1465961949', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '62', '10', '10', '64', '3', '100.00', '1', '0.00', null, null, '0', '1465962422', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '63', '10', '10', '66', '3', '100.00', '1', '0.00', null, null, '0', '1465962737', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '64', '10', '10', '67', '3', '100.00', '1', '0.00', null, null, '0', '1465963091', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '65', '10', '10', '68', '3', '100.00', '1', '0.00', null, null, '0', '1465963169', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '66', '10', '10', '69', '3', '100.00', '1', '0.00', null, null, '0', '1465963240', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '67', '10', '10', '70', '3', '100.00', '1', '0.00', null, null, '1', '1465966410', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '68', '12', '12', '71', '3', '100.00', '1', '0.00', null, null, '1', '1465966716', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '69', '2', '2', '72', '3', '100.00', '2', '0.00', null, null, '1', '1466042623', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '70', '2', '2', '73', '2', '200.00', '3', '0.00', null, null, '0', '1466042624', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '71', '2', '2', '74', '2', '200.00', '2', '0.00', null, null, '0', '1466042697', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '72', '2', '2', '74', '1', '100.00', '1', '0.00', null, null, '0', '1466042697', null, '1');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '73', '2', '2', '75', '3', '100.00', '2', '0.00', null, null, '1', '1466043252', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '74', '2', '2', '76', '3', '100.00', '2', '0.00', null, null, '1', '1466043402', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '75', '2', '2', '77', '3', '100.00', '2', '0.00', null, null, '1', '1466043631', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '76', '2', '2', '78', '3', '100.00', '1', '0.00', null, null, '0', '1466043970', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '77', '2', '2', '79', '3', '100.00', '1', '0.00', null, null, '0', '1466044465', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '78', '2', '2', '80', '3', '100.00', '1', '0.00', null, null, '0', '1466044480', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '79', '2', '2', '81', '3', '100.00', '1', '0.00', null, null, '1', '1466044694', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '80', '2', '2', '82', '3', '100.00', '1', '0.00', null, null, '1', '1466058976', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '81', '10', '10', '83', '3', '100.00', '1', '0.00', null, null, '1', '1466133854', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '82', '10', '10', '84', '3', '100.00', '1', '0.00', null, null, '1', '1466134315', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '83', '10', '10', '85', '3', '100.00', '1', '0.00', null, null, '1', '1466149319', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '85', '12', '12', '87', '3', '100.00', '1', '0.00', null, null, '0', '1466154795', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '86', '2', '2', '88', '3', '100.00', '1', '0.00', null, null, '1', '1466216570', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '87', '2', '2', '89', '3', '100.00', '1', '0.00', null, null, '1', '1466217471', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '88', '2', '2', '90', '3', '100.00', '1', '0.00', null, null, '1', '1466218104', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '89', '2', '2', '91', '3', '100.00', '1', '0.00', null, null, '0', '1466222200', null, '8');
INSERT INTO `distribute_record` VALUES ('pl2hu3uczz', '90', '12', '12', '92', '3', '100.00', '1', '0.00', null, null, '0', '1466389466', null, '8');

-- ----------------------------
-- Table structure for distribute_sales_record
-- ----------------------------
DROP TABLE IF EXISTS `distribute_sales_record`;
CREATE TABLE `distribute_sales_record` (
  `id` int(32) unsigned NOT NULL AUTO_INCREMENT COMMENT '业务奖励表id',
  `Users_ID` char(10) DEFAULT '' COMMENT '总平台id',
  `User_ID` int(16) DEFAULT '0' COMMENT '分销商id',
  `Biz_ID` int(16) DEFAULT '0' COMMENT '商家id',
  `Status` int(3) DEFAULT '0' COMMENT '奖励状态0生成1已付款2完成',
  `Order_ID` int(16) DEFAULT NULL COMMENT '订单id',
  `Products_ID` int(16) DEFAULT '0' COMMENT '产品id',
  `Products_Price` decimal(10,2) DEFAULT '0.00',
  `Products_Qty` int(5) DEFAULT '0' COMMENT '商品数量',
  `Sales_Money` decimal(16,2) DEFAULT '0.00' COMMENT '业务奖励',
  `Sales_Description` char(100) DEFAULT '' COMMENT '奖励描述',
  `Level` int(2) DEFAULT '0' COMMENT '所处级别',
  `update_time` int(12) DEFAULT '0',
  `create_time` int(12) DEFAULT '0',
  `deleted_at` char(30) DEFAULT NULL,
  `CartID` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_sales_record
-- ----------------------------
INSERT INTO `distribute_sales_record` VALUES ('1', 'pl2hu3uczz', '12', '8', '1', '43', '3', '100.00', '1', '4.00', '1级，', '1', '0', '1465812005', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('2', 'pl2hu3uczz', '11', '8', '1', '43', '3', '100.00', '1', '2.40', '2级，', '2', '0', '1465812005', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('3', 'pl2hu3uczz', '10', '8', '1', '43', '3', '100.00', '1', '1.60', '3级，', '3', '0', '1465812005', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('4', 'pl2hu3uczz', '12', '8', '0', '44', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1465812528', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('5', 'pl2hu3uczz', '11', '8', '0', '44', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1465812528', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('6', 'pl2hu3uczz', '10', '8', '0', '44', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1465812528', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('7', 'pl2hu3uczz', '12', '8', '1', '45', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1465867057', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('8', 'pl2hu3uczz', '11', '8', '1', '45', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1465867057', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('9', 'pl2hu3uczz', '10', '8', '1', '45', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1465867057', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('10', 'pl2hu3uczz', '12', '8', '1', '46', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1465867765', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('11', 'pl2hu3uczz', '11', '8', '1', '46', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1465867765', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('12', 'pl2hu3uczz', '10', '8', '1', '46', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1465867765', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('13', 'pl2hu3uczz', '12', '8', '1', '47', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1465868022', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('14', 'pl2hu3uczz', '11', '8', '1', '47', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1465868022', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('15', 'pl2hu3uczz', '10', '8', '1', '47', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1465868022', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('16', 'pl2hu3uczz', '12', '8', '2', '48', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1465868264', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('17', 'pl2hu3uczz', '11', '8', '2', '48', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1465868264', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('18', 'pl2hu3uczz', '10', '8', '2', '48', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1465868264', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('19', 'pl2hu3uczz', '12', '8', '2', '49', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1465870064', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('20', 'pl2hu3uczz', '11', '8', '2', '49', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1465870064', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('21', 'pl2hu3uczz', '10', '8', '2', '49', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1465870064', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('22', 'pl2hu3uczz', '12', '8', '2', '52', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1465874259', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('23', 'pl2hu3uczz', '11', '8', '2', '52', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1465874259', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('24', 'pl2hu3uczz', '10', '8', '2', '52', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1465874259', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('25', 'pl2hu3uczz', '12', '8', '2', '54', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1465957587', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('26', 'pl2hu3uczz', '11', '8', '2', '54', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1465957587', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('27', 'pl2hu3uczz', '10', '8', '2', '54', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1465957587', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('28', 'pl2hu3uczz', '12', '8', '0', '62', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1465961949', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('29', 'pl2hu3uczz', '11', '8', '0', '62', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1465961949', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('30', 'pl2hu3uczz', '10', '8', '0', '62', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1465961949', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('31', 'pl2hu3uczz', '12', '8', '2', '72', '3', '100.00', '2', '2.40', '1级，', '1', '0', '1466042624', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('32', 'pl2hu3uczz', '11', '8', '2', '72', '3', '100.00', '2', '1.44', '2级，', '2', '0', '1466042624', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('33', 'pl2hu3uczz', '10', '8', '2', '72', '3', '100.00', '2', '0.96', '3级，', '3', '0', '1466042624', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('34', 'pl2hu3uczz', '9', '1', '0', '73', '2', '200.00', '3', '3.00', '1级，', '1', '0', '1466042624', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('35', 'pl2hu3uczz', '9', '1', '0', '74', '2', '200.00', '2', '3.00', '1级，', '1', '0', '1466042697', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('36', 'pl2hu3uczz', '9', '1', '0', '74', '1', '100.00', '1', '0.00', '1级，', '1', '0', '1466042697', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('37', 'pl2hu3uczz', '12', '8', '2', '75', '3', '100.00', '2', '2.40', '1级，', '1', '0', '1466043252', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('38', 'pl2hu3uczz', '11', '8', '2', '75', '3', '100.00', '2', '1.44', '2级，', '2', '0', '1466043252', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('39', 'pl2hu3uczz', '10', '8', '2', '75', '3', '100.00', '2', '0.96', '3级，', '3', '0', '1466043252', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('40', 'pl2hu3uczz', '12', '8', '2', '76', '3', '100.00', '2', '2.40', '1级，', '1', '0', '1466043402', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('41', 'pl2hu3uczz', '11', '8', '2', '76', '3', '100.00', '2', '1.44', '2级，', '2', '0', '1466043402', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('42', 'pl2hu3uczz', '10', '8', '2', '76', '3', '100.00', '2', '0.96', '3级，', '3', '0', '1466043402', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('43', 'pl2hu3uczz', '12', '8', '2', '77', '3', '100.00', '2', '2.40', '1级，', '1', '0', '1466043632', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('44', 'pl2hu3uczz', '11', '8', '2', '77', '3', '100.00', '2', '1.44', '2级，', '2', '0', '1466043632', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('45', 'pl2hu3uczz', '10', '8', '2', '77', '3', '100.00', '2', '0.96', '3级，', '3', '0', '1466043632', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('46', 'pl2hu3uczz', '12', '8', '0', '78', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1466043970', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('47', 'pl2hu3uczz', '11', '8', '0', '78', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1466043970', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('48', 'pl2hu3uczz', '10', '8', '0', '78', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1466043970', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('49', 'pl2hu3uczz', '12', '8', '2', '81', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1466044694', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('50', 'pl2hu3uczz', '11', '8', '2', '81', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1466044694', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('51', 'pl2hu3uczz', '10', '8', '2', '81', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1466044694', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('52', 'pl2hu3uczz', '12', '8', '2', '82', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1466058977', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('53', 'pl2hu3uczz', '11', '8', '2', '82', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1466058977', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('54', 'pl2hu3uczz', '10', '8', '2', '82', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1466058977', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('55', 'pl2hu3uczz', '12', '8', '2', '83', '3', '100.00', '1', '0.00', '1级，', '1', '0', '1466133854', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('56', 'pl2hu3uczz', '11', '8', '2', '83', '3', '100.00', '1', '0.00', '2级，', '2', '0', '1466133854', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('57', 'pl2hu3uczz', '10', '8', '2', '83', '3', '100.00', '1', '0.00', '3级，', '3', '0', '1466133854', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('58', 'pl2hu3uczz', '12', '8', '2', '84', '3', '100.00', '1', '0.00', '1级，', '1', '0', '1466134315', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('59', 'pl2hu3uczz', '11', '8', '2', '84', '3', '100.00', '1', '0.00', '2级，', '2', '0', '1466134315', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('60', 'pl2hu3uczz', '10', '8', '2', '84', '3', '100.00', '1', '0.00', '3级，', '3', '0', '1466134315', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('61', 'pl2hu3uczz', '12', '8', '2', '85', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1466149319', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('62', 'pl2hu3uczz', '11', '8', '2', '85', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1466149319', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('63', 'pl2hu3uczz', '10', '8', '2', '85', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1466149319', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('64', 'pl2hu3uczz', '12', '8', '0', '86', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1466154721', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('65', 'pl2hu3uczz', '11', '8', '0', '86', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1466154721', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('66', 'pl2hu3uczz', '10', '8', '0', '86', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1466154721', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('67', 'pl2hu3uczz', '12', '8', '1', '87', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1466154795', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('68', 'pl2hu3uczz', '11', '8', '1', '87', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1466154795', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('69', 'pl2hu3uczz', '10', '8', '1', '87', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1466154795', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('70', 'pl2hu3uczz', '12', '8', '2', '88', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1466216570', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('71', 'pl2hu3uczz', '11', '8', '2', '88', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1466216570', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('72', 'pl2hu3uczz', '10', '8', '2', '88', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1466216570', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('73', 'pl2hu3uczz', '12', '8', '2', '89', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1466217471', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('74', 'pl2hu3uczz', '11', '8', '2', '89', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1466217471', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('75', 'pl2hu3uczz', '10', '8', '2', '89', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1466217471', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('76', 'pl2hu3uczz', '12', '8', '2', '90', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1466218104', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('77', 'pl2hu3uczz', '11', '8', '2', '90', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1466218104', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('78', 'pl2hu3uczz', '10', '8', '2', '90', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1466218104', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('79', 'pl2hu3uczz', '12', '8', '1', '91', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1466222200', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('80', 'pl2hu3uczz', '11', '8', '1', '91', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1466222200', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('81', 'pl2hu3uczz', '10', '8', '1', '91', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1466222200', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('82', 'pl2hu3uczz', '12', '8', '1', '92', '3', '100.00', '1', '2.40', '1级，', '1', '0', '1466389466', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('83', 'pl2hu3uczz', '11', '8', '1', '92', '3', '100.00', '1', '1.44', '2级，', '2', '0', '1466389466', null, '0');
INSERT INTO `distribute_sales_record` VALUES ('84', 'pl2hu3uczz', '10', '8', '1', '92', '3', '100.00', '1', '0.96', '3级，', '3', '0', '1466389466', null, '0');

-- ----------------------------
-- Table structure for distribute_sha_rec
-- ----------------------------
DROP TABLE IF EXISTS `distribute_sha_rec`;
CREATE TABLE `distribute_sha_rec` (
  `Record_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) DEFAULT NULL,
  `Account_ID` int(10) DEFAULT '0',
  `Real_Name` varchar(400) DEFAULT NULL COMMENT '真实姓名',
  `Account_Mobile` varchar(11) DEFAULT NULL,
  `Record_Money` float(10,2) DEFAULT NULL,
  `Sha_Qty` int(10) DEFAULT '1' COMMENT '当前股东人数',
  `Record_Type` tinyint(1) DEFAULT '1' COMMENT '代理类型 1 普通代理 2省代理 3城市代理',
  `Record_CreateTime` int(10) DEFAULT NULL,
  `Order_ID` int(11) unsigned DEFAULT '0' COMMENT '订单编号',
  `Order_CreateTime` int(11) unsigned DEFAULT '0' COMMENT '订单创建时间',
  `Products_Name` varchar(50) DEFAULT NULL COMMENT '商品名称',
  `Products_Qty` int(10) unsigned DEFAULT '0' COMMENT '商品购买数量',
  `Products_PriceX` decimal(10,2) DEFAULT NULL COMMENT '商品价格',
  `sha_Reward` int(10) unsigned DEFAULT '0' COMMENT '区域代理提成比例',
  `Sha_Accountid` text COMMENT '股东分销id组合',
  `sha_level_name` varchar(50) DEFAULT NULL COMMENT '股东级别名称',
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_sha_rec
-- ----------------------------
INSERT INTO `distribute_sha_rec` VALUES ('1', 'pl2hu3uczz', '2', '暂无姓名', '', '1.50', '2', '1', '1465372378', '9', '1464938513', '三星手机', '1', '100.00', '20', ',2,10,', null);
INSERT INTO `distribute_sha_rec` VALUES ('2', 'pl2hu3uczz', '2', '暂无姓名', '', '0.50', '1', '1', '1465372378', '9', '1464938513', '三星手机', '1', '100.00', '20', ',9,', null);
INSERT INTO `distribute_sha_rec` VALUES ('3', 'pl2hu3uczz', '2', '暂无姓名', '', '1.50', '2', '1', '1465694594', '6', '1464938372', '三星手机', '1', '100.00', '20', ',2,10,', null);
INSERT INTO `distribute_sha_rec` VALUES ('4', 'pl2hu3uczz', '2', '暂无姓名', '', '1.00', '2', '1', '1465694595', '6', '1464938372', '三星手机', '1', '100.00', '20', ',6,9,', null);
INSERT INTO `distribute_sha_rec` VALUES ('5', 'pl2hu3uczz', '2', '暂无姓名', '', '1.50', '2', '1', '1465694595', '7', '1464938424', '三星手机', '1', '100.00', '20', ',2,10,', null);
INSERT INTO `distribute_sha_rec` VALUES ('6', 'pl2hu3uczz', '2', '暂无姓名', '', '1.00', '2', '1', '1465694595', '7', '1464938424', '三星手机', '1', '100.00', '20', ',6,9,', null);
INSERT INTO `distribute_sha_rec` VALUES ('7', 'pl2hu3uczz', '2', '暂无姓名', '', '1.50', '2', '1', '1465694595', '8', '1464938471', '三星手机', '1', '100.00', '20', ',2,10,', null);
INSERT INTO `distribute_sha_rec` VALUES ('8', 'pl2hu3uczz', '2', '暂无姓名', '', '1.00', '2', '1', '1465694595', '8', '1464938471', '三星手机', '1', '100.00', '20', ',6,9,', null);
INSERT INTO `distribute_sha_rec` VALUES ('9', 'pl2hu3uczz', '3', '暂无姓名', '', '1.50', '2', '1', '1465694596', '13', '1465177071', '三星手机', '1', '100.00', '20', ',2,10,', null);
INSERT INTO `distribute_sha_rec` VALUES ('10', 'pl2hu3uczz', '3', '暂无姓名', '', '1.00', '2', '1', '1465694596', '13', '1465177071', '三星手机', '1', '100.00', '20', ',6,9,', null);
INSERT INTO `distribute_sha_rec` VALUES ('11', 'pl2hu3uczz', '11', '', '', '1.50', '2', '1', '1465695091', '16', '1465694977', '三星手机', '1', '100.00', '20', ',2,10,', null);
INSERT INTO `distribute_sha_rec` VALUES ('12', 'pl2hu3uczz', '11', '', '', '1.00', '2', '1', '1465695091', '16', '1465694977', '三星手机', '1', '100.00', '20', ',6,9,', null);
INSERT INTO `distribute_sha_rec` VALUES ('13', 'pl2hu3uczz', '14', '', '', '2.88', '2', '1', '1465866695', '43', '1465812005', '测试业务产品', '1', '100.00', '20', ',2,10,', null);
INSERT INTO `distribute_sha_rec` VALUES ('14', 'pl2hu3uczz', '14', '', '', '1.92', '2', '1', '1465866696', '43', '1465812005', '测试业务产品', '1', '100.00', '20', ',6,9,', null);
INSERT INTO `distribute_sha_rec` VALUES ('15', 'pl2hu3uczz', '14', '', '', '2.88', '2', '1', '1465867497', '45', '1465867057', '测试业务产品', '1', '100.00', '20', ',2,10,', null);
INSERT INTO `distribute_sha_rec` VALUES ('16', 'pl2hu3uczz', '14', '', '', '1.92', '2', '1', '1465867497', '45', '1465867057', '测试业务产品', '1', '100.00', '20', ',6,9,', null);
INSERT INTO `distribute_sha_rec` VALUES ('17', 'pl2hu3uczz', '14', '', '', '2.88', '2', '1', '1465867820', '46', '1465867765', '测试业务产品', '1', '100.00', '20', ',2,10,', null);
INSERT INTO `distribute_sha_rec` VALUES ('18', 'pl2hu3uczz', '14', '', '', '1.92', '2', '1', '1465867820', '46', '1465867765', '测试业务产品', '1', '100.00', '20', ',6,9,', null);
INSERT INTO `distribute_sha_rec` VALUES ('19', 'pl2hu3uczz', '14', '', '', '2.88', '2', '1', '1465868302', '48', '1465868264', '测试业务产品', '1', '100.00', '20', ',2,10,', null);
INSERT INTO `distribute_sha_rec` VALUES ('20', 'pl2hu3uczz', '14', '', '', '1.92', '2', '1', '1465868302', '48', '1465868264', '测试业务产品', '1', '100.00', '20', ',6,9,', null);
INSERT INTO `distribute_sha_rec` VALUES ('21', 'pl2hu3uczz', '15', '不是创始人', '', '2.88', '2', '1', '1465870395', '49', '1465870064', '测试业务产品', '1', '100.00', '20', ',2,10,', null);
INSERT INTO `distribute_sha_rec` VALUES ('22', 'pl2hu3uczz', '15', '不是创始人', '', '1.92', '2', '1', '1465870395', '49', '1465870064', '测试业务产品', '1', '100.00', '20', ',6,9,', null);
INSERT INTO `distribute_sha_rec` VALUES ('23', 'pl2hu3uczz', '15', '不是创始人', '', '2.88', '2', '1', '1465897613', '52', '1465874259', '测试业务产品', '1', '100.00', '20', ',2,10,', '金牌');
INSERT INTO `distribute_sha_rec` VALUES ('24', 'pl2hu3uczz', '15', '不是创始人', '', '1.92', '2', '1', '1465897613', '52', '1465874259', '测试业务产品', '1', '100.00', '20', ',6,9,', '银牌');
INSERT INTO `distribute_sha_rec` VALUES ('25', 'pl2hu3uczz', '16', '', '', '2.88', '2', '1', '1465957677', '54', '1465957586', '测试业务产品', '1', '100.00', '20', ',2,10,', '金牌');
INSERT INTO `distribute_sha_rec` VALUES ('26', 'pl2hu3uczz', '16', '', '', '1.92', '2', '1', '1465957677', '54', '1465957586', '测试业务产品', '1', '100.00', '20', ',6,9,', '银牌');
INSERT INTO `distribute_sha_rec` VALUES ('27', 'pl2hu3uczz', '2', '暂无姓名', '', '2.88', '2', '1', '1466043148', '72', '1466042623', '测试业务产品', '2', '100.00', '20', ',2,10,', '金牌');
INSERT INTO `distribute_sha_rec` VALUES ('28', 'pl2hu3uczz', '2', '暂无姓名', '', '1.92', '3', '1', '1466043148', '72', '1466042623', '测试业务产品', '2', '100.00', '20', ',6,9,11,', '银牌');
INSERT INTO `distribute_sha_rec` VALUES ('29', 'pl2hu3uczz', '2', '暂无姓名', '', '2.88', '2', '1', '1466043326', '75', '1466043252', '测试业务产品', '2', '100.00', '20', ',2,10,', '金牌');
INSERT INTO `distribute_sha_rec` VALUES ('30', 'pl2hu3uczz', '2', '暂无姓名', '', '1.92', '3', '1', '1466043326', '75', '1466043252', '测试业务产品', '2', '100.00', '20', ',6,9,11,', '银牌');
INSERT INTO `distribute_sha_rec` VALUES ('31', 'pl2hu3uczz', '2', '暂无姓名', '', '2.88', '2', '1', '1466043482', '76', '1466043402', '测试业务产品', '2', '100.00', '20', ',2,10,', '金牌');
INSERT INTO `distribute_sha_rec` VALUES ('32', 'pl2hu3uczz', '2', '暂无姓名', '', '1.92', '3', '1', '1466043483', '76', '1466043402', '测试业务产品', '2', '100.00', '20', ',6,9,11,', '银牌');
INSERT INTO `distribute_sha_rec` VALUES ('33', 'pl2hu3uczz', '2', '暂无姓名', '', '5.76', '2', '1', '1466043698', '77', '1466043631', '测试业务产品', '2', '100.00', '20', ',2,10,', '金牌');
INSERT INTO `distribute_sha_rec` VALUES ('34', 'pl2hu3uczz', '2', '暂无姓名', '', '3.84', '3', '1', '1466043698', '77', '1466043631', '测试业务产品', '2', '100.00', '20', ',6,9,11,', '银牌');
INSERT INTO `distribute_sha_rec` VALUES ('35', 'pl2hu3uczz', '2', '暂无姓名', '', '2.88', '2', '1', '1466044800', '81', '1466044694', '测试业务产品', '1', '100.00', '20', ',2,10,', '金牌');
INSERT INTO `distribute_sha_rec` VALUES ('36', 'pl2hu3uczz', '2', '暂无姓名', '', '1.92', '3', '1', '1466044800', '81', '1466044694', '测试业务产品', '1', '100.00', '20', ',6,9,11,', '银牌');
INSERT INTO `distribute_sha_rec` VALUES ('37', 'pl2hu3uczz', '2', '暂无姓名', '', '2.88', '2', '1', '1466059018', '82', '1466058976', '测试业务产品', '1', '100.00', '20', ',2,10,', '金牌');
INSERT INTO `distribute_sha_rec` VALUES ('38', 'pl2hu3uczz', '2', '暂无姓名', '', '1.92', '3', '1', '1466059018', '82', '1466058976', '测试业务产品', '1', '100.00', '20', ',6,9,11,', '银牌');
INSERT INTO `distribute_sha_rec` VALUES ('39', 'pl2hu3uczz', '12', '三级业务', '', '2.88', '2', '1', '1466133959', '83', '1466133854', '测试业务产品', '1', '100.00', '20', ',2,10,', '金牌');
INSERT INTO `distribute_sha_rec` VALUES ('40', 'pl2hu3uczz', '12', '三级业务', '', '1.92', '3', '1', '1466133959', '83', '1466133854', '测试业务产品', '1', '100.00', '20', ',6,9,11,', '银牌');
INSERT INTO `distribute_sha_rec` VALUES ('41', 'pl2hu3uczz', '12', '三级业务', '', '2.88', '2', '1', '1466147009', '84', '1466134315', '测试业务产品', '1', '100.00', '20', ',2,10,', '金牌');
INSERT INTO `distribute_sha_rec` VALUES ('42', 'pl2hu3uczz', '12', '三级业务', '', '1.92', '3', '1', '1466147009', '84', '1466134315', '测试业务产品', '1', '100.00', '20', ',6,9,11,', '银牌');
INSERT INTO `distribute_sha_rec` VALUES ('43', 'pl2hu3uczz', '12', '三级业务', '', '2.88', '2', '1', '1466149392', '85', '1466149319', '测试业务产品', '1', '100.00', '20', ',2,10,', '金牌');
INSERT INTO `distribute_sha_rec` VALUES ('44', 'pl2hu3uczz', '12', '三级业务', '', '1.92', '3', '1', '1466149392', '85', '1466149319', '测试业务产品', '1', '100.00', '20', ',6,9,11,', '银牌');
INSERT INTO `distribute_sha_rec` VALUES ('45', 'pl2hu3uczz', '2', '暂无姓名', '', '2.88', '2', '1', '1466217412', '88', '1466216570', '测试业务产品', '1', '100.00', '20', ',2,10,', '金牌');
INSERT INTO `distribute_sha_rec` VALUES ('46', 'pl2hu3uczz', '2', '暂无姓名', '', '1.92', '3', '1', '1466217412', '88', '1466216570', '测试业务产品', '1', '100.00', '20', ',6,9,11,', '银牌');
INSERT INTO `distribute_sha_rec` VALUES ('47', 'pl2hu3uczz', '2', '暂无姓名', '', '2.88', '2', '1', '1466217494', '89', '1466217471', '测试业务产品', '1', '100.00', '20', ',2,10,', '金牌');
INSERT INTO `distribute_sha_rec` VALUES ('48', 'pl2hu3uczz', '2', '暂无姓名', '', '1.92', '3', '1', '1466217494', '89', '1466217471', '测试业务产品', '1', '100.00', '20', ',6,9,11,', '银牌');
INSERT INTO `distribute_sha_rec` VALUES ('49', 'pl2hu3uczz', '2', '暂无姓名', '', '2.88', '2', '1', '1466218164', '90', '1466218104', '测试业务产品', '1', '100.00', '20', ',2,10,', '金牌');
INSERT INTO `distribute_sha_rec` VALUES ('50', 'pl2hu3uczz', '2', '暂无姓名', '', '1.92', '3', '1', '1466218164', '90', '1466218104', '测试业务产品', '1', '100.00', '20', ',6,9,11,', '银牌');

-- ----------------------------
-- Table structure for distribute_withdraw_method
-- ----------------------------
DROP TABLE IF EXISTS `distribute_withdraw_method`;
CREATE TABLE `distribute_withdraw_method` (
  `Method_ID` int(6) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Method_Name` varchar(100) NOT NULL,
  `Method_Type` varchar(20) NOT NULL DEFAULT '0' COMMENT '0 银行卡,1支付宝',
  `Method_CreateTime` int(10) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 为可用 0为被禁用',
  PRIMARY KEY (`Method_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_withdraw_method
-- ----------------------------

-- ----------------------------
-- Table structure for distribute_withdraw_methods
-- ----------------------------
DROP TABLE IF EXISTS `distribute_withdraw_methods`;
CREATE TABLE `distribute_withdraw_methods` (
  `User_Method_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `User_ID` int(10) NOT NULL,
  `Method_Name` varchar(100) NOT NULL COMMENT '户名',
  `Method_Type` varchar(100) NOT NULL,
  `Account_Name` varchar(20) DEFAULT NULL COMMENT '银行卡则为户名，支付宝则为支付宝账号',
  `Account_Val` varchar(100) DEFAULT NULL COMMENT '银行卡编号',
  `Bank_Position` varchar(200) DEFAULT NULL COMMENT '开户行',
  `Method_CreateTime` int(10) DEFAULT NULL,
  `Method_Status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`User_Method_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_withdraw_methods
-- ----------------------------

-- ----------------------------
-- Table structure for distribute_withdraw_record
-- ----------------------------
DROP TABLE IF EXISTS `distribute_withdraw_record`;
CREATE TABLE `distribute_withdraw_record` (
  `Record_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL COMMENT '平台编号',
  `User_ID` int(11) unsigned NOT NULL COMMENT '用户编号',
  `Method_Name` varchar(100) NOT NULL DEFAULT '',
  `Method_Account` varchar(20) DEFAULT NULL COMMENT '银行卡则为户名，支付宝则为支付宝账号',
  `Method_No` varchar(100) DEFAULT NULL COMMENT '银行卡编号',
  `Method_Bank` varchar(200) DEFAULT NULL COMMENT '开户行',
  `Record_Total` decimal(10,2) DEFAULT '0.00' COMMENT '提现总金额',
  `Record_Fee` decimal(10,2) DEFAULT '0.00' COMMENT '提现手续费',
  `Record_Yue` decimal(10,2) DEFAULT '0.00' COMMENT '提现金额转入会员余额',
  `Record_Money` decimal(10,2) DEFAULT '0.00' COMMENT '最总发放金额',
  `Record_Status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0申请,1已执行，2驳回',
  `No_Record_Desc` varchar(100) DEFAULT NULL COMMENT '驳回的回复信息',
  `Record_CreateTime` int(11) unsigned NOT NULL COMMENT '创建时间',
  `Record_SendID` varchar(100) DEFAULT '' COMMENT '红包/转账订单的本系统-微信商户单号',
  `Record_SendTime` int(10) DEFAULT '0' COMMENT '红包/转账发送时间',
  `Record_WxID` varchar(100) DEFAULT '' COMMENT '红包订单的微信单号',
  `Record_SendType` varchar(50) DEFAULT '' COMMENT '处理类型 微信红包/转账',
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribute_withdraw_record
-- ----------------------------

-- ----------------------------
-- Table structure for fruit
-- ----------------------------
DROP TABLE IF EXISTS `fruit`;
CREATE TABLE `fruit` (
  `Users_ID` varchar(10) NOT NULL,
  `Fruit_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Fruit_Title` varchar(50) NOT NULL,
  `Fruit_StartTime` int(10) NOT NULL DEFAULT '0',
  `Fruit_EndTime` int(10) NOT NULL DEFAULT '0',
  `Fruit_OverTimesTipsToday` varchar(100) DEFAULT NULL,
  `Fruit_OverTimesTips` varchar(100) DEFAULT NULL,
  `Fruit_FirstPrize` varchar(50) DEFAULT NULL,
  `Fruit_FirstPrizeCount` int(11) DEFAULT '0',
  `Fruit_FirstPrizeProbability` decimal(11,2) DEFAULT '0.00',
  `Fruit_SecondPrize` varchar(50) DEFAULT NULL,
  `Fruit_SecondPrizeCount` int(11) DEFAULT '0',
  `Fruit_SecondPrizeProbability` decimal(11,2) DEFAULT '0.00',
  `Fruit_ThirdPrize` varchar(50) DEFAULT NULL,
  `Fruit_ThirdPrizeCount` int(11) DEFAULT '0',
  `Fruit_ThirdPrizeProbability` decimal(11,2) DEFAULT '0.00',
  `Fruit_IsShowPrizes` tinyint(1) DEFAULT '0',
  `Fruit_LotteryTimes` int(11) DEFAULT '0',
  `Fruit_EveryDayLotteryTimes` int(11) DEFAULT '0',
  `Fruit_BusinessPassWord` varchar(50) DEFAULT NULL,
  `Fruit_UsedIntegral` tinyint(1) DEFAULT '0',
  `Fruit_UsedIntegralValue` int(11) DEFAULT '0',
  `Fruit_Description` text,
  `Fruit_CreateTime` int(10) DEFAULT '0',
  `Fruit_Status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`Fruit_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fruit
-- ----------------------------

-- ----------------------------
-- Table structure for fruit_config
-- ----------------------------
DROP TABLE IF EXISTS `fruit_config`;
CREATE TABLE `fruit_config` (
  `Users_ID` varchar(10) NOT NULL,
  `FruitName` varchar(50) DEFAULT NULL,
  `SendSms` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`Users_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fruit_config
-- ----------------------------

-- ----------------------------
-- Table structure for fruit_sn
-- ----------------------------
DROP TABLE IF EXISTS `fruit_sn`;
CREATE TABLE `fruit_sn` (
  `SN_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SN_Code` int(9) DEFAULT NULL,
  `SN_Status` tinyint(1) DEFAULT '0',
  `Users_ID` varchar(10) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `User_Name` varchar(50) DEFAULT NULL,
  `User_Mobile` varchar(50) DEFAULT NULL,
  `Fruit_ID` int(11) DEFAULT NULL,
  `Fruit_PrizeID` tinyint(1) DEFAULT '0',
  `Fruit_Prize` varchar(50) DEFAULT '0.00',
  `SN_UsedTimes` int(11) DEFAULT '0',
  `SN_CreateTime` int(10) DEFAULT NULL,
  `Open_ID` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`SN_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fruit_sn
-- ----------------------------

-- ----------------------------
-- Table structure for games
-- ----------------------------
DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `Games_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT NULL,
  `Model_ID` int(10) NOT NULL DEFAULT '0',
  `Games_Name` varchar(255) DEFAULT NULL,
  `Games_KeyWords` varchar(255) DEFAULT NULL,
  `Games_Intro` text,
  `Games_IsClose` tinyint(1) DEFAULT '0',
  `Games_Pattern` tinyint(1) DEFAULT '0' COMMENT '游戏模式  0 推广模式 1 积分模式',
  `Games_ScoreRules` text COMMENT '积分规则',
  `Games_AttentionImg` varchar(255) DEFAULT NULL,
  `Games_AttentionLink` varchar(255) DEFAULT NULL COMMENT '关注链接',
  `Games_Sorts` int(10) DEFAULT '0',
  `Games_Rules` text COMMENT '游戏规则',
  `Games_Json` text COMMENT '其他设置',
  `Games_CreateTime` int(10) DEFAULT '0',
  PRIMARY KEY (`Games_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of games
-- ----------------------------

-- ----------------------------
-- Table structure for games_config
-- ----------------------------
DROP TABLE IF EXISTS `games_config`;
CREATE TABLE `games_config` (
  `Users_ID` varchar(50) DEFAULT NULL,
  `Games_Name` varchar(100) DEFAULT NULL,
  `Games_Logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of games_config
-- ----------------------------
INSERT INTO `games_config` VALUES ('pl2hu3uczz', '游戏中心', '');

-- ----------------------------
-- Table structure for games_model
-- ----------------------------
DROP TABLE IF EXISTS `games_model`;
CREATE TABLE `games_model` (
  `Model_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Model_Name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Model_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of games_model
-- ----------------------------
INSERT INTO `games_model` VALUES ('1', '我是你的小苹果');
INSERT INTO `games_model` VALUES ('2', '一个都别掉');
INSERT INTO `games_model` VALUES ('3', '2048');
INSERT INTO `games_model` VALUES ('4', '全民拼图');
INSERT INTO `games_model` VALUES ('5', '宠物碰碰对');

-- ----------------------------
-- Table structure for games_result
-- ----------------------------
DROP TABLE IF EXISTS `games_result`;
CREATE TABLE `games_result` (
  `ItemID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT NULL,
  `User_ID` int(10) DEFAULT '0',
  `Games_ID` int(10) DEFAULT '0',
  `Games_Pattern` tinyint(1) DEFAULT '0',
  `Open_ID` varchar(50) DEFAULT NULL,
  `Score` int(10) DEFAULT '0',
  `CreateTime` int(10) DEFAULT '0',
  PRIMARY KEY (`ItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of games_result
-- ----------------------------

-- ----------------------------
-- Table structure for guide
-- ----------------------------
DROP TABLE IF EXISTS `guide`;
CREATE TABLE `guide` (
  `Guide_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Guide_Title` varchar(255) DEFAULT NULL,
  `Guide_Content` text,
  `Guide_Status` tinyint(1) DEFAULT '0',
  `Guide_CreateTime` int(10) DEFAULT '0',
  `Guide_Hits` int(10) DEFAULT '0',
  PRIMARY KEY (`Guide_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='操作指南存储表';

-- ----------------------------
-- Records of guide
-- ----------------------------

-- ----------------------------
-- Table structure for hongbao_act
-- ----------------------------
DROP TABLE IF EXISTS `hongbao_act`;
CREATE TABLE `hongbao_act` (
  `actid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usersid` varchar(50) DEFAULT '',
  `userid` varchar(255) DEFAULT '',
  `prizeid` int(10) DEFAULT '0' COMMENT '分配红包',
  `money` decimal(10,2) DEFAULT '0.00',
  `friend` int(10) DEFAULT '1' COMMENT '需求好友数量',
  `expire` int(10) DEFAULT '0' COMMENT '已有好友数量',
  `addtime` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0' COMMENT '1 已拆 0 未拆',
  PRIMARY KEY (`actid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of hongbao_act
-- ----------------------------

-- ----------------------------
-- Table structure for hongbao_config
-- ----------------------------
DROP TABLE IF EXISTS `hongbao_config`;
CREATE TABLE `hongbao_config` (
  `usersid` varchar(50) DEFAULT '',
  `name` varchar(255) DEFAULT '',
  `fromtime` int(10) DEFAULT '0',
  `totime` int(10) DEFAULT NULL,
  `rules` text,
  `pertime` int(11) DEFAULT '0',
  `supply` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of hongbao_config
-- ----------------------------

-- ----------------------------
-- Table structure for hongbao_prize
-- ----------------------------
DROP TABLE IF EXISTS `hongbao_prize`;
CREATE TABLE `hongbao_prize` (
  `prizeid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `money` decimal(10,2) DEFAULT '1.00',
  `usersid` varchar(50) DEFAULT '',
  `amount` int(10) DEFAULT '1',
  `friend` int(10) DEFAULT '0',
  `expire` int(10) DEFAULT '0' COMMENT '已使用数量',
  `addtime` int(10) DEFAULT '0',
  PRIMARY KEY (`prizeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of hongbao_prize
-- ----------------------------

-- ----------------------------
-- Table structure for hongbao_record
-- ----------------------------
DROP TABLE IF EXISTS `hongbao_record`;
CREATE TABLE `hongbao_record` (
  `recordid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `actid` int(10) NOT NULL DEFAULT '0',
  `usersid` varchar(50) NOT NULL DEFAULT '',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `userid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`recordid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of hongbao_record
-- ----------------------------

-- ----------------------------
-- Table structure for http_raw_post_data
-- ----------------------------
DROP TABLE IF EXISTS `http_raw_post_data`;
CREATE TABLE `http_raw_post_data` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `HTTP_RAW_POST_DATA` text,
  `CreateTime` int(10) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of http_raw_post_data
-- ----------------------------

-- ----------------------------
-- Table structure for industry
-- ----------------------------
DROP TABLE IF EXISTS `industry`;
CREATE TABLE `industry` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `parentid` int(12) DEFAULT NULL,
  `logo` varchar(100) DEFAULT '',
  `listorder` int(10) DEFAULT '0',
  `create_time` int(12) DEFAULT NULL,
  `indexshow` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of industry
-- ----------------------------
INSERT INTO `industry` VALUES ('123', '服装/鞋/帽', '0', '/uploadfiles/1001/image/55409bc712.jpg', '0', '1429509973', '1');
INSERT INTO `industry` VALUES ('133', '成人用品/保健品', '0', '/uploadfiles/1001/image/5540c6b1b9.jpg', '0', '1430202143', '1');
INSERT INTO `industry` VALUES ('134', '化妆品/日用品', '0', '/uploadfiles/1001/image/5540c9746a.jpg', '0', '1430202199', '1');
INSERT INTO `industry` VALUES ('135', '户外运动/体育用品', '0', '/uploadfiles/1001/image/5540c70a69.jpg', '0', '1430202216', '1');
INSERT INTO `industry` VALUES ('136', '蛋糕/鲜花/礼品', '0', '/uploadfiles/1001/image/5540c91428.jpeg', '0', '1430202245', '1');
INSERT INTO `industry` VALUES ('137', '珠宝钻石/手表', '0', '/uploadfiles/1001/image/5540c93e1c.jpg', '0', '1430202297', '1');
INSERT INTO `industry` VALUES ('138', '数码/手机/电器', '0', '/uploadfiles/1001/image/5540c88c54.jpg', '0', '1430202368', '1');
INSERT INTO `industry` VALUES ('139', '古董/字画/收藏', '0', '/uploadfiles/1001/image/5540c8d562.jpg', '0', '1430202392', '1');
INSERT INTO `industry` VALUES ('140', '网络服务/建站/话费/虚拟产品', '0', '/uploadfiles/1001/image/5540cb1a24.jpg', '0', '1430202440', '1');
INSERT INTO `industry` VALUES ('141', '食品/茶叶/特产', '0', '/uploadfiles/1001/image/5540c92b49.jpg', '0', '1430202515', '1');
INSERT INTO `industry` VALUES ('142', '旅游/婚庆/装修', '0', '/uploadfiles/1001/image/5540c952b8.jpg', '0', '1430202542', '1');
INSERT INTO `industry` VALUES ('143', '男装', '123', '', '0', '1430304507', '0');
INSERT INTO `industry` VALUES ('144', '女装', '123', '', '0', '1430304517', '0');
INSERT INTO `industry` VALUES ('146', '童装', '123', '', '0', '1430304556', '0');
INSERT INTO `industry` VALUES ('147', '男鞋', '123', '', '0', '1430304566', '0');
INSERT INTO `industry` VALUES ('148', '女鞋', '123', '', '0', '1430304708', '0');
INSERT INTO `industry` VALUES ('149', '帽子围巾', '123', '', '0', '1430304771', '0');
INSERT INTO `industry` VALUES ('150', '保健品', '133', '', '0', '1430304786', '0');
INSERT INTO `industry` VALUES ('151', '成人用品', '133', '', '0', '1430304800', '0');
INSERT INTO `industry` VALUES ('152', '化妆品', '134', '', '0', '1430304825', '0');
INSERT INTO `industry` VALUES ('153', '日化用品', '134', '', '0', '1430304837', '0');
INSERT INTO `industry` VALUES ('154', '户外用品', '135', '', '0', '1430304875', '0');
INSERT INTO `industry` VALUES ('155', '体育用品', '135', '', '0', '1430304898', '0');
INSERT INTO `industry` VALUES ('156', '蛋糕', '136', '', '0', '1430304915', '0');
INSERT INTO `industry` VALUES ('157', '鲜花', '136', '', '0', '1430304929', '0');
INSERT INTO `industry` VALUES ('158', '珠宝钻石', '137', '', '0', '1430305003', '0');
INSERT INTO `industry` VALUES ('159', '手表', '137', '', '0', '1430305014', '0');
INSERT INTO `industry` VALUES ('160', '礼品', '136', '', '0', '1430305027', '0');
INSERT INTO `industry` VALUES ('161', '数码', '138', '', '0', '1430305330', '0');
INSERT INTO `industry` VALUES ('162', '手机', '138', '', '0', '1430305338', '0');
INSERT INTO `industry` VALUES ('164', '电器', '138', '', '0', '1430305375', '0');
INSERT INTO `industry` VALUES ('165', '古董', '139', '', '0', '1430305393', '0');
INSERT INTO `industry` VALUES ('166', '字画', '139', '', '0', '1430305403', '0');
INSERT INTO `industry` VALUES ('167', '收藏', '139', '', '0', '1430305417', '0');
INSERT INTO `industry` VALUES ('168', '网络服务', '140', '', '0', '1430305761', '0');
INSERT INTO `industry` VALUES ('169', '建站', '140', '', '0', '1430305769', '0');
INSERT INTO `industry` VALUES ('170', '话费', '140', '', '0', '1430305777', '0');
INSERT INTO `industry` VALUES ('171', '虚拟产品', '140', '', '0', '1430305797', '0');
INSERT INTO `industry` VALUES ('172', '食品', '141', '', '0', '1430305839', '0');
INSERT INTO `industry` VALUES ('173', '茶叶', '141', '', '0', '1430305849', '0');
INSERT INTO `industry` VALUES ('174', '特产', '141', '', '0', '1430305861', '0');
INSERT INTO `industry` VALUES ('175', '旅游', '142', '', '0', '1430305897', '0');
INSERT INTO `industry` VALUES ('176', '婚庆', '142', '', '0', '1430305908', '0');
INSERT INTO `industry` VALUES ('177', '装修', '142', '', '0', '1430305918', '0');
INSERT INTO `industry` VALUES ('178', '其他', '0', '', '0', '1430457697', '0');
INSERT INTO `industry` VALUES ('179', '其他', '178', '', '0', '1430457707', '0');

-- ----------------------------
-- Table structure for kanjia
-- ----------------------------
DROP TABLE IF EXISTS `kanjia`;
CREATE TABLE `kanjia` (
  `Kanjia_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Kanjia_Name` varchar(100) NOT NULL DEFAULT '',
  `Users_ID` varchar(50) NOT NULL DEFAULT '',
  `Product_Name` varchar(100) NOT NULL DEFAULT '',
  `Product_ID` varchar(10) NOT NULL DEFAULT '',
  `Member_Count` int(10) DEFAULT NULL,
  `Beginnum` int(5) NOT NULL,
  `Endnum` int(5) NOT NULL,
  `Bottom_Price` float(10,2) DEFAULT NULL,
  `Fromtime` int(11) DEFAULT '0',
  `Totime` int(11) DEFAULT '0',
  `Kanjia_Createtime` varchar(10) DEFAULT NULL,
  `is_recommend` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Kanjia_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kanjia
-- ----------------------------

-- ----------------------------
-- Table structure for kanjia_helper_record
-- ----------------------------
DROP TABLE IF EXISTS `kanjia_helper_record`;
CREATE TABLE `kanjia_helper_record` (
  `Record_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Helper_ID` int(10) DEFAULT NULL,
  `User_ID` int(10) NOT NULL DEFAULT '0',
  `Kanjia_ID` varchar(50) NOT NULL DEFAULT '',
  `Record_Reduce` int(10) NOT NULL DEFAULT '0',
  `Record_Time` char(10) NOT NULL DEFAULT '0',
  `Open_ID` varchar(255) NOT NULL DEFAULT '',
  `Open_Info` text,
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kanjia_helper_record
-- ----------------------------

-- ----------------------------
-- Table structure for kanjia_member
-- ----------------------------
DROP TABLE IF EXISTS `kanjia_member`;
CREATE TABLE `kanjia_member` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) DEFAULT NULL,
  `User_ID` int(10) DEFAULT NULL,
  `Kanjia_ID` int(10) DEFAULT NULL,
  `Self_Kan` int(3) DEFAULT '0',
  `Cur_Price` float(10,2) DEFAULT NULL,
  `Helper_Count` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kanjia_member
-- ----------------------------

-- ----------------------------
-- Table structure for kf_account
-- ----------------------------
DROP TABLE IF EXISTS `kf_account`;
CREATE TABLE `kf_account` (
  `Account_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT NULL,
  `Account_Name` varchar(50) DEFAULT NULL,
  `Account_PassWord` varchar(50) DEFAULT NULL,
  `Account_Online` tinyint(1) DEFAULT '0' COMMENT '在线状态 0 离线 1 在线',
  `Account_Chat` int(10) DEFAULT '0' COMMENT '在线聊天人数',
  `Account_CreateTime` int(10) DEFAULT '0',
  PRIMARY KEY (`Account_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='客服账号存储表';

-- ----------------------------
-- Records of kf_account
-- ----------------------------

-- ----------------------------
-- Table structure for kf_config
-- ----------------------------
DROP TABLE IF EXISTS `kf_config`;
CREATE TABLE `kf_config` (
  `KF_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT NULL,
  `KF_IsWeb` tinyint(1) DEFAULT '1' COMMENT '官网是否启用  0 否  1 是',
  `KF_IsShop` tinyint(1) DEFAULT '1' COMMENT '商城是否启用 0 否  1 是',
  `KF_IsUser` tinyint(1) DEFAULT '1' COMMENT '会员中心是否启用 0 否  1 是',
  `KF_Icon` varchar(50) DEFAULT NULL,
  `KF_Code` text,
  PRIMARY KEY (`KF_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='客服设置存储表';

-- ----------------------------
-- Records of kf_config
-- ----------------------------

-- ----------------------------
-- Table structure for kf_language
-- ----------------------------
DROP TABLE IF EXISTS `kf_language`;
CREATE TABLE `kf_language` (
  `Lan_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT NULL,
  `KF_Account` varchar(50) DEFAULT NULL,
  `Lan_Content` text,
  `Lan_CreateTime` int(10) DEFAULT '0',
  PRIMARY KEY (`Lan_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='客服常用语存储表';

-- ----------------------------
-- Records of kf_language
-- ----------------------------

-- ----------------------------
-- Table structure for kf_message
-- ----------------------------
DROP TABLE IF EXISTS `kf_message`;
CREATE TABLE `kf_message` (
  `Message_ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Open_ID` varchar(255) DEFAULT NULL,
  `Users_ID` varchar(50) DEFAULT NULL,
  `KF_Account` varchar(50) DEFAULT NULL,
  `Message_CreateTime` int(10) DEFAULT '0',
  `Message_LastTime` int(10) DEFAULT '0',
  PRIMARY KEY (`Message_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='在线客服聊天记录';

-- ----------------------------
-- Records of kf_message
-- ----------------------------

-- ----------------------------
-- Table structure for message_model
-- ----------------------------
DROP TABLE IF EXISTS `message_model`;
CREATE TABLE `message_model` (
  `Model_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Model_Name` varchar(50) DEFAULT NULL,
  `Model_Table` varchar(20) DEFAULT NULL,
  `Model_Type` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Model_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of message_model
-- ----------------------------
INSERT INTO `message_model` VALUES ('1', '会员注册成功', 'user', 'user_create');
INSERT INTO `message_model` VALUES ('3', '微商圈下订单', 'user_order', 'weicbd_order');
INSERT INTO `message_model` VALUES ('4', '微团购下订单', 'user_order', 'tuan_order');
INSERT INTO `message_model` VALUES ('7', '发货通知消息!', 'user_order', 'deliver_order');
INSERT INTO `message_model` VALUES ('8', '微商城下订单!', 'user_order', 'shop_order');
INSERT INTO `message_model` VALUES ('10', '积分变更通知', 'user_order', 'user_integral');

-- ----------------------------
-- Table structure for message_template
-- ----------------------------
DROP TABLE IF EXISTS `message_template`;
CREATE TABLE `message_template` (
  `Template_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT NULL,
  `Model_ID` int(10) DEFAULT NULL,
  `Template_LinkID` varchar(255) DEFAULT '',
  `Template_Json` text,
  `Template_CreateTime` int(10) DEFAULT '0',
  PRIMARY KEY (`Template_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of message_template
-- ----------------------------

-- ----------------------------
-- Table structure for module
-- ----------------------------
DROP TABLE IF EXISTS `module`;
CREATE TABLE `module` (
  `moduleid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '模块ID',
  `module` char(20) DEFAULT '' COMMENT '模块标识',
  `name` char(100) DEFAULT '' COMMENT '模块名称',
  `parentid` int(10) DEFAULT '0',
  `type` char(20) DEFAULT '',
  `listorder` int(10) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`moduleid`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of module
-- ----------------------------
INSERT INTO `module` VALUES ('1', 'shop', '微商城', '0', 'module', '0');
INSERT INTO `module` VALUES ('2', 'biz', '商家', '0', 'module', '0');
INSERT INTO `module` VALUES ('3', 'distribute', '分销中心', '0', 'module', '0');
INSERT INTO `module` VALUES ('4', 'article', '文章', '0', 'module', '0');
INSERT INTO `module` VALUES ('5', 'web', '微官网', '0', 'module', '0');
INSERT INTO `module` VALUES ('6', 'votes', '微投票', '0', 'module', '0');
INSERT INTO `module` VALUES ('7', 'user', '会员中心', '0', 'module', '0');
INSERT INTO `module` VALUES ('8', 'stores', '门店管理', '0', 'module', '0');
INSERT INTO `module` VALUES ('9', 'weicuxiao', '微促销', '0', 'module', '0');
INSERT INTO `module` VALUES ('10', 'category', '产品分类', '1', 'url', '0');
INSERT INTO `module` VALUES ('11', 'lists', '产品列表', '1', 'url', '0');
INSERT INTO `module` VALUES ('12', 'category', '文章分类', '4', 'url', '0');
INSERT INTO `module` VALUES ('13', 'lists', '文章列表', '4', 'url', '0');
INSERT INTO `module` VALUES ('14', 'category', '栏目分类', '5', 'url', '0');
INSERT INTO `module` VALUES ('15', 'lists', '内容列表', '5', 'url', '0');
INSERT INTO `module` VALUES ('16', 'pc', 'PC商城', '0', 'module', '0');
INSERT INTO `module` VALUES ('17', 'category', '栏目分类', '16', 'url', '0');
INSERT INTO `module` VALUES ('18', 'lists', '内容列表', '16', 'url', '0');

-- ----------------------------
-- Table structure for mycount
-- ----------------------------
DROP TABLE IF EXISTS `mycount`;
CREATE TABLE `mycount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usersid` varchar(50) NOT NULL,
  `appsecret` varchar(255) NOT NULL DEFAULT '',
  `appkey` varchar(255) NOT NULL DEFAULT '',
  `qianmizhanghao` varchar(255) NOT NULL DEFAULT '',
  `qianmimima` varchar(255) NOT NULL DEFAULT '',
  `token` varchar(255) NOT NULL DEFAULT '',
  `refreshToken` varchar(255) NOT NULL DEFAULT '',
  `expiretime` int(10) DEFAULT '0' COMMENT '刷新token',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mycount
-- ----------------------------

-- ----------------------------
-- Table structure for newtable
-- ----------------------------
DROP TABLE IF EXISTS `newtable`;
CREATE TABLE `newtable` (
  `Order_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL DEFAULT '',
  `User_ID` varchar(10) NOT NULL DEFAULT '',
  `Applyfor_Name` varchar(50) DEFAULT '' COMMENT '联系人',
  `Applyfor_Mobile` varchar(20) DEFAULT '' COMMENT '手机号',
  `Applyfor_WeixinID` varchar(100) DEFAULT '' COMMENT '购买人微信号',
  `Order_PaymentMethod` varchar(10) DEFAULT '' COMMENT '支付方式',
  `Order_PaymentInfo` varchar(255) DEFAULT '' COMMENT '支付信息  线下支付有用',
  `Order_TotalPrice` decimal(11,2) DEFAULT '0.00' COMMENT '订单金额',
  `Owner_ID` int(10) DEFAULT '0' COMMENT '上级ID',
  `Order_PayTime` int(10) DEFAULT '0' COMMENT '订单支付时间',
  `Order_PayID` varchar(100) DEFAULT '' COMMENT '订单支付号',
  `ProvinceId` int(10) unsigned DEFAULT '0' COMMENT '省份编号',
  `CityId` int(10) unsigned DEFAULT '0' COMMENT '城市编号',
  `AreaId` int(10) unsigned DEFAULT '0' COMMENT '区域编号',
  `Level_ID` int(10) DEFAULT '0' COMMENT '分销级别ID',
  `Level_Name` varchar(100) DEFAULT '' COMMENT '分销级别名称',
  `Order_Status` tinyint(1) DEFAULT '0' COMMENT '订单状态   0 待审核 1待付款 2已付款(已完成) 3取消申请',
  `Order_CreateTime` int(10) DEFAULT '0' COMMENT '下单时间',
  `Area` tinyint(1) unsigned DEFAULT NULL COMMENT '申请区域1，省级2市级3县级',
  `AreaMark` varchar(20) DEFAULT NULL COMMENT '地区描述',
  `Area_Concat` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`Order_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of newtable
-- ----------------------------

-- ----------------------------
-- Table structure for node
-- ----------------------------
DROP TABLE IF EXISTS `node`;
CREATE TABLE `node` (
  `Node_ID` varchar(10) NOT NULL DEFAULT '',
  `Node_Name` varchar(50) DEFAULT NULL,
  `Node_ParentID` varchar(10) DEFAULT NULL,
  `Node_Path` varchar(50) DEFAULT NULL,
  `Node_Portal` varchar(50) DEFAULT NULL,
  `Node_Status` tinyint(1) DEFAULT '1',
  `Node_Index` int(2) DEFAULT NULL,
  `Node_Type` tinyint(1) DEFAULT '3',
  `Node_Display` tinyint(1) DEFAULT '0',
  `Node_Icons` varchar(50) DEFAULT NULL,
  `Node_Notes` text,
  PRIMARY KEY (`Node_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of node
-- ----------------------------

-- ----------------------------
-- Table structure for pc_focus
-- ----------------------------
DROP TABLE IF EXISTS `pc_focus`;
CREATE TABLE `pc_focus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(40) DEFAULT NULL,
  `pic` char(100) DEFAULT NULL,
  `sort` int(10) DEFAULT '0',
  `is_show` int(10) DEFAULT '1',
  `add_time` int(10) DEFAULT NULL,
  `link` char(200) DEFAULT NULL,
  `Users_ID` char(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pc_focus
-- ----------------------------

-- ----------------------------
-- Table structure for pc_index
-- ----------------------------
DROP TABLE IF EXISTS `pc_index`;
CREATE TABLE `pc_index` (
  `web_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '模块ID',
  `Users_ID` char(10) DEFAULT NULL,
  `web_name` char(20) DEFAULT '' COMMENT '模块名称',
  `style_name` char(20) DEFAULT NULL COMMENT '风格名称',
  `web_page` char(10) DEFAULT 'index' COMMENT '所在页面(暂时只有index)',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `web_sort` tinyint(1) unsigned DEFAULT '9' COMMENT '排序',
  `web_show` tinyint(1) unsigned DEFAULT '1' COMMENT '是否显示，0为否，1为是，默认为1',
  `web_html` text COMMENT '模块html代码',
  PRIMARY KEY (`web_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='页面模块表';

-- ----------------------------
-- Records of pc_index
-- ----------------------------

-- ----------------------------
-- Table structure for pc_setting
-- ----------------------------
DROP TABLE IF EXISTS `pc_setting`;
CREATE TABLE `pc_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module` char(15) DEFAULT NULL,
  `Users_ID` char(10) DEFAULT NULL,
  `logo` char(200) DEFAULT NULL,
  `site_status` tinyint(1) DEFAULT '1' COMMENT 'pc网站状态',
  `closed_reason` char(200) DEFAULT NULL,
  `login_bg` char(150) DEFAULT NULL,
  `reg_bg` char(150) DEFAULT NULL,
  `site_url` char(200) DEFAULT NULL,
  `web_share_bg` char(150) DEFAULT NULL COMMENT '网站分享页面自定义背景',
  `diy_share_bg` char(150) DEFAULT NULL COMMENT '分享自定义图片',
  `bdText` char(200) DEFAULT NULL,
  `bdDesc` char(200) DEFAULT NULL,
  `share_rules` char(250) DEFAULT NULL,
  `web_share_bg_color` char(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pc_setting
-- ----------------------------
INSERT INTO `pc_setting` VALUES ('1', 'shop', 'pl2hu3uczz', '/uploadfiles/pl2hu3uczz/image/574e7a8af9.png', '1', null, '/uploadfiles/pl2hu3uczz/image/574e7a5875.jpg', '/uploadfiles/pl2hu3uczz/image/574e79d21a.png', 'pc.ceshi.cc', null, null, null, null, null, null);

-- ----------------------------
-- Table structure for pc_user_message
-- ----------------------------
DROP TABLE IF EXISTS `pc_user_message`;
CREATE TABLE `pc_user_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` char(10) NOT NULL,
  `User_ID` int(10) NOT NULL,
  `content` text NOT NULL,
  `CreateTime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pc_user_message
-- ----------------------------
INSERT INTO `pc_user_message` VALUES ('1', 'pl2hu3uczz', '3', '购买商品送 11 个积分', '1464864035');
INSERT INTO `pc_user_message` VALUES ('2', 'pl2hu3uczz', '14', '购买商品送 11 个积分', '1465957323');
INSERT INTO `pc_user_message` VALUES ('3', 'pl2hu3uczz', '10', '您下单成功，支付了110.00元，您将获取佣金2.88元', '1465966538');
INSERT INTO `pc_user_message` VALUES ('4', 'pl2hu3uczz', '10', '购买商品送 11 个积分', '1465966561');
INSERT INTO `pc_user_message` VALUES ('5', 'pl2hu3uczz', '12', '您下单成功，支付了110.00元，您将获取佣金2.88元', '1465966764');
INSERT INTO `pc_user_message` VALUES ('6', 'pl2hu3uczz', '11', '您推荐的一级会员购买者一级下单成功，支付了110.00元，您将获取佣金9.60元', '1465966764');
INSERT INTO `pc_user_message` VALUES ('7', 'pl2hu3uczz', '10', '您推荐的二级会员购买者一级下单成功，支付了110.00元，您将获取佣金3.84元', '1465966764');
INSERT INTO `pc_user_message` VALUES ('8', 'pl2hu3uczz', '12', '购买商品送 11 个积分', '1465966799');

-- ----------------------------
-- Table structure for pifa_category
-- ----------------------------
DROP TABLE IF EXISTS `pifa_category`;
CREATE TABLE `pifa_category` (
  `Category_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` char(10) NOT NULL,
  `Category_Index` int(11) NOT NULL COMMENT '排序',
  `Category_Name` char(50) NOT NULL,
  `Category_ParentID` int(11) NOT NULL DEFAULT '0',
  `Category_Img` char(50) NOT NULL,
  `Category_IndexShow` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`Category_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pifa_category
-- ----------------------------

-- ----------------------------
-- Table structure for pifa_config
-- ----------------------------
DROP TABLE IF EXISTS `pifa_config`;
CREATE TABLE `pifa_config` (
  `Users_ID` char(10) NOT NULL,
  `PifaName` char(50) DEFAULT NULL COMMENT '批发商城名称',
  `p_NeedShipping` tinyint(1) DEFAULT '0' COMMENT '需要物流',
  `p_SendSms` tinyint(1) DEFAULT '0' COMMENT '订单手机短信通知',
  `p_MobilePhone` char(20) DEFAULT NULL COMMENT '短信提醒手机号',
  `p_Commit_Check` tinyint(1) DEFAULT '0' COMMENT '评论是否开启审核  0关闭  1开启',
  PRIMARY KEY (`Users_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pifa_config
-- ----------------------------

-- ----------------------------
-- Table structure for pifa_products
-- ----------------------------
DROP TABLE IF EXISTS `pifa_products`;
CREATE TABLE `pifa_products` (
  `Products_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` char(10) NOT NULL,
  `Products_Name` char(50) NOT NULL,
  `Products_Category` int(11) NOT NULL,
  `Products_price_rule` text NOT NULL COMMENT '价格区间',
  `Products_Profit` int(10) NOT NULL DEFAULT '0' COMMENT '产品利润',
  `Products_Distributes` char(50) DEFAULT NULL COMMENT '佣金返利json信息',
  `Products_JSON` text COMMENT '产品图片信息',
  `Products_BriefDescription` text COMMENT '批发须知',
  `Products_SoldOut` tinyint(4) DEFAULT '0' COMMENT '下架1',
  `Products_IsNew` tinyint(4) DEFAULT '0' COMMENT '新品',
  `Products_IsRecommend` tinyint(4) DEFAULT '0' COMMENT '推荐',
  `Products_IsHot` tinyint(4) DEFAULT '0' COMMENT '热卖',
  `Products_IsShippingFree` tinyint(1) DEFAULT '0' COMMENT '运费计算方式',
  `Products_Description` text COMMENT '产品详情',
  `Products_CreateTime` int(10) DEFAULT NULL,
  `Products_Count` int(10) DEFAULT '0' COMMENT '商品库存',
  `Products_Weight` int(10) DEFAULT '0' COMMENT '单件商品重量',
  `Products_Sales` int(10) DEFAULT '0' COMMENT '销量',
  `Products_Qrcode` char(150) DEFAULT NULL COMMENT '二维码',
  `Products_Shipping` smallint(6) DEFAULT NULL,
  `Shipping_Free_Company` smallint(6) DEFAULT '0' COMMENT '免运费时所指定的快递公司，0为所有快递公司均可',
  `Products_unit` char(10) DEFAULT NULL COMMENT '产品单位',
  `deleted_at` char(30) DEFAULT NULL,
  `Products_Index` char(20) DEFAULT '1' COMMENT '排序',
  `commission_ratio` int(5) DEFAULT NULL,
  PRIMARY KEY (`Products_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pifa_products
-- ----------------------------

-- ----------------------------
-- Table structure for scratch
-- ----------------------------
DROP TABLE IF EXISTS `scratch`;
CREATE TABLE `scratch` (
  `Users_ID` varchar(10) NOT NULL,
  `Scratch_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Scratch_Title` varchar(50) NOT NULL,
  `Scratch_StartTime` int(10) NOT NULL DEFAULT '0',
  `Scratch_EndTime` int(10) NOT NULL DEFAULT '0',
  `Scratch_OverTimesTipsToday` varchar(100) DEFAULT NULL,
  `Scratch_OverTimesTips` varchar(100) DEFAULT NULL,
  `Scratch_FirstPrize` varchar(50) DEFAULT NULL,
  `Scratch_FirstPrizeCount` int(11) DEFAULT '0',
  `Scratch_FirstPrizeProbability` decimal(11,2) DEFAULT '0.00',
  `Scratch_SecondPrize` varchar(50) DEFAULT NULL,
  `Scratch_SecondPrizeCount` int(11) DEFAULT '0',
  `Scratch_SecondPrizeProbability` decimal(11,2) DEFAULT '0.00',
  `Scratch_ThirdPrize` varchar(50) DEFAULT NULL,
  `Scratch_ThirdPrizeCount` int(11) DEFAULT '0',
  `Scratch_ThirdPrizeProbability` decimal(11,2) DEFAULT '0.00',
  `Scratch_IsShowPrizes` tinyint(1) DEFAULT '0',
  `Scratch_LotteryTimes` int(11) DEFAULT '0',
  `Scratch_EveryDayLotteryTimes` int(11) DEFAULT '0',
  `Scratch_BusinessPassWord` varchar(50) DEFAULT NULL,
  `Scratch_UsedIntegral` tinyint(1) DEFAULT '0',
  `Scratch_UsedIntegralValue` int(11) DEFAULT '0',
  `Scratch_Description` text,
  `Scratch_CreateTime` int(10) DEFAULT '0',
  `Scratch_Status` tinyint(1) DEFAULT '0',
  `Scratch_More_Integral` text,
  `Scratch_If_Share` tinyint(1) DEFAULT '0',
  `Scratch_Share_num` int(10) DEFAULT NULL,
  PRIMARY KEY (`Scratch_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of scratch
-- ----------------------------

-- ----------------------------
-- Table structure for scratch_config
-- ----------------------------
DROP TABLE IF EXISTS `scratch_config`;
CREATE TABLE `scratch_config` (
  `Users_ID` varchar(10) NOT NULL,
  `ScratchName` varchar(50) DEFAULT NULL,
  `SendSms` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`Users_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of scratch_config
-- ----------------------------
INSERT INTO `scratch_config` VALUES ('pl2hu3uczz', '刮刮卡', '0');

-- ----------------------------
-- Table structure for scratch_sn
-- ----------------------------
DROP TABLE IF EXISTS `scratch_sn`;
CREATE TABLE `scratch_sn` (
  `SN_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SN_Code` int(9) DEFAULT NULL,
  `SN_Status` tinyint(1) DEFAULT '0',
  `Users_ID` varchar(10) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `User_Name` varchar(50) DEFAULT NULL,
  `User_Mobile` varchar(50) DEFAULT NULL,
  `Scratch_ID` int(11) DEFAULT NULL,
  `Scratch_PrizeID` tinyint(1) DEFAULT '0',
  `Scratch_Prize` varchar(50) DEFAULT '0.00',
  `SN_UsedTimes` int(11) DEFAULT '0',
  `SN_CreateTime` int(10) DEFAULT NULL,
  `Open_ID` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`SN_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of scratch_sn
-- ----------------------------

-- ----------------------------
-- Table structure for setting
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `sys_name` varchar(100) DEFAULT NULL,
  `sys_logo` varchar(100) DEFAULT NULL,
  `sys_copyright` varchar(255) DEFAULT NULL,
  `sys_baidukey` varchar(255) DEFAULT '',
  `sys_price` varchar(200) DEFAULT '0.00' COMMENT '系统价格',
  `sys_max_level` int(10) DEFAULT NULL,
  `alipay_partner` varchar(16) DEFAULT NULL,
  `alipay_key` varchar(32) DEFAULT NULL,
  `alipay_selleremail` varchar(40) DEFAULT NULL,
  `sms_enabled` tinyint(1) DEFAULT '0' COMMENT '1',
  `sms_account` varchar(255) DEFAULT '',
  `sms_pass` varchar(255) DEFAULT '',
  `sms_sign` varchar(255) DEFAULT '',
  `sms_price` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of setting
-- ----------------------------
INSERT INTO `setting` VALUES ('1', '好分销', '/uploadfiles/1001/image/554097f011.png', '', 'zaq2EHvUKKnudzEmsHwU8PVM', '{\"1\":\"6500\",\"2\":\"11000\",\"3\":\"14000\"}', null, '2088011781995263', 'jcd91vx39h182qrzjl6v5u0ei8fyhgfh', '965607844@qq.com', '0', 'wangzhongwang', '5926172', '【好分销】', '[{\"min\":\"0\",\"max\":\"2000\",\"price\":\"0.1\"},{\"min\":\"2000\",\"max\":\"20000\",\"price\":\"0.08\"},{\"min\":\"20000\",\"max\":\"50000\",\"price\":\"0.06\"}]');

-- ----------------------------
-- Table structure for share_click
-- ----------------------------
DROP TABLE IF EXISTS `share_click`;
CREATE TABLE `share_click` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Record_ID` int(10) DEFAULT '0',
  `Users_ID` varchar(50) DEFAULT '',
  `from_user` int(10) DEFAULT '0',
  `User_ID` int(10) DEFAULT '0',
  `CreateTime` int(10) DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of share_click
-- ----------------------------

-- ----------------------------
-- Table structure for share_record
-- ----------------------------
DROP TABLE IF EXISTS `share_record`;
CREATE TABLE `share_record` (
  `Record_ID` int(20) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT NULL,
  `User_ID` int(10) DEFAULT NULL,
  `Type` varchar(20) DEFAULT NULL,
  `CreateTime` int(10) DEFAULT NULL,
  `Items_ID` int(10) DEFAULT '0',
  `Share_Type` int(11) DEFAULT NULL,
  `Record_Description` varchar(50) DEFAULT NULL,
  `Action_ID` int(20) DEFAULT NULL,
  `Transfer` varchar(255) DEFAULT '',
  `Score` int(10) DEFAULT '0',
  `Category_ID` int(10) DEFAULT '0',
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of share_record
-- ----------------------------

-- ----------------------------
-- Table structure for sha_account_record
-- ----------------------------
DROP TABLE IF EXISTS `sha_account_record`;
CREATE TABLE `sha_account_record` (
  `Users_ID` varchar(10) NOT NULL,
  `Record_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Sha_Accountid` text COMMENT '股东分销id',
  `Order_ID` int(11) DEFAULT NULL COMMENT '订单ID',
  `Shasingle_Money` decimal(30,2) DEFAULT '0.00' COMMENT '单位股东分红',
  `Record_CreateTime` int(10) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sha_account_record
-- ----------------------------

-- ----------------------------
-- Table structure for sha_order
-- ----------------------------
DROP TABLE IF EXISTS `sha_order`;
CREATE TABLE `sha_order` (
  `Order_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL DEFAULT '',
  `User_ID` varchar(10) NOT NULL DEFAULT '',
  `Applyfor_Name` varchar(50) DEFAULT '' COMMENT '联系人',
  `Applyfor_Mobile` varchar(20) DEFAULT '' COMMENT '手机号',
  `Applyfor_level` int(3) DEFAULT NULL COMMENT '股东级别',
  `Applyfor_WeixinID` varchar(100) DEFAULT '' COMMENT '购买人微信号',
  `Order_PaymentMethod` varchar(10) DEFAULT '' COMMENT '支付方式',
  `Order_PaymentInfo` varchar(255) DEFAULT '' COMMENT '支付信息  线下支付有用',
  `Order_TotalPrice` decimal(11,2) DEFAULT '0.00' COMMENT '订单金额',
  `Owner_ID` int(10) DEFAULT '0' COMMENT '上级ID',
  `Order_PayTime` int(10) DEFAULT '0' COMMENT '订单支付时间',
  `Order_PayID` varchar(100) DEFAULT '' COMMENT '订单支付号',
  `Level_ID` int(10) DEFAULT '0' COMMENT '分销级别ID',
  `Level_Name` varchar(100) DEFAULT '' COMMENT '分销级别名称',
  `Order_Status` tinyint(1) DEFAULT '0' COMMENT '订单状态   0 待审核 1待付款 2已付款(已完成) 3取消申请',
  `Refuse_Be` varchar(255) DEFAULT '' COMMENT '申请拒绝原因',
  `Order_CreateTime` int(10) DEFAULT '0' COMMENT '下单时间',
  PRIMARY KEY (`Order_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of sha_order
-- ----------------------------
INSERT INTO `sha_order` VALUES ('7', 'pl2hu3uczz', '2', 'sun', '15517105580', '1', '', '', '', '10.00', '2', '0', '', '391', '普通分销商', '3', '', '1465346921');
INSERT INTO `sha_order` VALUES ('8', 'pl2hu3uczz', '2', 'sun', '15517105580', '1', '', '', '', '10.00', '2', '0', '', '391', '普通分销商', '3', 'buhege', '1465347185');
INSERT INTO `sha_order` VALUES ('9', 'pl2hu3uczz', '2', 'sun', '15517105580', '1', '', '余额支付', '', '10.00', '2', '1465347428', '', '391', '普通分销商', '2', '', '1465347344');
INSERT INTO `sha_order` VALUES ('10', 'pl2hu3uczz', '2', 'sun', '15517105580', '2', '', '余额支付', '', '20.00', '2', '1465347530', '', '391', '普通分销商', '2', '', '1465347496');
INSERT INTO `sha_order` VALUES ('12', 'pl2hu3uczz', '2', 'sun', '15517105580', '3', '', '余额支付', '', '30.00', '2', '1465374537', '', '391', '普通分销商', '2', '', '1465374508');
INSERT INTO `sha_order` VALUES ('16', 'pl2hu3uczz', '9', 'sun', '15517105580', '1', '', '', '', '0.00', '9', '0', '', '391', '普通分销商', '3', '', '1465718209');
INSERT INTO `sha_order` VALUES ('17', 'v0wo2g5sah', '39', '001', '13712345001', '1', '', '', '', '10.00', '39', '0', '', '397', '中级分销商', '0', '', '1465717825');
INSERT INTO `sha_order` VALUES ('18', 'v0wo2g5sah', '39', '001', '13712345001', '1', '', '', '', '10.00', '39', '0', '', '397', '中级分销商', '0', '', '1465717825');
INSERT INTO `sha_order` VALUES ('19', 'pl2hu3uczz', '9', '1231', '1231', '1', '', '免费申请,费用0元', '', '0.00', '9', '1466038486', '', '391', '普通分销商', '2', '', '1465983069');
INSERT INTO `sha_order` VALUES ('20', 'pl2hu3uczz', '9', '孙', '15517101234', '2', '', '余额支付', '', '20.00', '9', '1466039343', '', '391', '普通分销商', '2', '', '1466038546');

-- ----------------------------
-- Table structure for shipping_orders
-- ----------------------------
DROP TABLE IF EXISTS `shipping_orders`;
CREATE TABLE `shipping_orders` (
  `Orders_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` char(10) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Detail_ID` int(11) NOT NULL,
  `Address_Province` char(50) DEFAULT NULL,
  `Address_City` char(50) DEFAULT NULL,
  `Address_Area` char(50) DEFAULT NULL,
  `Address_Detailed` char(200) DEFAULT NULL,
  `Address_Mobile` char(11) DEFAULT NULL,
  `Address_Name` char(50) DEFAULT NULL,
  `Orders_Status` tinyint(1) DEFAULT '0' COMMENT '0 未付款  1 已付款 2 已发货 3 已领取，完成',
  `Orders_Shipping` char(50) DEFAULT NULL,
  `Orders_ShippingID` char(50) DEFAULT NULL,
  `Orders_FinishTime` int(10) DEFAULT NULL,
  `Orders_CreateTime` int(10) DEFAULT NULL,
  `Orders_IsShipping` tinyint(1) DEFAULT '0' COMMENT '是否需要物流  0 不需要物流  1 需要物流',
  `Orders_TotalPrice` decimal(10,2) DEFAULT '0.00',
  `Orders_PaymentMethod` char(50) DEFAULT '',
  `Orders_PaymentInfo` char(200) DEFAULT '',
  `Orders_Code` char(50) DEFAULT '' COMMENT '消费码',
  `Orders_SendTime` int(10) DEFAULT '0',
  `Is_Commit` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Orders_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shipping_orders
-- ----------------------------

-- ----------------------------
-- Table structure for shipping_orders_commit
-- ----------------------------
DROP TABLE IF EXISTS `shipping_orders_commit`;
CREATE TABLE `shipping_orders_commit` (
  `Item_ID` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT '',
  `Biz_ID` int(10) DEFAULT '0',
  `User_ID` int(10) DEFAULT '0',
  `MID` varchar(50) DEFAULT '',
  `Order_ID` int(10) DEFAULT '0',
  `Product_ID` int(10) DEFAULT '0',
  `Score` int(10) DEFAULT '0',
  `Note` text,
  `CreateTime` int(10) DEFAULT '0',
  `Status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`Item_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of shipping_orders_commit
-- ----------------------------

-- ----------------------------
-- Table structure for shipping_template_section
-- ----------------------------
DROP TABLE IF EXISTS `shipping_template_section`;
CREATE TABLE `shipping_template_section` (
  `Section_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '配送模板区域细则',
  `Template_ID` int(11) NOT NULL COMMENT '模板ID',
  `Shipping_Business` varchar(10) NOT NULL COMMENT '业务类型 ems,express,common',
  `Shipping_Content` text NOT NULL COMMENT '具体内容',
  PRIMARY KEY (`Section_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shipping_template_section
-- ----------------------------

-- ----------------------------
-- Table structure for shop_articles
-- ----------------------------
DROP TABLE IF EXISTS `shop_articles`;
CREATE TABLE `shop_articles` (
  `Users_ID` varchar(10) NOT NULL,
  `Article_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Category_ID` int(10) DEFAULT '0',
  `Article_Type` tinyint(4) DEFAULT '0' COMMENT '1表示常见问题，2表示帮助中心',
  `Article_Title` varchar(255) DEFAULT NULL,
  `Article_Content` text,
  `Article_Status` tinyint(1) DEFAULT '0',
  `Article_CreateTime` int(10) DEFAULT '0',
  `Article_Hits` int(10) DEFAULT '0',
  `Article_Editor` varchar(100) DEFAULT '',
  PRIMARY KEY (`Article_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商城文章表';

-- ----------------------------
-- Records of shop_articles
-- ----------------------------

-- ----------------------------
-- Table structure for shop_articles_category
-- ----------------------------
DROP TABLE IF EXISTS `shop_articles_category`;
CREATE TABLE `shop_articles_category` (
  `Users_ID` varchar(10) DEFAULT '',
  `Category_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Category_Index` int(10) DEFAULT '0',
  `Category_Name` varchar(255) DEFAULT '',
  `Category_Type` varchar(20) DEFAULT NULL,
  `Category_Content` text,
  `mob_show` tinyint(1) DEFAULT '0' COMMENT '0手机  1电脑 2全部 ',
  PRIMARY KEY (`Category_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商城文章分类表';

-- ----------------------------
-- Records of shop_articles_category
-- ----------------------------

-- ----------------------------
-- Table structure for shop_attribute
-- ----------------------------
DROP TABLE IF EXISTS `shop_attribute`;
CREATE TABLE `shop_attribute` (
  `Attr_ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Type_ID` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Attr_Name` varchar(60) NOT NULL DEFAULT '',
  `Attr_Input_Type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `Attr_Type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `Attr_Values` text NOT NULL,
  `Sort_Order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `Attr_Group` varchar(10) NOT NULL DEFAULT '',
  `Biz_ID` int(10) DEFAULT '0',
  PRIMARY KEY (`Attr_ID`),
  KEY `Type_ID` (`Type_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COMMENT='商城产品属性列表';

-- ----------------------------
-- Records of shop_attribute
-- ----------------------------

-- ----------------------------
-- Table structure for shop_bank_card
-- ----------------------------
DROP TABLE IF EXISTS `shop_bank_card`;
CREATE TABLE `shop_bank_card` (
  `Card_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) DEFAULT NULL,
  `User_ID` int(10) DEFAULT NULL,
  `Card_Name` varchar(100) DEFAULT NULL COMMENT '户名',
  `Card_Bank` varchar(100) DEFAULT NULL,
  `Card_No` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Card_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_bank_card
-- ----------------------------

-- ----------------------------
-- Table structure for shop_category
-- ----------------------------
DROP TABLE IF EXISTS `shop_category`;
CREATE TABLE `shop_category` (
  `Category_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Category_Index` int(11) NOT NULL,
  `Category_Name` varchar(50) NOT NULL,
  `Category_ParentID` int(11) NOT NULL DEFAULT '0',
  `Category_ListTypeID` tinyint(1) NOT NULL DEFAULT '0',
  `Category_Img` varchar(50) NOT NULL,
  `Category_IndexShow` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`Category_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_category
-- ----------------------------
INSERT INTO `shop_category` VALUES ('1', 'pl2hu3uczz', '1', '数码', '0', '0', '/uploadfiles/pl2hu3uczz/image/574e7a8af9.png', '1');
INSERT INTO `shop_category` VALUES ('2', 'pl2hu3uczz', '1', '生活', '0', '0', '/uploadfiles/pl2hu3uczz/image/574e7aa487.jpg', '1');

-- ----------------------------
-- Table structure for shop_config
-- ----------------------------
DROP TABLE IF EXISTS `shop_config`;
CREATE TABLE `shop_config` (
  `Users_ID` varchar(10) NOT NULL,
  `ShopName` varchar(50) DEFAULT NULL,
  `ShopLogo` varchar(200) DEFAULT '/static/api/images/user/face.jpg',
  `NeedShipping` tinyint(1) DEFAULT '1',
  `SendSms` tinyint(1) DEFAULT '0',
  `MobilePhone` varchar(20) DEFAULT NULL,
  `Skin_ID` int(11) DEFAULT '1',
  `Delivery_AddressEnabled` tinyint(1) DEFAULT NULL,
  `Delivery_Address` varchar(255) DEFAULT NULL,
  `Shipping` text,
  `Default_Shipping` smallint(6) DEFAULT NULL,
  `Default_Business` varchar(10) DEFAULT NULL,
  `Man` text,
  `CheckOrder` tinyint(1) DEFAULT '0' COMMENT '商家是否确认订单 1 关闭',
  `Commit_Check` tinyint(1) DEFAULT '0',
  `Integral_Convert` int(5) DEFAULT '10',
  `Integral_Buy` int(5) DEFAULT '100' COMMENT '积分抵用设置',
  `Integral_Use_Laws` text COMMENT '积分使用规则',
  `CallEnable` tinyint(1) DEFAULT '0',
  `CallPhoneNumber` varchar(20) DEFAULT '',
  `Confirm_Time` int(10) DEFAULT NULL,
  `ShopAnnounce` varchar(255) DEFAULT '',
  `Substribe` tinyint(1) DEFAULT '0',
  `SubstribeUrl` varchar(255) DEFAULT '',
  `Distribute_Share` tinyint(1) DEFAULT '0',
  `Distribute_ShareScore` int(10) DEFAULT '0',
  `Member_Share` tinyint(1) DEFAULT '0',
  `Member_ShareScore` int(10) DEFAULT '0',
  `ShareLogo` varchar(255) DEFAULT '',
  `ShareIntro` varchar(255) DEFAULT '',
  `ShopMenuJson` text COMMENT '商城菜单配置json格式',
  `Shop_Commision_Reward_Json` text COMMENT '网站佣金比例配置',
  PRIMARY KEY (`Users_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_config
-- ----------------------------
INSERT INTO `shop_config` VALUES ('pl2hu3uczz', 'admin的微商城', '/static/api/images/user/face.jpg', '1', '0', '', '9', null, null, null, null, null, null, '1', '0', '10', '100', null, '0', '', '0', '', '0', '', '0', '0', '0', '0', '', '', null, '{\"platForm_Income_Reward\":\"80\",\"noBi_Reward\":\"20\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"15\",\"salesman_ratio\":\"20\",\"commission_Reward\":\"20\",\"Distribute\":{\"391\":[\"50\",\"30\",\"15\",\"5\"]},\"salesman_level_ratio\":[\"0\",\"0\",\"0\"]}');

-- ----------------------------
-- Table structure for shop_distribute_account
-- ----------------------------
DROP TABLE IF EXISTS `shop_distribute_account`;
CREATE TABLE `shop_distribute_account` (
  `Users_ID` varchar(10) NOT NULL,
  `Account_ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_ID` int(11) DEFAULT NULL,
  `User_Name` varchar(30) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL,
  `Real_Name` varchar(400) DEFAULT NULL COMMENT '真实姓名',
  `Shop_Name` varchar(50) DEFAULT '',
  `Shop_Logo` varchar(200) DEFAULT '',
  `Shop_Announce` varchar(500) DEFAULT NULL,
  `Email` varchar(20) DEFAULT NULL,
  `ID_Card` varchar(20) DEFAULT NULL COMMENT '身份证号',
  `Alipay_Account` varchar(25) DEFAULT NULL,
  `Bank_Name` varchar(25) DEFAULT NULL,
  `Bank_Card` varchar(25) DEFAULT NULL,
  `Total_Income` decimal(10,2) DEFAULT '0.00',
  `Account_CreateTime` varchar(10) DEFAULT NULL,
  `Group_Num` int(10) DEFAULT '0',
  `invite_id` int(10) DEFAULT NULL COMMENT '邀请人的id',
  `Dis_Path` varchar(100) DEFAULT '' COMMENT '分销商关系',
  `Account_Mobile` varchar(11) DEFAULT NULL,
  `Is_Audit` tinyint(1) DEFAULT '0' COMMENT '是否通过审核',
  `Is_Regeposter` tinyint(1) DEFAULT '0' COMMENT '是否需要重新生产推广海报',
  `Total_Sales` float(10,2) DEFAULT '0.00',
  `Group_Sales` float(10,2) DEFAULT '0.00' COMMENT '团队总销售额',
  `Up_Group_Sales` float(10,2) DEFAULT '0.00',
  `Up_Group_Num` int(10) DEFAULT '0',
  `last_award_income` float(10,2) DEFAULT '0.00',
  `Professional_Title` tinyint(1) DEFAULT '0',
  `Ex_Bonus` float(10,2) DEFAULT '0.00',
  `Enable_Tixian` tinyint(1) DEFAULT '0',
  `Enable_Agent` tinyint(1) DEFAULT '0' COMMENT '0 开启代理 1关闭代理',
  `deleted_at` varchar(30) DEFAULT NULL,
  `Fanxian_Remainder` int(10) DEFAULT '1' COMMENT '距离下次返现还剩直接下属数',
  `Fanxian_Count` int(10) DEFAULT '0' COMMENT '返现次数',
  `Is_Dongjie` tinyint(1) DEFAULT '0' COMMENT '用户是否被冻结 0 正常 1 已冻结',
  `Is_Delete` tinyint(1) DEFAULT '0' COMMENT '用户是否被删除 0 否 1 是',
  PRIMARY KEY (`Account_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_distribute_account
-- ----------------------------

-- ----------------------------
-- Table structure for shop_distribute_account_record
-- ----------------------------
DROP TABLE IF EXISTS `shop_distribute_account_record`;
CREATE TABLE `shop_distribute_account_record` (
  `Users_ID` varchar(10) NOT NULL,
  `Record_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Ds_Record_ID` int(11) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL COMMENT '奖金获得者ID',
  `level` int(2) DEFAULT NULL,
  `Record_Sn` varchar(20) DEFAULT NULL,
  `Account_Info` varchar(200) DEFAULT NULL,
  `Record_Qty` int(10) DEFAULT '0' COMMENT '数量',
  `Record_Price` decimal(10,2) DEFAULT '0.00' COMMENT '数量为1时佣金金额，用于退款',
  `Record_Money` decimal(10,2) DEFAULT '0.00',
  `Record_Description` varchar(200) DEFAULT NULL,
  `Record_Type` tinyint(1) DEFAULT '0' COMMENT '获得奖金为0,提现为1',
  `Record_Status` tinyint(1) DEFAULT '0' COMMENT '针对获取佣金,状态0为已生成，状态1为已付款，状态2位已完成 ;针对提现，状态0为已生成，状态1才为已执行,状态2为驳回',
  `Nobi_Money` decimal(30,2) DEFAULT '0.00' COMMENT '爵位奖金',
  `Nobi_Description` char(100) DEFAULT '' COMMENT '爵位奖描述',
  `Record_CreateTime` int(10) DEFAULT NULL,
  `Nobi_Level` char(10) DEFAULT NULL,
  `deleted_at` varchar(30) DEFAULT NULL,
  `Owner_ID` int(10) DEFAULT NULL,
  `CartID` int(10) DEFAULT '0' COMMENT '购物车ID，用于退款',
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_distribute_account_record
-- ----------------------------

-- ----------------------------
-- Table structure for shop_distribute_config
-- ----------------------------
DROP TABLE IF EXISTS `shop_distribute_config`;
CREATE TABLE `shop_distribute_config` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Pro_Title_Level` text COMMENT '分销商称号设置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_distribute_config
-- ----------------------------

-- ----------------------------
-- Table structure for shop_distribute_fuxiao
-- ----------------------------
DROP TABLE IF EXISTS `shop_distribute_fuxiao`;
CREATE TABLE `shop_distribute_fuxiao` (
  `Record_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT '',
  `User_ID` int(10) DEFAULT '0',
  `User_OpenID` varchar(255) DEFAULT '',
  `Account_ID` int(10) DEFAULT '0' COMMENT '分销商ID',
  `Fuxiao_StartTime` int(10) DEFAULT '0' COMMENT '复销开始时间',
  `Fuxiao_Status` tinyint(1) DEFAULT '0' COMMENT '账号状态  0 正常 1 冻结 2 删除',
  `Fuxiao_Count` int(10) DEFAULT '0' COMMENT '该账户进行复销总次数',
  `Fuxiao_SubNoticeCount` int(10) DEFAULT '0' COMMENT '当月复销提醒剩余天数（冻结前）',
  `Fuxiao_LastNoticeTime` int(10) DEFAULT '0' COMMENT '最近发送提醒消息时间(冻结前)',
  `Fuxiao_SubDenedCount` int(10) DEFAULT '0' COMMENT '冻结提醒提醒剩余天数（冻结后）',
  `Fuxiao_LastDenedTime` int(10) DEFAULT '0' COMMENT '最近发送提醒消息时间(冻结后)',
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_distribute_fuxiao
-- ----------------------------

-- ----------------------------
-- Table structure for shop_distribute_msg
-- ----------------------------
DROP TABLE IF EXISTS `shop_distribute_msg`;
CREATE TABLE `shop_distribute_msg` (
  `Message_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Message_Title` varchar(50) DEFAULT NULL,
  `Message_Type` tinyint(1) DEFAULT '0' COMMENT '0 分销成功提醒 1 提现成功提醒',
  `Message_Description` text,
  `Message_CreateTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`Message_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_distribute_msg
-- ----------------------------

-- ----------------------------
-- Table structure for shop_distribute_record
-- ----------------------------
DROP TABLE IF EXISTS `shop_distribute_record`;
CREATE TABLE `shop_distribute_record` (
  `Users_ID` varchar(10) NOT NULL,
  `Record_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Buyer_ID` int(11) DEFAULT NULL COMMENT '商品购买者ID',
  `Owner_ID` int(11) DEFAULT NULL,
  `Order_ID` int(11) DEFAULT NULL,
  `Product_ID` int(11) DEFAULT NULL,
  `Product_Price` float(10,2) DEFAULT '0.00',
  `Qty` smallint(6) DEFAULT '0',
  `status` tinyint(2) DEFAULT '0' COMMENT '0为已下单,1为已完成',
  `Record_CreateTime` int(10) DEFAULT '0',
  `deleted_at` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_distribute_record
-- ----------------------------

-- ----------------------------
-- Table structure for shop_dis_agent_areas
-- ----------------------------
DROP TABLE IF EXISTS `shop_dis_agent_areas`;
CREATE TABLE `shop_dis_agent_areas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '0' COMMENT '类型 1 省代理，2市代理',
  `Users_ID` varchar(10) DEFAULT NULL,
  `Account_ID` int(10) DEFAULT NULL COMMENT '代理人用户ID',
  `area_id` int(10) DEFAULT NULL COMMENT '代理地区ID',
  `area_name` varchar(20) DEFAULT NULL COMMENT '地区别名，也就是地区名的拼音',
  `create_at` int(10) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态 0 为禁用 1 为启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_dis_agent_areas
-- ----------------------------

-- ----------------------------
-- Table structure for shop_dis_agent_rec
-- ----------------------------
DROP TABLE IF EXISTS `shop_dis_agent_rec`;
CREATE TABLE `shop_dis_agent_rec` (
  `Record_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) DEFAULT NULL,
  `Account_ID` int(10) DEFAULT '0',
  `Record_Money` float(10,2) DEFAULT NULL,
  `Record_Type` tinyint(1) DEFAULT '1' COMMENT '代理类型 1 普通代理 2省代理 3城市代理',
  `Record_CreateTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_dis_agent_rec
-- ----------------------------

-- ----------------------------
-- Table structure for shop_home
-- ----------------------------
DROP TABLE IF EXISTS `shop_home`;
CREATE TABLE `shop_home` (
  `Home_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Skin_ID` int(11) NOT NULL,
  `Home_Json` text,
  PRIMARY KEY (`Home_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=488 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_home
-- ----------------------------
INSERT INTO `shop_home` VALUES ('487', 'pl2hu3uczz', '9', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/9/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"315\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/9/logo.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"322\",\"Height\":\"108\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/9/t3.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"296\",\"Height\":\"240\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/9/t4.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"296\",\"Height\":\"240\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/9/t5.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"600\",\"Height\":\"200\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/9/t6.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"600\",\"Height\":\"200\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/9/t7.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"600\",\"Height\":\"200\",\"NeedLink\":\"1\"}]');

-- ----------------------------
-- Table structure for shop_products
-- ----------------------------
DROP TABLE IF EXISTS `shop_products`;
CREATE TABLE `shop_products` (
  `Products_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Products_Name` varchar(50) NOT NULL,
  `Products_Category` text NOT NULL,
  `Products_Type` smallint(6) DEFAULT '0' COMMENT '产品类型',
  `Products_PriceY` decimal(11,2) NOT NULL,
  `Products_PriceX` decimal(11,2) NOT NULL,
  `Products_JSON` text,
  `Products_BriefDescription` varchar(255) DEFAULT NULL,
  `Products_SoldOut` tinyint(4) DEFAULT NULL,
  `Products_IsNew` tinyint(4) DEFAULT NULL,
  `Products_IsRecommend` tinyint(4) DEFAULT NULL,
  `Products_IsHot` tinyint(4) DEFAULT NULL,
  `Products_IsShippingFree` tinyint(1) DEFAULT '0',
  `Products_Description` longtext,
  `Products_CreateTime` int(10) DEFAULT NULL,
  `Products_Count` int(10) DEFAULT '0' COMMENT '商品库存',
  `Products_Weight` decimal(10,2) DEFAULT '0.00',
  `Products_Sales` int(10) DEFAULT '0',
  `Products_Qrcode` varchar(200) DEFAULT NULL,
  `Products_IsVirtual` tinyint(1) DEFAULT '0',
  `Products_IsRecieve` tinyint(1) DEFAULT '0',
  `Products_Shipping` smallint(6) DEFAULT NULL,
  `Shipping_Free_Company` smallint(6) DEFAULT '0' COMMENT '免运费时所指定的快递公司，0为所有快递公司均可',
  `Products_Business` varchar(10) DEFAULT NULL,
  `commission_ratio` int(5) DEFAULT '100' COMMENT '佣金所占利润的百分比',
  `Biz_ID` int(10) DEFAULT '0',
  `deleted_at` varchar(30) DEFAULT NULL,
  `Products_FinanceType` tinyint(1) DEFAULT '0' COMMENT '0 按交易额比例 1 按供货价',
  `Products_PriceS` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品供货价',
  `Products_FinanceRate` decimal(10,2) DEFAULT '0.00' COMMENT '网站提成百分比',
  `Products_Distributes` varchar(255) DEFAULT NULL,
  `Products_BizCategory` int(10) DEFAULT '0',
  `Products_BizIsNew` tinyint(1) DEFAULT '0' COMMENT '商家店铺新品标志',
  `Products_BizIsHot` tinyint(1) DEFAULT '0' COMMENT '商家店铺热卖标志',
  `Products_BizIsRec` tinyint(1) DEFAULT '0' COMMENT '商家店铺推荐标志',
  `Products_Status` tinyint(1) DEFAULT '0' COMMENT '产品审核状态 0 未审核 1 审核',
  `Products_Index` int(10) DEFAULT '9999',
  `nobi_ratio` int(10) DEFAULT '0' COMMENT '爵位奖励',
  `platForm_Income_Reward` int(10) DEFAULT '0' COMMENT '平台所获佣金比例',
  `area_Proxy_Reward` int(10) DEFAULT '0' COMMENT '区域代理所获佣金比例',
  `sha_Reward` int(10) unsigned DEFAULT '0' COMMENT '股东佣金比例',
  `salesman_ratio` int(8) DEFAULT '0' COMMENT '业务提成',
  `salesman_level_ratio` varchar(250) DEFAULT NULL COMMENT '业务各级提成',
  PRIMARY KEY (`Products_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_products
-- ----------------------------
INSERT INTO `shop_products` VALUES ('1', 'pl2hu3uczz', '三星手机', ',1,1,', '0', '110.00', '100.00', '{\"ImgPath\":[\"/uploadfiles/biz/1/image/56f5052054.jpg\"]}', '测试手机', '0', '0', '0', '0', '0', '水水水水水水水水水水水水水水水水', '1464761081', '9982', '1.00', '8', '/data/temp/testb318b3c08727cae372cf955b6424f243.png', '0', '0', null, '0', null, '40', '1', null, '0', '50.00', '50.00', '{\"391\":[\"20\",\"50\",\"30\"]}', '0', '0', '0', '0', '1', '9999', '20', '50', '20', '20', null, '0');
INSERT INTO `shop_products` VALUES ('2', 'pl2hu3uczz', '苹果手机', ',1,1,', '0', '210.00', '200.00', '{\"ImgPath\":[\"/uploadfiles/biz/1/image/56f4fb24d9.jpg\"]}', '谁谁谁水水水水', '0', '0', '0', '0', '0', '水水水水谁谁谁水水水水', '1464764304', '9997', '1.00', '1', '/data/temp/testa520c2cca214d314fde9c8b16393773e.png', '0', '0', null, '0', null, '40', '1', null, '0', '100.00', '50.00', '{\"391\":[\"50\",\"20\",\"15\",\"15\"]}', '0', '0', '0', '0', '1', '9999', '10', '60', '20', '20', '10', '[\"50\",\"30\",\"20\"]');
INSERT INTO `shop_products` VALUES ('3', 'pl2hu3uczz', '测试业务产品', ',1,1,', '0', '120.00', '100.00', '{\"ImgPath\":[\"/uploadfiles/biz/8/image/5716f5c799.jpg\"]}', '水水水水谁谁谁水水水水', '0', '0', '0', '0', '0', '水水水水水水水水水水水水水水水水谁谁谁水水水水', '1465804027', '9953', '1.00', '16', '/data/temp/test8fb8211c938d99aad06a44dfaf3e5319.png', '0', '0', null, '0', null, '20', '8', null, '0', '20.00', '80.00', '{\"391\":[\"50\",\"20\",\"15\",\"15 \"]}', '0', '0', '0', '0', '1', '9999', '20', '60', '20', '20', '10', '[\"50\",\"30\",\"20\"]');
INSERT INTO `shop_products` VALUES ('4', 'pl2hu3uczz', '测试业务虚拟', ',1,1,', '0', '220.00', '200.00', '{\"ImgPath\":[\"/uploadfiles/biz/8/image/5765161128.jpg\"]}', '1111111', '0', '0', '0', '0', '0', '的的顶顶顶顶顶', '1466242588', '10000', '0.00', '0', '/data/temp/testb352508084118b2ef08e41d7032732c5.png', '0', '0', null, '0', null, '20', '8', null, '0', '40.00', '80.00', '{\"391\":[\"50\",\"30\",\"15\",\"5\"]}', '0', '0', '0', '0', '1', '9999', '20', '80', '20', '20', '20', '[\"50\",\"30\",\"20\"]');

-- ----------------------------
-- Table structure for shop_products_attr
-- ----------------------------
DROP TABLE IF EXISTS `shop_products_attr`;
CREATE TABLE `shop_products_attr` (
  `Product_Attr_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Products_ID` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Attr_ID` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Attr_Value` text NOT NULL,
  `Attr_Price` varchar(50) NOT NULL DEFAULT '',
  `Supply_Price` varchar(50) DEFAULT NULL COMMENT 'Supply_Price',
  PRIMARY KEY (`Product_Attr_ID`),
  KEY `products_id` (`Products_ID`),
  KEY `attr_id` (`Attr_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_products_attr
-- ----------------------------

-- ----------------------------
-- Table structure for shop_products_copy
-- ----------------------------
DROP TABLE IF EXISTS `shop_products_copy`;
CREATE TABLE `shop_products_copy` (
  `Products_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Products_Name` varchar(50) NOT NULL,
  `Products_Category` text NOT NULL,
  `Products_Type` smallint(6) DEFAULT '0' COMMENT '产品类型',
  `Products_PriceY` decimal(11,2) NOT NULL,
  `Products_PriceX` decimal(11,2) NOT NULL,
  `Products_JSON` text,
  `Products_BriefDescription` varchar(255) DEFAULT NULL,
  `Products_SoldOut` tinyint(4) DEFAULT NULL,
  `Products_IsNew` tinyint(4) DEFAULT NULL,
  `Products_IsRecommend` tinyint(4) DEFAULT NULL,
  `Products_IsHot` tinyint(4) DEFAULT NULL,
  `Products_IsShippingFree` tinyint(1) DEFAULT '0',
  `Products_Description` longtext,
  `Products_CreateTime` int(10) DEFAULT NULL,
  `Products_Count` int(10) DEFAULT '0' COMMENT '商品库存',
  `Products_Weight` decimal(10,2) DEFAULT '0.00',
  `Products_Sales` int(10) DEFAULT '0',
  `Products_Qrcode` varchar(200) DEFAULT NULL,
  `Products_IsVirtual` tinyint(1) DEFAULT '0',
  `Products_IsRecieve` tinyint(1) DEFAULT '0',
  `Products_Shipping` smallint(6) DEFAULT NULL,
  `Shipping_Free_Company` smallint(6) DEFAULT '0' COMMENT '免运费时所指定的快递公司，0为所有快递公司均可',
  `Products_Business` varchar(10) DEFAULT NULL,
  `commission_ratio` int(5) DEFAULT '100' COMMENT '佣金所占利润的百分比',
  `Biz_ID` int(10) DEFAULT '0',
  `deleted_at` varchar(30) DEFAULT NULL,
  `Products_FinanceType` tinyint(1) DEFAULT '0' COMMENT '0 按交易额比例 1 按供货价',
  `Products_PriceS` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品供货价',
  `Products_FinanceRate` decimal(10,2) DEFAULT '0.00' COMMENT '网站提成百分比',
  `Products_Distributes` varchar(255) DEFAULT NULL,
  `Products_BizCategory` int(10) DEFAULT '0',
  `Products_BizIsNew` tinyint(1) DEFAULT '0' COMMENT '商家店铺新品标志',
  `Products_BizIsHot` tinyint(1) DEFAULT '0' COMMENT '商家店铺热卖标志',
  `Products_BizIsRec` tinyint(1) DEFAULT '0' COMMENT '商家店铺推荐标志',
  `Products_Status` tinyint(1) DEFAULT '0' COMMENT '产品审核状态 0 未审核 1 审核',
  `Products_Index` int(10) DEFAULT '9999',
  `nobi_ratio` int(10) DEFAULT '0' COMMENT '爵位奖励',
  `platForm_Income_Reward` int(10) DEFAULT '0' COMMENT '平台所获佣金比例',
  `area_Proxy_Reward` int(10) DEFAULT '0' COMMENT '区域代理所获佣金比例',
  `sha_Reward` int(10) unsigned DEFAULT '0' COMMENT '股东佣金比例',
  PRIMARY KEY (`Products_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_products_copy
-- ----------------------------

-- ----------------------------
-- Table structure for shop_product_type
-- ----------------------------
DROP TABLE IF EXISTS `shop_product_type`;
CREATE TABLE `shop_product_type` (
  `Type_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Type_Index` int(11) NOT NULL,
  `Type_Name` varchar(50) NOT NULL,
  `Attr_Group` varchar(200) NOT NULL DEFAULT '' COMMENT '产品类型属性组，用换行符分割',
  `Status` tinyint(1) NOT NULL DEFAULT '1',
  `Biz_ID` int(10) DEFAULT '0',
  PRIMARY KEY (`Type_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_product_type
-- ----------------------------

-- ----------------------------
-- Table structure for shop_property
-- ----------------------------
DROP TABLE IF EXISTS `shop_property`;
CREATE TABLE `shop_property` (
  `Property_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Type_ID` smallint(6) NOT NULL DEFAULT '0' COMMENT '产品类型的ID',
  `Property_Type` int(10) DEFAULT '0',
  `Property_Index` int(11) NOT NULL,
  `Property_Name` varchar(10) NOT NULL,
  `Property_Json` text,
  PRIMARY KEY (`Property_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_property
-- ----------------------------

-- ----------------------------
-- Table structure for shop_sales_payment
-- ----------------------------
DROP TABLE IF EXISTS `shop_sales_payment`;
CREATE TABLE `shop_sales_payment` (
  `Payment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Payment_Sn` varchar(20) DEFAULT '',
  `Users_ID` varchar(50) DEFAULT '',
  `Biz_ID` int(10) DEFAULT '0',
  `FromTime` int(10) DEFAULT '0',
  `EndTime` int(10) DEFAULT '0',
  `Amount` decimal(10,2) DEFAULT '0.00',
  `Diff` decimal(10,2) DEFAULT '0.00' COMMENT '优惠金额（优惠券）',
  `Web` decimal(10,2) DEFAULT '0.00',
  `Bonus` decimal(10,2) DEFAULT '0.00',
  `Total` decimal(10,2) DEFAULT '0.00' COMMENT '应付款',
  `Bank` varchar(100) DEFAULT '' COMMENT '银行类型',
  `BankNo` varchar(100) DEFAULT '' COMMENT '银行卡号',
  `BankName` varchar(100) DEFAULT '' COMMENT '户主姓名',
  `BankMobile` varchar(50) DEFAULT '',
  `Status` tinyint(1) DEFAULT '0',
  `CreateTime` int(10) DEFAULT '0',
  PRIMARY KEY (`Payment_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_sales_payment
-- ----------------------------
INSERT INTO `shop_sales_payment` VALUES ('2', '14649436462', 'pl2hu3uczz', '1', '1464770820', '1465548420', '110.00', '0.00', '50.00', '0.00', '60.00', '1231', '123', '31231', '15517105580', '1', '1464943646');
INSERT INTO `shop_sales_payment` VALUES ('3', '14651779953', 'pl2hu3uczz', '1', '1465091520', '1465264320', '100.00', '0.00', '50.00', '0.00', '60.00', '132', '1231231', '123333333', '15517105555', '1', '1465177995');

-- ----------------------------
-- Table structure for shop_sales_record
-- ----------------------------
DROP TABLE IF EXISTS `shop_sales_record`;
CREATE TABLE `shop_sales_record` (
  `Record_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT '',
  `Order_ID` int(10) DEFAULT '0',
  `Biz_ID` int(10) DEFAULT '0',
  `Order_Amount` decimal(10,2) DEFAULT '0.00',
  `Order_Diff` decimal(10,2) DEFAULT '0.00',
  `Order_Shipping` decimal(10,2) DEFAULT '0.00',
  `Order_TotalPrice` decimal(10,2) DEFAULT '0.00',
  `Order_Json` longtext,
  `Bonus` decimal(10,2) DEFAULT '0.00',
  `Record_Status` tinyint(1) DEFAULT '0' COMMENT '0 未结算  1 已结算',
  `Record_CreateTime` int(10) DEFAULT '0',
  `Payment_ID` int(10) DEFAULT '0' COMMENT '付款单ID',
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_sales_record
-- ----------------------------
INSERT INTO `shop_sales_record` VALUES ('1', 'pl2hu3uczz', '1', '1', '0.00', '0.00', '0.00', '1580.00', '{\"1\":[{\"ProductsName\":\"3M贴膜\",\"ImgPath\":\"/uploadfiles/biz/1/image/574ee1118a.jpg\",\"ProductsPriceX\":\"1580.00\",\"ProductsPriceY\":\"1680.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"1\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":480}]}', '96.00', '1', '1464930534', '1');
INSERT INTO `shop_sales_record` VALUES ('2', 'pl2hu3uczz', '32', '3', '80.00', '0.00', '0.00', '80.00', '{\"4\":[{\"ProductsName\":\"幼儿童婴儿积木 一周岁半男宝宝益智力玩具0-1-2-3岁以下早教女孩\",\"ImgPath\":\"/uploadfiles/biz/3/image/57512f5d8e.png\",\"ProductsPriceX\":80,\"ProductsPriceY\":\"100.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"15\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"69,72\",\"Property\":{\"53\":{\"Name\":\"大小\",\"Value\":\"S\"},\"54\":{\"Name\":\"颜色\",\"Value\":\"绿色\"}},\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":40}]}', '8.00', '0', '1464939653', '0');
INSERT INTO `shop_sales_record` VALUES ('3', 'pl2hu3uczz', '38', '1', '0.00', '0.00', '0.00', '1580.00', '{\"1\":[{\"ProductsName\":\"3M贴膜\",\"ImgPath\":\"/uploadfiles/biz/1/image/574ee1118a.jpg\",\"ProductsPriceX\":\"1580.00\",\"ProductsPriceY\":\"1680.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"1\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":480}]}', '96.00', '1', '1464939663', '4');
INSERT INTO `shop_sales_record` VALUES ('4', 'pl2hu3uczz', '39', '1', '0.00', '0.00', '0.00', '1580.00', '{\"1\":[{\"ProductsName\":\"3M贴膜\",\"ImgPath\":\"/uploadfiles/biz/1/image/574ee1118a.jpg\",\"ProductsPriceX\":\"1580.00\",\"ProductsPriceY\":\"1680.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"16\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":480}]}', '134.40', '1', '1464940596', '4');
INSERT INTO `shop_sales_record` VALUES ('5', 'pl2hu3uczz', '42', '3', '80.00', '0.00', '0.00', '80.00', '{\"4\":[{\"ProductsName\":\"幼儿童婴儿积木 一周岁半男宝宝益智力玩具0-1-2-3岁以下早教女孩\",\"ImgPath\":\"/uploadfiles/biz/3/image/57512f5d8e.png\",\"ProductsPriceX\":80,\"ProductsPriceY\":\"100.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"15\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"69,72\",\"Property\":{\"53\":{\"Name\":\"大小\",\"Value\":\"S\"},\"54\":{\"Name\":\"颜色\",\"Value\":\"绿色\"}},\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":40}]}', '8.00', '0', '1464942417', '0');
INSERT INTO `shop_sales_record` VALUES ('6', 'pl2hu3uczz', '48', '3', '200.00', '0.00', '0.00', '200.00', '{\"3\":[{\"ProductsName\":\"纯棉四件套全棉被套床单婚庆儿童1.5宿舍三件套1.8米床上用品2.0m \",\"ImgPath\":\"/uploadfiles/biz/3/image/5750f95b96.png\",\"ProductsPriceX\":\"200.00\",\"ProductsPriceY\":\"200.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"15\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":100}]}', '20.00', '0', '1464943456', '0');
INSERT INTO `shop_sales_record` VALUES ('7', 'pl2hu3uczz', '49', '3', '200.00', '0.00', '0.00', '200.00', '{\"3\":[{\"ProductsName\":\"纯棉四件套全棉被套床单婚庆儿童1.5宿舍三件套1.8米床上用品2.0m \",\"ImgPath\":\"/uploadfiles/biz/3/image/5750f95b96.png\",\"ProductsPriceX\":\"200.00\",\"ProductsPriceY\":\"200.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"15\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":100}]}', '20.00', '0', '1464943527', '0');
INSERT INTO `shop_sales_record` VALUES ('8', 'pl2hu3uczz', '34', '3', '200.00', '0.00', '0.00', '200.00', '{&amp;quot;3&amp;quot;:[{&amp;quot;ProductsName&amp;quot;:&amp;quot;纯棉四件套全棉被套床单婚庆儿童1.5宿舍三件套1.8米床上用品2.0m &amp;quot;,&amp;quot;ImgPath&amp;quot;:&amp;quot;/uploadfiles/biz/3/image/5750f95b96.png&amp;quot;,&amp;quot;ProductsPriceX&amp;quot;:&amp;quot;200.00&amp;quot;,&amp;quot;ProductsPriceY&amp;quot;:&amp;quot;200.00&amp;quot;,&amp;quot;ProductsWeight&amp;quot;:&amp;quot;1.00&amp;quot;,&amp;quot;Products_Shipping&amp;quot;:null,&amp;quot;Products_Business&amp;quot;:null,&amp;quot;Shipping_Free_Company&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;IsShippingFree&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;OwnerID&amp;quot;:&amp;quot;14&amp;quot;,&amp;quot;ProductsIsShipping&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;Qty&amp;quot;:&amp;quot;1&amp;quot;,&amp;quot;spec_list&amp;quot;:&amp;quot;&amp;quot;,&amp;quot;Property&amp;quot;:[],&amp;quot;ProductsProfit&amp;quot;:100}]}', '0.00', '0', '1465177052', '0');
INSERT INTO `shop_sales_record` VALUES ('9', 'pl2hu3uczz', '78', '3', '200.00', '0.00', '0.00', '200.00', '{&amp;quot;3&amp;quot;:[{&amp;quot;ProductsName&amp;quot;:&amp;quot;纯棉四件套全棉被套床单婚庆儿童1.5宿舍三件套1.8米床上用品2.0m &amp;quot;,&amp;quot;ImgPath&amp;quot;:&amp;quot;/uploadfiles/biz/3/image/5750f95b96.png&amp;quot;,&amp;quot;ProductsPriceX&amp;quot;:&amp;quot;200.00&amp;quot;,&amp;quot;ProductsPriceY&amp;quot;:&amp;quot;200.00&amp;quot;,&amp;quot;ProductsWeight&amp;quot;:&amp;quot;1.00&amp;quot;,&amp;quot;Products_Shipping&amp;quot;:null,&amp;quot;Products_Business&amp;quot;:null,&amp;quot;Shipping_Free_Company&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;IsShippingFree&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;OwnerID&amp;quot;:&amp;quot;25&amp;quot;,&amp;quot;ProductsIsShipping&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;Qty&amp;quot;:&amp;quot;1&amp;quot;,&amp;quot;spec_list&amp;quot;:&amp;quot;&amp;quot;,&amp;quot;Property&amp;quot;:[],&amp;quot;ProductsProfit&amp;quot;:100}]}', '0.00', '0', '1465180897', '0');
INSERT INTO `shop_sales_record` VALUES ('10', 'pl2hu3uczz', '82', '3', '200.00', '0.00', '0.00', '200.00', '{&amp;quot;3&amp;quot;:[{&amp;quot;ProductsName&amp;quot;:&amp;quot;纯棉四件套全棉被套床单婚庆儿童1.5宿舍三件套1.8米床上用品2.0m &amp;quot;,&amp;quot;ImgPath&amp;quot;:&amp;quot;/uploadfiles/biz/3/image/5750f95b96.png&amp;quot;,&amp;quot;ProductsPriceX&amp;quot;:&amp;quot;200.00&amp;quot;,&amp;quot;ProductsPriceY&amp;quot;:&amp;quot;200.00&amp;quot;,&amp;quot;ProductsWeight&amp;quot;:&amp;quot;1.00&amp;quot;,&amp;quot;Products_Shipping&amp;quot;:null,&amp;quot;Products_Business&amp;quot;:null,&amp;quot;Shipping_Free_Company&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;IsShippingFree&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;OwnerID&amp;quot;:&amp;quot;25&amp;quot;,&amp;quot;ProductsIsShipping&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;Qty&amp;quot;:&amp;quot;1&amp;quot;,&amp;quot;spec_list&amp;quot;:&amp;quot;&amp;quot;,&amp;quot;Property&amp;quot;:[],&amp;quot;ProductsProfit&amp;quot;:100}]}', '0.00', '0', '1465181230', '0');
INSERT INTO `shop_sales_record` VALUES ('11', 'pl2hu3uczz', '83', '3', '200.00', '0.00', '0.00', '200.00', '{\"3\":[{\"ProductsName\":\"纯棉四件套全棉被套床单婚庆儿童1.5宿舍三件套1.8米床上用品2.0m \",\"ImgPath\":\"/uploadfiles/biz/3/image/5750f95b96.png\",\"ProductsPriceX\":\"200.00\",\"ProductsPriceY\":\"200.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"25\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":100}]}', '20.00', '0', '1465181332', '0');
INSERT INTO `shop_sales_record` VALUES ('12', 'pl2hu3uczz', '86', '3', '200.00', '0.00', '0.00', '200.00', '{&amp;quot;3&amp;quot;:[{&amp;quot;ProductsName&amp;quot;:&amp;quot;纯棉四件套全棉被套床单婚庆儿童1.5宿舍三件套1.8米床上用品2.0m &amp;quot;,&amp;quot;ImgPath&amp;quot;:&amp;quot;/uploadfiles/biz/3/image/5750f95b96.png&amp;quot;,&amp;quot;ProductsPriceX&amp;quot;:&amp;quot;200.00&amp;quot;,&amp;quot;ProductsPriceY&amp;quot;:&amp;quot;200.00&amp;quot;,&amp;quot;ProductsWeight&amp;quot;:&amp;quot;1.00&amp;quot;,&amp;quot;Products_Shipping&amp;quot;:null,&amp;quot;Products_Business&amp;quot;:null,&amp;quot;Shipping_Free_Company&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;IsShippingFree&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;OwnerID&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;ProductsIsShipping&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;Qty&amp;quot;:&amp;quot;1&amp;quot;,&amp;quot;spec_list&amp;quot;:&amp;quot;&amp;quot;,&amp;quot;Property&amp;quot;:[],&amp;quot;ProductsProfit&amp;quot;:100}]}', '0.00', '0', '1465181786', '0');
INSERT INTO `shop_sales_record` VALUES ('13', 'pl2hu3uczz', '87', '3', '80.00', '0.00', '0.00', '80.00', '{&amp;quot;4&amp;quot;:[{&amp;quot;ProductsName&amp;quot;:&amp;quot;幼儿童婴儿积木 一周岁半男宝宝益智力玩具0-1-2-3岁以下早教女孩&amp;quot;,&amp;quot;ImgPath&amp;quot;:&amp;quot;/uploadfiles/biz/3/image/57512f5d8e.png&amp;quot;,&amp;quot;ProductsPriceX&amp;quot;:80,&amp;quot;ProductsPriceY&amp;quot;:&amp;quot;100.00&amp;quot;,&amp;quot;ProductsWeight&amp;quot;:&amp;quot;1.00&amp;quot;,&amp;quot;Products_Shipping&amp;quot;:null,&amp;quot;Products_Business&amp;quot;:null,&amp;quot;Shipping_Free_Company&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;IsShippingFree&amp;quot;:&amp;quot;1&amp;quot;,&amp;quot;OwnerID&amp;quot;:&amp;quot;30&amp;quot;,&amp;quot;ProductsIsShipping&amp;quot;:&amp;quot;1&amp;quot;,&amp;quot;Qty&amp;quot;:&amp;quot;1&amp;quot;,&amp;quot;spec_list&amp;quot;:&amp;quot;69,72&amp;quot;,&amp;quot;Property&amp;quot;:{&amp;quot;53&amp;quot;:{&amp;quot;Name&amp;quot;:&amp;quot;大小&amp;quot;,&amp;quot;Value&amp;quot;:&amp;quot;S&amp;quot;},&amp;quot;54&amp;quot;:{&amp;quot;Name&amp;quot;:&amp;quot;颜色&amp;quot;,&amp;quot;Value&amp;quot;:&amp;quot;绿色&amp;quot;}},&amp;quot;ProductsProfit&amp;quot;:40}]}', '0.00', '0', '1465181918', '0');
INSERT INTO `shop_sales_record` VALUES ('14', 'v0wo2g5sah', '97', '4', '400.00', '0.00', '1.00', '401.00', '{\"6\":[{\"ProductsName\":\"【优果】泰国山竹 进口 新鲜时令水果 3斤装 顺丰空运包邮 \",\"ImgPath\":\"/uploadfiles/biz/4/image/5755449039.png\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"39\",\"ProductsIsShipping\":\"0\",\"Qty\":\"4\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"10\",\"ProductsProfit\":50}]}', '67.20', '1', '1465206825', '2');
INSERT INTO `shop_sales_record` VALUES ('15', 'v0wo2g5sah', '102', '5', '279.00', '0.00', '2.00', '181.00', '{\"7\":[{\"ProductsName\":\"多喜爱樱桃小丸子系列2016新品空调被 全棉空调被可水洗 果味童年\",\"ImgPath\":\"/uploadfiles/biz/5/image/57561e0d41.png\",\"ProductsPriceX\":\"179.00\",\"ProductsPriceY\":\"398.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"39\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"10\",\"ProductsProfit\":107.4}]}', '36.09', '1', '1465261842', '3');
INSERT INTO `shop_sales_record` VALUES ('16', 'v0wo2g5sah', '103', '4', '279.00', '0.00', '1.00', '101.00', '{\"6\":[{\"ProductsName\":\"【优果】泰国山竹 进口 新鲜时令水果 3斤装 顺丰空运包邮 \",\"ImgPath\":\"/uploadfiles/biz/4/image/5755449039.png\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"39\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"10\",\"ProductsProfit\":50}]}', '16.80', '0', '1465261849', '0');
INSERT INTO `shop_sales_record` VALUES ('17', 'pl2hu3uczz', '105', '1', '0.00', '0.00', '0.00', '10.00', '{\"5\":[{\"ProductsName\":\"杭州宜车商家收款\",\"ImgPath\":\"/uploadfiles/biz/1/image/57551954ad.jpg\",\"ProductsPriceX\":\"10.00\",\"ProductsPriceY\":\"10.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"51\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":0.5}]}', '0.00', '1', '1465264619', '4');
INSERT INTO `shop_sales_record` VALUES ('18', 'pl2hu3uczz', '106', '1', '0.00', '0.00', '0.00', '1580.00', '{\"1\":[{\"ProductsName\":\"3M贴膜\",\"ImgPath\":\"/uploadfiles/biz/1/image/574ee1118a.jpg\",\"ProductsPriceX\":\"1580.00\",\"ProductsPriceY\":\"1680.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"32\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":480}]}', '96.00', '1', '1465264666', '4');
INSERT INTO `shop_sales_record` VALUES ('19', 'pl2hu3uczz', '108', '3', '80.00', '0.00', '0.00', '80.00', '{\"4\":[{\"ProductsName\":\"幼儿童婴儿积木 一周岁半男宝宝益智力玩具0-1-2-3岁以下早教女孩\",\"ImgPath\":\"/uploadfiles/biz/3/image/57512f5d8e.png\",\"ProductsPriceX\":80,\"ProductsPriceY\":\"100.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"53\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"69,72\",\"Property\":{\"53\":{\"Name\":\"大小\",\"Value\":\"S\"},\"54\":{\"Name\":\"颜色\",\"Value\":\"绿色\"}},\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":40}]}', '8.00', '0', '1465289660', '0');
INSERT INTO `shop_sales_record` VALUES ('20', 'pl2hu3uczz', '94', '1', '0.00', '0.00', '0.00', '10.00', '{\"5\":[{\"ProductsName\":\"杭州宜车商家收款\",\"ImgPath\":\"/uploadfiles/biz/1/image/57551954ad.jpg\",\"ProductsPriceX\":\"10.00\",\"ProductsPriceY\":\"10.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"32\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":0.5}]}', '0.10', '1', '1465290087', '4');
INSERT INTO `shop_sales_record` VALUES ('21', 'pl2hu3uczz', '109', '1', '0.00', '0.00', '0.00', '10.00', '{\"5\":[{\"ProductsName\":\"杭州宜车商家收款\",\"ImgPath\":\"/uploadfiles/biz/1/image/57551954ad.jpg\",\"ProductsPriceX\":\"10.00\",\"ProductsPriceY\":\"10.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"32\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":0.5}]}', '0.10', '1', '1465290158', '4');
INSERT INTO `shop_sales_record` VALUES ('22', 'pl2hu3uczz', '110', '1', '0.00', '0.00', '0.00', '10.00', '{\"5\":[{\"ProductsName\":\"杭州宜车商家收款\",\"ImgPath\":\"/uploadfiles/biz/1/image/57551954ad.jpg\",\"ProductsPriceX\":\"10.00\",\"ProductsPriceY\":\"10.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"53\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":0.5}]}', '0.10', '1', '1465290335', '4');
INSERT INTO `shop_sales_record` VALUES ('23', 'pl2hu3uczz', '111', '1', '0.00', '0.00', '0.00', '1580.00', '{\"1\":[{\"ProductsName\":\"3M贴膜\",\"ImgPath\":\"/uploadfiles/biz/1/image/574ee1118a.jpg\",\"ProductsPriceX\":\"1580.00\",\"ProductsPriceY\":\"1680.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"53\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":480}]}', '96.00', '1', '1465290393', '4');
INSERT INTO `shop_sales_record` VALUES ('24', 'pl2hu3uczz', '112', '1', '0.00', '0.00', '0.00', '10.00', '{\"5\":[{\"ProductsName\":\"杭州宜车商家收款\",\"ImgPath\":\"/uploadfiles/biz/1/image/57551954ad.jpg\",\"ProductsPriceX\":\"10.00\",\"ProductsPriceY\":\"10.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"32\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":0.5}]}', '0.10', '1', '1465291872', '4');
INSERT INTO `shop_sales_record` VALUES ('25', 'pl2hu3uczz', '113', '1', '0.00', '0.00', '0.00', '10.00', '{\"5\":[{\"ProductsName\":\"杭州宜车商家收款\",\"ImgPath\":\"/uploadfiles/biz/1/image/57551954ad.jpg\",\"ProductsPriceX\":\"10.00\",\"ProductsPriceY\":\"10.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"32\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":0.5}]}', '0.10', '1', '1465292368', '4');
INSERT INTO `shop_sales_record` VALUES ('26', 'pl2hu3uczz', '114', '1', '0.00', '0.00', '0.00', '1580.00', '{\"1\":[{\"ProductsName\":\"3M贴膜\",\"ImgPath\":\"/uploadfiles/biz/1/image/574ee1118a.jpg\",\"ProductsPriceX\":\"1580.00\",\"ProductsPriceY\":\"1680.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"32\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":480}]}', '96.00', '0', '1465307156', '0');
INSERT INTO `shop_sales_record` VALUES ('27', 'pl2hu3uczz', '115', '1', '0.00', '0.00', '0.00', '1580.00', '{\"1\":[{\"ProductsName\":\"3M贴膜\",\"ImgPath\":\"/uploadfiles/biz/1/image/574ee1118a.jpg\",\"ProductsPriceX\":\"1580.00\",\"ProductsPriceY\":\"1680.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"46\",\"ProductsIsShipping\":\"1\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":480}]}', '115.20', '0', '1465308880', '0');
INSERT INTO `shop_sales_record` VALUES ('28', 'pl2hu3uczz', '116', '1', '0.00', '0.00', '0.00', '500.00', '{\"5\":[{\"ProductsName\":\"杭州宜车商家收款\",\"ImgPath\":\"/uploadfiles/biz/1/image/57551954ad.jpg\",\"ProductsPriceX\":\"10.00\",\"ProductsPriceY\":\"10.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"1\",\"OwnerID\":\"56\",\"ProductsIsShipping\":\"1\",\"Qty\":\"50\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"80\",\"area_Proxy_Reward\":\"10\",\"sha_Reward\":\"20\",\"ProductsProfit\":0.5}]}', '6.00', '0', '1465364842', '0');
INSERT INTO `shop_sales_record` VALUES ('29', 'pl2hu3uczz', '43', '8', '100.00', '0.00', '10.00', '110.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"12\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '16.32', '0', '1465866696', '0');
INSERT INTO `shop_sales_record` VALUES ('30', 'pl2hu3uczz', '45', '8', '100.00', '0.00', '10.00', '110.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"12\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '16.32', '0', '1465867497', '0');
INSERT INTO `shop_sales_record` VALUES ('31', 'pl2hu3uczz', '46', '8', '100.00', '0.00', '10.00', '110.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"12\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '16.32', '0', '1465867820', '0');
INSERT INTO `shop_sales_record` VALUES ('32', 'pl2hu3uczz', '48', '8', '100.00', '0.00', '10.00', '110.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"12\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '16.32', '0', '1465868302', '0');
INSERT INTO `shop_sales_record` VALUES ('33', 'pl2hu3uczz', '49', '8', '100.00', '0.00', '10.00', '110.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"13\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '2.88', '0', '1465870395', '0');
INSERT INTO `shop_sales_record` VALUES ('34', 'pl2hu3uczz', '52', '8', '100.00', '0.00', '10.00', '110.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"13\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '2.88', '0', '1465897614', '0');
INSERT INTO `shop_sales_record` VALUES ('35', 'pl2hu3uczz', '53', '8', '110.00', '0.00', '10.00', '110.00', '{&amp;quot;3&amp;quot;:[{&amp;quot;ProductsName&amp;quot;:&amp;quot;测试业务产品&amp;quot;,&amp;quot;ImgPath&amp;quot;:&amp;quot;/uploadfiles/biz/8/image/5716f5c799.jpg&amp;quot;,&amp;quot;ProductsPriceX&amp;quot;:&amp;quot;100.00&amp;quot;,&amp;quot;ProductsPriceY&amp;quot;:&amp;quot;120.00&amp;quot;,&amp;quot;ProductsWeight&amp;quot;:&amp;quot;1.00&amp;quot;,&amp;quot;Products_Shipping&amp;quot;:null,&amp;quot;Products_Business&amp;quot;:null,&amp;quot;Shipping_Free_Company&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;IsShippingFree&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;OwnerID&amp;quot;:&amp;quot;14&amp;quot;,&amp;quot;ProductsIsShipping&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;Qty&amp;quot;:&amp;quot;1&amp;quot;,&amp;quot;spec_list&amp;quot;:&amp;quot;&amp;quot;,&amp;quot;Property&amp;quot;:[],&amp;quot;ProductsProfit&amp;quot;:80}]}', '0.00', '0', '1465957323', '0');
INSERT INTO `shop_sales_record` VALUES ('36', 'pl2hu3uczz', '54', '8', '100.00', '0.00', '10.00', '110.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"14\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '2.88', '0', '1465957678', '0');
INSERT INTO `shop_sales_record` VALUES ('37', 'pl2hu3uczz', '70', '8', '110.00', '0.00', '10.00', '110.00', '{&amp;quot;3&amp;quot;:[{&amp;quot;ProductsName&amp;quot;:&amp;quot;测试业务产品&amp;quot;,&amp;quot;ImgPath&amp;quot;:&amp;quot;/uploadfiles/biz/8/image/5716f5c799.jpg&amp;quot;,&amp;quot;ProductsPriceX&amp;quot;:&amp;quot;100.00&amp;quot;,&amp;quot;ProductsPriceY&amp;quot;:&amp;quot;120.00&amp;quot;,&amp;quot;ProductsWeight&amp;quot;:&amp;quot;1.00&amp;quot;,&amp;quot;Products_Shipping&amp;quot;:null,&amp;quot;Products_Business&amp;quot;:null,&amp;quot;Shipping_Free_Company&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;IsShippingFree&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;OwnerID&amp;quot;:&amp;quot;10&amp;quot;,&amp;quot;ProductsIsShipping&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;Qty&amp;quot;:&amp;quot;1&amp;quot;,&amp;quot;spec_list&amp;quot;:&amp;quot;&amp;quot;,&amp;quot;Property&amp;quot;:[],&amp;quot;ProductsProfit&amp;quot;:80}]}', '2.88', '0', '1465966562', '0');
INSERT INTO `shop_sales_record` VALUES ('38', 'pl2hu3uczz', '71', '8', '110.00', '0.00', '10.00', '110.00', '{&amp;quot;3&amp;quot;:[{&amp;quot;ProductsName&amp;quot;:&amp;quot;测试业务产品&amp;quot;,&amp;quot;ImgPath&amp;quot;:&amp;quot;/uploadfiles/biz/8/image/5716f5c799.jpg&amp;quot;,&amp;quot;ProductsPriceX&amp;quot;:&amp;quot;100.00&amp;quot;,&amp;quot;ProductsPriceY&amp;quot;:&amp;quot;120.00&amp;quot;,&amp;quot;ProductsWeight&amp;quot;:&amp;quot;1.00&amp;quot;,&amp;quot;Products_Shipping&amp;quot;:null,&amp;quot;Products_Business&amp;quot;:null,&amp;quot;Shipping_Free_Company&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;IsShippingFree&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;OwnerID&amp;quot;:&amp;quot;12&amp;quot;,&amp;quot;ProductsIsShipping&amp;quot;:&amp;quot;0&amp;quot;,&amp;quot;Qty&amp;quot;:&amp;quot;1&amp;quot;,&amp;quot;spec_list&amp;quot;:&amp;quot;&amp;quot;,&amp;quot;Property&amp;quot;:[],&amp;quot;ProductsProfit&amp;quot;:80}]}', '16.32', '0', '1465966799', '0');
INSERT INTO `shop_sales_record` VALUES ('39', 'pl2hu3uczz', '72', '8', '800.00', '0.00', '11.00', '211.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"2\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '5.76', '0', '1466043148', '0');
INSERT INTO `shop_sales_record` VALUES ('40', 'pl2hu3uczz', '75', '8', '200.00', '0.00', '11.00', '211.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"2\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '5.76', '0', '1466043326', '0');
INSERT INTO `shop_sales_record` VALUES ('41', 'pl2hu3uczz', '76', '8', '200.00', '0.00', '11.00', '211.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"2\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '5.76', '0', '1466043483', '0');
INSERT INTO `shop_sales_record` VALUES ('42', 'pl2hu3uczz', '77', '8', '200.00', '0.00', '11.00', '211.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"2\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '5.76', '0', '1466043698', '0');
INSERT INTO `shop_sales_record` VALUES ('43', 'pl2hu3uczz', '81', '8', '100.00', '0.00', '10.00', '110.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '2.88', '0', '1466044800', '0');
INSERT INTO `shop_sales_record` VALUES ('44', 'pl2hu3uczz', '82', '8', '100.00', '0.00', '10.00', '110.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '2.88', '0', '1466059018', '0');
INSERT INTO `shop_sales_record` VALUES ('45', 'pl2hu3uczz', '83', '8', '100.00', '0.00', '10.00', '110.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"10\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '0.48', '0', '1466133960', '0');
INSERT INTO `shop_sales_record` VALUES ('46', 'pl2hu3uczz', '84', '8', '100.00', '0.00', '10.00', '110.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"10\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '0.48', '0', '1466147009', '0');
INSERT INTO `shop_sales_record` VALUES ('47', 'pl2hu3uczz', '85', '8', '100.00', '0.00', '10.00', '110.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"10\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '1.44', '0', '1466149392', '0');
INSERT INTO `shop_sales_record` VALUES ('48', 'pl2hu3uczz', '88', '8', '100.00', '0.00', '10.00', '110.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '1.44', '0', '1466217412', '0');
INSERT INTO `shop_sales_record` VALUES ('49', 'pl2hu3uczz', '89', '8', '100.00', '0.00', '10.00', '110.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '1.44', '0', '1466217494', '0');
INSERT INTO `shop_sales_record` VALUES ('50', 'pl2hu3uczz', '90', '8', '100.00', '0.00', '10.00', '110.00', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '1.44', '0', '1466218164', '0');

-- ----------------------------
-- Table structure for shop_shipping_company
-- ----------------------------
DROP TABLE IF EXISTS `shop_shipping_company`;
CREATE TABLE `shop_shipping_company` (
  `Shipping_ID` smallint(6) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Shipping_Code` varchar(20) NOT NULL,
  `Shipping_Name` varchar(120) NOT NULL,
  `Shipping_Business` varchar(20) DEFAULT NULL COMMENT '快递公司业务 1 快递 2 EMS 3平邮',
  `Cur_Template` smallint(6) DEFAULT '0' COMMENT '当前使用模板的ID',
  `Shipping_Desc` varchar(200) DEFAULT NULL,
  `Shipping_Status` tinyint(1) NOT NULL DEFAULT '1',
  `Shipping_CreateTime` int(10) NOT NULL,
  `Biz_ID` int(10) DEFAULT '0',
  PRIMARY KEY (`Shipping_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_shipping_company
-- ----------------------------
INSERT INTO `shop_shipping_company` VALUES ('1', 'pl2hu3uczz', 'ShunFeng', '顺丰', 'express', '0', null, '1', '1464761547', '1');
INSERT INTO `shop_shipping_company` VALUES ('2', 'pl2hu3uczz', 'CeShiKuaiDiGongSi', '测试快递公司', 'express', '0', null, '1', '1465804527', '8');

-- ----------------------------
-- Table structure for shop_shipping_print_template
-- ----------------------------
DROP TABLE IF EXISTS `shop_shipping_print_template`;
CREATE TABLE `shop_shipping_print_template` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usersid` varchar(50) DEFAULT '',
  `bizid` int(10) DEFAULT '0',
  `title` varchar(100) DEFAULT '' COMMENT '模板名称',
  `companyid` int(10) DEFAULT '0' COMMENT '相对应的物流公司ID',
  `width` decimal(10,2) DEFAULT '0.00' COMMENT '运单宽度 单位mm',
  `height` decimal(10,2) DEFAULT '0.00' COMMENT '运单高度 单位mm',
  `offset_top` decimal(10,2) DEFAULT '0.00' COMMENT '上偏移量',
  `offset_left` decimal(10,2) DEFAULT '0.00' COMMENT '左偏移量',
  `thumb` varchar(255) DEFAULT '' COMMENT '运单模板图片',
  `enabled` tinyint(1) DEFAULT '0' COMMENT '是否启用',
  `createtime` int(10) DEFAULT '0',
  `data_json` longtext COMMENT '页面设计',
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_shipping_print_template
-- ----------------------------
INSERT INTO `shop_shipping_print_template` VALUES ('1', 'pl2hu3uczz', '1', '顺丰运单模板', '1', '100.00', '50.00', '10.00', '10.00', '/uploadfiles/biz/1/image/56f4fd69a6.png', '1', '1464761657', null);
INSERT INTO `shop_shipping_print_template` VALUES ('2', 'pl2hu3uczz', '8', '测试快递公司运单模板', '2', '200.00', '100.00', '10.00', '10.00', '/uploadfiles/biz/8/image/575e6757ee.jpg', '0', '1465804671', null);

-- ----------------------------
-- Table structure for shop_shipping_template
-- ----------------------------
DROP TABLE IF EXISTS `shop_shipping_template`;
CREATE TABLE `shop_shipping_template` (
  `Template_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `By_Method` varchar(10) NOT NULL,
  `Template_Name` varchar(100) NOT NULL,
  `Shipping_ID` smallint(6) NOT NULL COMMENT '快递公司',
  `Template_Content` text NOT NULL COMMENT '模板具体内容',
  `Free_Content` text COMMENT '免运费规则',
  `Template_Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '模板状态',
  `Template_CreateTime` int(10) NOT NULL COMMENT '模板创建时间',
  `Biz_ID` int(10) DEFAULT '0',
  PRIMARY KEY (`Template_ID`),
  KEY `shipping_template_link` (`Shipping_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_shipping_template
-- ----------------------------
INSERT INTO `shop_shipping_template` VALUES ('1', 'pl2hu3uczz', 'by_weight', '顺丰模板', '1', '{\"express\":{\"default\":{\"start\":\"1\",\"postage\":\"10\",\"plus\":\"1\",\"postageplus\":\"1\"}}}', '', '1', '1464761588', '1');
INSERT INTO `shop_shipping_template` VALUES ('2', 'pl2hu3uczz', 'by_weight', '测试快递公司模板', '2', '{\"express\":{\"default\":{\"start\":\"1\",\"postage\":\"10\",\"plus\":\"1\",\"postageplus\":\"1\"}}}', '', '1', '1465804565', '8');

-- ----------------------------
-- Table structure for shop_skin
-- ----------------------------
DROP TABLE IF EXISTS `shop_skin`;
CREATE TABLE `shop_skin` (
  `Skin_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Skin_Name` varchar(50) DEFAULT NULL,
  `Skin_Json` text,
  `Skin_Status` tinyint(1) DEFAULT '0',
  `Skin_Index` int(10) DEFAULT '0',
  PRIMARY KEY (`Skin_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_skin
-- ----------------------------
INSERT INTO `shop_skin` VALUES ('1', '风格1', null, '1', '1');
INSERT INTO `shop_skin` VALUES ('2', '风格2', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/2/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/2/i1.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/2/i2.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/2/i3.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/2/i4.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"320\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/2/i5.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"320\",\"Height\":\"210\",\"NeedLink\":\"1\"}]', '1', '2');
INSERT INTO `shop_skin` VALUES ('3', '风格3', '[{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/3/logo.jpg\",\"Url\":null,\"Postion\":\"t01\",\"Width\":\"260\",\"Height\":\"100\",\"NeedLink\":\"1\"},{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/3/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t02\",\"Width\":\"630\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/3/a0.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"300\",\"Height\":\"340\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/3/a0.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"300\",\"Height\":\"340\",\"NeedLink\":\"1\"}]', '1', '3');
INSERT INTO `shop_skin` VALUES ('4', '风格4', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/4/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/4/a0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"640\",\"Height\":\"116\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/4/a1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"640\",\"Height\":\"116\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/4/a2.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"640\",\"Height\":\"116\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/4/a3.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"640\",\"Height\":\"116\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/4/a4.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"640\",\"Height\":\"116\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/4/a5.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"640\",\"Height\":\"116\",\"NeedLink\":\"1\"}]', '1', '4');
INSERT INTO `shop_skin` VALUES ('5', '风格5', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/5/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/5/i1.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/5/i2.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/5/i3.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/5/i4.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"320\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/5/i5.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"320\",\"Height\":\"210\",\"NeedLink\":\"1\"}]', '1', '5');
INSERT INTO `shop_skin` VALUES ('6', '风格6', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/6/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"栏目1\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"栏目\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"栏目\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"栏目\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"}]', '1', '6');
INSERT INTO `shop_skin` VALUES ('7', '风格7', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/7/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"605\",\"Height\":\"205\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/7/logo.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"250\",\"Height\":\"60\",\"NeedLink\":\"0\"}]', '1', '7');
INSERT INTO `shop_skin` VALUES ('8', '风格8', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/8/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"314\",\"NeedLink\":\"1\"}]', '1', '8');
INSERT INTO `shop_skin` VALUES ('9', '风格9', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/9/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"315\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/9/logo.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"322\",\"Height\":\"108\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/9/t3.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"296\",\"Height\":\"240\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/9/t4.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"296\",\"Height\":\"240\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/9/t5.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"600\",\"Height\":\"200\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/9/t6.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"600\",\"Height\":\"200\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/9/t7.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"600\",\"Height\":\"200\",\"NeedLink\":\"1\"}]', '1', '9');
INSERT INTO `shop_skin` VALUES ('10', '风格10', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/10/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"390\",\"NeedLink\":\"1\"}]', '1', '10');
INSERT INTO `shop_skin` VALUES ('11', '风格11', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/11/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"优惠活动\",\"ImgPath\":\"\",\"Url\":\"\",\"Postion\":\"t02\",\"Width\":\"640\",\"Height\":\"63\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/11/t2.jpg\",\"Url\":\"\",\"Postion\":\"t03\",\"Width\":\"203\",\"Height\":\"121\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/11/t3.jpg\",\"Url\":\"\",\"Postion\":\"t04\",\"Width\":\"203\",\"Height\":\"121\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/11/t4.jpg\",\"Url\":\"\",\"Postion\":\"t05\",\"Width\":\"203\",\"Height\":\"121\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/11/t5.jpg\",\"Url\":\"\",\"Postion\":\"t06\",\"Width\":\"609\",\"Height\":\"93\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"热卖商品\",\"ImgPath\":\"\",\"Url\":\"\",\"Postion\":\"t07\",\"Width\":\"640\",\"Height\":\"63\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/11/t6.jpg\",\"Url\":\"\",\"Postion\":\"t08\",\"Width\":\"608\",\"Height\":\"187\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"新品上市\",\"ImgPath\":\"\",\"Url\":\"\",\"Postion\":\"t09\",\"Width\":\"640\",\"Height\":\"63\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/11/t7.jpg\",\"Url\":\"\",\"Postion\":\"t10\",\"Width\":\"608\",\"Height\":\"187\",\"NeedLink\":\"1\"}]', '1', '11');
INSERT INTO `shop_skin` VALUES ('12', '风格12', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/12/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"262\",\"NeedLink\":\"1\"}]', '1', '12');
INSERT INTO `shop_skin` VALUES ('13', '风格13', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/13/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"262\",\"NeedLink\":\"1\"}]', '1', '13');
INSERT INTO `shop_skin` VALUES ('14', '风格14', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/14/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"262\",\"NeedLink\":\"1\"}]', '1', '14');
INSERT INTO `shop_skin` VALUES ('15', '风格15', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/15/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"262\",\"NeedLink\":\"1\"}]', '1', '15');
INSERT INTO `shop_skin` VALUES ('16', '风格16', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/16/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"262\",\"NeedLink\":\"1\"}]', '1', '16');
INSERT INTO `shop_skin` VALUES ('17', '风格17', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/17/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"262\",\"NeedLink\":\"1\"}]', '1', '17');
INSERT INTO `shop_skin` VALUES ('18', '风格18', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/18/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"}]', '1', '18');
INSERT INTO `shop_skin` VALUES ('19', '风格19', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/19/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"262\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"代言产品 \",\"ImgPath\":\"/api/shop/skin/19/i1.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"75\",\"Height\":\"75\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"天天特价\",\"ImgPath\":\"/api/shop/skin/19/i2.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"75\",\"Height\":\"75\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"分享赚钱\",\"ImgPath\":\"/api/shop/skin/19/i3.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"75\",\"Height\":\"75\",\"NeedLink\":\"1\"}]', '1', '19');
INSERT INTO `shop_skin` VALUES ('20', '风格20', '[{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/20/logo.png\",\"Url\":null,\"Postion\":\"t01\",\"Width\":\"180\",\"Height\":\"44\",\"NeedLink\":\"1\"},{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/shop/skin/20/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t02\",\"Width\":\"640\",\"Height\":\"262\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/20/i0.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"108\",\"Height\":\"108\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/20/i1.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"108\",\"Height\":\"108\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/20/i2.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"108\",\"Height\":\"108\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/shop/skin/20/i3.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"108\",\"Height\":\"108\",\"NeedLink\":\"1\"}]', '1', '20');

-- ----------------------------
-- Table structure for shop_user_withdraw_methods
-- ----------------------------
DROP TABLE IF EXISTS `shop_user_withdraw_methods`;
CREATE TABLE `shop_user_withdraw_methods` (
  `User_Method_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `User_ID` int(10) NOT NULL,
  `Method_Name` varchar(100) NOT NULL COMMENT '户名',
  `Method_Type` varchar(100) NOT NULL,
  `Account_Name` varchar(20) DEFAULT NULL COMMENT '银行卡则为户名，支付宝则为支付宝账号',
  `Account_Val` varchar(100) DEFAULT NULL COMMENT '银行卡编号',
  `Bank_Position` varchar(200) DEFAULT NULL COMMENT '开户行',
  `Method_CreateTime` int(10) DEFAULT NULL,
  `Method_Status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`User_Method_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_user_withdraw_methods
-- ----------------------------

-- ----------------------------
-- Table structure for shop_virtual_card
-- ----------------------------
DROP TABLE IF EXISTS `shop_virtual_card`;
CREATE TABLE `shop_virtual_card` (
  `Card_Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '卡密编号',
  `User_Id` varchar(10) NOT NULL,
  `Card_Name` varchar(30) NOT NULL COMMENT '账号',
  `Card_Password` varchar(30) NOT NULL COMMENT '虚拟卡密码',
  `Type_Id` int(10) unsigned NOT NULL,
  `Products_Relation_ID` int(11) NOT NULL DEFAULT '0' COMMENT '关联产品编号',
  `Card_Status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '卡密状态',
  `Card_CreateTime` int(10) NOT NULL,
  `Card_UpadteTime` int(10) NOT NULL COMMENT '最后修改时间',
  `Card_Description` varchar(200) DEFAULT NULL COMMENT '卡备注',
  `Biz_ID` bigint(20) DEFAULT '0',
  PRIMARY KEY (`Card_Id`),
  KEY `userid` (`User_Id`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_virtual_card
-- ----------------------------

-- ----------------------------
-- Table structure for shop_virtual_card_type
-- ----------------------------
DROP TABLE IF EXISTS `shop_virtual_card_type`;
CREATE TABLE `shop_virtual_card_type` (
  `Type_Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Type_Name` varchar(30) NOT NULL COMMENT '类型名称',
  `User_Id` varchar(10) NOT NULL,
  `Type_CreateTime` int(10) NOT NULL,
  `Biz_ID` bigint(20) DEFAULT '0',
  PRIMARY KEY (`Type_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_virtual_card_type
-- ----------------------------

-- ----------------------------
-- Table structure for shop_withdraw_method
-- ----------------------------
DROP TABLE IF EXISTS `shop_withdraw_method`;
CREATE TABLE `shop_withdraw_method` (
  `Method_ID` int(6) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Method_Name` varchar(100) NOT NULL,
  `Method_Type` varchar(20) NOT NULL DEFAULT '0' COMMENT '0 银行卡,1支付宝',
  `Method_CreateTime` int(10) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 为可用 0为被禁用',
  PRIMARY KEY (`Method_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_withdraw_method
-- ----------------------------

-- ----------------------------
-- Table structure for slide
-- ----------------------------
DROP TABLE IF EXISTS `slide`;
CREATE TABLE `slide` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `thumb` varchar(100) DEFAULT '',
  `linkurl` varchar(100) DEFAULT '',
  `listorder` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of slide
-- ----------------------------

-- ----------------------------
-- Table structure for sms
-- ----------------------------
DROP TABLE IF EXISTS `sms`;
CREATE TABLE `sms` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(30) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `word` int(10) unsigned NOT NULL DEFAULT '0',
  `sendtime` int(10) unsigned NOT NULL DEFAULT '0',
  `code` varchar(255) NOT NULL DEFAULT '',
  `usersid` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='短信记录';

-- ----------------------------
-- Records of sms
-- ----------------------------

-- ----------------------------
-- Table structure for statistics
-- ----------------------------
DROP TABLE IF EXISTS `statistics`;
CREATE TABLE `statistics` (
  `SID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(20) DEFAULT NULL,
  `S_Module` varchar(50) DEFAULT NULL,
  `S_CreateTime` int(10) DEFAULT '0',
  PRIMARY KEY (`SID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of statistics
-- ----------------------------

-- ----------------------------
-- Table structure for stores
-- ----------------------------
DROP TABLE IF EXISTS `stores`;
CREATE TABLE `stores` (
  `Stores_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Stores_Name` varchar(15) DEFAULT NULL,
  `Stores_ImgPath` varchar(100) DEFAULT NULL,
  `Stores_Telephone` varchar(255) DEFAULT NULL,
  `Stores_Address` varchar(50) DEFAULT NULL,
  `Stores_PrimaryLng` varchar(20) DEFAULT NULL,
  `Stores_PrimaryLat` varchar(20) DEFAULT NULL,
  `Stores_CreateTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`Stores_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of stores
-- ----------------------------

-- ----------------------------
-- Table structure for stores_config
-- ----------------------------
DROP TABLE IF EXISTS `stores_config`;
CREATE TABLE `stores_config` (
  `Users_ID` varchar(10) NOT NULL,
  `StoresName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Users_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of stores_config
-- ----------------------------

-- ----------------------------
-- Table structure for sysusers
-- ----------------------------
DROP TABLE IF EXISTS `sysusers`;
CREATE TABLE `sysusers` (
  `Users_ID` varchar(10) NOT NULL DEFAULT '',
  `Role_ID` varchar(10) DEFAULT NULL,
  `Users_Name` varchar(50) DEFAULT NULL,
  `Users_Sex` varchar(1) DEFAULT NULL,
  `Users_Account` varchar(50) DEFAULT NULL,
  `Users_Password` varchar(50) DEFAULT NULL,
  `Users_Phone` varchar(50) DEFAULT NULL,
  `Users_Mobile` varchar(50) DEFAULT NULL,
  `Users_Email` varchar(50) DEFAULT NULL,
  `Users_Status` bigint(1) DEFAULT '0',
  `Users_Notes` text,
  PRIMARY KEY (`Users_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sysusers
-- ----------------------------
INSERT INTO `sysusers` VALUES ('1001', '1001', '系统管理员', '女', 'admin', 'f379eaf3c831b04de153469d1bec345e', '', '', '', '1', '');

-- ----------------------------
-- Table structure for sysusers_role
-- ----------------------------
DROP TABLE IF EXISTS `sysusers_role`;
CREATE TABLE `sysusers_role` (
  `Role_ID` varchar(10) NOT NULL DEFAULT '',
  `Role_Name` varchar(50) DEFAULT NULL,
  `Role_Access` text,
  `Role_Index` tinyint(2) DEFAULT NULL,
  `Role_Status` tinyint(1) DEFAULT NULL,
  `Role_Notes` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`Role_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sysusers_role
-- ----------------------------
INSERT INTO `sysusers_role` VALUES ('1001', '系统管理员', '{\"1001\":{\"100101\":{\"10010101\":\"1\",\"10010102\":\"1\",\"10010103\":\"1\",\"10010104\":\"1\"},\"100102\":{\"10010201\":\"1\",\"10010202\":\"1\",\"10010203\":\"1\",\"10010204\":\"1\"}},\"1002\":{\"100201\":{\"10020101\":\"1\",\"10020102\":\"1\",\"10020103\":\"1\",\"10020104\":\"1\"},\"100202\":{\"10020201\":\"1\",\"10020202\":\"1\",\"10020203\":\"1\",\"10020204\":\"1\"},\"100203\":{\"10020301\":\"1\",\"10020302\":\"1\",\"10020303\":\"1\",\"10020304\":\"1\"},\"100204\":{\"10020401\":\"1\",\"10020402\":\"1\",\"10020403\":\"1\",\"10020404\":\"1\"},\"100205\":{\"10020501\":\"1\",\"10020502\":\"1\",\"10020503\":\"1\",\"10020504\":\"1\"},\"100206\":{\"10020601\":\"1\",\"10020602\":\"1\",\"10020603\":\"1\",\"10020604\":\"1\"},\"100207\":{\"10020701\":\"1\",\"10020702\":\"1\",\"10020703\":\"1\",\"10020704\":\"1\"}},\"1004\":{\"100401\":{\"10040101\":\"1\",\"10040102\":\"1\",\"10040103\":\"1\",\"10040104\":\"1\"},\"100402\":{\"10040201\":\"1\",\"10040202\":\"1\",\"10040203\":\"1\",\"10040204\":\"1\"},\"100403\":{\"10040301\":\"1\",\"10040302\":\"1\",\"10040303\":\"1\",\"10040304\":\"1\"},\"100404\":{\"10040401\":\"1\",\"10040402\":\"1\",\"10040403\":\"1\",\"10040404\":\"1\"}},\"1005\":{\"100501\":{\"10050101\":\"1\",\"10050102\":\"1\",\"10050103\":\"1\",\"10050104\":\"1\"},\"100502\":{\"10050201\":\"1\",\"10050202\":\"1\",\"10050203\":\"1\",\"10050204\":\"1\"},\"100503\":{\"10050301\":\"1\",\"10050302\":\"1\",\"10050303\":\"1\",\"10050304\":\"1\"},\"100504\":{\"10050401\":\"1\",\"10050402\":\"1\",\"10050403\":\"1\",\"10050404\":\"1\"},\"100505\":{\"10050501\":\"1\",\"10050502\":\"1\",\"10050503\":\"1\",\"10050504\":\"1\"},\"100506\":{\"10050601\":\"1\",\"10050602\":\"1\",\"10050603\":\"1\",\"10050604\":\"1\"},\"100507\":{\"10050701\":\"1\",\"10050702\":\"1\",\"10050703\":\"1\"},\"100508\":{\"10050801\":\"1\",\"10050802\":\"1\",\"10050803\":\"1\",\"10050804\":\"1\"}},\"1006\":{\"100601\":{\"10060101\":\"1\",\"10060102\":\"1\",\"10060103\":\"1\",\"10060104\":\"1\"},\"100602\":{\"10060201\":\"1\",\"10060202\":\"1\",\"10060203\":\"1\",\"10060204\":\"1\"},\"100603\":{\"10060301\":\"1\",\"10060302\":\"1\",\"10060303\":\"1\",\"10060304\":\"1\"},\"100604\":{\"10060401\":\"1\",\"10060402\":\"1\",\"10060403\":\"1\",\"10060404\":\"1\"},\"100605\":{\"10060501\":\"1\",\"10060502\":\"1\",\"10060503\":\"1\",\"10060504\":\"1\"},\"100606\":{\"10060601\":\"1\",\"10060602\":\"1\",\"10060603\":\"1\",\"10060604\":\"1\"},\"100607\":{\"10060701\":\"1\",\"10060702\":\"1\",\"10060703\":\"1\",\"10060704\":\"1\"}}}', '1', '1', '拥有所有权限');
INSERT INTO `sysusers_role` VALUES ('1002', '网站管理员', '{\"1003\":{\"100302\":{\"10030201\":\"1\",\"10030202\":\"1\",\"10030203\":\"1\",\"10030204\":\"1\"},\"100303\":{\"10030301\":\"1\",\"10030302\":\"1\",\"10030303\":\"1\",\"10030306\":\"1\"}},\"1004\":{\"100401\":{\"10040101\":\"1\",\"10040102\":\"1\",\"10040103\":\"1\",\"10040104\":\"1\"},\"100402\":{\"10040201\":\"1\",\"10040202\":\"1\",\"10040203\":\"1\",\"10040204\":\"1\"},\"100403\":{\"10040301\":\"1\",\"10040302\":\"1\",\"10040303\":\"1\",\"10040304\":\"1\"},\"100404\":{\"10040401\":\"1\",\"10040402\":\"1\",\"10040403\":\"1\",\"10040404\":\"1\"}}}', '2', '1', '用户案例,友情链接,栏目管理,文档管理,微信图文,微信文本,自定义页面');
INSERT INTO `sysusers_role` VALUES ('1003', '微信管理员', '{\"1002\":{\"100205\":{\"10020501\":\"1\",\"10020502\":\"1\",\"10020503\":\"1\",\"10020504\":\"1\"},\"100206\":{\"10020601\":\"1\",\"10020602\":\"1\",\"10020603\":\"1\",\"10020604\":\"1\"}},\"1005\":{\"100501\":{\"10050101\":\"1\",\"10050102\":\"1\",\"10050103\":\"1\",\"10050104\":\"1\"},\"100502\":{\"10050201\":\"1\",\"10050202\":\"1\",\"10050203\":\"1\",\"10050204\":\"1\"},\"100503\":{\"10050301\":\"1\",\"10050302\":\"1\",\"10050303\":\"1\",\"10050304\":\"1\"},\"100504\":{\"10050401\":\"1\",\"10050402\":\"1\",\"10050403\":\"1\",\"10050404\":\"1\"}}}', '3', '1', '');

-- ----------------------------
-- Table structure for turntable
-- ----------------------------
DROP TABLE IF EXISTS `turntable`;
CREATE TABLE `turntable` (
  `Users_ID` varchar(10) NOT NULL,
  `Turntable_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Turntable_Title` varchar(50) NOT NULL,
  `Turntable_StartTime` int(10) NOT NULL DEFAULT '0',
  `Turntable_EndTime` int(10) NOT NULL DEFAULT '0',
  `Turntable_OverTimesTipsToday` varchar(100) DEFAULT NULL,
  `Turntable_OverTimesTips` varchar(100) DEFAULT NULL,
  `Turntable_FirstPrize` varchar(50) DEFAULT NULL,
  `Turntable_FirstPrizeCount` int(11) DEFAULT '0',
  `Turntable_FirstPrizeProbability` decimal(11,2) DEFAULT '0.00',
  `Turntable_SecondPrize` varchar(50) DEFAULT NULL,
  `Turntable_SecondPrizeCount` int(11) DEFAULT '0',
  `Turntable_SecondPrizeProbability` decimal(11,2) DEFAULT '0.00',
  `Turntable_ThirdPrize` varchar(50) DEFAULT NULL,
  `Turntable_ThirdPrizeCount` int(11) DEFAULT '0',
  `Turntable_ThirdPrizeProbability` decimal(11,2) DEFAULT '0.00',
  `Turntable_IsShowPrizes` tinyint(1) DEFAULT '0',
  `Turntable_LotteryTimes` int(11) DEFAULT '0',
  `Turntable_EveryDayLotteryTimes` int(11) DEFAULT '0',
  `Turntable_BusinessPassWord` varchar(50) DEFAULT NULL,
  `Turntable_UsedIntegral` tinyint(1) DEFAULT '0',
  `Turntable_UsedIntegralValue` int(11) DEFAULT '0',
  `Turntable_Description` text,
  `Turntable_CreateTime` int(10) DEFAULT '0',
  `Turntable_Status` tinyint(1) DEFAULT '0',
  `Turntable_More_Integral` text,
  `Turntable_If_Share` tinyint(1) DEFAULT '0',
  `Turntable_Share_num` int(10) DEFAULT NULL,
  PRIMARY KEY (`Turntable_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of turntable
-- ----------------------------

-- ----------------------------
-- Table structure for turntable_config
-- ----------------------------
DROP TABLE IF EXISTS `turntable_config`;
CREATE TABLE `turntable_config` (
  `Users_ID` varchar(10) NOT NULL,
  `TurntableName` varchar(50) DEFAULT NULL,
  `SendSms` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`Users_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of turntable_config
-- ----------------------------

-- ----------------------------
-- Table structure for turntable_sn
-- ----------------------------
DROP TABLE IF EXISTS `turntable_sn`;
CREATE TABLE `turntable_sn` (
  `SN_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SN_Code` int(9) DEFAULT NULL,
  `SN_Status` tinyint(1) DEFAULT '0',
  `Users_ID` varchar(10) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `User_Name` varchar(50) DEFAULT NULL,
  `User_Mobile` varchar(50) DEFAULT NULL,
  `Turntable_ID` int(11) DEFAULT NULL,
  `Turntable_PrizeID` tinyint(1) DEFAULT '0',
  `Turntable_Prize` varchar(50) DEFAULT '0.00',
  `SN_UsedTimes` int(11) DEFAULT '0',
  `SN_CreateTime` int(10) DEFAULT NULL,
  `Open_ID` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`SN_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of turntable_sn
-- ----------------------------

-- ----------------------------
-- Table structure for update
-- ----------------------------
DROP TABLE IF EXISTS `update`;
CREATE TABLE `update` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `content1` text,
  `addtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='更新日志存储表';

-- ----------------------------
-- Records of update
-- ----------------------------

-- ----------------------------
-- Table structure for update_category
-- ----------------------------
DROP TABLE IF EXISTS `update_category`;
CREATE TABLE `update_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(100) DEFAULT NULL,
  `parentid` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='更新日志分类表';

-- ----------------------------
-- Records of update_category
-- ----------------------------

-- ----------------------------
-- Table structure for uploadfiles
-- ----------------------------
DROP TABLE IF EXISTS `uploadfiles`;
CREATE TABLE `uploadfiles` (
  `UploadFiles_ID` varchar(20) NOT NULL,
  `UploadFiles_TableField` varchar(50) DEFAULT NULL,
  `UploadFiles_DirName` varchar(5) DEFAULT NULL,
  `UploadFiles_SavePath` varchar(255) DEFAULT NULL,
  `UploadFiles_FileName` varchar(255) DEFAULT NULL,
  `UploadFiles_FileSize` decimal(11,2) DEFAULT NULL,
  `UploadFiles_CreateDate` datetime DEFAULT NULL,
  `UploadFiles_IsUse` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`UploadFiles_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uploadfiles
-- ----------------------------
INSERT INTO `uploadfiles` VALUES ('574e79d21a', 'biz', 'image', '/uploadfiles/pl2hu3uczz/image/574e79d21a.png', '33.png', '25.48', '2016-06-01 13:59:46', '0');
INSERT INTO `uploadfiles` VALUES ('575e6757ee', 'printtemplate', 'image', '/uploadfiles/biz/8/image/575e6757ee.jpg', '2p-4.jpg', '91.80', '2016-06-13 15:57:11', '0');
INSERT INTO `uploadfiles` VALUES ('5765161128', 'shop_products', 'image', '/uploadfiles/biz/8/image/5765161128.jpg', '2p-2.jpg', '235.38', '2016-06-18 17:36:17', '0');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `Users_ID` varchar(10) NOT NULL DEFAULT '',
  `User_OpenID` varchar(50) NOT NULL DEFAULT '',
  `User_ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_No` int(11) NOT NULL,
  `User_Mobile` varchar(20) DEFAULT '',
  `User_Name` varchar(50) DEFAULT NULL,
  `User_Password` varchar(50) NOT NULL,
  `User_Gender` varchar(50) DEFAULT NULL,
  `User_Age` varchar(50) DEFAULT NULL,
  `User_NickName` varchar(50) DEFAULT NULL,
  `User_IDNum` varchar(50) DEFAULT NULL,
  `User_Telephone` varchar(50) DEFAULT NULL,
  `User_Fax` varchar(50) DEFAULT NULL,
  `User_Birthday` varchar(50) DEFAULT NULL,
  `User_QQ` varchar(50) DEFAULT NULL,
  `User_Email` varchar(50) DEFAULT NULL,
  `User_Company` varchar(50) DEFAULT NULL,
  `User_Level` tinyint(1) DEFAULT '0',
  `User_Integral` int(11) DEFAULT '0',
  `User_UseLessIntegral` int(10) DEFAULT '0',
  `User_TotalIntegral` int(11) DEFAULT '0',
  `User_Cost` decimal(10,2) DEFAULT '0.00',
  `User_Province` varchar(50) DEFAULT NULL,
  `User_City` varchar(50) DEFAULT NULL,
  `User_Area` varchar(50) DEFAULT NULL,
  `User_Address` varchar(100) DEFAULT NULL,
  `User_CreateTime` varchar(10) DEFAULT NULL,
  `User_From` tinyint(1) DEFAULT '0',
  `User_Status` tinyint(1) NOT NULL DEFAULT '0',
  `User_Remarks` varchar(255) DEFAULT NULL,
  `User_Json_Input` text,
  `User_Json_Select` text,
  `User_HeadImg` varchar(255) DEFAULT NULL,
  `User_Profile` tinyint(1) DEFAULT '1',
  `User_Money` decimal(10,2) DEFAULT '0.00',
  `User_PayPassword` varchar(50) DEFAULT NULL,
  `Is_Distribute` tinyint(1) DEFAULT '0',
  `Owner_Id` int(5) DEFAULT '0',
  `Root_ID` int(5) DEFAULT '0' COMMENT '此人的普通代理ID,就是根店ID',
  `User_ExpireTime` bigint(20) DEFAULT '0' COMMENT '会员有效期',
  PRIMARY KEY (`User_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('pl2hu3uczz', 'e5662c3876bd8c2ef7e74d7bf87d7041', '2', '600001', '15517105580', null, 'e10adc3949ba59abbe56e057f20f883e', null, null, null, null, null, null, null, null, null, null, '0', '238', '0', '238', '2890.00', null, null, null, null, '1464778338', '2', '1', null, null, null, null, '1', '7792.00', 'e10adc3949ba59abbe56e057f20f883e', '1', '0', '0', '0');
INSERT INTO `user` VALUES ('pl2hu3uczz', 'e42c2c1cb68328ae0d61a99b6560f3f1', '3', '600002', '15517105555', '5555', 'e10adc3949ba59abbe56e057f20f883e', null, null, '5566', null, null, null, null, null, null, null, '0', '44', '0', '44', '420.00', null, null, null, null, '1464828060', '2', '1', null, null, null, null, '1', '9791.00', 'e10adc3949ba59abbe56e057f20f883e', '1', '0', '0', '0');
INSERT INTO `user` VALUES ('pl2hu3uczz', '987e7e6cc2f4325968ec1623801bf35d', '4', '600003', '15517109999', null, 'e10adc3949ba59abbe56e057f20f883e', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0.00', null, null, null, null, '1464839367', '2', '1', null, null, null, null, '1', '10000.00', '202cb962ac59075b964b07152d234b70', '1', '0', '0', '0');
INSERT INTO `user` VALUES ('pl2hu3uczz', 'aa5d81e779190a2446fc6ba2541f19e5', '5', '600004', '12222222222222223', null, 'e10adc3949ba59abbe56e057f20f883e', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0.00', null, null, null, null, '1464849354', '2', '1', null, null, null, null, '1', '0.00', 'e10adc3949ba59abbe56e057f20f883e', '1', '3', '3', '0');
INSERT INTO `user` VALUES ('pl2hu3uczz', '7a0bb2f77ec0466632bbfc447efe5ca1', '6', '600005', '15517100000', null, 'e10adc3949ba59abbe56e057f20f883e', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0.00', null, null, null, null, '1464916467', '1', '1', '', '', '', null, '1', '0.00', 'e10adc3949ba59abbe56e057f20f883e', '1', '0', '0', '0');
INSERT INTO `user` VALUES ('pl2hu3uczz', '2f4be56faf63dbe72243b67ba02b1cbc', '7', '600006', '15512345001', null, '202cb962ac59075b964b07152d234b70', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0.00', null, null, null, null, '1465357480', '1', '1', '', '', '', null, '1', '0.00', '202cb962ac59075b964b07152d234b70', '1', '0', '0', '0');
INSERT INTO `user` VALUES ('pl2hu3uczz', '9d980c17eb2a670ec818904e37b3653a', '8', '600007', '15512345002', null, '202cb962ac59075b964b07152d234b70', null, null, null, null, null, null, null, null, null, null, '0', '0', '0', '0', '0.00', null, null, null, null, '1465357537', '1', '1', '', '', '', null, '1', '0.00', '202cb962ac59075b964b07152d234b70', '1', '0', '0', '0');
INSERT INTO `user` VALUES ('pl2hu3uczz', '7e14ab0902d68a188bc941da24ca9b73', '9', '600008', '15517101234', '', '202cb962ac59075b964b07152d234b70', null, null, '我是银牌', null, null, null, null, null, null, null, '0', '11', '0', '11', '100.00', null, null, null, null, '1465694817', '1', '1', '', '', '', null, '1', '160.00', '202cb962ac59075b964b07152d234b70', '1', '0', '0', '0');
INSERT INTO `user` VALUES ('pl2hu3uczz', 'edc57b4a1e3effd937bc3f6a023e3f2c', '10', '600009', '15517101111', '', '202cb962ac59075b964b07152d234b70', null, null, '三级业务', null, null, null, null, null, null, null, '0', '44', '0', '44', '410.00', null, null, null, null, '1465802252', '1', '1', '', '', '', null, '1', '5560.00', '202cb962ac59075b964b07152d234b70', '1', '0', '0', '0');
INSERT INTO `user` VALUES ('pl2hu3uczz', '909d4192cde0fe64c12f0e3ae64e3023', '11', '600010', '15517102222', '', '202cb962ac59075b964b07152d234b70', null, null, '二级业务', null, null, null, null, null, null, null, '0', '0', '0', '0', '0.00', null, null, null, null, '1465802315', '1', '1', '', '', '', null, '1', '0.00', '202cb962ac59075b964b07152d234b70', '1', '10', '10', '0');
INSERT INTO `user` VALUES ('pl2hu3uczz', '0309d53e968afd459c08d0c0fb08fe0f', '12', '600011', '15517103333', '', '202cb962ac59075b964b07152d234b70', null, null, '购买者一级', null, null, null, null, null, null, null, '0', '55', '0', '55', '510.00', null, null, null, null, '1465802953', '1', '1', '', '', '', null, '1', '290.00', '202cb962ac59075b964b07152d234b70', '1', '11', '11', '0');
INSERT INTO `user` VALUES ('pl2hu3uczz', 'f388525df2c6979126c708e6a8ea3e94', '13', '600012', '15517104444', '', '202cb962ac59075b964b07152d234b70', null, null, '不是创始人', null, null, null, null, null, null, null, '0', '22', '0', '22', '200.00', null, null, null, null, '1465869866', '1', '1', '', '', '', null, '1', '780.00', '202cb962ac59075b964b07152d234b70', '1', '0', '0', '0');
INSERT INTO `user` VALUES ('pl2hu3uczz', '9d4bc638a7e356a6d4a44091fc35a31c', '14', '600013', '15517106666', '', '202cb962ac59075b964b07152d234b70', null, null, '测试pc', null, null, null, null, null, null, null, '0', '22', '0', '22', '210.00', null, null, null, null, '1465950959', '1', '1', '', '', '', null, '1', '5780.00', '202cb962ac59075b964b07152d234b70', '1', '0', '0', '0');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `Users_ID` varchar(10) NOT NULL,
  `Users_Account` varchar(50) NOT NULL,
  `Users_Password` varchar(50) NOT NULL,
  `Agent_ID` int(11) DEFAULT '0',
  `Users_WechatType` tinyint(1) DEFAULT '0',
  `Users_WechatToken` varchar(10) DEFAULT NULL,
  `Users_WechatName` varchar(50) DEFAULT NULL,
  `Users_WechatEmail` varchar(50) DEFAULT NULL,
  `Users_WechatID` varchar(50) DEFAULT NULL,
  `Users_WechatAccount` varchar(50) DEFAULT NULL,
  `Users_WechatAppId` varchar(18) DEFAULT NULL,
  `Users_WechatAppSecret` varchar(32) DEFAULT NULL,
  `Users_WechatAuth` tinyint(1) DEFAULT NULL,
  `Users_WechatVoice` tinyint(1) DEFAULT NULL,
  `Users_EncodingAESKey` varchar(100) DEFAULT NULL,
  `Users_EncodingAESKeyType` tinyint(1) DEFAULT '0',
  `Users_CreateTime` varchar(10) DEFAULT NULL,
  `Users_ServiceYear` tinyint(4) DEFAULT '0',
  `Users_DisplaySupport` tinyint(1) DEFAULT '0',
  `Users_CustomerName` varchar(50) DEFAULT NULL,
  `Users_ContactName` varchar(50) DEFAULT NULL,
  `Users_ContactPhone` varchar(5) DEFAULT NULL,
  `Users_Mobile` varchar(11) DEFAULT NULL,
  `Users_Email` varchar(50) DEFAULT NULL,
  `Users_QQ` varchar(20) DEFAULT NULL,
  `Users_Status` tinyint(1) NOT NULL DEFAULT '0',
  `Users_Remarks` varchar(255) DEFAULT NULL,
  `Users_Phone` varchar(255) DEFAULT NULL,
  `Group_ID` tinyint(1) DEFAULT '0',
  `Users_ExpireDate` int(10) DEFAULT '0',
  `Users_Right` text,
  `Users_BdAppID` varchar(50) DEFAULT NULL,
  `Users_Company` varchar(100) DEFAULT '',
  `Users_Industry` int(10) DEFAULT '0',
  `Users_Logo` varchar(255) DEFAULT '',
  `Users_Sms` int(10) DEFAULT '0',
  PRIMARY KEY (`Users_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('pl2hu3uczz', 'admin', 'f379eaf3c831b04de153469d1bec345e', '0', '3', '3tlrqz5yfe', '', '', '', '', 'wx1215001c2958fdcc', 'c7544955ff67b9d52627e23e58231a53', '0', '0', '', '0', '1464431894', '0', '0', null, null, null, '15503724204', null, null, '1', '', null, '0', '1464691094', '{\"web\":[\"web\"],\"kanjia\":[\"kanjia\"],\"zhuli\":[\"zhuli\"],\"zhongchou\":[\"zhongchou\"],\"games\":[\"games\"],\"weicuxiao\":[\"sctrach\",\"fruit\",\"turntable\",\"battle\"],\"votes\":[\"votes\"]}', null, '', '143', '', '0');

-- ----------------------------
-- Table structure for users_access_token
-- ----------------------------
DROP TABLE IF EXISTS `users_access_token`;
CREATE TABLE `users_access_token` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usersid` varchar(10) DEFAULT '',
  `access_token` text,
  `expires_in` int(10) DEFAULT '0',
  `jssdk_ticket` text,
  `jssdk_expires_in` int(10) DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users_access_token
-- ----------------------------
INSERT INTO `users_access_token` VALUES ('1', 'pl2hu3uczz', 'QBI3EUZtNlIuUn0ehw-42wvOYQz4FEOudlJ8h5slIaMMoTHcHYyqPUKviyODJdvodIjLlGAPGwg_gV9Qoz6oMBbbEjAKQl9kot5hMjP_Y5RhAaKh_nGrePwdhdoi82emBLRiCIASCI', '1466396606', 'sM4AOVdWfPE4DxkXGEs8VHpNul1Hf37w-iNYF7X1BBIJJm3g2XM5bUKKFqWLpgWSvndvsltJYQnc_TveBRIdzw', '1466396606');

-- ----------------------------
-- Table structure for users_employee
-- ----------------------------
DROP TABLE IF EXISTS `users_employee`;
CREATE TABLE `users_employee` (
  `id` int(64) unsigned NOT NULL AUTO_INCREMENT COMMENT '员工账号id',
  `employee_name` char(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '员工名称',
  `employee_pass` char(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '员工密码',
  `employee_expiretime` int(12) DEFAULT NULL COMMENT '过期时间',
  `status` int(2) DEFAULT NULL COMMENT '状态0禁用1启用',
  `role_id` int(64) DEFAULT NULL COMMENT '角色id',
  `create_time` int(12) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(32) DEFAULT NULL COMMENT '修改时间',
  `users_account` char(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '商家账号',
  `employee_note` char(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '商家说明',
  `employee_login_name` char(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '账号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users_employee
-- ----------------------------

-- ----------------------------
-- Table structure for users_group
-- ----------------------------
DROP TABLE IF EXISTS `users_group`;
CREATE TABLE `users_group` (
  `Group_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Group_Name` varchar(50) DEFAULT NULL,
  `Group_DiyNum` int(11) DEFAULT NULL,
  `Group_ConnectNum` int(11) DEFAULT NULL,
  `Group_ActivityNum` int(11) DEFAULT NULL,
  `Group_Copyright` tinyint(1) DEFAULT NULL,
  `Group_Price` int(11) DEFAULT NULL,
  `Group_StatisticsUser` int(11) DEFAULT NULL,
  `Group_Card_CreateNum` int(4) DEFAULT NULL,
  `Group_Status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Group_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users_group
-- ----------------------------

-- ----------------------------
-- Table structure for users_menu
-- ----------------------------
DROP TABLE IF EXISTS `users_menu`;
CREATE TABLE `users_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(40) NOT NULL,
  `menu_target` tinyint(1) DEFAULT NULL,
  `menu_link` varchar(100) DEFAULT NULL,
  `Users_ID` varchar(10) DEFAULT NULL,
  `menu_sort` int(10) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users_menu
-- ----------------------------

-- ----------------------------
-- Table structure for users_money_record
-- ----------------------------
DROP TABLE IF EXISTS `users_money_record`;
CREATE TABLE `users_money_record` (
  `Record_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Record_Sn` varchar(30) DEFAULT NULL,
  `Record_CreateTime` int(10) DEFAULT NULL,
  `Record_Qty` int(10) DEFAULT NULL,
  `Record_Money` float(10,2) DEFAULT NULL,
  `trade_no` varchar(28) DEFAULT NULL,
  `Record_Status` tinyint(1) DEFAULT NULL,
  `Record_Type` tinyint(1) DEFAULT '0' COMMENT '0 续费 1 短信',
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店主续费记录表';

-- ----------------------------
-- Records of users_money_record
-- ----------------------------

-- ----------------------------
-- Table structure for users_payconfig
-- ----------------------------
DROP TABLE IF EXISTS `users_payconfig`;
CREATE TABLE `users_payconfig` (
  `Users_ID` varchar(10) NOT NULL,
  `PaymentWxpayEnabled` tinyint(1) DEFAULT NULL,
  `PaymentWxpayType` tinyint(1) DEFAULT '0' COMMENT '微信支付版本  0  旧版本 1 新版本',
  `PaymentWxpayPartnerId` varchar(100) DEFAULT NULL,
  `PaymentWxpayPartnerKey` varchar(100) DEFAULT NULL,
  `PaymentWxpayPaySignKey` text,
  `PaymentWxpayCert` text,
  `PaymentWxpayKey` text,
  `Payment_AlipayEnabled` tinyint(1) DEFAULT NULL,
  `Payment_AlipayPartner` varchar(50) DEFAULT NULL,
  `Payment_AlipayKey` varchar(50) DEFAULT NULL,
  `Payment_AlipayAccount` varchar(50) DEFAULT NULL,
  `Payment_RmainderEnabled` tinyint(1) DEFAULT '1',
  `PaymentYeepayEnabled` tinyint(1) DEFAULT NULL,
  `PaymentYeepayAccount` varchar(100) DEFAULT NULL,
  `PaymentYeepayPrivateKey` varchar(100) DEFAULT NULL,
  `PaymentYeepayPublicKey` varchar(100) DEFAULT NULL,
  `PaymentYeepayYeepayPublicKey` varchar(100) DEFAULT NULL,
  `PaymentYeepayProductCatalog` int(10) DEFAULT NULL,
  `Payment_OfflineEnabled` tinyint(1) DEFAULT NULL,
  `Payment_OfflineInfo` varchar(255) DEFAULT NULL,
  `Delivery_AddressEnabled` tinyint(1) DEFAULT NULL,
  `Delivery_Address` varchar(255) DEFAULT NULL,
  `Shipping` text,
  PRIMARY KEY (`Users_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users_payconfig
-- ----------------------------
INSERT INTO `users_payconfig` VALUES ('pl2hu3uczz', null, '0', null, null, null, null, null, null, null, null, null, '1', null, null, null, null, null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for users_permit
-- ----------------------------
DROP TABLE IF EXISTS `users_permit`;
CREATE TABLE `users_permit` (
  `Permit_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Permit_Web` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Shop` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Tuan` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Survey` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Albums` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_BusinessCard` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Panoramic` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_MicroBar` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Card` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Coupon` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Stores` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Reserve` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Scratch` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Fruit` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Turntable` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Wedding` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Catering` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Estate` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Hotels` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Medical` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_Car` tinyint(1) NOT NULL DEFAULT '0',
  `Permit_IsTry` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Permit_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users_permit
-- ----------------------------

-- ----------------------------
-- Table structure for users_reserve
-- ----------------------------
DROP TABLE IF EXISTS `users_reserve`;
CREATE TABLE `users_reserve` (
  `Users_ID` varchar(10) NOT NULL,
  `Reserve_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Reserve_Title` varchar(50) DEFAULT NULL,
  `Reserve_Keywords` varchar(50) DEFAULT NULL,
  `Reserve_Address` varchar(255) DEFAULT NULL,
  `Reserve_HeaderImgPath` varchar(100) DEFAULT NULL,
  `Reserve_Telephone` varchar(255) NOT NULL,
  `Reserve_RenameTelephone` varchar(50) NOT NULL,
  `Reserve_Remark` varchar(500) DEFAULT NULL,
  `Reserve_RenameRemark` varchar(50) DEFAULT NULL,
  `Reserve_DisplayName` tinyint(1) DEFAULT '0',
  `Reserve_DisplayTelephone` tinyint(1) DEFAULT '0',
  `Reserve_DisplayReserveDate` tinyint(1) DEFAULT '0',
  `Reserve_DisplayReserveTime` tinyint(1) DEFAULT '0',
  `Reserve_JSON` text,
  `Reserve_SendSmsMobile` varchar(50) DEFAULT NULL,
  `Reserve_SendSms` tinyint(1) DEFAULT '0',
  `Reserve_PrimaryLng` varchar(50) DEFAULT NULL,
  `Reserve_PrimaryLat` varchar(50) DEFAULT NULL,
  `Reserve_Status` tinyint(1) DEFAULT '0',
  `Reserve_CreateTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`Reserve_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users_reserve
-- ----------------------------

-- ----------------------------
-- Table structure for users_roles
-- ----------------------------
DROP TABLE IF EXISTS `users_roles`;
CREATE TABLE `users_roles` (
  `id` int(64) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `role` char(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '角色名称',
  `status` int(2) DEFAULT '1' COMMENT '角色状态',
  `role_note` char(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '商家角色简明',
  `users_account` char(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '商家账号',
  `role_right` text CHARACTER SET utf8 COMMENT '角色权限',
  `create_time` int(12) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(12) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users_roles
-- ----------------------------

-- ----------------------------
-- Table structure for user_address
-- ----------------------------
DROP TABLE IF EXISTS `user_address`;
CREATE TABLE `user_address` (
  `Users_ID` varchar(10) NOT NULL DEFAULT '',
  `User_ID` varchar(10) NOT NULL DEFAULT '',
  `Address_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Address_Name` varchar(50) NOT NULL,
  `Address_Mobile` varchar(11) NOT NULL,
  `Address_Province` varchar(50) DEFAULT NULL,
  `Address_City` varchar(50) DEFAULT NULL,
  `Address_Area` varchar(50) DEFAULT NULL,
  `Address_Detailed` varchar(255) DEFAULT NULL,
  `Address_Is_Default` tinyint(1) DEFAULT '0' COMMENT '是否是默认地址',
  PRIMARY KEY (`Address_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_address
-- ----------------------------
INSERT INTO `user_address` VALUES ('pl2hu3uczz', '1', '1', '测试', '15517105580', '1', '36', '37', '打死谁谁谁', '1');
INSERT INTO `user_address` VALUES ('pl2hu3uczz', '3', '2', '孙先生', '15517105580', '2', '40', '55', '花园路', '1');
INSERT INTO `user_address` VALUES ('pl2hu3uczz', '2', '3', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '1');
INSERT INTO `user_address` VALUES ('pl2hu3uczz', '9', '4', '测试', '15517101234', '1', '36', '38', '测试街道', '1');
INSERT INTO `user_address` VALUES ('pl2hu3uczz', '12', '5', '孙测试', '15517103333', '1', '36', '38', '测试业务发放', '1');
INSERT INTO `user_address` VALUES ('pl2hu3uczz', '13', '6', '周测试', '15517104444', '2', '40', '57', '西大街', '1');
INSERT INTO `user_address` VALUES ('pl2hu3uczz', '14', '7', '孙测试', '15517106666', '2', '40', '55', '西大街', '1');
INSERT INTO `user_address` VALUES ('pl2hu3uczz', '10', '8', 'df', '1111111111', '3', '74', '1153', 'eeee', '1');

-- ----------------------------
-- Table structure for user_back_order
-- ----------------------------
DROP TABLE IF EXISTS `user_back_order`;
CREATE TABLE `user_back_order` (
  `Back_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Back_Sn` varchar(15) DEFAULT '' COMMENT '退款单号',
  `Users_ID` varchar(10) NOT NULL,
  `Biz_ID` int(10) DEFAULT '0',
  `Order_ID` int(10) NOT NULL DEFAULT '0' COMMENT '来源订单号',
  `User_ID` int(10) NOT NULL DEFAULT '0',
  `Back_Type` varchar(20) DEFAULT '',
  `Back_Json` text COMMENT '退货详情',
  `Back_Shipping` varchar(20) DEFAULT NULL,
  `Back_ShippingID` varchar(20) DEFAULT NULL,
  `Back_Status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 买家申请\r\n 1 卖家批准\r\n 2 买家发货\r\n 3 卖家收到货并确定退款金额\r\n 4 网站退款\r\n 5 卖家不同意退款\r\n 6 买家不同意退款金额',
  `Back_CreateTime` varchar(10) DEFAULT NULL,
  `Biz_IsRead` tinyint(1) DEFAULT '0' COMMENT '商加是否查看',
  `Buyer_IsRead` tinyint(1) DEFAULT '0' COMMENT '买家是否查看',
  `Back_Qty` int(10) DEFAULT '0' COMMENT '退款数量',
  `Back_Amount` decimal(10,2) DEFAULT '0.00',
  `Back_CartID` int(10) DEFAULT '0',
  `ProductID` int(10) DEFAULT '0',
  `Back_UpdateTime` int(10) DEFAULT '0',
  `Back_IsCheck` tinyint(1) DEFAULT '0',
  `Back_Account` varchar(255) DEFAULT '',
  PRIMARY KEY (`Back_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_back_order
-- ----------------------------
INSERT INTO `user_back_order` VALUES ('1', '2016061447656', 'pl2hu3uczz', '8', '44', '12', 'shop', '{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"12\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}', null, null, '4', '1465864958', '0', '0', '1', '100.00', '0', '3', '1465866838', '1', '111111111111111111');

-- ----------------------------
-- Table structure for user_back_order_detail
-- ----------------------------
DROP TABLE IF EXISTS `user_back_order_detail`;
CREATE TABLE `user_back_order_detail` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `backid` int(10) DEFAULT '0',
  `detail` longtext,
  `status` tinyint(1) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_back_order_detail
-- ----------------------------
INSERT INTO `user_back_order_detail` VALUES ('1', '1', '买家申请退款，退款金额：100，退款原因：谁谁谁谁谁谁谁谁谁', '0', '1465864958');
INSERT INTO `user_back_order_detail` VALUES ('2', '1', '卖家已收货并确定了退款金额，退款金额为：100，理由：已付款/商家未发货订单退款，系统自动完成', '1', '1465864958');
INSERT INTO `user_back_order_detail` VALUES ('3', '1', '管理员已退款给买家', '4', '1465866838');

-- ----------------------------
-- Table structure for user_card_benefits
-- ----------------------------
DROP TABLE IF EXISTS `user_card_benefits`;
CREATE TABLE `user_card_benefits` (
  `Benefits_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Benefits_Title` varchar(50) DEFAULT NULL,
  `Benefits_UserLevel` tinyint(1) DEFAULT NULL,
  `Benefits_StartTime` int(10) DEFAULT NULL,
  `Benefits_EndTime` int(10) DEFAULT NULL,
  `Benefits_Description` text,
  `Benefits_CreateTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`Benefits_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_card_benefits
-- ----------------------------

-- ----------------------------
-- Table structure for user_charge
-- ----------------------------
DROP TABLE IF EXISTS `user_charge`;
CREATE TABLE `user_charge` (
  `Item_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT NULL,
  `User_ID` int(10) DEFAULT '0',
  `Amount` decimal(10,2) DEFAULT '0.00',
  `Total` decimal(10,2) DEFAULT '0.00',
  `Operator` varchar(255) DEFAULT NULL,
  `Status` tinyint(1) DEFAULT '0',
  `CreateTime` int(10) DEFAULT '0',
  PRIMARY KEY (`Item_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of user_charge
-- ----------------------------
INSERT INTO `user_charge` VALUES ('1', 'pl2hu3uczz', '3', '11.00', '9791.00', 'admin 线下充值 +11', '1', '1465208055');
INSERT INTO `user_charge` VALUES ('2', 'pl2hu3uczz', '2', '55.00', '9998.00', 'admin 线下充值 +55', '1', '1465261881');
INSERT INTO `user_charge` VALUES ('3', 'pl2hu3uczz', '9', '500.00', '500.00', 'admin 线下充值 +500', '1', '1465694910');
INSERT INTO `user_charge` VALUES ('4', 'pl2hu3uczz', '12', '500.00', '500.00', 'admin 线下充值 +500', '1', '1465804265');
INSERT INTO `user_charge` VALUES ('5', 'pl2hu3uczz', '12', '1000.00', '1060.00', 'admin 线下充值 +1000', '1', '1465867166');
INSERT INTO `user_charge` VALUES ('6', 'pl2hu3uczz', '13', '1000.00', '1000.00', 'admin 线下充值 +1000', '1', '1465870026');
INSERT INTO `user_charge` VALUES ('7', 'pl2hu3uczz', '14', '6000.00', '6000.00', 'admin 线下充值 +6000', '1', '1465956501');
INSERT INTO `user_charge` VALUES ('8', 'pl2hu3uczz', '10', '600.00', '600.00', 'admin 线下充值 +600', '1', '1465966522');
INSERT INTO `user_charge` VALUES ('9', 'pl2hu3uczz', '10', '5400.00', '6000.00', 'admin 线下充值 +5400', '1', '1465966527');

-- ----------------------------
-- Table structure for user_config
-- ----------------------------
DROP TABLE IF EXISTS `user_config`;
CREATE TABLE `user_config` (
  `Users_ID` varchar(10) NOT NULL,
  `BusinessName` varchar(50) DEFAULT NULL,
  `IsSign` tinyint(1) DEFAULT '0',
  `SignIntegral` int(11) DEFAULT '0',
  `BusinessPhone` varchar(20) DEFAULT NULL,
  `Address` varchar(50) DEFAULT NULL,
  `UserLevel` text,
  `CardName` varchar(50) DEFAULT NULL,
  `CardLogo` varchar(50) DEFAULT NULL,
  `CardStyle` tinyint(1) DEFAULT '0',
  `CardStyleCustom` tinyint(1) DEFAULT '0',
  `CustomImgPath` varchar(50) DEFAULT NULL,
  `PrimaryLng` varchar(50) DEFAULT '0',
  `PrimaryLat` varchar(50) DEFAULT '0',
  `ExpireTime` int(10) DEFAULT '0' COMMENT '会员有效时间 单位：天',
  PRIMARY KEY (`Users_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_config
-- ----------------------------
INSERT INTO `user_config` VALUES ('pl2hu3uczz', '会员卡', '0', '0', null, null, '[{\"Name\":\"普通会员\",\"UpIntegral\":0,\"ImgPath\":\"\"}]', null, null, '0', '0', null, '0', '0', '0');

-- ----------------------------
-- Table structure for user_coupon
-- ----------------------------
DROP TABLE IF EXISTS `user_coupon`;
CREATE TABLE `user_coupon` (
  `Users_ID` varchar(10) NOT NULL,
  `Coupon_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Coupon_Keywords` varchar(50) DEFAULT NULL,
  `Coupon_Title` varchar(50) DEFAULT NULL,
  `Coupon_Subject` varchar(50) DEFAULT NULL,
  `Coupon_PhotoPath` varchar(100) DEFAULT NULL,
  `Coupon_UsedTimes` tinyint(3) DEFAULT '-1',
  `Coupon_UserLevel` tinyint(1) DEFAULT '0',
  `Coupon_StartTime` int(10) DEFAULT NULL,
  `Coupon_EndTime` int(10) DEFAULT NULL,
  `Coupon_Description` text,
  `Coupon_CreateTime` int(10) DEFAULT NULL,
  `Coupon_UseArea` tinyint(1) DEFAULT '0' COMMENT '使用范围  0 实体店  1 微商城',
  `Coupon_UseType` tinyint(1) DEFAULT '0' COMMENT '优惠方式 0 折扣  1 抵现金',
  `Coupon_Condition` int(10) DEFAULT '0' COMMENT '使用条件 如 满300才可使用',
  `Coupon_Discount` decimal(10,2) DEFAULT '0.00' COMMENT '折扣',
  `Coupon_Cash` int(10) DEFAULT '0' COMMENT '现金',
  `Biz_ID` int(10) DEFAULT '0' COMMENT '微商圈商家',
  PRIMARY KEY (`Coupon_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_coupon
-- ----------------------------

-- ----------------------------
-- Table structure for user_coupon_logs
-- ----------------------------
DROP TABLE IF EXISTS `user_coupon_logs`;
CREATE TABLE `user_coupon_logs` (
  `Logs_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `User_Name` varchar(50) DEFAULT NULL,
  `Coupon_Subject` varchar(50) DEFAULT NULL,
  `Logs_Price` decimal(11,2) DEFAULT '0.00',
  `Coupon_UsedTimes` int(11) DEFAULT '0',
  `Logs_CreateTime` int(10) DEFAULT NULL,
  `Operator_UserName` varchar(50) DEFAULT NULL,
  `Biz_ID` int(10) DEFAULT '0',
  PRIMARY KEY (`Logs_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_coupon_logs
-- ----------------------------

-- ----------------------------
-- Table structure for user_coupon_record
-- ----------------------------
DROP TABLE IF EXISTS `user_coupon_record`;
CREATE TABLE `user_coupon_record` (
  `Users_ID` varchar(10) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Record_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Coupon_ID` int(11) DEFAULT '0',
  `Coupon_UsedTimes` int(11) DEFAULT '0',
  `Record_CreateTime` int(10) DEFAULT NULL,
  `Coupon_UseArea` tinyint(1) DEFAULT '0',
  `Coupon_UseType` tinyint(1) DEFAULT '0',
  `Coupon_Condition` int(10) DEFAULT '0',
  `Coupon_Discount` decimal(10,2) DEFAULT '0.00',
  `Coupon_Cash` int(10) DEFAULT '0',
  `Coupon_StartTime` int(10) DEFAULT '0',
  `Coupon_EndTime` int(10) DEFAULT '0',
  `Biz_ID` int(10) DEFAULT '0',
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_coupon_record
-- ----------------------------

-- ----------------------------
-- Table structure for user_favourite_products
-- ----------------------------
DROP TABLE IF EXISTS `user_favourite_products`;
CREATE TABLE `user_favourite_products` (
  `FAVOURITE_ID` smallint(10) unsigned NOT NULL AUTO_INCREMENT,
  `User_ID` int(11) unsigned NOT NULL,
  `Products_ID` int(11) unsigned NOT NULL,
  `Is_Attention` tinyint(1) NOT NULL,
  PRIMARY KEY (`FAVOURITE_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_favourite_products
-- ----------------------------

-- ----------------------------
-- Table structure for user_gift
-- ----------------------------
DROP TABLE IF EXISTS `user_gift`;
CREATE TABLE `user_gift` (
  `Users_ID` varchar(10) NOT NULL,
  `Gift_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Gift_Name` varchar(50) DEFAULT NULL,
  `Gift_ImgPath` varchar(100) DEFAULT NULL,
  `Gift_Integral` int(11) DEFAULT NULL,
  `Gift_Qty` int(11) DEFAULT NULL,
  `Gift_Shipping` tinyint(3) DEFAULT '-1',
  `Gift_MyOrder` tinyint(1) DEFAULT '0',
  `Gift_BriefDescription` text,
  `Gift_CreateTime` int(10) DEFAULT NULL,
  `Biz_ID` int(10) DEFAULT '0',
  PRIMARY KEY (`Gift_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_gift
-- ----------------------------

-- ----------------------------
-- Table structure for user_gift_orders
-- ----------------------------
DROP TABLE IF EXISTS `user_gift_orders`;
CREATE TABLE `user_gift_orders` (
  `Users_ID` varchar(10) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Orders_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Gift_ID` int(11) NOT NULL,
  `Address_Province` varchar(50) DEFAULT NULL,
  `Address_City` varchar(50) DEFAULT NULL,
  `Address_Area` varchar(50) DEFAULT NULL,
  `Address_Detailed` varchar(255) DEFAULT NULL,
  `Address_Mobile` varchar(11) DEFAULT NULL,
  `Address_Name` varchar(50) DEFAULT NULL,
  `Orders_Status` tinyint(1) DEFAULT '0',
  `Orders_Shipping` varchar(50) DEFAULT NULL,
  `Orders_ShippingID` varchar(50) DEFAULT NULL,
  `Orders_FinishTime` int(10) DEFAULT NULL,
  `Orders_CreateTime` int(10) DEFAULT NULL,
  `Biz_ID` int(10) DEFAULT '0',
  PRIMARY KEY (`Orders_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_gift_orders
-- ----------------------------

-- ----------------------------
-- Table structure for user_integral_record
-- ----------------------------
DROP TABLE IF EXISTS `user_integral_record`;
CREATE TABLE `user_integral_record` (
  `Users_ID` varchar(10) NOT NULL,
  `User_ID` varchar(10) NOT NULL,
  `Record_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Record_Integral` int(11) DEFAULT '0',
  `Record_SurplusIntegral` int(11) DEFAULT '0',
  `Operator_UserName` varchar(50) DEFAULT '0',
  `Record_Type` tinyint(1) DEFAULT '0',
  `Record_Description` varchar(255) DEFAULT '0',
  `Record_CreateTime` int(10) DEFAULT NULL,
  `Action_ID` int(20) DEFAULT NULL,
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_integral_record
-- ----------------------------
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '3', '1', '11', '11', '0', '2', '购买商品送 11 个积分', '1464864035', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '3', '2', '11', '33', '', '2', '购买商品送 11 个积分', '1465177420', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '3', '11', '22', '', '2', '购买商品送 11 个积分', '1465286134', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '5', '11', '33', '', '2', '购买商品送 11 个积分', '1465370575', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '7', '11', '44', '', '2', '购买商品送 11 个积分', '1465371305', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '8', '11', '55', '', '2', '购买商品送 11 个积分', '1465371372', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '9', '11', '66', '', '2', '购买商品送 11 个积分', '1465371419', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '15', '11', '77', '', '2', '购买商品送 11 个积分', '1465372377', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '3', '16', '11', '44', '', '2', '购买商品送 11 个积分', '1465694593', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '17', '11', '88', '', '2', '购买商品送 11 个积分', '1465694594', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '18', '11', '99', '', '2', '购买商品送 11 个积分', '1465694595', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '19', '11', '110', '', '2', '购买商品送 11 个积分', '1465694595', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '3', '20', '11', '55', '', '2', '购买商品送 11 个积分', '1465694596', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '9', '21', '11', '22', '', '2', '购买商品送 11 个积分', '1465695091', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '12', '22', '11', '22', '', '2', '购买商品送 11 个积分', '1465866695', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '12', '23', '11', '33', '', '2', '购买商品送 11 个积分', '1465867497', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '12', '24', '11', '44', '', '2', '购买商品送 11 个积分', '1465867820', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '12', '26', '11', '55', '', '2', '购买商品送 11 个积分', '1465868302', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '13', '27', '11', '22', '', '2', '购买商品送 11 个积分', '1465870394', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '13', '28', '11', '33', '', '2', '购买商品送 11 个积分', '1465897613', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '14', '29', '11', '11', '0', '2', '购买商品送 11 个积分', '1465957323', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '14', '30', '11', '33', '', '2', '购买商品送 11 个积分', '1465957677', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '10', '31', '11', '11', '0', '2', '购买商品送 11 个积分', '1465966561', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '12', '32', '11', '55', '0', '2', '购买商品送 11 个积分', '1465966799', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '33', '21', '141', '', '2', '购买商品送 21 个积分', '1466043148', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '34', '21', '162', '', '2', '购买商品送 21 个积分', '1466043326', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '35', '21', '183', '', '2', '购买商品送 21 个积分', '1466043482', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '36', '21', '204', '', '2', '购买商品送 21 个积分', '1466043698', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '37', '11', '205', '', '2', '购买商品送 11 个积分', '1466044800', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '38', '11', '216', '', '2', '购买商品送 11 个积分', '1466059018', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '10', '39', '11', '33', '', '2', '购买商品送 11 个积分', '1466133959', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '10', '40', '11', '44', '', '2', '购买商品送 11 个积分', '1466147009', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '10', '41', '11', '55', '', '2', '购买商品送 11 个积分', '1466149392', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '42', '11', '227', '', '2', '购买商品送 11 个积分', '1466217412', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '43', '11', '238', '', '2', '购买商品送 11 个积分', '1466217494', null);
INSERT INTO `user_integral_record` VALUES ('pl2hu3uczz', '2', '44', '11', '249', '', '2', '购买商品送 11 个积分', '1466218164', null);

-- ----------------------------
-- Table structure for user_message
-- ----------------------------
DROP TABLE IF EXISTS `user_message`;
CREATE TABLE `user_message` (
  `Message_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Message_Title` varchar(50) DEFAULT NULL,
  `Message_StartTime` int(10) DEFAULT NULL,
  `Message_EndTime` int(10) DEFAULT NULL,
  `Message_Description` text,
  `Message_CreateTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`Message_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_message
-- ----------------------------

-- ----------------------------
-- Table structure for user_message_record
-- ----------------------------
DROP TABLE IF EXISTS `user_message_record`;
CREATE TABLE `user_message_record` (
  `Users_ID` varchar(10) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Record_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Message_ID` int(11) NOT NULL,
  `Record_CreateTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_message_record
-- ----------------------------

-- ----------------------------
-- Table structure for user_money_record
-- ----------------------------
DROP TABLE IF EXISTS `user_money_record`;
CREATE TABLE `user_money_record` (
  `Item_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT NULL,
  `User_ID` int(10) DEFAULT '0',
  `Type` tinyint(1) DEFAULT '0',
  `Amount` decimal(10,2) DEFAULT '0.00',
  `Total` decimal(10,2) DEFAULT '0.00',
  `Note` varchar(255) DEFAULT NULL,
  `CreateTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`Item_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of user_money_record
-- ----------------------------
INSERT INTO `user_money_record` VALUES ('1', 'pl2hu3uczz', '3', '0', '110.00', '9890.00', '商城购买支出 -110.00 (订单号:1)', '1464849139');
INSERT INTO `user_money_record` VALUES ('2', 'pl2hu3uczz', '2', '0', '110.00', '9890.00', '商城购买支出 -110.00 (订单号:11)', '1464940859');
INSERT INTO `user_money_record` VALUES ('3', 'pl2hu3uczz', '2', '0', '110.00', '9780.00', '商城购买支出 -110.00 (订单号:10)', '1464944135');
INSERT INTO `user_money_record` VALUES ('4', 'pl2hu3uczz', '2', '0', '110.00', '9670.00', '商城购买支出 -110.00 (订单号:12)', '1464945428');
INSERT INTO `user_money_record` VALUES ('5', 'pl2hu3uczz', '3', '0', '110.00', '9780.00', '商城购买支出 -110.00 (订单号:13)', '1465177345');
INSERT INTO `user_money_record` VALUES ('6', 'pl2hu3uczz', '3', '1', '11.00', '9791.00', 'admin 线下充值 +11', '1465208055');
INSERT INTO `user_money_record` VALUES ('7', 'pl2hu3uczz', '2', '1', '55.00', '9998.00', 'admin 线下充值 +55', '1465261881');
INSERT INTO `user_money_record` VALUES ('8', 'pl2hu3uczz', '2', '3', '27.00', '10052.00', '财务结算余额+27', '1465262461');
INSERT INTO `user_money_record` VALUES ('9', 'pl2hu3uczz', '2', '3', '27.00', '10079.00', '财务结算余额+27', '1465262724');
INSERT INTO `user_money_record` VALUES ('10', 'pl2hu3uczz', '2', '3', '27.00', '10106.00', '财务结算余额+27', '1465262810');
INSERT INTO `user_money_record` VALUES ('11', 'pl2hu3uczz', '2', '0', '10.00', '10096.00', '购买成为股东支出 -10.00', '1465289866');
INSERT INTO `user_money_record` VALUES ('12', 'pl2hu3uczz', '2', '0', '10.00', '10086.00', '购买成为股东支出 -10.00', '1465292633');
INSERT INTO `user_money_record` VALUES ('13', 'pl2hu3uczz', '2', '0', '20.00', '10066.00', '购买成为股东支出 -20.00', '1465295514');
INSERT INTO `user_money_record` VALUES ('14', 'pl2hu3uczz', '2', '0', '30.00', '10036.00', '购买成为股东支出 -30.00', '1465295845');
INSERT INTO `user_money_record` VALUES ('15', 'pl2hu3uczz', '2', '0', '10.00', '10026.00', '购买成为股东支出 -10.00', '1465347428');
INSERT INTO `user_money_record` VALUES ('16', 'pl2hu3uczz', '2', '0', '20.00', '10006.00', '购买成为股东支出 -20.00', '1465347530');
INSERT INTO `user_money_record` VALUES ('17', 'pl2hu3uczz', '2', '0', '30.00', '9976.00', '购买成为股东支出 -30.00', '1465347564');
INSERT INTO `user_money_record` VALUES ('18', 'pl2hu3uczz', '2', '0', '210.00', '9766.00', '商城购买支出 -210.00 (订单号:15)', '1465351387');
INSERT INTO `user_money_record` VALUES ('19', 'pl2hu3uczz', '2', '0', '110.00', '9656.00', '商城购买支出 -110.00 (订单号:9)', '1465371242');
INSERT INTO `user_money_record` VALUES ('20', 'pl2hu3uczz', '2', '0', '110.00', '9546.00', '商城购买支出 -110.00 (订单号:8)', '1465371258');
INSERT INTO `user_money_record` VALUES ('21', 'pl2hu3uczz', '2', '0', '110.00', '9436.00', '商城购买支出 -110.00 (订单号:7)', '1465371267');
INSERT INTO `user_money_record` VALUES ('22', 'pl2hu3uczz', '2', '0', '110.00', '9326.00', '商城购买支出 -110.00 (订单号:6)', '1465371477');
INSERT INTO `user_money_record` VALUES ('23', 'pl2hu3uczz', '2', '0', '30.00', '9296.00', '购买成为股东支出 -30.00', '1465374537');
INSERT INTO `user_money_record` VALUES ('24', 'pl2hu3uczz', '9', '1', '500.00', '500.00', 'admin 线下充值 +500', '1465694910');
INSERT INTO `user_money_record` VALUES ('25', 'pl2hu3uczz', '9', '0', '110.00', '390.00', '商城购买支出 -110.00 (订单号:16)', '1465695035');
INSERT INTO `user_money_record` VALUES ('26', 'pl2hu3uczz', '9', '0', '210.00', '180.00', '商城购买支出 -210.00 (订单号:17)', '1465714327');
INSERT INTO `user_money_record` VALUES ('27', 'pl2hu3uczz', '12', '1', '500.00', '500.00', 'admin 线下充值 +500', '1465804265');
INSERT INTO `user_money_record` VALUES ('28', 'pl2hu3uczz', '12', '0', '110.00', '390.00', '商城购买支出 -110.00 (订单号:44)', '1465813111');
INSERT INTO `user_money_record` VALUES ('29', 'pl2hu3uczz', '12', '0', '110.00', '280.00', '商城购买支出 -110.00 (订单号:43)', '1465813754');
INSERT INTO `user_money_record` VALUES ('30', 'pl2hu3uczz', '12', '0', '110.00', '170.00', '商城购买支出 -110.00 (订单号:43)', '1465813973');
INSERT INTO `user_money_record` VALUES ('31', 'pl2hu3uczz', '12', '0', '110.00', '60.00', '商城购买支出 -110.00 (订单号:43)', '1465814357');
INSERT INTO `user_money_record` VALUES ('32', 'pl2hu3uczz', '12', '1', '1000.00', '1060.00', 'admin 线下充值 +1000', '1465867166');
INSERT INTO `user_money_record` VALUES ('33', 'pl2hu3uczz', '12', '0', '110.00', '950.00', '商城购买支出 -110.00 (订单号:45)', '1465867225');
INSERT INTO `user_money_record` VALUES ('34', 'pl2hu3uczz', '12', '0', '110.00', '840.00', '商城购买支出 -110.00 (订单号:46)', '1465867792');
INSERT INTO `user_money_record` VALUES ('35', 'pl2hu3uczz', '12', '0', '110.00', '730.00', '商城购买支出 -110.00 (订单号:47)', '1465868045');
INSERT INTO `user_money_record` VALUES ('36', 'pl2hu3uczz', '12', '0', '110.00', '620.00', '商城购买支出 -110.00 (订单号:48)', '1465868279');
INSERT INTO `user_money_record` VALUES ('37', 'pl2hu3uczz', '13', '1', '1000.00', '1000.00', 'admin 线下充值 +1000', '1465870026');
INSERT INTO `user_money_record` VALUES ('38', 'pl2hu3uczz', '13', '0', '110.00', '890.00', '商城购买支出 -110.00 (订单号:49)', '1465870164');
INSERT INTO `user_money_record` VALUES ('39', 'pl2hu3uczz', '13', '0', '110.00', '780.00', '商城购买支出 -110.00 (订单号:52)', '1465897559');
INSERT INTO `user_money_record` VALUES ('40', 'pl2hu3uczz', '14', '1', '6000.00', '6000.00', 'admin 线下充值 +6000', '1465956501');
INSERT INTO `user_money_record` VALUES ('41', 'pl2hu3uczz', '14', '0', '110.00', '5890.00', '商城购买支出 -110.00 (订单号:53)', '1465957294');
INSERT INTO `user_money_record` VALUES ('42', 'pl2hu3uczz', '14', '0', '110.00', '5780.00', '商城购买支出 -110.00 (订单号:54)', '1465957608');
INSERT INTO `user_money_record` VALUES ('43', 'pl2hu3uczz', '10', '1', '600.00', '600.00', 'admin 线下充值 +600', '1465966522');
INSERT INTO `user_money_record` VALUES ('44', 'pl2hu3uczz', '10', '1', '5400.00', '6000.00', 'admin 线下充值 +5400', '1465966527');
INSERT INTO `user_money_record` VALUES ('45', 'pl2hu3uczz', '10', '0', '110.00', '5890.00', '商城购买支出 -110.00 (订单号:70)', '1465966538');
INSERT INTO `user_money_record` VALUES ('46', 'pl2hu3uczz', '12', '0', '110.00', '510.00', '商城购买支出 -110.00 (订单号:71)', '1465966764');
INSERT INTO `user_money_record` VALUES ('47', 'pl2hu3uczz', '9', '0', '20.00', '160.00', '购买成为股东支出 -20.00', '1466039343');
INSERT INTO `user_money_record` VALUES ('48', 'pl2hu3uczz', '2', '0', '211.00', '9085.00', '商城购买支出 -211.00 (订单号:72)', '1466043120');
INSERT INTO `user_money_record` VALUES ('49', 'pl2hu3uczz', '2', '0', '211.00', '8874.00', '商城购买支出 -211.00 (订单号:75)', '1466043276');
INSERT INTO `user_money_record` VALUES ('50', 'pl2hu3uczz', '2', '0', '211.00', '8663.00', '商城购买支出 -211.00 (订单号:76)', '1466043435');
INSERT INTO `user_money_record` VALUES ('51', 'pl2hu3uczz', '2', '0', '211.00', '8452.00', '商城购买支出 -211.00 (订单号:77)', '1466043657');
INSERT INTO `user_money_record` VALUES ('52', 'pl2hu3uczz', '2', '0', '110.00', '8342.00', '商城购买支出 -110.00 (订单号:81)', '1466044755');
INSERT INTO `user_money_record` VALUES ('53', 'pl2hu3uczz', '2', '0', '110.00', '8232.00', '商城购买支出 -110.00 (订单号:82)', '1466058997');
INSERT INTO `user_money_record` VALUES ('54', 'pl2hu3uczz', '10', '0', '110.00', '5780.00', '商城购买支出 -110.00 (订单号:83)', '1466133927');
INSERT INTO `user_money_record` VALUES ('55', 'pl2hu3uczz', '10', '0', '110.00', '5670.00', '商城购买支出 -110.00 (订单号:84)', '1466146957');
INSERT INTO `user_money_record` VALUES ('56', 'pl2hu3uczz', '10', '0', '110.00', '5560.00', '商城购买支出 -110.00 (订单号:85)', '1466149344');
INSERT INTO `user_money_record` VALUES ('57', 'pl2hu3uczz', '12', '0', '110.00', '400.00', '商城购买支出 -110.00 (订单号:87)', '1466154801');
INSERT INTO `user_money_record` VALUES ('58', 'pl2hu3uczz', '2', '0', '110.00', '8122.00', '商城购买支出 -110.00 (订单号:88)', '1466216577');
INSERT INTO `user_money_record` VALUES ('59', 'pl2hu3uczz', '2', '0', '110.00', '8012.00', '商城购买支出 -110.00 (订单号:89)', '1466217479');
INSERT INTO `user_money_record` VALUES ('60', 'pl2hu3uczz', '2', '0', '110.00', '7902.00', '商城购买支出 -110.00 (订单号:90)', '1466218127');
INSERT INTO `user_money_record` VALUES ('61', 'pl2hu3uczz', '2', '0', '110.00', '7792.00', '商城购买支出 -110.00 (订单号:91)', '1466222235');
INSERT INTO `user_money_record` VALUES ('62', 'pl2hu3uczz', '12', '0', '110.00', '290.00', '商城购买支出 -110.00 (订单号:92)', '1466389471');

-- ----------------------------
-- Table structure for user_operator
-- ----------------------------
DROP TABLE IF EXISTS `user_operator`;
CREATE TABLE `user_operator` (
  `Users_ID` varchar(10) NOT NULL,
  `Operator_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Operator_UserName` varchar(50) DEFAULT '0',
  `Operator_Password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Operator_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_operator
-- ----------------------------

-- ----------------------------
-- Table structure for user_order
-- ----------------------------
DROP TABLE IF EXISTS `user_order`;
CREATE TABLE `user_order` (
  `Users_ID` varchar(10) NOT NULL DEFAULT '',
  `User_ID` varchar(10) NOT NULL DEFAULT '',
  `Order_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Order_Type` varchar(20) DEFAULT NULL,
  `Address_Name` varchar(50) DEFAULT NULL,
  `Address_Mobile` varchar(11) DEFAULT NULL,
  `Address_Province` varchar(50) DEFAULT NULL,
  `Address_City` varchar(50) DEFAULT NULL,
  `Address_Area` varchar(50) DEFAULT NULL,
  `Address_Detailed` varchar(255) DEFAULT NULL,
  `Order_Remark` varchar(255) DEFAULT NULL,
  `Order_Shipping` varchar(150) DEFAULT NULL,
  `Order_ShippingID` varchar(50) DEFAULT NULL,
  `Order_CartList` text,
  `Order_TotalPrice` decimal(11,2) DEFAULT NULL,
  `Order_CreateTime` int(10) DEFAULT NULL,
  `Order_DefautlPaymentMethod` varchar(10) DEFAULT '0',
  `Order_PaymentMethod` varchar(10) DEFAULT '0',
  `Order_PaymentInfo` varchar(255) DEFAULT NULL,
  `Order_Status` tinyint(1) DEFAULT '0',
  `Order_IsRead` tinyint(1) DEFAULT '0',
  `Coupon_ID` int(10) DEFAULT '0' COMMENT '使用优惠券',
  `Coupon_Discount` decimal(10,2) DEFAULT '0.00' COMMENT '折扣',
  `Coupon_Cash` int(10) DEFAULT '0' COMMENT '所抵现金',
  `Order_TotalAmount` decimal(11,2) DEFAULT '0.00' COMMENT '使用优惠券前总价格',
  `Owner_ID` int(10) DEFAULT '0',
  `Is_Commit` tinyint(1) DEFAULT '0',
  `Is_Backup` tinyint(1) DEFAULT '0',
  `Order_Code` varchar(255) DEFAULT '',
  `Order_IsVirtual` tinyint(1) DEFAULT '0',
  `Integral_Consumption` int(6) DEFAULT '0',
  `Integral_Money` float(10,2) DEFAULT '0.00',
  `Message_Notice` tinyint(1) DEFAULT '0',
  `Order_IsRecieve` tinyint(1) DEFAULT '0',
  `deleted_at` varchar(30) DEFAULT NULL,
  `Biz_ID` int(10) DEFAULT NULL,
  `Order_NeedInvoice` tinyint(1) DEFAULT '0' COMMENT '是否需要发票',
  `Order_InvoiceInfo` varchar(100) DEFAULT NULL COMMENT '发票信息',
  `Back_Amount` decimal(10,2) DEFAULT '0.00',
  `Order_SendTime` int(10) DEFAULT '0',
  `Order_Virtual_Cards` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Order_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_order
-- ----------------------------
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '3', '1', 'shop', '孙先生', '15517105580', '2', '40', '55', '花园路', 'sssssssssssssssss', '{\"Express\":\"顺丰\",\"Price\":\"10\"}', 'wqewqew', '{&quot;1&quot;:[{&quot;ProductsName&quot;:&quot;abc&quot;,&quot;ImgPath&quot;:&quot;/uploadfiles/biz/1/image/56f5052054.jpg&quot;,&quot;ProductsPriceX&quot;:&quot;100.00&quot;,&quot;ProductsPriceY&quot;:&quot;110.00&quot;,&quot;ProductsWeight&quot;:&quot;1.00&quot;,&quot;Products_Shipping&quot;:null,&quot;Products_Business&quot;:null,&quot;Shipping_Free_Company&quot;:&quot;0&quot;,&quot;IsShippingFree&quot;:&quot;0&quot;,&quot;OwnerID&quot;:&quot;3&quot;,&quot;ProductsIsShipping&quot;:&quot;0&quot;,&quot;Qty&quot;:&quot;1&quot;,&quot;spec_list&quot;:&quot;&quot;,&quot;Property&quot;:[],&quot;ProductsProfit&quot;:50}]}', '110.00', '1464849028', '0', '余额支付', null, '4', '0', '0', '0.00', '0', '110.00', '3', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', null, '0.00', '1464863960', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '3', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{&quot;Express&quot;:&quot;顺丰&quot;,&quot;Price&quot;:&quot;10&quot;}', null, '{&quot;1&quot;:[{&quot;ProductsName&quot;:&quot;三星手机&quot;,&quot;ImgPath&quot;:&quot;/uploadfiles/biz/1/image/56f5052054.jpg&quot;,&quot;ProductsPriceX&quot;:&quot;100.00&quot;,&quot;ProductsPriceY&quot;:&quot;110.00&quot;,&quot;ProductsWeight&quot;:&quot;1.00&quot;,&quot;Products_Shipping&quot;:null,&quot;Products_Business&quot;:null,&quot;Shipping_Free_Company&quot;:&quot;0&quot;,&quot;IsShippingFree&quot;:&quot;0&quot;,&quot;OwnerID&quot;:&quot;2&quot;,&quot;ProductsIsShipping&quot;:&quot;0&quot;,&quot;Qty&quot;:&quot;1&quot;,&quot;spec_list&quot;:&quot;&quot;,&quot;Property&quot;:[],&quot;ProductsProfit&quot;:50}]}', '110.00', '1464938256', '0', '0', null, '1', '0', '0', '0.00', '0', '110.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', null, '0.00', '0', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '4', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{&quot;Express&quot;:&quot;顺丰&quot;,&quot;Price&quot;:&quot;10&quot;}', null, '{&quot;1&quot;:[{&quot;ProductsName&quot;:&quot;三星手机&quot;,&quot;ImgPath&quot;:&quot;/uploadfiles/biz/1/image/56f5052054.jpg&quot;,&quot;ProductsPriceX&quot;:&quot;100.00&quot;,&quot;ProductsPriceY&quot;:&quot;110.00&quot;,&quot;ProductsWeight&quot;:&quot;1.00&quot;,&quot;Products_Shipping&quot;:null,&quot;Products_Business&quot;:null,&quot;Shipping_Free_Company&quot;:&quot;0&quot;,&quot;IsShippingFree&quot;:&quot;0&quot;,&quot;OwnerID&quot;:&quot;2&quot;,&quot;ProductsIsShipping&quot;:&quot;0&quot;,&quot;Qty&quot;:&quot;1&quot;,&quot;spec_list&quot;:&quot;&quot;,&quot;Property&quot;:[],&quot;ProductsProfit&quot;:50}]}', '110.00', '1464938309', '0', '0', null, '1', '0', '0', '0.00', '0', '110.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', null, '0.00', '0', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '5', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{&quot;Express&quot;:&quot;顺丰&quot;,&quot;Price&quot;:&quot;10&quot;}', null, '{\"1\":[{\"ProductsName\":\"三星手机\",\"ImgPath\":\"/uploadfiles/biz/1/image/56f5052054.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"110.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"3\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"50\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":50}]}\n', '110.00', '1464938335', '0', '0', null, '1', '0', '0', '0.00', '0', '110.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', null, '0.00', '0', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '6', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"顺丰\",\"Price\":\"10\"}', '42343434', '{\"1\":[{\"ProductsName\":\"三星手机\",\"ImgPath\":\"/uploadfiles/biz/1/image/56f5052054.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"110.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"3\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"50\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":50}]}\n', '110.00', '1464938372', '', '余额支付', '', '4', '0', '0', '0.00', '0', '110.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', null, '0.00', '1465371489', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '7', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"顺丰\",\"Price\":\"10\"}', '21545', '{\"1\":[{\"ProductsName\":\"三星手机\",\"ImgPath\":\"/uploadfiles/biz/1/image/56f5052054.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"110.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"3\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"50\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":50}]}\n', '110.00', '1464938424', '', '余额支付', '', '4', '0', '0', '0.00', '0', '110.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', null, '0.00', '1465371413', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '8', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"顺丰\",\"Price\":\"10\"}', '123123', '{\"1\":[{\"ProductsName\":\"三星手机\",\"ImgPath\":\"/uploadfiles/biz/1/image/56f5052054.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"110.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"3\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"50\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":50}]}\n', '110.00', '1464938471', '', '余额支付', '', '4', '0', '0', '0.00', '0', '110.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', null, '0.00', '1465371296', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '9', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"顺丰\",\"Price\":\"10\"}', '123123', '{\"1\":[{\"ProductsName\":\"三星手机\",\"ImgPath\":\"/uploadfiles/biz/1/image/56f5052054.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"110.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"3\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"50\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":50}]}\n', '110.00', '1464938513', '', '余额支付', '', '4', '0', '0', '0.00', '0', '110.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', null, '0.00', '1465371284', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '10', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"顺丰\",\"Price\":\"10\"}', '123123', '{\"1\":[{\"ProductsName\":\"三星手机\",\"ImgPath\":\"/uploadfiles/biz/1/image/56f5052054.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"110.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"3\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"50\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":50}]}\n', '110.00', '1464938546', '0', '余额支付', null, '4', '0', '0', '0.00', '0', '110.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', null, '0.00', '1465370853', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '11', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"顺丰\",\"Price\":\"10\"}', '34223423', '{\"1\":[{\"ProductsName\":\"三星手机\",\"ImgPath\":\"/uploadfiles/biz/1/image/56f5052054.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"110.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"3\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"50\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":50}]}\n', '110.00', '1464938559', '0', '余额支付', null, '4', '0', '0', '0.00', '0', '110.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', null, '0.00', '1465286128', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '12', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"顺丰\",\"Price\":\"10\"}', '123123', '{\"1\":[{\"ProductsName\":\"三星手机\",\"ImgPath\":\"/uploadfiles/biz/1/image/56f5052054.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"110.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"3\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"50\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":50}]}\n', '110.00', '1464945377', '0', '余额支付', null, '4', '0', '0', '0.00', '0', '110.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', null, '0.00', '1465370566', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '3', '13', 'shop', '孙先生', '15517105580', '2', '40', '55', '花园路', 'ww', '{\"Express\":\"顺丰\",\"Price\":\"10\"}', '777777777777', '{\"1\":[{\"ProductsName\":\"三星手机\",\"ImgPath\":\"/uploadfiles/biz/1/image/56f5052054.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"110.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"3\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"50\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":50}]}', '110.00', '1465177071', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '3', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', '', '0.00', '1465177411', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '15', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '123123', '{\"Express\":\"顺丰\",\"Price\":\"10\"}', '23213123', '{\"2\":[{\"ProductsName\":\"苹果手机\",\"ImgPath\":\"/uploadfiles/biz/1/image/56f4fb24d9.jpg\",\"ProductsPriceX\":\"200.00\",\"ProductsPriceY\":\"210.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":100}]}', '210.00', '1465350853', '', '余额支付', '', '4', '0', '0', '0.00', '0', '200.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', '', '0.00', '1465351494', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '9', '16', 'shop', '测试', '15517101234', '1', '36', '38', '测试街道', '', '{\"Express\":\"顺丰\",\"Price\":\"10\"}', '456456456', '{\"1\":[{\"ProductsName\":\"三星手机\",\"ImgPath\":\"/uploadfiles/biz/1/image/56f5052054.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"110.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"9\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"50\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":50}]}', '110.00', '1465694977', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '9', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', '', '0.00', '1465695050', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '9', '17', 'shop', '测试', '15517101234', '1', '36', '38', '测试街道', 'qweqwe', '{\"Express\":\"顺丰\",\"Price\":\"10\"}', null, '{\"2\":[{\"ProductsName\":\"苹果手机\",\"ImgPath\":\"/uploadfiles/biz/1/image/56f4fb24d9.jpg\",\"ProductsPriceX\":\"200.00\",\"ProductsPriceY\":\"210.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"9\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":100}]}', '210.00', '1465714281', '', '余额支付', '', '2', '0', '0', '0.00', '0', '200.00', '9', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', '', '0.00', '0', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '12', '43', 'shop', '孙测试', '15517103333', '1', '36', '38', '测试业务发放', '123132', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '12312', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"12\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1465812005', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '12', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1465866683', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '12', '44', 'shop', '孙测试', '15517103333', '1', '36', '38', '测试业务发放', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', null, '[]', '110.00', '1465812528', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '12', '0', '1', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '100.00', '0', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '12', '45', 'shop', '孙测试', '15517103333', '1', '36', '38', '测试业务发放', '3454', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '12312312', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"12\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1465867057', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '12', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1465867396', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '12', '46', 'shop', '孙测试', '15517103333', '1', '36', '38', '测试业务发放', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '234234', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"12\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1465867765', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '12', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1465867805', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '12', '47', 'shop', '孙测试', '15517103333', '1', '36', '38', '测试业务发放', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '234234', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"12\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1465868022', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '12', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1465868056', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '12', '48', 'shop', '孙测试', '15517103333', '1', '36', '38', '测试业务发放', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '123123', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"12\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1465868264', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '12', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1465868290', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '13', '49', 'shop', '周测试', '15517104444', '2', '40', '57', '西大街', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '213123', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"13\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1465870064', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '13', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1465870352', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '13', '50', 'shop', '周测试', '15517104444', '2', '40', '57', '西大街', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', null, '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"13\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1465871170', '0', '0', null, '0', '0', '0', '0.00', '0', '100.00', '13', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '0', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '13', '51', 'shop', '周测试', '15517104444', '2', '40', '57', '西大街', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', null, '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"13\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1465871280', '0', '0', null, '0', '0', '0', '0.00', '0', '100.00', '13', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '0', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '13', '52', 'shop', '周测试', '15517104444', '2', '40', '57', '西大街', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '234234234', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"13\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1465874259', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '13', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1465897597', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '14', '53', 'shop', '孙测试', '15517106666', '2', '40', '55', '西大街', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '12123123', '{&quot;3&quot;:[{&quot;ProductsName&quot;:&quot;测试业务产品&quot;,&quot;ImgPath&quot;:&quot;/uploadfiles/biz/8/image/5716f5c799.jpg&quot;,&quot;ProductsPriceX&quot;:&quot;100.00&quot;,&quot;ProductsPriceY&quot;:&quot;120.00&quot;,&quot;ProductsWeight&quot;:&quot;1.00&quot;,&quot;Products_Shipping&quot;:null,&quot;Products_Business&quot;:null,&quot;Shipping_Free_Company&quot;:&quot;0&quot;,&quot;IsShippingFree&quot;:&quot;0&quot;,&quot;OwnerID&quot;:&quot;14&quot;,&quot;ProductsIsShipping&quot;:&quot;0&quot;,&quot;Qty&quot;:&quot;1&quot;,&quot;spec_list&quot;:&quot;&quot;,&quot;Property&quot;:[],&quot;ProductsProfit&quot;:80}]}', '110.00', '1465957204', '0', '余额支付', null, '4', '0', '0', '0.00', '0', '110.00', '14', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', null, '0.00', '1465957311', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '14', '54', 'shop', '孙测试', '15517106666', '2', '40', '55', '西大街', '2123', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '2123123', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"14\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1465957586', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '14', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1465957631', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '10', '70', 'shop', 'df', '1111111111', '3', '74', '1153', 'eeee', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '123123', '{&quot;3&quot;:[{&quot;ProductsName&quot;:&quot;测试业务产品&quot;,&quot;ImgPath&quot;:&quot;/uploadfiles/biz/8/image/5716f5c799.jpg&quot;,&quot;ProductsPriceX&quot;:&quot;100.00&quot;,&quot;ProductsPriceY&quot;:&quot;120.00&quot;,&quot;ProductsWeight&quot;:&quot;1.00&quot;,&quot;Products_Shipping&quot;:null,&quot;Products_Business&quot;:null,&quot;Shipping_Free_Company&quot;:&quot;0&quot;,&quot;IsShippingFree&quot;:&quot;0&quot;,&quot;OwnerID&quot;:&quot;10&quot;,&quot;ProductsIsShipping&quot;:&quot;0&quot;,&quot;Qty&quot;:&quot;1&quot;,&quot;spec_list&quot;:&quot;&quot;,&quot;Property&quot;:[],&quot;ProductsProfit&quot;:80}]}', '110.00', '1465966410', '0', '余额支付', null, '4', '0', '0', '0.00', '0', '110.00', '10', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', null, '0.00', '1465966552', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '12', '71', 'shop', '孙测试', '15517103333', '1', '36', '38', '测试业务发放', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '123123', '{&quot;3&quot;:[{&quot;ProductsName&quot;:&quot;测试业务产品&quot;,&quot;ImgPath&quot;:&quot;/uploadfiles/biz/8/image/5716f5c799.jpg&quot;,&quot;ProductsPriceX&quot;:&quot;100.00&quot;,&quot;ProductsPriceY&quot;:&quot;120.00&quot;,&quot;ProductsWeight&quot;:&quot;1.00&quot;,&quot;Products_Shipping&quot;:null,&quot;Products_Business&quot;:null,&quot;Shipping_Free_Company&quot;:&quot;0&quot;,&quot;IsShippingFree&quot;:&quot;0&quot;,&quot;OwnerID&quot;:&quot;12&quot;,&quot;ProductsIsShipping&quot;:&quot;0&quot;,&quot;Qty&quot;:&quot;1&quot;,&quot;spec_list&quot;:&quot;&quot;,&quot;Property&quot;:[],&quot;ProductsProfit&quot;:80}]}', '110.00', '1465966716', '0', '余额支付', null, '4', '0', '0', '0.00', '0', '110.00', '12', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', null, '0.00', '1465966775', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '72', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"测试快递公司\",\"Price\":\"11\"}', '23423434', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"2\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '211.00', '1466042623', '', '余额支付', '', '4', '0', '0', '0.00', '0', '800.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1466043136', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '73', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"顺丰\",\"Price\":\"12\"}', null, '{\"2\":[{\"ProductsName\":\"苹果手机\",\"ImgPath\":\"/uploadfiles/biz/1/image/56f4fb24d9.jpg\",\"ProductsPriceX\":\"200.00\",\"ProductsPriceY\":\"210.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"3\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":100}]}', '612.00', '1466042624', '0', '0', null, '0', '0', '0', '0.00', '0', '800.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', '', '0.00', '0', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '74', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"顺丰\",\"Price\":\"12\"}', null, '{\"2\":[{\"ProductsName\":\"苹果手机\",\"ImgPath\":\"/uploadfiles/biz/1/image/56f4fb24d9.jpg\",\"ProductsPriceX\":\"200.00\",\"ProductsPriceY\":\"210.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"2\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":100}],\"1\":[{\"ProductsName\":\"三星手机\",\"ImgPath\":\"/uploadfiles/biz/1/image/56f5052054.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"110.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"50\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":50}]}', '512.00', '1466042697', '0', '0', null, '0', '0', '0', '0.00', '0', '500.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '1', '0', '', '0.00', '0', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '75', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"测试快递公司\",\"Price\":\"11\"}', '123123', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"2\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '211.00', '1466043252', '', '余额支付', '', '4', '0', '0', '0.00', '0', '200.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1466043289', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '76', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"测试快递公司\",\"Price\":\"11\"}', '123123', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"2\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '211.00', '1466043402', '', '余额支付', '', '4', '0', '0', '0.00', '0', '200.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1466043461', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '77', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"测试快递公司\",\"Price\":\"11\"}', '123123', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"2\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '211.00', '1466043631', '', '余额支付', '', '4', '0', '0', '0.00', '0', '200.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1466043678', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '78', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', null, '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1466043970', '0', '0', null, '0', '0', '0', '0.00', '0', '100.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '0', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '79', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', null, '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1466044465', '0', '0', null, '0', '0', '0', '0.00', '0', '100.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '0', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '80', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', null, '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1466044480', '0', '0', null, '0', '0', '0', '0.00', '0', '100.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '0', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '81', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '123123', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1466044694', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1466044791', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '82', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '1231', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"10\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1466058976', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1466059009', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '10', '83', 'shop', 'df', '1111111111', '3', '74', '1153', 'eeee', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '234234234', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"10\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1466133854', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '10', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1466133953', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '10', '84', 'shop', 'df', '1111111111', '3', '74', '1153', 'eeee', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '7777777777777', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"10\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1466134315', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '10', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1466146992', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '10', '85', 'shop', 'df', '1111111111', '3', '74', '1153', 'eeee', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '3123123', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"10\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1466149319', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '10', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1466149385', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '12', '87', 'shop', '孙测试', '15517103333', '1', '36', '38', '测试业务发放', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '213123', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"12\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1466154795', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '12', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1466154820', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '88', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '1222222223', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1466216570', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1466217400', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '89', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '132123', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1466217471', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1466217489', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '90', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', '212', '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1466218104', '', '余额支付', '', '4', '0', '0', '0.00', '0', '100.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '1466218159', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '2', '91', 'shop', 'wwd', 'qweqwe', '2', '40', '55', 'qweq', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', null, '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"2\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1466222200', '', '余额支付', '', '2', '0', '0', '0.00', '0', '100.00', '2', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '0', null);
INSERT INTO `user_order` VALUES ('pl2hu3uczz', '12', '92', 'shop', '孙测试', '15517103333', '1', '36', '38', '测试业务发放', '', '{\"Express\":\"测试快递公司\",\"Price\":\"10\"}', null, '{\"3\":[{\"ProductsName\":\"测试业务产品\",\"ImgPath\":\"/uploadfiles/biz/8/image/5716f5c799.jpg\",\"ProductsPriceX\":\"100.00\",\"ProductsPriceY\":\"120.00\",\"ProductsWeight\":\"1.00\",\"Products_Shipping\":null,\"Products_Business\":null,\"Shipping_Free_Company\":\"0\",\"IsShippingFree\":\"0\",\"OwnerID\":\"12\",\"ProductsIsShipping\":\"0\",\"Qty\":\"1\",\"spec_list\":\"\",\"Property\":[],\"nobi_ratio\":\"20\",\"platForm_Income_Reward\":\"60\",\"area_Proxy_Reward\":\"20\",\"sha_Reward\":\"20\",\"ProductsProfit\":80}]}', '110.00', '1466389466', '', '余额支付', '', '2', '0', '0', '0.00', '0', '100.00', '12', '0', '0', '', '0', '0', '0.00', '0', '0', null, '8', '0', '', '0.00', '0', null);

-- ----------------------------
-- Table structure for user_order_commit
-- ----------------------------
DROP TABLE IF EXISTS `user_order_commit`;
CREATE TABLE `user_order_commit` (
  `Item_ID` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT '',
  `Biz_ID` int(10) DEFAULT '0',
  `User_ID` int(10) DEFAULT '0',
  `MID` varchar(50) DEFAULT '',
  `Order_ID` int(10) DEFAULT '0',
  `Product_ID` int(10) DEFAULT '0',
  `Score` int(10) DEFAULT '0',
  `Note` text,
  `CreateTime` int(10) DEFAULT '0',
  `Status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`Item_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of user_order_commit
-- ----------------------------

-- ----------------------------
-- Table structure for user_pre_order
-- ----------------------------
DROP TABLE IF EXISTS `user_pre_order`;
CREATE TABLE `user_pre_order` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usersid` varchar(50) DEFAULT '',
  `userid` int(10) DEFAULT '0',
  `pre_sn` varchar(255) DEFAULT '',
  `orderids` varchar(255) DEFAULT '',
  `total` decimal(10,2) DEFAULT '0.00',
  `createtime` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_pre_order
-- ----------------------------
INSERT INTO `user_pre_order` VALUES ('1', 'pl2hu3uczz', '12', 'PRE2016061719839', '87', '110.00', '1466154796', '2');
INSERT INTO `user_pre_order` VALUES ('2', 'pl2hu3uczz', '2', 'PRE2016061829102', '88', '110.00', '1466216570', '2');
INSERT INTO `user_pre_order` VALUES ('3', 'pl2hu3uczz', '2', 'PRE2016061864881', '89', '110.00', '1466217471', '2');
INSERT INTO `user_pre_order` VALUES ('4', 'pl2hu3uczz', '2', 'PRE2016061851347', '90', '110.00', '1466218104', '2');
INSERT INTO `user_pre_order` VALUES ('5', 'pl2hu3uczz', '2', 'PRE2016061825847', '91', '110.00', '1466222200', '2');
INSERT INTO `user_pre_order` VALUES ('6', 'pl2hu3uczz', '12', 'PRE2016062008629', '92', '110.00', '1466389466', '2');

-- ----------------------------
-- Table structure for user_profile
-- ----------------------------
DROP TABLE IF EXISTS `user_profile`;
CREATE TABLE `user_profile` (
  `Users_ID` varchar(10) NOT NULL DEFAULT '',
  `Profile_Name` tinyint(1) DEFAULT '0',
  `Profile_NameNotNull` tinyint(1) DEFAULT '0',
  `Profile_Gender` tinyint(1) DEFAULT '0',
  `Profile_GenderNotNull` tinyint(1) DEFAULT '0',
  `Profile_Age` tinyint(1) DEFAULT '0',
  `Profile_AgeNotNull` tinyint(1) DEFAULT '0',
  `Profile_NickName` tinyint(1) DEFAULT '0',
  `Profile_NickNameNotNull` tinyint(1) DEFAULT '0',
  `Profile_IDNum` tinyint(1) DEFAULT '0',
  `Profile_IDNumNotNull` tinyint(1) DEFAULT '0',
  `Profile_Telephone` tinyint(1) DEFAULT '0',
  `Profile_TelephoneNotNull` tinyint(1) DEFAULT '0',
  `Profile_Fax` tinyint(1) DEFAULT '0',
  `Profile_FaxNotNull` tinyint(1) DEFAULT '0',
  `Profile_Birthday` tinyint(1) DEFAULT '0',
  `Profile_BirthdayNotNull` tinyint(1) DEFAULT '0',
  `Profile_QQ` tinyint(1) DEFAULT '0',
  `Profile_QQNotNull` tinyint(1) DEFAULT '0',
  `Profile_Email` tinyint(1) DEFAULT '0',
  `Profile_EmailNotNull` tinyint(1) DEFAULT '0',
  `Profile_Company` tinyint(1) DEFAULT '0',
  `Profile_CompanyNotNull` tinyint(1) DEFAULT '0',
  `Profile_Area` tinyint(1) DEFAULT '0',
  `Profile_AreaNotNull` tinyint(1) DEFAULT '0',
  `Profile_Address` tinyint(1) DEFAULT '0',
  `Profile_AddressNotNull` tinyint(1) DEFAULT '0',
  `Profile_Input` text,
  `Profile_Select` text,
  PRIMARY KEY (`Users_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_profile
-- ----------------------------

-- ----------------------------
-- Table structure for user_recieve_address
-- ----------------------------
DROP TABLE IF EXISTS `user_recieve_address`;
CREATE TABLE `user_recieve_address` (
  `Users_ID` varchar(50) DEFAULT '',
  `RecieveProvince` int(10) DEFAULT '0',
  `RecieveCity` int(10) DEFAULT '0',
  `RecieveArea` int(10) DEFAULT '0',
  `RecieveAddress` varchar(255) DEFAULT '',
  `RecieveName` varchar(255) DEFAULT '',
  `RecieveMobile` varchar(55) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_recieve_address
-- ----------------------------

-- ----------------------------
-- Table structure for user_reserve
-- ----------------------------
DROP TABLE IF EXISTS `user_reserve`;
CREATE TABLE `user_reserve` (
  `Users_ID` varchar(10) NOT NULL,
  `User_ID` varchar(10) NOT NULL,
  `Reserve_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Reserve_Name` varchar(50) DEFAULT '0',
  `Reserve_Telephone` varchar(50) DEFAULT '0',
  `Reserve_Date` varchar(10) DEFAULT '0',
  `Reserve_Hour` varchar(2) DEFAULT '0',
  `Reserve_Minute` varchar(2) DEFAULT '0',
  `Reserve_JSON` text,
  `Users_ReserveID` int(11) DEFAULT NULL,
  `Reserve_CreateTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`Reserve_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_reserve
-- ----------------------------

-- ----------------------------
-- Table structure for user_yielist
-- ----------------------------
DROP TABLE IF EXISTS `user_yielist`;
CREATE TABLE `user_yielist` (
  `Yielist_ID` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号ID',
  `Users_ID` varchar(10) NOT NULL COMMENT '商家ID',
  `User_ID` int(11) DEFAULT NULL COMMENT '会员ID',
  `Procee_Date` int(11) DEFAULT NULL COMMENT '收益日期',
  `Remainder_Mon` decimal(10,2) DEFAULT '0.00' COMMENT '当前余额',
  `Yield_Rate` int(11) DEFAULT '0' COMMENT '当前收益率',
  `Procee_Mon` decimal(10,2) DEFAULT '0.00' COMMENT '收益额',
  PRIMARY KEY (`Yielist_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_yielist
-- ----------------------------

-- ----------------------------
-- Table structure for votes
-- ----------------------------
DROP TABLE IF EXISTS `votes`;
CREATE TABLE `votes` (
  `Votes_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT NULL,
  `Votes_Title` varchar(255) DEFAULT NULL,
  `Votes_Keyword` varchar(255) DEFAULT NULL,
  `Votes_Intro` text,
  `Votes_Pattern` tinyint(1) DEFAULT NULL,
  `Votes_BgColor` varchar(50) DEFAULT NULL,
  `Votes_StartTime` int(10) DEFAULT '0',
  `Votes_EndTime` int(10) DEFAULT '0',
  `Votes_TotalCounts` int(10) DEFAULT '0',
  `Votes_DayCounts` int(10) DEFAULT NULL,
  `Votes_ListType` tinyint(1) DEFAULT NULL,
  `Votes_Banner` varchar(255) DEFAULT NULL,
  `Votes_CreateTime` int(10) DEFAULT '0',
  `Votes_Votes` int(10) DEFAULT '0',
  PRIMARY KEY (`Votes_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of votes
-- ----------------------------

-- ----------------------------
-- Table structure for votes_item
-- ----------------------------
DROP TABLE IF EXISTS `votes_item`;
CREATE TABLE `votes_item` (
  `Item_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) DEFAULT NULL,
  `Votes_ID` int(10) DEFAULT '0',
  `Item_Title` varchar(255) DEFAULT NULL,
  `Item_ImgPath` varchar(255) DEFAULT NULL,
  `Item_Intro` text,
  `Item_Sorts` int(10) DEFAULT '0',
  `Item_Votes` int(10) DEFAULT '0',
  `Item_CreateTime` int(10) DEFAULT '0',
  PRIMARY KEY (`Item_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of votes_item
-- ----------------------------

-- ----------------------------
-- Table structure for votes_order
-- ----------------------------
DROP TABLE IF EXISTS `votes_order`;
CREATE TABLE `votes_order` (
  `Order_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Open_ID` varchar(50) DEFAULT NULL COMMENT '投票人',
  `Users_ID` varchar(50) DEFAULT NULL,
  `Votes_ID` int(10) DEFAULT '0',
  `Item_ID` int(10) DEFAULT '0',
  `Order_CreateTime` int(10) DEFAULT '0',
  PRIMARY KEY (`Order_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of votes_order
-- ----------------------------

-- ----------------------------
-- Table structure for web_article
-- ----------------------------
DROP TABLE IF EXISTS `web_article`;
CREATE TABLE `web_article` (
  `Article_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Article_Index` int(10) DEFAULT '0',
  `Article_Title` varchar(50) NOT NULL,
  `Column_ID` int(11) NOT NULL,
  `Article_ImgPath` varchar(255) DEFAULT NULL,
  `Article_Link` tinyint(1) DEFAULT NULL,
  `Article_LinkUrl` varchar(255) DEFAULT NULL,
  `Article_BriefDescription` varchar(255) DEFAULT NULL,
  `Article_Description` text,
  `Article_CreateTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`Article_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_article
-- ----------------------------

-- ----------------------------
-- Table structure for web_code
-- ----------------------------
DROP TABLE IF EXISTS `web_code`;
CREATE TABLE `web_code` (
  `code_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '内容ID',
  `web_id` int(10) unsigned NOT NULL COMMENT '模块ID',
  `code_type` char(10) NOT NULL DEFAULT 'array' COMMENT '数据类型:array,html,json',
  `var_name` char(20) NOT NULL COMMENT '变量名称',
  `code_info` text COMMENT '内容数据 html',
  `show_name` char(20) DEFAULT '' COMMENT '页面名称',
  `Users_ID` char(10) DEFAULT NULL,
  PRIMARY KEY (`code_id`),
  KEY `web_id` (`web_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='模块内容表';

-- ----------------------------
-- Records of web_code
-- ----------------------------

-- ----------------------------
-- Table structure for web_column
-- ----------------------------
DROP TABLE IF EXISTS `web_column`;
CREATE TABLE `web_column` (
  `Column_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Column_Name` varchar(255) NOT NULL DEFAULT '',
  `Column_ImgPath` varchar(255) DEFAULT NULL,
  `Column_Link` tinyint(1) DEFAULT '0',
  `Column_LinkUrl` varchar(255) DEFAULT NULL,
  `Column_PopSubMenu` tinyint(1) DEFAULT '0',
  `Column_NavDisplay` tinyint(1) DEFAULT '0',
  `Column_ListTypeID` tinyint(1) DEFAULT '0',
  `Column_Index` int(11) DEFAULT '0',
  `Column_Description` text,
  `Column_ParentID` int(10) DEFAULT '0',
  `Column_PageType` tinyint(1) DEFAULT '0',
  `Column_ChildTypeID` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`Column_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_column
-- ----------------------------

-- ----------------------------
-- Table structure for web_config
-- ----------------------------
DROP TABLE IF EXISTS `web_config`;
CREATE TABLE `web_config` (
  `Users_ID` varchar(10) NOT NULL,
  `SiteName` varchar(50) DEFAULT NULL,
  `CallEnable` tinyint(1) DEFAULT '0',
  `CallPhoneNumber` varchar(20) DEFAULT NULL,
  `MusicPath` varchar(100) DEFAULT NULL,
  `Animation` tinyint(1) DEFAULT '0',
  `Skin_ID` int(11) DEFAULT '1',
  `PagesShow` tinyint(1) DEFAULT '0',
  `ShowTime` int(10) DEFAULT '0',
  `PagesPic` varchar(100) DEFAULT NULL,
  `Trade_ID` int(11) DEFAULT '1',
  `Stores_Name` varchar(50) DEFAULT NULL,
  `Stores_LBS` tinyint(1) DEFAULT '0',
  `Stores_Address` varchar(100) DEFAULT NULL,
  `Stores_Description` text,
  `Stores_ImgPath` varchar(100) DEFAULT NULL,
  `Stores_PrimaryLng` varchar(20) DEFAULT NULL,
  `Stores_PrimaryLat` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Users_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_config
-- ----------------------------
INSERT INTO `web_config` VALUES ('pl2hu3uczz', '微官网', '0', null, null, '0', '1', '0', '0', null, '1', null, '0', null, null, null, null, null);

-- ----------------------------
-- Table structure for web_home
-- ----------------------------
DROP TABLE IF EXISTS `web_home`;
CREATE TABLE `web_home` (
  `Home_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Skin_ID` int(11) NOT NULL,
  `Home_Json` text,
  PRIMARY KEY (`Home_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_home
-- ----------------------------

-- ----------------------------
-- Table structure for web_skin
-- ----------------------------
DROP TABLE IF EXISTS `web_skin`;
CREATE TABLE `web_skin` (
  `Skin_ID` int(2) NOT NULL AUTO_INCREMENT,
  `Skin_Name` varchar(50) DEFAULT NULL,
  `Skin_ImgPath` varchar(50) DEFAULT NULL,
  `Trade_ID` tinyint(1) NOT NULL DEFAULT '1',
  `Skin_Json` text,
  `Skin_Status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 未启用 1 启用',
  `Skin_Index` int(10) NOT NULL DEFAULT '0' COMMENT '模板排序',
  PRIMARY KEY (`Skin_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of web_skin
-- ----------------------------
INSERT INTO `web_skin` VALUES ('1', 'Diy个性首页', '/static/member/images/web/skin/skin-001.jpg', '0', null, '1', '1');
INSERT INTO `web_skin` VALUES ('2', '风格1', '/static/member/images/web/skin/skin-002.jpg', '4', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/2/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"服务项目\",\"ImgPath\":\"/api/web/skin/2/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"210\",\"Height\":\"200\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"核心优势\",\"ImgPath\":\"/api/web/skin/2/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"210\",\"Height\":\"200\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"网上选人\",\"ImgPath\":\"/api/web/skin/2/i2.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"210\",\"Height\":\"200\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"招聘培训\",\"ImgPath\":\"/api/web/skin/2/i3.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"210\",\"Height\":\"200\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"收费标准\",\"ImgPath\":\"/api/web/skin/2/i4.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"210\",\"Height\":\"200\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/2/i5.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"210\",\"Height\":\"200\",\"NeedLink\":\"1\"}]', '1', '20');
INSERT INTO `web_skin` VALUES ('3', '风格2', '/static/member/images/web/skin/skin-003.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/3/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/3/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"210\",\"Height\":\"200\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/3/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"420\",\"Height\":\"200\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"最新菜式\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"今日推荐\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"大奖送不停\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/3/i2.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"420\",\"Height\":\"200\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/3/i3.jpg\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"210\",\"Height\":\"200\",\"NeedLink\":\"1\"}]', '1', '21');
INSERT INTO `web_skin` VALUES ('4', '风格3', '/static/member/images/web/skin/skin-004.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/4/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"260\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/4/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/4/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/4/i2.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/4/i3.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"320\",\"Height\":\"250\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/4/i4.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"320\",\"Height\":\"110\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/4/i5.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"320\",\"Height\":\"180\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/4/i6.jpg\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"160\",\"Height\":\"180\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/4/i7.jpg\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"160\",\"Height\":\"180\",\"NeedLink\":\"1\"}]', '1', '22');
INSERT INTO `web_skin` VALUES ('5', '风格4', '/static/member/images/web/skin/skin-005.jpg', '5', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/5/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/5/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/5/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"420\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"汽车美容\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"车漆快修\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"打蜡保养\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/5/i2.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"420\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/5/i3.jpg\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"金牌洗车\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"高级维护\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t10\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"音响改装\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t11\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"}]', '1', '23');
INSERT INTO `web_skin` VALUES ('6', '风格5', '/static/member/images/web/skin/skin-006.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/6/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/6/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/6/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"420\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/6/i2.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"210\",\"Height\":\"100\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/6/i3.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"210\",\"Height\":\"100\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/6/i4.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"210\",\"Height\":\"100\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/6/i5.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"320\",\"Height\":\"350\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/6/i6.jpg\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"320\",\"Height\":\"180\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/6/i7.jpg\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"320\",\"Height\":\"180\",\"NeedLink\":\"1\"}]', '1', '24');
INSERT INTO `web_skin` VALUES ('7', '风格6', '/static/member/images/web/skin/skin-007.jpg', '4', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/7/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"服务项目\",\"ImgPath\":\"/api/web/skin/7/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"180\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"核心优势\",\"ImgPath\":\"/api/web/skin/7/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"180\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"网上选人\",\"ImgPath\":\"/api/web/skin/7/i2.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"180\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"招聘培训\",\"ImgPath\":\"/api/web/skin/7/i3.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"180\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"收费标准\",\"ImgPath\":\"/api/web/skin/7/i4.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"180\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/7/i5.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"180\",\"Height\":\"120\",\"NeedLink\":\"1\"}]', '1', '25');
INSERT INTO `web_skin` VALUES ('8', '风格7', '/static/member/images/web/skin/skin-008.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/8/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"芝士焗肉酱意粉\",\"ImgPath\":\"/api/web/skin/8/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"190\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"泰皇海鲜大汇披萨\",\"ImgPath\":\"/api/web/skin/8/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"190\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"彩虹冰淇淋雪糕\",\"ImgPath\":\"/api/web/skin/8/i2.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"190\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"日式银鳕鱼焗什菌\",\"ImgPath\":\"/api/web/skin/8/i3.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"190\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"南瓜海鲜忌廉汤\",\"ImgPath\":\"/api/web/skin/8/i4.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"190\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"香草澳洲牛肋排\",\"ImgPath\":\"/api/web/skin/8/i5.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"190\",\"Height\":\"160\",\"NeedLink\":\"1\"}]', '1', '26');
INSERT INTO `web_skin` VALUES ('9', '风格8', '/static/member/images/web/skin/skin-009.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/9/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/9/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/9/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"420\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"日常优惠\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"烫染套餐\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"损伤护理\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/9/i5.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"320\",\"Height\":\"200\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/9/i6.jpg\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"320\",\"Height\":\"200\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/9/i7.jpg\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"420\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/9/i8.jpg\",\"Url\":null,\"Postion\":\"t10\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"}]', '1', '27');
INSERT INTO `web_skin` VALUES ('10', '风格9', '/static/member/images/web/skin/skin-010.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/10/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"280\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/10/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/10/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"420\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"机车外套\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"摩登潮裤\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"魅力衬衫\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/10/i2.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"310\",\"Height\":\"350\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/10/i3.jpg\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"310\",\"Height\":\"180\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/10/i4.jpg\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"310\",\"Height\":\"180\",\"NeedLink\":\"1\"}]', '1', '28');
INSERT INTO `web_skin` VALUES ('11', '风格10', '/static/member/images/web/skin/skin-011.jpg', '6', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/11/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"1\"}]', '1', '29');
INSERT INTO `web_skin` VALUES ('12', '风格11', '/static/member/images/web/skin/skin-012.jpg', '6', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/12/banner.jpg\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"720\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/12/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"300\",\"Height\":\"225\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/12/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"300\",\"Height\":\"225\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/12/i2.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"300\",\"Height\":\"225\",\"NeedLink\":\"1\"}]', '1', '30');
INSERT INTO `web_skin` VALUES ('13', '风格12', '/static/member/images/web/skin/skin-013.jpg', '4', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/13/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"最新消息\",\"ImgPath\":\"/api/web/skin/13/star.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"40\",\"Height\":\"40\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"资询热线：\",\"ImgPath\":\"/api/web/skin/13/tel.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"40\",\"Height\":\"40\",\"NeedLink\":\"0\"}]', '1', '31');
INSERT INTO `web_skin` VALUES ('14', '风格13', '/static/member/images/web/skin/skin-014.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/14/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t02\",\"Width\":\"640\",\"Height\":\"264\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/14/logo.png\",\"Url\":null,\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"84\",\"NeedLink\":\"1\"}]', '1', '32');
INSERT INTO `web_skin` VALUES ('15', '风格14', '/static/member/images/web/skin/skin-015.jpg', '6', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/15/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/15/i2.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/15/i1.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"420\",\"Height\":\"210\",\"NeedLink\":\"1\"}]', '1', '33');
INSERT INTO `web_skin` VALUES ('16', '风格15', '/static/member/images/web/skin/skin-016.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/16/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"}]', '1', '34');
INSERT INTO `web_skin` VALUES ('17', '风格16', '/static/member/images/web/skin/skin-017.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/17/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/17/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"290\",\"Height\":\"270\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/17/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"325\",\"Height\":\"128\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/17/i2.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"325\",\"Height\":\"142\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/17/i3.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"145\",\"Height\":\"136\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/17/i4.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"145\",\"Height\":\"136\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/17/i5.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"290\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/17/i6.jpg\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"324\",\"Height\":\"256\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/17/i7.jpg\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"150\",\"Height\":\"256\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/17/i8.jpg\",\"Url\":null,\"Postion\":\"t10\",\"Width\":\"140\",\"Height\":\"128\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/17/i9.jpg\",\"Url\":null,\"Postion\":\"t11\",\"Width\":\"140\",\"Height\":\"128\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/17/i10.jpg\",\"Url\":null,\"Postion\":\"t12\",\"Width\":\"192\",\"Height\":\"128\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/17/i11.jpg\",\"Url\":null,\"Postion\":\"t13\",\"Width\":\"192\",\"Height\":\"128\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/17/i12.jpg\",\"Url\":null,\"Postion\":\"t14\",\"Width\":\"132\",\"Height\":\"256\",\"NeedLink\":\"1\"}]', '1', '35');
INSERT INTO `web_skin` VALUES ('18', '风格17', '/static/member/images/web/skin/skin-018.jpg', '4', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/18/banner.jpg\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"360\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/18/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"264\",\"Height\":\"108\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/18/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"264\",\"Height\":\"108\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/18/i2.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"264\",\"Height\":\"108\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/18/i3.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"370\",\"Height\":\"334\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/18/i4.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"640\",\"Height\":\"150\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/18/i5.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"200\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/18/i6.jpg\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"110\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/18/i7.jpg\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"200\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/18/i8.jpg\",\"Url\":null,\"Postion\":\"t10\",\"Width\":\"110\",\"Height\":\"160\",\"NeedLink\":\"1\"}]', '1', '36');
INSERT INTO `web_skin` VALUES ('19', '风格18', '/static/member/images/web/skin/skin-019.jpg', '3', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/19/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/19/i0.png\",\"Url\":\"\",\"Postion\":\"t02\",\"Width\":\"320\",\"Height\":\"112\",\"NeedLink\":\"0\"}]', '1', '37');
INSERT INTO `web_skin` VALUES ('20', '风格19', '/static/member/images/web/skin/skin-020.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/20/banner.jpg\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"}]', '1', '38');
INSERT INTO `web_skin` VALUES ('21', '风格20', '/static/member/images/web/skin/skin-021.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/21/banner.jpg\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"}]', '1', '39');
INSERT INTO `web_skin` VALUES ('22', '风格21', '/static/member/images/web/skin/skin-022.jpg', '5', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api//web/skin/22/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"}]', '1', '40');
INSERT INTO `web_skin` VALUES ('23', '风格22', '/static/member/images/web/skin/skin-023.jpg', '3', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/23/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"374\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/23/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"245\",\"Height\":\"170\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/23/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"380\",\"Height\":\"170\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/23/i2.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"154\",\"Height\":\"170\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/23/i3.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"154\",\"Height\":\"170\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/23/i4.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"154\",\"Height\":\"170\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/23/i5.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"154\",\"Height\":\"170\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/23/i6.jpg\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"380\",\"Height\":\"170\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/23/i7.jpg\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"245\",\"Height\":\"170\",\"NeedLink\":\"1\"}]', '1', '41');
INSERT INTO `web_skin` VALUES ('24', '风格23', '/static/member/images/web/skin/skin-024.jpg', '6', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/24/banner.jpg\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"}]', '1', '42');
INSERT INTO `web_skin` VALUES ('25', '风格24', '/static/member/images/web/skin/skin-025.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/25/banner.jpg\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"416\",\"NeedLink\":\"1\"}]', '1', '43');
INSERT INTO `web_skin` VALUES ('26', '风格25', '/static/member/images/web/skin/skin-026.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/24/banner.jpg\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"}]', '1', '44');
INSERT INTO `web_skin` VALUES ('27', '风格26', '/static/member/images/web/skin/skin-027.jpg', '2', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/27/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"280\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"最新优惠\",\"ImgPath\":\"/api/web/skin/27/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"幸运大转盘\",\"ImgPath\":\"/api/web/skin/27/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"服务报价\",\"ImgPath\":\"/api/web/skin/27/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"海外专辑\",\"ImgPath\":\"/api/web/skin/27/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"外景作品\",\"ImgPath\":\"/api/web/skin/27/i4.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"室内主题\",\"ImgPath\":\"/api/web/skin/27/i5.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"在线预约\",\"ImgPath\":\"/api/web/skin/27/i6.png\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"聚焦团队\",\"ImgPath\":\"/api/web/skin/27/i7.png\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"客片欣赏\",\"ImgPath\":\"/api/web/skin/27/i8.png\",\"Url\":null,\"Postion\":\"t10\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"}]', '1', '45');
INSERT INTO `web_skin` VALUES ('28', '风格27', '/static/member/images/web/skin/skin-028.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/28/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"}]', '1', '46');
INSERT INTO `web_skin` VALUES ('29', '风格28', '/static/member/images/web/skin/skin-029.jpg', '6', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/29/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"}]', '1', '47');
INSERT INTO `web_skin` VALUES ('30', '风格29', '/static/member/images/web/skin/skin-030.jpg', '6', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/30/banner.jpg\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"}]', '1', '48');
INSERT INTO `web_skin` VALUES ('31', '风格30', '/static/member/images/web/skin/skin-031.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/31/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"}]', '1', '49');
INSERT INTO `web_skin` VALUES ('32', '风格31', '/static/member/images/web/skin/skin-032.jpg', '5', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/32/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"}]', '1', '50');
INSERT INTO `web_skin` VALUES ('33', '风格32', '/static/member/images/web/skin/skin-033.jpg', '4', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/33/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"}]', '1', '51');
INSERT INTO `web_skin` VALUES ('34', '风格33', '/static/member/images/web/skin/skin-034.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/34/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"325\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"粤菜菜式\",\"ImgPath\":\"/api/web/skin/34/i0.png\",\"Url\":\"\",\"Postion\":\"t02\",\"Width\":\"162\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"日本菜式\",\"ImgPath\":\"/api/web/skin/34/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"162\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"湘菜菜式\",\"ImgPath\":\"/api/web/skin/34/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"162\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"意式菜式\",\"ImgPath\":\"/api/web/skin/34/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"162\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"西式菜式\",\"ImgPath\":\"/api/web/skin/34/i4.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"162\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"甜品雪糕\",\"ImgPath\":\"/api/web/skin/34/i5.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"162\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"韩式菜式\",\"ImgPath\":\"/api/web/skin/34/i6.png\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"162\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"法式菜式\",\"ImgPath\":\"/api/web/skin/34/i7.png\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"162\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"麻辣风味\",\"ImgPath\":\"/api/web/skin/34/i8.png\",\"Url\":null,\"Postion\":\"t10\",\"Width\":\"162\",\"Height\":\"160\",\"NeedLink\":\"1\"}]', '1', '52');
INSERT INTO `web_skin` VALUES ('35', '风格34', '/static/member/images/web/skin/skin-035.jpg', '2', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/35/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"345\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"品牌服务\",\"ImgPath\":\"/api/web/skin/35/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"功能介绍\",\"ImgPath\":\"/api/web/skin/35/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"关于我们\",\"ImgPath\":\"/api/web/skin/35/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"分享交流\",\"ImgPath\":\"/api/web/skin/35/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"最新优惠\",\"ImgPath\":\"/api/web/skin/35/i4.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"招兵买马\",\"ImgPath\":\"/api/web/skin/35/i5.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"体验预约\",\"ImgPath\":\"/api/web/skin/35/i6.png\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"会员卡\",\"ImgPath\":\"/api/web/skin/35/i7.png\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/35/i8.png\",\"Url\":null,\"Postion\":\"t10\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"}]', '1', '53');
INSERT INTO `web_skin` VALUES ('36', '风格35', '/static/member/images/web/skin/skin-036.jpg', '5', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/36/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"品牌服务\",\"ImgPath\":\"/api/web/skin/36/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"会员卡\",\"ImgPath\":\"/api/web/skin/36/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"申请试驾\",\"ImgPath\":\"/api/web/skin/36/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"保养服务\",\"ImgPath\":\"/api/web/skin/36/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"售前调查\",\"ImgPath\":\"/api/web/skin/36/i4.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"功能介绍\",\"ImgPath\":\"/api/web/skin/36/i5.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"关于我们\",\"ImgPath\":\"/api/web/skin/36/i6.png\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"微服务\",\"ImgPath\":\"/api/web/skin/36/i7.png\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/36/i8.png\",\"Url\":null,\"Postion\":\"t10\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"}]', '1', '54');
INSERT INTO `web_skin` VALUES ('37', '风格36', '/static/member/images/web/skin/skin-037.jpg', '4', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/37/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"327\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"品牌服务\",\"ImgPath\":\"/api/web/skin/37/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"功能介绍\",\"ImgPath\":\"/api/web/skin/37/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"关于我们\",\"ImgPath\":\"/api/web/skin/37/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"分享交流\",\"ImgPath\":\"/api/web/skin/37/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"最新优惠\",\"ImgPath\":\"/api/web/skin/37/i4.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"招兵买马\",\"ImgPath\":\"/api/web/skin/37/i5.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"体验预约\",\"ImgPath\":\"/api/web/skin/37/i6.png\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"会员卡\",\"ImgPath\":\"/api/web/skin/37/i7.png\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/37/i8.png\",\"Url\":null,\"Postion\":\"t10\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"}]', '1', '55');
INSERT INTO `web_skin` VALUES ('38', '风格37', '/static/member/images/web/skin/skin-038.jpg', '2', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/38/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"关于我们\",\"ImgPath\":\"/api/web/skin/38/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"63\",\"Height\":\"63\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"景点介绍\",\"ImgPath\":\"/api/web/skin/38/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"63\",\"Height\":\"63\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/38/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"63\",\"Height\":\"63\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"最新消息\",\"ImgPath\":\"/api/web/skin/38/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"63\",\"Height\":\"63\",\"NeedLink\":\"1\"}]', '1', '56');
INSERT INTO `web_skin` VALUES ('39', '风格38', '/static/member/images/web/skin/skin-039.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/39/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"品牌服务\",\"ImgPath\":\"/api/web/skin/39/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"160\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"功能介绍\",\"ImgPath\":\"/api/web/skin/39/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"160\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"关于我们\",\"ImgPath\":\"/api/web/skin/39/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"160\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"体验预约\",\"ImgPath\":\"/api/web/skin/39/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"160\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"会员卡\",\"ImgPath\":\"/api/web/skin/39/i4.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"160\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/39/i5.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"160\",\"Height\":\"160\",\"NeedLink\":\"1\"}]', '1', '57');
INSERT INTO `web_skin` VALUES ('40', '风格39', '/static/member/images/web/skin/skin-040.jpg', '3', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/40/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"554\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"网站首页\",\"ImgPath\":\"/api/web/skin/40/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"184\",\"Height\":\"126\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"公司简介\",\"ImgPath\":\"/api/web/skin/40/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"184\",\"Height\":\"126\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"服务项目\",\"ImgPath\":\"/api/web/skin/40/i2.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"184\",\"Height\":\"126\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"在线咨询\",\"ImgPath\":\"/api/web/skin/40/i3.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"184\",\"Height\":\"126\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"在线预约\",\"ImgPath\":\"/api/web/skin/40/i4.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"184\",\"Height\":\"126\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"公司相册\",\"ImgPath\":\"/api/web/skin/40/i5.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"184\",\"Height\":\"126\",\"NeedLink\":\"1\"}]', '1', '58');
INSERT INTO `web_skin` VALUES ('41', '风格40', '/static/member/images/web/skin/skin-041.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/41/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"325\",\"NeedLink\":\"1\"}]', '0', '59');
INSERT INTO `web_skin` VALUES ('42', '风格41', '/static/member/images/web/skin/skin-042.jpg', '2', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/42/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"关于我们\",\"ImgPath\":\"/api/web/skin/42/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"63\",\"Height\":\"63\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"景点介绍\",\"ImgPath\":\"/api/web/skin/42/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"63\",\"Height\":\"63\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/42/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"63\",\"Height\":\"63\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"最新消息\",\"ImgPath\":\"/api/web/skin/42/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"63\",\"Height\":\"63\",\"NeedLink\":\"1\"}]', '1', '60');
INSERT INTO `web_skin` VALUES ('43', '风格42', '/static/member/images/web/skin/skin-043.jpg', '5', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/43/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"品牌服务\",\"ImgPath\":\"/api/web/skin/43/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"会员卡\",\"ImgPath\":\"/api/web/skin/43/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"申请试驾\",\"ImgPath\":\"/api/web/skin/43/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"保养服务\",\"ImgPath\":\"/api/web/skin/43/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"售前调查\",\"ImgPath\":\"/api/web/skin/43/i4.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"功能介绍\",\"ImgPath\":\"/api/web/skin/43/i5.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"关于我们\",\"ImgPath\":\"/api/web/skin/43/i6.png\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"微服务\",\"ImgPath\":\"/api/web/skin/43/i7.png\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/43/i8.png\",\"Url\":null,\"Postion\":\"t10\",\"Width\":\"73\",\"Height\":\"73\",\"NeedLink\":\"1\"}]', '1', '61');
INSERT INTO `web_skin` VALUES ('44', '风格43', '/static/member/images/web/skin/skin-044.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/44/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"}]', '1', '62');
INSERT INTO `web_skin` VALUES ('45', '风格44', '/static/member/images/web/skin/skin-045.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/45/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"260\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"酒店简介\",\"ImgPath\":\"/api/web/skin/45/i1.png\",\"Url\":\"\",\"Postion\":\"t02\",\"Width\":\"50\",\"Height\":\"50\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"房间展示\",\"ImgPath\":\"/api/web/skin/45/i2.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"50\",\"Height\":\"50\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"酒店环境\",\"ImgPath\":\"/api/web/skin/45/i3.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"50\",\"Height\":\"50\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/45/i4.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"345\",\"Height\":\"280\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"客房预订\",\"ImgPath\":\"/api/web/skin/45/i5.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"80\",\"Height\":\"80\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/45/i6.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"80\",\"Height\":\"80\",\"NeedLink\":\"1\"}]', '1', '50');
INSERT INTO `web_skin` VALUES ('46', '风格45', '/static/member/images/web/skin/skin-046.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/46/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/46/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"420\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/46/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"汽车美容\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"车漆快修\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"打蜡保养\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/46/i2.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"210\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/46/i3.jpg\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"420\",\"Height\":\"210\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"金牌洗车\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"高级维护\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t10\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"音响改装\",\"ImgPath\":\"\",\"Url\":null,\"Postion\":\"t11\",\"Width\":\"0\",\"Height\":\"0\",\"NeedLink\":\"1\"}]', '1', '64');
INSERT INTO `web_skin` VALUES ('47', '风格46', '/static/member/images/web/skin/skin-047.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/47/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"384\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"教学课程\",\"ImgPath\":\"/api/web/skin/47/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"205\",\"Height\":\"195\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"师资力量\",\"ImgPath\":\"/api/web/skin/47/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"205\",\"Height\":\"195\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"教学环境\",\"ImgPath\":\"/api/web/skin/47/i2.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"205\",\"Height\":\"195\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/47/i3.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"422\",\"Height\":\"195\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/47/i4.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"205\",\"Height\":\"195\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"公司简介\",\"ImgPath\":\"/api/web/skin/47/i5.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"205\",\"Height\":\"173\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"学院风采\",\"ImgPath\":\"/api/web/skin/47/i6.jpg\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"205\",\"Height\":\"173\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/47/i7.jpg\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"205\",\"Height\":\"173\",\"NeedLink\":\"1\"}]', '1', '69');
INSERT INTO `web_skin` VALUES ('48', '风格47', '/static/member/images/web/skin/skin-048.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/48/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"260\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/48/i1.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"428\",\"Height\":\"218\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/48/i2.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"212\",\"Height\":\"218\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/48/i3.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"212\",\"Height\":\"170\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/48/i4.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"216\",\"Height\":\"170\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/48/i5.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"212\",\"Height\":\"170\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/48/i6.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"160\",\"Height\":\"198\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/48/i7.png\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"160\",\"Height\":\"198\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/48/i8.png\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"320\",\"Height\":\"198\",\"NeedLink\":\"1\"}]', '0', '66');
INSERT INTO `web_skin` VALUES ('49', '风格48', '/static/member/images/web/skin/skin-049.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/49/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"400\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"关于我们\",\"ImgPath\":\"/api/web/skin/49/i1.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"96\",\"Height\":\"64\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"公司动态\",\"ImgPath\":\"/api/web/skin/49/i2.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"86\",\"Height\":\"64\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"优惠团购\",\"ImgPath\":\"/api/web/skin/49/i3.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"96\",\"Height\":\"64\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"旅游热线\",\"ImgPath\":\"/api/web/skin/49/i4.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"86\",\"Height\":\"64\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"相关服务\",\"ImgPath\":\"/api/web/skin/49/i5.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"96\",\"Height\":\"64\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"旅游咨询\",\"ImgPath\":\"/api/web/skin/49/i6.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"86\",\"Height\":\"64\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"收费标准\",\"ImgPath\":\"/api/web/skin/49/i7.png\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"96\",\"Height\":\"64\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/49/i8.png\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"86\",\"Height\":\"64\",\"NeedLink\":\"1\"}]', '1', '67');
INSERT INTO `web_skin` VALUES ('50', '风格49', '/static/member/images/web/skin/skin-050.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/50/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/50/i1.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"160\",\"Height\":\"150\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/50/i2.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"160\",\"Height\":\"150\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/50/i3.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"160\",\"Height\":\"150\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/50/i4.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"160\",\"Height\":\"150\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/50/i5.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"160\",\"Height\":\"150\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/50/i6.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"160\",\"Height\":\"150\",\"NeedLink\":\"1\"}]', '1', '68');
INSERT INTO `web_skin` VALUES ('51', '风格50', '/static/member/images/web/skin/skin-051.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/51/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"顶级牛排\",\"ImgPath\":\"/api/web/skin/51/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"156\",\"Height\":\"104\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"海鲜披萨\",\"ImgPath\":\"/api/web/skin/51/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"156\",\"Height\":\"104\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"草莓忌廉沙拉\",\"ImgPath\":\"/api/web/skin/51/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"156\",\"Height\":\"104\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"酱汁烤鸡\",\"ImgPath\":\"/api/web/skin/51/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"156\",\"Height\":\"104\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"培根鸡腿\",\"ImgPath\":\"/api/web/skin/51/i4.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"156\",\"Height\":\"104\",\"NeedLink\":\"1\"}]', '1', '69');
INSERT INTO `web_skin` VALUES ('52', '风格51', '/static/member/images/web/skin/skin-052.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/52/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"品牌故事\",\"ImgPath\":\"/api/web/skin/52/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"61\",\"Height\":\"58\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"花好月圆\",\"ImgPath\":\"/api/web/skin/52/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"61\",\"Height\":\"58\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"精彩花絮\",\"ImgPath\":\"/api/web/skin/52/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"61\",\"Height\":\"58\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"创始人介绍\",\"ImgPath\":\"/api/web/skin/52/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"61\",\"Height\":\"58\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"人气活动\",\"ImgPath\":\"/api/web/skin/52/i4.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"61\",\"Height\":\"58\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"在线抽奖\",\"ImgPath\":\"/api/web/skin/52/i5.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"61\",\"Height\":\"58\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"会员卡\",\"ImgPath\":\"/api/web/skin/52/i6.png\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"61\",\"Height\":\"58\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/52/i7.png\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"61\",\"Height\":\"58\",\"NeedLink\":\"1\"}]', '1', '70');
INSERT INTO `web_skin` VALUES ('53', '风格52', '/static/member/images/web/skin/skin-053.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/53/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"江南古镇\",\"ImgPath\":\"/api/web/skin/53/i1.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"156\",\"Height\":\"104\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"桂林山水\",\"ImgPath\":\"/api/web/skin/53/i2.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"156\",\"Height\":\"104\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"八达岭长城\",\"ImgPath\":\"/api/web/skin/53/i3.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"156\",\"Height\":\"104\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"马尔代夫\",\"ImgPath\":\"/api/web/skin/53/i4.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"156\",\"Height\":\"104\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"九寨沟\",\"ImgPath\":\"/api/web/skin/53/i5.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"156\",\"Height\":\"104\",\"NeedLink\":\"1\"}]', '1', '71');
INSERT INTO `web_skin` VALUES ('54', '风格53', '/static/member/images/web/skin/skin-054.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/54/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"380\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/54/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"184\",\"Height\":\"107\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/54/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"184\",\"Height\":\"107\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/54/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"184\",\"Height\":\"107\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/54/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"184\",\"Height\":\"107\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/54/i4.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"184\",\"Height\":\"107\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/54/i5.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"184\",\"Height\":\"107\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/54/i6.png\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"184\",\"Height\":\"107\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/54/i7.png\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"184\",\"Height\":\"107\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/54/i8.png\",\"Url\":null,\"Postion\":\"t10\",\"Width\":\"184\",\"Height\":\"107\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/54/i9.png\",\"Url\":null,\"Postion\":\"t11\",\"Width\":\"184\",\"Height\":\"107\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/54/i10.png\",\"Url\":null,\"Postion\":\"t12\",\"Width\":\"184\",\"Height\":\"107\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/54/i11.png\",\"Url\":null,\"Postion\":\"t13\",\"Width\":\"184\",\"Height\":\"107\",\"NeedLink\":\"1\"}]', '1', '72');
INSERT INTO `web_skin` VALUES ('55', '风格54', '/static/member/images/web/skin/skin-055.jpg', '1', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/55/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/55/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"301\",\"Height\":\"141\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/55/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"301\",\"Height\":\"141\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/55/i2.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"301\",\"Height\":\"141\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/55/i3.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"301\",\"Height\":\"141\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/55/i4.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"301\",\"Height\":\"141\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/55/i5.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"301\",\"Height\":\"141\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/55/i6.jpg\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"301\",\"Height\":\"141\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/55/i7.jpg\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"301\",\"Height\":\"141\",\"NeedLink\":\"1\"}]', '1', '2');
INSERT INTO `web_skin` VALUES ('57', '风格56', '/static/member/images/web/skin/skin-057.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/57/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"400\",\"NeedLink\":\"0\"},{\"ContentsType\":\"0\",\"Title\":\"公司简介 \",\"ImgPath\":\"/api/web/skin/57/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"75\",\"Height\":\"75\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"优秀店铺\",\"ImgPath\":\"/api/web/skin/57/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"75\",\"Height\":\"75\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"产品视频\",\"ImgPath\":\"/api/web/skin/57/i2.png\",\"Url\":null,\"Postion\":\"75\",\"Width\":\"75\",\"Height\":\"75\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"售后服务\",\"ImgPath\":\"/api/web/skin/57/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"75\",\"Height\":\"75\",\"NeedLink\":\"1\"}]', '1', '4');
INSERT INTO `web_skin` VALUES ('58', '风格57', '/static/member/images/web/skin/skin-058.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/58/banner.jpg\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"}]', '1', '5');
INSERT INTO `web_skin` VALUES ('59', '风格58', '/static/member/images/web/skin/skin-059.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/59/banner.jpg\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"}]', '1', '6');
INSERT INTO `web_skin` VALUES ('60', '风格59', '/static/member/images/web/skin/skin-060.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/60/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"0\"}]', '1', '7');
INSERT INTO `web_skin` VALUES ('61', '风格60', '/static/member/images/web/skin/skin-061.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/61/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"320\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"品牌服务\",\"ImgPath\":\"/api/web/skin/61/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"功能介绍\",\"ImgPath\":\"/api/web/skin/61/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"关于我们\",\"ImgPath\":\"/api/web/skin/61/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"分享交流\",\"ImgPath\":\"/api/web/skin/61/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"最新优惠\",\"ImgPath\":\"/api/web/skin/61/i4.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"招兵买马\",\"ImgPath\":\"/api/web/skin/61/i5.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"体验预约\",\"ImgPath\":\"/api/web/skin/61/i6.png\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"会员卡\",\"ImgPath\":\"/api/web/skin/61/i7.png\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/61/i8.png\",\"Url\":null,\"Postion\":\"t10\",\"Width\":\"84\",\"Height\":\"84\",\"NeedLink\":\"1\"}]', '1', '8');
INSERT INTO `web_skin` VALUES ('62', '风格61', '/static/member/images/web/skin/skin-062.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/62/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"品牌服务\",\"ImgPath\":\"/api/web/skin/62/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"160\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"功能介绍\",\"ImgPath\":\"/api/web/skin/62/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"160\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"关于我们\",\"ImgPath\":\"/api/web/skin/62/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"160\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"体验预约\",\"ImgPath\":\"/api/web/skin/62/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"160\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"会员卡\",\"ImgPath\":\"/api/web/skin/62/i4.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"160\",\"Height\":\"160\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/62/i5.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"160\",\"Height\":\"160\",\"NeedLink\":\"1\"}]', '1', '9');
INSERT INTO `web_skin` VALUES ('63', '风格62', '/static/member/images/web/skin/skin-063.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/63/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"332\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/63/i0.jpg\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"192\",\"Height\":\"192\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/63/i1.jpg\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"192\",\"Height\":\"192\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/63/i2.jpg\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"192\",\"Height\":\"192\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/63/i3.jpg\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"192\",\"Height\":\"192\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/63/i4.jpg\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"192\",\"Height\":\"192\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/63/i5.jpg\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"192\",\"Height\":\"192\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/63/i6.jpg\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"192\",\"Height\":\"192\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/63/i7.jpg\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"192\",\"Height\":\"192\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/63/i8.jpg\",\"Url\":null,\"Postion\":\"t10\",\"Width\":\"192\",\"Height\":\"192\",\"NeedLink\":\"1\"}]', '1', '10');
INSERT INTO `web_skin` VALUES ('64', '风格63', '/static/member/images/web/skin/skin-064.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/64/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"1010\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"优惠活动\",\"ImgPath\":\"/api/web/skin/64/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"63\",\"Height\":\"63\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"订座服务\",\"ImgPath\":\"/api/web/skin/64/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"63\",\"Height\":\"63\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"尊贵会员\",\"ImgPath\":\"/api/web/skin/64/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"63\",\"Height\":\"63\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"订单查询\",\"ImgPath\":\"/api/web/skin/64/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"63\",\"Height\":\"63\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"开始订餐\",\"ImgPath\":\"/api/web/skin/64/i4.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"168\",\"Height\":\"78\",\"NeedLink\":\"1\"}]', '1', '11');
INSERT INTO `web_skin` VALUES ('65', '风格64', '/static/member/images/web/skin/skin-065.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/65/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"360\",\"NeedLink\":\"1\"}]', '1', '12');
INSERT INTO `web_skin` VALUES ('66', '风格65', '/static/member/images/web/skin/skin-066.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/66/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"350\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"咨询中心\",\"ImgPath\":\"/api/web/skin/66/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"营销中心\",\"ImgPath\":\"/api/web/skin/66/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"服务中心\",\"ImgPath\":\"/api/web/skin/66/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"微客生活\",\"ImgPath\":\"/api/web/skin/66/i3.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"优惠活动\",\"ImgPath\":\"/api/web/skin/66/i4.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"实用工具\",\"ImgPath\":\"/api/web/skin/66/i5.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"关于我们\",\"ImgPath\":\"/api/web/skin/66/i6.png\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/66/i7.png\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"}]', '1', '13');
INSERT INTO `web_skin` VALUES ('67', '风格66', '/static/member/images/web/skin/skin-067.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/67/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"410\",\"NeedLink\":\"1\"}]', '1', '14');
INSERT INTO `web_skin` VALUES ('68', '风格67', '/static/member/images/web/skin/skin-068.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/68/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"386\",\"NeedLink\":\"1\"}]', '1', '15');
INSERT INTO `web_skin` VALUES ('69', '风格68', '/static/member/images/web/skin/skin-069.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/69/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"386\",\"NeedLink\":\"1\"}]', '1', '16');
INSERT INTO `web_skin` VALUES ('70', '风格69', '/static/member/images/web/skin/skin-070.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/70/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"350\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"联系我们\",\"ImgPath\":\"/api/web/skin/70/i7.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"关于我们\",\"ImgPath\":\"/api/web/skin/70/i6.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"实用工具\",\"ImgPath\":\"/api/web/skin/70/i5.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"优惠活动\",\"ImgPath\":\"/api/web/skin/70/i4.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"微客生活\",\"ImgPath\":\"/api/web/skin/70/i3.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"服务中心\",\"ImgPath\":\"/api/web/skin/70/i2.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"营销中心\",\"ImgPath\":\"/api/web/skin/70/i1.png\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"咨询中心\",\"ImgPath\":\"/api/web/skin/70/i0.png\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"142\",\"Height\":\"142\",\"NeedLink\":\"1\"}]', '1', '17');
INSERT INTO `web_skin` VALUES ('71', '风格70', '/static/member/images/web/skin/skin-071.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/71/banner.jpg\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"410\",\"NeedLink\":\"1\"}]', '1', '18');
INSERT INTO `web_skin` VALUES ('72', '风格71', '/static/member/images/web/skin/skin-072.jpg', '0', '[{\"ContentsType\":\"1\",\"Title\":[\"\",\"\",\"\",\"\",\"\"],\"ImgPath\":[\"/api/web/skin/72/banner.jpg\",\"\",\"\",\"\",\"\"],\"Url\":[\"\",\"\",\"\",\"\",\"\"],\"Postion\":\"t01\",\"Width\":\"640\",\"Height\":\"313\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/72/i0.png\",\"Url\":null,\"Postion\":\"t02\",\"Width\":\"190\",\"Height\":\"280\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/72/i1.png\",\"Url\":null,\"Postion\":\"t03\",\"Width\":\"190\",\"Height\":\"280\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"\",\"ImgPath\":\"/api/web/skin/72/i2.png\",\"Url\":null,\"Postion\":\"t04\",\"Width\":\"190\",\"Height\":\"280\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"家电通讯\",\"ImgPath\":\"/api/web/skin/72/m0.png\",\"Url\":null,\"Postion\":\"t05\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"电脑数码\",\"ImgPath\":\"/api/web/skin/72/m1.png\",\"Url\":null,\"Postion\":\"t06\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"男女服饰\",\"ImgPath\":\"/api/web/skin/72/m2.png\",\"Url\":null,\"Postion\":\"t07\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"时尚鞋包\",\"ImgPath\":\"/api/web/skin/72/m3.png\",\"Url\":null,\"Postion\":\"t08\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"居家生活\",\"ImgPath\":\"/api/web/skin/72/m4.png\",\"Url\":null,\"Postion\":\"t09\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"食品保健\",\"ImgPath\":\"/api/web/skin/72/m5.png\",\"Url\":null,\"Postion\":\"t10\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"图书音像\",\"ImgPath\":\"/api/web/skin/72/m6.png\",\"Url\":null,\"Postion\":\"t11\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"},{\"ContentsType\":\"0\",\"Title\":\"服务中心\",\"ImgPath\":\"/api/web/skin/72/m7.png\",\"Url\":null,\"Postion\":\"t12\",\"Width\":\"120\",\"Height\":\"120\",\"NeedLink\":\"1\"}]', '1', '19');

-- ----------------------------
-- Table structure for wechat_attention_reply
-- ----------------------------
DROP TABLE IF EXISTS `wechat_attention_reply`;
CREATE TABLE `wechat_attention_reply` (
  `Reply_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Reply_MsgType` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0文字消息\r\n1图文消息',
  `Reply_TextContents` varchar(255) DEFAULT NULL,
  `Reply_MaterialID` int(11) NOT NULL DEFAULT '0',
  `Reply_Subscribe` tinyint(1) NOT NULL DEFAULT '1',
  `Reply_MemberNotice` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`Reply_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=302 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wechat_attention_reply
-- ----------------------------
INSERT INTO `wechat_attention_reply` VALUES ('301', 'pl2hu3uczz', '0', '非常高兴认识你，新朋友！', '0', '1', '0');

-- ----------------------------
-- Table structure for wechat_keyword_reply
-- ----------------------------
DROP TABLE IF EXISTS `wechat_keyword_reply`;
CREATE TABLE `wechat_keyword_reply` (
  `Reply_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Reply_Table` varchar(20) DEFAULT '0',
  `Reply_TableID` int(11) DEFAULT '0',
  `Reply_Display` tinyint(1) DEFAULT '0',
  `Reply_Keywords` text NOT NULL,
  `Reply_PatternMethod` tinyint(1) DEFAULT '0' COMMENT '0精确匹配1模糊匹配',
  `Reply_MsgType` tinyint(1) DEFAULT '0' COMMENT '0文字消息1图文消息',
  `Reply_TextContents` text,
  `Reply_MaterialID` int(11) DEFAULT NULL,
  `Reply_CreateTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`Reply_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2391 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wechat_keyword_reply
-- ----------------------------
INSERT INTO `wechat_keyword_reply` VALUES ('2381', 'pl2hu3uczz', 'shop', '0', '0', '微商城', '0', '1', null, '2382', '1464431894');
INSERT INTO `wechat_keyword_reply` VALUES ('2382', 'pl2hu3uczz', 'user', '0', '0', '会员中心', '0', '1', null, '2383', '1464431894');
INSERT INTO `wechat_keyword_reply` VALUES ('2383', 'pl2hu3uczz', 'scratch', '0', '0', '刮刮卡', '0', '1', null, '2384', '1464431894');
INSERT INTO `wechat_keyword_reply` VALUES ('2384', 'pl2hu3uczz', 'fruit', '0', '0', '水果达人', '0', '1', null, '2385', '1464431894');
INSERT INTO `wechat_keyword_reply` VALUES ('2385', 'pl2hu3uczz', 'turntable', '0', '0', '欢乐大转盘', '0', '1', null, '2386', '1464431894');
INSERT INTO `wechat_keyword_reply` VALUES ('2386', 'pl2hu3uczz', 'battle', '0', '0', '一战到底', '0', '1', null, '2387', '1464431894');
INSERT INTO `wechat_keyword_reply` VALUES ('2387', 'pl2hu3uczz', 'votes', '0', '0', '微投票', '0', '1', null, '2388', '1464607198');
INSERT INTO `wechat_keyword_reply` VALUES ('2388', 'pl2hu3uczz', 'zhuli', '0', '0', '微助力', '0', '1', null, '2389', '1464607202');
INSERT INTO `wechat_keyword_reply` VALUES ('2389', 'pl2hu3uczz', 'games', '0', '0', '游戏中心', '0', '1', null, '2390', '1464682847');
INSERT INTO `wechat_keyword_reply` VALUES ('2390', 'pl2hu3uczz', 'web', '0', '0', '微官网', '0', '1', null, '2391', '1464683714');

-- ----------------------------
-- Table structure for wechat_material
-- ----------------------------
DROP TABLE IF EXISTS `wechat_material`;
CREATE TABLE `wechat_material` (
  `Material_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(10) NOT NULL,
  `Material_Table` varchar(20) DEFAULT '0' COMMENT '0系统1用户',
  `Material_TableID` int(11) DEFAULT '0',
  `Material_Display` tinyint(1) DEFAULT '0',
  `Material_Type` tinyint(1) DEFAULT '0',
  `Material_Json` text,
  `Material_CreateTime` int(10) DEFAULT NULL,
  PRIMARY KEY (`Material_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2392 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wechat_material
-- ----------------------------
INSERT INTO `wechat_material` VALUES ('2382', 'pl2hu3uczz', 'shop', '0', '0', '0', '{\"Title\":\"微商城\",\"ImgPath\":\"/static/api/images/cover_img/shop.jpg\",\"TextContents\":\"\",\"Url\":\"/api/pl2hu3uczz/shop/\"}', '1464431894');
INSERT INTO `wechat_material` VALUES ('2383', 'pl2hu3uczz', 'user', '0', '0', '0', '{\"Title\":\"会员中心\",\"ImgPath\":\"/static/api/images/cover_img/user.jpg\",\"TextContents\":\"\",\"Url\":\"/api/pl2hu3uczz/user/\"}', '1464431894');
INSERT INTO `wechat_material` VALUES ('2384', 'pl2hu3uczz', 'scratch', '0', '0', '0', '{\"Title\":\"刮刮卡\",\"ImgPath\":\"/static/api/images/cover_img/scratch.jpg\",\"TextContents\":\"\",\"Url\":\"/api/pl2hu3uczz/scratch/\"}', '1464431894');
INSERT INTO `wechat_material` VALUES ('2385', 'pl2hu3uczz', 'fruit', '0', '0', '0', '{\"Title\":\"水果达人\",\"ImgPath\":\"/static/api/images/cover_img/fruit.jpg\",\"TextContents\":\"\",\"Url\":\"/api/pl2hu3uczz/fruit/\"}', '1464431894');
INSERT INTO `wechat_material` VALUES ('2386', 'pl2hu3uczz', 'turntable', '0', '0', '0', '{\"Title\":\"欢乐大转盘\",\"ImgPath\":\"/static/api/images/cover_img/turntable.jpg\",\"TextContents\":\"\",\"Url\":\"/api/pl2hu3uczz/turntable/\"}', '1464431894');
INSERT INTO `wechat_material` VALUES ('2387', 'pl2hu3uczz', 'battle', '0', '0', '0', '{\"Title\":\"一战到底\",\"ImgPath\":\"/static/api/images/cover_img/battle.jpg\",\"TextContents\":\"\",\"Url\":\"/api/pl2hu3uczz/battle/\"}', '1464431894');
INSERT INTO `wechat_material` VALUES ('2388', 'pl2hu3uczz', 'votes', '0', '0', '0', '{\"Title\":\"微投票\",\"ImgPath\":\"/static/api/images/cover_img/votes.jpg\",\"TextContents\":\"\",\"Url\":\"/api/pl2hu3uczz/votes/\"}', '1464607198');
INSERT INTO `wechat_material` VALUES ('2389', 'pl2hu3uczz', 'zhuli', '0', '0', '0', '{\"Title\":\"微助力\",\"ImgPath\":\"/static/api/images/cover_img/zhuli.jpg\",\"TextContents\":\"\",\"Url\":\"/api/pl2hu3uczz/zhuli/\"}', '1464607202');
INSERT INTO `wechat_material` VALUES ('2390', 'pl2hu3uczz', 'games', '0', '0', '0', '{\"Title\":\"游戏中心\",\"ImgPath\":\"/static/api/images/cover_img/games.jpg\",\"TextContents\":\"\",\"Url\":\"/api/pl2hu3uczz/games/\"}', '1464682847');
INSERT INTO `wechat_material` VALUES ('2391', 'pl2hu3uczz', 'web', '0', '0', '0', '{\"Title\":\"微官网\",\"ImgPath\":\"/static/api/images/cover_img/web.jpg\",\"TextContents\":\"\",\"Url\":\"/api/pl2hu3uczz/web/\"}', '1464683714');

-- ----------------------------
-- Table structure for wechat_menu
-- ----------------------------
DROP TABLE IF EXISTS `wechat_menu`;
CREATE TABLE `wechat_menu` (
  `Menu_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Menu_Index` tinyint(1) DEFAULT NULL,
  `Users_ID` varchar(10) NOT NULL,
  `Menu_Name` varchar(30) NOT NULL,
  `Menu_ParentID` int(11) NOT NULL,
  `Menu_MsgType` tinyint(1) NOT NULL,
  `Menu_TextContents` text,
  `Menu_MaterialID` int(11) DEFAULT NULL,
  `Menu_Url` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`Menu_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wechat_menu
-- ----------------------------

-- ----------------------------
-- Table structure for wechat_url
-- ----------------------------
DROP TABLE IF EXISTS `wechat_url`;
CREATE TABLE `wechat_url` (
  `Users_ID` varchar(10) NOT NULL,
  `Url_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Url_Name` varchar(50) DEFAULT '0',
  `Url_Value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Url_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wechat_url
-- ----------------------------

-- ----------------------------
-- Table structure for weixin_log
-- ----------------------------
DROP TABLE IF EXISTS `weixin_log`;
CREATE TABLE `weixin_log` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `message` text,
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of weixin_log
-- ----------------------------
INSERT INTO `weixin_log` VALUES ('1', 'pl2hu3uczzaccess_token2016-06-02 10:57:08');
INSERT INTO `weixin_log` VALUES ('2', 'pl2hu3uczzaccess_token2016-06-02 15:02:36');
INSERT INTO `weixin_log` VALUES ('3', 'pl2hu3uczzaccess_token2016-06-03 09:14:32');
INSERT INTO `weixin_log` VALUES ('4', 'pl2hu3uczzaccess_token2016-06-06 09:32:12');
INSERT INTO `weixin_log` VALUES ('5', 'pl2hu3uczzaccess_token2016-06-07 08:23:54');
INSERT INTO `weixin_log` VALUES ('6', 'pl2hu3uczzaccess_token2016-06-07 10:25:41');
INSERT INTO `weixin_log` VALUES ('7', 'pl2hu3uczzaccess_token2016-06-07 14:29:33');
INSERT INTO `weixin_log` VALUES ('8', 'pl2hu3uczzaccess_token2016-06-07 16:29:54');
INSERT INTO `weixin_log` VALUES ('9', 'pl2hu3uczzaccess_token2016-06-08 08:30:26');
INSERT INTO `weixin_log` VALUES ('10', 'pl2hu3uczzaccess_token2016-06-08 11:44:45');
INSERT INTO `weixin_log` VALUES ('11', 'pl2hu3uczzaccess_token2016-06-08 16:48:40');
INSERT INTO `weixin_log` VALUES ('12', 'pl2hu3uczzaccess_token2016-06-12 09:24:44');
INSERT INTO `weixin_log` VALUES ('13', 'pl2hu3uczzaccess_token2016-06-12 14:48:04');
INSERT INTO `weixin_log` VALUES ('14', '您下单成功，支付了210.00元，您将获取佣金2.40元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [KcuolA0328rsz5]\"} _ 7e14ab0902d68a188bc941da24ca9b73');
INSERT INTO `weixin_log` VALUES ('15', 'invalid credential, access_token is invalid or not latest hint: [70601vr21]');
INSERT INTO `weixin_log` VALUES ('16', 'pl2hu3uczzaccess_token2016-06-12 16:03:22');
INSERT INTO `weixin_log` VALUES ('17', 'invalid credential, access_token is invalid or not latest hint: [nTEd.A0537vr22]');
INSERT INTO `weixin_log` VALUES ('18', 'pl2hu3uczzaccess_token2016-06-12 17:42:17');
INSERT INTO `weixin_log` VALUES ('19', 'pl2hu3uczzaccess_token2016-06-13 08:29:52');
INSERT INTO `weixin_log` VALUES ('20', 'pl2hu3uczzaccess_token2016-06-13 11:26:07');
INSERT INTO `weixin_log` VALUES ('21', 'pl2hu3uczzaccess_token2016-06-13 15:17:39');
INSERT INTO `weixin_log` VALUES ('22', 'pl2hu3uczzaccess_token2016-06-13 17:24:34');
INSERT INTO `weixin_log` VALUES ('23', 'invalid credential, access_token is invalid or not latest hint: [61Unxa0113vr21]');
INSERT INTO `weixin_log` VALUES ('24', 'pl2hu3uczzaccess_token2016-06-13 18:18:34');
INSERT INTO `weixin_log` VALUES ('25', '您下单成功，支付了110.00元，您将获取佣金2.88元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [fwxXXa0114rsz4]\"} _ 0309d53e968afd459c08d0c0fb08fe0f');
INSERT INTO `weixin_log` VALUES ('26', '您推荐的一级会员购买者一级下单成功，支付了110.00元，您将获取佣金9.60元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [WqoTJA0115rsz5]\"} _ 909d4192cde0fe64c12f0e3ae64e3023');
INSERT INTO `weixin_log` VALUES ('27', '您推荐的二级会员购买者一级下单成功，支付了110.00元，您将获取佣金3.84元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [VHuY5a0115rsz5]\"} _ edc57b4a1e3effd937bc3f6a023e3f2c');
INSERT INTO `weixin_log` VALUES ('28', 'invalid credential, access_token is invalid or not latest hint: [oLLBoa0359vr20]');
INSERT INTO `weixin_log` VALUES ('29', 'pl2hu3uczzaccess_token2016-06-13 18:39:19');
INSERT INTO `weixin_log` VALUES ('30', '您下单成功，支付了110.00元，您将获取佣金2.88元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [qUqDba0360rsz3]\"} _ 0309d53e968afd459c08d0c0fb08fe0f');
INSERT INTO `weixin_log` VALUES ('31', '您推荐的一级会员购买者一级下单成功，支付了110.00元，您将获取佣金9.60元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [Hl_pha0360rsz4]\"} _ 909d4192cde0fe64c12f0e3ae64e3023');
INSERT INTO `weixin_log` VALUES ('32', '您推荐的二级会员购买者一级下单成功，支付了110.00元，您将获取佣金3.84元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [0Q0UJa0370rsz4]\"} _ edc57b4a1e3effd937bc3f6a023e3f2c');
INSERT INTO `weixin_log` VALUES ('33', 'pl2hu3uczzaccess_token2016-06-14 08:20:20');
INSERT INTO `weixin_log` VALUES ('34', 'invalid credential, access_token is invalid or not latest hint: [nNth80227vr21]');
INSERT INTO `weixin_log` VALUES ('35', 'pl2hu3uczzaccess_token2016-06-14 09:20:28');
INSERT INTO `weixin_log` VALUES ('36', '您下单成功，支付了110.00元，您将获取佣金2.88元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [BNJqUA0228rsz4]\"} _ 0309d53e968afd459c08d0c0fb08fe0f');
INSERT INTO `weixin_log` VALUES ('37', '您推荐的一级会员购买者一级下单成功，支付了110.00元，您将获取佣金9.60元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [NbRcCA0228rsz4]\"} _ 909d4192cde0fe64c12f0e3ae64e3023');
INSERT INTO `weixin_log` VALUES ('38', '您推荐的二级会员购买者一级下单成功，支付了110.00元，您将获取佣金3.84元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [fh3OXa0229rsz3]\"} _ edc57b4a1e3effd937bc3f6a023e3f2c');
INSERT INTO `weixin_log` VALUES ('39', '您下单成功，支付了110.00元，您将获取佣金2.88元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [tL7BcA0794rsz4]\"} _ 0309d53e968afd459c08d0c0fb08fe0f');
INSERT INTO `weixin_log` VALUES ('40', '您推荐的一级会员购买者一级下单成功，支付了110.00元，您将获取佣金9.60元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [cc1iHa0794rsz4]\"} _ 909d4192cde0fe64c12f0e3ae64e3023');
INSERT INTO `weixin_log` VALUES ('41', '您推荐的二级会员购买者一级下单成功，支付了110.00元，您将获取佣金3.84元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [BknvwA0795rsz3]\"} _ edc57b4a1e3effd937bc3f6a023e3f2c');
INSERT INTO `weixin_log` VALUES ('42', '您下单成功，支付了110.00元，您将获取佣金2.88元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [H0047rsz3]\"} _ 0309d53e968afd459c08d0c0fb08fe0f');
INSERT INTO `weixin_log` VALUES ('43', '您推荐的一级会员购买者一级下单成功，支付了110.00元，您将获取佣金9.60元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [ZL1j60048rsz3]\"} _ 909d4192cde0fe64c12f0e3ae64e3023');
INSERT INTO `weixin_log` VALUES ('44', '您推荐的二级会员购买者一级下单成功，支付了110.00元，您将获取佣金3.84元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [lb_P0a0048rsz3]\"} _ edc57b4a1e3effd937bc3f6a023e3f2c');
INSERT INTO `weixin_log` VALUES ('45', '您下单成功，支付了110.00元，您将获取佣金2.88元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [.SIiqA0280rsz4]\"} _ 0309d53e968afd459c08d0c0fb08fe0f');
INSERT INTO `weixin_log` VALUES ('46', '您推荐的一级会员购买者一级下单成功，支付了110.00元，您将获取佣金9.60元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [6PAYla0280rsz5]\"} _ 909d4192cde0fe64c12f0e3ae64e3023');
INSERT INTO `weixin_log` VALUES ('47', '您推荐的二级会员购买者一级下单成功，支付了110.00元，您将获取佣金3.84元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [AdLBda0281rsz3]\"} _ edc57b4a1e3effd937bc3f6a023e3f2c');
INSERT INTO `weixin_log` VALUES ('48', 'invalid credential, access_token is invalid or not latest hint: [LhuKia0166vr21]');
INSERT INTO `weixin_log` VALUES ('49', 'pl2hu3uczzaccess_token2016-06-14 10:09:26');
INSERT INTO `weixin_log` VALUES ('50', '您下单成功，支付了110.00元，您将获取佣金2.88元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [C04.Ta0167rsz5]\"} _ f388525df2c6979126c708e6a8ea3e94');
INSERT INTO `weixin_log` VALUES ('51', 'pl2hu3uczzaccess_token2016-06-14 17:16:52');
INSERT INTO `weixin_log` VALUES ('52', '您下单成功，支付了110.00元，您将获取佣金2.88元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [XJsA4a0562rsz5]\"} _ f388525df2c6979126c708e6a8ea3e94');
INSERT INTO `weixin_log` VALUES ('53', 'pl2hu3uczzaccess_token2016-06-15 08:29:20');
INSERT INTO `weixin_log` VALUES ('54', 'pl2hu3uczzaccess_token2016-06-15 10:26:21');
INSERT INTO `weixin_log` VALUES ('55', '您下单成功，支付了110.00元，您将获取佣金2.88元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [el.I70608rsz5]\"} _ 9d4bc638a7e356a6d4a44091fc35a31c');
INSERT INTO `weixin_log` VALUES ('56', 'pl2hu3uczzaccess_token2016-06-15 12:57:11');
INSERT INTO `weixin_log` VALUES ('57', 'pl2hu3uczzaccess_token2016-06-15 16:55:28');
INSERT INTO `weixin_log` VALUES ('58', 'pl2hu3uczzaccess_token2016-06-16 08:52:41');
INSERT INTO `weixin_log` VALUES ('59', 'invalid credential, access_token is invalid or not latest hint: [fojKiA0121vr18]');
INSERT INTO `weixin_log` VALUES ('60', 'pl2hu3uczzaccess_token2016-06-16 10:12:02');
INSERT INTO `weixin_log` VALUES ('61', '您下单成功，支付了211.00元，您将获取佣金5.76元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [15kXqa0122rsz5]\"} _ e5662c3876bd8c2ef7e74d7bf87d7041');
INSERT INTO `weixin_log` VALUES ('62', '您下单成功，支付了211.00元，您将获取佣金5.76元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [MRDuDa0275rsz5]\"} _ e5662c3876bd8c2ef7e74d7bf87d7041');
INSERT INTO `weixin_log` VALUES ('63', '您下单成功，支付了211.00元，您将获取佣金5.76元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [PPqWfa0436rsz4]\"} _ e5662c3876bd8c2ef7e74d7bf87d7041');
INSERT INTO `weixin_log` VALUES ('64', '您下单成功，支付了211.00元，您将获取佣金5.76元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [Tal8LA0657rsz5]\"} _ e5662c3876bd8c2ef7e74d7bf87d7041');
INSERT INTO `weixin_log` VALUES ('65', 'invalid credential, access_token is invalid or not latest hint: [Xmy.Qa0755vr21]');
INSERT INTO `weixin_log` VALUES ('66', 'pl2hu3uczzaccess_token2016-06-16 10:39:17');
INSERT INTO `weixin_log` VALUES ('67', '您下单成功，支付了110.00元，您将获取佣金2.88元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [wqLogA0756rsz3]\"} _ e5662c3876bd8c2ef7e74d7bf87d7041');
INSERT INTO `weixin_log` VALUES ('68', 'invalid credential, access_token is invalid or not latest hint: [nLEzOa0352vr21]');
INSERT INTO `weixin_log` VALUES ('69', 'pl2hu3uczzaccess_token2016-06-16 11:05:53');
INSERT INTO `weixin_log` VALUES ('70', 'pl2hu3uczzaccess_token2016-06-16 14:17:58');
INSERT INTO `weixin_log` VALUES ('71', 'invalid credential, access_token is invalid or not latest hint: [8PSBUA0998vr19]');
INSERT INTO `weixin_log` VALUES ('72', 'pl2hu3uczzaccess_token2016-06-16 14:36:39');
INSERT INTO `weixin_log` VALUES ('73', '您下单成功，支付了110.00元，您将获取佣金2.88元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [m6DLEA0999rsz3]\"} _ e5662c3876bd8c2ef7e74d7bf87d7041');
INSERT INTO `weixin_log` VALUES ('74', 'pl2hu3uczzaccess_token2016-06-16 16:44:07');
INSERT INTO `weixin_log` VALUES ('75', 'pl2hu3uczzaccess_token2016-06-17 10:29:03');
INSERT INTO `weixin_log` VALUES ('76', 'invalid credential, access_token is invalid or not latest hint: [wP0drA0932vr19]');
INSERT INTO `weixin_log` VALUES ('77', 'pl2hu3uczzaccess_token2016-06-17 11:25:32');
INSERT INTO `weixin_log` VALUES ('78', '您下单成功，支付了110.00元，您将获取佣金0.48元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [GzrwJa0933rsz4]\"} _ edc57b4a1e3effd937bc3f6a023e3f2c');
INSERT INTO `weixin_log` VALUES ('79', 'pl2hu3uczzaccess_token2016-06-17 15:02:39');
INSERT INTO `weixin_log` VALUES ('80', '您下单成功，支付了110.00元，您将获取佣金0.48元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [k_F8ba0960rsz3]\"} _ edc57b4a1e3effd937bc3f6a023e3f2c');
INSERT INTO `weixin_log` VALUES ('81', '您下单成功，支付了110.00元，您将获取佣金1.44元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [fJwoRA0347rsz4]\"} _ edc57b4a1e3effd937bc3f6a023e3f2c');
INSERT INTO `weixin_log` VALUES ('82', 'pl2hu3uczzaccess_token2016-06-17 17:11:55');
INSERT INTO `weixin_log` VALUES ('83', '您下单成功，支付了110.00元，您将获取佣金1.44元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [R8nr.a0802rsz4]\"} _ 0309d53e968afd459c08d0c0fb08fe0f');
INSERT INTO `weixin_log` VALUES ('84', '您推荐的一级会员购买者一级下单成功，支付了110.00元，您将获取佣金4.80元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [VYu2TA0803rsz5]\"} _ 909d4192cde0fe64c12f0e3ae64e3023');
INSERT INTO `weixin_log` VALUES ('85', '您推荐的二级会员购买者一级下单成功，支付了110.00元，您将获取佣金1.92元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [lXU7ea0803rsz3]\"} _ edc57b4a1e3effd937bc3f6a023e3f2c');
INSERT INTO `weixin_log` VALUES ('86', 'pl2hu3uczzaccess_token2016-06-18 08:26:40');
INSERT INTO `weixin_log` VALUES ('87', 'pl2hu3uczzaccess_token2016-06-18 10:22:33');
INSERT INTO `weixin_log` VALUES ('88', '您下单成功，支付了110.00元，您将获取佣金1.44元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [XmUAfa0577rsz5]\"} _ e5662c3876bd8c2ef7e74d7bf87d7041');
INSERT INTO `weixin_log` VALUES ('89', '您下单成功，支付了110.00元，您将获取佣金1.44元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [Z0481rsz4]\"} _ e5662c3876bd8c2ef7e74d7bf87d7041');
INSERT INTO `weixin_log` VALUES ('90', 'invalid credential, access_token is invalid or not latest hint: [j0r2PA0128vr20]');
INSERT INTO `weixin_log` VALUES ('91', 'pl2hu3uczzaccess_token2016-06-18 10:48:49');
INSERT INTO `weixin_log` VALUES ('92', '您下单成功，支付了110.00元，您将获取佣金1.44元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [NhNiLA0129rsz3]\"} _ e5662c3876bd8c2ef7e74d7bf87d7041');
INSERT INTO `weixin_log` VALUES ('93', 'invalid credential, access_token is invalid or not latest hint: [5o8YvA0237vr22]');
INSERT INTO `weixin_log` VALUES ('94', 'pl2hu3uczzaccess_token2016-06-18 11:57:17');
INSERT INTO `weixin_log` VALUES ('95', '您下单成功，支付了110.00元，您将获取佣金1.44元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [CIFkCA0237rsz5]\"} _ e5662c3876bd8c2ef7e74d7bf87d7041');
INSERT INTO `weixin_log` VALUES ('96', 'invalid credential, access_token is invalid or not latest hint: [f4QM40176vr22]');
INSERT INTO `weixin_log` VALUES ('97', 'pl2hu3uczzaccess_token2016-06-18 12:29:36');
INSERT INTO `weixin_log` VALUES ('98', 'pl2hu3uczzaccess_token2016-06-18 16:17:12');
INSERT INTO `weixin_log` VALUES ('99', 'pl2hu3uczzaccess_token2016-06-20 10:23:26');
INSERT INTO `weixin_log` VALUES ('100', '您下单成功，支付了110.00元，您将获取佣金1.44元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [LwtThA0472rsz5]\"} _ 0309d53e968afd459c08d0c0fb08fe0f');
INSERT INTO `weixin_log` VALUES ('101', '您推荐的一级会员购买者一级下单成功，支付了110.00元，您将获取佣金4.80元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [O_yRsa0472rsz5]\"} _ 909d4192cde0fe64c12f0e3ae64e3023');
INSERT INTO `weixin_log` VALUES ('102', '您推荐的二级会员购买者一级下单成功，支付了110.00元，您将获取佣金1.92元 _ {\"errcode\":40003,\"errmsg\":\"invalid openid hint: [nPxXlA0473rsz4]\"} _ edc57b4a1e3effd937bc3f6a023e3f2c');

-- ----------------------------
-- Table structure for zhongchou_config
-- ----------------------------
DROP TABLE IF EXISTS `zhongchou_config`;
CREATE TABLE `zhongchou_config` (
  `usersid` varchar(50) DEFAULT '',
  `name` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhongchou_config
-- ----------------------------

-- ----------------------------
-- Table structure for zhongchou_prize
-- ----------------------------
DROP TABLE IF EXISTS `zhongchou_prize`;
CREATE TABLE `zhongchou_prize` (
  `prizeid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usersid` varchar(50) DEFAULT '',
  `projectid` int(10) DEFAULT '0',
  `money` decimal(10,2) DEFAULT '1.00',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `addtime` int(10) DEFAULT '0',
  `introduce` varchar(255) DEFAULT '',
  `maxtimes` int(10) DEFAULT '0',
  PRIMARY KEY (`prizeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhongchou_prize
-- ----------------------------

-- ----------------------------
-- Table structure for zhongchou_project
-- ----------------------------
DROP TABLE IF EXISTS `zhongchou_project`;
CREATE TABLE `zhongchou_project` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usersid` varchar(50) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `fromtime` int(10) DEFAULT '0',
  `totime` int(10) DEFAULT '0',
  `introduce` varchar(255) DEFAULT '',
  `description` longtext,
  `amount` decimal(10,2) DEFAULT '0.00',
  `addtime` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0' COMMENT '0 进行中 1 已过期',
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhongchou_project
-- ----------------------------

-- ----------------------------
-- Table structure for zhuli
-- ----------------------------
DROP TABLE IF EXISTS `zhuli`;
CREATE TABLE `zhuli` (
  `Users_ID` varchar(50) NOT NULL DEFAULT '',
  `Zhuli_Name` varchar(100) NOT NULL DEFAULT '',
  `Prizes` text,
  `Rules` text,
  `Awordrules` text,
  `Fromtime` int(11) DEFAULT '0',
  `Totime` int(11) DEFAULT '0',
  `Banner` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhuli
-- ----------------------------
INSERT INTO `zhuli` VALUES ('pl2hu3uczz', '微助力', null, null, null, '1464607202', '1465212002', '/static/api/images/cover_img/zhuli.jpg');

-- ----------------------------
-- Table structure for zhuli_act
-- ----------------------------
DROP TABLE IF EXISTS `zhuli_act`;
CREATE TABLE `zhuli_act` (
  `Act_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(50) NOT NULL DEFAULT '',
  `Open_ID` varchar(255) DEFAULT '',
  `User_ID` int(10) DEFAULT '0',
  `Act_Time` int(10) NOT NULL DEFAULT '0',
  `Act_Score` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Act_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhuli_act
-- ----------------------------

-- ----------------------------
-- Table structure for zhuli_record
-- ----------------------------
DROP TABLE IF EXISTS `zhuli_record`;
CREATE TABLE `zhuli_record` (
  `Record_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Act_ID` int(10) NOT NULL DEFAULT '0',
  `Users_ID` varchar(50) NOT NULL DEFAULT '',
  `Record_Score` int(10) NOT NULL DEFAULT '0',
  `Record_Time` int(10) NOT NULL DEFAULT '0',
  `User_ID` int(10) NOT NULL DEFAULT '0',
  `Open_ID` varchar(255) NOT NULL DEFAULT '',
  `Open_Info` text,
  PRIMARY KEY (`Record_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhuli_record
-- ----------------------------
