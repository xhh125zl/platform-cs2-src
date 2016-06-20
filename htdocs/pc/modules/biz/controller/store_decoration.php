<?php
namespace biz\controller;
/**
 * 店铺装修
 *
 **/
class store_decorationController extends controllController {
	public $rsBiz = array();
    public function __construct() {
       parent::_initialize();
	   $this->rsBiz = model('biz')->field('*')->where(array('Biz_ID'=>$_SESSION['BIZ_ID']))->find();
    }
	//幻灯片、banner设置
	public function index_settingOp() {
		if(!empty($_POST)) {
			if(empty($_POST['bg_color'])){
				$this->error('导航背景色不能为空');
			}
			$slide = array();
			for($i=0; $i<5; $i++){
				if(!empty($_POST['slide'][$i]['ImgPath'])){
					$slide[$i] = $_POST['slide'][$i];
				}
			}
			if(!$slide){
				$this->error('幻灯片不能为空');
			}
			$Data = array(
			    'pc_banner'=>empty($_POST['banner']) ? '' : $_POST['banner'],
				'pc_slide'=>serialize($slide),
				'pc_bg_color'=>$_POST['bg_color']
			);
			$flag = model('biz')->where(array('Biz_ID'=>$_SESSION['BIZ_ID']))->update($Data);
			if($flag){
				$this->success('保存成功');
			}else{
				$this->error('保存失败');
			}
		}else {
			$this->rsBiz['pc_slide'] = unserialize(htmlspecialchars_decode($this->rsBiz['pc_slide']));
			$this->assign('rsBiz', $this->rsBiz);
			$this->assign('pc_slide', $this->rsBiz['pc_slide']);
			$this->display('index_setting.php', '@', 'biz_layout');
		}
	}
	
	public function menu_settingOp() {
		if(!empty($_GET['action']) && $_GET['action'] == 'del') {
			if(empty($_GET['id'])) {
				$this->error('参数错误');
			}
			$flag = model('biz_menu')->where(array('Biz_ID'=>$_SESSION['BIZ_ID'],'id'=>$_GET['id']))->delete();
			if($flag) {
				$this->success('删除成功');
			}else {
				$this->error('删除失败');
			}
		}else {
			$biz_menu = model('biz_menu')->where(array('Biz_ID'=>$_SESSION['BIZ_ID']))->select();
			$this->assign('biz_menu', $biz_menu);
			$this->display('menu_setting.php', '@', 'biz_layout');
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
				'Users_ID'=>$this->rsBiz['Users_ID'],
				'Biz_ID'=>$_SESSION['BIZ_ID'],
				'menu_sort'=>$_POST['sort'],
			);
			$flag = model('biz_menu')->insert($Data);
			if($flag){
				$this->success('保存成功',url('store_decoration/menu_setting'));
			}else{
				$this->error('保存失败');
			}
		}else {
			$this->display('menu_add.php', '@', 'biz_layout');
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
			$flag = model('biz_menu')->where(array('id'=>$_POST['id']))->update($Data);
			if($flag){
				$this->success('保存成功');
			}else{
				$this->error('保存失败');
			}
		}else {
			if(empty($_GET['id'])){
				$this->error('缺少参数');
			}
			$menu_info = model('biz_menu')->where(array('id'=>$_GET['id']))->find();
			$this->assign('menu_info', $menu_info);
			$this->display('menu_edit.php', '@', 'biz_layout');
		}
	}
    /**
     * 店铺装修设置
     */
    public function decoration_settingOp() {
        $model_store_decoration = model('store_decoration');
		$store_decoration_info = $model_store_decoration->field('*')->where(array('Biz_ID' => $this->rsBiz['Biz_ID']))->find();
		if(empty($store_decoration_info)) {
				//创建默认装修
			$param = array();
			$param['decoration_name'] = '默认装修';
			$param['Biz_ID'] = $this->rsBiz['BIZ_ID'];
			$param['Users_ID'] = $this->rsBiz['Users_ID'];
			$decoration_id = $model_store_decoration->insert($param);
			$store_decoration_info = $model_store_decoration->field('*')->where(array('decoration_id' => $decoration_id))->find();
		}
        $this->assign('store_decoration_info', $store_decoration_info);
        $this->display('store_decoration.setting.php', '@', 'biz_layout');
    }

    /**
     * 店铺装修设置保存
     */
    public function decoration_setting_saveOp() {
        $model_store_decoration = model('store_decoration');
		$decoration_id = empty($_POST['id']) ? 0 : $_POST['id'];
        $store_decoration_info = $model_store_decoration->field('*')->where(array('decoration_id' => $decoration_id))->find();
        if(empty($store_decoration_info)) {
           $this->error('参数错误');
        }
		$result = $model_store_decoration->where(array('decoration_id' => $store_decoration_info['decoration_id']))->update('store_decoration_only='.intval($_POST['store_decoration_only']));
        if($result) {
            $this->success('保存成功');
        } else {
            $this->error('保存失败');
        }
    }
}
