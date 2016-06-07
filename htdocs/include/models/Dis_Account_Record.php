<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 分销账户记录Model
 */
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Capsule\Manager as Capsule;
class Dis_Account_Record extends Illuminate\Database\Eloquent\Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = ['Record_Status'];
	protected $primaryKey = "Record_ID";
	protected $table = "distribute_account_record";
	public $timestamps = false;

	//一个佣金获得记录属于一个分销记录
	public function DisRecord() {
		return $this->belongsTo('Dis_Record', 'Ds_Record_ID');
	}
	
	/*一条佣金分销记录属于一个用户*/
	public function User(){
		return $this->belongsTo('User','User_ID','User_ID');
	}

	
	// 多where
	public function scopeMultiwhere($query, $arr) {
		if (!is_array($arr)) {
			return $query;
		}

		foreach ($arr as $key => $value) {
			$query = $query->where($key, $value);
		}
		return $query;
	}

	//无需日期转换
	public function getDates() {
		return array();
	}

	/**
	 * 指定时间内分销佣金合计
	 * @param  string $Users_ID   本店唯一ID
	 * @param  int $Begin_Time 开始时间
	 * @param  int $End_Time   结束视
	 * @param  int  $Status     佣金状态
	 * @return float  $sum     佣金合计数额
	 */
	public function recordMoneySum($Users_ID, $Begin_Time, $End_Time, $Record_Status = '') {

		$builder = $this->where('Users_ID', $Users_ID)
		               ->whereBetween('Record_CreateTime', [$Begin_Time, $End_Time]);
			
			
			
		if (strlen($Record_Status) > 0) {
			$builder->where('Record_Status', $Record_Status);
		}
		

		$sum = $builder->sum('Record_Money');
			
		return $sum;
	}
	
	/**
	 * 指定时间内的记录
	 * @param  $Users_ID 店铺唯一标识
	 * @param  $Begin_Time 开始时间
	 * @param  $End_Time 结束时间
	 * @return array 订单列表
	 */
	public function recordBetween($Users_ID, $Begin_Time, $End_Time, $Record_Status) {
		$builder = $this::with('User')
		                 ->where('Users_ID', $Users_ID);
		 
		
		if ($Record_Status != 'all') {
			$builder = $builder->where('Record_Status', $Record_Status);
		}
		
		
		
		$builder->whereBetween('Record_CreateTime', [$Begin_Time, $End_Time])
			->orderBy('Record_CreateTime', 'desc');
		
		return $builder;
	}
	
	/**
	 * 批量添加分销账号记录
	 * @param  Collection  $records 分销账号记录
	 * @return bool        记录是否添加成功
	 */
	public  function batchAdd($records){
		$flag = Capsule::table($this->table)->insert($records);
		return $flag;
		
	}
	
	/**
	 * 通过订单ID更改分销账号记录
	 * @param  int $orderID 订单ID
	 * @param  int $Status 分销账号记录的状态
	 * @return bool $flag  是否更改成功
	 */
    public static function changeStatusByOrderID($OrderID,$Status){

    	$order = Order::Find($OrderID);
		$disAccountRecord = $order->disAccountRecord();
		$flag = true;
		if($disAccountRecord->count() >0){
			 $flag = $disAccountRecord->rawUpdate(array('Record_Status'=>$Status)); 
		}	         
		return $flag;
    }
}