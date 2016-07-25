<?php
/**
 *确定需要多少运费
 *
 */
function get_shipping_fee($UsersID, $Template_ID, $Business, $City_Code, $Biz_ID, $Products_Info) {
	
	$template = get_cur_shipping_template($UsersID, $Biz_ID, $Template_ID);

	if(!$template) {
	   $result = array('error'=>1,'msg'=>'没有找到相应的物流模板，请联系店主');
	   return $result;
	}
	
	$method = $template['by_method'];
	
	//是否符合免运费条件
	if (!empty($template['free_content'])) {
		//进行免运费处理
		$res = free_shipping_deal($template['free_content'], $Business, $City_Code, $Products_Info);
		//如果免运费
		if ($res) {
			return 0;
		}
	}

	//不符合免运费条件,计算具体运费
	$rule = array();
	$template_content = organize_template_content(json_decode($template['template_content'], TRUE));
	$business_content = $template_content[$Business];
	$specify_areas = array();

	//此运费模板存在特殊区域
	if (!empty($business_content['specify'])) {
		foreach ($business_content['specify'] as $key => $specify) {
			$citys = explode(',', $specify['areas']);
			if (in_array($City_Code, $citys)) {
				$rule = $specify;
				break;
			}
		}
	}
	
	//如果rule仍为空
	if (empty($rule)) {
		$rule = $business_content['default'];
	}

	//根据规则计算具体运费
	$weight = $Products_Info['weight'];
	$qty = $Products_Info['qty'];
	$money = $Products_Info['money'];
	$fee = 0;
	
	if ($template['by_method'] == 'by_weight') {
		
		if ($weight == 0) {
			$fee = 0;
		} elseif ($weight <= $rule['start']) {
			$fee = $rule['postage'];
		} else {
			$extra_weight = $weight - $rule['start'];
			
			$extra_fee = ceil($extra_weight / $rule['plus']) * $rule['postageplus'];
			$fee = $rule['postage'] + $extra_fee;
		}

	} elseif ($template['by_method'] == 'by_qty') {
		if ($qty == 0) {
			$fee = 0;
		} elseif ($qty <= $rule['start']) {
			$fee = $rule['postage'];
		} else {
			$extra_qty = $qty - $rule['start'];
			$extra_fee = ceil($extra_qty / $rule['plus']) * $rule['postageplus'];
			$fee = $rule['postage'] + $extra_fee;
		}
	}
	return $fee;
}

/**
 *确定此产品是否满足免运费条件
 */
function free_shipping_deal($free_content, $Business, $City_Code, $Products_Info) {

	$free_content = json_decode($free_content, true);

	//整理免运费条件
	$list_by_business = array();
	foreach ($free_content as $key => $item) {
		$list_by_business[$item['trans_type']][] = $item;
	}

	//如果不存在此业务的免费条款
	if (!array_key_exists($Business, $list_by_business)) {
		return false;
	} else {
		$regulartions = $list_by_business[$Business];
		$result = FALSE;

		foreach ($regulartions as $key => $regulation) {
			//是否在免运费城市之内
			if (!empty($regulation['areas'])) {
				$citys = explode(',', $regulation['areas']);
				//如果不在免运费城市中，继续循环
				if (!in_array($City_Code, $citys)) {
					continue;
				}
			}

			//根据优惠类型，进行具体决定
			//0 按件数,1 按金额,2 件数+金额
			if ($regulation['designated'] == 0) {

				if ($Products_Info['qty'] >= $regulation['prefrrendial_qty']) {
					$result = TRUE;
					break;
				}

			} elseif ($regulation['designated'] == 1) {

				$money = $Products_Info['money'];
				if ($money >= $regulation['prefrrendial_money']) {
					$result = TRUE;
					break;
				}

			} elseif ($regulation['designated'] == 2) {
				$money = $Products_Info['money'];

				if ($Products_Info['qty'] >= $regulation['prefrrendial_qty'] && $money >= $regulation['prefrrendial_money']) {
					$result = TRUE;
					break;
				}
			}
		}
	}
	return $result;
}

/**
 *确定使用的是哪一个物流模板
 *店铺配置参数
 *
 */
function  get_cur_shipping_template($UsersID, $Biz_ID, $Template_ID) {
	$condition = array(
	    'Users_ID'=>$UsersID,
		'Biz_ID'=>$Biz_ID,
		'Template_ID'=>$Template_ID
	);
	$result = model('shop_shipping_template')->where($condition)->find();
	return $result;
}

