<?php
namespace shop\controller;
class distributeController extends controllController {
	private $rsDisAccount;
	public function __construct() {
		parent::_initialize();
		$this->check_login();
		$user_model = model('user');
		$rsUser = $user_model->field('Is_Distribute')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find();
                if(!$rsUser || ($rsUser['Is_Distribute'] == 0)) {
                    $this->error('您还不是分销商...');            
		}
		//$rsDisAccount = model('shop_distribute_account')->field('Account_ID,Is_Dongjie,Is_Delete,Is_Audit,status,balance,Enable_Tixian,Total_Income,Group_Num')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find();
		$rsDisAccount = model('distribute_account')->field('Account_ID,Is_Dongjie,Is_Delete,Is_Audit,status,balance,Enable_Tixian,Total_Income,Group_Num')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find();
                $this->rsDisAccount = $rsDisAccount;

		if($rsDisAccount){
			if($rsDisAccount['Is_Dongjie'] == 1 || $rsDisAccount['Is_Delete'] == 1) {//账号被冻结
				header('location:' . url('distribute/distribute_dongjie'));
			}

			if($rsDisAccount['Is_Audit'] == 0){
				$this->error('您的分销商账号正在审核...');
			}

			if($rsDisAccount['status'] == 0){
				$this->error('您的分销商账号已被禁用...');
			}
		}else{
			$no_check_arr = array('distribute_join','distribute_goods');
			if(!in_array($this->_action, $no_check_arr)){
				if(!$rsUser || ($rsUser['Is_Distribute'] == 0)) {
					header('location:' . url('distribute/distribute_join')); exit;
				}
			}
		}	
	}
	public function indexOp() {
		
	}
	public function distribute_inviteOp() {
		$this->assign('title', '分享返利');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('distribute/distribute_invite');
		$cur_url = get_cur_url($this->UsersID);
		$this->assign('cur_url', $cur_url);
		$this->assign('user_id', $_SESSION[$this->UsersID . 'User_ID']);
		$this->display('distribute_invite.php', 'distribute', 'distribute_layout');
	}
	//分销记录
	public function distribute_recordOp() {
		$filter = empty($_GET['filter']) ? (empty($_POST['filter']) ? 'all' : $_POST['filter']) : $_GET['filter'];
		$this->assign('filter', $filter);
		if(IS_AJAX) {
			if($filter != 'all') {
				if($filter == 'self') {

				    $sql1 = 'SELECT COUNT(*) as count FROM distribute_account_record as a LEFT JOIN distribute_record as r ON a.Ds_Record_ID=r.Record_ID LEFT JOIN shop_products as p ON p.Products_ID=r.Product_ID WHERE a.Users_ID="' . $this->UsersID . '" AND a.User_ID=' . $_SESSION[$this->UsersID . 'User_ID'] . ' AND r.Owner_ID=' . $_SESSION[$this->UsersID . 'User_ID'] . ' AND a.Record_Type=0';
				}elseif($filter == 'down') {
				    $sql1 = 'SELECT COUNT(*) as count FROM distribute_account_record as a LEFT JOIN distribute_record as r ON a.Ds_Record_ID=r.Record_ID LEFT JOIN shop_products as p ON p.Products_ID=r.Product_ID WHERE a.Users_ID="' . $this->UsersID . '" AND a.User_ID=' . $_SESSION[$this->UsersID . 'User_ID'] . ' AND r.Owner_ID!=' . $_SESSION[$this->UsersID . 'User_ID'] . ' AND a.Record_Type=0';
				}
			}else {
			    $sql1 = 'SELECT COUNT(*) as count FROM distribute_account_record as a LEFT JOIN distribute_record as r ON a.Ds_Record_ID=r.Record_ID LEFT JOIN shop_products as p ON p.Products_ID=r.Product_ID WHERE a.Users_ID="' . $this->UsersID . '" AND a.User_ID=' . $_SESSION[$this->UsersID . 'User_ID'] . ' AND a.Record_Type=0';

			}
			$count = model()->query($sql1, 'find');
			$num = 12;//每页记录数
			$p = !empty($_POST['p']) ? intval(trim($_POST['p'])) : 1;
			$total = $count['count'];//数据记录总数
			$totalpage = ceil($total / $num);//总计页数
			$limitpage = ($p-1) * $num;//每次查询取记录
			if($filter != 'all') {
				if($filter == 'self') {

				    $sql2 = 'SELECT * FROM distribute_account_record as a LEFT JOIN distribute_record as r ON a.Ds_Record_ID=r.Record_ID LEFT JOIN shop_products as p ON p.Products_ID=r.Product_ID WHERE a.Users_ID="' . $this->UsersID . '" AND a.User_ID=' . $_SESSION[$this->UsersID . 'User_ID'] . ' AND r.Owner_ID=' . $_SESSION[$this->UsersID . 'User_ID'] . ' AND a.Record_Type=0 order by a.Record_ID desc limit ' . $limitpage . ',' . $num;
					
				}elseif($filter == 'down') {
				    $sql2 = 'SELECT * FROM distribute_account_record as a LEFT JOIN distribute_record as r ON a.Ds_Record_ID=r.Record_ID LEFT JOIN shop_products as p ON p.Products_ID=r.Product_ID WHERE a.Users_ID="' . $this->UsersID . '" AND a.User_ID=' . $_SESSION[$this->UsersID . 'User_ID'] . ' AND r.Owner_ID!=' . $_SESSION[$this->UsersID . 'User_ID'] . ' AND a.Record_Type=0 order by a.Record_ID desc limit ' . $limitpage . ',' . $num;	
				}
			}else {
				$sql2 = 'SELECT * FROM distribute_account_record as a LEFT JOIN distribute_record as r ON a.Ds_Record_ID=r.Record_ID LEFT JOIN shop_products as p ON p.Products_ID=r.Product_ID WHERE a.Users_ID="' . $this->UsersID . '" AND a.User_ID=' . $_SESSION[$this->UsersID . 'User_ID'] . ' AND a.Record_Type=0  order by a.Record_ID desc limit ' . $limitpage . ',' . $num;	

			}
			$rsRecords = model()->query($sql2);
			
			if($rsRecords) {
				foreach($rsRecords as $key => $val) {
				    $rsRecords[$key]['Record_CreateTime'] = date('Y-m-d H:i:s', $val['Record_CreateTime']);
					$rsRecords[$key]['Record_Money'] = round_pad_zero($val['Record_Money'], 2);
					if($val['Record_Status'] == 0){
						$rsRecords[$key]['Status'] = '进行中';
					}else if($val['Record_Status'] == 1){
						$rsRecords[$key]['Status'] = '已付款';
					}else {
						$rsRecords[$key]['Status'] = '已完成';
					}
					if($val['Owner_ID'] == $_SESSION[$this->UsersID . 'User_ID']) {
						$rsRecords[$key]['Type'] = '自销';
					}else {
						$rsRecords[$key]['Type'] = '下级分销';
					}
					$rsRecords[$key]['P_URL'] = url('goods/index', array('id'=>$val['Products_ID']));
					$JSON = json_decode($val['Products_JSON'], TRUE);
					$rsRecords[$key]['ImgPath'] = $JSON['ImgPath'][0];
			    }
				$data = array(
					'list' => $rsRecords,
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
		}else {
			$this->assign('title', '分销明细');
			$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('distribute/distribute_record');
			
			//计算全部个数
			$all_distribute_count = model()->query('SELECT COUNT(*) as count FROM distribute_account_record WHERE Users_ID="' . $this->UsersID . '" AND User_ID=' . $_SESSION[$this->UsersID . 'User_ID'] . ' AND Record_Type=0','find');
			$this->assign('all_distribute_count', $all_distribute_count['count']);
			//自销次数
			$self_distribute_count = model()->query('SELECT COUNT(*) as count FROM distribute_account_record as a LEFT JOIN distribute_record as r ON a.Ds_Record_ID=r.Record_ID WHERE a.Users_ID="' . $this->UsersID . '" AND a.User_ID=' . $_SESSION[$this->UsersID . 'User_ID'] . ' AND r.Owner_ID=' . $_SESSION[$this->UsersID . 'User_ID'] . ' AND a.Record_Type=0','find');
			$this->assign('self_distribute_count', $self_distribute_count['count']);
			//下级销售次数
			$posterity_distribute_count = model()->query('SELECT COUNT(*) as count FROM distribute_account_record as a LEFT JOIN distribute_record as r ON a.Ds_Record_ID=r.Record_ID WHERE a.Users_ID="' . $this->UsersID . '" AND a.User_ID=' . $_SESSION[$this->UsersID . 'User_ID'] . ' AND r.Owner_ID!=' . $_SESSION[$this->UsersID . 'User_ID'] . ' AND a.Record_Type=0','find');

			$this->assign('posterity_distribute_count', $posterity_distribute_count['count']);
			
			$this->display('distribute_record.php', 'distribute', 'distribute_layout');
		}
	}
	public function distribute_joinOp() {
		$this->assign('title', '立即成为分销商');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('distribute/distribute_join');
		$rsUser = model('user')->field('*')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find();
		//$error_msg = pre_add_distribute_account($this->shopConfig, $this->UsersID);
                $error_msg = '';
		$html_mes = '';
		if($error_msg != '4'){
			switch($error_msg){
				case '1':
					$html_mes = '<div class="join_tip">在本店累计积满<span>' . $this->shopConfig['distribute_limit'] . '</span>积分，才可成为分销商，您当前有<span>'.$rsUser["User_TotalIntegral"].'</span>积分，马上赚积分<a style="color:#F60" href="' . url('index/index') . '">继续购物</a></div>'; 
				break;
				case '2':
					$arr_temp = explode('|', $this->shopConfig['distribute_limit']);
					$arr_temp[1] = !empty($arr_temp[1]) ? intval($arr_temp[1]) : 0;
					if($arr_temp[0]==0){
						$html_mes = '<div class="join_tip">在本店累计消费满<span>'.$arr_temp[1].'</span>元，即可成为分销商，您已累计消费<span>'.$rsUser["User_Cost"].'</span>元';
					}else{
						$html_mes = '<div class="join_tip">在本店一次性消费满<span>'.$arr_temp[1].'</span>元，即可成为分销商';
					}
					$html_mes .= '<a style="color:#F60" href="' . url('index/index') . '">继续购物</a></div>';
				break;
				case '3':
					$arr_temp = explode('|', $this->shopConfig['distribute_limit']);
					$arr_temp[1] = !empty($arr_temp[1]) ? $arr_temp[1] : 0;
					if($arr_temp[0] == 0) {
						$html_mes = '<div class="join_tip">在本店购买<span>任意商品</span>即可成为分销商<a style="color:#F60" href="' . url('index/index') . '">马上购买</a></div>';
					}else {
						$html_mes = '在本店购买以下任一商品即可成为分销商：<br />';
						$arr_temp[1] = $arr_temp[1] ? $arr_temp[1] : 0;
						if(!empty($arr_temp[1])) {
							$dis_product_ids = explode(',', $arr_temp[1]);
						}else {
							$dis_product_ids = 0;
						}
							
						$dis_products = model('shop_products')->where(array('Products_ID'=>$dis_product_ids))->select();
						foreach($dis_products as $k => $r) {
							$JSON = json_decode($r['products_json'], TRUE);
							$html_mes .= '<div class="list"><div class="list_img"><a href="' .url('distribute/distribute_goods', array('id'=>$r['products_id'])). '"><img title="'.$r['products_name'].'" src="'.$JSON['ImgPath'][0].'"></a></div><div class="list_word"><div class="bb_proce"><span>￥'.$r['products_pricex'].'</span><i>￥'.$r['products_pricey'].'</i></div><div class="bb_name"><a title="'.$r['products_name'].'" href="'.url('distribute/distribute_goods', array('id'=>$r['products_id'])).'">'.$r['products_name'].'</a></div></div></div>';
						}
					}
				break;
				case 'OK':
					header('location:' . url('member/index'));
					exit;
				break;
				default:
				    $this->error('发生未知错误，请稍后再试');
				break;
			}
		}
		$this->assign('owner', $this->owner);
		$this->assign('error_msg', $error_msg);
		$this->assign('html_mes', $html_mes);
		$this->display('distribute_join.php', 'distribute', 'distribute_layout');
	}
	//购买指定商品
	public function distribute_goodsOp() {
		$products_model = model('shop_products');
		$rsProducts = $products_model->where(array('Users_ID'=>$this->UsersID, 'Products_ID'=>$_GET['id'], 'Products_SoldOut'=>0, 'Products_Status'=>1))->find();
		if(!$rsProducts){
			$this->error('产品已下架！');
		}
		$JSON = json_decode($rsProducts['products_json'], TRUE);
		if(isset($JSON['ImgPath'])) {
			$rsProducts['ImgPath'] = $JSON['ImgPath'][0];
		}else {
			$rsProducts['ImgPath'] =  SITE_URL . '/static/api/shop/skin/default/nopic.jpg';
		}
		$this->assign('title', $rsProducts['products_name']);
		/*若用户已经登陆，判断此商品是否被当前登陆用户收藏*/
		$favourite_model = model('user_favourite_products');
		$favourite_products_total = $favourite_model->where(array('Products_ID'=>$_GET['id']))->total();
		$rsProducts['favourite_products_total'] = $favourite_products_total;
		$rsProducts['products_isfavourite'] = 0;
		if(!empty($_SESSION[$this->UsersID . 'User_ID'])) {
			$rsUser = model('user')->field('User_HeadImg,User_NickName,Is_Distribute,User_Level')->where(array('Users_ID'=>$this->UsersID, 'User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find();
			$rsFavourites = model('user_favourite_products')->field('Products_ID')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Products_ID'=>$_GET['id']))->find();
				
			if($rsFavourites) {
				$rsProducts['products_isfavourite'] = 1;
			}
		}
		
		//获取登录用户的用户级别及其是否对应优惠价
		$rsUserConfig = model('user_config')->field('UserLevel')->where(array('Users_ID'=>$this->UsersID))->find();
		$discount_list = json_decode($rsUserConfig['UserLevel'], TRUE);

		$cur_price = $rsProducts['products_pricex'];
		//必选属性价格
        $properties = get_product_properties($_GET['id']);  // 获得商品的规格和属性
		if(!empty($properties['spe'])) {
			$specification = $properties['spe'];
			foreach($specification as $Attr_ID=>$item) {
				if($item['Attr_Type'] == 1) {
					foreach($item['Values'] as $k => $v){
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
		
		//判断是否为分销商
		$head_img = '';
        $head_name = '您还不是分销商<br />立即购买，马上加入我们';
		$fx_enable = 0;
		if(!empty($_SESSION[$this->UsersID . 'User_ID'])){
			if($rsUser){
				$head_img = $rsUser['User_HeadImg'];
				if($rsUser['Is_Distribute']){
					$a = model('distribute_account')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find();

					if($a){
						if($a['enable_tixian'] == 1){
							$fx_enable = 1;
						}
					}
					$head_name = $rsUser['User_NickName'].'<br />已为“'.$this->shopConfig['shopname'].'”代言';
				}else{
					$head_name = $rsUser['User_NickName'].'，您还不是分销商<br />立即购买，马上加入我们';
				}
			}
		}
		$this->assign('head_name', $head_name);
		$this->assign('fx_enable', $fx_enable);
		$this->display('distribute_goods.php', 'distribute', 'distribute_layout');
	}
	//提现
	public function distribute_withdrawOp() {
		$this->assign('title', '佣金提现');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('distribute/distribute_withdraw');
		$status = !isset($_GET['status']) ? (!isset($_POST['status']) ? 'all' : $_POST['status']) : $_GET['status'];
		$this->assign('status', $status);

		$dis_account_record_model = model('distribute_account_record');

		if(IS_AJAX) {
            $condition = array(
			    'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],
				'Users_ID'=>$this->UsersID,
				'Record_Type'=>1
			);
			if($status != 'all') {
				$condition['Record_Status'] = intval($status);
			}
			$count = $dis_account_record_model->where($condition)->total();
			$num = 12;//每页记录数
			$p = !empty($_POST['p']) ? intval(trim($_POST['p'])) : 1;
			$total = $count;//数据记录总数
			$totalpage = ceil($total / $num);//总计页数
			$limitpage = ($p-1) * $num;//每次查询取记录
			$rsRecords = $dis_account_record_model->field('*')->where($condition)->limit($limitpage, $num)->select();
			
			if($rsRecords) {
				foreach($rsRecords as $key => $val) {
				    $rsRecords[$key]['Record_CreateTime'] = date('Y-m-d H:i:s', $val['Record_CreateTime']);
					$rsRecords[$key]['Record_Money'] = round_pad_zero($val['Record_Money'], 2);
					if($val['Record_Status'] == 0){
						$rsRecords[$key]['Status'] = '申请中';
					}else if($val['Record_Status'] == 1){
						$rsRecords[$key]['Status'] = '已执行';
					}else if($val['Record_Status'] == 2){
						$rsRecords[$key]['Status'] = '已驳回';
					}
			    }
				$data = array(
					'list' => $rsRecords,
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
		}else {
			$total_income = round_pad_zero(get_my_leiji_income($this->UsersID, $_SESSION[$this->UsersID . 'User_ID']), 2);
			$this->assign('total_income', $total_income);
			$this->assign('rsDisAccount', $this->rsDisAccount);
			if($this->rsDisAccount['Enable_Tixian'] == 0){
				if($this->shopConfig['withdraw_type'] == 0){//无限制
					$data['Enable_Tixian'] = 1;

					model('distribute_account')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->update($data);
				}elseif($this->shopConfig['withdraw_type'] == 1){//佣金限制
					if($this->shopConfig['withdraw_limit'] == 0){			
						$data['Enable_Tixian'] = 1;
					    model('distribute_account')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->update($data);			
					}else{
						if($this->rsDisAccount['Total_Income'] >= $this->shopConfig['withdraw_limit']){
							$data['Enable_Tixian'] = 1;
					        model('distribute_account')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->update($data);

						}else{
							header('location:' . url('distribute/withdraw_apply'));
						}
					}
				}else{
					header('location:' . url('distribute/withdraw_apply'));
				}
			}
			//提现方式列表
			$user_method_list = model('shop_user_withdraw_methods')->field('*')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->select();
			$this->assign('user_method_list', $user_method_list);
			
			//获取此用户可用的提现方式
			$condition = array(
			    'Users_ID'=>$this->UsersID,
				'Status'=>1,
			);
			$rsMethods = model('shop_withdraw_method')->field('*')->where($condition)->select();
			$this->assign('rsMethods', $rsMethods);
			$this->assign('rsConfig', $this->shopConfig);
			$this->display('distribute_withdraw.php', 'distribute', 'distribute_layout');
		}
	}
	public function distribute_withdraw_methodOp() {
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('distribute/distribute_withdraw_method');
        $this->assign('title', '提现方式管理');
		//获取此用户可用的提现方式 
        $condition = array('Users_ID'=>$this->UsersID,'Status'=>1);
        $enabled_method_list = model('shop_withdraw_method')->field('*')->where($condition)->select();
		$this->assign('enabled_method_list', $enabled_method_list);
        //银行账号列表
		$method_list = model('shop_user_withdraw_methods')->field('*')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->select();
		$this->assign('method_list', $method_list);
		$this->display('distribute_withdraw_method.php', 'distribute', 'distribute_layout');
	}
	public function distribute_qrcodehbOp() {
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('distribute/distribute_qrcodehb');
		$this->assign('title', '推广二维码');
		$rsUser = model('user')->field('*')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find();
		if($rsUser['Is_Distribute'] == 1){
			$poster_path = SITE_PATH . '/data/poster/' . $this->UsersID . $this->owner['id'] . '.png';	

                        $poster_web_path = '/data/poster/' . $this->UsersID . $this->owner['id'] . '.png';
			$rsAccount = model('distribute_account')->field('*')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find();

			$this->assign('rsUser', $rsUser);
			$this->assign('rsAccount', $rsAccount);
			$this->assign('rsConfig', $this->shopConfig);
			$weixin_qrcode = new \vendor\weixin_qrcode($this->UsersID);
			$qrcode_url = $weixin_qrcode->get_qrcode('user_' . $this->owner['id']);
			$this->assign('qrcode_url', $qrcode_url);
		}
		$this->display('distribute_qrcodehb.php', 'distribute', 'null_layout');
	}
	//我的团队
	public function distribute_groupOp() {
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('distribute/distribute_group');
		$this->assign('title', '我的团队');

		$rsAccount = model('distribute_account')->field('*')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find();

		$this->assign('rsAccount', $rsAccount);
		$level_config = $this->shopConfig['dis_level'];
		$posterity_list = getPosterity($this->UsersID, $_SESSION[$this->UsersID . 'User_ID'], $level_config);
		foreach($posterity_list as $k => $v){
			$posterity_list[$k]['Account_CreateTime'] = date('Y-m-d H:i:s', $v['Account_CreateTime']);
			if(empty($v['Shop_Name'])) {
				$posterity_list[$k]['Shop_Name'] = '暂无';
			}
			if(empty($v['child'])){
				$posterity_list[$k]['child'] = 0;
			}
		}
		$level_name_list = array(1 => '一级分销商', 2 => '二级分销商', 3 => '三级分销商',
                         4 => '四级分销商',5=>'五级分销商',6=>'六级分销商',
						 7=>'七级分销商',8=>'八级分销商',9=>'九级分销商');
		$this->assign('level_name_list', $level_name_list);
		$this->assign('posterity_list', $posterity_list);
		$this->display('distribute_group.php', 'distribute', 'distribute_layout');
	}
	//提现门槛
	public function withdraw_applyOp() {
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('distribute/withdraw_apply');
		$this->assign('title', '您还未拥有提现权利');
		$html_mes = '';
		
		if($this->rsDisAccount['Enable_Tixian'] == 1){
			header('location:' . url('distribute/distribute_withdraw'));exit;
		}else{
			if($this->shopConfig['withdraw_type'] == 0){

				model('distribute_account')->where(array('Users_ID'=>$this->UsersID,'Account_ID'=>$this->rsDisAccount['Account_ID']))->update(array('Enable_Tixian'=>1));
				header('location:' . url('distribute/distribute_withdraw'));exit;
			}elseif($this->shopConfig['withdraw_type'] == 1){
				if($this->rsDisAccount['Total_Income'] >= $this->shopConfig['withdraw_limit']){
					model('distribute_account')->where(array('Users_ID'=>$this->UsersID,'Account_ID'=>$this->rsDisAccount['Account_ID']))->update(array('Enable_Tixian'=>1));

				    header('location:' . url('distribute/distribute_withdraw'));exit;
				}else{
					$html_mes = '<div class="join_tip">当您所得佣金满<span>' . $this->shopConfig['withdraw_limit'] . '</span>元时，即可拥有提现权利；您当前获得佣金<span>' . $this->rsDisAccount['Total_Income'] . '</span>元</div>';
				}
			}elseif($this->shopConfig['withdraw_type'] == 2){
				$arr_temp = explode('|', $this->shopConfig['withdraw_limit']);
				$arr_temp[1] = !empty($arr_temp[1]) ? $arr_temp[1] : 0;
				if($arr_temp[0] == 0){
					$html_mes = '<div class="join_tip">在本店购买<span>任意商品</span>即可拥有提现权利<a href="' . url('index/index') . '">马上购买</a></div>';
				}else{
					$html_mes = '在本店购买以下任一商品即可拥有提现权利：<br />';
					$arr_temp[1] = $arr_temp[1] ? $arr_temp[1] : 0;
					if(!empty($arr_temp[1])) {
						$product_ids = explode(',', $arr_temp[1]);
					}else {
						$product_ids = 0;
					}
							
					$products = model('shop_products')->where(array('Products_ID'=>$product_ids))->select();
					foreach($products as $k => $r) {
						$JSON = json_decode($r['products_json'], TRUE);
						$html_mes .= '<div class="list"><div class="list_img"><a href="' .url('distribute/distribute_goods', array('id'=>$r['products_id'])). '"><img title="'.$r['products_name'].'" src="'.$JSON['ImgPath'][0].'"></a></div><div class="list_word"><div class="bb_proce"><span>￥'.$r['products_pricex'].'</span><i>￥'.$r['products_pricey'].'</i></div><div class="bb_name"><a title="'.$r['products_name'].'" href="'.url('distribute/distribute_goods', array('id'=>$r['products_id'])).'">'.$r['products_name'].'</a></div></div></div>';
					}
				}
			}
		}
		$this->assign('html_mes', $html_mes);
		$this->display('withdraw_apply.php', 'distribute', 'distribute_layout');
	}
	//我的称号(爵位晋级)
	public function pro_titleOp() {
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('distribute/withdraw_apply');
		$this->assign('title', '爵位晋升');
		//会员信息
        $front_title = get_dis_pro_title($this->UsersID);
		$this->assign('front_title', $front_title);
		//消费额
		$level_config = $this->shopConfig['dis_level'];
		$posterity = getPosterity($this->UsersID, $_SESSION[$this->UsersID . 'User_ID'], $level_config);
		$total_sales = round_pad_zero(get_my_leiji_sales($this->UsersID,$_SESSION[$this->UsersID . 'User_ID'],$posterity),2);
		
		$this->assign('total_sales', $total_sales?:'0.00');
		
		//直销人数

		$user_count = model('distribute_account')->where(array('invite_id'=>$_SESSION[$this->UsersID . 'User_ID']))->total();

		$this->assign('user_count', $user_count);
		$ex_bonus = array(
			'total' => 0.00,
			'pay' => 0.00,
			'payed' => 0.00
		);

		//$dis_account_record_list = model('distribute_account_record')->field('Nobi_Money,Record_Status,Nobi_Status')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Nobi_Money >'=>0))->select();
		$dis_account_record_list = model('distribute_account_record')->field('Nobi_Money,Record_Status')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Nobi_Money >'=>0))->select();
                foreach($dis_account_record_list as $k => $r){

			if($r['Record_Status'] == 2){
				$ex_bonus['payed'] += $r['Nobi_Money'];
			}else{
				$ex_bonus['pay'] += $r['Nobi_Money'];
			}
			$ex_bonus['total'] += $r['Nobi_Money'];
		}
		$this->assign('ex_bonus', $ex_bonus);
		$this->assign('rsDisAccount', $this->rsDisAccount);
		$this->display('pro_title.php', 'distribute', 'distribute_layout');
	}
}
?>