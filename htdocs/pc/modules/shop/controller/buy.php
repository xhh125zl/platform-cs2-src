<?php
namespace shop\controller;
class buyController extends controllController {
	public function __construct() {
		parent::_initialize();
		$this->check_login();
	}
	
	public function cartOp() {
		$this->assign('title', '购物车');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('buy/cart');
		$this->display('cart.php', 'home', 'home_layout');
	}
	
	public function order_realOp() {
		$this->assign('title', '提交订单');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('buy/order_real');
		//获得用户地址
		$condition = array(
			'Users_ID' => $this->UsersID,
		    'User_ID' => $_SESSION[$this->UsersID . 'User_ID'],
			'Address_Is_Default' => 1
		);
		$rsAddress = model('user_address')->where($condition)->find();
		$area_json = read_file(SITE_PATH . '/data/area.js');
		$area_array = json_decode($area_json, TRUE);
		$province_list = $area_array[0];
		if($rsAddress) {
			$rsAddress['Province'] = $province_list[$rsAddress['address_province']];
			$rsAddress['City'] = $area_array['0,'.$rsAddress['address_province']][$rsAddress['address_city']];
			$rsAddress['Area'] = $area_array['0,'.$rsAddress['address_province'].','.$rsAddress['address_city']][$rsAddress['address_area']];
		}else {
			$_SESSION[$this->UsersID . 'Address_Return_Url'] = url('buy/order_real');
			header('location:' . url('member/address'));exit;
		}
		$this->assign('address_info', $rsAddress);
		
		if(!empty($_SESSION[$this->UsersID . 'CartList'])) {
			$cartList = json_decode($_SESSION[$this->UsersID . 'CartList'], true);
			$this->assign('cartList', $cartList);
		}else {
			$this->error('购物车为空！赶快重新下单吧');
		}
		$info = get_order_total_info($this->UsersID, $cartList, array(), $rsAddress['address_city']);
		/*商品信息汇总*/
		$this->assign('order_total_info', $info);
		$this->display('order_real.php', 'home', 'home_layout');
	}
	public function order_directbuyOp() {
		$this->assign('title', '立即购买-提交订单');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('buy/order_directbuy');
		//获得用户地址
		$condition = array(
			'Users_ID'=>$this->UsersID,
		    'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],
			'Address_Is_Default'=>1
		);
		$rsAddress = model('user_address')->where($condition)->find();
		$area_json = read_file(SITE_PATH.'/data/area.js');
		$area_array = json_decode($area_json, TRUE);
		$province_list = $area_array[0];
		if($rsAddress) {
			$rsAddress['Province'] = $province_list[$rsAddress['address_province']];
			$rsAddress['City'] = $area_array['0,'.$rsAddress['address_province']][$rsAddress['address_city']];
			$rsAddress['Area'] = $area_array['0,'.$rsAddress['address_province'].','.$rsAddress['address_city']][$rsAddress['address_area']];
		}else {
			$_SESSION[$this->UsersID . 'Address_Return_Url'] = url('buy/order_directbuy');
			header('location:' . url('member/address'));exit;
		}
		$this->assign('address_info', $rsAddress);
		if(!empty($_SESSION[$this->UsersID . 'DirectBuy'])) {
			$cartList = json_decode($_SESSION[$this->UsersID . 'DirectBuy'], true);
			$this->assign('cartList', $cartList);
		}else {
			$this->error('订单过期！赶快重新下单吧');
		}
		
		$info = get_order_total_info($this->UsersID, $cartList, array(), $rsAddress['address_city']);
		/*商品信息汇总*/
		$this->assign('order_total_info', $info);
		$this->display('order_directbuy.php', 'home', 'home_layout');
	}
	public function order_virtualOp() {
		$this->assign('title', '提交订单');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('buy/order_directbuy');
		//获取产品列表
		if(!empty($_SESSION[$this->UsersID . 'Virtual'])) {
			$cartList = json_decode($_SESSION[$this->UsersID . 'Virtual'], true);
			$this->assign('cartList', $cartList);
		}else {
			$this->error('订单过期！赶快重新下单吧');
		}
		$info = get_order_total_info($this->UsersID, $cartList, array(), 0);
		/*商品信息汇总*/
		$this->assign('order_total_info', $info);
		$this->display('order_virtual.php', 'home', 'home_layout');
	}
}
?>