<?php
namespace shop\controller;
class alipay_goodsController extends controllController {
	public function __construct() {
		parent::_initialize();
		$this->check_login();
		include(FRAMEWORK_PATH . '/vendor/alipay/pc/Corefunction.php');
		include(FRAMEWORK_PATH . '/vendor/alipay/pc/Md5function.php');
	}
	
	public function index_pcOp() {
		if(empty($_GET['OrderID'])) {
			$this->error('缺少参数');
		}else {
			$OrderID = $_GET['OrderID'];
		}
		$pay_order = new pay_orderController($OrderID);
        $payinfo = $pay_order->get_pay_info();
		$users_payconfig = model('users_payconfig')->where(array('Users_ID'=>$this->UsersID))->find();
		$alipay_config = array(
		    'partner' => $users_payconfig['payment_alipaypartner'],   //这里是你在成功申请支付宝接口后获取到的PID；
			'key' => $users_payconfig['payment_alipaykey'],//这里是你在成功申请支付宝接口后获取到的Key
			'sign_type' => 'MD5',
			'input_charset' => 'utf-8',
			'transport' => 'http',
		);
		$rsUsers = model('users')->where(array('Users_ID'=>$this->UsersID))->find();
		/**************************请求参数**************************/
        $payment_type = '1'; //支付类型 //必填，不能修改
        $notify_url = _url('alipay_goods/notify', '', false, true); //服务器异步通知页面路径
        $return_url = _url('alipay_goods/return', '', false, true); //页面跳转同步通知页面路径
        $seller_email = $users_payconfig['payment_alipayaccount'];//卖家支付宝帐户必填
        $out_trade_no = $payinfo['out_trade_no'];//商户订单号 通过支付页面的表单进行传递，注意要唯一！
        $subject = $payinfo['subject'];  //订单名称 //必填 通过支付页面的表单进行传递
		$total_fee = strval(floatval($payinfo['total_fee']));   //付款金额  //必填 通过支付页面的表单进行传递
        $anti_phishing_key = '';//防钓鱼时间戳 //若要使用请调用类文件submit中的query_timestamp函数
        $exter_invoke_ip = get_client_ip(); //客户端的IP地址 
        /************************************************************/
		
		//构造要请求的参数数组，无需改动
        $parameter = array(
			'service' => 'create_direct_pay_by_user',
			'partner' => trim($alipay_config['partner']),
			'payment_type' => $payment_type,
			'notify_url' => $notify_url,
			'return_url' => $return_url,
			'seller_email' => $seller_email,
			'out_trade_no' => $out_trade_no,
			'subject' => $subject,
			'total_fee' => $total_fee,
			'anti_phishing_key' => $anti_phishing_key,
			'exter_invoke_ip' => $exter_invoke_ip,
			'_input_charset' => 'utf-8'
		);
		//建立请求
        $alipaySubmit = new \vendor\alipay\pc\Submit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter, 'post', '确认');
        echo $html_text;
	}
	public function notifyOp() {
		if(empty($_POST)) {//判断POST来的数组是否为空
			echo 'fail';
			exit;
		}
		$out_trade_no = $_POST['out_trade_no'];
		if(strpos($out_trade_no, 'PRE') > -1){
			$OrderID = $out_trade_no;
			$rsOrder = model('user_pre_order')->where(array('pre_sn'=>$OrderID))->find();
			if(!$rsOrder){
				echo 'fail';
				exit;
			}
			$UsersID = $rsOrder['usersid'];
			$UserID = $rsOrder['userid'];
			$Status = $rsOrder['status'];
		}else {
			$OrderID = substr($out_trade_no, 10);
			$rsOrder = model('user_order')->field('Users_ID,User_ID,Order_Status')->where(array('Order_ID'=>$OrderID))->find();
			if(!$rsOrder){
				echo 'fail';
				exit;
			}
			$UsersID = $rsOrder['Users_ID'];
			$UserID = $rsOrder['User_ID'];
			$Status = $rsOrder['Order_Status'];
		}
		$users_payconfig = model('users_payconfig')->where(array('Users_ID'=>$UsersID))->find();
        $alipay_config = array(
		    'sign_type'=>'MD5',
			'key'=>$users_payconfig['payment_alipaykey'],
			'transport'=>'http',
			'partner'=>$users_payconfig['payment_alipaypartner'],
			'input_charset'=> strtolower('utf-8'),
		);
		//计算得出通知验证结果
        $alipayNotify = new \vendor\alipay\pc\Notify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) { //验证成功
            if($_POST['trade_status'] == 'TRADE_FINISHED') {
				if(!$this->checkorderstatus($Status)) {
					$this->notify_orderhandle($OrderID);
				}else {
					echo 'success';
				    exit;
				}
            }else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                if(!$this->checkorderstatus($Status)) {
					$this->notify_orderhandle($OrderID);
				}else {
					echo 'success';
				    exit;
				}
            }
            echo 'success';//请不要修改或删除
        }else {
            //验证失败
            echo 'fail';
        }    
    }
	/*
        页面跳转处理方法；
        */
    public function returnOp() {
        $out_trade_no = $_GET['out_trade_no'];
		if(strpos($out_trade_no, 'PRE') > -1){
			$OrderID = $out_trade_no;
			$rsOrder = model('user_pre_order')->where(array('pre_sn'=>$OrderID))->find();
			if(!$rsOrder){
				echo '订单不存在';
				exit;
			}
			$UsersID = $rsOrder['usersid'];
			$UserID = $rsOrder['userid'];
			$Status = $rsOrder['status'];
		}else{
			$OrderID = substr($out_trade_no, 10);
			$rsOrder = model('user_order')->field('Users_ID,User_ID,Order_Status')->where(array('Order_ID'=>$OrderID))->find();
			if(!$rsOrder){
				echo "订单不存在";
				exit;
			}
			$UsersID = $rsOrder['Users_ID'];
			$UserID = $rsOrder['User_ID'];
			$Status = $rsOrder['Order_Status'];
		}
        $users_payconfig = model('users_payconfig')->where(array('Users_ID'=>$UsersID))->find();
        $alipay_config = array(
		    'sign_type'=>'MD5',
			'key'=>$users_payconfig['payment_alipaykey'],
			'transport'=>'http',
			'partner'=>$users_payconfig['payment_alipaypartner'],
			'input_charset'=> strtolower('utf-8'),
		);
        $alipayNotify = new \vendor\alipay\pc\Notify($alipay_config);//计算得出通知验证结果
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {
            //验证成功
		    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
				if(!$this->checkorderstatus($Status)) {
					$this->return_orderhandle($OrderID);  //进行订单处理，并传送从支付宝返回的参数；
			    }
				$url = _url('member/status',array('UsersID'=>$UsersID,'Status'=>$Status));
				echo '<script type=\'text/javascript\'>window.location.href=\''.$url.'\';</script>';	
				exit;
			}else {
				echo 'trade_status=' . $_GET['trade_status'];
				exit;
			}
		}else {
			//验证失败
			echo "支付失败！";
		}
	}
	
	function notify_orderhandle($OrderID) {
		$pay_order = new \shop\controller\pay_order($OrderID);
		$data = $pay_order->make_pay();
		if($data['status'] == 1) {
			echo 'success';
			exit;
		}else {
			echo 'fail';
			exit;
		}
	}
	function return_orderhandle($OrderID) {
		$pay_order = new \shop\controller\pay_order($OrderID);
		$data = $pay_order->make_pay();
		if($data['status']==1) {
			echo '<script type=\'text/javascript\'>window.location.href=\''.$data['url'].'\';</script>';	
			exit;		
		}else {
			echo $data['msg'];
			exit;
		}
	}
	function checkorderstatus($Status) {
		if($Status == 1) {
			return true;
		}else {
			return false;    
		}
	}
}
?>