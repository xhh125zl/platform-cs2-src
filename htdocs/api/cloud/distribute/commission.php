<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

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

$level_config = $rsConfig['Dis_Level'];

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
<title>佣金明细</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<link href="/static/api/cloud/css/comm.css?t=<?php echo time();?>" rel="stylesheet" type="text/css" />
<link href="/static/api/cloud/css/invite.css?t=<?php echo time();?>" rel="stylesheet" type="text/css" />
</head>
<body class="g-acc-bg">
<div id="wrapper">
	<div class="inviteDetails commiss">
		<p>佣金余额：<em class="orange">￥0.00</em><cite>（累计收入：￥0.00）</cite></p>
		<dl id="divList">
			<dt style="width:auto;margin:0;padding:0 10px;height:37px;"><span>用户名</span><span>时间</span><span>云购金额(￥)</span><span>佣金(￥)</span></dt>
			<div class="noRecords colorbbb clearfix"><s></s>暂无记录
				<div class="z-use"><?php echo $_SERVER['HTTP_HOST'];?></div>
			</div>
		</dl>
		<div style="display: none;" id="divLoading" class="loading clearfix g-acc-bg">加载更多</div>
	</div>
	<?php require_once('../footer.php');?>
</div>
</body>
</html>