<?php
require_once ('../global.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/helper/balance.class.php');
$balance = new balance($DB, $rsBiz["Users_ID"]);

$condition = "WHERE Users_ID='" . $rsBiz["Users_ID"] . "' AND Biz_ID=" . $_SESSION["BIZ_ID"];
if (isset($_GET["search"])) {
    if ($_GET["search"] == 1) {
        if (! empty($_GET["Keyword"])) {
            $condition .= " AND " . $_GET["Fields"] . " LIKE '%" . $_GET["Keyword"] . "%'";
        }
        if (isset($_GET["Status"])) {
            if ($_GET["Status"] != '') {
                $condition .= " AND Status=" . $_GET["Status"];
            }
        }
        if (! empty($_GET["AccTime_S"])) {
            $condition .= " AND CreateTime>=" . strtotime($_GET["AccTime_S"]);
        }
        if (! empty($_GET["AccTime_E"])) {
            $condition .= " AND CreateTime<=" . strtotime($_GET["AccTime_E"]);
        }
    }
}
$BizPayRate = array();
$DB->Get('biz', '*', "WHERE Users_ID='" . $rsBiz["Users_ID"] . "'");
while ($BizRs = $DB->fetch_assoc()) {
    $BizPayRate[$BizRs["Biz_ID"]] = empty($BizRs['PaymenteRate']) ? '100' : $BizRs['PaymenteRate'];
}
if (isset($_GET["action"])) {
    if ($_GET["action"] == "getpay") {
        
        $paymentid = empty($_GET['paymentid']) ? 0 : $_GET['paymentid'];
        $payment_status = $DB->GetRs("shop_sales_payment", 'Status', "WHERE Payment_ID=" . $paymentid);
        if ($payment_status['Status'] == 1) {
            echo '<script language="javascript">alert("此收款单已完成,不可再次操作!");window.location.href="payment.php";</script>';
            exit();
        }
        $DB->Set("shop_sales_payment", array("Status" => 1), "WHERE Payment_ID=" . $paymentid);
        $DB->Set("shop_sales_record", array("Record_Status" => 1), "WHERE Payment_ID='{$paymentid}'");
        $Biz_ID = $DB->GetRs("shop_sales_payment", 'Biz_ID,Total', "WHERE Payment_ID=" . $paymentid);
        $usermoney = $Biz_ID["Total"]-($Biz_ID["Total"]*$BizPayRate[$Biz_ID["Biz_ID"]]/100);
                $usermoney = !empty($usermoney)?$usermoney:'0';        
                $UserID = $DB->GetRs("biz",'UserID',"where Biz_ID=".$Biz_ID['Biz_ID']);
                $flag = $DB->Set("user",'User_Money=User_Money+'.$usermoney,"where User_ID=".$UserID['UserID']);
                $rsUser=$DB->GetRs("user","User_Money","where Users_ID='".$rsBiz["Users_ID"]."' and User_ID='".$UserID["UserID"]."'");
                if($flag){
                    //增加资金流水
                    $Data=array(
                            'Users_ID'=>$rsBiz['Users_ID'],
                            'User_ID'=>$UserID['UserID'],				
                            'Type'=>3,
                            'Amount'=>$usermoney,
                            'Total'=>$rsUser['User_Money'],
                            'Note'=>"财务结算余额+".$usermoney,
                            'CreateTime'=>time()			
                    );
                    $Add=$DB->Add('user_money_record',$Data);
                }
        echo '<script language="javascript">window.location.href="payment.php";</script>';
    }
    
    if ($_GET["action"] == "del") {
        $paymentid = empty($_GET['paymentid']) ? 0 : $_GET['paymentid'];
        $item = $DB->GetRs("shop_sales_payment", "Status", "WHERE Payment_ID=" . $paymentid);
        if ($item["Status"] != 0) {
            echo '<script language="javascript">alert("该收款单已确认收款，不得删除");history.back();</script>';
            exit();
        }
        $DB->Set("shop_sales_record", array(
            "Record_Status" => 0,
            "Payment_ID" => 0
        ), "WHERE Payment_ID=" . $paymentid);
        $DB->Del("shop_sales_payment", "Payment_ID=" . $paymentid);
        echo '<script language="javascript">window.location.href="payment.php";</script>';
    }
}

$condition .= " order by Payment_ID desc";

