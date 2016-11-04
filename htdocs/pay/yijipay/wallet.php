<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once(CMS_ROOT.'/include/helper/tools.php');
require_once(CMS_ROOT.'/include/api/users.class.php');

$BizAccount = "";
if(isset($_GET['source']) && $_GET['source']){
	$UsersID = isset($_SESSION['Users_ID'])?$_SESSION['Users_ID']:'';
	if($UsersID){
		$rsPay = $DB->GetRs("users_payconfig", "PaymentTeegonEnabled, PaymentTeegonClientID, PaymentTeegonClientSecret","WHERE Users_ID = '" . $UsersID . "'" );
		require_once(CMS_ROOT.'/pay/yijipay/autoload.php');
        $param = [
            'termnalType' => 'PC',
            'orderNo' => 'W'.date("YmdHis",time()).time()
        ];
        $param['userId'] = PARTNER_ID;
        $charge = new Charge();
        $charge->wallet($param);
        exit;
	}
}

if (isset($_SESSION['BIZ_ID'])) {
	$UsersID = isset($_SESSION['Users_ID'])?$_SESSION['Users_ID']:'';
	$BizID = $_SESSION["BIZ_ID"];
	$BizAccount = $_SESSION['Biz_Account'];
}else{
    header("location:/user/login.php");
    exit;
}
$result = users::getyijiid(['Biz_Account' => $BizAccount]);
if($result['errorCode']==0){
	$rsPay = $DB->GetRs("users_payconfig", "PaymentTeegonEnabled, PaymentTeegonClientID, PaymentTeegonClientSecret","WHERE Users_ID = '" . $UsersID . "'" );
    if(!empty($rsPay)){
        require_once(CMS_ROOT.'/pay/yijipay/autoload.php');
        $param = [
            'termnalType' => 'MOBILE',
            'orderNo' => 'W'.date("YmdHis",time()).time()
        ];
        $param['userId'] = $result['data'];
        $charge = new Charge();
        $charge->wallet($param);
        exit;
    }
} else {
    echo '您还未开通支付账户，暂无法提现';
}
