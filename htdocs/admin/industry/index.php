<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
$Keywords=empty($_REQUEST["Keywords"])?"":trim($_REQUEST["Keywords"]);
$condition = "where parentid=0";
if($Keywords){
	$condition .= " and name like '%".$Keywords."%'";
}

//删除开始
if(!empty($_GET["action"])&&$_GET["action"]=="Del"){
	$ID=empty($_GET["ID"]) ? "0" : $_GET["ID"];
	$r = $DB->GetRs("industry","count(*) num","where parentid=".$ID);
	if($r["num"]>0){
		echo "<script language='javascript'>alert('请先删除其子栏目！');window.open('index.php','_self');</script>";
		exit();
	}else{
		mysql_query("delete from industry where id='".$ID."'");
		echo "<script language='javascript'>alert('删除成功！');window.open('index.php','_self');</script>";
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
        <li class="cur"><a href="index.php">行业管理</a></li>
        <li><a href="add.php">添加行业</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <div class="r_con_wrap">
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
          	<td nowrap="nowrap" width="8%">排序</td>
            <td nowrap="nowrap" width="20%">行业名称</td>            
            <td nowrap="nowrap">行业图标</td>
            <td nowrap="nowrap" width="15%" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php 
			$lists = array();
			$DB->getPage("industry","*",$condition." order by id asc",10);
			while($r=$DB->fetch_assoc()){
				$lists[] = $r;
			}
			foreach($lists as $t){
		?>
          <tr>
            <td nowrap="nowrap"><?php echo $t["listorder"] ?></td>
            <td style="text-align:left; padding-left:15px;"><?php echo $t["name"] ?></td>
            <td nowrap="nowrap"><?php if($t["logo"]){?><img src="<?php echo $t["logo"];?>" width="56" height="56" /><?php }?></td>
            <td class="last" nowrap="nowrap"><a href="edit.php?ID=<?php echo $t["id"];?>"><img src="/static/admin/images/ico/mod.gif" align="absmiddle" alt="修改" title="修改" /></a>&nbsp;<a href="?action=Del&ID=<?php echo $t["id"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/admin/images/ico/del.gif" align="absmiddle" alt="删除" title="删除" /></a></td>
          </tr>
        <?php
           $DB->get("industry","*","where parentid=".$t["id"]." order by id asc");
		   while($tag=$DB->fetch_assoc()){
		?>
          <tr>
            <td nowrap="nowrap"><?php echo $tag["listorder"] ?></td>
            <td style="text-align:left; padding-left:25px;">  └   <?php echo $tag["name"] ?></td>
            <td nowrap="nowrap"><?php if($tag["logo"]){?><img src="<?php echo $tag["logo"];?>" width="56" height="56" /><?php }?></td>
            <td class="last" nowrap="nowrap"><a href="edit.php?ID=<?php echo $tag["id"];?>"><img src="/static/admin/images/ico/mod.gif" align="absmiddle" alt="修改" title="修改" /></a>&nbsp;<a href="?action=Del&ID=<?php echo $tag["id"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/admin/images/ico/del.gif" align="absmiddle" alt="删除" title="删除" /></a></td>
          </tr>
		<?php
			}
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