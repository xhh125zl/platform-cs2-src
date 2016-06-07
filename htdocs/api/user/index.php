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

$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/user/";

$is_login=1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

$rsConfig=$DB->GetRs("user_config","*","where Users_ID='".$UsersID."'");
$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
if(empty($rsUser["User_Profile"])){
	header("location:/api/".$UsersID."/user/complete/");
	exit;
}
$LevelName = '普通会员';
$CardStyleCustom =  empty($rsConfig['CardStyleCustom'])?'/static/api/images/user/card_bg/'.$rsConfig['CardStyle'].'.png':$rsConfig['CustomImgPath'];

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
<title>会员中心</title>
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/user.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/user.js'></script>
</head>

<body>
<script language="javascript">$(document).ready(user_obj.card_init);</script>
<div id="card">
  <div class="price">
	<a href="/api/<?php echo $UsersID ?>/user/money/"><span class="money"><?php echo $rsUser["User_Money"];?></span>余额</a><a href="/api/<?php echo $UsersID ?>/user/charge/"><span><img src="/static/api/images/user/money.png" width="20" style="margin-top:8px" /></span>充值</a><a href="#" title="<?php echo $LevelName;?>"><span class="level"><img src="/static/api/images/user/v<?php echo $rsUser["User_Level"];?>.png" width="20" style="margin-top:8px" /></span>会员级别</a><div class="clear"></div>
  </div>
  
  <div class="list_item">
	<div class="dline"></div>
	<a href="/api/<?php echo $UsersID ?>/user/zhuanzhang/" class="item item_13"><span class="ico"></span> 余额转帐
    <a href="/api/<?php echo $UsersID ?>/user/integral/" class="item item_0"><span class="ico"></span> 我的积分：<?php echo $rsUser["User_Integral"] ?>分
    <?php if($rsUser['User_UseLessIntegral']>0): ?>
    	&nbsp;&nbsp;不可用(<?=$rsUser['User_UseLessIntegral']?>分)
	<?php endif; ?>
    
    <span class="jt"></span></a>
    <div class="item item_1 benefits_btn"><span class="ico"></span>会员权利<span class="jt"></span></div>
    <dl class="benefits">
      <?php $DB->get("user_card_benefits","*","where Users_ID='".$UsersID."' and Benefits_UserLevel=".$rsUser["User_Level"]." and Benefits_StartTime<".time()." and Benefits_EndTime>".time()." order by Benefits_ID desc");
		$i=0;
		while($rsBenefits=$DB->fetch_assoc()){
			echo '<dt>'.$rsBenefits["Benefits_Title"].'</dt>
			<dd>'.$rsBenefits["Benefits_Description"].'</dd>';
			$i++;
		}?>
    </dl>
    <a href="/api/<?php echo $UsersID ?>/user/coupon/" class="item item_2"><span class="ico"></span>我的优惠券<span class="jt"></span></a>
	<a href="/api/<?php echo $UsersID ?>/user/my/" class="item item_3"><span class="ico"></span>我的资料<span class="jt"></span></a>
    <div class="dline"></div>    
    <a href="/api/<?php echo $UsersID ?>/shop/" class="item item_4"><span class="ico"></span>进入商城<span class="jt"></span></a>
     <a href="/api/<?php echo $UsersID ?>/user/kanjia_order/" class="item item_4"><span class="ico"></span>我的砍价订单<span class="jt"></span></a>
	<a href="/api/<?php echo $UsersID ?>/user/gift/1/" class="item item_5"><span class="ico"></span>积分兑换礼品<span class="jt"></span></a>
	<div class="dline"></div>
	<a href="/api/<?php echo $UsersID ?>/user/paymoney/" class="item item_6"><span class="ico"></span>实体店消费<span class="jt"></span></a>
	<a href="/api/<?php echo $UsersID ?>/user/payword/" class="item item_12"><span class="ico"></span>修改支付密码<span class="jt"></span></a>
    <div class="dline"></div>
    <a href="tel:<?php echo $rsConfig['BusinessPhone'] ?>" class="item item_7"><span class="ico"></span><?php echo $rsConfig['BusinessPhone'] ?><span class="jt"></span></a>
	<a href="http://api.map.baidu.com/marker?location=<?php echo $rsConfig["PrimaryLat"].','.$rsConfig["PrimaryLng"] ?>&title=<?php echo $rsConfig['BusinessName'] ?>&name=<?php echo $rsConfig['BusinessName'] ?>&content=<?php echo $rsConfig['Address'] ?>&output=html" class="item item_8"><span class="ico"></span><?php echo $rsConfig['Address'] ?><span class="jt"></span></a>
    </div>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>