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
		$r = $DB->GetRs("web_column","count(*) as num","where Users_ID='".$_SESSION["Users_ID"]."' and Column_ParentID=".$_GET["ColumnID"]);
		if($r["num"]>0){
			echo '<script language="javascript">alert("该栏目下有子栏目,请勿删除");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			$r = $DB->GetRs("web_article","count(*) as num","where Users_ID='".$_SESSION["Users_ID"]."' and Column_ID=".$_GET["ColumnID"]);
			if($r["num"]>0){
				echo '<script language="javascript">alert("该栏目下有内容,请勿删除");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
			}else{
				$Flag=$DB->Del("web_column","Users_ID='".$_SESSION["Users_ID"]."' and Column_ID=".$_GET["ColumnID"]);
				if($Flag){
					echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
				}else{
					echo '<script language="javascript">alert("删除失败");history.back();</script>';
				}
			}
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
        <li class="cur"><a href="column.php">栏目管理</a></li>
        <li class=""><a href="article.php">内容管理</a></li>
        <li class=""><a href="lbs.php">一键导航</a></li>
      </ul>
    </div>
    <div id="column" class="r_con_wrap"> 
      <script type='text/javascript' src='/static/js/plugin/dragsort/dragsort-0.5.1.min.js'></script> 
      <script language="javascript">$(document).ready(web_obj.column_init);</script>
      <div class="control_btn"><a href="column_add.php" class="btn_green btn_w_120">添加栏目</a></div>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="mytable">
                <tr>
                  <td width="50" align="center"><strong>排序</strong></td>
                  <td width="150" align="center"><strong>栏目名称</strong></td>
                  <td width="100" align="center">相关选项</td>
                  <td align="center"><strong>栏目链接</strong></td>
                  <td width="60" align="center"><strong>操作</strong></td>
                </tr>
				<?php
				$DB->get("web_column","*","where Users_ID='".$_SESSION["Users_ID"]."' and Column_ParentID=0 order by Column_Index asc");
				$Columns = array();
				while($r=$DB->fetch_assoc()){
					$Columns[] = $r;
				}
				foreach($Columns as $rsColumn){
				?>
                <tr onMouseOver="this.bgColor='#D8EDF4';" onMouseOut="this.bgColor='';" onDblClick="location.href='column_edit.php?ColumnID=<?php echo $rsColumn["Column_ID"]; ?>'">
                  <td align="center"><?php echo $rsColumn["Column_Index"]; ?></td>
                  <td><?php echo $rsColumn["Column_Name"]; ?></td>
                  <td align="center"><?php echo empty($rsColumn["Column_PopSubMenu"])?"":"弹出二级菜单<br>";
				  echo empty($rsColumn["Column_NavDisplay"])?"":"导航显示"?></td>
                  <td><?php if(empty($rsColumn["Column_Link"])){
						  echo "无";
					  }else{
						  echo strpos($rsColumn["Column_LinkUrl"],"http://")>-1?$rsColumn["Column_LinkUrl"]:"http://".$_SERVER['HTTP_HOST'].$rsColumn["Column_LinkUrl"];
					  } ?></td>
                  <td align="center"><a href="column_edit.php?ColumnID=<?php echo $rsColumn["Column_ID"]; ?>" title="修改"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a> <a href="column.php?action=del&ColumnID=<?php echo $rsColumn["Column_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
                </tr>
				<?php
				$DB->get("web_column","*","where Users_ID='".$_SESSION["Users_ID"]."' and Column_ParentID=".$rsColumn["Column_ID"]." order by Column_Index asc");
				while($items=$DB->fetch_assoc()){
				?>
				<tr onMouseOver="this.bgColor='#D8EDF4';" onMouseOut="this.bgColor='';" onDblClick="location.href='column_edit.php?ColumnID=<?php echo $items["Column_ID"]; ?>'">
                  <td align="center"><?php echo $items["Column_Index"]; ?></td>
                  <td> └ <?php echo $items["Column_Name"]; ?></td>
                  <td align="center"><?php echo empty($items["Column_PopSubMenu"])?"":"弹出二级菜单<br>";
				  echo empty($items["Column_NavDisplay"])?"":"导航显示"?></td>
                  <td><?php if(empty($items["Column_Link"])){
						  echo "无";
					  }else{
						  echo strpos($items["Column_LinkUrl"],"http://")>-1?$items["Column_LinkUrl"]:"http://".$_SERVER['HTTP_HOST'].$items["Column_LinkUrl"];
					  } ?></td>
                  <td align="center"><a href="column_edit.php?ColumnID=<?php echo $items["Column_ID"]; ?>" title="修改"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a> <a href="column.php?action=del&ColumnID=<?php echo $items["Column_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
                </tr>
				<?php }?>
				<?php }?>
              </table>
      <div class="clear"></div>
    </div>
  </div>
</div>
</body>
</html>