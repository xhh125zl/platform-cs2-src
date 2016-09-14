<?php 
ini_set("display_errors","On");

$DB->showErr=TRUE;
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
if($_POST){
	//开始事务定义
	$flag=true;
	$msg="";
	mysql_query("begin");

	if($_POST["action"]=="payment"){
		
		$Data=array(
			"Payment_AlipayEnabled"=>isset($_POST["AlipayEnabled"])?$_POST["AlipayEnabled"]:0,
			"Payment_AlipayPartner"=>$_POST["AlipayPartner"],
			"Payment_AlipayKey"=>$_POST["AlipayKey"],
			"Payment_AlipayAccount"=>$_POST["AlipayAccount"],
			"Payment_OfflineEnabled"=>isset($_POST["OfflineEnabled"])?$_POST["OfflineEnabled"]:0,
			"Payment_RmainderEnabled"=>isset($_POST["RemainderEnabled"])?$_POST["RemainderEnabled"]:0,
			"Payment_OfflineInfo"=>$_POST["OfflineInfo"],			
			"PaymentWxpayEnabled"=>isset($_POST["PaymentWxpayEnabled"])?$_POST["PaymentWxpayEnabled"]:0,
			"PaymentWxpayType"=>$_POST["PaymentWxpayType"],
			"PaymentWxpayPartnerId"=>$_POST["PaymentWxpayPartnerId"],
			"PaymentWxpayPartnerKey"=>$_POST["PaymentWxpayPartnerKey"],
			"PaymentWxpayPaySignKey"=>$_POST["PaymentWxpayPaySignKey"],	
			"PaymentWxpayCert"=>$_POST["CertPath"],
			"PaymentWxpayKey"=>$_POST["KeyPath"],		
			"PaymentYeepayEnabled"=>isset($_POST["PaymentYeepayEnabled"])?$_POST["PaymentYeepayEnabled"]:0,
			"PaymentYeepayAccount"=>$_POST["PaymentYeepayAccount"],
			"PaymentYeepayPrivateKey"=>$_POST["PaymentYeepayPrivateKey"],
			"PaymentYeepayPublicKey"=>$_POST["PaymentYeepayPublicKey"],
			"PaymentYeepayYeepayPublicKey"=>$_POST["PaymentYeepayYeepayPublicKey"],
			"PaymentYeepayProductCatalog"=>!empty($_POST["PaymentYeepayProductCatalog"])?$_POST["PaymentYeepayProductCatalog"]:0,
			"PaymentTeegonEnabled"=>isset($_POST["PaymentTeegonEnabled"])?$_POST["PaymentTeegonEnabled"]:0,
			"PaymentTeegonClientID"=>$_POST["PaymentTeegonClientID"],
			"PaymentTeegonClientSecret"=>$_POST["PaymentTeegonClientSecret"]
			
		);
	
		$string = serialize($Data);
		//write_file(BASEPATH.'/data/count.txt',$string,'a');
		
		$Set=$DB->Set("users_payconfig",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	 
		$flag=$flag&&$Set;
		
		if($flag)
		{
			mysql_query("commit");
			echo '<script language="javascript">alert("保存成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			mysql_query("roolback");
			echo '<script language="javascript">alert("保存失败");history.go(-1);</script>';
		}
		exit;
	}
}else{
	$rsConfig =$DB->GetRs("users_payconfig","*","where Users_ID='".$_SESSION["Users_ID"]."'");
	if(!$rsConfig){
		$Data = array(
			'Users_ID'=>$_SESSION["Users_ID"]
		);
		$DB->Add("users_payconfig",$Data);
		$rsConfig=$DB->GetRs("users_payconfig","*","where Users_ID='".$_SESSION["Users_ID"]."'");
	}
	
}
?>


<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/shop.js'></script>
<script type="text/javascript" src="/third_party/uploadify/jquery.uploadify.min.js"></script>
<link href="/third_party/uploadify/uploadify.css" rel="stylesheet" type="text/css">
<script type='text/javascript'>
$(document).ready(shop_obj.pay_shipping_config_init);
	 
</script>
<style type="text/css">
#web_payment_form .up_input{width:100px; position:absolute; top:8px; right:80px}
</style>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/wechat.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="shopping.php">运费&支付管理</a></li>
      </ul>
    </div>
    <script type='text/javascript' src='/static/js/plugin/dragsort/dragsort-0.5.1.min.js'></script> 
    <script language="javascript">//$(document).ready(shop_obj.shopping_init);</script>
    <div id="shopping" class="r_con_wrap">
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="34%">支付方式管理</td>
            <td width="33%">&nbsp;</td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td valign="top" class="payment"><form action="shopping.php" method="post" id="web_payment_form">
                <ul>
                
							<li>
								<h1>微信支付<span><input type="checkbox" value="1" id="check_0" name="PaymentWxpayEnabled" <?php echo $rsConfig["PaymentWxpayEnabled"] ? 'checked' : '';?> onClick="show_pay_ment(0);"/>启用</span></h1>
								<dl id="pay_0" style="display:block">
								    <dd><input type="radio" name="PaymentWxpayType" value="0"<?php echo $rsConfig["PaymentWxpayType"]==0 ? " checked" : "";?> id="type_0" onClick="document.getElementById('paysignkey').style.display='block';" style="width:25px; height:10px"/><label for="type_0">旧版本</label>&nbsp;&nbsp;<input type="radio" name="PaymentWxpayType" value="1"<?php echo $rsConfig["PaymentWxpayType"]==1 ? " checked" : "";?> id="type_1" onClick="document.getElementById('paysignkey').style.display='none';" style="width:25px; height:10px"/><label for="type_1">新版本</label></dd>
									<dd>商户号PartnerId：<input type="text" name="PaymentWxpayPartnerId" value="<?php echo $rsConfig["PaymentWxpayPartnerId"];?>" maxlength="10" /></dd>
									<dd>&nbsp;密钥PartnerKey：<input type="text" name="PaymentWxpayPartnerKey" value="<?php echo $rsConfig["PaymentWxpayPartnerKey"];?>" maxlength="32" /></dd>
									<dd id="paysignkey" style="display:<?php echo $rsConfig["PaymentWxpayType"]==0 ? "block" : "none";?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PaySignKey：<input type="text" name="PaymentWxpayPaySignKey" value="<?php echo $rsConfig["PaymentWxpayPaySignKey"];?>" maxlength="128" /></dd>
                                    <dd style="position:relative">&nbsp;微信支付商户证书：<input type="text" class="input" id="CertPath" name="CertPath" value="<?php echo empty($rsConfig["PaymentWxpayCert"])?"":$rsConfig["PaymentWxpayCert"] ?>" maxlength="500" placeholder="apiclient_cert.pem" notnull />
                                    	<span class="up_input">
                                        	<input id="CertUpload" name="CertUpload" type="file">
                                        </span>
                                    </dd>
                                    <dd style="position:relative">&nbsp;微信支付证书密钥：<input type="text" class="input" id="KeyPath" name="KeyPath" value="<?php echo empty($rsConfig["PaymentWxpayKey"])?"":$rsConfig["PaymentWxpayKey"] ?>" maxlength="500" placeholder="apiclient_key.pem" notnull />
                                       <span class="up_input">
                                           <input id="KeyUpload" name="KeyUpload" type="file">
                                       </span>
                                    </dd>
                                    <dd style="margin-top:3px; color:#999">微信支付商户证书、微信支付证书密钥主要适用于分销商试用<font style="color:#F00"> 微信红包提现 </font>和<font style="color:#F00">  抢红包 </font>功能</dd>
									<dd>还需到“<a href="../wechat/auth_set.php">微信授权配置</a>”设置“AppId”和“AppSecret”</dd>
								</dl>
							</li>
							<li>
								<h1>易宝支付<span><input type="checkbox" value="1"  id="check_1" name="PaymentYeepayEnabled" <?php echo $rsConfig["PaymentYeepayEnabled"] ? 'checked' : '';?> onClick="show_pay_ment(1);"/>启用</span></h1>
								<dl id="pay_1" style="display:<?php echo $rsConfig["PaymentYeepayEnabled"] ? 'block' : 'none';?>">
									<dd>&nbsp;&nbsp;商户编号：<input type="text" name="PaymentYeepayAccount" value="<?php echo $rsConfig["PaymentYeepayAccount"];?>" /></dd>
									<dd>&nbsp;&nbsp;商户私钥：<input type="text" name="PaymentYeepayPrivateKey" value="<?php echo $rsConfig["PaymentYeepayPrivateKey"];?>" /></dd>
									<dd>&nbsp;&nbsp;商户公钥：<input type="text" name="PaymentYeepayPublicKey" value="<?php echo $rsConfig["PaymentYeepayPublicKey"];?>" /></dd>
									<dd>&nbsp;&nbsp;易宝公钥：<input type="text" name="PaymentYeepayYeepayPublicKey" value="<?php echo $rsConfig["PaymentYeepayYeepayPublicKey"];?>" /></dd>
									<dd>商品类别码：<input type="text" name="PaymentYeepayProductCatalog" value="<?php echo $rsConfig["PaymentYeepayProductCatalog"];?>" /></dd>
								</dl>
							</li>
                            <li>
                    <h1>支付宝<span>
                      <input type="checkbox" value="1" id="check_2" name="AlipayEnabled"<?php echo empty($rsConfig["Payment_AlipayEnabled"])?"":" checked"; ?>  onclick="show_pay_ment(2);"/>
                      启用 <a href="https://b.alipay.com/order/productDetail.htm?productId=2013080604609688" target="_blank">申请</a></span></h1>
                    <dl id="pay_2" style="display:<?php echo $rsConfig["Payment_AlipayEnabled"] ? 'block' : 'none';?>">
                      <dd>合作身份ID：
                        <input type="text" name="AlipayPartner" value="<?php echo $rsConfig["Payment_AlipayPartner"]; ?>" maxlength="16" />
                      </dd>
                      <dd>安全检验码：
                        <input type="text" name="AlipayKey" value="<?php echo $rsConfig["Payment_AlipayKey"]; ?>" maxlength="32" />
                      </dd>
                      <dd>支付宝账号：
                        <input type="text" name="AlipayAccount" value="<?php echo $rsConfig["Payment_AlipayAccount"]; ?>" maxlength="30" />
                      </dd>
                    </dl>
                  </li>
                  <li>
                    <h1>商派天工<span>
                      <input type="checkbox" value="1" id="check_4" name="PaymentTeegonEnabled"<?php echo empty($rsConfig["PaymentTeegonEnabled"])?"":" checked"; ?>  onclick="show_pay_ment(4);"/>
                      启用 <a href="https://charging.teegon.com/domain/2aa40378/charging/api" target="_blank">申请</a></span></h1>
                    <dl id="pay_4" style="display:<?php echo $rsConfig["PaymentTeegonEnabled"] ? 'block' : 'none';?>">
                      <dd>Client ID：
                        <input type="text" name="PaymentTeegonClientID" value="<?php echo $rsConfig["PaymentTeegonClientID"]; ?>" maxlength="40" />
                      </dd>
                      <dd>Client Secret：
                        <input type="text" name="PaymentTeegonClientSecret" value="<?php echo $rsConfig["PaymentTeegonClientSecret"]; ?>" maxlength="40" />
                      </dd>
                    </dl>
                  </li>
				<li>
                    <h1>余额支付<span>
                      <input type="checkbox" value="1" name="RemainderEnabled"<?php echo empty($rsConfig["Payment_RmainderEnabled"])?"":" checked"; ?>/>
                      启用</span></h1>
                  </li>
                  <li>
                    <h1>线下支付<span>
                      <input type="checkbox" value="1" id="check_3" name="OfflineEnabled"<?php echo empty($rsConfig["Payment_OfflineEnabled"])?"":" checked"; ?> onClick="show_pay_ment(3);"/>
                      启用（填写收款帐号信息）</span></h1>
                    <dl id="pay_3" style="display:<?php echo $rsConfig["Payment_OfflineEnabled"] ? 'block' : 'none';?>">
                      <dd>
                        <textarea name="OfflineInfo"><?php echo $rsConfig["Payment_OfflineInfo"]; ?></textarea>
                      </dd>
                    </dl>
                  </li>
						</ul>
                <div class="submit">
                  <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
                </div>
                <input type="hidden" name="action" value="payment">
              </form></td>
            <td valign="top" class="shipping">
           	&nbsp;
            </td>
            
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script type="text/javascript">
function show_pay_ment(id){
	for(var i=0; i<=4; i++){
		if(i==id){
			if(document.getElementById('check_'+id).checked==false){
				document.getElementById('pay_'+id).style.display = 'none';
			}else{
				document.getElementById('pay_'+id).style.display = 'block';
			}
		}else{
			if(document.getElementById('check_'+i).checked==false){
				document.getElementById('pay_'+i).style.display = 'none';
			}else{
				document.getElementById('pay_'+i).style.display = 'block';
			}
		}
	}
}
</script>
</body>
</html>