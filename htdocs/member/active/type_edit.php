<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/update/common.php');

if (IS_POST) {
    $typeid = $_POST["typeid"];
    $Data = array(
        "Type_Name" => $_POST["Type_Name"],
        "module" => $_POST["module"],
        "Status" => $_POST["Status"]
    );
    if (! $_POST["Type_Name"] || ! $_POST["module"]) {
        echo '<script language="javascript">alert("类型名或者活动类型不能为空");history.back();</script>';
        exit();
    }
    $Flag = $DB->Set("active_type", $Data, "WHERE Users_ID='{$UsersID}' AND Type_ID=" . $typeid);
    if ($Flag) {
        echo '<script language="javascript">alert("保存成功");window.location="type.php";</script>';
    } else {
        echo '<script language="javascript">alert("保存失败");history.back();</script>';
    }
    exit();
} else {
    $typeid = $_GET['typeid'];
    $type = $DB->GetRs("active_type", "*", "WHERE Users_ID='{$UsersID}' AND Type_ID={$typeid}");
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
<script type="text/javascript" src="/third_party/uploadify/jquery.uploadify.min.js"></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
	<div class="iframe_content">
		<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
		<script type='text/javascript' src='/static/member/js/shop.js'></script> 
		<script type='text/javascript'>
$(document).ready(function(){
	$("select[name='module']").change(function(){
       var name = $(this).find('option:selected').text();
       $("input[name='Type_Name']").val(name);
  });
});
</script>
		<div id="products" class="r_con_wrap"> 
			<script type='text/javascript' src='/static/js/plugin/dragsort/dragsort-0.5.1.min.js'></script>
			<link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
			<script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
			<script language="javascript">//$(document).ready(shop_obj.products_category_init);</script>
			<div class="category">
				<div class="m_righter" style="margin-left:0px;">
					<form action="type_edit.php" name="category_form" id="category_form" method="post">
            <input type="hidden" name="typeid" value="<?=$typeid ?>"/>
						<div class="opt_item">
							<label>类别名称：</label>
							<span class="input">
							<input type="text" name="Type_Name" value="<?=$type['Type_Name'] ?>" class="form_input" size="15" maxlength="30" notnull />
							<font class="fc_red">*</font></span>
							<div class="clear"></div>
						</div>
						<div class="opt_item">
							<label>活动类型：</label>
							<span class="input">
                  <select name="module">
                      <?php foreach($ActiveType as $key => $val){ ?>
                      <option value="<?=$val['module'] ?>" <?=$type['module']==$val['module']?'selected':'' ?> ><?=$val['name'] ?></option>
                      <?php } ?>
                  </select>
							</span>
							<div class="clear"></div>
						</div>
						<div class="opt_item">
                      <label>活动状态</label>
                      <span class="input">
                      	<?php if($type['Status']==1){ ?>
                          <input type="radio" value="0" name="Status" />关闭 &nbsp;&nbsp;
                          <input type="radio" value="1" name="Status" checked />开启 
                      	<?php }else{ ?>
                      	  <input type="radio" value="0" name="Status" checked/>关闭 &nbsp;&nbsp;
                          <input type="radio" value="1" name="Status"  />开启
                      	<?php }?>
                      </span>
                      <div class="clear"></div>
            </div>    
						<div class="opt_item">
							<label></label>
							<span class="input">
							<input type="submit" class="btn_green btn_w_120" name="submit_button" value="保存分类" />
							<a href="javascript:void(0);" class="btn_gray" onClick="location.href='type.php'">返回</a></span>
							<div class="clear"></div>
						</div>
					</form>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</div>
</body>
</html>