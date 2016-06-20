<?php 
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

if($_POST){
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
		"Users_ID"=>$_SESSION["Users_ID"],
		"Model_ID"=>$_POST["ModelID"],
		"Template_LinkID"=>$_POST["LinkID"],
		"Template_Json"=>empty($JSON) ? "" : json_encode($JSON,JSON_UNESCAPED_UNICODE),
		"Template_CreateTime"=>time()
	);
	$Flag = $DB->Add("message_template",$Data);
	if($Flag){
		$Data = array("status"=>1,"msg"=>"添加成功");
	}else{
		$Data = array("status"=>0,"msg"=>"添加失败");
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}

$json = array();

$json["user_create"] = array(
	"Mobile"=>"手机",
	"Name"=>"姓名",
	"NickName"=>"昵称",
	"No"=>"会员卡号",
	"Integral"=>"会员积分",
	"CreateTime"=>"注册时间"						 
);

$json["shop_order"] = $json["weicbd_order"] = $json["tuan_order"] = $json["deliver_order"] = array(
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

$json["user_integral"] = array(
	"Mobile"=>"手机",
	"Name"=>"姓名",
	"NickName"=>"昵称",
	"Integral"=>"会员积分",
	"LastIntegral"=>"获得或消耗积分",
	"CreateTime"=>"变更时间"							   
);

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
      <form action="?" method="post" class="r_con_form" id="message_form">
        <div class="rows">
          <label>模板ID</label>
          <span class="input">
		  <input type="text" class="form_input" size="60" name="LinkID" value="" maxlength="100" notnull />
          <font style="color:red">*</font>
          </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>消息类型 </label>
          <span class="input">
          <select name="ModelID">
            <?php
              $DB->get("message_model","*","where Model_ID not in(3,4) order by Model_ID desc");
			  while($r=$DB->fetch_assoc()){
					echo '<option value="'.$r["Model_ID"].'" rel="'.$r["Model_Type"].'">'.$r["Model_Name"].'</option>';
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
           <div class="data">
			<div><input type="text" name="Field[]" value="" class="form_input" size="15" maxlength="20" /></div>
			<div><select name="Data[]"></select></div>
			<div class="custom"><input type="text" name="Custom[]" value="" class="form_input" size="16" maxlength="100" /><div></div></div>
			<div class="clear"></div>
		   </div>
          </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          <a href="message.php" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>