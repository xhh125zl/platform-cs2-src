<?php

/**
 * @Filename DigestUtil.java
 * @Description 签名工具类
 * @author yanglie
 * @Email yanglie@yiji.com
 * @date 2014年10月11日
 */
class DigestUtil{
    /**
     * 签名key
     */
    const SIGN_KEY = "sign";

    /**
     * 以Map中key的字符顺序排序后签名，如果secretKey不为空，排在最后面签名。
     * 比如：Map中值如下：
     * keyA=valueA
     * keyB=valueB
     * keyA1=valueA1
     * security_check_code为yjf
     * 待签名字符串为：
     * keyA=valueA&keyA1=valueA1&keyB=valueByjf<br/>
     * 注意:SIGN_KEY不会被签名
     * @param array $dataMap
     * @param String|\密钥 $securityCheckKey 密钥
     * @param String|\摘要算法 $digestAlg 摘要算法
     * @throws Exception
     * @internal param \编码 $encoding
     * @return null
     */
    public static function digest(array $dataMap, $securityCheckKey, $digestAlg){
        if(is_null($dataMap)){
            throw new Exception("数据不能为空");
        }
        if(empty($dataMap)){
            return null;
        }
        if(is_null($securityCheckKey)){
            throw new Exception("安全检验码数据不能为空");
        }
        if(empty($digestAlg)){
            throw new Exception("摘要算法不能为空");
        }
        $digestStr = "";
        //需要对data map 进行a~z,A~Z排序
        ksort($dataMap);
        foreach($dataMap as $key=>$value){
            if(empty($value)|| $value===""){
        		unset($dataMap[$key]);
            }else{
                if($key  == self::SIGN_KEY){
                    continue;
                }
        		$digestStr .= $key."=".$value.'&';
            }
        }
        $digestStr = trim($digestStr, '&');
        $digestStr .= $securityCheckKey;
        $digestStrMd5 = "";
        if($digestAlg === "MD5"){
            $digestStrMd5 = md5($digestStr);
        }else{
            throw new Exception("暂不支持此加密方式: " + $digestAlg);
        }

        return $digestStrMd5;
    }

    public static function verify(array $param)
    {
        return static::digest($param,SECURITY_KEY,"MD5")==$param['sign']?true:false;
    }
}
?>