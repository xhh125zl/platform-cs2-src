<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 快递公司Model
 */

class Shipping_Company extends Illuminate\Database\Eloquent\Model
{
	
	protected  $primaryKey = "Shipping_ID";
	protected  $table = "shop_shipping_company";
	public $timestamps = false;

	// 多where
	public function scopeMultiwhere($query, $arr)
	{
		if (!is_array($arr)) {
			return $query;
		}
	
		foreach ($arr as $key => $value) {
			$query = $query->where($key, $value);
		}
		return $query;
	}
	
	
	//无需日期转换
	public function getDates()
	{
		return array();
	}
	
	
}
