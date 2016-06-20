<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 用户Model
 */
class User extends Illuminate\Database\Eloquent\Model {

	protected $primaryKey = "User_ID";
	protected $table = "user";
	public $timestamps = false;
	protected $fillable = array('Is_Distribute');

	//一个用户对应一个分销账号
	public function disAccount() {
		return $this->hasOne('Dis_Account', 'User_ID', 'User_ID');
	}

	//一个用户拥有多个订单
	public function Order() {
		return $this->hasMany('Order', 'Order_ID', 'User_ID');
	}
	
	//一个用户作为购买者拥有多个分销账户
	public function BuyerDisRecord(){
		return $this->hasMany('Order', 'Buyder_ID', 'User_ID');
	}
	
	//一个用户作为店主拥有多个分销账户
	public function OwnerDisRecord(){
		return $this->hasMany('Order', 'Owner_ID', 'User_ID');
	}
	
	//一个用户拥有多个分销账号记录
	public function disAccountRecord(){
	
	}
	
	
	/*
	 *确定用户是否满足升级条件
	 *@param Int  $sales 销售额
	 */
	public function determineUserLevel($User_Level_Config,$sales) {

		$User_Cost = $this->User_Cost;
		
		$level_dropdown = array();
		$level_range_list = array();
		$level_count = count($User_Level_Config);
		$level_begin_cost = $User_Level_Config[1]['UpCost'];
		$level_end_cost = $User_Level_Config[$level_count - 1]['UpCost'];

		//如果消费额小于等级起始消费额，1级

		if ($User_Cost < $level_begin_cost) {
			return 0;
		}

		//如果消费额大于等级结束消费额,最高级
		if ($User_Cost >= $level_end_cost) {
			return $level_count - 1;
		}

		//除此之外，循环确定
		foreach ($User_Level_Config as $key => $item) {

			if ($key != $level_count - 1) {
				$end_cost = $User_Level_Config[$key + 1]['UpCost'];
			} else {
				$end_cost = 999999999; //用一个很大的数表示等级的终点
			}

			$level_range_list[$key] = array('begin_cost' => $item['UpCost'], 'end_cost' => $end_cost);
		}

		foreach ($level_range_list as $key => $item) {

			if ($User_Cost >= $item['begin_cost'] && $User_Cost < $item['end_cost']) {
				return $key;
			}

		}

	}

	// 多where
	public function scopeMultiwhere($query, $arr) {
		if (!is_array($arr)) {
			return $query;
		}

		foreach ($arr as $key => $value) {
			$query = $query->where($key, $value);
		}

		return $query;
	}

	/**
	 * 关闭日期转换功能
	 *
	 */
	public function getDates() {
		return array();
	}

}