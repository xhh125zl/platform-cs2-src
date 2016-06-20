<?php


//$condition = "where Users_ID= '".$_SESSION['Users_ID']."'";

$rsDsRecords = $DB->get('shop_distribute_record','Record_ID,Order_ID');

$dsRecord_list = $DB->toArray($rsDsRecords);


foreach($dsRecord_list as $key=>$dsRecord){
	
	$condition = "where  Order_ID=".$dsRecord['Order_ID'];
	$data = array();
	$data['Ds_Record_ID'] = $dsRecord['Record_ID'];
   

	$flag = $DB->set('shop_distribute_account_record',$data,$condition);

	if($flag){
		echo '记录'.$dsRecord['Record_ID'].'同步成功'.'<br/>';
	}else{
		echo 'error';
		break;
	}
}