<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/Alipay.php');
require_once($_SERVER["DOCUMENT_ROOT"]."/third_party/alipay/alipay_notify.class.php"); 
							
//---获取订单号，订单类型
$order_sn  = isset($_POST['out_trade_no']) ? $_POST['out_trade_no'] : '';
               
//---计算得出通知验证结果

$UsersID = '';
$alipay_cnf = get_alipay_conf($DB,$UsersID);
$alipayNotify = new AlipayNotify($alipay_cnf);
$verify_result = $alipayNotify->verifyNotify();
  
if($verify_result) {
	$trade_no = $_POST['trade_no'];
   	$trade_status = $_POST['trade_status'];
	//write_file('../data/alipay/log.txt',$order_sn.'___'.$trade_no.'___'.$trade_status.'\r\n','a');
	if($trade_status == 'TRADE_FINISHED'||$trade_status == 'TRADE_SUCCESS') {		  
		$condition = "where Record_Sn='".$order_sn."'";
		$record = $DB->getRs("users_money_record","*",$condition);
		if($record['Record_Status'] == 0){
			$DB->Set("users_money_record",array("Record_Status"=>1,'trade_no'=>$trade_no),$condition);
			if($record['Record_Type']==0){					
				$condition = "where Users_ID= '".$record['Users_ID']."'";
				$rsUsers = $DB->getRs("Users","Users_ExpireDate",$condition);
						
				$str = '+'.$record['Record_Qty'].' years';
				$ExpireDate = strtotime($str,$rsUsers['Users_ExpireDate']);
				$DB->Set("users",array("Users_ExpireDate"=>$ExpireDate),$condition);
			}else{					
				$condition = "where Users_ID= '".$record['Users_ID']."'";
				$rsUsers = $DB->getRs("Users","Users_Sms",$condition);
				$number = $record['Record_Qty']+$rsUsers["Users_Sms"];
				$DB->Set("users",array("Users_Sms"=>$number),$condition);
			}
	    }
    }
    echo "success";
    exit();
}
else {
    echo "fail";
	exit();
}



		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	