<?php

class Charge extends BasePay
{
    public function pay($param)
    {
        $param['userEndIp'] = isset($param['userEndIp']) && $param['userEndIp']?$param['userEndIp']:$this->getClientIP();
        $param['service'] = "commandPayTradeCreatePay";
        $data = $this->handle($param);
        $this->doForm(REQUEST_GATEWAY, $data, "POST");
    }
    
    public function wallet($param)
    {
        $param['service'] = "wallet";
        $data = $this->handle($param);
        $this->doForm(REQUEST_GATEWAY, $data, "POST");
    }
}