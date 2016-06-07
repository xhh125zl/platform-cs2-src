<?php
namespace common\controller;
class bizController extends commonController {
	public function _initialize() {
		parent::_initialize();
		if(empty($_SESSION['BIZ_ID'])) {
			header('location:/biz/login.php');exit;
		}
	}
}