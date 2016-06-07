<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
$TypeID=empty($_GET["TypeID"])?0:$_GET["TypeID"];	

if(!strpos($_SERVER['REQUEST_URI'],"OpenID=")){
	if(empty($_SESSION[$UsersID.'OpenID'])){
		$_SESSION[$UsersID.'OpenID']=session_id();
	}
}else{
	$url_arr = explode("OpenID=",$_SERVER['REQUEST_URI']);
	$endpos = explode("&",$url_arr[1]);
	$_SESSION[$UsersID.'OpenID']=$endpos[0];	
}

if(!empty($_SESSION[$UsersID."User_ID"])){
	$userexit = $DB->GetRs("user","*","where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'");
	if(!$userexit){
		$_SESSION[$UsersID."User_ID"] = "";
	}	
}

if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}
$rsConfig=$DB->GetRs("user_config","*","where Users_ID='".$UsersID."'");
if(empty($_SESSION[$UsersID."User_ID"]) || !isset($_SESSION[$UsersID."User_ID"])){
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/user/coupon/";
	header("location:/api/".$UsersID."/user/login/?wxref=mp.weixin.qq.com");
}

$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
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
<link href='/static/api/css/user.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/user.js'></script>
</head>

<body>
<script language="javascript">$(document).ready(user_obj.coupon_init);</script>
<div class="pop_form">
  <form id="coupon_use_form">
    <h1>使用优惠券</h1>
    <input type="text" name="Price" class="input" value="" placeholder="本次消费金额" pattern="[0-9]*" maxlength="10" />
    <input type="text" name="Integral" class="input" value="" placeholder="获得积分" pattern="[0-9]*" maxlength="5" />
    <input type="password" name="Password" class="input" value="" placeholder="商家密码" notnull />
    <div class="btn">
      <input type="button" class="submit" value="提 交" />
      <input type="button" class="cancel" value="取 消" />
      <div class="clear"></div>
    </div>
  </form>
</div>
<div id="coupon">
  <div class="t_list"> <a href="/api/<?php echo $UsersID ?>/user/coupon/" class="<?php echo $TypeID==0?'c':'' ?>">我的优惠券</a> <a href="/api/<?php echo $UsersID ?>/user/coupon/1/" class="m<?php echo $TypeID==1?' c':'' ?>">领取优惠券</a> <a href="/api/<?php echo $UsersID ?>/user/coupon/2/" class="<?php echo $TypeID==2?'c':'' ?>">已过期/失效</a> </div>
  <?php if($TypeID==0){
		$DB->query("SELECT b.Coupon_Subject,b.Coupon_PhotoPath,b.Coupon_ID,b.Coupon_EndTime,b.Coupon_Description,a.* FROM user_coupon_record AS a LEFT JOIN user_coupon AS b ON b.Users_ID = a.Users_ID AND b.Coupon_ID = a.Coupon_ID where a.User_ID='".$_SESSION[$UsersID."User_ID"]."' and b.Users_ID='".$UsersID."' and b.Coupon_StartTime<".time()." and b.Coupon_EndTime>".time()." order by b.Coupon_CreateTime desc");		
	}elseif($TypeID==1){
		$DB->query("SELECT * FROM user_coupon WHERE Users_ID='".$UsersID."' and Coupon_StartTime<".time()." and Coupon_EndTime>".time()." and user_coupon.Coupon_ID NOT IN ( SELECT Coupon_ID FROM user_coupon_record WHERE Users_ID='".$UsersID."' and User_ID = ".$_SESSION[$UsersID."User_ID"]." ) order by Coupon_CreateTime desc");
	}else{
		$DB->query("SELECT b.*,a.Coupon_UsedTimes FROM user_coupon_record AS a LEFT JOIN user_coupon AS b ON b.Users_ID = a.Users_ID AND b.Coupon_ID = a.Coupon_ID where a.User_ID='".$_SESSION[$UsersID."User_ID"]."' and b.Users_ID='".$UsersID."' and b.Coupon_EndTime<".time()." order by b.Coupon_CreateTime desc");
	}
	while($rsCoupon=$DB->fetch_assoc()){
		echo '<div class="item">
		<h1>【'.$rsCoupon['Coupon_Subject'].'】</h1>
			<div class="p"><img src="'.$rsCoupon['Coupon_PhotoPath'].'" />';
			if($TypeID==0){
				if($rsCoupon['Coupon_UseArea']==0){
					echo empty($rsCoupon['Coupon_UsedTimes'])?'':'<div class="use" CouponID="'.$rsCoupon['Coupon_ID'].'">使用</div>';
				}else{
					echo empty($rsCoupon['Coupon_UsedTimes'])?'':'<div class="use_shop">商城使用</div>';
				}
			}elseif($TypeID==1){
				echo empty($rsCoupon['Coupon_UsedTimes'])?'':'<div class="get" CouponID="'.$rsCoupon['Coupon_ID'].'">领取</div>';
			}
		echo '</div>
		<h2>'.date("Y-m-d H:i:s",$rsCoupon['Coupon_EndTime']).'过期【'.($rsCoupon['Coupon_UsedTimes']==-1?'无限':$rsCoupon['Coupon_UsedTimes']).'次使用】</h2>';
		if($rsCoupon['Coupon_UseArea']==0){
			echo '<h3>实体店可用</h3>';
		}else{
			echo '<h3>微商城'.($rsCoupon['Coupon_Condition']==0 ? '' : '(一次性购买满'.$rsCoupon['Coupon_Condition'].')').'可用</h3>';
			if($rsCoupon['Coupon_UseType']==0 && $rsCoupon['Coupon_Discount']>0 && $rsCoupon['Coupon_Discount']<1){
				echo '<h3>可享受折扣'.($rsCoupon['Coupon_Discount']*10).'折</h3>';
			}
			if($rsCoupon['Coupon_UseType']==1 && $rsCoupon['Coupon_Cash']>0){
				echo '<h3>可抵现金'.$rsCoupon['Coupon_Cash'].'元</h3>';
			}
		}
		echo '<h3>'.$rsCoupon['Coupon_Description'].'</h3>
	</div>';
	}?>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>