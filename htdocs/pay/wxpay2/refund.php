<?php
ini_set('date.timezone','Asia/Shanghai');
require_once($_SERVER["DOCUMENT_ROOT"]."/pay/wxpay2/lib/WxPay.Api.php");
function refund($order,$Users_ID){
    global $DB;
    if($Users_ID){
        $userinfo = $DB->GetRs("users","*","where Users_ID='{$Users_ID}'");
        $payConfig = $DB->GetRs("users_payconfig","*","where Users_ID='{$Users_ID}'");
    }else{
        return false;
    }
    $orderid = $order['order_id'];
    if($orderid){
        $orderInfo = $DB->GetRs("user_order","*","where Order_ID='{$orderid}'");
        if($orderInfo){
            
            $input = new WxPayRefund();
           
            if(isset($orderInfo['transaction_id']) && $orderInfo['transaction_id']){
                $input->SetTransaction_id($orderInfo['transaction_id']);
                $input->SetMch_id($payConfig['PaymentWxpayPartnerId']);
                $input->SetAppid($userinfo['Users_WechatAppId']);
                $input->SetOut_trade_no($orderInfo['Order_Code']);
                $input->SetOut_refund_no($orderInfo['Order_Code']);
                $input->SetTotal_fee($orderInfo['Order_TotalPrice']*100);
                $input->SetRefund_fee($orderInfo['Order_TotalPrice']*100);
                $input->SetOp_user_id($payConfig['PaymentWxpayPartnerId']);
            }else{
                $input->SetAppid($userinfo['Users_WechatAppId']);
                $input->SetMch_id($payConfig['PaymentWxpayPartnerId']);
                $input->SetOut_trade_no($orderInfo['Order_Code']);
                $input->SetOut_refund_no($orderInfo['Order_Code']);
                $input->SetTotal_fee($orderInfo['Order_TotalPrice']*100);
                $input->SetRefund_fee($orderInfo['Order_TotalPrice']*100);
                $input->SetOp_user_id($payConfig['PaymentWxpayPartnerId']);

            }
            WxPayApi::setkey($payConfig['PaymentWxpayPartnerKey']);
            WxPayApi::setSSLCert($payConfig['PaymentWxpayCert']);
            WxPayApi::setSSLKey($payConfig['PaymentWxpayKey']);
            $result = WxPayApi::refund($input);
            if($result['return_code'] === 'SUCCESS'){
                return true;
            }
        }
    }
    return false;
}