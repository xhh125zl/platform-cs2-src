<?php
/*订单帮助函数*/
if (!function_exists('order_input_record')) {
	
	//生成进账记录信息 	
	function order_input_record($Users_ID,$Begin_Time,$End_Time){
		
		$fields = array('Order_ID','Order_CreateTime','Order_TotalAmount','Order_Status','Order_IsVirtual');
		$order = new Order();
		
		$input_record_builder = $order->ordersBetween($Users_ID,$Begin_Time,$End_Time,4);
		$paginate_obj = $input_record_builder->paginate(5,$fields);
		
		$res = array(
					 'sum'=>$input_record_builder->sum('Order_TotalAmount'),
		             'input_paginate'=>$paginate_obj,
					 'total_pages'=>$paginate_obj->lastPage()
					 );	 
				 
		return $res;			 
	
	}
}

if(!function_exists('generate_input_table')){
	
	//生成进账记录table
	function generate_input_record_table($smarty,$info){

		$order_status = array('待确认','待付款','已付款','已发货','已完成');
			
		$paginate = $info['input_paginate'];
		$paginate_array = $paginate->toArray();
		$record_list = $paginate_array['data'];
		
		if(!empty($record_list)){
			foreach($record_list as $key=>$item){
				$item['Order_Sn'] = sdate($item['Order_CreateTime'],'').$item['Order_ID'];
				$item['Order_CreateTime'] = ldate($item['Order_CreateTime']);
				$item['Order_Status'] = $order_status[$item['Order_Status']];
				$item['Order_Link'] = base_url('member/shop/'.($item['Order_IsVirtual']==1 ? 'virtual_' : '').'orders_view.php?OrderID='.$item['Order_ID']);
				$record_list[$key] = $item;	
			}
		}
		
		$smarty->assign('input_record_list',$record_list );		
		$html = $smarty->fetch('input_record_list.html');
		

		return $html;
			
	}
}

if(!function_exists('output_record')){
	
	//生成出账记录信息
	function output_record($Users_ID,$Begin_Time,$End_Time){
	    $fields = array('Record_ID','Record_Sn','Record_CreateTime','Record_Money','Record_Status','User_ID');
		$record = new Dis_Account_Record();

		
		$output_record_builder = $record->recordBetween($Users_ID,$Begin_Time,$End_Time,2)
		                                ->where('Record_Type',0);
		
		$paginate_obj = $output_record_builder->paginate(5,$fields);
		
		$res = array(
					 'sum'=>$output_record_builder->sum('Record_Money'),
		             'output_paginate'=>$paginate_obj,
					 'total_pages'=>$paginate_obj->lastPage()
					 );	 
				 
		return $res;			 
		
	}
}

if(!function_exists('generate_output_table')){
	
	//生成出账记录table
	function generate_output_table($smarty,$info){

		$record_status = array('未完成','已付款','已完成');
			
		$paginate = $info['output_paginate'];
		$paginate_array = $paginate->toArray();
		$record_list = $paginate_array['data'];
		
		
	
		if(!empty($record_list)){
			foreach($record_list as $key=>$item){
				$item['User_NickName'] = $item['user']['User_NickName'];
				$item['Record_CreateTime'] = ldate($item['Record_CreateTime']);
				$item['Record_Status'] = $record_status[$item['Record_Status']];
				$record_list[$key] = $item;	
			}
		}
		
	
		$smarty->assign('output_record_list',$record_list );		
		$html = $smarty->fetch('output_record_list.html');
		

		return $html;
			
	}
}


