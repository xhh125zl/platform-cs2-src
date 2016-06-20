<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 分销账号爵位model
 */
class Dis_Level extends Illuminate\Database\Eloquent\Model
{
	
	protected  $primaryKey = "Users_ID";
	protected  $table = "distribute_level";
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
	
	
	public static function get_dis_pro_level($UsersID,$type = 'front'){
		$dis_level = static::where('Users_ID',$UsersID)->get(array('Level_ID','Level_Name'));
		$pro_levels = json_decode($dis_level, TRUE);
			return $pro_levels;		
	}
	
	//无需日期转换
	public function getDates()
	{
		return array();
	}
	
	
	/**
	 * 产生获得佣金限制tr
	 * @param  array  $bonusConfig 获得佣金限制配置 
	 * @param  int $level     级别
	 * @return string $html    佣金限制tr
	 */
	public static function getDisBonusTrs($smarty,$level,$bonusConfig  = array()){
		$html = '';
	
		$smarty->assign('levelList',self::_buildLevelList($level,$bonusConfig));
			
		$html = $smarty->fetch('dis_bonus_trs.html');
		
		return $html;
	}
	
	/**
	 * 产生手机显示多少级别的select
	 * @param  Smarty $smarty      smarty对象
	 * @param  int $level    当前所设置的分销级别
	 * @param  int $mobileLevel 当前所设置的手机显示分销级别列表
	 * @return string $html   级别设置html select 
	 */
	public static function getDisMobileLevelDropdown($smarty,$level,$mobileLevel){
		
		$html = '';
		$smarty->assign('mobileLevel',self::_buildMobileLevelList($level,$mobileLevel));
		$html = $smarty->fetch('dis_mobile_level.html');

		return $html;
	}



    private static function _buildMobileLevelList($level,$mobileLevel){
    	$levelList = array();
    	for($i=1;$i<=$level;$i++){
    		$levelList[$i] = $i==$mobileLevel?TRUE:FALSE;
    	}

    	return $levelList;
    }

	private static function _buildLevelList($level,$bonusConfig){

		$levelList = array();
		$levelName = array('一','二','三','四','五','六','七','八','九','十');
		$max = ceil($level/3)*3;
		$fill = $max-$level;
	
		$bonusConfig =  json_decode($bonusConfig,TRUE);
		
		for($i=1;$i<=$level;$i++){
		
			if(!empty($bonusConfig[$i])){
				$levelList[$i] = ['name'=>$levelName[$i-1],'Cost'=>$bonusConfig[$i]['Cost'],'Enable'=>$bonusConfig[$i]['Enable']];	
			}else{
				$levelList[$i] = ['name'=>$levelName[$i-1],'Cost'=>0,'Enable'=>FALSE];	
			}
			
		}


		for($j=1;$j<=$fill;$j++){
			array_push($levelList,FALSE);	
		}

		return $levelList;
	}
	
	
}