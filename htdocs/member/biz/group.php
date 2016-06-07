<?php
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		$Flag=$DB->Del("biz_group","Users_ID='".$_SESSION["Users_ID"]."' and Group_ID=".$_GET["GroupID"]);
		if($Flag){
			$DB->Set("biz","Group_ID=0","where Users_ID='".$_SESSION["Users_ID"]."' and Group_ID=".$_GET["GroupID"]);
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
}
$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";
$OrderBy = "Group_Index ASC,Group_ID asc";
if(isset($_GET['search'])){
	if($_GET['Keyword']){
		$condition .= " and Group_Name like '%".$_GET['Keyword']."%'";
	}
}

$condition .= " order by ".$OrderBy;
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
<style type="text/css">
#group .search{padding:10px; background:#f7f7f7; border:1px solid #ddd; margin-bottom:8px; font-size:12px;}
#group .search *{font-size:12px;}
#group .search .search_btn{background:#1584D5; color:white; border:none; height:22px; line-height:22px; width:50px;}
</style>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li><a href="index.php">商家列表</a></li>
        <li class="cur"><a href="group.php">商家分组</a></li>
		<li><a href="apply.php">入驻申请列表</a></li>
		<li><a href="apply_config.php">入驻设置</a></li>
      </ul>
    </div>
	
    <div id="group" class="r_con_wrap">
      <div class="control_btn"><a href="group_add.php" class="btn_green btn_w_120">添加分组</a></div>
      <form class="search" method="get" action="?">
        关键词：
        <input type="text" name="Keyword" value="" class="form_input" size="15" />
        <input type="hidden" name="search" value="1" />
        <input type="submit" class="search_btn" value="搜索" />
      </form>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%" nowrap="nowrap">排序</td>
            <td nowrap="nowrap">分组名称</td>
            <td width="20%" nowrap="nowrap">开通店铺</td>
            <td width="15%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php 
		  $lists = array();
		  $DB->getPage("biz_group","*",$condition,10);
		  
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }
		  foreach($lists as $k=>$rsGroup){
			  ?>
              
          <tr>
            <td nowrap="nowrap"><?php echo $rsGroup["Group_Index"] ?></td>
            <td><?php echo $rsGroup["Group_Name"] ?></td>
            <td><?php echo $rsGroup["Group_IsStore"] ? '<font style="color:blue">已开通</font>' : '<font style="color:red">不开通</font>';?></td>
            <td class="last" nowrap="nowrap"><a href="group_edit.php?GroupID=<?php echo $rsGroup["Group_ID"] ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a> <a href="?action=del&GroupID=<?php echo $rsGroup["Group_ID"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a></td>
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