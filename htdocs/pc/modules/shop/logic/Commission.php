<?php
namespace shop\logic;
//佣金发放类
class Commission {
	//获取分销商爵位级别
	function get_nobility_level($userid) {
		$md = model('shop_distribute_account')->field('Professional_Title')->where(array('User_ID' => $userid))->find();
		$nobility = $md['Professional_Title'];
		return $nobility;//(爵位)
	}
		
	//获取分销商爵位级别设置信息
	function get_nobility_config($usersid) {
		$md = model('shop_distribute_config')->field('Pro_Title_Level')->where(array('Users_ID'=>$usersid))->find();
		if(!empty($md['Pro_Title_Level'])){
			$Pro_Title_Level = json_decode($md['Pro_Title_Level'], true);
			krsort($Pro_Title_Level);
		}
		if(empty($Pro_Title_Level)){
			return false;
		}
		return $Pro_Title_Level;
	}
		
	//获取上级
	function get_parents($ower_id) {
		$cc = model('shop_distribute_account')->field('Dis_Path')->where(array('User_ID' => $ower_id))->find();
		return $cc['Dis_Path'];
	}
		
	//取分销级别信息
	function get_dis_level($Users_ID) {
		$dis_level = model('shop_config')->field('*')->where(array('Users_ID'=>$Users_ID))->find();
		return $dis_level;
	}
		
	//分销级别佣金/爵位奖发放
	/*
	*	$orderid 可为订单id 或订单内容
	*/
	function handout_dis_commission($orderid, $products, $qty) {
		if(is_array($orderid)) {
			$cartlist = $orderid;//订单购物车
		}else {
			$order = model('user_order')->field('*')->where(array('Order_ID'=>$orderid))->find();
			if(empty($order)) {
				return 0;
			}
			$cartlist = json_decode(htmlspecialchars_decode($order['Order_CartList']), true);//订单购物车
		}
			
		//产品利润计算
		$ammount = array();
		$dis_config = $this->get_dis_level($order['Users_ID']);
		$dis_level = $dis_config['Dis_Level'];//分销级别
		$dis_self_bonus = $dis_config['Dis_Self_Bonus'];//自销模式
		if($dis_self_bonus) {//开启自销
			$dis_level = $dis_level + 1;
		}
			
		$commission_ratio = $products['commission_ratio'];//佣金/爵位奖比例
		if($commission_ratio > 100) {
			$commission_ratio = 100;
		}else if($commission_ratio < 0) {
			$commission_ratio = 0;
		}
		$profit = $qty * $products['Products_Profit'];
		$nobility = $profit * (100 - $commission_ratio) / 100;//爵位奖励
			
		$dis_bonus_limit = json_decode($dis_config['Dis_Bonus_Limit'], true);//佣金发放条件

		$parents = $this->get_parents($order['Owner_ID']);//上级分销商
		$parents_arr = explode(',', $parents);
		$psa = array_filter($parents_arr);
		$psars = array_reverse($psa);
		$result = array();	
		
			
		if(!$dis_self_bonus && $order['Owner_ID'] == $order['User_ID']){
			//自销关闭 自销本人不得佣金
		}else{
			array_unshift($psars, strval($order['Owner_ID']));
		}	
		/*-------------------------爵位奖处理--------------------------*/
		if($psars){
		    $nobility_level_bak = model('shop_distribute_account')->field('User_ID,Professional_Title')->where(array('Users_ID'=>$order['Users_ID'],'User_ID'=>$psars))->select();
		}else{
		   $nobility_level_bak = array(); 
		}
		$nobility_level = array();//所有上级分销商信息
		if($nobility_level_bak){
			foreach($nobility_level_bak as $k => $v){
				$nobility_level[$v['User_ID']] = $v['Professional_Title'];
			}
		}
			
		$Pro_Title_Level = $this->get_nobility_config($order['Users_ID']);//获取分销商爵位级别配置信息

		$nobility_commission = array();	
		$nobility_level_new = array();//
		$t = 0;
		foreach($psars as $k => $v){
			if($t > $dis_level){
				break;
			}
			if(isset($nobility_level[$v])) {
				$nobility_level_new[$v] = $nobility_level[$v];
			}
			$t++;
		}
						
		$nobi_total = 0;
		$nobi_level = '';
		$Pro_Title_Level[0]['Bonus'] = 0;
		foreach($nobility_level_new as $k => $v){	
			$nobility_commission[$k]['Nobi_Level'] = !empty($Pro_Title_Level[$v]['Name']) ? $Pro_Title_Level[$v]['Name'] : '无爵位';
			if($v == '0'){
				$nobility_commission[$k]['Nobi_Money'] = 0;
				$nobility_commission[$k]['Nobi_Description'] = '您还没有爵位';
				continue;
			}		
			$nobility_commission[$k]['Nobi_Money'] = (($nobility - $nobi_total) > 0 && (($nobility * $Pro_Title_Level[$v]['Bonus'] / 100 - $nobility * $Pro_Title_Level[($v-1)]['Bonus'] / 100)<=($nobility - $nobi_total))) ? ($nobility * $Pro_Title_Level[$v]['Bonus'] / 100 - $nobility * $Pro_Title_Level[($v-1)]['Bonus'] / 100) : 0;
			
			$nobility_commission[$k]['Nobi_Money'] = ($nobility_commission[$k]['Nobi_Money'] > 0) ? $nobility_commission[$k]['Nobi_Money'] : 0;
			$nobility_commission[$k]['Nobi_Description'] = ($nobility > 0) ? ($nobility_commission[$k]['Nobi_Money'] > 0) ? '正常' : ($nobility_commission[$k]['Nobi_Money'] == 0) ? '爵位奖金已发完' : ($nobility_commission[$k]['Nobi_Money'] < 0) ? '商家设置有误' : '' : '该商品无爵位奖';		

			if($nobi_level === intval($v)) {
				$nobility_commission[$k]['Nobi_Description'] = '你的下级与您平级';
				$nobility_commission[$k]['Nobi_Money'] = 0;
			}
						
			$nobi_total += $nobility_commission[$k]['Nobi_Money'];
			if($nobi_level == count($Pro_Title_Level)) {
				$nobi_level = count($Pro_Title_Level);
			}else {
				$nobi_level = intval($v);
			}
		}			
		return $nobility_commission;
	}
}