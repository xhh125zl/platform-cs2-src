<?php
require_once('../global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/backup.class.php');

$backup = new backup($DB,$rsBiz["Users_ID"]);
$BackID=empty($_REQUEST['BackID'])?0:$_REQUEST['BackID'];
$rsBack = $DB->GetRs("user_back_order","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Back_ID='".$BackID."'");
$Status=$rsBack["Back_Status"];
$rsOrder = $DB->GetRs("user_order","Order_IsVirtual,Order_Status,Front_Order_Status","where Order_ID=".$rsBack["Order_ID"]);

if(!empty($_GET["action"])){
	$action = $_GET["action"];
	if($action=="agree"){
		if($Status<>0){
			echo '<script language="javascript">alert("操作错误");history.back();</script>';
		}else{
		    if($rsOrder['Front_Order_Status']==3 && $rsBack['Back_Status']==0){//已发货的状态下
			    $backup->update_backup("seller_agree",$BackID);
		    }
			if($rsOrder['Front_Order_Status']==2 && $rsBack['Back_Status']==0){  //已付款未发货的状态下
				$backup->update_backup("seller_recieve",$BackID,$rsBack['Back_Amount']."||%$%买家付款后申请退款");
			}
			echo '<script language="javascript">alert("操作成功");window.location="back_view.php?BackID='.$BackID.'";</script>';
		}
	}elseif($action=="reject"){
		if($Status<>0){
			echo '<script language="javascript">alert("操作错误");history.back();</script>';
		}else{
			$backup->update_backup("seller_reject",$BackID,$_GET["reason"]);
			echo '<script language="javascript">alert("操作成功");window.location="back_view.php?BackID='.$BackID.'";</script>';
		}
	}elseif($action=="recieve"){
		if($_GET["Amount"]>$rsBack["Back_Amount"]){
			echo '<script language="javascript">alert("操作错误,退款金额不得大于'.$rsBack["Back_Amount"].'");history.back();</script>';
			exit;
		}
		if(($Status==2 && $rsOrder["Order_IsVirtual"]==0) || ($Status==0 && $rsOrder["Order_IsVirtual"]==1)){
			$backup->update_backup("seller_recieve",$BackID,$_GET["Amount"]."||%$%".$_GET["reason"]);
			echo '<script language="javascript">alert("操作成功");window.location="back_view.php?BackID='.$BackID.'";</script>';
		}else{
			echo '<script language="javascript">alert("操作错误");history.back();</script>';
		}
	}
	exit;
}else{
	$rsUser = $DB->GetRs("user","*","where User_ID=".$rsBack['User_ID']);	
	$_STATUS = array('<font style="color:#F00">申请中</font>','<font style="color:#F60">卖家同意</font>','<font style="color:#0F3">买家发货</font>','<font style="color:#600">卖家收货并确定退款价格</font>','<font style="color:#blue">完成</font>','<font style="color:#999; text-decoration:line-through;">卖家拒绝退款</font>');
	$_TUIKUAN = array('<font style="color:#F00">未退款</font>','<font style="color:blue">已退款</font>');
	$rsBack["Back_Json"] = str_replace('\n','',$rsBack["Back_Json"]);
	$ProductList = json_decode(htmlspecialchars_decode($rsBack["Back_Json"]),true);
	
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
<link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/biz/js/shop.js'></script>
<script language="javascript">$(document).ready(shop_obj.backorder_edit);</script>
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
    <div class="r_nav">
      <ul>
        <li><a href="orders.php">订单列表</a></li>
        <li><a href="virtual_orders.php">消费认证</a></li>
        <li class="cur"><a href="backorders.php">退货列表</a></li>
        <li><a href="commit.php">评论管理</a></li>
      </ul>
    </div>
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
              if($Status==0){
			  ?>
                <?php if($rsOrder["Order_IsVirtual"]==0){?>
              	<a href="?action=agree&BackID=<?php echo $BackID;?>" class="back_btn_blue">同意</a>&nbsp;&nbsp;<a href="javascript:void(0);" id="reject_btn" class="back_btn_grey">驳回</a>
                <?php }else{?>
                <a href="javascript:void(0);" id="recieve_btn" class="back_btn_blue">同意</a>&nbsp;&nbsp;<a href="javascript:void(0);" id="reject_btn" class="back_btn_grey">驳回</a>
                <?php }?>
              <?php }elseif($Status==2){?>
                <a href="javascript:void(0);" id="recieve_btn" class="back_btn_blue">收货</a>
              <?php }?>
              </td>
            </tr>
            </tbody>
            <tbody id="reject">
            <form id="reject_form" action="back_view.php" method="get">
            <tr>
             <td nowrap>驳回理由：</td>
             <td>
               <textarea name="reason" style="width:200px; height:50px; border:1px #dfdfdf solid; font-size:12px;" notnull></textarea>
             </td>
            </tr>
            <tr>
             <td nowrap>&nbsp;</td>
             <td>
               <input type="submit" name="submit" value="提交" style="float:left; display:block; height:32px; line-height:26px; width:86px; text-align:center; font-size:12px; color:#FFF; background:#1584D5; border-radius:5px; margin-right:8px;" /><a href="javascript:void(0);" class="btn_gray" id="goback_reject">返回</a>
             </td>
            </tr>
            <input type="hidden" name="action" value="reject" />
            <input type="hidden" name="BackID" value="<?php echo $BackID;?>" />
            </form>
            </tbody>
            <tbody id="recieve">
            <form id="recieve_form" action="back_view.php" method="get">
            <tr>
             <td nowrap>退款金额：</td>
             <td>
               <input name="Amount" value="<?php echo $rsBack["Back_Amount"];?>" style="width:180px; height:30px; line-height:30px; border:1px #dfdfdf solid; font-size:12px;" notnull />
             </td>
            </tr>
            <tr>
             <td nowrap>理由：</td>
             <td>
               <textarea name="reason" style="width:200px; height:50px; border:1px #dfdfdf solid; font-size:12px;" notnull></textarea>
             </td>
            </tr>
            <tr>
             <td nowrap>&nbsp;</td>
             <td>
               <input type="submit" name="submit" value="提交" style="float:left; display:block; height:32px; line-height:26px; width:86px; text-align:center; font-size:12px; color:#FFF; background:#1584D5; border-radius:5px; margin-right:8px;" /><a href="javascript:void(0);" class="btn_gray" id="goback_recieve">返回</a>
             </td>
            </tr>
            <input type="hidden" name="action" value="recieve" />
            <input type="hidden" name="BackID" value="<?php echo $BackID;?>" />
            </form>
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