<?php
namespace common\controller;
class shopController extends commonController {
	protected $UsersID = '';
	public function _initialize() {
		parent::_initialize();
		if(isset($_GET['UsersID'])) {
			$this->UsersID = $_GET['UsersID'];
		}else {

           if(MAIN_SITE == $_SERVER['HTTP_HOST']){
			   header('location:http://' . MAIN_SITE . '/member/');
			}

			$users_info = model('pc_setting')->field('Users_ID')->where(array('site_url'=>$_SERVER['HTTP_HOST']))->find();
			if($users_info) {
				$this->UsersID = $users_info['Users_ID'];
			}else {
				$this->error('网址不存在！');
			}
		}
		$this->assign('UsersID', $this->UsersID);
		
	}
	protected function url_parsing($ownerid){
	    //网址自由跳转 
		$UA = strtoupper($_SERVER['HTTP_USER_AGENT']);
		if(strpos($UA, 'WINDOWS NT') == false) {
			if($this->_controller == 'goods') {
			    $url = SITE_URL . '/api/'.$this->UsersID.'/shop/'.$ownerid.'/products/'.$_GET['id'].'/';
			}else if($this->_controller == 'list') {
			    $url = SITE_URL . '/api/'.$this->UsersID.'/shop/'.$ownerid.'/category/'.$_GET['id'].'/';
			}else {
			    $url = SITE_URL . '/api/'.$this->UsersID.'/shop/'.$ownerid.'/';
			}
		}
		if(isset($url)){
		    header('location:' . $url);
		}
	}
}