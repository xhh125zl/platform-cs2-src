<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 分销账户Model
 */
use Illuminate\Database\Eloquent\SoftDeletes;

class Dis_Account extends Illuminate\Database\Eloquent\Model {

	use SoftDeletes;
	protected $dates = ['deleted_at'];
	
	protected $fillable = ['Users_ID', 'User_Name', 'Account_ID', 'invite_id', 'balance',
		'Total_Income', 'Enable_Agent', 'User_ID', 'Account_CreateTime', 'Shop_Name',
		'Shop_Logo', 'Dis_Path', 'status', 'Ex_Bonus', 'Is_Audit',
		'Up_Group_Num', 'Group_Num', 'Professional_Title', 'last_award_income','sha_level'];

	protected $primaryKey = "Account_ID";
	protected $table = "distribute_account";
	public $timestamps = false;
	public $descendants = null;
	

	//一个分销账号属于一个用户
	public function user() {
		return $this->belongsTo('User', 'User_ID', 'User_ID');
	}

	//获取此分销商的邀请人
	public function inviter() {
		return $this->belongsTo('User', 'invite_id', 'User_ID');
	}

	//一个分销账户拥有多个代理地区
	public function disAreaAgent() {
		return $this->hasMany('Dis_Agent_Area', 'Account_ID', 'Account_ID');
	}

	/*一个分销账号拥有多个分销记录*/
	public function disRecord() {
		return $this->hasMany('Dis_Record', 'Owner_ID', 'User_ID');
	}

	/*一个分销账号拥有多个发钱记录*/
	public function disAccountRecord() {
		return $this->hasManyThrough('Dis_Account_Record', 'Dis_Record', 'Owner_ID', 'Record_ID');
	}

	/*一个分销账号拥有多个得钱记录*/
	public function disAccountPayRecord() {
		return $this->hasMany('Dis_Account_Record', 'User_ID', 'User_ID');
	}

	/**
	 * 获取此分销账号的祖先id列表
	 * @param 本店当前分销级数 int $level
	 * @return Array $ids 祖先id列表
	 */
	function getAncestorIds($level, $self = 0) {

		$ids = array();

		if (!empty($this->Dis_Path)) {
			$res = trim($this->Dis_Path, ',,');
			$list = explode(',', $res);

			$ids = array_slice($list, -$level);

			//convert id from  string to int
			foreach ($ids as $key => $item) {
				$ids[$key] = intval($item);
			}
		
		}
		

		$self ? array_push($ids, $this->User_ID) : '';
	
		return $ids;
	}

	/**
	 * 统计某一时间段内加入的分销商数量
	 * @param  string $Users_ID   本店唯一标识
	 * @param  string $Begin_Time 开始时间
	 * @param  string  $End_Time  结束时间
	 * @return int     $num     分销商数量
	 */
	public function accountCount($Users_ID, $Begin_Time, $End_Time) {
		$num = $this->where('Users_ID', $Users_ID)
			->whereBetween('Account_CreateTime', [$Begin_Time, $End_Time])
			->count();

		return $num;
	}

	/**
	 * 生成本分销账号的Dis_Path
	 * @return string $Dis_Path
	 */
	public function generateDisPath() {

		$Dis_Path = '';

		$user = $this->user()->getResults();
		$userOwnerID = $user->Owner_Id;
		$invite_id = $this->invite_id;
		$inviter = $this->inviter()->getResults();

		if ($invite_id != 0) {
			$inviterDisPath = $inviter->disAccount()
				->getResults()
				->Dis_Path;
			$ids = explode(',', trim($inviterDisPath, ',,'));
			$num = count($ids);

			//如果等于九
			if ($num == 9) {
				array_shift($ids);
				array_push($ids, $userOwnerID);
				$Dis_Path = ',' . implode(',', $ids) . ',';
				//如果小于九
			} else if ($num < 9) {

				$pre = strlen($inviterDisPath) ? '' : ',';
				$Dis_Path = $pre . $inviterDisPath . $userOwnerID . ',';
			}

		} else {
			//如果不是根店
			if ($userOwnerID != 0) {
				$Dis_Path = ',' . $userOwnerID . ',';
			}
		}

		return $Dis_Path;
	}

	/**
	 * 统计分销商各个级别有下属的数目
	 * @param  int    $level 本商城分销层数
	 * @return array  $levelNum 每个级别的下属数目
	 *                example  [1=>3,2=>4,5=6]
	 */
	public function getLevelNum($level) {

		$levelList = $this->getLevelList($level);
	
		$levelNum = array();
		if(!empty($levelList)){
			foreach($levelList as $level=>$collection){
				$levelNum[$level] = $collection->count();
			}
		}
		
		return $levelNum;
	}

