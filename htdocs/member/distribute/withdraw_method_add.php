<?php
ini_set("display_errors","On");

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
$_METHOD = array(
	'bank_card'=>'银行卡',
	'alipay'=>'支付宝',
	'wx_hongbao'=>'微信红包',
	'wx_zhuanzhang'=>'微信转账',
);
if($_POST){
	if($_POST["Type"]  == 'bank_card'){
		$Method_Name = trim($_POST['Name']);
		if(empty($Method_Name)){
			echo '<script language="javascript">alert("请输入银行名称");history.back();</script>';
			exit();
		}
	}else{
		$Method_Name = $_METHOD[$_POST['Type']];
	}
		
		//如果已经存在这种提现方式，不可再添加
	$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Method_Name= '".$Method_Name."'";
	$rsMethod = $DB->getRs('distribute_withdraw_method','*',$condition);
		
	if($rsMethod){
		echo '<script language="javascript">alert("添加失败，提现方式不能重名");history.back();</script>';
		exit();
	}
		
	$data = array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Method_Type"=>$_POST["Type"],
		"Method_Name"=>$Method_Name,
		"Status"=>$_POST['Status'],
		"Method_CreateTime"=>time(),
	);
			  
	$flag = $DB->Add('distribute_withdraw_method',$data);
	if($flag){
		echo '<script language="javascript">alert("添加成功");window.location.href="withdraw_method.php";</script>';
		exit();
	}else{
		echo '<script language="javascript">alert("添加失败");history.back();</script>';
		exit();
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
<script type='text/javascript' src='/static/member/js/distribute/withdraw.js'></script>
<script type="text/javascript">
	$(document).ready(withdraw_obj.method_edit);
</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
      <ul>
        <li><a href="withdraw.php">提现记录</a></li>
        <li class="cur"><a href="withdraw_method.php">提现方法管理</a></li>
      </ul>
    </div>
    <div class="r_con_wrap">
      <form id="method_form" class="r_con_form" method="post" action="?">
		<div class="rows">
          <label>提现方式</label>
          <span class="input">
          	<select name="Type">
            	<?php foreach($_METHOD as $key=>$value){?>
                <option value="<?php echo $key;?>"><?php echo $value;?></option>
                <?php }?>
           	</select>
          </span>
          <div class="clear"></div>
        </div>
        <div id="type_0">
            <div class="rows">
              <label>银行名称</label>
              <span class="input">
              <input type="text" name="Name" value="" class="form_input" size="20" />
              </span>
              <div class="clear"></div>
            </div>
        </div>
        <div class="rows">
          <label>是否启用</label>
          <span class="input">
          	<input type="radio" id="status_1" value="1" name="Status" checked><label for="status_1">启用</label>
            <input type="radio" id="status_0" value="0" name="Status"><label for="status_0">不启用</label>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          </span>
          <div class="clear"></div>
        </div>    
      </form>
    </div>
  </div>
</div>
</body>
</html>