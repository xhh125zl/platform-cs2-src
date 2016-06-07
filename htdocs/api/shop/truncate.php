<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
use Illuminate\Database\Capsule\Manager as Capsule;


//清空分销有关数据

$Users_ID = 'yd0tcni067';


Capsule::transaction(function() use ($Users_ID) 
{    
	
	$where = array('Users_ID'=>$Users_ID);
	
	//删除用户
	Capsule::table('user')->where($where)->delete();
	//删除订单
	Capsule::table('user_order')->where($where)->delete();
	//删除分销账号
	Capsule::table('shop_distribute_account')->where($where)->delete();
	//删除分销记录
	Capsule::table('shop_distribute_record')->where($where)->delete();
	//删除分销账号记录
	Capsule::table('shop_distribute_account_record')->where($where)->delete();
	//清除地区代理信息
	Capsule::table('shop_dis_agent_areas')->where($where)->delete();
	//清除地区代理奖励记录
	Capsule::table('shop_dis_agent_rec')->where($where)->delete();
	//清除本店代理配置
	Capsule::table('shop_distribute_config')->where($where)->delete();
	//清除提现方式
	Capsule::table('shop_withdraw_method')->where($where)->delete();
});
