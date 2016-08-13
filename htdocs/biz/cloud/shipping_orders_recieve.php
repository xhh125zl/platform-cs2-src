<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/update/common.php');

$DB->Get("shipping_orders","Orders_SendTime,Orders_Status,User_ID,Orders_IsShipping,Orders_ID","WHERE Users_ID='{$UsersID}' AND Orders_Status=2 AND Orders_SendTime<=".(time()-86400*7));
	$lists = array();
	while($r = $DB->fetch_assoc()){
		$lists[] = $r;
	}
	foreach($lists as $v){
		$DB->Set("shipping_orders","Orders_Status=3,Orders_FinishTime=".time(),"WHERE Users_ID='{$UsersID}' AND Orders_ID=".$v["Orders_ID"]);
	}
	echo '<script language="javascript">alert("操作成功");window.location.href="shipping_orders.php";</script>';
	exit;
?>