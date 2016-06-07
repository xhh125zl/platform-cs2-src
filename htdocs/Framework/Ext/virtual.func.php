<?php
function get_virtual_confirm_code($UsersID) {
	global $DB1;
	for($i=0;$i<=1;$i++){
		$temchars = virtual_randcode(10);
		$r = $DB1->GetRs("user_order","*","where Users_ID='".$UsersID."' and Order_Code='".$temchars."'");
		$i=$r?0:1;
	}
	return $temchars;
}
function virtual_randcode($length=10){
	$chars = '0123456789';
	$temchars = '';
	for($i=0;$i<$length;$i++){
		$temchars .= $chars[ mt_rand(0, strlen($chars) - 1) ];
	}
	return $temchars;
}

function create_virtual_account($DB, $Mobile){
	$flag=true;
	mysql_query("begin");
	for($i=0;$i<=1;$i++){
		$Users_ID = virtual_randchar(10);
		$rsUsers=$DB->GetRs("users","*","where Users_ID='".$Users_ID."'");
		$i=$rsUsers?0:1;
	}
	
	for($i=0;$i<=1;$i++){
		$Account = virtual_randchar(10,'abcdefghijklmnopqrstuvwxyz');
		$rsUsers=$DB->GetRs("users","*","where Users_Account='".$Account."'");
		$i=$rsUsers?0:1;
	}
	
	$PassWord = virtual_randchar(8,'abcdefghijklmnopqrstuvwxyz');
		
	$Data=array(
		"Users_ID"=>$Users_ID,
		"Users_WechatToken"=>virtual_randchar(10),
		"Users_Account"=>$Account,
		"Users_Password"=>md5($PassWord),
		"Users_Mobile"=>$Mobile,
		"Users_Status"=>1,
		"Users_ExpireDate"=>time()+86400*30,
		"Users_Industry"=>0,
		"Users_Remarks"=>'',
		"Users_CreateTime"=>time()
	);
	$Add=$DB->Add("users",$Data);
	$flag=$flag&&$Add;
	//设置上传文件夹
	$save_path = $_SERVER["DOCUMENT_ROOT"].'/uploadfiles/'.$Users_ID.'/';
	if(!is_dir($save_path)){
		mkdir($save_path,0777,true);
	}
	if(!is_dir($save_path.'image/')){
		mkdir($save_path.'image/');
	}
	if(!is_dir($save_path.'media/')){
		mkdir($save_path.'media/');
	}
	if(!is_dir($save_path.'file/')){
		mkdir($save_path.'file/');
	}
	//设置首次关注
	$Data=array(
		"Users_ID"=>$Users_ID,
		"Reply_TextContents"=>"非常高兴认识你，新朋友！"
	);
	$Add=$DB->Add("wechat_attention_reply",$Data);
	$flag=$flag&&$Add;
	//初始化微商城
	$Data=array(
		"Users_ID"=>$Users_ID,
		"ShopName"=>$Account."的微商城",
		"Skin_ID"=>9
	);
	$Add=$DB->Add("shop_config",$Data);
	$flag=$flag&&$Add;
	$skin_home = $DB->GetRs("shop_skin","Skin_Json","where Skin_ID=9");
	//初始化微商城首页
	$Data=array(
		"Users_ID"=>$Users_ID,
		"Skin_ID"=>9,
		"Home_Json"=>$skin_home["Skin_Json"]
	);
	$Add=$DB->Add("shop_home",$Data);
	$flag=$flag&&$Add;
		
	//循环设置各功能模块	
	$Permit=array(
		"shop"=>"微商城",
		"user"=>"会员中心",
		"scratch"=>"刮刮卡",
		"fruit"=>"水果达人",
		"turntable"=>"欢乐大转盘",
		"battle"=>"一战到底"
	);
	foreach($Permit as $k=>$v){
		//根据授权的功能模块添加素材
		$Material=array(
			"Title"=>$v,
			"ImgPath"=>"/static/api/images/cover_img/".$k.".jpg",
			"TextContents"=>"",
			"Url"=>"/api/".$Users_ID."/".$k."/"
		);
		$Data=array(
			"Users_ID"=>$Users_ID,
			"Material_Table"=>$k,
			"Material_TableID"=>0,
			"Material_Display"=>0,
			"Material_Type"=>0,
			"Material_Json"=>json_encode($Material,JSON_UNESCAPED_UNICODE),
			"Material_CreateTime"=>time()
		);
		$Add=$DB->Add("wechat_material",$Data);
		$flag=$flag&&$Add;
		//添加关键词自动回复功能,并将素材id对应进去
		$Data=array(
			"Users_ID"=>$Users_ID,
			"Reply_Table"=>$k,
			"Reply_TableID"=>0,
			"Reply_Display"=>0,
			"Reply_Keywords"=>$v,
			"Reply_PatternMethod"=>0,
			"Reply_MsgType"=>1,
			"Reply_MaterialID"=>$DB->insert_id(),
			"Reply_CreateTime"=>time()
		);
		$Add=$DB->Add("wechat_keyword_reply",$Data);
		$flag=$flag&&$Add;
	}
	if($flag){
		mysql_query("commit");
		return array($Account, $PassWord);
	}else{
		mysql_query("roolback");
		return false;
	}
}

function virtual_randchar($length=10, $chars = 'abcdefghijklmnopqrstuvwxyz0123456789'){
	$temchars = '';
	for($i=0;$i<$length;$i++){
		$temchars .= $chars[ mt_rand(0, strlen($chars) - 1) ];
	}
	return $temchars;
}
?>