<?php

	if((!empty($rsConfig["Distribute_Share"]) && !empty($rsConfig["Distribute_ShareScore"])) || (!empty($rsConfig["Member_Share"]) && !empty($rsConfig["Member_ShareScore"]))){
		if(!empty($_SESSION[$UsersID."User_ID"])){
			$share_score = 0;
			if($owner['id'] == $_SESSION[$UsersID."User_ID"] && $rsConfig["Distribute_Share"]){//分销商分享获积分
				$share_score = $rsConfig["Distribute_ShareScore"];
			}elseif($rsConfig["Member_Share"]){
				$share_score = $rsConfig["Member_ShareScore"];
			}
			if($share_score>0){
				$Data = array(
					"Users_ID"=>$UsersID,
					"User_ID"=>$_SESSION[$UsersID."User_ID"],
					"Type"=>"shop",
					"Score"=>$share_score,
					"CreateTime"=>time(),
					"Transfer"=>$share_config["link"]
				);
				$DB->Add("share_record",$Data);
				$share_config["link"] = 'http://'.$_SERVER['HTTP_HOST'].'/api/'.$UsersID.'/'.(empty($owner["id"]) ? '' : $owner["id"].'/').'share_recieve/'.$DB->insert_id().'/';
			}
		}
	}
?>