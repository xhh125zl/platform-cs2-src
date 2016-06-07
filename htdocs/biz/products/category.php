<?php
require_once('../global.php');

if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		$r = $DB->GetRs("biz_category","count(*) as num","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Category_ParentID=".$_GET["CategoryID"]);
		if($r["num"]>0){
			echo '<script language="javascript">alert("该栏分类下有子分类,请勿删除");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			$r = $DB->GetRs("shop_products","count(*) as num","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Products_BizCategory=".$_GET["CategoryID"]);
			if($r["num"]>0){
				echo '<script language="javascript">alert("该分类下有商品,请勿删除");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
			}else{
				$Flag=$DB->Del("biz_category"," Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Category_ID=".$_GET["CategoryID"]);
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
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="category.php">自定义分类</a></li>
        <li><a href="category_add.php">添加分类</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap"> 
      <script type='text/javascript' src='/static/js/plugin/dragsort/dragsort-0.5.1.min.js'></script>
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
      <div class="category">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="mytable">
          <tr bgcolor="#f5f5f5">
            <td width="50" align="center"><strong>排序</strong></td>
            <td align="center"><strong>类别名称</strong></td>
            <td width="60" align="center"><strong>操作</strong></td>
          </tr>
          <?php
			$DB->get("biz_category","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Category_ParentID=0 order by Category_Index asc,Category_ID asc");
			$ParentMenu=array();
			while($rsPCategory=$DB->fetch_assoc()){
				$ParentMenu[]=$rsPCategory;
			}
			foreach($ParentMenu as $key=>$value){
				$DB->get("biz_category","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Category_ParentID=".$value["Category_ID"]." order by Category_Index asc,Category_ID asc");
		?>
          <tr>
            <td>&nbsp;&nbsp;<?php echo $key+1; ?></td>
            <td><?php echo $value["Category_Name"]; ?></td>
            <td align="center"><a href="category_edit.php?CategoryID=<?php echo $value["Category_ID"]; ?>" title="修改"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a> <a href="category.php?action=del&CategoryID=<?php echo $value["Category_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
          </tr>
          <?php
		  $i=0;
			while($rsCategory=$DB->fetch_assoc()){
				$i++;
		  ?>
          <tr>
            <td>&nbsp;&nbsp;<?php echo ($key+1).'.'.$i; ?></td>
            <td>└─<?php echo $rsCategory["Category_Name"]; ?></td>
            <td align="center"><a href="category_edit.php?CategoryID=<?php echo $rsCategory["Category_ID"]; ?>" title="修改"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a> <a href="category.php?action=del&CategoryID=<?php echo $rsCategory["Category_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
          </tr>
          <?php
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