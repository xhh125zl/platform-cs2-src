<?php
/*导出表格处理文件*/
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/update/common.php');
require_once(CMS_ROOT.'/include/library/outputExcel.php');
require_once(CMS_ROOT.'/include/helper/balance.class.php');
$balance = new balance($DB,$UsersID);

$type = $_REQUEST['type'];

if($type == 'product_gross_info'){
	$table = 'shop_products';
	$fields  = '*';
	$condition = "where Users_ID='{$UsersID}'";
	if($_GET['action'] != 'outproduct'){
	if($_GET['Keyword']){
		$condition .= " and Products_Name like '%".$_GET['Keyword']."%'";
	}	
	if($_GET['SearchCateId']>0){
		$catids = ','.$_GET['SearchCateId'].',';
		$condition .= " and Products_Category like '%".$catids."%'";
	}
	if($_GET["Attr"]){
		$condition .= " and Products_".$_GET["Attr"]."=1";
	}
	if($_GET["Status"]){
		$condition .= " and Products_Status=".$_GET["Attr"];
	}
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
		$data[$key] = $item;
	}
	$outputExcel = new OutputExcel();
	$outputExcel->product_gross_info($data);
	
}elseif($type == 'order_detail_list'){
	
	$condition = "where Users_ID='{$UsersID}' AND Biz_ID={$BizID} and (Order_Type='pintuan' or Order_Type='dangou')";

	if(!empty($_GET["Keyword"])){
		$condition .= " and Order_CartList like '%".$_GET["Keyword"]."%'";
	}
	if(isset($_GET["Status"])){
		if($_GET["Status"]<>-1){
			$condition .= " and Order_Status=".$_GET["Status"];
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
		
		
		if(is_numeric($item['Address_Province'])){
			$area_json = read_file(CMS_ROOT.'/data/area.js');
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
	$outputExcel->order_pintuan_list($beginTime,$endTime,$list);
	
}elseif($type == 'order_staticstic_list'){   /*edit数据统计20160405--start--*/
	
	$condition = "where Users_ID='{$UsersID}' and Order_Type='shop'";
        

	if(!empty($_GET["Keyword"])){
		$condition .= " and Order_CartList like '%".$_GET["Keyword"]."%'";
	}
	if(isset($_GET["status"])){
		if($_GET["status"]<>''){
			$condition .= " and Order_Status=".$_GET["status"];
		}
	}
	
	if(!empty($_GET["starttime"])){
		$condition .= " and Order_CreateTime>=".strtotime($_GET["starttime"]."00:00:00");
	}
	if(!empty($_GET["stoptime"])){
		$condition .= " and Order_CreateTime<=".strtotime($_GET["stoptime"]."23:59:59");
	}

	$beginTime = !empty($_GET["starttime"])?$_GET["starttime"]:'';
	$endTime  = !empty($_GET["stoptime"])?$_GET["stoptime"]:'';
	
	$resource = $DB->get("user_order","*",$condition);
	$list = $DB->toArray($resource);
	 
        $ds_list = Dis_Account::with('User')
            ->where(array('Users_ID' => $UsersID))
            ->get(array('Users_ID', 'User_ID', 'invite_id', 'User_Name', 'Account_ID', 'Shop_Name','Account_CreateTime'))
            ->toArray();
        $ds_list_dropdown = array();
        foreach($ds_list as $key=>$item){
                if(!empty($item['user'])){
                        $ds_list_dropdown[$item['User_ID']] = $item['user']['User_NickName'];
                }
        }
	foreach($list as $key=>$item){
		
		
		if(is_numeric($item['Address_Province'])){
			$area_json = read_file(CMS_ROOT.'/data/area.js');
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
		$item['dis_Name']=$ds_list_dropdown[$item['User_ID']];
		$item['receiver_address'] = $Province.$City.$Area.$item['Address_Detailed'];
		$item['Order_CartList'] = json_decode(htmlspecialchars_decode($item['Order_CartList']),TRUE);
		$list[$key] = $item;
	} 
	/*edit数据统计20160405--end--*/
	$outputExcel = new OutputExcel();
	$outputExcel->order_staticstic_list($beginTime,$endTime,$list);
/*edit数据统计20160415--start--*/	
}elseif($type == 'user_staticstic_list'){
  
    $condition = "where Users_ID='{$UsersID}' and Order_Type='shop'";
    if(!empty($_GET["Keyword"])){
            $condition .= " and Order_CartList like '%".$_GET["Keyword"]."%'";
    }
    if(isset($_GET["status"])){
            if($_GET["status"]<>''){
                    $condition .= " and Order_Status=".$_GET["status"];
            }
    }
    if(!empty($_GET["starttime"])){
            $condition .= " and Order_CreateTime>=".strtotime($_GET["starttime"]."00:00:00");
    }
    if(!empty($_GET["stoptime"])){
            $condition .= " and Order_CreateTime<=".strtotime($_GET["stoptime"]."23:59:59");
    }
    $beginTime = !empty($_GET["starttime"])?$_GET["starttime"]:'';
    $endTime  = !empty($_GET["stoptime"])?$_GET["stoptime"]:'';
    if(!empty($_GET['param'])){
        
        $results = json_decode($_GET['param'],true);   
        $outputExcel = new OutputExcel();
        if($_GET['searchtype']=="shop"){
            $shoptype = $_GET['SearchType'];
            $outputExcel->user_staticstic_list($beginTime,$endTime,$results,'shop',$shoptype);
        }elseif($_GET['searchtype']=="visit"){
            $outputExcel->user_staticstic_list($beginTime,$endTime,$results,'visit');
        }elseif($_GET['searchtype']=="sales"){
            $outputExcel->sales_staticstic_list($beginTime,$endTime,$results,'sales');
        }elseif($_GET['searchtype']=="commission"){
            $outputExcel->sales_staticstic_list($beginTime,$endTime,$results,'commission');
        }elseif($_GET['searchtype']=="user"){
            $userType = $_GET['SearchType'];
            if($userType == 'purchase'){
                foreach($results as $k=>$v){
                    foreach($v as $ks=>$vs){
                        $User_ID = $vs['User_ID'];
                        $conditon_user = "where Users_ID='{$UsersID}' and User_ID>=".$User_ID;
                        $user_array[$k][$ks] = $DB->getrs('user',"User_ID,User_No,User_Mobile,User_CreateTime,User_NickName",$conditon_user);
                    }
                }
                $results = $user_array;
            }
            $outputExcel->user_staticstic_list($beginTime,$endTime,$results,$userType);
        }elseif($_GET['searchtype']=="distributer"){
            $outputExcel->user_staticstic_list($beginTime,$endTime,$results,'distributer');
        }elseif($_GET['searchtype']=="area"){
             $outputExcel->area_staticstic_list($results);
        }
    }else{
        echo '<script language="javascript">alert("无数据可导出");history.back();</script>';
    }
}elseif($type == 'product_staticstic_list'){
    $condition = "where Users_ID='{$UsersID}'"; 
    $result = $DB->get("shop_products","Products_ID,Users_ID,Products_Name,Products_Count,Products_Sales,click_count",$condition);
    while($pro_array = $DB->fetch_assoc($result)){ 
        $pro_arrays[] = $pro_array;//产品
    }
    $condition = "where Users_ID = '{$UsersID}' group by  Product_ID"; 
    $Commit = $DB->Get("user_order_commit","AVG(Score) as avgcore,Product_ID",$condition);
    while($rsCommit=$DB->fetch_assoc($Commit)){
        $rsCommit_array[] = $rsCommit;
    }
    if(isset($rsCommit_array)){
        foreach ($rsCommit_array as $k=>$v){
            $newCommit[$v['Product_ID']] = $v['avgcore'];//评分
        }
    }
    
    $conditionSale = "where Users_ID = '{$UsersID}' and order_status = 4";
    $arrayOrder = $DB->Get('user_order','Order_CartList',$conditionSale);
    while($resultOrder=$DB->fetch_assoc($arrayOrder)){
       $resultOrder_array[]=json_decode($resultOrder['Order_CartList'],true);
    }
    if(!empty($resultOrder_array)){
        foreach($resultOrder_array as $k => $v){
            foreach ($v as $ks=>$vs){
                foreach($vs as $ks1=>$vs1){
                    $product_sale[$ks][] = $vs1['ProductsPriceX']*$vs1['Qty'];
                }
            }
        } 
        if(!empty($product_sale)){
            foreach($product_sale as $k =>$v){
                $product_sales[$k] = array_sum($v);//商品销量
            }
        }
    }
    
$conditionBack = "where Users_ID = '{$UsersID}' and Back_Status = 4";
$arrayBack = $DB->Get('user_back_order','Back_Qty,ProductID',$conditionBack);
while($resultBack=$DB->fetch_assoc($arrayBack)){
   $resultBack_array[]=$resultBack;
}
if(!empty($resultBack_array)){
    foreach($resultBack_array as $k => $v){
        $ProductIDBack = $v['ProductID'];
        $product_back[$ProductIDBack][] = $v['Back_Qty'];
    }
    if(!empty($product_back)){
        foreach($product_back as $k =>$v){
            $product_backs[$k] = array_sum($v);//商品退货
        }
    }
}

    if(isset($pro_arrays)){
        foreach($pro_arrays as $k=>$v){
            $Products_ID = $v['Products_ID'];
            if(!empty($newCommit["$Products_ID"])){
               $pro_arrays[$k]['agv'] =$newCommit["$Products_ID"];
            }else{
               $pro_arrays[$k]['agv'] ='';
            } 
            $pro_arrays[$k]['sale'] = isset($product_sales[$Products_ID])?"$product_sales[$Products_ID]":'0';
            $pro_arrays[$k]['back'] = isset($product_backs[$Products_ID])?"$product_backs[$Products_ID]":'0';
        }
	$outputExcel = new OutputExcel();
        $outputExcel->product_staticstic_list($pro_arrays);
    }else{
        echo '<script language="javascript">alert("无数据可导出");history.back();</script>';
    }
}
/*edit数据统计20160415--end--*/