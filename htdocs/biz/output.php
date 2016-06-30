<?php
/*导出表格处理文件*/
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/outputExcel.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/balance.class.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
$rsBiz = $DB->GetRs('biz','*','where Biz_ID='.$_SESSION["BIZ_ID"]);
$balance = new balance($DB,$rsBiz["Users_ID"]);
 
$UsersID = $rsBiz['Users_ID'];
 
$type = $_REQUEST['type'];

if($type == 'product_gross_info'){
	$table = 'shop_products';
	$fields  = '*';
	$condition = "where Users_ID='".$UsersID."' and Biz_ID=".$rsBiz['Biz_ID'];
	if($_GET['Keyword']){
		$condition .= " and Products_Name like '%".$_GET['Keyword']."%'";
	}
	if($_GET['SearchCateId']>0){
		$catids = ','.$_GET['SearchCateId'].',';
		$condition .= " and Products_Category like '%".$catids."%'";
	}
	if($_GET['BizID']>0){
		$condition .= " and Biz_ID=".$_GET['BizID'];
	}
	if($_GET["Attr"]){
		$condition .= " and Products_".$_GET["Attr"]."=1";
	}
	if($_GET["Status"]){
		$condition .= " and Products_Status=".$_GET["Attr"];
	}
	
	$resource = $DB->get($table,$fields,$condition);
	$data = $DB->toArray($resource);	
	foreach($data as $key=>$item){
		//处理产品属性
		$JSON = json_decode($item['Products_JSON'], TRUE);
		$property = '';
		if(isset($JSON['Property'])){

			foreach($JSON['Property'] as  $k=>$value){
				$property .= $k.':';

				if(is_array($value)){
					foreach($value as $v){
						$property .= $v;
					}

				}else{
					$property .= $value;
				}
			}
				
		}
		$item['Products_Property'] = $property;
		
		if($item["Biz_ID"]==0){
			$item["Biz_Name"] = "本站供货";
		}else{
			$item['Biz_Name'] = $rsBiz['Biz_Name'];	
		}
		$data[$key] = $item;
	}
	$outputExcel = new OutputExcel();
	$outputExcel->product_gross_info($data);
	
}elseif($type == 'order_detail_list'){
	
	$condition = "where Users_ID='".$UsersID."' and Biz_ID=".$rsBiz['Biz_ID']." and Order_Type='shop'";

	if(!empty($_GET["Keyword"])){
		$condition .= " and Order_CartList like '%".$_GET["Keyword"]."%'";
	}
	if(isset($_GET["Status"])){
		if($_GET["Status"]<>''){
			$condition .= " and Order_Status=".$_GET["Status"];
		}
	}
	
	if(!empty($_GET['BizID'])){
		if($_GET['BizID']>0){
			$condition .= " and Biz_ID=".$_GET['BizID'];
		}
	}

	if(!empty($_GET["AccTime_S"])){
		$condition .= " and Order_CreateTime>=".strtotime($_GET["AccTime_S"]);
	}
	if(!empty($_GET["AccTime_E"])){
		$condition .= " and Order_CreateTime<=".strtotime($_GET["AccTime_E"]);
	}
	
	$beginTime = !empty($_GET["AccTime_S"])?$_GET["AccTime_S"]:'';
	$endTime  = !empty($_GET["AccTime_E"])?$_GET["AccTime_E"]:'';
	

	$resource = $DB->get("user_order","*",$condition);
	$list = $DB->toArray($resource);
	
	foreach($list as $key=>$item){
		if($item["Biz_ID"]==0){
			$item["Biz_Name"] = "本站供货";
		}else{
			$item['Biz_Name'] = $rsBiz['Biz_Name'];
		}
		
		if(is_numeric($item['Address_Province'])){
			$area_json = read_file($_SERVER["DOCUMENT_ROOT"].'/data/area.js');
			$area_array = json_decode($area_json,TRUE);
			$province_list = $area_array[0];
			$Province = '';
			if(!empty($item['Address_Province'])){
				$Province = $province_list[$item['Address_Province']].',';
			}
			$City = '';
			if(!empty($item['Address_City'])){
				$City = $area_array['0,'.$item['Address_Province']][$item['Address_City']].',';
			}

			$Area = '';
			if(!empty($item['Address_Area'])){
				$Area = $area_array['0,'.$item['Address_Province'].','.$item['Address_City']][$item['Address_Area']];
			}
		}else{
			$Province = $item['Address_Province'];
			$City = $item['Address_City'];
			$Area = $item['Address_Area'];
		}
		
		$item['receiver_address'] = $Province.$City.$Area.$item['Address_Detailed'];
		$item['Order_CartList'] = json_decode(htmlspecialchars_decode($item['Order_CartList']),TRUE);
		$list[$key] = $item;
	}
	
	$outputExcel = new OutputExcel();
	$outputExcel->order_detail_list($beginTime,$endTime,$list);
	
}elseif($type == 'sales_record_list'){
	$condition = "where Users_ID='".$rsBiz["Users_ID"]."'";
	if(isset($_GET["Status"])){
		if($_GET["Status"]<>''){
			$condition .= " and Record_Status=".$_GET["Status"];
		}
	}
	if($_GET['BizID']>0){
		$condition .= " and Biz_ID=".$_GET['BizID'];
	}
	if(!empty($_GET["AccTime_S"])){
		$condition .= " and Record_CreateTime>=".strtotime($_GET["AccTime_S"]);
	}
	if(!empty($_GET["AccTime_E"])){
		$condition .= " and Record_CreateTime<=".strtotime($_GET["AccTime_E"]);
	}
	$condition .= " order by Record_ID desc";
	
	$beginTime = !empty($_GET["AccTime_S"])?$_GET["AccTime_S"]:'';
	$endTime  = !empty($_GET["AccTime_E"])?$_GET["AccTime_E"]:'';

	$lists = array();
	$DB->Get("shop_sales_record","*",$condition);
	while($r=$DB->Fetch_assoc()){
		$lists[$r["Record_ID"]] = $r;
	}
	$lists = $balance->repeat_list($lists);
	 foreach($lists as $recordid=>$value){
		if($value["Biz_ID"]==0){
			$value["Biz_Name"] = "本站供货";
		}else{
			$item['Biz_Name'] = $rsBiz['Biz_Name'];	
		}
		$lists[$recordid] = $value;
	 }
	
	$outputExcel = new OutputExcel();
	$outputExcel->sales_record_list($beginTime,$endTime,$lists);
}