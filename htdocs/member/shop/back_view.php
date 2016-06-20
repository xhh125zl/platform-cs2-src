<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/backup.class.php');
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
$backup = new backup($DB,$_SESSION["Users_ID"]);
$BackID=empty($_REQUEST['BackID'])?0:$_REQUEST['BackID'];
$rsBack = $DB->GetRs("user_back_order","*","where Users_ID='".$_SESSION["Users_ID"]."' and Back_ID='".$BackID."'");
$Status=$rsBack["Back_Status"];
if(!empty($_GET["action"])){
	$action = $_GET["action"];
	if($action=="back_money"){
		if($Status<>3){
			echo '<script language="javascript">alert("操作错误");history.back();</script>';
		}else{
			$backup->update_backup("admin_backmoney",$BackID);
			echo '<script language="javascript">alert("操作成功");window.location="back_view.php?BackID='.$BackID.'";</script>';
		}
	}
	exit;
}else{
	$rsUser = $DB->GetRs("user","*","where User_ID=".$rsBack['User_ID']);
	$rsConfig=$DB->GetRs("shop_config","ShopName,NeedShipping","where Users_ID='".$_SESSION["Users_ID"]."'");
	$rsPay=$DB->GetRs("users_payconfig","Shipping","where Users_ID='".$_SESSION["Users_ID"]."'");
	
	$_STATUS = array('<font style="color:#F00">申请中</font>','<font style="color:#F60">卖家同意</font>','<font style="color:#0F3">买家发货</font>','<font style="color:#600">卖家收货并确定退款价格</font>','<font style="color:blue">完成</font>','<font style="color:#999; text-decoration:line-through;">卖家拒绝退款</font>');
	$_TUIKUAN = array('<font style="color:#F00">未退款</font>','<font style="color:blue">已退款</font>');
	$Shipping=json_decode($rsBack["Back_Shipping"],true);
	$PayShipping=empty($rsPay["Shipping"])?array():json_decode($rsPay["Shipping"],true);
	$rsBack["Back_Json"] = str_replace('\n','',$rsBack["Back_Json"]);
	$ProductList = json_decode(htmlspecialchars_decode($rsBack["Back_Json"]),true);
	
	if($rsBack["Biz_ID"]==0){
		$rsBack["Biz_Name"] = "本站供货";
	}else{
		$item = $DB->GetRs("biz","*","where Biz_ID=".$rsBack["Biz_ID"]);
		if($item){
			$rsBack["Biz_Name"] = $item["Biz_Name"];
		}else{
			$rsBack["Biz_Name"] = "已被删除";
		}
	}
	$rsBiz = $item;
	$area_json = read_file($_SERVER["DOCUMENT_ROOT"].'/data/area.js');
	$area_array = json_decode($area_json,TRUE);
	$province_list = $area_array[0];
	$Province = '';
	if(!empty($rsBiz['Biz_RecieveProvince'])){
		$Province = $province_list[$rsBiz['Biz_RecieveProvince']].',';
	}
	$City = '';
	if(!empty($rsBiz['Biz_RecieveCity'])){
		$City = $area_array['0,'.$rsBiz['Biz_RecieveProvince']][$rsBiz['Biz_RecieveCity']].',';
	}

	$Area = '';
	if(!empty($rsBiz['Biz_RecieveArea'])){
		$Area = $area_array['0,'.$rsBiz['Biz_RecieveProvince'].','.$rsBiz['Biz_RecieveCity']][$rsBiz['Biz_RecieveArea']];
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
<style type="text/css">
.back_btn_blue{display:block; width:86px; line-height:28px; height:30px; text-align:center; background:#1584D5; color:#FFF; border-radius:5px; float:left; margin-right:5px;}
.back_btn_grey{display:block; width:86px; line-height:28px; height:30px; text-align:center; background:#888; color:#FFF; border-radius:5px; float:left; margin-right:5px;}
.back_btn_blue a:hover,.back_btn_grey a:hover{text-decoration:none; color:#FFF}
#reject,#recieve{display:none}
</style>
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
        <li class="cur"><a href="backups.php">退货单管理</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 

    <div id="orders" class="r_con_wrap">
      <div class="cp_title">
        <div id="cp_view" class="cur">退货单详情</div>
      </div>
      <div class="detail_card">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order_info">
            <tr>
              <td width="8%" nowrap>退货单编号：</td>
              <td width="92%"><?php echo $rsBack["Back_Sn"] ?></td>
            </tr>
            <tr>
              <td width="8%" nowrap>所属商家：</td>
              <td width="92%"><?php echo $rsBack["Biz_Name"] ?></td>
            </tr>
            <tr>
              <td nowrap>退货单时间：</td>
              <td><?php echo date("Y-m-d H:i:s",$rsBack["Back_CreateTime"]) ?></td>
            </tr>
            <tr>
              <td nowrap>退款数量：</td>
              <td><?php echo $rsBack["Back_Qty"] ?></td>
            </tr>
			<tr>
              <td nowrap>退款总价：</td>
              <td><font style="color:red"><?php echo $rsBack["Back_Amount"] ?></font></td>
            </tr>
            <tr>
              <td nowrap>退款账号：</td>
              <td><?php echo $rsBack["Back_Account"] ?></td>
            </tr>
			<tr>
              <td nowrap>商家收货地址：</td>
              <td><?php echo $Province.$City.$Area.'【'.$rsBiz["Biz_RecieveAddress"].' ， '.$rsBiz["Biz_RecieveName"].'，'.$rsBiz["Biz_RecieveMobile"].'】';?></td>
            </tr>
            <tr>
              <td nowrap>退货单状态：</td>
              <td><?php echo $_STATUS[$Status];?></td>
            </tr>
            <tr>
              <td nowrap>网站是否退款：</td>
              <td><?php echo $_TUIKUAN[$rsBack["Back_IsCheck"]] ?></td>
            </tr>
            <tbody id="btns">
            <tr>
              <td nowrap>&nbsp;</td>
              <td>
              <?php
              if($Status==3){
			  ?>
              	<a href="?action=back_money&BackID=<?php echo $BackID;?>" class="back_btn_blue">退款给买家</a>
              <?php }?>
              </td>
            </tr>
            </tbody>
          </table>
        <div class="blank12"></div>
          <div class="item_info">退款流程</div>
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order_info">
          <?php
          	$DB->Get("user_back_order_detail","*","where backid=".$BackID." order by createtime asc");
			while($r = $DB->fetch_assoc()){
		  ?>
          	<tr>
              <td width="150"><?php echo date("Y-m-d H:i:s",$r["createtime"]);?></td>
              <td style="color:#777"><?php echo $r["detail"];?></td>
            </tr>
          <?php }?>
          </table>
        <div class="blank12"></div>
        <div class="item_info">物品清单</div>
        <table class="order_item_list" border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr class="tb_title">
            <td width="20%">图片</td>
            <td width="35%">产品信息</td>
            <td width="15%">价格</td>
            <td width="15%">数量</td>
            <td class="last" width="15%">小计</td>
          </tr>
          <tr class="item_list" align="center">
            <td valign="top"><img src="<?=$ProductList['ImgPath']?>" height="100" width="100"></td>
            <td class="flh_180" align="left"><?=$ProductList['ProductsName']?>
            <td><?=$ProductList['ProductsPriceX']?></td>
            <td><?=$ProductList['Qty']?></td>
            <td><?=$ProductList['ProductsPriceX']*$ProductList['Qty']?></td>
          </tr>
        </tbody></table>
      </div>
    </div>
  </div>
</div>
</body>
</html>