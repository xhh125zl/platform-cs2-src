<?php
$ss = $DB->GetRs("shop_config","Substribe,SubstribeUrl","where Users_ID='".$UsersID."'");
if($ss["Substribe"] == 1){//开启关注提醒
	$subparentid = empty($owner["id"]) ? 0 : $owner["id"];//当前ownerid
	$subopenid = '';//当前登录用户openid
	$subflag = 0;//是否关注标志
	
	if(!empty($_SESSION[$UsersID."User_ID"])){//如果用户登录
		$s = $DB->GetRs("user","User_OpenID,Owner_Id","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
		$subparentid = empty($s["Owner_Id"]) ? 0 : $s["Owner_Id"];//推荐者ID
		$subopenid = empty($s["User_OpenID"]) ? '' : $s["User_OpenID"];
	}
	
	
	
	$subflag = get_substribe($DB,$subopenid);
	
	if($subflag == 0){
		if($subparentid == 0){
			echo '<div style="clear:both; position:fixed; top:0px; left:0px; height:42px; font-size:1px; width:100%; background:#000; filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5; z-index:500"></div><div style="width:100%; display:block; position:fixed; top:0px; height:42px; left:0px; z-index:999999; color:#FFF" /><p style="background:#4878C6; border-radius:8px; height:30px; line-height:28px; font-size:14px; text-align:center; width:80px; margin:6px 8px 0px 0px; padding:0px; float:right">'.($ss["SubstribeUrl"] ? '<a href="'.$ss["SubstribeUrl"].'" style="display:block; width:100%; height:100%; color:#FFF">' : '').'关注我们'.($ss["SubstribeUrl"] ? '</a>' : '').'</p><img src="" style="float:left; width:42px; height:42px; display:block;" /><span style="display:block; font-size:14px; color:#FFF; height:42px; line-height:42px; float:left; margin-left:8px">您还未关注公众号</span></div>';
		}else{
			$subparent = $DB->GetRs("user","User_NickName,User_HeadImg","where User_ID=".$subparentid);
			echo '<div style="clear:both; position:fixed; top:0px; left:0px; height:42px; font-size:1px; width:100%; background:#000; filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5; z-index:500"></div><div style="width:100%; display:block; position:fixed; top:0px; height:42px; left:0px; z-index:999999; color:#FFF" /><p style="background:#4878C6; border-radius:8px; height:30px; line-height:28px; font-size:14px; text-align:center; width:80px; margin:6px 8px 0px 0px; padding:0px; float:right">'.($ss["SubstribeUrl"] ? '<a href="'.$ss["SubstribeUrl"].'" style="display:block; width:100%; height:100%; color:#FFF">' : '').'关注我们'.($ss["SubstribeUrl"] ? '</a>' : '').'</p><img src="'.$subparent['User_HeadImg'].'" style="float:left; width:42px; height:42px; display:block;" /><span style="display:block; font-size:14px; color:#FFF; height:42px; line-height:18px; float:left; margin-left:8px">'.$subparent['User_NickName'].'<br />推荐</span></div>';
		}
	}
}

function get_substribe($DB,$openid){
	if(empty($openid)){
		return 0;
	}
	$flag = 0;
	$r = $DB->GetRs("http_raw_post_data","HTTP_RAW_POST_DATA","where HTTP_RAW_POST_DATA like '%".$openid."%' ORDER BY ID desc");
	if(!$r){
		$flag = 0;
	}else{
		if(strpos($r['HTTP_RAW_POST_DATA'],"unsubscribe")>-1){
			$flag = 0;
		}else{
			$flag = 1;
		}
	}
	return $flag;
}
?>