<?php
namespace common\controller;
class emptyController extends commonController {
	public function __construct() {
		parent::_initialize();
		$this->error('文件不存在！');
	}
}