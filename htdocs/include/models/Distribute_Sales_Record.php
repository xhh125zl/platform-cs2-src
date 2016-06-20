<?php 
/**
 * 提现记录Model
 */
class Distribute_Sales_Record extends Illuminate\Database\Eloquent\Model
{	
	protected  $primaryKey = "Record_ID";
	protected  $table = "distribute_sales_record";
	public $timestamps = false;


    //提现记录属于用户
    function User(){
    	return $this->belongsTo('User', 'User_ID', 'User_ID');
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
