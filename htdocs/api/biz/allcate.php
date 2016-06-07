<?php
require_once('global.php');
$header_title = "全部分类";
$categorys = array();
$DB->Get("biz_category","*","where Users_ID='".$UsersID."' and Biz_ID=".$BizID." order by Category_ParentID asc,Category_Index asc, Category_ID asc");
while($r = $DB->fetch_assoc()){
	if($r["Category_ParentID"]==0){
		$categorys[$r["Category_ID"]] = $r;
	}else{
		$categorys[$r["Category_ParentID"]]["child"][] = $r;
	}
}
include($rsBiz['Skin_ID']."/allcate.php");
?>