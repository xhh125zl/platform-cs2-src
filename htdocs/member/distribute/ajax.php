<?php  
//网站后台微商城ajax 操作
ini_set("display_errors","On");
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/shipping.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_products.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/smarty.php');
require_once(BASEPATH.'/include/support/order_helpers.php');

//设置smarty
$smarty->left_delimiter = "{{";
$smarty->right_delimiter = "}}";
$template_dir = $_SERVER["DOCUMENT_ROOT"].'/member/shop/html';
$smarty->template_dir = $template_dir;

$UsersID = $_SESSION['Users_ID'];

$action = $_REQUEST['action'];
if($action == 'reject_withdraw'){//申请提现驳回
	$Record_ID = $_POST['Record_ID'];

	//获取此次提现记录内容
	$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Record_ID='".$Record_ID."'";
	$rsRecord = $DB->GetRs("distribute_withdraw_record","Record_Money,User_ID,Record_Status",$condition);
	if($rsRecord["Record_Status"]==2){
		$response = array("status"=>0,"msg"=>'已驳回,请勿重复驳回');
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	//将此次提现状态改为驳回
	$data = array("Record_Status"=>2,"No_Record_Desc"=>$_POST['Reject_Reason']);
	$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Record_ID='".$Record_ID."'";
	$Flag = $DB->Set("distribute_withdraw_record",$data,$condition);
		
	//将钱退回
	$money = $rsRecord['Record_Money'];
	$condition = "where User_ID=".$rsRecord['User_ID']." and Users_ID='".$_SESSION["Users_ID"]."'";
	$rsAccount = $DB->GetRs("distribute_account","balance",$condition);
	$Data = array(
		"balance"=>$rsAccount["balance"]+$money
	);
	$SET = $DB->set('distribute_account',$Data,$condition);

	if($Flag&$SET){
		$response = array("status"=>1,"msg"=>"驳回申请成功");
	}else{
		$response = array("status"=>0,"msg"=>'驳回用户提现申请失败，出现未知错误');
	}

	echo json_encode($response,JSON_UNESCAPED_UNICODE);
}elseif($action == 'protitle'){
	$accountid = $_POST["AccountID"];
	$value = $_POST["Value"];
	$Flag = $DB->Set("distribute_account","Professional_Title=".$value,"where Account_ID=".$accountid);
	if($Flag){
		$Data=array("status"=>1);
	}else{
		$Data=array("status"=>0,"msg"=>"修改失败");
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}else if($action == 'get_dis_agent_form'){	
	$account_id = $_GET['account_id'];
	$region_list = Area::getRegionList(array(35));
	$area_json = read_file(BASEPATH.'/data/areagent.js');
	$area_array = json_decode($area_json,TRUE);
	$province_list = $area_array[0];

	//获取代理地区设置信息
	$agent_areas = Dis_Agent_Area::where('Users_ID',$UsersID)
	               ->get();    
	$agent_provinces = $agent_areas->where('type',1)->map(function($agent_area){
    				              	return $agent_area->toArray();
    				              })->all();
	$agent_citys = $agent_areas->where('type',2)->map(function($agent_area){
    				              	return $agent_area->toArray();
    				              })->all();
	/*edit in 20160408*/							  
	$agent_countys = $agent_areas->where('type',3)->map(function($agent_area){
    				              	return $agent_area->toArray();
    				              })->all();
    				             
	$agent_provinces_dropdown = get_dropdown_list($agent_provinces,'area_id');
	$agent_citys_dropdown = get_dropdown_list($agent_citys,'area_id');	
	/*edit in 20160408*/
	$agent_countys_dropdown = get_dropdown_list($agent_countys,'area_id');
	//整理出属于此分销商的代理地区以及已被占用的分销地区
	$province_checked = array();
	$province_disabled = array();
	$city_checked = array();	
	$city_disabled = array();
	$county_checked = array();
	$county_disabled = array();
	
    foreach($agent_provinces_dropdown as $area_id=>$agent_area){
    	if($agent_area['Account_ID'] == $account_id ){
    		$province_checked[] = $area_id;
    	}else{
    		$province_disabled[] = $area_id;
    	}
    }
    
    foreach($agent_citys_dropdown as $area_id=>$agent_area){
    	if($agent_area['Account_ID'] == $account_id ){
    		$city_checked[] = $area_id;
    	}else{
    		$city_disabled[] = $area_id;
    	}
    }
	/*edit in 20160408*/
	foreach($agent_countys_dropdown as $area_id=>$agent_area){
    	if($agent_area['Account_ID'] == $account_id ){
    		$county_checked[] = $area_id;
    	}else{
    		$county_disabled[] = $area_id;
    	}
    }
    $province_data_list = array();
    $city_data_list = array();
	$county_data_list = array();
	$provinc_lists = array();
    
    foreach($province_list as $province_id=>$Province_Name){
    	
    	$province = array();
    	$province['checked'] = in_array($province_id,$province_checked);
    	$province['disabled'] = in_array($province_id,$province_disabled);
    	$province['Province_Name'] = $Province_Name;
    	$selectd_city_num = 0;
		$disable_city_num = 0;
    	
    	$city_list = $area_array['0,'.$province_id];
    	
    	if(count($city_list) > 0 ){
    	    foreach($city_list as $city_id=>$City_Name){
				if(in_array($city_id,$city_checked)){
					$city['checked'] = true;
					$selectd_city_num++;
				}else{
					$city['checked'] = false;
				}
				
				if(in_array($city_id,$city_disabled)){
					$city['disabled'] = true;
					$disable_city_num++;
				}else{
					$city['disabled'] = false;
				}
    	    	$city['City_Name'] = $City_Name;    	    	
				/*edit in 20160408*/
				$selectd_county_num = 0;
				$disable_county_num = 0;				
				$county_list = isset($area_array['0,'.$province_id.','.$city_id])?$area_array['0,'.$province_id.','.$city_id]:array();				
    	if(count($county_list) > 0 ){
			$provinc_lists[$province_id][] = $city_id;
    	    foreach($county_list as $county_id=>$County_Name){
				if(in_array($county_id,$county_checked)){
					$county['checked'] = true;
					$selectd_county_num++;
				}else{
					$county['checked'] = false;
				}
				
				if(in_array($county_id,$county_disabled)){
					$county['disabled'] = true;
					$disable_county_num++;
				}else{
					$county['disabled'] = false;
				}
    	    	
    	    	
    	    	$county['County_Name'] = $County_Name;
    	    	$county_data_list[$province_id][$city_id][$county_id] = $county;
    	    }
    				
    	}	
		$city['selectd_county_num'] = $selectd_county_num;
		$city['disable_county_num'] = $disable_county_num;
		$city_data_list[$province_id][$city_id] = $city;
    	    }
    				
    	}
		
		$province['selectd_city_num'] = $selectd_city_num;
		$province['disable_city_num'] = $disable_city_num;
		$province_data_list[$province_id] = $province;
    }
	
	//模板赋值
	$smarty->assign('account_id',$account_id);
	$smarty->assign('region_list',$region_list);
	$smarty->assign('province_data_list',$province_data_list);
	$smarty->assign('city_data_list',$city_data_list);
	$smarty->assign('county_data_list',$county_data_list);
	$smarty->assign('provinc_lists',$provinc_lists);
	$content = $smarty->fetch('dis_agent_form.html');
	$response = array('status'=>1,'content'=>$content,'msg'=>'获取信息成功');
	echo json_encode($response,JSON_UNESCAPED_UNICODE);
}else if($action == 'save_dis_agent_area'){	
	//JProvinces 属于省份设置	
	$JProvinces = !empty($_POST['JProvinces'])?$_POST['JProvinces']:array();
	$KCitys = !empty($_POST['KCitys'])?$_POST['KCitys']:array();
	$KCountys = !empty($_POST['KCountys'])?$_POST['KCountys']:array();
	
	$Account_ID = $_POST['account_id'];
	$Area_Province_Agent = $Area_City_Agent = $Area_County_Agent =  array();

	$pinyin = new Utf8pinyin();
	
	foreach($JProvinces as $key=>$province){
		list($Province_ID,$Province_Name) = explode('_',$province);
		$row = array(
		             'area_name'=>$Province_Name,
					 'area_id'=>$Province_ID,	
				      'type'=>1
					  );					  
		$Area_Province_Agent[$Province_ID]  = $row ;
	}
	
	foreach($KCitys as $key=>$city){
		
		list($City_ID,$City_Name) = explode('_',$city);
		//去掉市字
		$length = mb_strlen($City_Name,'utf-8');
		$City_Name = mb_substr($City_Name,0,$length-1,'utf-8');
	
		$area_name = $City_Name;
		$row = array(
				'area_name'=>$City_Name,
				'area_id'=>$City_ID,
				'type'=>2
		);
		$Area_City_Agent[$City_ID]  = $row ;
	}
	
	foreach($KCountys as $key=>$county){
		
		list($County_ID,$County_Name) = explode('_',$county);
		//去掉县或区字
		$length = mb_strlen($County_Name,'utf-8');
		$County_Name = mb_substr($County_Name,0,$length-1,'utf-8');
	
		$area_name = $County_Name;
		$row = array(
				'area_name'=>$County_Name,
				'area_id'=>$County_ID,
				'type'=>3
		);
		$Area_County_Agent[$County_ID]  = $row ;
	}
	
	$Area_Agent = array_merge($Area_Province_Agent,$Area_City_Agent,$Area_County_Agent);
	$Area_Agent = get_dropdown_list($Area_Agent,'area_id');
	$area_id_list = array_keys($Area_Agent);
	
	//取得此用户原有省级代理数据
	$sql_id_list = Dis_Agent_Area::where('account_id',$Account_ID)
	              ->get(array('area_id'))
				  ->map(function($row){
					   return $row->area_id;
				  })->all();

	$res = sql_diff($area_id_list,$sql_id_list);
	
	//进行更新
	if(count($res['need_update'])>0){
		
		foreach($res['need_update'] as $key=>$area_id){
		
			Dis_Agent_Area::Multiwhere(array('Users_ID'=>$UsersID,'area_id'=>$area_id))
			               ->update(array('Account_ID'=>$Account_ID));
		}
	}
	
	$add_datat = array();
	//新增数据
	if(count($res['need_add'])>0){
		$add_data = array();		
		foreach($res['need_add'] as $key=>$area_id){
			$dis_agent_area = new Dis_Agent_Area();
			$dis_agent_area->type = $Area_Agent[$area_id]['type'];;
			$dis_agent_area->Users_ID = $UsersID;
			$dis_agent_area->Account_ID = $Account_ID;
			$dis_agent_area->area_id = $area_id;
			$dis_agent_area->area_name = $Area_Agent[$area_id]['area_name'];
			$dis_agent_area->create_at = time();
			$dis_agent_area->status = 1;
			$add_data[] = $dis_agent_area;
		}
		$dis_acount = Dis_Account::find($Account_ID);
		$dis_acount->disAreaAgent()->saveMany($add_data);
	}
	
	//删除数据
	if(count($res['need_del'])>0){
			
		Dis_Agent_Area::where('account_id',$Account_ID)
				   ->whereIn('area_id',$res['need_del'])
				   ->delete();
	}
			$dis_agent_tie = new Dis_Agent_Tie();
			$dis_agent_tie->type = 1;
			$dis_agent_tie->Users_ID = $UsersID;
			$dis_agent_tie->Disname = $Account_ID;			
			$dis_agent_tie->Barjson = json_encode($res,JSON_UNESCAPED_UNICODE);
			$dis_agent_tie->Order_CreateTime = time();			
			$add_datat[] = $dis_agent_tie;
	$dis_tie = Users::find($UsersID);
	$dis_tie->disAreaAgenta()->saveMany($add_datat);
	
	$response = array('status'=>1);
    echo json_encode($response,JSON_UNESCAPED_UNICODE);
}else if($action == 'get_dis_level'){
	$type = $_GET['type'];
	$arr = array('一','二','三','四','五','六','七','八','九','十');
	$_TYPE = array('直接购买','消费额','购买商品','无门槛');
	$lists = get_dis_level($DB,$UsersID);
	$html = '<table width="100%" cellpadding="0" cellspacing="0">';
	$i = 0;
	if($type==0){//直接购买
		$html .= '<tr>
            <th width="10%" nowrap="nowrap">序号</th>
            <th width="18%" nowrap="nowrap">级别名称</th>
            <th width="18%" nowrap="nowrap">门槛</th>
            <th width="18%" nowrap="nowrap">价格</th>
            <th width="18%" nowrap="nowrap">人数限制</th>
            <th width="18%" nowrap="nowrap" class="last">佣金明细</th>
          </tr>';
		  foreach($lists as $key=>$value){
			  $i++;
			  $PeopleLimit = json_decode($value['Level_PeopleLimit'],true);
			  if($i==1 && $value['Level_LimitType']==3){//第一个无门槛
			  	  $Distributes = array();
			  	  $value["Level_LimitValue"] = '';
			  }elseif($value['Level_LimitType']!=0){
				  $Distributes = array();
				  $value["Level_LimitValue"] = '';
			  }else{
				  $Distributes = json_decode($value['Level_Distributes'],true);
			  }
			  $html .= '<tr><td nowrap="nowrap">'.$i.'</td>
            <td nowrap="nowrap">'.$value["Level_Name"].'</td>
            <td nowrap="nowrap">'.($i==1 ? $_TYPE[$value["Level_LimitType"]] : $_TYPE[$type]).'</td>
            <td nowrap="nowrap"><font style="color:#F60">'.$value["Level_LimitValue"].'</font></td>
            <td nowrap="nowrap">';
              foreach($PeopleLimit as $k=>$v){
			  	  if($k>1){
					 $html .= '<br />';
				  }
				  $html .= $arr[$k-1].'级&nbsp;&nbsp;';
				  if($v==0){
					  $html .= '无限制';
				  }elseif($v==-1){
					  $html .= '禁止';
				  }else{
					  $html .= $v.'&nbsp;个';
				  }
			  }
              $html .= '</td>
            <td nowrap="nowrap" class="last">';
              foreach($Distributes as $k=>$v){
				  if($k>1){
					  $html .= '<br />';
				  }
				  $html .= $arr[$k-1].'级&nbsp;&nbsp;'.$v.'&nbsp;元';
			  }
              $html .='</td></tr>';
		  }
	}elseif($type==1){//消费额
		$html .= '<tr>
            <th width="12%" nowrap="nowrap">序号</th>
            <th width="22%" nowrap="nowrap">级别名称</th>
            <th width="22%" nowrap="nowrap">门槛</th>
            <th width="22%" nowrap="nowrap">消费额</th>
            <th width="22%" nowrap="nowrap" class="last">人数限制</th
          </tr>';
		  foreach($lists as $key=>$value){
			  $i++;
			  $PeopleLimit = json_decode($value['Level_PeopleLimit'],true);
			  if($i==1 && $value['Level_LimitType']==3){//第一个无门槛
				  $limit = '';
			  }elseif($value['Level_LimitType']!=1){
				  $limit = '';
			  }else{
				  $limit_arr = explode('|',$value['Level_LimitValue']);
				  $limit = $limit_arr[0]==0 ? '商城总消费'.$limit_arr[1].'元' : '一次性消费'.$limit_arr[1].'元'; 
			  }
			  $html .= '<tr><td nowrap="nowrap">'.$i.'</td>
            <td nowrap="nowrap">'.$value["Level_Name"].'</td>
            <td nowrap="nowrap">'.($i==1 ? $_TYPE[$value["Level_LimitType"]] : $_TYPE[$type]).'</td>
            <td nowrap="nowrap"><font style="color:#F60">'.$limit.'</font></td>
            <td nowrap="nowrap" class="last">';
              foreach($PeopleLimit as $k=>$v){
			  	  if($k>1){
					 $html .= '<br />';
				  }
				  $html .= $arr[$k-1].'级&nbsp;&nbsp;';
				  if($v==0){
					  $html .= '无限制';
				  }elseif($v==-1){
					  $html .= '禁止';
				  }else{
					  $html .= $v.'&nbsp;个';
				  }
			  }
              $html .= '</td></tr>';
		  }
	}elseif($type==2){//购买商品
		$products = array();
		$DB->Get('shop_products','Products_ID,Products_Name','where Users_ID="'.$UsersID.'"');
		while($r = $DB->fetch_assoc()){
			$products[$r['Products_ID']] = $r['Products_Name'];
		}
		$html .= '<tr>
            <th width="12%" nowrap="nowrap">序号</th>
            <th width="22%" nowrap="nowrap">级别名称</th>
            <th width="22%" nowrap="nowrap">门槛</th>
            <th width="22%" nowrap="nowrap">商品</th>
            <th width="22%" nowrap="nowrap" class="last">人数限制</th
          </tr>';
		  foreach($lists as $key=>$value){
			  $i++;
			  $PeopleLimit = json_decode($value['Level_PeopleLimit'],true);
			  if($i==1 && $value['Level_LimitType']==3){//第一个无门槛
				  $limit = '';
			  }elseif($value['Level_LimitType']!=2){
				  $limit = '';
			  }else{
				  $limit_arr = explode('|',$value['Level_LimitValue']);
				  if($limit_arr[0]==0){//任意商品
				  	  $limit = '购买任意商品';
				  }else{
					  $limit = '购买以下商品：';
					  $pids = explode(',',$limit_arr[1]);
					  foreach($pids as $id){
						  $limit .= empty($products[$id]) ? '' : '<br />'.$products[$id];
					  }
				  } 
			  }
			  $html .= '<tr><td nowrap="nowrap">'.$i.'</td>
            <td nowrap="nowrap">'.$value["Level_Name"].'</td>
            <td nowrap="nowrap">'.($i==1 ? $_TYPE[$value["Level_LimitType"]] : $_TYPE[$type]).'</td>
            <td nowrap="nowrap"><font style="color:#F60">'.$limit.'</font></td>
            <td nowrap="nowrap" class="last">';
              foreach($PeopleLimit as $k=>$v){
			  	  if($k>1){
					 $html .= '<br />';
				  }
				  $html .= $arr[$k-1].'级&nbsp;&nbsp;';
				  if($v==0){
					  $html .= '无限制';
				  }elseif($v==-1){
					  $html .= '禁止';
				  }else{
					  $html .= $v.'&nbsp;个';
				  }
			  }
              $html .= '</td></tr>';
		  }
	}
	$html .= '</table>';
	echo json_encode(array("html"=>$html),JSON_UNESCAPED_UNICODE);
	exit;
}