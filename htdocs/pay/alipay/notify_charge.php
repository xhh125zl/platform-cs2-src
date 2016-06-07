<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_POST)) {//判断POST来的数组是否为空
	echo "fail";
	exit;
}
$doc1 = new DOMDocument();
$doc1->loadXML($_POST['notify_data']);
$out_trade_no = $doc1->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;

$OrderID = substr($out_trade_no,10);
$rsCharge = $DB->GetRs("user_charge","*","where Item_ID=".$OrderID);
$rsPay=$DB->GetRs("users_payconfig","*","where Users_ID='".$rsCharge["Users_ID"]."'");
$rsUsers=$DB->GetRs("users","*","where Users_ID='".$rsCharge["Users_ID"]."'");

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");

$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {
	$doc = new DOMDocument();	
	if ($alipay_config['sign_type'] == 'MD5') {
		$doc->loadXML($_POST['notify_data']);
	}
	
	if( ! empty($doc->getElementsByTagName( "notify" )->item(0)->nodeValue) ) {	
	
		$trade_no = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;		
		$trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;
		if($trade_status == 'TRADE_FINISHED') {
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
		}
		else if ($trade_status == 'TRADE_SUCCESS') {
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
		}
	}
}
else {
    echo "fail";
}
?>