<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
$TypeID=empty($_GET["TypeID"])?0:$_GET["TypeID"];	
if(isset($_GET['OpenID'])){
	$_SESSION[$UsersID.'OpenID']=$_GET['OpenID'];
	header("location:/api/".$UsersID."/user/my/".(empty($TypeID)?'':$TypeID.'/')."?wxref=mp.weixin.qq.com");
	exit;
}else{
	if(empty($_SESSION[$UsersID.'OpenID'])){
		$_SESSION[$UsersID.'OpenID']=session_id();
	}
}
if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}
$rsConfig=$DB->GetRs("user_config","*","where Users_ID='".$UsersID."'");
$UserLevel=json_decode($rsConfig['UserLevel'],true);
if(isset($_SESSION[$UsersID."User_ID"])){
	$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
}else{
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/user/my/";
	header("location:/api/".$UsersID."/user/login/?wxref=mp.weixin.qq.com");
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
<title>会员中心</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/user.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/user.js'></script>
</head>

<body>
<div id="my_header">
  <div class="face"><?php echo $rsUser["User_HeadImg"] ? '<img src="'.$rsUser["User_HeadImg"].'" />' : '';?></div>
  <ul>
    <li><?php echo $rsUser["User_Name"] ?>【<?php echo $UserLevel[$rsUser["User_Level"]]["Name"] ?>】</li>
    <!--<li>余额:￥1.25</li>-->
    <li>现有积分: <?php echo $rsUser["User_Integral"] ?>分</li>
    <li>总获积分: <?php echo $rsUser["User_TotalIntegral"] ?>分</li>
  </ul>
</div>
<script language="javascript">$(document).ready(user_obj.my_init);</script>
<div class="pop_form" id="modify_password_div">
  <form id="modify_password_form">
    <h1>修改登录密码</h1>
    <input type="password" name="YPassword" class="input" value="" placeholder="原登录密码" notnull />
    <input type="password" name="Password" class="input" value="" placeholder="登录密码" notnull />
    <input type="password" name="ConfirmPassword" class="input" value="" placeholder="确认密码" notnull />
    <div class="btn">
      <input type="button" class="submit" value="提 交" />
      <input type="button" class="cancel" value="取 消" />
      <div class="clear"></div>
    </div>
  </form>
</div>
<div class="pop_form" id="modify_mobile_div">
  <form id="modify_mobile_form">
    <h1>修改手机号码</h1>
    <input type="password" name="Password" class="input" value="" placeholder="登录密码" notnull />
    <input type="tel" name="Mobile" class="input" value="<?php echo $rsUser["User_Mobile"] ?>" maxlength="11" placeholder="手机号码" pattern="[0-9]*" notnull />
    <div class="btn"><input name="MobileCheck" type="hidden" value="<?php echo $rsUser["User_Mobile"] ?>">
      <input type="button" class="submit" value="提 交" />
      <input type="button" class="cancel" value="取 消" />
      <div class="clear"></div>
    </div>
  </form>
</div>
<div id="my">
  <div class="list_item"> 
    <!--<a href="#" class="item item_6"><span class="ico"></span>我的消费记录<span class="jt"></span></a>--> 
    <a href="/api/<?php echo $UsersID ?>/user/coupon/" class="item item_2"><span class="ico"></span>我的优惠券<span class="jt"></span></a> <a href="/api/<?php echo $UsersID ?>/user/my/profile/" class="item item_3"><span class="ico"></span>修改我的资料<span class="jt"></span></a>
    <div class="modify_mobile item item_9"><span class="ico"></span>修改手机号码<span class="jt"></span></div>
    <div class="modify_password item item_12"><span class="ico"></span>修改登录密码<span class="jt"></span></div>
    <div class="dline"></div>
    <a href="/api/<?php echo $UsersID ?>/user/my/address/" class="item item_7"><span class="ico"></span>收货地址管理<span class="jt"></span></a> </div>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>