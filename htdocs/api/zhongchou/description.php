<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$rsConfig = $DB->GetRs("zhongchou_config","*","where usersid='".$UsersID."'");
if(!$rsConfig){
	echo '未开通微众筹';
	exit;
}

require_once('../share.php');
if(isset($_GET["itemid"])){
	$itemid = $_GET["itemid"];
	$item = $DB->GetRs("zhongchou_project","*","where usersid='".$UsersID."' and itemid=$itemid");
	if(!$item){
		echo '该项目不存在';
		exit;
	}
}else{
	echo '缺少必要的参数';
	exit;
}

$_SESSION[$UsersID."HTTP_REFERER"]="/api/zhongchou/description.php?UsersID=".$_GET["UsersID"]."&itemid=".$itemid;

$rsUsers = $DB->GetRs("users","*","where Users_ID='".$UsersID."'");
$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."' and PaymentWxpayEnabled=1");

require_once(CMS_ROOT.'/include/library/wechatuser.php');

$item["description"] = str_replace('&quot;','"',$item["description"]);
$item["description"] = str_replace("&quot;","'",$item["description"]);
$item["description"] = str_replace('&gt;','>',$item["description"]);
$item["description"] = str_replace('&lt;','<',$item["description"]);
$lists = array();
$DB->get("zhongchou_prize","*","where usersid='".$UsersID."' and projectid=$itemid");
while($r = $DB->fetch_assoc()){
	$lists[] = $r;
}
if($item["totime"]<time()){
	$Data = array("status"=>1);
	$DB->Set("zhongchou_project",$Data,"where itemid=$itemid");
}
$r = $DB->GetRs("user_order","count(*) as num,sum(Order_TotalPrice) as amount","where Order_Type='zhongchou_".$itemid."' and Order_Status=2 and Users_ID='".$UsersID."'");
$item["people"] = empty($r["num"]) ? 0 : $r["num"];
$item["complete"] = empty($r["amount"]) ? 0 : $r["amount"];
require_once('skin/description.php');
?>
