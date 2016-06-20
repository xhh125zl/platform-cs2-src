<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 地区Model
 */

class Dis_Area_Agent extends Illuminate\Database\Eloquent\Model
{	
	protected  $primaryKey = "id";
	protected  $table = "dis_area_agents";
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