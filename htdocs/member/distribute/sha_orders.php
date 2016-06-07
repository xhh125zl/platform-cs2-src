<?php
require_once('sha_orders_global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_products.php');
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$dis_config = Dis_Config::find($_SESSION['Users_ID']);

//获取所有分销商列表
$rsDsAccounts = $DB->Get("distribute_account","User_ID,Real_Name","where Users_ID='".$rsShaod["Users_ID"]."'");
$ds_list = $DB->toArray($rsDsAccounts);
$ds_list_dropdown = array();

foreach($ds_list as $key=>$item){
	$ds_list_dropdown[$item['User_ID']] = $item['Real_Name'];
}

//取出商城配置信息

$psize = 10;
$condition = "where Users_ID='".$rsShaod["Users_ID"]."'";
if(isset($_GET["search"])){
	if($_GET["search"]==1){
		if(!empty($_GET["Keyword"])){
			$condition .= " and `".$_GET["Fields"]."` like '%".$_GET["Keyword"]."%'";
		}
		if(!empty($_GET["OrderNo"])){
			$OrderID = substr($_GET["OrderNo"],8);
			$OrderID =  empty($OrderID) ? 0 : intval($OrderID);
			$condition .= " and Order_ID=".$OrderID;
		}
		if(isset($_GET["Status"])){
			if($_GET["Status"]<>''){
				$condition .= " and Order_Status=".$_GET["Status"];
			}
		}
		if(!empty($_GET["AccTime_S"])){
			$condition .= " and Order_CreateTime>=".strtotime($_GET["AccTime_S"]);
		}
		if(!empty($_GET["AccTime_E"])){
			$condition .= " and Order_CreateTime<=".strtotime($_GET["AccTime_E"]);
		}
		if(!empty($_GET["psize"])){
			$psize = intval($_GET["psize"]);
		}
	}
}
$curid = 3;
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
<script type='text/javascript' src='/static/member/js/distribute/order.js?t=041655521'></script>
<link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
<script language="javascript">$(document).ready(order_obj.orders_init);</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />   
    <?php require_once('shamenu.php'); ?>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script>
    <div id="orders" class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="?">
        <select name="Fields">			
			<option value='Applyfor_Name'>申请人</option>
			<option value='Applyfor_Mobile'>申请电话</option>
		</select>
        <input type="text" name="Keyword" value="" class="form_input" size="15" />&nbsp;
		订单号：<input type="text" name="OrderNo" value="" class="form_input" size="15" />&nbsp;
        订单状态：
        <select name="Status">
         <option value="">--请选择--</option>
          <option value='0'>待审核</option>
          <option value='1'>待付款</option>
          <option value='2'>已付款</option>
		  <option value='3'>已取消</option>
		  <option value='4'>已拒绝</option>       
        </select>
        时间：
        <input type="text" class="input" name="AccTime_S" value="" maxlength="20" />
        -
        <input type="text" class="input" name="AccTime_E" value="" maxlength="20" />
        &nbsp;
        <input type="text" name="psize" value="" class="form_input" size="5" /> 条/页
        <input type="submit" class="search_btn" value="搜索" />        
        <input type="hidden" value="1" name="search" />
      </form>
      <form id="submit_form" method="get" action="/biz/orders/send_print.php">
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table" id="order_list">
        <thead>
          <tr>          	
            <td width="10%" nowrap="nowrap">序号</td>
            <td width="15%" nowrap="nowrap">订单号</td>
            <td width="10%" nowrap="nowrap">分销商</td>
            <td width="15%" nowrap="nowrap">姓名</td>
            <td width="10%" nowrap="nowrap">金额</td>            
            <td width="10%" nowrap="nowrap">状态</td>
            <td width="15%" nowrap="nowrap">申请时间</td>
            <td width="15%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php	
		  $condition .= " order by Order_ID desc";
		  $DB->getPage("sha_order","*",$condition,$psize);
		  $Order_Status=array("待审核","待付款","已付款",'已取消','已拒绝');		  
		  /*获取订单列表牵扯到的分销商*/
		  $order_list = array();
		  while($rr=$DB->fetch_assoc()){
			$order_list[] = $rr;
		  }		  
		  $i = 1;
		  foreach($order_list as $rsOrder){		  
		  ?>
          <tr>
            <td nowrap="nowrap"><?=$i?></td>            
            <td nowrap="nowrap"><?php echo date("Ymd",$rsOrder["Order_CreateTime"]).$rsOrder["Order_ID"] ?></td>
            <td nowrap="nowrap">
			<?php
			if($rsOrder["Owner_ID"] == 0 ){
				echo '无';
			}else{
				if(isset($ds_list_dropdown[$rsOrder["Owner_ID"]])){
					echo $ds_list_dropdown[$rsOrder["Owner_ID"]];
				}else{
					echo '无昵称';
				}
			}
			?></td>
            <td><?=$rsOrder["Applyfor_Name"]?></td> 
			<td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsOrder["Order_CreateTime"]) ?></td>            
            <td nowrap="nowrap"><?=$Order_Status[$rsOrder["Order_Status"]]?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsOrder["Order_CreateTime"]) ?></td>
            <td class="last" nowrap="nowrap">
            <a href="sha_orders_view.php?OrderID=<?=$rsOrder["Order_ID"]?>">[详情]</a>
           <?php if($rsOrder["Order_Status"]==0){?>
            <a href="sha_orders_confirm.php?OrderID=<?php echo $rsOrder["Order_ID"] ?>">[审核]</a>
            <?php }?>
            </td>
          </tr>
          <?php $i++;}?>
        </tbody>
      </table>
      <div style="height:10px; width:100%;"></div>       
       <input type="hidden" name="templateid" value="" />
      </form>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>