<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 店主Model
 */
use Illuminate\Database\Eloquent\SoftDeletes;
class Users extends Illuminate\Database\Eloquent\Model
{
	
	protected  $primaryKey = "Users_ID";
	protected  $table = "users";
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
	
	public function disAreaAgenta() {	
		return $this->hasMany('Dis_Agent_Tie', 'Users_ID', 'Users_ID');
	}

	//无需日期转换
	public function getDates()
	{
		return array();
	}
	
	
}
