<?php
//业务员类
use Illuminate\Database\Capsule\Manager as Capsule;
class Salesman {
		
		private $Users_ID;
		private $User_ID;
		
		public function __construct($Users_ID){
			$this->Users_ID = $Users_ID;
			$this->User_ID =  $_SESSION[$this->Users_ID.'User_ID'];
		}
		
		//成为业务员
		/*
		* @return $consume	消费额
		*/
		function up_salesman($type=''){
			$salesman_limit = $this->get_sales_config();//成为业务员消费额限制
                        $Dis_Account = Dis_Account::where('User_ID',$this->User_ID)->first()->toarray();
                        $Salesman_Deltime =!empty($Dis_Account['Salesman_Deltime'])?$Dis_Account['Salesman_Deltime']:'0';
                        $total = Order::where('User_ID',$this->User_ID)->where('Order_Status','>=',2)->where('Order_CreateTime','>',$Salesman_Deltime)->sum('Order_TotalPrice');
			$flag = '';
			if($total >= $salesman_limit){
				$data['Is_Salesman'] = 1;
				
				$user = User::find($this->User_ID)->disAccount()->getResults();
				
				//$User = User::Multiwhere(['Users_ID'=>$this->Users_ID,'User_ID'=>$this->User_ID])->first()->Is_Distribute;
				//$dis_user = Dis_Account::Multiwhere(['Users_ID'=>$this->Users_ID,'User_ID'=>$this->User_ID])->first();
				
				if($user){
					$flag = Dis_Account::Multiwhere(['Users_ID'=>$this->Users_ID,'User_ID'=>$this->User_ID])->update($data);
				}
			}
			if($type == 1){
				return $salesman_limit;
			}else{
				return $flag;
			}
		}
		
		//获取业务设置信息
		function get_sales_config(){
			$md = Dis_Config::where('Users_ID',$this->Users_ID)->first(['Salesman']);
			if(!$md)
			{
				$sales_limit = 0;
			}else{
				$sales_limit = $md->Salesman;
			}
			return $sales_limit;
		}
		//取业务员身份
		function get_salesman(){
			$is_salesman = Dis_Account::Multiwhere(array('Users_ID'=>$this->Users_ID,'User_ID'=>$this->User_ID))->first()->Is_Salesman;
			return $is_salesman;
		}
		
		//邀请码算法
		function make_coupon_card() {    
			$code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';    
			$rand = $code[rand(0,25)]    
				.strtoupper(dechex(date('m')))    
				.date('d').substr(time(),-5)    
				.substr(microtime(),2,5)    
				.sprintf('%02d',rand(0,99));    
			for(    
				$a = md5( $rand, true ),    
				$s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',    
				$d = '',    
				$f = 0;    
				$f < 8;    
				$g = ord( $a[ $f ] ),    
				$d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],    
				$f++    
			);    
			return $d;    
		}    
		
		//生成邀请码过程
		/*
		* @param string	邀请码
		* @return string	邀请码
		*/
		function invitation_code($code=''){
			if(empty($code)){
				do{
					$Invitation_Code = $this->make_coupon_card();
					$selec_code = Dis_Account::Multiwhere(array('Users_ID'=>$this->Users_ID,'User_ID'=>$this->User_ID))->first()->Invitation_Code;
				}while(!empty($selec_code));
				
				while(Dis_Account::Multiwhere(array('Users_ID'=>$this->Users_ID,'User_ID'=>$this->User_ID))->update(['Invitation_Code'=>$Invitation_Code])){
					return $Invitation_Code;
				}
			}else{
				$Invitation_Code = $code;
			}
			return $Invitation_Code;
		}
		
		//二维码
		function get_qrcode($qrcode){
			$file_path = generate_qrcode($qrcode);
			$flag = Dis_Account::Multiwhere(array('Users_ID'=>$this->Users_ID,'User_ID'=>$this->User_ID))->update(['Qrcode'=>$file_path]);
			if($flag){
				return $file_path;
			}
		}
		
		//提成统计
		function sales_statistics($userid,$status){
			$result = Shop_Distribute_Sales_Record::Multiwhere(['User_ID'=>$userid,'Status'=>$status])->sum('Sales_Money');
			return $result;
		}
		
		//获取上级
		function get_parents(){
			$cc = Dis_Account::Multiwhere(array('Users_ID'=>$this->Users_ID,'User_ID'=>$this->User_ID))
			            ->first()
						->Dis_Path;
			return $cc;
		}
		
		//获取下级
		function get_sons(){
			$sons = Dis_Account::where('Dis_Path','like','%'.$this->User_ID.',%');
			$num = $sons->count();
			$content = $sons->lists('Dis_Path');
			return array($num,$content);
		}
		
		//取分销级别信息
		function get_dis_level(){
			//$dis_level = Shop_Config::where('Users_ID',$this->Users_ID)->first();
			//return $dis_level;
			return 3;
		}
}