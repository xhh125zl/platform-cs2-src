<?php
ini_set("display_errors","On");
/*分享页面初始化配置*/
$is_login = 1;
require_once($_SERVER["DOCUMENT_ROOT"].'/api/shop/global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/compser_library/Salesman.php');

$share_flag = 1;
$signature = '';

//获取登录用户账号
$User_ID = $_SESSION[$UsersID."User_ID"];
$rsUser =  User::Multiwhere(array('Users_ID'=>$UsersID,'User_ID'=>$User_ID))
			   ->first()
			   ->toArray();
			   

//如果开启会员自动成为分销商,且此会员还不是分销商
if($rsUser['Is_Distribute'] == 0) {
	header("location:".$shop_url."distribute/join/");
}

//获取登录用户分销账号
$accountObj =  Dis_Account::Multiwhere(array('Users_ID'=>$UsersID,'User_ID'=>$User_ID))
			   ->first();
			   
$rsAccount = $accountObj->toArray();

if ($rsConfig['Distribute_Customize'] == 0) {
	$show_name = $rsUser['User_NickName'];
	$show_logo = !empty($rsUser['User_HeadImg']) ? $rsUser['User_HeadImg'] : '/static/api/images/user/face.jpg';
} else {
	$show_name = !empty($rsAccount['Shop_Name']) ? $rsAccount['Shop_Name'] : '暂无';
	$show_logo = !empty($rsAccount['Shop_Logo']) ? $rsAccount['Shop_Logo'] : '/static/api/images/user/face.jpg';

}

if ($rsAccount["Enable_Tixian"]==0 && $rsConfig["Withdraw_Type"]==3) {
	if($rsAccount["balance"]>=$rsConfig["Withdraw_Limit"]){
		$ff = $DB->Set("distribute_account",array("Enable_Tixian"=>1),"where Users_ID='".$UsersID."' and Account_ID=".$rsAccount["Account_ID"]);
		if($ff){
			$rsAccount["Enable_Tixian"] = 1;
		}
	}
}

$withdraw_msg = get_distribute_withdraw($DB,$UsersID,$rsAccount["Enable_Tixian"],$rsConfig["Withdraw_Type"],$rsConfig["Withdraw_Limit"],$shop_url,'#FFF',1);

$salesman = new Salesman($UsersID, $User_ID);
$limit = $salesman->up_salesman();
$is_salesman = $salesman->get_salesman();
if (!$is_salesman) {
	header("location:".$base_url."api/".$UsersID."/shop/distribute/sales/join/");
	exit;
}

$level_config = $rsConfig['Dis_Level'];
$posterity = $accountObj->getPosterity($level_config);


$total_sales = 0;

if($rsAccount["Invitation_Code"]){
	$bizids = array();
	$DB->get("biz","Biz_ID","where Users_ID='".$UsersID."' and Invitation_Code='".$rsAccount["Invitation_Code"]."'");
	while($tem = $DB->fetch_assoc()){
		$bizids[] = $tem["Biz_ID"];
	}
	
	if(count($bizids)>0){
		$r = $DB->GetRs("distribute_sales_record","SUM(Products_Price*Products_Qty) as money","where Users_ID='".$UsersID."' and User_ID=".$User_ID." and Biz_ID in(".(implode(",",$bizids)).")");
		$total_sales = empty($r["money"]) ? 0 : $r["money"];
	}
	
}
$r = $DB->GetRs("distribute_sales_record","SUM(Sales_Money) as money","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
$total_income = empty($r["money"]) ? 0 : $r["money"];
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title></title>
<link href="/static/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="/static/css/font-awesome.css">
<link href="/static/api/distribute/css/style.css" rel="stylesheet">
<link href="/static/api/distribute/sales/style.css" rel="stylesheet">
<link href="/static/api/distribute/css/distribute_center.css" rel="stylesheet">
<script src="/static/js/jquery-1.11.1.min.js"></script>
<script src="/static/api/distribute/js/distribute.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		distribute_obj.init();
	});
