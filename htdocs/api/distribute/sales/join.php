<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/compser_library/Salesman.php');

$base_url = base_url();
$shop_url = shop_url();

/*分享页面初始化配置*/
$share_flag = 1;
$signature = '';

if(isset($_GET["UsersID"])){
  $UsersID = $_GET["UsersID"];
}else{
  echo '缺少必要的参数';
  exit;
}

if(empty($_SESSION[$UsersID."User_ID"])){
  	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/distribute/";
  	header("location:/api/".$UsersID."/user/login/");
}


$salesman = new Salesman($UsersID, $_SESSION[$UsersID."User_ID"]);
$limit = $salesman->up_salesman(1);
$is_salesman = $salesman->get_salesman();
if($is_salesman){
	header("location:".$base_url."api/".$UsersID."/distribute/sales/");
	exit;
}
$rsConfig = $DB->GetRs("distribute_config","Salesman,Salesman_ImgPath","where Users_ID='".$UsersID."'");
$money = $DB->GetRs("user_order","SUM(Order_TotalPrice) as money","where Order_Status=2 and User_ID=".$_SESSION[$UsersID."User_ID"]);
?>


<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>加入创始人</title>
 <link href="/static/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="/static/css/font-awesome.css">
    <link href="/static/api/distribute/css/style.css" rel="stylesheet">
     <link href="/static/api/distribute/sales/style.css" rel="stylesheet">
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/static/js/jquery-1.11.1.min.js"></script>
    <script src="/static/api/js/ZeroClipboard.min.js"></script>
    <script type='text/javascript' src='/static/api/js/global.js'></script>
    <script src="/static/api/distribute/js/distribute.js"></script>
     <script language="javascript">
	 
	var base_url = '<?=$base_url?>';
	var UsersID = '<?=$UsersID?>';
	$(document).ready(distribute_obj.pro_file_init);
</script>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>

<body style="background:#f5f5f5">
<div class="join_header">
	<img src="<?php echo !empty($rsConfig["Salesman_ImgPath"])?$rsConfig["Salesman_ImgPath"]:'/static/api/distribute/images/sales_join_header.jpg';?>" />
</div>
<div class="join_content">
您目前的消费额为<span><?php echo empty($money["money"]) ? 0 : $money["money"];?></span>元，消费<span>满<?php echo $rsConfig["Salesman"];?></span>元才能够成为创始人
<a href="<?php echo $shop_url;?>">立即购买</a>
</div>
<div class="join_intro">
<h2>创始人特权</h2>
<p class="weidian"><strong>推荐商家</strong>商家产生的营业额、创始人可以分红</p>
<div class="join_dline"></div>
<p class="yongjin"><strong>推荐创始人</strong>可以拿三级商家营业额的利润</p>
</div>
</body>
</html>

