<?php

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
$rsConfig = $DB->GetRs("hongbao_config","*","where usersid='".$_SESSION["Users_ID"]."'");
if(!$rsConfig){
	header("location:config.php");
}
$actid=empty($_REQUEST['actid'])?0:$_REQUEST['actid'];
$condition = "where usersid='".$_SESSION["Users_ID"]."' and actid=".$actid." order by addtime desc";
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
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
		<li class=""><a href="rules.php">活动规则</a></li>
        <li class=""><a href="prize.php">红包管理</a></li>
		<li class="cur"><a href="users.php">用户列表</a></li>
      </ul>
    </div>
    <div id="users" class="r_con_wrap">
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="15%" nowrap="nowrap">序号</td>
            <td width="30%" nowrap="nowrap">头像</td>
            <td width="30%" nowrap="nowrap">昵称</td>
            <td width="25%" nowrap="nowrap">帮助时间</td>
          </tr>
        </thead>
        <tbody>
          <?php
			  $lists = array();
			  $DB->getPage("hongbao_record","*",$condition,10);			  
			  while($r=$DB->fetch_assoc()){
				  $lists[] = $r;
			  }
			  foreach($lists as $k=>$v){
				$user = $DB->GetRs("user","User_HeadImg,User_NickName","where User_ID=".$v["userid"]);
				$v["User_HeadImg"] = $user["User_HeadImg"];
				$v["User_NickName"] = $user["User_NickName"];
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $k+1;?></td>
            <td nowrap="nowrap"><img src="<?php echo $v["User_HeadImg"] ? $v["User_HeadImg"] : '/static/api/zhuli/images/user.jpg';?>" width="50" height="50" /></td>
            <td nowrap="nowrap"><?php echo $v["User_NickName"] ? $v["User_NickName"] : '微友助力';?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$v["addtime"]) ?></td>
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