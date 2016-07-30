<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/order.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
ini_set("display_errors","On");  

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
$_STATUS=array("待确认","待付款","已付款","已发货","已完成");
$step = isset($_GET["step"]) ? $_GET["step"] : 0;
if(isset($_GET["action"])){
	if($_GET["action"]=='search'){
		if(empty($_GET["code"])){
			echo '<script language="javascript">alert("请输入消费券码");history.back();</script>';
			exit;
		}else{
			$rsOrder = $DB->GetRs("user_order","*","where Users_ID='".$_SESSION["Users_ID"]."' and Order_Code='".$_GET["code"]."'");
			if(!$rsOrder){
				echo '<script language="javascript">alert("该订单不存在");history.back();</script>';
				exit;
			}
			$step = 1;
		}
	}
}
if($_POST){
	if(empty($_POST["code"])){
		echo '<script language="javascript">alert("非法提交");history.back();</script>';
		exit;
	}else{
		$code = trim($_POST["code"]);
	}
	if(empty($_POST["orderid"])){
		echo '<script language="javascript">alert("非法提交");history.back();</script>';
		exit;
	}else{
		$orderid = trim($_POST["orderid"]);
	}
	$rsOrder = $DB->GetRs("user_order","*","where Order_ID=".$orderid." and Order_Code='".$code."' and Users_ID='".$_SESSION["Users_ID"]."'");
	if(!$rsOrder){
		echo '<script language="javascript">alert("该订单不存在");history.back();</script>';
		exit;
	}
	if($rsOrder["Order_Status"]!=2){
		echo '<script language="javascript">alert("该订单为“'.$_STATUS[$rsOrder["Order_Status"]].'”状态，不能消费");history.back();</script>';
		exit;
	}
	
	confirm_receive($DB,$_SESSION["Users_ID"],$orderid);
	echo '<script language="javascript">alert("该订单消费成功");window.location="virtual_orders.php";</script>';
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
        <li><a href="orders.php">订单管理</a></li>
        <li class="cur"><a href="virtual_orders.php">消费认证</a></li>
      </ul>
    </div>
    
    <div id="orders" class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="?">
        消费认证码：
        <input type="text" name="code" value="<?php echo empty($_GET["code"]) ? '' : $_GET["code"];?>" class="form_input" size="15" />
        <input type="hidden" name="action" value="search" />
        <input type="submit" class="search_btn" value="搜索" />
      </form>
      <?php if($step==1){if(!empty($rsOrder)){?>
      <div class="detail_card">
        <?php if($rsOrder["Order_Status"]==2){?>
        <form method="post" action="?">
        <input type="hidden" name="code" value="<?php echo $rsOrder["Order_Code"];?>" />
        <input type="hidden" name="orderid" value="<?php echo $rsOrder["Order_ID"];?>" />
        <?php }?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order_info">
            <tr>
              <td width="8%" nowrap>订单编号：</td>
              <td width="92%"><?php echo date("Ymd",$rsOrder["Order_CreateTime"]).$rsOrder["Order_ID"] ?></td>
            </tr>
            <tr>
              <td nowrap>订单总价：</td>
              <td>￥<?php echo $rsOrder["Order_TotalPrice"] ?></td>
            </tr>
			<?php if($rsOrder["Coupon_ID"]>0){?>
			<tr>
			  <td nowrap>优惠详情</td>
			  <td><font style="color:blue;">已使用优惠券</font>(
				  <?php if($rsOrder["Coupon_Discount"]>0){?>
				  享受<?php echo $rsOrder["Coupon_Discount"]*10;?>折
				  <?php }?>
				  <?php if($rsOrder["Coupon_Cash"]>0){?>
				  抵现金<?php echo $rsOrder["Coupon_Cash"];?>元
				  <?php }?>)
			  </td>
			</tr>
			<?php }?>
            <tr>
              <td nowrap>订单时间：</td>
              <td><?php echo date("Y-m-d H:i:s",$rsOrder["Order_CreateTime"]) ?></td>
            </tr>
            <tr>
              <td nowrap>购买手机：</td>
              <td><?php echo $rsOrder["Address_Mobile"] ?></td>
            </tr>
            <tr>
              <td nowrap>消费券码：</td>
              <td><font style="color:#088704; font-size:16px; font-weight:bold; font-family:'Times New Roman'"><?php echo $rsOrder["Order_Code"];?></font></td>
            </tr>
            <tr>
              <td nowrap>订单备注：</td>
              <td><?php echo $rsOrder["Order_Remark"] ?></td>
            </tr>
            <tr>
              <td></td>
              <td><?php if($rsOrder["Order_Status"]==2){?><input type="submit" class="btn_green" name="submit_button" value="确认消费" /><?php }else{?><font sty="color:#F00">此订单为“<?php $_STATUS[$rsOrder["Order_Status"]]?>”状态，不能消费</font><?php }?></td>
            </tr>
          </table>
        <?php if($rsOrder["Order_Status"]==2){?>
        </form>
        <?php }?>
        <div class="blank12"></div>
        <div class="item_info">订单清单</div>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="order_item_list">
          <tr class="tb_title">
            <td width="20%">图片</td>
            <td width="35%">产品信息</td>
            <td width="15%">价格</td>
            <td width="15%">数量</td>
            <td width="15%" class="last">小计</td>
          </tr>
          <?php
            $total=0;
			$qty=0;
			$CartList=json_decode(htmlspecialchars_decode($rsOrder["Order_CartList"]),true);
			foreach($CartList as $key=>$value){
				foreach($value as $k=>$v){
					$total+=$v["Qty"]*$v["ProductsPriceX"];
					$qty+=$v["Qty"];
					echo '<tr class="item_list" align="center">
						<td valign="top"><img src="'.$v["ImgPath"].'" width="100" height="100" /></td>
						<td align="left" class="flh_180">'.$v["ProductsName"].'<br>';
					foreach($v["Property"] as $m=>$n){
						echo $m.': '.$n.'<br>';
					}
					echo '</td>
						<td>￥'.$v["ProductsPriceX"].'</td>
						<td>'.$v["Qty"].'</td>
						<td>￥'.$v["ProductsPriceX"]*$v["Qty"].'</td>
					  </tr>';
				}
			}
		  ?>
          <tr class="total">
            <td colspan="3">&nbsp;</td>
            <td><?php echo $qty ?></td>
            <td>￥<?php echo $total ?></td>
          </tr>
        </table>
      </div>
      <?php }}?>
    </div>
  </div>
</div>
</body>
</html>