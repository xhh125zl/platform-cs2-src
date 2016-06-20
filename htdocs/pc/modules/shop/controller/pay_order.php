<?php
namespace shop\controller;
class pay_orderController extends controllController {
	private $orderid;

	function __construct($orderid){
		parent::_initialize();
		$this->orderid = $orderid;
	}
	
	private function get_order($orderid){
		$r = model('user_order')->where(array('Order_ID'=>$orderid))->find();
		return $r;
	}
	
	private function get_user($userid){
		$r = model('user')->where(array('User_ID'=>$userid))->find();
		return $r;
	}
	
	private function get_products($pid){
		$r = model('shop_products')->where(array('Products_ID'=>$pid))->find();
		return $r;
	}
	
	private function update_order($orderid, $data){
		$r = model('user_order')->where(array('Order_ID'=>$orderid))->update($data);
		return $r;
	}
	
	private function pay_orders($orderid) {
		$rsOrder = $this->get_order($orderid);
		$rsUser = $this->get_user($rsOrder['user_id']);
		
		if(!$rsOrder){
			return array('status'=>0,'msg'=>'订单不存在');
		}
		$url = url('member/status',array('Status'=>$rsOrder['order_status']));
		
		if($rsOrder['order_status'] <> 1){
			return array('status'=>1,'url'=>$url);
		}
		//更新订单状态
		$Data = array(
			'Order_Status' => 2
		);
		$this->update_order($orderid, $Data);
		if(strpos($rsOrder['order_type'], 'zhongchou') >- 1){
			$url = url('zhongchou/orders');
			return array('status'=>1,'url'=>$url);
		}elseif($rsOrder['order_type'] == 'kanjia'){
			$url = url('kanjia_order/status', array('Status'=>2));
			return array('status'=>1, 'url'=>$url);
		}
		//积分抵用
		$Flag_b = true;
		if($rsOrder['integral_consumption'] > 0 ) {
			$Flag_b = change_user_integral($rsOrder['users_id'], $rsOrder['user_id'], $rsOrder['integral_consumption'], 'reduce', '积分抵用消耗积分');
		}
		
		//更改分销账号记录状态,置为已付款
		$Flag_c = change_dsaccount_record_status($orderid, 1);
		handle_products_count($rsOrder['users_id'], $rsOrder);
		
		if($Flag_b && $Flag_c) {
			$isvirtual = $rsOrder['order_isvirtual'];
			$url = url('member/status', array('Status'=>2));			
			$cartList = json_decode(htmlspecialchars_decode($rsOrder['order_cartlist']), true);
			
			//判定用户是不是分销商并处理
			if($rsUser['is_distribute'] == 0) {//不是分销商
				$distribute_enabled = 0;
				if($this->shopConfig['distribute_type'] == 3) {
					$arr_temp = explode('|', $this->shopConfig['distribute_limit']);
					if($arr_temp[0] == 0) {//任意商品
						$distribute_enabled = 1;
					}else {//特定商品
						if(!empty($arr_temp[1])) {
							$productsid = explode(',', $arr_temp[1]);
							foreach($productsid as $id){
								if(!empty($cartList[$id])) {
									$distribute_enabled = 1;
									break;
								}
							}
						}
					}
				}
				if($distribute_enabled == 1) {
					$truename = $rsUser['user_name'] ? $rsUser['user_name'] : ($rsUser['user_nickname'] ? $rsUser['user_nickname'] : '真实姓名');
					$owner['id'] = $rsUser['owner_id'];					
					create_distribute_acccount($this->shopConfig, $rsOrder['user_id'], $truename, $owner['id'], '', 1);
				}
			}else {//是分销商判定可提现条件
				$tixian = 0;

				$rsAccount = model('distribute_account')->field('User_ID,Enable_Tixian,Account_ID,Is_Dongjie,Is_Delete,Users_ID')->where(array('Users_ID'=>$rsOrder['users_id'],'User_ID'=>$rsOrder['user_id']))->find();

				if($rsAccount) {
					if($rsAccount['Enable_Tixian'] == 0) {
						if($this->shopConfig['withdraw_type'] == 0) {
							$tixian = 1;
						}elseif($this->shopConfig['withdraw_type'] == 2) {
							$arr_temp = explode('|', $this->shopConfig['withdraw_limit']);
							if($arr_temp[0] == 0) {
								$tixian = 1;
							}else {
								if(!empty($arr_temp[1])) {
									$productsid = explode(',', $arr_temp[1]);
									foreach($productsid as $id) {
										if(!empty($cartList[$id])) {
											$tixian = 1;
											break;
										}
									}
								}
							}
						}
						if($tixian == 1) {

							model('distribute_account')->where(array('Users_ID'=>$rsOrder['users_id'],'Account_ID'=>$rsAccount['Account_ID']))->update(array('Enable_Tixian'=>1));

						}
					}
					
					if($this->shopConfig['fuxiao_open'] == 1 && $rsAccount['Is_Delete'] == 0){//开启复销功能
						distribute_fuxiao_return_action($this->shopConfig['fuxiao_rules'], $rsAccount, $rsUser['user_openid']);//是否达到复销要求并处理
					}
				}
			}
			
			$confirm_code = '';
			if($rsOrder['order_isvirtual'] == 1) {
				if($rsOrder['order_isrecieve'] == 1) {
					//确认收货(购买成为老板、佣金变可提现)
					$flag_a = model('user_order')->where(array('Order_ID'=>$orderid))->update(['Order_Status' => 4]);
					if($flag_a){
						$user_config = model('user_config')->where(array('Users_ID'=>$rsOrder['users_id']))->find();
						$OrderObserver = new \shop\logic\OrderObserver();
						$OrderObserver->shop_config = $this->shopConfig;
						$OrderObserver->user_config = $user_config;
						$OrderObserver->confirmed($rsOrder);
					}
					$url = url('member/status', array('Status'=>4));
				}else {
					$confirm_code = get_virtual_confirm_code($rsOrder['users_id']);//生成消费码
					$Data = array('Order_Code'=>$confirm_code);
					$this->update_order($orderid, $Data);
				}
			}
			$setting = model('setting')->field('sms_enabled')->where(array('id'=>1))->find();
			if($this->shopConfig['sendsms'] == 1 && $setting['sms_enabled'] == 1) {
			    if($this->shopConfig['mobilephone']){
					$sms_mess = '您的商品有订单付款，订单号' . $orderid . '请及时查看！';
					send_sms($this->shopConfig['mobilephone'], $sms_mess, $rsOrder['users_id']);
				}
				$rsBiz = model('biz')->field('Biz_SmsPhone')->where(array('Biz_ID'=>$rsOrder['biz_id']))->find();
				if($rsBiz){
					if($rsBiz['Biz_SmsPhone']) {
						$sms_mess = '您的商品有订单付款，订单号' . $orderid . '请及时查看！';
						send_sms($rsBiz['Biz_SmsPhone'], $sms_mess, $rsOrder['users_id']);
					}
                }				
				if($rsOrder['order_isvirtual'] == 1 && $rsOrder['order_isrecieve'] == 0) {
					$sms_mess = '您已成功购买商品，订单号' . $orderid . '，消费券码为 ' . $confirm_code;
					send_sms($rsOrder['address_mobile'], $sms_mess, $rsOrder['users_id']);
				}
			}
			//发信息  
			$weixin_message = new \shop\logic\weixin_message($rsOrder['users_id'], $rsOrder['user_id']);
			$weixin_message->sendorder($rsOrder['order_totalprice'], $orderid);
			return array('status'=>1, 'url'=>$url);
		}else{
			return array('status'=>0, 'msg'=>'订单支付失败');
		}
	}
	
