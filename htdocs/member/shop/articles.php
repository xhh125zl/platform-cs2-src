<?php


if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}


$Keywords=empty($_REQUEST["Keywords"])?"":trim($_REQUEST["Keywords"]);
$condition = "where Users_ID='".$_SESSION["Users_ID"]."' ";
if($Keywords){
	$condition .= " and Article_Title like '%".$Keywords."%'";
}


$STATUS=array('<img src="/static/member/images/shop/no.gif" />','<img src="/static/member/images/shop/yes.gif" />');

$TYPE = array(1=>'常见问题',2=>'帮助中心');

//删除开始
if(!empty($_GET["action"])&&$_GET["action"]=="Del"){
	$ID=empty($_GET["ID"]) ? "0" : $_GET["ID"];
	mysql_query("delete from shop_articles where Article_ID='".$ID."'");
	echo "<script language='javascript'>alert('删除成功！');window.open('articles.php','_self');</script>";
	exit();
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/shop.js'></script>
<script type="text/javascript">
$(document).ready(function(){
	shop_obj.article_init()
});
</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="articles.php">文章管理</a></li>
		<li><a href="articles_category.php">分类管理</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <div class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="">
        关键字：<input name="Keywords" value="<?php echo $Keywords;?>" type="text" class="form_input" size="30" notnull/>
        <input type="submit" class="search_btn" value="搜索" />
      </form>
      <br/>
      <div class="b10"></div>
      <div class="control_btn"><a href="article_add.php" class="btn_green btn_w_120">添加文章</a></div>
      <div class="b10"></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
          	<td nowrap="nowrap" width="6%">ID</td>         
            <td nowrap="nowrap">标题</td>
            <td nowrap="nowrap" width="15%">所属分类</td>
            <td nowrap="nowrap" width="10%">显示</td>
            <td nowrap="nowrap" width="15%">添加时间</td>
            <td nowrap="nowrap" width="8%" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php 
			$lists = array();
			$DB->getPage("shop_articles","*",$condition." order by Article_ID desc",10);
			while($r=$DB->fetch_assoc()){
				$lists[] = $r;
			}
			foreach($lists as $t){
				$categoryname = '';
				$category = $DB->GetRs("shop_articles_category","Category_Name","where Category_ID=".$t["Category_ID"]);
				if($category){
					$categoryname=$category["Category_Name"];
				}
		?>
          <tr>
            <td nowrap="nowrap"><?php echo $t["Article_ID"] ?></td>
            <td style="text-align:left; padding-left:15px;"><?php echo $t["Article_Title"];?></td>
 			<td nowrap="nowrap"><?php echo $categoryname; ?></td>
            <td nowrap="nowrap"><?php echo $STATUS[$t["Article_Status"]]; ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$t["Article_CreateTime"]); ?></td>
            <td class="last" nowrap="nowrap"><a href="article_edit.php?ID=<?php echo $t["Article_ID"];?>"><img src="/static/admin/images/ico/mod.gif" align="absmiddle" alt="修改" title="修改" /></a>&nbsp;<a href="?action=Del&ID=<?php echo $t["Article_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/admin/images/ico/del.gif" align="absmiddle" alt="删除" title="删除" /></a></td>
          </tr>
        
		<?php
		}
		?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>