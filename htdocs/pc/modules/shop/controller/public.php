<?php
//不进行权限检测
namespace shop\controller;
use common\controller\commonController;
class publicController extends commonController {
	private $shopConfig = array();
	public $owner = array();
	public function __construct() {
		parent::_initialize();
		if(isset($_GET['UsersID'])) {
			$this->UsersID = $_GET['UsersID'];
			$this->assign('UsersID', $this->UsersID);
		}else {
			$this->error('缺少必要的参数');
		}

		//$wap_config = model('shop_config')->where(array('Users_ID'=>$this->UsersID))->find();
                $wap_config = model()->query("SELECT * FROM shop_config where Users_ID='".$this->UsersID."' limit 0,1");
                $dis_config = model()->query("SELECT * FROM distribute_config where Users_ID='".$this->UsersID."' limit 0,1");
		$pc_setting = model('pc_setting')->where(array('Users_ID'=>$this->UsersID,'module'=>'shop'))->find();
		$config = array_change_key_case(array_merge($wap_config[0],$dis_config[0],$pc_setting));

		$this->shopConfig = $config;
		$this->owner = get_owner($this->shopConfig, $this->UsersID);
		$this->assign('shopConfig', $config);
	}
	
	function loginOp() {
		if($_POST) {
			if(empty($_POST['mobile'])) {
				$this->error('手机号不能为空');
			}
			if(empty($_POST['password'])) {
				$this->error('密码不能为空');
			}
			if(empty($_SESSION[$this->UsersID . 'HTTP_REFERER'])) {
				$HTTP_REFERER = url('index/index');
			}else {
				$HTTP_REFERER = $_SESSION[$this->UsersID . 'HTTP_REFERER'];
			}
			$rsUser = model('user')->where(array('Users_ID'=>$this->UsersID, 'User_Mobile'=>$_POST['mobile']))->find();
			if($rsUser) {
				if(md5($_POST['password']) == $rsUser['user_password']) {
					$_SESSION[$this->UsersID . 'User_ID'] = $rsUser['user_id'];
					$_SESSION[$this->UsersID . 'User_Name'] = $rsUser['user_name'];
					$_SESSION[$this->UsersID . 'User_Mobile'] = $rsUser['user_mobile'];
					$_SESSION[$this->UsersID . 'HTTP_REFERER'] = '';
					header('location:' . $HTTP_REFERER);
					exit;
				}else {
					$this->error('登录失败!');
				}
			}else {
				$this->error('登录失败!');
			}
		}
		
		$this->display('login.php', 'public', 'null_layout');
	}
	function registerOp() {
		$item = model('user_config')->where(array('Users_ID'=>$this->UsersID))->find();
		$expiretime = $item['expiretime'];
		if($_POST) {
			if(empty($_POST['mobile'])) {
				$this->error('手机号不能为空');
			}
			if(empty($_POST['password'])) {
				$this->error('密码不能为空');
			}
			if($_POST['password'] != $_POST['repassword']) {
				$this->error('密码不一致');
			}
			$rsUser = model('user')->where(array('Users_ID'=>$this->UsersID))->order('User_ID desc')->find();
			if($rsUser && isset($rsUser['User_Mobile']) && $rsUser['User_Mobile'] == $_POST['mobile']) {
				$this->error('手机号已经存在！');
			}
			if(empty($rsUser['user_no'])) {//第一个会员
				$User_No = '600001';
			}else {
				$User_No = $rsUser['user_no'] + 1;
			}
			$Data = array(
				'User_Mobile' => $_POST['mobile'],
				'User_Password' => md5($_POST['password']),
				'User_PayPassword' => md5($_POST['password']),				
				'User_From' => 2,//代表pc端
				'User_CreateTime' => time(),
				'User_Status' => 1,
				'User_Remarks' => '',
				'User_No' => $User_No,
				'User_Json_Input' => isset($User_Json_Input) ? json_encode($User_Json_Input, JSON_UNESCAPED_UNICODE) : '',
				'User_Json_Select' => isset($User_Json_Select) ? json_encode($User_Json_Select, JSON_UNESCAPED_UNICODE) : '',
				'User_ExpireTime' => $expiretime == 0 ? 0 : ( time() + $expiretime * 86400 ),
				'Users_ID' => $this->UsersID,
			);
			if($this->owner['id'] != 0){
				$Data['Owner_Id'] = $this->owner['id'] ;
				$Data['Root_ID'] = $this->owner['root_id'];
			}
			$User_ID = model('user')->insert($Data);
			if($User_ID) {
			    $OpenID = md5(session_id() . $User_ID);
				model('user')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$User_ID))->update(array('User_OpenID'=>$OpenID));
				$this->success('注册成功！', url('public/login', array('UsersID'=>$this->UsersID)));
			}
		}
		$this->display('register.php', 'public', 'null_layout');
	}
	public function logoutOp() {
		$flag = false;
		if(!empty($_SESSION[$this->UsersID . 'User_ID'])){
			$_SESSION[$this->UsersID . 'User_ID'] = '';
			unset($_SESSION[$this->UsersID . 'User_ID']);
			$flag = true;
		}
		if(!empty($_SESSION[$this->UsersID . 'User_Name'])){
			$_SESSION[$this->UsersID . 'User_Name'] = '';
			unset($_SESSION[$this->UsersID . 'User_Name']);
			$flag = true;
		}
		if(!empty($_SESSION[$this->UsersID . 'User_Mobile'])){
			$_SESSION[$this->UsersID . 'User_Mobile'] = '';
			unset($_SESSION[$this->UsersID . 'User_Mobile']);
			$flag = true;
		}
		if($flag) {
			$this->success('退出成功');
		}else {
			$this->error('退出失败');
		}
	}
}
?>