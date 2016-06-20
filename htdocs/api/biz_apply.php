<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
ini_set ( "display_errors", "On" );
if(isset($_GET["UsersID"])){
	$UsersID = $_GET["UsersID"];
}else{
	echo "缺少必要的参数";
	exit;
}

if($_POST){
	$Data = array(
		"Users_ID"=>$UsersID,
		"Category_ID"=>$_POST["CategoryID"],
		"Biz_Name"=>$_POST["Name"],
		"CreateTime"=>time(),
		"Contact"=>$_POST["Contact"],
		"Mobile"=>$_POST["Mobile"]
	);
	$flag = $DB->Add("biz_apply",$Data);
	if($flag){
		echo '<script language="javascript">alert("申请成功");window.location.href="/api/'.$UsersID.'/biz_apply/";</script>';
		exit;
	}else{
		echo '<script language="javascript">alert("申请失败");history.back();</script>';
		exit;
	}
}else{
	$item = $DB->GetRs("biz_config","*","where Users_ID='".$UsersID."'");
	if($item){
		$item["BaoZhengJin"] = str_replace('&quot;','"',$item["BaoZhengJin"]);
		$item["BaoZhengJin"] = str_replace("&quot;","'",$item["BaoZhengJin"]);
		$item["BaoZhengJin"] = str_replace('&gt;','>',$item["BaoZhengJin"]);
		$item["BaoZhengJin"] = str_replace('&lt;','<',$item["BaoZhengJin"]);
		
		$item["NianFei"] = str_replace('&quot;','"',$item["NianFei"]);
		$item["NianFei"] = str_replace("&quot;","'",$item["NianFei"]);
		$item["NianFei"] = str_replace('&gt;','>',$item["NianFei"]);
		$item["NianFei"] = str_replace('&lt;','<',$item["NianFei"]);
		
		$item["JieSuan"] = str_replace('&quot;','"',$item["JieSuan"]);
		$item["JieSuan"] = str_replace("&quot;","'",$item["JieSuan"]);
		$item["JieSuan"] = str_replace('&gt;','>',$item["JieSuan"]);
		$item["JieSuan"] = str_replace('&lt;','<',$item["JieSuan"]);
	}else{
		$item["BaoZhengJin"] = "";
		$item["NianFei"] = "";
		$item["JieSuan"] = "";	
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="">
<meta name="keywords" content="">
<title>商家申请入驻</title>
<link href='/static/api/apply/style.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script language="javascript">
$(document).ready(function() {
	$(".content2 h2 p span").click(function(){
		var rel = $(this).attr("rel");
		$(".content2 h2 p span").removeClass("cur");
		$(this).addClass("cur");
		$(".items div").hide();
		$("#items_"+rel).show();
	});
	
	$('#apply_form').submit(function(){
		if(global_obj.check_form($('*[notnull]'))){return false};
		$('#apply_form input:submit').attr('disabled', true);
		return true;
	});
});
</script>
</head>
<body>
<div class="common bg1">
 <div class="content">
  <img src="/static/api/apply/1_01.jpg" />
  <img src="/static/api/apply/1_02.jpg" />
  <img src="/static/api/apply/1_03.jpg" />
  <img src="/static/api/apply/1_04.jpg" />
  <img src="/static/api/apply/1_05.jpg" />
  <img src="/static/api/apply/1_06.jpg" />
  <img src="/static/api/apply/1_07.jpg" />
  <img src="/static/api/apply/1_08.jpg" />
  <img src="/static/api/apply/1_09.jpg" />
  <div class="clear"></div>
 </div>
</div>
<div class="common bg2">
 <div class="content">
  <img src="/static/api/apply/2_01.jpg" />
  <img src="/static/api/apply/2_02.jpg" />
  <img src="/static/api/apply/2_03.jpg" />
  <div class="clear"></div>
 </div>
</div>
<div class="common">
  <div class="content2">
     <h2><p><span rel="1" class="s_left cur">保证金</span><span rel="2">平台使用年费</span><span rel="3" class="s_right">产品供货价结算</span><div class="clear"></div></p></h2>
	 <div class="items">
		<div id="items_1" style="display:block">
			<?php echo $item["BaoZhengJin"]?>
		</div>
		<div id="items_2" style="display:none">
			<?php echo $item["NianFei"]?>
		</div>
		<div id="items_3" style="display:none">
			<?php echo $item["JieSuan"]?>
		</div>
	 </div>
  </div>
</div>
<div class="common bg3">
 <div class="content">
  <img src="/static/api/apply/3_01.jpg" />
  <img src="/static/api/apply/3_02.jpg" />
  <img src="/static/api/apply/3_03.jpg" />
  <div class="clear"></div>
 </div>
</div>

<div class="common bg4">
 <div class="content3">
  <form id="apply_form" action="/api/<?php echo $UsersID;?>/biz_apply/" method="post">
   <div class="rows">
     <label>企业名称：</label>
	 <input type="text" name="Name" class="input_text" notnull />
	 <div class="clear"></div>
   </div>
   <div class="rows">
     <label>行业类别：</label>
	 <select name="CategoryID">
	    <?php
		$lists = array();
		$DB->Get("shop_category","*","where Users_ID='".$UsersID."' and Category_ParentID=0 order by Category_Index asc");
		while($r=$DB->fetch_assoc()){
			$lists[] = $r;
		}
		foreach($lists as $l){
			echo '<option value="'.$l["Category_ID"].'">'.$l["Category_Name"].'</option>';
			$DB->Get("shop_category","*","where Users_ID='".$UsersID."' and Category_ParentID=".$l["Category_ID"]." order by Category_Index asc");
			while($value = $DB->fetch_assoc()){
				echo '<option value="'.$value["Category_ID"].'">&nbsp;&nbsp;└&nbsp;'.$value["Category_Name"].'</option>';
			}
		}
		?>
	 </select>
	 <div class="clear"></div>
   </div>
   <div class="rows">
     <label>联系人：</label>
	 <input type="text" name="Contact" class="input_text" notnull />
	 <div class="clear"></div>
   </div>
   <div class="rows">
     <label>联系电话：</label>
	 <input type="text" name="Mobile" class="input_text" notnull />
	 <div class="clear"></div>
   </div>
   <div class="rows">
	 <input type="submit" class="input_submit" value="立即入驻" />
   </div>
  </form>
 </div>
</div>
</body>
</html>