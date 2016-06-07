<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 总后台配置
 */
use Illuminate\Database\Eloquent\SoftDeletes;
class Setting extends Illuminate\Database\Eloquent\Model
{
	
	protected  $primaryKey = "id";
	protected  $table = "setting";
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
