<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 代理商获取佣金记录
 */
class Dis_Agent_Record extends Illuminate\Database\Eloquent\Model
{
	
	protected  $primaryKey = "Record_ID";
	protected  $table = "distribute_agent_rec";
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