/**
 * 获取物流简述信息,包含可用快递公司信息，及其下属物流模板
 * @param  String $UsersID
 * @return Array  $brief  结构
 *
 */
function get_shipping_brief($UsersID, $Biz_ID) {

	
	global $DB1;
	//获得所有可用的快递公司
	$condition = "where Users_ID ='" . $UsersID . "' and Biz_ID = " . $Biz_ID . " and Shipping_Status = 1";
	$rsShippingCompanys = $DB1->get('shop_shipping_company', 'Shipping_ID,Shipping_Name,Cur_Template,Biz_ID', $condition);

	if (empty($rsShippingCompanys)) {
		return false;
	}

	$Shipping_List = $DB1->toArray($rsShippingCompanys);

	//获取这些快递公司下属的物流模板
	$brief['Shipping_List'] = $Shipping_List;

	//检索出快递公司Shipping_ID
	$Shipping_ID_List = array();
	foreach ($Shipping_List as $key => $company) {
		$Shipping_ID_List[] = $company['Shipping_ID'];
	}
	
	if(count($Shipping_ID_List) >0){
		$Shipping_ID_Str = implode(',', $Shipping_ID_List);
		$condition = "where Users_ID ='" . $UsersID . "'  and Biz_ID = " . $Biz_ID . " and Template_Status = 1" .
	" and  Shipping_ID in (" . $Shipping_ID_Str . ")";

		$rsTemplates = $DB1->get('shop_shipping_template', 'Template_ID,Template_Name,Shipping_ID', $condition);
	}else{
		$rsTemplates = false;
	}
	
	$Template_List = array();
	if ($rsTemplates) {
		$Template_List = $DB1->toArray($rsTemplates);
	}

	$Template_Dropdown = array();
	foreach ($Template_List as $key => $item) {
		$Template_Dropdown[$item['Shipping_ID']][] = $item;
	}

	$brief['Template_Dropdown'] = $Template_Dropdown;

	return $brief;

}


/*获取某供货商下运费*/
function get_biz_total_info($UsersID, $Biz_Cart, $Biz_ID, $Template_ID, $City_Code = 0) {
	$qty = 0;
	$weight = 0;
	$money = 0;
	$total = 0;

	foreach ($Biz_Cart as $Products_ID => $Products_List) {
		foreach ($Products_List as $k => $v) {
			if ($City_Code != 0) {
				if ($v['IsShippingFree'] == 0) {
					$qty += $v["Qty"];
					$weight += $v["Qty"] * $v["ProductsWeight"];
					$money += $v["Qty"] * $v["ProductsPriceX"];
				} else {
					$qty += 0;
					$weight += 0;
					$money += 0;
				}
			}
			$total += $v["Qty"] * $v["ProductsPriceX"];
		}

	}
	$Product_Info = array('qty'=>$qty, 'weight'=>$weight, 'money'=>$money);
   
	$Business = 'express';

	if ($City_Code == 0) {
		$total_shipping_fee = 0;
	} else {
		$total_shipping_fee = get_shipping_fee($UsersID, $Template_ID, $Business, $City_Code, $Biz_ID, $Product_Info);
		
		if(is_array($total_shipping_fee)){
		   
			return $total_shipping_fee;
		}
	}
	
	$total_info = array('total'=>$total, 'total_shipping_fee'=>$total_shipping_fee);
	return $total_info;
	
}

/**
 *获取订单（未入库前）产品总价
 *以及总运费
 */
