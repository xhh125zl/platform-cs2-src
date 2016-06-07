<?php
namespace shop\controller;
use base;
class controllController extends \common\controller\shopController {
	public $shopConfig = array();
	public $owner = array();
	public $rsUser = array();
	public $rsUserConfig = array();
	public function _initialize() {
		parent::_initialize();
		$this->shopConfig = $this->shopConfig();
		$this->assign('shopConfig', $this->shopConfig);
		if(!isset($this->shopConfig['site_status'])) {
			$this->error('电脑端未配置开启！');
		}
		if(!$this->shopConfig['site_status']) exit($this->shopConfig['closed_reason']);
		$this->assign('main_nav', $this->get_main_nav());
		$p_c = $this->categoryTree();//分类与产品
		$this->assign('categoryTree', $p_c[0]);
		$info = explode('&', $_SERVER['QUERY_STRING']);
		foreach($info as $k => $v) {
			if(preg_match('/^FXY(\d+)FXY$/', $v, $matches)) {
				$_GET['OwnerID'] = intval($matches[1]);
				break;
			}
		}
		$this->owner = get_owner($this->shopConfig, $this->UsersID);
		$this->assign('ownerid', $this->owner['id']);
		$this->url_parsing($this->owner['id']);//pc于手机友好跳转
		//分销商处理
		if($_SERVER['REQUEST_METHOD'] == 'GET') {
			$error_msg = pre_add_distribute_account($this->shopConfig, $this->UsersID);
		}
		if($this->shopConfig['fuxiao_open'] == 1) {
			//冻结前复销提醒处理
			distribute_fuxiao_tixing($this->shopConfig);
			//冻结动作
			distribute_dongjie_action($this->shopConfig);
			//冻结后复销提醒处理
			distribute_dongjie_tixing($this->shopConfig);
			//删除动作
			distribute_delete_action($this->shopConfig);
		}
		//获取会员等级、昵称、图像
		$array2 = array('member', 'distribute');
		if(in_array($this->_controller, $array2) && !empty($_SESSION[$this->UsersID . 'User_ID'])){
			$this->rsUser = model('user')->field('*')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find();
			$this->rsUserConfig = model('user_config')->field('*')->where(array('Users_ID'=>$this->UsersID))->find();
			$this->rsUser['LevelName'] = '普通会员';
			if(!empty($this->rsUserConfig['UserLevel'])){
				$level_arr = json_decode($this->rsUserConfig['UserLevel'], true);
				if(!empty($level_arr[$this->rsUser['User_Level']])){
					$this->rsUser['LevelName'] = $level_arr[$this->rsUser['User_Level']]["Name"];
				}
			}
			$this->assign('rsUser', $this->rsUser);
		}
		$this->assign('articles', $this->articles());
		//购物车
		$cartList = array();
		$car_num = 0;
		if(!empty($_SESSION[$this->UsersID . 'CartList'])){
			$cartList = json_decode($_SESSION[$this->UsersID . 'CartList'], true);
			foreach($cartList as $BizID => $value_first){
				foreach($value_first as $ProductID => $value_second){
					foreach($value_second as $CartID => $value_third){
						$car_num += $value_third['Qty'];
					}
				}
			}
		}
		$this->assign('car_num', $car_num);//购物车商品数量
		$this->assign('cartList', $cartList);//购物车
	}
	function categoryTree() {
		$CategoryList = model('shop_category')->field('*')->where(array('Users_ID'=>$this->UsersID))->order('Category_Index desc')->select();
		if(count($CategoryList) >0){
			$param = array('result'=>$CategoryList,'fields'=>array('Category_ID','Category_ParentID'));
			$generalTree = new \vendor\General_tree($param);
			
			//生成分类树
			$categoryTree = $generalTree->leaf();
			$proWhere = ' WHERE Users_ID="'.$this->UsersID.'" AND Products_SoldOut=0 AND Products_Status=1 AND Products_Category IN(';
			$CategoryIDS = '';
			foreach($categoryTree as $key => $val){
			    if(!empty($val['child'])) {
					foreach($val['child'] as $k => $v){
						$CategoryIDS .= ($v['Category_ID'].',');
					}
				}else {
					$CategoryIDS .= ($val['Category_ID'].',');
				}
		    }
			$CategoryIDS = trim($CategoryIDS, ',');
			$proWhere .= $CategoryIDS . ')';
			$ProductsList = model()->query('SELECT * FROM shop_products' . $proWhere);
		}else {
			$categoryTree = array();
			$ProductsList = array();
		}
		return array($categoryTree, $ProductsList);
	}
	function shopConfig() {
		$wap_config = model('shop_config')->where(array('Users_ID'=>$this->UsersID))->find();
		$pc_setting = model('pc_setting')->where(array('Users_ID'=>$this->UsersID,'module'=>'shop'))->find();
		if($pc_setting) {
			$config = array_merge($wap_config, $pc_setting);
		}else {
			$config = $wap_config;
		}
		return $config;
	}
	
	function get_main_nav() {
	    $menu_list = model('users_menu')->where(array('Users_ID'=>$this->UsersID))->order('menu_sort asc')->select();
		$main_nav = array();
		$main_nav['首页'] = url('index/index');
		foreach($menu_list as $v){
		    $main_nav[$v['menu_name']] = $v['menu_link'];
		}
		return $main_nav;
	}
	function check_login() {
		if(empty($_SESSION[$this->UsersID . 'User_ID'])) {
			header('Location:' . url('public/login'));
		}else {
			$rsUser = model('user')->field('User_Profile')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find();
			if(empty($rsUser['User_Profile'])) {
				header('location:/api/' . $this->UsersID . '/user/complete/');
				exit;
			}
		}
	}
	//底部内容
	function articles(){
	    $categorys = model('shop_articles_category')->field('*')->where(array('Users_ID'=>$this->UsersID,'mob_show !='=>0))->order('Category_Index asc')->limit('0,5')->select();
		$cids = $articles = array();
		foreach($categorys as $k => $v){
			$cids[] = $v['Category_ID'];
		}
		if($cids){
			$articles = model('shop_articles')->field('*')->where(array('Users_ID'=>$this->UsersID,'Category_ID'=>$cids,'Article_Status'=>1))->limit('0,5')->select();
		}
		return array($categorys, $articles);
	}
}
?>