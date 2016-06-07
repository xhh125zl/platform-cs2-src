<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
$Keywords=empty($_REQUEST["Keywords"])?"":trim($_REQUEST["Keywords"]);
$condition = "where 1";
if($Keywords){
	$condition .= " and Announce_Title like '%".$Keywords."%'";
}
$STATUS=array('<font style="color:red">待审核</font>','<font style="color:blue">通过审核</font>');
//删除开始
if(!empty($_GET["action"])&&$_GET["action"]=="Del"){
	$ID=empty($_GET["ID"]) ? "0" : $_GET["ID"];
	mysql_query("delete from announce where Announce_ID='".$ID."'");
	echo "<script language='javascript'>alert('删除成功！');window.open('index.php','_self');</script>";
	exit();
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
        <li class="cur"><a href="index.php">公告管理</a></li>
        <li><a href="category.php">分类管理</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <div class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="">
        关键字：<input name="Keywords" value="<?php echo $Keywords;?>" type="text" class="form_input" size="30"/>
        <input type="submit" class="search_btn" value="搜索" />
      </form>
      <div class="b10"></div>
      <a href="add.php" style="display:block; width:80px; height:26px; line-height:26px; text-align:center; border-radius:5px; background:#3AA0EB; color:#FFF; font-size:12px;">添加公告</a>
      <div class="b10"></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
          	<td nowrap="nowrap" width="6%">ID</td>
            <td nowrap="nowrap" width="10%">所属分类</td>            
            <td nowrap="nowrap">标题</td>
            <td nowrap="nowrap" width="10%">状态</td>
            <td nowrap="nowrap" width="15%">添加时间</td>
            <td nowrap="nowrap" width="8%" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php 
			$lists = array();
			$DB->getPage("announce","*",$condition." order by Announce_ID desc",10);
			while($r=$DB->fetch_assoc()){
				$lists[] = $r;
			}
			foreach($lists as $t){
				$category = $DB->GetRs("announce_category","Category_Name","where Category_ID=".$t["Category_ID"]);
		?>
          <tr>
            <td nowrap="nowrap"><?php echo $t["Announce_ID"] ?></td>
            <td nowrap="nowrap"><?php echo empty($category["Category_Name"]) ? '' : $category["Category_Name"]; ?></td>
            <td style="text-align:left; padding-left:15px;"><?php echo $t["Announce_Title"];?></td>
            <td nowrap="nowrap"><?php echo $STATUS[$t["Announce_Status"]]; ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$t["Announce_CreateTime"]); ?></td>
            <td class="last" nowrap="nowrap"><a href="edit.php?ID=<?php echo $t["Announce_ID"];?>"><img src="/static/admin/images/ico/mod.gif" align="absmiddle" alt="修改" title="修改" /></a>&nbsp;<a href="?action=Del&ID=<?php echo $t["Announce_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/admin/images/ico/del.gif" align="absmiddle" alt="删除" title="删除" /></a></td>
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