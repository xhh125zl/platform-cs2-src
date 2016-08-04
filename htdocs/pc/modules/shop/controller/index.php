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
				$data = $this->_getHot();dump($data);
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
            $rsProducts = model('shop_products')->field('Products_ID')->where(array('Users_ID'=>$this->UsersID,'Products_SoldOut'=>0,'Products_IsHot'=>1,'Products_Status'=>1))->select();
            foreach ($rsProducts as $k => $v) {
                $productid_array[$v['Products_ID']] = $v['Products_ID'];
            }
            if (count($productid_array) != 0) {
                $rand_num = 5;
                if (count($productid_array) < 5) {
                    $rand_num = count($productid_array);
                }
                $productid_rand = array_rand($productid_array,$rand_num);
                $map['Products_ID'] = $productid_rand;
                $product_list = model('shop_products')->where($map)->select();
                foreach ($product_list as $key => $val) {
                    $JSON = json_decode($val['products_json'], TRUE);
                    if (isset($JSON["ImgPath"])) {
                            $product_list[$key]['ImgPath'] = $JSON["ImgPath"][0];
                    } else {
                            $product_list[$key]['ImgPath'] =  SITE_URL . '/static/api/shop/skin/default/nopic.jpg';
                    }
                    $product_list[$key]['link'] = url('goods/index', array('UsersID'=>$this->UsersID, 'id'=>$val['products_id']));
                } 
            } else {
                 $product_list = '';
            }
            $data = array(
			'list' => $product_list,
			'totalpage' => '',
                    );
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