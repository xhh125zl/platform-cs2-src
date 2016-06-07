<?php
require_once('../global.php');

$itemid=empty($_REQUEST['itemid'])?'':$_REQUEST['itemid'];

$item = $DB->GetRs("shop_shipping_print_template","*","where usersid='".$rsBiz["Users_ID"]."' and bizid=".$_SESSION["BIZ_ID"]." and itemid=".$itemid);
if(!$item){
	echo '<script language="javascript">alert("该运单模板不存在");history.back();</script>';
	exit;
}
	
$thumb_width = $item["width"]*3.8;
$thumb_height = $item["height"]*3.8;
$thumb_top = $item["offset_top"]*3.8;
$thumb_left = $item["offset_left"]*3.8;
	
$data_json = $item["data_json"] ? json_decode($item["data_json"],true) : array();
$fields = array(
	"Address_Name"=>"收货人",
	"Address_Area"=>"收货人地区",
	"Address_Detailed"=>"收货人地址",
	"Address_Mobile"=>"收货人手机",
	"Biz_Contact"=>"发货人",
	"Biz_RecieveArea"=>"发货人地区",
	"Biz_Address"=>"发货人地址",
	"Biz_Phone"=>"发货人电话",
	"Biz_Name"=>"发货人公司"
);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>运单模板预览</title>
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<style>
body { margin: 0; }
.waybill_area { position: relative; width: <?php echo $thumb_width;?>px; height: <?php echo $thumb_height;?>px; }
.waybill_back { position: relative; width: <?php echo $thumb_width;?>px; height: <?php echo $thumb_height;?>px; }
.waybill_back img { width: <?php echo $thumb_width;?>px; height: <?php echo $thumb_height;?>px; }
.waybill_design { position: absolute; left: 0; top: 0; width: <?php echo $thumb_width;?>px; height: <?php echo $thumb_height;?>px; }
.waybill_item { position: absolute; left: 0; top: 0; width:100px; height: 20px; border: 1px solid #CCCCCC; }

.print-btn {width:120px; background:#3AA0EB; height:40px; line-height:38px; font-size:14px; color:#FFF; border-radius:5px; text-align:center; margin:20px 0px 0px 100px; cursor:pointer}
</style>
</head>
<body>
<div class="waybill_back"> <img src="<?php echo $item["thumb"];?>" alt=""> </div>
<div class="waybill_design">
	<?php
	foreach($fields as $k=>$value){
		if(!empty($data_json[$k]["check"])){
	?>
    <div class="waybill_item" style="width:<?php echo $data_json[$k]["width"];?>px;height:<?php echo $data_json[$k]["height"];?>px;left:<?php echo $data_json[$k]["left"];?>px;top:<?php echo $data_json[$k]["top"];?>px;"><?php echo $value;?></div>
    <?php }}?>
</div>
<div class="print-btn">打印运单</div>
<script type="text/javascript">
$(document).ready(function() {
	$('.print-btn').on('click', function() {
		$(this).hide();
		pos();	
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
});
</script>
</body>
</html>