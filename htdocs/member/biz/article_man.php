<?php


if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$_GET = daddslashes($_GET,1);
$Keywords=empty($_REQUEST["Keywords"])?"":trim($_REQUEST["Keywords"]);
$condition = "where Users_ID='".$_SESSION["Users_ID"]."' ";
if($Keywords){
	$condition .= " and atricle_title like '%".$Keywords."%'";
}


$STATUS=array('<img src="/static/member/images/shop/no.gif" />','<img src="/static/member/images/shop/yes.gif" />');


//删除开始
if(!empty($_GET["action"])&&$_GET["action"]=="Del"){
	$ID=empty($_GET["ID"]) ? "0" : $_GET["ID"];
	mysql_query("delete from biz_article where id='".$ID."'");
	echo "<script language='javascript'>alert('删除成功！');window.open('article_man.php','_self');</script>";
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
        <li class="cur"><a href="article_man.php">文章管理</a></li>
		<li><a href="articlecate_man.php">分类管理</a></li>
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

            <td nowrap="nowrap" width="15%">添加时间</td>
            <td nowrap="nowrap" width="8%" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php 
			$lists = array();
			$DB->getPage("biz_article","*",$condition." order by id desc",10);
			while($r=$DB->fetch_assoc()){
				$lists[] = $r;
			}
			foreach($lists as $t){
				$categoryname = '';
				$category = $DB->GetRs("biz_article_cate","category_name","where id=".$t["category_id"]);
				if($category){
					$categoryname=$category["category_name"];
				}
		?>
          <tr>
            <td nowrap="nowrap"><?php echo $t["id"] ?></td>
            <td style="text-align:left; padding-left:15px;"><?php echo $t["atricle_title"];?></td>
 			<td nowrap="nowrap"><?php echo $categoryname; ?></td>

            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$t["addtime"]); ?></td>
            <td class="last" nowrap="nowrap"><a href="article_edit.php?ID=<?php echo $t["id"];?>"><img src="/static/admin/images/ico/mod.gif" align="absmiddle" alt="修改" title="修改" /></a>&nbsp;<a href="?action=Del&ID=<?php echo $t["id"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/admin/images/ico/del.gif" align="absmiddle" alt="删除" title="删除" /></a></td>
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