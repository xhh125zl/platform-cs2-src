<?php
class Charge extends TeegonService {
    
    /**
        支付功能接口
    **/
    public function chargePay($parm, $json = true) {
        $shortAPI = '/charge/';
        if(empty($parm)) return false;
        if(is_array($parm)){
            if(!isset($parm['order_no']) || empty($parm['order_no'])){
                return [
                    'code' => 10001,
                    'error' => '订单号不能为空'
                ];
            }

            if(!isset($parm['return_url']) || empty($parm['return_url'])){
                return [
                    'code' => 10001,
                    'error' => '回调地址不能为空'
                ];
            }

            if(!isset($parm['amount']) || empty($parm['amount'])){
                return [
                    'code' => 10001,
                    'error' => '支付金额错误'
                ];
            }

            $parm['notify_url'] = isset($parm['notify_url']) && $parm['notify_url']?$parm['notify_url']:NOTIFY_URL;
            $parm['return_url'] = isset($parm['return_url']) && $parm['return_url']?$parm['return_url']:RETURN_URL;
            $parm['currency'] = isset($parm['currency']) && $parm['currency']?$parm['currency']:'RMB';
            $parm['time_expire'] = isset($parm['time_expire']) && $parm['time_expire']?$parm['time_expire']:300;
            $parm['ip'] = isset($parm['ip']) && $parm['ip']?$parm['ip']:$this->getClientIP();
            $parm['client_id'] = TEE_CLIENT_ID;
            $parm['client_secret'] = TEE_CLIENT_SECRET;
            
            return $this->handle($shortAPI, 'post', $parm, $json);
        }
    }
    
    /** 
        根据条件获取订单列表
     */
    public function charge_list($parm = [], $json = true){
        $shortAPI = '/charge/list/';
        if(is_array($parm)){
            $parm = array_merge($parm, $this->parm);
        }
        return $this->handle($shortAPI, 'get', $parm, $json);
    }

     /** 
        根据条件获取订单列表
     */
    public function charge_get($parm = [], $json = true){
        $shortAPI = '/charge/get/';
        if(is_array($parm)){
            $parm = array_merge($parm, $this->parm);
        }
         
        return $this->handle($shortAPI, 'get', $parm, $json);
    }

    private function getClientIP()
    {
        $onlineip = "";
        if(isset($_SERVER['HTTP_CLIENT_IP'])){
            $onlineip=$_SERVER['HTTP_CLIENT_IP'];
        }elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $onlineip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $onlineip=$_SERVER['REMOTE_ADDR'];
        }
        return $onlineip;
    }
}
