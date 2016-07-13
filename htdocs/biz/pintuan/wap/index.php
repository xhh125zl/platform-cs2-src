<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
//slide
$Slide = $S_Title = $S_Img = $S_Link = array();
$DB->get("slide","*","order by listorder asc");
while($r = $DB->fetch_assoc()){
	$S_Img[] = $r["thumb"];
	$S_Link[] = $r["linkurl"];
	$S_Title[] = "";
}
if(!empty($S_Img)){
	$Slide[0]["ContentsType"] = "1";
	$Slide[0]["Title"] = json_encode($S_Title);
	$Slide[0]["ImgPath"] = json_encode($S_Img);
	$Slide[0]["Url"] = json_encode($S_Link);
	$Slide[0]["Postion"] = "t01";
	$Slide[0]["Width"] = "640";
	$Slide[0]["Height"] = "320";
	$Slide[0]["NeedLink"] = "1";
}
//slide end
//category
$catelist = array();
$DB->get("industry","*","where parentid=0 and indexshow=1 order by listorder asc");
while($r = $DB->fetch_assoc()){
	$catelist[] = $r;
}

//products
$firstcate = $products_list = array();
$DB->get("industry","*","where parentid=0 order by listorder asc");
while($r = $DB->fetch_assoc()){
	$firstcate[] = $r;
}
foreach($firstcate as $f){
	$products_list[$f["id"]] = array(
		"catid"=>$f["id"],
		"catname"=>$f["name"],
		"products"=>get_products($f["id"])
	);
}
function get_company($catid){
	global $DB1;
	$catids = array();
	$companys = "";
	$catids[] = $catid;
	$DB1->get("industry","*","where parentid=".$catid);
	while($r = $DB1->fetch_assoc()){
		$catids[] = $r["id"];
	}
	
	$DB1->get("users","Users_ID","where Users_Industry in(".(implode(",",$catids)).")");
	while($r = $DB1->fetch_assoc()){
		$companys .= ",'".$r["Users_ID"]."'";
	}
	return $companys ? substr($companys,1) : '';	
}

function get_products($catid){
	global $DB1;
	$lists = array();
	$companys = get_company($catid);
	if($companys){
		$DB1->get("shop_products","*","where Users_ID in($companys) and Products_SoldOut=0 order by Products_Sales desc");
		while($r = $DB1->fetch_assoc()){
			$lists[] = $r;
		}
	}
	return $lists;
}
include("skin/index.php");
?>