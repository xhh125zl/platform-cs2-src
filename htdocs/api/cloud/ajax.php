﻿<?php
require_once($_SERVER ["DOCUMENT_ROOT"].'/Framework/Conn.php');
ini_set("display_errors","On");
require_once(CMS_ROOT.'/include/helper/shipping.php');
require_once(CMS_ROOT.'/include/helper/tools.php');
require_once(CMS_ROOT.'/include/helper/flow.php');
require_once(CMS_ROOT.'/include/helper/lib_products.php');
require_once(CMS_ROOT.'/include/helper/order.php');
require_once(CMS_ROOT.'/include/library/smarty.php');
require_once(CMS_ROOT.'/Framework/Ext/virtual.func.php');
require_once(CMS_ROOT.'/Framework/Ext/sms.func.php');

if(isset($_REQUEST["UsersID"])){
	$UsersID = $_REQUEST["UsersID"];
}else{
	echo 'error';
	exit;
}

$BizID = isset($_REQUEST['BizID']) && $_REQUEST['BizID']?$_REQUEST['BizID']:0;
$BizInfo = [];
$ActiveID     = isset($_REQUEST['ActiveID']) && $_REQUEST['ActiveID']?$_REQUEST['ActiveID']:0;
$activelist   = [];
$listGoods    = "";
$result       = "";
$time         = time();
$action=empty($_REQUEST["action"])?"":$_REQUEST["action"];
$ListShowGoodsCount = 0;

