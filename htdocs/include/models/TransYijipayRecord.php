<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 店主充值记录
 */
use Illuminate\Database\Eloquent\SoftDeletes;
class TransYijipayRecord extends Illuminate\Database\Eloquent\Model
{
	
	protected  $primaryKey = "ID";
	protected  $table = "trans_yijipay_record";
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
