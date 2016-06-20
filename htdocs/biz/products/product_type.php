<?php
require_once('../global.php');

//获取分类列表
$lists = array();
$rsTypes = $DB->get("shop_product_type","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." order by Type_Index asc");
$lists = $DB->toArray($rsTypes);

foreach ($lists as $key=>$val){
  	$lists[$key]['Attr_Group'] = strtr($val['Attr_Group'], array("\r" => '', "\n" => ", "));
}



if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		$Flag=$DB->Del("shop_product_type","Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Type_ID=".$_GET["TypeID"]);
		if($Flag){
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
}


function get_properity_list($typeID){
	global $DB;
	$condition = "  where Type_ID=".$typeID;

	$r = $DB->Get("shop_attribute","Attr_Name",$condition);

	$property_list = $DB->toArray($r);
	
	$result = array();
	
	foreach($property_list as $key=>$item){
		$result[] = $item['Attr_Name'];
	}
	return $result;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href="/static/style.css" rel="stylesheet" type="text/css" />
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="product_type.php">属性类型</a></li>
        <li><a href="product_type_add.php">添加类型</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <div class="category">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="mytable">
          <tr bgcolor="#f5f5f5">
            <td width="50" align="center"><strong>#</strong></td>
            <td width="120" align="center"><strong>类型名称</strong></td>
            <td align="center"><strong>属性列表</strong></td>
            <td width="200" align="center"><strong>操作</strong></td>
          </tr>
       
		 <?php foreach($lists as $key=>$rsType):?>
          <tr onMouseOver="this.bgColor='#D8EDF4';" onMouseOut="this.bgColor='';">
            <td>&nbsp;&nbsp;<?=$rsType['Type_ID']?></td>
            <td align="center"><?php echo $rsType["Type_Name"]; ?></td>
            <td align="center"><?php echo implode(',',get_properity_list($rsType["Type_ID"])); ?></td>
            <td align="center" style="font-size:12px;"><a href="shop_attr.php?Type_ID=<?=$rsType["Type_ID"]?>">[属性列表]</a>&nbsp;&nbsp;<a href="product_type_edit.php?TypeID=<?php echo $rsType["Type_ID"]; ?>" title="修改">[修改]</a>&nbsp;&nbsp;<a href="?action=del&TypeID=<?php echo $rsType["Type_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};">[删除]</a></td>
          </tr>
          <?php endforeach; ?>
     
        </table>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>