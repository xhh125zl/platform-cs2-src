<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
require_once('vertify.php');
$rsConfig=$DB->GetRs("web_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");
$rsSkin=$DB->GetRs("web_home","*","where Users_ID='".$_SESSION["Users_ID"]."' and Skin_ID=".$rsConfig['Skin_ID']);

function UrlList(){
	global $DB;
	echo '<option value="">--请选择--</option>
	<optgroup label="------------------系统业务模块------------------"></optgroup>';
	$DB->get("wechat_material","Material_ID,Material_Table,Material_Json","where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table<>'0' and Material_TableID=0 and Material_Display=0 order by Material_ID desc");
	while($rsMaterial=$DB->fetch_assoc()){
		$Material_Json=json_decode($rsMaterial['Material_Json'],true);
		echo '<option value="/api/'.$_SESSION["Users_ID"].'/'.$rsMaterial['Material_Table'].'/">'.$Material_Json['Title'].'</option>';
	}
	echo '<optgroup label="------------------微官网二级页面------------------"></optgroup>';
	$DB->get("web_column","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Column_Index asc");
	while($rsColumn=$DB->fetch_assoc()){
		echo '<option value="/api/'.$_SESSION["Users_ID"].'/web/column/'.$rsColumn['Column_ID'].'/">'.$rsColumn['Column_Name'].'</option>';
	}
	echo '<option value="/api/'.$_SESSION["Users_ID"].'/web/lbs/">一键导航(LBS)</option>';
	echo '<optgroup label="------------------微商城产品分类页面------------------"></optgroup>';
	$DB->get("shop_category","*","where Users_ID='".$_SESSION["Users_ID"]."' and Category_ParentID=0 order by Category_Index asc");
	$ParentCategory=array();
	$i=1;
	while($rsPCategory=$DB->fetch_assoc()){
		$ParentCategory[$i]=$rsPCategory;
		$i++;
	}
	foreach($ParentCategory as $key=>$value){
		$DB->get("shop_category","*","where Users_ID='".$_SESSION["Users_ID"]."' and Category_ParentID=".$value["Category_ID"]." order by Category_Index asc");
		if($DB->num_rows()>0){
			echo '<option value="/api/'.$_SESSION["Users_ID"].'/shop/category/'.$value["Category_ID"].'/">'.$value["Category_Name"].'</option>';
			while($rsCategory=$DB->fetch_assoc()){
				echo '<option value="/api/'.$_SESSION["Users_ID"].'/shop/category/'.$rsCategory["Category_ID"].'/">&nbsp;&nbsp;├'.$rsCategory["Category_Name"].'</option>';
			}
		}else{
			echo '<option value="/api/'.$_SESSION["Users_ID"].'/shop/category/'.$value["Category_ID"].'/">'.$value["Category_Name"].'</option>';
		}
	}
	
	echo '<optgroup label="------------------自定义URL------------------"></optgroup>';
	$DB->get("wechat_url","*","where Users_ID='".$_SESSION["Users_ID"]."'");
	while($rsUrl=$DB->fetch_assoc()){
		echo '<option value="'.$rsUrl['Url_Value'].'">'.$rsUrl['Url_Name'].'('.$rsUrl['Url_Value'].')</option>';
	}
}
require_once('skin/'.$rsConfig['Skin_ID'].'.php');
?>