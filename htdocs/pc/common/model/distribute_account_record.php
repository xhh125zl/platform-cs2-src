<?php 
namespace common\model;
/**
 * 分销账户记录Model
 */
class distribute_account_record extends \base\model{
	protected $dates = ['deleted_at'];
	protected $fillable = ['Record_Status'];
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
}