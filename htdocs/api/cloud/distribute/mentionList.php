<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');

/*分享页面初始化配置*/
$share_flag = 1;
$signature = '';

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';
$shop_url = shop_url();

if(!empty($_SESSION[$UsersID."User_ID"])){
	$userexit = $DB->GetRs("user","*","where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'");
	if(!$userexit){
		$_SESSION[$UsersID."User_ID"] = "";
	}	
}


if(empty($_SESSION[$UsersID."User_ID"]))
{
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/cloud/distribute/";
	header("location:/api/".$UsersID."/user/login/");
}
$Status=empty($_GET["Status"])?0:$_GET["Status"];
if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}

$rsConfig=$DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
$rsUser = $DB->GetRs("user","*","where User_ID=".$_SESSION[$UsersID."User_ID"]);

//获取帮助此用户的记录
//Record_Type 为1的记录就是提现记录

$condition ="where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."' and  Record_Type=1";

if(isset($_GET['status'])){
	$status = $_GET['status']; 
	$condition .= " and Record_Status=".$status;
}else{
	$status = 'all';
}

$rsRecords = $DB->get('shop_distribute_account_record','*',$condition);
$records = $DB->toArray($rsRecords);

$Status = array("申请中","已执行","已驳回");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>提现记录</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<link href="/static/api/cloud/css/comm.css?t=<?php echo time();?>" rel="stylesheet" type="text/css" />
<link href="/static/api/cloud/css/invite.css?t=<?php echo time();?>" rel="stylesheet" type="text/css" />
</head>
<body class="g-acc-bg">
<div id="wrapper">
	<div class="withdrawDetails">
		<dl id="mentionList" class="gray9">
			<dt style="width:auto;margin:0;padding:0 10px;height:37px;"><span>申请时间</span><span>提现金额</span><span>提现状态</span></dt>
			<?php if(!empty($records)){?>
			<?php foreach($records as $key => $item){?>
			<dd><span><?=sdate($item['Record_CreateTime'])?></span><span>¥<?=$item['Record_Money']?></span><span><?=$Status[$item['Record_Status']];?></span></dd>
			<?php }?>
			<?php }else{?>
			<div class="noRecords colorbbb clearfix"><s></s>暂无记录
				<div class="z-use"><?php echo $_SERVER['HTTP_HOST'];?></div>
			</div>
			<?php }?>
		</dl>
		<div style="display: none;" id="divLoading" class="loading clearfix g-acc-bg">加载更多</div>
	</div>
 <?php require_once('../footer.php');?>
</div>
</body>
</html>