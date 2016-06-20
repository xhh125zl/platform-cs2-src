<?php
namespace shop\controller;
class member_ajaxController extends controllController {
    public function __construct() {
        parent::_initialize();
    }
    public function indexOp() {
        $action = empty($_REQUEST['action']) ? '' : $_REQUEST['action'];
        if (IS_AJAX) {
            $func_name = '_' . $action;
			$data = $this->$func_name();
            $this->ajaxReturn($data);
        }
    }
	//拉取地址信息
	private function _get_address() {
		$Address_ID = $_POST['addr_id'];
		$address_model = model('user_address');
		$info = $address_model->field('*')->where(array('Address_ID'=>$Address_ID))->find();
		$Data = array(
			'status' => 1,
		    'info' => $info
		);
		return $Data;
	}
	//删除地址
	private function _del_address() {
		$Address_ID = $_POST['addr_id'];
		$flag = model('user_address')->delete($Address_ID);
		if($flag) {
			$Data = array(
			    'status' => 1,
			);
		}else {
			$Data = array(
			    'status' => 0,
				'msg' => '删除失败'
			);
		}
		return $Data;
	}
	//增加地址
	private function _save_address() {
		if(empty($_POST['name'])){
			$Data = array(
			    'status' => 0,
				'msg' => '收件人不能为空'
			);
		}else if(empty($_POST['province'])){
			$Data = array(
			    'status' => 0,
				'msg' => '请选择省份'
			);
		}else if(empty($_POST['city'])){
			$Data = array(
			    'status' => 0,
				'msg' => '请选择二级地区'
			);
		}else if(empty($_POST['area'])){
			$Data = array(
			    'status' => 0,
				'msg' => '请选择三级地区'
			);
		}else if(empty($_POST['detailed'])){
			$Data = array(
			    'status' => 0,
				'msg' => '请填写详细地址'
			);
		}else if(empty($_POST['mobile'])){
			$Data = array(
			    'status' => 0,
				'msg' => '请填写联系方式'
			);
		}else{
			$save_data = array(
			    'Address_Name' =>$_POST['name'],
				'Address_Province'=>$_POST['province'],
				'Address_City'=>$_POST['city'],
				'Address_Area'=>$_POST['area'],
				'Address_Detailed'=>$_POST['detailed'],
				'Address_Mobile'=>$_POST['mobile'],
				'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],
				'Users_ID'=>$this->UsersID
			);
			if(empty($_POST['addr_id'])) {
				$flag = model('user_address')->insert($save_data);
		    }else {
				$flag = model('user_address')->where(array('Address_ID'=>$_POST['addr_id']))->update($save_data);
			}
			$url = '';
			if(!empty($_SESSION[$this->UsersID . 'Address_Return_Url'])){
			    $url = $_SESSION[$this->UsersID . 'Address_Return_Url'];
			}
			if($flag) {
				$Data = array(
				    'status' => 1,
					'url'=>$url
				);
			}else {
				$Data = array(
					'status' => 0,
					'msg' => '保存失败'
				);
			}
		}
		
		return $Data;
	}
	//设置默认地址
	private function _set_address() {
		$address_model = model('user_address');
		$address_model->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Address_ID <>'=>$_POST['addr_id']))->update('Address_Is_Default=0');
		$flag = $address_model->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Address_ID'=>$_POST['addr_id']))->update('Address_Is_Default=1');
		$url = '';
		if(!empty($_SESSION[$this->UsersID . 'Address_Return_Url'])){
			$url = $_SESSION[$this->UsersID . 'Address_Return_Url'];
		}
		if($flag) {
			$Data = array(
				'status' => 1,
				//'url' => empty($_SESSION[$this->UsersID . 'HTTP_REFERER']) || strpos($_SESSION[$this->UsersID . 'HTTP_REFERER'], 'buy/order_') === false ? '' : $_SESSION[$this->UsersID . 'HTTP_REFERER']
				'url' => $url
			);
		}else {
			$Data = array(
				'status' => 0,
				'msg' => '设置失败'
			);
		}
		return $Data;
	}
	//确认收货
	private function _confirm_receive() {
		$Order_ID = $_POST['Order_ID'];
		$order_model = model('user_order');
		$rsOrder = $order_model->where(array('Order_ID'=>$Order_ID))->find();
		if(!$rsOrder) {
			$response = array(
				'status' => 0,
				'msg' => '该订单不存在'
			);
		}else {
			if($rsOrder['order_status'] <> 3) {
				$response = array(
					'status'=>0,
					'msg'=>'只有在‘已发货’状态下才可确认收货'
				);
			}else {
				$Flag = $order_model->where(array('Order_ID'=>$Order_ID))->update(array('order_status' => 4));
				if($Flag) {
					$user_config = model('user_config')->where(array('Users_ID'=>$this->UsersID))->find();
					$OrderObserver = new \shop\logic\OrderObserver();
					$OrderObserver->shop_config = $this->shopConfig;
					$OrderObserver->user_config = $user_config;
					$OrderObserver->confirmed($rsOrder);
					$response = array(
						'status'=>1,
						'url' => url('member/status', array('status' => 4))
					);
				}else {
					$response = array(
						'status'=>0,
						'msg'=>'确认收货失败'
					);
				}
			}
		}
		return $response;
	}
	private function _backup_apply() {
	    $OrderID = $_POST['OrderID'];
		$rsOrder = model('user_order')->field('*')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Order_ID'=>$OrderID))->find();
		if(!$rsOrder){
			$Data = array(
				'status'=>0,
				'msg'=>'订单不存在'
			);
		}else if($rsOrder['Order_Status'] <> 3 && $rsOrder['Order_Status'] <> 2){
			$Data = array(
				'status'=>0,
				'msg'=>'只有“已付款或已发货”状态下的订单才可申请退款'
			);
		}else {
			$CartList = json_decode(htmlspecialchars_decode($rsOrder['Order_CartList']), true);
			if(empty($CartList[$_POST['ProductsID']][$_POST['KEY']])) {
				$Data = array(
					'status'=>0,
					'msg'=>'退款的商品不存在'
				);
			}else {
				$item = $CartList[$_POST['ProductsID']][$_POST['KEY']];
				if($item['Qty'] < $_POST['Qty']) {
					$Data = array(
						'status'=>0,
						'msg'=>'退款的退款数量大于商品总数量'
					);
				}else {
				    $backup = new \shop\logic\backup($this->UsersID);
					$backup->add_backup($rsOrder, $_POST["ProductsID"], $_POST["KEY"], $_POST["Qty"], $_POST["Reason"], $_POST["Account"]);
					
					$Data = array(
						'status' => 1,
						'url'=> url('member/backup', array('status'=>0))
					);
				}
			}
		}
		return $Data;
	}
	private function _backup_send(){
	    $BackID = $_POST["BackID"];
		$rsBack = model('user_back_order')->field('*')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Back_ID'=>$BackID))->find();
		if(!$rsBack){
			$Data = array(
				"status"=>0,
				"msg"=>'退款单不存在'
			);
		}elseif($rsBack["Back_Status"]<>1){
			$Data = array(
				"status"=>0,
				"msg"=>'只有“卖家同意状态下的退货单才可发货操作”'
			);
		}else{
		     $backup = new \shop\logic\backup($this->UsersID);
			$backup->update_backup("buyer_send", $BackID, $_POST["shipping"].'||%$%'.$_POST["shippingID"]);
			$Data = array(
				"status"=>1,
				"url"=>url('member/backup',array('status'=>0))
			);
		}
		return $Data;
	}
	private function _commit(){
		$OrderID = $_POST['Order_ID'];
		$order_model = model('user_order');
		$rsOrder = $order_model->field('*')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Order_Status'=>4,'Order_ID'=>$OrderID))->find();
		if(!$rsOrder){
			$Data = array(
				'status'=>2,
				'msg'=>"无此订单"
			);
		}else{
			if($rsOrder['Is_Commit'] == 1){
				$Data = array(
					'status'=>4,
					'msg'=>'此订单已评论过，不可重复评论'
				);
			}else{
				$Data1=array(
					'Is_Commit'=>1
				);
				$order_model->where(array('Order_ID'=>$OrderID))->update($Data1);
				$CartList = json_decode(htmlspecialchars_decode($rsOrder['Order_CartList']), true);
				foreach($CartList as $key => $v){
					$Data = array(
						'MID' => $rsOrder['Order_Type'],
						'Order_ID' => $OrderID,
						'Product_ID' => $key,
						'Score' => $_POST['Score'],
						'Note' => $_POST['Note'],
						'Status'=>$this->shopConfig['commit_check']==1 ? 1 : 0,
						'Users_ID'=>$rsOrder['Users_ID'],
						'Biz_ID'=>$rsOrder['Biz_ID'],
						'User_ID'=>$_SESSION[$rsOrder['Users_ID'] . 'User_ID'],
						'CreateTime' => time()
					);
					model('user_order_commit')->insert($Data);
				}
				
				$Data = array(
					'status'=>1,
					'msg'=>'评论成功!'
				);
			}
		}
		return $Data;
	}
	private function _del_shoucang() {
		$flag = model('user_favourite_products')->where(array('FAVOURITE_ID'=>$_POST['del_id']))->delete();
		if($flag){
			$Data = array(
				'status'=>1,
				'msg'=>'删除成功'
			);
		}else {
			$Data = array(
				'status'=>0,
				'msg'=>'删除失败'
			);
		}
		return $Data;
	}
	//充值
	private function _charge() {
		$PaymentMethod = array(
			'2'=>'支付宝'
		);
		$rsUser = model('user')->field('User_Money')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find();
		if($rsUser && is_numeric($_POST['Amount']) && $_POST['Amount'] > 0 && !empty($_POST['Operator'])){
			$Data = array(
				'Users_ID' => $this->UsersID,
				'User_ID' => $_SESSION[$this->UsersID . 'User_ID'],
				'Amount' => $_POST['Amount'],
				'Total' => $rsUser['User_Money'] + $_POST['Amount'],
				'Operator' => $PaymentMethod[$_POST['Operator']] . '充值 +' . $_POST['Amount'],
				'CreateTime' => time()			
			);
			$itemid = model('user_charge')->insert($Data);
			if($itemid) {
				$Data = array(
					'status' => 1,
					'url' => url('member/chargepay', array('ItemID'=>$itemid,'Method'=>$_POST['Operator'])),
					'msg' => '操作成功！'
				);
			}else {
				$Data = array(
					'status' => 0,
					'msg' => '网络拥堵，请稍后再试！'
				);
			}
		}else {
			$Data = array(
				'status' => 0,
				'msg' => '错误操作'
			);
		}
		return $Data;
	}
	//删除提醒信息
	private function _del_sys_msg(){
	    $flag = model('pc_user_message')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'id'=>$_POST['id']))->delete();
		if($flag) {
			$Data = array(
				'status'=>1,
				'msg'=>'删除成功'
			);
		}else {
			$Data = array(
				'status'=>0,
				'msg'=>'删除失败'
			);
		}
		return $Data;
	}
}
?>