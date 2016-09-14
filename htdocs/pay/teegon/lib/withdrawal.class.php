<?php
class Withdrawal extends TeegonService {
    
    //获取提现记录
    public function getList($parm, $json = true)
    {
        $shortAPI = "/withdrawal/list";
        return $this->handle($shortAPI, 'get', $parm, $json);
    }
    
    //创建提现申请
    public function create($parm, $json = true)
    {
        $shortAPI = "/withdrawal/create";
        return $this->handle($shortAPI, 'post', $parm, $json);
    }
    
    //提现手机短信验证
    public function confirm($parm, $json = true)
    {
        $shortAPI = "/withdrawal/confirm";
        return $this->handle($shortAPI, 'post', $parm, $json);
    }
}
