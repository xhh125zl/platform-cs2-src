<?php
require_once('../global.php');

$itemid=empty($_REQUEST['itemid'])?'':$_REQUEST['itemid'];
if($_POST){
	$Data = array(
		"data_json"=>empty($_POST["waybill_data"]) ? '' : json_encode($_POST["waybill_data"],JSON_UNESCAPED_UNICODE)
	);
	$Flag = $DB->Set("shop_shipping_print_template",$Data,"where itemid=".$itemid);
	if($Flag){			
		echo '<script language="javascript">alert("保存成功");window.location.href="printtemplate.php";</script>';
	}else{
		echo '<script language="javascript">alert("操作失败");history.back();</script>';
	}
	exit;
}else{
	$item = $DB->GetRs("shop_shipping_print_template","*","where usersid='".$rsBiz["Users_ID"]."' and bizid=".$_SESSION["BIZ_ID"]." and itemid=".$itemid);
	if(!$item){
		echo '<script language="javascript">alert("该运单模板不存在");history.back();</script>';
		exit;
	}
	
	$thumb_width = $item["width"]*3.8;
	$thumb_height = $item["height"]*3.8;
	
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
<link href="/biz/js/templates.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/biz/js/jquery-ui/jquery.ui.js"></script>
<link type="text/css" rel="stylesheet" href="/biz/js/jquery-ui/themes/ui-lightness/jquery.ui.css"/>
<script type="text/javascript" src="/biz/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
</head>

<body>
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li><a href="config.php">运费设置</a></li>
        <li><a href="company.php">快递公司管理</a></li>
        <li><a href="template.php">快递模板</a></li>
		<li class="cur"><a href="printtemplate.php">运单模板</a></li>
      </ul>
    </div>
    <div id="setemplate" class="r_con_wrap">
    <style type="text/css">
	.waybill_area { margin: 10px auto; width: <?php echo $thumb_width;?>px; height: <?php echo $thumb_height;?>px; position: relative; z-index: 1;}
	.waybill_back { position: relative; width: <?php echo $thumb_width;?>px; height: <?php echo $thumb_height;?>px;}
	.waybill_back img { width: <?php echo $thumb_width;?>px; height: <?php echo $thumb_height;?>px;}
	.waybill_design { position: absolute; left: 0; top: 0; width: <?php echo $thumb_width;?>px; height: <?php echo $thumb_height;?>px;}
	</style>
      <div class="main-content" id="mainContent">
		<div class="tips_info">
			1、勾选需要打印的项目，勾选后可以用鼠标拖动确定项目的位置、宽度和高度<br />
			2、设置完成后点击提交按钮完成设计
		</div>
		<div class="ncsc-form-default">
          <dl>
            <dt>选择打印项：</dt>
            <dd>
              <form id="design_form" action="?" method="post">
                <input type="hidden" name="itemid" value="<?php echo $itemid;?>">
                <ul id="waybill_item_list" class="ncsc-form-checkbox-list">
                  <?php
                  	foreach($fields as $key=>$items){
				  ?>
                  <li>
                    <input id="check_<?php echo $key;?>" class="checkbox" type="checkbox" name="waybill_data[<?php echo $key;?>][check]" data-waybill-name="<?php echo $key;?>" data-waybill-text="<?php echo $items;?>"<?php echo empty($data_json[$key]["check"]) ? '' : ' checked'?>>
                    <label for="check_<?php echo $key;?>" class="label"><?php echo $items;?></label>
                    <input id="left_<?php echo $key;?>" type="hidden" name="waybill_data[<?php echo $key;?>][left]" value="<?php echo empty($data_json[$key]["left"]) ? 0 : $data_json[$key]["left"];?>">
                    <input id="top_<?php echo $key;?>" type="hidden" name="waybill_data[<?php echo $key;?>][top]" value="<?php echo empty($data_json[$key]["top"]) ? 0 : $data_json[$key]["top"];?>">
                    <input id="width_<?php echo $key;?>" type="hidden" name="waybill_data[<?php echo $key;?>][width]" value="<?php echo empty($data_json[$key]["width"]) ? 0 : $data_json[$key]["width"];?>">
                    <input id="height_<?php echo $key;?>" type="hidden" name="waybill_data[<?php echo $key;?>][height]" value="<?php echo empty($data_json[$key]["height"]) ? 0 : $data_json[$key]["height"];?>">
                  </li>
                  <?php }?>
                </ul>
              </form>
            </dd>
          </dl>
          <dl>
            <dt>打印项偏移校正：</dt>
          </dl>
          <div>
              <div class="waybill_area">
                <div class="waybill_back"> <img src="<?php echo $item["thumb"];?>" alt=""> </div>
                <div class="waybill_design">
                	<?php
					foreach($fields as $k=>$value){
						if(!empty($data_json[$k]["check"])){
					?>
                    <div id="div_<?php echo $k;?>" data-item-name="<?php echo $k;?>" class="waybill_item" style="position: absolute;width:<?php echo $data_json[$k]["width"];?>px;height:<?php echo $data_json[$k]["height"];?>px;left:<?php echo $data_json[$k]["left"];?>px;top:<?php echo $data_json[$k]["top"];?>px;"><?php echo $value;?></div>
                    <?php }}?>
                </div>
              </div>
          </div>
  		  <div class="bottom"><label class="submit-border"><input id="submit"  type="submit" class="submit" value="提交"></label></div>
		</div>
      </div>

<script type="text/javascript">
$(document).ready(function() {
    var draggable_event = {
        stop: function(event, ui) {
            var item_name = ui.helper.attr('data-item-name');
            var position = ui.helper.position();
            $('#left_' + item_name).val(position.left);
            $('#top_' + item_name).val(position.top);
        }
    };

    var resizeable_event = {
        stop: function(event, ui) {
            var item_name = ui.helper.attr('data-item-name');
            $('#width_' + item_name).val(ui.size.width);
            $('#height_' + item_name).val(ui.size.height);
        }
    };

    $('.waybill_item').draggable(draggable_event);
    $('.waybill_item').resizable(resizeable_event);

    $('#waybill_item_list input:checkbox').on('click', function() {
        var item_name = $(this).attr('data-waybill-name');
        var div_name = 'div_' + item_name;
        if($(this).prop('checked')) {
            var item_text = $(this).attr('data-waybill-text');
            var waybill_item = '<div id="' + div_name + '" data-item-name="' + item_name + '" class="waybill_item">' + item_text + '</div>';
            $('.waybill_design').append(waybill_item);
            $('#' + div_name).draggable(draggable_event);
            $('#' + div_name).resizable(resizeable_event);
            $('#left_' + item_name).val('0');
            $('#top_' + item_name).val('0');
            $('#width_' + item_name).val('100');
            $('#height_' + item_name).val('20');
        } else {
            $('#' + div_name).remove();
        }
    });

    $('.waybill_design').on('click', '.waybill_item', function() {
        console.log($(this).position());
    });

    $('#submit').on('click', function() {
        $('#design_form').submit();
    });
});
</script> 
    


    </div>  
  </div>
</div>
</body>
</html>