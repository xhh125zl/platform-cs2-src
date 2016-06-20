<?php
namespace shop\controller;
class listController extends controllController {
	private $id = 0;
	public function __construct() {
		parent::_initialize();
		if(!empty($_GET['id'])){
			$this->id = $_GET['id'];
		}
	}
	
	public function indexOp() {
		$category_model = model('shop_category');
		if(!empty($this->id)) {
			$this->assign('title', '商品列表页');
			$cateInfo = $category_model->where(array('Users_ID'=>$this->UsersID,'Category_ID'=>$this->id))->find();
			if(!empty($cateInfo['category_parentid'])) {
                $parentInfo = $category_model->where(array('Users_ID'=>$this->UsersID,'Category_ID'=>$cateInfo['category_parentid']))->find();
				$Bread = array(
					url('index/index', array('UsersID'=>$this->UsersID)) => '首页',
					url('list/index', array('UsersID'=>$this->UsersID, 'id'=>$parentInfo['category_id'])) => $parentInfo['category_name'],
					url('list/index', array('UsersID'=>$this->UsersID, 'id'=>$this->id)) => $cateInfo['category_name'],
				);
			}else {
				$Bread = array(
					url('index/index', array('UsersID'=>$this->UsersID)) => '首页',
					url('list/index', array('UsersID'=>$this->UsersID, 'id'=>$this->id)) => $cateInfo['category_name'],
				);
			}
			$this->assign('Bread', $Bread);
			$this->_list();
		}else {
			$this->assign('title', '搜索结果');
			$this->_list();
		}
		$this->assign('Recommend', $this->_getRecommend());
		$this->assign('Hot', $this->_getHot());
		$this->display('list.php', 'home', 'home_layout');
	}
	function _list(){
	    $products_model = model('shop_products');
	    if(IS_AJAX) {
			if(!empty($_POST['sort'])) {
				switch($_POST['sort']){
					case 'price':
						$order = 'Products_PriceX asc';
						break;
					case 'time_a':
						$order = 'Products_CreateTime asc';
						break;
					case 'time_d':
						$order = 'Products_CreateTime desc';
						break;
					default:
						$order = 'Products_Sales desc';
						break;
				}
			}else {
				$order = 'Products_Sales desc';
			}
			if(!empty($_POST['where'])) {
				$where_str = trim($_POST['where'], ',');
				$where_arr = explode(',', $where_str);
				$where_arr = array_unique($where_arr);
				if(in_array('shipping', $where_arr)){
					$where['Products_IsShippingFree'] = 1;
				}
				if(in_array('recommend', $where_arr)){
					$where['Products_IsRecommend'] = 1;
				}
				if(in_array('hot', $where_arr)){
					$where['Products_IsHot'] = 1;
				}
				if(in_array('new', $where_arr)){
					$where['Products_IsNew'] = 1;
				}
				if(in_array('counts', $where_arr)){
					$where['Products_Count >'] = 0;
				}
				foreach($where_arr as $search){
					if(strpos($search, 'search') !== false){
						$k = substr($search, 6);
						if(!empty($k)){
							$where['Products_Name'] = '%'.$k.'%';
						}
					}
				}
			}
			if(!empty($_GET['Keyword'])){//搜索页面
				$where['Products_Name'] = '%' . $_GET['Keyword'] . '%';
			}else{
			    if($this->id){
				    $where['Products_Category'] = '%,'.$this->id.',%';
				} 
			}
			$where['Users_ID'] = $this->UsersID;
			$where['Products_SoldOut'] = 0;
			$where['Products_Status'] = 1;
			$count = $products_model->where($where)->total();
			$num = 12;//每页记录数
			$p = !empty($_POST['p']) ? intval(trim($_POST['p'])) : 1;
			$total = $count;//数据记录总数
			$totalpage = ceil($total / $num);//总计页数
			$limitpage = ($p-1) * $num;//每次查询取记录
			$rsProducts = $products_model->where($where)->limit($limitpage,$num)->order($order)->select();
			foreach($rsProducts as $key => $val) {
				$JSON = json_decode($val['products_json'], TRUE);
				if(isset($JSON["ImgPath"])) {
					$rsProducts[$key]['ImgPath'] = $JSON["ImgPath"][0];
				}else {
					$rsProducts[$key]['ImgPath'] =  SITE_URL . '/static/api/shop/skin/default/nopic.jpg';
				}
				$rsProducts[$key]['link'] = url('goods/index', array('id'=>$val['products_id']));
				$p_commit_total = model('user_order_commit')->where(array('Users_ID'=>$this->UsersID,'Product_ID'=>$val['products_id'],'MID'=>'shop'))->total();
				$rsProducts[$key]['products_commit'] = $p_commit_total;
			}
			if(count($rsProducts) > 0) {
				$data = array(
					'list' => $rsProducts,
					'totalpage' => $totalpage,
					'count' => $total,
				);
			}else {
				$data = array(//没有数据可加载
					'list' => '',
					'totalpage' => $totalpage,
					'count' => $total,
				);
			}
			 $this->ajaxReturn($data);
		}
	}
	function _getRecommend(){
		$rsProducts = model('shop_products')->where(array('Users_ID'=>$this->UsersID,'Products_SoldOut'=>0,'Products_IsRecommend'=>1,'Products_Status'=>1))->select();
		foreach($rsProducts as $key => $val){
			$JSON = json_decode($val['products_json'], TRUE);
			if(isset($JSON["ImgPath"])){
				$rsProducts[$key]['ImgPath'] = $JSON["ImgPath"][0];
			}else{
				$rsProducts[$key]['ImgPath'] =  SITE_URL . '/static/api/shop/skin/default/nopic.jpg';
			}
			$rsProducts[$key]['link'] = url('goods/index', array('id'=>$val['products_id']));
		}
		return $rsProducts;
	}
	function _getHot(){
		$rsProducts = model('shop_products')->where(array('Users_ID'=>$this->UsersID,'Products_SoldOut'=>0,'Products_IsHot'=>1,'Products_Status'=>1))->limit(0,4)->select();
		foreach($rsProducts as $key => $val){
			$JSON = json_decode($val['products_json'], TRUE);
			if(isset($JSON["ImgPath"])){
				$rsProducts[$key]['ImgPath'] = $JSON["ImgPath"][0];
			}else{
				$rsProducts[$key]['ImgPath'] =  SITE_URL . '/static/api/shop/skin/default/nopic.jpg';
			}
			$rsProducts[$key]['link'] = url('goods/index', array('id'=>$val['products_id']));
		}
		return $rsProducts;
	}
}
?>