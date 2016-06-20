<?php
basename($_SERVER['PHP_SELF'])=='message.func.php'&&header('Location:http://'.$_SERVER['HTTP_HOST']);

function send_message_tem($access_token, $data = array()){// 发送消息	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data,JSON_UNESCAPED_UNICODE));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	return json_decode(curl_exec($ch),true);
}

function get_token_tem($usersid){
	global $DB1;
	$access_token = '';
	require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_token.class.php');
	$weixin_token = new weixin_token($DB1,$usersid);
	$access_token = $weixin_token->get_access_token();
	return $access_token;
}

function get_openid_tem($usersid,$userid){
	global $DB1;
	$openid = '';
	$r = $DB1->GetRs("user","User_OpenID","where Users_ID='".$usersid."' and User_ID=".$userid);
	if($r){
		$openid = $r["User_OpenID"];
	}
	return $openid;
}

function user_create_tem($usersid,$userid,$url){
	global $DB1;
	$data = array();
	
	$access_token = get_token_tem($usersid);
	$t = $DB1->GetRs("message_template","*","where Users_ID='".$usersid."' and Model_ID=1 order by Template_CreateTime desc");
	$r = $DB1->GetRs("user","*","where Users_ID='".$usersid."' and User_ID=".$userid);
	
	//获取会员openid
	$openid_user = get_openid_tem($r["Users_ID"],$userid);
		
	//获取分销商openid
	$openid_ower = get_openid_tem($r["Users_ID"],$r["Owner_Id"]);
	
	if(($openid_user || $openid_ower) && $access_token && $t && $r){
		$json = $t["Template_Json"] ? json_decode($t["Template_Json"],true) : array();
		$data["template_id"] = $t["Template_LinkID"];
		$data["topcolor"] = "#FF0000";
		
		if(empty($json["keyword1"])){
			$data["data"]["keyword1"] = array(
				"value"=>$r["User_NickName"],
				"color"=>"#000"
			);
		}else{
			$data["data"]["keyword1"]["color"]="#000";
			$arr = explode("|",$json["keyword1"]);
			if($arr[0]=="0"){
				$data["data"]["keyword1"]["value"]=$r["User_".$arr[1]];
			}else{
				$data["data"]["keyword1"]["value"]=$arr[1];
			}
		}
		
		if(empty($json["keyword2"])){
			$data["data"]["keyword2"] = array(
				"value"=>$r["User_Mobile"],
				"color"=>"#000"
			);
		}else{
			$data["data"]["keyword2"]["color"]="#000";
			$arr = explode("|",$json["keyword2"]);
			if($arr[0]=="0"){
				$data["data"]["keyword2"]["value"]=$r["User_".$arr[1]];
			}else{
				$data["data"]["keyword2"]["value"]=$arr[1];
			}
		}
		
		if(empty($json["keyword3"])){
			$data["data"]["keyword3"] = array(
				"value"=>$r["User_Integral"],
				"color"=>"#000"
			);
		}else{
			$data["data"]["keyword3"]["color"]="#000";
			$arr = explode("|",$json["keyword3"]);
			if($arr[0]=="0"){
				$data["data"]["keyword3"]["value"]=$r["User_".$arr[1]];
			}else{
				$data["data"]["keyword3"]["value"]=$arr[1];
			}
		}
		
		if(empty($json["keyword4"])){
			$data["data"]["keyword4"] = array(
				"value"=>$r['User_Province'],
				"color"=>"#000"
			);
		}else{
			$data["data"]["keyword4"]["color"]="#000";
			$arr = explode("|",$json["keyword4"]);
			if($arr[0]=="0"){
				$data["data"]["keyword4"]["value"]=$r["User_".$arr[1]];
			}else{
				$data["data"]["keyword4"]["value"]=$arr[1];
			}
		}
		
		if(empty($json["keyword5"])){
			$data["data"]["keyword5"] = array(
				"value"=>$r["User_No"],
				"color"=>"#000"
			);
		}else{
			$data["data"]["keyword5"]["color"]="#000";
			$arr = explode("|",$json["keyword5"]);
			if($arr[0]=="0"){
				$data["data"]["keyword5"]["value"]=$r["User_".$arr[1]];
			}else{
				$data["data"]["keyword5"]["value"]=$arr[1];
			}
		}
		
		if(empty($json["remark"])){
			$data["data"]["remark"] = array(
				"value"=>'点击进入会员中心',
				"color"=>"#000"
			);
		}else{
			$data["data"]["remark"]["color"]="#000";
			$arr = explode("|",$json["remark"]);
			if($arr[0]=="0"){
				$data["data"]["remark"]["value"]=$r["User_".$arr[1]];
			}else{
				$data["data"]["remark"]["value"]=$arr[1];
			}
		}
		if($openid_user){
			if(empty($json["first"])){
				$data["data"]["first"] = array(
					"value"=>'欢迎成为会员',
					"color"=>"#000"
				);
			}else{
				$data["data"]["first"]["color"]="#000";
				$arr = explode("|",$json["first"]);
				if($arr[0]=="0"){
					$data["data"]["first"]["value"]=$r["User_".$arr[1]];
				}else{
					$data["data"]["first"]["value"]=$arr[1];
				}
			}
			$data["touser"] = $openid_user;
			$data["url"] = $url;
			$mess = send_message_tem($access_token, $data);
			
		}
		if($openid_ower){
			$data["data"]["first"] = array(
				"value"=>'您分销帐号下有新会员加入',
				"color"=>"#000"
			);
			$data["touser"] = $openid_ower;
			$data["url"] = '';
			$mess = send_message_tem($access_token, $data);
			return $mess;
		}
	}
	return true;
}

