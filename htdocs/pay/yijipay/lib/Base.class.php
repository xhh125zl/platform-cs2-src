<?php
class BasePay {
    
    const CHARSET = "UTF-8";
    /**
     *签名类型，支持：
     * SHA-256  暂不支持，请使用MD5
     * MD5
     */
    const SIGN_TYPE = 'MD5';
    /**
     *请求协议，建议使用httpPost
     */
    const HTTP_POST_PROTOCOL = 'httpPost';
    const VERSION = "1.0";
    
    protected function initParams($param) {
        $data = [
            'protocol' => self::HTTP_POST_PROTOCOL,
            'version' => self::VERSION,
            'partnerId' => PARTNER_ID,
            'orderNo' => isset($param['orderNo']) && $param['orderNo']?$param['orderNo']:$this->OrderNo(),
            'merchOrderNo' => isset($param['orderNo']) && $param['orderNo']?$param['orderNo']:$this->OrderNo(4),
            'signType' => self::SIGN_TYPE,
            'returnUrl' => RETURN_URL,
            'notifyUrl' => NOTIFY_URL
        ];
        if(!empty($param)){
            $data = array_merge($data, $param);
        }
        return $data;
    }
    
    public function handle($param){
        $data = $this->initParams($param);
        $data['sign'] = DigestUtil::digest($data, SECURITY_KEY, self::SIGN_TYPE);
        return $data;
    }
    
    protected function OrderNo($len = 8)
    {
        $no = date('YmdHis',time()). time() . str_pad(mt_rand(1, 99999999), $len, '0', STR_PAD_LEFT);
        return $no;
    }
    
    /**
     * 将参数组装成post字符串
     * @param array $param
     * @param parameters
     * @return string
     */
    protected function buildParm(array $param, $sign){
        $postStr = "";
        foreach ($param as $key => $value) {
            $postStr .= $key . '=' . htmlentities($value,ENT_QUOTES,"UTF-8") . '&';
        }
        $postStr .= 'sign='.$sign;
        return $postStr;
    }
    
    protected function doPost($requestURL, array $param){
        $postStr = $this->buildParm($param, $param['sign']);
        $options = [
            CURLOPT_URL => $requestURL,
            CURLOPT_CONNECTTIMEOUT => 7,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => "devo php",
            
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_POST => count($param) + 1,
            CURLOPT_POSTFIELDS => $postStr,
            CURLOPT_SSL_VERIFYPEER =>FALSE,
            CURLOPT_SSL_VERIFYHOST =>FALSE
        ];
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);
        if(!$result){
            $this->onError(curl_error($curl));
        }
        curl_close($curl);
        return $result;
    }
    
    //通过表单的形式进行提交
    protected function doForm($requesturl, $param, $type = 'post'){
        $html='<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /></head><body onLoad="document.dinpayForm.submit();" ><form name="dinpayForm" id="dinpayForm" method="'.$type.'" action="'.$requesturl.'" >';
        foreach($param as $k => $v){
            $html .='<input type="hidden" name="'.$k.'" value="'.htmlentities($v,ENT_QUOTES,"UTF-8").'"/>';
        }
        $html .='</form></body></html>';
        echo $html;
    }
    
    protected function onError($err){
        throw (new YijiPayException($err));
    }
    
    protected function getClientIP()
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

class YijiPayException extends Exception{
    
}