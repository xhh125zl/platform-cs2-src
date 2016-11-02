<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once(CMS_ROOT.'/include/helper/tools.php');
require_once(CMS_ROOT.'/include/api/users.class.php');

$BizAccount = "";
if (isset($_SESSION['BIZ_ID'])) {
	$UsersID = $_SESSION['Users_ID'];
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
}
