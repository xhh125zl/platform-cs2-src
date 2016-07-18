<?php
namespace member\controller;
class pc_diyController extends controllController {
	public function __construct() {
		parent::_initialize();
	}
	
	public function index_blockOp() {
		$web_list = model('pc_index')->where(array('web_page' => 'index', 'Users_ID'=>$_SESSION['Users_ID']))->order('web_sort asc')->select();
		$this->assign('web_list', $web_list);
		$this->display('index_block.php', '@', 'member_layout');
	}
	public function block_addOp() {
		$model_pc_index = model('pc_index');
		if (!empty($_POST)) {
			$web_array = array();
			$web_array['web_name'] = $_POST["web_name"];
			$web_array['style_name'] = $_POST["style_name"];
			$web_array['web_sort'] = intval($_POST["web_sort"]);
			$web_array['web_show'] = intval($_POST["web_show"]);
			$web_array['update_time'] = time();
			$web_array['Users_ID'] = $_SESSION['Users_ID'];
			$web_id = $model_pc_index->insert($web_array);
			$this->doWebCode($web_id, $_POST['style_name']);//更新前台显示的html内容
			$this->success('操作成功',url('pc_diy/index_block'));
		}
		$this->display('block_add.php', '@', 'member_layout');
	}
	public function block_editOp() {
		$model_pc_index = model('pc_index');
		if (!empty($_POST)) {
			$web_array = array();
			$web_id = intval($_POST["web_id"]);
			$web_array['web_name'] = $_POST["web_name"];
			$web_array['style_name'] = $_POST["style_name"];//背景色
			$web_array['web_sort'] = intval($_POST["web_sort"]);
			$web_array['web_show'] = intval($_POST["web_show"]);
			$web_array['update_time'] = time();
			$model_pc_index->where(array('web_id'=>$web_id, 'Users_ID'=>$_SESSION['Users_ID']))->update($web_array);
			$this->doWebCode($web_id, $_POST['style_name']);//更新前台显示的html内容
			$this->success('操作成功', url('pc_diy/index_block'));
		}else {
			$web_id = intval($_GET["web_id"]);
			$web_info = $model_pc_index->where(array('web_id'=>$web_id, 'Users_ID'=>$_SESSION['Users_ID']))->find();
			$this->assign('web_array', $web_info);
			$this->display('block_edit.php', '@', 'member_layout');
		}
	}
	/**
	 * 更新模块html信息 如果没有则插入
	 *
	 */
	private function doWebCode($web_id = 0, $style_name = 'orange') {
		$web_html = '';
		if($web_id == 0){
			$code_list = array();
			$block_info = array();
		}else {
			$code_list = model('web_code')->where(array('web_id' => $web_id, 'Users_ID'=>$_SESSION['Users_ID']))->order('web_id asc')->select();
		    $block_info = model('pc_index')->where(array('web_id' => $web_id, 'Users_ID'=>$_SESSION['Users_ID']))->find();
		}
		if(!empty($code_list) && is_array($code_list)) {
			$output = array();
			$output['style_name'] = $style_name;//颜色风格
			foreach ($code_list as $key => $val) {
				$var_name = $val['var_name'];
				$code_info = $val['code_info'];
				$code_type = $val['code_type'];
				$val['code_info'] = $this->get_array($code_info, $code_type);
				$output['code_' . $var_name] = $val;
			}
			if($var_name == 'recommend_list') {
				if(!empty($code_info)){
					$code_recommend_list = unserialize(htmlspecialchars_decode($code_info));
					foreach($code_recommend_list as $key => $val) {
						if(!empty($val['goods_list'])){
							foreach($val['goods_list'] as $goods_id => $v) {
								$rsP = model('shop_products')->where(array('Users_ID'=>$_SESSION['Users_ID'],'Products_ID'=>$goods_id,'Products_SoldOut'=>0,'Products_Status'=>1))->find();
								if(empty($rsP)){
									if(IS_AJAX){
										$this->ajaxReturn(array('status'=>0,'msg'=>'更新失败！有产品已经删除！'));
									}else{
										$this->error('更新失败！有产品已经删除！');
									}
								}else{
									if($rsP['products_soldout'] == 1) {
										if(IS_AJAX) {
											$this->ajaxReturn(array('status'=>0, 'msg'=>'更新失败！产品“' . $rsP['products_name'] . '”已经下架！'));
										}else {
											$this->error('更新失败！产品“' . $rsP['products_name'] . '”已经下架！');
										}
									}
								}
							}
						}
					}
				}
			}else if($var_name == 'category_list') { 
				$code_category_list = unserialize(htmlspecialchars_decode($code_info));
				foreach($code_recommend_list['goods_class'] as $key => $val) {

					$rsC = model('shop_category')->where(array('Category_ID'=>$key))->find();
					if(empty($rsC)){
						if(IS_AJAX){
							$this->ajaxReturn(array('status'=>0,'msg'=>'更新失败！有分类' . $rsC['category_name'] . '已经删除！'));
						}else{
						    $this->error('更新失败！有分类' . $rsC['category_name'] . '已经删除！');
						}
					}
				}
			}
    		$style_file = SITE_PATH . '/static/pc/member/js/web_config/tpl.php';
			if (file_exists($style_file)) {
				ob_start();
                include $style_file;
                $web_html = ob_get_contents();
                ob_end_clean();
			}
			$web_array = array();
			$web_array['web_html'] = $web_html;
			$web_array['update_time'] = time();
			model('pc_index')->where(array('web_id'=>$web_id))->update($web_array);
		}else {
			$result_tpl = array(
				array(
					'code_type' => 'array',
					'var_name' => 'tit',
					'code_info' => serialize(array('pic'=>'','url'=>'','type'=>'txt','floor'=>'hello','title'=>'')),
					'show_name' => '标题图片'
				),
				array(
					'code_type' => 'array',
					'var_name' => 'act',
					'code_info' => serialize(array('pic'=>'','url'=>'','title'=>'')),
					'show_name' => '活动图片1'
				),
				array(
					'code_type' => 'array',
					'var_name' => 'category_list',
					'code_info' => serialize(array('goods_class'=>array())),
					'show_name' => '推荐分类'
				),
				array(
					'code_type' => 'array',
					'var_name' => 'adv',
					'code_info' => '',
					'show_name' => '广告图片'
				),
				array(
					'code_type' => 'array',
					'var_name' => 'recommend_list',
					'code_info' => '',
					'show_name' => '商品推荐'
				)
			);
			
			$INSERT_SQL = 'INSERT INTO web_code (web_id,code_type,var_name,code_info,show_name,Users_ID) VALUES ';
			foreach($result_tpl as $k => $v) {
				$INSERT_SQL .= '(' . $web_id . ', "' . $v['code_type'] . '", "' . $v['var_name'] . '", \'' . $v['code_info'] . '\', "' . $v['show_name'] . '", "' . $_SESSION['Users_ID'] . '"),';
			}
			$INSERT_SQL = rtrim($INSERT_SQL, ',').';';
			model()->query($INSERT_SQL,'insert');
		}
		return $web_html;
	}
	
