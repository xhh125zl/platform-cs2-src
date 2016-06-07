<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 产品Model
 */
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Illuminate\Database\Eloquent\Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	
	protected  $primaryKey = "Products_ID";
	protected  $table = "shop_products";
	public $timestamps = false;
	
	public function DisRecord(){
		return $this->hasMany('Dis_Record','Product_ID','Products_ID');
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