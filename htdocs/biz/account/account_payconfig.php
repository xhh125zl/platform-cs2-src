<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/flow.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/AES.php');

if(isset($_POST['action']) && $_POST['action']=='ajax'){
    //Ajax提交，轮询是否更改openid成功
    if(!isset($_SESSION["BIZ_ID"])){
        $Data=array(
            'status' => 0,
            'url' => '/biz/login.php'
        );
        die(json_encode($Data));
    }
    $Biz_Id = $_SESSION["BIZ_ID"];
    $res = $DB->GetRs("biz","Biz_ID,Biz_Flag,Biz_PayConfig","WHERE Biz_ID='{$Biz_Id}' and Biz_Flag=1");
    if(empty($res)){
        $Data=array(
            'status' => -1,
        );
        die(json_encode($Data));
    }else{
        $DB->Set("biz", array('Biz_Flag' => 0), "WHERE Biz_ID='{$Biz_Id}' and Biz_Flag=1");
        if(!empty($res['Biz_PayConfig'])){
            $biz_PayConfig = json_decode($res['Biz_PayConfig'],true);
        }
        $Data=array(
            'status' => 1,
            'openid' => isset($biz_PayConfig['config']['OpenID'])?$biz_PayConfig['config']['OpenID']:'',
            'nickname'=>isset($biz_PayConfig['config']['nickname'])?$biz_PayConfig['config']['nickname']:'',
            'nickname'=>isset($biz_PayConfig['config']['headimgurl'])?$biz_PayConfig['config']['headimgurl']:''
        );
        die(json_encode($Data));
    }
}

$DB->showErr=false;
if(!isset($_SESSION["BIZ_ID"]) || empty($_SESSION["BIZ_ID"])){
    header("location:/biz/login.php");
}
$Biz_Id = $_SESSION["BIZ_ID"];
$Biz_Info = $DB->GetRs("biz","Biz_ID,Users_ID,Biz_Account,Biz_Name,Biz_PayConfig,Biz_PassWord","WHERE Biz_ID='{$Biz_Id}'");
$biz_PayConfig = array();
if(!empty($Biz_Info['Biz_PayConfig'])){
    $biz_PayConfig = json_decode($Biz_Info['Biz_PayConfig'],true);
}
$Biz_Users_ID = $Biz_Info["Users_ID"];
$Biz_Name = $Biz_Info["Biz_Name"];
$authCode = array(
    'Users_ID' => $Biz_Users_ID,
    'Biz_ID' => $Biz_Info['Biz_ID'],
    'Biz_Account' => $Biz_Info['Biz_Account']
);

$authCode = base64_encode(serialize($authCode));
$authKey =  md5($Biz_Info['Biz_Account'].$Biz_Info['Users_ID']); //私钥
$auth = Security::encrypt($authCode, $authKey); //加密后的字符串
$pay = getPayConfig($Biz_Users_ID, true);
$url = 'http://'.$_SERVER['HTTP_HOST'].'/biz/openid.php?auth='.$auth.'&Biz_ID='.$Biz_Id;
$qrcode = createQrcode($url);

if($_POST){
	
	switch($_POST['PaymentID'])
	{
	    case 1:
	        {
	            $Data['OpenID'] = $_POST["OpenID"];
	            $Data['PaymentMethod'] = "微信结算";
	            break;
	        }
	    case 2:
	        {
	            $Data['aliPayNo'] = $_POST["aliPayNo"];
	            $Data['aliPayName'] = $_POST["aliPayName"];
	            $Data['PaymentMethod'] = "支付宝结算";
	            break;
	        }
	    case 3:
	        {
	            $Data['Bank'] = $_POST["Bank"];
	            $Data['BankNo'] = $_POST["BankNo"];
	            $Data['BankName'] = $_POST["BankName"];
	            $Data['BankMobile'] = $_POST["BankMobile"];
	            $Data['PaymentMethod'] = "银行转账结算";
	            break;
	        }
	}
	
	$Data = json_encode(array('PaymentID' => $_POST['PaymentID'], 'config' => $Data),JSON_UNESCAPED_UNICODE);
	$Flag=$DB->Set("biz", "Biz_PayConfig = '{$Data}'" ,"where Biz_ID=".$Biz_Id);
	if($Flag){
		echo '<script language="javascript">alert("修改成功");window.location="account_payconfig.php";</script>';
	}else{
		echo '<script language="javascript">alert("修改失败");history.back();</script>';
	}
	exit;
}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<link href="/static/css/select2.css" rel="stylesheet"/>
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src="/static/js/select2.js"></script>

