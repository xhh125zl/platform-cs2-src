<?php
require_once('../global.php');

$condition = "where Users_ID='".$rsBiz["Users_ID"]."' and Back_Type='shop' and Biz_ID=".$_SESSION["BIZ_ID"];
if(isset($_GET["search"])){
	if($_GET["search"]==1){
		if(!empty($_GET["Keyword"])){
			$condition .= " and Back_Json like '%".$_GET["Keyword"]."%'";
			$condition .= " or Back_Sn like '%".$_GET["Keyword"]."%'";
		}
		if(isset($_GET["Status"])){
			if($_GET["Status"]<>''){
				$condition .= " and Back_Status=".$_GET["Status"];
			}
		}
		if(isset($_GET["IsCheck"])){
			if($_GET["IsCheck"]<>''){
				$condition .= " and Back_IsCheck=".$_GET["IsCheck"];
			}
		}
		if(!empty($_GET["AccTime_S"])){
			$condition .= " and Back_CreateTime>=".strtotime($_GET["AccTime_S"]);
		}
		if(!empty($_GET["AccTime_E"])){
			$condition .= " and Back_CreateTime<=".strtotime($_GET["AccTime_E"]);
		}
	}
}
$condition .= " order by Back_CreateTime desc";
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
    <div class="r_nav">
      <ul>
        <li><a href="orders.php">订单列表</a></li>
        <li><a href="virtual_orders.php">消费认证</a></li>
        <li class="cur"><a href="backorders.php">退货列表</a></li>
        <li><a href="commit.php">评论管理</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <div id="orders" class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="">
        关键词：
        <input type="text" name="Keyword" value="" class="form_input" size="15" />&nbsp;
        退货单状态：
        <select name="Status">
          <option value="">--请选择--</option>
          <option value='0'>申请中</option>
          <option value='1'>卖家同意</option>
          <option value='2'>买家发货</option>
          <option value='3'>卖家收货</option>
          <option value='4'>已完成</option>
          <option value='5'>卖家驳回申请</option>
          <option value='6'>买家驳回退款金额</option>
        </select>&nbsp;
        网站是否退款：
        <select name="IsCheck">
          <option value="">--请选择--</option>
          <option value='0'>未退款</option>
          <option value='1'>已退款</option>
        </select>&nbsp;
        时间：
        <input type="text" class="input" name="AccTime_S" value="" maxlength="20" />
        -
        <input type="text" class="input" name="AccTime_E" value="" maxlength="20" />
		<input type="hidden" value="1" name="search" />
        <input type="submit" class="search_btn" value="搜索" />

      </form>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table" id="order_list">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">序号</td>
            <td width="20%" nowrap="nowrap">退货单号</td>
            <td width="8%" nowrap="nowrap">退货数量</td>
            <td width="8%" nowrap="nowrap">退款金额</td>
            <td width="8%" nowrap="nowrap">状态</td>
            <td width="8%" nowrap="nowrap">是否退款</td>
            <td width="12%" nowrap="nowrap">时间</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php
        $DB->getPage("user_back_order","*",$condition,10);
		$_STATUS = array('<font style="color:#F00">申请中</font>','<font style="color:#F60">卖家同意</font>','<font style="color:#0F3">买家发货</font>','<font style="color:#600">卖家收货并确定退款价格</font>','<font style="color:#blue">完成</font>','<font style="color:#999; text-decoration:line-through;">卖家拒绝退款</font>');
		$_TUIKUAN = array('<font style="color:#F00">未退款</font>','<font style="color:blue">已退款</font>');
		$lists  =array();
		while($r=$DB->fetch_assoc()){
			$lists[] = $r;
		}
		foreach($lists as $key=>$rsBack){
			if($rsBack["Biz_ID"]==0){
				$rsBack["Biz_Name"] = "本站供货";
			}else{
				$item = $DB->GetRs("biz","Biz_Name","where Biz_ID=".$rsBack["Biz_ID"]);
				if($item){
					$rsBack["Biz_Name"] = $item["Biz_Name"];
				}else{
					$rsBack["Biz_Name"] = "已被删除";
				}
			}
			$rsOrder = $DB->GetRs("user_order","Order_Type,Order_IsVirtual","where Order_ID=".$rsBack["Order_ID"]);
		?>
          <tr>
            <td nowrap="nowrap"><?php echo $key+1; ?></td>
            <td nowrap="nowrap"><?php echo $rsBack["Back_Sn"]  ?>
			 &nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $rsOrder["Order_IsVirtual"]==1 ? 'virtual_' : '';?>orders_view.php?OrderID=<?=$rsBack['Order_ID']?>">相关订单&nbsp;&nbsp;<img src="/static/member/images/ico/jt.gif"/></a>
			</td>
            <td nowrap="nowrap"><?php echo $rsBack["Back_Qty"] ?></td>
            <td nowrap="nowrap"><?php echo $rsBack["Back_Amount"] ?></td>
            <td nowrap="nowrap"><?php echo $_STATUS[$rsBack["Back_Status"]] ?></td>
            <td nowrap="nowrap"><?php echo $_TUIKUAN[$rsBack["Back_IsCheck"]] ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsBack["Back_CreateTime"]) ?></td>
            <td class="last" nowrap="nowrap"><a href="back_view.php?BackID=<?php echo $rsBack["Back_ID"] ?>">[详情]</a></td>
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