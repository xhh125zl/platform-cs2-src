<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($GLOBALS['HTTP_RAW_POST_DATA'])){
    
    include_once("WxPayPubHelper.php");
    $notify = new Notify_pub();
    $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
    $notify->saveData($xml);
    $OrderID = $notify->data["out_trade_no"];
    $OrderID = substr($OrderID,10);    
    $rsCharge = $DB->GetRs("user_charge","*","where Item_ID=".$OrderID);
	if($rsCharge && $rsCharge["Status"]==0){
		$rsUsers=$DB->GetRs("users","*","where Users_ID='".$rsCharge["Users_ID"]."'");
		$rsPay=$DB->GetRs("users_payconfig","*","where Users_ID='".$rsCharge["Users_ID"]."'");
		$UsersID=$rsCharge["Users_ID"];
		include_once("WxPay.pub.config.php");
		if($notify->checkSign() == FALSE){
			$notify->setReturnParameter("return_code","FAIL");//返回状态码
			$notify->setReturnParameter("return_msg","签名失败");//返回信息
		}else{
			$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
		}
		$returnXml = $notify->returnXml();
		
		if($notify->checkSign() == TRUE){
			if ($notify->data["return_code"] == "FAIL") {
				echo "【通信出错】";
			}elseif($notify->data["result_code"] == "FAIL"){
				echo "【业务出错】";
			}else{
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
					echo "SUCCESS";
					exit;
				}else{
					echo "SUCCESS";
					exit;
				}
			}
		}
	}
}else{
	$OrderID = isset($_GET["OrderID"]) ? $_GET["OrderID"] : 0;
	$rsCharge = $DB->GetRs("user_charge","*","where Item_ID=".$OrderID);
	if($rsCharge){
		if($rsCharge["Status"]==1){
			echo "<script type='text/javascript'>window.location.href='/api/".$rsCharge["Users_ID"]."/user/charge_record/';</script>";
		}else{			
			echo "充值失败！";
		}		
	}else{
		echo "充值记录不存在！";
	}
}
?>