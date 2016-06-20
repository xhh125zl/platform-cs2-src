<?php
include_once('WxPay.pub.config.php');
include_once('WxPayPubHelper.php');

$weixinhongbao = new Wxpay_client_pub();
//参数设定
$mch_billno = MCHID.date('YmdHis').rand(1000, 9999);
$weixinhongbao->setParameter("mch_billno",$mch_billno);
$weixinhongbao->setParameter("send_name",$sendname);//发送者名称
$weixinhongbao->setParameter("re_openid",$openid);
$weixinhongbao->setParameter("total_amount",$money);
$weixinhongbao->setParameter("total_num","1");
$weixinhongbao->setParameter("wishing",$wishing);//红包祝福语
$weixinhongbao->setParameter("client_ip",getIp());
$weixinhongbao->setParameter("act_name","佣金发放");//活动名称
$weixinhongbao->setParameter("remark",$remark);//描述
$weixinhongbao->url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
$weixinhongbao->curl_timeout = 30;
$return_xml = $weixinhongbao->postXmlSSL_hongbao();
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