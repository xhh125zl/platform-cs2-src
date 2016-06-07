<?php
include_once('WxPay.pub.config.php');
include_once('WxPayPubHelper.php');

$weixinzhuanzhang = new Wxpay_client_pub();
//参数设定
$mch_billno = MCHID.date('YmdHis').rand(1000, 9999);

$weixinzhuanzhang->setParameter("partner_trade_no",$mch_billno);
//$weixinzhuanzhang->setParameter("device_info",'');
$weixinzhuanzhang->setParameter("openid",$openid);
$weixinzhuanzhang->setParameter("check_name",'NO_CHECK');
//$weixinzhuanzhang->setParameter("re_user_name",'');
$weixinzhuanzhang->setParameter("amount",$money);
$weixinzhuanzhang->setParameter("spbill_create_ip",getIp());
$weixinzhuanzhang->setParameter("desc",'佣金提现微信转帐');//描述

$weixinzhuanzhang->url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
$weixinzhuanzhang->curl_timeout = 30;
$return_xml = $weixinzhuanzhang->postXmlSSL_zhuanzhang();

$responseObj = simplexml_load_string($return_xml, 'SimpleXMLElement', LIBXML_NOCDATA);
$return_code = trim($responseObj->return_code);
$return_msg = trim($responseObj->return_msg);
if($return_code=='SUCCESS'){
	$result_code = trim($responseObj->result_code);
	if($result_code=='SUCCESS'){
		$Data = array(
			"status"=>1
		);
	}else{
		$Data = array(
			"status"=>0,
			"msg"=>trim($responseObj->err_code_des)
		);
	}
}else{
	$Data = array(
		"status"=>0,
		"msg"=>$return_msg
	);
}

function getIp(){
	if (!empty($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP']!='unknown') {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']!='unknown') {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return preg_match('/^\d[\d.]+\d$/', $ip) ? $ip : '';
}

?>