<?php

/**
 *获取快递公司列表
 */
function get_shipping_company($UsersID,$status = null,$Biz_ID){
    global $DB1; 
	
	$condition = "where Users_ID='".$UsersID."' and Biz_ID =".$Biz_ID;
	
	if(!empty($status)){
		$condition .= ' and Shipping_Status ='.$status;
	}
	
	$condition .= " order by Shipping_CreateTime";

	$rsShippings = $DB1->get("shop_shipping_company","*",$condition);
	$shipping_list = $DB1->toArray($rsShippings);
	
	$result = false;
	if(!empty($shipping_list)){
		foreach($shipping_list as $key=>$shipping){
			$shipping_list[$key]['Shipping_Business'] = explode(',',$shipping['Shipping_Business']);
		}
		
		$result = $shipping_list;
	}

	return $result;      
}

/**
 * 后台 指定包邮条件html
 * @param  Smarty $smarty       Smarty对象
 * @param  Array $free_content  运费模板包邮条件设置
 * @return Sting  $html         
 */
function build_shipping_free_html($smarty,$free_content){
	
	$free_content = json_decode($free_content,true);
	
	$smarty->assign('free_content',$free_content);
	$html = $smarty->fetch('shipping_free_setting.html');
	
	return $html;
}

/**
 * 获取快递区域列表
 *
 */
function build_shipping_section_html($smarty,$UsersID,$Shipping_id,$By_Method,$template_content = null){
	

	global $DB1;
	$condition = "where Users_ID='".$UsersID."' and Shipping_ID=".$Shipping_id;
	$rsShippingCompany = $DB1->getRs('shop_shipping_company','*',$condition);

	$Business_Name_List = array('express'=>'快递','common'=>'平邮');
	
	$by_method_names = array('by_qty'=>'件数','by_weight'=>'重量','by_volume'=>'体积');
    $by_method_units = array('by_qty'=>'件','by_weight'=>'kg','by_volume'=>'m³');
	
	$Business_List = array();
	$selected_business = explode(',',$rsShippingCompany['Shipping_Business']);
	foreach($selected_business as $item){
		$Business_List[$item] = $Business_Name_List[$item];
	}
	
	
	$method_name = $by_method_names[$By_Method];
	$unit = $by_method_units[$By_Method];
	$smarty->assign('business_alias','express');
	$smarty->assign('By_Method',$By_Method);
	$smarty->assign('method_name',$method_name);
	$smarty->assign('unit',$unit);
	$smarty->assign('Business_List',$Business_List);
	
	
	if(!empty($template_content)){
		$template_content = json_decode($template_content,TRUE);
		$template_content = organize_template_content($template_content);
	}
	
	
	$smarty->assign('template_content',$template_content);

	$shipping_tpl_item = $smarty->fetch('shipping_tpl_item.html');
	
	
	return $shipping_tpl_item;
}

/**
 *处理物流模板数据
 */
 
function handle_template_post_data($UsersID,$Biz_ID,$input){

	
	$data = array();
	$data['Users_ID'] = $UsersID;
	$data['Template_Name'] = $input['Template_Name'];
	$data['Shipping_ID'] = $input['Shipping_ID'];
	$data['By_Method'] = $input['By_Method'];
	$data['Biz_ID'] = $Biz_ID;
	
	$Business_Type = array('express');
	
	$template_content = array();
	foreach($Business_Type as $key=>$item){
		$template_content[$item] = find_postage($item,$input);
	}

	$data['Template_Content'] = json_encode($template_content,JSON_UNESCAPED_UNICODE);	

	return $data;
}	

/**
 *找出input数组中与特定业务有关的字段
 *@method by_method
 *
 */
function find_postage($business,$input){
	
	$data = array();
	foreach($input as $key=>$item){
	
		if(strstr($key,$business.'_')){
			$pics = explode('_',$key);
				
			if(count($pics) == 2){
				//默认的配置
				$data['default'][$pics[1]] = $item;
				
			}elseif(count($pics) == 3){
				//指定区域的配置
				$data[$pics[2]][$pics[1]] = $item;
			}
		}
	}
	



	return $data;
}

/**
 *整理运费模板输出数据
 */
function organize_template_content($template_content){

	
	foreach($template_content as $business=>$content){
		
		$new_content = array();
		$new_content['default'] = $content['default'];
		unset($content['default']);
      
		if(count($content) > 0 ){
			$new_content['except'] = true;
			$new_content['specify'] = $content;
		}else{
			$new_content['except'] = false;
		}
		
	
		$template_content[$business] = $new_content;
		
	}
	return $template_content;
	
}	
/**
 *获取此物流模板描述信息
 */
function get_template_desc($template_content){
	return $template_content;
}

/**
 *处理运费模板免费信息
 */
function handle_shipping_free_data($input){
	$free_data = array();

	foreach($input['areas'] as $key=>$item){
		$setting['areas'] = $item;
		$setting['areas_desc'] = $input['areas_desc'][$key];
		$setting['trans_type'] = 'express';
		$setting['designated']  =$input['designated'][$key];
        $setting['prefrrendial_qty'] = !empty($input['preferentialQty'][$key])?$input['preferentialQty'][$key]:0;
		$setting['prefrrendial_money'] = !empty($input['preferentialMoney'][$key])?$input['preferentialMoney'][$key]:0;
		
		$free_data[] = $setting;
	}
	
	
	return json_encode($free_data,JSON_UNESCAPED_UNICODE);
	
}


/**
 * 获取前台快递公司列表，此快递公司可用，且含有默认模板
 * @param  String $UsersID 
 * @param  Array  $rsConfig 店铺配置
 * @return Array  $shipping_company_dropdown 以快递公司shipping_id为键，以快递公司名为值的一列数组
 */
function get_front_shiping_company_dropdown($UsersID, $BizConfig) {
	if(empty($BizConfig['Shipping'])) {
		return false;
	}else {
		$Biz_Config = json_decode($BizConfig['Shipping'], TRUE);
		$shipping_ids = array_keys($Biz_Config);
		$condition = array(
		    'Users_ID' => $UsersID,
			'Biz_ID' => $BizConfig['Biz_ID'],
			'Shipping_ID' => $shipping_ids
		);
		$shipping_list = model('shop_shipping_company')->field('Shipping_ID,Shipping_Name')->where($condition)->select();
		$biz_company_dropdown = array();
		
		foreach($shipping_list as $key => $item){
			$biz_company_dropdown[$item['Shipping_ID']] = $item['Shipping_Name'];
		}
		return $biz_company_dropdown;
	}
}
function organize_shipping_id($shipping_id) {
	$Shipping_IDS = array ();
	foreach ( $shipping_id as $key => $item ) {
		$Shipping_IDS [$item ['Biz_ID']] = $item ['Shipping_ID'];
	}
	return $Shipping_IDS;
}