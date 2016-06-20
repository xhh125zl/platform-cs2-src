<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 地区Model
 */

class Area extends Illuminate\Database\Eloquent\Model
{	
	protected  $primaryKey = "area_id";
	protected  $table = "area";
	public $timestamps = false;

	
	public function AgentArea(){
		return $this->hasMany('Dis_Agent_Area','area_id','area_id');
	}
    /**
	 *获取地区列表 ，华北，华东，华南 等
	 *@param  Array $except 例外地区id 
	 *@return Array $region 地区列表
	 *					   ['华北'=>[1,2,3,4,5],'东北'=>[6,7,8] ...]
	 */
	public static function getRegionList($except = []){
		
		$builder = self::where('area_deep',1);
		if(!empty($except)){
			$builder->whereNotIn('area_id',$except);
		}
		
		$province_all_array = $builder->get()->toArray();
								
		
		foreach ($province_all_array as $a) {
            if ($a['area_deep'] == 1 && $a['area_region'])
                $region[$a['area_region']][] = $a['area_id'];
		}
		
		return $region;

	}
	
	/**
	 *获取省地区列表 
	 *@param  string $areaid 例外地区id 
	 *@return Array $are_id 地区列表	
	 */
	public static function getAreaList($areaid = 0){
		
		$areaidder = self::where('area_parent_id',$areaid);		
		$are_idarr = $areaidder->get()->toArray();
		foreach ($are_idarr as $a) {            
                $are_id[$a['area_id']] = $a;
		}
		return $are_id;
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