if($BizID && $action != 'category'){
    $BizInfo = $DB->GetRs("biz","*","WHERE Users_ID='{$UsersID}' AND Biz_ID='{$BizID}'");
    if(empty($BizInfo))
    {
       die("商家不存在");
    }
    
    $sql = "SELECT a.Users_ID,a.Active_ID,a.MaxBizCount,a.ListShowGoodsCount,a.BizShowGoodsCount FROM active AS a LEFT JOIN active_type AS t ON a.Type_ID=t.Type_ID WHERE a.Users_ID='{$UsersID}' AND t.module='cloud' AND a.Active_ID={$ActiveID} ";
    $result = $DB->query($sql);
    $rsActive = $DB->fetch_assoc($result);
    if(empty($rsActive)) {
        die("活动不存在");
    }
    $result = $DB->Get("biz_active","ListConfig,IndexConfig,Biz_ID,Active_ID","WHERE Users_ID='{$UsersID}' AND Active_ID={$ActiveID} AND Status=2 LIMIT 0,{$rsActive['MaxBizCount']}");
    $activelist = $DB->toArray($result);
    if(empty($activelist)){
        sendAlert("没有商家参与相关活动");
    }
    $indexGoods = "";
    $listGoods = "";
    foreach ($activelist as $k => $v)
    {
        $indexGoods .= $v['IndexConfig'].',';
        $listGoods .= $v['ListConfig'].',';
    }
    $listGoods = trim($listGoods,',');
    $indexGoods = trim($indexGoods,',');
    $listGoods_temp = explode(",",$listGoods);
    $indexGoods_temp = explode(",",$indexGoods);
    $dis_temp = array_diff($listGoods_temp,$indexGoods_temp);
    $dis_temp = implode($dis_temp,',');
    $listGoods = $indexGoods.','.$dis_temp;
    $listGoods = trim($listGoods,',');
    
    $bizCount = $rsActive['BizShowGoodsCount'];
    $method=!isset($_POST["method"])?"asc":$_POST["method"];
    $orderby = "";
    $fields = "Products_Name,Products_ID,Products_IsVirtual,Products_IsShippingFree,Products_Weight,Products_JSON,Products_PriceX,Products_PriceY,qishu,canyurenshu,zongrenci,Products_xiangoutimes,Products_CreateTime";
    if("republicTime" == $action){
        $orderby .= "ORDER BY Products_CreateTime {$method}";
    }else if("sales" == $action){
        $orderby .= "ORDER BY ROUND(canyurenshu/zongrenci,2) {$method}";
    }else if("prices" == $action){
        $orderby .= "ORDER BY Products_PriceY {$method}";
    }else if("define" == $action){
        $orderby .= "ORDER BY Products_Order {$method}";
    }else{
        $orderby .= "ORDER BY Products_CreateTime {$method}";
    }
    $counts = $DB->GetRs("cloud_products","count(Products_ID) as count","WHERE Users_ID='".$UsersID."' AND Biz_ID={$BizID} AND Products_Status = 1 AND zongrenci<>canyurenshu AND Products_ID IN ({$listGoods})");
    $num = 6;//每页记录数
    $p = !empty($_POST['p'])?intval(trim($_POST['p'])):1;
    $total = $counts['count'];//数据记录总数
    $totalpage = ceil($total/$num);//总计页数
    $limitpage = ($p-1)*$num;//每次查询取记录
    $sql = "SELECT {$fields} FROM (SELECT {$fields} FROM `cloud_products` WHERE Users_ID='{$UsersID}' AND Biz_ID={$BizID} AND Products_Status = 1 AND zongrenci<>canyurenshu AND Products_ID IN ({$listGoods}) LIMIT {$bizCount}) as t {$orderby} LIMIT {$limitpage},{$num}";
    $goods = $DB->query($sql);
    $list = $DB->toArray($goods);
    $products = [];
    foreach($list as $key=>$item){
        $JSON = json_decode($item['Products_JSON'],TRUE);
        if(isset($JSON["ImgPath"])){
            $list[$key]['ImgPath'] = $JSON["ImgPath"][0];
        }else{
            $list[$key]['ImgPath'] =  'static/api/shop/skin/default/nopic.jpg';
        }
    }
    if(count($list) > 0){
      $data = array(
        'list' => $list,
        'totalpage' => $totalpage,
      );
    }else{
      $data = array(//没有数据可加载
        'list' => '',
        'totalpage' => $totalpage,
      );
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);exit;
}else{
    $sql = "SELECT a.Users_ID,a.Active_ID,a.MaxBizCount,a.ListShowGoodsCount FROM active AS a LEFT JOIN active_type AS t ON a.Type_ID=t.Type_ID WHERE a.Users_ID='{$UsersID}' AND t.module='cloud' AND a.starttime<={$time} AND a.stoptime>{$time} AND a.Status = 1 ";

    if($ActiveID){
        $sql.= "AND a.Active_ID={$ActiveID}";
        $result = $DB->query($sql);
        $result = $DB->fetch_assoc();
        if(empty($result) || !$result){
            sendAlert("没有相关活动");
        }
    }else{    //没有传参就显示最新一次活动里边的内容
        $sql .= "ORDER BY a.Active_ID ASC";
        $result = $DB->query($sql);
        $result = $DB->fetch_assoc();
        if(empty($result) || !$result){
            sendAlert("没有相关活动");
        }
        $ActiveID = $result['Active_ID'];
    }
    $ListShowGoodsCount = $result['ListShowGoodsCount'];
    $result = $DB->Get("biz_active","ListConfig,IndexConfig,Biz_ID,Active_ID","WHERE Users_ID='{$UsersID}' AND Active_ID={$ActiveID} AND Status=2 LIMIT 0,{$result['MaxBizCount']}");
    $activelist = $DB->toArray($result);

    $indexGoods = "";
    foreach ($activelist as $k => $v)
    {
        $indexGoods .= $v['IndexConfig'].',';
        $listGoods .= $v['ListConfig'].',';
    }
    $listGoods = trim($listGoods,',');
    $indexGoods = trim($indexGoods,',');
    $listGoods_temp = explode(",",$listGoods);
    $indexGoods_temp = explode(",",$indexGoods);
    $dis_temp = array_diff($listGoods_temp,$indexGoods_temp);
    $dis_temp = implode($dis_temp,',');
    $listGoods = $indexGoods.','.$dis_temp;
    $listGoods = trim($listGoods,',');
}

