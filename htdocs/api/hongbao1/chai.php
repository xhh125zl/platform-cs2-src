<?php
require_once('skin/chai.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');


if(isset($_GET["UsersID"])){
	
	if(!strpos($_GET["UsersID"],"_")){
		echo '缺少必要的参数';
		exit;
	}else{//help friend
		$arr = explode("_",$_GET["UsersID"]);
		$UsersID = $arr[0];
		$actid = $arr[1];
	}		
	$rsConfig = $DB->GetRs("hongbao_config","*","where usersid='".$UsersID."'");
	
	if(!$rsConfig){
		echo '未开通抢红包';
		exit;
	}
}else{
	echo '缺少必要的参数';
	exit;
}
$_SESSION[$UsersID."HTTP_REFERER"]="/api/hongbao/chai.php?UsersID=".$_GET["UsersID"];
$rsUsers = $DB->GetRs("users","*","where Users_ID='".$UsersID."'");
$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."' and PaymentWxpayEnabled=1");

$is_login = 1;
$shopConfig = $DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$shopConfig = array_merge($shopConfig,$dis_config);
$owner = get_owner($shopConfig,$UsersID);
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

$actinfo = $DB->GetRs("hongbao_act","*","where usersid='".$UsersID."' and userid=".$_SESSION[$UsersID."User_ID"]." and actid=$actid");
if(!$actinfo){
	echo '<script type="text/javascript">alert("该红包不存在");window.location.href="/api/'.$UsersID.'/hongbao/mycenter/";</script>';
	exit;
}else{
	if($actinfo["status"]==1){
		echo '<script type="text/javascript">alert("红包成功开启");window.location.href="/api/'.$UsersID.'/hongbao/mycenter/";</script>';
		exit;
	}else{
		if($actinfo["expire"]>=$actinfo["friend"]){
			$userinfo = $DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
			define("APPID" , trim($rsUsers["Users_WechatAppId"]));
			define("APPSECRET", trim($rsUsers["Users_WechatAppSecret"]));
			define("MCHID",trim($rsPay["PaymentWxpayPartnerId"]));
			define("KEY",trim($rsPay["PaymentWxpayPartnerKey"]));
			define("SSLCERT_PATH",$_SERVER["DOCUMENT_ROOT"].$rsPay["PaymentWxpayCert"]);
			define("SSLKEY_PATH",$_SERVER["DOCUMENT_ROOT"].$rsPay["PaymentWxpayKey"]);
			include_once("../../pay/wxpay2/WxPayPubHelper.php");
			$mch_billno = MCHID.date('YmdHis').rand(1000, 9999);
			$money = strval($actinfo["money"]*100);
			$weixinhongbao = new Wxpay_client_pub();
			$weixinhongbao->setParameter("mch_billno",$mch_billno);
			$weixinhongbao->setParameter("nick_name",($rsConfig["supply"] ? $rsConfig["supply"] : $UsersID));
			$weixinhongbao->setParameter("send_name",($rsConfig["supply"] ? $rsConfig["supply"] : $UsersID));
			$weixinhongbao->setParameter("re_openid",(empty($_SESSION[$UsersID."OpenID"]) ? $userinfo["User_OpenID"] : $_SESSION[$UsersID."OpenID"]));
			$weixinhongbao->setParameter("total_amount",$money);
			$weixinhongbao->setParameter("min_value",$money);
			$weixinhongbao->setParameter("max_value",$money);
			$weixinhongbao->setParameter("total_num","1");
			$weixinhongbao->setParameter("wishing","恭喜你得到红包".$actinfo["money"]."元");
			$weixinhongbao->setParameter("client_ip","203.195.161.41");
			$weixinhongbao->setParameter("act_name",$rsConfig["name"]);
			$weixinhongbao->setParameter("act_id",$actid);
			$weixinhongbao->setParameter("remark",$rsConfig["name"]);
			$weixinhongbao->url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
			$weixinhongbao->curl_timeout = 30;
			$return_xml = $weixinhongbao->postXmlSSL_hongbao();
			$responseObj = simplexml_load_string($return_xml, 'SimpleXMLElement', LIBXML_NOCDATA);
			$return_code = trim($responseObj->return_code);
			$return_msg = trim($responseObj->return_msg);
			if($return_code=='SUCCESS'){
				$Data = array(
					"status"=>1
				);
				$flag = $DB->Set("hongbao_act",$Data,"where actid=".$actid);
				if($flag){
					echo '<script type="text/javascript">alert("红包成功开启");window.location.href="/api/'.$UsersID.'/hongbao/mycenter/";</script>';
				}
			}else{
				echo '<script type="text/javascript">alert("服务器繁忙，请稍候再试");window.location.href="/api/'.$UsersID.'/hongbao/mycenter/";</script>';
			}
		}else{
			echo '<script type="text/javascript">alert("还不能拆，还需邀请'.($actinfo["friend"]-$actinfo["expire"]).'位好友相助");window.location.href="/api/'.$UsersID.'/hongbao/mycenter/";</script>';
		}
	}
}	
?>