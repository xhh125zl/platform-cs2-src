<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/virtual.func.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/order.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/sms.func.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
require_once(CMS_ROOT . '/include/api/distribute.class.php');

$notifyData = $_GET;
if(isset($notifyData['creatTradeResult'])){
	$creatTradeResult = json_decode($notifyData['creatTradeResult'],true);
	if($creatTradeResult[0]){
		echo $creatTradeResult[0]['failReason'];
		exit;
	}
}

$UsersID = $_SESSION['Users_ID'];
$rsObjPay = Users_PayConfig::where('Users_ID', $UsersID)->first();
$rsPay = $rsObjPay->toArray();
require_once (CMS_ROOT . '/pay/yijipay/autoload.php');
$verify = DigestUtil::verify($notifyData);
if($verify){
	
	if($notifyData['resultCode'] == 'EXECUTE_SUCCESS' && $notifyData['success'] == true){
    $yijilist = TransYijipayRecord::where("orderNo",$notifyData['orderNo'])->get();
		$DB->Set("trans_yijipay_record",['status' => 1, 'confirmTime' =>time()], "WHERE orderNo = '" . $notifyData['orderNo'] . "'");
		logging("同步调试记录29",$notifyData);
		if($yijilist){
		   $yijilist = $yijilist->toArray();
		   $Syndata = [];
		   foreach($yijilist as $k => $v){
			   if($v['status'] == 0){
					$Syndata[$v['User_ID']] = $v['balance'];
			   }
		   }
		   logging("同步调试记录38",$notifyData);
		   if(!empty($Syndata)){
			   logging("同步调试记录40",$notifyData);
			   $result = distribute::updateyijibalance(['counters' => $Syndata]);
           if($result['errorCode'] == 0){
           $DB->Set("trans_yijipay_record",['SynStatus' => 2], "WHERE orderNo = '" . $notifyData['orderNo'] . "'");
           }else{
           $DB->Set("trans_yijipay_record",['SynStatus' => -1], "WHERE orderNo = '" . $notifyData['orderNo'] . "'");
           }
		   }
		}
		header("Location:/member/yijipay/trans_yijipay_batch.php");
		exit;
	}else{
		echo $notifyData['resultMessage'];
		exit;
	}
}else{
	echo "签名错误";
	exit;
}

?>