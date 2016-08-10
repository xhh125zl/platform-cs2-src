<?php
namespace shop\controller;
class goodsController extends controllController {
	private $id = 0;
	public function __construct() {
		parent::_initialize();
		if(!empty($_GET['id'])) {
			$this->id = $_GET['id'];
		}else {
			$this->error('缺少参数');
		}
	}
	
	public function indexOp() {
		$category_model = model('shop_category');
		$products_model = model('shop_products');
		$rsProducts = $products_model->where(array('Users_ID'=>$this->UsersID, 'Products_ID'=>$this->id, 'Products_SoldOut'=>0, 'Products_Status'=>1))->find();
		if(!$rsProducts){
			$this->error('产品已下架！');
		}
		$JSON = json_decode($rsProducts['products_json'], TRUE);
		if(isset($JSON["ImgPath"])) {
			$rsProducts['ImgPath'] = $JSON["ImgPath"][0];
		}else {
			$rsProducts['ImgPath'] =  SITE_URL . '/static/api/shop/skin/default/nopic.jpg';
		}
		$this->assign('title', $rsProducts['products_name']);
		$cidArr = array_filter(explode(',', $rsProducts['products_category']));
		$cid = array_shift($cidArr);
		$cateInfo = $category_model->where(array('Users_ID'=>$this->UsersID,'Category_ID'=>$cid))->find();
		if(!empty($cateInfo['category_parentid'])) {
            $parentInfo = $category_model->where(array('Users_ID'=>$this->UsersID,'Category_ID'=>$cateInfo['category_parentid']))->find();
			$Bread = array(
				url('index/index', array('UsersID'=>$this->UsersID)) => '首页',
				url('list/index', array('UsersID'=>$this->UsersID, 'id'=>$parentInfo['category_id'])) => $parentInfo['category_name'],
				url('list/index', array('UsersID'=>$this->UsersID, 'id'=>$cateInfo['category_id'])) => $cateInfo['category_name'],
				url('goods/index', array('UsersID'=>$this->UsersID, 'id'=>$this->id)) => $rsProducts['products_name']
			);
		}else {
			$Bread = array(
				url('index/index', array('UsersID'=>$this->UsersID)) => '首页',
				url('list/index', array('UsersID'=>$this->UsersID, 'id'=>$cateInfo['category_id'])) => $cateInfo['category_name'],
				url('goods/index', array('UsersID'=>$this->UsersID, 'id'=>$this->id)) => $rsProducts['products_name']
			);
		}
		$this->assign('Bread', $Bread);//面包屑
		
		/*若用户已经登陆，判断此商品是否被当前登陆用户收藏*/
		$favourite_model = model('user_favourite_products');
		$favourite_products_total = $favourite_model->where(array('Products_ID'=>$this->id))->total();
		$rsProducts['favourite_products_total'] = $favourite_products_total;
		$rsProducts['products_isfavourite'] = 0;
		if(!empty($_SESSION[$this->UsersID . 'User_ID'])) {
			$rsUser = model('user')->field('User_Level')->where(array('Users_ID'=>$this->UsersID, 'User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find();
			$rsFavourites = model('user_favourite_products')->field('Products_ID')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Products_ID'=>$this->id))->find();
				
			if($rsFavourites) {
				$rsProducts['products_isfavourite'] = 1;
			}
		}
		
		//获取登录用户的用户级别及其是否对应优惠价
		$rsUserConfig = model('user_config')->field('UserLevel')->where(array('Users_ID'=>$this->UsersID))->find();
		$discount_list = json_decode($rsUserConfig['UserLevel'], TRUE);

		$cur_price = $rsProducts['products_pricex'];
		//必选属性价格
        $properties = get_product_properties($this->id);  // 获得商品的规格和属性
		if(!empty($properties['spe'])) {
			$specification = $properties['spe'];
			foreach($specification as $Attr_ID=>$item) {
				if($item['Attr_Type'] == 1) {
					foreach($item['Values'] as $k=>$v){
						if($k == 0) {
							$cur_price += $v['price'];
						}
						
					}
				}
			}
		}else {
			$specification = array();
		}
		
		//评论
		$commit_model = model('user_order_commit');
		$commit = $commit_model->field('count(*) as num, sum(Score) as score')->where(array('Users_ID'=>$this->UsersID, 'Status'=>1, 'Product_ID'=>$rsProducts['products_id']))->find();
		$commitList = $commit_model->where(array('Users_ID'=>$this->UsersID, 'Status'=>1, 'Product_ID'=>$rsProducts['products_id']))->order('CreateTime DESC')->select();
		$this->assign('commit', $commit);
		$this->assign('commitList', $commitList);
		
		$this->assign('cur_price', $cur_price);
		$this->assign('properties', $properties);
		$this->assign('specification', $specification);
		$this->assign('rsProducts', $rsProducts);
		$this->assign('Images', $JSON["ImgPath"]);
		$this->assign('history_goods', $this->_getHistory($cateInfo['category_id'])); //看了又看
		$this->assign('same_goods', $this->_getSame($cateInfo['category_id'])); //商家同款
		$this->display('goods.php', 'home', 'home_layout');
	}
	function _getHistory($no_cateID) {
		$rsProducts = model('shop_products')->where(array('Users_ID'=>$this->UsersID,'Products_SoldOut'=>0,'Products_Status'=>1,'Products_Category !='=>$no_cateID))->limit(0,4)->select();
		foreach($rsProducts as $key => $val) {
			$JSON = json_decode($val['products_json'], TRUE);
			if(isset($JSON["ImgPath"])){
				$rsProducts[$key]['ImgPath'] = $JSON["ImgPath"][0];
			}else{
				$rsProducts[$key]['ImgPath'] =  SITE_URL . '/static/api/shop/skin/default/nopic.jpg';
			}
			$rsProducts[$key]['link'] = url('goods/index', array('UsersID'=>$this->UsersID, 'id'=>$val['products_id']));
		}
		return $rsProducts;
	}
	//商家同款
	function _getSame($cateID) {
                $rsProducts_arr = array();
		$where['Users_ID'] = $this->UsersID;
		$where['Products_SoldOut'] = 0;
		$where['Products_Status'] = 1;
		$where['Products_Category'] = '%'.$cateID.'%';
		//$where['Products_ID'] != $this->id;
		$rsProducts = model('shop_products')->where($where)->limit(0,20)->select();
		foreach($rsProducts as $key => $val) {
			$JSON = json_decode($val['products_json'], TRUE);
			if(isset($JSON["ImgPath"])) {
				$rsProducts[$key]['ImgPath'] = $JSON["ImgPath"][0];
			}else {
				$rsProducts[$key]['ImgPath'] =  SITE_URL . '/static/api/shop/skin/default/nopic.jpg';
			}
			$rsProducts[$key]['link'] = url('goods/index', array('UsersID'=>$this->UsersID, 'id'=>$val['products_id']));
		}
		$rsProducts_arr = array_chunk($rsProducts,2);
		return $rsProducts_arr;
	}
}
?>