	/**
	 * 获取以级别为索引的下属列表
	 * @param  int   $level 本商城分销层数
	 * @return array $levelList 下属列表
	 */
	public function getLevelList($level){
		
		$posterity = $this->getPosterity($level);
		
		$levelList = array();
		for($i=1;$i<=$level;$i++){
			$levelList[$i] = $posterity->where('level',$i);
		}

		return $levelList;

	}
	/**
	 * 获取此账号下属分销商
	 * @param  int $level 此店的分销商层数
	 * @return Collection $posterity
	 */
	 /*edit in 20160328*/
	public function getPosterity($level, $force = false) {

		$User_ID = $this->User_ID;
		//获取分销父路径中包含此用户ID的分销商
		if(empty($this->descendants)||$force){
		 $fields = ['Account_ID', 'User_ID', 'Dis_Path','Shop_Name','balance',
		           'Is_Audit','Total_Income','Account_CreateTime','Level_ID','Real_Name','Shop_Logo','Professional_Title'];
		 $this->descendants = $this->where('Users_ID', $this->Users_ID)
			->where('Dis_Path', 'like', '%,' . $User_ID . ',%')
			->get($fields);
		}
		
		//筛选出处于$level级别中的分销商
		$posterity = $this->descendants->filter(function (&$dsAccount) use ($level, $User_ID) {

			//计算出分销商级数
			$dis_path = trim($dsAccount->Dis_Path, ',');
			$dis_path_nodes = explode(',', $dis_path);
			$dis_path_nodes = array_reverse($dis_path_nodes);
			$pos = array_search($User_ID, $dis_path_nodes);
			$curLevel = $pos + 1;

			//为分销账号动态指定级别
			$dsAccount->level = $curLevel;

			if ($curLevel <= $level) {
				return true;
			}
		});
		return $posterity;
	}
	
	public function getFirstchild($force=false) {
		$lists = array();
		$User_ID = $this->User_ID;
		//获取分销父路径中包含此用户ID的分销商
		if(empty($this->descendants)||$force){
		 $fields = ['Account_ID', 'User_ID', 'Dis_Path','Shop_Name','balance','invite_id',
		           'Is_Audit','Total_Income','Account_CreateTime'];
		 $this->descendants = $this->where('Users_ID', $this->Users_ID)
			->where('Dis_Path', 'like', '%,' . $User_ID . ',%')
			->get($fields);
		}
		
		//筛选出处于$level级别中的分销商
		$posterity = $this->descendants->toArray();
		
		foreach($posterity as $p){
			$p["Dis_Path"] = substr($p["Dis_Path"],1,-1);
			$arr_temp = explode(",",$p["Dis_Path"]);
			if($arr_temp[count($arr_temp)-1]==$User_ID){
				$lists[$p["User_ID"]] = $p;
				$lists[$p["User_ID"]]["child"] = 0;
			}else{
				$current = array_search($User_ID,$arr_temp);
				$lists[$arr_temp[$current+1]]["child"] = empty($lists[$arr_temp[$current+1]]["child"]) ? 1 : $lists[$arr_temp[$current+1]]["child"]+1;
			}
		}
		return $lists;
	}
	
	/**
	 * 返回此分销账号完整父路径
	 * @param  int $level 此店的分销商层数
	 * @return String $fullDisPath
	 */
	public function getFullDisPath(){
		if(!empty($this->Dis_Path)){
			$fullDisPath = trim($this->Dis_Path,',,');
			$Users_ID = $this->Users_ID;
			$disCollection  = $this->where('Users_ID',$Users_ID)
			                    ->get(array('User_ID','Dis_Path'));
								
			
				
			$disDictionary = get_dropdown_collection($disCollection,'User_ID');
			
			$Dis_Path = trim($this->Dis_Path,',,');
		
			//向上循环，直至找到自己的根店分销商
			while(!empty($Dis_Path)){
				$first = strstr($Dis_Path,',',TRUE);
				$first = $first?$first:$Dis_Path;
				
				$parentDistirbuteAccount = $disDictionary[$first];
				$Dis_Path = $parentDistirbuteAccount->Dis_Path;
				$Dis_Path = trim($Dis_Path,',,');
			    if(!empty($Dis_Path)){
					$fullDisPath =  $Dis_Path.','.$fullDisPath;
				}
			}					
			
		    return  $fullDisPath ;
	
		}else{
			$fullDisPath = '';
		}

		return $fullDisPath ;
		
	}

	//无需日期转换
	public function getDates() {
		return array();
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
	

}

?>