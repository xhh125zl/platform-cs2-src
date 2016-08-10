<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
ini_set("display_errors","On");
if (isset($_GET["UsersID"])) {
	$UsersID = $_GET["UsersID"];
} else {
	echo '缺少必要的参数';
	exit;
}

if(!empty($_SESSION[$UsersID."User_ID"])){
  
  $userexit = User::Multiwhere(array('Users_ID'=>$UsersID,'User_ID'=>$_SESSION[$UsersID."User_ID"]))
  					->first();
  if(empty($userexit)){
    $_SESSION[$UsersID."User_ID"] = "";
  }
}

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';
$shop_url = shop_url();

/*分享页面初始化配置*/
$share_flag = 1;
$signature = '';

//获取本店配置
//商城配置信息
$rsConfig = shop_config($UsersID);
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);
$level_config = $rsConfig['Dis_Level'];

$is_login = 1;
$owner = get_owner($rsConfig,$UsersID);
require_once $_SERVER["DOCUMENT_ROOT"] . '/include/library/wechatuser.php';
$owner = get_owner($rsConfig,$UsersID);

//获取登录用户账号
$User_ID = $_SESSION[$UsersID."User_ID"];
$rsUser =  User::Multiwhere(array('Users_ID'=>$UsersID,'User_ID'=>$User_ID))
			   ->first()
			   ->toArray();
			   
if($rsUser['Is_Distribute'] == 0) {
	header("location:".$shop_url."distribute/join/");
}

//获取登录用户分销账号
$accountObj = Dis_Account::Multiwhere(array('Users_ID'=>$UsersID,'User_ID'=>$User_ID))
			   ->first();

$posterity = $accountObj->getPosterity($level_config);	

//获取此分销账户佣金情况
$record_list = Dis_Account_Record::Multiwhere(array('Users_ID'=>$UsersID,'User_ID'=>$User_ID,'Record_Type'=>0))
                                   ->get(array('Record_Money','Record_Status','Record_CreateTime'))
								   ->toArray();	

$total_sales = round_pad_zero(get_my_leiji_sales($UsersID,$User_ID,$posterity),2);
$total_income = round_pad_zero(get_my_leiji_income($UsersID,$User_ID),2);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>分销赚钱</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<link href="/static/api/cloud/css/comm.css?t=<?php echo time();?>" rel="stylesheet" type="text/css" />
<link href="/static/api/cloud/css/invite.css?t=<?php echo time();?>" rel="stylesheet" type="text/css" />
</head>
<body class="g-acc-bg">
<div id="wrapper">
	<div class="inv-count clearfix">
		<ul>
			<li> <em class="orange">￥<?php echo $total_sales;?></em> <i class="colorbbb">累计销售额</i> </li>
			<li> <em class="orange" id="emMoney">￥<?php echo $total_income;?></em> <i class="colorbbb">累计佣金</i> </li>
		</ul>
	</div>
	<div class="ann_btn"> 
		<a href="<?php echo $cloud_url;?>distribute/commission/">佣金明细<s class="fr"></s></a> 
		<a href="<?php echo $cloud_url;?>distribute/mentionList/">提现记录<s class="fr"></s></a> 
	</div>
	<div class="pro_foot">
		<ul>
			<li id="liMention" class="border-orange-Btn"><a href="javascript:;">佣金提现</a></li>
		</ul>
	</div>
</div>
</body>
</html>