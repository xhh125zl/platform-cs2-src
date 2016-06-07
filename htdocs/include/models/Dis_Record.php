<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 分销账户Model
 */
use Illuminate\Database\Eloquent\SoftDeletes;
class Dis_Record extends Illuminate\Database\Eloquent\Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	
	protected  $primaryKey = "Record_ID";
	protected  $table = "distribute_record";
	public $timestamps = false;
	

	//一条分销记录属于一个订单
	public function Order(){
		return $this->belongsTo('Order','Order_ID','Order_ID');
	}
	
	//一条分销记录对应一个购买者
	public function Buyer(){
		return $this->belongsTo('User','Buyer_ID');
	}
	
	//一个分销记录对应一个拥有者
	public function Owner(){
		return $this->belongsTo('User','Owner_ID');
	}
	
	
	//一条分销记录对应一个产品
	public function Product(){
		return $this->belongsTo('Product','Product_ID');
	}
	
	/*一条分销记录拥有多个分销佣金记录*/	
    public function DisAccountRecord(){
        return $this->hasMany('Dis_Account_Record','Ds_Record_ID','Record_ID');
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