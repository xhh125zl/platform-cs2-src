<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once(CMS_ROOT . '/Framework/Ext/virtual.func.php');
require_once(CMS_ROOT . '/include/helper/order.php');
require_once(CMS_ROOT . '/include/helper/tools.php');
require_once(CMS_ROOT . '/Framework/Ext/sms.func.php');
require_once(CMS_ROOT . '/include/library/pay_order.class.php');
require_once(CMS_ROOT . '/include/api/distribute.class.php');
ini_set("display_errors","On");

require_once(CMS_ROOT.'/include/api/const.php');
require_once(CMS_ROOT.'/include/api/pay.class.php');
$rsPayRes = pay::getlist();
if ($rsPayRes['errorCode'] == 0) {
    $rsPay = $rsPayRes['payConfig'];
}else{
     echo "易极付支付配置信息不正确！";
     exit;
}
require_once(CMS_ROOT.'/pay/yijipay/autoload.php');

$xml = isset($GLOBALS['HTTP_RAW_POST_DATA']) && ! empty(isset($GLOBALS['HTTP_RAW_POST_DATA'])) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
if($xml){
	parse_str ($xml, $post);
}else{
	echo "FAILE";
	exit;
}
logging("易极付转账异步调试记录",$post);
$UsersID = 'pl2hu3uczz';  //固定值
$rsObjPay = Users_PayConfig::where('Users_ID', $UsersID)->first();
$rsPay = $rsObjPay->toArray();
require_once (CMS_ROOT . '/pay/yijipay/autoload.php');
$verify = DigestUtil::verify($notifyData);
if($verify){
	if($post['success']==true && $post['resultCode']=='EXECUTE_SUCCESS'){
	   $DB->Set("trans_yijipay_record",['status' => 1, 'transTime' =>time()], "WHERE orderNo = '" . $post['orderNo'] . "'");
	   $yijilist = TransYijipayRecord::where("orderNo",$post['orderNo'])->get();
	   if($yijilist){
		   $yijilist = $yijilist->toArray();
		   $Syndata = [];
		   foreach($yijilist as $k => $v){
			   $Syndata[$v['User_ID']] = $v['balance'];
		   }
		   $result = distribute::updateyijibalance(['counters' => $Syndata]);
		   if($result['errorCode'] == 0){
			   $DB->Set("trans_yijipay_record",['SynStatus' => 2], "WHERE orderNo = '" . $post['orderNo'] . "'");
			   echo "success";
			   exit;
		   }else{
			   $DB->Set("trans_yijipay_record",['SynStatus' => -1], "WHERE orderNo = '" . $post['orderNo'] . "'");
			   echo "success";
		   }
	   }
	}else{
	   echo "error";exit;
	}
}else{
	echo "签名错误";
	exit;
}
?>