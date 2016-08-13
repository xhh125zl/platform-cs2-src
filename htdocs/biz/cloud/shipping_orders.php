<?php
$condition = "WHERE Users_ID='{$UsersID}' AND Orders_Status<4";
if(isset($_GET["search"])){
	if($_GET["search"]==1){
		if(!empty($_GET["Keyword"])){
			$condition .= " AND `".$_GET["Fields"]."` LIKE '%".$_GET["Keyword"]."%'";
		}
		if(isset($_GET["Status"])){
			if($_GET["Status"]<>''){
				$condition .= " AND Orders_Status=".$_GET["Status"];
			}
		}
		if(!empty($_GET["AccTime_S"])){
			$condition .= " AND Orders_CreateTime>=".strtotime($_GET["AccTime_S"]);
		}
		if(!empty($_GET["AccTime_E"])){
			$condition .= " AND Orders_CreateTime<=".strtotime($_GET["AccTime_E"]);
		}
	}
}

$condition .= " order by Orders_CreateTime desc";

$_STATUS_SHIPPING = array('<font style="color:#FF0000">待付款</font>','<font style="color:#03A84E">待发货</font>','<font style="color:#F60">待收货</font>','<font style="color:blue">已领取</font>','<font style="color:#999; text-decoration:line-through">&nbsp;已取消&nbsp;</font>');
$_STATUS = array('','<font style="color:#FF0000">未领取</font>','','<font style="color:blue">已领取</font>');
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
<link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
<link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/user.js?t=<?php echo time();?>'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"> <a href="shipping_orders.php">商品领取订单</a></li>
      </ul>
    </div>
    <div id="gift_orders" class="r_con_wrap"> 
      <script language="javascript">$(document).ready(user_obj.shipping_orders_init);</script>
      <form class="search" id="search_form" method="get" action="">
      	<select name="Fields">
			<option value='Address_Name'>购买人</option>
			<option value='Address_Mobile'>购买手机</option>
			<option value='Address_Detailed'>收货地址</option>
		</select>
        <input type="text" name="Keyword" value="" class="form_input" size="15" />
        订单状态：
        <select name="Status">
          <option value="">--请选择--</option>
          <option value='0'>已领取</option>
          <option value='1'>待发货</option>
          <option value='2'>待收货</option>
          <option value='3'>已领取</option>
        </select>
        领取时间：
        <input type="text" class="input" name="AccTime_S" value="" maxlength="20" />
        -
        <input type="text" class="input" name="AccTime_E" value="" maxlength="20" />
        <input type="hidden" value="1" name="search" />
        <input type="submit" class="search_btn" value="搜索" />
        <input type="button" class="virtual_btn" value="电子券验证" style="background:#1584D5; color:white; border:none; height:22px; cursor:pointer; line-height:22px; border-radius:5px; width:120px;display:none;" />
		<input type="button" class="recieve_btn" value="批量收货" style="background:#1584D5; color:white; border:none; height:22px; cursor:pointer; line-height:22px; border-radius:5px; width:120px;" />
      </form>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">序号</td>
            <td width="20%" nowrap="nowrap">名称</td>
            <td width="20%" nowrap="nowrap">图片</td>
            <td width="10%" nowrap="nowrap">姓名</td>
            <td width="10%" nowrap="nowrap">手机</td>
            <td width="10%" nowrap="nowrap">生成时间</td>
            <td width="10%" nowrap="nowrap">状态</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php $DB->getPage("shipping_orders","*",$condition,$pageSize=10);
		$i=1;
		$lists = array();
		while($rs=$DB->fetch_assoc()){
			$lists[] = $rs;
		}
		foreach($lists as $k=>$v){
			$rsDetail = $DB->GetRs("cloud_products_detail","*","WHERE Cloud_Detail_ID=".$v['Detail_ID']."");
			$rsProducts = $DB->GetRs("cloud_products","*","WHERE Products_ID=".$rsDetail['Products_ID']."");
			$v['Products_Name'] = $rsProducts['Products_Name'];
			$ImgPath = get_prodocut_cover_img($rsProducts);
			?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td><?php echo $v['Products_Name'];?></td>
            <td nowrap="nowrap"><img src="<?php echo $ImgPath?>" class="img" /></a></td>
            <td nowrap="nowrap"><?php echo $v['Address_Name'];?></td>
            <td nowrap="nowrap"><?php echo $v['Address_Mobile'];?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$v['Orders_CreateTime']);?></td>
            <td nowrap="nowrap">
            	<?php
                if($v["Orders_IsShipping"]==0){
					echo $_STATUS[$v["Orders_Status"]];
				}else{
					echo $_STATUS_SHIPPING[$v["Orders_Status"]];
				}
				?>
            </td>
            <td class="last" nowrap="nowrap">
            	<a href="shipping_orders_view.php?OrderId=<?php echo $v['Orders_ID']?>&page=<?php echo $DB->pageNo;?>">[详情]</a>
                <?php
                if($v["Orders_IsShipping"]==1){
					if($v["Orders_Status"]==1){
						echo '<a href="shipping_orders_send.php?OrderId='.$v['Orders_ID'].'">[发货]</a>';
					}else{
						if($v["Orders_Status"]==0 && $v["Orders_PaymentMethod"] == '线下支付'){
							echo '<a href="shipping_orders_send.php?OrderId='.$v['Orders_ID'].'">[发货]</a><br />线下支付';
						}
					}
				}
				?>
            </td>
          </tr>
          <?php $i++;
		  }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
    
    <div id="virtual_div" class="lean-modal lean-modal-form">
      <div class="h">电子券验证<a class="modal_close" href="#"></a></div>
      <form class="form" id="virtual_form">
        <div class="rows">
          <label>电子券码：</label>
          <span class="input">
          <input name="Code" value="" id="Code" type="text" class="form_input" size="26" maxlength="100" notnull>
          <font class="fc_red">*</font> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="submit">
          <input type="submit" value="确定提交" name="submit_btn">
          </span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
	
	<div id="recieve_div" class="lean-modal lean-modal-form">
      <div class="h">批量收货<a class="modal_close" href="#"></a></div>
      <form class="form" id="recieve_form">
        <div class="rows">
          <label></label>
          <span class="input">
			<span class="tips">发货七天后的商品将自动收货</span>
          </span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label></label>
          <span class="submit">
          <input type="submit" value="确定" name="submit_btn">
          </span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>