</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
      <ul>
        <li><a href="account.php">商家资料</a></li>
        <li><a href="account_edit.php">修改资料</a></li>
		<li><a href="address_edit.php">收货地址</a></li>
        <li><a href="account_password.php">修改密码</a></li>
        <li class="cur"><a href="account_payconfig.php">结算配置</a></li>
      </ul>
    </div>
    <div id="bizs" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      <form class="r_con_form" method="post" action="?" id="biz_edit">
            <div class="rows">
              <label>付款方式</label>
              <span class="input time">
                  <select name='PaymentID'>
                  		<?php $count = 0; ?>
                  		<?php foreach ($pay as $key => $value): ?>
                  		<?php
                  		    $selected = $count ==0 ?"selected":"";
                  		    $count++;
                  		?>
                  		<option value="<?=$value['ID'] ?>" <?=$selected ?>><?=$value['Name'] ?></option>
                  		<?php endforeach;?>
        		  		<option value="3">银行转账</option>
                  </select>&nbsp;
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>
        	<div class="rows">
              <label>微信识别码</label>
              <span class="input">
              <input name="OpenID" value="<?php if(!empty($biz_PayConfig) && $biz_PayConfig['PaymentID'] ==1){ echo $biz_PayConfig['config']['OpenID']; } ?>" type="text" class="form_input" size="40" maxlength="100" notnull>
              <font class="fc_red">* (用微信扫描二维码绑定OpenID) </font> 
              </span>
              <div style="position:absolute;left:52%;z-index:9999;">
              <span class="tips"><img src="<?=$qrcode ?>" width="180"/></span>
              </div>
              <div class="clear"></div>
            </div>
            <div id="nickname" class="rows">
                <?php  if(isset($biz_PayConfig['config']['headimgurl']) && $biz_PayConfig['config']['headimgurl']){ ?>
                <label>微信昵称</label>
                <span class="input" style="font-size:20px;"><img src="<?=$biz_PayConfig['config']['headimgurl'] ?>" width="22"/> <?=$biz_PayConfig['config']['nickname'] ?></span>
                <?php } ?>
                <div class="clear"></div>
            </div>
            <div class="rows">
              <label>银行类型</label>
              <span class="input">
              <input name="Bank" value="<?php if(!empty($biz_PayConfig) && $biz_PayConfig['PaymentID'] ==3){ echo $biz_PayConfig['config']['Bank']; } ?>" type="text" class="form_input" size="40" maxlength="100" notnull>
              <font class="fc_red">*</font> <span class="tips">如交通银行，***分行</span></span>
              <div class="clear"></div>
            </div>
            <div class="rows">
              <label>银行卡号</label>
              <span class="input">
              <input name="BankNo" value="<?php if(!empty($biz_PayConfig) && $biz_PayConfig['PaymentID'] ==3){ echo $biz_PayConfig['config']['BankNo']; } ?>" type="text" class="form_input" size="40" maxlength="100" notnull>
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>
            <div class="rows">
              <label>收款人</label>
              <span class="input">
              <input name="BankName" value="<?php if(!empty($biz_PayConfig) && $biz_PayConfig['PaymentID'] ==3){ echo $biz_PayConfig['config']['BankName']; } ?>" type="text" class="form_input" size="40" maxlength="100" notnull>
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>
            <div class="rows">
						<label>付款账户</label> <span class="input"> <input name="aliPayNo"
							value="<?php if(!empty($biz_PayConfig) && $biz_PayConfig['PaymentID'] ==3){ echo isset($biz_PayConfig['config']['aliPayNo'])?$biz_PayConfig['config']['aliPayNo']:""; } ?>"
							type="text" class="form_input" size="40" maxlength="100" notnull>
							<font class="fc_red">*</font></span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<label>付款账户名</label> <span class="input"> <input name="aliPayName"
							value="<?php if(!empty($biz_PayConfig) && $biz_PayConfig['PaymentID'] ==3){ echo isset($biz_PayConfig['config']['aliPayName'])?$biz_PayConfig['config']['aliPayName']:""; } ?>"
							type="text" class="form_input" size="40" maxlength="100" notnull>
							<font class="fc_red">*</font></span>
						<div class="clear"></div>
					</div>
            <div class="rows">
              <label>收款人手机</label>
              <span class="input">
              <input name="BankMobile" value="<?php if(!empty($biz_PayConfig) && $biz_PayConfig['PaymentID'] ==3){ echo $biz_PayConfig['config']['BankMobile']; } ?>" type="text" class="form_input" size="40" maxlength="100" notnull>
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>
            <div class="rows">
              <label></label>
              <span class="input">
              <input type="submit" class="btn_green" name="submit_button" value="提交保存" /></span>
              <div class="clear"></div>
            </div>
      </form>
    </div>
  </div>
