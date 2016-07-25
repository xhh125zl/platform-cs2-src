<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

// 获取所有分销商列表
$ds_list = Dis_Account::with('User')->WHERE(array(
    'Users_ID' => $_SESSION["Users_ID"]
))
    ->get(array(
    'Users_ID',
    'User_ID',
    'invite_id',
    'User_Name',
    'Account_ID',
    'Shop_Name',
    'Account_CreateTime'
))
    ->toArray();

$ds_list_dropdown = array();
foreach ($ds_list as $key => $item) {
    if (! empty($item['user'])) {
        $ds_list_dropdown[$item['User_ID']] = $item['user']['User_NickName'];
    }
}

// 获取可用的支付方式列表
$Pay_List = get_enabled_pays($DB, $_SESSION["Users_ID"]);

// 取出商城配置信息
$rsConfig = $DB->GetRs("shop_config", "ShopName,NeedShipping", "WHERE Users_ID='" . $_SESSION["Users_ID"] . "'");
$condition = " LEFT JOIN pintuan_teamdetail AS p ON u.Order_ID=p.order_id LEFT JOIN pintuan_team as t ON p.teamid=t.id ";
$condition .= "WHERE u.Users_ID='" . $_SESSION["Users_ID"] . "' AND ( u.Order_Type='pintuan' OR u.Order_Type='dangou') ";
if (isset($_GET["search"])) {
    if ($_GET["search"] == 1) {
        if (! empty($_GET["Keyword"])) {
            $condition .= " AND `" . $_GET["Fields"] . "` like '%" . $_GET["Keyword"] . "%'";
        }
        if (! empty($_GET["OrderNo"])) {
            $OrderID = trim($_GET["OrderNo"]);
            $condition .= " AND u.Order_Code=" . $OrderID;
        }
        if (isset($_GET["Status"])) {
            if ($_GET["Status"] != - 1) {
                $condition .= " AND u.Order_Status=" . $_GET["Status"];
            }
        }
        if (! empty($_GET["AccTime_S"])) {
            $condition .= " AND u.Order_CreateTime>=" . strtotime($_GET["AccTime_S"]);
        }
        if (! empty($_GET["AccTime_E"])) {
            $condition .= " AND u.Order_CreateTime<=" . strtotime($_GET["AccTime_E"]);
        }
        if (isset($_GET['teamstatus']) && $_GET['teamstatus'] != - 1) {
            if ($_GET['teamstatus'] == 5) { // 单购
                $condition .= " AND u.Order_Type='dangou' ";
            } else {
                $condition .= " AND t.teamstatus='{$_GET['teamstatus']}' ";
            }
        }
    }
}
// 111 201 202

$condition .= " order by u.Order_CreateTime desc";

$templates = array();
$DB->Get("shop_shipping_print_template", "title,itemid", "WHERE usersid='" . $_SESSION["Users_ID"] . "' AND enabled=1");
while ($rsTemplate = $DB->fetch_assoc()) {
    $templates[] = $rsTemplate;
}
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
<script>
$(function(){
  $("#checkall").click(function(){
     if($(this).prop("checked")==true){
      $("input[name='OrderID[]']").attr("checked","checked");
      }else{
        $("input[name='OrderID[]']").removeAttr("checked");
      }
  });
  $("#print").click(function(){
      var ids = new Array;
      $("input[name='OrderID[]']:checked").each(function(){
          ids.push($(this).val());
      });
      if(ids.length<2)
      {
          alert("至少选择2个");
          return false;
      }
      var idlist = "";
      for(var i=0;i<ids.length;i++)
      {
          if(i==ids.length-1){
              idlist +=ids[i];
          }else{
              idlist +=ids[i]+",";
          }
          
      }
      location.href = "/member/pintuan/order_print.php?OrderID="+idlist;
  });
});
</script>
</head>

