<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
require_once('vertify.php');
if(isset($_GET["action"]))
{
	if($_GET["action"]=="del")
	{
		$Flag=$DB->Del('web_article',"Users_ID='".$_SESSION["Users_ID"]."' and Article_ID=".$_GET["ArticleID"]);
		if($Flag)
		{
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
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
    <link href='/static/member/css/web.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/web.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class=""><a href="skin.php">风格设置</a></li>
        <li class=""><a href="home.php">首页设置</a></li>
        <li class=""><a href="column.php">栏目管理</a></li>
        <li class="cur"><a href="article.php">内容管理</a></li>
        <li class=""><a href="lbs.php">一键导航</a></li>
      </ul>
    </div>
    <div id="column" class="r_con_wrap"> 
      <script type='text/javascript' src='/static/js/plugin/dragsort/dragsort-0.5.1.min.js'></script> 
      <script language="javascript">$(document).ready(web_obj.column_init);</script>
      <div class="control_btn"><a href="article_add.php" class="btn_green btn_w_120">添加内容</a></div>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="mytable">
        <tr>
          <td width="50" align="center"><strong>排序</strong></td>
          <td width="100" align="center"><strong>隶属栏目</strong></td>
          <td align="center"><strong>内容标题</strong></td>
          <td width="60" align="center"><strong>操作</strong></td>
        </tr>
        <?php
$DB->get("web_column`,`web_article","*","where web_article.Users_ID='".$_SESSION["Users_ID"]."' and web_column.Column_ID=web_article.Column_ID order by web_article.Article_Index asc,web_article.Article_ID desc");
while($rsArticle=$DB->fetch_assoc()){?>
        <tr onMouseOver="this.bgColor='#D8EDF4';" onMouseOut="this.bgColor='';" onDblClick="location.href='article_edit.php?ArticleID=<?php echo $rsArticle["Article_ID"]; ?>'">
          <td align="center"><?php echo $rsArticle["Article_Index"]; ?></td>
          <td align="center"><?php echo $rsArticle["Column_Name"]; ?></td>
          <td><?php echo $rsArticle["Article_Title"]; ?></td>
          <td align="center"><a href="article_edit.php?ArticleID=<?php echo $rsArticle["Article_ID"]; ?>" title="修改"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a> <a href="article.php?action=del&ArticleID=<?php echo $rsArticle["Article_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
        </tr>
        <?php }?>
      </table>
      <div class="clear"></div>
    </div>
  </div>
</div>
</body>
</html>