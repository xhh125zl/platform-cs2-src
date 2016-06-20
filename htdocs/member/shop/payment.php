<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/balance.class.php');
$balance = new balance($DB,$_SESSION["Users_ID"]);

$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";
if(isset($_GET["search"])){
	if($_GET["search"]==1){
		if(!empty($_GET["Keyword"])){
			$condition .= " and ".$_GET["Fields"]." like '%".$_GET["Keyword"]."%'";
		}
		if(isset($_GET["Status"])){
			if($_GET["Status"]<>''){
				$condition .= " and Status=".$_GET["Status"];
			}
		}
		if($_GET['BizID']>0){
			$condition .= " and Biz_ID=".$_GET['BizID'];
		}
		if(!empty($_GET["AccTime_S"])){
			$condition .= " and CreateTime>=".strtotime($_GET["AccTime_S"]);
		}
		if(!empty($_GET["AccTime_E"])){
			$condition .= " and CreateTime<=".strtotime($_GET["AccTime_E"]);
		}
	}
}

if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		$paymentid=empty($_GET['paymentid'])?0:$_GET['paymentid'];
		$item = $DB->GetRs("shop_sales_payment","Status","where Payment_ID=".$paymentid);
		if($item["Status"]<>0){
			echo '<script language="javascript">alert("该收款单已确认收款，不得删除");history.back();</script>';
			exit;
		}
		$DB->Set("shop_sales_record",array("Record_Status"=>0,"Payment_ID"=>0),"where Payment_ID=".$paymentid);
		$DB->Del("shop_sales_payment","Payment_ID=".$paymentid);
		echo '<script language="javascript">window.location.href="payment.php";</script>';
	}
}

$condition .= " order by Payment_ID desc";

$lists = array();
$DB->GetPage("shop_sales_payment","*",$condition,20);
while($r=$DB->Fetch_assoc()){
	$lists[$r["Payment_ID"]] = $r;
}
$_STATUS = array('<font style="color:red">未收款</font>','<font style="color:blue">已收款</font>');
if($_POST){

  
	$bankmobile = empty($_POST['BankMobile'])?0:$_POST['BankMobile'];

	$payment_object = $DB->GetRs("user","Users_ID,User_ID,User_Mobile,User_OpenID","where User_Mobile=".$bankmobile);
	if(empty($payment_object)){
		echo 0;
		exit;
	}	
	$pay_price = $_POST["price"];
	if($pay_price<0){
		echo '<script language="javascript">alert("金额必须大于零才可使用微信转帐");history.back();</script>';
		exit;
	}
	require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
	$pay_order = new pay_order($DB, 0);

	$Data = $pay_order->withdraws($payment_object["Users_ID"],$payment_object['User_ID'],$pay_price); 

	if($Data["status"]==1){
		$DB->Set("shop_sales_payment",array("Status"=>1),"where Payment_ID=".$paymentid);
		unset($_Get);
		echo '<script language="javascript">alert("操作成功");window.location="withdraw.php";</script>';
		exit;
	}else{
		unset($_Get);

                echo '<script language="javascript">alert("'.$Data["msg"].'");window.location="withdraw.php";</script>';

		exit;
	}	
	// echo '<pre>';
	// print_R($payment_object);exit;
}
  
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<style>
	.btn_greens{
		display: block;
		height: 30px;
		line-height: 30px;
		border: none;
		width: 145px;
		border-radius: 5px;
		text-align: center;
		text-decoration: none;
		float: left;
	}
	
