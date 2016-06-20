<?php
namespace common\controller;
class memberController extends commonController {
	public function _initialize() {
		parent::_initialize();
		if(empty($_SESSION['Users_Account'])) {
			header('location:/member/login.php');exit;
		}
	}
}