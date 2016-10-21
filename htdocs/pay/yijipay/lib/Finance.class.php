<?php
class Finance extends BasePay
{
    // 分账清分接口
    public function commandPayConfirm($param)
    {
        $param['service'] = "commandPayConfirm";
        $param['userEndIp'] = $this->getClientIP();
        $data = $this->handle($param);
        $result = $this->doPost(REQUEST_GATEWAY, $data);
        $result = json_decode($result, true);
        return $result;
    }
}