function user_order_tem($orderid,$id,$url){
	global $DB1;
	$data = array();
	$r = $DB1->GetRs("user_order","*","where Order_ID=".$orderid." and Order_Status in(0,1)");
	if($r){
		//$r["Order_CreateTime"] = date("Y-m-d H:i:s",$r["Order_CreateTime"]);
		$r["Order_Name"] = $r["Address_Name"];
		$r["Order_Mobile"] = $r["Address_Mobile"];
		$r["Order_Detailed"] = $r["Address_Detailed"];
		$shipping = '';
		if(empty($r["Order_Shipping"])){
			$shipping = "暂无信息";
		}else{
			$Shippings = json_decode(htmlspecialchars_decode($r["Order_Shipping"]),true);
			if(empty($Shippings["Express"])){
				$shipping = "暂无信息";			   
			}else{
				$shipping = $Shippings["Express"];
			}
		}
		$r["Order_Shipping"] = $shipping;
		$r["Order_Qty"] = 0;
		$cartlist = '';
		$CartList=json_decode(htmlspecialchars_decode($r["Order_CartList"]),true);
		if($id==3){
			foreach($CartList as $key=>$value){				
				$r["Order_Qty"]+=$value["Qty"];
				$cartlist .= $value["ProductsName"];
			}
		}else{
			foreach($CartList as $key=>$value){
				foreach($value as $k=>$v){
					$r["Order_Qty"]+=$v["Qty"];
					$cartlist .= $v["ProductsName"];
				}
			}
		}
		$r["Order_CartList"] = $cartlist;
		
		//获取会员openid
		$openid_user = get_openid_tem($r["Users_ID"],$r["User_ID"]);
		//return $openid_user;
		//获取分销商openid
		$owner = $DB1->GetRs("user","Owner_Id","where Users_ID='".$r["Users_ID"]."' and  User_ID=".$r["User_ID"]);
		$openid_owner = get_openid_tem($r["Users_ID"],$owner["Owner_Id"]);
		
		$access_token = get_token_tem($r["Users_ID"]);
		
		
		$t = $DB1->GetRs("message_template","*","where Users_ID='".$r["Users_ID"]."' and Model_ID=".$id." order by Template_CreateTime desc");
		
		if(($openid_user || $openid_owner) && $access_token && $t){
			$json = $t["Template_Json"] ? json_decode($t["Template_Json"],true) : array();			
			$data["template_id"] = $t["Template_LinkID"];			
			$data["topcolor"] = "#FF0000";
			
			
			if(empty($json["keyword1"])){
				$data["data"]["keyword1"] = array(
					"value"=>$r["Order_CartList"],
					"color"=>"#000"
				);
			}else{
				$data["data"]["keyword1"]["color"]="#111";
				$arr = explode("|",$json["keyword1"]);
				if($arr[0]=="0"){
					$data["data"]["keyword1"]["value"]=$r["Order_".$arr[1]];
				}else{
					$data["data"]["keyword1"]["value"]=$arr[1];
				}
			}
			
			
			if(empty($json["keyword2"])){
				$data["data"]["keyword2"] = array(
					"value"=>$r["Order_TotalPrice"],
					"color"=>"#000"
				);
			}else{
				$data["data"]["keyword2"]["color"]="#111";
				$arr = explode("|",$json["keyword2"]);
				if($arr[0]=="0"){
					$data["data"]["keyword2"]["value"]=$r["Order_".$arr[1]];
				}else{
					$data["data"]["keyword2"]["value"]=$arr[1];
				}
			}
			
			if(empty($json["keyword3"])){
				$data["data"]["keyword3"] = array(
					"value"=>date("Y-m-d H:i:s",$r["Order_CreateTime"]),
					"color"=>"#000"
				);
			}else{
				$data["data"]["keyword3"]["color"]="#111";
				$arr = explode("|",$json["keyword3"]);
				if($arr[0]=="0"){
					$data["data"]["keyword3"]["value"]=date("Y-m-d H:i:s",$r["Order_".$arr[1]]);
				}else{
					$data["data"]["keyword3"]["value"]=$arr[1];
				}
			}
			if(empty($json["keyword4"])){
					$data["data"]["keyword4"] = array(
						"value"=>date("Ymd",$r['Order_CreateTime']).$r["Order_ID"],
						"color"=>"#000"
					);
				}else{
					$data["data"]["keyword4"]["color"]="#000";
					$arr = explode("|",$json["keyword4"]);
					if($arr[0]=="0"){
						$data["data"]["keyword4"]["value"]=date("Ymd",$r['Order_CreateTime']).$r["Order_".$arr[1]];
					}else{
						$data["data"]["keyword4"]["value"]=$arr[1];
					}
				}
			
			
			if(empty($json["remark"])){
				$data["data"]["remark"] = array(
					"value"=>'点击查看详情',
					"color"=>"#000"
				);
			}else{
				$data["data"]["remark"]["color"]="#333";
				$arr = explode("|",$json["remark"]);
				if($arr[0]=="0"){
					$data["data"]["remark"]["value"]=$r["Order_".$arr[1]];
				}else{
					$data["data"]["remark"]["value"]=$arr[1];
				}
			}
			
			 if($openid_user){
				if(empty($json["first"])){
					$data["data"]["first"] = array(
						"value"=>'您的订单已提交成功！',
						"color"=>"#000"
					);
				}else{
					$data["data"]["first"]["color"]="#000";
					$arr = explode("|",$json["first"]);
					if($arr[0]=="0"){
						$data["data"]["first"]["value"]=$r["Order_".$arr[1]];
					}else{
						$data["data"]["first"]["value"]=$arr[1];
					}
				}
				$data["touser"] = $openid_user;
				$data["url"] = $url;
				send_message_tem($access_token, $data);
				
			} 
			if($openid_owner){
				
					$data["data"]["first"] = (array(
						"value"=>'您的下级会员有新订单了! ',
						"color"=>"#111"
					));
				
				$data["touser"] = $openid_owner;
				$data["url"] = "";
				
				send_message_tem($access_token, $data);
				
			}
			
		}
	}
	return true;
}
	function deliver_message_tem($OrderID,$id,$url){		
		global $DB1;		
		$data = array();
		$r = $DB1->GetRs("user_order","*","where Order_ID=".$OrderID." and Order_Status>=3");
	
		if($r){
			$r["Order_CreateTime"] = date("Ymd",$r["Order_CreateTime"]);
			$r["Order_Name"] = $r["Address_Name"];
			$r["Order_Mobile"] = $r["Address_Mobile"];
			$r["Order_Detailed"] = $r["Address_Detailed"];
			$shipping = '';
			if(empty($r["Order_Shipping"])){
				$shipping = "暂无信息";
			}else{
				$Shippings = json_decode(htmlspecialchars_decode($r["Order_Shipping"]),true);
				if(empty($Shippings["Express"])){
					$shipping = "暂无信息";			   
				}else{
					$shipping = $Shippings["Express"];
				}
			}
			$r["Order_Shipping"] = $shipping;
			$r["Order_Qty"] = 0;
			$cartlist = '';
			$CartList=json_decode(htmlspecialchars_decode($r["Order_CartList"]),true);
			if($id==3){
				foreach($CartList as $key=>$value){				
					$r["Order_Qty"]+=$value["Qty"];
					$cartlist .= $value["ProductsName"];
				}
			}else{
				foreach($CartList as $key=>$value){
					foreach($value as $k=>$v){
						$r["Order_Qty"]+=$v["Qty"];
						$cartlist .= $v["ProductsName"];
					}
				}
			}
			$r["Order_CartList"] = $cartlist;		
		    $openid = get_openid_tem($r['Users_ID'],$r['User_ID']);
			$access_token = get_token_tem($r['Users_ID']);		
			
			$t = $DB1->GetRs("message_template","*","where Users_ID='".$r["Users_ID"]."' and Model_ID=".$id." order by Template_CreateTime desc");
			if($openid && $access_token && $t){
				$json = $t["Template_Json"] ? json_decode($t["Template_Json"],true) : array();
				$data["touser"] = $openid;
				$data["template_id"] = $t["Template_LinkID"];
				$data["url"] = $url;
				$data["topcolor"] = "#FF0000";
				if(empty($json["first"])){
					$data["data"]["first"] = array(
						"value"=>'您的商品已发货',
						"color"=>"#40C0FA"
					);
				}else{
					$data["data"]["first"]["color"]="#000";
					$arr = explode("|",$json["first"]);
					if($arr[0]=="0"){
						$data["data"]["first"]["value"]=$r["Order_".$arr[1]];
					}else{
						$data["data"]["first"]["value"]=$arr[1];
					}
				}
				
				if(empty($json["keyword1"])){
					$data["data"]["keyword1"] = array(
						"value"=>$r['Order_CreateTime'].$r["Order_ID"],
						"color"=>"#000"
					);
				}else{
					$data["data"]["keyword1"]["color"]="#000";
					$arr = explode("|",$json["keyword1"]);
					if($arr[0]=="0"){
						$data["data"]["keyword1"]["value"]=$r['Order_CreateTime'].$r["Order_".$arr[1]];
					}else{
						$data["data"]["keyword1"]["value"]=$arr[1];
					}
				}
				if(empty($json["keyword2"])){
					$data["data"]["keyword2"] = array(
						"value"=>$r["Order_CartList"],
						"color"=>"#000"
					);
				}else{
					$data["data"]["keyword2"]["color"]="#000";
					$arr = explode("|",$json["keyword2"]);
					if($arr[0]=="0"){
						$data["data"]["keyword2"]["value"]=$r["Order_".$arr[1]];
					}else{
						$data["data"]["keyword2"]["value"]=$arr[1];
					}
				}
				
				if(empty($json["keyword3"])){
					$data["data"]["keyword3"] = array(
						"value"=>$r["Order_TotalPrice"],
						"color"=>"#000"
					);
				}else{
					$data["data"]["keyword3"]["color"]="#000";
					$arr = explode("|",$json["keyword3"]);
					if($arr[0]=="0"){
						$data["data"]["keyword3"]["value"]=$r["Order_".$arr[1]];
					}else{
						$data["data"]["keyword3"]["value"]=$arr[1];
					}
				}
				
				if(empty($json["remark"])){
					$data["data"]["remark"] = array(
						"value"=>'点击查看详情',
						"color"=>"#000"
					);
				}else{
					$data["data"]["remark"]["color"]="#000";
					$arr = explode("|",$json["remark"]);
					if($arr[0]=="0"){
						$data["data"]["remark"]["value"]=$r["Order_".$arr[1]];
					}else{
						$data["data"]["remark"]["value"]=$arr[1];
					}
				}			
				send_message_tem($access_token, $data);
			}
		}
		return true;
	}
	
 function user_integral_message_tem($usersid,$userid,$val,$url=''){
	$url = "http://distribute.wybao.cn/api/".$usersid."/user/gift/1/?wxref=mp.weixin.qq.com";
	global $DB1;
	$data = array();
	$mess = '';
	$access_token = get_token_tem($usersid);
	$t = $DB1->GetRs("message_template","*","where Users_ID='".$usersid."' and Model_ID=10 order by Template_CreateTime desc");
	$r = $DB1->GetRs("user","*","where Users_ID='".$usersid."' and User_ID=".$userid);
	$s = $DB1->GetRs("user_Integral_record","*","where Users_ID='".$usersid."' and User_ID=".$userid." order by Record_CreateTime desc");
	//获取会员openid
	$openid_user = get_openid_tem($r["Users_ID"],$userid);
	if($openid_user && $access_token && $t && $r){
		
		$json = $t["Template_Json"] ? json_decode($t["Template_Json"],true) : array();
		$data["template_id"] = $t["Template_LinkID"];
		$data["topcolor"] = "#FF0000";

		if(empty($json["keyword1"])){
			$data["data"]["keyword1"] = array(
				"value"=>$r["User_NickName"],
				"color"=>"#000"
			);
		}else{
			$data["data"]["keyword1"]["color"]="#000";
			$arr = explode("|",$json["keyword1"]);
			if($arr[0]=="0"){
				$data["data"]["keyword1"]["value"]=$r["User_".$arr[1]];
			}else{
				$data["data"]["keyword1"]["value"]=$arr[1];
			}
		}

		if(empty($json["keyword2"])){
			$data["data"]["keyword2"] = array(
				"value"=>date("Y-m-d H:i:s",time()),
				"color"=>"#000"
			);
		} else{
			$data["data"]["keyword2"]["color"]="#000";
			$arr = explode("|",$json["keyword2"]);
			if($arr[0]=="0"){
				$data["data"]["keyword2"]["value"] = date("Y-m-d H:i:s",$s["Record_".$arr[1]]);
			}else{
				$data["data"]["keyword2"]["value"]=$arr[1];
			}
		}
		if($val>0){
			$val = "+".$val;
		}
		$data["data"]["keyword3"] = array(
					"value"=>$val,
					"color"=>"#D11B0E"
			);
		/*if(empty($json["keyword3"])){
			$data["data"]["keyword3"] = array(
					"value"=>$val,
					"color"=>"#000"
			);
		} else{
			$data["data"]["keyword3"]["color"]="#000";
			$arr = explode("|",$json["keyword3"]);
			if($arr[0]=="0"){
				$data["data"]["keyword3"]["value"]=$r["User_".$arr[1]];
			}else{
				$data["data"]["keyword3"]["value"]=$arr[1];
			}
		} */

		if(empty($json["keyword4"])){
			$data["data"]["keyword4"] = array(
				"value"=>empty($r['User_integral']) ? 0 : $r['User_integral'],
				"color"=>"#000"
			);
		} else{
			$data["data"]["keyword4"]["color"]="#000";
			$arr = explode("|",$json["keyword4"]);
			if($arr[0]=="0"){
				$data["data"]["keyword4"]["value"]=$r["User_".$arr[1]];
			}else{
				$data["data"]["keyword4"]["value"]=$arr[1];
			}
		}
		
		if(empty($json["keyword5"])){
			$data["data"]["keyword5"] = array(
				"value"=>"系统更改",
				"color"=>"#000"
			);
		} else{
			$data["data"]["keyword5"]["color"]="#000";
			$arr = explode("|",$json["keyword5"]);
			if($arr[0]=="0"){
				$data["data"]["keyword5"]["value"]=$s["Record_".$arr[1]];
			}else{
				$data["data"]["keyword5"]["value"]=$arr[1];
			}
		}
		
		if(empty($json["remark"])){
			$data["data"]["remark"] = array(
				"value"=>$url,
				"color"=>"#000"
			);
		}else{
			$data["data"]["remark"]["color"]="#000";
			$arr = explode("|",$json["remark"]);
			if($arr[0]=="0"){
				$data["data"]["remark"]["value"]=$r["User_".$arr[1]];
			}else{
				$data["data"]["remark"]["value"]=$arr[1];
			}
		}

		if(empty($json["first"])){
			$data["data"]["first"] = array(
				"value"=>'您的积分有变动',
				"color"=>"#000"
			);
		}else{
			$data["data"]["first"]["color"]="#000";
			$arr = explode("|",$json["first"]);
			if($arr[0]=="0"){
				$data["data"]["first"]["value"]=$r["User_".$arr[1]];
			}else{
				$data["data"]["first"]["value"]=$arr[1];
			}
		}

		$data["touser"] = $openid_user;
		$data["url"] = $url;
		$mess = send_message_tem($access_token, $data);
		
	}
	return $mess;
}
	//物流
	function Logistics($type,$postid){
		$url = 'http://m.kuaidi100.com/index_all.html?type='.$type.'&postid='.$postid.'&callbackurl='.'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
		return $url;
	} 

?>