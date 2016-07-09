<?php
require_once ('../global.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/helper/flow.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/helper/balance.class.php');

$BizRs = $DB->GetRS('biz','Users_ID,UserID',"where Users_ID='".$rsBiz["Users_ID"]."' and BIZ_ID=".$_SESSION["BIZ_ID"]);
if(empty($BizRs['UserID'])){
    echo '<script language="javascript">alert("您没有绑定前台会员,暂不能结款!");history.back();</script>';
    exit;
}
$biz_PayConfig = array();
if (! empty($rsBiz['Biz_PayConfig'])) {
    $biz_PayConfig = json_decode($rsBiz['Biz_PayConfig'], true);
}
$Biz_Users_ID = $rsBiz["Users_ID"];
$Biz_Name = $rsBiz["Biz_Name"];
$pay = getPayConfig($Biz_Users_ID, true);

$balance = new balance($DB, $rsBiz["Users_ID"]);
if ($_POST) {
    $Time = empty($_POST["Time"]) ? array(
        time(),
        time()
    ) : explode(" - ", $_POST["Time"]);
    $StartTime = strtotime($Time[0]);
    $EndTime = strtotime($Time[1]);
    $condition = "WHERE Biz_ID=" . $_SESSION["BIZ_ID"] . " AND Users_ID='" . $rsBiz["Users_ID"] . "' AND Record_CreateTime>=" . $StartTime . " AND Record_CreateTime<=" . $EndTime . " AND Record_Status=0";
    $paymentinfo = $balance->create_payment($condition);
    if (!$paymentinfo || $paymentinfo["products_num"] == 0) {
        echo '<script language="javascript">alert("暂无结算数据");history.back();</script>';
        exit();
    }
    $createtime = time();
    $Data = array(
        "FromTime" => $StartTime,
        "EndTime" => $EndTime,
        "Payment_Type" => $_POST['PaymentID'],
        "Amount" => $paymentinfo["alltotal"],
        "Diff" => $paymentinfo["cash"],
        "Web" => $paymentinfo["web"] - $paymentinfo["bonus"],
        "Bonus" => $paymentinfo["bonus"],
        "Total" => $paymentinfo["supplytotal"],
        "CreateTime" => $createtime,
        "Biz_ID" => $_SESSION["BIZ_ID"],
        "Users_ID" => $rsBiz["Users_ID"],
        "Status" => 3
    );
    switch ($_POST['PaymentID']) {
        case 1:
            {
                $Data['OpenID'] = $_POST["OpenID"];
                break;
            }
        case 2:
            {
                $Data['aliPayNo'] = $_POST["aliPayNo"];
                $Data['aliPayName'] = $_POST["aliPayName"];
                break;
            }
        case 3:
            {
                $Data['Bank'] = $_POST["Bank"];
                $Data['BankNo'] = $_POST["BankNo"];
                $Data['BankName'] = $_POST["BankName"];
                $Data['BankMobile'] = $_POST["BankMobile"];
                break;
            }
    }
    $Flag = $DB->Add("shop_sales_payment", $Data);
    $paymentid = $DB->insert_id();
    if ($Flag) {
        $DB->Set("shop_sales_record", array(
            "Payment_ID" => $paymentid,
            "Record_Status"=>3
        ), $condition);
        $Payment_Sn = $createtime . $paymentid;
        $DB->Set("shop_sales_payment", array(
            "Payment_Sn" => $Payment_Sn
        ), "WHERE Payment_ID=" . $paymentid);
        echo '<script language="javascript">window.location.href="payment_detail.php?paymentid=' . $paymentid . '";</script>';
    } else {
        echo '<script language="javascript">alert("生成失败！");history.back();</script>';
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>结算申请</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet'
	type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
	<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
	<style type="text/css">
body, html {
	background: url(/static/member/images/main/main-bg.jpg) left top fixed
		no-repeat;
}
</style>
	<div id="iframe_page">
		<div class="iframe_content">
			<link href='/static/member/css/shop.css' rel='stylesheet'
				type='text/css' />
			<script type='text/javascript' src='/static/member/js/payment.js'></script>
			<div class="r_nav">
				<ul>
					<li><a href="sales_record.php">销售记录</a></li>
					<li class="cur"><a href="payment.php">付款单</a></li>
				</ul>
			</div>
			<div id="payment" class="r_con_wrap">
				<link href='/static/js/plugin/operamasks/operamasks-ui.css'
					rel='stylesheet' type='text/css' />
				<script type='text/javascript'
					src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
				<script type='text/javascript'
					src='/static/js/plugin/daterangepicker/moment_min.js'></script>
				<link href='/static/js/plugin/daterangepicker/daterangepicker.css'
					rel='stylesheet' type='text/css' />
				<script type='text/javascript'
					src='/static/js/plugin/daterangepicker/daterangepicker.js'></script>
				<script language="javascript">$(document).ready(payment.payment_edit_init);</script>
				<form id="payment_form" class="r_con_form" method="post" action="?">
					<div class="rows">
						<label>付款方式</label> <span class="input time"> <select
							name='PaymentID'>
          				<?php $count = 0; ?>
                  		<?php foreach ($pay as $key => $value): ?>
                  		<?php
                        $selected = $count == 0 ? "selected" : "";
                        $count ++;
                        ?>
                  		<option value="<?=$value['ID'] ?>" <?=$selected ?>><?=$value['Name'] ?></option>
                  		<?php endforeach;?>
        		  		<option value="3">银行转账</option>
						</select>&nbsp; <font class="fc_red">*</font></span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<label>结算时间</label> <span class="input time"> <input name="Time"
							type="text"
							value="<?php echo date("Y-m-01 00:00:00") ?> - <?php echo date("Y-m-d H:i:s",strtotime("+7 day")) ?>"
							class="form_input" size="40" readonly notnull /> <font
							class="fc_red">*</font> <span class="tips">需要结算的销售记录的时间段</span></span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<label>微信识别码</label> <span class="input"> <input name="OpenID"
							value="<?php if(!empty($biz_PayConfig) && $biz_PayConfig['PaymentID'] ==1){ echo $biz_PayConfig['config']['OpenID']; } ?>"
							type="text" class="form_input" size="40" maxlength="100" notnull>
							<font class="fc_red">*</font> <span class="tips">商家微信OpenID</span></span>
						<div class="clear"></div>
					</div>
					<div id="nickname" class="rows">
                <?php  if(isset($biz_PayConfig['config']['headimgurl']) && $biz_PayConfig['config']['headimgurl']){ ?>
                <label>微信昵称</label> <span class="input"
							style="font-size: 20px;"><img
							src="<?=$biz_PayConfig['config']['headimgurl'] ?>" width="22" /> <?=$biz_PayConfig['config']['nickname'] ?></span>
                <?php } ?>
                <div class="clear"></div>
					</div>
					<div class="rows">
						<label>银行类型</label> <span class="input"> <input name="Bank"
							value="<?php if(!empty($biz_PayConfig) && $biz_PayConfig['PaymentID'] ==3){ echo $biz_PayConfig['config']['Bank']; } ?>"
							type="text" class="form_input" size="40" maxlength="100" notnull>
							<font class="fc_red">*</font> <span class="tips">如交通银行，***分行</span></span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<label>银行卡号</label> <span class="input"> <input name="BankNo"
							value="<?php if(!empty($biz_PayConfig) && $biz_PayConfig['PaymentID'] ==3){ echo $biz_PayConfig['config']['BankNo']; } ?>"
							type="text" class="form_input" size="40" maxlength="100" notnull>
							<font class="fc_red">*</font></span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<label>收款人</label> <span class="input"> <input name="BankName"
							value="<?php if(!empty($biz_PayConfig) && $biz_PayConfig['PaymentID'] ==3){ echo $biz_PayConfig['config']['BankName']; } ?>"
							type="text" class="form_input" size="40" maxlength="100" notnull>
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
						<label>收款人手机</label> <span class="input"> <input name="BankMobile"
							value="<?php if(!empty($biz_PayConfig) && $biz_PayConfig['PaymentID'] ==3){ echo $biz_PayConfig['config']['BankMobile']; } ?>"
							type="text" class="form_input" size="40" maxlength="100" notnull>
							<font class="fc_red">*</font></span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<label></label> <span class="input"> <input type="submit"
							class="btn_green" value="一键生成" name="submit_btn"></span>
						<div class="clear"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
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