<?php
namespace member\controller;
class web_apiController extends controllController {
	public function __construct() {
		parent::_initialize();
	}

	/**
	 * 商品推荐
	 */
	public function recommend_listOp() {
		$condition = array();
		$condition['Products_SoldOut'] = 0;
		$condition['Users_ID'] = $_SESSION['Users_ID'];
		$condition['Products_Status'] = 1;
		$gc_id = intval($_GET['id']);
		if ($gc_id > 0) {
			$condition['Products_Category'] = '%,' . $gc_id . ',%';
		}
		if (!empty($_GET['goods_name'])) {
			$goods_name = trim($_GET['goods_name']);
			$condition['Products_Name'] = '%'.$goods_name.'%';
		}
		$goods_list = model('shop_products')->where($condition)->order('Products_ID desc')->select();
		foreach($goods_list as $key => $val) {
			$JSON = json_decode($val['products_json'], TRUE);
			if(isset($JSON["ImgPath"])){
				$goods_list[$key]['ImgPath'] = $JSON["ImgPath"][0];
			}else {
				$goods_list[$key]['ImgPath'] =  SITE_URL . '/static/api/shop/skin/default/nopic.jpg';
			}
		}
		//$this->assign('show_page', $model_web_config->showpage(2));
		$this->assign('show_page', '1');
		$this->assign('goods_list', $goods_list);
		$this->display('web_goods.list.php', '@', 'null_layout');
	}

	/**
	 * 保存设置
	 */
	public function code_updateOp() {
		$code_id = intval($_POST['code_id']);
		$web_id = intval($_POST['web_id']);
		$model_web_code = model('web_code');
		$code = $model_web_code->where(array('code_id'=>$code_id,'web_id'=>$web_id))->find();
		if (!empty($code)) {
			$code_type = $code['code_type'];
			$var_name = $code['var_name'];
			$code_info = $_POST[$var_name];
			$code_info = $this->get_str($code_info, $code_type);
			$state = $model_web_code->where(array('code_id'=> $code_id))->update(array('code_info'=> $code_info));
		}
		if($state) {
			echo '1';exit;
		} else {
			echo '0';exit;
		}
	}

	/**
	 * 保存图片
	 */
	public function upload_picOp() {
		$code_id = intval($_POST['code_id']);
		$web_id = intval($_POST['web_id']);
		$model_web_config = model('web_code');
		$code = $model_web_config->where(array('code_id'=>$code_id,'web_id'=>$web_id))->find();
		if (!empty($code)) {
			$code_type = $code['code_type'];
			$var_name = $code['var_name'];
			$code_info = $_POST[$var_name];

			$file_name = 'web-'.$web_id.'-'.$code_id;
			$pic_name = $this->_upload_pic($file_name);//上传图片
			if (!empty($pic_name)) {
				$code_info['pic'] = $pic_name;
			}
			$this->assign('var_name', $var_name);
			$this->assign('pic', $code_info['pic']);
			$this->assign('type', $code_info['type']);
			//$this->assign('ap_id', $code_info['ap_id']);
			$code_info = $this->get_str($code_info, $code_type);
			$state = $model_web_config->where(array('code_id'=>$code_id))->update(array('code_info'=>$code_info));
			$this->display('web_upload_pic.php', '@', 'null_layout');
		}
	}

	/**
	 * 保存楼层切换图片
	 */
	public function slide_advOp() {
		$code_id = intval($_POST['code_id']);
		$web_id = intval($_POST['web_id']);
		$model_web_code = model('web_code');
		$code = $model_web_code->where(array('code_id'=>$code_id,'web_id'=>$web_id))->find();
		if (!empty($code)) {
			$code_type = $code['code_type'];
			$var_name = $code['var_name'];
			$code_info = $_POST[$var_name];

			$pic_id = intval($_POST['slide_id']);
			if ($pic_id > 0) {
    			$var_name = "slide_pic";
    			$pic_info = $_POST[$var_name];
    			$pic_info['pic_id'] = $pic_id;
    			if (!empty($code_info[$pic_id]['pic_img'])) {//原图片
    			    $pic_info['pic_img'] = $code_info[$pic_id]['pic_img'];
    			}
    			$file_name = 'web-'.$web_id.'-'.$code_id.'-'.$pic_id;
    			$pic_name = $this->_upload_pic($file_name);//上传图片
    			if (!empty($pic_name)) {
    				$pic_info['pic_img'] = $pic_name;
    			}

			    $code_info[$pic_id] = $pic_info;
			    $this->assign('pic', $pic_info);
			}
			$code_info = $this->get_str($code_info, $code_type);
			$state = $model_web_code->where(array('code_id' => $code_id))->update(array('code_info' => $code_info));
    		$this->display('web_upload_slide.php', '@', 'null_layout');
		}
	}

	/**
	 * 上传图片
	 */
	private function _upload_pic($file_name) {
	    $pic_name = '';
	    if (!empty($file_name)) {
			if (!empty($_FILES['pic']['name'])) {//上传图片
				$upload = new \vendor\uploadfile();
				$filename_tmparr = explode('.', $_FILES['pic']['name']);
				$ext = end($filename_tmparr);
    			$upload->set('default_dir','uploadfiles/' . $_SESSION['Users_ID'] . '/image');
    			$upload->set('file_name',$file_name.".".$ext);
				$result = $upload->upfile('pic');
				if ($result) {
					$pic_name = $upload->file_name . '?' . mt_rand(100,999);//加随机数防止浏览器缓存图片
				}
			}
	    }
	    return $pic_name;
	}
	
	public function category_listOp() {
		$gc_parent_id = intval($_GET['id']);
		$condition['Category_ParentID'] = $gc_parent_id;
		$goods_class = $this->getGoodsClassList($condition, $field = '*');
		$this->assign('goods_class',$goods_class);
		$this->display('web_goods_class.php', '@', 'null_layout');
	}
	
	//json输出商品分类 菜单联动
	public function josn_classOp() {
		$gc_parent_id = intval($_GET['gc_id']);
		$condition['Category_ParentID'] = $gc_parent_id;
		$goods_class = $this->getGoodsClassList($condition, $field = '*');
		$array = array();
		if(is_array($goods_class) and count($goods_class)>0) {
			foreach ($goods_class as $val) {
				//$array[$val['Category_ID']] = array('gc_id'=>$val['Category_ID'],'gc_name'=>htmlspecialchars($val['Category_Name']),'gc_parent_id'=>$val['Category_ParentID'],'commis_rate'=>$val['commis_rate'],'gc_sort'=>$val['gc_sort']);
				$array[$val['Category_ID']] = array('gc_id'=>$val['Category_ID'],'gc_name'=>htmlspecialchars($val['Category_Name']),'gc_parent_id'=>$val['Category_ParentID']);
			}
		}
		$array = array_values($array);
		echo $_GET['callback'].'('.json_encode($array).')';
	}
}