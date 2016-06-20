<?php
require_once('../global.php');
if($IsStore==0){
	echo '<script language="javascript">alert("请勿非法操作！");history.back();</script>';
	exit();
}
$rsSkin=$DB->GetRs("biz_home","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Skin_ID=".$rsBiz["Skin_ID"]);

function UrlList($DB){
	$rsBiz=$DB->GetRs("biz","*","where Biz_ID=".$_SESSION["BIZ_ID"]);
	echo '<option value="">--请选择--</option>
	      <optgroup label="------------------产品分类------------------"></optgroup>';
	echo '<option value="/api/shop/'.$rsBiz["Users_ID"].'/biz/'.$_SESSION["BIZ_ID"].'/">店铺主页</option>';
	
	$cates = array();
	$DB->get("biz_category","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Category_ParentID=0 order by Category_Index asc,Category_ID asc");
	while($r=$DB->fetch_assoc()){
		$cates[] = $r;
	}
	foreach($cates as $v){
		echo '<option value="/api/shop/'.$rsBiz["Users_ID"].'/biz/'.$_SESSION["BIZ_ID"].'/products/'.$v["Category_ID"].'/">'.$v["Category_Name"].'</option>';
		$DB->get("biz_category","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Category_ParentID=".$v["Category_ID"]." order by Category_Index asc,Category_ID asc");
		while($f=$DB->fetch_assoc()){
			echo '<option value="/api/shop/'.$rsBiz["Users_ID"].'/biz/'.$_SESSION["BIZ_ID"].'/products/'.$f["Category_ID"].'/">└─'.$f["Category_Name"].'</option>';
		}
	}
}
require_once('skin/'.$rsBiz['Skin_ID'].'.php');
?>