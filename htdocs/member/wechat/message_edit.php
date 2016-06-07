<?php 
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

if($_POST){
	$MessageID = $_POST["MessageID"];
	$JSON = array();
	if(!empty($_POST["Field"])){
		foreach($_POST["Field"] as $k=>$v){
			if($v){
				if($_POST["Data"][$k]){
					$JSON[$v] = '0|'.$_POST["Data"][$k];
				}elseif($_POST["Custom"][$k]){
					$JSON[$v] = '1|'.$_POST["Custom"][$k];
				}
			}
		}
	}
	$Data = array(
		"Template_LinkID"=>$_POST["LinkID"],
		"Template_Json"=>empty($JSON) ? "" : json_encode($JSON,JSON_UNESCAPED_UNICODE)
	);
	$Flag = $DB->Set("message_template",$Data,"where Template_ID=".$MessageID);
	if($Flag){
		$Data = array("status"=>1,"msg"=>"修改成功");
	}else{
		$Data = array("status"=>0,"msg"=>"修改失败");
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}
$MessageID=empty($_REQUEST['MessageID'])?0:$_REQUEST['MessageID'];
$rsMessage=$DB->GetRs("message_template","*","where Template_ID=".$MessageID);
if(!$rsMessage){
	echo "此信息不存在";
	exit;
}
$json_c = $rsMessage["Template_Json"] ? json_decode($rsMessage["Template_Json"],true) : array();
$rsModel = $DB->GetRs("message_model","Model_Type","where Model_ID=".$rsMessage["Model_ID"]);
$json = array();
if($rsModel["Model_Type"]=="user_create"){
	$json["user_create"] = array(
		"Mobile"=>"手机",
		"Name"=>"姓名",
		"NickName"=>"昵称",
		"No"=>"会员卡号",
		"Integral"=>"会员积分",
		"CreateTime"=>"注册时间"						 
	);
}elseif(in_array($rsModel["Model_Type"],array("deliver_order","shop_order","weicbd_order","tuan_order"))){
	$json[$rsModel["Model_Type"]] = array(
		"ID"=>"订单号",
		"Name"=>"姓名",
		"Mobile"=>"手机",
		"Detailed"=>"收货地址",
		"Shipping"=>"送货方式",
		"Qty"=>"商品数量",
		"TotalPrice"=>"订单总价",
		"CartList"=>"商品详情",
		"Status"=>"订单状态",
		"PaymentMethod"=>"付款方式",
		"Remark"=>"备注",
		"CreateTime"=>"订单时间"
	);
}elseif($rsModel["Model_Type"]=="user_integral"){
	$json["user_integral"] = array(
		"Mobile"=>"手机",
		"Name"=>"姓名",
		"NickName"=>"昵称",
		"Integral"=>"会员积分",
		"LastIntegral"=>"获得和消耗积分",
		"CreateTime"=>"变更时间",					   
		"Description"=>"变更描述"						   
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
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/wechat.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/wechat.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="/member/wechat/message.php">模板消息管理</a></li>        
      </ul>
    </div>
    <div id="message" class="r_con_wrap"> 
      <script language="javascript">var msg_field=<?php echo json_encode($json) ?>;$(document).ready(wechat_obj.message_init);</script>
      <form action="message_edit.php" method="post" class="r_con_form" id="message_form">
        <div class="rows">
          <label>模板ID</label>
          <span class="input">
		  <input type="text" class="form_input" size="60" name="LinkID" value="<?php echo $rsMessage["Template_LinkID"];?>" maxlength="100" notnull />
          <font style="color:red">*</font>
          </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>消息类型 </label>
          <span class="input">
          <select name="ModelID" disabled="disabled">
            <?php
              $DB->get("message_model","*","order by Model_ID desc");
			  while($r=$DB->fetch_assoc()){
					echo '<option value="'.$r["Model_ID"].'" rel="'.$r["Model_Type"].'"'.($rsMessage["Model_ID"]==$r["Model_ID"] ? ' selected' : '').'>'.$r["Model_Name"].'</option>';
			  }
			?>           
          </select>
          <span style="margin-top:2px"><a href="javascript:void(0);" class="input_add"><img src="/static/member/images/ico/add.gif" /></a></span>
          </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>消息内容 </label>
          <span class="input">
           <?php if(!empty($json_c)){
			   foreach($json_c as $k=>$v){
		   ?>
           <div class="data">
			<div><input type="text" name="Field[]" value="<?php echo $k;?>" class="form_input" size="15" maxlength="20" /></div>
            <?php
            	$arr = explode("|",$v);
			?>
			<div>
                <select name="Data[]" rel="<?php echo $arr[0]=="0" ? $arr[1] : ''?>"></select>
            </div>
			<div class="custom"><input type="text" name="Custom[]" value="<?php echo $arr[0]=="1" ? $arr[1] : "";?>" class="form_input" size="16" maxlength="100" /><div></div></div>
			<div class="clear"></div>
		   </div>
          <?php }}else{?>
		  <div class="data">
			<div><input type="text" name="Field[]" value="" class="form_input" size="15" maxlength="20" /></div>
			<div><select name="Data[]"></select></div>
			<div class="custom"><input type="text" name="Custom[]" value="" class="form_input" size="16" maxlength="100" /><div></div></div>
			<div class="clear"></div>
		   </div>
		  <?php }?>
          </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          <input type="hidden" name="MessageID" value="<?php echo $MessageID;?>" />
          <a href="message.php" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>