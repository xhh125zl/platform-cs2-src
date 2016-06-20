<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}

if(!empty($_GET["action"])&&$_GET["action"]=="Del"){
	$ID=empty($_GET["ID"]) ? "0" : $_GET["ID"];
	$r = $DB->GetRs("announce","count(*) as num","where Category_ID=".$ID);
	if($r["num"]>0){
		echo "<script language='javascript'>alert('请先删除其内容！');window.open('announce.php','_self');</script>";
		exit();
	}else{
		mysql_query("delete from announce_category where Category_ID='".$ID."'");
		echo "<script language='javascript'>alert('删除成功！');window.open('announce.php','_self');</script>";
		exit();
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/admin/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/admin/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li><a href="index.php">公告管理</a></li>
        <li class="cur"><a href="category.php">公告类别</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <div class="r_con_wrap">
      <a href="category_add.php" style="display:block; width:80px; height:26px; line-height:26px; text-align:center; border-radius:5px; background:#3AA0EB; color:#FFF; font-size:12px;">添加分类</a>
      <div class="b10"></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
          	<td nowrap="nowrap" width="8%">ID</td>
            <td nowrap="nowrap">类别名称</td> 
            <td nowrap="nowrap" width="15%" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php 
			$lists = array();
			$DB->getPage("announce_category","*"," order by Category_ID asc",10);
			while($t=$DB->fetch_assoc()){
		?>
          <tr>
            <td nowrap="nowrap"><?php echo $t["Category_ID"] ?></td>
            <td nowrap="nowrap"><?php echo $t["Category_Name"] ?></td>
            <td class="last" nowrap="nowrap"><a href="category_edit.php?ID=<?php echo $t["Category_ID"];?>"><img src="/static/admin/images/ico/mod.gif" align="absmiddle" alt="修改" title="修改" /></a>&nbsp;<a href="?action=Del&ID=<?php echo $t["Category_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/admin/images/ico/del.gif" align="absmiddle" alt="删除" title="删除" /></a></td>
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