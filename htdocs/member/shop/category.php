<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		$r = $DB->GetRs("shop_category","count(*) as num","where Users_ID='".$_SESSION["Users_ID"]."' and Category_ParentID=".$_GET["CategoryID"]);
		if($r["num"]>0){
			echo '<script language="javascript">alert("该栏分类下有子分类,请勿删除");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			$r = $DB->GetRs("shop_products","count(*) as num","where Users_ID='".$_SESSION["Users_ID"]."' and Products_Category=".$_GET["CategoryID"]);
			if($r["num"]>0){
				echo '<script language="javascript">alert("该分类下有商品,请勿删除");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
			}else{
				$Flag=$DB->Del("shop_category","Users_ID='".$_SESSION["Users_ID"]."' and Category_ID=".$_GET["CategoryID"]);
				if($Flag){
					echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
				}else{
					echo '<script language="javascript">alert("删除失败");history.back();</script>';
				}
				exit;
			}
		}
	}
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
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="products.php">产品列表</a></li>
        <li class="cur"><a href="category.php">产品分类</a></li>
        <li class=""><a href="commision_setting.php">佣金设置</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap"> 
      <script type='text/javascript' src='/static/js/plugin/dragsort/dragsort-0.5.1.min.js'></script>
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
      <script language="javascript">$(document).ready(shop_obj.products_category_init);</script>
      <div class="category">
        <div class="control_btn"><a href="category_add.php" class="btn_green btn_w_120">添加分类</a></div>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="mytable">
          <tr bgcolor="#f5f5f5">
            <td width="50" align="center"><strong>排序</strong></td>
            <td width="100" align="center"><strong>显示方式</strong></td>
            <td align="center"><strong>类别名称</strong></td>
            <td align="center"><strong>保证金</strong></td>
            <td width="60" align="center"><strong>操作</strong></td>
          </tr>
          <?php
$ListType=array(0=>"一行一列",1=>"一行两列");
$DB->get("shop_category","*","where Users_ID='".$_SESSION["Users_ID"]."' and Category_ParentID=0 order by Category_Index asc");
$ParentMenu=array();
$i=1;
while($rsPCategory=$DB->fetch_assoc()){
	$ParentMenu[$i]=$rsPCategory;
	$i++;
}
foreach($ParentMenu as $key=>$value){
	$DB->get("shop_category","*","where Users_ID='".$_SESSION["Users_ID"]."' and Category_ParentID=".$value["Category_ID"]." order by Category_Index asc");?>
          <tr onMouseOver="this.bgColor='#D8EDF4';" onMouseOut="this.bgColor='';" onDblClick="location.href='category_edit.php?CategoryID=<?php echo $value["Category_ID"]; ?>'">
            <td>&nbsp;&nbsp;<?php echo $key; ?></td>
            <td align="center"><?php echo $ListType[$value["Category_ListTypeID"]]; ?></td>
            <td><?php echo $value["Category_Name"]; ?></td>
            <td></td>
            <td align="center"><a href="category_edit.php?CategoryID=<?php echo $value["Category_ID"]; ?>" title="修改"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a> <a href="category.php?action=del&CategoryID=<?php echo $value["Category_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
          </tr>
          <?php
	$i=1;
	while($rsCategory=$DB->fetch_assoc()){
?>
          <tr onMouseOver="this.bgColor='#D8EDF4';" onMouseOut="this.bgColor='';" onDblClick="location.href='category_edit.php?CategoryID=<?php echo $rsCategory["Category_ID"]; ?>'">
            <td>&nbsp;&nbsp;<?php echo $key.'.'.$i; ?></td>
            <td align="center"><?php echo $ListType[$rsCategory["Category_ListTypeID"]]; ?></td>
            <td>└─<?php echo $rsCategory["Category_Name"]; ?></td>
            <td><?php echo $rsCategory["Category_Bond"]; ?></td>
            <td align="center"><a href="category_edit.php?CategoryID=<?php echo $rsCategory["Category_ID"]; ?>" title="修改"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a> <a href="category.php?action=del&CategoryID=<?php echo $rsCategory["Category_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
          </tr>
          <?php $i++;
	}
}?>
        </table>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>