	public function make_pay() {
		if(strpos($this->orderid, 'PRE') > -1){
			$pre_order = model('user_pre_order')->field('orderids')->where(array('pre_sn'=>$this->orderid))->find();
			$orderids = explode(',', $pre_order['orderids']);
			foreach($orderids as $orderid){
				if(!$orderid) {
					continue;
				}
				$data = $this->pay_orders($orderid);
			}
			model('user_pre_order')->where(array('pre_sn'=>$this->orderid))->update(array('status'=>2));
		}else {
			$data = $this->pay_orders($this->orderid);
		}
		return $data;
	}
	
	public function get_pay_info() {
		if(strpos($this->orderid, 'PRE') >- 1){
			$pre_order = model('user_pre_order')->where(array('pre_sn'=>$this->orderid))->find();
			$data = array(
				'out_trade_no'=>$this->orderid,
				'subject'=>'微商城在线付款，订单编号:' . $pre_order['orderids'],
				'total_fee'=>$pre_order['total']
			);
		}else{
			$orderinfo = $this->get_order($this->orderid);
			if(!strpos($orderinfo['Order_Type'], 'zhongchou')){
				if($orderinfo['Order_Type'] == 'weicbd'){
					$pay_subject = '微商圈在线付款，订单编号:' . $this->orderid;
				}elseif($orderinfo['Order_Type'] == 'kanjia'){
					$pay_subject = '微砍价在线付款，订单编号:' . $this->orderid;
				}else{
					$pay_subject = '微商城在线付款，订单编号:' . $this->orderid;
				}
			}else {
				$pay_subject = '微众筹在线付款，订单编号:' . $this->orderid;
			}
			$data = array(
				'out_trade_no'=>$orderinfo['Order_CreateTime'] . $this->orderid,
				'subject'=>$pay_subject,
				'total_fee'=>$orderinfo['Order_TotalPrice']
			);
		}
		return $data;
	}
}
?>