<?php
namespace shop\controller;
class paymentController extends controllController {
	public function __construct() {
		parent::_initialize();
		$this->check_login();
	}
	
	public function indexOp() {
		$this->assign('title', '订单支付');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('payment/index');
		if(empty($_GET['OrderID'])) {
			$this->error('缺少必要的参数');
		}else {
			$OrderID = $_GET['OrderID'];
		}
		
		$rsPay = model('users_payconfig')->where(array('Users_ID'=>$this->UsersID))->find();

		if(strpos($OrderID, 'PRE') !== false) {
			$rsOrder = model('user_pre_order')->where(array('usersid'=>$this->UsersID,'userid'=>$_SESSION[$this->UsersID . 'User_ID'],'pre_sn'=>$OrderID))->find();
			$total = $rsOrder['total'];
			$ordersn = $rsOrder['pre_sn'];
		}else {
			$rsOrder = model('user_order')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Order_ID'=>$OrderID))->find();
			$total = $rsOrder['order_totalprice'];
			$ordersn = date("Ymd", $rsOrder["order_createtime"]) . $rsOrder['order_id'];
		}
		$this->assign('rsPay', $rsPay);
		$this->assign('rsOrder', $rsOrder);
		$this->assign('total', $total);
		$this->assign('ordersn', $ordersn);
		$this->assign('OrderID', $OrderID);
		$this->display('payment.php', 'home', 'home_layout');
	}
	public function payOp() {
		$OrderID = $_GET['OrderID'];
		if(strpos($OrderID, 'PRE') > -1) {
			$rsOrder = model('user_pre_order')->where(array('usersid'=>$this->UsersID,'userid'=>$_SESSION[$this->UsersID . 'User_ID'],'pre_sn'=>$OrderID))->find();
			$status = $rsOrder['status'];
		}else {
			$rsOrder = model('user_order')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Order_ID'=>$OrderID))->find();
			$status = $rsOrder['order_status'];
		}
		$Method = $_GET['Method'];
		
        $rsPay = model('users_payconfig')->where(array('Users_ID'=>$this->UsersID))->find();
		if(!$rsOrder) {
			echo '此订单无效';
			exit;
		}
		if($rsOrder && $status != 1) {
			echo '此订单不是“待付款”状态，不能付款';
			exit;
		}
		$PaymentMethod = array(
			'1'=>'微支付',
			'2'=>'支付宝'
		);
		if($Method == 1) {//微支付
		    $rsUsers = model('users')->where(array('Users_ID'=>$this->UsersID))->find();
			if($rsPay['paymentwxpayenabled'] == 0 || empty($rsPay['paymentwxpaypartnerid']) || empty($rsPay['paymentwxpaypartnerkey']) || empty($rsUsers['users_wechatappid']) || empty($rsUsers['users_wechatappsecret'])) {
				$this->error('商家“微支付”支付方式未启用或信息不全，暂不能支付！');
			}
			$pay_fee = $rsOrder['order_totalprice'];
			$pay_orderno = $OrderID;
			$pay_subject = $SiteName . '(' . $UsersID . ')微商城在线付款，订单编号:' . $OrderID;
			
			if($rsPay['paymentwxpaytype'] == 1) {
				header('location:/pay/wxpay2/sendto.php?UsersID = ' . $UsersID . '_' . $OrderID);
			}else {
				header('location:/pay/wxpay/sendto.php?UsersID = ' . $UsersID . '&OrderID=' . $OrderID);
			}
		}else if($Method == 2) {//支付宝
			if($rsPay['payment_alipayenabled'] == 0 || empty($rsPay['payment_alipaypartner']) || empty($rsPay['payment_alipaykey']) || empty($rsPay['payment_alipayaccount'])) {
				$this->error('商家“支付宝”支付方式未启用或信息不全，暂不能支付！');
			}
			header('location:' . url('alipay_goods/index_pc', array('OrderID'=>$OrderID)));
		}else if($Method == 4) {//易宝支付
			if($rsPay['paymentyeepayenabled'] == 0 || empty($rsPay['paymentyeepayaccount']) || empty($rsPay['paymentyeepayprivatekey']) || empty($rsPay['paymentyeepaypublickey']) || empty($rsPay['paymentyeepayyeepaypublickey'])) {
				$this->error('商家“支付宝”支付方式未启用或信息不全，暂不能支付！');
			}
			header('location:/pay/yeepay/sendto.php?UsersID=' . $UsersID . '_' . $OrderID);
		}
	}
	//线下和余额支付
	public function complete_payOp() {
		$this->assign('title', '订单支付');
		$OrderID = $_GET['OrderID'];
		if(strpos($OrderID, 'PRE') !== false) {
			$rsOrder = model('user_pre_order')->where(array('usersid'=>$this->UsersID,'userid'=>$_SESSION[$this->UsersID . 'User_ID'],'pre_sn'=>$OrderID))->find();
			$total = $rsOrder['total'];
			$ordersn = $rsOrder['pre_sn'];
		}else {
			$rsOrder = model('user_order')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Order_ID'=>$OrderID))->find();
			$total = $rsOrder['order_totalprice'];
			$ordersn = date('Ymd', $rsOrder['order_createtime']) . $rsOrder['order_id'];
		}
		$rsUser = model('user')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find();
        $method_list = array('money'=>'余额支付', 'huodao'=>'线下支付');
		$this->assign('method_list', $method_list);
		
		$this->assign('rsUser', $rsUser);
		$this->assign('rsOrder', $rsOrder);
		$this->assign('total', $total);
		$this->assign('ordersn', $ordersn);
		$this->assign('OrderID', $OrderID);
		$this->display('complete_pay.php', 'home', 'home_layout');
	}
}
?>