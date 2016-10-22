<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once(CMS_ROOT.'/include/helper/tools.php');
if(empty($_SESSION["Users_ID"]))
{
    header("location:/member/login.php");
    exit;
}

$rsPay = $DB->GetRs("users_payconfig", "PaymentTeegonEnabled, PaymentTeegonClientID, PaymentTeegonClientSecret","WHERE Users_ID = '" . $_SESSION["Users_ID"] . "'" );
if(!empty($rsPay)){
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