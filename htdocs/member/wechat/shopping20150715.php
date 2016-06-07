<?php 
ini_set("display_errors","On");


require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');

$DB->showErr=false;
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if($_POST)
{
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
			"Payment_OfflineInfo"=>$_POST["OfflineInfo"],
			"PaymentWxpayEnabled"=>isset($_POST["PaymentWxpayEnabled"])?$_POST["PaymentWxpayEnabled"]:0,
			"PaymentWxpayType"=>$_POST["PaymentWxpayType"],
			"PaymentWxpayPartnerId"=>$_POST["PaymentWxpayPartnerId"],
			"PaymentWxpayPartnerKey"=>$_POST["PaymentWxpayPartnerKey"],
			"PaymentWxpayPaySignKey"=>$_POST["PaymentWxpayPaySignKey"],
			
			"PaymentYeepayEnabled"=>isset($_POST["PaymentYeepayEnabled"])?$_POST["PaymentYeepayEnabled"]:0,
			"PaymentYeepayAccount"=>$_POST["PaymentYeepayAccount"],
			"PaymentYeepayPrivateKey"=>$_POST["PaymentYeepayPrivateKey"],
			"PaymentYeepayPublicKey"=>$_POST["PaymentYeepayPublicKey"],
			"PaymentYeepayYeepayPublicKey"=>$_POST["PaymentYeepayYeepayPublicKey"],
			"PaymentYeepayProductCatalog"=>$_POST["PaymentYeepayProductCatalog"]
		);
	
		$Set=$DB->Set("users_payconfig",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
		$flag=$flag&&$Set;
	
	}elseif($_POST["action"]=="shipping"){
		
		$Data["Shipping"] = json_encode($_POST['Default_Template'],JSON_UNESCAPED_UNICODE);
		$Data["Default_Shipping"]= isset($_POST["Default_Shipping"])?$_POST["Default_Shipping"]:null;
		$Data["Default_Business"] = isset($_POST["Default_Business"])?$_POST["Default_Business"]:'express';
		
		$Set = $DB->Set('shop_config',$Data,"where Users_ID= '".$_SESSION["Users_ID"]."'");
	
		$flag = $flag&&$Set;
	
		
	}
	
	
	if($flag)
	{
		mysql_query("commit");
		echo '<script language="javascript">alert("保存成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
	}else{
		mysql_query("roolback");
		echo '<script language="javascript">alert("保存失败");history.go(-1);</script>';
	}
	exit;
}else{
	
	$rsConfig =$DB->GetRs("users_payconfig","*","where Users_ID='".$_SESSION["Users_ID"]."'");
	if(!$rsConfig){
		$Data = array(
			'Users_ID'=>$_SESSION["Users_ID"]
		);
		$DB->Add("users_payconfig",$Data);
		$rsConfig=$DB->GetRs("users_payconfig","*","where Users_ID='".$_SESSION["Users_ID"]."'");
	}
	
	//获取物流模板设置信息
	$rsShippingConfig = $DB->getRs('shop_config','Shipping,Default_Shipping,Default_Business',"where Users_ID='".$_SESSION["Users_ID"]."'");
	$Shipping_Config = json_decode($rsShippingConfig['Shipping'],true);
	$Shipping_Brief = get_shipping_brief($_SESSION["Users_ID"]);

	
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
<script type='text/javascript'>
$(document).ready(shop_obj.pay_shipping_config_init);
	 
</script>
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
            <td width="33%">选择默认物流模板</td>
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
									<dd>商品类别码：<input type="text" name="PaymentYeepayProductCatalog" value="7" /></dd>
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
           	<?php if(!empty($Shipping_Brief['Shipping_List'])):?>
                <form action="shopping.php" method="post" id="shipping_default_config">
                	<table style="width:100%;">
                <tbody>
                   	<tr>
                   	<td>快递公司</td><td>物流模板</td><td>首选物流公司</td>
                    </tr>
                   <?php foreach($Shipping_Brief['Shipping_List'] as $key=>$company):?>
                   	<tr>
                    	<td><?=$company['Shipping_Name']?></td>
                    	<td>
                        	<?php if(!empty($Shipping_Brief['Template_Dropdown'][$company['Shipping_ID']])): ?>
                            	<select name="Default_Template[<?=$company['Shipping_ID']?>]" notnull >
                                	<option value="">请选择默认物流模板</option>
                                    <?php foreach($Shipping_Brief['Template_Dropdown'][$company['Shipping_ID']] as $k=>$template):?>
                                    	<?php
											
											//如果存在此快递公司的默认模板配置
											//并且默认模板配置ID等于当前模板ID
											if(!empty($Shipping_Config[$company['Shipping_ID']])&&$Shipping_Config[$company['Shipping_ID']] == $template['Template_ID']){											
												$selected = 'selected';
											}else{
												$selected = '';
											}
										
										?>
                                    <option value="<?=$template['Template_ID']?>"  <?=$selected;?> ><?=$template['Template_Name']?></option>
                                    <?php endforeach;?>	
                                   
                                </select>
                            <?php else: ?>
                                请去添加物流模板
                            <?php endif; ?>
                        </td>
                    	<td>
             <?php if(!empty($rsShippingConfig['Default_Shipping'])):?>
             	<?php $Default_Shipping = $rsShippingConfig['Default_Shipping']; ?>
             	  <input type="radio" name="Default_Shipping" class="Default_Shipping" value="<?=$company['Shipping_ID']?>" <?=($Default_Shipping == $company['Shipping_ID'])?'checked':''?> />
             <?php else: ?>
            	 <input type="radio" name="Default_Shipping" class="Default_Shipping" value="<?=$company['Shipping_ID']?>" <?=($key == 0)?'checked':''?> />
			 <?php endif;?>
                        </td>
                    </tr>
				   <?php endforeach; ?>
                   
               		</tbody>
                </table>
                
                <dl>
                	<dd>默认发货方式&nbsp;&nbsp;<input type="radio" name="Default_Business" <?=$rsShippingConfig['Default_Business'] == 'express'?'checked':''?> value="express"  /><label>快递</label> 
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <input type="radio" name="Default_Business" value="common"  <?=$rsShippingConfig['Default_Business'] == 'common'?'checked':''?> />  <label>平邮</label></dd>
                </dl>
                
                	<input type="hidden" name="action" value="shipping">
                	
                <div class="submit" >
                 	<input class="btn_green" type="submit" value="提交保存" />
              
                </div>
                </form>
            <?php else: ?>
            	<p><br/><br/>请先添加物流公司及其模板</p>
			<?php endif; ?>   
            </td>
            
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script type="text/javascript">
function show_pay_ment(id){
	for(var i=0; i<=3; i++){
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