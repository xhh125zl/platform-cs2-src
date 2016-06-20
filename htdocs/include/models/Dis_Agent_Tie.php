<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dis_Agent_Tie extends Illuminate\Database\Eloquent\Model
{	
	/**
	* 地区Model
	*/
	protected  $primaryKey = "ID";
	protected  $table = "agent_back_tie";
	public $timestamps = false;
	
	protected  $fillable = array('type','Users_ID','Disname','Barjson','Order_CreateTime');

	function __construct(){
		
	}
	
	//无需日期转换
	public function getDates()
	{
		return array();
	}
	
	
}
