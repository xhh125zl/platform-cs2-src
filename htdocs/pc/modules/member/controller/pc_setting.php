<?php
namespace member\controller;
class pc_settingController extends controllController {
	public function __construct() {
		parent::_initialize();
	}
	//基本设置
	public function indexOp() {
		$setting_model = model('pc_setting');
		$pc_setting = $setting_model->where(array('module' => 'shop', 'Users_ID' => $_SESSION['Users_ID']))->find();
		if(!empty($_POST)) {
			$_POST['Users_ID'] = $_SESSION['Users_ID'];
			$_POST['module'] = 'shop';
			$_POST['site_url'] = empty($_POST['site_url']) ? ' ' : trim($_POST['site_url'], '/');
			if($pc_setting) {
				$flag = $setting_model->update($_POST, 0);
			}else {
				$flag = $setting_model->insert($_POST);
			}
			if($flag) {
				$this->success('设置成功！', url('pc_setting/index'));
			}else {
				$this->error('设置失败');
			}
		}
		$this->assign('config', $pc_setting);
		$this->display('setting_index.php', '@', 'member_layout');
	}
	//分享设置
	public function share_settingOp() {
		$setting_model = model('pc_setting');
		$pc_setting = $setting_model->where(array('module' => 'shop', 'Users_ID' => $_SESSION['Users_ID']))->find();
		if(!empty($_POST)) {
			$_POST['Users_ID'] = $_SESSION['Users_ID'];
			$_POST['module'] = 'shop';
			if($pc_setting) {
				$flag = $setting_model->update($_POST);
			}else {
				$flag = $setting_model->insert($_POST);
			}
			if($flag) {
				$this->success('设置成功！',url('pc_setting/share_setting'));
			}else {
				$this->error('设置失败');
			}
		}
		$this->assign('config', $pc_setting);
		$this->display('share_setting.php', '@', 'member_layout');
	}
	
	public function menu_indexOp() {
		if(!empty($_GET['action']) && $_GET['action'] == 'del') {
			if(empty($_GET['id'])) {
				$this->error('参数错误');
			}
			$flag = model('users_menu')->where(array('Users_ID'=>$_SESSION['Users_ID'],'id'=>$_GET['id']))->delete();
			if($flag) {
				$this->success('删除成功');
			}else {
				$this->error('删除失败');
			}
		}else {
			$users_menu = model('users_menu')->where(array('Users_ID'=>$_SESSION['Users_ID']))->select();
			$this->assign('users_menu', $users_menu);
			$this->display('menu_index.php', '@', 'member_layout');
		}
	}
	public function menu_addOp() {
		if(!empty($_POST)) {
			if(empty($_POST['name'])){
				$this->error('名称不能为空');
			}
			if(empty($_POST['link'])){
				$this->error('链接不能为空');
			}
			$Data = array(
			    'menu_name'=>$_POST['name'],
				'menu_link'=>$_POST['link'],
				'menu_target'=>$_POST['target'],
				'Users_ID'=>$_SESSION['Users_ID'],
				'menu_sort'=>$_POST['sort'],
			);
			$flag = model('users_menu')->insert($Data);
			if($flag){
				$this->success('保存成功',url('pc_setting/menu_index'));
			}else{
				$this->error('保存失败');
			}
		}else {
			$this->display('menu_add.php', '@', 'member_layout');
		}
	}
	public function menu_editOp() {
		if(!empty($_POST)) {
			if(empty($_POST['name'])){
				$this->error('名称不能为空');
			}
			if(empty($_POST['link'])){
				$this->error('链接不能为空');
			}
			$Data = array(
			    'menu_name'=>$_POST['name'],
				'menu_link'=>$_POST['link'],
				'menu_target'=>$_POST['target'],
				'menu_sort'=>$_POST['sort'],
			);
			$flag = model('users_menu')->where(array('id'=>$_POST['id']))->update($Data);
			if(false !== $flag){
				$this->success('保存成功');
			}else{
				$this->error('保存失败');
			}
		}else {
			if(empty($_GET['id'])){
				$this->error('缺少参数');
			}
			$menu_info = model('users_menu')->where(array('id'=>$_GET['id']))->find();
			$this->assign('menu_info', $menu_info);
			$this->display('menu_edit.php', '@', 'member_layout');
		}
	}
}
?>