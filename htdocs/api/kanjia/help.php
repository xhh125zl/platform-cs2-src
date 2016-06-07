<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}


if(isset($_GET["KanjiaID"])){
	$KanjiaID=$_GET["KanjiaID"];
}else{
	echo '缺少必要的参数';
	exit;
}


//获取此活动信息
$condition = "where Users_ID = '".$UsersID."' and Kanjia_ID='".$KanjiaID."'";
$activity = $DB->GetRs('kanjia',"*",$condition);

//获取此活动关联产品信息
$product_id = $activity['Product_ID'];
$condition = "where Users_ID = '".$UsersID."' and Products_ID='".$product_id."'";
$product = $DB->GetRs('shop_products',"*",$condition);

$Bottom_Price =  $activity['Bottom_Price'];
if(isset($_GET['action'])){
	$action = $_GET['action'];
	if($action == 'self_kan'){
		
		//如果用户已经登录
		if(!empty($_SESSION[$UsersID."User_ID"])){
			
			//砍价动作
			$Record_Reduce = rand($activity['Beginnum'],$activity['Endnum']);
			
			if($product['Products_PriceX']-$Record_Reduce < $Bottom_Price){
				$Record_Reduce = $product['Products_PriceX']-$Bottom_Price;
			}
	     	
			$data = array(
				'Users_ID'=>$UsersID,
				'User_ID'=> $_SESSION[$UsersID."User_ID"],
				'Kanjia_ID'=>$KanjiaID,
				'Self_Kan' => $Record_Reduce,
				'Cur_Price' => $product['Products_PriceX']-$Record_Reduce,
				'Helper_Count'=>0
			);
			
			$DB->Add("kanjia_member",$data);
			
			
			$data = array(
				'Users_ID'=>$UsersID,
				'Helper_ID'=>$_SESSION[$UsersID."User_ID"],
				'User_ID'=> $_SESSION[$UsersID."User_ID"],
				'Kanjia_ID'=>$KanjiaID,
				'Record_Reduce'=>$Record_Reduce,
				'Record_Time' => time()
				);

			//加入砍价记录
			$DB->Add("kanjia_helper_record",$data);
         
			Header("Location:/api/".$UsersID."/kanjia/activity/".$KanjiaID.'/userid/'.$_SESSION[$UsersID."User_ID"].'/');
			
		}else{
			$_SESSION[$UsersID."HTTP_REFERER"] ="/api/".$UsersID."/kanjia/activity/";
			$_SESSION[$UsersID."HTTP_REFERER"] .= $KanjiaID."/userid/";
			$_SESSION[$UsersID."is_kanjia"] = 1;
			Header("Location:/api/".$UsersID."/user/login/?wxref=mp.weixin.qq.com");
		}

	}else if($action == 'help_kan'){
		   
		    //砍价动作
			$help_user_id = $_GET['help_user_id'];
			$helper_id = $_SESSION[$UsersID.'User_ID'];
			
			$condition = "where Users_ID = '".$UsersID."' and User_ID='".$help_user_id."'";
			$condition .= 'and Kanjia_ID='.$KanjiaID;
		
			
			$memberActivity = $DB->GetRS('kanjia_member','*',$condition);
			
			$Record_Reduce = rand($activity['Beginnum'],$activity['Endnum']);
	     	
			//如果随机出来后的加价砍价后产品价格小于底价
			if($memberActivity['Cur_Price'] - $Record_Reduce < $Bottom_Price){
				$Record_Reduce = $memberActivity['Cur_Price'] - $Bottom_Price;
			}
			
			$data = array(
				'Cur_Price' => $memberActivity['Cur_Price']-$Record_Reduce,
				'Helper_Count' => $memberActivity['Helper_Count']+1
			);
	
			 
			$DB->Set("kanjia_member",$data,$condition);
	
			$data  = array();
			$data = array(
				'Users_ID'=>$UsersID,
				'Helper_ID'=>$helper_id,
				'User_ID'=> $help_user_id,
				'Kanjia_ID'=>$KanjiaID,
				'Record_Reduce'=> $Record_Reduce,
				'Record_Time' => time()
				);

			//加入砍价记录
			$DB->Add("kanjia_helper_record",$data);
			Header("Location:/api/".$UsersID."/kanjia/activity/".$KanjiaID.'/userid/'.$help_user_id.'/');
		    
	}
	
}
