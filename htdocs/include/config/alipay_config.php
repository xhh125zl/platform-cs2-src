<?php
 
//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//合作身份者id，以2088开头的16位纯数字
$alipay_cnf['partner']		= '2088011781995263';

//安全检验码，以数字和字母组成的32位字符
$alipay_cnf['key']			= 'jcd91vx39h182qrzjl6v5u0ei8fyhgfh';


//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
$alipay_cnf['seller_email'] = '965607844@qq.com';
$alipay_cnf['return_url']   = base_url().'member/pay.php?action=alipay_return';//本地使用 127.0.0.1
$alipay_cnf['notify_url']   = base_url().'member/notify.php';//本地使用 127.0.0.1

//签名方式 不需修改
$alipay_cnf['sign_type']    = strtoupper('MD5');

//字符编码格式 目前支持 gbk 或 utf-8
$alipay_cnf['input_charset']= strtolower('utf-8');

//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$alipay_cnf['transport']    = 'http';

