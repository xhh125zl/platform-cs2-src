<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/Alipay.php');

$UsersID = $_SESSION["Users_ID"];

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$price = json_decode($setting["sms_price"],true);
/*如果此订单不存在，则创建此订单*/
if($_POST){
	$qty = $_POST['Qty'];
	$money = 0;
	if(!empty($price)){
		foreach($price as $k=>$v){
			if($v["max"]>0){
				if($qty>$v["min"] && $qty<=$v["max"]){
					$money = $qty*$v["price"];
					break;
				}
			}else{
				if($qty>$v["min"]){
					$money = $qty*$v["price"];
					break;
				}
			}
		}
	}
	if($money==0){
		echo "系统未设置你购买的数量的价格";
		exit;
	}
	$Order_Sn = build_order_sn();
	$data = array();
	$data = array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Record_Sn"=>$Order_Sn,
		"Record_CreateTime"=>time(),
		"Record_Qty"=>$_POST['Qty'],
		"Record_Type"=>1,
		"Record_Money"=>$money,
		"Record_Status"=>0
	);

	$Flag = $DB->Add("users_money_record",$data);
    $record_id = $DB->insert_id();
	$data['description'] = "购买短信".$_POST['Qty']."条";
	
}else{
	//否则取出
	if(isset($_GET['ID'])){
		$record_id = $_GET['ID'];
	}else{
		echo '缺少记录ID';
	}
	$condition = "where Users_ID= '".$_SESSION['Users_ID']."' and Record_ID =".$record_id;
	$data = $DB->getRs('users_money_record','*',$condition);
	$data['description'] = "购买短信".$data['Record_Qty']."条";	
}

//生成支付宝支付表单
$alipay_cnf = get_alipay_conf($DB,$UsersID);
$alipay = new Alipay($alipay_cnf);
$alipay_form =  $alipay->get_payreq_html($data);

function build_order_sn()
{
    /* 选择一个随机的方案 */
    mt_srand((double) microtime() * 1000000);
    return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>您的购买信息</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>

</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
   
    <div class="r_nav">
      <ul>
      
        <li class="cur"><a href="#">购买信息</a></li>
   
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <script language="javascript">$(document).ready(function(){
		$(document).ready(function(){
			setInterval(is_payed,500);
			//每200ms,请求一次,查看此订单是否已经支付
			$('#alipay_button').click(function(){
				$("#alipaysubmit").submit();
			
			});
		});
		
		function is_payed(){
			var record_id = $("#record_id").val();
			var param = {action:'is_payed',record_id:record_id};
			
			$.post('/member/ajax.php',param,function(data){
				
				if(data.status == 1){
				    if(data.record_status == 1){	
						window.location.href = '/sms/sms_record.php';
					}
				
				}
			},'json');
		
		}
					
	});</script>
    <div class="r_con_config r_con_wrap">

	<div class="r_con_form">
    	
     
    	<div class="rows">
        	<input type="hidden" name="record_id"  id="record_id" value="<?=$record_id?>"/>
        	<label>购买数量</label>
    		<span class="input">
             <?=$data['Record_Qty']?>条
            </span>
            <div class="clear"></div>
        </div>
        
       
        <div class="rows">
        	<label>费用总和</label>
    		<span class="input fc_red">
            <?=$data['Record_Money']?>元
            </span>
            <div class="clear"></div>
        
        </div>
        
         <div class="rows">
        	<label>续费号</label>
    		<span class="input">
            <?=$data['Record_Sn'];?>
            </span>
            <div class="clear"></div>
        </div>
        
        <div class="rows">
        	<label>&nbsp;&nbsp;</label>
        	<span class="input">
            <?=$alipay_form?>
            <button class="btn btn-sm btn-info" id="alipay_button">支付宝支付</button>
            </span>
            <div class="clear"></div>
        </div>
	 </div>

</div>
</div>
</body>
</html>
	