<?php

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
$ActID=empty($_REQUEST['ActID'])?0:$_REQUEST['ActID'];
$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Act_ID=".$ActID." order by Record_Score desc";
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
    <link href='/static/member/css/zhuli.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/zhuli.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
		<li class=""><a href="rules.php">活动规则</a></li>
		<li class=""><a href="awordrules.php">兑奖规则</a></li>
        <li class=""><a href="prize.php">奖品设置</a></li>
		<li class="cur"><a href="users.php">用户列表</a></li>
      </ul>
    </div>
    <div id="users" class="r_con_wrap">
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="13%" nowrap="nowrap">序号</td>
            <td width="28%" nowrap="nowrap">头像</td>
            <td width="20%" nowrap="nowrap">昵称</td>
            <td width="19%" nowrap="nowrap">助力指数</td>
            <td width="20%" nowrap="nowrap">助力时间</td>
          </tr>
        </thead>
        <tbody>
          <?php
			  $lists = array();
			  $DB->getPage("zhuli_record","*",$condition,10);			  
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
            <td nowrap="nowrap"><?php echo $v["Record_Score"];?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$v["Record_Time"]) ?></td>
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