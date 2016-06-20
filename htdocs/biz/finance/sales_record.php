<?php
require_once('../global.php');

require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/balance.class.php');
$balance = new balance($DB,$rsBiz["Users_ID"]);

$condition = "where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"];
if(isset($_GET["search"])){
	if($_GET["search"]==1){
		if(isset($_GET["Status"])){
			if($_GET["Status"]<>''){
				$condition .= " and Record_Status=".$_GET["Status"];
			}
		}
		if(!empty($_GET["AccTime_S"])){
			$condition .= " and Record_CreateTime>=".strtotime($_GET["AccTime_S"]);
		}
		if(!empty($_GET["AccTime_E"])){
			$condition .= " and Record_CreateTime<=".strtotime($_GET["AccTime_E"]);
		}
	}
}

$condition .= " order by Record_ID desc";
$b0 = $b1 = $b2 = $b3 = $b4 = $b5 = $b6 = $b7 = 0;
$lists = array();
$DB->GetPage("shop_sales_record","*",$condition,20);
while($r=$DB->Fetch_assoc()){
	$lists[$r["Record_ID"]] = $r;
}
$lists = $balance->repeat_list($lists);
$_STATUS = array('<font style="color:red">未结算</font>','<font style="color:blue">已结算</font>');
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
    <script type='text/javascript' src='/biz/js/shop.js'></script>
    <script language="javascript">$(document).ready(shop_obj.sales_init);</script>
    <div class="r_nav">
      <ul>
      	<li><a href="distribute_record.php">分销记录</a></li>
        <li class="cur"><a href="sales_record.php">销售记录</a></li>
        <li><a href="payment.php">收款单</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <div id="orders" class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="?">
        是否结算：
        <select name="Status">
          <option value="">全部</option>
          <option value='0'>未结算</option>
          <option value='1'>已结算</option>
        </select>
        时间：
        <input type="text" class="input" name="AccTime_S" value="" maxlength="20" />
        -
        <input type="text" class="input" name="AccTime_E" value="" maxlength="20" />
        <input type="submit" class="search_btn" value="搜索" />
        <input type="hidden" value="1" name="search" />
      </form>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table" id="order_list">
        <thead>
          <tr>
            <td width="6%" nowrap="nowrap">序号</td>
            <td width="10%" nowrap="nowrap">订单号</td>
            <td width="8%" nowrap="nowrap">商品总额</td>
            <td width="8%" nowrap="nowrap">运费费用</td>
            <td width="8%" nowrap="nowrap">应收金额</td>
            <td width="8%" nowrap="nowrap">优惠金额</td>
            <td width="8%" nowrap="nowrap">实收金额</td>
            <td width="8%" nowrap="nowrap">网站所得</td>
            <td width="8%" nowrap="nowrap">分销佣金</td>
            <td width="8%" nowrap="nowrap">结算金额</td>
            <td width="8%" nowrap="nowrap">结算状态</td>
            <td width="12%" nowrap="nowrap">时间</td>
          </tr>
        </thead>
        <tbody>
         <?php
          foreach($lists as $recordid=>$value){
			  $b0 += $value["product_amount"];
			  $b1 += $value["Order_Shipping"];
			  $b2 += $value["Order_Amount"];
			  $b3 += $value["Order_Diff"];
			  $b4 += $value["Order_TotalPrice"];
			  $b5 += ($value["web"]-$value["bonus"]);
			  $b6 += $value["bonus"];
			  $b7 += $value["supplytotal"];
		 ?>
         <tr>
            <td nowrap="nowrap"><?php echo $recordid;?></td>
            <td nowrap="nowrap"><?php echo $value["orderno"];?></td>
            <td nowrap="nowrap"><?php echo $value["product_amount"];?></td>
            <td nowrap="nowrap"><?php echo $value["Order_Shipping"];?></td>
            <td nowrap="nowrap"><font style="color:#F60"><?php echo $value["Order_Amount"];?></font></td>
            <td nowrap="nowrap"><?php echo $value["Order_Diff"];?></td>
            <td nowrap="nowrap"><font style="color:#FF0000"><?php echo $value["Order_TotalPrice"];?></font></td>
            <td nowrap="nowrap"><?php echo $value["web"]-$value["bonus"];?></td>
            <td nowrap="nowrap"><?php echo $value["bonus"];?></td>
            <td nowrap="nowrap"><font style="color:blue"><?php echo $value["supplytotal"];?></font></td>
            <td nowrap="nowrap"><?php echo $_STATUS[$value["Record_Status"]];?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$value["Record_CreateTime"]);?></td>
          </tr>
         <?php }?>
         <?php
         if(count($lists)>0){
		 ?>
          <tr style="background:#f5f5f5">
            <td nowrap="nowrap" colspan="2">总计</td>
            <td nowrap="nowrap"><?php echo $b0;?></td>
            <td nowrap="nowrap"><?php echo $b1;?></td>
            <td nowrap="nowrap"><font style="color:#F60"><?php echo $b2;?></font></td>
            <td nowrap="nowrap"><?php echo $b3;?></td>
            <td nowrap="nowrap"><font style="color:#FF0000"><?php echo $b4;?></font></td>
            <td nowrap="nowrap"><?php echo $b5;?></td>
            <td nowrap="nowrap"><?php echo $b6;?></td>
            <td nowrap="nowrap"><font style="color:blue"><?php echo $b7;?></font></td>
            <td nowrap="nowrap" colspan="2"></td>
          </tr>
         <?php
		 }
		 ?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>