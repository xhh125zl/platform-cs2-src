<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
if(isset($_GET['OpenID'])){
	$_SESSION[$UsersID.'OpenID']=$_GET['OpenID'];
	header("location:/api/".$UsersID."/user/?wxref=mp.weixin.qq.com");
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
if(isset($_SESSION[$UsersID."User_ID"])){
	$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
}else{
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/user/";
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
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/user.css?t=123' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/user.js'></script>
</head>

<body> 
<script language="javascript">$(document).ready(user_obj.integral_init);</script>
<div class="pop_form">
  <form id="integral_form">
    <h1></h1>
    <input type="text" name="Integral" class="input" value="" placeholder="积分" pattern="[0-9]*" maxlength="5" notnull />
    <input type="password" name="Password" class="input" value="" placeholder="商家密码" notnull />
    <div class="btn">
      <input type="button" class="submit" value="提 交" />
      <input type="button" class="cancel" value="取 消" />
      <input type="hidden" name="RecordType" value="" />
      <input type="hidden" name="action" value="record" />
      <div class="clear"></div>
    </div>
  </form>
</div>
<div id="integral_header">
  <div class="l"><span><?php echo $rsUser["User_Integral"] ?></span><br />
    我的积分</div>
  <?php if($rsConfig['IsSign']){
	  $rsSign=$DB->GetRs("user_Integral_record","*","where Record_Type=0 and Record_CreateTime>".strtotime(date("Y-m-d 00:00:00"))." and Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
	  if($rsSign){
		  echo '<div class="sign_ok">已签到</div>';
	  }else{
		  echo '<div class="sign">签到</div>';
	  }
	  $rsSign=$DB->GetRs("user_Integral_record","count(*) as count","where Record_Type=0 and Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
	  echo '<div class="r"><span>'.$rsSign['count'].'</span><br />
    签到次数</div>';
  }?>
</div>
<div id="integral_get_use">
   
  <div>使用积分</div>
</div>
<div id="integral">
  <div class="list_item">
    <div class="dline"></div>
    <!--<a href="#" class="item item_4"><span class="ico"></span>积分抽奖<span class="jt"></span></a>--> 
    <a href="/api/<?php echo $UsersID ?>/user/gift/" class="item item_4"><span class="ico"></span>我兑换的礼品<span class="jt"></span></a>
	<a href="/api/<?php echo $UsersID ?>/user/gift/1/" class="item item_5"><span class="ico"></span>积分兑换礼品<span class="jt"></span></a>
	<a href="/api/<?php echo $UsersID ?>/user/integral_record/" class="item item_0"><span class="ico"></span>查看积分明细记录<span class="jt"></span></a>
	</div>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>