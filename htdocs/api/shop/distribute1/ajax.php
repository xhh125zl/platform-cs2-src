<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');

$base_url = base_url();

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo 'error';
	exit;
}


$action=empty($_REQUEST["action"])?"":$_REQUEST["action"];

//申请成为分销商
if($action == 'join'){

	$UserID = $_SESSION[$UsersID.'User_ID'];
	$Real_Name = $_POST['Real_Name'];
	$User_Mobile = $_POST['User_Mobile'];
	
	$user = $DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$UserID);
	$owner["id"] = $user["Owner_Id"];
	$rsConfig = shop_config($UsersID);
	$status = $rsConfig['Distribute_Limit']==0 ? 1 : 0;						
	create_distribute_acccount($rsConfig,$UserID,$Real_Name,$owner,$User_Mobile,$status);

	$Flag = $DB->set('user',array('Is_Distribute'=>1),"where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'");
	
	if($Flag){
		$response = array('status'=>1);
	}else{
		$response = array('status'=>0);
	}
	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);
	
}elseif($action == 'edit_card'){
	//修改分销账户银行卡号
	
	$data = array("Bank_Card"=>$_POST['bank_card'],
				  "Bank_Name"=>$_POST['bank_name']);

	$condition = "where Users_ID='".$UsersID."' and User_ID= '".$_SESSION[$UsersID.'User_ID']."'";

	$Flag = $DB->Set('shop_distribute_account',$data,$condition);

	if($Flag){
		$response = array('status'=>1);
	}else{
		$response = array('status'=>0);
	}
	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);
}elseif($action == 'withdraw_appy'){
	//查看是否有足够的余额用于提现
	$rsConfig = $DB->GetRs('shop_config', '*', 'where Users_ID="'.$UsersID.'"');
	$condition = "where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'";
	$dsAccount = $DB->getRs('shop_distribute_account',"balance",$condition);
	
	if($_POST['money'] >$dsAccount['balance']){
		$response = array('status'=>0,'msg'=>'余额不足');
	}else{
		
		$condition = "where User_Method_ID=".$_POST['User_Method_ID']." and Users_ID='".$UsersID."'";
		$UserMethod = $DB->getRs('shop_user_withdraw_methods',"*",$condition);
		
		//转入余额
		if($UserMethod["Method_Type"]=='yue_income'){
			//增加资金流水
			$amount = $_POST['money'];
			$rsUser = $DB->GetRs("user", "User_Money", "where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
			$Data=array(
				'Users_ID'=>$UsersID,
				'User_ID'=>$_SESSION[$UsersID.'User_ID'],				
				'Type'=>1,
				'Amount'=>$amount,
				'Total'=>$rsUser['User_Money']+$amount,
				'Note'=>"佣金提现转入余额 +".$amount,
				'CreateTime'=>time()			
			);
			$Flag=$DB->Add('user_money_record',$Data);
			//更新用户余额
			$Data=array(				
				'User_Money'=>$rsUser['User_Money']+$amount					
			);		
			$Set = $DB->Set("user",$Data,"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
			$Flag = $Flag && $Set;
			//提现记录
			$data = array(
				"Users_ID"=>$UsersID,
				"User_ID"=>$_SESSION[$UsersID.'User_ID'],
				"Account_Info"=>'已成功转入余额',
				"Record_Sn"=>build_withdraw_sn(),
				"Record_Money"=>$amount,
				"Record_CreateTime"=>time(),
				"Record_Type"=>1,
				"Record_Status"=>1,
			);
			$Add = $DB->add('shop_distribute_account_record',$data);
			$Flag = $Flag && $Add;
			$condition = "where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'";
			$Data = array(
				"balance"=>$dsAccount["balance"]-$amount
			);
			$Set = $DB->Set('shop_distribute_account',$Data,$condition);
			$Flag = $Flag && $Set;
			$response = array('status'=>1,'msg'=>'已成功转入余额');
		}else{//提现转帐
			$total_money  = $_POST['money'];
			$fee = 0;
			if($rsConfig["Poundage_Ratio"]>0){
				$fee = $total_money * $rsConfig["Poundage_Ratio"] * 0.01;
			}
			$money  = $total_money * (1 - ($rsConfig['Balance_Ratio']/100));//佣金
			if($money<$fee){
				$response = array('status'=>0,'msg'=>'提现金额中的转帐部分小于手续费');
				echo json_encode($response,JSON_UNESCAPED_UNICODE);
				exit;
			}
			$money = $money - $fee;
			$Ratio_money = $total_money * $rsConfig['Balance_Ratio']/100;//余额
			
			//获取用户提现方式
			
			
			if($UserMethod["Method_Type"]=='wx_hongbao' && ($money<1 || $money>200)){
				$response = array('status'=>0,'msg'=>'提现金额在1-200之间才可试用微信红包提现');
			}else{
				$Account_Info = $UserMethod['Method_Name'].' '.$UserMethod['Account_Name'].' '.$UserMethod['Account_Val'].' '.$UserMethod['Bank_Position'];
			
				$data = array(
					"Users_ID"=>$UsersID,
					"User_ID"=>$_SESSION[$UsersID.'User_ID'],
					"Account_Info"=>$Account_Info,
					"Record_Sn"=>build_withdraw_sn(),
					"Record_Money"=>$money,
					"Record_CreateTime"=>time(),
					"Record_Type"=>1,
					"Record_Status"=>0,
				);
				
				$Flag = $DB->add('shop_distribute_account_record',$data);
				$recordid = $DB->insert_id();
				$condition = "where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'";			
				$DB->set('shop_distribute_account',"balance=balance-$total_money",$condition);
				if($Ratio_money>0){
					$rsUser = $DB->GetRs("user", "User_Money", "where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
					$Ratio_Data = array(
						'Users_ID' => $UsersID,
						'User_ID' => $_SESSION[$UsersID.'User_ID'],
						'Type'=>1,
						'Amount' => $Ratio_money,
						'Total' => $rsUser['User_Money']+$Ratio_money,
						'Note' => "佣金提现 +".$Ratio_money,
						'CreateTime' => time()            
					);
					$Flag = $Flag && $DB->add('user_money_record', $Ratio_Data);
					$Data = array(                
						'User_Money'=>$rsUser['User_Money']+$Ratio_money                 
					);
					$Flag = $Flag && $DB->Set('user',$Data,"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
				}
				if($Flag){
					if($UserMethod["Method_Type"]=='wx_hongbao' && ($money>=1 || $money<=200)){
						$rsUser = $DB->GetRs("user","User_OpenID","where User_ID=".$_SESSION[$UsersID.'User_ID']);
						if(empty($rsUser["User_OpenID"])){
							$response = array('status'=>1,'msg'=>'你还没有授权，系统不能自动发送红包，请等待管理员处理。');
							echo json_encode($response,JSON_UNESCAPED_UNICODE);
							exit;
						}
						
						$rsUsers = $DB->GetRs("users","Users_ID,Users_WechatAppId,Users_WechatAppSecret","where Users_ID='".$UsersID."'");
						if(empty($rsUsers["Users_WechatAppId"]) || empty($rsUsers["Users_WechatAppSecret"])){
							$response = array('status'=>1,'msg'=>'商家微信支付信息配置不完全，系统不能自动发送红包，请等待管理员处理。');
							echo json_encode($response,JSON_UNESCAPED_UNICODE);
							exit;
						}
						
						$rsPay = $DB->GetRs("users_payconfig","PaymentWxpayPartnerId,PaymentWxpayPartnerKey,PaymentWxpayCert,PaymentWxpayKey","where Users_ID='".$UsersID."'");
						if(empty($rsPay["PaymentWxpayPartnerId"]) || empty($rsPay["PaymentWxpayPartnerKey"]) || empty($rsPay["PaymentWxpayCert"]) || empty($rsPay["PaymentWxpayKey"])){
							$response = array('status'=>1,'msg'=>'商家微信支付信息配置不完全，系统不能自动发送红包，请等待管理员处理。');
							echo json_encode($response,JSON_UNESCAPED_UNICODE);
							exit;
						}
						
						$sendname = $rsConfig["ShopName"];
						$openid = $rsUser["User_OpenID"];
						$money = strval($money*100);
						$wishing = $actname = $remark = $rsConfig["ShopName"]."佣金提现";
						//必需,UsersID,OrderID无关参数
						$UsersID = $rsUsers["Users_ID"];
						$OrderID=0;
						require_once($_SERVER["DOCUMENT_ROOT"].'/pay/wxpay2/send_hongbao.php');
						if($Data["status"]==1){
							$data = array("Record_Status"=>1);
							$condition = "where Users_ID='".$UsersID."' and Record_ID='".$recordid."'";
							$DB->set("shop_distribute_account_record",$data,$condition);
							$response = array('status'=>1,'msg'=>'提现成功，请查收微信红包');
						}
					}else{
						$response = array('status'=>1,'msg'=>'提现申请提交成功,预计2个工作日内申请通过');
					}
				}else{
					$response = array('status'=>0,'msg'=>'发生位置错误，申请提交失败');
				}
			}
		}
	}

	echo json_encode($response,JSON_UNESCAPED_UNICODE);
}elseif($action == 'check_exist'){
	$field = $_GET['field'];
	if($field == 'Real_Name'){
		$value = $_GET['real_name'];
	}elseif($field == 'ID_Card'){
		$value = $_GET['idcard'];
	}elseif($field == 'Email'){
		$value = $_GET['email'];
	}
	$condition = "where User_ID=".$_SESSION[$UsersID."User_ID"]." and ".$field."='".$value."'";
	
	$rsUser = $DB->getRs("shop_distribute_account","*",$condition);
	
	if(!empty($rsUser)){
		echo 'false';
	}else{
		echo 'true';
	}
}elseif($action == "add_user_withdraw_method"){

	
	$data = array();
	$data['Users_ID'] = $UsersID;
	$data['User_ID'] = $_SESSION[$UsersID.'User_ID'];
	$data['Method_Name'] = $_POST['Method_Name'];
	$data['Method_Type'] = $_POST['Method_Type'];
	$data['Account_Name'] = $_POST['Account_Name'];
    $data['Account_Val'] = !empty($_POST['Account_Val'])?$_POST['Account_Val']:'';
	$data['Bank_Position'] = !empty($_POST['Bank_Position'])?$_POST['Bank_Position']:'';
	$data['Method_CreateTime'] = time();
	$data['Method_Status'] = 1;		
	
	$Flag = $DB->add('shop_user_withdraw_methods',$data);
	
	if($Flag){
		$response = array('status'=>1,'msg'=>'添加新的提现方式成功');
	}else{
		$response = array('status'=>0,'msg'=>'添加新的提现方式失败');
	}
	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);
	
}elseif($action == "delete_user_withdraw_method"){
	$method_id = $_POST['method_id'];
	
	$condition = "User_Method_ID=".$method_id." and Users_ID='".$UsersID."'";
	$Flag = $DB->Del('shop_user_withdraw_methods',$condition);
	
	if($Flag){
		$response = array('status'=>1);
	}else{
		$response = array('status'=>0);
	}

	echo json_encode($response,JSON_UNESCAPED_UNICODE);

}elseif($action == 'store_poster'){
	
 	$img = $_POST['dataUrl'];
 	$owner_id = $_POST['owner_id'];
	$file_path = '/data/poster/'.$UsersID.$owner_id.'.png';
	$web_path = '/data/poster/'.$UsersID.$owner_id.'.png';
	 
	$Flag = generate_postere($img,$UsersID,$owner_id);
	
	if($Flag){
		
		$condition = "Where Users_ID = '".$UsersID."' and User_ID=".$owner_id;	
		$DB->Set('shop_distribute_account',array('Is_Regeposter'=>0),$condition);
		$response = array('status'=>1,'poster_path'=>$web_path);
		
	}else{
		$response = array('status'=>0,'msg'=>'Unable to save the file.');
	}
	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);
	
}elseif($action == 'get_ex_bonus'){
   //检测此账号是否有额外奖金
   $condition = "where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID'];
   $rsDistirbuteAccount = $DB->getRs('shop_distribute_account','balance',$condition);
   $rsDistirbuteRecord = $DB->getRs('shop_distribute_account_record','SUM(Nobi_Money) as money',$condition." and Nobi_Money>0 and Record_Status=2 and Nobi_Status=0");
   $rsDistirbuteRecord["money"] = empty($rsDistirbuteRecord["money"]) ? 0 : $rsDistirbuteRecord["money"];
   if($rsDistirbuteRecord["money"] == 0){
   		$response = array('status'=>0,'msg'=>'分销额外奖金为零');
   }else{
   		 		
   		$data = array('Ex_Bonus'=>0,'balance'=>$rsDistirbuteAccount['balance']+$rsDistirbuteRecord["money"]);
   					  
   		$Flag = $DB->Set('shop_distribute_account',$data,$condition);
		$DB->Set('shop_distribute_account_record',array("Nobi_Status"=>1),"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']." and Nobi_Money>0 and Record_Status=2 and Nobi_Status=0");
   		$response = array('status'=>1, 'msg'=>'获取奖金成功');
   }

   echo json_encode($response,JSON_UNESCAPED_UNICODE);

}elseif( $action== 'check_mobile_exist'){
	
	$User_Mobile = $request->input('User_Mobile');
    
	$user = User::multiWhere(array('Users_ID'=>$UsersID,'User_Mobile'=>$User_Mobile))
	             ->get()
				 ->first();
	if(!empty($user)){
		echo 'false';
	}else{
		echo 'true';
	}
	
}elseif($action == 'send_short_msg'){

      //发送短信功能未开启
      if($setting["sms_enabled"]==0){
			$Data = array(
				"status"=> 0,
				"msg"=>"短信发送功能已关闭"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}

		$mobile = trim($request->input('mobile'));
       
		if(!$mobile){
			$Data = array(
				"status"=> 0,
				"msg"=>"请输入手机号码"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}

		
		if(!isset($_SESSION[$UsersID.'mobile_send']) || empty($_SESSION[$UsersID.'mobile_send'])){
			$_SESSION[$UsersID.'mobile_send'] = 0;
		}
		
		
		if(isset($_SESSION[$UsersID.'mobile_time']) && !empty($_SESSION[$UsersID.'mobile_time'])){
			if(time() - $_SESSION[$UsersID.'mobile_time'] < 300){
				$Data = array(
					"status"=> 0,
					"msg"=>"发送短信过快"
				);
				echo json_encode($Data,JSON_UNESCAPED_UNICODE);
				exit;
			}
		}
		
		
		
		if($_SESSION[$UsersID.'mobile_send'] > 4){
			$Data = array(
				"status"=> 0,
				"msg"=>"发送短信次数频繁"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}		
	
		$code = randcode(6);		
		
		$message = $SiteName."注册验证码：".$code."。此验证码十分钟有效。";
		require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/sms.func.php');
	
		$result = send_sms($mobile,$message,$UsersID);
		
		if($result==1){
			$_SESSION[$UsersID.'mobile'] = $mobile;
			$_SESSION[$UsersID.'sms_code'] = $code;
			$_SESSION[$UsersID.'mobile_code'] = md5($mobile.'|'.$code);
			$_SESSION[$UsersID.'mobile_time'] = time();
			$_SESSION[$UsersID.'mobile_send'] = $_SESSION[$UsersID.'mobile_send'] + 1;
			
			$Data = array(
				"status"=> 1,
				"msg"=>"发送成功，验证码四分钟有效。"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}else{
			$Data = array(
				"status"=> 0,
				"msg"=>"发送失败"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
}