</script>


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

</head>

<body style="background:#FFF">
<?php if($rsAccount['Is_Audit'] == 1): ?>
<div class="wrap">
<?php if($rsAccount['status']): ?>
	<div class="my_tjm"><a href="/api/<?php echo $UsersID;?>/distribute/sales/tjm/">我的推荐码 &gt;&gt;</a></div>
	<div class="container">
		<div class="row">
			<div class="distribute_header">
				<div id="header_cushion">
					<div id="account_info" style="width:100%">
						<div class="pull-left" style="width:30%">
							<img id="hd_image" src="<?=$show_logo?>"/>
						</div>
						<div class="pull-right" style="width:70%;">
							<ul id="txt" style="padding-left:0px;<?php echo $withdraw_msg=="" ? '' : ' margin-top:20px;';?>">
                                                            <li>昵称：<?=$show_name?></li>
                                                            <li style="padding-right:3px;">分销级别：<?php echo empty($dis_level[$rsAccount['Level_ID']]) ? '' : $dis_level[$rsAccount['Level_ID']]['Level_Name']; ?> 【<a style="color:#FFF;" href="<?=distribute_url('upgrade/')?>">升级</a>】</li>
                                                            <li style="padding-right:3px;">
                                                              <?php if(strlen($rsUser['User_Mobile'])==0): ?> 
                                                                 <a style="color:#4878C6;text-decoration:underline;line-height:20px;" href="<?=distribute_url('bind_mobile/')?>">&nbsp;【绑定手机】</a>
                                                              <?php else:?>
                                                                 手机：<?php echo $rsUser['User_Mobile'];?>　　<a style="color:#4878C6;line-height:20px;" href="<?=distribute_url('change_bind/')?>">&nbsp;【更改手机】</a>
                                                              <?php endif;?>
                                                            </li>
							</ul>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div id="account_sum">	
					<a href="javascript:void(0)"><span><?=$total_sales?></span><br>累计销售额</a>
					<a href="javascript:void(0)"><span><?=$total_income?></span><br>累计提成</a>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
    
    <div class="salse_index_btns">
        	<ul>
            	<li class="border_r">
                	<a href="/api/<?php echo $UsersID;?>/distribute/sales/company/"><div class="images"><img src="/static/api/distribute/sales/ico_0.png" style="width:50px; padding-top:30px; margin:0px auto"></div><span>我的商家</span></a>
                </li>
                <li>
                	<a href="/api/<?php echo $UsersID;?>/distribute/sales/profit/"><div class="images"><img src="/static/api/distribute/sales/ico_1.png" style="width:40px; padding-top:30px; margin:0px auto"></div><span>我的利润</span></a>
                </li>
                <li class="border_r">
                	<a href="/api/<?php echo $UsersID;?>/distribute/sales/profit/"><div class="images"><img src="/static/api/distribute/sales/ico_2.png" style="width:40px; padding-top:30px; margin:0px auto"></div><span>利润明细</span></a>
                </li>
                <li>
                	<a href="/api/<?php echo $UsersID;?>/distribute/withdraw/"><div class="images"><img src="/static/api/distribute/sales/ico_3.png" style="width:50px; padding-top:40px; margin:0px auto"></div><span>申请提现</span></a>
                </li>
                <div class="clearfix"></div>
            </ul>
    </div>
<?php else: ?>
	<p>您的分销账号已被禁用</p>
	<a href="<?=$shop_url?>">返回</a>
<?php endif;?>
<?php else: ?>
	<div>
		<div id="desc" class="col-xs-10">
			<p style="font-size:18px;color:red;">
                <br/>
                <br/>
                您的分销申请正在审核中,<br/>
                请耐心等待...
			</p>
		</div>
	</div>
<?php endif;?>
</div>
<?php require_once '../../shop/skin/distribute_footer.php';?>
</body>
</html>
