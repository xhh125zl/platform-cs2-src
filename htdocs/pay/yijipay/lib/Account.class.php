<?php
class Account extends BasePay
{
    // 创建支付账号
    public function registerPayAccount($param)
    {
        $param['service'] = "openPaymentAccount";
        $param['protocol'] = "httpJSON";
        $data = $this->handle($param);
        $this->doForm(REQUEST_GATEWAY, $data, "POST");
    }
    
    // 新规会员注册接口
    public function ppmNewRuleRegisterUser($param)
    {
        $param['service'] = "ppmNewRuleRegisterUser";
        $param['protocol'] = "httpJSON";
        $data = $this->handle($param);
        return $this->doPost(REQUEST_GATEWAY, $data);
    }

    public function userBalanceQuery($param)
    {
        $param['service'] = "userBalanceQuery";
        $param['protocol'] = "httpJSON";
        $data = $this->handle($param);
        return $this->doPost(REQUEST_GATEWAY, $data);
    }

    public function qftBatchTransfer($param)
    {
        $param['service'] = "qftBatchTransfer";
        $param['protocol'] = "httpJSON";
        $data = $this->handle($param);
        $this->doForm(REQUEST_GATEWAY, $data, "POST");
    }
}