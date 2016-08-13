<?php 
//获取所有分销商列表
$ds_list = Dis_Account::with('User')
	->where(array('Users_ID' => $UsersID))
	->get(array('Users_ID', 'User_ID', 'invite_id', 'User_Name', 'Account_ID', 'Shop_Name','Account_CreateTime'))
	->toArray();
			
$ds_list_dropdown = array();
foreach($ds_list as $key=>$item){
	if(!empty($item['user'])){
		$ds_list_dropdown[$item['User_ID']] = $item['user']['User_NickName'];
	}
}

//取出商城配置信息
$rsConfig = $DB->GetRs("shop_config","ShopName,NeedShipping","where Users_ID='{$UsersID}'");

$condition = "where Users_ID='{$UsersID}' and Biz_ID={$BizID} AND Order_Type='cloud'";
if(isset($_GET["search"])){
	if($_GET["search"]==1){
		if(!empty($_GET["Keyword"])){
			$condition .= " and `".$_GET["Fields"]."` like '%".$_GET["Keyword"]."%'";
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
	}
}

$condition .= " order by Order_CreateTime desc";

if(isset($_GET["action"]))
{
	if($_GET["action"]=="del")
	{
		$Flag=$DB->Del("user_order","Users_ID='{$UsersID}' and Order_ID=".$_GET["OrderID"]);
		if($Flag)
		{
			echo '<script language="javascript">alert("删除成功");window.location="orders.php'.(isset($_GET["page"]) ? '?page='.$_GET["page"] : '').'";</script>';
		}else
		{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}elseif($_GET["action"]=="set_read")
	{
		$Flag=$DB->Set("user_order","Order_IsRead=1","where Users_ID='{$UsersID}' and Order_ID=".$_GET["OrderID"]);
		$Data=array("ret"=>1);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}elseif($_GET["action"]=="is_not_read")
	{
		$Flag=$DB->Set("user_order","Order_IsRead=1","where Users_ID='{$UsersID}' and Order_ID=".$_GET["OrderID"]);
		$Data=array(
			"ret"=>1,
			"msg"=>""
		);
		
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
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
		<script type='text/javascript' src='/static/member/js/shop.js'></script>
		<div class="r_nav">
			<ul>
				<li class="cur"><a href="orders.php">订单管理</a></li>
				<li><a href="virtual_orders.php">消费认证</a></li>
			</ul>
		</div>
		<link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
		<script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
		<script language="javascript">
		var NeedShipping=1;
		var orders_status=["待付款","待确认","已付款","已发货","已完成"];
		$(document).ready(shop_obj.orders_init);
		</script> 
		<script>
		function CheckAll(form1){
			for(var i=0;i<form1.elements.length;i++){
				var e = form1.elements[i];
				if(e.name != 'chkall'){
					e.checked = form1.chkall.checked;
				}
			}
		}
		function SelectThis(index){
			if(typeof(form1.ID[index-1])=='undefined'){
				form1.ID.checked=!form1.ID.checked;
			}else{
				form1.ID[index-1].checked=!form1.ID[index-1].checked;
			}
		}
		</script>
		<div id="orders" class="r_con_wrap">
			<form class="search" id="search_form" method="get" action="?">
				<select name="Fields">
					<option value='Order_CartList'>商品</option>
					<option value='Address_Name'>购买人</option>
					<option value='Address_Mobile'>购买手机</option>
					<option value='Address_Detailed'>收货地址</option>
				</select>
				<input type="text" name="Keyword" value="" class="form_input" size="15" />
				订单状态：
				<select name="Status">
					<option value="">--请选择--</option>
					<option value='1'>待付款</option>
					<option value='4'>已付款</option>
				</select>
				时间：
				<input type="text" class="input" name="AccTime_S" value="" maxlength="20" />
				-
				<input type="text" class="input" name="AccTime_E" value="" maxlength="20" />
				<input type="hidden" value="1" name="search" />
				<input type="submit" class="search_btn" value="搜索" />
			</form>
			<style>
			.tips_info {
				background: #f7f7f7 none repeat scroll 0 0;
				border: 1px solid #ddd;
				border-radius: 5px;
				font-size: 12px;
				line-height: 22px;
				margin-bottom: 10px;
				padding: 10px;
			}
			</style>
			<?php if(!empty($_GET['DetailID'])){?>
			<div class="tips_info"> 本期获得者：<font style="color:#F00; font-size:12px;">XXX</font><br />
				订单号：<font style="color:#F00; font-size:12px;">XXX</font><br />
				参与人次：<font style="color:#F00; font-size:12px;">XXX</font> </div>
			<?php }?>
			<form name="form1" method="post" action="?">
				<table border="0" cellpadding="5" cellspacing="0" class="r_con_table" id="order_list">
					<thead>
						<tr>
							<td width="5%" nowrap="nowrap"><input name="chkall" type="checkbox" id="chkall" value="select" onClick="CheckAll(this.form)" style="border:0"></td>
							<td width="5%" nowrap="nowrap">序号</td>
							<td width="10%" nowrap="nowrap">订单号</td>
							<td width="5%" nowrap="nowrap">分销商</td>
							<td width="13%" nowrap="nowrap">姓名</td>
							<td width="12%" nowrap="nowrap">金额</td>
							<td width="9%" nowrap="nowrap">配送方式</td>
							<td width="9%" nowrap="nowrap">订单状态</td>
							<td width="12%" nowrap="nowrap">时间</td>
							<td width="10%" nowrap="nowrap" class="last">操作</td>
						</tr>
					</thead>
					<tbody>
						<?php
						$i=0;
						$DB->getPage("user_order","*",$condition,10);
						$Order_Status=array("待确认","待付款","已付款","已发货","已完成");
						/*获取订单列表牵扯到的分销商*/
						while($r = $DB->fetch_assoc()){
							$lists[] = $r;
						}
						if(!empty($lists)){
							foreach($lists as $k=>$rsOrder){
								$rsUser = $DB->GetRs('user','*',"WHERE Users_ID='{$UsersID}' and User_ID=".$rsOrder['User_ID']);
								$Shipping=json_decode(htmlspecialchars_decode($rsOrder["Order_Shipping"]),true);
							?>
						<tr class="<?php echo empty($rsOrder["Order_IsRead"])?"is_not_read":"" ?>" IsRead="<?php echo $rsOrder["Order_IsRead"] ?>" OrderId="<?php echo $rsOrder["Order_ID"] ?>">
							<td nowrap="nowrap"><input type="checkbox" name="ID" value="<?php echo $rsOrder["Order_ID"]; ?>" style="border:0" onClick="SelectThis(<?php echo $i+1;?>)"></td>
							<td nowrap="nowrap"><?php echo $rsOrder["Order_ID"] ?></td>
							<td nowrap="nowrap"><?php echo date("Ymd",$rsOrder["Order_CreateTime"]).$rsOrder["Order_ID"] ?></td>
							<td nowrap="nowrap"><?php
									if($rsOrder["Owner_ID"] == 0 ){
										echo '无';
									}else{
										
										if(!empty($ds_list_dropdown[$rsOrder["Owner_ID"]])){
											echo $ds_list_dropdown[$rsOrder["Owner_ID"]];
										}else{
											echo '无昵称';
										}
										
										
									}	
									
									?></td>
							<td><?php echo empty($rsOrder["Address_Name"]) ? $rsUser['User_NickName'] : $rsOrder["Address_Name"];?></td>
							<td nowrap="nowrap">￥<?php echo $rsOrder["Order_TotalPrice"] ?></td>
							<td nowrap="nowrap"><?php		
										if(empty($Shipping)){
											echo "免运费";
										}else{
											if(isset($Shipping["Express"])){
												echo $Shipping["Express"];
											}else{
												echo '无配送信息';
											}
										}
									?></td>
							<td nowrap="nowrap"><?php echo $Order_Status[$rsOrder["Order_Status"]] ?></td>
							<td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsOrder["Order_CreateTime"]) ?></td>
							<td class="last" nowrap="nowrap"><a href="<?php echo $rsOrder["Order_IsVirtual"]==1 ? 'virtual_' : '';?>orders_view.php?OrderID=<?php echo $rsOrder["Order_ID"] ?>"><img src="/static/member/images/ico/view.gif" align="absmiddle" alt="修改" /></a> 
								<!--<a href="orders.php?action=del&OrderID=<?php echo $rsOrder["Order_ID"] ?>&page=<?php echo isset($_GET["page"]) ? $_GET["page"] : 1;?>" title="删除" onClick="if(!confirm('删除后不可恢复，并且会影响分销佣金计算,继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>-->
						</tr>
						<?php $i++;}?>
						<?php }?>
					</tbody>
				</table>
			</form>
			<div class="blank20"></div>
			<?php $DB->showPage(); ?>
		</div>
	</div>
</div>
</body>
</html>