/* 获取活动首页列表 */
$condition = "Users_ID='".$UsersID."' AND Products_Status = 1  and Products_SoldOut=0 AND Products_ID in ({$listGoods})";
$fields = "Products_Name,Products_ID,Products_IsVirtual,Products_IsShippingFree,Products_Weight,Products_JSON,Products_PriceX,Products_PriceY,qishu,canyurenshu,zongrenci,Products_xiangoutimes,Products_Order,Products_CreateTime";

if($action == "jjjx"){//即将揭晓(参与人数与总人数的比例大于80%的为即将揭晓)
	$counts = $DB->GetRs("cloud_products","count(Products_ID) as count","where {$condition} and (canyurenshu/zongrenci)=1 ");
	$num = 20;//每页记录数
	$p = !empty($_POST['p'])?intval(trim($_POST['p'])):1;
	$total = $counts['count'];//数据记录总数
	$totalpage = ceil($total/$num);//总计页数
	$limitpage = ($p-1)*$num;//每次查询取记录
	$sql = "SELECT {$fields} FROM (SELECT {$fields} FROM `cloud_products` WHERE {$condition} and (canyurenshu/zongrenci)=1 LIMIT {$ListShowGoodsCount}) as t order by ROUND(canyurenshu/zongrenci,2) desc, Products_Order asc, Products_CreateTime desc LIMIT {$limitpage},{$num}";
	$rsJjjxProducts = $DB->query($sql);
	$products_tmp = handle_product_list($DB->toArray($rsJjjxProducts));
	$products = array();
	foreach($products_tmp as $key => $val){
		if(($val['canyurenshu']/$val['zongrenci']) == 1 ){
			$products[] = $val;
			//$products[$key]['near'] = $val['canyurenshu']/$val['zongrenci'];
		}
	}
	
	if(count($products) > 0){
		$data = array(
			'list' => $products,
			'totalpage' => $totalpage,
		);
	}else{
		$data = array(//没有数据可加载
			'list' => '',
			'totalpage' => $totalpage,
		);
	}
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
	exit;
}elseif($action == "IsNew"){
	$counts = $DB->GetRs("cloud_products","count(Products_ID) as count","where {$condition} and Products_IsNew=1 and ROUND(canyurenshu/zongrenci,2)<1 ");
	$num = 20;//每页记录数
	$p = !empty($_POST['p'])?intval(trim($_POST['p'])):1;
	$total = $counts['count'];//数据记录总数
	$totalpage = ceil($total/$num);//总计页数
	$limitpage = ($p-1)*$num;//每次查询取记录
	$sql = "SELECT {$fields} FROM (SELECT {$fields} FROM `cloud_products` WHERE {$condition} and Products_IsNew=1 and ROUND(canyurenshu/zongrenci,2)<1 LIMIT {$ListShowGoodsCount}) as t order by Products_Order asc, Products_CreateTime asc LIMIT {$limitpage},{$num}";
	$rsProducts = $DB->query($sql);
	$products = handle_product_list($DB->toArray($rsProducts));
	
	if(count($products) > 0){
		$data = array(
			'list' => $products,
			'totalpage' => $totalpage,
		);
	}else{
		$data = array(//没有数据可加载
			'list' => '',
			'totalpage' => $totalpage,
		);
	}
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
	exit;
}elseif($action == "IsHot"){
	$counts = $DB->GetRs("cloud_products","count(Products_ID) as count","where {$condition} and Products_IsHot=1 and ROUND(canyurenshu/zongrenci,2)<1");
	$num = 20;//每页记录数
	$p = !empty($_POST['p'])?intval(trim($_POST['p'])):1;
	$total = $counts['count'];//数据记录总数
	$totalpage = ceil($total/$num);//总计页数
	$limitpage = ($p-1)*$num;//每次查询取记录
	$sql = "SELECT {$fields} FROM (SELECT {$fields} FROM `cloud_products` WHERE {$condition} and Products_IsHot=1 and ROUND(canyurenshu/zongrenci,2)<1 LIMIT {$ListShowGoodsCount}) as t order by Products_Order asc, Products_CreateTime asc LIMIT {$limitpage},{$num}";
	$rsProducts = $DB->query($sql);
	$products = handle_product_list($DB->toArray($rsProducts));
	if(count($products) > 0){
		$data = array(
			'list' => $products,
			'totalpage' => $totalpage,
		);
	}else{
		$data = array(//没有数据可加载
			'list' => '',
			'totalpage' => $totalpage,
		);
	}
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
	exit;
}elseif($action == "IsRecommend"){
	$counts = $DB->GetRs("cloud_products","count(Products_ID) as count","where {$condition} and Products_IsRecommend=1 and ROUND(canyurenshu/zongrenci,2)<1");
	$num = 20;//每页记录数
	$p = !empty($_POST['p'])?intval(trim($_POST['p'])):1;
	$total = $counts['count'];//数据记录总数
	$totalpage = ceil($total/$num);//总计页数
	$limitpage = ($p-1)*$num;//每次查询取记录
	$sql = "SELECT {$fields} FROM (SELECT {$fields} FROM `cloud_products` WHERE {$condition} and Products_IsRecommend=1 and ROUND(canyurenshu/zongrenci,2)<1 LIMIT {$ListShowGoodsCount}) as t order by Products_Order asc, Products_CreateTime asc LIMIT {$limitpage},{$num}";
	$rsProducts = $DB->query($sql);
	$products = handle_product_list($DB->toArray($rsProducts));
	if(count($products) > 0){
		$data = array(
			'list' => $products,
			'totalpage' => $totalpage,
		);
	}else{
		$data = array(//没有数据可加载
			'list' => '',
			'totalpage' => $totalpage,
		);
	}
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
	exit;
}elseif($action == 'lottery'){
	$rsProducts = $DB->GetRs('cloud_products','*','where Users_ID="'.$UsersID.'" and Products_ID='.$_POST['ProductsID'].' and Products_SoldOut=0');
	$shit = $DB->GetRs('cloud_record','Add_Time','where Products_ID ='.$rsProducts['Products_ID'].' order by Add_Time desc');
	$Products_End_Time = $shit['Add_Time'];
	$sumTime = 0;
	$Result = array();//计算结果
	//100条时、分、秒、毫秒之和 与 总人次取余数 加上10000001
	//$DB->Get('cloud_record','Products_ID,Add_Time,User_ID','group by Products_ID order by Add_Time desc limit 0,100');
	$DB->Get('cloud_record','Products_ID,Add_Time,User_ID','order by Add_Time desc limit 0,100');
	while($r = $DB->fetch_assoc()) {
		$Result['timerecord'][] = $r['Add_Time'];
		$Result['userrecord'][] = $r['User_ID'];
		if(strpos($r['Add_Time'], '.')){
			list($usec, $sec) = explode('.', $r['Add_Time']);
			$date = date('H:i:s', $usec);
		}else{
			$date = date('H:i:s', $r['Add_Time']);
			$sec = 0;
		}
		$h = explode(':', $date)[0];
		$i = explode(':', $date)[1];
		$s = explode(':', $date)[2];
		$sum[] = intval($h.$i.$s.$sec);
	}
	
	$sumTime = array_sum($sum);
	$Luck_Sn = intval(fmod(floatval($sumTime), floatval($rsProducts['zongrenci']))) + 10000001;
	$Result['sumTime'] = $sumTime;
	$Result['zongrenci'] = $rsProducts['zongrenci'];
	$rsRecord = $DB->GetRs('cloud_record', 'User_ID', 'where Products_ID='.$rsProducts['Products_ID'].' and qishu='.$rsProducts['qishu'].' and Cloud_Code=' . $Luck_Sn);
	$UserID = $rsRecord['User_ID'];
	$rsUser = $DB->GetRs('user', '*', 'where User_ID='.$UserID);
	$theLuckUserCount = $DB->GetRs('cloud_record', 'count(Record_ID) as count', 'where Products_ID='.$rsProducts['Products_ID'].' and User_ID='.$UserID.' and qishu='.$rsProducts['qishu']);
	$User_Info = array(
	    $rsUser['User_NickName'],
		$rsUser['User_Province'].''.$rsUser['User_City'],
		$theLuckUserCount['count'],//中奖者购买人次
	);
	//插入往期记录
	$insert_data = array(
	  'Users_ID'=>$UsersID,
		'Products_ID'=>$rsProducts['Products_ID'],
		'qishu'=>$rsProducts['qishu'],
		'User_ID'=>$UserID,
		'User_Info'=>serialize($User_Info),
		'Luck_Sn'=>$Luck_Sn,
		'Products_End_Time'=>$Products_End_Time,
		'Products_PriceX'=>$rsProducts['Products_PriceX'],
		'Products_Profit'=>$rsProducts['Products_Profit'],
		'commission_ratio'=>$rsProducts['commission_ratio'],
		'Products_Distributes'=>$rsProducts['Products_Distributes'],
		'Result' => serialize($Result),
	);
	$DB->Add('cloud_products_detail', $insert_data);
	$detail_id = $DB->insert_id();
	//产品自动添加下一期
	$updata1 = array(
	    'qishu'=>$rsProducts['qishu']+1,
		'canyurenshu'=>0,
	);
	$Flag = $DB->Set('cloud_products',$updata1,'where Users_ID="'.$UsersID.'" and Products_ID='.$rsProducts['Products_ID'].' and Products_SoldOut=0');
	
	$Flag = $Flag && $DB->query('UPDATE `cloud_shopcodes` SET s_codes_tmp=s_codes where s_id = '.$rsProducts['Products_ID']);
	if($Flag){
		require_once(CMS_ROOT.'/include/library/weixin_message.class.php');
		$weixin_message = new weixin_message($DB,$UsersID,$UserID);
		$contentStr = '恭喜，您获得了本期商品：'.$rsProducts['Products_Name'].'  <a href="http://'.$_SERVER["HTTP_HOST"].'/api/'.$UsersID.'/cloud/member/products/order/'.$detail_id.'/">点击领取</a>';
		$weixin_message->sendscorenotice($contentStr);
		$data = array(//没有数据可加载
			'status' => 1,
			'detail_id' => $detail_id
		);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		exit;
	}
}elseif($action == 'category'){
	$CategoryID = $_POST['cid'];
	$condition = '';
	if($BizID){
	    $condition = ' AND Biz_ID = '.$BizID;
	}

	if($CategoryID){
		$where = "where Users_ID='".$UsersID."' {$condition} and Products_Category=".$CategoryID." and Products_SoldOut=0 and ROUND(canyurenshu/zongrenci,2)<1";
	}else{
		$where = "where Users_ID='".$UsersID."' {$condition} and Products_SoldOut=0 and ROUND(canyurenshu/zongrenci,2)<1";
	}

	$counts = $DB->GetRs("cloud_products","count(Products_ID) as count",$where);
	$num = 20;//每页记录数
	$p = !empty($_POST['p'])?intval(trim($_POST['p'])):1;
	$total = $counts['count'];//数据记录总数
	$totalpage = ceil($total/$num);//总计页数
	$limitpage = ($p-1)*$num;//每次查询取记录
	$where .= " order by Products_Order asc, Products_CreateTime asc limit $limitpage,$num";
	$rsProducts = $DB->get("cloud_products","Products_Name,Products_ID,Products_IsVirtual,Products_IsShippingFree,Products_Weight,Products_JSON,Products_PriceX,Products_PriceY,qishu,canyurenshu,zongrenci,Products_xiangoutimes",$where);
	$products = handle_product_list($DB->toArray($rsProducts));
	if(count($products) > 0){
		$data = array(
			'list' => $products,
			'totalpage' => $totalpage,
		);
	}else{
		$data = array(//没有数据可加载
			'list' => '',
			'totalpage' => $totalpage,
		);
	}
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
	exit;
}
?>