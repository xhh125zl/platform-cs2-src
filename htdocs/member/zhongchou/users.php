<?php

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
$rsConfig = $DB->GetRs("zhongchou_config","*","where usersid='".$_SESSION["Users_ID"]."'");
if(!$rsConfig){
	header("location:config.php");
}
$projectid=empty($_REQUEST['projectid'])?0:$_REQUEST['projectid'];
$item=$DB->GetRs("zhongchou_project","*","where usersid='".$_SESSION["Users_ID"]."' and itemid=".$projectid);
if(!$item){
	echo '<script language="javascript">alert("该项目不存在！");window.location="project.php";</script>';
}
$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Order_Type='zhongchou_".$projectid."' and Order_Status=2";
$OrderBy = "Order_CreateTime desc";
if(isset($_GET['search'])){
	if($_GET['money']==""){
		$condition .= " and Order_CartList=''";
	}else{
		$condition .= " and Order_TotalPrice=".$_GET['money'];
	}
}
$condition .= " order by ".$OrderBy;
$prizes = array();
$DB->get("zhongchou_prize","*","where usersid='".$_SESSION["Users_ID"]."' and projectid=$projectid order by money asc");
while($r=$DB->fetch_assoc()){
	$prizes[] = $r;
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
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
#users .search{padding:10px; background:#f7f7f7; border:1px solid #ddd; margin-bottom:8px; font-size:12px;}
#users .search *{font-size:12px;}
#users .search .search_btn{background:#1584D5; color:white; border:none; height:22px; line-height:22px; width:50px;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="project.php">项目管理</a></li>
      </ul>
    </div>
    <div id="users" class="r_con_wrap">
      <form class="search" method="get" action="?">
         支持金额：
        <select name="money">
          <option value="">无私奉献</option>
          <?php foreach($prizes as $v){?>
          <option value="<?php echo $v["money"];?>"><?php echo $v["money"];?></option>
          <?php }?>
        </select>
        <input type="hidden" name="search" value="1" />
        <input type="hidden" name="projectid" value="<?php echo $projectid;?>" />
        <input type="submit" class="search_btn" value="搜索" />
      </form>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">序号</td>
            <td width="20%" nowrap="nowrap">头像</td>
            <td width="15%" nowrap="nowrap">昵称</td>
            <td width="10%" nowrap="nowrap">支持金额</td>         
            <td nowrap="nowrap">回报</td>
            <td width="15%" nowrap="nowrap">时间</td>
            <td width="8%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php
			  $lists = array();
			  $DB->getPage("user_order","*",$condition,10);			  
			  while($r=$DB->fetch_assoc()){
				  $lists[] = $r;
			  }
			  foreach($lists as $k=>$v){
				$user = $DB->GetRs("user","User_HeadImg,User_NickName","where User_ID=".$v["User_ID"]);
				$v["User_HeadImg"] = $user["User_HeadImg"];
				$v["User_NickName"] = $user["User_NickName"];
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $k+1;?></td>
            <td nowrap="nowrap"><img src="<?php echo $v["User_HeadImg"] ? $v["User_HeadImg"] : '/static/api/zhuli/images/user.jpg';?>" width="50" height="50" /></td>
            <td nowrap="nowrap"><?php echo $v["User_NickName"] ? $v["User_NickName"] : '微友助力';?></td>
            <td nowrap="nowrap"><?php echo $v["Order_TotalPrice"];?></td>
            <td nowrap="nowrap"><?php echo $v["Order_CartList"] ? $v["Order_CartList"] : '无私奉献';?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$v["Order_CreateTime"]) ?></td>
            <td class="last" nowrap="nowrap"><a href="users_view.php?orderid=<?php echo $v["Order_ID"] ?>&projectid=<?php echo $projectid;?>"><img src="/static/member/images/ico/view.gif" align="absmiddle" alt="查看详情" /></a></td>
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