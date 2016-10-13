<?php
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
if($_POST){
	$Data=array(
		"Group_Name"=>$_POST['Name'],
		"Group_Index"=>empty($_POST['Index']) ? 0 : intval($_POST['Index']),
		"Group_IsStore"=>empty($_POST['IsStore']) ? 0 : intval($_POST['IsStore']),
		"Users_ID"=>$_SESSION["Users_ID"],
		
	);
	$Flag=$DB->Add("biz_group",$Data);
	if($Flag){
		echo '<script language="javascript">alert("添加成功");window.location="group.php";</script>';
	}else{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
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
    <div class="r_nav">
      <ul>
        <li><a href="index.php">商家列表</a></li>
        <li class="cur"><a href="group.php">商家分组</a></li>
		<li><a href="apply.php">入驻申请列表</a></li>
		<li><a href="apply_config.php">入驻设置</a></li>
      </ul>
    </div>
    <div id="bizs" class="r_con_wrap">
      <!--<script type='text/javascript' src='/static/member/js/biz.js'></script>
      <script language="javascript">$(document).ready(biz_obj.group_edit);</script>-->
      <form class="r_con_form" method="post" action="?" id="group_edit">
        <div class="rows">
          <label>分组名称</label>
          <span class="input">
          <input type="text" name="Name" value="" class="form_input" size="35" maxlength="50" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>分组排序</label>
          <span class="input">
          <input type="text" name="Index" value="0" class="form_input" size="10" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>是否开通店铺</label>
          <span class="input">
              <input type="radio" name="IsStore" value="1" id="IsStore_1" /><label for="IsStore_1">开通</label>&nbsp;&nbsp;
              <input type="radio" name="IsStore" value="0" id="IsStore_0" checked /><label for="IsStore_0">不开通</label>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="button" class="btn_green" name="submit_button" value="提交保存" /></span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
<script type="text/javascript">
		$(".btn_green").click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false;};		
	
			var re = /^(\+|-)?\d+$/;			
			var Index = $("input[name='Index']").val();			
			if(!re.test(Index) || Index < 0){
				alert('排序必须是整数！');
				return false;
			}			
			$('#group_edit').submit();
			
		});
		
</script>