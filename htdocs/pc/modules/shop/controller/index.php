<?php
namespace shop\controller;
class indexController extends controllController {
	public function __construct() {
		parent::_initialize();
	}
	
	public function indexOp() {
		$this->assign('title', '首页');
		$focus_list = $this->focus_list();
		$this->assign('focus_list', $focus_list);
		if(IS_AJAX) {
			if($_POST['action'] == 'getHot') {
				$data = $this->_getHot();
			}
			$this->ajaxReturn($data);
		}
		$model_pc_index = model('pc_index', 'common');
		$web_html = $model_pc_index->getWebHtml('index',$this->UsersID);
		$this->assign('web_html', $web_html);
		$block_list = $model_pc_index->getWebList(array('web_page' => 'index','Users_ID'=>$this->UsersID));
		$this->assign('block_list', $block_list);
		$this->display('index.php', 'home', 'home_layout');
	}
	
	
	//随机获取热卖商品
	private function _getHot() {
		$count = model('shop_products')->where(array('Users_ID'=>$this->UsersID,'Products_SoldOut'=>0,'Products_IsHot'=>1,'Products_Status'=>1))->total();
		$num = 5;//每页记录数
		$p = !empty($_POST['p']) ? intval(trim($_POST['p'])) : 1;
		$total = $count;//数据记录总数
		$totalpage = ceil($total / $num);//总计页数
		$limitpage = ($p-1) * $num;//每次查询取记录
		$rsProducts = model('shop_products')->where(array('Users_ID'=>$this->UsersID,'Products_SoldOut'=>0,'Products_IsHot'=>1,'Products_Status'=>1))->limit($limitpage,$num)->select();
		foreach($rsProducts as $key => $val) {
			$JSON = json_decode($val['products_json'], TRUE);
			if(isset($JSON["ImgPath"])){
				$rsProducts[$key]['ImgPath'] = $JSON["ImgPath"][0];
			}else{
				$rsProducts[$key]['ImgPath'] =  SITE_URL . '/static/api/shop/skin/default/nopic.jpg';
			}
			$rsProducts[$key]['link'] = url('goods/index', array('UsersID'=>$this->UsersID, 'id'=>$val['products_id']));
		}
		if(count($rsProducts) > 0) {
			$data = array(
				'list' => $rsProducts,
				'totalpage' => $totalpage,
			);
		}else {
			$data = array(//没有数据可加载
				'list' => '',
				'totalpage' => $totalpage,
			);
		}
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		exit;
	}
	//头部幻灯
	function focus_list() {
		$focus_model = model('pc_focus');
		$focus_list = $focus_model->where(array('Users_ID'=>$this->UsersID,'is_show'=>1))->select();
		return $focus_list;
	}
}
?>