<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

$base_url = base_url();
$shop_url = shop_url();

if(isset($_GET["UsersID"])){
  $UsersID = $_GET["UsersID"];
}else{
  echo '缺少必要的参数';
  exit;
}

$rsConfig =$DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
$is_login = 1;
$owner = get_owner($rsConfig,$UsersID);
require_once $_SERVER["DOCUMENT_ROOT"] . '/include/library/wechatuser.php';
$owner = get_owner($rsConfig,$UsersID);

$rsUser = $DB->GetRs("user","*","where User_ID=".$_SESSION[$UsersID."User_ID"]);

if($rsUser['Is_Distribute'] == 1){
	header("location:/api/" . $UsersID . "/shop/distribute/");
	exit;
}
/*edit by xpc*/
$error_msg = pre_add_distribute_account($rsConfig,$UsersID);

$html_mes = '';
if($error_msg != '4'){
	switch($error_msg){
		case '1':
			$html_mes = '在本店累计积满<span>'.$rsConfig["Distribute_Limit"].'</span>积分，才可成为分销商，您已积满<span>'.$rsUser["User_TotalIntegral"].'</span>积分，马上赚积分<a href="'.$shop_url.'">继续购物</a>'; 
		break;
		case '2':
			$arr_temp = explode("|",$rsConfig["Distribute_Limit"]);
			 $arr_temp[1] = !empty($arr_temp[1]) ? intval($arr_temp[1]) : 0;
			 if($arr_temp[0]==0){
				 $html_mes = '在本店累计消费满<span>'.$arr_temp[1].'</span>元，即可成为分销商，您已累计消费<span>'.$rsUser["User_Cost"].'</span>元';
			 }else{
				 $html_mes = '在本店一次性消费满<span>'.$arr_temp[1].'</span>元，即可成为分销商';
			 }
			 $html_mes .= '<a href="'.$shop_url.'">继续购物</a>';
		break;
		case '3':
			$arr_temp = explode("|",$rsConfig["Distribute_Limit"]);
			 $arr_temp[1] = !empty($arr_temp[1]) ? $arr_temp[1] : 0;
			 if($arr_temp[0]==0){
				 $html_mes = '在本店购买<span>任意商品</span>即可成为分销商<a href="'.$shop_url.'">马上购买</a>';
			 }else{
				 $html_mes = '在本店购买以下任一商品即可成为分销商：<br />';
				 $arr_temp[1] = $arr_temp[1] ? $arr_temp[1] : 0;
				 $DB->Get("shop_products","Products_Name,Products_ID","where Products_ID in(".$arr_temp[1].")");
				 while($r = $DB->fetch_assoc()){
					 $html_mes .= '<a href="'.$shop_url.'products_virtual/'.$r["Products_ID"].'/" class="pro">'.$r["Products_Name"].'</a>';
				 }
			 }
		break;
		case 'OK':
			header("location:/api/" . $UsersID . "/shop/distribute/");
			exit;
		break;
		default:
			echo '发生未知错误，请稍后再试';
			exit;
		break;
	}
}
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>申请成为分销商</title>
 <link href="/static/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="/static/css/font-awesome.css">
    <link href="/static/api/distribute/css/style.css" rel="stylesheet">
     <link href="/static/api/distribute/css/apply_distribute.css" rel="stylesheet">
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/static/js/jquery-1.11.1.min.js"></script>
	<script type='text/javascript' src='/static/js/jquery.validate.js'></script>
    <script type='text/javascript' src='/static/api/js/global.js'></script>
    <script src="/static/api/distribute/js/distribute.js"></script>
  
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script language="javascript">
	jQuery.extend(jQuery.validator.messages, {  
       	 	required: "必须填写",  
			email: "请输入正确格式的电子邮件",  
			url: "请输入合法的网址",  
			date: "请输入合法的日期",  
			dateISO: "请输入合法的日期 (ISO).",  
			number: "请输入合法的数字",  
			digits: "只能输入整数",  
			creditcard: "请输入合法的信用卡号",  
			equalTo: "请再次输入相同的值",  
			accept: "请输入拥有合法后缀名的字符串",  
			maxlength: jQuery.validator.format("请输入一个长度最多是 {0} 的字符串"),  
			minlength: jQuery.validator.format("请输入一个长度最少是 {0} 的字符串"),  
			rangelength: jQuery.validator.format("请输入一个长度介于 {0} 和 {1} 之间的字符串"),  
			range: jQuery.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),  
			max: jQuery.validator.format("请输入一个最大为 {0} 的值"),  
			min: jQuery.validator.format("请输入一个最小为 {0} 的值")  
	});  

	var base_url = '<?=$base_url?>';
	var UsersID = '<?=$UsersID?>';
	$(document).ready(distribute_obj.apply_distribute);

</script>

</head>

<body>
<div class="wrap">
	<div class="container">
     <div class="row">
      <div class="apply-image">
      	<img  width="100%" src="<?php echo $rsConfig["ApplyBanner"] ? $rsConfig["ApplyBanner"] : '/static/api/distribute/images/apply_distribute.png';?>" />
      </div>
     </div>
	 <div class="html_mes_red">您还不是分销商</div>	 
     <?php if($error_msg !='4'){?>
	 <div class="html_mes"><?php echo $html_mes;?></div>
     <?php }else{?>
     <div class="row apply_title">
         <div style="padding:8px 8px 10px 8px; font-size:14px; color:#323232; line-height:24px;">填写申请信息<br />推荐人：<?php echo $owner["shop_name"] ? '<font style="color:#F60">'.$owner["shop_name"].'</font>' : '<font style="color:#F60">总店</font>';?></div>
         <div>
            <ul class="list-group" id="apply_form_panel">
			 <form action="/api/<?=$UsersID?>/shop/distribute/ajax/" id="join-distribute-form"/>
			 
			  <input type="hidden" name="action"  value="join"/>
			  <li class="list-group-item">
				 <label>姓名</label>&nbsp;&nbsp;<input type="text" name="Real_Name"  value="" placeholder="请输入你您的姓名" />
			  </li>
			  
			  <li class="list-group-item">
				 <label>手机</label>&nbsp;&nbsp;<input type="text" name="User_Mobile" value="<?=$rsUser['User_Mobile']?>" placeholder="请输入您的手机号码" />
			  </li>

			  <li class="list-group-item  text-center" style="margin:0px; padding:10px 0px">
				 <a href="javascript:void(0)" id="submit-btn" class="btn btn-default">提交申请</a>
			  </li>

			 </form>
		   </ul>
         </div>
     </div>
     <?php }?>
    </div>
</div>

 
<?php require_once('../skin/distribute_footer.php');?> 
 
</body>
</html>

