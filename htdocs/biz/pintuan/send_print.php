<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$area_buyer = $area_seller = '';
$area_json = read_file($_SERVER["DOCUMENT_ROOT"].'/data/area.js');
$area_array = json_decode($area_json,TRUE);
$province_list = $area_array[0];

if($_POST){
	$Province = '';
	if(!empty($_POST['Province'])){
		$Province = $province_list[$_POST['Province']];
	}
	$City = '';
	if(!empty($_POST['City'])){
		$City = $area_array['0,'.$_POST['Province']][$_POST['City']];
	}
	
	$Area = '';
	if(!empty($_POST['Area'])){
		$Area = $area_array['0,'.$_POST['Province'].','.$_POST['City']][$_POST['Area']];
	}
	
	$Data = array(
		"status"=>1,
		"Biz_Contact"=>$_POST['Contact'],
		"Biz_RecieveArea"=>$Province.'&nbsp;&nbsp;&nbsp;&nbsp;'.$City.'&nbsp;&nbsp;&nbsp;&nbsp;'.$Area,
		"Biz_Address"=>$_POST['Address'],
		"Biz_Phone"=>$_POST['Phone'],
		"Biz_Name"=>$_POST["Name"]
	);
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}else{
	if(empty($_GET["templateid"])){
		echo '<script language="javascript">alert("请选择运单模板");history.back();</script>';
		exit;
	}
	$templateid = intval($_GET["templateid"]);
	
	$item = $DB->GetRs("shop_shipping_print_template","*","where usersid='{$UsersID}' and enabled=1 and itemid=".$templateid);
	if(!$item){
		echo '未找到您选择的运单模板，不能打印发货单';
		exit;
	}
	
	//卖家地址
	$Province = '';
	
	$City = '';
	
	
	$Area = '';
	
	$area_seller = $Province.'&nbsp;&nbsp;&nbsp;&nbsp;'.$City.'&nbsp;&nbsp;&nbsp;&nbsp;'.$Area;
	
	if(empty($_GET["OrderID"])){
		echo '<script language="javascript">alert("请选择订单");history.back();</script>';
		exit;
	}else{
		if(is_array($_GET["OrderID"])){
			$OrderID = $_GET["OrderID"];
		}else{
			$OrderID[] = $_GET["OrderID"];
		}
		
		$orders = array();
		$DB->Get("user_order","Order_ID,Address_Name,Address_Detailed,Address_Mobile,Address_Province,Address_City,Address_Area","where Users_ID='{$UsersID}' and Order_Status=2 and Order_ID in(".str_replace(',,',',',implode(",",$OrderID)).") order by Order_ID desc");
		while($rsOrder = $DB->fetch_assoc()){
			$Province = '';
			if(!empty($rsOrder['Address_Province'])){
				$Province = $province_list[$rsOrder['Address_Province']];
			}
			$City = '';
			if(!empty($rsOrder['Address_City'])){
				$City = $area_array['0,'.$rsOrder['Address_Province']][$rsOrder['Address_City']];
			}
			
			$Area = '';
			if(!empty($rsOrder['Address_Area'])){
				$Area = $area_array['0,'.$rsOrder['Address_Province'].','.$rsOrder['Address_City']][$rsOrder['Address_Area']];
			}
			$rsOrder["Address_Area"] = $Province.'&nbsp;&nbsp;&nbsp;&nbsp;'.$City.'&nbsp;&nbsp;&nbsp;&nbsp;'.$Area;
			$rsOrder["Biz_Contact"] = '';
			$rsOrder["Biz_RecieveArea"] = $area_seller;
			$rsOrder["Biz_Address"] = '';
			$rsOrder["Biz_Phone"] = '';
			$rsOrder["Biz_Name"] = '';
			$orders[$rsOrder["Order_ID"]] = $rsOrder;
		}
	}
		
	$thumb_width = $item["width"]*3.8;
	$thumb_height = $item["height"]*3.8;
	$thumb_top = $item["offset_top"]*3.8;
	$thumb_left = $item["offset_left"]*3.8;
	$data_json = $item["data_json"] ? json_decode($item["data_json"],true) : array();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>运单模板预览</title>
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src="/static/js/select2.js"></script>
<script type="text/javascript" src="/static/js/location.js"></script>
<script type="text/javascript" src="/static/js/area.js"></script>
<link href="/static/css/select2.css" rel="stylesheet"/>
<style>
body { margin: 0; }
.waybill_area { position: relative; width: <?php echo $thumb_width;?>px; height: <?php echo $thumb_height;?>px; }
.waybill_back { position: relative; width: <?php echo $thumb_width;?>px; height: <?php echo $thumb_height;?>px; }
.waybill_back img { width: <?php echo $thumb_width;?>px; height: <?php echo $thumb_height;?>px; }
.waybill_design { position: absolute; left: 0; top: 0; width: <?php echo $thumb_width;?>px; height: <?php echo $thumb_height;?>px; }
.waybill_item { position: absolute; left: 0; top: 0; width:100px; height: 20px; }
.print-btn {width:120px; background:#3AA0EB; height:40px; line-height:38px; font-size:14px; color:#FFF; border-radius:5px; text-align:center; margin:20px 0px 0px 100px; cursor:pointer}
#send_form table{width:500px; border:1px #dfdfdf solid; margin-top:8px; margin-left:8px; margin-bottom:8px}
#send_form table td{height:40px; line-height:40px; border-top:1px #f5f5f5 solid}
#send_form table td.title{background:#f5f5f5; border-bottom:none; font-size:14px; text-indent:8px}
#send_form table td.left{width:85px; font-size:14px; text-align:right; padding-right:15px; border-right:1px #f5f5f5 solid}
#send_form .input{display:block; height:28px; border:1px #efefef solid; width:300px; line-height:26px; margin-left:10px; padding:0px 5px}
#send_form label{display:block; width:120px; height:32px; line-height:30px; color:#FFF; cursor:pointer; background:#3AA0EB; font-size:12px; border-radius:5px;}
</style>
</head>
<body>
<form action="?" id="send_form" method="post">
<table cellpadding="0" cellspacing="0" width="500">
	<tr>
    	<td colspan="2" class="title">发货人信息设置</td>
    </tr>
    <tr>
    	<td class="left">发货人</td>
        <td><input name="Contact" class="input" value=""></td>
    </tr>
    <tr>
    	<td class="left">发货人电话</td>
        <td><input name="Phone" class="input" value=""></td>
    </tr>
    <tr>
    	<td class="left">发货人公司</td>
        <td><input name="Name" class="input" value=""></td>
    </tr>
    <tr>
    	<td class="left">发货人地区</td>
        <td>&nbsp;&nbsp;<select name="Province"  id="loc_province" style="width:120px">
				<option>选择省份</option>
			</select>&nbsp;
			<select name="City" id="loc_city" style="width:120px">
				<option>选择城市</option>
			</select>
			<select name="Area"  id="loc_town" style="width:120px">
				<option>选择区县</option>
			</select></td>
    </tr>
    <tr>
    	<td class="left">发货人地址</td>
        <td><input name="Address" class="input" value=""></td>
    </tr>
    <tr>
    	<td colspan="2" align="center"><label>确定</label></td>
    </tr>
</table>
</form>
<?php foreach($orders as $order){?>
<div class="waybill_area">
    <div class="waybill_back"> <img src="<?php echo $item["thumb"];?>" alt=""> </div>
    <div class="waybill_design">
        <?php
        foreach($data_json as $k=>$json){
			if(empty($json["check"])){
				continue;
			}
        ?>
        <div class="waybill_item" ret="<?php echo $k;?>" style="width:<?php echo $json["width"];?>px;height:<?php echo $json["height"];?>px;left:<?php echo $json["left"];?>px;top:<?php echo $json["top"];?>px;"><?php echo $order[$k];?></div>
        <?php }?>
    </div>
</div>
<?php }?>
<div class="print-btn">打印运单</div>
<script type="text/javascript">
$(document).ready(function() {
	showLocation(0,0,0);
	$('.print-btn').on('click', function() {
		$(this).hide();
		$("#send_form").hide();
		$(".waybill_back").hide();
		window.print();
	});

    var pos = function () {
		var top = <?php echo $thumb_top;?>;
		var left = <?php echo $thumb_left;?>;
		$(".waybill_design .waybill_item").each(function(index) {
			var offset = $(this).offset();
			var offset_top = offset.top + top;
			var offset_left = offset.left + left;
			$(this).offset({ top: offset_top, left: offset_left})
		});
	};
	pos();
	
	$('#send_form label').click(function(){
		$.post('?', $('#send_form').serialize(), function(data){
			if(data.status==1){
				$('.waybill_design .waybill_item').each(function(index){
					var ret = $(this).attr("ret");
					if(typeof(data[ret])!='undefined'){
						$(this).html(data[ret]);
					}
				});
			};
		}, 'json');
	});
});
</script>
</body>
</html>