<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 商家Model
 */

class Biz extends Illuminate\Database\Eloquent\Model
{	
	protected  $primaryKey = "Biz_ID";
	protected  $table = "biz";
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