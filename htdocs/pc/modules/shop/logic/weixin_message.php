<?php
namespace shop\logic;
class weixin_message{
	private $usersid;
	private $userid;
	private $access_token;

	function __construct($usersid, $userid){
		$this->usersid = $usersid;
		$this->userid = $userid;
	}
	
	public function sendmessage($openid, $contentStr){//单用户发送
	    $rsUser = model('user')->field('User_From,User_ID')->where(array('Users_ID'=>$this->usersid,'User_OpenID'=>$openid))->find();
		 model('pc_user_message')->insert(array('Users_ID'=>$this->usersid,'User_ID'=>$rsUser['User_ID'],'content'=>$contentStr,'CreateTime'=>time()));
	    if($rsUser && $rsUser['User_From'] == 0){
			$this->get_access_token_self();
			if($this->access_token){
				$postdata = array(
					'touser' => $openid,
					'msgtype' => 'text',
					'text' => array(
						'content' => $contentStr
					)
				);
				$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->access_token;
				$postdata = json_encode($postdata, JSON_UNESCAPED_UNICODE);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
				curl_setopt($ch, CURLOPT_HEADER, FALSE);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				$res = curl_exec($ch);
				curl_close($ch);
			}
		}
    }
	
	public function sendmess($openid_array, $contentStr){//群发消息
	    $rsUser = model('user')->field('User_From,User_ID')->where(array('Users_ID'=>$this->usersid,'User_OpenID'=>$openid_array))->select();
		$sql = 'INSERT INTO pc_user_message (Users_ID,User_ID,content,CreateTime) VALUES ';
		foreach($rsUser as $k => $v){
		    if($v['User_From'] != 0){
			    array_splice($openid_array, $k, 1);
			}
		    $values .= '(' . '\'' . $this->usersid . '\',' . $v['User_ID'] . ',\'' . $contentStr . '\',' . time() . '),';
		}
		$sql .= trim($values, ',');
		model()->query($sql);
		if(!empty($openid_array)){
			$this->get_access_token_self();
			if($this->access_token){
				$postdata = array(
					'touser'=>$openid_array,
					'msgtype'=>'text',
					'text'=>array(
						'content'=>$contentStr
					)
				);
				$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$this->access_token;
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
			}
		}
    }
	
	private function get_access_token_self(){
		$this->access_token = '';
		$weixin_token = new \vendor\weixin_token($this->usersid);
		$this->access_token = $weixin_token->get_access_token();
	}
	
	private function getownerinfo($uid){
		if($uid > 0){
		    $u = model('user')->field('Owner_Id,User_OpenID,User_NickName')->where(array('Users_ID'=>$this->usersid,'User_ID'=>$uid))->find();
			if($u){
				$data = array(
					'Owner_Id'=>$u['Owner_Id'],
					'User_OpenID'=>$u['User_OpenID'],
					'User_NickName'=>$u['User_NickName']
				);
				$account = model('shop_distribute_account')->field('Enable_Tixian')->where(array('Users_ID'=>$this->usersid,'User_ID'=>$uid))->find();
				
				if($account['Enable_Tixian']==1){
					$data['boss'] = 1;
				}else{
					$data['boss'] = 0;
				}
				
				return $data;
			}else{
				return '';
			}
		}else{
			return '';
		}
	}
	
	private function get_bonus($orderid, $uid){
		$distribute_account_record_model = model('shop_distribute_account_record');
		$distribute_record_model = model('shop_distribute_record');
		$distribute_records = $distribute_record_model->field('Record_ID')->where(array('Order_ID'=>$orderid))->select();
		$Record_IDS = array();
		foreach($distribute_records as $k => $v) {
			$Record_IDS[] = $v['Record_ID'];
		}
		
		$ds_account_record = $distribute_account_record_model->field('Record_Money')->where(array('Ds_Record_ID'=>$Record_IDS, 'User_ID'=>$uid))->select();
		$bonus = 0;	
		if(!empty($ds_account_record)){
		    foreach($ds_account_record as $k => $v){
			    $bonus += $v['Record_Money'];
			}
		}
		return round_pad_zero($bonus, 2);
	}
	
