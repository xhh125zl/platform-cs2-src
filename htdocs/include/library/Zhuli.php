<?php
/***
 *微信助力活动类
 *通过此类可以获取参加此活动者信息
 *以及活动规则
 *还有给力朋友列表
 *获取用户排行榜
 */
class Zhuli{

	/**
	 *此类的初始化函数
	 *@param Database $DB 数据库操作类
	 */
	private $zhuli_table = 'zhuli';      //助力活动表
	private $act_table = 'zhuli_act';    //活动参加者信息表
	private $record_table = 'zhuli_record';  //助力记录表
	private $score_range = array('5','30');   //获取随机积分范围

	public function __construct($UsersID,$DB){
		$this->db = $DB;
		$this->UsersID = $UsersID;

	}

	/**
	 *@desc根据指定userid,判断用户是否已参加此次助力活动
	 *@param string $user_id  指定userid
	 *@return bool  $result         是否已经参加
	 */
	public function is_joined($user_id){
		$fields = "Act_ID,Open_ID,User_ID,Act_Time,Act_Score";

		$condition = "where Users_ID ='".$this->UsersID."' and User_ID=".$user_id;
		$rsAct = $this->db->getRs($this->act_table,$fields,$condition);
		if($rsAct){
			return $rsAct['Act_ID'];
		}else{
			return FALSE;
		}
		
		
	}

	/**
	 *@desc 获取指定参加者信息
	 *@param string $id 指定参加者的open_id
	 *@return array $id 指定参加者详细信息
	 */
	public function get_act_info($Act_ID)
	{
		$fields = "Open_ID,User_ID,Act_Time,Act_Score";

		
		$condition = "where Users_ID ='".$this->UsersID."' and Act_ID=".$Act_ID;
		$rsAct = $this->db->getRs($this->act_table,$fields,$condition);

		/*获取此用户的排名*/
		$rsAct['rank'] = $this->get_my_rank($Act_ID);

		return $rsAct;
	}


	/**
	 *@desc 获取活动规则
	 *@param stirng $id 本活动的id
	 *@return $rszhuli 本活动的信息
	 *
	 */
	public function get_zhuli_info( )
	{
		$condition = "where Users_ID ='".$this->UsersID."'";

		$rsZhuli = $this->db->getRs($this->zhuli_table,'*',$condition);

		$rsZhuli['Prizes'] = json_decode($rsZhuli['Prizes'],TRUE);
		$rsZhuli['Fromtime'] = date("Y/m/d",$rsZhuli['Fromtime']);
		$rsZhuli['Totime'] = date("Y/m/d",$rsZhuli['Totime']);
		$prize_level_list = array('一等奖','二等奖','三等奖','四等奖','五等奖','六等奖');

		foreach($rsZhuli['Prizes'] as $key=>$prize){
			$rsZhuli['Prizes'][$key]['Prize_Level'] = $prize_level_list[$key];
		}
			
		$info = $rsZhuli;
		return $info;
	}



	/**
	 *@desc 助力操作
	 *@param string $help_open_id 帮助者的open_id
	 *@param string $user_id 被帮助则的act_id
	 */
	public function do_zhuli($help_open_id,$act_id)
	{
		//获取助力分数
		list($begin_score,$end_score) = $this->score_range;
		
		$score = rand($begin_score,$end_score);
		
		//添加助力记录
	    $this->add_zhuli_record($help_open_id,$act_id,$score);
		//增加助力分数
		$this->add_act_score($act_id,$score);
	}



	/**
	 * @desc 为指定的act_id 添加助力指数
	 *@param string $help_open_id 帮助者的open_id
	 *@param string $act_id 被帮助则的$act_id
	 *@pram int $score  增加分数值
	 *@return bool $result 是否操作成功
	 */
	private function add_zhuli_record($help_open_id,$act_id,$score){
		$data  = array(
			    'Act_ID'=>$act_id,
				'Users_ID'=>$this->UsersID,
			    'Open_ID'=>$help_open_id,
				'Open_Info'=>'Open_Info',
				'Record_Time'=>time(),
				'Record_Score'=>$score
				);
		$flag = $this->db->Add($this->record_table,$data);		
		return $flag;
	}

	/**
	 *@desc 给指定的act_id 增加助力分数
	 *@param string $act_id 指定的act_id
	 *@param int $score 所需增加的分数
	 *@return bool $return 是否增加成功
	 */
	private function add_act_score($act_id,$score)
	{
		
		$info =$this->get_act_info($act_id);
		$now_socre = $info['Act_Score'];
		
		$data = array(
				'Act_Score'=>$now_socre+$score
				);
				
		$condition = " where Users_ID='".$this->UsersID."' and Act_ID='".$act_id."'";		
		$flag = $this->db->Set($this->act_table,$data,$condition);		
		return $flag;
	}

	/**
	 *@desc 获取指定用户的给力列表
	 *@param string $act_id 指定的用户id
	 *@return array $给力列表
	 */
	public function zhuli_friend_list($act_id)
	{
		$condition = "where Users_ID ='".$this->UsersID."' and Act_ID=".$act_id." order by Record_Time" ;

		$rsList = $this->db->get($this->record_table,'Record_Score,Open_ID,Open_Info,Record_Time',$condition);

		/*将Json数据变为 数组*/

		$list = $this->db->toArray($rsList);

		foreach($list as $key=>$item){
			$list[$key]['Open_Info'] = json_decode($item['Open_Info'] ,TRUE);
			$list[$key]['Record_Time'] = date("Y/m/d h:m",$item['Record_Time']);
		}

		return $list;
	}

	/**
	 * @desc 获取此次活动的排行榜
	 * @return Array $rank 此次活动的用户排行榜
	 */
	public function get_rank()
	{

		$sql = "SELECT u.User_HeadImg,u.User_NickName,za.Act_Score,za.Act_ID FROM zhuli_act za,user u where u.User_OpenID = za.Open_ID
		";

		$rsRank = $this->db->query($sql);

		$rank = $this->db->toArray($rsRank);
		foreach($rank as $key=>$item){
			$rank[$key]['rank'] = $key+1;
		}

		return $rank;
	}


	/**
	 *@desc  获取指定用户id
	 *@param string $act_id 指定用户的act_id
	 *@return string $myRank 指定用户的排名
	 *
	 */
	public function get_my_rank($act_id){
		$rank_list = $this->get_rank();

		foreach($rank_list as $key=>$act){
			if($act['Act_ID'] == $act_id){
				$myRank =  $act['rank'];
			}

		}

		return $myRank;
	}
	
	/**
	 *@desc  参加此次活动
	 *@param string $act_id 指定用户的act_id
	 *@return string $myRank 指定用户的排名
	 *
	 */
	public function join($Open_ID,$User_ID){
		$data  = array(
				'Users_ID'=>$this->UsersID,
			    'Open_ID'=>$Open_ID,
				'User_ID'=>$User_ID,
				'Act_Time'=>time(),
				'Act_Score'=>0
				);
				
				
		$flag = $this->db->Add($this->act_table,$data);		
		if($flag){
			$act_id = $this->db->insert_id();
		}
		return $act_id;
	}

}