	/**
	 * 板块编辑
	 */
	public function code_editOp() {
		$web_id = intval($_GET["web_id"]);
		$code_list = model('web_code')->where(array('web_id' => $web_id, 'Users_ID'=>$_SESSION['Users_ID']))->order('web_id asc')->select();
		$parent_goods_class = $this->getTreeClassList(2, array('Users_ID' => $_SESSION['Users_ID']));//商品分类父类列表，只取到第二级
		if (is_array($parent_goods_class) && !empty($parent_goods_class)) {
			foreach ($parent_goods_class as $k => $v){
				$parent_goods_class[$k]['Category_Name'] = str_repeat("&nbsp;",$v['deep']*2).$v['Category_Name'];
			}
		}
		$this->assign('parent_goods_class', $parent_goods_class);

		$goods_class = $this->getTreeClassList(1);//第一级商品分类
		$this->assign('goods_class', $goods_class);

		foreach ($code_list as $key => $val) {//将变量输出到页面
			$var_name = $val["var_name"];
			$code_info = $val["code_info"];
			$code_type = $val["code_type"];
			$val['code_info'] = $this->get_array($code_info, $code_type);
			$this->assign('code_'.$var_name, $val);
		}
		$block_info = model('pc_index')->where(array('web_id'=>$web_id, 'Users_ID'=>$_SESSION['Users_ID']))->find();
		$this->assign('web_array', $block_info);
		$this->display('web_code.edit.php', '@', 'member_layout');
	}
	/**
	 * 更新前台显示的html内容
	 */
	public function web_htmlOp() {
		$model_pc_diy = model('pc_index');
		$web_id = intval($_POST["web_id"]);
		$web_array = $model_pc_diy->where(array('web_id' => $web_id))->find();
		if(!empty($web_array) && is_array($web_array)) {
			$this->doWebCode($web_id, $_POST['style_name']);//更新前台显示的html内容
			$this->ajaxReturn(array('status'=>1,'msg'=>'操作成功','url'=>url('member/pc_diy/index_block')));
		} else {
			$this->ajaxReturn(array('status'=>0,'msg'=>'操作失败'));
		}
	}
	
	//首页幻灯片管理
	function index_focusOp() {
		$focus_list = model('pc_focus')->where(array('Users_ID'=>$_SESSION['Users_ID']))->select();
		$this->assign('focus_list', $focus_list);
		$this->display('index_focus.php', '@', 'member_layout');
	}
	//添加幻灯
	function focus_addOp() {
		$focus_model = model('pc_focus');
		if (!empty($_POST)) {
			$_POST['add_time'] = time();
			$_POST['Users_ID'] = $_SESSION['Users_ID'];
			$insert = $_POST;
			$flag = $focus_model->insert($insert);
			if($flag) {
				$this->success('添加成功', url('pc_diy/index_focus'));
			}else {
				$this->success('添加失败');
			}
		}
		$this->display('focus_add.php', '@', 'member_layout');
	}
	
	function focus_editOp() {
		$focus_model = model('pc_focus');
		if (!empty($_POST)) {
			$insert = $_POST;
			$flag = $focus_model->update($insert);
			if($flag) {
				$this->success('编辑成功', url('pc_diy/index_focus'));
			}else {
				$this->success('编辑失败');
			}
		}else {
			$info = $focus_model->where(array('id'=>$_GET['id'],'Users_ID'=>$_SESSION['Users_ID']))->find();
			$this->assign('info', $info);
		}
		$this->display('focus_edit.php', '@', 'member_layout');
	}
	
	function focus_delOp() {
		$focus_model = model('pc_focus');
		$flag = $focus_model->where(array('id'=>$_GET['id'],'Users_ID'=>$_SESSION['Users_ID']))->delete();
		if($flag) {
			$this->success('删除成功', url('pc_diy/index_focus'));
		}else {
			$this->error('删除失败');
		}
	}
	
	//更新首页
	function update_indexOp() {
		$web_array = '';
		$web_list = model('pc_index')->where(array('Users_ID'=>$_SESSION['Users_ID'], 'web_show'=>1))->select();
		if(!empty($web_list) && is_array($web_list)) {
			foreach($web_list as $k => $v) {
					$web_array .= $this->doWebCode($v['web_id'], $v['style_name']);
			}
		}
		$this->success('更新成功', url('pc_diy/index_block'));
	}
}
?>