<?php
//分销模块url
$distribute_url = distribute_url();
$distribute_flag = false;//底部分销中心名称,true 分销中心,false 我要分销
$User_ID = empty($_SESSION[$UsersID.'User_ID']) ? 0 : $_SESSION[$UsersID.'User_ID'];
$rsUser = $DB->GetRs('user','*','where Users_ID="'.$UsersID.'" and User_ID='.$User_ID);

//获得分销级别
$dis_level = get_dis_level($DB,$UsersID);

//处理用户,不是分销商成为分销商,是分销商的更新分销等级
if(!$distribute_flag){
	$distribute_flag = deal_user_to_distribute($UsersID,$rsConfig,$rsUser,$dis_level);
}
?>