</div>
<script>
$(function(){
	var paymentid = parseInt($("select[name='PaymentID']").val());
	if(paymentid==1){
		//每3秒进行轮询
		var instance = window.setInterval(function () {
			$.post("/biz/account/account_payconfig.php",{action:'ajax'},function(data){
				if(data.status==0){
					top.location.href = data.url; 
				}else if(data.status==1){	//成功获取并更改openid
          window.clearInterval(instance);
					alert("微信openid已设置成功");
					var openid = data.openid;
					location.reload();
				}
			},"json");
		},3000);
	}
});
</script>
<script>
$(function(){
	call();
	$("select[name='PaymentID']").change(function(){
		call();
	});
	$("input[name='submit_btn']").submit(function(){
		var paymentid = parseInt($("select[name='PaymentID']").val());
		if(paymentid===1){
			var openid = $("input[name='OpenID']").val();
			
			if(openid=="" || openid==null){
				alert("微信识别码OpenID 不能为空");
				return false;
			}
		} else if (paymentid === 2){
			var aliPayNo = $("input[name='aliPayNo']").val(),
				aliPayName = $("input[name='aliPayName']").val();
			
			if ( aliPayNo ==="" || aliPayNo ===null ){
				alert("支付宝帐号不能为空");
				return false;
			}
			if ( aliPayName ==="" || aliPayName ===null ){
				alert("支付宝账户名不能为空");
				return false;
			}
		} else if (paymentid === 3){
			var Bank = $("input[name='Bank']").val(),
				BankNo = $("input[name='BankNo']").val(),
    			BankName = $("input[name='BankName']").val(),
    			BankMobile = $("input[name='BankMobile']").val();
			
			if ( Bank ==="" || Bank === null ){
				alert("银行类型不能为空");
				return false;
			}
			if ( BankNo ==="" || BankNo === null ){
				alert("银行卡号不能为空");
				return false;
			}  
			if ( BankName ==="" || BankName === null ){
				alert("收款人不能为空");
				return false;
			} 
			if ( BankMobile ==="" || BankMobile === null ){
				alert("收款人手机不能为空");
				return false;
			}   
		}
	});
});


function call()
{
	var paymentid = parseInt($("select[name='PaymentID']").val());
	switch(paymentid)
	{
    	case 1:		//微信支付
    	{
    		$("input[name='aliPayNo']").parent().parent().hide();
    		$("input[name='aliPayName']").parent().parent().hide();
    		$("input[name='OpenID']").parent().parent().show();
    		$("input[name='Bank']").parent().parent().hide();
    		$("input[name='BankNo']").parent().parent().hide();
    		$("input[name='BankName']").parent().parent().hide();
    		$("input[name='BankMobile']").parent().parent().hide();
    		$("#nickname").show();
    		break;
    	}
    	case 2:		//支付宝支付
    	{
    		$("input[name='aliPayNo']").parent().parent().show();
    		$("input[name='aliPayName']").parent().parent().show();
    		$("input[name='OpenID']").parent().parent().hide();
    		$("input[name='Bank']").parent().parent().hide();
    		$("input[name='BankName']").parent().parent().hide();
    		$("input[name='BankNo']").parent().parent().hide();
    		$("input[name='BankMobile']").parent().parent().hide();
    		$("#nickname").hide();
    		break;
    	}
    	case 3:
    	{
    		$("input[name='aliPayNo']").parent().parent().hide();
    		$("input[name='aliPayName']").parent().parent().hide();
    		$("input[name='OpenID']").parent().parent().hide();
    		$("input[name='Bank']").parent().parent().show();
    		$("input[name='BankNo']").parent().parent().show();
    		$("input[name='BankName']").parent().parent().show();
    		$("input[name='BankMobile']").parent().parent().show();
    		$("#nickname").hide();
    		break;
    	}
	}
}
</script>
</body>
</html>