<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

$out_trade_no = $_GET['out_trade_no'];
$OrderID = substr($out_trade_no,10);
$rsCharge = $DB->GetRs("user_charge","*","where Item_ID=".$OrderID);
$rsPay=$DB->GetRs("users_payconfig","*","where Users_ID='".$rsCharge["Users_ID"]."'");
$rsUsers=$DB->GetRs("users","*","where Users_ID='".$rsCharge["Users_ID"]."'");

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php

$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {//验证成功
	if($rsCharge && $rsCharge["Status"]==0){
		$rsUser = $DB->GetRs("user","*","where User_ID=".$rsCharge['User_ID']);
		//增加资金流水
		$Data=array(
			'Users_ID'=>$rsCharge["Users_ID"],
			'User_ID'=>$rsCharge['User_ID'],				
			'Type'=>1,
			'Amount'=>$rsCharge['Amount'],
			'Total'=>$rsUser['User_Money']+$rsCharge['Amount'],
			'Note'=>$rsCharge['Operator'],
			'CreateTime'=>time()		
		);
		$Flag=$DB->Add('user_money_record',$Data);
		//更新用户余额
		$Data=array(				
			'User_Money'=>$rsUser['User_Money']+$rsCharge['Amount']					
		);
		$Flag=$DB->Set('user',$Data,"where Users_ID='".$rsCharge['Users_ID']."' and User_ID=".$rsCharge['User_ID']);	
		$Data = array(
			"Status" => 1
		);
		$Flag=$DB->Set("user_charge",$Data,"where Item_ID=".$OrderID);
		if($Flag){
			echo "<script type='text/javascript'>window.location.href='/api/".$rsCharge["Users_ID"]."/user/charge_record/';</script>";
		}else{
			echo "因发生未知错误导致订单更新失败，请联系网站管理员！";
		}
	}else{
		echo "充值记录不存在！";
	}
}else {
    echo "验证失败";
}
?>
        <title>支付宝即时到账交易接口</title>
	</head>
    <body>
    </body>
</html>