</style>
<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/payment.js'></script>
    <script language="javascript">$(document).ready(payment.orders_init);</script>
    <div class="r_nav">
      <ul>
        <li><a href="sales_record.php">销售记录</a></li>
        <li class="cur"><a href="payment.php">付款单</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <div id="orders" class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="?">
      	<select name="Fields">
          <option value="Bank">银行类型</option>
          <option value='BankNo'>银行卡号</option>
          <option value='BankName'>收款人</option>
          <option value='BankMobile'>收款人手机</option>
        </select>
        <input type="text" name="Keyword" value="" class="form_input" size="15" />&nbsp;
        商家
        <select name='BizID'>
          <option value='0'>--请选择--</option>
          <?php
          	$DB->get("biz","*","where Users_ID='".$_SESSION["Users_ID"]."'");
			while($value = $DB->fetch_assoc()){
		  		echo '<option value="'.$value["Biz_ID"].'">'.$value["Biz_Name"].'</option>';
		  	}
		  ?>
        </select>&nbsp;
        是否结算：
        <select name="Status">
          <option value="">全部</option>
          <option value='0'>未结算</option>
          <option value='1'>已结算</option>
        </select>&nbsp;
        时间：
        <input type="text" class="input" name="AccTime_S" value="" maxlength="20" />
        -
        <input type="text" class="input" name="AccTime_E" value="" maxlength="20" />
        <input type="submit" class="search_btn" value="搜索" />
        <input type="hidden" value="1" name="search" />
      </form>
      <div class="control_btn"><a href="payment_add.php" class="btn_green btn_w_120">生成付款单</a></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table" id="order_list">
        <thead>
          <tr>
            <td width="6%" nowrap="nowrap">序号</td>
            <td width="12%" nowrap="nowrap">商家</td>
            <td width="10%" nowrap="nowrap">付款单号</td>
            <td width="10%" nowrap="nowrap">结算时间</td>
            <td width="7%" nowrap="nowrap">应收总额</td>
            <td width="7%" nowrap="nowrap">优惠金额</td>
            <td width="7%" nowrap="nowrap">网站所得</td>
            <td width="7%" nowrap="nowrap">分销佣金</td>
            <td width="7%" nowrap="nowrap">结算金额</td>
            <td width="7%" nowrap="nowrap">状态</td>
            <td width="10%" nowrap="nowrap">支付</td>

	    <td width="10%" nowrap="nowrap">生成时间</td>

            <td width="10%" nowrap="nowrap">操作</td>
          </tr>
        </thead>
        <tbody>
         <?php

        $DB->Get('biz','*',"where Users_ID='".$_SESSION["Users_ID"]."'");
        while($BizRs = $DB->fetch_assoc()){
             $BizPayRate[$BizRs["Biz_ID"]] = empty($BizRs['PaymenteRate'])?'100':$BizRs['PaymenteRate'];    
        }

          foreach($lists as $paymentid=>$value){
			  if($value["Biz_ID"]==0){
				  $value["Biz_Name"] = "本站供货";
			  }else{
				  $item = $DB->GetRs("biz","Biz_Name","where Biz_ID=".$value["Biz_ID"]);
				  if($item){
					  $value["Biz_Name"] = $item["Biz_Name"];
				  }else{
					  $value["Biz_Name"] = "已被删除";
				  }
			  }
			 
		 ?>
         <tr>
            <td nowrap="nowrap" class="paymentid"><?php echo $paymentid;?></td>
            <td nowrap="nowrap"><?php echo $value["Biz_Name"];?></td>
            <td nowrap="nowrap"><?php echo $value["Payment_Sn"];?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$value["FromTime"]);?><br />~<br /><?php echo date("Y-m-d H:i:s",$value["EndTime"]);?></td>
            <td nowrap="nowrap"><font style="color:#F60"><?php echo $value["Amount"];?></font></td>
            <td nowrap="nowrap"><?php echo $value["Diff"];?></td>
            <td nowrap="nowrap"><?php echo $value["Web"];?></td>
            <td nowrap="nowrap"><?php echo $value["Bonus"];?></td>

            <td nowrap="nowrap"><font style="color:blue"><?php echo $value["Total"];?></font><br>(转账
           <span><?php echo $value["Total"]*$BizPayRate[$value["Biz_ID"]]/100;?></span>
           <?php echo"+转向余额";echo $value["Total"]-($value["Total"]*$BizPayRate[$value["Biz_ID"]]/100); echo ")"
           ?> 
            </td>

            <td nowrap="nowrap"><?php echo $_STATUS[$value["Status"]];?></td>
			<td nowrap="nowrap">
			<?php if($value["Status"]==0){?>
				<a href="#" class="btn_green btn_w_120 weixin" style="width:70px; margin:0px;">微信转账</a></td>
			<?php }else{?>
				<a href="#"  class="btn_greens" style="width:70px; margin:0px; color:#FFF; background-color:#494A4A;">微信转账</a></td>
			<?php } ?>
			
			<td nowrap="nowrap" style="display:none;"><?php echo $value["Payment_ID"];?></td>
			<td nowrap="nowrap" style="display:none;"><?php echo $value["Payment_Sn"];?></td>
			<td nowrap="nowrap" style="display:none;"><?php echo $value["BankMobile"];?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$value["CreateTime"]);?></td>
            <td nowrap="nowrap">
            	<a href="payment_detail.php?paymentid=<?php echo $paymentid;?>">[查看详情]</a>
                <?php if($value["Status"]==0){?><a href="?action=del&paymentid=<?php echo $paymentid;?>">[删除]</a><?php }?>
            </td>
          </tr>
         <?php }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>
<script type='text/javascript'>
	$(".weixin").click(function(){
		 // var  price = $(this).parent().prev().prev().children().html(); 
                  var  price = $(this).parent().prev().prev().children().next().next().html(); 

		  var Payment_ID = $(this).parent().next().html();
		  var Payment_Sn = $(this).parent().next().next().html();
		  var BankMobile = $(this).parent().next().next().next().html();
		  //var BankMobile = 222222222;
		  if(confirm("您确定要用微信支付吗？")){
			$.ajax({
               type: "POST",
               url: "?",
               data: {"price" : price,"Payment_ID":Payment_ID,"Payment_Sn":Payment_Sn,"BankMobile":BankMobile},
               dataType: 'json',
               success: function(data){
                    if(data == 0){
					   alert('手机号码不一致，请联系商家！！');
					}
               }
			}); 
		  }
          
		
	});
	 

</script>