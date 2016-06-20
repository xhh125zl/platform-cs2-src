<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

if(isset($_GET["action"]))
{
	if($_GET["action"]=="logout")
	{
		session_unset();
	}
}

if(!empty($_SESSION[$UsersID."User_ID"])){
	$userexit = $DB->GetRs("user","*","where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'");
	if(!$userexit){
		$_SESSION[$UsersID."User_ID"] = "";
	}	
}
$_SESSION[$UsersID."HTTP_REFERER"]="/api/user/index.php?UsersID=".$UsersID;

$is_login=1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

$rsConfig=$DB->GetRs("user_config","*","where Users_ID='".$UsersID."'");
$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);

if($rsUser["User_ExpireTime"]>0 && $rsUser["User_ExpireTime"]<time()){
	echo "<script>alert('您的会员身份已到期,请联系网站管理员');window.history.back(-1);</script>";
	exit;
}

$LevelName = '普通会员';
$CardStyleCustom =  empty($rsConfig['CardStyleCustom'])?'/static/api/images/user/card_bg/'.$rsConfig['CardStyle'].'.png':$rsConfig['CustomImgPath'];
$UserLevel=json_decode($rsConfig['UserLevel'],true);
if(!empty($rsConfig["UserLevel"])){
	
	$level_arr = json_decode($rsConfig["UserLevel"],true);
	if(!empty($level_arr[$rsUser["User_Level"]])){
		$LevelName = $level_arr[$rsUser["User_Level"]]["Name"];
		if(!empty($level_arr[$rsUser["User_Level"]]["ImgPath"])){
			$CardStyleCustom = $level_arr[$rsUser["User_Level"]]["ImgPath"];
			
		}
	}

}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="telephone=no" name="format-detection" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的会员卡</title>
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/user.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/user.js'></script>
</head>

<body>
<script language="javascript">$(document).ready(user_obj.card_init);</script>
<div id="my_header">
  <div class="face"><?php echo $rsUser["User_HeadImg"] ? '<img src="'.$rsUser["User_HeadImg"].'" />' : '';?></div>
  <ul>
    <li><?php echo $rsUser["User_Name"] ?>【<?php echo $UserLevel[$rsUser["User_Level"]]["Name"] ?>】</li>
    <!--<li>余额:￥1.25</li>-->
    <li>现有积分: <?php echo $rsUser["User_Integral"] ?>分</li>
    <li>总获积分: <?php echo $rsUser["User_TotalIntegral"] ?>分</li>
  </ul>
</div>
<div id="card">
  <div class="card_header">
    <div class="card_frame">
	  <img src="<?=$CardStyleCustom?>" class="img" />
      <div class="logo"><?php echo empty($rsConfig['CardLogo'])?'':'<img src="'.$rsConfig['CardLogo'].'" />' ?></div>
      <div class="title" style='left:7%;bottom:10%;text-align:left;color:#ffffff'><?php echo $rsConfig['CardName'] ?></div>
      <div class="no" style='right:7%;bottom:10%;text-align:right;color:#ffffff'><?php echo empty($rsUser['User_No'])?'会员卡号':'No. '.$rsUser['User_No'] ?></div>
    </div>
  </div>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>