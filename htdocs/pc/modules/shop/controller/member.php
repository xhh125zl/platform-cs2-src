<?php
namespace shop\controller;
class memberController extends controllController {
	public function __construct() {
		parent::_initialize();
		$this->check_login();
	}
	
	public function indexOp() {
		$this->assign('title', '会员中心');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('member/index');
		$user_order = model('user_order');
		$num0 = $num1 = $num2 = $num3 = 0;
		$num0 = $user_order->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Order_Type'=>'shop','Order_Status'=>1))->total();
		$num1 = $user_order->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Order_Type'=>'shop','Order_Status'=>2))->total();
		$num2 = $user_order->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Order_Type'=>'shop','Order_Status'=>3))->total();
		$num3 = $user_order->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Order_Type'=>'shop','Order_Status'=>4))->total();
		$this->assign('order_num', [$num0,$num1,$num2,$num3]);
		//佣金
		$total_income = round_pad_zero(get_my_leiji_income($this->UsersID, $_SESSION[$this->UsersID . 'User_ID']), 2);
		$this->assign('total_income', $total_income);
		//我的订单
		$where['Users_ID'] = $this->UsersID;
		$where['User_ID'] = $_SESSION[$this->UsersID . 'User_ID'];
		$where['Order_Type'] = 'shop';
		$my_order = model('user_order')->field('*')->where($where)->order('Order_CreateTime desc')->limit('0,3')->select();
		foreach($my_order as $key => $val){
			$my_order[$key]['order_sn'] = date('Ymd', $val['Order_CreateTime']) . $val['Order_ID'];
			$CartList = json_decode(htmlspecialchars_decode($val['Order_CartList']), true);
			if(!empty($CartList)) {
				foreach($CartList as $k => $v){
					foreach($v as $k2 => $v2){
						$my_order[$key]['products_url'] = url('goods/index', array('id'=>$k));
						$my_order[$key]['products_img'] = $v2['ImgPath'];
						$my_order[$key]['ProductsPriceX'] = $v2['ProductsPriceX'];
						$my_order[$key]['ProductsPriceY'] = $v2['ProductsPriceY'];
						$my_order[$key]['ProductsQty'] = $v2['Qty'];
						$my_order[$key]['ProductsName'] = $v2['ProductsName'];
						//申请退款链接
						$my_order[$key]['backup_url'] = url('member/backup_apply', array('pama'=>$val['Order_ID'] . '_' . $k . '_' . $k2));
						//下单时间
						$my_order[$key]['Order_CreateTime'] = date('Y-m-d H:i:s', $val['Order_CreateTime']);
						//属性
						$my_order[$key]['Property'] = $v2['Property'];
						//物流跟踪
						$shipping = json_decode(htmlspecialchars_decode($val['Order_Shipping']), true) ;
						if(!empty($shipping['Express'])) {
							$shipping_trace = 'http://m.kuaidi100.com/index_all.html?type=' . $shipping['Express'] . '&postid=' . $val['Order_ShippingID'] . '&callbackurl=' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
								$Shipping_Express = !empty($shipping['Express']) ? $shipping['Express'] . '-' : '';
						}else {
							$shipping_trace = 'javascript:void(0)';
							$Shipping_Express = '';
						}
						//付款url
						$my_order[$key]['pay_url'] = url('payment/index',array('OrderID'=>$val['Order_ID']));
						$my_order[$key]['shipping_trace'] = $shipping_trace;
						$my_order[$key]['Express_Name'] = $Shipping_Express . $val['Order_ShippingID'];
						$my_order[$key]['status_arr'] = array('待确认','待付款','已付款','已发货','已完成');
						$my_order[$key]['detail_url'] = url('member/detail', array('id'=>$val['Order_ID']));
					}
				}
			}else {
				unset($my_order[$key]);
				continue;
			}
			//操作按钮
			$paydo_html = '';
			if($val['Order_Status'] == 0) {
				$paydo_html = '<a href="' . url('member/order_del', array('id'=>$my_order[$key]['Order_ID'])) . '">取消</a>';
			}else if($val['Order_Status'] == 1) {
				$paydo_html = '<a href="' . url('member/order_del', array('id'=>$my_order[$key]['Order_ID'])) . '">取消</a>&nbsp;&nbsp;<a href="' . $my_order[$key]['pay_url'] . '">付款</a>';
			}else if($val['Order_Status'] == 2) {
				$paydo_html = '等待发货..';
			}else if($val['Order_Status'] == 3) {
				$paydo_html = '<a href="javascript:;" class="confirm_receive" Order_ID="' . $my_order[$key]['Order_ID'] . '">确认收货</a>&nbsp;&nbsp;<a href="' . $my_order[$key]['backup_url'] . '">申请退款</a>';
			}else if($val['Order_Status'] == 4) {
			    $paydo_html = '<a href="javascript:;" class="commit" Order_ID="' . $my_order[$key]['Order_ID'] . '">评论</a>';
		    }
			$my_order[$key]['paydo_html'] = $paydo_html;
		}
		$this->assign('my_order', $my_order);
		
		//我的收藏
		$sql = "select f.FAVOURITE_ID,p.Products_ID,p.Products_Name,Products_PriceX,p.Products_PriceY,p.Products_JSON from Shop_Products as p join user_favourite_products as f on p.Products_id = f.Products_ID and f.User_ID =".$_SESSION[$this->UsersID . 'User_ID'];
		$shoucang = model()->query($sql, 'SELECT');
		foreach($shoucang as $key => $item){
			$JSON = json_decode($item['Products_JSON'], TRUE);				
			$shoucang[$key]['ImgPath'] = $JSON['ImgPath'][0];
			$shoucang[$key]['P_URL'] = url('goods/index', array('id'=>$item['Products_ID']));
		}
		$this->assign('shoucang', $shoucang);
		$this->display('index.php', 'member', 'member_layout');
	}
	//会员特权
	public function vip_privilegeOp() {
	    $this->assign('title', '会员特权');
	    $_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('member/vip_privilege');
		$user_card_benefits = model('user_card_benefits');
		if(IS_AJAX) {
		    $count = $user_card_benefits->field('*')->where(array('Users_ID'=>$this->UsersID))->total();
			$num = 12;//每页记录数
			$p = !empty($_POST['p']) ? intval(trim($_POST['p'])) : 1;
			$total = $count;//数据记录总数
			$totalpage = ceil($total / $num);//总计页数
			$limitpage = ($p-1) * $num;//每次查询取记录
			
			$rsRecords = $user_card_benefits->field('*')->where(array('Users_ID'=>$this->UsersID))->order('Benefits_ID desc')->limit($limitpage,$num)->select();
		    $rsConfig = model('user_config')->field('UserLevel')->where(array('Users_ID'=>$this->UsersID))->find();
			//会员等级
			if(!$rsConfig) {
				$flag = 0;
			}else {
			    $flag = 1;
				if(empty($rsConfig['UserLevel'])){
					$UserLevel[0] = array(
						'Name' => '普通会员',
						'UpIntegral' => 0,
						'ImgPath' => ''
					);
					$Data = array(
						'UserLevel' => json_encode($UserLevel, JSON_UNESCAPED_UNICODE)
					);
					model('user_config')->where(array('Users_ID'=>$this->UsersID))->update($Data);
				}else{
					$UserLevel = json_decode($rsConfig['UserLevel'], true);
				}
			}
			if($rsRecords) {
				foreach($rsRecords as $key => $val) {
				    //开始时间
					$rsRecords[$key]['Benefits_StartTime'] = date('Y-m-d H:i:s',$val['Benefits_StartTime']);
					//结束时间
					$rsRecords[$key]['Benefits_EndTime'] = date('Y-m-d H:i:s',$val['Benefits_EndTime']);
					//状态
					if($val['Benefits_StartTime'] > time()){
						$rsRecords[$key]['status'] = '未显示';
					}elseif($val['Benefits_EndTime'] < time()){
						$rsRecords[$key]['status'] = '已过期';
					}else{
						$rsRecords[$key]['status'] = '正常';
					}
					//会员等级
					$UserLevel = json_decode($rsConfig['UserLevel'], true);
					$rsRecords[$key]['Benefits_UserLevel'] = $UserLevel[$val['Benefits_UserLevel']]['Name'];
					//详情连接
					$rsRecords[$key]['url'] = url('member/vip_privilege_view', array('id'=>$val['Benefits_ID']));
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
		}
		$this->display('vip_privilege.php', 'member', 'member_layout');
	}
	public function vip_privilege_viewOp() {
	    $_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('member/vip_privilege_view');
	    if(empty($_GET['id'])) {
		    $this->error('非法操作');
		}else{
		   $id = $_GET['id'];
		}
		$info = model('user_card_benefits')->field('*')->where(array('Users_ID'=>$this->UsersID,'Benefits_ID'=>$id))->find();
		//开始时间
		$info['Benefits_StartTime'] = date('Y-m-d H:i:s',$info['Benefits_StartTime']);
		//结束时间
		$info['Benefits_EndTime'] = date('Y-m-d H:i:s',$info['Benefits_EndTime']);
		$this->assign('title', $info['Benefits_Title']);
		$this->assign('info', $info);
		$this->display('vip_privilege_view.php', 'member', 'member_layout');
	}
	public function order_delOp() {
		if(!empty($_GET['id'])){
			$Order_ID = $_GET['id'];
		}else{
			$this->error('缺少参数');
		}
	    //若是分销订单，删除分销记录
		if(is_distribute_order($this->UsersID, $Order_ID)){
			delete_distribute_record($this->UsersID, $Order_ID);
		}
		$condition = array(
		    'Users_ID'=>$this->UsersID,
			'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],
			'Order_ID'=>$Order_ID
		);
		$rsOrder = model('user_order')->field('Integral_Consumption')->where($condition)->find();
		
		$Flag_a = TRUE;
		if($rsOrder['Integral_Consumption'] > 0){
			$Falg_a = remove_userless_integral($this->UsersID, $_SESSION[$this->UsersID . 'User_ID'], $rsOrder['Integral_Consumption']);
		}
		$condition = array(
		    'Users_ID'=>$this->UsersID,
			'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],
			'Order_ID'=>$Order_ID
		);
		$Flag_b = model('user_order')->where($condition)->delete();
		
		if($Flag_a && $Flag_b) {
			$this->success('订单取消成功', url('member/status'));
		}else {
			$this->error('订单取消失败');
		}
	}
	//收货地址
	public function addressOp() {
		$this->assign('title', '我的收货地址');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('member/address');
		$address_list = model('user_address')->field('*')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->select();
        $area_json = read_file(SITE_PATH . '/data/area.js');
        $area_array = json_decode($area_json, TRUE);
		$this->assign('address_list', $address_list);
		$this->assign('area_array', $area_array);
		$this->assign('province_list', $area_array[0]);
		$this->display('address.php', 'member', 'member_layout');
	}
	//订单管理
	public function statusOp() {
		$this->assign('title', '订单管理');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('member/status');
		$order_model = model('user_order');
		if(IS_AJAX) {
			$where['Users_ID'] = $this->UsersID;
			$where['User_ID'] = $_SESSION[$this->UsersID . 'User_ID'];
			$where['Order_Type'] = 'shop';
			if(isset($_REQUEST['Status']) && $_REQUEST['Status'] != 'all'){
				$where['Order_Status'] = $_REQUEST['Status'];
			}
			$count = $order_model->where($where)->order('Order_CreateTime desc')->total();
			$num = 12;//每页记录数
			$p = !empty($_POST['p']) ? intval(trim($_POST['p'])) : 1;
			$total = $count;//数据记录总数
			$totalpage = ceil($total / $num);//总计页数
			$limitpage = ($p-1) * $num;//每次查询取记录
			$rsOrders = $order_model->field('*')->where($where)->limit($limitpage, $num)->order('Order_CreateTime desc')->select();
			foreach($rsOrders as $key => $val){
				$rsOrders[$key]['order_sn'] = date('Ymd', $val['Order_CreateTime']) . $val['Order_ID'];
				$rsOrders[$key]['del_url'] = url('member/order_del', array('id'=>$val['Order_ID']));
				$CartList = json_decode(htmlspecialchars_decode($val['Order_CartList']), true);
				if(!empty($CartList)) {
					foreach($CartList as $k => $v){
						foreach($v as $k2 => $v2){
							$rsOrders[$key]['products_url'] = url('goods/index', array('id'=>$k));
							$rsOrders[$key]['products_img'] = $v2['ImgPath'];
							$rsOrders[$key]['ProductsPriceX'] = $v2['ProductsPriceX'];
							$rsOrders[$key]['ProductsPriceY'] = $v2['ProductsPriceY'];
							$rsOrders[$key]['ProductsQty'] = $v2['Qty'];
							$rsOrders[$key]['ProductsName'] = $v2['ProductsName'];
							//申请退款链接
							$rsOrders[$key]['backup_url'] = url('member/backup_apply', array('pama'=>$val['Order_ID'] . '_' . $k . '_' . $k2));
							//下单时间
							$rsOrders[$key]['Order_CreateTime'] = date('Y-m-d H:i:s', $val['Order_CreateTime']);
							//属性
							$rsOrders[$key]['Property'] = $v2['Property'];
							//物流跟踪
							$shipping = json_decode(htmlspecialchars_decode($val['Order_Shipping']), true) ;
							if(!empty($shipping['Express'])) {
								$shipping_trace = 'http://m.kuaidi100.com/index_all.html?type=' . $shipping['Express'] . '&postid=' . $val['Order_ShippingID'] . '&callbackurl=' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
									$Shipping_Express = !empty($shipping['Express']) ? $shipping['Express'] . '-' : '';
							}else {
								$shipping_trace = 'javascript:void(0)';
								$Shipping_Express = '';
							}
							//付款url
							$rsOrders[$key]['pay_url'] = url('payment/index',array('OrderID'=>$val['Order_ID']));
							$rsOrders[$key]['shipping_trace'] = $shipping_trace;
							$rsOrders[$key]['Express_Name'] = $Shipping_Express . $val['Order_ShippingID'];
							$rsOrders[$key]['status_arr'] = array('待确认','待付款','已付款','已发货','已完成');
							$rsOrders[$key]['detail_url'] = url('member/detail', array('id'=>$val['Order_ID']));
						}
					}
				}else {
					unset($rsOrders[$key]);
					continue;
				}
			}
			if($rsOrders) {
				$data = array(
					'list' => $rsOrders,
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
			$this->display('orders.php', 'member', 'member_layout');
		}
	}
	//订单详情
	public function detailOp() {
		$this->assign('title', '订单详情');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('member/detail');
		if(!empty($_GET['id'])) {
			$OrderID = $_GET['id'];
		}else {
			$this->error('缺少参数');
		}
		$rsOrder = model('user_order')->field('*')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Order_ID'=>$OrderID))->find();
		$Status = $rsOrder['Order_Status'];
		$Order_Status = array('待付款','待确认','已付款','已发货','已完成');

		$Shipping = json_decode(htmlspecialchars_decode($rsOrder['Order_Shipping']), true);
		//var_dump(htmlspecialchars_decode($rsOrder['Order_Shipping']));exit;
		$CartList = json_decode(htmlspecialchars_decode($rsOrder['Order_CartList']), true);
		$fee = empty($Shipping['Price']) ? 0 : $Shipping['Price'];

		$lists_back = array();
		if($rsOrder['Is_Backup'] == 1){
			$condition = array(
			    'Users_ID'=>$this->UsersID,
				'Order_ID'=>$rsOrder['Order_ID'],
				'Back_Type'=>'shop'
			);
			$lists_back = model('user_back_order')->field('*')->where($condition)->select();
		}
		$_STATUS = array('<font style="color:#F00; font-size:12px;">申请中</font>','<font style="color:#F60; font-size:12px;">卖家同意</font>','<font style="color:#0F3; font-size:12px;">买家发货</font>','<font style="color:#600; font-size:12px;">卖家收货并确定退款价格</font>','<font style="color:blue; font-size:12px;">完成</font>','<font style="color:#999; font-size:12px; text-decoration:line-through;">卖家拒绝退款</font>');


		//收货地址
		$area_json = read_file(SITE_PATH . '/data/area.js');
		$area_array = json_decode($area_json, TRUE);
		$province_list = $area_array[0];
		$Province = '';
		if(!empty($rsOrder['Address_Province'])){
			$Province = $province_list[$rsOrder['Address_Province']] . ',';
		}
		$City = '';
		if(!empty($rsOrder['Address_City'])){
			$City = $area_array['0,'.$rsOrder['Address_Province']][$rsOrder['Address_City']].',';
		}

		$Area = '';
		if(!empty($rsOrder['Address_Area'])){
			$Area = $area_array['0,'.$rsOrder['Address_Province'].','.$rsOrder['Address_City']][$rsOrder['Address_Area']];
		}
		$this->assign('rsOrder', $rsOrder);
		$this->assign('Shipping', $Shipping);
		$this->assign('Order_Status', $Order_Status);
		$this->assign('Province', $Province);
		$this->assign('City', $City);
		$this->assign('Area', $Area);
		$this->assign('CartList', $CartList);
		$this->assign('fee', $fee);
		$this->assign('lists_back', $lists_back);
		$this->assign('_STATUS', $_STATUS);
		$this->display('detail.php', 'member', 'member_layout');
	}
	
	//我的收藏
	public function shoucangOp() {
		$this->assign('title', '我的收藏');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('member/shoucang');
		$favourite_model = model('user_favourite_products');
		if(IS_AJAX) {
			$sql1 = "select count(*) as count from Shop_Products as p join user_favourite_products as f on p.Products_id = f.Products_ID and f.User_ID =".$_SESSION[$this->UsersID . 'User_ID'];
			$count_tmp = model()->query($sql1, 'FIND');
			$count = $count_tmp['count'];
			$num = 12;//每页记录数
			$p = !empty($_POST['p']) ? intval(trim($_POST['p'])) : 1;
			$total = $count;//数据记录总数
			$totalpage = ceil($total / $num);//总计页数
			$limitpage = ($p-1) * $num;//每次查询取记录
			//获取此用户所收藏的商品
			$sql2 = "select f.FAVOURITE_ID,p.Products_ID,p.Products_Name,Products_PriceX,p.Products_PriceY,p.Products_JSON from Shop_Products as p join user_favourite_products as f on p.Products_id = f.Products_ID and f.User_ID =".$_SESSION[$this->UsersID . 'User_ID'] . " limit $limitpage, $num";
			$result = model()->query($sql2, 'SELECT');

			foreach($result as $key => $item){
				$JSON = json_decode($item['Products_JSON'], TRUE);				
				$result[$key]['ImgPath'] = $JSON['ImgPath'][0];
				$result[$key]['P_URL'] = url('goods/index', array('id'=>$item['Products_ID']));
			}
			if($result) {
				$data = array(
					'list' => $result,
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
			$this->display('shoucang.php', 'member', 'member_layout');
		}
	}
	//我的退款单
	public function backupOp() {
		$this->assign('title', '我的退款单');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('member/backup');
		$order_model = model('user_back_order');
		
		if(IS_AJAX) {
			$where['Users_ID'] = $this->UsersID;
			$where['User_ID'] = $_SESSION[$this->UsersID . 'User_ID'];
			$where['Back_Type'] = 'shop';
			//if(isset($_REQUEST['Status']) && $_REQUEST['Status'] != 'all'){
			//	$where['Back_Status'] = $_REQUEST['Status'];
			//}
			$count = $order_model->where($where)->order('Back_UpdateTime desc, Back_CreateTime desc')->total();
			$num = 12;//每页记录数
			$p = !empty($_POST['p']) ? intval(trim($_POST['p'])) : 1;
			$total = $count;//数据记录总数
			$totalpage = ceil($total / $num);//总计页数
			$limitpage = ($p-1) * $num;//每次查询取记录
			$rsBacks = $order_model->field('*')->where($where)->limit($limitpage, $num)->order('Back_UpdateTime desc, Back_CreateTime desc')->select();
			foreach($rsBacks as $key => $val){
				$rsBacks[$key]['CartList_back'] = json_decode(htmlspecialchars_decode($val['Back_Json']), TRUE);
				
				$rsBacks[$key]['P_URL'] = url('goods/index', array('id'=>$val['ProductID']));
				$rsBacks[$key]['status_arr'] = array('<font style="color:#F00">申请中</font>','<font style="color:#F60">卖家同意</font>','<font style="color:#0F3">买家发货</font>','<font style="color:#600">卖家收货并确定退款价格</font>','<font style="color:blue">完成</font>','<font style="color:#999; text-decoration:line-through;">卖家拒绝退款</font>');
				//详情链接
				$rsBacks[$key]['detail_url'] = url('member/backup_detail', array('id'=>$val['Back_ID']));
				//详情链接
				$rsBacks[$key]['backup_send_url'] = url('member/backup_send', array('BackID'=>$val['Back_ID']));
			}
			if($rsBacks) {
				$data = array(
					'list' => $rsBacks,
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
			$this->display('backup.php', 'member', 'member_layout');
		}
	}
	//申请退款
	public function backup_applyOp() {
	    $this->assign('title', '申请退款');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('member/backup_apply');
	    if(!empty($_GET['pama'])){
			$pama = $_GET['pama'];
		}else{
			$this->error('缺少必要的参数');
		}
		$arr = explode('_', $pama);
		$OrderID = $arr[0];
		$ProductsID = $arr[1];
		$KEY = $arr[2];
		$rsOrder = model('user_order')->field('*')->where(array('Users_ID'=>$this->UsersID, 'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Order_ID'=>$OrderID))->find();
		$CartList = json_decode(htmlspecialchars_decode($rsOrder['Order_CartList']), true);
		$item = $CartList[$ProductsID][$KEY];
		$this->assign('ProductsID', $ProductsID);
		$this->assign('item', $item);
		$this->assign('KEY', $KEY);
		$this->assign('rsOrder', $rsOrder);
		$this->display('backup_apply.php', 'member', 'member_layout');
	}
	public function backup_detailOp() {
		$this->assign('title', '退款详情');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('member/backup_detail');
		if(!empty($_GET['id'])){
			$BackID = $_GET['id'];
		}else{
			$this->error('缺少参数');
		}
		$rsBackup = model('user_back_order')->field('*')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Back_ID'=>$BackID))->find();

		$CartList_back = json_decode(htmlspecialchars_decode($rsBackup['Back_Json']), TRUE);

		$Status = $rsBackup['Back_Status'];
		$_STATUS = array('<font style="color:#F00">申请中</font>','<font style="color:#F60">卖家同意</font>','<font style="color:#0F3">买家发货</font>','<font style="color:#600">卖家收货并确定退款价格</font>','<font style="color:#blue">完成</font>','<font style="color:#999; text-decoration:line-through;">卖家拒绝退款</font>');

		$Shipping = json_decode($rsBackup['Back_Shipping'], true);
        $rsBiz = model('biz')->field('*')->where(array('Users_ID'=>$this->UsersID,'Biz_ID'=>$rsBackup['Biz_ID']))->find();

		$area_json = read_file(SITE_PATH . '/data/area.js');
		$area_array = json_decode($area_json, TRUE);
		$province_list = $area_array[0];
		$Province = '';
		if(!empty($rsBiz['Biz_RecieveProvince'])){
			$Province = $province_list[$rsBiz['Biz_RecieveProvince']].',';
		}
		$City = '';
		if(!empty($rsBiz['Biz_RecieveCity'])){
			$City = $area_array['0,'.$rsBiz['Biz_RecieveProvince']][$rsBiz['Biz_RecieveCity']].',';
		}

		$Area = '';
		if(!empty($rsBiz['Biz_RecieveArea'])){
			$Area = $area_array['0,'.$rsBiz['Biz_RecieveProvince'].','.$rsBiz['Biz_RecieveCity']][$rsBiz['Biz_RecieveArea']];
		}
		
		//退款产品列表
		$user_back_order_detail = model('user_back_order_detail')->field('*')->where(array('backid'=>$BackID))->order('createtime asc')->select();
		$this->assign('user_back_order_detail', $user_back_order_detail);
		$this->assign('rsBackup', $rsBackup);
		$this->assign('_STATUS', $_STATUS);
		$this->assign('Province', $Province);
		$this->assign('City', $City);
		$this->assign('Area', $Area);
		$this->assign('rsBiz',$rsBiz);
		$this->display('backup_detail.php', 'member', 'member_layout');
	}
	//我要发货
	public function backup_sendOp() {
	    $this->assign('title', '退款发货');
	    $_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('member/backup_apply');
		if(!empty($_GET['BackID'])){
			$BackID = $_GET['BackID'];
		}else{
			$this->error('缺少参数');
		}
		$rsBackup = model('user_back_order')->field('*')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Back_ID'=>$BackID))->find();

		$CartList_back = json_decode(htmlspecialchars_decode($rsBackup['Back_Json']), TRUE);

		$Status = $rsBackup['Back_Status'];
		$_STATUS = array('<font style="color:#F00">申请中</font>','<font style="color:#F60">卖家同意</font>','<font style="color:#0F3">买家发货</font>','<font style="color:#600">卖家收货并确定退款价格</font>','<font style="color:#blue">完成</font>','<font style="color:#999; text-decoration:line-through;">卖家拒绝退款</font>');

		$Shipping = json_decode($rsBackup['Back_Shipping'], true);
		
        $rsBiz = model('biz')->field('*')->where(array('Users_ID'=>$this->UsersID,'Biz_ID'=>$rsBackup['Biz_ID']))->find();
		$area_json = read_file(SITE_PATH . '/data/area.js');
		$area_array = json_decode($area_json, TRUE);
		$province_list = $area_array[0];
		$Province = '';
		if(!empty($rsBiz['Biz_RecieveProvince'])){
			$Province = $province_list[$rsBiz['Biz_RecieveProvince']].',';
		}
		$City = '';
		if(!empty($rsBiz['Biz_RecieveCity'])){
			$City = $area_array['0,'.$rsBiz['Biz_RecieveProvince']][$rsBiz['Biz_RecieveCity']].',';
		}

		$Area = '';
		if(!empty($rsBiz['Biz_RecieveArea'])){
			$Area = $area_array['0,'.$rsBiz['Biz_RecieveProvince'].','.$rsBiz['Biz_RecieveCity']][$rsBiz['Biz_RecieveArea']];
		}
		$this->assign('rsBackup', $rsBackup);
		$this->assign('_STATUS', $_STATUS);
		$this->assign('Province', $Province);
		$this->assign('City', $City);
		$this->assign('Area', $Area);
		$this->assign('rsBiz',$rsBiz);
		$this->display('backup_send.php', 'member', 'member_layout');
	}
	//退款表单页面
	public function backup_formOp() {
		if(isset($_GET['pama'])){
			$pama = $_GET['pama'];
		}else{
			$this->error('缺少必要的参数');
		}
		$arr = explode('_', $pama);
		$OrderID = array_shift($arr);
		$ProductsID = array_shift($arr);
		$KEY = array_shift($arr);
		
	}
	public function moneyOp() {
		$this->assign('title', '我的余额');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('member/money');
		$type = empty($_GET['type']) ? 'charge_record' : $_GET['type'];
		$this->assign('type', $type);
		if(IS_AJAX) {
			if($type == 'money_record') {
				$this->money_record();//资金流水
			}else if($type == 'charge_record'){
				$this->charge_record();//充值记录
			}
		}else {
			$this->assign('rsUser', $this->rsUser);
			$this->display('money.php', 'member', 'member_layout');
		}
	}
	
	private function money_record() {
		$user_money_record_model = model('user_money_record');
		$where['Users_ID'] = $this->UsersID;
		$where['User_ID'] = $_SESSION[$this->UsersID . 'User_ID'];
		$count = $user_money_record_model->where($where)->order('Item_ID desc')->total();
		$num = 12;//每页记录数
		$p = !empty($_POST['p']) ? intval(trim($_POST['p'])) : 1;
		$total = $count;//数据记录总数
		$totalpage = ceil($total / $num);//总计页数
		$limitpage = ($p-1) * $num;//每次查询取记录
		$list = $user_money_record_model->field('*')->where($where)->limit($limitpage, $num)->order('Item_ID desc')->select();
		if($list) {
			foreach($list as $key => $val){
				$list[$key]['time'] = date('Y/m/d', $val['CreateTime']);
				
			}
			$data = array(
				'list' => $list,
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
	private function charge_record() {
		$user_charge_model = model('user_charge');
		$where['Users_ID'] = $this->UsersID;
		$where['User_ID'] = $_SESSION[$this->UsersID . 'User_ID'];
		$where['Status'] = 1;
		$count = $user_charge_model->where($where)->order('Item_ID desc')->total();
		$num = 12;//每页记录数
		$p = !empty($_POST['p']) ? intval(trim($_POST['p'])) : 1;
		$total = $count;//数据记录总数
		$totalpage = ceil($total / $num);//总计页数
		$limitpage = ($p-1) * $num;//每次查询取记录
		$list = $user_charge_model->field('*')->where($where)->limit($limitpage, $num)->order('Item_ID desc')->select();
		if($list) {
			foreach($list as $key => $val){
				$list[$key]['time'] = date('Y/m/d', $val['CreateTime']);
				$list[$key]['Note'] = $val['Operator'];
			}
			$data = array(
				'list' => $list,
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
	public function chargepayOp() {
		if(isset($_GET['ItemID'])) {
			$ItemID = $_GET['ItemID'];
		}else {
			$this->error('缺少必要的参数');
		}
		if(isset($_GET['Method'])) {
			$Method = $_GET['Method'];
		}else {
			$this->error('缺少必要的参数');
		}
		$rsCharge = model('user_charge')->field('*')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Item_ID'=>$ItemID))->find();
		$rsPay = model('users_payconfig')->field('*')->where(array('Users_ID'=>$this->UsersID))->find();
		if(!$rsCharge) {
			$this->error('此充值记录不存在');
		}
		if($rsCharge && $rsCharge['Status'] == 1) {
			$this->error('此充值记录已完成');
		}
		$PaymentMethod = array(
			'2' => '支付宝'
		);
		if($Method == 2){//支付宝
			if($rsPay['Payment_AlipayEnabled'] == 0 || empty($rsPay['Payment_AlipayPartner']) || empty($rsPay['Payment_AlipayKey']) || empty($rsPay['Payment_AlipayAccount'])) {
				$this->error('商家“支付宝”支付方式未启用或信息不全，暂不能支付！');
			}
			$pay_fee = $rsCharge['Amount'];
			$pay_orderno = $ItemID;
			$pay_subject = $this->shopConfig['shopname'] . '(会员:' . $_SESSION[$this->UsersID . 'User_ID'] . ')在线充值，充值编号:' . $ItemID;
			$url = url('alipay_charge/index_pc', array('ItemID'=>$ItemID));
			header('location:' . $url);
		}
	}
	//我的优惠券
	public function couponOp() {
		$this->assign('title', '我的优惠券');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('member/coupon');
		$TypeID = empty($_GET['TypeID']) ? 0 : $_GET['TypeID'];
		$this->assign('TypeID', $TypeID);
		if(IS_AJAX) {
			if($TypeID == 0){
				$sql1 = "SELECT COUNT(*) as count FROM user_coupon_record AS a LEFT JOIN user_coupon AS b ON b.Users_ID = a.Users_ID AND b.Coupon_ID = a.Coupon_ID where a.User_ID='".$_SESSION[$this->UsersID . 'User_ID']."' and b.Users_ID='".$this->UsersID."' and b.Coupon_StartTime<".time()." and b.Coupon_EndTime>".time()." order by b.Coupon_CreateTime desc";
				$sql2 = "SELECT b.Coupon_Subject,b.Coupon_PhotoPath,b.Coupon_ID,b.Coupon_EndTime,b.Coupon_Description,a.* FROM user_coupon_record AS a LEFT JOIN user_coupon AS b ON b.Users_ID = a.Users_ID AND b.Coupon_ID = a.Coupon_ID where a.User_ID='".$_SESSION[$this->UsersID . 'User_ID']."' and b.Users_ID='".$this->UsersID."' and b.Coupon_StartTime<".time()." and b.Coupon_EndTime>".time()." order by b.Coupon_CreateTime desc";
			}else if($TypeID == 1){
				$sql1 = "SELECT COUNT(*) as count FROM user_coupon WHERE Users_ID='".$this->UsersID."' and Coupon_StartTime<".time()." and Coupon_EndTime>".time()." and user_coupon.Coupon_ID NOT IN ( SELECT Coupon_ID FROM user_coupon_record WHERE Users_ID='".$this->UsersID."' and User_ID = ".$_SESSION[$this->UsersID."User_ID"]." ) order by Coupon_CreateTime desc";
				$sql2 = "SELECT * FROM user_coupon WHERE Users_ID='".$this->UsersID."' and Coupon_StartTime<".time()." and Coupon_EndTime>".time()." and user_coupon.Coupon_ID NOT IN ( SELECT Coupon_ID FROM user_coupon_record WHERE Users_ID='".$this->UsersID."' and User_ID = ".$_SESSION[$this->UsersID."User_ID"]." ) order by Coupon_CreateTime desc";
			}else{
				$sql1 = "SELECT COUNT(*) as count FROM user_coupon_record AS a LEFT JOIN user_coupon AS b ON b.Users_ID = a.Users_ID AND b.Coupon_ID = a.Coupon_ID where a.User_ID='".$_SESSION[$this->UsersID."User_ID"]."' and b.Users_ID='".$this->UsersID."' and b.Coupon_EndTime<".time()." order by b.Coupon_CreateTime desc";
				$sql2 = "SELECT b.*,a.Coupon_UsedTimes FROM user_coupon_record AS a LEFT JOIN user_coupon AS b ON b.Users_ID = a.Users_ID AND b.Coupon_ID = a.Coupon_ID where a.User_ID='".$_SESSION[$this->UsersID."User_ID"]."' and b.Users_ID='".$this->UsersID."' and b.Coupon_EndTime<".time()." order by b.Coupon_CreateTime desc";
			}
			
			$count_tmp = model()->query($sql1, 'FIND');
			$count = $count_tmp['count'];
			$num = 12;//每页记录数
			$p = !empty($_POST['p']) ? intval(trim($_POST['p'])) : 1;
			$total = $count;//数据记录总数
			$totalpage = ceil($total / $num);//总计页数
			$limitpage = ($p-1) * $num;//每次查询取记录
			//获取此用户所收藏的商品
			$sql2 .= " limit $limitpage, $num";
			$result = model()->query($sql2, 'SELECT');

			foreach($result as $key => $item){
				$JSON = json_decode($item['Products_JSON'], TRUE);				
				$result[$key]['ImgPath'] = $JSON['ImgPath'][0];
				$result[$key]['P_URL'] = url('goods/index', array('id'=>$item['Products_ID']));
			}
			if($result) {
				$data = array(
					'list' => $result,
					'totalpage' => $totalpage,
					'count' => $total,
					'type' => $TypeID,
				);
			}else {
				$data = array(//没有数据可加载
					'list' => '',
					'totalpage' => $totalpage,
					'count' => $total,
					'type' => $TypeID,
				);
			}
			$this->ajaxReturn($data);
		}else {
			$this->display('coupon.php', 'member', 'member_layout');
		}
	}
	//个人资料
	public function personal_informationOp() {
		$this->assign('title', '我的个人信息');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('member/personal_information');
		$user_model = model('user');
		if($_POST) {
			if(empty($_POST['User_Name'])){
				$this->error('昵称必须');
			}
			if(empty($_POST['RadioGroup1'])){
				$this->error('性别必须');
			}
			if(empty($_POST['User_Email'])){
				$this->error('邮箱必须');
			}
			if(empty($_POST['User_NickName'])){
				$this->error('真实姓名必须');
			}
			if(empty($_POST['Mobile'])){
				$this->error('手机号必须');
			}
			
			$Data = array(
				'User_Name'=>$_POST['User_Name'],
				'User_Gender'=>!empty($_POST['RadioGroup1']) ? $_POST['RadioGroup1'] : 1,
				'User_Birthday'=>$_POST['birthday_year'].'-'.$_POST['birthday_month'].'-'.$_POST['birthday_day'],
				'User_Email'=>$_POST['User_Email'],
				'User_NickName'=>$_POST['User_NickName'],
				'User_Province'=>$_POST['s_province'],
				'User_City'=>$_POST['s_city'],
				'User_Area'=>$_POST['s_county'],
				'User_Mobile'=>$_POST['Mobile'],
			);
			if(!empty($_POST['Password'])){
				$Data['User_Password'] = md5($_POST['Password']);
			}
			$flag = $user_model->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->update($Data);
			if($flag) {
				$this->success('更新成功');
			}else {
				$this->error('更新失败');
			}
		}else {
			$this->assign('birthday_year', getSystemYearArr());
			$this->assign('birthday_month', getSystemMonthArr());
			$this->assign('Birthday_arr', explode('-', $this->rsUser['User_Birthday'], 3));
			$this->display('personal_information.php', 'member', 'member_layout');
		}
	}
	//系统消息通知
	public function sys_msgOp(){
	    $this->assign('title', '消息提醒');
		$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('member/sys_msg');
		if(IS_AJAX) {
		    $pc_user_message = model('pc_user_message');
			$where['Users_ID'] = $this->UsersID;
			$where['User_ID'] = $_SESSION[$this->UsersID . 'User_ID'];
			$count = $pc_user_message->where($where)->order('id desc')->total();
			$num = 12;//每页记录数
			$p = !empty($_POST['p']) ? intval(trim($_POST['p'])) : 1;
			$total = $count;//数据记录总数
			$totalpage = ceil($total / $num);//总计页数
			$limitpage = ($p-1) * $num;//每次查询取记录
			$list = $pc_user_message->field('*')->where($where)->limit($limitpage, $num)->order('id desc')->select();
			if($list) {
				foreach($list as $key => $val){
					$list[$key]['time'] = date('Y-m-d H:i:m', $val['CreateTime']);
					
				}
				$data = array(
					'list' => $list,
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
		$this->display('sys_msg.php', 'member', 'member_layout');
	}
}
?>