$lists = array();
$DB->GetPage("shop_sales_payment", "*", $condition, 20);
while ($r = $DB->Fetch_assoc()) {
    $lists[$r["Payment_ID"]] = $r;
}
$_STATUS = array(
    '<font style="color:red">未收款</font>',
    '<font style="color:blue">已收款</font>',
    '<font style="color:red">已结算（等待商家确认）</font>',
    '<font style="color:red">申请中</font>'
);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet'
	type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
	<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

	<div id="iframe_page">
		<div class="iframe_content">
			<link href='/static/member/css/shop.css' rel='stylesheet'
				type='text/css' />
			<script type='text/javascript' src='/static/member/js/payment.js'></script>
			<script language="javascript">$(document).ready(payment.orders_init);</script>
			<div class="r_nav">
				<ul>
					<li><a href="distribute_record.php">分销记录</a></li>
					<li><a href="sales_record.php">销售记录</a></li>
					<li class="cur"><a href="payment.php">收款单</a></li>
				</ul>
			</div>
			<link href='/static/js/plugin/operamasks/operamasks-ui.css'
				rel='stylesheet' type='text/css' />
			<script type='text/javascript'
				src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
			<div id="orders" class="r_con_wrap">
				<form class="search" id="search_form" method="get" action="?">
					<select name="Fields">
						<option value="Bank">银行类型</option>
						<option value='BankNo'>银行卡号</option>
						<option value='BankName'>收款人</option>
						<option value='BankMobile'>收款人手机</option>
					</select> <input type="text" name="Keyword" value=""
						class="form_input" size="15" />&nbsp;&nbsp; 是否结算： <select
						name="Status">
						<option value="">全部</option>
						<option value='0'>未结算</option>
						<option value='1'>已结算</option>
					</select>&nbsp;&nbsp; 时间： <input type="text" class="input"
						name="AccTime_S" value="" maxlength="20" /> - <input type="text"
						class="input" name="AccTime_E" value="" maxlength="20" /> <input
						type="submit" class="search_btn" value="搜索" /> <input
						type="hidden" value="1" name="search" />
				</form>
				<div class="control_btn">
					<a href="payment_add.php" id="payment" class="btn_green btn_w_120">生成收款单</a>
				</div>

				<table border="0" cellpadding="5" cellspacing="0"
					class="r_con_table" id="order_list">
					<thead>
						<tr>
							<td width="6%" nowrap="nowrap">序号</td>
							<td width="12%" nowrap="nowrap">收款单号</td>
							<td width="12%" nowrap="nowrap">结算时间</td>
							<td width="8%" nowrap="nowrap">应收总额</td>
							<td width="8%" nowrap="nowrap">优惠金额</td>
							<td width="8%" nowrap="nowrap">网站所得</td>

							<td width="8%" nowrap="nowrap">结算金额</td>
							<td width="8%" nowrap="nowrap">状态</td>
							<td width="12%" nowrap="nowrap">生成时间</td>
							<td width="10%" nowrap="nowrap">操作</td>
						</tr>
					</thead>
					<tbody>
         <?php
        
        foreach ($lists as $paymentid => $value) {
            ?>
         <tr>
							<td nowrap="nowrap"><?php echo $paymentid;?></td>
							<td nowrap="nowrap"><?php echo $value["Payment_Sn"];?></td>
							<td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$value["FromTime"]);?><br />~<br /><?php echo date("Y-m-d H:i:s",$value["EndTime"]);?></td>
							<td nowrap="nowrap"><font style="color: #F60"><?php echo $value["Amount"];?></font></td>
							<td nowrap="nowrap"><?php echo $value["Diff"];?></td>
							<td nowrap="nowrap"><?php echo $value["Web"];?></td>
							<td nowrap="nowrap"><font style="color: blue"><?php echo $value["Total"];?></font><br>(转账
						    <span><?php echo $value["Total"]*$BizPayRate[$value["Biz_ID"]]/100;?></span>
           <?php
echo "+转向余额";
            echo $usermoney = $value["Total"] - ($value["Total"] * $BizPayRate[$value["Biz_ID"]] / 100);
            echo ")"?> 
            </td>
            <td nowrap="nowrap"><?php echo $_STATUS[$value["Status"]];?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$value["CreateTime"]);?></td>
            <td nowrap="nowrap"> 
                <a href="payment_detail.php?paymentid=<?php echo $paymentid;?>">[查看详情]</a>
                <?php if($value["Status"]==2){?>
                    <a href="?action=getpay&paymentid=<?php echo $paymentid;?>">[确定收款]</a>&nbsp;<?php } ?>
                <?php if($value["Status"]==0 || $value["Status"]==3){?>
                    <a href="?action=del&paymentid=<?php echo $paymentid;?>">[删除]</a>
                <?php }?>

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

	<script>
$(document).ready(function(){
  //  $("#payment")
}); 
//$(function(){
    
//})    
    
 
</script>

</body>
</html>