function get_order_total_info($UsersID, $CartList, $Shipping_IDS, $City_Code = 0) {
    $Biz_ID_Arr = array_keys($CartList);
	$shopConfig = model('shop_config')->field('*')->where(array('Users_ID'=>$UsersID))->find();
	//获取每个供货商的运费默认配置
	$condition = array(
	    'Users_ID'=>$UsersID,
		'Biz_ID'=>$Biz_ID_Arr,
	);
	$Biz_Config_List = model('biz')->field('Biz_ID,Biz_Name,Shipping,Default_Shipping,Default_Business')->where($condition)->select();
	$Biz_Config_Dropdown = array();

	foreach($Biz_Config_List as $key => $item){
		$Biz_Config_Dropdown[$item['Biz_ID']] = $item; 
	}
	
    $total = 0;
	$total_shipping_fee = 0;
    foreach($CartList as $Biz_ID => $item){
		$Biz_Config = $Biz_Config_Dropdown[$Biz_ID];
		$Shipping_Config = json_decode($Biz_Config['Shipping'], TRUE);
		//如果未指定Shipping_ID,则使用默认配置
		if(empty($Shipping_IDS)) {
			$Shipping_ID = $Biz_Config['Default_Shipping'];
		}else {
			$Shipping_ID = isset($Shipping_IDS[$Biz_ID]) ? $Shipping_IDS[$Biz_ID] : $Biz_Config['Default_Shipping'];
		}
		$Biz_Default_Shipping = json_decode($Biz_Config['Shipping'], TRUE);

		$biz_company_dropdown = get_front_shiping_company_dropdown($UsersID, $Biz_Config);
		if(!empty($Biz_Default_Shipping[$Shipping_ID])) {
			$Template_ID = $Biz_Default_Shipping[$Shipping_ID];
			$Biz_Total_Info = get_biz_total_info($UsersID, $item, $Biz_ID, $Template_ID, $City_Code);	
			$Biz_Total_Info['Shipping_ID'] = $Shipping_ID;
			$Biz_Total_Info['Shipping_Name'] = $biz_company_dropdown[$Shipping_ID];			
		}else {
			$Biz_Total_Info['error'] = 1;
			$Biz_Total_Info['msg'] = '此商家，未指定运费模板，请联系店主。';
			$Biz_Total_Info['Shipping_ID'] = 0;
			$Biz_Total_Info['Shipping_Name'] = '';
			$Biz_Total_Info['total'] = 0;
			$Biz_Total_Info['total_shipping_fee'] = 0;
		}
		if(empty($Biz_Total_Info['error'])) {
			$total += $Biz_Total_Info['total'];
			if($shopConfig['NeedShipping']){ //如果平台没开启物流 则total_shipping_fee为零
			    $total_shipping_fee += $Biz_Total_Info['total_shipping_fee'];
			}
		}
		$Biz_Total_Info['Biz_Config'] = $Biz_Config_Dropdown[$Biz_ID];
		$order_total_info[$Biz_ID] = $Biz_Total_Info;
	}
	$order_total_info['total'] = $total;
	$order_total_info['total_shipping_fee'] = $total_shipping_fee;
        
        /*增加积分显示*/
        $man_list = $shopConfig['Man'];
        $man_array = json_decode($man_list, true);
        $order_total_info['integral'] = 0;
        if(!empty($man_array)){
            $order_total_info['man_array'] = $man_array;
            foreach ($man_array as $k => $v) {
                if ($total >= $v['reach']) {
                    $order_total_info['integral'] = $v['award'];
                    break;
                }
            }
        }
        
	return $order_total_info;
}

//计算参加满多少减多少活动后的金额,无需叠加性
function  man_act($sum, $regulation){
	$man_sum = $sum;
	$regulation =  array_reverse($regulation);
	foreach($regulation as $key=>$item){
		if($sum >= $item['reach']){
		    $man_sum = $sum - $item['award'];
			break;
		}
	}
	return $man_sum;
}

/**
 *获取可用优惠券
 **/
function get_useful_coupons($User_ID, $UsersID, $Biz_ID, $total_price) {

	global $DB1;
	$num = 0;
	$condition = "where User_ID=" . $User_ID . " and Users_ID='" . $UsersID . "' and Coupon_UseArea=1 and (Coupon_UsedTimes=-1 or Coupon_UsedTimes>0) and Coupon_StartTime<=" . time() . " and Coupon_EndTime>=" . time()." and Biz_ID=".$Biz_ID;
	
	$DB1->Get("user_coupon_record", "*", $condition);

	$lists = array();
	while ($rsCoupon = $DB1->fetch_assoc()) {
		if ($rsCoupon["Coupon_Condition"] <= $total_price) {
			if ($rsCoupon['Coupon_UseType'] == 0 && $rsCoupon['Coupon_Discount'] > 0 && $rsCoupon['Coupon_Discount'] < 1) {
				$lists[] = $rsCoupon;
				$num++;
			}
			if ($rsCoupon['Coupon_UseType'] == 1 && $rsCoupon['Coupon_Cash'] > 0) {
				$lists[] = $rsCoupon;
				$num++;
			}
		}
	}

	//完善优惠券信息
	if ($num > 0) {
		foreach ($lists as $k => $v) {
			$r = $DB1->GetRs("user_coupon", "Coupon_Subject", "where Coupon_ID=" . $v["Coupon_ID"]);
			$v["Subject"] = $r["Coupon_Subject"];
			if ($v['Coupon_UseType'] == 0 && $v['Coupon_Discount'] > 0 && $v['Coupon_Discount'] < 1) {
				$v["Subject"] .= '(可享受折扣' . ($v['Coupon_Discount'] * 10) . '折)';
			}
			if ($v['Coupon_UseType'] == 1 && $v['Coupon_Cash'] > 0) {
				$v["Subject"] .= '(可抵现金' . $v['Coupon_Cash'] . '元)';
			}
			$lists[$k] = $v;
		}

	}
	

	$coupon_info = Array();
	$coupon_info['num'] = $num;
	$coupon_info['lists'] = $lists;
	return $coupon_info;
}

