<?php
class Bank extends TeegonService {
    
    /** 
        获取银行卡绑定的url
     */
    public function bind($parm, $json = true){
        $shortAPI = '/bank/url/bind';
        if(is_array($parm)){
            $parm = array_merge($parm, $this->parm);
        }
        if(!isset($parm['account_id']) || !$parm['account_id']) return ['code' => '10001', 'msg' => '缺少account_id参数'];
        if(!isset($parm['notify_url']) || !$parm['notify_url']) return ['code' => '10001', 'msg' => '缺少notify_url参数'];

        return $this->handle($shortAPI, 'post', $parm, $json);
    }

     /** 
        提现验证密码
     */
    public function verify($parm, $json = true){
        $shortAPI = '/bank/verify';
        if(is_array($parm)){
            $parm = array_merge($parm, $this->parm);
        }
        if(!isset($parm['sub_id']) || !$parm['sub_id']) return ['code' => '10001', 'msg' => '缺少sub_id参数'];
        if(!isset($parm['amount']) || !$parm['amount']) return ['code' => '10001', 'msg' => '缺少amount参数'];

        return $this->handle($shortAPI, 'post', $parm, $json);
    }
}
