<?php 
//加载数据库类
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET['categoryID'])){
	$catid = $_GET['categoryID'];
}else{
	echo "缺少必要的参数";
	exit;
}

$catelist = $cateinfos = array();
$DB->get("industry","*","where parentid=0 order by listorder asc");
while($r = $DB->fetch_assoc()){
	$catelist[] = $r;
}
foreach($catelist as $f){
	$cateinfos[$f["id"]] = array(
		"catid"=>$f["id"],
		"catname"=>$f["name"],
		"child"=>get_childs($f["id"])
	);
}
function get_childs($catid){
	global $DB1;
	$catids = array();
	$DB1->get("industry","*","where parentid=".$catid);
	while($r = $DB1->fetch_assoc()){
		$catids[] = $r;
	}
	return $catids;
}

include("skin/allcategory.php");
?>