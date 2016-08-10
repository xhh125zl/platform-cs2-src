<?php  
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

if(isset($_GET["action"]))
{
	if($_GET["action"]=="del")
	{
		$Flag=$DB->Del("cloud_slide","Users_ID='".$_SESSION["Users_ID"]."' and id=".$_GET["id"]);
		if($Flag){
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
}

$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";

$condition .= " order by slide_index asc";
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>微易宝</title>
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
        <li class="cur"><a href="slide_list.php">首页幻灯片</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap"> 
      <script language="javascript">$(document).ready(shop_obj.products_list_init);</script>
      <div class="control_btn">
      <a href="slide_add.php" class="btn_green btn_w_120">添加幻灯</a>
      </div>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%" nowrap="nowrap">排序</td>
            <td width="20%" nowrap="nowrap">标题</td>
            <td width="20%" nowrap="nowrap">URL链接</td>
            <td width="30%" nowrap="nowrap">图片</td>
            <td width="20%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php 
		  $lists = array();
		  $DB->getPage("cloud_slide","*",$condition,10);
		  
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }
		  foreach($lists as $k=>$slide){
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $slide["slide_index"] ?></td>
            <td><?php echo $slide["slide_title"] ?></td>
			<td><?php echo $slide["slide_link"] ?></td>
            <td nowrap="nowrap"><?php echo empty($slide["slide_img"])?'':'<img src="'.$slide["slide_img"].'" class="proimg" />'; ?></td>
            <td class="last" nowrap="nowrap">
			    <a href="slide_edit.php?id=<?php echo $slide["id"] ?>">[修改]</a>
			    <a href="slide_list.php?&action=del&id=<?php echo $slide["id"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};">[删除]</a>
			</td>
          </tr>
          <?php }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>