CREATE TABLE IF NOT EXISTS `pintuan_attribute` (
  `Attr_ID` smallint(5) NOT NULL,
  `Users_ID` varchar(10) NOT NULL,
  `cate_id` smallint(5) NOT NULL,
  `Attr_Name` varchar(60) NOT NULL,
  `Attr_Input_Type` tinyint(1) NOT NULL,
  `Attr_Type` tinyint(1) NOT NULL,
  `Attr_Values` text NOT NULL,
  `Sort_Order` tinyint(3) NOT NULL,
  `Attr_Group` varchar(10) NOT NULL,
  `Biz_ID` int(10) DEFAULT NULL,
  PRIMARY KEY (`Attr_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;