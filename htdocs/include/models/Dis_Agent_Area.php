<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dis_Agent_Area extends Illuminate\Database\Eloquent\Model
{	
	/**
	* 地区Model
	*/
	protected  $primaryKey = "id";
	protected  $table = "distribute_agent_areas";
	public $timestamps = false;
	
	protected  $fillable = array('type','Users_ID','Account_ID','area_id','area_name','create_at','status');

	function __construct(){
		
	}
   
    //代理地区属于分销账号
	public function disAccount(){
		return $this->belongsTo('Dis_Account', 'Account_ID');
	}
	
	//代理地区对应一个地区
	public function area(){
		return $this->belongsTo('Area', 'area_id');
	}
	
	
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
