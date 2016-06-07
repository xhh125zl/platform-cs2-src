<?php
class weixin_message{
	var $db;
	var $usersid;
	var $userid;
	var $access_token;

	function __construct($DB,$usersid,$userid){
		$this->db = $DB;
		$this->usersid = $usersid;
		$this->userid = $userid;
	}
	
	public function sendmessage($openid,$contentStr){//单用户发送
		$this->get_access_token_self();
		if($this->access_token){
			$postdata = array(
				"touser"=>$openid,
				"msgtype"=>"text",
				"text"=>array(
					"content"=>$contentStr
				)
			);
			$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$this->access_token;
			$postdata = json_encode($postdata,JSON_UNESCAPED_UNICODE);
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
			curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			$res = curl_exec($ch);
			curl_close($ch);
			$message = $contentStr;
			$this->db->Add("weixin_log",array("message"=>$message.' _ '.$res.' _ '.$openid));
		}
    }
	
	public function sendmess($openid_array,$contentStr){//群发消息
		$this->get_access_token_self();
		if($this->access_token){
			$postdata = array(
				"touser"=>$openid_array,
				"msgtype"=>"text",
				"text"=>array(
					"content"=>$contentStr
				)
			);
			$url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".$this->access_token;
			$postdata = json_encode($postdata,JSON_UNESCAPED_UNICODE);
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
			curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			$res = curl_exec($ch);
			curl_close($ch);
			//$message = $contentStr;
			//$this->db->Add("weixin_log",array("message"=>$message.' _ '.$res.' _ '.$openid));
		}
    }
	
	private function get_access_token_self(){
		$this->access_token = "";
		require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_token.class.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
		$weixin_token = new weixin_token($this->db,$this->usersid);
		$this->access_token = $weixin_token->get_access_token();
	}
	
	private function getownerinfo($uid){
		if($uid>0){
			$u = $this->db->GetRs("user","Owner_Id,User_OpenID,User_NickName","where Users_ID='".$this->usersid."' and User_ID=".$uid);
			if($u){
				$data = array(
					"Owner_Id"=>$u["Owner_Id"],
					"User_OpenID"=>$u["User_OpenID"],
					"User_NickName"=>$u["User_NickName"]
				);
				$account = $this->db->GetRs("distribute_account","Enable_Tixian","where Users_ID='".$this->usersid."' and User_ID=".$uid);
				
				if($account["Enable_Tixian"]==1){
					$data["boss"] = 1;
				}else{
					$data["boss"] = 0;
				}
				
				return $data;
			}else{
				return "";
			}
		}else{
			return "";
		}
	}
	
	private function get_bonus($orderid,$uid){
		
		$ds_account_record = Order::find($orderid)->disAccountRecord()->getResults();
		$bonus = 0;	
		if(!empty($ds_account_record)){
			$records = $ds_account_record->filter(function($record) use($uid){
				$res = ($record->User_ID == $uid);
				return $res;
			});
			if($records->count() >0){
			$bonus = $records->sum('Record_Money');
			}
		
		}
		
		return round_pad_zero($bonus,2);

	}
	
	public function sendmember(){
		$u0 = $this->db->GetRs("user","Owner_Id,User_NickName","where Users_ID='".$this->usersid."' and User_ID=".$this->userid);
		$name = $u0["User_NickName"];
		$u1 = $this->getownerinfo($u0["Owner_Id"]);
		if(is_array($u1)){
			$text = "您的一级会员".$name."关注了本公众号";			
			$this->sendmessage($u1["User_OpenID"],$text);
			$u2 = $this->getownerinfo($u1["Owner_Id"]);
			if(is_array($u2)){
				$text = "您的二级会员".$name."关注了本公众号";
				$this->sendmessage($u2["User_OpenID"],$text);
				$u3 = $this->getownerinfo($u2["Owner_Id"]);
				if(is_array($u3)){
					$text = "您的三级会员".$name."关注了本公众号";
					$this->sendmessage($u3["User_OpenID"],$text);
				}
			}
		}
	}
	