<body>
	<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

	<div id="iframe_page">
		<div class="iframe_content">
			<link href='/static/member/css/shop.css' rel='stylesheet'
				type='text/css' />
			<script type='text/javascript' src='/static/member/js/shop.js'></script>
			<?php include 'top.php'; ?>
			<link href='/static/js/plugin/operamasks/operamasks-ui.css'
				rel='stylesheet' type='text/css' />
			<script type='text/javascript'
				src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
			<link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet'
				type='text/css' />
			<script type='text/javascript'
				src='/static/js/plugin/lean-modal/lean-modal.min.js'></script>
			<script language="javascript">$(document).ready(shop_obj.orders_init);</script>

			<div id="orders" class="r_con_wrap">
				<form class="search" id="search_form" method="get" action="?">
					<select name="Fields">
						<option value='Order_CartList'>商品</option>
						<option value='Address_Name'>购买人</option>
						<option value='Address_Mobile'>购买手机</option>
						<option value='Address_Detailed'>收货地址</option>
					</select> <input type="text" name="Keyword" value=""
						class="form_input" size="15" />&nbsp; 订单号：<input type="text"
						name="OrderNo" value="" class="form_input" size="15" />&nbsp;
					订单状态： <select name="Status">
						<option value="-1">--请选择--</option>
						<option value='0'>待确认</option>
						<option value='1'>待付款</option>
						<option value='2'>已付款</option>
						<option value='3'>已发货</option>
						<option value='4'>已完成</option>
					</select> 订单流程： <select name="teamstatus">
						<option value="-1">--请选择--</option>
						<option value='0'>拼团中</option>
						<option value='1'>拼团成功</option>
						<option value='2'>已中奖</option>
						<option value='3'>未中奖</option>
						<option value='4'>拼团失败</option>
						<option value='5'>单购</option>
					</select> 时间： <input type="text" class="input" name="AccTime_S"
						value="" maxlength="20" /> - <input type="text" class="input"
						name="AccTime_E" value="" maxlength="20" /> <input type="hidden"
						value="1" name="search" /> <input type="submit" class="search_btn"
						value="搜索" /> <input type="button" class="output_btn" value="导出" />
				</form>
				<form id="submit_form" method="get" action="send_print.php">
					<table border="0" cellpadding="5" cellspacing="0"
						class="r_con_table" id="order_list">
						<thead>
							<tr>
								<td width="6%" nowrap="nowrap"><input type="checkbox"
									id="checkall" /></td>
								<td width="5%" nowrap="nowrap">序号</td>
								<td width="10%" nowrap="nowrap">订单号</td>
								<td width="13%" nowrap="nowrap">姓名</td>
								<td width="12%" nowrap="nowrap">金额</td>
								<td width="11%" nowrap="nowrap">配送方式</td>
								<td width="11%" nowrap="nowrap">订单流程</td>
								<td width="11%" nowrap="nowrap">订单状态</td>
								<td width="12%" nowrap="nowrap">时间</td>
								<td width="10%" nowrap="nowrap" class="last">操作</td>
							</tr>
						</thead>
						<tbody>
                        <?php
                        $i = 0;
                        $DB->getPages("user_order as u", "*", $condition, 10);
                        $Order_Status = array(
                            "待确认",
                            "待付款",
                            "已付款",
                            "已发货",
                            "已完成",
                            "已退款",
                            "退款成功",
                            "手动退款成功"
                        );
                        /* 获取订单列表牵扯到的分销商 */
                        while ($rsOrder = $DB->fetch_assoc()) {
                            
                            $Shipping = json_decode(htmlspecialchars_decode($rsOrder["Order_Shipping"]), true);
                            $goodsinfo = json_decode(htmlspecialchars_decode($rsOrder['Order_CartList']), true);
                            ?>
                          <tr>
                								<td nowrap="nowrap"><input type="checkbox" name="OrderID[]"
                									value="<?php echo $rsOrder["Order_ID"];?>" /></td>
                								<td nowrap="nowrap"><?php echo $rsOrder["Order_ID"] ?></td>
                								<td nowrap="nowrap"><?php echo $rsOrder["Order_Code"]; ?></td>
                
                								<td><?php echo $rsOrder["Address_Name"] ?></td>
                								<td nowrap="nowrap">￥<?php echo $rsOrder["Order_TotalPrice"] ?><?php echo $rsOrder["Back_Amount"]>0 ? '<br /><font style="text-decoration:line-through; color:#999">&nbsp;退款金额：￥'.$rsOrder["Back_Amount"].'&nbsp;</font>' : "";?></td>
                								<td nowrap="nowrap"><?php
                            if (empty($Shipping)) {
                                echo "免运费";
                            } else {
                                if (isset($Shipping["Express"])) {
                                    echo $Shipping["Express"];
                                } else {
                                    echo '无配送信息';
                                }
                            }
                            ?></td>
                
                								<td nowrap="nowrap">
                           <?php
                            if ($rsOrder['Order_Type'] == 'pintuan') {
                                if($goodsinfo['order_process'] == 2){
                                    echo "其他";
                                }else{
                                    if ($rsOrder["teamstatus"] == 0) {
                                        echo "拼团中";
                                    } elseif ($rsOrder["teamstatus"] == 1) {
                                        echo "拼团成功";
                                    } elseif ($rsOrder["teamstatus"] == 2) {
                                        echo "已中奖";
                                    } elseif ($rsOrder["teamstatus"] == 3) {
                                        echo "未中奖";
                                    } else {
                                        echo "拼团失败";
                                    }
                                }
                            } elseif ($rsOrder['Order_Type'] == 'dangou') {
                                if($goodsinfo['order_process'] == 2){
                                    echo "其他";
                                }else{
                                    echo "单购";
                                }
                                
                            }
                            
                            ?>
                           </td>
                			<td nowrap="nowrap"><?php echo $Order_Status[$rsOrder["Order_Status"]] ?></td>
                			<td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsOrder["Order_CreateTime"]) ?></td>
                			<td class="last" nowrap="nowrap"><a
                				href="<?php echo $rsOrder["Order_IsVirtual"]==1 ? 'virtual_' : '';?>orders_view.php?OrderID=<?php echo $rsOrder["Order_ID"] ?>">[详情]</a>
                			</td>
                		</tr>
                          <?php $i++;}?>
                        </tbody>
					</table>
					<div style="height: 10px; width: 100%;"></div>
					<label
						style="display: block; width: 120px; border-radius: 5px; height: 32px; line-height: 30px; background: #3AA0EB; color: #FFF; text-align: center; font-size: 12px; cursor: pointer"
						id="print">打印订单</label> <input type="hidden" name="templateid"
						value="" />
				</form>
				<div class="blank20"></div>
                  <?php $DB->showPage(); ?>
                </div>
		</div>
	</div>
</body>
</html>