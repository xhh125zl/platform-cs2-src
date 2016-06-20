<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 店铺配置 Model
 */
use Illuminate\Database\Eloquent\SoftDeletes;
class Shop_Config extends Illuminate\Database\Eloquent\Model
{
	
	protected  $primaryKey = "Users_ID";
	protected  $table = "shop_config";
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