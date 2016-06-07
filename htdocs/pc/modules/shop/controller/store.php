<?php
namespace shop\controller;
/**
 * 店铺装修
 *
 **/
class storeController extends controllController {
	public $rsBiz = array();
    public function __construct() {
        parent::_initialize();
	    if(isset($_GET['id'])) {
			$BizID = $_GET['id'];
			$this->rsBiz = model()->query("SELECT b.*,g.Group_IsStore FROM biz as b,biz_group as g WHERE b.Group_ID=g.Group_ID and b.Biz_Status=0 and b.Biz_ID=" . $BizID, 'find');
			if(empty($this->rsBiz['Biz_ID'])) {
				$this->error('您要访问的商铺不存在！');
			}
			if(empty($this->rsBiz['Group_IsStore'])) {
				$IsStore = 0;
			}else {
				$IsStore = $this->rsBiz['Group_IsStore'];
			}
			
			if($IsStore == 0) {
				$this->error('您要访问的商铺不存在！');
			}
		}else {
			$this->error('缺少必要的参数');
		}
		//banner
		$store_banner = $this->rsBiz['pc_banner'];
		$this->assign('store_banner', $store_banner);
		//导航颜色
		$store_color = $this->rsBiz['pc_bg_color'];
		$this->assign('store_color', $store_color);
		//导航
		$store_menu = model('biz_menu')->where(array('Biz_ID'=>$this->rsBiz['Biz_ID']))->select();
		$this->assign('store_menu', $store_menu);
    }
	public function indexOp() {
		$this->assign('title', $this->rsBiz['Biz_Name']);
		//评论
		$commit_model = model('user_order_commit');
		$commit = $commit_model->field('count(*) as num, sum(Score) as score')->where(array('Users_ID'=>$this->UsersID, 'Biz_ID'=>$this->rsBiz['Biz_ID'], 'Status'=>1))->find();
		$average_score = 0;
		if($commit && $commit['score'] && $commit['num']){
			$average_score =  number_format($commit['score'] / $commit['num'], 1, '.', '');
		}
		$this->assign('average_score', $average_score);
		
		$this->assign('rsBiz', $this->rsBiz);
		$this->assign('bizCategoryTree', $this->bizCategoryTree());
		$shop_products_model = model('shop_products');
		//推荐商品
		$_RecProducts = $shop_products_model->field('*')->where(array('Users_ID'=>$this->UsersID,'Biz_ID'=>$this->rsBiz['Biz_ID'],'Products_Status'=>1,'Products_SoldOut'=>0,'Products_BizIsRec'=>1))->order('Products_CreateTime desc')->select();
		$this->assign('RecProducts', $_RecProducts);
		//新品上市
		$_NewProducts = $shop_products_model->field('*')->where(array('Users_ID'=>$this->UsersID,'Biz_ID'=>$this->rsBiz['Biz_ID'],'Products_Status'=>1,'Products_SoldOut'=>0,'Products_BizIsNew'=>1))->order('Products_CreateTime desc')->select();
		$this->assign('NewProducts', $_NewProducts);
		//热卖商品
		$_HotProducts = $shop_products_model->field('*')->where(array('Users_ID'=>$this->UsersID,'Biz_ID'=>$this->rsBiz['Biz_ID'],'Products_Status'=>1,'Products_SoldOut'=>0,'Products_BizIsHot'=>1))->order('Products_CreateTime desc')->select();
		$this->assign('HotProducts', $_HotProducts);
		
		//幻灯片
		$store_slide = unserialize(htmlspecialchars_decode($this->rsBiz['pc_slide']));
		$this->assign('store_slide', $store_slide);
		$this->display('index.php', 'biz/' . $this->rsBiz['PC_Skin_ID'], 'home_layout');
	}
	public function listOp() {
		$this->assign('title', '商品列表');
		$this->assign('rsBiz', $this->rsBiz);
		$cid = empty($_GET['cid']) ? '' : $_GET['cid'];
		$type = empty($_GET['type']) ? '' : $_GET['type'];
		$products_model = model('shop_products');
		if(IS_AJAX) {
			if(!empty($_GET['sort'])) {
				switch($_GET['sort']){
					case 'price_a':
						$order = 'Products_PriceX asc';
						break;
					case 'price_d':
						$order = 'Products_PriceX desc';
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
			if(!empty($type)) {
				switch($type){
					case 'rec':
						$where['Products_BizIsRec'] = 1;
						break;
					case 'new':
						$where['Products_BizIsNew'] = 1;
						break;
					case 'hot':
						$where['Products_BizIsHot'] = 1;
						break;
				}
			}
			if(!empty($_GET['search'])){
				$where['Products_Name'] = '%' . $_GET['search'] . '%';
			}
			$where['Users_ID'] = $this->UsersID;
			$where['Biz_ID'] = $this->rsBiz['Biz_ID'];
			$where['Products_SoldOut'] = 0;
			$where['Products_Status'] = 1;
			if($cid){
				$where['Products_Category'] = '%,' . $cid . ',%';
			}
			$count = $products_model->where($where)->total();
			$num = 12;//每页记录数
			$p = !empty($_GET['p']) ? intval(trim($_GET['p'])) : 1;
			$total = $count;//数据记录总数
			$totalpage = ceil($total / $num);//总计页数
			$limitpage = ($p-1) * $num;//每次查询取记录
			$rsProducts = $products_model->where($where)->limit($limitpage,$num)->order($order)->select();
			foreach($rsProducts as $key => $val) {
				$JSON = json_decode($val['products_json'], TRUE);
				if(isset($JSON['ImgPath'])) {
					$rsProducts[$key]['ImgPath'] = $JSON['ImgPath'][0];
				}else {
					$rsProducts[$key]['ImgPath'] =  SITE_URL . '/static/api/shop/skin/default/nopic.jpg';
				}
				$rsProducts[$key]['link'] = url('goods/index', array('id'=>$val['products_id']));
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
		$this->display('list.php', 'biz/' . $this->rsBiz['PC_Skin_ID'], 'home_layout');
	}
	private function bizCategoryTree() {
		$CategoryList = model('biz_category')->field('*')->where(array('Users_ID'=>$this->UsersID,'Biz_ID'=>$this->rsBiz['Biz_ID']))->order('Category_Index desc')->select();
		$categoryTree = array();
		if(count($CategoryList) >0){
			$param = array('result'=>$CategoryList,'fields'=>array('Category_ID','Category_ParentID'));
			$generalTree = new \vendor\General_tree($param);
			
			//生成分类树
			$categoryTree = $generalTree->leaf();
		}
		return $categoryTree;
	}
}