	public function sendorder($money,$orderid){//佣金提醒
		$rsConfig = $this->db->GetRs("distribute_config","Withdraw_Type,Withdraw_Limit","where Users_ID='".$this->usersid."'");
		$msg = '';
		
		$u0 = $this->db->GetRs("user","Owner_Id,User_NickName,User_OpenID","where Users_ID='".$this->usersid."' and User_ID=".$this->userid);
		//自销
		$self_bonus = $this->get_bonus($orderid, $this->userid);
		if($self_bonus>0){
			$text = "您下单成功，支付了".$money."元，您将获取佣金".$self_bonus."元";
			$this->sendmessage($u0["User_OpenID"],$text);
		}
		
		
		$name = $u0["User_NickName"];
		$u1 = $this->getownerinfo($u0["Owner_Id"]);
		$b1 = $b2 = 1;
		if(is_array($u1)){
			$bonus = $this->get_bonus($orderid, $u0["Owner_Id"]);
			$text = "您推荐的一级会员".$name."下单成功，支付了".$money."元，您将获取佣金".$bonus."元";
			$this->sendmessage($u1["User_OpenID"],$text);
			$u2 = $this->getownerinfo($u1["Owner_Id"]);
			if(is_array($u2)){
				$bonus = $this->get_bonus($orderid, $u1["Owner_Id"]);
				$text = "您推荐的二级会员".$name."下单成功，支付了".$money."元，您将获取佣金".$bonus."元";
				$this->sendmessage($u2["User_OpenID"],$text);
				
				$u3 = $this->getownerinfo($u2["Owner_Id"]);
				if(is_array($u3)){
					$bonus = $this->get_bonus($orderid, $u2["Owner_Id"]);
					$text = "您推荐的三级会员".$name."下单成功，支付了".$money."元，您将获取佣金".$bonus."元";
					$this->sendmessage($u3["User_OpenID"],$text);
				}
			}
		}
	}
	
	public function sendorder_distribute($money,$orderid){//购买分销商佣金提醒
		$arr = array('','一','二','三','四','五','六','七','八','九');
		$this->db->query('select u.User_NickName,o.Order_Type,o.Level_Name from user as u left join distribute_order as o on o.User_ID=u.User_ID where o.Order_ID='.$orderid);
		$rsUserList = $rsUser = array();
		while($r = $this->db->fetch_assoc()){
			$rsUserList[] = $r;
		}
		if(empty($rsUserList)){
			return true;
		}
		$rsUser = $rsUserList[0];
		
		$msg = '';
		$lists = array();
		$this->db->query('select u.User_OpenID,o.level,o.Record_Money from user as u left join distribute_order_record as o on o.User_ID=u.User_ID where o.Order_ID='.$orderid.' and o.Record_Money>0');
		while($r_temp = $this->db->fetch_assoc()){
			$lists[] = $r_temp;
		}
		foreach($lists as $key=>$rr){
			$msg = "您推荐的".$arr[$rr['level']]."级会员".$rsUser['User_NickName'].($rsUser['Order_Type']==0 ? '购买成为' : '升级成为').$rsUser['Level_Name']."，支付了".$money."元，您将获取佣金".$rr['Record_Money']."元";
			$this->sendmessage($rr["User_OpenID"],$msg);
		}		
	}
	
	public function sendordernotice(){
		$diff = time() - 1200;
		$diff1 = time() - 600;
		
		$this->db->Get("user_order","User_ID,Order_ID","where Order_Status=1 and Message_Notice=0 and Order_CreateTime>=".$diff." and Order_CreateTime<=".$diff1);
		$lists = array();
		$users = array();
		while($r=$this->db->fetch_assoc()){
			$lists[] = $r;		
		}
		foreach($lists as $v){
			if(!in_array($v["User_ID"],$users)){
				$users[] = $v["User_ID"];
			}
			$this->db->Set("user_order",array("Message_Notice"=>1),"where Order_ID=".$v["Order_ID"]);	
		}
		
		foreach($users as $u){
			$usertemp = $this->db->GetRs("user","User_OpenID,Users_ID","where User_ID=".$u);
			if(!empty($usertemp["User_OpenID"])){
				$text = '您购买的商品还未付款，如需付款请<a href="http://'.$_SERVER["HTTP_HOST"].'/api/'.$usertemp["Users_ID"].'/shop/member/status/1/">点击付款</a>';
				$this->sendmessage($usertemp["User_OpenID"],$text);
			}
		}
	}
	
	private function get_user_openid(){
		$r = $this->db->GetRs("user","User_OpenID","where Users_ID='".$this->usersid."' and User_ID=".$this->userid);
		return $r ? $r["User_OpenID"] : '';
	}
	
	public function sendscorenotice($content){
		//$openid = $this->get_user_openid();
		//if($openid){
		//	$this->sendmessage($openid,$content);
		//}
	}
}
?>
