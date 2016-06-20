<?php
namespace shop\controller;
class distribute_ajaxController extends controllController {
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
	//手动申请分销商
	private function _join() {
		if(empty($_POST['User_Name'])) {
			$Data = array(
				'status' => 0,
				'msg' => '昵称不能为空',
			);
			return $Data;
		}
		if(empty($_POST['Mobile'])) {
			$Data = array(
				'status' => 0,
				'msg' => '手机不能为空',
			);
			return $Data;
		}
		$UserID = $_SESSION[$this->UsersID . 'User_ID'];
		$Real_Name = $_POST['User_Name'];
		$User_Mobile = $_POST['Mobile'];
		$user = model('user')->field('*')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$UserID))->find();
		$owner['id'] = $user['Owner_Id'];						
		$Flag = create_distribute_acccount($this->shopConfig, $UserID, $Real_Name, $owner['id'], $User_Mobile);
        $Flag = $Flag && model('user')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->update(array('Is_Distribute'=>1));
		
		/*if($Flag) {
			$Data = array(
				'status'=>1,
			    'url'=>url('member/index')
			);
			return $Data;
		}else {
			$Data = array(
				'status'=>0,
				'msg'=>'您已经是分销商'
			);
		    return $Data;
		}*/
		$Data = array(
		    'status'=>1,
			'url'=>url('member/index')
		);
		return $Data;
	}
	private function _withdraw_appy() {
		if(empty($_POST['money'])){
			$Data = array('status'=>0,'msg'=>'提现金额不能为空');
			return $Data;
		}
		if(empty($_POST['User_Method_ID'])){
			$Data = array('status'=>0,'msg'=>'账号不能为空');
			return $Data;
		}
		//查看是否有足够的余额用于提现
		$condition = array(
		    'Users_ID'=>$this->UsersID,
			'User_ID'=>$_SESSION[$this->UsersID . 'User_ID']
		);
		$dsAccount = model('distribute_account')->field('Account_ID,balance')->where($condition)->find();
		
		if($_POST['money'] > $dsAccount['balance']) {
			$Data = array('status'=>0, 'msg'=>'余额不足');
		}else {
			$money  = $_POST['money'];
		
			//获取用户提现方式
			$condition = array(
			    'Users_ID'=>$this->UsersID,
				'User_Method_ID'=>$_POST['User_Method_ID']
			);
			$UserMethod = model('shop_user_withdraw_methods')->field('*')->where($condition)->find();
			
			if($UserMethod['Method_Type'] == 'wx_hongbao' && ($money < 1 || $money > 200)){
				$Data = array('status'=>0, 'msg'=>'提现金额在1-200之间才可试用微信红包提现');
			}else{
				$Account_Info = $UserMethod['Method_Name'].' '.$UserMethod['Account_Name'].' '.$UserMethod['Account_Val'].' '.$UserMethod['Bank_Position'];
			
				$data = array(
					'Users_ID'=>$this->UsersID,
					'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],
					'Account_Info'=>$Account_Info,
					'Record_Sn'=>build_withdraw_sn(),
					'Record_Money'=>$money,
					'Record_CreateTime'=>time(),
					'Record_Type'=>1,
					'Record_Status'=>0,
				);
			    $condition = array(
				    'Users_ID'=>$this->UsersID,
					'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],
					'Account_ID'=>$dsAccount['Account_ID']
				);
				model('distribute_account')->where($condition)->update('balance=balance-' . $money);
				$Flag = model('distribute_account_record')->insert($data);
				if($Flag) {
					$Data = array('status'=>1);
				}else {
					$Data = array('status'=>0,'msg'=>'发生位置错误，申请提交失败');
				}
			}
		}
        return $Data;
	}
	
    private function _save_withdraw_method() {
		$shop_user_withdraw_methods = model('shop_user_withdraw_methods');
		if(empty($_POST['Method_Name'])){
			$Data = array('status'=>0, 'msg'=>'提现方式不能为空');
			return $Data;
		}else {
			if($_POST['Method_Type'] == 'bank_card'){
				if(empty($_POST['Account_Name'])){
					$Data = array('status'=>0, 'msg'=>'户名不能为空'.$_POST['Account_Name']);
					return $Data;
				}
				if(empty($_POST['Account_Val'])){
					$Data = array('status'=>0, 'msg'=>'账号不能为空');
					return $Data;
				}
				if(empty($_POST['Bank_Position'])){
					$Data = array('status'=>0, 'msg'=>'开户行不能为空');
					return $Data;
				}
			    $data = array(
				    'Users_ID'=>$this->UsersID,
					'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],
					'Method_Name'=>$_POST['Method_Name'],
					'Method_Type'=>$_POST['Method_Type'],
					'Account_Name'=>$_POST['Account_Name'],
					'Account_Val'=>$_POST['Account_Val'],
					'Bank_Position'=>$_POST['Bank_Position'],
					'Method_CreateTime'=>time(),
				);
			}else if($_POST['Method_Type'] == 'alipay') {
				if(empty($_POST['Account_Name2'])){
					$Data = array('status'=>0, 'msg'=>'户名不能为空');
					return $Data;
				}
				if(empty($_POST['Account_Val2'])){
					$Data = array('status'=>0, 'msg'=>'账号不能为空');
					return $Data;
				}
				$data = array(
				    'Users_ID'=>$this->UsersID,
					'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],
					'Method_Name'=>$_POST['Method_Name'],
					'Method_Type'=>$_POST['Method_Type'],
					'Account_Name'=>$_POST['Account_Name2'],
					'Account_Val'=>$_POST['Account_Val2'],
					'Method_CreateTime'=>time(),
				);
			}else{
				$data = array(
				    'Users_ID'=>$this->UsersID,
					'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],
					'Method_Name'=>$_POST['Method_Name'],
					'Method_Type'=>$_POST['Method_Type'],
					'Method_CreateTime'=>time(),
				);
			}
			$flag = $shop_user_withdraw_methods->insert($data);
			if($flag){
				$Data = array('status'=>1);
				return $Data;
			}else{
				$Data = array('status'=>0, 'msg'=>'保存失败');
				return $Data;
			}
		}
    }
	private function _del_withdraw_method() {
		if(empty($_POST['id'])){
			$Data = array('status'=>0, 'msg'=>'非法操作');
			return $Data;
		}
		$shop_user_withdraw_methods = model('shop_user_withdraw_methods');
		$flag = $shop_user_withdraw_methods->where(array('User_Method_ID'=>$_POST['id']))->delete();
		if($flag){
			$Data = array('status'=>1);
			return $Data;
		}else{
			$Data = array('status'=>0, 'msg'=>'删除失败');
			return $Data;
		}
	}
}
?>