	public function sendmember(){//威信关注相关
		$u0 = model('user')->field('Owner_Id,User_NickName')->where(array('Users_ID'=>$this->usersid,'User_ID'=>$this->userid))->find();
		$name = $u0['User_NickName'];
		$u1 = $this->getownerinfo($u0['Owner_Id']);
		if(is_array($u1)){
			$text = '您的一级会员'.$name.'关注了本公众号';			
			$this->sendmessage($u1['User_OpenID'],$text);
			$u2 = $this->getownerinfo($u1['Owner_Id']);
			if(is_array($u2)){
				$text = '您的二级会员'.$name.'关注了本公众号';
				$this->sendmessage($u2['User_OpenID'],$text);
				$u3 = $this->getownerinfo($u2['Owner_Id']);
				if(is_array($u3)){
					$text = '您的三级会员'.$name.'关注了本公众号';
					$this->sendmessage($u3['User_OpenID'],$text);
				}
			}
		}
	}
	
	public function sendorder($money, $orderid){//佣金提醒
		$rsConfig = model('shop_config')->field('Withdraw_Type,Withdraw_Limit')->where(array('Users_ID'=>$this->usersid))->find();
		
		$msg = '';

		$u0 = model('user')->field('Owner_Id,User_NickName,User_OpenID,User_From')->where(array('Users_ID'=>$this->usersid,'User_ID'=>$this->userid))->find();
		//自销
		$self_bonus = $this->get_bonus($orderid, $this->userid);
		if($self_bonus > 0){
			$text = '您下单成功，支付了'.$money.'元，您将获取佣金'.$self_bonus.'元';
			$this->sendmessage($u0['User_OpenID'], $text);
		}
		
		
		$name = $u0['User_NickName'];
		$u1 = $this->getownerinfo($u0['Owner_Id']);
		$b1 = $b2 = 1;
		if(is_array($u1)){
			$bonus = $this->get_bonus($orderid, $u0['Owner_Id']);
			$text = '您推荐的一级会员'.$name.'下单成功，支付了'.$money.'元，您将获取佣金'.$bonus.'元';
			$this->sendmessage($u1['User_OpenID'],$text);
			$u2 = $this->getownerinfo($u1['Owner_Id']);
			if(is_array($u2)){
				$bonus = $this->get_bonus($orderid, $u1['Owner_Id']);
				$text = '您推荐的二级会员'.$name.'下单成功，支付了'.$money.'元，您将获取佣金'.$bonus.'元';
				$this->sendmessage($u2['User_OpenID'],$text);
				
				$u3 = $this->getownerinfo($u2['Owner_Id']);
				if(is_array($u3)){
					$bonus = $this->get_bonus($orderid, $u2['Owner_Id']);
					$text = '您推荐的三级会员'.$name.'下单成功，支付了'.$money.'元，您将获取佣金'.$bonus.'元';
					$this->sendmessage($u3['User_OpenID'],$text);
				}
			}
		}
	}
	
	public function sendordernotice(){
	    $user_order = model('user_order');
		$diff = time() - 1200;
		$diff1 = time() - 600;
		$lists = $user_order->field('User_ID,Order_ID')->where(array('Order_Status'=>1,'Message_Notice'=>0,'Order_CreateTime >='=>$diff,'Order_CreateTime <='=>$diff1))->select();
		$users = array();
		foreach($lists as $v){
			if(!in_array($v['User_ID'], $users)){
				$users[] = $v['User_ID'];
			}
			$user_order->where(array('Order_ID'=>$v['Order_ID']))->update(array('Message_Notice'=>1));
		}
		
		foreach($users as $u){
			$usertemp = model('user')->field('User_OpenID,Users_ID')->where(array('User_ID'=>$u))->find();
			if(!empty($usertemp['User_OpenID'])){
				$text = '您购买的商品还未付款，如需付款请<a href="http://'.$_SERVER['HTTP_HOST'].'/api/'.$usertemp['Users_ID'].'/shop/member/status/1/">点击付款</a>';
				$this->sendmessage($usertemp['User_OpenID'], $text);
			}
		}
	}
	
	private function get_user_openid(){
	    $r = model('user')->field('User_OpenID')->where(array('Users_ID'=>$this->usersid,'User_ID'=>$this->userid))->find();
		return $r ? $r['User_OpenID'] : '';
	}
	
	public function sendscorenotice($content){
		$openid = $this->get_user_openid();
		if($openid){
			$this->sendmessage($openid,$content);
		}
	}
}
?>