/**
 *生成优惠券html
 */
function build_coupon_html($smarty, $coupon_info) {

	$coupon_html = '';
	if ($coupon_info['num'] > 0) {
		$lists = $coupon_info['lists'];
		foreach ($lists as $key => $item) {
			$lists[$key]['Price'] = $item["Coupon_UseType"] == 0 ? $item["Coupon_Discount"] : $item["Coupon_Cash"];
		}
		$smarty->assign('lists', $lists);
		$coupon_html = $smarty->fetch('order_coupon.html');
	}

	return $coupon_html;
}
/**
 * 用户积分加减与添加积分变动记录
 * @param  String $UsersID  
 * @param  int $User_Id  用户ID
 * @param  int $Integral 所变动积分多少
 * @param  String $type    变动类型 add与reduce
 * @param  String $desc    积分变动描述
 * @param  String $useless  真实积分变动，还是虚拟积分变动 TRUE ,真实积分 FALSE 真实积分
 * @return Bool           操作是否成功
 */
function change_user_integral($UsersID, $User_Id, $Integral, $type, $desc, $useless = TRUE){

	if($type == 'add') {
		$operate = '+';
	}else if($type == 'reduce') {
		$operate = '-';
	}
	$user_model = model('user');
	$rsUser = $user_model->field('User_Money,User_PayPassword,Is_Distribute,User_Name,User_NickName,Owner_Id,User_Integral,User_From')->where(array('Users_ID'=>$UsersID,'User_ID'=>$User_Id))->find();
	$user_model->beginTransaction();//开启事务处理
	if($useless) {
		$integral_data = 'User_UseLessIntegral' . $operate . $Integral;
	}else {
		$integral_data = 'User_Integral' . $operate . $Integral; 
	}
	$condition = array(
	    'Users_ID'=>$UsersID,
		'User_ID'=>$User_Id
	);
	$Flag_a = $user_model->where($condition)->update($integral_data);
	$integral_minus_data = array(
		'Record_Integral'=>$operate . $Integral,
		'Record_SurplusIntegral'=>$rsUser['User_Integral'] - $Integral,
		'Operator_UserName'=>'',
		'Record_Type'=>3,
		'Record_Description'=>$desc,
		'Record_CreateTime'=>time(),
		'Users_ID'=>$UsersID,
		'User_ID'=>$User_Id
	);
	$Flag_b = model('user_Integral_record')->insert($integral_minus_data);
	
	if($Flag_a && $Flag_b) {
		$weixin_message = new \shop\logic\weixin_message($UsersID, $User_Id);
		$contentStr = '购买商品抵用 ' . $Integral . ' 个积分';
		$weixin_message->sendscorenotice($contentStr);
		$user_model->commit();
		$result = TRUE;
	}else {
		$user_model->rollback();
		$result = FALSE;
	}
	return $result;
}
/**
 *将不可用积分返还到可用积分 
 *@param $UsersID
 *@param $UserID  用户ID
 *@param $Integral 移动积分量 
 */	
function remove_userless_integral($UsersID, $User_ID, $Integral){
	$condition = array(
	    'Users_ID'=>$UsersID,
		'User_ID'=>$User_ID
	);
	$Flag = model('user')->where($condition)->update('User_Integral=User_Integral+' . $Integral . ',' . 'User_UseLessIntegral=User_UseLessIntegral-' . $Integral);
	return $Flag;
}
//获取当前url
function get_cur_url($UsersID){
    $cur_url = SITE_URL . url('shop/index/index');
	$pcConfig = model('pc_setting')->where(array('Users_ID'=>$UsersID,'module'=>'shop'))->find();
	if(!empty($pcConfig['site_url'])){
		$cur_url = 'http://' . trim($pcConfig['site_url'], '/') . '/pc.php';
	}
	return $cur_url;
}